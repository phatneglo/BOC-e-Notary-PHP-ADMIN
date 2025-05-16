<?php
// app/api/routes/permissions.routes.php
namespace PHPMaker2024\eNotary;

/**
 * Permissions API Routes
 */

$app->get("/permission/systems", function ($request, $response, $args) {
    $service = new PermissionService();
    return $response->write($service->getSystems());
})->add($jwtMiddleware);

$app->get("/permission/userlevels/{system_code}", function ($request, $response, $args) {
    $service = new PermissionService();
    return $response->write($service->getUserLevels($args['system_code']));
})->add($accessMiddleware);

$app->get("/permission/{system_code}/{user_level_id}", function ($request, $response, $args) {
    $service = new PermissionService();
    return $response->write($service->getPermissions($args['system_code'], $args['user_level_id']));
})->add($accessMiddleware);

$app->post("/permission", function ($request, $response, $args) {
    $service = new PermissionService();
    $data = $request->getParsedBody();
    return $response->write($service->savePermissions($data));
})->add($accessMiddleware);

