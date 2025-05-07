<?php
// app/api/routes/notification.routes.php
namespace PHPMaker2024\eNotary;

/**
 * Notification API Routes
 */

// Get recent notifications
$app->get("/notifications/recent", function ($request, $response) {
    $service = new NotificationService();
    $userId = $request->getAttribute('user_id') ?: CurrentUserID();
    return $response->withJson($service->getRecentNotifications($userId));
})->add($jwtMiddleware);

// Mark notification as read
$app->post("/notifications/mark-read", function ($request, $response) {
    $service = new NotificationService();
    $data = $request->getParsedBody();
    $userId = CurrentUserID();
    return $response->withJson($service->markAsRead($data, $userId));
});

// Get unread notification count
$app->get("/notifications/count", function ($request, $response) {
    $service = new NotificationService();
    $userId = CurrentUserID();
    return $response->withJson($service->getUnreadCount($userId));
});

// Mark all notifications as read
$app->post("/notifications/mark-all-read", function ($request, $response) {
    $service = new NotificationService();
    $userId = CurrentUserID();
    return $response->withJson($service->markAllAsRead($userId));
});

// Send test notifications
$app->get("/notifications/test", function ($request, $response) {
    $service = new NotificationService();
    $userId = CurrentUserID();
    $userLevel = CurrentUserLevel();
    $result = $service->sendTestNotifications($userId, $userLevel);
    
    if (!$result['success']) {
        return $response->withStatus(500)->withJson($result);
    }
    
    return $response->withJson($result);
});

/**
 * Send notification
 * 
 * Example payloads for different notification types:
 * 
 * 1. System-wide notification:
 * POST /notifications/send
 * {
 *     "type": "system",
 *     "subject": "System Maintenance",
 *     "body": "The system will be down for maintenance on Sunday at 2 AM.",
 *     "link": "/maintenance-schedule",
 *     "from_system": "ADS"  // Administrative System
 * }
 * 
 * 2. Personal notification:
 * {
 *     "type": "personal",
 *     "target": "123",  // user_id
 *     "subject": "Document Approved",
 *     "body": "Your document #ABC123 has been approved.",
 *     "link": "/documents/ABC123",
 *     "from_system": "DMS"  // Document Management System
 * }
 * 
 * 3. User Level notification:
 * {
 *     "type": "userLevel",
 *     "target": "1000",  // user_level_id
 *     "subject": "New Feature Available",
 *     "body": "Archive search feature is now available for administrators.",
 *     "link": "/features/archive-search",
 *     "from_system": "AMS"  // Archives Management System
 * }
 */
$app->post("/notifications/send", function ($request, $response) {
    $service = new NotificationService();
    $data = $request->getParsedBody();
    $result = $service->sendNotification($data);
    
    if (!$result['success']) {
        $statusCode = isset($result['message']) && strpos($result['message'], 'Missing required field') !== false ? 400 : 500;
        return $response->withStatus($statusCode)->withJson($result);
    }
    
    return $response->withJson($result);
});