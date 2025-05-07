<?php
// app/api/services/AuthService.php
namespace PHPMaker2024\eNotary;

class AuthService {
    /**
     * Register a new user account
     * @param array $userData User registration data
     * @return array Response data
     */
    public function registerUser($userData) {
        try {
            // Validate required fields
            $requiredFields = ['username', 'email', 'password', 'first_name', 'last_name', 'mobile_number'];
            foreach ($requiredFields as $field) {
                if (empty($userData[$field])) {
                    return [
                        'success' => false,
                        'message' => ucfirst($field) . ' is required',
                        'errors' => [$field => ['This field is required']]
                    ];
                }
            }
            
            // Check if privacy agreement is accepted
            if (empty($userData['privacy_agreement_accepted']) || $userData['privacy_agreement_accepted'] !== true) {
                return [
                    'success' => false,
                    'message' => 'You must accept the privacy agreement',
                    'errors' => ['privacy_agreement_accepted' => ['You must accept the privacy agreement']]
                ];
            }
            
            // Check if username already exists
            $usernameExists = ExecuteScalar(
                "SELECT COUNT(*) FROM users WHERE username = " . QuotedValue($userData['username'], DataType::STRING),
                "DB"
            );
            
            if ($usernameExists > 0) {
                return [
                    'success' => false,
                    'message' => 'Username already exists',
                    'errors' => ['username' => ['Username already exists']]
                ];
            }
            
            // Check if email already exists
            $emailExists = ExecuteScalar(
                "SELECT COUNT(*) FROM users WHERE email = " . QuotedValue($userData['email'], DataType::STRING),
                "DB"
            );
            
            if ($emailExists > 0) {
                return [
                    'success' => false,
                    'message' => 'Email already exists',
                    'errors' => ['email' => ['Email already exists']]
                ];
            }
            
            // Hash password
            $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Insert user record
                $sql = "INSERT INTO users (
                    username, 
                    email, 
                    password_hash, 
                    first_name, 
                    middle_name, 
                    last_name, 
                    mobile_number, 
                    is_active, 
                    created_at,
                    user_level_id
                ) VALUES (
                    " . QuotedValue($userData['username'], DataType::STRING) . ",
                    " . QuotedValue($userData['email'], DataType::STRING) . ",
                    " . QuotedValue($passwordHash, DataType::STRING) . ",
                    " . QuotedValue($userData['first_name'], DataType::STRING) . ",
                    " . QuotedValue($userData['middle_name'] ?? '', DataType::STRING) . ",
                    " . QuotedValue($userData['last_name'], DataType::STRING) . ",
                    " . QuotedValue($userData['mobile_number'], DataType::STRING) . ",
                    " . QuotedValue(true, DataType::BOOLEAN) . ",
                    CURRENT_TIMESTAMP,
                    " . QuotedValue(1, DataType::NUMBER) . " -- Default user level
                ) RETURNING user_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['user_id'])) {
                    throw new \Exception("Failed to create user account");
                }
                
                $userId = $result[0]['user_id'];
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'User registered successfully',
                    'data' => [
                        'user_id' => $userId,
                        'username' => $userData['username'],
                        'email' => $userData['email'],
                        'first_name' => $userData['first_name'],
                        'last_name' => $userData['last_name']
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
                'message' => 'Failed to register user: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Complete user profile with additional information
     * @param int $userId User ID
     * @param array $profileData Profile data including uploaded files
     * @return array Response data
     */
    public function completeUserProfile($userId, $profileData) {
        try {
            // Validate required fields
            $requiredFields = ['government_id_type', 'government_id_number', 'address'];
            foreach ($requiredFields as $field) {
                if (empty($profileData[$field])) {
                    return [
                        'success' => false,
                        'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required',
                        'errors' => [$field => ['This field is required']]
                    ];
                }
            }
            
            // Check if ID document is uploaded
            if (empty($profileData['id_document'])) {
                return [
                    'success' => false,
                    'message' => 'ID document is required',
                    'errors' => ['id_document' => ['ID document is required']]
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Save ID document file
                $idDocument = $profileData['id_document'];
                $idDocumentPath = 'uploads/id_documents/' . uniqid('id_doc_', true) . '.' . pathinfo($idDocument['name'], PATHINFO_EXTENSION);
                
                // Ensure directory exists
                $uploadDir = dirname($idDocumentPath);
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Move uploaded file
                move_uploaded_file($idDocument['tmp_name'], $idDocumentPath);
                
                // Process digital signature if provided
                $digitalSignaturePath = null;
                if (!empty($profileData['digital_signature'])) {
                    $signature = $profileData['digital_signature'];
                    $digitalSignaturePath = 'uploads/signatures/' . uniqid('sig_', true) . '.' . pathinfo($signature['name'], PATHINFO_EXTENSION);
                    
                    // Ensure directory exists
                    $uploadDir = dirname($digitalSignaturePath);
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    // Move uploaded file
                    move_uploaded_file($signature['tmp_name'], $digitalSignaturePath);
                }
                
                // Update user profile
                $sql = "UPDATE users SET
                    government_id_type = " . QuotedValue($profileData['government_id_type'], DataType::STRING) . ",
                    government_id_number = " . QuotedValue($profileData['government_id_number'], DataType::STRING) . ",
                    address = " . QuotedValue($profileData['address'], DataType::STRING) . ",
                    id_document_path = " . QuotedValue($idDocumentPath, DataType::STRING);
                
                // Add digital signature if provided
                if ($digitalSignaturePath) {
                    $sql .= ", digital_signature = " . QuotedValue($digitalSignaturePath, DataType::STRING);
                }
                
                $sql .= " WHERE user_id = " . QuotedValue($userId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Profile completed successfully',
                    'data' => [
                        'user_id' => $userId,
                        'profile_status' => 'complete'
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
                'message' => 'Failed to complete profile: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Authenticate user and generate tokens
     * @param array $credentials User credentials
     * @return array Response data
     */
    public function login($credentials) {
        try {
            // Validate required fields
            if (empty($credentials['username']) || empty($credentials['password'])) {
                return [
                    'success' => false,
                    'message' => 'Username and password are required',
                    'errors' => [
                        'username' => empty($credentials['username']) ? ['Username is required'] : [],
                        'password' => empty($credentials['password']) ? ['Password is required'] : []
                    ]
                ];
            }
            
            // Get user by username
            $sql = "SELECT user_id, username, email, password_hash, first_name, last_name, user_level_id, is_notary 
                    FROM users 
                    WHERE username = " . QuotedValue($credentials['username'], DataType::STRING) . "
                    AND is_active = true";
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Invalid username or password',
                    'errors' => ['username' => ['Invalid username or password']]
                ];
            }
            
            $user = $result[0];
            
            // Verify password
            if (!password_verify($credentials['password'], $user['password_hash'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid username or password',
                    'errors' => ['password' => ['Invalid username or password']]
                ];
            }
            
            // Generate tokens
            $accessToken = CreateJwt($user);
            $refreshToken = $this->generateRefreshToken($user['user_id']);
            
            // Update last login timestamp
            $sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = " . QuotedValue($user['user_id'], DataType::NUMBER);
            Execute($sql, "DB");
            
            // Return success response with tokens
            return [
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'expires_in' => 3600, // Token expires in 1 hour
                    'user' => [
                        'user_id' => $user['user_id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'user_level_id' => $user['user_level_id'],
                        'is_notary' => (bool)$user['is_notary']
                    ]
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate a new access token using a refresh token
     * @param string $refreshToken Refresh token
     * @return array Response data
     */
    public function refreshToken($refreshToken) {
        try {
            if (empty($refreshToken)) {
                return [
                    'success' => false,
                    'message' => 'Refresh token is required',
                    'errors' => ['refresh_token' => ['Refresh token is required']]
                ];
            }
            
            // Verify refresh token
            $sql = "SELECT rt.user_id, rt.token, rt.expires_at, u.username, u.email, u.first_name, u.last_name, u.user_level_id, u.is_notary 
                    FROM refresh_tokens rt
                    JOIN users u ON rt.user_id = u.user_id
                    WHERE rt.token = " . QuotedValue($refreshToken, DataType::STRING) . "
                    AND rt.expires_at > CURRENT_TIMESTAMP";
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Invalid or expired refresh token',
                    'errors' => ['refresh_token' => ['Invalid or expired refresh token']]
                ];
            }
            
            $tokenData = $result[0];
            
            // Generate new tokens
            $user = [
                'user_id' => $tokenData['user_id'],
                'username' => $tokenData['username'],
                'email' => $tokenData['email'],
                'first_name' => $tokenData['first_name'],
                'last_name' => $tokenData['last_name'],
                'user_level_id' => $tokenData['user_level_id'],
                'is_notary' => (bool)$tokenData['is_notary']
            ];
            
            $accessToken = $this->generateAccessToken($user);
            $newRefreshToken = $this->generateRefreshToken($user['user_id']);
            
            // Delete the old refresh token
            $sql = "DELETE FROM refresh_tokens WHERE token = " . QuotedValue($refreshToken, DataType::STRING);
            Execute($sql, "DB");
            
            // Return success response with new tokens
            return [
                'success' => true,
                'message' => 'Token refreshed',
                'data' => [
                    'access_token' => $accessToken,
                    'refresh_token' => $newRefreshToken,
                    'expires_in' => 3600 // Token expires in 1 hour
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to refresh token: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Logout user by invalidating refresh token
     * @param string $refreshToken Refresh token to invalidate
     * @return array Response data
     */
    public function logout($refreshToken) {
        try {
            if (empty($refreshToken)) {
                return [
                    'success' => false,
                    'message' => 'Refresh token is required',
                    'errors' => ['refresh_token' => ['Refresh token is required']]
                ];
            }
            
            // Delete the refresh token
            $sql = "DELETE FROM refresh_tokens WHERE token = " . QuotedValue($refreshToken, DataType::STRING);
            Execute($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Logout successful'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Logout failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Request a password reset link
     * @param string $email User email
     * @return array Response data
     */
    public function forgotPassword($email) {
        try {
            if (empty($email)) {
                return [
                    'success' => false,
                    'message' => 'Email is required',
                    'errors' => ['email' => ['Email is required']]
                ];
            }
            
            // Check if email exists
            $sql = "SELECT user_id, username, email, first_name, last_name FROM users WHERE email = " . QuotedValue($email, DataType::STRING);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                // For security reasons, always return success even if email doesn't exist
                return [
                    'success' => true,
                    'message' => 'Password reset instructions sent to your email'
                ];
            }
            
            $user = $result[0];
            
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store reset token
            $sql = "INSERT INTO password_reset_tokens (user_id, token, expires_at, created_at)
                    VALUES (
                        " . QuotedValue($user['user_id'], DataType::NUMBER) . ",
                        " . QuotedValue($token, DataType::STRING) . ",
                        " . QuotedValue($expiresAt, DataType::DATE) . ",
                        CURRENT_TIMESTAMP
                    )";
            
            Execute($sql, "DB");
            
            // Send reset email (this would be implemented in a real system)
            // Here we'll just log it for demonstration
            Log('Password reset requested for user: ' . $user['username'] . ', Token: ' . $token);
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Password reset instructions sent to your email'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to request password reset: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Reset password using token
     * @param string $token Reset token
     * @param string $newPassword New password
     * @param string $confirmPassword Confirm password
     * @return array Response data
     */
    public function resetPassword($token, $newPassword, $confirmPassword) {
        try {
            if (empty($token)) {
                return [
                    'success' => false,
                    'message' => 'Token is required',
                    'errors' => ['token' => ['Token is required']]
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
            
            // Verify token
            $sql = "SELECT user_id, token, expires_at FROM password_reset_tokens 
                    WHERE token = " . QuotedValue($token, DataType::STRING) . "
                    AND expires_at > CURRENT_TIMESTAMP";
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Invalid or expired token',
                    'errors' => ['token' => ['Invalid or expired token']]
                ];
            }
            
            $resetToken = $result[0];
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Update user password
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET 
                        password_hash = " . QuotedValue($passwordHash, DataType::STRING) . "
                        WHERE user_id = " . QuotedValue($resetToken['user_id'], DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Delete used token
                $sql = "DELETE FROM password_reset_tokens WHERE token = " . QuotedValue($token, DataType::STRING);
                Execute($sql, "DB");
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Password reset successfully'
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
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate JWT access token
     * @param array $user User data
     * @return string JWT token
     */
    private function generateAccessToken($user) {
        $secretKey = Config("JWT.SECRET_KEY");
        $issuedAt = time();
        $expiresAt = $issuedAt + 3600; // Expires in 1 hour
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expiresAt,
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'user_level_id' => $user['user_level_id'],
            'is_notary' => (bool)$user['is_notary']
        ];
        
        // Use JWT library to generate token (implementation depends on the JWT library used)
        // For demonstration, we'll return a placeholder
        
        return 'jwt_token_placeholder.' . base64_encode(json_encode($payload));
    }
    
    /**
     * Generate refresh token and store in database
     * @param int $userId User ID
     * @return string Refresh token
     */
    private function generateRefreshToken($userId) {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+14 days'));
        
        // Delete any existing refresh tokens for the user
        $sql = "DELETE FROM refresh_tokens WHERE user_id = " . QuotedValue($userId, DataType::NUMBER);
        Execute($sql, "DB");
        
        // Store new refresh token
        $sql = "INSERT INTO refresh_tokens (user_id, token, expires_at, created_at)
                VALUES (
                    " . QuotedValue($userId, DataType::NUMBER) . ",
                    " . QuotedValue($token, DataType::STRING) . ",
                    " . QuotedValue($expiresAt, DataType::DATE) . ",
                    CURRENT_TIMESTAMP
                )";
        
        Execute($sql, "DB");
        
        return $token;
    }
}
