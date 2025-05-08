<?php
// app/api/services/TemplateSectionService.php
namespace PHPMaker2024\eNotary;

class TemplateSectionService {
    /**
     * Get sections for a template
     * @param int $templateId Template ID
     * @return array Response data
     */
    public function getTemplateSections($templateId) {
        try {
            $sql = "SELECT
                    section_id,
                    template_id,
                    section_name,
                    section_order
                FROM
                    template_sections
                WHERE
                    template_id = " . QuotedValue($templateId, DataType::NUMBER) . "
                ORDER BY
                    section_order ASC";
            
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
                'message' => 'Failed to get template sections: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Add a section to a template
     * @param int $templateId Template ID
     * @param array $sectionData Section data
     * @return array Response data
     */
    public function addTemplateSection($templateId, $sectionData) {
        try {
            // Validate required fields
            if (empty($sectionData['section_name'])) {
                return [
                    'success' => false,
                    'message' => 'Section name is required',
                    'errors' => ['section_name' => ['Section name is required']]
                ];
            }
            
            // Get next section order
            $sql = "SELECT MAX(section_order) AS max_order FROM template_sections WHERE template_id = " . QuotedValue($templateId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            $nextOrder = isset($result[0]['max_order']) ? ((int)$result[0]['max_order'] + 1) : 0;
            
            // Insert section
            $sql = "INSERT INTO template_sections (
                    template_id,
                    section_name,
                    section_order
                ) VALUES (
                    " . QuotedValue($templateId, DataType::NUMBER) . ",
                    " . QuotedValue($sectionData['section_name'], DataType::STRING) . ",
                    " . QuotedValue($nextOrder, DataType::NUMBER) . "
                ) RETURNING section_id";
            
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result) || !isset($result[0]['section_id'])) {
                return [
                    'success' => false,
                    'message' => 'Failed to add template section'
                ];
            }
            
            $sectionId = $result[0]['section_id'];
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Section added successfully',
                'data' => [
                    'section_id' => $sectionId,
                    'template_id' => $templateId,
                    'section_name' => $sectionData['section_name'],
                    'section_order' => $nextOrder
                ]
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to add template section: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update a template section
     * @param int $sectionId Section ID
     * @param array $sectionData Section data
     * @return array Response data
     */
    public function updateTemplateSection($sectionId, $sectionData) {
        try {
            // Validate required fields
            if (empty($sectionData['section_name'])) {
                return [
                    'success' => false,
                    'message' => 'Section name is required',
                    'errors' => ['section_name' => ['Section name is required']]
                ];
            }
            
            // Update section
            $sql = "UPDATE template_sections SET
                    section_name = " . QuotedValue($sectionData['section_name'], DataType::STRING) . "
                WHERE
                    section_id = " . QuotedValue($sectionId, DataType::NUMBER);
            
            Execute($sql, "DB");
            
            // Return success response
            return [
                'success' => true,
                'message' => 'Section updated successfully'
            ];
        } catch (\Exception $e) {
            // Log error
            LogError($e->getMessage());
            
            // Return error response
            return [
                'success' => false,
                'message' => 'Failed to update template section: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a template section
     * @param int $sectionId Section ID
     * @return array Response data
     */
    public function deleteTemplateSection($sectionId) {
        try {
            // Check if section exists
            $sql = "SELECT section_id, template_id FROM template_sections WHERE section_id = " . QuotedValue($sectionId, DataType::NUMBER);
            $result = ExecuteRows($sql, "DB");
            
            if (empty($result)) {
                return [
                    'success' => false,
                    'message' => 'Section not found'
                ];
            }
            
            $section = $result[0];
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Get all fields in this section
                $sql = "SELECT field_id FROM template_fields WHERE section_id = " . QuotedValue($sectionId, DataType::NUMBER);
                $fields = ExecuteRows($sql, "DB");
                
                // Update fields to remove section_id
                if (!empty($fields)) {
                    $sql = "UPDATE template_fields SET
                            section_id = NULL
                        WHERE
                            section_id = " . QuotedValue($sectionId, DataType::NUMBER);
                    
                    Execute($sql, "DB");
                }
                
                // Delete section
                $sql = "DELETE FROM template_sections WHERE section_id = " . QuotedValue($sectionId, DataType::NUMBER);
                Execute($sql, "DB");
                
                // Reorder remaining sections
                $sql = "SELECT section_id FROM template_sections 
                        WHERE template_id = " . QuotedValue($section['template_id'], DataType::NUMBER) . "
                        ORDER BY section_order";
                
                $sections = ExecuteRows($sql, "DB");
                
                // Update section orders
                foreach ($sections as $index => $sectionItem) {
                    $sql = "UPDATE template_sections SET
                            section_order = " . QuotedValue($index, DataType::NUMBER) . "
                        WHERE
                            section_id = " . QuotedValue($sectionItem['section_id'], DataType::NUMBER);
                    
                    Execute($sql, "DB");
                }
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Section deleted successfully'
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
                'message' => 'Failed to delete template section: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Reorder template sections
     * @param int $templateId Template ID
     * @param array $sectionOrder Array of section IDs in new order
     * @return array Response data
     */
    public function reorderTemplateSections($templateId, $sectionOrder) {
        try {
            // Validate input
            if (empty($sectionOrder) || !is_array($sectionOrder)) {
                return [
                    'success' => false,
                    'message' => 'Section order is required and must be an array',
                    'errors' => ['section_order' => ['Section order is required and must be an array']]
                ];
            }
            
            // Begin transaction
            Execute("BEGIN", "DB");
            
            try {
                // Update section orders
                foreach ($sectionOrder as $index => $sectionId) {
                    $sql = "UPDATE template_sections SET
                            section_order = " . QuotedValue($index, DataType::NUMBER) . "
                        WHERE
                            section_id = " . QuotedValue($sectionId, DataType::NUMBER) . "
                            AND template_id = " . QuotedValue($templateId, DataType::NUMBER);
                    
                    Execute($sql, "DB");
                }
                
                // Commit transaction
                Execute("COMMIT", "DB");
                
                // Return success response
                return [
                    'success' => true,
                    'message' => 'Sections reordered successfully'
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
                'message' => 'Failed to reorder template sections: ' . $e->getMessage()
            ];
        }
    }
}
