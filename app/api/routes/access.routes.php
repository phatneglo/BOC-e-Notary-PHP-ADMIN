<?php
// app/api/routes/access.routes.php

namespace PHPMaker2024\eNotary;

/**
 * User Access API Routes
 */

// Import the service class
// Note: Service is already loaded by the main index file, but including here for clarity
// require_once __DIR__ . "/../services/AccessService.php";

// Get all users
$app->get("/access/users", function ($request, $response, $args) {
    $service = new UserAccessService();
    return $response->write($service->getUsers());
})->add($accessMiddleware);

// Get user access matrix
$app->get("/access/matrix[/{user_id}]", function ($request, $response, $args) {
    $service = new UserAccessService();
    $userId = isset($args['user_id']) ? $args['user_id'] : null;
    return $response->write($service->getUserAccessMatrix($userId));
})->add($accessMiddleware);

// Save user access
$app->post("/access", function ($request, $response, $args) {
    $service = new UserAccessService();
    $data = $request->getParsedBody();
    return $response->write($service->saveUserAccess($data));
})->add($accessMiddleware);