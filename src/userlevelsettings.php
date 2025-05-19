<?php
/**
 * PHPMaker 2024 User Level Settings
 */
namespace PHPMaker2024\eNotary;

/**
 * User levels
 *
 * @var array<int, string>
 * [0] int User level ID
 * [1] string User level name
 */
$USER_LEVELS = [["-2","Anonymous"],
    ["0","Default"]];

/**
 * User level permissions
 *
 * @var array<string, int, int>
 * [0] string Project ID + Table name
 * [1] int User level ID
 * [2] int Permissions
 */
$USER_LEVEL_PRIVS = [["{eNotary}audit_logs","-2","0"],
    ["{eNotary}audit_logs","0","0"],
    ["{eNotary}systems","-2","0"],
    ["{eNotary}systems","0","0"],
    ["{eNotary}user_level_assignments","-2","0"],
    ["{eNotary}user_level_assignments","0","0"],
    ["{eNotary}user_level_permissions","-2","0"],
    ["{eNotary}user_level_permissions","0","0"],
    ["{eNotary}user_levels","-2","0"],
    ["{eNotary}user_levels","0","0"],
    ["{eNotary}users","-2","0"],
    ["{eNotary}users","0","0"],
    ["{eNotary}aggregated_audit_logs","-2","0"],
    ["{eNotary}aggregated_audit_logs","0","0"],
    ["{eNotary}MainDashboard.php","-2","0"],
    ["{eNotary}MainDashboard.php","0","0"],
    ["{eNotary}departments","-2","0"],
    ["{eNotary}departments","0","0"],
    ["{eNotary}notifications","-2","0"],
    ["{eNotary}notifications","0","0"],
    ["{eNotary}UserManagement.php","-2","0"],
    ["{eNotary}UserManagement.php","0","0"],
    ["{eNotary}UserAccess.php","-2","0"],
    ["{eNotary}UserAccess.php","0","0"],
    ["{eNotary}psgc","-2","0"],
    ["{eNotary}psgc","0","0"],
    ["{eNotary}document_activity_logs","-2","0"],
    ["{eNotary}document_activity_logs","0","0"],
    ["{eNotary}document_attachments","-2","0"],
    ["{eNotary}document_attachments","0","0"],
    ["{eNotary}document_fields","-2","0"],
    ["{eNotary}document_fields","0","0"],
    ["{eNotary}document_templates","-2","0"],
    ["{eNotary}document_templates","0","0"],
    ["{eNotary}document_verification","-2","0"],
    ["{eNotary}document_verification","0","0"],
    ["{eNotary}documents","-2","0"],
    ["{eNotary}documents","0","0"],
    ["{eNotary}faq_categories","-2","0"],
    ["{eNotary}faq_categories","0","0"],
    ["{eNotary}faq_items","-2","0"],
    ["{eNotary}faq_items","0","0"],
    ["{eNotary}fee_schedules","-2","0"],
    ["{eNotary}fee_schedules","0","0"],
    ["{eNotary}notarization_queue","-2","0"],
    ["{eNotary}notarization_queue","0","0"],
    ["{eNotary}notarization_requests","-2","0"],
    ["{eNotary}notarization_requests","0","0"],
    ["{eNotary}notarized_documents","-2","0"],
    ["{eNotary}notarized_documents","0","0"],
    ["{eNotary}notary_qr_settings","-2","0"],
    ["{eNotary}notary_qr_settings","0","0"],
    ["{eNotary}payment_methods","-2","0"],
    ["{eNotary}payment_methods","0","0"],
    ["{eNotary}payment_transactions","-2","0"],
    ["{eNotary}payment_transactions","0","0"],
    ["{eNotary}pdf_metadata","-2","0"],
    ["{eNotary}pdf_metadata","0","0"],
    ["{eNotary}support_request_history","-2","0"],
    ["{eNotary}support_request_history","0","0"],
    ["{eNotary}support_requests","-2","0"],
    ["{eNotary}support_requests","0","0"],
    ["{eNotary}system_status","-2","0"],
    ["{eNotary}system_status","0","0"],
    ["{eNotary}template_categories","-2","0"],
    ["{eNotary}template_categories","0","0"],
    ["{eNotary}template_fields","-2","0"],
    ["{eNotary}template_fields","0","0"],
    ["{eNotary}user_templates","-2","0"],
    ["{eNotary}user_templates","0","0"],
    ["{eNotary}verification_attempts","-2","0"],
    ["{eNotary}verification_attempts","0","0"],
    ["{eNotary}refresh_tokens","-2","0"],
    ["{eNotary}refresh_tokens","0","0"],
    ["{eNotary}document_statuses","-2","0"],
    ["{eNotary}document_statuses","0","0"],
    ["{eNotary}document_status_view","-2","0"],
    ["{eNotary}document_status_view","0","0"],
    ["{eNotary}TransactionDashboard.php","-2","0"],
    ["{eNotary}TransactionDashboard.php","0","0"]];

