<?php
// app/api/services/RequestService.php
namespace PHPMaker2024\eNotary;

class RequestService {
    /**
     * Submit document for notarization
     * @param int $documentId Document ID
     * @return array Response data
     */
    public function submitDocument($documentId) {
        try {
            // Check if document exists and belongs to the current user
            $sql = "SELECT 
                    d.document_id,
                    d.user_id,
                    d.status,
                    d.document_title,
                    dt.template_id,
                    dt.notary_required,
                    dt.fee_amount
                FROM 
                    documents d
                LEFT JOIN 
                    document_templates dt ON d.template_id = dt.template_id
                WHERE 
                    d.document_id = " . QuotedValue($documentId, DataType::NUMBER) . "
                    AND d.is_deleted = false";
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            $document = $result[0];
            $currentUserId = Authentication::getUserId();
            
            if ($document['user_id'] != $currentUserId) {
                return [
                    'success' => false,
                    'message' => 'Document does not belong to the current user'
                ];
            }
            
            // Check if document is in draft status
            if ($document['status'] !== 'draft') {
                return [
                    'success' => false,
                    'message' => 'Only documents in draft status can be submitted for notarization'
                ];
            }
            
            // Generate unique request reference
            $requestReference = $this->generateRequestReference();
            
            // Determine fee amount
            $feeAmount = 0;
            if (!empty($document['fee_amount'])) {
                $feeAmount = $document['fee_amount'];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Update document status
                $sql = "UPDATE documents SET
                    status_id = (SELECT status_id FROM document_statuses WHERE status_code = 'submitted'),
                    submitted_at = CURRENT_TIMESTAMP,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
                        
                Execute($sql, "DB");
                
                // Create notarization request
                $sql = "INSERT INTO notarization_requests (
                        document_id,
                        user_id,
                        request_reference,
                        status,
                        priority,
                        payment_status,
                        requested_at,
                        ip_address,
                        browser_info
                    ) VALUES (
                        " . QuotedValue($documentId, DataType::NUMBER) . ",
                        " . QuotedValue($currentUserId, DataType::NUMBER) . ",
                        " . QuotedValue($requestReference, DataType::STRING) . ",
                        'pending',
                        0,
                        " . ($feeAmount > 0 ? "'pending'" : "'paid'") . ",
                        CURRENT_TIMESTAMP,
                        " . QuotedValue($_SERVER['REMOTE_ADDR'] ?? '', DataType::STRING) . ",
                        " . QuotedValue($_SERVER['HTTP_USER_AGENT'] ?? '', DataType::STRING) . "
                    ) RETURNING request_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['request_id'])) {
                    throw new \Exception("Failed to create notarization request");
                }
                
                $requestId = $result[0]['request_id'];
                
