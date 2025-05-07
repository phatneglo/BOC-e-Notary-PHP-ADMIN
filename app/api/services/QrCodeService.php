<?php
// app/api/services/QrCodeService.php
namespace PHPMaker2024\eNotary;

class QrCodeService {
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
}
