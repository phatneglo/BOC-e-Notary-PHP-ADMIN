<?php
// app/api/routes/verify.routes.php
namespace PHPMaker2024\eNotary;

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