/**
 * Tables
 *
 * @var array<string, string, string, bool, string>
 * [0] string Table name
 * [1] string Table variable name
 * [2] string Table caption
 * [3] bool Allowed for update (for userpriv.php)
 * [4] string Project ID
 * [5] string URL (for OthersController::index)
 */
$USER_LEVEL_TABLES = [["audit_logs","audit_logs","audit logs",false,"{eNotary}","AuditLogsList"],
    ["systems","systems","systems",true,"{eNotary}","SystemsList"],
    ["user_level_assignments","user_level_assignments","user level assignments",true,"{eNotary}","UserLevelAssignmentsList"],
    ["user_level_permissions","user_level_permissions","user level permissions",false,"{eNotary}",""],
    ["user_levels","_user_levels","user levels",true,"{eNotary}","UserLevelsList"],
    ["users","users","users",true,"{eNotary}","UsersList"],
    ["aggregated_audit_logs","aggregated_audit_logs","audit logs",true,"{eNotary}","AggregatedAuditLogsList"],
    ["MainDashboard.php","MainDashboard","Dashboard",true,"{eNotary}","MainDashboard"],
    ["departments","departments","departments",true,"{eNotary}","DepartmentsList"],
    ["notifications","notifications","notifications",true,"{eNotary}","NotificationsList"],
    ["UserManagement.php","UserManagement","User Level Management",true,"{eNotary}","UserManagement"],
    ["UserAccess.php","UserAccess","User Access Management",true,"{eNotary}","UserAccess"],
    ["psgc","psgc","PH Standard GeoCode",true,"{eNotary}","PsgcList"],
    ["document_activity_logs","document_activity_logs","document activity logs",true,"{eNotary}","DocumentActivityLogsList"],
    ["document_attachments","document_attachments","document attachments",true,"{eNotary}","DocumentAttachmentsList"],
    ["document_fields","document_fields","document fields",true,"{eNotary}","DocumentFieldsList"],
    ["document_templates","document_templates","document templates",true,"{eNotary}","DocumentTemplatesList"],
    ["document_verification","document_verification","document verification",true,"{eNotary}","DocumentVerificationList"],
    ["documents","documents","documents",true,"{eNotary}","DocumentsList"],
    ["faq_categories","faq_categories","faq categories",true,"{eNotary}","FaqCategoriesList"],
    ["faq_items","faq_items","faq items",true,"{eNotary}","FaqItemsList"],
    ["fee_schedules","fee_schedules","fee schedules",true,"{eNotary}","FeeSchedulesList"],
    ["notarization_queue","notarization_queue","notarization queue",true,"{eNotary}","NotarizationQueueList"],
    ["notarization_requests","notarization_requests","notarization requests",true,"{eNotary}","NotarizationRequestsList"],
    ["notarized_documents","notarized_documents","notarized documents",true,"{eNotary}","NotarizedDocumentsList"],
    ["notary_qr_settings","notary_qr_settings","notary qr settings",true,"{eNotary}","NotaryQrSettingsList"],
    ["payment_methods","payment_methods","payment methods",true,"{eNotary}","PaymentMethodsList"],
    ["payment_transactions","payment_transactions","payment transactions",true,"{eNotary}","PaymentTransactionsList"],
    ["pdf_metadata","pdf_metadata","pdf metadata",true,"{eNotary}","PdfMetadataList"],
    ["support_request_history","support_request_history","support request history",true,"{eNotary}","SupportRequestHistoryList"],
    ["support_requests","support_requests","support requests",true,"{eNotary}","SupportRequestsList"],
    ["system_status","system_status","system status",true,"{eNotary}","SystemStatusList"],
    ["template_categories","template_categories","template categories",true,"{eNotary}","TemplateCategoriesList"],
    ["template_fields","template_fields","template fields",true,"{eNotary}","TemplateFieldsList"],
    ["user_templates","user_templates","user templates",true,"{eNotary}","UserTemplatesList"],
    ["verification_attempts","verification_attempts","verification attempts",true,"{eNotary}","VerificationAttemptsList"],
    ["refresh_tokens","refresh_tokens","refresh tokens",true,"{eNotary}","RefreshTokensList"],
    ["document_statuses","document_statuses","document statuses",true,"{eNotary}","DocumentStatusesList"],
    ["document_status_view","document_status_view","document status view",true,"{eNotary}","DocumentStatusViewList"],
    ["TransactionDashboard.php","TransactionDashboard","Transaction Dashboard",true,"{eNotary}","TransactionDashboard"]];
