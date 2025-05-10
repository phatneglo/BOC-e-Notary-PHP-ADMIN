<?php
// app/api/routes/consolidated-qrcode-verify.routes.php
namespace PHPMaker2024\eNotary;

/**
 * QR CODE MANAGEMENT ROUTES
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
    
    // Read file and output directly
    $imageData = file_get_contents($logoPath);
    
    $response = $response->withHeader('Content-Type', $mimeType);
    $response = $response->withHeader('Content-Length', strlen($imageData));
    $response->getBody()->write($imageData);
    
    return $response;
})->add($jwtMiddleware);


/**
 * @api {get} /notary/qr-logo Get QR logo
 * @apiName GetQrLogo
 * @apiGroup QrCode
 */

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
        
        $response = $response->withHeader('Content-Type', 'image/png');
        $response = $response->withHeader('Content-Length', strlen($imageData));
        $response->getBody()->write($imageData);
        
        return $response;
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

/**
 * VERIFICATION ROUTES
 */

/**
 * @api {post} /verify Verify document
 * @apiName VerifyDocument
 * @apiGroup Verification
 */
$app->post("/verify", function ($request, $response, $args) {
    $service = new VerificationService();
    $verificationData = $request->getParsedBody();
    return $response->withJson($service->verifyDocument($verificationData));
});

/**
 * @api {post} /verify/document Verify document by number and code
 * @apiName VerifyDocumentByNumberAndCode
 * @apiGroup Verification
 */
$app->post("/verify/document", function ($request, $response, $args) {
    $service = new VerificationService();
    $data = $request->getParsedBody();
    
    if (empty($data['document_number']) || empty($data['keycode'])) {
        return $response->withJson([
            'success' => false,
            'message' => 'Document number and keycode are required'
        ]);
    }
    
    return $response->withJson($service->verifyDocumentByNumberAndCode($data['document_number'], $data['keycode']));
});

/**
 * @api {get} /verify/qr/{verification_id} Verification by QR code
 * @apiName VerificationByQRCode
 * @apiGroup Verification
 */
$app->get("/verify/qr/{verification_id}", function ($request, $response, $args) {
    $service = new VerificationService();
    $verificationId = isset($args['verification_id']) ? $args['verification_id'] : '';
    
    // Get verification details
    $result = $service->getVerificationByQrCode($verificationId);
    
    // If verification is successful and has redirect info, redirect to verification page with params
    if ($result['success'] && isset($result['data']['document_number']) && isset($result['data']['keycode'])) {
        $docNumber = urlencode($result['data']['document_number']);
        $keycode = urlencode($result['data']['keycode']);
        return $response->withRedirect("/verify?doc={$docNumber}&code={$keycode}");
    }
    
    // Otherwise just redirect to verify page
    return $response->withRedirect('/verify');
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
