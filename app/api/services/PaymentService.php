<?php
// app/api/services/PaymentService.php
namespace PHPMaker2024\eNotary;

class PaymentService {
    /**
     * Get all available payment methods
     * @return array Response data
     */
    public function getPaymentMethods() {
        try {
            $sql = "SELECT
                    method_id,
                    method_name,
                    method_code,
                    description,
                    is_active,
                    requires_verification,
                    additional_fields
                FROM
                    payment_methods
                WHERE
                    is_active = true
                ORDER BY
                    method_name ASC";
            
            $result = ExecuteRows($sql, "DB");
            
            // Process additional fields
            foreach ($result as &$method) {
                if (!empty($method['additional_fields']) && is_string($method['additional_fields'])) {
                    $method['additional_fields'] = json_decode($method['additional_fields'], true);
                }
            }
            
            // Return success response
            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get payment methods: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get payment details for a notarization request
     * @param int $requestId Request ID
     * @return array Response data
     */
    public function getRequestPaymentDetails($requestId) {
        try {
            // Get request details
            $sql = "SELECT
                    r.request_id,
                    r.request_reference,
                    d.document_title,
                    r.payment_status,
                    t.transaction_id,
                    t.transaction_reference,
                    t.amount,
                    t.currency,
                    t.created_at AS payment_date
                FROM
                    notarization_requests r
                JOIN
                    documents d ON r.document_id = d.document_id
                LEFT JOIN
                    payment_transactions t ON r.payment_transaction_id = t.transaction_id
                WHERE
                    r.request_id = " . QuotedValue($requestId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Request not found'
                ];
            }
            
            // If no transaction exists, check the fee amount
            if (empty($result[0]['transaction_id'])) {
                $sql = "SELECT
                        dt.fee_amount
                    FROM
                        notarization_requests r
                    JOIN
                        documents d ON r.document_id = d.document_id
                    LEFT JOIN
                        document_templates dt ON d.template_id = dt.template_id
                    WHERE
                        r.request_id = " . QuotedValue($requestId, DataType::NUMBER);
                
                $feeResult = ExecuteRows($sql, "DB");
                
                if (!empty($feeResult)) {
                    $result[0]['amount'] = $feeResult[0]['fee_amount'] ?? 0;
                    $result[0]['currency'] = 'PHP'; // Default currency
                }
            }
            
            // Return success response
            return [
                'success' => true,
                'data' => $result[0]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get payment details: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Process payment for a notarization request
     * @param int $requestId Request ID
     * @param array $paymentData Payment data
     * @return array Response data
     */
    public function processPayment($requestId, $paymentData) {
        try {
            // Validate required fields
            if (empty($paymentData['payment_method_id'])) {
                return [
                    'success' => false,
                    'message' => 'Payment method is required',
                    'errors' => ['payment_method_id' => ['Payment method is required']]
                ];
            }
            
            // Get request details
            $sql = "SELECT
                    r.request_id,
                    r.user_id,
                    r.request_reference,
                    r.payment_status,
                    r.payment_transaction_id,
                    d.document_title,
                    dt.fee_amount
                FROM
                    notarization_requests r
                JOIN
                    documents d ON r.document_id = d.document_id
                LEFT JOIN
                    document_templates dt ON d.template_id = dt.template_id
                WHERE
                    r.request_id = " . QuotedValue($requestId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Request not found'
                ];
            }
            
            $request = $result[0];
            
            // Check if payment is already completed
            if ($request['payment_status'] === 'paid') {
                return [
                    'success' => false,
                    'message' => 'Payment is already completed for this request'
                ];
            }
            
            // Check if payment is already in progress
            if ($request['payment_status'] === 'processing' && !empty($request['payment_transaction_id'])) {
                return [
                    'success' => false,
                    'message' => 'Payment is already in progress for this request'
                ];
            }
            
            // Get payment method details
            $sql = "SELECT
                    method_id,
                    method_name,
                    method_code,
                    requires_verification,
                    additional_fields
                FROM
                    payment_methods
                WHERE
                    method_id = " . QuotedValue($paymentData['payment_method_id'], DataType::NUMBER) . "
                    AND is_active = true";
            
            $methodResult = ExecuteRows($sql, "DB");
            
            if (empty($methodResult)) {
                return [
                    'success' => false,
                    'message' => 'Invalid or inactive payment method'
                ];
            }
            
            $paymentMethod = $methodResult[0];
            
            // Check if additional fields are required
            if ($paymentMethod['requires_verification'] && empty($paymentData['additional_fields'])) {
                return [
                    'success' => false,
                    'message' => 'Additional payment details are required',
                    'errors' => ['additional_fields' => ['Additional payment details are required']]
                ];
            }
            
            // Validate additional fields
            if (!empty($paymentMethod['additional_fields']) && !empty($paymentData['additional_fields'])) {
                $requiredFields = [];
                
                if (is_string($paymentMethod['additional_fields'])) {
                    $additionalFields = json_decode($paymentMethod['additional_fields'], true);
                } else {
                    $additionalFields = $paymentMethod['additional_fields'];
                }
                
                if (!empty($additionalFields)) {
                    foreach ($additionalFields as $field) {
                        if (!empty($field['required']) && $field['required']) {
                            $requiredFields[] = $field['name'];
                        }
                    }
                }
                
                foreach ($requiredFields as $field) {
                    if (empty($paymentData['additional_fields'][$field])) {
                        return [
                            'success' => false,
                            'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required',
                            'errors' => ['additional_fields.' . $field => [ucfirst(str_replace('_', ' ', $field)) . ' is required']]
                        ];
                    }
                }
            }
            
            // Calculate payment amount
            $amount = !empty($request['fee_amount']) ? $request['fee_amount'] : 0;
            $currency = 'PHP'; // Default currency
            
            // Generate transaction reference
            $transactionReference = $this->generateTransactionReference();
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Create payment transaction
                $sql = "INSERT INTO payment_transactions (
                        request_id,
                        user_id,
                        payment_method_id,
                        transaction_reference,
                        amount,
                        currency,
                        status,
                        gateway_response,
                        fee_amount,
                        created_at
                    ) VALUES (
                        " . QuotedValue($requestId, DataType::NUMBER) . ",
                        " . QuotedValue($request['user_id'], DataType::NUMBER) . ",
                        " . QuotedValue($paymentData['payment_method_id'], DataType::NUMBER) . ",
                        " . QuotedValue($transactionReference, DataType::STRING) . ",
                        " . QuotedValue($amount, DataType::NUMBER) . ",
                        " . QuotedValue($currency, DataType::STRING) . ",
                        'pending',
                        " . QuotedValue(json_encode($paymentData['additional_fields'] ?? []), DataType::STRING) . ",
                        " . QuotedValue(0, DataType::NUMBER) . ", -- No processing fee for now
                        CURRENT_TIMESTAMP
                    ) RETURNING transaction_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['transaction_id'])) {
                    throw new \Exception("Failed to create payment transaction");
                }
                
