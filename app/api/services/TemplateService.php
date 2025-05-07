<?php
// app/api/services/TemplateService.php
namespace PHPMaker2024\eNotary;

class TemplateService {
    /**
     * Get all template categories
     * @return array Response data
     */
    public function getCategories() {
        try {
            $sql = "SELECT
                    category_id,
                    category_name,
                    description,
                    is_active
                FROM
                    template_categories
                WHERE
                    is_active = true
                ORDER BY
                    category_name ASC";
            
            $result = ExecuteRows($sql, "DB");
            
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
                'message' => 'Failed to get template categories: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * List document templates with pagination and filtering
     * @param array $params Query parameters
     * @return array Response data
     */
    public function listTemplates($params = []) {
        try {
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Filter parameters
            $categoryId = isset($params['category_id']) ? (int)$params['category_id'] : null;
            $search = isset($params['search']) ? trim($params['search']) : null;
            
            // Build WHERE clause
            $where = "dt.is_active = true";
            
            if ($categoryId) {
                $where .= " AND dt.category_id = " . QuotedValue($categoryId, DataType::NUMBER);
            }
            
            if ($search) {
                $where .= " AND (
                    dt.template_name ILIKE " . QuotedValue('%' . $search . '%', DataType::STRING) . " OR 
                    dt.template_code ILIKE " . QuotedValue('%' . $search . '%', DataType::STRING) . " OR
                    tc.category_name ILIKE " . QuotedValue('%' . $search . '%', DataType::STRING) . "
                )";
            }
            
            // Query templates
            $sql = "SELECT
                    dt.template_id,
                    dt.template_name,
                    dt.template_code,
                    dt.category_id,
                    tc.category_name,
                    dt.description,
                    dt.is_active,
                    dt.template_type,
                    dt.fee_amount,
                    dt.created_at
                FROM
                    document_templates dt
                JOIN
                    template_categories tc ON dt.category_id = tc.category_id
                WHERE
                    " . $where . "
                ORDER BY
                    dt.template_name ASC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $result = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total FROM document_templates dt
                          JOIN template_categories tc ON dt.category_id = tc.category_id
                          WHERE " . $where;
            
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
                'message' => 'Failed to list templates: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get detailed information about a specific template
     * @param int $templateId Template ID
     * @return array Response data
     */
    public function getTemplateDetails($templateId) {
        try {
            // Get template details
            $sql = "SELECT
                    dt.template_id,
                    dt.template_name,
                    dt.template_code,
                    dt.category_id,
                    tc.category_name,
                    dt.description,
                    dt.html_content,
                    dt.is_active,
                    dt.template_type,
                    dt.fee_amount,
                    dt.notary_required,
                    dt.header_text,
                    dt.footer_text,
                    dt.created_at
                FROM
                    document_templates dt
                JOIN
                    template_categories tc ON dt.category_id = tc.category_id
                WHERE
                    dt.template_id = " . QuotedValue($templateId, DataType::NUMBER) . "
                    AND dt.is_active = true";
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Template not found'
                ];
            }
            
            $template = $result[0];
            
            // Get template fields
            $sql = "SELECT
                    field_id,
                    field_name,
                    field_label,
                    field_type,
                    field_options,
                    is_required,
                    placeholder,
                    default_value,
                    field_order,
                    validation_rules,
                    help_text,
                    field_width,
                    section_name
                FROM
                    template_fields
                WHERE
                    template_id = " . QuotedValue($templateId, DataType::NUMBER) . "
                ORDER BY
                    field_order ASC";
            
            $fields = ExecuteRows($sql, "DB");
            
            // Process field options (convert from string to array if needed)
            foreach ($fields as &$field) {
                if (!empty($field['field_options']) && is_string($field['field_options'])) {
                    $fieldOptions = json_decode($field['field_options'], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $field['field_options'] = $fieldOptions;
                    } else {
                        // If not valid JSON, try comma-separated string
                        $field['field_options'] = explode(',', $field['field_options']);
                    }
                } else {
                    $field['field_options'] = [];
                }
            }
            
            // Add fields to template data
            $template['fields'] = $fields;
            
            // Return success response
            return [
                'success' => true,
                'data' => $template
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get template details: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get templates saved by the user
     * @param int $userId User ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getUserTemplates($userId, $params = []) {
        try {
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Query user templates
            $sql = "SELECT
                    ut.user_template_id,
                    ut.template_id,
                    COALESCE(dt.template_name, 'Custom Template') AS template_name,
                    ut.custom_name,
                    tc.category_name,
                    ut.is_custom,
                    ut.created_at,
                    ut.updated_at
                FROM
                    user_templates ut
                LEFT JOIN
                    document_templates dt ON ut.template_id = dt.template_id
                LEFT JOIN
                    template_categories tc ON dt.category_id = tc.category_id
                WHERE
                    ut.user_id = " . QuotedValue($userId, DataType::NUMBER) . "
                ORDER BY
                    ut.updated_at DESC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $result = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total FROM user_templates WHERE user_id = " . QuotedValue($userId, DataType::NUMBER);
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
                'message' => 'Failed to get user templates: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Save a template to user's templates
     * @param int $userId User ID
     * @param array $templateData Template data
     * @return array Response data
     */
    public function saveUserTemplate($userId, $templateData) {
        try {
            // Validate required fields
            if (empty($templateData['custom_name'])) {
                return [
                    'success' => false,
                    'message' => 'Custom name is required',
                    'errors' => ['custom_name' => ['Custom name is required']]
                ];
            }
            
            // Check if template ID is provided for non-custom templates
            if (empty($templateData['is_custom']) && empty($templateData['template_id'])) {
                return [
                    'success' => false,
                    'message' => 'Template ID is required for non-custom templates',
                    'errors' => ['template_id' => ['Template ID is required']]
                ];
            }
            
            // If custom template, custom content is required
            if (!empty($templateData['is_custom']) && empty($templateData['custom_content'])) {
                return [
                    'success' => false,
                    'message' => 'Custom content is required for custom templates',
                    'errors' => ['custom_content' => ['Custom content is required']]
                ];
            }
            
            // Check if template exists for non-custom templates
            if (!empty($templateData['template_id'])) {
                $sql = "SELECT template_id FROM document_templates WHERE template_id = " . QuotedValue($templateData['template_id'], DataType::NUMBER);
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result)) {
                    return [
                        'success' => false,
                        'message' => 'Template not found',
                        'errors' => ['template_id' => ['Template not found']]
                    ];
                }
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Insert user template
                $sql = "INSERT INTO user_templates (
                        user_id,
                        template_id,
                        custom_name,
                        custom_content,
                        is_custom,
                        created_at,
                        updated_at
                    ) VALUES (
                        " . QuotedValue($userId, DataType::NUMBER) . ",
                        " . (!empty($templateData['template_id']) ? QuotedValue($templateData['template_id'], DataType::NUMBER) : "NULL") . ",
                        " . QuotedValue($templateData['custom_name'], DataType::STRING) . ",
                        " . QuotedValue($templateData['custom_content'] ?? null, DataType::TEXT) . ",
                        " . QuotedValue(!empty($templateData['is_custom']), DataType::BOOLEAN) . ",
                        CURRENT_TIMESTAMP,
                        CURRENT_TIMESTAMP
                    ) RETURNING user_template_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['user_template_id'])) {
                    throw new \Exception("Failed to save user template");
                }
                
                $userTemplateId = $result[0]['user_template_id'];
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Template saved successfully',
                    'data' => [
                        'user_template_id' => $userTemplateId
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
                'message' => 'Failed to save user template: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Upload a custom document as a template
     * @param int $userId User ID
     * @param array $templateData Template data including uploaded file
     * @return array Response data
     */
    public function uploadCustomTemplate($userId, $templateData) {
        try {
            // Validate required fields
            if (empty($templateData['custom_name'])) {
                return [
                    'success' => false,
                    'message' => 'Custom name is required',
                    'errors' => ['custom_name' => ['Custom name is required']]
                ];
            }
            
            if (empty($templateData['description'])) {
                return [
                    'success' => false,
                    'message' => 'Description is required',
                    'errors' => ['description' => ['Description is required']]
                ];
            }
            
            if (empty($templateData['document'])) {
                return [
                    'success' => false,
                    'message' => 'Document file is required',
                    'errors' => ['document' => ['Document file is required']]
                ];
            }
            
            // Process uploaded document
            $document = $templateData['document'];
            $filename = uniqid('template_', true) . '_' . $document->getClientFilename();
            $uploadPath = 'uploads/templates/' . $filename;
            
            // Ensure upload directory exists
            $uploadDir = dirname($uploadPath);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Save uploaded file
            $document->moveTo($uploadPath);
            
            // Generate HTML content from document file
            // This would typically involve parsing the document and generating HTML
            // For simplicity, we'll just store the file path in the custom_content field
            $customContent = $uploadPath;
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Insert user template
                $sql = "INSERT INTO user_templates (
                        user_id,
                        template_id,
                        custom_name,
                        custom_content,
                        is_custom,
                        created_at,
                        updated_at
                    ) VALUES (
                        " . QuotedValue($userId, DataType::NUMBER) . ",
                        NULL,
                        " . QuotedValue($templateData['custom_name'], DataType::STRING) . ",
                        " . QuotedValue($customContent, DataType::TEXT) . ",
                        TRUE,
                        CURRENT_TIMESTAMP,
                        CURRENT_TIMESTAMP
                    ) RETURNING user_template_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['user_template_id'])) {
                    throw new \Exception("Failed to save user template");
                }
                
                $userTemplateId = $result[0]['user_template_id'];
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Custom template uploaded successfully',
                    'data' => [
                        'user_template_id' => $userTemplateId
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
                'message' => 'Failed to upload custom template: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get details of a user's saved template
     * @param int $userTemplateId User Template ID
     * @return array Response data
     */
    public function getUserTemplateDetails($userTemplateId) {
        try {
            // Get user template details
            $sql = "SELECT
                    ut.user_template_id,
                    ut.template_id,
                    COALESCE(dt.template_name, 'Custom Template') AS template_name,
                    ut.custom_name,
                    ut.custom_content,
                    ut.is_custom,
                    ut.created_at
                FROM
                    user_templates ut
                LEFT JOIN
                    document_templates dt ON ut.template_id = dt.template_id
                WHERE
                    ut.user_template_id = " . QuotedValue($userTemplateId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'User template not found'
                ];
            }
            
            $userTemplate = $result[0];
            
            // If not a custom uploaded template, get template fields
            if (!$userTemplate['is_custom'] && !empty($userTemplate['template_id'])) {
                $sql = "SELECT
                        field_id,
                        field_name,
                        field_label,
                        field_type,
                        field_options,
                        is_required,
                        placeholder,
                        default_value,
                        field_order,
                        validation_rules,
                        help_text
                    FROM
                        template_fields
                    WHERE
                        template_id = " . QuotedValue($userTemplate['template_id'], DataType::NUMBER) . "
                    ORDER BY
                        field_order ASC";
                
                $fields = ExecuteRows($sql, "DB");
                
                // Process field options (convert from string to array if needed)
                foreach ($fields as &$field) {
                    if (!empty($field['field_options']) && is_string($field['field_options'])) {
                        $fieldOptions = json_decode($field['field_options'], true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $field['field_options'] = $fieldOptions;
                        } else {
                            // If not valid JSON, try comma-separated string
                            $field['field_options'] = explode(',', $field['field_options']);
                        }
                    } else {
                        $field['field_options'] = [];
                    }
                }
                
                // Add fields to template data
                $userTemplate['fields'] = $fields;
            }
            
            // Return success response
            return [
                'success' => true,
                'data' => $userTemplate
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to get user template details: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a user template
     * @param int $userTemplateId User Template ID
     * @return array Response data
     */
    public function deleteUserTemplate($userTemplateId) {
        try {
            // Check if user template exists
            $sql = "SELECT user_template_id, is_custom, custom_content FROM user_templates WHERE user_template_id = " . QuotedValue($userTemplateId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'User template not found'
                ];
            }
            
            $userTemplate = $result[0];
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Delete user template
                $sql = "DELETE FROM user_templates WHERE user_template_id = " . QuotedValue($userTemplateId, DataType::NUMBER);
                Execute($sql, "DB");
                
                // If custom template and has file path, delete the file
                if ($userTemplate['is_custom'] && !empty($userTemplate['custom_content']) && file_exists($userTemplate['custom_content'])) {
                    unlink($userTemplate['custom_content']);
                }
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Template removed successfully'
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
                'message' => 'Failed to delete user template: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate a PDF preview of an empty template
     * @param int $templateId Template ID
     * @param array $options Generation options
     * @return array Response data
     */
    public function convertTemplateToPdf($templateId, $options = []) {
        try {
            // Get template details
            $sql = "SELECT
                    template_id,
                    template_name,
                    html_content,
                    header_text,
                    footer_text
                FROM
                    document_templates
                WHERE
                    template_id = " . QuotedValue($templateId, DataType::NUMBER);
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Template not found'
                ];
            }
            
            $template = $result[0];
            
            // Get template fields
            $sql = "SELECT
                    field_id,
                    field_name,
                    field_label,
                    field_type,
                    is_required
                FROM
                    template_fields
                WHERE
                    template_id = " . QuotedValue($templateId, DataType::NUMBER) . "
                ORDER BY
                    field_order ASC";
            
            $fields = ExecuteRows($sql, "DB");
            
            // Process options
            $showFieldLabels = isset($options['show_field_labels']) ? (bool)$options['show_field_labels'] : true;
            $highlightRequiredFields = isset($options['highlight_required_fields']) ? (bool)$options['highlight_required_fields'] : true;
            $pageSize = isset($options['page_size']) ? $options['page_size'] : 'A4';
            $orientation = isset($options['orientation']) ? $options['orientation'] : 'portrait';
            
            // Generate PDF file
            // In a real implementation, this would create a PDF file using a library like mPDF or TCPDF
            // For now, we'll just simulate the PDF generation
            
            // Generate a unique filename for the PDF
            $filename = uniqid('template_preview_', true) . '.pdf';
            $pdfPath = 'uploads/previews/' . $filename;
            
            // Ensure directory exists
            $previewDir = dirname($pdfPath);
            if (!is_dir($previewDir)) {
                mkdir($previewDir, 0755, true);
            }
            
            // Simulate PDF creation (in a real implementation, this would be replaced with actual PDF generation)
            file_put_contents($pdfPath, "PDF Preview for Template: " . $template['template_name']);
            
            // Set expiry time (1 hour from now)
            $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Return success response
            return [
                'success' => true,
                'data' => [
                    'pdf_url' => $pdfPath,
                    'page_count' => 2, // Simulated page count
                    'expires_at' => $expiryTime
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to generate PDF preview: ' . $e->getMessage()
            ];
        }
    }
}
