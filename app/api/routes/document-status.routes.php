<?php
// app/api/routes/document-status.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /document-statuses Get all document statuses
 * @apiName GetDocumentStatuses
 * @apiGroup DocumentStatuses
 * @apiParam {Boolean} [active_only=false] Show only active statuses
 */
$app->get("/document-statuses", function ($request, $response, $args) {
    $service = new DocumentStatusService();
    $params = $request->getQueryParams();
    return $response->withJson($service->getDocumentStatuses($params));
})->add($jwtMiddleware);

/**
 * @api {get} /document-statuses/:id Get document status by ID
 * @apiName GetDocumentStatus
 * @apiGroup DocumentStatuses
 */
$app->get("/document-statuses/{id}", function ($request, $response, $args) {
    $service = new DocumentStatusService();
    $statusId = isset($args['id']) ? (int)$args['id'] : 0;
    return $response->withJson($service->getDocumentStatus($statusId));
})->add($jwtMiddleware);

/**
 * @api {get} /document-statuses/code/:code Get document status by code
 * @apiName GetDocumentStatusByCode
 * @apiGroup DocumentStatuses
 */
$app->get("/document-statuses/code/{code}", function ($request, $response, $args) {
    $service = new DocumentStatusService();
    $statusCode = isset($args['code']) ? $args['code'] : '';
    return $response->withJson($service->getDocumentStatusByCode($statusCode));
})->add($jwtMiddleware);