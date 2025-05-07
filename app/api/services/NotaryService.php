<?php
// app/api/services/NotaryService.php
namespace PHPMaker2024\eNotary;

class NotaryService {
    /**
     * Get notary profile information
     * @param int $userId User ID
     * @return array Response data
     */
    public function getNotaryProfile($userId) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($userId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Get notary profile
            $sql = "SELECT
                    user_id,
                    notary_commission_number,
                    notary_commission_expiry,
                    (digital_seal IS NOT NULL) AS has_digital_seal
                FROM
                    \"DB\".users
                WHERE
                    user_id = " . QuotedValue($userId, DataType::NUMBER) . "
                    AND is_notary = true";
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Notary profile not found'
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
                'message' => 'Failed to get notary profile: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update notary profile information
     * @param int $userId User ID
     * @param array $profileData Profile data including files
     * @return array Response data
     */
    public function updateNotaryProfile($userId, $profileData) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($userId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Validate required fields
            $requiredFields = ['notary_commission_number', 'notary_commission_expiry'];
            foreach ($requiredFields as $field) {
                if (empty($profileData[$field])) {
                    return [
                        'success' => false,
                        'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required',
                        'errors' => [$field => ['This field is required']]
                    ];
                }
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Process digital seal if provided
                $digitalSealPath = null;
                if (!empty($profileData['digital_seal'])) {
                    $seal = $profileData['digital_seal'];
                    
                    // Validate file type
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    $fileType = $seal->getClientMediaType();
                    
                    if (!in_array($fileType, $allowedTypes)) {
                        return [
                            'success' => false,
                            'message' => 'Invalid file type for digital seal. Only JPG, JPEG, and PNG are allowed.',
                            'errors' => ['digital_seal' => ['Invalid file type. Only JPG, JPEG, and PNG are allowed.']]
                        ];
                    }
                    
                    // Generate unique filename
                    $extension = pathinfo($seal->getClientFilename(), PATHINFO_EXTENSION);
                    $filename = uniqid('seal_', true) . '.' . $extension;
                    $digitalSealPath = 'uploads/seals/' . $filename;
                    
                    // Ensure upload directory exists
                    $uploadDir = dirname($digitalSealPath);
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    // Save file
                    $seal->moveTo($digitalSealPath);
                }
                
                // Update notary profile
                $sql = "UPDATE \"DB\".users SET
                        notary_commission_number = " . QuotedValue($profileData['notary_commission_number'], DataType::STRING) . ",
                        notary_commission_expiry = " . QuotedValue($profileData['notary_commission_expiry'], DataType::DATE);
                
                // Add digital seal if provided
                if ($digitalSealPath) {
                    $sql .= ", digital_seal = " . QuotedValue($digitalSealPath, DataType::STRING);
                }
                
                $sql .= " WHERE user_id = " . QuotedValue($userId, DataType::NUMBER) . " AND is_notary = true";
                
                Execute($sql, "DB");
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Notary profile updated successfully'
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
                'message' => 'Failed to update notary profile: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get all pending requests in the notarization queue
     * @param int $notaryId Notary ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getQueue($notaryId, $params = []) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Query notarization queue
            $sql = "SELECT
                    q.queue_id,
                    r.request_id,
                    r.request_reference,
                    r.document_id,
                    d.document_title,
                    r.user_id,
                    CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                    q.queue_position,
                    q.entry_time,
                    q.status,
                    q.estimated_wait_time
                FROM
                    notarization_queue q
                JOIN
                    notarization_requests r ON q.request_id = r.request_id
                JOIN
                    documents d ON r.document_id = d.document_id
                JOIN
                    \"DB\".users u ON r.user_id = u.user_id
                WHERE
                    q.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                    AND q.status IN ('queued', 'processing')
                ORDER BY
                    q.queue_position ASC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $result = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total 
                          FROM notarization_queue 
                          WHERE notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                          AND status IN ('queued', 'processing')";
            
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
                'message' => 'Failed to get notarization queue: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Accept a request from the queue for processing
     * @param int $notaryId Notary ID
     * @param int $queueId Queue ID
     * @return array Response data
     */
    public function acceptRequest($notaryId, $queueId) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Check if queue item exists and belongs to this notary
            $sql = "SELECT
                    q.queue_id,
                    q.request_id,
                    r.document_id,
                    q.status
                FROM
                    notarization_queue q
                JOIN
                    notarization_requests r ON q.request_id = r.request_id
                WHERE
                    q.queue_id = " . QuotedValue($queueId, DataType::NUMBER) . "
                    AND q.notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Queue item not found or not assigned to this notary'
                ];
            }
            
            $queueItem = $result[0];
            
            if ($queueItem['status'] !== 'queued') {
                return [
                    'success' => false,
                    'message' => 'Queue item cannot be accepted (status: ' . $queueItem['status'] . ')'
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Update queue status
                $sql = "UPDATE notarization_queue SET
                        status = 'processing'
                        WHERE queue_id = " . QuotedValue($queueId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Update request status
                $sql = "UPDATE notarization_requests SET
                        status = 'processing',
                        notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . ",
                        assigned_at = CURRENT_TIMESTAMP
                        WHERE request_id = " . QuotedValue($queueItem['request_id'], DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Add to activity log
                $this->addActivityLog(
                    $queueItem['document_id'],
                    $notaryId,
                    'accept_request',
                    'Notary accepted request for processing'
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Request accepted',
                    'data' => [
                        'request_id' => $queueItem['request_id'],
                        'document_id' => $queueItem['document_id']
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
                'message' => 'Failed to accept request: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Approve and notarize a document
     * @param int $notaryId Notary ID
     * @param int $requestId Request ID
     * @param array $notarizationData Notarization data
     * @return array Response data
     */
    public function approveRequest($notaryId, $requestId, $notarizationData) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary, notary_commission_number, notary_commission_expiry 
                    FROM \"DB\".users 
                    WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            $notary = $result[0];
            
            // Check if commission is expired
            $today = date('Y-m-d');
            if ($notary['notary_commission_expiry'] < $today) {
                return [
                    'success' => false,
                    'message' => 'Notary commission is expired'
                ];
            }
            
            // Validate required fields
            $requiredFields = [
                'document_number',
                'page_number',
                'book_number',
                'series_of',
                'notary_location',
                'certificate_type',
                'certificate_text'
            ];
            
            foreach ($requiredFields as $field) {
                if (empty($notarizationData[$field])) {
                    return [
                        'success' => false,
                        'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required',
                        'errors' => [$field => ['This field is required']]
                    ];
                }
            }
            
            // Check if request exists and is assigned to this notary
            $sql = "SELECT
                    r.request_id,
                    r.document_id,
                    r.status,
                    r.user_id,
                    r.payment_status,
                    d.document_title
                FROM
                    notarization_requests r
                JOIN
                    documents d ON r.document_id = d.document_id
                WHERE
                    r.request_id = " . QuotedValue($requestId, DataType::NUMBER) . "
                    AND r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Request not found or not assigned to this notary'
                ];
            }
            
            $request = $result[0];
            
            if ($request['status'] !== 'processing') {
                return [
                    'success' => false,
                    'message' => 'Request cannot be approved (status: ' . $request['status'] . ')'
                ];
            }
            
            if ($request['payment_status'] !== 'paid') {
                return [
                    'success' => false,
                    'message' => 'Request payment is not completed'
                ];
            }
            
            // Generate unique keycode for verification
            $keycode = $this->generateKeycode();
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Create notarized document record
                $sql = "INSERT INTO notarized_documents (
                        request_id,
                        document_id,
                        notary_id,
                        document_number,
                        page_number,
                        book_number,
                        series_of,
                        doc_keycode,
                        digital_signature,
                        digital_seal,
                        certificate_text,
                        certificate_type,
                        notary_location,
                        revoked
                    ) VALUES (
                        " . QuotedValue($requestId, DataType::NUMBER) . ",
                        " . QuotedValue($request['document_id'], DataType::NUMBER) . ",
                        " . QuotedValue($notaryId, DataType::NUMBER) . ",
                        " . QuotedValue($notarizationData['document_number'], DataType::STRING) . ",
                        " . QuotedValue($notarizationData['page_number'], DataType::NUMBER) . ",
                        " . QuotedValue($notarizationData['book_number'], DataType::STRING) . ",
                        " . QuotedValue($notarizationData['series_of'], DataType::STRING) . ",
                        " . QuotedValue($keycode, DataType::STRING) . ",
                        NULL, -- Digital signature will be added during PDF generation
                        NULL, -- Digital seal will be added during PDF generation
                        " . QuotedValue($notarizationData['certificate_text'], DataType::STRING) . ",
                        " . QuotedValue($notarizationData['certificate_type'], DataType::STRING) . ",
                        " . QuotedValue($notarizationData['notary_location'], DataType::STRING) . ",
                        FALSE
                    ) RETURNING notarized_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['notarized_id'])) {
                    throw new \Exception("Failed to create notarized document record");
                }
                
