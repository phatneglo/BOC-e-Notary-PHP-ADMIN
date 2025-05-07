<?php
// app/api/services/NotarizedDocumentService.php
namespace PHPMaker2024\eNotary;

class NotarizedDocumentService {
    /**
     * List notarized documents for a user
     * @param int $userId User ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function listNotarizedDocuments($userId, $params = []) {
        try {
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Filter parameters
            $search = isset($params['search']) ? trim($params['search']) : null;
            
            // Build WHERE clause
            $where = "d.user_id = " . QuotedValue($userId, DataType::NUMBER) . " AND d.status = 'notarized'";
            
            if ($search) {
                $where .= " AND (
                    d.document_title ILIKE " . QuotedValue('%' . $search . '%', DataType::STRING) . " OR 
                    nd.document_number ILIKE " . QuotedValue('%' . $search . '%', DataType::STRING) . "
                )";
            }
            
            // Query notarized documents
            $sql = "SELECT
                    nd.notarized_id,
                    nd.document_id,
                    d.document_title,
                    nd.document_number,
                    nd.doc_keycode,
                    nd.notarization_date,
                    nd.notary_id,
                    CONCAT(u.first_name, ' ', u.last_name) AS notary_name,
                    dv.expiry_date AS expires_at,
                    dv.qr_code_path,
                    nd.revoked
                FROM
                    notarized_documents nd
                JOIN
                    documents d ON nd.document_id = d.document_id
                JOIN
                    users u ON nd.notary_id = u.user_id
                LEFT JOIN
                    document_verification dv ON nd.notarized_id = dv.notarized_id
                WHERE
                    " . $where . "
                ORDER BY
                    nd.notarization_date DESC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $result = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total 
                FROM notarized_documents nd
                JOIN documents d ON nd.document_id = d.document_id
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
                'message' => 'Failed to list notarized documents: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get detailed information about a notarized document
     * @param int $notarizedId Notarized Document ID
     * @return array Response data
     */
    public function getNotarizedDocumentDetails($notarizedId) {
        try {
            // Get notarized document details
            $sql = "SELECT
                    nd.notarized_id,
                    nd.document_id,
                    d.document_title,
                    nd.document_number,
                    nd.page_number,
                    nd.book_number,
                    nd.series_of,
                    nd.doc_keycode,
                    nd.notary_location,
                    nd.notarization_date,
                    nd.certificate_type,
                    nd.certificate_text,
                    dv.qr_code_path,
                    pm.file_path AS notarized_document_path,
                    dv.expiry_date AS expires_at,
                    nd.notary_id,
                    CONCAT(u.first_name, ' ', u.last_name) AS notary_name,
                    dv.verification_url,
                    nd.revoked,
                    nd.revoked_at,
                    nd.revoked_by,
                    nd.revocation_reason
                FROM
                    notarized_documents nd
                JOIN
                    documents d ON nd.document_id = d.document_id
                JOIN
                    users u ON nd.notary_id = u.user_id
                LEFT JOIN
                    document_verification dv ON nd.notarized_id = dv.notarized_id
                LEFT JOIN
                    pdf_metadata pm ON nd.notarized_id = pm.notarized_id AND pm.pdf_type = 'final'
                WHERE
                    nd.notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Notarized document not found'
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
                'message' => 'Failed to get notarized document details: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Download a notarized document as PDF
     * @param int $notarizedId Notarized Document ID
     * @return array Response data
     */
    public function downloadNotarizedDocument($notarizedId) {
        try {
            // Get notarized document file path
            $sql = "SELECT
                    nd.notarized_id,
                    nd.document_id,
                    d.document_title,
                    pm.file_path AS notarized_document_path
                FROM
                    notarized_documents nd
                JOIN
                    documents d ON nd.document_id = d.document_id
                LEFT JOIN
                    pdf_metadata pm ON nd.notarized_id = pm.notarized_id AND pm.pdf_type = 'final'
                WHERE
                    nd.notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || empty($result[0]['notarized_document_path'])) {
                return [
                    'success' => false,
                    'message' => 'Notarized document file not found'
                ];
            }
            
            $document = $result[0];
            $filePath = $document['notarized_document_path'];
            
            // Check if file exists
            if (!file_exists($filePath)) {
                return [
                    'success' => false,
                    'message' => 'Notarized document file not found on server'
                ];
            }
            
            // Add to activity log
            $this->addActivityLog(
                $document['document_id'],
                Authentication::getUserId(),
                'download_notarized',
                'Notarized document downloaded'
            );
            
            // Return success response with file info
            return [
                'success' => true,
                'data' => [
                    'file_path' => $filePath,
                    'file_name' => $document['document_title'] . '.pdf',
                    'file_size' => filesize($filePath),
                    'mime_type' => 'application/pdf'
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to download notarized document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get QR code for document verification
     * @param int $notarizedId Notarized Document ID
     * @return array Response data
     */
    public function getQrCode($notarizedId) {
        try {
            // Get QR code path
            $sql = "SELECT
                    nd.notarized_id,
                    nd.document_id,
                    dv.qr_code_path
                FROM
                    notarized_documents nd
                LEFT JOIN
                    document_verification dv ON nd.notarized_id = dv.notarized_id
                WHERE
                    nd.notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || empty($result[0]['qr_code_path'])) {
                return [
                    'success' => false,
                    'message' => 'QR code not found'
                ];
            }
            
            $document = $result[0];
            $filePath = $document['qr_code_path'];
            
            // Check if file exists
            if (!file_exists($filePath)) {
                return [
                    'success' => false,
                    'message' => 'QR code file not found on server'
                ];
            }
            
            // Add to activity log
            $this->addActivityLog(
                $document['document_id'],
                Authentication::getUserId(),
                'get_qr_code',
                'QR code retrieved'
            );
            
            // Return success response with file info
            return [
                'success' => true,
                'data' => [
                    'file_path' => $filePath,
                    'file_name' => 'qrcode_' . $notarizedId . '.png',
                    'file_size' => filesize($filePath),
                    'mime_type' => 'image/png'
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get QR code: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate verification QR code for a notarized document
     * @param int $notarizedId Notarized Document ID
     * @param array $options Generation options
     * @return array Response data
     */
    public function generateVerificationQrCode($notarizedId, $options = []) {
        try {
            // Get notarized document details
            $sql = "SELECT
                    nd.notarized_id,
                    nd.document_id,
                    d.document_title,
                    nd.document_number,
                    nd.doc_keycode,
                    dv.verification_url,
                    dv.verification_id
                FROM
                    notarized_documents nd
                JOIN
                    documents d ON nd.document_id = d.document_id
                LEFT JOIN
                    document_verification dv ON nd.notarized_id = dv.notarized_id
                WHERE
                    nd.notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Notarized document not found'
                ];
            }
            
            $document = $result[0];
            
            // If verification URL is already set, generate new QR code with existing URL
            $verificationUrl = $document['verification_url'];
            $verificationId = $document['verification_id'];
            
            if (empty($verificationUrl)) {
                // Generate new verification URL and ID
                $verificationId = uniqid('ver_', true);
                $verificationUrl = 'verify/qr/' . $verificationId;
            }
            
            // Process options
            $size = isset($options['size']) ? min(max(100, (int)$options['size']), 800) : 250;
            $includeLogo = isset($options['include_logo']) ? (bool)$options['include_logo'] : true;
            $errorCorrection = isset($options['error_correction']) && in_array($options['error_correction'], ['L', 'M', 'Q', 'H']) 
                ? $options['error_correction'] 
                : 'M';
            
            // Generate QR code
            // In a real implementation, this would use a QR code library
            // For now, we'll just simulate QR code generation
            
            // Generate a unique filename for the QR code
            $filename = 'qrcode_' . $notarizedId . '_' . time() . '.png';
            $qrCodePath = 'uploads/qrcodes/' . $filename;
            
            // Ensure directory exists
            $qrCodeDir = dirname($qrCodePath);
            if (!is_dir($qrCodeDir)) {
                mkdir($qrCodeDir, 0755, true);
            }
            
            // Simulate QR code creation (in a real implementation, this would be replaced with actual QR code generation)
            file_put_contents($qrCodePath, "QR Code for document: " . $document['document_number']);
            
            // Set expiry time (5 years from now)
            $expiryTime = date('Y-m-d H:i:s', strtotime('+5 years'));
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Update document verification record
                $sql = "UPDATE document_verification SET
                        qr_code_path = " . QuotedValue($qrCodePath, DataType::STRING) . ",
                        verification_url = " . QuotedValue($verificationUrl, DataType::STRING) . ",
                        verification_id = " . QuotedValue($verificationId, DataType::STRING) . ",
                        expiry_date = " . QuotedValue($expiryTime, DataType::DATETIME) . "
                        WHERE notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
                
                $result = Execute($sql, "DB");
                
                // If no verification record exists, create one
                if (!$result) {
                    $sql = "INSERT INTO document_verification (
                            notarized_id,
                            document_number,
                            keycode,
                            verification_url,
                            verification_id,
                            qr_code_path,
                            expiry_date,
                            failed_attempts
                        ) VALUES (
                            " . QuotedValue($notarizedId, DataType::NUMBER) . ",
                            " . QuotedValue($document['document_number'], DataType::STRING) . ",
                            " . QuotedValue($document['doc_keycode'], DataType::STRING) . ",
                            " . QuotedValue($verificationUrl, DataType::STRING) . ",
                            " . QuotedValue($verificationId, DataType::STRING) . ",
                            " . QuotedValue($qrCodePath, DataType::STRING) . ",
                            " . QuotedValue($expiryTime, DataType::DATETIME) . ",
                            0
                        )";
                    
                    Execute($sql, "DB");
                }
                
                // Add to activity log
                $this->addActivityLog(
                    $document['document_id'],
                    Authentication::getUserId(),
                    'generate_qr_code',
                    'Verification QR code generated'
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'QR code generated successfully',
                    'data' => [
                        'qr_code_path' => $qrCodePath,
                        'verification_url' => $verificationUrl,
                        'verification_id' => $verificationId,
                        'expires_at' => $expiryTime
                    ]
                ];
            } catch (\Exception $e) {
                // Rollback transaction on error
                Execute("ROLLBACK", "DB");
                
                // Delete generated QR code if it exists
                if (file_exists($qrCodePath)) {
                    unlink($qrCodePath);
                }
                
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to generate QR code: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get statistics about QR code scans for a document
     * @param int $notarizedId Notarized Document ID
     * @return array Response data
     */
    public function getVerificationQrStats($notarizedId) {
        try {
            // Get verification ID
            $sql = "SELECT
                    verification_id
                FROM
                    document_verification
                WHERE
                    notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || empty($result[0]['verification_id'])) {
                return [
                    'success' => false,
                    'message' => 'Verification record not found'
                ];
            }
            
            $verificationId = $result[0]['verification_id'];
            
            // Get scan statistics
            $sql = "SELECT
                    COUNT(*) AS total_scans,
                    COUNT(DISTINCT ip_address) AS unique_scans,
                    MAX(created_at) AS last_scan
                FROM
                    verification_attempts
                WHERE
                    verification_id = " . QuotedValue($verificationId, DataType::STRING) . "
                    AND is_successful = true";
            
            $scanStats = ExecuteRows($sql, "DB");
            
            // Get scan locations
            $sql = "SELECT
                    location,
                    COUNT(*) AS count
                FROM
                    verification_attempts
                WHERE
                    verification_id = " . QuotedValue($verificationId, DataType::STRING) . "
                    AND is_successful = true
                    AND location IS NOT NULL
                GROUP BY
                    location
                ORDER BY
                    count DESC
                LIMIT 5";
            
            $scanLocations = ExecuteRows($sql, "DB");
            
            // Get scan devices
            $sql = "SELECT
                    COALESCE(
                        CASE 
                            WHEN browser_info ILIKE '%Mobile%' THEN 'Mobile'
                            WHEN browser_info ILIKE '%Tablet%' THEN 'Tablet'
                            ELSE 'Desktop'
                        END,
                        'Unknown'
                    ) AS device_type,
                    COUNT(*) AS count
                FROM
                    verification_attempts
                WHERE
                    verification_id = " . QuotedValue($verificationId, DataType::STRING) . "
                    AND is_successful = true
                GROUP BY
                    device_type
                ORDER BY
                    count DESC";
            
            $scanDevices = ExecuteRows($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'total_scans' => (int)($scanStats[0]['total_scans'] ?? 0),
                    'unique_scans' => (int)($scanStats[0]['unique_scans'] ?? 0),
                    'last_scan' => $scanStats[0]['last_scan'] ?? null,
                    'scan_locations' => $scanLocations,
                    'scan_devices' => $scanDevices
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get QR code statistics: ' . $e->getMessage()
            ];
        }
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
                    " . QuotedValue($details, DataType::TEXT) . ",
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
}
