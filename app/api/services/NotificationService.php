<?php
// app/api/services/NotificationService.php
namespace PHPMaker2024\eNotary;
@session_start();

/**
 * Notification Service Class
 * Defines notification API endpoints functionality
 */
class NotificationService {
    /**
     * Get recent notifications for the current user
     * @param int $userId User ID
     * @return array Notification data
     */
    public function getRecentNotifications($userId) {
        $notifications = NotificationManager::getUserNotifications($userId);
        
        return [
            'success' => true,
            'data' => $notifications,
            'unread_count' => NotificationManager::getUnreadCount($userId)
        ];
    }

    /**
     * Mark a notification as read
     * @param array $data Request data
     * @param int $userId User ID
     * @return array Response
     */
    public function markAsRead($data, $userId) {
        $notificationId = $data['id'] ?? null;
        
        if (!$notificationId) {
            return [
                'success' => false,
                'message' => 'Notification ID required'
            ];
        }

        $success = NotificationManager::markAsRead($notificationId, $userId);
        
        return [
            'success' => $success
        ];
    }

    /**
     * Get unread notification count
     * @param int $userId User ID
     * @return array Response
     */
    public function getUnreadCount($userId) {
        $count = NotificationManager::getUnreadCount($userId);
        
        return [
            'success' => true,
            'count' => $count
        ];
    }

    /**
     * Mark all notifications as read
     * @param int $userId User ID
     * @return array Response
     */
    public function markAllAsRead($userId) {
        $success = NotificationManager::markAllAsRead($userId);
        
        return [
            'success' => $success
        ];
    }

    /**
     * Send test notifications
     * @param int $userId User ID
     * @param string $userLevel User level
     * @return array Response
     */
    public function sendTestNotifications($userId, $userLevel) {
        try {
            // Test System Notification
            $systemResult = NotificationManager::sendSystem(
                "🔧 Test System Notification",
                "This is a test system-wide notification sent at " . date('Y-m-d H:i:s'),
                "/test-system",
                "UAC"
            );

            // Test Personal Notification
            $personalResult = NotificationManager::sendToUser(
                $userId,
                "👤 Test Personal Notification",
                "This is a test personal notification for user #" . $userId,
                "/test-personal",
                "UAC"
            );

            // Test User Level Notification
            $userLevelResult = NotificationManager::sendToUserLevel(
                $userLevel,
                "👥 Test User Level Notification",
                "This is a test notification for user level #" . $userLevel,
                "/test-user-level",
                "UAC"
            );

            return [
                'success' => true,
                'results' => [
                    'system' => $systemResult,
                    'personal' => $personalResult,
                    'userLevel' => $userLevelResult
                ],
                'user_info' => [
                    'user_id' => $userId,
                    'user_level' => $userLevel
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error sending test notifications',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send notification
     * @param array $data Request data
     * @return array Response
     */
    public function sendNotification($data) {
        try {
            // Validate required fields
            $requiredFields = ['type', 'subject', 'body'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    return [
                        'success' => false,
                        'message' => "Missing required field: {$field}"
                    ];
                }
            }

            // Validate notification type
            $validTypes = ['system', 'personal', 'userLevel'];
            if (!in_array($data['type'], $validTypes)) {
                return [
                    'success' => false,
                    'message' => "Invalid notification type. Must be one of: " . implode(', ', $validTypes)
                ];
            }

            // If not system notification, target is required
            if ($data['type'] !== 'system' && empty($data['target'])) {
                return [
                    'success' => false,
                    'message' => "Target is required for {$data['type']} notifications"
                ];
            }

            // Send notification based on type
            $result = false;
            switch ($data['type']) {
                case 'system':
                    $result = NotificationManager::sendSystem(
                        $data['subject'],
                        $data['body'],
                        $data['link'] ?? null,
                        $data['from_system'] ?? null
                    );
                    break;

                case 'personal':
                    $result = NotificationManager::sendToUser(
                        $data['target'],
                        $data['subject'],
                        $data['body'],
                        $data['link'] ?? null,
                        $data['from_system'] ?? null
                    );
                    break;

                case 'userLevel':
                    $result = NotificationManager::sendToUserLevel(
                        $data['target'],
                        $data['subject'],
                        $data['body'],
                        $data['link'] ?? null,
                        $data['from_system'] ?? null
                    );
                    break;
            }

            return [
                'success' => $result,
                'message' => $result ? 'Notification sent successfully' : 'Failed to send notification'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error sending notification',
                'error' => $e->getMessage()
            ];
        }
    }
}