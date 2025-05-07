<?php
// app/api/routes/auth.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {post} /auth/register Register a new user
 * @apiName RegisterUser
 * @apiGroup Authentication
 */
$app->post("/auth/register", function ($request, $response, $args) {
    $service = new AuthService();
    $userData = $request->getParsedBody();
    return $response->withJson($service->registerUser($userData));
});

/**
 * @api {post} /auth/complete-profile Complete user profile
 * @apiName CompleteUserProfile
 * @apiGroup Authentication
 */
$app->post("/auth/complete-profile", function ($request, $response, $args) {
    $service = new AuthService();
    $userId = $request->getAttribute('user_id');
    $profileData = array_merge(
        $request->getParsedBody() ?? [],
        $request->getUploadedFiles() ?? []
    );
    return $response->withJson($service->completeUserProfile($userId, $profileData));
})->add($jwtMiddleware);

/**
 * @api {post} /auth/login User login
 * @apiName Login
 * @apiGroup Authentication
 */
$app->post("/auth/login", function ($request, $response, $args) {
    $service = new AuthService();
    $credentials = $request->getParsedBody();
    return $response->withJson($service->login($credentials));
});

/**
 * @api {post} /auth/refresh Refresh access token
 * @apiName RefreshToken
 * @apiGroup Authentication
 */
$app->post("/auth/refresh", function ($request, $response, $args) {
    $service = new AuthService();
    $data = $request->getParsedBody();
    $refreshToken = $data['refresh_token'] ?? '';
    return $response->withJson($service->refreshToken($refreshToken));
});

/**
 * @api {post} /auth/logout Logout user
 * @apiName Logout
 * @apiGroup Authentication
 */
$app->post("/auth/logout", function ($request, $response, $args) {
    $service = new AuthService();
    $data = $request->getParsedBody();
    $refreshToken = $data['refresh_token'] ?? '';
    return $response->withJson($service->logout($refreshToken));
})->add($jwtMiddleware);

/**
 * @api {post} /auth/forgot-password Request password reset
 * @apiName ForgotPassword
 * @apiGroup Authentication
 */
$app->post("/auth/forgot-password", function ($request, $response, $args) {
    $service = new AuthService();
    $data = $request->getParsedBody();
    $email = $data['email'] ?? '';
    return $response->withJson($service->forgotPassword($email));
});

/**
 * @api {post} /auth/reset-password Reset password
 * @apiName ResetPassword
 * @apiGroup Authentication
 */
$app->post("/auth/reset-password", function ($request, $response, $args) {
    $service = new AuthService();
    $data = $request->getParsedBody();
    $token = $data['token'] ?? '';
    $newPassword = $data['new_password'] ?? '';
    $confirmPassword = $data['confirm_password'] ?? '';
    return $response->withJson($service->resetPassword($token, $newPassword, $confirmPassword));
});