                $notarizedId = $result[0]['notarized_id'];
                
                // Generate verification URL
                $verificationUrl = 'verify/' . $notarizedId;
                
                // Create verification record
                $expiryDate = date('Y-m-d H:i:s', strtotime('+5 years')); // Verification expires in 5 years
                
                $sql = "INSERT INTO document_verification (
                        notarized_id,
                        document_number,
                        keycode,
                        verification_url,
                        qr_code_path,
                        expiry_date,
                        failed_attempts
                    ) VALUES (
                        " . QuotedValue($notarizedId, DataType::NUMBER) . ",
                        " . QuotedValue($notarizationData['document_number'], DataType::STRING) . ",
                        " . QuotedValue($keycode, DataType::STRING) . ",
                        " . QuotedValue($verificationUrl, DataType::STRING) . ",
                        NULL, -- QR code path will be added later
                        " . QuotedValue($expiryDate, DataType::DATE) . ",
                        0
                    )";
                
                Execute($sql, "DB");
                
                // Update request and document status
                $sql = "UPDATE notarization_requests SET
                        status = 'notarized',
                        notarized_at = CURRENT_TIMESTAMP
                        WHERE request_id = " . QuotedValue($requestId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                $sql = "UPDATE documents SET
                        status = 'notarized'
                        WHERE document_id = " . QuotedValue($request['document_id'], DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Remove from queue if exists
                $sql = "DELETE FROM notarization_queue 
                        WHERE request_id = " . QuotedValue($requestId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Add to activity log
                $this->addActivityLog(
                    $request['document_id'],
                    $notaryId,
                    'notarize_document',
                    'Document notarized by notary'
                );
                
                // Create notification for document owner
                $this->createNotification(
                    $request['user_id'],
                    'document_notarized',
                    'document_' . $request['document_id'],
                    'Document Notarized',
                    'Your document "' . $request['document_title'] . '" has been notarized.',
                    'notarized/' . $notarizedId
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Document notarized successfully',
                    'data' => [
                        'notarized_id' => $notarizedId,
                        'document_number' => $notarizationData['document_number'],
                        'doc_keycode' => $keycode
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
                'message' => 'Failed to approve and notarize document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Reject a document with reason
     * @param int $notaryId Notary ID
     * @param int $requestId Request ID
     * @param string $rejectionReason Reason for rejection
     * @return array Response data
     */
    public function rejectRequest($notaryId, $requestId, $rejectionReason) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Validate rejection reason
            if (empty($rejectionReason)) {
                return [
                    'success' => false,
                    'message' => 'Rejection reason is required',
                    'errors' => ['rejection_reason' => ['Rejection reason is required']]
                ];
            }
            
            // Check if request exists and is assigned to this notary
            $sql = "SELECT
                    r.request_id,
                    r.document_id,
                    r.status,
                    r.user_id,
                    d.document_title
                FROM
                    notarization_requests r
                JOIN
                    documents d ON r.document_id = d.document_id
                WHERE
                    r.request_id = " . QuotedValue($requestId, DataType::NUMBER) . "
                    AND r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Request not found or not assigned to this notary'
                ];
            }
            
            $request = $result[0];
            
            if ($request['status'] !== 'processing') {
                return [
                    'success' => false,
                    'message' => 'Request cannot be rejected (status: ' . $request['status'] . ')'
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Update request status
                $sql = "UPDATE notarization_requests SET
                        status = 'rejected',
                        rejection_reason = " . QuotedValue($rejectionReason, DataType::STRING) . ",
                        rejected_at = CURRENT_TIMESTAMP,
                        rejected_by = " . QuotedValue($notaryId, DataType::NUMBER) . "
                        WHERE request_id = " . QuotedValue($requestId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Update document status
                $sql = "UPDATE documents SET
                        status = 'rejected'
                        WHERE document_id = " . QuotedValue($request['document_id'], DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Remove from queue if exists
                $sql = "DELETE FROM notarization_queue 
                        WHERE request_id = " . QuotedValue($requestId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Add to activity log
                $this->addActivityLog(
                    $request['document_id'],
                    $notaryId,
                    'reject_document',
                    'Document rejected by notary: ' . $rejectionReason
                );
                
                // Create notification for document owner
                $this->createNotification(
                    $request['user_id'],
                    'document_rejected',
                    'document_' . $request['document_id'],
                    'Document Rejected',
                    'Your document "' . $request['document_title'] . '" has been rejected by the notary.',
                    'requests/' . $requestId
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Document rejected'
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
                'message' => 'Failed to reject document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get processed (approved/rejected) requests by the notary
     * @param int $notaryId Notary ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getProcessedRequests($notaryId, $params = []) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Filter parameters
            $status = isset($params['status']) && in_array($params['status'], ['notarized', 'rejected']) 
                ? $params['status'] 
                : null;
            
            $startDate = !empty($params['start_date']) && strtotime($params['start_date']) 
                ? date('Y-m-d', strtotime($params['start_date'])) 
                : null;
            
            $endDate = !empty($params['end_date']) && strtotime($params['end_date']) 
                ? date('Y-m-d', strtotime($params['end_date'])) 
                : null;
            
            // Build WHERE clause
            $where = "r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . " 
                     AND r.status IN ('notarized', 'rejected')";
            
            if ($status) {
                $where .= " AND r.status = " . QuotedValue($status, DataType::STRING);
            }
            
            if ($startDate) {
                $where .= " AND (
                    CASE 
                        WHEN r.status = 'notarized' THEN r.notarized_at 
                        WHEN r.status = 'rejected' THEN r.rejected_at 
                        ELSE r.modified_at 
                    END
                ) >= " . QuotedValue($startDate . ' 00:00:00', DataType::DATE);
            }
            
            if ($endDate) {
                $where .= " AND (
                    CASE 
                        WHEN r.status = 'notarized' THEN r.notarized_at 
                        WHEN r.status = 'rejected' THEN r.rejected_at 
                        ELSE r.modified_at 
                    END
                ) <= " . QuotedValue($endDate . ' 23:59:59', DataType::DATE);
            }
            
            // Query processed requests
            $sql = "SELECT
                    r.request_id,
                    r.request_reference,
                    r.document_id,
                    d.document_title,
                    r.user_id,
                    CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                    r.status,
                    r.requested_at,
                    CASE 
                        WHEN r.status = 'notarized' THEN r.notarized_at 
                        WHEN r.status = 'rejected' THEN r.rejected_at 
                        ELSE r.modified_at 
                    END AS processed_at,
                    nd.document_number,
                    nd.doc_keycode
                FROM
                    notarization_requests r
                JOIN
                    documents d ON r.document_id = d.document_id
                JOIN
                    \"DB\".users u ON r.user_id = u.user_id
                LEFT JOIN
                    notarized_documents nd ON r.request_id = nd.request_id
                WHERE
                    " . $where . "
                ORDER BY
                    CASE 
                        WHEN r.status = 'notarized' THEN r.notarized_at 
                        WHEN r.status = 'rejected' THEN r.rejected_at 
                        ELSE r.modified_at 
                    END DESC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $result = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total 
                          FROM notarization_requests r
                          WHERE " . $where;
            
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
                'message' => 'Failed to get processed requests: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get performance metrics for the notary
     * @param int $notaryId Notary ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getPerformanceMetrics($notaryId, $params = []) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Filter parameters
            $startDate = !empty($params['start_date']) && strtotime($params['start_date']) 
                ? date('Y-m-d', strtotime($params['start_date'])) 
                : date('Y-m-d', strtotime('-30 days'));
            
            $endDate = !empty($params['end_date']) && strtotime($params['end_date']) 
                ? date('Y-m-d', strtotime($params['end_date'])) 
                : date('Y-m-d');
            
            // Date filter condition
            $dateFilter = " AND (
                CASE 
                    WHEN r.status = 'notarized' THEN r.notarized_at 
                    WHEN r.status = 'rejected' THEN r.rejected_at 
                    ELSE r.modified_at 
                END
            ) BETWEEN " . QuotedValue($startDate . ' 00:00:00', DataType::DATE) . " 
              AND " . QuotedValue($endDate . ' 23:59:59', DataType::DATE);
            
            // Get total documents processed
            $sqlProcessed = "SELECT
                            COUNT(*) AS total,
                            SUM(CASE WHEN r.status = 'notarized' THEN 1 ELSE 0 END) AS approved,
                            SUM(CASE WHEN r.status = 'rejected' THEN 1 ELSE 0 END) AS rejected
                        FROM
                            notarization_requests r
                        WHERE
                            r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                            AND r.status IN ('notarized', 'rejected')
                            " . $dateFilter;
            
            $processed = ExecuteRows($sqlProcessed, "DB");
            
            // Calculate approval rate
            $totalProcessed = (int)($processed[0]['total'] ?? 0);
            $approved = (int)($processed[0]['approved'] ?? 0);
            $rejected = (int)($processed[0]['rejected'] ?? 0);
            
            $approvalRate = $totalProcessed > 0 ? round(($approved / $totalProcessed) * 100, 1) : 0;
            
            // Calculate average processing time
            $sqlAvgTime = "SELECT
                            AVG(
                                CASE 
                                    WHEN r.status = 'notarized' THEN 
                                        EXTRACT(EPOCH FROM (r.notarized_at - r.assigned_at)) / 60
                                    WHEN r.status = 'rejected' THEN 
                                        EXTRACT(EPOCH FROM (r.rejected_at - r.assigned_at)) / 60
                                    ELSE NULL
                                END
                            ) AS avg_processing_time
                        FROM
                            notarization_requests r
                        WHERE
                            r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                            AND r.status IN ('notarized', 'rejected')
                            AND r.assigned_at IS NOT NULL
                            " . $dateFilter;
            
            $avgTime = ExecuteRows($sqlAvgTime, "DB");
            $averageProcessingTime = $avgTime[0]['avg_processing_time'] ? round($avgTime[0]['avg_processing_time'], 1) : 0;
            
            // Calculate queue efficiency
            $sqlQueueEfficiency = "SELECT
                                    COUNT(*) AS total_queue,
                                    SUM(CASE WHEN r.status IN ('notarized', 'rejected') THEN 1 ELSE 0 END) AS completed
                                FROM
                                    notarization_queue q
                                JOIN
                                    notarization_requests r ON q.request_id = r.request_id
                                WHERE
                                    q.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                                    " . $dateFilter;
            
            $queueEfficiency = ExecuteRows($sqlQueueEfficiency, "DB");
            $totalQueue = (int)($queueEfficiency[0]['total_queue'] ?? 0);
            $completed = (int)($queueEfficiency[0]['completed'] ?? 0);
            
            $queueEfficiencyRate = $totalQueue > 0 ? round(($completed / $totalQueue) * 100, 1) : 100;
            
            // Get daily processing trends
            $sqlDaily = "SELECT
                            TO_CHAR(
                                CASE 
                                    WHEN r.status = 'notarized' THEN r.notarized_at 
                                    WHEN r.status = 'rejected' THEN r.rejected_at 
                                    ELSE r.modified_at 
                                END, 'YYYY-MM-DD'
                            ) AS date,
                            COUNT(*) AS processed,
                            AVG(
                                CASE 
                                    WHEN r.status = 'notarized' THEN 
                                        EXTRACT(EPOCH FROM (r.notarized_at - r.assigned_at)) / 60
                                    WHEN r.status = 'rejected' THEN 
                                        EXTRACT(EPOCH FROM (r.rejected_at - r.assigned_at)) / 60
                                    ELSE NULL
                                END
                            ) AS average_time
                        FROM
                            notarization_requests r
                        WHERE
                            r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                            AND r.status IN ('notarized', 'rejected')
                            " . $dateFilter . "
                        GROUP BY
                            TO_CHAR(
                                CASE 
                                    WHEN r.status = 'notarized' THEN r.notarized_at 
                                    WHEN r.status = 'rejected' THEN r.rejected_at 
                                    ELSE r.modified_at 
                                END, 'YYYY-MM-DD'
                            )
                        ORDER BY
                            date DESC";
            
            $dailyTrends = ExecuteRows($sqlDaily, "DB");
            
            // Get weekly processing trends
            $sqlWeekly = "SELECT
                            TO_CHAR(
                                CASE 
                                    WHEN r.status = 'notarized' THEN r.notarized_at 
                                    WHEN r.status = 'rejected' THEN r.rejected_at 
                                    ELSE r.modified_at 
                                END, 'IYYY-IW'
                            ) AS week,
                            COUNT(*) AS processed,
                            AVG(
                                CASE 
                                    WHEN r.status = 'notarized' THEN 
                                        EXTRACT(EPOCH FROM (r.notarized_at - r.assigned_at)) / 60
                                    WHEN r.status = 'rejected' THEN 
                                        EXTRACT(EPOCH FROM (r.rejected_at - r.assigned_at)) / 60
                                    ELSE NULL
                                END
                            ) AS average_time
                        FROM
                            notarization_requests r
                        WHERE
                            r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                            AND r.status IN ('notarized', 'rejected')
                            " . $dateFilter . "
                        GROUP BY
                            TO_CHAR(
                                CASE 
                                    WHEN r.status = 'notarized' THEN r.notarized_at 
                                    WHEN r.status = 'rejected' THEN r.rejected_at 
                                    ELSE r.modified_at 
                                END, 'IYYY-IW'
                            )
                        ORDER BY
                            week DESC";
            
            $weeklyTrends = ExecuteRows($sqlWeekly, "DB");
            
            // Get document types breakdown
            $sqlDocTypes = "SELECT
                            t.template_name,
                            COUNT(*) AS count,
                            ROUND((COUNT(*) * 100.0 / " . ($totalProcessed > 0 ? $totalProcessed : 1) . "), 1) AS percentage
                        FROM
                            notarization_requests r
                        JOIN
                            documents d ON r.document_id = d.document_id
                        JOIN
                            document_templates t ON d.template_id = t.template_id
                        WHERE
                            r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                            AND r.status IN ('notarized', 'rejected')
                            " . $dateFilter . "
                        GROUP BY
                            t.template_name
                        ORDER BY
                            count DESC";
            
            $documentTypes = ExecuteRows($sqlDocTypes, "DB");
            
            // Format daily trends for better readability
            $formattedDailyTrends = [];
            foreach ($dailyTrends as $day) {
                $formattedDailyTrends[] = [
                    'date' => $day['date'],
                    'processed' => (int)$day['processed'],
                    'average_time' => $day['average_time'] ? round($day['average_time'], 1) : 0
                ];
            }
            
            // Format weekly trends for better readability
            $formattedWeeklyTrends = [];
            foreach ($weeklyTrends as $week) {
                // Convert YYYY-WW to "Week WW, YYYY" format
                $weekParts = explode('-', $week['week']);
                $weekLabel = 'Week ' . $weekParts[1] . ', ' . $weekParts[0];
                
                $formattedWeeklyTrends[] = [
                    'week' => $weekLabel,
                    'processed' => (int)$week['processed'],
                    'average_time' => $week['average_time'] ? round($week['average_time'], 1) : 0
                ];
            }
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'metrics' => [
                        'documents_processed' => $totalProcessed,
                        'approval_rate' => $approvalRate,
                        'average_processing_time' => $averageProcessingTime,
                        'queue_efficiency' => $queueEfficiencyRate
                    ],
                    'trends' => [
                        'daily' => $formattedDailyTrends,
                        'weekly' => $formattedWeeklyTrends
                    ],
                    'document_types' => $documentTypes
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get performance metrics: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get notary dashboard statistics
     * @param int $notaryId Notary ID
     * @return array Response data
     */
    public function getDashboardStats($notaryId) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Get queue statistics
            $sqlQueue = "SELECT
                        SUM(CASE WHEN status = 'queued' THEN 1 ELSE 0 END) AS pending,
                        SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) AS processing
                    FROM
                        notarization_queue
                    WHERE
                        notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
            
            $queueStats = ExecuteRows($sqlQueue, "DB");
            
            // Get processed document statistics
            $sqlProcessed = "SELECT
                            COUNT(*) AS total,
                            SUM(CASE WHEN r.notarized_at >= CURRENT_DATE THEN 1 ELSE 0 END) AS today,
                            SUM(CASE WHEN r.notarized_at >= CURRENT_DATE - INTERVAL '7 days' THEN 1 ELSE 0 END) AS this_week,
                            SUM(CASE WHEN r.notarized_at >= CURRENT_DATE - INTERVAL '30 days' THEN 1 ELSE 0 END) AS this_month
                        FROM
                            notarization_requests r
                        WHERE
                            r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                            AND r.status = 'notarized'";
            
            $processedStats = ExecuteRows($sqlProcessed, "DB");
            
            // Get performance metrics
            $sqlPerformance = "SELECT
                              AVG(EXTRACT(EPOCH FROM (r.notarized_at - r.assigned_at)) / 60) AS average_time,
                              COUNT(*) FILTER (WHERE r.status = 'notarized') * 100.0 / 
                                  NULLIF(COUNT(*) FILTER (WHERE r.status IN ('notarized', 'rejected')), 0) AS completion_rate
                           FROM
                              notarization_requests r
                           WHERE
                              r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                              AND r.status IN ('notarized', 'rejected')
                              AND r.assigned_at IS NOT NULL
                              AND r.notarized_at >= CURRENT_DATE - INTERVAL '30 days'";
            
            $performanceStats = ExecuteRows($sqlPerformance, "DB");
            
            // Get recent activity
            $sqlActivity = "SELECT
                           r.request_id,
                           d.document_title,
                           CASE 
                               WHEN r.status = 'notarized' THEN 'notarized'
                               WHEN r.status = 'rejected' THEN 'rejected'
                               WHEN r.status = 'processing' THEN 'processing'
                               ELSE 'accepted'
                           END AS action,
                           CASE 
                               WHEN r.status = 'notarized' THEN r.notarized_at
                               WHEN r.status = 'rejected' THEN r.rejected_at
                               ELSE r.modified_at
                           END AS date
                       FROM
                           notarization_requests r
                       JOIN
                           documents d ON r.document_id = d.document_id
                       WHERE
                           r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                       ORDER BY
                           CASE 
                               WHEN r.status = 'notarized' THEN r.notarized_at
                               WHEN r.status = 'rejected' THEN r.rejected_at
                               ELSE r.modified_at
                           END DESC
                       LIMIT 10";
            
            $recentActivity = ExecuteRows($sqlActivity, "DB");
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'queue' => [
                        'pending' => (int)($queueStats[0]['pending'] ?? 0),
                        'processing' => (int)($queueStats[0]['processing'] ?? 0)
                    ],
                    'processed' => [
                        'today' => (int)($processedStats[0]['today'] ?? 0),
                        'this_week' => (int)($processedStats[0]['this_week'] ?? 0),
                        'this_month' => (int)($processedStats[0]['this_month'] ?? 0),
                        'total' => (int)($processedStats[0]['total'] ?? 0)
                    ],
                    'performance' => [
                        'average_time' => $performanceStats[0]['average_time'] ? round($performanceStats[0]['average_time'], 1) : 0,
                        'completion_rate' => $performanceStats[0]['completion_rate'] ? round($performanceStats[0]['completion_rate'], 1) : 100
                    ],
                    'recent_activity' => $recentActivity
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
    
    /**
     * Generate notarization summary report
     * @param int $notaryId Notary ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function generateSummaryReport($notaryId, $params = []) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Filter parameters
            $startDate = !empty($params['start_date']) && strtotime($params['start_date']) 
                ? date('Y-m-d', strtotime($params['start_date'])) 
                : date('Y-m-d', strtotime('-30 days'));
            
            $endDate = !empty($params['end_date']) && strtotime($params['end_date']) 
                ? date('Y-m-d', strtotime($params['end_date'])) 
                : date('Y-m-d');
            
            // Date filter condition
            $dateFilter = " AND (
                CASE 
                    WHEN r.status = 'notarized' THEN r.notarized_at 
                    WHEN r.status = 'rejected' THEN r.rejected_at 
                    ELSE r.modified_at 
                END
            ) BETWEEN " . QuotedValue($startDate . ' 00:00:00', DataType::DATE) . " 
              AND " . QuotedValue($endDate . ' 23:59:59', DataType::DATE);
            
            // Get notarization summary by status
            $sqlSummary = "SELECT
                          COUNT(*) FILTER (WHERE r.status = 'notarized') AS total_notarized,
                          COUNT(*) FILTER (WHERE r.status = 'rejected') AS total_rejected
                       FROM
                          notarization_requests r
                       WHERE
                          r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                          AND r.status IN ('notarized', 'rejected')
                          " . $dateFilter;
            
            $summary = ExecuteRows($sqlSummary, "DB");
            
            // Get notarization breakdown by document type
            $sqlByDocType = "SELECT
                           t.template_name,
                           COUNT(*) AS count
                       FROM
                           notarization_requests r
                       JOIN
                           documents d ON r.document_id = d.document_id
                       JOIN
                           document_templates t ON d.template_id = t.template_id
                       WHERE
                           r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                           AND r.status = 'notarized'
                           " . $dateFilter . "
                       GROUP BY
                           t.template_name
                       ORDER BY
                           count DESC";
            
            $byDocumentType = ExecuteRows($sqlByDocType, "DB");
            
            // Get notarization breakdown by date
            $sqlByDate = "SELECT
                        TO_CHAR(r.notarized_at, 'YYYY-MM-DD') AS date,
                        COUNT(*) AS count
                     FROM
                        notarization_requests r
                     WHERE
                        r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER) . "
                        AND r.status = 'notarized'
                        " . $dateFilter . "
                     GROUP BY
                        TO_CHAR(r.notarized_at, 'YYYY-MM-DD')
                     ORDER BY
                        date ASC";
            
            $byDate = ExecuteRows($sqlByDate, "DB");
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'total_notarized' => (int)($summary[0]['total_notarized'] ?? 0),
                    'total_rejected' => (int)($summary[0]['total_rejected'] ?? 0),
                    'period_start' => $startDate,
                    'period_end' => $endDate,
                    'by_document_type' => $byDocumentType,
                    'by_date' => $byDate
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to generate summary report: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate detailed notarization report
     * @param int $notaryId Notary ID
     * @param array $params Query parameters
     * @return array Response data or file download
     */
    public function generateDetailedReport($notaryId, $params) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM \"DB\".users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Validate required parameters
            if (empty($params['start_date']) || empty($params['end_date']) || empty($params['format'])) {
                return [
                    'success' => false,
                    'message' => 'Missing required parameters',
                    'errors' => [
                        'start_date' => empty($params['start_date']) ? ['Start date is required'] : [],
                        'end_date' => empty($params['end_date']) ? ['End date is required'] : [],
                        'format' => empty($params['format']) ? ['Format is required'] : []
                    ]
                ];
            }
            
            // Validate format
            $allowedFormats = ['pdf', 'csv', 'excel'];
            if (!in_array($params['format'], $allowedFormats)) {
                return [
                    'success' => false,
                    'message' => 'Invalid format',
                    'errors' => ['format' => ['Format must be one of: ' . implode(', ', $allowedFormats)]]
                ];
            }
            
            // Filter parameters
            $startDate = date('Y-m-d', strtotime($params['start_date']));
            $endDate = date('Y-m-d', strtotime($params['end_date']));
            $status = isset($params['status']) && in_array($params['status'], ['all', 'notarized', 'rejected']) 
                ? $params['status'] 
                : 'all';
            $templateId = !empty($params['template_id']) ? (int)$params['template_id'] : null;
            
            // Build WHERE clause
            $where = "r.notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
            
            if ($status === 'notarized') {
                $where .= " AND r.status = 'notarized'";
            } elseif ($status === 'rejected') {
                $where .= " AND r.status = 'rejected'";
            } else {
                $where .= " AND r.status IN ('notarized', 'rejected')";
            }
            
            $where .= " AND (
                CASE 
                    WHEN r.status = 'notarized' THEN r.notarized_at 
                    WHEN r.status = 'rejected' THEN r.rejected_at 
                    ELSE r.modified_at 
                END
            ) BETWEEN " . QuotedValue($startDate . ' 00:00:00', DataType::DATE) . " 
              AND " . QuotedValue($endDate . ' 23:59:59', DataType::DATE);
            
            if ($templateId) {
                $where .= " AND d.template_id = " . QuotedValue($templateId, DataType::NUMBER);
            }
            
            // Query detailed report data
            $sql = "SELECT
                    r.request_id,
                    r.request_reference,
                    d.document_title,
                    t.template_name,
                    u.first_name || ' ' || u.last_name AS requestor_name,
                    u.email AS requestor_email,
                    r.status,
                    r.requested_at,
                    r.assigned_at,
                    CASE 
                        WHEN r.status = 'notarized' THEN r.notarized_at 
                        WHEN r.status = 'rejected' THEN r.rejected_at 
                        ELSE NULL 
                    END AS processed_at,
                    CASE 
                        WHEN r.status = 'notarized' THEN
                            EXTRACT(EPOCH FROM (r.notarized_at - r.assigned_at)) / 60
                        WHEN r.status = 'rejected' THEN
                            EXTRACT(EPOCH FROM (r.rejected_at - r.assigned_at)) / 60
                        ELSE NULL
                    END AS processing_time,
                    nd.document_number,
                    nd.page_number,
                    nd.book_number,
                    nd.series_of,
                    nd.certificate_type,
                    r.rejection_reason
                FROM
                    notarization_requests r
                JOIN
                    documents d ON r.document_id = d.document_id
                JOIN
                    document_templates t ON d.template_id = t.template_id
                JOIN
                    \"DB\".users u ON r.user_id = u.user_id
                LEFT JOIN
                    notarized_documents nd ON r.request_id = nd.request_id
                WHERE
                    " . $where . "
                ORDER BY
                    CASE 
                        WHEN r.status = 'notarized' THEN r.notarized_at 
                        WHEN r.status = 'rejected' THEN r.rejected_at 
                        ELSE r.modified_at 
                    END DESC";
            
            $reportData = ExecuteRows($sql, "DB");
            
            // Generate the report based on the requested format
            // In a real implementation, this would create the file in the requested format
            // For now, we'll just return the data
            
            // Format processing time
            foreach ($reportData as &$row) {
                if (isset($row['processing_time']) && $row['processing_time'] !== null) {
                    $row['processing_time'] = round($row['processing_time'], 1) . ' minutes';
                }
            }
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Report generated successfully',
                'data' => [
                    'format' => $params['format'],
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status,
                    'template_id' => $templateId,
                    'records' => $reportData,
                    'total_records' => count($reportData)
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to generate detailed report: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate unique keycode for document verification
     * @return string Keycode
     */
    private function generateKeycode() {
        // Generate a random alphanumeric keycode (8 characters)
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $keycode = '';
        
        for ($i = 0; $i < 8; $i++) {
            $keycode .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Check if keycode already exists
        $sql = "SELECT COUNT(*) FROM document_verification WHERE keycode = " . QuotedValue($keycode, DataType::STRING);
        $result = ExecuteScalar($sql, "DB");
        
        // If keycode exists, generate a new one recursively
        if ($result > 0) {
            return $this->generateKeycode();
        }
        
        return $keycode;
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
