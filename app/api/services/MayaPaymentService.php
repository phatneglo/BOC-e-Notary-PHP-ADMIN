<?php
// app/api/services/MayaPaymentService.php
namespace PHPMaker2024\eNotary;

class MayaPaymentService {
    private $publicKey;
    private $secretKey;
    public $baseUrl; // Changed to public for testing
    private $isProduction;
    
    /**
     * Initialize Maya Payment Service
     */
    public function __construct() {
        // Set environment (production or sandbox)
        $this->isProduction = Config("MAYA.LIVE") ?? false; // Set to true for production
        Log("Current environment: " . ($this->isProduction ? "Production" : "Sandbox"));
        // Set API keys based on environment
        if ($this->isProduction) {
            $this->publicKey = Config("MAYA.API_KEY"); // Sandbox public key from docs
            $this->secretKey = Config("MAYA.API_SECRET"); // Sandbox secret key from docs
            $this->baseUrl = Config("MAYA.API_URL");
        } else {
        // These are updated test/sandbox keys for Maya - replace with your own correct sandbox keys
            $this->publicKey = "pk-lNAUk1jk7VPnf7koOT1uoGJoZJjmAxrbjpj6urB8EIA"; // Sandbox public key from docs
            $this->secretKey = "pk-yaj6GVzYkce52R193RIWpuRR5tTZKqzBWsUeCkP9EAf"; // Sandbox secret key from docs
            $this->baseUrl = "https://pg-sandbox.paymaya.com/checkout/v1";
        }
    }
    
