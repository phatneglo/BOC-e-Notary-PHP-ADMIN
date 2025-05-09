<?php
// app/api/routes/requests.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {post} /documents/{document_id}/submit Submit document for notarization
 * @apiName SubmitDocument
 * @apiGroup Notarization
 */
$app->post("/documents/{document_id}/submit", function ($request, $response, $args) {
    $service = new RequestService();
    $userId = $request->getAttribute('user_id');
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    return $response->withJson($service->submitDocument($documentId, $userId));
})->add($jwtMiddleware);

/**
 * @api {get} /requests/{request_id} Get request details
 * @apiName GetRequestDetails
 * @apiGroup Notarization
 */
$app->get("/requests/{request_id}", function ($request, $response, $args) {
    $service = new RequestService();
    $userId = $request->getAttribute('user_id');
    $requestId = isset($args['request_id']) ? (int)$args['request_id'] : 0;
    return $response->withJson($service->getRequestDetails($requestId, ));
})->add($jwtMiddleware);

/**
 * @api {put} /requests/{request_id}/modify Modify rejected document
 * @apiName ModifyRejectedDocument
 * @apiGroup Notarization
 */
$app->put("/requests/{request_id}/modify", function ($request, $response, $args) {
    $service = new RequestService();
    $requestId = isset($args['request_id']) ? (int)$args['request_id'] : 0;
    $userId = $request->getAttribute('user_id');
    $documentData = $request->getParsedBody();
    return $response->withJson($service->modifyRejectedDocument($requestId, $userId, $documentData));
})->add($jwtMiddleware);
