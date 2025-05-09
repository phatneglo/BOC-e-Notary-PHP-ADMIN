<?php
// app/api/services/DocumentStatusService.php
namespace PHPMaker2024\eNotary;

class DocumentStatusService {
    /**
     * Get all document statuses
     * @param array $args Query parameters
     * @return array Response data
     */
    public function getDocumentStatuses($args = []) {
        try {
            // Get active filter
            $activeOnly = isset($args['active_only']) ? (bool)$args['active_only'] : false;
            
            // Build query
            $where = "1=1";
            if ($activeOnly) {
                $where .= " AND is_active = TRUE";
            }
            
            // Execute query
            $sql = "SELECT
                    status_id,
                    status_code,
                    status_name,
                    description,
                    is_active,
                    created_at,
                    updated_at
                FROM
                    document_statuses
                WHERE
                    $where
                ORDER BY
                    status_id ASC";
            
            $result = ExecuteRows($sql, "DB");
            
            // Return standardized response
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
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get a specific document status by ID
     * @param int $statusId Status ID
     * @return array Response data
     */
    public function getDocumentStatus($statusId) {
        try {
            // Validate status_id
            if (!$statusId) {
                return [
                    'success' => false,
                    'message' => 'Status ID is required'
                ];
            }
            
            // Execute query
            $sql = "SELECT
                    status_id,
                    status_code,
                    status_name,
                    description,
                    is_active,
                    created_at,
                    updated_at
                FROM
                    document_statuses
                WHERE
                    status_id = " . QuotedValue($statusId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document status not found'
                ];
            }
            
            // Return standardized response
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
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get a specific document status by status code
     * @param string $statusCode Status code
     * @return array Response data
     */
    public function getDocumentStatusByCode($statusCode) {
        try {
            // Validate status_code
            if (!$statusCode) {
                return [
                    'success' => false,
                    'message' => 'Status code is required'
                ];
            }
            
            // Execute query
            $sql = "SELECT
                    status_id,
                    status_code,
                    status_name,
                    description,
                    is_active,
                    created_at,
                    updated_at
                FROM
                    document_statuses
                WHERE
                    status_code = " . QuotedValue($statusCode, DataType::STRING);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document status not found'
                ];
            }
            
            // Return standardized response
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
                'message' => $e->getMessage()
            ];
        }
    }
}