                $transactionId = $result[0]['transaction_id'];
                
                // Update request with payment transaction ID
                $sql = "UPDATE notarization_requests SET
                        payment_transaction_id = " . QuotedValue($transactionId, DataType::NUMBER) . ",
                        payment_status = 'pending',
                        modified_at = CURRENT_TIMESTAMP
                        WHERE request_id = " . QuotedValue($requestId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Prepare payment response
                $paymentResponse = [
                    'transaction_id' => $transactionId,
                    'transaction_reference' => $transactionReference,
                    'amount' => $amount,
                    'currency' => $currency,
                    'status' => 'pending'
                ];
                
                // Handle payment method specific logic
                switch ($paymentMethod['method_code']) {
                    case 'credit_card':
                        // Credit card would typically redirect to a payment gateway
                        $paymentResponse['payment_url'] = 'https://payment.gateway.com/pay/' . $transactionReference;
                        break;
                        
                    case 'gcash':
                        // Generate QR code for GCash payment
                        $qrCodePath = $this->generatePaymentQrCode($transactionReference, $amount);
                        $paymentResponse['qr_code_path'] = $qrCodePath;
                        break;
                        
                    case 'bank_transfer':
                        // For bank transfers, no additional action needed
                        break;
                        
                    case 'cash':
                        // For cash payments, no additional action needed
                        break;
                }
                
