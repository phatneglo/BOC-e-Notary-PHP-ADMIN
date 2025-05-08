<?php
// app/api/services/NotificationService.php
namespace PHPMaker2024\eNotary;

class NotificationService {
    /**
     * Get notifications for a user
     * @param int $userId User ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getUserNotifications($userId, $params = []) {
        try {
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Filter parameters
            $isRead = isset($params['is_read']) ? $params['is_read'] === 'true' : null;
            $type = isset($params['type']) ? trim($params['type']) : null;
            
            // Build WHERE clause
            $where = "user_id = " . QuotedValue($userId, DataType::NUMBER);
            
            if ($isRead !== null) {
                $where .= " AND is_read = " . QuotedValue($isRead, DataType::BOOLEAN);
            }
            
            if ($type) {
                $where .= " AND type = " . QuotedValue($type, DataType::STRING);
            }
            
            // Query notifications
            $sql = "SELECT
                    id,
                    timestamp,
                    type,
                    target,
                    subject,
                    body,
                    link,
                    is_read,
                    created_at
                FROM
                    notifications
                WHERE
                    " . $where . "
                ORDER BY
                    timestamp DESC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $result = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total FROM notifications WHERE " . $where;
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
                'message' => 'Failed to get notifications: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Mark a notification as read
     * @param string $notificationId Notification ID
     * @return array Response data
     */
    public function markNotificationAsRead($notificationId) {
        try {
            // Validate notification ID
            if (empty($notificationId)) {
                return [
                    'success' => false,
                    'message' => 'Notification ID is required'
                ];
            }
            
            // Get current user ID
            $userId = Authentication::getUserId();
            
            // Check if notification exists and belongs to the user
            $sql = "SELECT id, user_id FROM notifications 
                    WHERE id = " . QuotedValue($notificationId, DataType::STRING);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Notification not found'
                ];
            }
            
            if ($result[0]['user_id'] != $userId) {
                return [
                    'success' => false,
                    'message' => 'Notification does not belong to the current user'
                ];
            }
            
            // Update notification as read
            $sql = "UPDATE notifications SET
                    is_read = true
                    WHERE id = " . QuotedValue($notificationId, DataType::STRING);
            
            Execute($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Notification marked as read'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Mark all notifications as read for a user
     * @param int $userId User ID
     * @return array Response data
     */
    public function markAllNotificationsAsRead($userId) {
        try {
            // Update all notifications as read
            $sql = "UPDATE notifications SET
                    is_read = true
                    WHERE user_id = " . QuotedValue($userId, DataType::NUMBER) . "
                    AND is_read = false";
            
            Execute($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'message' => 'All notifications marked as read'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to mark all notifications as read: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a notification
     * @param string $notificationId Notification ID
     * @return array Response data
     */
    public function deleteNotification($notificationId) {
        try {
            // Validate notification ID
            if (empty($notificationId)) {
                return [
                    'success' => false,
                    'message' => 'Notification ID is required'
                ];
            }
            
            // Get current user ID
            $userId = Authentication::getUserId();
            
            // Check if notification exists and belongs to the user
            $sql = "SELECT id, user_id FROM notifications 
                    WHERE id = " . QuotedValue($notificationId, DataType::STRING);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Notification not found'
                ];
            }
            
            if ($result[0]['user_id'] != $userId) {
                return [
                    'success' => false,
                    'message' => 'Notification does not belong to the current user'
                ];
            }
            
            // Delete notification
            $sql = "DELETE FROM notifications 
                    WHERE id = " . QuotedValue($notificationId, DataType::STRING);
            
            Execute($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Notification deleted successfully'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to delete notification: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create a notification for a user
     * @param int $userId User ID
     * @param string $type Notification type
     * @param string $target Target entity reference
     * @param string $subject Notification subject
     * @param string $body Notification body
     * @param string $link Action URL
     * @return bool Success status
     */
    public function createNotification($userId, $type, $target, $subject, $body, $link = '') {
        try {
            $id = uniqid('notif_', true);
            
            $sql = "INSERT INTO notifications (
                    id,
                    timestamp,
                    type,
                    target,
                    user_id,
                    subject,
                    body,
                    link,
                    is_read
                ) VALUES (
                    " . QuotedValue($id, DataType::STRING) . ",
                    CURRENT_TIMESTAMP,
                    " . QuotedValue($type, DataType::STRING) . ",
                    " . QuotedValue($target, DataType::STRING) . ",
                    " . QuotedValue($userId, DataType::NUMBER) . ",
                    " . QuotedValue($subject, DataType::STRING) . ",
                    " . QuotedValue($body, DataType::STRING) . ",
                    " . QuotedValue($link, DataType::STRING) . ",
                    FALSE
                )";
            
            Execute($sql, "DB");
            
            return true;
        } catch (\Exception $e) {
            LogError('Failed to create notification: ' . $e->getMessage());
            return false;
        }
    }
}
