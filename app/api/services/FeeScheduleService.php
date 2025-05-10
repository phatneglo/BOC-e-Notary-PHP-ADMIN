<?php
// app/api/services/FeeScheduleService.php
namespace PHPMaker2024\eNotary;

class FeeScheduleService {
    /**
     * Get pending fee proposals
     * @return array Response data
     */
    public function getPendingFeeProposals() {
        try {
            $sql = "SELECT fs.*, dt.template_name, dt.template_type, dt.category_id, tc.category_name,
                    u.first_name, u.last_name, u.username, u.is_notary
                    FROM fee_schedules fs
                    JOIN document_templates dt ON fs.template_id = dt.template_id
                    JOIN users u ON fs.created_by = u.user_id
                    LEFT JOIN template_categories tc ON dt.category_id = tc.category_id
                    WHERE fs.is_active = false AND fs.effective_to IS NULL
                    ORDER BY fs.created_at DESC";
            
            $result = ExecuteRows($sql, "DB");
            
            return [
                "success" => true,
                "data" => $result
            ];
        } catch (\Exception $e) {
            LogError($e->getMessage());
            return [
                "success" => false,
                "message" => "Failed to retrieve fee proposals: " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get fee schedules for a template
     * @param int $templateId Template ID
     * @return array Response data
     */
    public function getFeeSchedulesForTemplate($templateId) {
        try {
            $sql = "SELECT fs.*, u.first_name, u.last_name, u.username, u.is_notary
                    FROM fee_schedules fs
                    LEFT JOIN users u ON fs.created_by = u.user_id
                    WHERE fs.template_id = " . QuotedValue($templateId, DataType::NUMBER) . "
                    ORDER BY fs.created_at DESC";
            
            $result = ExecuteRows($sql, "DB");
            
            // Get the base fee from document_templates as well
            $sqlTemplate = "SELECT template_name, fee_amount FROM document_templates 
                           WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $templateResult = ExecuteRows($sqlTemplate, "DB");
            
            $templateData = null;
            if (!empty($templateResult)) {
                $templateData = $templateResult[0];
            }
            
            return [
                "success" => true,
                "data" => $result,
                "template" => $templateData
            ];
        } catch (\Exception $e) {
            LogError($e->getMessage());
            return [
                "success" => false,
                "message" => "Failed to retrieve fee schedules: " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create a fee proposal
     * @param int $userId User ID creating the proposal
     * @param int $templateId Template ID
     * @param array $feeData Fee data
     * @return array Response data
     */
    public function createFeeProposal($userId, $templateId, $feeData) {
        try {
            // Validate inputs
            if (empty($feeData['fee_amount']) || !is_numeric($feeData['fee_amount'])) {
                return [
                    "success" => false,
                    "message" => "Valid fee amount is required"
                ];
            }
            
            // Verify the template exists
            $sqlTemplate = "SELECT template_id, template_name, owner_id, is_system 
                           FROM document_templates 
                           WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $templateResult = ExecuteRows($sqlTemplate, "DB");
            
            if (empty($templateResult)) {
                return [
                    "success" => false,
                    "message" => "Template not found"
                ];
            }
            
            $template = $templateResult[0];
            
            // Check if user has permission to propose fees for this template
            // Only owner or admin can propose fees
            $authService = new AuthService();
            if ($template['owner_id'] != $userId && !$authService->hasAdminAccess($userId)) {
                return [
                    "success" => false,
                    "message" => "You do not have permission to propose fees for this template"
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Insert fee proposal
                $insertData = [
                    "template_id" => $templateId,
                    "fee_name" => $feeData['fee_name'] ?? "Fee Proposal",
                    "fee_amount" => $feeData['fee_amount'],
                    "fee_type" => $feeData['fee_type'] ?? "fixed",
                    "currency" => $feeData['currency'] ?? "PHP",
                    "effective_from" => CurrentDate(), // Use current date instead of NULL
                    "effective_to" => null,
                    "created_at" => CurrentDateTime(),
                    "created_by" => $userId,
                    "is_active" => false, // Inactive until approved
                    "description" => $feeData['description'] ?? null
                ];
                
                $sql = "INSERT INTO fee_schedules (" . implode(", ", array_keys($insertData)) . ") VALUES (" . 
                        implode(", ", array_map(function($key) use ($insertData) {
                            $value = $insertData[$key];
                            if ($value === null) return "NULL";
                            
                            if (is_bool($value)) {
                                return $value ? "TRUE" : "FALSE";
                            } else if (is_numeric($value)) {
                                return QuotedValue($value, DataType::NUMBER);
                            } else {
                                return QuotedValue($value, DataType::STRING);
                            }
                        }, array_keys($insertData))) . ") RETURNING fee_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['fee_id'])) {
                    throw new \Exception("Failed to create fee proposal");
                }
                
                $feeId = $result[0]['fee_id'];
                
                // Create notification for admin to review fee
                $this->createFeeReviewNotification($templateId, $feeData['fee_amount'], $userId);
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                return [
                    "success" => true,
                    "message" => "Fee proposal submitted successfully",
                    "data" => [
                        "fee_id" => $feeId
                    ]
                ];
            } catch (\Exception $e) {
                // Rollback on error
                Execute("ROLLBACK", "DB");
                throw $e;
            }
        } catch (\Exception $e) {
            LogError($e->getMessage());
            return [
                "success" => false,
                "message" => "Failed to create fee proposal: " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Approve a fee proposal
     * @param int $feeId Fee ID
     * @param array $data Approval data
     * @return array Response data
     */
    public function approveFeeProposal($feeId, $data) {
        try {
            // Begin transaction
            Execute("BEGIN", "DB");
            
            // Get fee proposal
            $sql = "SELECT * FROM fee_schedules WHERE fee_id = " . QuotedValue($feeId, DataType::NUMBER);
            $fee = ExecuteRow($sql, "DB");
            
            if (!$fee) {
                throw new \Exception("Fee proposal not found");
            }
            
            // Get the template to update its base fee
            $sqlTemplate = "SELECT * FROM document_templates WHERE template_id = " . QuotedValue($fee["template_id"], DataType::NUMBER);
            $template = ExecuteRow($sqlTemplate, "DB");
            
            if (!$template) {
                throw new \Exception("Template not found");
            }
            
            // Deactivate all previous active fee schedules for this template
            $sqlDeactivate = "UPDATE fee_schedules SET 
                             is_active = false,
                             effective_to = CURRENT_DATE
                             WHERE template_id = " . QuotedValue($fee["template_id"], DataType::NUMBER) . "
                             AND is_active = true";
            
            Execute($sqlDeactivate, "DB");
            
            // Update fee schedule to make it active
            $updateData = [
                "is_active" => true,
                "effective_from" => CurrentDate(), // Current date
                "updated_at" => CurrentDateTime(),
                "updated_by" => Security::getCurrentUserID()
            ];
            
            // Additional adjustments if provided
            if (isset($data["fee_amount"])) {
                $updateData["fee_amount"] = $data["fee_amount"];
            }
            
            // Update fee schedule
            $sqlUpdate = "UPDATE fee_schedules SET " . implode(", ", array_map(function($key) use ($updateData) {
                $value = $updateData[$key];
                if (is_bool($value)) {
                    return $key . " = " . ($value ? "TRUE" : "FALSE");
                } elseif (is_numeric($value)) {
                    return $key . " = " . QuotedValue($value, DataType::NUMBER);
                } else {
                    return $key . " = " . QuotedValue($value, DataType::STRING);
                }
            }, array_keys($updateData))) . " WHERE fee_id = " . QuotedValue($feeId, DataType::NUMBER);
            
            Execute($sqlUpdate, "DB");
            
            // Update template base fee to match the approved fee
            $feeAmount = $updateData["fee_amount"] ?? $fee["fee_amount"];
            $sqlUpdateTemplate = "UPDATE document_templates SET 
                                fee_amount = " . QuotedValue($feeAmount, DataType::NUMBER) . ",
                                updated_at = CURRENT_TIMESTAMP
                                WHERE template_id = " . QuotedValue($fee["template_id"], DataType::NUMBER);
            
            Execute($sqlUpdateTemplate, "DB");
            
            // Create notification for template creator
            $this->createFeeApprovalNotification($fee["template_id"], $fee["created_by"], $feeAmount);
            
            // Commit transaction
            Execute("COMMIT", "DB");
            
            return [
                "success" => true,
                "message" => "Fee proposal approved successfully"
            ];
        } catch (\Exception $e) {
            // Rollback on error
            Execute("ROLLBACK", "DB");
            LogError($e->getMessage());
            return [
                "success" => false,
                "message" => "Failed to approve fee proposal: " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Reject a fee proposal
     * @param int $feeId Fee ID
     * @param array $data Rejection data
     * @return array Response data
     */
    public function rejectFeeProposal($feeId, $data) {
        try {
            // Get fee proposal
            $sql = "SELECT * FROM fee_schedules WHERE fee_id = " . QuotedValue($feeId, DataType::NUMBER);
            $fee = ExecuteRow($sql, "DB");
            
            if (!$fee) {
                throw new \Exception("Fee proposal not found");
            }
            
            // Get the template to update its base fee
            $sqlTemplate = "SELECT * FROM document_templates WHERE template_id = " . QuotedValue($fee["template_id"], DataType::NUMBER);
            $template = ExecuteRow($sqlTemplate, "DB");
            
            if (!$template) {
                throw new \Exception("Template not found");
            }
            
            // Update fee schedule status (mark as rejected)
            $updateData = [
                "updated_at" => CurrentDateTime(),
                "updated_by" => Security::getCurrentUserID(),
                "description" => ($fee["description"] ? $fee["description"] . "\n" : "") . 
                                "Rejected: " . ($data["reason"] ?? "No reason provided")
            ];
            
            $sqlUpdate = "UPDATE fee_schedules SET " . implode(", ", array_map(function($key) use ($updateData) {
                $value = $updateData[$key];
                if (is_bool($value)) {
                    return $key . " = " . ($value ? "TRUE" : "FALSE");
                } elseif (is_numeric($value)) {
                    return $key . " = " . QuotedValue($value, DataType::NUMBER);
                } else {
                    return $key . " = " . QuotedValue($value, DataType::STRING);
                }
            }, array_keys($updateData))) . " WHERE fee_id = " . QuotedValue($feeId, DataType::NUMBER);
            
            Execute($sqlUpdate, "DB");
            
            // Create notification for template creator
            $this->createFeeRejectionNotification($fee["template_id"], $fee["created_by"], $data["reason"] ?? "");
            
            return [
                "success" => true,
                "message" => "Fee proposal rejected successfully"
            ];
        } catch (\Exception $e) {
            LogError($e->getMessage());
            return [
                "success" => false,
                "message" => "Failed to reject fee proposal: " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create fee review notification
     * @param int $templateId Template ID
     * @param float $feeAmount Proposed fee amount
     * @param int $userId User who proposed the fee
     */
    private function createFeeReviewNotification($templateId, $feeAmount, $userId) {
        try {
            // Get template information for the notification
            $sqlTemplate = "SELECT template_name FROM document_templates WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $template = ExecuteRow($sqlTemplate, "DB");
            
            if (!$template) {
                return;
            }
            
            // Get admin users to notify
            $sqlAdmins = "SELECT user_id FROM users WHERE user_level_id IN (
                          SELECT user_level_id FROM user_levels WHERE name ilike '%Administrator%'
                         )";
            
            $adminUsers = ExecuteRows($sqlAdmins, "DB");
            
            if (empty($adminUsers)) {
                return;
            }
            
            // Get user information
            $sqlUser = "SELECT first_name, last_name, username FROM users WHERE user_id = " . QuotedValue($userId, DataType::NUMBER);
            $user = ExecuteRow($sqlUser, "DB");
            
            $userName = "User";
            if ($user) {
                $userName = trim($user["first_name"] . " " . $user["last_name"]) ?: $user["username"];
            }
            
            // Create notification for each admin
            foreach ($adminUsers as $admin) {
                $notificationData = [
                    "id" => uniqid("fee_review_", true),
                    "timestamp" => CurrentDateTime(),
                    "type" => "fee_proposal",
                    "target" => "template_" . $templateId,
                    "user_id" => $admin["user_id"],
                    "subject" => "Fee Proposal Requires Review",
                    "body" => $userName . " has proposed a fee of PHP " . number_format($feeAmount, 2) . " for template \"" . $template["template_name"] . "\".",
                    "link" => "/admin/fee-approval", 
                    "from_system" => "eNotary",
                    "is_read" => false,
                    "created_at" => CurrentDateTime()
                ];
                
                $sqlNotification = "INSERT INTO notifications (" . implode(", ", array_keys($notificationData)) . ") VALUES (" . 
                                   implode(", ", array_map(function($key) use ($notificationData) {
                                       $value = $notificationData[$key];
                                       if (is_bool($value)) {
                                           return $value ? "TRUE" : "FALSE";
                                       } elseif (is_numeric($value)) {
                                           return QuotedValue($value, DataType::NUMBER);
                                       } else {
                                           return QuotedValue($value, DataType::STRING);
                                       }
                                   }, array_keys($notificationData))) . ")";
                
                Execute($sqlNotification, "DB");
            }
        } catch (\Exception $e) {
            LogError("Error creating fee review notification: " . $e->getMessage());
        }
    }
    
    /**
     * Create fee approval notification
     * @param int $templateId Template ID
     * @param int $userId User to notify
     * @param float $feeAmount Approved fee amount
     */
    private function createFeeApprovalNotification($templateId, $userId, $feeAmount) {
        try {
            // Get template information for the notification
            $sqlTemplate = "SELECT template_name FROM document_templates WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $template = ExecuteRow($sqlTemplate, "DB");
            
            if (!$template) {
                return;
            }
            
            // Create notification for the template owner
            $notificationData = [
                "id" => uniqid("fee_approved_", true),
                "timestamp" => CurrentDateTime(),
                "type" => "fee_approved",
                "target" => "template_" . $templateId,
                "user_id" => $userId,
                "subject" => "Fee Proposal Approved",
                "body" => "Your proposed fee of PHP " . number_format($feeAmount, 2) . " for template \"" . $template["template_name"] . "\" has been approved.",
                "link" => "/user/templates", 
                "from_system" => "eNotary",
                "is_read" => false,
                "created_at" => CurrentDateTime()
            ];
            
            $sqlNotification = "INSERT INTO notifications (" . implode(", ", array_keys($notificationData)) . ") VALUES (" . 
                               implode(", ", array_map(function($key) use ($notificationData) {
                                   $value = $notificationData[$key];
                                   if (is_bool($value)) {
                                       return $value ? "TRUE" : "FALSE";
                                   } elseif (is_numeric($value)) {
                                       return QuotedValue($value, DataType::NUMBER);
                                   } else {
                                       return QuotedValue($value, DataType::STRING);
                                   }
                               }, array_keys($notificationData))) . ")";
            
            Execute($sqlNotification, "DB");
        } catch (\Exception $e) {
            LogError("Error creating fee approval notification: " . $e->getMessage());
        }
    }
    
    /**
     * Create fee rejection notification
     * @param int $templateId Template ID
     * @param int $userId User to notify
     * @param string $reason Rejection reason
     */
    private function createFeeRejectionNotification($templateId, $userId, $reason) {
        try {
            // Get template information for the notification
            $sqlTemplate = "SELECT template_name FROM document_templates WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $template = ExecuteRow($sqlTemplate, "DB");
            
            if (!$template) {
                return;
            }
            
            // Create notification for the template owner
            $notificationData = [
                "id" => uniqid("fee_rejected_", true),
                "timestamp" => CurrentDateTime(),
                "type" => "fee_rejected",
                "target" => "template_" . $templateId,
                "user_id" => $userId,
                "subject" => "Fee Proposal Rejected",
                "body" => "Your fee proposal for template \"" . $template["template_name"] . "\" has been rejected. Reason: " . $reason,
                "link" => "/user/templates", 
                "from_system" => "eNotary",
                "is_read" => false,
                "created_at" => CurrentDateTime()
            ];
            
            $sqlNotification = "INSERT INTO notifications (" . implode(", ", array_keys($notificationData)) . ") VALUES (" . 
                               implode(", ", array_map(function($key) use ($notificationData) {
                                   $value = $notificationData[$key];
                                   if (is_bool($value)) {
                                       return $value ? "TRUE" : "FALSE";
                                   } elseif (is_numeric($value)) {
                                       return QuotedValue($value, DataType::NUMBER);
                                   } else {
                                       return QuotedValue($value, DataType::STRING);
                                   }
                               }, array_keys($notificationData))) . ")";
            
            Execute($sqlNotification, "DB");
        } catch (\Exception $e) {
            LogError("Error creating fee rejection notification: " . $e->getMessage());
        }
    }
}