                // Create notification for user
                $this->createNotification(
                    $request['user_id'],
                    'payment_initiated',
                    'payment_' . $transactionId,
                    'Payment Initiated',
                    'Payment initiated for document "' . $request['document_title'] . '".',
                    'payments/' . $transactionId
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Payment initiated',
                    'data' => $paymentResponse
                ];
            } catch (\Exception $e) {
                // Rollback transaction on error
                Execute("ROLLBACK", "DB");
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Verify payment status for manual verification methods
     * @param int $transactionId Transaction ID
     * @param array $verificationData Verification data
     * @return array Response data
     */
    public function verifyPayment($transactionId, $verificationData) {
        try {
            // Validate required fields
            if (empty($verificationData['reference_number'])) {
                return [
                    'success' => false,
                    'message' => 'Reference number is required',
                    'errors' => ['reference_number' => ['Reference number is required']]
                ];
            }
            
            // Get transaction details
            $sql = "SELECT
                    t.transaction_id,
                    t.request_id,
                    t.user_id,
                    t.payment_method_id,
                    t.status,
                    t.amount,
                    r.document_id,
                    d.document_title,
                    m.method_name,
                    m.requires_verification
                FROM
                    payment_transactions t
                JOIN
                    payment_methods m ON t.payment_method_id = m.method_id
                JOIN
                    notarization_requests r ON t.request_id = r.request_id
                JOIN
                    documents d ON r.document_id = d.document_id
                WHERE
                    t.transaction_id = " . QuotedValue($transactionId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Transaction not found'
                ];
            }
            
            $transaction = $result[0];
            
            // Check if transaction is already completed
            if ($transaction['status'] === 'completed') {
                return [
                    'success' => false,
                    'message' => 'Transaction is already completed'
                ];
            }
            
            // Check if transaction is failed
            if ($transaction['status'] === 'failed') {
                return [
                    'success' => false,
                    'message' => 'Transaction has failed and cannot be verified'
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Update transaction status
                $sql = "UPDATE payment_transactions SET
                        status = 'completed',
                        modified_at = CURRENT_TIMESTAMP,
                        verification_data = " . QuotedValue(json_encode($verificationData), DataType::STRING) . "
                        WHERE transaction_id = " . QuotedValue($transactionId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Update request payment status
                $sql = "UPDATE notarization_requests SET
                        payment_status = 'paid',
                        modified_at = CURRENT_TIMESTAMP
                        WHERE request_id = " . QuotedValue($transaction['request_id'], DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // If payment is completed, add to notarization queue
                // Assign to notary (in a real system, this would use some algorithm for load balancing)
                // For now, we'll just get a random active notary
                $sql = "SELECT 
                        user_id 
                    FROM 
                        users 
                    WHERE 
                        is_notary = true 
                        AND is_active = true 
                        AND notary_commission_expiry > CURRENT_DATE
                    ORDER BY RANDOM() 
                    LIMIT 1";
                
                $notaryResult = ExecuteRows($sql, "DB");
                
                if (!empty($notaryResult)) {
                    $notaryId = $notaryResult[0]['user_id'];
                    
                    // Get current queue position
                    $sql = "SELECT COALESCE(MAX(queue_position), 0) + 1 AS next_position 
                            FROM notarization_queue
                            WHERE notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
                    
                    $positionResult = ExecuteRows($sql, "DB");
                    $queuePosition = $positionResult[0]['next_position'] ?? 1;
                    
                    // Add to queue
                    $sql = "INSERT INTO notarization_queue (
                            request_id,
                            notary_id,
                            queue_position,
                            status,
                            estimated_wait_time,
                            entry_time
                        ) VALUES (
                            " . QuotedValue($transaction['request_id'], DataType::NUMBER) . ",
                            " . QuotedValue($notaryId, DataType::NUMBER) . ",
                            " . QuotedValue($queuePosition, DataType::NUMBER) . ",
                            'queued',
                            " . QuotedValue(15, DataType::NUMBER) . ", -- Default 15 min estimate
                            CURRENT_TIMESTAMP
                        )";
                    
                    Execute($sql, "DB");
                }
                
                // Create receipt URL
                $receiptUrl = 'payments/' . $transactionId . '/receipt';
                
                // Create notification for user
                $this->createNotification(
                    $transaction['user_id'],
                    'payment_completed',
                    'payment_' . $transactionId,
                    'Payment Completed',
                    'Payment completed for document "' . $transaction['document_title'] . '".',
                    $receiptUrl
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Payment verified successfully',
                    'data' => [
                        'transaction_id' => $transactionId,
                        'status' => 'completed',
                        'receipt_url' => $receiptUrl
                    ]
                ];
            } catch (\Exception $e) {
                // Rollback transaction on error
                Execute("ROLLBACK", "DB");
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to verify payment: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Process payment gateway callback
     * @param array $callbackData Callback data
     * @return array Response data
     */
    public function handlePaymentCallback($callbackData) {
        try {
            // Validate callback data
            if (empty($callbackData['transaction_reference'])) {
                return [
                    'success' => false,
                    'message' => 'Transaction reference is required'
                ];
            }
            
            if (empty($callbackData['status'])) {
                return [
                    'success' => false,
                    'message' => 'Status is required'
                ];
            }
            
            // Get transaction by reference
            $sql = "SELECT
                    t.transaction_id,
                    t.request_id,
                    t.user_id,
                    t.status,
                    r.document_id,
                    d.document_title
                FROM
                    payment_transactions t
                JOIN
                    notarization_requests r ON t.request_id = r.request_id
                JOIN
                    documents d ON r.document_id = d.document_id
                WHERE
                    t.transaction_reference = " . QuotedValue($callbackData['transaction_reference'], DataType::STRING);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Transaction not found'
                ];
            }
            
            $transaction = $result[0];
            
            // Check if transaction is already in final state
            if (in_array($transaction['status'], ['completed', 'failed'])) {
                return [
                    'success' => true,
                    'message' => 'Transaction already processed'
                ];
            }
            
            // Map callback status to internal status
            $status = strtolower($callbackData['status']);
            if (in_array($status, ['success', 'successful', 'completed', 'approved'])) {
                $status = 'completed';
            } elseif (in_array($status, ['failed', 'declined', 'rejected'])) {
                $status = 'failed';
            } else {
                $status = 'pending';
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Update transaction status
                $sql = "UPDATE payment_transactions SET
                        status = " . QuotedValue($status, DataType::STRING) . ",
                        modified_at = CURRENT_TIMESTAMP,
                        gateway_response = " . QuotedValue(json_encode($callbackData), DataType::STRING) . "
                        WHERE transaction_id = " . QuotedValue($transaction['transaction_id'], DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Update request payment status
                $requestStatus = ($status === 'completed') ? 'paid' : 'pending';
                
                $sql = "UPDATE notarization_requests SET
                        payment_status = " . QuotedValue($requestStatus, DataType::STRING) . ",
                        modified_at = CURRENT_TIMESTAMP
                        WHERE request_id = " . QuotedValue($transaction['request_id'], DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // If payment is completed, add to notarization queue
                if ($status === 'completed') {
                    // Assign to notary (in a real system, this would use some algorithm for load balancing)
                    // For now, we'll just get a random active notary
                    $sql = "SELECT 
                            user_id 
                        FROM 
                            users 
                        WHERE 
                            is_notary = true 
                            AND is_active = true 
                            AND notary_commission_expiry > CURRENT_DATE
                        ORDER BY RANDOM() 
                        LIMIT 1";
                    
                    $notaryResult = ExecuteRows($sql, "DB");
                    
                    if (!empty($notaryResult)) {
                        $notaryId = $notaryResult[0]['user_id'];
                        
                        // Get current queue position
                        $sql = "SELECT COALESCE(MAX(queue_position), 0) + 1 AS next_position 
                                FROM notarization_queue
                                WHERE notary_id = " . QuotedValue($notaryId, DataType::NUMBER);
                        
                        $positionResult = ExecuteRows($sql, "DB");
                        $queuePosition = $positionResult[0]['next_position'] ?? 1;
                        
                        // Add to queue
                        $sql = "INSERT INTO notarization_queue (
                                request_id,
                                notary_id,
                                queue_position,
                                status,
                                estimated_wait_time,
                                entry_time
                            ) VALUES (
                                " . QuotedValue($transaction['request_id'], DataType::NUMBER) . ",
                                " . QuotedValue($notaryId, DataType::NUMBER) . ",
                                " . QuotedValue($queuePosition, DataType::NUMBER) . ",
                                'queued',
                                " . QuotedValue(15, DataType::NUMBER) . ", -- Default 15 min estimate
                                CURRENT_TIMESTAMP
                            )";
                        
                        Execute($sql, "DB");
                    }
                    
                    // Create notification for user
                    $this->createNotification(
                        $transaction['user_id'],
                        'payment_completed',
                        'payment_' . $transaction['transaction_id'],
                        'Payment Completed',
                        'Payment completed for document "' . $transaction['document_title'] . '".',
                        'payments/' . $transaction['transaction_id'] . '/receipt'
                    );
                } elseif ($status === 'failed') {
                    // Create notification for user
                    $this->createNotification(
                        $transaction['user_id'],
                        'payment_failed',
                        'payment_' . $transaction['transaction_id'],
                        'Payment Failed',
                        'Payment failed for document "' . $transaction['document_title'] . '". Please try again.',
                        'requests/' . $transaction['request_id'] . '/payment'
                    );
                }
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Callback processed'
                ];
            } catch (\Exception $e) {
                // Rollback transaction on error
                Execute("ROLLBACK", "DB");
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to process callback: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get payment receipt
     * @param int $transactionId Transaction ID
     * @return array Response data
     */
    public function getPaymentReceipt($transactionId) {
        try {
            // Get transaction details
            $sql = "SELECT
                    t.transaction_id,
                    t.transaction_reference,
                    t.request_id,
                    t.user_id,
                    t.payment_method_id,
                    t.amount,
                    t.currency,
                    t.status,
                    t.created_at,
                    t.modified_at,
                    m.method_name,
                    r.document_id,
                    d.document_title,
                    u.first_name,
                    u.last_name,
                    u.email
                FROM
                    payment_transactions t
                JOIN
                    payment_methods m ON t.payment_method_id = m.method_id
                JOIN
                    notarization_requests r ON t.request_id = r.request_id
                JOIN
                    documents d ON r.document_id = d.document_id
                JOIN
                    users u ON t.user_id = u.user_id
                WHERE
                    t.transaction_id = " . QuotedValue($transactionId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Transaction not found'
                ];
            }
            
            $transaction = $result[0];
            
            // Check if transaction is completed
            if ($transaction['status'] !== 'completed') {
                return [
                    'success' => false,
                    'message' => 'Payment receipt is only available for completed transactions'
                ];
            }
            
            // Generate receipt data
            $receiptData = [
                'transaction_id' => $transaction['transaction_id'],
                'transaction_reference' => $transaction['transaction_reference'],
                'date' => date('Y-m-d H:i:s', strtotime($transaction['modified_at'])),
                'payment_method' => $transaction['method_name'],
                'document_title' => $transaction['document_title'],
                'amount' => $transaction['amount'],
                'currency' => $transaction['currency'],
                'customer_name' => $transaction['first_name'] . ' ' . $transaction['last_name'],
                'customer_email' => $transaction['email']
            ];
            
            // In a real implementation, this would generate a PDF receipt
            // For now, we'll just return the receipt data
            
            // Return success response
            return [
                'success' => true,
                'data' => $receiptData
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get payment receipt: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate unique transaction reference
     * @return string Transaction reference
     */
    private function generateTransactionReference() {
        // Generate a unique alphanumeric reference (16 characters)
        $prefix = 'TXN';
        $timestamp = substr(time(), -8);
        $random = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
        
        return $prefix . $timestamp . $random;
    }
    
    /**
     * Generate payment QR code
     * @param string $transactionReference Transaction reference
     * @param float $amount Payment amount
     * @return string QR code path
     */
    private function generatePaymentQrCode($transactionReference, $amount) {
        // In a real implementation, this would generate a QR code using a library
        // For now, we'll just return a simulated QR code path
        
        // Generate a unique filename for the QR code
        $filename = 'payment_qr_' . $transactionReference . '.png';
        $qrCodePath = 'uploads/payment_qrcodes/' . $filename;
        
        // Ensure directory exists
        $qrCodeDir = dirname($qrCodePath);
        if (!is_dir($qrCodeDir)) {
            mkdir($qrCodeDir, 0755, true);
        }
        
        // Simulate QR code creation
        file_put_contents($qrCodePath, "Payment QR Code for: " . $transactionReference . ", Amount: " . $amount);
        
        return $qrCodePath;
    }
    
    /**
     * Create notification for a user
     * @param int $userId User ID
     * @param string $type Notification type
     * @param string $target Target entity reference
     * @param string $subject Notification subject
     * @param string $body Notification body
     * @param string $link Action URL
     * @return bool Success status
     */
    private function createNotification($userId, $type, $target, $subject, $body, $link = '') {
        try {
            $id = uniqid('notif_', true);
            
            $sql = "INSERT INTO notifications (
                    id,
                    timestamp,
                    type,
                    target,
                    user_id,
                    subject,
                    body,
                    link,
                    is_read
                ) VALUES (
                    " . QuotedValue($id, DataType::STRING) . ",
                    CURRENT_TIMESTAMP,
                    " . QuotedValue($type, DataType::STRING) . ",
                    " . QuotedValue($target, DataType::STRING) . ",
                    " . QuotedValue($userId, DataType::NUMBER) . ",
                    " . QuotedValue($subject, DataType::STRING) . ",
                    " . QuotedValue($body, DataType::STRING) . ",
                    " . QuotedValue($link, DataType::STRING) . ",
                    FALSE
                )";
            
            Execute($sql, "DB");
            
            return true;
        } catch (\Exception $e) {
            LogError('Failed to create notification: ' . $e->getMessage());
            return false;
        }
    }
}
