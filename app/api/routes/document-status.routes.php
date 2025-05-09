<?php
// app/api/routes/document-status.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /document-statuses Get all document statuses
 * @apiName GetDocumentStatuses
 * @apiGroup DocumentStatus
 */
$app->get("/document-statuses", function ($request, $response, $args) {
    $service = new DocumentStatusService();
    $params = $request->getQueryParams();
    return $response->withJson($service->getDocumentStatuses($params));
});

/**
 * @api {get} /document-statuses/{status_id} Get document status by ID
 * @apiName GetDocumentStatus
 * @apiGroup DocumentStatus
 */
$app->get("/document-statuses/{status_id}", function ($request, $response, $args) {
    $service = new DocumentStatusService();
    $statusId = isset($args['status_id']) ? (int)$args['status_id'] : 0;
    return $response->withJson($service->getDocumentStatus($statusId));
});

/**
 * @api {get} /document-statuses/code/{status_code} Get document status by code
 * @apiName GetDocumentStatusByCode
 * @apiGroup DocumentStatus
 */
$app->get("/document-statuses/code/{status_code}", function ($request, $response, $args) {
    $service = new DocumentStatusService();
    $statusCode = isset($args['status_code']) ? $args['status_code'] : '';
    return $response->withJson($service->getDocumentStatusByCode($statusCode));
});