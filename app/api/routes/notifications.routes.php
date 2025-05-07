<?php
// app/api/routes/notifications.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /notifications Get user notifications
 * @apiName GetUserNotifications
 * @apiGroup Notifications
 */
$app->get("/notifications", function ($request, $response, $args) {
    $service = new NotificationService();
    $userId = $request->getAttribute('user_id');
    $params = $request->getQueryParams();
    return $response->withJson($service->getUserNotifications($userId, $params));
})->add($jwtMiddleware);
/**
 * @api {put} /notifications/read-all Mark all notifications as read
 * @apiName MarkAllNotificationsAsRead
 * @apiGroup Notifications
 */
$app->put("/notifications/read-all", function ($request, $response, $args) {
    $service = new NotificationService();
    $userId = $request->getAttribute('user_id');
    return $response->withJson($service->markAllNotificationsAsRead($userId));
})->add($jwtMiddleware);
/**
 * @api {put} /notifications/{notification_id} Mark notification as read
 * @apiName MarkNotificationAsRead
 * @apiGroup Notifications
 */
$app->put("/notifications/{notification_id}", function ($request, $response, $args) {
    $service = new NotificationService();
    $notificationId = isset($args['notification_id']) ? $args['notification_id'] : '';
    return $response->withJson($service->markNotificationAsRead($notificationId));
})->add($jwtMiddleware);



/**
 * @api {delete} /notifications/{notification_id} Delete notification
 * @apiName DeleteNotification
 * @apiGroup Notifications
 */
$app->delete("/notifications/{notification_id}", function ($request, $response, $args) {
    $service = new NotificationService();
    $notificationId = isset($args['notification_id']) ? $args['notification_id'] : '';
    return $response->withJson($service->deleteNotification($notificationId));
})->add($jwtMiddleware);
