<?php
// app/api/routes/system.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /system/status Get system status
 * @apiName GetSystemStatus
 * @apiGroup System
 */
$app->get("/system/status", function ($request, $response, $args) {
    $service = new SystemService();
    return $response->withJson($service->getSystemStatus());
});

/**
 * @api {get} /admin/system/status-history Get system status history
 * @apiName GetSystemStatusHistory
 * @apiGroup System
 */
$app->get("/admin/system/status-history", function ($request, $response, $args) {
    $service = new SystemService();
    $params = $request->getQueryParams();
    return $response->withJson($service->getSystemStatusHistory($params));
})->add($jwtMiddleware);

/**
 * @api {get} /departments Get departments
 * @apiName GetDepartments
 * @apiGroup System
 */
$app->get("/departments", function ($request, $response, $args) {
    $service = new SystemService();
    return $response->withJson($service->listDepartments());
})->add($jwtMiddleware);

/**
 * @api {get} /user-levels Get user levels
 * @apiName GetUserLevels
 * @apiGroup System
 */
$app->get("/user-levels", function ($request, $response, $args) {
    $service = new SystemService();
    return $response->withJson($service->getUserLevelInfo());
})->add($jwtMiddleware);

/**
 * @api {get} /allowed-directories Get allowed directories
 * @apiName GetAllowedDirectories
 * @apiGroup System
 */
$app->get("/allowed-directories", function ($request, $response, $args) {
    $service = new SystemService();
    return $response->withJson($service->listAllowedDirectories());
})->add($jwtMiddleware);
