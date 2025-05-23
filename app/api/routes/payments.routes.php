<?php
// app/api/routes/payments.routes.php
namespace PHPMaker2024\eNotary;

use Dflydev\DotAccessData\Data;

/**
 * @api {get} /payments/test-maya Test Maya API
 * @apiName TestMaya
 * @apiGroup Payments
 */
$app->get("/payments/test-maya", function ($request, $response, $args) {
    // Create a Maya Payment Service instance
    $service = new MayaPaymentService();
    
    // Log the test
    LogError('Running Maya API test');
    
    // Get the current URL base
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . '/';
    LogError('Base URL: ' . $baseUrl);
    
    // Test data
    $testData = [
        'amount' => 100.00,
        'currency' => 'PHP',
        'description' => 'E-Notary Test Payment',
        'requestReferenceNumber' => 'TEST' . time(),
        'successUrl' => $baseUrl . 'payments/success?test=1',
        'failureUrl' => $baseUrl . 'payments/failure?test=1',
        'cancelUrl' => $baseUrl . 'payments/cancel?test=1',
        'metadata' => [
            'transaction_id' => 'TEST' . time(),
        ],
        'buyer' => [
            'firstName' => 'Test',
            'middleName' => '',
            'lastName' => 'User',
            'contact' => [
                'email' => 'test@example.com',
                'phone' => '09123456789'
            ]
        ]
    ];
    
    // Call the Maya API
    $result = $service->createCheckout($testData);
    
    // Return the result
    return $response->withJson($result);
});

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
 * @api {post} /payments/maya/webhook Maya payment webhook
 * @apiName MayaWebhook
 * @apiGroup Payments
 */
$app->post("/payments/maya/webhook", function ($request, $response, $args) {
    // Set CORS headers directly on this endpoint for maximum compatibility
    $response = $response
        ->withHeader('Access-Control-Allow-Origin', '*') 
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, X-Authorization, Authorization, X-Api-Key')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withHeader('Access-Control-Max-Age', '86400'); // Cache preflight for 24 hours
    
    // Verify the source IP address
    $isProduction = Config("MAYA.LIVE") ?? false;
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
    
    // Define allowed IPs based on environment
    $allowedIPs = $isProduction
        ? ['18.138.50.235', '3.1.207.200'] // Production IPs
        : ['13.229.160.234', '3.1.199.75', '127.0.0.1']; // Sandbox IPs + localhost for testing
    
    // Log the attempt for debugging
    Log('Maya Webhook attempt from IP: ' . $clientIp . ' in ' . ($isProduction ? 'Production' : 'Sandbox') . ' mode via POST');
    
    // Check if the request is from an authorized IP
    if (!in_array($clientIp, $allowedIPs)) {
        LogError('Unauthorized Maya webhook attempt from IP: ' . $clientIp);
        return $response->withStatus(403)->withJson([
            'success' => false,
            'message' => 'Unauthorized source IP address'
        ]);
    }
    
    $service = new MayaPaymentService();
    $webhookData = $request->getParsedBody();
    
    // Log webhook data for debugging
    Log('Maya Webhook received: ' . json_encode($webhookData));
    
    // Process webhook
    $result = $service->processWebhook($webhookData);
    return $response->withJson($result);
});
/**
 * @api {get} /payments/maya/webhook Maya payment webhook
 * @apiName MayaWebhook
 * @apiGroup Payments
 */
$app->get("/payments/maya/webhook", function ($request, $response, $args) {
    // Set CORS headers directly on this endpoint for maximum compatibility
    $response = $response
        ->withHeader('Access-Control-Allow-Origin', '*') 
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, X-Authorization, Authorization, X-Api-Key')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withHeader('Access-Control-Max-Age', '86400'); // Cache preflight for 24 hours
    
    // Verify the source IP address
    $isProduction = Config("MAYA.LIVE") ?? false;
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
    
    // Define allowed IPs based on environment
    $allowedIPs = $isProduction
        ? ['18.138.50.235', '3.1.207.200'] // Production IPs
        : ['13.229.160.234', '3.1.199.75', '127.0.0.1']; // Sandbox IPs + localhost for testing
    
    // Log the attempt for debugging
    Log('Maya Webhook attempt from IP: ' . $clientIp . ' in ' . ($isProduction ? 'Production' : 'Sandbox') . ' mode via GET');
    
    // Check if the request is from an authorized IP
    if (!in_array($clientIp, $allowedIPs)) {
        LogError('Unauthorized Maya webhook attempt from IP: ' . $clientIp);
        return $response->withStatus(403)->withJson([
            'success' => false,
            'message' => 'Unauthorized source IP address'
        ]);
    }
    
    $service = new MayaPaymentService();
    $webhookData = $request->getParsedBody();
    
    // Log webhook data for debugging
    Log('Maya Webhook received: ' . json_encode($webhookData));
    
    // Process webhook
    $result = $service->processWebhook($webhookData);
    return $response->withJson($result);
});


