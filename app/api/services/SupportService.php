<?php
// app/api/services/SupportService.php
namespace PHPMaker2024\eNotary;

class SupportService {
    /**
     * Submit a support request
     * @param array $data Request data
     * @return array Response data
     */
    public function submitRequest($data) {
        try {
            // Validate required fields
            if (empty($data['name']) || empty($data['email']) || empty($data['subject']) || empty($data['message'])) {
                return [
                    'success' => false,
                    'message' => 'Please fill in all required fields'
                ];
            }
            
            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' => 'Please provide a valid email address'
                ];
            }
            
            // Generate unique reference number (BOC-YYYYMMDD-XXXXX)
            $referenceNumber = 'BOC-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 5));
            
            // Prepare data for insertion
            $insertData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'message' => $data['message'],
                'request_type' => isset($data['request_type']) ? $data['request_type'] : 'general',
                'reference_number' => $referenceNumber,
                'status' => 'new',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ];
            
            // Include user_id if provided
            if (!empty($data['user_id'])) {
                $insertData['user_id'] = (int)$data['user_id'];
            }
            
            // Start transaction
            Execute("BEGIN", "DB");
            
            // Insert the support request
            $fields = implode(", ", array_keys($insertData));
            $values = implode(", ", array_map(function($field) use ($insertData) {
                $value = $insertData[$field];
                $type = is_numeric($value) ? DataType::NUMBER : DataType::STRING;
                return QuotedValue($value, $type);
            }, array_keys($insertData)));
            
            $sql = "INSERT INTO support_requests ({$fields}) VALUES ({$values}) RETURNING request_id";
            $result = ExecuteScalar($sql, "DB");
            
            if (!$result) {
                Execute("ROLLBACK", "DB");
                return [
                    'success' => false,
                    'message' => 'Failed to submit support request'
                ];
            }
            
            $requestId = $result;
            
            // Add initial history entry
            $historySql = "INSERT INTO support_request_history (request_id, status, comment, created_by, created_at) 
                            VALUES (" . QuotedValue($requestId, DataType::NUMBER) . ", 
                                    'new', 
                                    'Support request submitted through web portal', 
                                    " . (empty($data['user_id']) ? "NULL" : QuotedValue($data['user_id'], DataType::NUMBER)) . ", 
                                    " . QuotedValue(date('Y-m-d H:i:s'), DataType::STRING) . ")";
            
            Execute($historySql, "DB");
            
            // Commit transaction
            Execute("COMMIT", "DB");
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'request_id' => $requestId,
                    'reference_number' => $referenceNumber
                ]
            ];
            
        } catch (\Exception $e) {
            // Rollback on error
            Execute("ROLLBACK", "DB");
            LogError($e->getMessage());
            
            return [
                'success' => false,
                'message' => 'An error occurred while submitting your request'
            ];
        }
    }
    
    /**
     * Check support request status by reference number
     * @param string $referenceNumber Reference number
     * @return array Response data
     */
    public function checkRequestStatus($referenceNumber) {
        try {
            // Validate input
            if (empty($referenceNumber)) {
                return [
                    'success' => false,
                    'message' => 'Reference number is required'
                ];
            }
            
            // Get request details
            $sql = "SELECT r.request_id, r.reference_number, r.status, r.created_at as submitted_at, 
                          r.updated_at as last_updated, r.subject, r.request_type
                   FROM support_requests r 
                   WHERE r.reference_number = " . QuotedValue($referenceNumber, DataType::STRING);
            
            $request = ExecuteRow($sql, "DB");
            
            if (!$request) {
                return [
                    'success' => false,
                    'message' => 'Request not found'
                ];
            }
            
            // Get history entries
            $historySql = "SELECT h.status, h.comment, h.created_at, 
                                  CONCAT(u.first_name, ' ', u.last_name) as staff_name
                           FROM support_request_history h
                           LEFT JOIN users u ON h.created_by = u.user_id
                           WHERE h.request_id = " . QuotedValue($request['request_id'], DataType::NUMBER) . "
                           ORDER BY h.created_at DESC";
            
            $history = ExecuteRows($historySql, "DB");
            
            // Return request details with history
            return [
                'success' => true,
                'data' => [
                    'request_id' => $request['request_id'],
                    'reference_number' => $request['reference_number'],
                    'status' => $request['status'],
                    'submitted_at' => $request['submitted_at'],
                    'last_updated' => $request['last_updated'],
                    'subject' => $request['subject'],
                    'request_type' => $request['request_type'],
                    'history' => $history
                ]
            ];
            
        } catch (\Exception $e) {
            LogError($e->getMessage());
            
            return [
                'success' => false,
                'message' => 'An error occurred while checking request status'
            ];
        }
    }
}
