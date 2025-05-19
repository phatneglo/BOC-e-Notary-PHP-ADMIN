<?php
// Custom API route to fix document preview with proper JSON encoding
namespace PHPMaker2024\eNotary;

// Add this to your /app/api/routes/documents.routes.php file

/**
 * @api {get} /documents/{document_id}/preview-fixed Get document preview with proper JSON encoding
 * @apiName GetDocumentPreviewFixed
 * @apiGroup Documents
 */
$app->get("/documents/{document_id}/preview-fixed", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    
    try {
        // Get document HTML content directly
        $sql = "SELECT
                document_title,
                document_html
            FROM
                documents
            WHERE
                document_id = " . QuotedValue($documentId, DataType::NUMBER);
        
        $result = ExecuteRows($sql, "DB");
        
        if (empty($result)) {
            return $response->withJson([
                'success' => false,
                'message' => 'Document not found'
            ]);
        }
        
        // Return response with properly encoded JSON
        // The JSON_UNESCAPED_UNICODE flag helps with proper character encoding
        // Using withJson method will apply json_encode automatically with the right settings
        return $response->withJson([
            'success' => true,
            'data' => [
                'document_html' => $result[0]['document_html'],
                'document_title' => $result[0]['document_title']
            ]
        ], 200, JSON_UNESCAPED_SLASHES);
        
    } catch (\Exception $e) {
        // Log error
        LogError($e->getMessage());
        
        // Return error response
        return $response->withJson([
            'success' => false,
            'message' => 'Failed to get document preview: ' . $e->getMessage()
        ]);
    }
})->add($jwtMiddleware);
