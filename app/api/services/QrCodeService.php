<?php
// app/api/services/QrCodeService.php
namespace PHPMaker2024\eNotary;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

class QrCodeService {
    public function __construct() {
        // Ensure the required libraries are loaded
        if (!class_exists('\Endroid\QrCode\Builder\Builder')) {
            throw new \Exception('Endroid QR Code library not found. Please install it using: composer require endroid/qr-code');
        }
    }
    
    
    /**
     * Get QR code settings for a notary
     * @param int $notaryId Notary ID
     * @return array Response data
     */
    public function getNotaryQrSettings($notaryId) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Get QR settings
            $sql = "SELECT
                    settings_id,
                    notary_id,
                    default_size,
                    foreground_color,
                    background_color,
                    logo_path,
                    logo_size_percent,
                    error_correction,
                    corner_radius_percent
                FROM
                    notary_qr_settings
                WHERE
                    notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            // If no settings exist, return default settings
            if (empty($result)) {
                return [
                    'success' => true,
                    'data' => [
                        'settings_id' => null,
                        'default_size' => 250,
                        'foreground_color' => '#000000',
                        'background_color' => '#FFFFFF',
                        'logo_path' => null,
                        'logo_size_percent' => 20,
                        'error_correction' => 'M',
                        'corner_radius_percent' => 0
                    ]
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
                'message' => 'Failed to get QR code settings: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update QR code settings for a notary
     * @param int $notaryId Notary ID
     * @param array $settingsData Settings data
     * @return array Response data
     */
    public function updateNotaryQrSettings($notaryId, $settingsData) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Validate settings data
            $defaultSize = isset($settingsData['default_size']) ? min(max(100, (int)$settingsData['default_size']), 1000) : 250;
            $foregroundColor = isset($settingsData['foreground_color']) && preg_match('/^#[0-9A-F]{6}$/i', $settingsData['foreground_color']) ? $settingsData['foreground_color'] : '#000000';
            $backgroundColor = isset($settingsData['background_color']) && preg_match('/^#[0-9A-F]{6}$/i', $settingsData['background_color']) ? $settingsData['background_color'] : '#FFFFFF';
            $logoSizePercent = isset($settingsData['logo_size_percent']) ? min(max(5, (int)$settingsData['logo_size_percent']), 50) : 20;
            $errorCorrection = isset($settingsData['error_correction']) && in_array($settingsData['error_correction'], ['L', 'M', 'Q', 'H']) ? $settingsData['error_correction'] : 'M';
            $cornerRadiusPercent = isset($settingsData['corner_radius_percent']) ? min(max(0, (int)$settingsData['corner_radius_percent']), 50) : 0;
            
            // Process logo if provided
            $logoPath = null;
            if (!empty($settingsData['logo_path'])) {
                $logoPath = $settingsData['logo_path'];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Check if settings exist
                $sql = "SELECT settings_id FROM notary_qr_settings WHERE notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result)) {
                    // Insert new settings
                    $sql = "INSERT INTO notary_qr_settings (
                            notary_id,
                            default_size,
                            foreground_color,
                            background_color,
                            logo_path,
                            logo_size_percent,
                            error_correction,
                            corner_radius_percent
                        ) VALUES (
                            " . QuotedValue($notaryId, DataType::NUMBER) . ",
                            " . QuotedValue($defaultSize, DataType::NUMBER) . ",
                            " . QuotedValue($foregroundColor, DataType::STRING) . ",
                            " . QuotedValue($backgroundColor, DataType::STRING) . ",
                            " . QuotedValue($logoPath, DataType::STRING) . ",
                            " . QuotedValue($logoSizePercent, DataType::NUMBER) . ",
                            " . QuotedValue($errorCorrection, DataType::STRING) . ",
                            " . QuotedValue($cornerRadiusPercent, DataType::NUMBER) . "
                        )";
                } else {
                    // Update existing settings
                    $settingsId = $result[0]['settings_id'];
                    
                    $sql = "UPDATE notary_qr_settings SET
                            default_size = " . QuotedValue($defaultSize, DataType::NUMBER) . ",
                            foreground_color = " . QuotedValue($foregroundColor, DataType::STRING) . ",
                            background_color = " . QuotedValue($backgroundColor, DataType::STRING) . ",
                            logo_size_percent = " . QuotedValue($logoSizePercent, DataType::NUMBER) . ",
                            error_correction = " . QuotedValue($errorCorrection, DataType::STRING) . ",
                            corner_radius_percent = " . QuotedValue($cornerRadiusPercent, DataType::NUMBER);
                    
                    // Only update logo path if provided
                    if ($logoPath !== null) {
                        $sql .= ", logo_path = " . QuotedValue($logoPath, DataType::STRING);
                    }
                    
                    $sql .= " WHERE settings_id = " . QuotedValue($settingsId, DataType::NUMBER);
                }
                
                Execute($sql, "DB");
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'QR code settings updated successfully'
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
                'message' => 'Failed to update QR code settings: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Customize QR code appearance
     * @param int $notaryId Notary ID
     * @param array $customizationData Customization data including logo file
     * @return array Response data
     */
    public function customizeQrCodeAppearance($notaryId, $customizationData) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Process logo if provided
            $logoPath = null;
            if (!empty($customizationData['logo'])) {
                $logo = $customizationData['logo'];
                
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                $fileType = $logo->getClientMediaType();
                
                if (!in_array($fileType, $allowedTypes)) {
                    return [
                        'success' => false,
                        'message' => 'Invalid file type for logo. Only JPG, JPEG, and PNG are allowed.',
                        'errors' => ['logo' => ['Invalid file type. Only JPG, JPEG, and PNG are allowed.']]
                    ];
                }
                
                // Generate unique filename
                $extension = pathinfo($logo->getClientFilename(), PATHINFO_EXTENSION);
                $filename = uniqid('qr_logo_', true) . '.' . $extension;
                $logoPath = 'uploads/qr_logos/' . $filename;
                
                // Ensure upload directory exists
                $uploadDir = dirname($logoPath);
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Save logo file
                $logo->moveTo($logoPath);
            }
            
            // Prepare settings data
            $settingsData = [
                'default_size' => $customizationData['default_size'] ?? 250,
                'foreground_color' => $customizationData['foreground_color'] ?? '#000000',
                'background_color' => $customizationData['background_color'] ?? '#FFFFFF',
                'logo_size_percent' => $customizationData['logo_size_percent'] ?? 20,
                'error_correction' => $customizationData['error_correction'] ?? 'M',
                'corner_radius_percent' => $customizationData['corner_radius_percent'] ?? 0
            ];
            
            // Add logo path if available
            if ($logoPath) {
                $settingsData['logo_path'] = $logoPath;
            }
            
            // Update QR settings
            return $this->updateNotaryQrSettings($notaryId, $settingsData);
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to customize QR code appearance: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Upload QR code logo
     * @param int $notaryId Notary ID
     * @param object $file Uploaded file
     * @return array Response data
     */
    public function uploadQrLogo($notaryId, $file) {
        try {
            // Verify user is a notary
            $sql = "SELECT is_notary FROM users WHERE user_id = " . QuotedValue($notaryId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !$result[0]['is_notary']) {
                return [
                    'success' => false,
                    'message' => 'User is not a notary'
                ];
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            $fileType = $file->getClientMediaType();
            
            if (!in_array($fileType, $allowedTypes)) {
                return [
                    'success' => false,
                    'message' => 'Invalid file type for logo. Only JPG, JPEG, and PNG are allowed.'
                ];
            }
            
            // Generate unique filename
            $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
            $filename = 'qr_logo_' . $notaryId . '_' . uniqid() . '.' . $extension;
            $logoPath = 'uploads/qr_logos/' . $filename;
            
            // Ensure upload directory exists
            $uploadDir = dirname($logoPath);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Save logo file
            $file->moveTo($logoPath);
            
            // Update settings with new logo path
            $sql = "UPDATE notary_qr_settings 
                    SET logo_path = " . QuotedValue($logoPath, DataType::STRING) . "
                    WHERE notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
            
            $result = Execute($sql, "DB");
            
            if (!$result) {
                // If no rows were updated, the settings might not exist yet
                // Get current settings or defaults to create new record
                $settings = $this->getNotaryQrSettings($notaryId);
                
                if ($settings['success']) {
                    $settingsData = $settings['data'];
                    $settingsData['logo_path'] = $logoPath;
                    $this->updateNotaryQrSettings($notaryId, $settingsData);
                }
            }
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Logo uploaded successfully',
                'data' => [
                    'logo_path' => $logoPath
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to upload logo: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate QR code preview
     * @param array $params QR code parameters
     * @return array Response data with QR code image data
     */
    public function generateQrPreview($params) {
        try {
            // Parse parameters
            $text = $params['text'] ?? 'https://verify.enotary.boc.gov.ph';
            $size = min(max(100, (int)($params['size'] ?? 250)), 1000);
            $fgHex = $params['foreground'] ?? '#000000';
            $bgHex = $params['background'] ?? '#FFFFFF';
            $errorCorrection = $params['error_correction'] ?? 'M';
            $cornerRadius = min(max(0, (int)($params['corner_radius'] ?? 0)), 50);
            $logoSize = min(max(5, (int)($params['logo_size'] ?? 20)), 50);
            $useLogo = ($params['use_logo'] ?? '0') === '1';
            $notaryId = $params['notary_id'] ?? null;
            
            // Resolve logo path if using logo
            $logoPath = null;
            if ($useLogo && $notaryId) {
                $sql = "SELECT logo_path FROM notary_qr_settings WHERE notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
                $result = ExecuteRows($sql, "DB");
                
                if (!empty($result) && !empty($result[0]['logo_path'])) {
                    $logoPath = $result[0]['logo_path'];
                }
            }
            
            // Map error correction level
            $errorCorrectionMap = [
                'L' => ErrorCorrectionLevel::Low,
                'M' => ErrorCorrectionLevel::Medium,
                'Q' => ErrorCorrectionLevel::Quartile,
                'H' => ErrorCorrectionLevel::High,
            ];
            
            $errorCorrectionLevel = $errorCorrectionMap[$errorCorrection] ?? ErrorCorrectionLevel::Medium;
            
            // Create RGB color objects
            $fgRgb = $this->hexToRgb($fgHex);
            $bgRgb = $this->hexToRgb($bgHex);
            $fgColor = new Color($fgRgb['r'], $fgRgb['g'], $fgRgb['b']);
            $bgColor = new Color($bgRgb['r'], $bgRgb['g'], $bgRgb['b']);
            
            // Build the QR code using named arguments
            $builder = new Builder(
                writer: new PngWriter(),
                writerOptions: [],
                validateResult: false,
                data: $text,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: $errorCorrectionLevel,
                size: $size,
                margin: 10,
                roundBlockSizeMode: $cornerRadius > 0 ? RoundBlockSizeMode::Margin : RoundBlockSizeMode::None,
                foregroundColor: $fgColor,
                backgroundColor: $bgColor
            );
            
            // Add logo if specified
            if ($useLogo && isset($logoPath) && file_exists($logoPath)) {
                $builder->setLogoPath($logoPath);
                $builder->setLogoResizeToWidth(intval($size * $logoSize / 100));
                $builder->setLogoResizeToHeight(intval($size * $logoSize / 100));
            }
            
            // Build the QR code
            $result = $builder->build();
            
            // Get the data URL
            $dataUri = $result->getDataUri();
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'qr_code' => $dataUri
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to generate QR code preview: ' . $e->getMessage()
            ];
        }
    }
    
        
    /**
     * Generate QR code for document verification
     * @param int $notarizedId Notarized document ID
     * @return array Response data with QR code path
     */
    public function generateDocumentQrCode($notarizedId) {
        try {
            // Get notarized document info
            $sql = "SELECT
                        nd.notarized_id,
                        nd.document_number,
                        nd.doc_keycode,
                        nd.notary_id,
                        u.user_id
                    FROM
                        notarized_documents nd
                    JOIN
                        users u ON nd.notary_id = u.user_id
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
            $notaryId = $document['notary_id'];
            
            // Get notary QR settings
            $settings = $this->getNotaryQrSettings($notaryId);
            
            if (!$settings['success']) {
                return $settings;
            }
            
            $qrSettings = $settings['data'];
            
            // Generate verification URL
            $verificationUrl = 'https://verify.enotary.boc.gov.ph/verify/' . 
                urlencode($document['document_number']) . '/' . 
                urlencode($document['doc_keycode']);
            
            // Create QR code directory
            $qrDir = 'uploads/qr_codes';
            if (!is_dir($qrDir)) {
                mkdir($qrDir, 0755, true);
            }
            
            // Generate QR code filename
            $qrFilename = 'qr_' . $document['document_number'] . '_' . uniqid() . '.png';
            $qrPath = $qrDir . '/' . $qrFilename;
            
            // Map error correction level
            $errorCorrectionMap = [
                'L' => ErrorCorrectionLevel::Low,
                'M' => ErrorCorrectionLevel::Medium,
                'Q' => ErrorCorrectionLevel::Quartile,
                'H' => ErrorCorrectionLevel::High,
            ];
            
            $errorCorrectionLevel = $errorCorrectionMap[$qrSettings['error_correction']] ?? ErrorCorrectionLevel::Medium;
            
            // Parse colors and create Color objects
            $fgRgb = $this->hexToRgb($qrSettings['foreground_color']);
            $bgRgb = $this->hexToRgb($qrSettings['background_color']);
            $fgColor = new Color($fgRgb['r'], $fgRgb['g'], $fgRgb['b']);
            $bgColor = new Color($bgRgb['r'], $bgRgb['g'], $bgRgb['b']);
                
            // Build the QR code using named arguments
            $builder = new Builder(
                writer: new PngWriter(),
                writerOptions: [],
                validateResult: false,
                data: $verificationUrl,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: $errorCorrectionLevel,
                size: $qrSettings['default_size'],
                margin: 10,
                roundBlockSizeMode: $qrSettings['corner_radius_percent'] > 0 ? 
                    RoundBlockSizeMode::Margin : RoundBlockSizeMode::None,
                foregroundColor: $fgColor,
                backgroundColor: $bgColor
            );
            
            // Add logo if specified
            if (!empty($qrSettings['logo_path']) && file_exists($qrSettings['logo_path'])) {
                $logoSize = intval($qrSettings['default_size'] * $qrSettings['logo_size_percent'] / 100);
                $builder->setLogoPath($qrSettings['logo_path']);
                $builder->setLogoResizeToWidth($logoSize);
                $builder->setLogoResizeToHeight($logoSize);
            }
            
            // Build the QR code
            $result = $builder->build();
            
            // Save the QR code to file
            $result->saveToFile($qrPath);
            
            // Update notarized document with QR code path
            $sql = "UPDATE notarized_documents
                    SET qr_code_path = " . QuotedValue($qrPath, DataType::STRING) . "
                    WHERE notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
            
            Execute($sql, "DB");
            
            // Update document verification table
            $this->updateDocumentVerification($notarizedId, $qrPath);
            
            // Return success response
            return [
                'success' => true,
                'message' => 'QR code generated successfully',
                'data' => [
                    'qr_code_path' => $qrPath
                ]
            ];
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
     * Update document verification entry
     * @param int $notarizedId Notarized document ID
     * @param string $qrCodePath QR code path
     * @return bool Success status
     */
    private function updateDocumentVerification($notarizedId, $qrCodePath) {
        try {
            // Get document verification info
            $sql = "SELECT
                        verification_id
                    FROM
                        document_verification
                    WHERE
                        notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                // Get notarized document info
                $sql = "SELECT
                            document_number,
                            doc_keycode
                        FROM
                            notarized_documents
                        WHERE
                            notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
                
                $docResult = ExecuteRows($sql, "DB");
                
                if (empty($docResult)) {
                    return false;
                }
                
                $document = $docResult[0];
                
                // Create verification URL
                $verificationUrl = 'https://verify.enotary.boc.gov.ph/verify/' . 
                    urlencode($document['document_number']) . '/' . 
                    urlencode($document['doc_keycode']);
                
                // Insert new verification record
                $sql = "INSERT INTO document_verification (
                            notarized_id,
                            document_number,
                            keycode,
                            verification_url,
                            qr_code_path,
                            is_active,
                            created_at
                        ) VALUES (
                            " . QuotedValue($notarizedId, DataType::NUMBER) . ",
                            " . QuotedValue($document['document_number'], DataType::STRING) . ",
                            " . QuotedValue($document['doc_keycode'], DataType::STRING) . ",
                            " . QuotedValue($verificationUrl, DataType::STRING) . ",
                            " . QuotedValue($qrCodePath, DataType::STRING) . ",
                            1,
                            NOW()
                        )";
                
                Execute($sql, "DB");
            } else {
                // Update existing verification record
                $verificationId = $result[0]['verification_id'];
                
                $sql = "UPDATE document_verification
                        SET qr_code_path = " . QuotedValue($qrCodePath, DataType::STRING) . "
                        WHERE verification_id = " . QuotedValue($verificationId, DataType::NUMBER);
                
                Execute($sql, "DB");
            }
            
            return true;
        } catch (\Exception $e) {
            LogError($e->getMessage());
            return false;
        }
    }
    
    /**
     * Verify document using document number and keycode
     * @param string $documentNumber Document number
     * @param string $keycode Verification keycode
     * @return array Response data with verification result
     */
    public function verifyDocument($documentNumber, $keycode) {
        try {
            // Get IP address for tracking
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            // Get document verification info
            $sql = "SELECT
                        dv.verification_id,
                        dv.notarized_id,
                        dv.document_number,
                        dv.keycode,
                        dv.is_active,
                        dv.expiry_date,
                        dv.failed_attempts,
                        dv.blocked_until
                    FROM
                        document_verification dv
                    WHERE
                        dv.document_number = " . QuotedValue($documentNumber, DataType::STRING) . "
                        AND dv.keycode = " . QuotedValue($keycode, DataType::STRING);
            
            $result = ExecuteRows($sql, "DB");
            Log("Verfify Document" . json_encode($result));
            // No verification found
            if (empty($result)) {
                // Record failed attempt
                $this->recordVerificationAttempt(null, $documentNumber, $keycode, $ipAddress, $userAgent, false, 'Invalid document number or keycode');
                
                return [
                    'success' => true,
                    'data' => [
                        'is_authentic' => false,
                        'message' => 'Document verification failed. Invalid document number or keycode.'
                    ]
                ];
            }
            
            $verification = $result[0];
            $verificationId = $verification['verification_id'];
            $notarizedId = $verification['notarized_id'];
            
            // Check if verification is blocked
            if (!empty($verification['blocked_until']) && new \DateTime($verification['blocked_until']) > new \DateTime()) {
                $this->recordVerificationAttempt($verificationId, $documentNumber, $keycode, $ipAddress, $userAgent, false, 'Verification blocked');
                
                return [
                    'success' => true,
                    'data' => [
                        'is_authentic' => false,
                        'message' => 'Too many failed verification attempts. Please try again later.'
                    ]
                ];
            }
            
            // Check if verification is active
            if (!$verification['is_active']) {
                $this->recordVerificationAttempt($verificationId, $documentNumber, $keycode, $ipAddress, $userAgent, false, 'Verification inactive');
                
                return [
                    'success' => true,
                    'data' => [
                        'is_authentic' => false,
                        'message' => 'Document verification is not active.'
                    ]
                ];
            }
            
            // Check if verification is expired
            if (!empty($verification['expiry_date']) && new \DateTime($verification['expiry_date']) < new \DateTime()) {
                $this->recordVerificationAttempt($verificationId, $documentNumber, $keycode, $ipAddress, $userAgent, false, 'Verification expired');
                
                return [
                    'success' => true,
                    'data' => [
                        'is_authentic' => false,
                        'message' => 'Document verification has expired.'
                    ]
                ];
            }
            
            // Get notarized document info
            $sql = "SELECT
                        nd.notarized_id,
                        nd.document_number,
                        nd.doc_keycode,
                        nd.notary_id,
                        nd.request_id,
                        nd.document_id,
                        nd.notarization_date,
                        nd.expires_at,
                        nd.revoked,
                        d.document_title,
                        u.first_name AS notary_first_name,
                        u.last_name AS notary_last_name,
                        u.notary_commission_number
                    FROM
                        notarized_documents nd
                    JOIN
                        documents d ON nd.document_id = d.document_id
                    JOIN
                        users u ON nd.notary_id = u.user_id
                    WHERE
                        nd.notarized_id = " . QuotedValue($notarizedId, DataType::NUMBER);
            
            $docResult = ExecuteRows($sql, "DB");
            
            if (empty($docResult)) {
                $this->recordVerificationAttempt($verificationId, $documentNumber, $keycode, $ipAddress, $userAgent, false, 'Notarized document not found');
                
                return [
                    'success' => true,
                    'data' => [
                        'is_authentic' => false,
                        'message' => 'Document verification failed. Document not found.'
                    ]
                ];
            }
            
            $document = $docResult[0];
            
            // Check if document is revoked
            if ($document['revoked']) {
                $this->recordVerificationAttempt($verificationId, $documentNumber, $keycode, $ipAddress, $userAgent, false, 'Document revoked');
                
                return [
                    'success' => true,
                    'data' => [
                        'is_authentic' => false,
                        'message' => 'This document has been revoked and is no longer valid.'
                    ]
                ];
            }
            
            // Successful verification
            $this->recordVerificationAttempt($verificationId, $documentNumber, $keycode, $ipAddress, $userAgent, true, '');
            
            // Reset failed attempts
            $sql = "UPDATE document_verification
                    SET failed_attempts = 0,
                        blocked_until = NULL
                    WHERE verification_id = " . QuotedValue($verificationId, DataType::NUMBER);
            
            Execute($sql, "DB");
            
            // Return verification info
            return [
                'success' => true,
                'data' => [
                    'is_authentic' => true,
                    'document_number' => $document['document_number'],
                    'document_title' => $document['document_title'],
                    'notarization_date' => $document['notarization_date'],
                    'notary_name' => $document['notary_first_name'] . ' ' . $document['notary_last_name'],
                    'notary_commission_number' => $document['notary_commission_number'],
                    'expires_at' => $document['expires_at']
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
     * @param int|null $verificationId Verification ID
     * @param string $documentNumber Document number
     * @param string $keycode Verification keycode
     * @param string $ipAddress IP address
     * @param string $userAgent User agent
     * @param bool $isSuccessful Whether the attempt was successful
     * @param string $failureReason Reason for failure
     * @return void
     */
    private function recordVerificationAttempt($verificationId, $documentNumber, $keycode, $ipAddress, $userAgent, $isSuccessful, $failureReason) {
        try {
            // Insert verification attempt record
            $sql = "INSERT INTO verification_attempts (
                    verification_id,
                    document_number,
                    keycode,
                    ip_address,
                    user_agent,
                    verification_date,
                    is_successful,
                    failure_reason
                ) VALUES (
                    " . ($verificationId ? QuotedValue($verificationId, DataType::NUMBER) : "NULL") . ",
                    " . QuotedValue($documentNumber, DataType::STRING) . ",
                    " . QuotedValue($keycode, DataType::STRING) . ",
                    " . QuotedValue($ipAddress, DataType::STRING) . ",
                    " . QuotedValue($userAgent, DataType::STRING) . ",
                    NOW(),
                    " . QuotedValue($isSuccessful, DataType::BOOLEAN) . ", 
                    " . QuotedValue($failureReason, DataType::STRING) . "
                )";
        
            Execute($sql, "DB");

            
            // If verification ID exists and attempt failed, update failed attempts count
            if ($verificationId && !$isSuccessful) {
                // Get current failed attempts
                $sql = "SELECT failed_attempts FROM document_verification WHERE verification_id = " . QuotedValue($verificationId, DataType::NUMBER);
                $result = ExecuteRows($sql, "DB");
                
                if (!empty($result)) {
                    $failedAttempts = (int)$result[0]['failed_attempts'] + 1;
                    
                    // Block after 5 failed attempts
                    $blockedUntil = null;
                    if ($failedAttempts >= 5) {
                        // Block for 30 minutes
                        $blockedUntil = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                    }
                    
                    // Update failed attempts and blocked until
                    $sql = "UPDATE document_verification
                            SET failed_attempts = " . QuotedValue($failedAttempts, DataType::NUMBER);
                    
                    if ($blockedUntil) {
                        $sql .= ", blocked_until = " . QuotedValue($blockedUntil, DataType::DATE);
                    }
                    
                    $sql .= " WHERE verification_id = " . QuotedValue($verificationId, DataType::NUMBER);
                    
                    Execute($sql, "DB");
                }
            }
        } catch (\Exception $e) {
            LogError($e->getMessage());
        }
    }
    
    /**
     * Convert hex color to RGB array
     * @param string $hex Hex color code
     * @return array RGB color array
     */
    private function hexToRgb($hex) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

}
