<?php
// app/api/routes/qrcode.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /notary/qr-settings Get notary QR settings
 * @apiName GetNotaryQrSettings
 * @apiGroup QrCode
 */
$app->get("/notary/qr-settings", function ($request, $response, $args) {
    $service = new QrCodeService();
    $userId = Authentication::getUserId();
    return $response->withJson($service->getNotaryQrSettings($userId));
})->add($jwtMiddleware);

/**
 * @api {post} /notary/qr-settings Update QR code settings
 * @apiName UpdateQrSettings
 * @apiGroup QrCode
 */
$app->post("/notary/qr-settings", function ($request, $response, $args) {
    $service = new QrCodeService();
    $userId = Authentication::getUserId();
    $settingsData = $request->getParsedBody();
    return $response->withJson($service->updateNotaryQrSettings($userId, $settingsData));
})->add($jwtMiddleware);

/**
 * @api {post} /notary/qr-appearance Customize QR appearance
 * @apiName CustomizeQrAppearance
 * @apiGroup QrCode
 */
$app->post("/notary/qr-appearance", function ($request, $response, $args) {
    $service = new QrCodeService();
    $userId = Authentication::getUserId();
    $customizationData = array_merge(
        $request->getParsedBody() ?? [],
        $request->getUploadedFiles() ?? []
    );
    return $response->withJson($service->customizeQrCodeAppearance($userId, $customizationData));
})->add($jwtMiddleware);
