<?php
// app/api/services/UserService.php
namespace PHPMaker2024\eNotary;

class UserService {
    /**
     * Get user profile information
     * @param int $userId User ID
     * @return array Response data
     */
    public function getProfile($userId) {
        try {
            
            $sql = "SELECT
                    u.user_id,
                    u.username,
                    u.email,
                    u.first_name,
                    u.middle_name,
                    u.last_name,
                    u.mobile_number,
                    u.address,
                    u.government_id_type,
                    u.government_id_number,
                    (u.digital_signature IS NOT NULL) AS has_digital_signature,
                    u.is_notary,
                    u.department_id,
                    d.department_name,
                    u.date_created,
                    u.last_login
                FROM
                    users u
                LEFT JOIN
                    departments d ON u.department_id = d.department_id
                WHERE
                    u.user_id = " . QuotedValue($userId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'User not found'
                ];
            }
            
            $user = $result[0];
            
            // Return success response
            return [
                'success' => true,
                'data' => $user
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get user profile: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update user profile information
     * @param int $userId User ID
     * @param array $profileData Profile data
     * @return array Response data
     */
    public function updateProfile($userId, $profileData) {
        try {
            // Validate required fields
            $requiredFields = ['first_name', 'last_name', 'mobile_number', 'address'];
            foreach ($requiredFields as $field) {
                if (empty($profileData[$field])) {
                    return [
                        'success' => false,
                        'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required',
                        'errors' => [$field => ['This field is required']]
                    ];
                }
            }
            
            // Update user profile
            $sql = "UPDATE users SET
                    first_name = " . QuotedValue($profileData['first_name'], DataType::STRING) . ",
                    middle_name = " . QuotedValue($profileData['middle_name'] ?? null, DataType::STRING) . ",
                    last_name = " . QuotedValue($profileData['last_name'], DataType::STRING) . ",
                    mobile_number = " . QuotedValue($profileData['mobile_number'], DataType::STRING) . ",
                    address = " . QuotedValue($profileData['address'], DataType::STRING) . "
                WHERE
                    user_id = " . QuotedValue($userId, DataType::NUMBER);
            
            Execute($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Profile updated successfully'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update user's digital signature
     * @param int $userId User ID
     * @param array $signatureFile Uploaded signature file
     * @return array Response data
     */
    public function updateDigitalSignature($userId, $signatureFile) {
        try {
            if (empty($signatureFile)) {
                return [
                    'success' => false,
                    'message' => 'Signature file is required',
                    'errors' => ['signature' => ['Signature file is required']]
                ];
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            $fileType = $signatureFile->getClientMediaType();
            
            if (!in_array($fileType, $allowedTypes)) {
                return [
                    'success' => false,
                    'message' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed.',
                    'errors' => ['signature' => ['Invalid file type. Only JPG, JPEG, and PNG are allowed.']]
                ];
            }
            
            // Generate unique filename
            $extension = pathinfo($signatureFile->getClientFilename(), PATHINFO_EXTENSION);
            $filename = uniqid('sig_', true) . '.' . $extension;
            $uploadPath = 'uploads/signatures/' . $filename;
            
            // Ensure upload directory exists
            $uploadDir = dirname($uploadPath);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Save file
            $signatureFile->moveTo($uploadPath);
            
            // Update user record
            $sql = "UPDATE users SET
                    digital_signature = " . QuotedValue($uploadPath, DataType::STRING) . "
                WHERE
                    user_id = " . QuotedValue($userId, DataType::NUMBER);
            
            Execute($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Digital signature updated successfully',
                'data' => [
                    'has_digital_signature' => true
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to update digital signature: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Change user password
     * @param int $userId User ID
     * @param string $currentPassword Current password
     * @param string $newPassword New password
     * @param string $confirmPassword Confirm new password
     * @return array Response data
     */
    public function changePassword($userId, $currentPassword, $newPassword, $confirmPassword) {
        try {
            // Validate input
            if (empty($currentPassword)) {
                return [
                    'success' => false,
                    'message' => 'Current password is required',
                    'errors' => ['current_password' => ['Current password is required']]
                ];
            }
            
            if (empty($newPassword)) {
                return [
                    'success' => false,
                    'message' => 'New password is required',
                    'errors' => ['new_password' => ['New password is required']]
                ];
            }
            
            if ($newPassword !== $confirmPassword) {
                return [
                    'success' => false,
                    'message' => 'Passwords do not match',
                    'errors' => ['confirm_password' => ['Passwords do not match']]
                ];
            }
            
            // Validate password strength
            if (strlen($newPassword) < 8) {
                return [
                    'success' => false,
                    'message' => 'Password must be at least 8 characters long',
                    'errors' => ['new_password' => ['Password must be at least 8 characters long']]
                ];
            }
            
            // Get user's current password hash
            $sql = "SELECT password_hash FROM users WHERE user_id = " . QuotedValue($userId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'User not found'
                ];
            }
            
            $currentPasswordHash = $result[0]['password_hash'];
            
            // Verify current password
            if (!password_verify($currentPassword, $currentPasswordHash)) {
                return [
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'errors' => ['current_password' => ['Current password is incorrect']]
                ];
            }
            
            // Update password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET
                    password_hash = " . QuotedValue($newPasswordHash, DataType::STRING) . "
                WHERE
                    user_id = " . QuotedValue($userId, DataType::NUMBER);
            
            Execute($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Password changed successfully'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to change password: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get user activity summary
     * @param int $userId User ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getUserActivity($userId, $params = []) {
            try {
                // Pagination parameters
                $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
                $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
                $offset = ($page - 1) * $perPage;
                
                // Query user activity logs from aggregated_audit_logs table
                $sql = "SELECT
                        a.aggregated_id AS activity_id,
                        a.action,
                        a.details,
                        a.table AS entity_type,
                        a.script AS entity_id,
                        a.action_type AS ip_address,
                        a.action_date AS created_at
                    FROM
                        aggregated_audit_logs a
                    WHERE
                        a.user = " . QuotedValue($userId, DataType::NUMBER) . "
                    ORDER BY
                        a.action_date DESC
                    LIMIT " . $perPage . " OFFSET " . $offset;
                
                $result = ExecuteRows($sql, "DB");
                
                // Get total count
                $sqlCount = "SELECT COUNT(*) AS total FROM aggregated_audit_logs WHERE user = " . QuotedValue($userId, DataType::NUMBER);
                $resultCount = ExecuteRows($sqlCount, "DB");
                $total = $resultCount[0]['total'] ?? 0;
                
                // Calculate pagination metadata
                $totalPages = ceil($total / $perPage);
                
                // Return success response
                return [
                    'success' => true,
                    'data' => $result,
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
                    'message' => 'Failed to get user activity: ' . $e->getMessage()
                ];
            }
        }    
    /**
     * Get user dashboard statistics
     * @param int $userId User ID
     * @return array Response data
     */
    public function getDashboardStats($userId) {
        try {
            // Get document counts by status
            $sqlDocStats = "SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS draft,
                    SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) AS submitted,
                    SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS paid,
                    SUM(CASE WHEN status = 'notarized' THEN 1 ELSE 0 END) AS notarized,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS rejected
                FROM
                    documents
                WHERE
                    user_id = " . QuotedValue($userId, DataType::NUMBER);
            
            $docStats = ExecuteRows($sqlDocStats, "DB");
            
            // Get recent activity
            $sqlActivity = "SELECT
                d.document_id,
                d.document_title,
                a.action,
                a.action_date AS date
            FROM
                aggregated_audit_logs a
            JOIN
                documents d ON a.script LIKE CONCAT('%/documents/', d.document_id, '%') AND a.table = 'documents'
            WHERE
                a.user = " . QuotedValue($userId, DataType::NUMBER) . "
            ORDER BY
                a.action_date DESC
            LIMIT 5";
            
            $recentActivity = ExecuteRows($sqlActivity, "DB");
            
            // Get recently notarized documents
            $sqlNotarized = "SELECT
                    d.document_id,
                    d.document_title,
                    nd.notarization_date AS notarized_at,
                    nd.document_number
                FROM
                    notarized_documents nd
                JOIN
                    documents d ON nd.document_id = d.document_id
                WHERE
                    d.user_id = " . QuotedValue($userId, DataType::NUMBER) . "
                ORDER BY
                    nd.notarization_date DESC
                LIMIT 5";
            
            $recentNotarized = ExecuteRows($sqlNotarized, "DB");
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'documents' => [
                        'total' => (int)($docStats[0]['total'] ?? 0),
                        'draft' => (int)($docStats[0]['draft'] ?? 0),
                        'submitted' => (int)($docStats[0]['submitted'] ?? 0),
                        'paid' => (int)($docStats[0]['paid'] ?? 0),
                        'notarized' => (int)($docStats[0]['notarized'] ?? 0),
                        'rejected' => (int)($docStats[0]['rejected'] ?? 0)
                    ],
                    'recent_activity' => $recentActivity,
                    'recent_notarized' => $recentNotarized
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get dashboard statistics: ' . $e->getMessage()
            ];
        }
    }
}
