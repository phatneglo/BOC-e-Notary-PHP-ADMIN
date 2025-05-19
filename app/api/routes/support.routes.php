<?php
// app/api/routes/support.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {post} /support/contact Submit support request
 * @apiName SubmitSupportRequest
 * @apiGroup Support
 * @apiParam {String} name Full name
 * @apiParam {String} email Email address
 * @apiParam {String} subject Request subject
 * @apiParam {String} message Request message
 * @apiParam {String} [request_type=general] Type of request 
 * @apiParam {Number} [user_id] User ID (if authenticated)
 * @apiSuccess {Boolean} success Operation status
 * @apiSuccess {Object} data Request details including reference number
 */
$app->post("/support/contact", function ($request, $response, $args) {
    $service = new SupportService();
    $data = $request->getParsedBody();
    
    // If user is authenticated, add user_id
    $userId = $request->getAttribute('user_id');
    if ($userId) {
        $data['user_id'] = $userId;
    }
    
    $result = $service->submitRequest($data);
    return $response->withJson($result);
});

/**
 * @api {get} /support/status/:referenceNumber Check support request status
 * @apiName CheckRequestStatus
 * @apiGroup Support
 * @apiParam {String} referenceNumber Reference number of the support request
 * @apiSuccess {Boolean} success Operation status
 * @apiSuccess {Object} data Request status details
 */
$app->get("/support/status/{referenceNumber}", function ($request, $response, $args) {
    $service = new SupportService();
    $referenceNumber = $args['referenceNumber'];
    $result = $service->checkRequestStatus($referenceNumber);
    return $response->withJson($result);
});
