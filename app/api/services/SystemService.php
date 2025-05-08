<?php
// app/api/services/SystemService.php
namespace PHPMaker2024\eNotary;

class SystemService {
    /**
     * Get current system status and performance metrics
     * @return array Response data
     */
    public function getSystemStatus() {
        try {
            // Get current system status
            $sql = "SELECT
                    status_id,
                    status,
                    message,
                    uptime,
                    active_users,
                    queue_size,
                    average_processing_time
                FROM
                    system_status
                ORDER BY
                    created_at DESC
                LIMIT 1";
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                // If no status record exists, create default
                $statusData = [
                    'status' => 'operational',
                    'message' => 'System is operational',
                    'uptime' => 0,
                    'active_users' => 0,
                    'queue_size' => 0,
                    'average_processing_time' => 0
                ];
            } else {
                $statusData = $result[0];
            }
            
            // Add server time
            $statusData['server_time'] = date('Y-m-d H:i:s');
            
            // Count active users (logged in within the last 30 minutes)
            $sql = "SELECT COUNT(*) AS active_users 
                   FROM users 
                   WHERE last_login > CURRENT_TIMESTAMP - INTERVAL '30 minutes'";
            
            $usersResult = ExecuteRows($sql, "DB");
            $statusData['active_users'] = $usersResult[0]['active_users'] ?? 0;
            
            // Count queue size
            $sql = "SELECT COUNT(*) AS queue_size FROM notarization_queue WHERE status = 'queued'";
            $queueResult = ExecuteRows($sql, "DB");
            $statusData['queue_size'] = $queueResult[0]['queue_size'] ?? 0;
            
            // Calculate average processing time (last 24 hours)
            $sql = "SELECT
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
                    r.status IN ('notarized', 'rejected')
                    AND r.assigned_at IS NOT NULL
                    AND (
                        r.notarized_at > CURRENT_TIMESTAMP - INTERVAL '24 hours'
                        OR r.rejected_at > CURRENT_TIMESTAMP - INTERVAL '24 hours'
                    )";
            
            $avgTimeResult = ExecuteRows($sql, "DB");
            $statusData['average_processing_time'] = $avgTimeResult[0]['avg_processing_time'] ? round($avgTimeResult[0]['avg_processing_time'], 1) : 0;
            
            // Return success response
            return [
                'success' => true,
                'data' => $statusData
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get system status: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get historical system status data
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getSystemStatusHistory($params = []) {
        try {
            // Verify user is an admin
            $userId = Authentication::getUserId();
            $sql = "SELECT user_level_id FROM users WHERE user_id = " . QuotedValue($userId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || $result[0]['user_level_id'] != 1) { // Assuming 1 is admin level
                return [
                    'success' => false,
                    'message' => 'Unauthorized access'
                ];
            }
            
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Filter parameters
            $startDate = !empty($params['start_date']) && strtotime($params['start_date']) 
                ? date('Y-m-d', strtotime($params['start_date'])) 
                : date('Y-m-d', strtotime('-7 days'));
            
            $endDate = !empty($params['end_date']) && strtotime($params['end_date']) 
                ? date('Y-m-d', strtotime($params['end_date'])) 
                : date('Y-m-d');
            
            // Build WHERE clause
            $where = "created_at BETWEEN " . QuotedValue($startDate . ' 00:00:00', DataType::DATE) . " 
                     AND " . QuotedValue($endDate . ' 23:59:59', DataType::DATE);
            
            // Query status history
            $sql = "SELECT
                    status_id,
                    status,
                    message,
                    uptime,
                    active_users,
                    queue_size,
                    average_processing_time,
                    created_at
                FROM
                    system_status
                WHERE
                    " . $where . "
                ORDER BY
                    created_at DESC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $result = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total FROM system_status WHERE " . $where;
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
                'message' => 'Failed to get system status history: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get all departments
     * @return array Response data
     */
    public function listDepartments() {
        try {
            $sql = "SELECT
                    department_id,
                    department_name,
                    description
                FROM
                    departments
                ORDER BY
                    department_name ASC";
            
            $result = ExecuteRows($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get departments: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get user levels information
     * @return array Response data
     */
    public function getUserLevelInfo() {
        try {
            $sql = "SELECT
                    ul.user_level_id,
                    ul.name,
                    ul.description,
                    ul.system_id,
                    s.system_name
                FROM
                    user_levels ul
                JOIN
                    systems s ON ul.system_id = s.system_id
                ORDER BY
                    ul.user_level_id ASC";
            
            $result = ExecuteRows($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get user levels: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get list of allowed directories
     * @return array Response data
     */
    public function listAllowedDirectories() {
        try {
            // In a real implementation, this would query the database or configuration
            // For now, we'll just return a static list
            $allowedDirectories = [
                'uploads/attachments',
                'uploads/qrcodes',
                'uploads/pdfs',
                'uploads/templates',
                'uploads/signatures',
                'uploads/seals',
                'temp'
            ];
            
            // Return success response
            return [
                'success' => true,
                'data' => $allowedDirectories
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get allowed directories: ' . $e->getMessage()
            ];
        }
    }
}
