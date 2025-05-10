<?php
// app/api/routes/fee-schedules.routes.php
namespace PHPMaker2024\eNotary;

// Admin: Get all pending fee proposals
$app->get("/fee-schedules/pending", function ($request, $response, $args) {
    $service = new FeeScheduleService();
    return $response->withJson($service->getPendingFeeProposals());
})->add($adminOnlyMiddleware);

// Admin: Approve fee proposal
$app->post("/fee-schedules/{fee_id}/approve", function ($request, $response, $args) {
    $service = new FeeScheduleService();
    $data = $request->getParsedBody();
    return $response->withJson($service->approveFeeProposal((int)$args['fee_id'], $data));
})->add($adminOnlyMiddleware);

// Admin: Reject fee proposal
$app->post("/fee-schedules/{fee_id}/reject", function ($request, $response, $args) {
    $service = new FeeScheduleService();
    $data = $request->getParsedBody();
    return $response->withJson($service->rejectFeeProposal((int)$args['fee_id'], $data));
})->add($adminOnlyMiddleware);

// Get fee schedules for a template
$app->get("/templates/{template_id}/fee-schedules", function ($request, $response, $args) {
    $service = new FeeScheduleService();
    return $response->withJson($service->getFeeSchedulesForTemplate((int)$args['template_id']));
})->add($jwtMiddleware);

// Create fee proposal for a template
$app->post("/templates/{template_id}/fee-proposal", function ($request, $response, $args) {
    $userId = $request->getAttribute('user_id');
    
    $service = new FeeScheduleService();
    $data = $request->getParsedBody();
    return $response->withJson($service->createFeeProposal($userId, (int)$args['template_id'], $data));
})->add($jwtMiddleware);
