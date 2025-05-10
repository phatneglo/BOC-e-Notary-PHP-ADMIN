<?php
// app/api/services/VerificationService.php
namespace PHPMaker2024\eNotary;

class VerificationService {
    /**
     * Verify the authenticity of a notarized document
     * @param array $verificationData Verification data
     * @return array Response data
     */
    public function verifyDocument($verificationData) {
        try {
            // Validate required fields
            if (empty($verificationData['document_number'])) {
                return [
                    'success' => false,
                    'message' => 'Document number is required',
                    'errors' => ['document_number' => ['Document number is required']]
                ];
            }
            
            if (empty($verificationData['keycode'])) {
                return [
                    'success' => false,
                    'message' => 'Verification code is required',
                    'errors' => ['keycode' => ['Verification code is required']]
                ];
            }
            
            // Get verification record
            $sql = "SELECT
                    dv.verification_id,
                    dv.notarized_id,
                    dv.document_number,
                    dv.keycode,
                    dv.expiry_date,
                    dv.failed_attempts,
                    nd.notarization_date,
                    nd.revoked,
                    d.document_title,
                    CONCAT(u.first_name, ' ', u.last_name) AS notary_name,
                    u.notary_commission_number
                FROM
                    document_verification dv
                JOIN
                    notarized_documents nd ON dv.notarized_id = nd.notarized_id
                JOIN
                    documents d ON nd.document_id = d.document_id
                JOIN
                    users u ON nd.notary_id = u.user_id
                WHERE
                    dv.document_number = " . QuotedValue($verificationData['document_number'], DataType::STRING) . "
                    AND dv.keycode = " . QuotedValue($verificationData['keycode'], DataType::STRING);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                // Record failed verification attempt
                $this->recordVerificationAttempt([
                    'document_number' => $verificationData['document_number'],
                    'keycode' => $verificationData['keycode'],
                    'is_successful' => false,
                    'failure_reason' => 'Invalid document number or verification code'
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Invalid document number or verification code'
                ];
            }
            
            $verification = $result[0];
            
            // Check if document is revoked
            if ($verification['revoked']) {
                // Record failed verification attempt
                $this->recordVerificationAttempt([
                    'verification_id' => $verification['verification_id'],
                    'document_number' => $verification['document_number'],
                    'keycode' => $verification['keycode'],
                    'is_successful' => false,
                    'failure_reason' => 'Document has been revoked'
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Document has been revoked'
                ];
            }
            
            // Check if verification has expired
            if (strtotime($verification['expiry_date']) < time()) {
                // Record failed verification attempt
                $this->recordVerificationAttempt([
                    'verification_id' => $verification['verification_id'],
                    'document_number' => $verification['document_number'],
                    'keycode' => $verification['keycode'],
                    'is_successful' => false,
                    'failure_reason' => 'Verification has expired'
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Verification has expired'
                ];
            }
            
            // Record successful verification attempt
            $this->recordVerificationAttempt([
                'verification_id' => $verification['verification_id'],
                'document_number' => $verification['document_number'],
                'keycode' => $verification['keycode'],
                'is_successful' => true
            ]);
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'is_authentic' => true,
                    'document_number' => $verification['document_number'],
                    'document_title' => $verification['document_title'],
                    'notarization_date' => $verification['notarization_date'],
                    'notary_name' => $verification['notary_name'],
                    'notary_commission_number' => $verification['notary_commission_number'],
                    'expires_at' => $verification['expiry_date']
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to verify document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Verify document by document number and keycode (adapter for QrCodeService)
     * @param string $documentNumber Document number
     * @param string $keycode Verification keycode
     * @return array Response data
     */
    public function verifyDocumentByNumberAndCode($documentNumber, $keycode) {
        // Create a QrCodeService instance to use its verification method
        $qrCodeService = new QrCodeService();
        return $qrCodeService->verifyDocument($documentNumber, $keycode);
    }
    
    /**
     * Get verification from QR code
     * @param string $verificationId Verification ID
     * @return array Response data
     */
    public function getVerificationByQrCode($verificationId) {
        try {
            // Get verification record
            $sql = "SELECT
                    dv.verification_id,
                    dv.notarized_id,
                    dv.document_number,
                    dv.keycode,
                    dv.expiry_date,
                    nd.notarization_date,
                    nd.revoked,
                    d.document_title,
                    CONCAT(u.first_name, ' ', u.last_name) AS notary_name,
                    u.notary_commission_number
                FROM
                    document_verification dv
                JOIN
                    notarized_documents nd ON dv.notarized_id = nd.notarized_id
                JOIN
                    documents d ON nd.document_id = d.document_id
                JOIN
                    users u ON nd.notary_id = u.user_id
                WHERE
                    dv.verification_id = " . QuotedValue($verificationId, DataType::STRING);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Invalid verification code'
                ];
            }
            
            $verification = $result[0];
            
            // Check if document is revoked
            if ($verification['revoked']) {
                // Record verification attempt
                $this->recordVerificationAttempt([
                    'verification_id' => $verification['verification_id'],
                    'document_number' => $verification['document_number'],
                    'keycode' => $verification['keycode'],
                    'is_successful' => false,
                    'failure_reason' => 'Document has been revoked'
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Document has been revoked'
                ];
            }
            
            // Check if verification has expired
            if (strtotime($verification['expiry_date']) < time()) {
                // Record verification attempt
                $this->recordVerificationAttempt([
                    'verification_id' => $verification['verification_id'],
                    'document_number' => $verification['document_number'],
                    'keycode' => $verification['keycode'],
                    'is_successful' => false,
                    'failure_reason' => 'Verification has expired'
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Verification has expired'
                ];
            }
            
            // Record successful verification attempt
            $this->recordVerificationAttempt([
                'verification_id' => $verification['verification_id'],
                'document_number' => $verification['document_number'],
                'keycode' => $verification['keycode'],
                'is_successful' => true
            ]);
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'is_authentic' => true,
                    'document_number' => $verification['document_number'],
                    'document_title' => $verification['document_title'],
                    'notarization_date' => $verification['notarization_date'],
                    'notary_name' => $verification['notary_name'],
                    'notary_commission_number' => $verification['notary_commission_number'],
                    'expires_at' => $verification['expiry_date']
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to verify document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Record verification attempt
     * @param array $attemptData Verification attempt data
     * @return array Response data
     */
    public function recordVerificationAttempt($attemptData) {
        try {
            // Check if we should limit verification attempts
            if (!empty($attemptData['verification_id'])) {
                // Check if there are too many failed attempts already
                $sql = "SELECT
                        verification_id,
                        failed_attempts
                    FROM
                        document_verification
                    WHERE
                        verification_id = " . QuotedValue($attemptData['verification_id'], DataType::STRING);
                
                $result = ExecuteRows($sql, "DB");
                
                if (!empty($result)) {
                    $verification = $result[0];
                    
                    // Check if there are too many recent failed attempts
                    $sql = "SELECT
                            COUNT(*) AS recent_failures
                        FROM
                            verification_attempts
                        WHERE
                            verification_id = " . QuotedValue($attemptData['verification_id'], DataType::STRING) . "
                            AND is_successful = false
                            AND created_at > CURRENT_TIMESTAMP - INTERVAL '24 hours'";
                    
                    $failuresResult = ExecuteRows($sql, "DB");
                    $recentFailures = $failuresResult[0]['recent_failures'] ?? 0;
                    
                    if ($recentFailures >= 3) {
                        return [
                            'success' => false,
                            'message' => 'Too many failed verification attempts. Please try again later.',
                            'data' => [
                                'is_successful' => false,
                                'attempt_count' => $recentFailures,
                                'attempts_remaining' => 0
                            ]
                        ];
                    }
                    
                    // Update failed attempts counter if this attempt is unsuccessful
                    if (!$attemptData['is_successful']) {
                        $sql = "UPDATE document_verification SET
                                failed_attempts = failed_attempts + 1
                                WHERE verification_id = " . QuotedValue($attemptData['verification_id'], DataType::STRING);
                        
                        Execute($sql, "DB");
                    }
                }
            }
            
            // Insert verification attempt
            $sql = "INSERT INTO verification_attempts (
                    verification_id,
                    document_number,
                    keycode,
                    ip_address,
                    user_agent,
                    location,
                    is_successful,
                    failure_reason,
                    created_at
                ) VALUES (
                    " . (!empty($attemptData['verification_id']) ? QuotedValue($attemptData['verification_id'], DataType::STRING) : "NULL") . ",
                    " . QuotedValue($attemptData['document_number'], DataType::STRING) . ",
                    " . QuotedValue($attemptData['keycode'], DataType::STRING) . ",
                    " . QuotedValue($_SERVER['REMOTE_ADDR'] ?? null, DataType::STRING) . ",
                    " . QuotedValue($_SERVER['HTTP_USER_AGENT'] ?? null, DataType::STRING) . ",
                    " . QuotedValue($attemptData['location'] ?? null, DataType::STRING) . ",
                    " . QuotedValue($attemptData['is_successful'], DataType::BOOLEAN) . ",
                    " . QuotedValue($attemptData['failure_reason'] ?? null, DataType::STRING) . ",
                    CURRENT_TIMESTAMP
                )";
            
            Execute($sql, "DB");
            
            // Calculate attempts remaining (if verification ID is provided)
            $attemptsRemaining = 3; // Default max attempts
            
            if (!empty($attemptData['verification_id'])) {
                $sql = "SELECT
                        COUNT(*) AS recent_failures
                    FROM
                        verification_attempts
                    WHERE
                        verification_id = " . QuotedValue($attemptData['verification_id'], DataType::STRING) . "
                        AND is_successful = false
                        AND created_at > CURRENT_TIMESTAMP - INTERVAL '24 hours'";
                
                $failuresResult = ExecuteRows($sql, "DB");
                $recentFailures = $failuresResult[0]['recent_failures'] ?? 0;
                
                $attemptsRemaining = max(0, 3 - $recentFailures);
            }
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Verification attempt recorded',
                'data' => [
                    'is_successful' => $attemptData['is_successful'],
                    'attempt_count' => $attemptsRemaining > 0 ? 3 - $attemptsRemaining : 3,
                    'attempts_remaining' => $attemptsRemaining
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return failure silently
            return false;
        }
    }
    
    /**
     * Test QR code scanning
     * @param array $qrData QR code data
     * @return array Response data
     */
    public function testQrCodeScan($qrData) {
        try {
            // Validate file
            if (empty($qrData['qr_code'])) {
                return [
                    'success' => false,
                    'message' => 'QR code file is required',
                    'errors' => ['qr_code' => ['QR code file is required']]
                ];
            }
            
            $qrCode = $qrData['qr_code'];
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            $fileType = $qrCode->getClientMediaType();
            
            if (!in_array($fileType, $allowedTypes)) {
                return [
                    'success' => false,
                    'message' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed.',
                    'errors' => ['qr_code' => ['Invalid file type. Only JPG, JPEG, and PNG are allowed.']]
                ];
            }
            
            // Save temporary QR code file
            $tempPath = tempnam(sys_get_temp_dir(), 'qr_');
            $qrCode->moveTo($tempPath);
            
            // In a real implementation, this would decode the QR code and extract the verification URL and ID
            // For now, we'll just simulate this process and assume it's valid
            
            $isValid = true;
            $verificationUrl = 'verify/qr/test_verification_id';
            $verificationId = 'test_verification_id';
            
            // Clean up temporary file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'is_valid' => $isValid,
                    'verification_url' => $verificationUrl,
                    'verification_id' => $verificationId
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to test QR code: ' . $e->getMessage()
            ];
        }
    }
}