/**
 * @api {options} /payments/maya/webhook CORS preflight for Maya webhook
 * @apiName OptionsMayaWebhook
 * @apiGroup Payments
 */
$app->options("/payments/maya/webhook", function ($request, $response, $args) {
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, X-Authorization, Authorization, X-Api-Key')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withHeader('Access-Control-Max-Age', '86400') // Cache preflight for 24 hours
        ->withStatus(200);
});

/**
 * @api {get} /payments/status/{transaction_id} Check payment status
 * @apiName CheckPaymentStatus
 * @apiGroup Payments
 */
$app->get("/payments/status/{transaction_id}", function ($request, $response, $args) {
    $service = new PaymentService();
    $transactionId = isset($args['transaction_id']) ? (int)$args['transaction_id'] : 0;
    
    // Get transaction details
    $sql = "SELECT
            t.transaction_id,
            t.transaction_reference,
            t.gateway_reference,
            t.status,
            m.method_code
        FROM
            payment_transactions t
        JOIN
            payment_methods m ON t.payment_method_id = m.method_id
        WHERE
            t.transaction_id = " . QuotedValue($transactionId, DataType::NUMBER);
    
    $result = ExecuteRows($sql, "DB");
    
    if (empty($result)) {
        return $response->withJson([
            'success' => false,
            'message' => 'Transaction not found'
        ]);
    }
    
    $transaction = $result[0];
    
    // For Maya payments, check status from Maya API
    if ($transaction['method_code'] === 'PAYMAYA' && !empty($transaction['gateway_reference']) && $transaction['status'] === 'pending') {
        $mayaService = new MayaPaymentService();
        $statusResult = $mayaService->getPaymentStatus($transaction['gateway_reference']);
        
        if ($statusResult['success']) {
            // If payment completed or failed, update transaction status
            if ($statusResult['data']['status'] === 'completed' || $statusResult['data']['status'] === 'failed') {
                // Prepare callback data for standard payment handler
                $callbackData = [
                    'transaction_reference' => $transaction['transaction_reference'],
                    'status' => $statusResult['data']['status'],
                    'gateway_reference' => $transaction['gateway_reference'],
                    'payment_details' => $statusResult['data']['paymentDetails']
                ];
                
                // Update transaction status
                $paymentService = new PaymentService();
                $paymentService->handlePaymentCallback($callbackData);
                
                // Get updated transaction status
                $sql = "SELECT status FROM payment_transactions WHERE transaction_id = " . QuotedValue($transactionId, DataType::NUMBER);
                $updatedResult = ExecuteRows($sql, "DB");
                
                if (!empty($updatedResult)) {
                    $statusResult['data']['status'] = $updatedResult[0]['status'];
                }
            }
            
            return $response->withJson($statusResult);
        }
    }
    
    // For other payment methods or if Maya API check fails, return current status
    return $response->withJson([
        'success' => true,
        'data' => [
            'status' => $transaction['status'],
            'transaction_reference' => $transaction['transaction_reference']
        ]
    ]);
})->add($jwtMiddleware);

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


/**
 * @api {post} /payments/maya/register-webhook Register Maya webhook
 * @apiName RegisterMayaWebhook
 * @apiGroup Payments
 */
$app->post("/payments/maya/register-webhook", function ($request, $response, $args) {
    // Only allow in development/admin environment

    
    $service = new MayaPaymentService();
    $data = $request->getParsedBody();
    
    // Get base URL for webhook
    
    // Define webhook URL
    $webhookUrl ='https://boc-enotary.itbsstudio.com/api/payments/maya/webhook';
    $webhookName = $data['name'] ?? 'eNotarize Payment Webhook';
    $events = $data['events'] ?? [];
    Log('Registering Web Hook Details ' . json_encode($data));
    $result = $service->registerWebhook($webhookName, $webhookUrl, $events);
    return $response->withJson($result);
})->add($jwtMiddleware);