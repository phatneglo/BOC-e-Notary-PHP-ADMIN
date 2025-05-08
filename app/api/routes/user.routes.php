<?php
// app/api/routes/user.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /users/profile Get user profile
 * @apiName GetUserProfile
 * @apiGroup User
 */
$app->get("/users/profile", function ($request, $response, $args) {
    $service = new UserService();
// For debugging - display all attributes
    $allAttributes = $request->getAttributes();
    Log("All attributes: " . json_encode($allAttributes));

    $userId = $request->getAttribute('user_id');
    return $response->withJson($service->getProfile($userId));
})->add($jwtMiddleware);

/**
 * @api {put} /users/profile Update user profile
 * @apiName UpdateUserProfile
 * @apiGroup User
 */
$app->put("/users/profile", function ($request, $response, $args) {
    $service = new UserService();
    $userId = $request->getAttribute('user_id');
    $profileData = $request->getParsedBody();
    return $response->withJson($service->updateProfile($userId, $profileData));
})->add($jwtMiddleware);

/**
 * @api {post} /users/signature Update digital signature
 * @apiName UpdateDigitalSignature
 * @apiGroup User
 */
$app->post("/users/signature", function ($request, $response, $args) {
    $service = new UserService();
    $userId = $request->getAttribute('user_id');
    $files = $request->getUploadedFiles();
    $signatureFile = $files['signature'] ?? null;
    
    return $response->withJson($service->updateDigitalSignature($userId, $signatureFile));
})->add($jwtMiddleware);

/**
 * @api {put} /users/password Change password
 * @apiName ChangePassword
 * @apiGroup User
 */
$app->put("/users/password", function ($request, $response, $args) {
    $service = new UserService();
    $userId = $request->getAttribute('user_id');
    $data = $request->getParsedBody();
    
    $currentPassword = $data['current_password'] ?? '';
    $newPassword = $data['new_password'] ?? '';
    $confirmPassword = $data['confirm_password'] ?? '';
    
    return $response->withJson($service->changePassword($userId, $currentPassword, $newPassword, $confirmPassword));
})->add($jwtMiddleware);

/**
 * @api {get} /users/activity Get user activity
 * @apiName GetUserActivity
 * @apiGroup User
 */
$app->get("/users/activity", function ($request, $response, $args) {
    $service = new UserService();
    $userId = $request->getAttribute('user_id');
    $params = $request->getQueryParams();
    
    return $response->withJson($service->getUserActivity($userId, $params));
})->add($jwtMiddleware);

/**
 * @api {get} /users/dashboard Get user dashboard statistics
 * @apiName GetUserDashboard
 * @apiGroup User
 */
$app->get("/users/dashboard", function ($request, $response, $args) {
    $service = new UserService();
    $userId = $request->getAttribute('user_id');
    
    return $response->withJson($service->getDashboardStats($userId));
})->add($jwtMiddleware);
