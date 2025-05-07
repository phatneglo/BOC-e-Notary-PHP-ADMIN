<?php
// app/api/lib/Authentication.php
namespace PHPMaker2024\eNotary;

class Authentication {
    private static $userId = null;
    
    /**
     * Verify JWT token
     * @param string $token JWT token
     * @return object Decoded token
     * @throws \Exception If token is invalid
     */
    public static function verifyToken($token) {
        // In a real implementation, this would use a JWT library to verify the token
        // For development purposes, we'll just return a mock decoded token
        
        // Simulate token verification
        if ($token === 'invalid_token') {
            throw new \Exception('Invalid token');
        }
        
        // Return mock decoded token
        return (object)[
            'user_id' => 1,
            'username' => 'test_user',
            'user_level_id' => 1,
            'is_notary' => true
        ];
    }
    
    /**
     * Set current user ID
     * @param int $userId User ID
     */
    public static function setUserId($userId) {
        self::$userId = $userId;
    }
    
    /**
     * Get current user ID
     * @return int|null User ID or null if not set
     */
    public static function getUserId() {
        return self::$userId;
    }
    
    /**
     * Check if current user is an admin
     * @return bool True if admin, false otherwise
     */
    public static function isAdmin() {
        // In a real implementation, this would check the user's role
        // For development purposes, we'll assume user ID 1 is an admin
        return self::$userId === 1;
    }
    
    /**
     * Check if current user is a notary
     * @return bool True if notary, false otherwise
     */
    public static function isNotary() {
        // In a real implementation, this would check the user's role
        // For development purposes, we'll assume user ID 1 is a notary
        return self::$userId === 1;
    }
}
