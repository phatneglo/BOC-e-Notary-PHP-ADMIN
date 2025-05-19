<?php
// app/api/services/DocumentService.php
namespace PHPMaker2024\eNotary;

class DocumentService {
    /**
     * Create a new document from a template
     * @param int $userId User ID
     * @param array $documentData Document data
     * @return array Response data
     */
    public function createDocument($userId, $documentData) {
        try {
            // Validate required fields
            if (empty($documentData['document_title'])) {
                return [
                    'success' => false,
                    'message' => 'Document title is required',
                    'errors' => ['document_title' => ['Document title is required']]
                ];
            }
            
            // Check if either template_id or user_template_id is provided
            if (empty($documentData['template_id']) && empty($documentData['user_template_id'])) {
                return [
                    'success' => false,
                    'message' => 'Either template ID or user template ID is required',
                    'errors' => [
                        'template_id' => ['Either template ID or user template ID is required'],
                        'user_template_id' => ['Either template ID or user template ID is required']
                    ]
                ];
            }
            
            // Validate document_data
            if (empty($documentData['document_data']) || !is_array($documentData['document_data'])) {
                return [
                    'success' => false,
                    'message' => 'Document data is required and must be an object',
                    'errors' => ['document_data' => ['Document data is required and must be an object']]
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Get template details if template_id is provided
                $templateId = null;
                $templateHtml = null;
                $isUserTemplate = false;
                

                
                if (!empty($documentData['template_id'])) {
                    $sql = "SELECT template_id, html_content FROM document_templates 
                            WHERE template_id = " . QuotedValue($documentData['template_id'], DataType::NUMBER);
                    $result = ExecuteRows($sql, "DB");
                    
                    if (empty($result)) {
                        throw new \Exception("Template not found");
                    }
                    
                    $templateId = $result[0]['template_id'];
                    $templateHtml = $result[0]['html_content'];
                } elseif (!empty($documentData['user_template_id'])) {
                    $sql = "SELECT ut.user_template_id, ut.template_id, ut.custom_content, ut.is_custom, dt.html_content
                            FROM user_templates ut
                            LEFT JOIN document_templates dt ON ut.template_id = dt.template_id
                            WHERE ut.user_template_id = " . QuotedValue($documentData['user_template_id'], DataType::NUMBER) . "
                            AND ut.user_id = " . QuotedValue($userId, DataType::NUMBER);
                    $result = ExecuteRows($sql, "DB");
                    
                    if (empty($result)) {
                        throw new \Exception("User template not found");
                    }
                    
                    $isUserTemplate = true;
                    $templateId = $result[0]['template_id'];
                    $templateHtml = $result[0]['is_custom'] ? $result[0]['custom_content'] : $result[0]['html_content'];
                }
                
                // Generate document HTML content using template and field data
                $documentHtml = $this->generateDocumentHtml($templateHtml, $documentData['document_data']);
                
                // Generate unique document reference
                $documentReference = $this->generateDocumentReference();
                
                // Insert document record
                $sql = "INSERT INTO documents (
                    user_id,
                    template_id,
                    document_title,
                    document_reference,
                    status_id, /* Changed from status */
                    company_name,
                    customs_entry_number,
                    date_of_entry,
                    document_html,
                    document_data,
                    parent_document_id,
                    version,
                    created_at,
                    updated_at
                ) VALUES (
                    " . QuotedValue($userId, DataType::NUMBER) . ",
                    " . ($templateId ? QuotedValue($templateId, DataType::NUMBER) : "NULL") . ",
                    " . QuotedValue($documentData['document_title'], DataType::STRING) . ",
                    " . QuotedValue($documentReference, DataType::STRING) . ",
                    (SELECT status_id FROM document_statuses WHERE status_code = 'draft'), /* Get ID for 'draft' */
                    " . QuotedValue($documentData['company_name'] ?? null, DataType::STRING) . ",
                    " . QuotedValue($documentData['customs_entry_number'] ?? null, DataType::STRING) . ",
                    " . QuotedValue($documentData['date_of_entry'] ?? null, DataType::DATE) . ",
                    " . QuotedValue($documentHtml, DataType::STRING) . ",
                    " . QuotedValue(json_encode($documentData['document_data']), DataType::STRING) . ",
                    NULL,
                    1,
                    CURRENT_TIMESTAMP,
                    CURRENT_TIMESTAMP
                ) RETURNING document_id";
            
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['document_id'])) {
                    throw new \Exception("Failed to create document");
                }
                
                $documentId = $result[0]['document_id'];
                
                // Insert document fields
                foreach ($documentData['document_data'] as $fieldName => $fieldValue) {
                    $fieldId = null;
                    
                    // Try to get field_id from template_fields if template_id is available
                    if ($templateId) {
                        $sql = "SELECT field_id FROM template_fields 
                                WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER) . "
                                AND field_name = " . QuotedValue($fieldName, DataType::STRING);
                        $fieldResult = ExecuteRows($sql, "DB");
                        
                        if (!empty($fieldResult)) {
                            $fieldId = $fieldResult[0]['field_id'];
                        }
                    }
                    
                    // Insert document field
                    $sql = "INSERT INTO document_fields (
                            document_id,
                            field_id,
                            field_value,
                            is_verified
                        ) VALUES (
                            " . QuotedValue($documentId, DataType::NUMBER) . ",
                            " . ($fieldId ? QuotedValue($fieldId, DataType::NUMBER) : "NULL") . ",
                            " . QuotedValue(is_array($fieldValue) ? json_encode($fieldValue) : $fieldValue, DataType::STRING) . ",
                            FALSE
                        )";
                    
