<?php
// app/api/routes/consolidated-qrcode-verify.routes.php
namespace PHPMaker2024\eNotary;

/*
 * DOCUMENT VERIFICATION ROUTES
 */

/**
 * @api {post} /verify Verify document by document number and keycode
 * @apiName VerifyDocument
 * @apiGroup Verification
 */
$app->post("/verify", function ($request, $response, $args) {
    $service = new VerificationService();
    $verificationData = $request->getParsedBody();
    return $response->withJson($service->verifyDocument($verificationData));
});

/**
 * @api {get} /verify/qr/{verification_id} Verification by QR code
 * @apiName VerificationByQRCode
 * @apiGroup Verification
 */
$app->get("/verify/qr/{verification_id}", function ($request, $response, $args) {
    $service = new VerificationService();
    $verificationId = isset($args['verification_id']) ? $args['verification_id'] : '';
    return $response->withJson($service->getVerificationByQrCode($verificationId));
});

/**
 * @api {post} /verify/record Record verification attempt
 * @apiName RecordVerificationAttempt
 * @apiGroup Verification
 */
$app->post("/verify/record", function ($request, $response, $args) {
    $service = new VerificationService();
    $attemptData = $request->getParsedBody();
    return $response->withJson($service->recordVerificationAttempt($attemptData));
});

/**
 * @api {post} /verify/qr/test Test QR code scan
 * @apiName TestQRCodeScan
 * @apiGroup Verification
 */
$app->post("/verify/qr/test", function ($request, $response, $args) {
    $service = new VerificationService();
    $qrData = $request->getUploadedFiles();
    return $response->withJson($service->testQrCodeScan($qrData));
})->add($jwtMiddleware);

/*
 * QR CODE SETTINGS ROUTES
 */

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
    
    // Stream the file
    $file = fopen($logoPath, 'rb');
    
    return $response
        ->withHeader('Content-Type', $mimeType)
        ->withBody(new \Slim\Psr7\Stream($file));
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
        
        return $response
            ->withHeader('Content-Type', 'image/png')
            ->withBody(new \Slim\Psr7\Stream(fopen('php://temp', 'r+')))
            ->getBody()->write($imageData);
    }
    
    return $response->withJson($result);
})->add($jwtMiddleware);

/*
 * DOCUMENT QR CODE ROUTES
 */

/**
 * @api {post} /notarized/{notarized_id}/qrcode Generate document QR code
 * @apiName GenerateDocumentQrCode
 * @apiGroup QrCode
 */
$app->post("/notarized/{notarized_id}/qrcode", function ($request, $response, $args) {
    $service = new QrCodeService();
    $notarizedId = (int)$args['notarized_id'];
    
    return $response->withJson($service->generateDocumentQrCode($notarizedId));
})->add($jwtMiddleware);

/**
 * @api {get} /notarized/{notarized_id}/qrcode Get notarized document QR code
 * @apiName GetNotarizedDocumentQrCode
 * @apiGroup Notarized
 */
$app->get("/notarized/{notarized_id}/qrcode", function ($request, $response, $args) {
    $service = new NotarizedDocumentService();
    $notarizedId = isset($args['notarized_id']) ? (int)$args['notarized_id'] : 0;
    
    $result = $service->getQrCode($notarizedId);
    
    if (!$result['success']) {
        return $response->withJson($result);
    }
    
    $fileData = $result['data'];
    
    // Return image response
    $fileStream = fopen($fileData['file_path'], 'rb');
    return $response
        ->withHeader('Content-Type', $fileData['mime_type'])
        ->withHeader('Content-Disposition', 'inline; filename="' . $fileData['file_name'] . '"')
        ->withHeader('Content-Length', $fileData['file_size'])
        ->withBody(new \Slim\Http\Stream($fileStream));
})->add($jwtMiddleware);

/**
 * @api {get} /notarized/{notarized_id}/qr-stats Get QR statistics
 * @apiName GetQRStatistics
 * @apiGroup Notarized
 */
$app->get("/notarized/{notarized_id}/qr-stats", function ($request, $response, $args) {
    $service = new NotarizedDocumentService();
    $notarizedId = isset($args['notarized_id']) ? (int)$args['notarized_id'] : 0;
    return $response->withJson($service->getVerificationQrStats($notarizedId));
})->add($jwtMiddleware);
