<?php
// app/api/services/FaqService.php
namespace PHPMaker2024\eNotary;

class FaqService {
    /**
     * Get FAQ categories
     * @return array Response data
     */
    public function getCategories() {
        try {
            $sql = "SELECT * FROM faq_categories WHERE is_active = true ORDER BY display_order ASC";
            $result = ExecuteRows($sql, "DB");
            
            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            LogError($e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get FAQs by category
     * @param int $categoryId Category ID
     * @return array Response data
     */
    public function getFaqsByCategory($categoryId) {
        try {
            // Validate input
            $categoryId = (int)$categoryId;
            
            $sql = "SELECT f.*, c.category_name 
                    FROM faq_items f
                    JOIN faq_categories c ON f.category_id = c.category_id
                    WHERE f.category_id = " . QuotedValue($categoryId, DataType::NUMBER) . "
                    AND f.is_active = true
                    ORDER BY f.display_order ASC";
            
            $faqs = ExecuteRows($sql, "DB");
            
            // Update view count for all fetched FAQs
            foreach ($faqs as $faq) {
                $updateSql = "UPDATE faq_items SET view_count = view_count + 1 
                              WHERE faq_id = " . QuotedValue($faq['faq_id'], DataType::NUMBER);
                Execute($updateSql, "DB");
            }
            
            return [
                'success' => true,
                'data' => $faqs
            ];
        } catch (\Exception $e) {
            LogError($e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Search FAQs
     * @param string $query Search query
     * @return array Response data
     */
    public function searchFaqs($query) {
        try {
            // Validate and sanitize input
            $query = trim($query);
            if (empty($query)) {
                return [
                    'success' => true,
                    'data' => []
                ];
            }
            
            // Split query into keywords for better search
            $keywords = preg_split('/\s+/', $query);
            $whereClauses = [];
            
            foreach ($keywords as $keyword) {
                $keyword = QuotedValue('%' . $keyword . '%', DataType::STRING);
                $whereClauses[] = "(f.question ILIKE {$keyword} OR f.answer ILIKE {$keyword} OR f.tags ILIKE {$keyword})";
            }
            
            $whereClause = implode(' OR ', $whereClauses);
            
            $sql = "SELECT f.*, c.category_name 
                    FROM faq_items f
                    JOIN faq_categories c ON f.category_id = c.category_id
                    WHERE ({$whereClause}) AND f.is_active = true
                    ORDER BY 
                        CASE WHEN f.question ILIKE " . QuotedValue('%' . $query . '%', DataType::STRING) . " THEN 1 ELSE 2 END,
                        f.view_count DESC";
            
            $faqs = ExecuteRows($sql, "DB");
            
            // Update view count for all fetched FAQs
            foreach ($faqs as $faq) {
                $updateSql = "UPDATE faq_items SET view_count = view_count + 1 
                              WHERE faq_id = " . QuotedValue($faq['faq_id'], DataType::NUMBER);
                Execute($updateSql, "DB");
            }
            
            return [
                'success' => true,
                'data' => $faqs
            ];
        } catch (\Exception $e) {
            LogError($e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