                    Execute($sql, "DB");
                }
                
                // Add to activity log
                $this->addActivityLog(
                    $documentId,
                    $userId,
                    'create',
                    'Document created'
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Document created successfully',
                    'data' => [
                    'document_id' => $documentId,
                    'document_reference' => $documentReference,
                    'status' => 'draft',
                        'status_id' => ExecuteScalar("SELECT status_id FROM document_statuses WHERE status_code = 'draft'", "DB"),
                    'status_name' => 'Draft'
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
                'message' => 'Failed to create document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Upload supporting documents for a document
     * @param int $documentId Document ID
     * @param int $userId User ID
     * @param array $attachmentData Attachment data including uploaded file
     * @return array Response data
     */
    public function uploadAttachment($documentId, $userId, $attachmentData) {
        try {
            // Validate required fields
            if (empty($attachmentData['description'])) {
                return [
                    'success' => false,
                    'message' => 'Description is required',
                    'errors' => ['description' => ['Description is required']]
                ];
            }
            
            if (empty($attachmentData['attachment'])) {
                return [
                    'success' => false,
                    'message' => 'Attachment file is required',
                    'errors' => ['attachment' => ['Attachment file is required']]
                ];
            }
            
            // Check if document exists
            $sql = "SELECT document_id, status FROM documents WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            $document = $result[0];
            
            // Process uploaded file
            $attachment = $attachmentData['attachment'];
            if (is_array($attachment)) {
                // Handle array format
                $originalFileName = $attachment['name'] ?? '';
                $fileType = $attachment['type'] ?? '';
                $fileSize = $attachment['size'] ?? 0;
                $tempPath = $attachment['tmp_name'] ?? '';
                
                // Generate unique filename
                $filename = uniqid('attachment_', true) . '_' . $originalFileName;
                $uploadPath = 'uploads/attachments/' . $filename;
                
                // Ensure upload directory exists
                $uploadDir = dirname($uploadPath);
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Move the uploaded file
                move_uploaded_file($tempPath, $uploadPath);
            } else {
                // Handle PSR-7 UploadedFile object
                $originalFileName = $attachment->getClientFilename();
                $fileType = $attachment->getClientMediaType();
                $fileSize = $attachment->getSize();
                
                // Generate unique filename
                $filename = uniqid('attachment_', true) . '_' . $originalFileName;
                $uploadPath = 'uploads/attachments/' . $filename;
                
                // Ensure upload directory exists
                $uploadDir = dirname($uploadPath);
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Save uploaded file
                $attachment->moveTo($uploadPath);
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Insert attachment record
                $sql = "INSERT INTO document_attachments (
                        document_id,
                        file_name,
                        file_path,
                        file_type,
                        file_size,
                        description,
                        is_supporting,
                        uploaded_at,
                        uploaded_by
                    ) VALUES (
                        " . QuotedValue($documentId, DataType::NUMBER) . ",
                        " . QuotedValue($originalFileName, DataType::STRING) . ",
                        " . QuotedValue($uploadPath, DataType::STRING) . ",
                        " . QuotedValue($fileType, DataType::STRING) . ",
                        " . QuotedValue($fileSize, DataType::NUMBER) . ",
                        " . QuotedValue($attachmentData['description'], DataType::STRING) . ",
                        " . QuotedValue(isset($attachmentData['is_supporting']) ? (bool)$attachmentData['is_supporting'] : true, DataType::BOOLEAN) . ",
                        CURRENT_TIMESTAMP,
                        " . QuotedValue($userId, DataType::NUMBER) . "
                    ) RETURNING attachment_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['attachment_id'])) {
                    throw new \Exception("Failed to upload attachment");
                }
                
                $attachmentId = $result[0]['attachment_id'];
                
                // Add to activity log
                $this->addActivityLog(
                    $documentId,
                    $userId,
                    'upload_attachment',
                    'Attachment uploaded: ' . $originalFileName
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Attachment uploaded successfully',
                    'data' => [
                        'attachment_id' => $attachmentId,
                        'file_name' => $originalFileName,
                        'file_type' => $fileType,
                        'file_size' => $fileSize,
                        'uploaded_at' => date('Y-m-d H:i:s')
                    ]
                ];
            } catch (\Exception $e) {
                // Rollback transaction on error
                Execute("ROLLBACK", "DB");
                
                // Delete uploaded file if it exists
                if (file_exists($uploadPath)) {
                    unlink($uploadPath);
                }
                
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to upload attachment: ' . $e->getMessage()
            ];
        }
    }
    /**
     * Generate preview for a new document before saving
     * @param int $userId User ID
     * @param array $previewData Preview data
     * @return array Response data
     */
    public function generateDocumentPreview($userId, $previewData) {
        try {
            // Validate required fields
            if (empty($previewData['template_id'])) {
                return [
                    'success' => false,
                    'message' => 'Template ID is required',
                    'errors' => ['template_id' => ['Template ID is required']]
                ];
            }
            
            if (empty($previewData['document_data']) || !is_array($previewData['document_data'])) {
                return [
                    'success' => false,
                    'message' => 'Document data is required and must be an object',
                    'errors' => ['document_data' => ['Document data is required and must be an object']]
                ];
            }
            
            // Get template details
            $sql = "SELECT template_id, template_name, html_content FROM document_templates 
                    WHERE template_id = " . QuotedValue($previewData['template_id'], DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Template not found'
                ];
            }
            
            $template = $result[0];
            
            // Generate document HTML content using template and field data
            $previewHtml = $this->generateDocumentHtml($template['html_content'], $previewData['document_data']);
            
            // Clean up any extra whitespace/line breaks
            $previewHtml = trim($previewHtml);
            
            // For debugging purposes, log a summary of the generated HTML
            LogError("Preview HTML generated, length: " . strlen($previewHtml));
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'preview_html' => $previewHtml,
                    'document_title' => $previewData['document_title'] ?? $template['template_name']
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to generate document preview: ' . $e->getMessage()
            ];
        }
    }    
    /**
     * Get all attachments for a document
     * @param int $documentId Document ID
     * @return array Response data
     */
    public function getAttachments($documentId) {
        try {
            // Check if document exists
            $sql = "SELECT document_id FROM documents WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            // Get document attachments
            $sql = "SELECT
                    attachment_id,
                    file_name,
                    file_type,
                    file_size,
                    description,
                    is_supporting,
                    uploaded_at,
                    uploaded_by
                FROM
                    document_attachments
                WHERE
                    document_id = " . QuotedValue($documentId, DataType::NUMBER) . "
                ORDER BY
                    uploaded_at DESC";
            
            $attachments = ExecuteRows($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'data' => $attachments
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get attachments: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete an attachment from a document
     * @param int $documentId Document ID
     * @param int $attachmentId Attachment ID
     * @param int $userId User ID
     * @return array Response data
     */
    public function deleteAttachment($documentId, $attachmentId, $userId) {
        try {
            // Check if document exists
            $sql = "SELECT d.document_id, ds.status_code as status FROM documents d JOIN document_statuses ds ON d.status_id = ds.status_id WHERE d.document_id = " . QuotedValue($documentId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            $document = $result[0];
            
            // Check if document is in draft status
            if ($document['status'] !== 'draft') {
                return [
                    'success' => false,
                    'message' => 'Attachments can only be deleted for documents in draft status'
                ];
            }
            
            // Check if attachment exists and belongs to the document
            $sql = "SELECT
                    attachment_id,
                    file_name,
                    file_path
                FROM
                    document_attachments
                WHERE
                    attachment_id = " . QuotedValue($attachmentId, DataType::NUMBER) . "
                    AND document_id = " . QuotedValue($documentId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Attachment not found or does not belong to the document'
                ];
            }
            
            $attachment = $result[0];
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Delete attachment record
                $sql = "DELETE FROM document_attachments 
                        WHERE attachment_id = " . QuotedValue($attachmentId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Delete file if it exists
                if (!empty($attachment['file_path']) && file_exists($attachment['file_path'])) {
                    unlink($attachment['file_path']);
                }
                
                // Add to activity log
                $this->addActivityLog(
                    $documentId,
                    $userId,
                    'delete_attachment',
                    'Attachment deleted: ' . $attachment['file_name']
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Attachment deleted successfully'
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
                'message' => 'Failed to delete attachment: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update a document (draft status only)
     * @param int $documentId Document ID
     * @param array $documentData Document data
     * @param int $userId User ID
     * @return array Response data
     */
    public function updateDocument($documentId, $documentData, $userId) {
        try {
            // Validate required fields
            if (empty($documentData['document_title'])) {
                return [
                    'success' => false,
                    'message' => 'Document title is required',
                    'errors' => ['document_title' => ['Document title is required']]
                ];
            }
            
            // Validate document_data
            if (empty($documentData['document_data']) || !is_array($documentData['document_data'])) {
                return [
                    'success' => false,
                    'message' => 'Document data is required and must be an object',
                    'errors' => ['document_data' => ['Document data is required and must be an object']]
                ];
            }
            
            // Check if document exists and is in draft status
            $sql = "SELECT
                    d.document_id,
                    d.status_id,
                    ds.status_code as status,
                    ds.status_name,
                    d.template_id,
                    d.document_html,
                    d.document_data,
                    dt.html_content AS template_html
                FROM
                    documents d
                JOIN document_statuses ds ON d.status_id = ds.status_id
                LEFT JOIN
                    document_templates dt ON d.template_id = dt.template_id
                WHERE
                    d.document_id = " . QuotedValue($documentId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            $document = $result[0];
            
            if ($document['status'] !== 'draft') {
                return [
                    'success' => false,
                    'message' => 'Only documents in draft status can be updated'
                ];
            }
            
            // Generate updated document HTML
            $updatedHtml = $this->generateDocumentHtml($document['template_html'], $documentData['document_data']);
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Update document record
                $sql = "UPDATE documents SET
                        document_title = " . QuotedValue($documentData['document_title'], DataType::STRING) . ",
                        company_name = " . QuotedValue($documentData['company_name'] ?? null, DataType::STRING) . ",
                        customs_entry_number = " . QuotedValue($documentData['customs_entry_number'] ?? null, DataType::STRING) . ",
                        date_of_entry = " . QuotedValue($documentData['date_of_entry'] ?? null, DataType::DATE) . ",
                        document_html = " . QuotedValue($updatedHtml, DataType::STRING) . ",
                        document_data = " . QuotedValue(json_encode($documentData['document_data']), DataType::STRING) . ",
                        updated_at = CURRENT_TIMESTAMP
                        WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Delete existing document fields
                $sql = "DELETE FROM document_fields WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
                Execute($sql, "DB");
                
                // Insert updated document fields
                foreach ($documentData['document_data'] as $fieldName => $fieldValue) {
                    $fieldId = null;
                    
                    // Try to get field_id from template_fields if template_id is available
                    if ($document['template_id']) {
                        $sql = "SELECT field_id FROM template_fields 
                                WHERE template_id = " . QuotedValue($document['template_id'], DataType::NUMBER) . "
                                AND field_name = " . QuotedValue($fieldName, DataType::STRING);
                        $fieldResult = ExecuteRows($sql, "DB");
                        
                        if (!empty($fieldResult)) {
                            $fieldId = $fieldResult[0]['field_id'];
                        }
                    }
                    
                    // Insert document field
                    $sql = "INSERT INTO document_fields (
                            document_id,
                            field_id,
                            field_value,
                            is_verified
                        ) VALUES (
                            " . QuotedValue($documentId, DataType::NUMBER) . ",
                            " . ($fieldId ? QuotedValue($fieldId, DataType::NUMBER) : "NULL") . ",
                            " . QuotedValue(is_array($fieldValue) ? json_encode($fieldValue) : $fieldValue, DataType::STRING) . ",
                            FALSE
                        )";
                    
                    Execute($sql, "DB");
                }
                
                // Add to activity log
                $this->addActivityLog(
                    $documentId,
                    $userId,
                    'update',
                    'Document updated'
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Document updated successfully'
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
                'message' => 'Failed to update document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get detailed information about a document
     * @param int $documentId Document ID
     * @return array Response data
     */
    public function getDocumentDetails($documentId) {
        try {
            // Get document details
            $sql = "SELECT
                    d.document_id,
                    d.document_reference,
                    d.document_title,
                    d.template_id,
                    dt.template_name,
                    ds.status_code AS status,
                    ds.status_name,
                    d.status_id,
                    d.created_at,
                    d.updated_at,
                    d.submitted_at,
                    d.company_name,
                    d.customs_entry_number,
                    d.date_of_entry,
                    d.document_html,
                    d.document_data,
                    d.notes,
                    d.is_deleted,
                    d.parent_document_id,
                    d.version
                FROM
                    documents d
                JOIN
                    document_statuses ds ON d.status_id = ds.status_id
                LEFT JOIN
                    document_templates dt ON d.template_id = dt.template_id
                WHERE
                    d.document_id = " . QuotedValue($documentId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            $document = $result[0];
            
            // Convert JSON string to object if needed
            if (is_string($document['document_data'])) {
                $document['document_data'] = json_decode($document['document_data'], true);
            }
            
            // Get request info if available
            $sql = "SELECT
                    r.request_id,
                    r.status,
                    r.requested_at,
                    r.notary_id,
                    CONCAT(u.first_name, ' ', u.last_name) AS notary_name,
                    r.payment_status
                FROM
                    notarization_requests r
                LEFT JOIN
                    users u ON r.notary_id = u.user_id
                WHERE
                    r.document_id = " . QuotedValue($documentId, DataType::NUMBER);
            
            $requestInfo = ExecuteRows($sql, "DB");
            
            if (!empty($requestInfo)) {
                $document['request_info'] = $requestInfo[0];
            }
            
            // Return success response
            return [
                'success' => true,
                'data' => $document
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get document details: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get HTML preview of a document
     * @param int $documentId Document ID
     * @return array Response data
     */
    public function getDocumentPreview($documentId) {
        try {
            // Get document HTML content
            $sql = "SELECT
                    document_title,
                    document_html
                FROM
                    documents
                WHERE
                    document_id = " . QuotedValue($documentId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (isset($result[0]['document_html']) && is_string($result[0]['document_html'])) {
                // Replace literal escaped newlines with actual newlines
                $result[0]['document_html'] = str_replace('\\n', "", $result[0]['document_html']);
                
                // Normalize line breaks
                $result[0]['document_html'] = str_replace("\r\n", "", $result[0]['document_html']);
                $result[0]['document_html'] = str_replace("\r", "", $result[0]['document_html']);
                
            }


            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'document_html' => $result[0]['document_html'],
                    'document_title' => $result[0]['document_title']
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get document preview: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * List documents for the authenticated user
     * @param int $userId User ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function listUserDocuments($userId, $params = []) {
        try {
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Filter parameters
            $status = isset($params['status']) && in_array($params['status'], ['draft', 'submitted', 'pending_payment', 'in_queue', 'processing', 'notarized', 'rejected']) 
                ? $params['status'] 
                : null;
            
            $search = isset($params['search']) ? trim($params['search']) : null;
            
            // Build WHERE clause
            $where = "d.user_id = " . QuotedValue($userId, DataType::NUMBER) . " AND d.is_deleted = false";

            if ($status) {
                $where .= " AND ds.status_code = " . QuotedValue($status, DataType::STRING);
            }
            
            
            if ($search) {
                $where .= " AND (
                    d.document_title ILIKE " . QuotedValue('%' . $search . '%', DataType::STRING) . " OR 
                    d.document_reference ILIKE " . QuotedValue('%' . $search . '%', DataType::STRING) . "
                )";
            }
            
            // Query documents
            $sql = "SELECT
                d.document_id,
                d.document_reference,
                d.document_title,
                dt.template_name,
                ds.status_code AS status,
                ds.status_name,
                d.status_id,
                d.created_at,
                d.updated_at,
                d.submitted_at,
                COALESCE(r.status, '') AS request_status,
                COALESCE(r.payment_status, '') AS payment_status,
                d.version
            FROM
                documents d
            JOIN
                document_statuses ds ON d.status_id = ds.status_id
            LEFT JOIN
                document_templates dt ON d.template_id = dt.template_id
            LEFT JOIN
                notarization_requests r ON d.document_id = r.document_id
            WHERE
                " . $where . "
            ORDER BY
                CASE 
                    WHEN ds.status_code = 'draft' THEN d.updated_at
                    WHEN ds.status_code = 'submitted' THEN d.submitted_at
                    ELSE d.updated_at
                END DESC
            LIMIT " . $perPage . " OFFSET " . $offset;
                
            $result = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total FROM documents d JOIN document_statuses ds ON d.status_id = ds.status_id WHERE " . $where;
            $resultCount = ExecuteRows($sqlCount, "DB");
            $total = $resultCount[0]['total'] ?? 0;
            
            // Calculate pagination metadata
            $totalPages = ceil($total / $perPage);
            
            // Return success response
            return [
                'success' => true,
                'data' => $result,
                'meta' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to list documents: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a document (draft status only)
     * @param int $documentId Document ID
     * @param int $userId User ID
     * @return array Response data
     */
    public function deleteDocument($documentId, $userId) {
        try {
            // Check if document exists and is in draft status
            $sql = "SELECT d.document_id, d.status_id, ds.status_code as status, ds.status_name 
                FROM documents d
                JOIN document_statuses ds ON d.status_id = ds.status_id
                WHERE d.document_id = " . QuotedValue($documentId, DataType::NUMBER);
                
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            $document = $result[0];
            
            if ($document['status'] !== 'draft') {
                return [
                    'success' => false,
                    'message' => 'Only documents in draft status can be deleted'
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Soft delete document by setting is_deleted flag
                $sql = "UPDATE documents SET
                        is_deleted = true,
                        updated_at = CURRENT_TIMESTAMP
                        WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
                
                Execute($sql, "DB");
                
                // Add to activity log
                $this->addActivityLog(
                    $documentId,
                    $userId,
                    'delete',
                    'Document deleted'
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Document deleted successfully'
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
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get document status summary for the user
     * @param int $userId User ID
     * @return array Response data
     */
    public function getDocumentSummary($userId) {
        try {
            // Get document counts by status
            $sql = "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN ds.status_code = 'draft' THEN 1 ELSE 0 END) AS draft,
                SUM(CASE WHEN ds.status_code = 'submitted' THEN 1 ELSE 0 END) AS submitted,
                SUM(CASE WHEN ds.status_code = 'pending_payment' THEN 1 ELSE 0 END) AS pending_payment,
                SUM(CASE WHEN ds.status_code = 'in_queue' THEN 1 ELSE 0 END) AS in_queue,
                SUM(CASE WHEN ds.status_code = 'processing' THEN 1 ELSE 0 END) AS processing,
                SUM(CASE WHEN ds.status_code = 'notarized' THEN 1 ELSE 0 END) AS notarized,
                SUM(CASE WHEN ds.status_code = 'rejected' THEN 1 ELSE 0 END) AS rejected
            FROM
                documents d
            JOIN
                document_statuses ds ON d.status_id = ds.status_id
            WHERE
                d.user_id = " . QuotedValue($userId, DataType::NUMBER) . "
                AND d.is_deleted = false";            
            $statusCounts = ExecuteRows($sql, "DB");
            
            // Get recent document updates
            $sql = "SELECT
                    d.document_id,
                    d.document_title,
                    ds.status_code AS status,
                    d.status_id,
                    ds.status_name,
                    d.updated_at
                FROM
                    documents d
                JOIN
                    document_statuses ds ON d.status_id = ds.status_id
                WHERE
                    d.user_id = " . QuotedValue($userId, DataType::NUMBER) . "
                    AND d.is_deleted = false
                ORDER BY
                    d.updated_at DESC
                LIMIT 5";
            
            $recentUpdates = ExecuteRows($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'total' => (int)($statusCounts[0]['total'] ?? 0),
                    'by_status' => [
                        'draft' => (int)($statusCounts[0]['draft'] ?? 0),
                        'submitted' => (int)($statusCounts[0]['submitted'] ?? 0),
                        'pending_payment' => (int)($statusCounts[0]['pending_payment'] ?? 0),
                        'in_queue' => (int)($statusCounts[0]['in_queue'] ?? 0),
                        'processing' => (int)($statusCounts[0]['processing'] ?? 0),
                        'notarized' => (int)($statusCounts[0]['notarized'] ?? 0),
                        'rejected' => (int)($statusCounts[0]['rejected'] ?? 0)
                    ],
                    'recent_updates' => $recentUpdates
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get document summary: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Convert document to PDF format
     * @param int $documentId Document ID
     * @param int $userId User ID
     * @param array $options Conversion options
     * @return array Response data
     */
    public function convertToPdf($documentId, $userId, $options = []) {
        try {
            // Get document details with status information
            $sql = "SELECT
                    d.document_id,
                    d.document_title,
                    d.document_html,
                    d.status_id,
                    ds.status_code, 
                    ds.status_name
                FROM
                    documents d
                JOIN
                    document_statuses ds ON d.status_id = ds.status_id
                WHERE
                    d.document_id = " . QuotedValue($documentId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            $document = $result[0];

            if (isset($document['document_html']) && is_string($document['document_html'])) {
                // Replace literal escaped newlines with actual newlines
                $document['document_html'] = str_replace('\\n', "", $document['document_html']);
                
                // Normalize line breaks
                $document['document_html'] = str_replace("\r\n", "", $document['document_html']);
                $document['document_html'] = str_replace("\r", "", $document['document_html']);
                
            }            
            
            // Process options
            $includeSupportingDocs = isset($options['include_supporting_docs']) ? (bool)$options['include_supporting_docs'] : false;
            $pageSize = isset($options['page_size']) ? $options['page_size'] : 'A4';
            $orientation = isset($options['orientation']) ? $options['orientation'] : 'portrait';
            
            // Get supporting documents if requested
            $supportingDocs = [];
            if ($includeSupportingDocs) {
                $sql = "SELECT
                        attachment_id,
                        file_name,
                        file_path,
                        file_type
                    FROM
                        document_attachments
                    WHERE
                        document_id = " . QuotedValue($documentId, DataType::NUMBER) . "
                        AND is_supporting = true";
                
                $supportingDocs = ExecuteRows($sql, "DB");
            }
            
            // Check if DOMPDF is available
            if (!class_exists('\Dompdf\Dompdf')) {
                throw new \Exception('DOMPDF library not found. Please install it using: composer require dompdf/dompdf');
            }
            
            // Initialize DOMPDF
            $dompdf = new \Dompdf\Dompdf();
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);
            
            // Set paper size and orientation
            $dompdf->setPaper($pageSize, $orientation);
            
            // Load HTML content
            $dompdf->loadHtml($document['document_html']);
            
            // Render PDF
            $dompdf->render();
            
            // Generate a unique filename for the PDF
            $filename = uniqid('document_', true) . '.pdf';
            $pdfPath = 'uploads/pdfs/' . $filename;
            
            // Ensure directory exists
            $pdfDir = dirname($pdfPath);
            if (!is_dir($pdfDir)) {
                mkdir($pdfDir, 0755, true);
            }
            
            // Save PDF file
            file_put_contents($pdfPath, $dompdf->output());
            
            // If supporting documents should be included, append them to the PDF
            if ($includeSupportingDocs && !empty($supportingDocs)) {
                // For simplicity, this example doesn't implement the actual PDF merging
                // In a real implementation, you would use a library like FPDI to merge PDFs
                // For now, we'll just log that supporting documents should be included
                LogError("Supporting documents should be included in PDF: " . count($supportingDocs));
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Create PDF metadata record
                $sql = "INSERT INTO pdf_metadata (
                        document_id,
                        notarized_id,
                        pdf_type,
                        file_path,
                        file_size,
                        page_count,
                        is_final,
                        processing_options,
                        generated_at,
                        generated_by
                    ) VALUES (
                        " . QuotedValue($documentId, DataType::NUMBER) . ",
                        NULL,
                        " . QuotedValue($document['status_code'], DataType::STRING) . ", -- Use status_code
                        " . QuotedValue($pdfPath, DataType::STRING) . ",
                        " . QuotedValue(filesize($pdfPath), DataType::NUMBER) . ",
                        " . QuotedValue(1, DataType::NUMBER) . ", -- Basic page count
                        false,
                        " . QuotedValue(json_encode($options), DataType::STRING) . ",
                        CURRENT_TIMESTAMP,
                        " . QuotedValue($userId, DataType::NUMBER) . "
                    )";
                
                Execute($sql, "DB");
                
                // Add to activity log
                $this->addActivityLog(
                    $documentId,
                    $userId,
                    'convert_to_pdf',
                    'Document converted to PDF (' . $document['status_name'] . ')'
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Document converted to PDF successfully',
                    'data' => [
                        'pdf_path' => $pdfPath,
                        'status' => $document['status_code'],
                        'status_name' => $document['status_name'],
                        'page_count' => 1, // Basic page count
                        'file_size' => filesize($pdfPath)
                    ]
                ];
            } catch (\Exception $e) {
                // Rollback transaction on error
                Execute("ROLLBACK", "DB");
                
                // Delete generated PDF if it exists
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
                
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to convert document to PDF: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Render document with field data for preview
     * @param int $documentId Document ID
     * @param int $userId User ID
     * @param array $options Rendering options
     * @return array Response data
     */
    public function renderDocument($documentId, $userId, $options = []) {
        try {
            // Get document details
            $sql = "SELECT
                    document_id,
                    document_title,
                    document_html,
                    document_data
                FROM
                    documents
                WHERE
                    document_id = " . QuotedValue($documentId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            $document = $result[0];
            
            // Process options
            $format = isset($options['format']) && in_array($options['format'], ['pdf', 'html']) ? $options['format'] : 'pdf';
            $includeSupportingDocs = isset($options['include_supporting_docs']) ? (bool)$options['include_supporting_docs'] : false;
            $includeWatermark = isset($options['include_watermark']) ? (bool)$options['include_watermark'] : false;
            
            // If HTML format requested, return HTML content directly
            if ($format === 'html') {
                // Add watermark if requested
                $htmlContent = $document['document_html'];
                if ($includeWatermark) {
                    // Add watermark to HTML content
                    $watermarkStyle = '<style>.watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 72px; opacity: 0.2; z-index: 1000; color: #ff0000; }</style>';
                    $watermarkDiv = '<div class="watermark">DRAFT</div>';
                    $htmlContent = $watermarkStyle . $htmlContent . $watermarkDiv;
                }
                
                // Return success response with HTML content
                return [
                    'success' => true,
                    'data' => [
                        'render_url' => null,
                        'format' => 'html',
                        'html_content' => $htmlContent
                    ]
                ];
            }
            
            // For PDF format, generate a PDF file
            // In a real implementation, this would create a PDF file using a library like mPDF or TCPDF
            // For now, we'll just simulate the PDF generation
            
            // Generate a unique filename for the render
            $filename = uniqid('render_', true) . '.pdf';
            $renderPath = 'uploads/renders/' . $filename;
            
            // Ensure directory exists
            $renderDir = dirname($renderPath);
            if (!is_dir($renderDir)) {
                mkdir($renderDir, 0755, true);
            }
            
            // Simulate PDF creation (in a real implementation, this would be replaced with actual PDF generation)
            file_put_contents($renderPath, "PDF Render for Document: " . $document['document_title']);
            
            // Set expiry time (1 hour from now)
            $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Add to activity log
            $this->addActivityLog(
                $documentId,
                $userId,
                'render_document',
                'Document rendered for preview'
            );
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'render_url' => $renderPath,
                    'format' => 'pdf',
                    'page_count' => 1, // Simulated page count
                    'expires_at' => $expiryTime
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to render document: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Merge selected supporting documents with the main document
     * @param int $documentId Document ID
     * @param array $options Merge options
     * @param int $userId User ID
     * @return array Response data
     */
    public function mergeAttachments($documentId, $options, $userId) {
        try {
            // Validate required options
            if (empty($options['attachment_ids']) || !is_array($options['attachment_ids'])) {
                return [
                    'success' => false,
                    'message' => 'Attachment IDs are required',
                    'errors' => ['attachment_ids' => ['Attachment IDs are required']]
                ];
            }
            
            // Get document details
            $sql = "SELECT
                    document_id,
                    document_title
                FROM
                    documents
                WHERE
                    document_id = " . QuotedValue($documentId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            $document = $result[0];
            
            // Get attachments to merge
            $attachmentIdsPlaceholder = implode(', ', array_map(function($id) {
                return QuotedValue($id, DataType::NUMBER);
            }, $options['attachment_ids']));
            
            $sql = "SELECT
                    attachment_id,
                    file_name,
                    file_path,
                    file_type
                FROM
                    document_attachments
                WHERE
                    document_id = " . QuotedValue($documentId, DataType::NUMBER) . "
                    AND attachment_id IN (" . $attachmentIdsPlaceholder . ")
                ORDER BY
                    ARRAY_POSITION(ARRAY[" . $attachmentIdsPlaceholder . "], attachment_id::text::int)";
            
            $attachments = ExecuteRows($sql, "DB");
            
            if (empty($attachments)) {
                return [
                    'success' => false,
                    'message' => 'No valid attachments found'
                ];
            }
            
            // Process options
            $appendToMain = isset($options['append_to_main']) ? (bool)$options['append_to_main'] : true;
            $includeCoverPage = isset($options['include_cover_page']) ? (bool)$options['include_cover_page'] : false;
            
            // Generate a merged PDF file
            // In a real implementation, this would create a PDF file by merging the document and attachments
            // For now, we'll just simulate the PDF generation
            
            // Generate a unique filename for the merged PDF
            $filename = uniqid('merged_', true) . '.pdf';
            $mergedPath = 'uploads/merged/' . $filename;
            
            // Ensure directory exists
            $mergedDir = dirname($mergedPath);
            if (!is_dir($mergedDir)) {
                mkdir($mergedDir, 0755, true);
            }
            
            // Simulate PDF creation (in a real implementation, this would be replaced with actual PDF generation)
            file_put_contents($mergedPath, "Merged PDF for Document: " . $document['document_title']);
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Create PDF metadata record for the merged document
                $sql = "INSERT INTO pdf_metadata (
                        document_id,
                        notarized_id,
                        pdf_type,
                        file_path,
                        page_count,
                        is_final,
                        processing_options,
                        created_at
                    ) VALUES (
                        " . QuotedValue($documentId, DataType::NUMBER) . ",
                        NULL,
                        'merged',
                        " . QuotedValue($mergedPath, DataType::STRING) . ",
                        " . QuotedValue(count($attachments) + 1, DataType::NUMBER) . ", -- Main document + attachments
                        false,
                        " . QuotedValue(json_encode($options), DataType::STRING) . ",
                        CURRENT_TIMESTAMP
                    )";
                
                Execute($sql, "DB");
                
                // Add to activity log
                $this->addActivityLog(
                    $documentId,
                    $userId,
                    'merge_attachments',
                    'Document merged with attachments'
                );
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Documents merged successfully',
                    'data' => [
                        'merged_document_path' => $mergedPath,
                        'page_count' => count($attachments) + 1, // Main document + attachments
                        'file_size' => filesize($mergedPath)
                    ]
                ];
            } catch (\Exception $e) {
                // Rollback transaction on error
                Execute("ROLLBACK", "DB");
                
                // Delete generated PDF if it exists
                if (file_exists($mergedPath)) {
                    unlink($mergedPath);
                }
                
                throw $e;
            }
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to merge documents: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get activity history for a document
     * @param int $documentId Document ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getDocumentActivityHistory($documentId, $params = []) {
        try {
            // Check if document exists
            $sql = "SELECT document_id FROM documents WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Query activity logs
            $sql = "SELECT
                    l.log_id,
                    l.document_id,
                    l.user_id,
                    CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                    l.action,
                    l.details,
                    l.created_at,
                    l.ip_address
                FROM
                    document_activity_logs l
                JOIN
                    users u ON l.user_id = u.user_id
                WHERE
                    l.document_id = " . QuotedValue($documentId, DataType::NUMBER) . "
                ORDER BY
                    l.created_at DESC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $activityLogs = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total FROM document_activity_logs WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
            $resultCount = ExecuteRows($sqlCount, "DB");
            $total = $resultCount[0]['total'] ?? 0;
            
            // Calculate pagination metadata
            $totalPages = ceil($total / $perPage);
            
            // Return success response
            return [
                'success' => true,
                'data' => $activityLogs,
                'meta' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get document activity history: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Add activity log entry for a document
     * @param int $documentId Document ID
     * @param array $activityData Activity data
     * @param int $userId User ID
     * @return array Response data
     */
    public function addDocumentActivity($documentId, $activityData, $userId) {
        try {
            // Validate required fields
            if (empty($activityData['action'])) {
                return [
                    'success' => false,
                    'message' => 'Action is required',
                    'errors' => ['action' => ['Action is required']]
                ];
            }
            
            if (empty($activityData['details'])) {
                return [
                    'success' => false,
                    'message' => 'Details are required',
                    'errors' => ['details' => ['Details are required']]
                ];
            }
            
            // Check if document exists
            $sql = "SELECT document_id FROM documents WHERE document_id = " . QuotedValue($documentId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Document not found'
                ];
            }
            
            // Add activity log
            $added = $this->addActivityLog(
                $documentId,
                $userId,
                $activityData['action'],
                $activityData['details']
            );
            
            if (!$added) {
                return [
                    'success' => false,
                    'message' => 'Failed to add activity log'
                ];
            }
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Activity logged successfully'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to add activity log: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate HTML content for a document using template and field data
     * @param string $templateHtml Template HTML content
     * @param array $fieldData Field data
     * @return string Generated HTML content
     */
    private function generateDocumentHtml($templateHtml, $fieldData) {
        // In a real implementation, this would parse the template and replace placeholders with field data
        
        // Make sure template HTML is not null to avoid deprecation warning
        if ($templateHtml === null) {
            $templateHtml = '';
        }
        
        // Process the template HTML to handle escaped newlines and normalize line breaks
        $templateHtml = str_replace('\\n', "\n", $templateHtml); // Replace literal \n with actual newlines
        $templateHtml = str_replace("\r\n", "\n", $templateHtml); // Normalize CRLF to LF
        $templateHtml = str_replace("\r", "\n", $templateHtml); // Normalize CR to LF
        
        // Trim to remove any extraneous whitespace
        $html = trim($templateHtml);
        
        // Replace field placeholders
        foreach ($fieldData as $fieldName => $fieldValue) {
            // Handle array values (e.g., for checkboxes or multi-select)
            if (is_array($fieldValue)) {
                $fieldValue = implode(', ', $fieldValue);
            }
            
            // Make sure fieldValue is not null to avoid deprecation warning
            if ($fieldValue === null) {
                $fieldValue = '';
            }
            
            // Clean and normalize line breaks in text fields
            if (is_string($fieldValue)) {
                // Normalize line breaks to prevent mixed breaks
                $fieldValue = str_replace("\r\n", "\n", $fieldValue);
                $fieldValue = str_replace("\r", "\n", $fieldValue);
            }
            
            // Replace placeholders in format {{field_name}}
            $html = str_replace('{{' . $fieldName . '}}', htmlspecialchars($fieldValue), $html);
        }
        
        return $html;
    }
    
    /**
     * Generate unique document reference
     * @return string Document reference
     */
    private function generateDocumentReference() {
        // Generate a unique alphanumeric reference (12 characters)
        $prefix = 'DOC';
        $timestamp = substr(time(), -6);
        $random = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3);
        
        return $prefix . $timestamp . $random;
    }
    
    /**
     * Add activity log for a document
     * @param int $documentId Document ID
     * @param int $userId User ID
     * @param string $action Action name
     * @param string $details Action details
     * @return bool Success status
     */
    private function addActivityLog($documentId, $userId, $action, $details) {
        try {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
            
            $sql = "INSERT INTO document_activity_logs (
                    document_id,
                    user_id,
                    action,
                    details,
                    ip_address,
                    created_at
                ) VALUES (
                    " . QuotedValue($documentId, DataType::NUMBER) . ",
                    " . QuotedValue($userId, DataType::NUMBER) . ",
                    " . QuotedValue($action, DataType::STRING) . ",
                    " . QuotedValue($details, DataType::STRING) . ",
                    " . QuotedValue($ipAddress, DataType::STRING) . ",
                    CURRENT_TIMESTAMP
                )";
            
            Execute($sql, "DB");
            
            return true;
        } catch (\Exception $e) {
            LogError('Failed to add activity log: ' . $e->getMessage());
            return false;
        }
    }
}
