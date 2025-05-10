<?php
// app/api/routes/maya-test.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /maya-test Simple Maya API test
 * @apiName MayaTest
 * @apiGroup Testing
 */
$app->get("/maya-test", function ($request, $response, $args) {
    // Create a Maya Payment Service instance
    $mayaService = new MayaPaymentService();
    
    // Log the test execution
    LogError('Starting Maya API test with corrected integration');
    
    // Get the current URL base
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . '/';
    
    // Test payment data
    $testPaymentData = [
        'amount' => 100.00,
        'currency' => 'PHP',
        'description' => 'E-Notary Test Payment',
        'requestReferenceNumber' => 'TEST' . time(),
        'successUrl' => $baseUrl . 'payments/success?test=1',
        'failureUrl' => $baseUrl . 'payments/failure?test=1',
        'cancelUrl' => $baseUrl . 'payments/cancel?test=1',
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
    $result = $mayaService->createCheckout($testPaymentData);
    
    // Return the result
    return $response->withJson([
        'test_result' => $result,
        'maya_base_url' => $mayaService->baseUrl ?? 'Not available',
        'test_data' => $testPaymentData
    ]);
});

/**
 * Add this route file to the main routes index
 */
if (function_exists('__PHPMAKER_ROUTE_INDEX__')) {
    __PHPMAKER_ROUTE_INDEX__('maya-test.routes.php');
}
