<?php
    // app/api/services/PermissionsService.php

    namespace PHPMaker2024\eNotary;

    /**
     * PermissionsService class
     * Handles permissions-related operations
     */

    
    class PermissionService
    {
        public function getSystems()
        {
            $sql = "SELECT system_id, system_name, system_code FROM systems ORDER BY system_code";
            $result = ExecuteRows($sql, "DB");
            return json_encode(['success' => true, 'data' => $result]);
        }
    
        public function getUserLevels($system_code)
        {
            $sql = "SELECT ul.user_level_id, ul.name, ul.description 
                    FROM user_levels ul 
                    JOIN systems s ON ul.system_id = s.system_id 
                    WHERE s.system_code = '" . $system_code . "' 
                    ORDER BY ul.user_level_id";
            $result = ExecuteRows($sql, "DB");
            return json_encode(['success' => true, 'data' => $result]);
        }
    
        public function getPermissions($system_code, $user_level_id) 
        {
            // Get system tables
            $sql = "SELECT level_permissions FROM systems WHERE system_code = '" . $system_code . "'";
            $tablesJson = ExecuteScalar($sql, "DB");
            $tables = json_decode($tablesJson, true);

            // Get level permissions
            $sql = "SELECT table_name, permission 
                    FROM user_level_permissions 
                    WHERE table_name LIKE '{" . $system_code . "}%'
                    AND user_level_id = " . $user_level_id;
            $permissions = ExecuteRows($sql, "DB");

            // Format data
            $formattedTables = [];
            foreach ($tables as $table) {
                // Skip tables not allowed for update
                if ($table[3] === false) {
                    continue;
                }

                $tableName = "{" . $system_code . "}" . $table[0];
                $permission = 0;
                foreach ($permissions as $perm) {
                    if ($perm['table_name'] === $tableName) {
                        $permission = (int)$perm['permission'];
                        break;
                    }
                }
                $formattedTables[] = [
                    'table_name' => $tableName,
                    'caption' => $table[2],
                    'permission' => $permission,
                    'allowed_for_update' => $table[3]
                ];
            }

            return json_encode([
                'success' => true,
                'data' => [
                    'system_code' => $system_code,
                    'user_level_id' => $user_level_id,
                    'tables' => $formattedTables
                ]
            ]);
        }
    
        public function savePermissions($data)
        {
            try {
                $system_code = $data['system_code'];
                $permissions = $data['permissions'];
                
                foreach ($permissions as $perm) {
                    // Delete existing permission
                    $sql = "DELETE FROM user_level_permissions 
                            WHERE table_name = '" . $perm['table_name'] . "' 
                            AND user_level_id = " . $perm['user_level_id'];
                    Execute($sql, "DB");
                    
                    // Insert new permission if not 0
                    if ($perm['permission'] > 0) {
                        $sql = "INSERT INTO user_level_permissions (user_level_id, table_name, permission) 
                                VALUES (" . $perm['user_level_id'] . ", '" . $perm['table_name'] . "', " . $perm['permission'] . ")";
                        Execute($sql, "DB");
                    }
                }
                
                return json_encode(['success' => true]);
            } catch (\Exception $e) {
                return json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
    
