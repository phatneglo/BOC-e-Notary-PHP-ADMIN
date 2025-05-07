<?php
// app/api/routes/payments.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /payments/methods Get payment methods
 * @apiName GetPaymentMethods
 * @apiGroup Payments
 */
$app->get("/payments/methods", function ($request, $response, $args) {
    $service = new PaymentService();
    return $response->withJson($service->getPaymentMethods());
})->add($jwtMiddleware);

/**
 * @api {get} /requests/{request_id}/payment Get request payment details
 * @apiName GetRequestPaymentDetails
 * @apiGroup Payments
 */
$app->get("/requests/{request_id}/payment", function ($request, $response, $args) {
    $service = new PaymentService();
    $requestId = isset($args['request_id']) ? (int)$args['request_id'] : 0;
    return $response->withJson($service->getRequestPaymentDetails($requestId));
})->add($jwtMiddleware);

/**
 * @api {post} /requests/{request_id}/payment Process payment
 * @apiName ProcessPayment
 * @apiGroup Payments
 */
$app->post("/requests/{request_id}/payment", function ($request, $response, $args) {
    $service = new PaymentService();
    $requestId = isset($args['request_id']) ? (int)$args['request_id'] : 0;
    $paymentData = $request->getParsedBody();
    return $response->withJson($service->processPayment($requestId, $paymentData));
})->add($jwtMiddleware);

/**
 * @api {post} /payments/{transaction_id}/verify Verify payment
 * @apiName VerifyPayment
 * @apiGroup Payments
 */
$app->post("/payments/{transaction_id}/verify", function ($request, $response, $args) {
    $service = new PaymentService();
    $transactionId = isset($args['transaction_id']) ? (int)$args['transaction_id'] : 0;
    $verificationData = $request->getParsedBody();
    return $response->withJson($service->verifyPayment($transactionId, $verificationData));
})->add($jwtMiddleware);

/**
 * @api {post} /payments/callback Payment callback
 * @apiName PaymentCallback
 * @apiGroup Payments
 */
$app->post("/payments/callback", function ($request, $response, $args) {
    $service = new PaymentService();
    $callbackData = $request->getParsedBody();
    return $response->withJson($service->handlePaymentCallback($callbackData));
});

/**
 * @api {get} /payments/{transaction_id}/receipt Get payment receipt
 * @apiName GetPaymentReceipt
 * @apiGroup Payments
 */
$app->get("/payments/{transaction_id}/receipt", function ($request, $response, $args) {
    $service = new PaymentService();
    $transactionId = isset($args['transaction_id']) ? (int)$args['transaction_id'] : 0;
    
    $result = $service->getPaymentReceipt($transactionId);
    
    if (!$result['success']) {
        return $response->withJson($result);
    }
    
    // In a real implementation, this would generate and serve a PDF receipt
    // For now, we'll just return the receipt data as JSON
    return $response->withJson($result);
})->add($jwtMiddleware);
