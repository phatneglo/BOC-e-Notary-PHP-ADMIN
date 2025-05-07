<?php
// app/api/services/AccessService.php

namespace PHPMaker2024\eNotary;

/**
 * User Access Service
 * Handles user access management functionality
 */
class UserAccessService {
    /**
     * Get all users
     * @return string JSON response
     */
    public function getUsers() {
        $sql = "SELECT u.user_id, u.username, u.first_name, u.last_name, u.email, u.is_active, 
                       d.department_name 
                FROM users u 
                LEFT JOIN departments d ON u.department_id = d.department_id 
                ORDER BY u.username";
        $result = ExecuteRows($sql, "DB");
        return json_encode(['success' => true, 'data' => $result]);
    }

    /**
     * Get user access matrix
     * @param int|null $userId User ID
     * @return string JSON response
     */
    public function getUserAccessMatrix($userId = null) {
        // Get all systems
        $sql = "SELECT s.system_id, s.system_name, s.system_code, s.description
                FROM systems s 
                ORDER BY s.system_code";
        $systems = ExecuteRows($sql, "DB");

        // Get user level assignments if user ID provided
        $assignments = [];
        if ($userId) {
            $sql = "SELECT ula.system_id, ula.user_level_id 
                    FROM user_level_assignments ula 
                    WHERE ula.user_id = " . $userId;
            $assignments = ExecuteRows($sql, "DB");
        }

        // Get all user levels per system
        $sql = "SELECT ul.user_level_id, ul.name, ul.system_id 
                FROM user_levels ul 
                ORDER BY ul.user_level_id";
        $userLevels = ExecuteRows($sql, "DB");

        // Format data
        $formattedLevels = [];
        foreach ($systems as $system) {
            $systemLevels = array_filter($userLevels, function($level) use ($system) {
                return $level['system_id'] == $system['system_id'];
            });
            
            $formattedLevels[$system['system_code']] = [
                'system_name' => $system['system_name'],
                'system_description' => $system['description'],
                'system_code' => $system['system_code'],
                'levels' => array_values($systemLevels)
            ];
        }

        return json_encode([
            'success' => true,
            'data' => [
                'systems' => $systems,
                'user_levels' => $formattedLevels,
                'assignments' => $assignments
            ]
        ]);
    }

    /**
     * Save user access assignments
     * @param array $data Request data
     * @return string JSON response
     */
    public function saveUserAccess($data) {
        try {
            $userId = (int)$data['user_id']; // Force integer type
            $assignments = $data['assignments'];
            
            // Start transaction
            Execute("BEGIN", "DB");
            
            try {
                // Delete existing assignments
                $sql = "DELETE FROM user_level_assignments WHERE user_id = " . $userId;
                Execute($sql, "DB");
    
                // Insert new assignments and collect user_level_ids
                $userLevelIds = [];
                foreach ($assignments as $assignment) {
                    if (!empty($assignment['user_level_id'])) {
                        // Validate system and user level relationship
                        $checkSql = "SELECT 1 FROM user_levels 
                                   WHERE user_level_id = " . QuotedValue($assignment['user_level_id'], DataType::NUMBER) . 
                                   " AND system_id = " . QuotedValue($assignment['system_id'], DataType::NUMBER);
                        $valid = ExecuteScalar($checkSql, "DB");
    
                        if (!$valid) {
                            throw new \Exception("Invalid user level for system");
                        }
    
                        // Insert assignment
                        $sql = "INSERT INTO user_level_assignments
                                (user_id, system_id, user_level_id, assigned_by, created_at)
                                VALUES (" . 
                                $userId . ", " .
                                QuotedValue($assignment['system_id'], DataType::NUMBER) . ", " .
                                QuotedValue($assignment['user_level_id'], DataType::NUMBER) . ", " .
                                CurrentUserID() . ", CURRENT_TIMESTAMP)";
                        Execute($sql, "DB");
                        
                        $userLevelIds[] = $assignment['user_level_id'];
                    }
                }
    
                // Update users table
                $userLevelIds = array_unique($userLevelIds); // Ensure unique IDs

                $concatenatedLevels = implode(',', $userLevelIds);
               
                $sql = "UPDATE users SET user_level_id = " . 
                       QuotedValue($concatenatedLevels, DataType::STRING) . 
                       " WHERE user_id = " . $userId;
                Execute($sql, "DB");
    
                // Add audit log
                $sql = "INSERT INTO audit_logs (date_time, script, \"user\", action, \"table\", field, key_value, new_value) 
                       VALUES (CURRENT_TIMESTAMP, 'user_access.php', " . 
                       CurrentUserID() . ", 'U', 'user_level_assignments', 'user_levels', " .
                       QuotedValue($userId, DataType::NUMBER) . ", " .
                       QuotedValue($concatenatedLevels, DataType::STRING) . ")";
                Execute($sql, "DB");
    
                // Commit transaction
                Execute("COMMIT", "DB");
                return json_encode(['success' => true]);
    
            } catch (\Exception $e) {
                // Rollback on error
                Execute("ROLLBACK", "DB");
                throw $e;
            }
        } catch (\Exception $e) {
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}