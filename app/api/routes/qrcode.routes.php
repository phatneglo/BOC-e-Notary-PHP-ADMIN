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
    $userId = $request->getAttribute('user_id');
    return $response->withJson($service->getNotaryQrSettings($userId));
})->add($jwtMiddleware);

/**
 * @api {post} /notary/qr-settings Update QR code settings
 * @apiName UpdateQrSettings
 * @apiGroup QrCode
 */
$app->post("/notary/qr-settings", function ($request, $response, $args) {
    $service = new QrCodeService();
    $userId = $request->getAttribute('user_id');
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
    $userId = $request->getAttribute('user_id');
    $customizationData = array_merge(
        $request->getParsedBody() ?? [],
        $request->getUploadedFiles() ?? []
    );
    return $response->withJson($service->customizeQrCodeAppearance($userId, $customizationData));
})->add($jwtMiddleware);

/**
 * @api {post} /notary/qr-logo Upload QR logo
 * @apiName UploadQrLogo
 * @apiGroup QrCode
 */
$app->post("/notary/qr-logo", function ($request, $response, $args) {
    $service = new QrCodeService();
    $userId = $request->getAttribute('user_id');
    $uploadedFiles = $request->getUploadedFiles();
    
    if (empty($uploadedFiles['logo'])) {
        return $response->withJson([
            'success' => false,
            'message' => 'No logo file provided'
        ]);
    }
    
    return $response->withJson($service->uploadQrLogo($userId, $uploadedFiles['logo']));
})->add($jwtMiddleware);

/**
 * @api {get} /notary/qr-logo Get QR logo
 * @apiName GetQrLogo
 * @apiGroup QrCode
 */
$app->get("/notary/qr-logo", function ($request, $response, $args) {
    $userId = $request->getAttribute('user_id');
    
    // Get notary QR settings
    $service = new QrCodeService();
    $settings = $service->getNotaryQrSettings($userId);
    
    if (!$settings['success'] || empty($settings['data']['logo_path'])) {
        return $response->withStatus(404)->withJson([
            'success' => false,
            'message' => 'Logo not found'
        ]);
    }
    
    $logoPath = $settings['data']['logo_path'];
    
    if (!file_exists($logoPath)) {
        return $response->withStatus(404)->withJson([
            'success' => false,
            'message' => 'Logo file not found'
        ]);
    }
    
    // Get file extension
    $extension = pathinfo($logoPath, PATHINFO_EXTENSION);
    $mimeType = 'image/png'; // Default
    
    if ($extension === 'jpg' || $extension === 'jpeg') {
        $mimeType = 'image/jpeg';
    }
    
    // Read file contents directly
    $imageData = file_get_contents($logoPath);
    
    // Set headers and output image directly
    return $response
        ->withHeader('Content-Type', $mimeType)
        ->withHeader('Content-Length', strlen($imageData))
        ->write($imageData);
})->add($jwtMiddleware);

/**
 * @api {get} /notary/qr-preview Generate QR preview
 * @apiName GenerateQrPreview
 * @apiGroup QrCode
 */
$app->get("/notary/qr-preview", function ($request, $response, $args) {
    $service = new QrCodeService();
    $userId = $request->getAttribute('user_id');
    
    // Get query parameters
    $params = $request->getQueryParams();
    $params['notary_id'] = $userId;
    
    $result = $service->generateQrPreview($params);
    
    if (!$result['success']) {
        return $response->withJson($result);
    }
    
    // If data URI is returned, convert to response
    if (isset($result['data']['qr_code']) && preg_match('/^data:image\/png;base64,(.*)$/', $result['data']['qr_code'], $matches)) {
        $imageData = base64_decode($matches[1]);
        
        // Set headers and output image directly
        return $response
            ->withHeader('Content-Type', 'image/png')
            ->withHeader('Content-Length', strlen($imageData))
            ->write($imageData);
    }
    
    return $response->withJson($result);
})->add($jwtMiddleware);

/**
 * @api {post} /notary/document/{notarized_id}/qrcode Generate document QR code
 * @apiName GenerateDocumentQrCode
 * @apiGroup QrCode
 */
$app->post("/notary/document/{notarized_id}/qrcode", function ($request, $response, $args) {
    $service = new QrCodeService();
    $notarizedId = (int)$args['notarized_id'];
    
    return $response->withJson($service->generateDocumentQrCode($notarizedId));
})->add($jwtMiddleware);
