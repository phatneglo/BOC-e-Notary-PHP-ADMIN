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
            $ownerIdFilter = isset($params['owner_id']) ? $params['owner_id'] : null;
            
            // Get current user ID
            global $Request;
            $userId = $Request->getAttribute('user_id');
            
            // Check if user is admin (for filtering purposes)
            $authService = new AuthService();
            $isAdmin = $authService->hasAdminAccess($userId);
            
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
            
            // Apply owner_id filter if present
            if ($ownerIdFilter === true || $ownerIdFilter === 'true' || $ownerIdFilter === '1') {
                // When owner_id=true, filter by current user's ID
                if ($userId) {
                    $where .= " AND dt.owner_id = " . QuotedValue($userId, DataType::NUMBER);
                }
            } else if (is_numeric($ownerIdFilter)) {
                // When owner_id is a specific user ID
                $where .= " AND dt.owner_id = " . QuotedValue($ownerIdFilter, DataType::NUMBER);
            } else {
                // For regular listing (no specific owner_id parameter)
                // Non-admin users should only see their own templates and system templates
                if (!$isAdmin) {
                    $where .= " AND (dt.owner_id = " . QuotedValue($userId, DataType::NUMBER) . " OR dt.is_system = TRUE)";
                }
                // Admin users can see all templates by default
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
                    dt.created_at,
                    dt.is_system,
                    dt.owner_id
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
                    dt.created_at,
                    dt.is_system,
                    dt.owner_id
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
            // Process the HTML content to ensure proper newline handling
            if (isset($template['html_content']) && is_string($template['html_content'])) {
                // Replace literal escaped newlines with actual newlines
                $template['html_content'] = str_replace('\\n', "", $template['html_content']);
                
                // Normalize line breaks
                $template['html_content'] = str_replace("\r\n", "", $template['html_content']);
                $template['html_content'] = str_replace("\r", "", $template['html_content']);
                
                // Log for debugging
                Log("Processed HTML content for template ID {$templateId}: " . substr($template['html_content'], 0, 100) . "...");
            }
                   
            // Get template fields
            $sql = "SELECT
                    tf.field_id,
                    tf.field_name,
                    tf.field_label,
                    tf.field_type,
                    tf.field_options,
                    tf.is_required,
                    tf.placeholder,
                    tf.default_value,
                    tf.field_order,
                    tf.validation_rules,
                    tf.help_text,
                    tf.field_width,
                    tf.section_name
                FROM
                    template_fields tf
                WHERE
                    tf.template_id = " . QuotedValue($templateId, DataType::NUMBER) . "
                ORDER BY
                    tf.field_order ASC";
            
            Log("Fetching fields SQL: " . $sql);
            $fields = ExecuteRows($sql, "DB");
            Log("Template fields found: " . count($fields));
            Log("Fields data: " . json_encode($fields));
            
            // Process field options (convert from string to array of simple values)
            foreach ($fields as &$field) {
                if (!empty($field['field_options']) && is_string($field['field_options'])) {
                    // Try to parse as JSON first
                    $fieldOptions = json_decode($field['field_options'], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        // If it was an array of objects with label/value, extract just the values
                        if (is_array($fieldOptions) && !empty($fieldOptions) && isset($fieldOptions[0]) && is_array($fieldOptions[0])) {
                            if (isset($fieldOptions[0]['label'])) {
                                // Convert old format to new format
                                $field['field_options'] = array_map(function($item) {
                                    return $item['label'];
                                }, $fieldOptions);
                            } else {
                                $field['field_options'] = $fieldOptions;
                            }
                        } else {
                            // Already in the right format
                            $field['field_options'] = $fieldOptions;
                        }
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
     * Duplicate a system template as a user's custom template
     * @param int $userId User ID
     * @param int $templateId Template ID to duplicate
     * @param array $customData Custom data for the new template
     * @return array Response data
     */
    public function duplicateSystemTemplate($userId, $templateId, $templateData) {
        try {
            // Start transaction
            Execute("BEGIN", "DB");
            
            // Get original template
            $sqlTemplate = "SELECT * FROM document_templates WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $originalTemplate = ExecuteRows($sqlTemplate, "DB");
            
            if (empty($originalTemplate)) {
                return [
                    'success' => false,
                    'message' => 'Original template not found'
                ];
            }
            
            $original = $originalTemplate[0];
            
            // Log what we're duplicating
            Log("Duplicating template ID: " . $templateId . " with template name: " . ($templateData['template_name'] ?? $templateData['custom_name'] ?? 'Untitled'));
            Log("Original template data: " . json_encode($original));
            
            // Insert duplicate template with original_template_id
            $sqlInsert = "INSERT INTO document_templates (
                template_name,
                template_code,
                category_id,
                description,
                html_content,
                is_active,
                template_type,
                fee_amount,
                notary_required,
                header_text,
                footer_text,
                created_at,
                is_system,
                owner_id,
                original_template_id
            ) VALUES (
                " . QuotedValue($templateData['template_name'] ?? $templateData['custom_name'], DataType::STRING) . ",
                " . QuotedValue('COPY_' . substr(md5(uniqid()), 0, 8), DataType::STRING) . ",
                " . QuotedValue($original['category_id'], DataType::NUMBER) . ",
                " . QuotedValue(isset($templateData['description']) ? $templateData['description'] : '', DataType::STRING) . ",
                " . QuotedValue($original['html_content'], DataType::STRING) . ",
                TRUE,
                " . QuotedValue($original['template_type'], DataType::STRING) . ",
                " . QuotedValue($original['fee_amount'], DataType::NUMBER) . ",
                " . QuotedValue($original['notary_required'], DataType::BOOLEAN) . ",
                " . QuotedValue($original['header_text'], DataType::STRING) . ",
                " . QuotedValue($original['footer_text'], DataType::STRING) . ",
                CURRENT_TIMESTAMP,
                FALSE,
                " . QuotedValue($userId, DataType::NUMBER) . ",
                " . QuotedValue($templateId, DataType::NUMBER) . "
            ) RETURNING template_id";
            
            $resultInsert = ExecuteRows($sqlInsert, "DB");
            
            if (empty($resultInsert) || !isset($resultInsert[0]['template_id'])) {
                throw new \Exception("Failed to create duplicate template");
            }
            
            $newTemplateId = $resultInsert[0]['template_id'];
            Log("Created new template with ID: " . $newTemplateId);
            
            // Copy fields from the original template
            $sqlFields = "SELECT * FROM template_fields WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $fields = ExecuteRows($sqlFields, "DB");
            
            Log("Found " . count($fields) . " fields to copy");
            Log("Fields data: " . json_encode($fields));
            
            // Check if we actually have fields to copy
            if (empty($fields)) {
                Log("No fields found in original template, creating a default field");
                
                // Create a default field if no fields exist
                $sqlField = "INSERT INTO template_fields (
                    template_id,
                    field_name,
                    field_label,
                    field_type,
                    is_required,
                    placeholder,
                    field_order,
                    field_width,
                    section_name
                ) VALUES (
                    " . QuotedValue($newTemplateId, DataType::NUMBER) . ",
                    'full_name',
                    'Full Name',
                    'text',
                    TRUE,
                    'Enter your full name',
                    0,
                    'full',
                    'Default'
                )";
                
                Execute($sqlField, "DB");
                Log("Created default field for the duplicated template");
            } else {
                // Copy each field from the original template
                foreach ($fields as $fieldIndex => $field) {
                    Log("Copying field: " . $field['field_name']);
                    
                    $sqlField = "INSERT INTO template_fields (
                        template_id,
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
                    ) VALUES (
                        " . QuotedValue($newTemplateId, DataType::NUMBER) . ",
                        " . QuotedValue($field['field_name'], DataType::STRING) . ",
                        " . QuotedValue($field['field_label'], DataType::STRING) . ",
                        " . QuotedValue($field['field_type'], DataType::STRING) . ",
                        " . ($field['field_options'] ? QuotedValue($field['field_options'], DataType::STRING) : "NULL") . ",
                        " . ($field['is_required'] ? "TRUE" : "FALSE") . ",
                        " . ($field['placeholder'] ? QuotedValue($field['placeholder'], DataType::STRING) : "NULL") . ",
                        " . ($field['default_value'] ? QuotedValue($field['default_value'], DataType::STRING) : "NULL") . ",
                        " . QuotedValue($field['field_order'] !== null ? $field['field_order'] : $fieldIndex, DataType::NUMBER) . ",
                        " . ($field['validation_rules'] ? QuotedValue($field['validation_rules'], DataType::STRING) : "NULL") . ",
                        " . ($field['help_text'] ? QuotedValue($field['help_text'], DataType::STRING) : "NULL") . ",
                        " . ($field['field_width'] ? QuotedValue($field['field_width'], DataType::STRING) : "'full'") . ",
                        " . ($field['section_name'] ? QuotedValue($field['section_name'], DataType::STRING) : "'Default'") . "
                    )";
                    
                    try {
                        Execute($sqlField, "DB");
                        Log("Successfully copied field: " . $field['field_name']);
                    } catch (\Exception $fieldError) {
                        LogError("Error copying field: " . $fieldError->getMessage());
                        LogError("SQL: " . $sqlField);
                        // Continue even if one field fails
                    }
                }
                
                Log("Finished copying all fields");
            }
            
            // Commit the transaction
            Execute("COMMIT", "DB");
            
            return [
                'success' => true,
                'message' => 'Template duplicated successfully',
                'data' => [
                    'template_id' => $newTemplateId
                ]
            ];
        } catch (\Exception $e) {
            // Rollback on error
            Execute("ROLLBACK", "DB");
            
            LogError("Error in duplicateSystemTemplate: " . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to duplicate template: ' . $e->getMessage()
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
            
            // Two-part query: first get user-owned templates from document_templates
            $sql1 = "SELECT
                    NULL as user_template_id,
                    dt.template_id,
                    dt.template_name,
                    dt.template_name as custom_name,
                    tc.category_name,
                    TRUE as is_custom,
                    dt.created_at,
                    dt.updated_at as updated_at,
                    dt.is_system,
                    dt.owner_id
                FROM
                    document_templates dt
                LEFT JOIN
                    template_categories tc ON dt.category_id = tc.category_id
                WHERE
                    dt.owner_id = " . QuotedValue($userId, DataType::NUMBER) . "
                    AND dt.is_system = FALSE";
            
            // Then get templates saved in user_templates table for backward compatibility
            $sql2 = "SELECT
                    ut.user_template_id,
                    ut.template_id,
                    COALESCE(dt.template_name, 'Custom Template') AS template_name,
                    ut.custom_name,
                    tc.category_name,
                    ut.is_custom,
                    ut.created_at,
                    ut.updated_at,
                    dt.is_system,
                    dt.owner_id
                FROM
                    user_templates ut
                LEFT JOIN
                    document_templates dt ON ut.template_id = dt.template_id
                LEFT JOIN
                    template_categories tc ON dt.category_id = tc.category_id
                WHERE
                    ut.user_id = " . QuotedValue($userId, DataType::NUMBER);
            
            // Combine the results with UNION ALL
            $sql = "(
                    $sql1
                ) UNION ALL (
                    $sql2
                )
                ORDER BY
                    updated_at DESC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $result = ExecuteRows($sql, "DB");
            
            // Get total count (combine both queries)
            $sqlCount1 = "SELECT COUNT(*) AS total FROM document_templates 
                         WHERE owner_id = " . QuotedValue($userId, DataType::NUMBER) . " AND is_system = FALSE";
            
            $sqlCount2 = "SELECT COUNT(*) AS total FROM user_templates WHERE user_id = " . QuotedValue($userId, DataType::NUMBER);
            
            $resultCount1 = ExecuteRows($sqlCount1, "DB");
            $resultCount2 = ExecuteRows($sqlCount2, "DB");
            
            $total = ($resultCount1[0]['total'] ?? 0) + ($resultCount2[0]['total'] ?? 0);
            
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
            
            // Determine is_custom value properly
            $isCustom = isset($templateData['is_custom']) && ($templateData['is_custom'] === true || $templateData['is_custom'] === 'true' || $templateData['is_custom'] === '1' || $templateData['is_custom'] === 1);
            
            // If we're saving a system template, use duplicateSystemTemplate instead
            if (!$isCustom && !empty($templateData['template_id'])) {
                // Check if the template is a system template
                $sql = "SELECT is_system FROM document_templates WHERE template_id = " . QuotedValue($templateData['template_id'], DataType::NUMBER);
                $result = ExecuteRows($sql, "DB");
                
                if (!empty($result) && isset($result[0]['is_system']) && $result[0]['is_system']) {
                    // It's a system template, so duplicate it as a custom template
                    return $this->duplicateSystemTemplate($userId, $templateData['template_id'], [
                        'custom_name' => $templateData['custom_name'],
                        'description' => $templateData['description'] ?? null
                    ]);
                }
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                $templateId = null;
                
                if ($isCustom) {
                    // If custom template, create a new document_template record
                    // Parse the custom_content to extract fields and sections
                    $customContent = $templateData['custom_content'];
                    
                    // If custom_content is a string, try to parse it as JSON
                    $parsedContent = null;
                    if (is_string($customContent)) {
                        try {
                            $parsedContent = json_decode($customContent, true);
                            
                            // Check if JSON parsing was successful
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                LogError('JSON parse error: ' . json_last_error_msg() . ' for custom_content: ' . substr($customContent, 0, 100) . '...');
                                $parsedContent = [
                                    'sections' => [['id' => 'default', 'name' => 'Default']],
                                    'fields' => []
                                ];
                            }
                        } catch (\Exception $e) {
                            LogError('Exception parsing custom_content: ' . $e->getMessage());
                            $parsedContent = [
                                'sections' => [['id' => 'default', 'name' => 'Default']],
                                'fields' => []
                            ];
                        }
                    } else if (is_array($customContent)) {
                        // If it's already an array, use it directly
                        $parsedContent = $customContent;
                    } else {
                        // Default structure if neither string nor array
                        $parsedContent = [
                            'sections' => [['id' => 'default', 'name' => 'Default']],
                            'fields' => []
                        ];
                    }
                    
                    // Insert the template into document_templates
                    $sql = "INSERT INTO document_templates (
                            template_name,
                            template_code,
                            category_id,
                            description,
                            html_content,
                            is_active,
                            template_type,
                            fee_amount,
                            notary_required,
                            created_at,
                            is_system,
                            owner_id
                        ) VALUES (
                            " . QuotedValue($templateData['custom_name'], DataType::STRING) . ",
                            " . QuotedValue('USR_' . substr(md5(uniqid()), 0, 8), DataType::STRING) . ",
                            " . QuotedValue(1, DataType::NUMBER) . ", -- Default category (update if needed)
                            " . QuotedValue($templateData['description'] ?? $templateData['custom_name'], DataType::STRING) . ",
                            " . QuotedValue('', DataType::STRING) . ", -- Empty HTML content for custom templates
                            TRUE,
                            'custom',
                            0.00,
                            FALSE,
                            CURRENT_TIMESTAMP,
                            FALSE, -- Not a system template
                            " . QuotedValue($userId, DataType::NUMBER) . "
                        ) RETURNING template_id";
                    
                    $result = ExecuteRows($sql, "DB");
                    
                    if (empty($result) || !isset($result[0]['template_id'])) {
                        throw new \Exception("Failed to create custom template");
                    }
                    
                    $templateId = $result[0]['template_id'];
                    
                    // Extract sections from the parsed content to use their names
                    $sectionMap = []; // Map to store section names for reference in fields
                    if (isset($parsedContent['sections']) && is_array($parsedContent['sections'])) {
                        foreach ($parsedContent['sections'] as $section) {
                            if (isset($section['id']) && isset($section['name'])) {
                                $sectionMap[$section['id']] = $section['name'];
                            }
                        }
                    }
                    
                    // Create fields for the template
                    if (isset($parsedContent['fields']) && is_array($parsedContent['fields'])) {
                        foreach ($parsedContent['fields'] as $index => $field) {
                            // Determine section_name if field has a section
                            $sectionName = null;
                            if (isset($field['section_id']) && isset($sectionMap[$field['section_id']])) {
                                $sectionName = $sectionMap[$field['section_id']];
                            }
                            
                            // Store options as a comma-separated string
                            $fieldOptions = null;
                            if (isset($field['options']) && is_array($field['options'])) {
                                // Simple array of strings
                                $fieldOptions = implode(',', $field['options']);
                            }
                            
                            $sql = "INSERT INTO template_fields (
                                    template_id,
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
                                ) VALUES (
                                    " . QuotedValue($templateId, DataType::NUMBER) . ",
                                    " . QuotedValue($field['name'], DataType::STRING) . ",
                                    " . QuotedValue($field['label'], DataType::STRING) . ",
                                    " . QuotedValue($field['type'], DataType::STRING) . ",
                                    " . ($fieldOptions ? QuotedValue($fieldOptions, DataType::STRING) : "NULL") . ",
                                    " . (isset($field['required']) && $field['required'] ? "TRUE" : "FALSE") . ",
                                    " . QuotedValue($field['placeholder'] ?? '', DataType::STRING) . ",
                                    " . QuotedValue($field['default_value'] ?? '', DataType::STRING) . ",
                                    " . QuotedValue($index, DataType::NUMBER) . ",
                                    NULL,
                                    " . QuotedValue($field['help_text'] ?? '', DataType::STRING) . ",
                                    " . QuotedValue($field['width'] ?? 'full', DataType::STRING) . ",
                                    " . ($sectionName ? QuotedValue($sectionName, DataType::STRING) : "'Default'") . "
                                )";
                            
                            Execute($sql, "DB");
                        }
                    }
                } else {
                    // For system templates, just verify the template exists
                    $sql = "SELECT template_id FROM document_templates WHERE template_id = " . QuotedValue($templateData['template_id'], DataType::NUMBER);
                    $result = ExecuteRows($sql, "DB");
                    
                    if (empty($result)) {
                        throw new \Exception("Template not found");
                    }
                    
                    $templateId = $templateData['template_id'];
                }
                
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
                        " . QuotedValue($templateId, DataType::NUMBER) . ",
                        " . QuotedValue($templateData['custom_name'], DataType::STRING) . ",
                        " . ($isCustom ? QuotedValue($templateData['custom_content'], DataType::STRING) : "NULL") . ",
                        " . ($isCustom ? "TRUE" : "FALSE") . ",
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
            
            // Create a default template structure with a default field
            $defaultStructure = [
                'fields' => [
                    [
                        'id' => 'field_1',
                        'name' => 'full_name',
                        'label' => 'Full Name',
                        'type' => 'text',
                        'placeholder' => 'Enter your full name',
                        'required' => true,
                        'section_name' => 'Default', // Changed section_id to section_name
                        'width' => 'full',
                        'options' => []
                    ]
                ],
                'sections' => [
                    [
                        'id' => 'section_default',
                        'name' => 'Default'
                    ]
                ]
            ];
            
            // Encode the structure as JSON to store in custom_content
            $customContent = json_encode($defaultStructure);
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Create a template in document_templates first
                $sql = "INSERT INTO document_templates (
                        template_name,
                        template_code,
                        category_id,
                        description,
                        html_content,
                        is_active,
                        template_type,
                        fee_amount,
                        notary_required,
                        created_at,
                        is_system,
                        owner_id
                    ) VALUES (
                        " . QuotedValue($templateData['custom_name'], DataType::STRING) . ",
                        " . QuotedValue('USR_' . substr(md5(uniqid()), 0, 8), DataType::STRING) . ",
                        " . QuotedValue(1, DataType::NUMBER) . ", -- Default category (update if needed)
                        " . QuotedValue($templateData['description'], DataType::STRING) . ",
                        " . QuotedValue($uploadPath, DataType::STRING) . ", -- Store the file path in html_content
                        TRUE,
                        'uploaded',
                        0.00,
                        FALSE,
                        CURRENT_TIMESTAMP,
                        FALSE, -- Not a system template
                        " . QuotedValue($userId, DataType::NUMBER) . "
                    ) RETURNING template_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['template_id'])) {
                    throw new \Exception("Failed to create template record");
                }
                
                $templateId = $result[0]['template_id'];
                
                // No need to create default section, just use 'Default' as the section_name
                
                // Add default field
                $sql = "INSERT INTO template_fields (
                        template_id,
                        field_name,
                        field_label,
                        field_type,
                        is_required,
                        placeholder,
                        field_order,
                        field_width,
                        section_name
                    ) VALUES (
                        " . QuotedValue($templateId, DataType::NUMBER) . ",
                        'full_name',
                        'Full Name',
                        'text',
                        TRUE,
                        'Enter your full name',
                        0,
                        'full',
                        'Default'
                    )";
                
                Execute($sql, "DB");
                
                // Insert user template record
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
                        " . QuotedValue($templateId, DataType::NUMBER) . ",
                        " . QuotedValue($templateData['custom_name'], DataType::STRING) . ",
                        " . QuotedValue($customContent, DataType::STRING) . ",
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
     * Get templates owned by a user
     * @param int $userId User ID
     * @param array $params Query parameters
     * @return array Response data
     */
    public function getUserOwnedTemplates($userId, $params = []) {
        try {
            // Pagination parameters
            $page = isset($params['page']) ? max(1, (int)$params['page']) : 1;
            $perPage = isset($params['per_page']) ? min(max(1, (int)$params['per_page']), 100) : 20;
            $offset = ($page - 1) * $perPage;
            
            // Query user-owned templates from document_templates
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
                    dt.created_at,
                    dt.updated_at,
                    dt.is_system,
                    dt.owner_id,
                    dt.created_by
                FROM
                    document_templates dt
                LEFT JOIN
                    template_categories tc ON dt.category_id = tc.category_id
                WHERE
                    dt.owner_id = " . QuotedValue($userId, DataType::NUMBER) . "
                ORDER BY
                    dt.updated_at DESC
                LIMIT " . $perPage . " OFFSET " . $offset;
            
            $result = ExecuteRows($sql, "DB");
            
            // Get total count
            $sqlCount = "SELECT COUNT(*) AS total FROM document_templates WHERE owner_id = " . QuotedValue($userId, DataType::NUMBER);
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
     * Get details of a user's saved template
     * @param int $userTemplateId User Template ID
     * @return array Response data
     */
    public function getUserTemplateDetails($userTemplateId) {
        try {
            // First, check if this is actually a template_id from the document_templates table
            if (is_numeric($userTemplateId)) {
                // Try to get it directly from document_templates first
                $sql = "SELECT template_id FROM document_templates WHERE template_id = " . QuotedValue($userTemplateId, DataType::NUMBER);
                $templateResult = ExecuteRows($sql, "DB");
                
                if (!empty($templateResult)) {
                    // This is a direct template_id from document_templates, so get the details
                    return $this->getTemplateDetails($userTemplateId);
                }
            }
            
            // If not found directly, look in user_templates (legacy approach)
            $sql = "SELECT
                    ut.user_template_id,
                    ut.template_id,
                    COALESCE(dt.template_name, 'Custom Template') AS template_name,
                    ut.custom_name,
                    ut.custom_content,
                    ut.is_custom,
                    ut.created_at,
                    dt.is_system,
                    dt.owner_id
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
            
            // Get template fields from template_fields table
            if (!empty($userTemplate['template_id'])) {
                // Get unique section names from fields
                $sql = "SELECT DISTINCT
                        section_name
                    FROM
                        template_fields
                    WHERE
                        template_id = " . QuotedValue($userTemplate['template_id'], DataType::NUMBER) . "
                    ORDER BY
                        section_name ASC";
                
                $sectionRows = ExecuteRows($sql, "DB");
                
                // Transform section rows into proper format
                $sections = [];
                foreach ($sectionRows as $index => $row) {
                    if (!empty($row['section_name'])) {
                        $sections[] = [
                            'id' => 'section_' . md5($row['section_name']), // Generate consistent ID from name
                            'name' => $row['section_name'],
                            'order' => $index
                        ];
                    }
                }
                
                // Ensure Default section exists
                if (!array_filter($sections, function($section) { return $section['name'] === 'Default'; })) {
                    array_unshift($sections, [
                        'id' => 'section_default',
                        'name' => 'Default',
                        'order' => 0
                    ]);
                }
                
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
                    
                    // If no section_name, set it to Default
                    if (empty($field['section_name'])) {
                        $field['section_name'] = 'Default';
                    }
                }
                
                // Add fields and sections to template data
                $userTemplate['fields'] = $fields;
                $userTemplate['sections'] = $sections;
            }
            
            // If custom template, also return the parsed custom_content for the editor
            if ($userTemplate['is_custom'] && !empty($userTemplate['custom_content'])) {
                // Check if custom_content is valid JSON
                $parsedContent = json_decode($userTemplate['custom_content'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // It's already in our JSON format
                    $userTemplate['parsed_structure'] = $parsedContent;
                }
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
            // Check if user template exists and get related info
            $sql = "SELECT 
                    ut.user_template_id, 
                    ut.is_custom, 
                    ut.custom_content, 
                    ut.template_id,
                    dt.owner_id,
                    dt.is_system,
                    dt.html_content
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
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Delete user template record
                $sql = "DELETE FROM user_templates WHERE user_template_id = " . QuotedValue($userTemplateId, DataType::NUMBER);
                Execute($sql, "DB");
                
                // If this is a custom template created by the user (not a system template)
                if ($userTemplate['template_id'] && $userTemplate['is_custom'] && !$userTemplate['is_system']) {
                    // Delete template fields
                    $sql = "DELETE FROM template_fields WHERE template_id = " . QuotedValue($userTemplate['template_id'], DataType::NUMBER);
                    Execute($sql, "DB");
                    
                    // Delete the template record
                    $sql = "DELETE FROM document_templates WHERE template_id = " . QuotedValue($userTemplate['template_id'], DataType::NUMBER);
                    Execute($sql, "DB");
                    
                    // If it has a file path in html_content, delete the file
                    if (!empty($userTemplate['html_content']) && file_exists($userTemplate['html_content'])) {
                        unlink($userTemplate['html_content']);
                    }
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
            // Note: This is a placeholder - the actual implementation would render the template with field placeholders
            
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
    
    /**
     * Create a new template
     * @param int $userId User ID
     * @param array $templateData Template data
     * @return array Response data
     */
    public function createTemplate($userId, $templateData) {
        try {
            // Log input data
            Log("Creating template with data: " . json_encode($templateData));
            
            // Validate required fields
            if (empty($templateData['template_name'])) {
                return [
                    'success' => false,
                    'message' => 'Template name is required',
                    'errors' => ['template_name' => ['Template name is required']]
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Generate a unique template code
                $templateCode = 'T' . date('YmdHis') . mt_rand(1000, 9999);
                
                // Process the HTML content
                $htmlContent = '';
                if (isset($templateData['html_content'])) {
                    // Replace literal \n with actual newlines
                    $htmlContent = str_replace('\\n', "\n", $templateData['html_content']);
                    
                    // Normalize line breaks
                    $htmlContent = str_replace("\r\n", "\n", $htmlContent);
                    $htmlContent = str_replace("\r", "\n", $htmlContent);
                    
                    // Trim whitespace
                    $htmlContent = trim($htmlContent);
                }
                
                // Insert the template into document_templates
                $sql = "INSERT INTO document_templates (
                        template_name,
                        template_code,
                        category_id,
                        description,
                        html_content,
                        is_active,
                        template_type,
                        fee_amount,
                        notary_required,
                        created_at,
                        is_system,
                        owner_id
                    ) VALUES (
                        " . QuotedValue($templateData['template_name'], DataType::STRING) . ",
                        " . QuotedValue($templateCode, DataType::STRING) . ",
                        " . QuotedValue($templateData['category_id'] ?? 1, DataType::NUMBER) . ",
                        " . QuotedValue($templateData['description'] ?? '', DataType::STRING) . ",
                        " . QuotedValue($templateData['html_content'] ?? '', DataType::STRING) . ",
                        TRUE,
                        " . QuotedValue($templateData['template_type'] ?? 'custom', DataType::STRING) . ",
                        " . QuotedValue($templateData['fee_amount'] ?? 0.00, DataType::NUMBER) . ",
                        " . ($templateData['notary_required'] ?? false ? "TRUE" : "FALSE") . ",
                        CURRENT_TIMESTAMP,
                        " . ($templateData['is_system'] ?? false ? "TRUE" : "FALSE") . ",
                        " . QuotedValue($userId, DataType::NUMBER) . "
                    ) RETURNING template_id";
                
                $result = ExecuteRows($sql, "DB");
                
                if (empty($result) || !isset($result[0]['template_id'])) {
                    throw new \Exception("Failed to create template");
                }
                
                $templateId = $result[0]['template_id'];
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Template created successfully',
                    'data' => [
                        'template_id' => $templateId
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
                'message' => 'Failed to create template: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update an existing template
     * @param int $userId User ID
     * @param int $templateId Template ID
     * @param array $templateData Template data
     * @return array Response data
     */
    public function updateTemplate($userId, $templateId, $templateData) {
        try {
            // Check if template exists and user has permission to edit it
            $sql = "SELECT 
                    template_id, 
                    owner_id,
                    is_system,
                    created_by
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
            
            // Check if user has permission to edit this template
            $authService = new AuthService();
            if ($template['is_system'] && !$authService->hasAdminAccess($userId)) {
                return [
                    'success' => false,
                    'message' => 'You do not have permission to edit system templates'
                ];
            }
            
            if (!$template['is_system'] && $template['owner_id'] != $userId && !$authService->hasAdminAccess($userId)) {
                return [
                    'success' => false,
                    'message' => 'You do not have permission to edit this template'
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Build update query
                $updateFields = [];
                
                if (isset($templateData['template_name'])) {
                    $updateFields[] = "template_name = " . QuotedValue($templateData['template_name'], DataType::STRING);
                }
                
                if (isset($templateData['category_id'])) {
                    $updateFields[] = "category_id = " . QuotedValue($templateData['category_id'], DataType::NUMBER);
                }
                
                if (isset($templateData['description'])) {
                    $updateFields[] = "description = " . QuotedValue($templateData['description'], DataType::STRING);
                }
                
                // Process the HTML content to handle escaped newlines and normalize line breaks
                if (isset($templateData['html_content'])) {
                    // Replace literal \n with actual newlines
                    $templateData['html_content'] = str_replace('\\n', "\n", $templateData['html_content']);
                    
                    // Normalize line breaks
                    $templateData['html_content'] = str_replace("\r\n", "\n", $templateData['html_content']);
                    $templateData['html_content'] = str_replace("\r", "\n", $templateData['html_content']);
                    
                    // Trim whitespace
                    $templateData['html_content'] = trim($templateData['html_content']);
                    
                    // Update the HTML content field
                    $updateFields[] = "html_content = " . QuotedValue($templateData['html_content'], DataType::STRING);
                }
                
                if (isset($templateData['is_active'])) {
                    $updateFields[] = "is_active = " . ($templateData['is_active'] ? "TRUE" : "FALSE");
                }
                
                if (isset($templateData['template_type'])) {
                    $updateFields[] = "template_type = " . QuotedValue($templateData['template_type'], DataType::STRING);
                }
                
                if (isset($templateData['fee_amount'])) {
                    $updateFields[] = "fee_amount = " . QuotedValue($templateData['fee_amount'], DataType::NUMBER);
                }
                
                if (isset($templateData['notary_required'])) {
                    $updateFields[] = "notary_required = " . ($templateData['notary_required'] ? "TRUE" : "FALSE");
                }
                
                if (isset($templateData['is_system']) && $this->hasAdminAccess($userId)) {
                    // Only admin can change is_system flag
                    $updateFields[] = "is_system = " . ($templateData['is_system'] ? "TRUE" : "FALSE");
                }
                
                // Perform update if there are fields to update
                if (!empty($updateFields)) {
                    $sql = "UPDATE document_templates SET 
                            " . implode(", ", $updateFields) . ",
                            updated_at = CURRENT_TIMESTAMP
                        WHERE 
                            template_id = " . QuotedValue($templateId, DataType::NUMBER);
                    
                    Execute($sql, "DB");
                }
                
                // Process fields if they were provided
                if (!empty($templateData['fields']) && is_array($templateData['fields'])) {
                    // Log fields data
                    Log("Processing fields: " . json_encode($templateData['fields']));
                    
                    // First, delete existing fields for the template
                    $sql = "DELETE FROM template_fields WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
                    Execute($sql, "DB");
                    
                    // Then add the new fields
                    foreach ($templateData['fields'] as $index => $field) {
                        // Prepare field options
                        $fieldOptions = null;
                        if (!empty($field['field_options'])) {
                            if (is_array($field['field_options'])) {
                                $fieldOptions = json_encode($field['field_options']);
                            } else {
                                $fieldOptions = $field['field_options'];
                            }
                        }
                        
                        // Determine section name
                        $sectionName = !empty($field['section_name']) ? $field['section_name'] : 'Default';
                        
                        // Insert the field
                        $sql = "INSERT INTO template_fields (
                                template_id,
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
                            ) VALUES (
                                " . QuotedValue($templateId, DataType::NUMBER) . ",
                                " . QuotedValue($field['field_name'] ?? 'field_' . $index, DataType::STRING) . ",
                                " . QuotedValue($field['field_label'] ?? 'Field ' . ($index + 1), DataType::STRING) . ",
                                " . QuotedValue($field['field_type'] ?? 'text', DataType::STRING) . ",
                                " . ($fieldOptions ? QuotedValue($fieldOptions, DataType::STRING) : "NULL") . ",
                                " . (isset($field['is_required']) && $field['is_required'] ? "TRUE" : "FALSE") . ",
                                " . QuotedValue($field['placeholder'] ?? '', DataType::STRING) . ",
                                " . QuotedValue($field['default_value'] ?? '', DataType::STRING) . ",
                                " . QuotedValue($index, DataType::NUMBER) . ",
                                " . QuotedValue($field['validation_rules'] ?? '', DataType::STRING) . ",
                                " . QuotedValue($field['help_text'] ?? '', DataType::STRING) . ",
                                " . QuotedValue($field['field_width'] ?? 'full', DataType::STRING) . ",
                                " . QuotedValue($sectionName, DataType::STRING) . "
                            )";
                        
                        try {
                            Log("Adding field $index: " . $field['field_name'] . " of type " . $field['field_type']);
                            Execute($sql, "DB");
                        } catch (\Exception $fieldError) {
                            LogError("Error adding field: " . $fieldError->getMessage());
                            LogError("SQL: " . $sql);
                            // Continue even if one field fails
                        }
                    }
                    
                    Log("Added " . count($templateData['fields']) . " fields to template ID: " . $templateId);
                }
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Template updated successfully',
                    'data' => [
                        'template_id' => $templateId
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
                'message' => 'Failed to update template: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a template
     * @param int $userId User ID requesting the deletion
     * @param int $templateId Template ID to delete
     * @return array Response data
     */
    public function deleteTemplate($userId, $templateId) {
        try {
            // Check if template exists and user has permission to delete it
            $sql = "SELECT 
                    template_id, 
                    owner_id,
                    is_system,
                    created_by,
                    html_content
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
            
            // Check if user has permission to delete this template
            $authService = new AuthService();
            if ($template['is_system'] && !$authService->hasAdminAccess($userId)) {
                return [
                    'success' => false,
                    'message' => 'You do not have permission to delete system templates'
                ];
            }
            
            if (!$template['is_system'] && $template['owner_id'] != $userId && !$authService->hasAdminAccess($userId)) {
                return [
                    'success' => false,
                    'message' => 'You do not have permission to delete this template'
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // First check if this template is being used in user_templates (legacy)
                $sql = "SELECT user_template_id FROM user_templates WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
                $userTemplates = ExecuteRows($sql, "DB");
                
                // Delete related user_templates records first if any exist
                if (!empty($userTemplates)) {
                    $sql = "DELETE FROM user_templates WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
                    Execute($sql, "DB");
                }
                
                // Delete template fields
                $sql = "DELETE FROM template_fields WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
                Execute($sql, "DB");
                
                // Delete the template record
                $sql = "DELETE FROM document_templates WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
                Execute($sql, "DB");
                
                // If it has a file path in html_content, delete the file
                if (!empty($template['html_content']) && file_exists($template['html_content'])) {
                    unlink($template['html_content']);
                }
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Template deleted successfully'
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
                'message' => 'Failed to delete template: ' . $e->getMessage()
            ];
        }
    }
    

    
    /**
     * Add a field to a template
     * @param int $templateId Template ID
     * @param array $fieldData Field data
     * @return array Response data
     */
    public function addTemplateField($templateId, $fieldData) {
        try {
            // Validate required fields
            if (empty($fieldData['field_name'])) {
                return [
                    'success' => false,
                    'message' => 'Field name is required',
                    'errors' => ['field_name' => ['Field name is required']]
                ];
            }
            
            if (empty($fieldData['field_label'])) {
                return [
                    'success' => false,
                    'message' => 'Field label is required',
                    'errors' => ['field_label' => ['Field label is required']]
                ];
            }
            
            if (empty($fieldData['field_type'])) {
                return [
                    'success' => false,
                    'message' => 'Field type is required',
                    'errors' => ['field_type' => ['Field type is required']]
                ];
            }
            
            // Check if template exists
            $sql = "SELECT template_id FROM document_templates WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Template not found'
                ];
            }
            
            // Get next field order
            $sql = "SELECT COALESCE(MAX(field_order), 0) AS max_order FROM template_fields WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            $nextOrder = (int)$result[0]['max_order'] + 1;
            
            // Prepare field options as a comma-separated string
            $fieldOptions = null;
            if (!empty($fieldData['field_options'])) {
                if (is_array($fieldData['field_options'])) {
                    $fieldOptions = implode(',', $fieldData['field_options']);
                } else {
                    $fieldOptions = $fieldData['field_options'];
                }
            }
            
            // Insert the field
            $sql = "INSERT INTO template_fields (
                    template_id,
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
                ) VALUES (
                    " . QuotedValue($templateId, DataType::NUMBER) . ",
                    " . QuotedValue($fieldData['field_name'], DataType::STRING) . ",
                    " . QuotedValue($fieldData['field_label'], DataType::STRING) . ",
                    " . QuotedValue($fieldData['field_type'], DataType::STRING) . ",
                    " . ($fieldOptions ? QuotedValue($fieldOptions, DataType::STRING) : "NULL") . ",
                    " . (isset($fieldData['is_required']) && $fieldData['is_required'] ? "TRUE" : "FALSE") . ",
                    " . QuotedValue($fieldData['placeholder'] ?? '', DataType::STRING) . ",
                    " . QuotedValue($fieldData['default_value'] ?? '', DataType::STRING) . ",
                    " . QuotedValue($nextOrder, DataType::NUMBER) . ",
                    " . QuotedValue($fieldData['validation_rules'] ?? '', DataType::STRING) . ",
                    " . QuotedValue($fieldData['help_text'] ?? '', DataType::STRING) . ",
                    " . QuotedValue($fieldData['field_width'] ?? 'full', DataType::STRING) . ",
                    " . (!empty($fieldData['section_name']) ? QuotedValue($fieldData['section_name'], DataType::STRING) : "'Default'") . "
                ) RETURNING field_id";
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !isset($result[0]['field_id'])) {
                return [
                    'success' => false,
                    'message' => 'Failed to add template field'
                ];
            }
            
            $fieldId = $result[0]['field_id'];
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Field added successfully',
                'data' => [
                    'field_id' => $fieldId,
                    'field_order' => $nextOrder
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to add template field: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update a template field
     * @param int $fieldId Field ID
     * @param array $fieldData Field data
     * @return array Response data
     */
    public function updateTemplateField($fieldId, $fieldData) {
        try {
            // Check if field exists
            $sql = "SELECT field_id FROM template_fields WHERE field_id = " . QuotedValue($fieldId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Field not found'
                ];
            }
            
            // Build update query
            $updateFields = [];
            
            if (isset($fieldData['field_name'])) {
                $updateFields[] = "field_name = " . QuotedValue($fieldData['field_name'], DataType::STRING);
            }
            
            if (isset($fieldData['field_label'])) {
                $updateFields[] = "field_label = " . QuotedValue($fieldData['field_label'], DataType::STRING);
            }
            
            if (isset($fieldData['field_type'])) {
                $updateFields[] = "field_type = " . QuotedValue($fieldData['field_type'], DataType::STRING);
            }
            
            if (isset($fieldData['field_options'])) {
                if (is_array($fieldData['field_options'])) {
                    $fieldOptions = json_encode($fieldData['field_options']);
                } else {
                    $fieldOptions = $fieldData['field_options'];
                }
                $updateFields[] = "field_options = " . QuotedValue($fieldOptions, DataType::STRING);
            }
            
            if (isset($fieldData['is_required'])) {
                $updateFields[] = "is_required = " . ($fieldData['is_required'] ? "TRUE" : "FALSE");
            }
            
            if (isset($fieldData['placeholder'])) {
                $updateFields[] = "placeholder = " . QuotedValue($fieldData['placeholder'], DataType::STRING);
            }
            
            if (isset($fieldData['default_value'])) {
                $updateFields[] = "default_value = " . QuotedValue($fieldData['default_value'], DataType::STRING);
            }
            
            if (isset($fieldData['field_order'])) {
                $updateFields[] = "field_order = " . QuotedValue($fieldData['field_order'], DataType::NUMBER);
            }
            
            if (isset($fieldData['validation_rules'])) {
                $updateFields[] = "validation_rules = " . QuotedValue($fieldData['validation_rules'], DataType::STRING);
            }
            
            if (isset($fieldData['help_text'])) {
                $updateFields[] = "help_text = " . QuotedValue($fieldData['help_text'], DataType::STRING);
            }
            
            if (isset($fieldData['field_width'])) {
                $updateFields[] = "field_width = " . QuotedValue($fieldData['field_width'], DataType::STRING);
            }
            
            if (array_key_exists('section_name', $fieldData)) {
                if (empty($fieldData['section_name'])) {
                    $updateFields[] = "section_name = 'Default'";
                } else {
                    $updateFields[] = "section_name = " . QuotedValue($fieldData['section_name'], DataType::STRING);
                }
            }
            
            // Skip update if no fields to update
            if (empty($updateFields)) {
                return [
                    'success' => true,
                    'message' => 'No changes to update'
                ];
            }
            
            // Perform update
            $sql = "UPDATE template_fields SET 
                    " . implode(", ", $updateFields) . "
                WHERE 
                    field_id = " . QuotedValue($fieldId, DataType::NUMBER);
            
            Execute($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Field updated successfully'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to update template field: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a template field
     * @param int $fieldId Field ID
     * @return array Response data
     */
    public function deleteTemplateField($fieldId) {
        try {
            // Check if field exists
            $sql = "SELECT field_id, template_id, field_order FROM template_fields WHERE field_id = " . QuotedValue($fieldId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Field not found'
                ];
            }
            
            $field = $result[0];
            $templateId = $field['template_id'];
            $fieldOrder = $field['field_order'];
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Delete the field
                $sql = "DELETE FROM template_fields WHERE field_id = " . QuotedValue($fieldId, DataType::NUMBER);
                Execute($sql, "DB");
                
                // Reorder remaining fields
                $sql = "UPDATE template_fields 
                        SET field_order = field_order - 1 
                        WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER) . "
                        AND field_order > " . QuotedValue($fieldOrder, DataType::NUMBER);
                Execute($sql, "DB");
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Field deleted successfully'
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
                'message' => 'Failed to delete template field: ' . $e->getMessage()
            ];
        }
    }
}