                // If payment is not required, add to notarization queue immediately
                if ($feeAmount <= 0) {
                    // Assign to notary (in a real system, this would use some algorithm for load balancing)
                    // For now, we'll just get a random active notary
                    $sql = "SELECT 
                            user_id 
                        FROM 
                            users 
                        WHERE 
                            is_notary = true 
                            AND is_active = true 
                            AND notary_commission_expiry > CURRENT_DATE
                        ORDER BY RANDOM() 
                        LIMIT 1";
                    
                    $notaryResult = ExecuteRows($sql, "DB");
                    
                    if (!empty($notaryResult)) {
                        $notaryId = $notaryResult[0]['user_id'];
                        
                        // Get current queue position
                        $sql = "SELECT COALESCE(MAX(queue_position), 0) + 1 AS next_position 
                                FROM notarization_queue
                                WHERE notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
                        
                        $positionResult = ExecuteRows($sql, "DB");
                        $queuePosition = $positionResult[0]['next_position'] ?? 1;
                        
                        // Add to queue
                        $sql = "INSERT INTO notarization_queue (
                                request_id,
                                notary_id,
                                queue_position,
                                status,
                                estimated_wait_time,
                                entry_time
                            ) VALUES (
                                " . QuotedValue($requestId, DataType::NUMBER) . ",
                                " . QuotedValue($notaryId, DataType::NUMBER) . ",
                                " . QuotedValue($queuePosition, DataType::NUMBER) . ",
                                'queued',
                                " . QuotedValue(15, DataType::NUMBER) . ", -- Default 15 min estimate
                                CURRENT_TIMESTAMP
                            )";
                        
                        Execute($sql, "DB");
                    }
                }
                
                // Add to activity log
                $this->addActivityLog(
                    $documentId,
                    $currentUserId,
                    'submit',
                    'Document submitted for notarization'
                );
                
                // Create user notification
                $this->createNotification(
                    $currentUserId,
                    'document_submitted',
                    'document_' . $documentId,
                    'Document Submitted',
                    'Your document "' . $document['document_title'] . '" has been submitted for notarization.',
                    'requests/' . $requestId
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Document submitted for notarization',
                    'data' => [
                        'request_id' => $requestId,
                        'request_reference' => $requestReference,
                        'payment_required' => $feeAmount > 0,
                        'fee_amount' => $feeAmount
                    ]
                ];
            } catch (\Exception $e) {
                // Rollback transaction on error
                Execute("ROLLBACK", "DB");
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to submit document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get details of a notarization request
     * @param int $requestId Request ID
     * @return array Response data
     */
    public function getRequestDetails($requestId) {
        try {
            // Get request details
            $sql = "SELECT
                    r.request_id,
                    r.request_reference,
                    r.document_id,
                    d.document_reference,
                    d.document_title,
                    r.status,
                    r.requested_at,
                    r.notary_id,
                    CONCAT(u.first_name, ' ', u.last_name) AS notary_name,
                    r.assigned_at,
                    r.notarized_at,
                    r.rejection_reason,
                    r.rejected_at,
                    r.rejected_by,
                    r.payment_status,
                    r.payment_transaction_id,
                    r.priority,
                    q.queue_position,
                    q.estimated_wait_time,
                    r.modified_at,
                    r.ip_address,
                    r.browser_info
                FROM
                    notarization_requests r
                JOIN
                    documents d ON r.document_id = d.document_id
                LEFT JOIN
                    users u ON r.notary_id = u.user_id
                LEFT JOIN
                    notarization_queue q ON r.request_id = q.request_id
                WHERE
                    r.request_id = " . QuotedValue($requestId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Request not found'
                ];
            }
            
            // Return success response
            return [
                'success' => true,
                'data' => $result[0]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get request details: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Modify a rejected document and resubmit
     * @param int $requestId Request ID
     * @param array $documentData Document data
     * @return array Response data
     */
    public function modifyRejectedDocument($requestId, $documentData) {
        try {
            // Validate required fields
            if (empty($documentData['document_title'])) {
                return [
                    'success' => false,
                    'message' => 'Document title is required',
                    'errors' => ['document_title' => ['Document title is required']]
                ];
            }
            
            if (empty($documentData['document_data']) || !is_array($documentData['document_data'])) {
                return [
                    'success' => false,
                    'message' => 'Document data is required and must be an object',
                    'errors' => ['document_data' => ['Document data is required and must be an object']]
                ];
            }
            
            // Check if request exists, is rejected, and belongs to the current user
            $sql = "SELECT
                    r.request_id,
                    r.status,
                    r.document_id,
                    r.user_id,
                    d.document_title,
                    d.template_id,
                    dt.html_content AS template_html
                FROM
                    notarization_requests r
                JOIN
                    documents d ON r.document_id = d.document_id
                LEFT JOIN
                    document_templates dt ON d.template_id = dt.template_id
                WHERE
                    r.request_id = " . QuotedValue($requestId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Request not found'
                ];
            }
            
            $request = $result[0];
            $currentUserId = Authentication::getUserId();
            
            if ($request['user_id'] != $currentUserId) {
                return [
                    'success' => false,
                    'message' => 'Request does not belong to the current user'
                ];
            }
            
            if ($request['status'] !== 'rejected') {
                return [
                    'success' => false,
                    'message' => 'Only rejected documents can be modified and resubmitted'
                ];
            }
            
            // Generate updated document HTML
            $updatedHtml = $this->generateDocumentHtml($request['template_html'], $documentData['document_data']);
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Create a new document version
                $sql = "INSERT INTO documents (
                        user_id,
                        template_id,
                        document_title,
                        document_reference,
                        status,
                        company_name,
                        customs_entry_number,
                        date_of_entry,
                        document_html,
                        document_data,
                        parent_document_id,
                        version,
                        created_at,
                        updated_at
                    ) SELECT
                        user_id,
                        template_id,
                        " . QuotedValue($documentData['document_title'], DataType::STRING) . ",
                        document_reference,
                        'submitted',
                        " . QuotedValue($documentData['company_name'] ?? null, DataType::STRING) . ",
                        " . QuotedValue($documentData['customs_entry_number'] ?? null, DataType::STRING) . ",
                        " . QuotedValue($documentData['date_of_entry'] ?? null, DataType::DATE) . ",
                        " . QuotedValue($updatedHtml, DataType::STRING) . ",
                        " . QuotedValue(json_encode($documentData['document_data']), DataType::STRING) . ",
                        " . QuotedValue($request['document_id'], DataType::NUMBER) . ",
                        version + 1,
                        CURRENT_TIMESTAMP,
                        CURRENT_TIMESTAMP
                    FROM
                        documents
                    WHERE
                        document_id = " . QuotedValue($request['document_id'], DataType::NUMBER) . "
                    RETURNING document_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['document_id'])) {
                    throw new \Exception("Failed to create new document version");
                }
                
                $newDocumentId = $result[0]['document_id'];
                
                // Update request with new document ID and reset status
                $sql = "UPDATE notarization_requests SET
                        document_id = " . QuotedValue($newDocumentId, DataType::NUMBER) . ",
                        status = 'pending',
                        rejection_reason = NULL,
                        rejected_at = NULL,
                        rejected_by = NULL,
                        notary_id = NULL,
                        assigned_at = NULL,
                        modified_at = CURRENT_TIMESTAMP
                        WHERE request_id = " . QuotedValue($requestId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Insert document fields for the new version
                foreach ($documentData['document_data'] as $fieldName => $fieldValue) {
                    $fieldId = null;
                    
                    // Try to get field_id from template_fields if template_id is available
                    if ($request['template_id']) {
                        $sql = "SELECT field_id FROM template_fields 
                                WHERE template_id = " . QuotedValue($request['template_id'], DataType::NUMBER) . "
                                AND field_name = " . QuotedValue($fieldName, DataType::STRING);
                        $fieldResult = ExecuteRows($sql, "DB");
                        
                        if (!empty($fieldResult)) {
                            $fieldId = $fieldResult[0]['field_id'];
                        }
                    }
                    
                    // Insert document field
                    $sql = "INSERT INTO document_fields (
                            document_id,
                            field_id,
                            field_name,
                            field_value,
                            is_verified
                        ) VALUES (
                            " . QuotedValue($newDocumentId, DataType::NUMBER) . ",
                            " . ($fieldId ? QuotedValue($fieldId, DataType::NUMBER) : "NULL") . ",
                            " . QuotedValue($fieldName, DataType::STRING) . ",
                            " . QuotedValue(is_array($fieldValue) ? json_encode($fieldValue) : $fieldValue, DataType::STRING) . ",
                            FALSE
                        )";
                    
                    Execute($sql, "DB");
                }
                
                // Add to activity log
                $this->addActivityLog(
                    $newDocumentId,
                    $currentUserId,
                    'resubmit',
                    'Document modified and resubmitted'
                );
                
                // Create user notification
                $this->createNotification(
                    $currentUserId,
                    'document_resubmitted',
                    'document_' . $newDocumentId,
                    'Document Resubmitted',
                    'Your document "' . $documentData['document_title'] . '" has been resubmitted for notarization.',
                    'requests/' . $requestId
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Document updated and resubmitted',
                    'data' => [
                        'request_id' => $requestId,
                        'status' => 'pending'
                    ]
                ];
            } catch (\Exception $e) {
                // Rollback transaction on error
                Execute("ROLLBACK", "DB");
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to modify and resubmit document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get activity history for a document
     * @param int $documentId Document ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getDocumentActivityHistory($documentId, $params = []) {
        try {
            // Validate document ID
            if (empty($documentId)) {
                return [
                    'success' => false,
                    'message' => 'Document ID is required'
                ];
            }
            
            // Check if document exists
            $sql = "SELECT document_id FROM documents WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Query activity logs
            $sql = "SELECT
                    l.log_id,
                    l.document_id,
                    l.user_id,
                    CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                    l.action,
                    l.details,
                    l.created_at,
                    l.ip_address
                FROM
                    document_activity_logs l
                JOIN
                    users u ON l.user_id = u.user_id
                WHERE
                    l.document_id = " . QuotedValue($documentId, DataType::NUMBER) . "
                ORDER BY
                    l.created_at DESC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $activityLogs = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total FROM document_activity_logs WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
            $resultCount = ExecuteRows($sqlCount, "DB");
            $total = $resultCount[0]['total'] ?? 0;
            
            // Calculate pagination metadata
            $totalPages = ceil($total / $perPage);
            
            // Return success response
            return [
                'success' => true,
                'data' => $activityLogs,
                'meta' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get document activity history: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Add activity log entry for a document
     * @param int $documentId Document ID
     * @param array $activityData Activity data
     * @return array Response data
     */
    public function addDocumentActivity($documentId, $activityData) {
        try {
            // Validate required fields
            if (empty($activityData['action'])) {
                return [
                    'success' => false,
                    'message' => 'Action is required',
                    'errors' => ['action' => ['Action is required']]
                ];
            }
            
            if (empty($activityData['details'])) {
                return [
                    'success' => false,
                    'message' => 'Details are required',
                    'errors' => ['details' => ['Details are required']]
                ];
            }
            
            // Check if document exists
            $sql = "SELECT document_id FROM documents WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            // Add activity log
            $added = $this->addActivityLog(
                $documentId,
                Authentication::getUserId(),
                $activityData['action'],
                $activityData['details']
            );
            
            if (!$added) {
                return [
                    'success' => false,
                    'message' => 'Failed to add activity log'
                ];
            }
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Activity logged successfully'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to add activity log: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate unique request reference
     * @return string Request reference
     */
    private function generateRequestReference() {
        // Generate a unique alphanumeric reference (12 characters)
        $prefix = 'REQ';
        $timestamp = substr(time(), -6);
        $random = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3);
        
        return $prefix . $timestamp . $random;
    }
    
    /**
     * Generate HTML content for a document using template and field data
     * @param string $templateHtml Template HTML content
     * @param array $fieldData Field data
     * @return string Generated HTML content
     */
    private function generateDocumentHtml($templateHtml, $fieldData) {
        // In a real implementation, this would parse the template and replace placeholders with field data
        // For now, we'll just do a simple placeholder replacement
        
        $html = $templateHtml;
        
        // Replace field placeholders
        foreach ($fieldData as $fieldName => $fieldValue) {
            // Handle array values (e.g., for checkboxes or multi-select)
            if (is_array($fieldValue)) {
                $fieldValue = implode(', ', $fieldValue);
            }
            
            // Replace placeholders in format {{field_name}}
            $html = str_replace('{{' . $fieldName . '}}', htmlspecialchars($fieldValue), $html);
        }
        
        return $html;
    }
    
    /**
     * Add activity log for a document
     * @param int $documentId Document ID
     * @param int $userId User ID
     * @param string $action Action name
     * @param string $details Action details
     * @return bool Success status
     */
    private function addActivityLog($documentId, $userId, $action, $details) {
        try {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
            
            $sql = "INSERT INTO document_activity_logs (
                    document_id,
                    user_id,
                    action,
                    details,
                    ip_address,
                    created_at
                ) VALUES (
                    " . QuotedValue($documentId, DataType::NUMBER) . ",
                    " . QuotedValue($userId, DataType::NUMBER) . ",
                    " . QuotedValue($action, DataType::STRING) . ",
                    " . QuotedValue($details, DataType::STRING) . ",
                    " . QuotedValue($ipAddress, DataType::STRING) . ",
                    CURRENT_TIMESTAMP
                )";
            
            Execute($sql, "DB");
            
            return true;
        } catch (\Exception $e) {
            LogError('Failed to add activity log: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create notification for a user
     * @param int $userId User ID
     * @param string $type Notification type
     * @param string $target Target entity reference
     * @param string $subject Notification subject
     * @param string $body Notification body
     * @param string $link Action URL
     * @return bool Success status
     */
    private function createNotification($userId, $type, $target, $subject, $body, $link = '') {
        try {
            $id = uniqid('notif_', true);
            
            $sql = "INSERT INTO notifications (
                    id,
                    timestamp,
                    type,
                    target,
                    user_id,
                    subject,
                    body,
                    link,
                    is_read
                ) VALUES (
                    " . QuotedValue($id, DataType::STRING) . ",
                    CURRENT_TIMESTAMP,
                    " . QuotedValue($type, DataType::STRING) . ",
                    " . QuotedValue($target, DataType::STRING) . ",
                    " . QuotedValue($userId, DataType::NUMBER) . ",
                    " . QuotedValue($subject, DataType::STRING) . ",
                    " . QuotedValue($body, DataType::STRING) . ",
                    " . QuotedValue($link, DataType::STRING) . ",
                    FALSE
                )";
            
            Execute($sql, "DB");
            
            return true;
        } catch (\Exception $e) {
            LogError('Failed to create notification: ' . $e->getMessage());
            return false;
        }
    }
}
