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
            
            // Use QrCodeService for actual verification
            $qrCodeService = new QrCodeService();
            return $qrCodeService->verifyDocument($verificationData['document_number'], $verificationData['keycode']);
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
            
            // Use QrCodeService for verification attempt recording and status checks
            $qrCodeService = new QrCodeService();
            
            // Check if document is revoked
            if ($verification['revoked']) {
                // Record verification attempt
                $qrCodeService->recordVerificationAttempt(
                    $verification['verification_id'],
                    $verification['document_number'],
                    $verification['keycode'],
                    $_SERVER['REMOTE_ADDR'] ?? '',
                    $_SERVER['HTTP_USER_AGENT'] ?? '',
                    false,
                    'Document has been revoked'
                );
                
                return [
                    'success' => false,
                    'message' => 'Document has been revoked'
                ];
            }
            
            // Check if verification has expired
            if (strtotime($verification['expiry_date']) < time()) {
                // Record verification attempt
                $qrCodeService->recordVerificationAttempt(
                    $verification['verification_id'],
                    $verification['document_number'],
                    $verification['keycode'],
                    $_SERVER['REMOTE_ADDR'] ?? '',
                    $_SERVER['HTTP_USER_AGENT'] ?? '',
                    false,
                    'Verification has expired'
                );
                
                return [
                    'success' => false,
                    'message' => 'Verification has expired'
                ];
            }
            
            // Record successful verification attempt
            $qrCodeService->recordVerificationAttempt(
                $verification['verification_id'],
                $verification['document_number'],
                $verification['keycode'],
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? '',
                true,
                ''
            );
            
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
            // Create QrCodeService instance to use its verification method
            $qrCodeService = new QrCodeService();
            
            // Forward to QrCodeService to avoid duplication
            return $qrCodeService->recordVerificationAttempt(
                $attemptData['verification_id'] ?? null,
                $attemptData['document_number'] ?? '',
                $attemptData['keycode'] ?? '',
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? '',
                $attemptData['is_successful'] ?? false,
                $attemptData['failure_reason'] ?? ''
            );
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return failure silently
            return [
                'success' => false,
                'message' => 'Failed to record verification attempt: ' . $e->getMessage()
            ];
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
