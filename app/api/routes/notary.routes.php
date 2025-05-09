<?php
// app/api/routes/notary.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /notary/profile Get notary profile
 * @apiName GetNotaryProfile
 * @apiGroup Notary
 */
$app->get("/notary/profile", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    return $response->withJson($service->getNotaryProfile($userId));
})->add($jwtMiddleware);

/**
 * @api {put} /notary/profile Update notary profile
 * @apiName UpdateNotaryProfile
 * @apiGroup Notary
 */
$app->put("/notary/profile", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $profileData = array_merge(
        $request->getParsedBody() ?? [],
        $request->getUploadedFiles() ?? []
    );
    return $response->withJson($service->updateNotaryProfile($userId, $profileData));
})->add($jwtMiddleware);

/**
 * @api {get} /notary/queue Get notarization queue
 * @apiName GetNotarizationQueue
 * @apiGroup Notary
 */
$app->get("/notary/queue", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $params = $request->getQueryParams();
    return $response->withJson($service->getQueue($userId, $params));
})->add($jwtMiddleware);

/**
 * @api {get} /notary/requests/{request_id} Get request details
 * @apiName GetRequestDetails
 * @apiGroup Notary
 */
$app->get("/notary/requests/{request_id}", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $requestId = isset($args['request_id']) ? (int)$args['request_id'] : 0;
    return $response->withJson($service->getRequestDetails($userId, $requestId));
})->add($jwtMiddleware);

/**
 * @api {post} /notary/requests/preview Generate document preview
 * @apiName GenerateDocumentPreview
 * @apiGroup Notary
 */
$app->post("/notary/requests/preview", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $previewData = $request->getParsedBody();
    return $response->withJson($service->generateDocumentPreview($userId, $previewData));
})->add($jwtMiddleware);

/**
 * @api {post} /notary/queue/{queue_id}/accept Accept request from queue
 * @apiName AcceptRequest
 * @apiGroup Notary
 */
$app->post("/notary/queue/{queue_id}/accept", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $queueId = isset($args['queue_id']) ? (int)$args['queue_id'] : 0;
    return $response->withJson($service->acceptRequest($userId, $queueId));
})->add($jwtMiddleware);

/**
 * @api {post} /notary/requests/{request_id}/approve Approve and notarize document
 * @apiName ApproveRequest
 * @apiGroup Notary
 */
$app->post("/notary/requests/{request_id}/approve", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $requestId = isset($args['request_id']) ? (int)$args['request_id'] : 0;
    $notarizationData = $request->getParsedBody();
    return $response->withJson($service->approveRequest($userId, $requestId, $notarizationData));
})->add($jwtMiddleware);

/**
 * @api {post} /notary/requests/{request_id}/reject Reject document
 * @apiName RejectRequest
 * @apiGroup Notary
 */
$app->post("/notary/requests/{request_id}/reject", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $requestId = isset($args['request_id']) ? (int)$args['request_id'] : 0;
    $data = $request->getParsedBody();
    $rejectionReason = $data['rejection_reason'] ?? '';
    return $response->withJson($service->rejectRequest($userId, $requestId, $rejectionReason));
})->add($jwtMiddleware);

/**
 * @api {get} /notary/processed Get processed requests
 * @apiName GetProcessedRequests
 * @apiGroup Notary
 */
$app->get("/notary/processed", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $params = $request->getQueryParams();
    return $response->withJson($service->getProcessedRequests($userId, $params));
})->add($jwtMiddleware);

/**
 * @api {get} /notary/performance Get performance metrics
 * @apiName GetPerformanceMetrics
 * @apiGroup Notary
 */
$app->get("/notary/performance", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $params = $request->getQueryParams();
    return $response->withJson($service->getPerformanceMetrics($userId, $params));
})->add($jwtMiddleware);

/**
 * @api {get} /notary/dashboard Get notary dashboard
 * @apiName GetNotaryDashboard
 * @apiGroup Notary
 */
$app->get("/notary/dashboard", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    return $response->withJson($service->getDashboardStats($userId));
})->add($jwtMiddleware);

/**
 * @api {get} /notary/reports/summary Get notarization summary
 * @apiName GetNotarizationSummary
 * @apiGroup Notary
 */
$app->get("/notary/reports/summary", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $params = $request->getQueryParams();
    return $response->withJson($service->generateSummaryReport($userId, $params));
})->add($jwtMiddleware);

/**
 * @api {post} /notary/reports/detailed Generate detailed report
 * @apiName GenerateDetailedReport
 * @apiGroup Notary
 */
$app->post("/notary/reports/detailed", function ($request, $response, $args) {
    $service = new NotaryService();
    $userId = $request->getAttribute('user_id');
    $params = $request->getParsedBody();
    return $response->withJson($service->generateDetailedReport($userId, $params));
})->add($jwtMiddleware);