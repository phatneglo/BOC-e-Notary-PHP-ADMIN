<?php
// app/api/routes/notarized.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /notarized List notarized documents
 * @apiName ListNotarizedDocuments
 * @apiGroup Notarized
 */
$app->get("/notarized", function ($request, $response, $args) {
    $service = new NotarizedDocumentService();
    $userId = $request->getAttribute('user_id');
    $params = $request->getQueryParams();
    return $response->withJson($service->listNotarizedDocuments($userId, $params));
})->add($jwtMiddleware);

/**
 * @api {get} /notarized/{notarized_id} Get notarized document details
 * @apiName GetNotarizedDocumentDetails
 * @apiGroup Notarized
 */
$app->get("/notarized/{notarized_id}", function ($request, $response, $args) {
    $service = new NotarizedDocumentService();
    $notarizedId = isset($args['notarized_id']) ? (int)$args['notarized_id'] : 0;
    return $response->withJson($service->getNotarizedDocumentDetails($notarizedId));
})->add($jwtMiddleware);

/**
 * @api {get} /notarized/{notarized_id}/download Download notarized document
 * @apiName DownloadNotarizedDocument
 * @apiGroup Notarized
 */
$app->get("/notarized/{notarized_id}/download", function ($request, $response, $args) {
    $service = new NotarizedDocumentService();
    $notarizedId = isset($args['notarized_id']) ? (int)$args['notarized_id'] : 0;
    
    $result = $service->downloadNotarizedDocument($notarizedId);
    
    if (!$result['success']) {
        return $response->withJson($result);
    }
    
    $fileData = $result['data'];
    
    // Return file download response
    $fileStream = fopen($fileData['file_path'], 'r');
    return $response
        ->withHeader('Content-Type', $fileData['mime_type'])
        ->withHeader('Content-Disposition', 'attachment; filename="' . $fileData['file_name'] . '"')
        ->withHeader('Content-Length', $fileData['file_size'])
        ->withBody(new \Slim\Http\Stream($fileStream));
})->add($jwtMiddleware);

/**
 * @api {get} /notarized/{notarized_id}/qrcode Get QR code
 * @apiName GetQRCode
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
    $fileStream = fopen($fileData['file_path'], 'r');
    return $response
        ->withHeader('Content-Type', $fileData['mime_type'])
        ->withHeader('Content-Disposition', 'inline; filename="' . $fileData['file_name'] . '"')
        ->withHeader('Content-Length', $fileData['file_size'])
        ->withBody(new \Slim\Http\Stream($fileStream));
})->add($jwtMiddleware);

/**
 * @api {post} /notarized/{notarized_id}/generate-qr Generate verification QR
 * @apiName GenerateVerificationQR
 * @apiGroup Notarized
 */
$app->post("/notarized/{notarized_id}/generate-qr", function ($request, $response, $args) {
    $service = new NotarizedDocumentService();
    $notarizedId = isset($args['notarized_id']) ? (int)$args['notarized_id'] : 0;
    $options = $request->getParsedBody();
    return $response->withJson($service->generateVerificationQrCode($notarizedId, $options));
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