    /**
     * Create a payment checkout session
     * 
     * @param array $paymentData Payment details
     * @return array Response data
     */
    public function createCheckout($paymentData) {
        try {
            // Validate required fields
            if (empty($paymentData['amount']) || 
                empty($paymentData['currency']) ||
                empty($paymentData['description']) ||
                empty($paymentData['requestReferenceNumber'])) {
                return [
                    'success' => false,
                    'message' => 'Missing required payment data'
                ];
            }
            
            // Get base URL for redirects
            $baseUrl = '';
            if (function_exists('Config')) {
                $baseUrl = Config("BASE_URL");
            } else {
                // Fallback method to get base URL
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . '/';
            }
            
            // Ensure the baseUrl ends with a slash
            if (substr($baseUrl, -1) !== '/') {
                $baseUrl .= '/';
            }
            
            // Log the base URL for debugging
            LogError('Maya API Base URL for redirects: ' . $baseUrl);
            
            // Format amount correctly (Maya expects string with 2 decimal places)
            $amount = number_format((float)$paymentData['amount'], 2, '.', '');
            
            // Get transaction ID from metadata if available
            $transactionId = '';
            if (!empty($paymentData['metadata']) && !empty($paymentData['metadata']['transaction_id'])) {
                $transactionId = $paymentData['metadata']['transaction_id'];
            }
            
            // Build checkout request body
            $checkoutData = [
                'totalAmount' => [
                    'value' => $amount,
                    'currency' => $paymentData['currency']
                ],
                'requestReferenceNumber' => $paymentData['requestReferenceNumber'],
                'redirectUrl' => [
                    'success' => $paymentData['successUrl'] ?? $baseUrl . "payments/success" . ($transactionId ? "?transaction_id=" . $transactionId : ""), 
                    'failure' => $paymentData['failureUrl'] ?? $baseUrl . "payments/failure" . ($transactionId ? "?transaction_id=" . $transactionId : ""),
                    'cancel' => $paymentData['cancelUrl'] ?? $baseUrl . "payments/cancel" . ($transactionId ? "?transaction_id=" . $transactionId : "")
                ],
                'items' => [
                    [
                        'name' => $paymentData['description'],
                        'quantity' => 1,
                        'code' => 'DOC-NOTARY',
                        'description' => $paymentData['description'],
                        'amount' => [
                            'value' => $amount,
                            'currency' => $paymentData['currency']
                        ],
                        'totalAmount' => [
                            'value' => $amount,
                            'currency' => $paymentData['currency']
                        ]
                    ]
                ]
            ];
            
            // Add optional metadata if provided
            if (!empty($paymentData['metadata'])) {
                $checkoutData['metadata'] = $paymentData['metadata'];
            }
            
            // Add buyer info if provided
            if (!empty($paymentData['buyer'])) {
                $checkoutData['buyer'] = $paymentData['buyer'];
            }
            
            // Send request to Maya API
            $response = $this->sendApiRequest('/checkout/v1/checkouts', 'POST', $checkoutData);
            
            if (isset($response['checkoutId']) && isset($response['redirectUrl'])) {
                return [
                    'success' => true,
                    'data' => [
                        'checkoutId' => $response['checkoutId'],
                        'paymentUrl' => $response['redirectUrl']
                    ]
                ];
            } else {
                // Log error details
                LogError('Maya API Error: ' . json_encode($response));
                
                return [
                    'success' => false,
                    'message' => 'Failed to create Maya payment checkout',
                    'details' => $response
                ];
            }
        } catch (\Exception $e) {
            LogError('Maya Payment Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment gateway error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get payment status from Maya
     * 
     * @param string $checkoutId Maya checkout ID
     * @return array Response data
     */
    public function getPaymentStatus($checkoutId) {
        try {
            $response = $this->sendApiRequest('/checkout/v1/checkouts/' . $checkoutId, 'GET');
            
            if (isset($response['id'])) {
                // Map Maya payment status to internal status
                $status = 'pending';
                
                if (isset($response['paymentStatus'])) {
                    switch ($response['paymentStatus']) {
                        case 'PAYMENT_SUCCESS':
                            $status = 'completed';
                            break;
                        case 'PAYMENT_FAILED':
                            $status = 'failed';
                            break;
                        case 'PAYMENT_EXPIRED':
                            $status = 'expired';
                            break;
                        default:
                            $status = 'pending';
                    }
                }
                
                return [
                    'success' => true,
                    'data' => [
                        'checkoutId' => $response['id'],
                        'status' => $status,
                        'paymentDetails' => $response
                    ]
                ];
            } else {
                // Log error details
                LogError('Maya API Error: ' . json_encode($response));
                
                return [
                    'success' => false,
                    'message' => 'Failed to get payment status from Maya',
                    'details' => $response
                ];
            }
        } catch (\Exception $e) {
            LogError('Maya Payment Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment gateway error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Register a webhook with Maya
     * 
     * @param string $name Webhook name
     * @param string $callbackUrl The URL to send webhook notifications to
     * @param array $events List of events to subscribe to
     * @return array Response data
     */
    public function registerWebhook($name, $callbackUrl, $events = []) {
        try {
            // If no events specified, register for all events
            if (empty($events)) {
                $events = [
                    "CHECKOUT_SUCCESS",
                    "CHECKOUT_FAILURE",
                    "CHECKOUT_DROPOUT",
                    "PAYMENT_SUCCESS",
                    "PAYMENT_FAILED",
                    "PAYMENT_EXPIRED",
                    "ONE_TIME_PAYMENT_SUCCESS",
                    "ONE_TIME_PAYMENT_FAILURE",
                    "ONE_TIME_PAYMENT_DROPOUT",
                    "AUTHORIZED"
                ];
            }
            
            $webhookData = [
                "name" => $name,
                "callbackUrl" => $callbackUrl,
                "events" => $events
            ];
            
            // Note: Webhook registration uses a different API endpoint and might require different authorization
            // Maya webhook API uses the /v1/webhooks endpoint
            $response = $this->sendApiRequest('/payments/v1/webhooks', 'POST', $webhookData);
            
            if (isset($response['id'])) {
                return [
                    'success' => true,
                    'data' => $response
                ];
            } else {
                // Log error details
                LogError('Maya API Webhook Registration Error: ' . json_encode($response));
                
                return [
                    'success' => false,
                    'message' => 'Failed to register Maya webhook',
                    'details' => $response
                ];
            }
        } catch (\Exception $e) {
            LogError('Maya Webhook Registration Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Webhook registration error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process Maya webhook notifications
     * 
     * @param array $webhookData Webhook data from Maya
     * @return array Response data
     */
    public function processWebhook($webhookData) {
        try {
            // Validate webhook data
            if (empty($webhookData['id'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid webhook data - missing ID'
                ];
            }
            
            // Extract information from webhook
            $checkoutId = $webhookData['id'];
            $eventType = $webhookData['type'] ?? null;
            $paymentStatus = $webhookData['status'] ?? null;
            
            // Log webhook data for debugging
            LogError('Maya Webhook Received: ' . json_encode($webhookData));
            
            // Process based on event type or payment status
            if ($eventType === 'CHECKOUT_SUCCESS' || $paymentStatus === 'PAYMENT_SUCCESS') {
                // Payment successful
                // Get payment status to confirm if it's an event type
                if ($eventType === 'CHECKOUT_SUCCESS') {
                    $statusResponse = $this->getPaymentStatus($checkoutId);
                    
                    if (!$statusResponse['success'] || $statusResponse['data']['status'] !== 'completed') {
                        return [
                            'success' => false,
                            'message' => 'Payment confirmation failed'
                        ];
                    }
                }
                
                // Extract reference number from data
                $requestReferenceNumber = '';
                if (isset($webhookData['requestReferenceNumber'])) {
                    $requestReferenceNumber = $webhookData['requestReferenceNumber'];
                } elseif (isset($webhookData['data']['requestReferenceNumber'])) {
                    $requestReferenceNumber = $webhookData['data']['requestReferenceNumber'];
                }
                
                if (empty($requestReferenceNumber)) {
                    return [
                        'success' => false,
                        'message' => 'Missing request reference number in webhook data'
                    ];
                }
                
                // Prepare callback data
                $callbackData = [
                    'transaction_reference' => $requestReferenceNumber,
                    'status' => 'completed',
                    'gateway_reference' => $checkoutId,
                    'payment_details' => $webhookData
                ];
                
                // Use the standard payment service to update transaction status
                $paymentService = new PaymentService();
                $result = $paymentService->handlePaymentCallback($callbackData);
                
                return $result;
            } elseif ($eventType === 'CHECKOUT_FAILURE' || $paymentStatus === 'PAYMENT_FAILED' ||
                      $eventType === 'CHECKOUT_EXPIRY' || $paymentStatus === 'PAYMENT_EXPIRED') {
                // Payment failed or expired
                
                // Extract reference number
                $requestReferenceNumber = '';
                if (isset($webhookData['requestReferenceNumber'])) {
                    $requestReferenceNumber = $webhookData['requestReferenceNumber'];
                } elseif (isset($webhookData['data']['requestReferenceNumber'])) {
                    $requestReferenceNumber = $webhookData['data']['requestReferenceNumber'];
                }
                
                if (empty($requestReferenceNumber)) {
                    return [
                        'success' => false,
                        'message' => 'Missing request reference number in webhook data'
                    ];
                }
                
                // Determine status
                $status = 'failed';
                if ($eventType === 'CHECKOUT_EXPIRY' || $paymentStatus === 'PAYMENT_EXPIRED') {
                    $status = 'expired';
                }
                
                // Prepare callback data
                $callbackData = [
                    'transaction_reference' => $requestReferenceNumber,
                    'status' => $status,
                    'gateway_reference' => $checkoutId,
                    'payment_details' => $webhookData
                ];
                
                // Use the standard payment service to update transaction status
                $paymentService = new PaymentService();
                $result = $paymentService->handlePaymentCallback($callbackData);
                
                return $result;
            } elseif ($paymentStatus === 'AUTHORIZED') {
                // Handle authorized payment status (payment is authorized but not yet captured)
                // Extract reference number
                $requestReferenceNumber = '';
                if (isset($webhookData['requestReferenceNumber'])) {
                    $requestReferenceNumber = $webhookData['requestReferenceNumber'];
                } elseif (isset($webhookData['data']['requestReferenceNumber'])) {
                    $requestReferenceNumber = $webhookData['data']['requestReferenceNumber'];
                }
                
                if (empty($requestReferenceNumber)) {
                    return [
                        'success' => false,
                        'message' => 'Missing request reference number in webhook data'
                    ];
                }
                
                // Prepare callback data
                $callbackData = [
                    'transaction_reference' => $requestReferenceNumber,
                    'status' => 'authorized',
                    'gateway_reference' => $checkoutId,
                    'payment_details' => $webhookData
                ];
                
                // Use the standard payment service to update transaction status
                $paymentService = new PaymentService();
                $result = $paymentService->handlePaymentCallback($callbackData);
                
                return $result;
            } else {
                // Unknown event type
                return [
                    'success' => true,
                    'message' => 'Unhandled webhook event/status: ' . ($eventType ?? $paymentStatus ?? 'UNKNOWN')
                ];
            }
        } catch (\Exception $e) {
            LogError('Maya Webhook Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Webhook processing error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Send API request to Maya
     * 
     * @param string $endpoint API endpoint
     * @param string $method HTTP method
     * @param array $data Request data
     * @return array Response data
     */
    private function sendApiRequest($endpoint, $method = 'GET', $data = null) {
        // Log request data for debugging
        if ($data) {
            LogError('Maya API Request: ' . $this->baseUrl . $endpoint . ' - ' . json_encode($data));
            // Log correct URL and authorization for debugging
            LogError('Maya API URL: ' . $this->baseUrl . $endpoint);
            LogError('Maya API Authorization: Basic ' . substr(base64_encode($this->publicKey . ':'), 0, 10) . '...');
        }
        
        // Initialize cURL session
        $curl = curl_init();
        
        // Set cURL options
        curl_setopt($curl, CURLOPT_URL, $this->baseUrl . $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for testing
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);     // Disable host verification for testing
        
        // Set HTTP method
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        
        // Set headers according to Maya documentation
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->publicKey . ':'),
            'Accept: application/json'
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        // Add request data for POST, PUT requests
        if (in_array($method, ['POST', 'PUT']) && !empty($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        // Execute cURL request
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        // Check for errors
        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            LogError('Maya API cURL Error: ' . $error);
            throw new \Exception('cURL error: ' . $error);
        }
        
        // Log response for debugging
        LogError('Maya API Response: ' . $response . ' (HTTP ' . $httpCode . ')');
        
        // Close cURL session
        curl_close($curl);
        
        // Decode JSON response
        $responseData = json_decode($response, true);
        
        // Check for API errors
        if ($httpCode >= 400) {
            LogError('Maya API Error: ' . $response);
            
            if (is_array($responseData) && !empty($responseData['message'])) {
                throw new \Exception('Maya API error: ' . $responseData['message']);
            } else {
                throw new \Exception('Maya API error: HTTP ' . $httpCode);
            }
        }
        
        return $responseData;
    }
}
