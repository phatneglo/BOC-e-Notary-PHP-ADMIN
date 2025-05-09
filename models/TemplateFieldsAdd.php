<?php

namespace PHPMaker2024\eNotary;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;
use Closure;

/**
 * Page class
 */
class TemplateFieldsAdd extends TemplateFields
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "TemplateFieldsAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "TemplateFieldsAdd";

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<div id="ew-page-header">' . $header . '</div>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<div id="ew-page-footer">' . $footer . '</div>';
        }
    }

    // Set field visibility
    public function setVisibility()
    {
        $this->field_id->Visible = false;
        $this->template_id->setVisibility();
        $this->field_name->setVisibility();
        $this->field_label->setVisibility();
        $this->field_type->setVisibility();
        $this->field_options->setVisibility();
        $this->is_required->setVisibility();
        $this->placeholder->setVisibility();
        $this->default_value->setVisibility();
        $this->field_order->setVisibility();
        $this->validation_rules->setVisibility();
        $this->help_text->setVisibility();
        $this->field_width->setVisibility();
        $this->is_visible->setVisibility();
        $this->section_name->setVisibility();
        $this->x_position->setVisibility();
        $this->y_position->setVisibility();
        $this->group_name->setVisibility();
        $this->conditional_display->setVisibility();
        $this->created_at->setVisibility();
        $this->section_id->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'template_fields';
        $this->TableName = 'template_fields';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (template_fields)
        if (!isset($GLOBALS["template_fields"]) || $GLOBALS["template_fields"]::class == PROJECT_NAMESPACE . "template_fields") {
            $GLOBALS["template_fields"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'template_fields');
        }

        // Start timer
        $DebugTimer = Container("debug.timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return $Response?->getBody() ?? ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }
        DispatchEvent(new PageUnloadedEvent($this), PageUnloadedEvent::NAME);
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (WithJsonResponse()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $pageName = GetPageName($url);
                $result = ["url" => GetUrl($url), "modal" => "1"];  // Assume return to modal for simplicity
                if (
                    SameString($pageName, GetPageName($this->getListUrl())) ||
                    SameString($pageName, GetPageName($this->getViewUrl())) ||
                    SameString($pageName, GetPageName(CurrentMasterTable()?->getViewUrl() ?? ""))
                ) { // List / View / Master View page
                    if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                        $result["caption"] = $this->getModalCaption($pageName);
                        $result["view"] = SameString($pageName, "TemplateFieldsView"); // If View page, no primary button
                    } else { // List page
                        $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                        $this->clearFailureMessage();
                    }
                } else { // Other pages (add messages and then clear messages)
                    $result = array_merge($this->getMessages(), ["modal" => "1"]);
                    $this->clearMessages();
                }
                WriteJson($result);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from result set
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Result set
            while ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($row);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DataType::BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['field_id'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->field_id->Visible = false;
        }
    }

    // Lookup data
    public function lookup(array $req = [], bool $response = true)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $req["field"] ?? null;
        if (!$fieldName) {
            return [];
        }
        $fld = $this->Fields[$fieldName];
        $lookup = $fld->Lookup;
        $name = $req["name"] ?? "";
        if (ContainsString($name, "query_builder_rule")) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $req["ajax"] ?? "unknown";
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $req["q"] ?? $req["sv"] ?? "";
            $pageSize = $req["n"] ?? $req["recperpage"] ?? 10;
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $req["q"] ?? "";
            $pageSize = $req["n"] ?? -1;
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $req["start"] ?? -1;
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $req["page"] ?? -1;
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($req["s"] ?? "");
        $userFilter = Decrypt($req["f"] ?? "");
        $userOrderBy = Decrypt($req["o"] ?? "");
        $keys = $req["keys"] ?? null;
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $req["v0"] ?? $req["lookupValue"] ?? "";
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $req["v" . $i] ?? "";
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, $response); // Use settings from current page
    }
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $CopyRecord;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Load user profile
        if (IsLoggedIn()) {
            Profile()->setUserName(CurrentUserName())->loadFromStorage();

            // Force logout user
            if (!IsSysAdmin() && Profile()->isForceLogout(session_id())) {
                $this->terminate("logout");
                return;
            }

            // Check if valid user and update last accessed time
            if (!IsSysAdmin() && !IsPasswordExpired() && !Profile()->isValidUser(session_id(), false)) {
                $this->terminate("logout"); // Handle as session expired
                return;
            }
        }

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        DispatchEvent(new PageLoadingEvent($this), PageLoadingEvent::NAME);

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->is_required);
        $this->setupLookupOptions($this->is_visible);

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action", "") !== "") {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("field_id") ?? Route("field_id")) !== null) {
                $this->field_id->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
                $this->setKey($this->OldKey); // Set up record key
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record or default values
        $rsold = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$rsold) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("TemplateFieldsList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($rsold)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "TemplateFieldsList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "TemplateFieldsView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "TemplateFieldsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "TemplateFieldsList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson(["success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage()]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = RowType::ADD; // Render add type

        // Render row
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            DispatchEvent(new PageRenderingEvent($this), PageRenderingEvent::NAME);

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->field_width->DefaultValue = $this->field_width->getDefault(); // PHP
        $this->field_width->OldValue = $this->field_width->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'template_id' first before field var 'x_template_id'
        $val = $CurrentForm->hasValue("template_id") ? $CurrentForm->getValue("template_id") : $CurrentForm->getValue("x_template_id");
        if (!$this->template_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->template_id->Visible = false; // Disable update for API request
            } else {
                $this->template_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'field_name' first before field var 'x_field_name'
        $val = $CurrentForm->hasValue("field_name") ? $CurrentForm->getValue("field_name") : $CurrentForm->getValue("x_field_name");
        if (!$this->field_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->field_name->Visible = false; // Disable update for API request
            } else {
                $this->field_name->setFormValue($val);
            }
        }

        // Check field name 'field_label' first before field var 'x_field_label'
        $val = $CurrentForm->hasValue("field_label") ? $CurrentForm->getValue("field_label") : $CurrentForm->getValue("x_field_label");
        if (!$this->field_label->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->field_label->Visible = false; // Disable update for API request
            } else {
                $this->field_label->setFormValue($val);
            }
        }

        // Check field name 'field_type' first before field var 'x_field_type'
        $val = $CurrentForm->hasValue("field_type") ? $CurrentForm->getValue("field_type") : $CurrentForm->getValue("x_field_type");
        if (!$this->field_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->field_type->Visible = false; // Disable update for API request
            } else {
                $this->field_type->setFormValue($val);
            }
        }

        // Check field name 'field_options' first before field var 'x_field_options'
        $val = $CurrentForm->hasValue("field_options") ? $CurrentForm->getValue("field_options") : $CurrentForm->getValue("x_field_options");
        if (!$this->field_options->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->field_options->Visible = false; // Disable update for API request
            } else {
                $this->field_options->setFormValue($val);
            }
        }

        // Check field name 'is_required' first before field var 'x_is_required'
        $val = $CurrentForm->hasValue("is_required") ? $CurrentForm->getValue("is_required") : $CurrentForm->getValue("x_is_required");
        if (!$this->is_required->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_required->Visible = false; // Disable update for API request
            } else {
                $this->is_required->setFormValue($val);
            }
        }

        // Check field name 'placeholder' first before field var 'x_placeholder'
        $val = $CurrentForm->hasValue("placeholder") ? $CurrentForm->getValue("placeholder") : $CurrentForm->getValue("x_placeholder");
        if (!$this->placeholder->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->placeholder->Visible = false; // Disable update for API request
            } else {
                $this->placeholder->setFormValue($val);
            }
        }

        // Check field name 'default_value' first before field var 'x_default_value'
        $val = $CurrentForm->hasValue("default_value") ? $CurrentForm->getValue("default_value") : $CurrentForm->getValue("x_default_value");
        if (!$this->default_value->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->default_value->Visible = false; // Disable update for API request
            } else {
                $this->default_value->setFormValue($val);
            }
        }

        // Check field name 'field_order' first before field var 'x_field_order'
        $val = $CurrentForm->hasValue("field_order") ? $CurrentForm->getValue("field_order") : $CurrentForm->getValue("x_field_order");
        if (!$this->field_order->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->field_order->Visible = false; // Disable update for API request
            } else {
                $this->field_order->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'validation_rules' first before field var 'x_validation_rules'
        $val = $CurrentForm->hasValue("validation_rules") ? $CurrentForm->getValue("validation_rules") : $CurrentForm->getValue("x_validation_rules");
        if (!$this->validation_rules->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->validation_rules->Visible = false; // Disable update for API request
            } else {
                $this->validation_rules->setFormValue($val);
            }
        }

        // Check field name 'help_text' first before field var 'x_help_text'
        $val = $CurrentForm->hasValue("help_text") ? $CurrentForm->getValue("help_text") : $CurrentForm->getValue("x_help_text");
        if (!$this->help_text->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->help_text->Visible = false; // Disable update for API request
            } else {
                $this->help_text->setFormValue($val);
            }
        }

        // Check field name 'field_width' first before field var 'x_field_width'
        $val = $CurrentForm->hasValue("field_width") ? $CurrentForm->getValue("field_width") : $CurrentForm->getValue("x_field_width");
        if (!$this->field_width->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->field_width->Visible = false; // Disable update for API request
            } else {
                $this->field_width->setFormValue($val);
            }
        }

        // Check field name 'is_visible' first before field var 'x_is_visible'
        $val = $CurrentForm->hasValue("is_visible") ? $CurrentForm->getValue("is_visible") : $CurrentForm->getValue("x_is_visible");
        if (!$this->is_visible->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_visible->Visible = false; // Disable update for API request
            } else {
                $this->is_visible->setFormValue($val);
            }
        }

        // Check field name 'section_name' first before field var 'x_section_name'
        $val = $CurrentForm->hasValue("section_name") ? $CurrentForm->getValue("section_name") : $CurrentForm->getValue("x_section_name");
        if (!$this->section_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->section_name->Visible = false; // Disable update for API request
            } else {
                $this->section_name->setFormValue($val);
            }
        }

        // Check field name 'x_position' first before field var 'x_x_position'
        $val = $CurrentForm->hasValue("x_position") ? $CurrentForm->getValue("x_position") : $CurrentForm->getValue("x_x_position");
        if (!$this->x_position->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->x_position->Visible = false; // Disable update for API request
            } else {
                $this->x_position->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'y_position' first before field var 'x_y_position'
        $val = $CurrentForm->hasValue("y_position") ? $CurrentForm->getValue("y_position") : $CurrentForm->getValue("x_y_position");
        if (!$this->y_position->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->y_position->Visible = false; // Disable update for API request
            } else {
                $this->y_position->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'group_name' first before field var 'x_group_name'
        $val = $CurrentForm->hasValue("group_name") ? $CurrentForm->getValue("group_name") : $CurrentForm->getValue("x_group_name");
        if (!$this->group_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->group_name->Visible = false; // Disable update for API request
            } else {
                $this->group_name->setFormValue($val);
            }
        }

        // Check field name 'conditional_display' first before field var 'x_conditional_display'
        $val = $CurrentForm->hasValue("conditional_display") ? $CurrentForm->getValue("conditional_display") : $CurrentForm->getValue("x_conditional_display");
        if (!$this->conditional_display->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->conditional_display->Visible = false; // Disable update for API request
            } else {
                $this->conditional_display->setFormValue($val);
            }
        }

        // Check field name 'created_at' first before field var 'x_created_at'
        $val = $CurrentForm->hasValue("created_at") ? $CurrentForm->getValue("created_at") : $CurrentForm->getValue("x_created_at");
        if (!$this->created_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->created_at->Visible = false; // Disable update for API request
            } else {
                $this->created_at->setFormValue($val, true, $validate);
            }
            $this->created_at->CurrentValue = UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        }

        // Check field name 'section_id' first before field var 'x_section_id'
        $val = $CurrentForm->hasValue("section_id") ? $CurrentForm->getValue("section_id") : $CurrentForm->getValue("x_section_id");
        if (!$this->section_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->section_id->Visible = false; // Disable update for API request
            } else {
                $this->section_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'field_id' first before field var 'x_field_id'
        $val = $CurrentForm->hasValue("field_id") ? $CurrentForm->getValue("field_id") : $CurrentForm->getValue("x_field_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->template_id->CurrentValue = $this->template_id->FormValue;
        $this->field_name->CurrentValue = $this->field_name->FormValue;
        $this->field_label->CurrentValue = $this->field_label->FormValue;
        $this->field_type->CurrentValue = $this->field_type->FormValue;
        $this->field_options->CurrentValue = $this->field_options->FormValue;
        $this->is_required->CurrentValue = $this->is_required->FormValue;
        $this->placeholder->CurrentValue = $this->placeholder->FormValue;
        $this->default_value->CurrentValue = $this->default_value->FormValue;
        $this->field_order->CurrentValue = $this->field_order->FormValue;
        $this->validation_rules->CurrentValue = $this->validation_rules->FormValue;
        $this->help_text->CurrentValue = $this->help_text->FormValue;
        $this->field_width->CurrentValue = $this->field_width->FormValue;
        $this->is_visible->CurrentValue = $this->is_visible->FormValue;
        $this->section_name->CurrentValue = $this->section_name->FormValue;
        $this->x_position->CurrentValue = $this->x_position->FormValue;
        $this->y_position->CurrentValue = $this->y_position->FormValue;
        $this->group_name->CurrentValue = $this->group_name->FormValue;
        $this->conditional_display->CurrentValue = $this->conditional_display->FormValue;
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->section_id->CurrentValue = $this->section_id->FormValue;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from result set or record
     *
     * @param array $row Record
     * @return void
     */
    public function loadRowValues($row = null)
    {
        $row = is_array($row) ? $row : $this->newRow();

        // Call Row Selected event
        $this->rowSelected($row);
        $this->field_id->setDbValue($row['field_id']);
        $this->template_id->setDbValue($row['template_id']);
        $this->field_name->setDbValue($row['field_name']);
        $this->field_label->setDbValue($row['field_label']);
        $this->field_type->setDbValue($row['field_type']);
        $this->field_options->setDbValue($row['field_options']);
        $this->is_required->setDbValue((ConvertToBool($row['is_required']) ? "1" : "0"));
        $this->placeholder->setDbValue($row['placeholder']);
        $this->default_value->setDbValue($row['default_value']);
        $this->field_order->setDbValue($row['field_order']);
        $this->validation_rules->setDbValue($row['validation_rules']);
        $this->help_text->setDbValue($row['help_text']);
        $this->field_width->setDbValue($row['field_width']);
        $this->is_visible->setDbValue((ConvertToBool($row['is_visible']) ? "1" : "0"));
        $this->section_name->setDbValue($row['section_name']);
        $this->x_position->setDbValue($row['x_position']);
        $this->y_position->setDbValue($row['y_position']);
        $this->group_name->setDbValue($row['group_name']);
        $this->conditional_display->setDbValue($row['conditional_display']);
        $this->created_at->setDbValue($row['created_at']);
        $this->section_id->setDbValue($row['section_id']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['field_id'] = $this->field_id->DefaultValue;
        $row['template_id'] = $this->template_id->DefaultValue;
        $row['field_name'] = $this->field_name->DefaultValue;
        $row['field_label'] = $this->field_label->DefaultValue;
        $row['field_type'] = $this->field_type->DefaultValue;
        $row['field_options'] = $this->field_options->DefaultValue;
        $row['is_required'] = $this->is_required->DefaultValue;
        $row['placeholder'] = $this->placeholder->DefaultValue;
        $row['default_value'] = $this->default_value->DefaultValue;
        $row['field_order'] = $this->field_order->DefaultValue;
        $row['validation_rules'] = $this->validation_rules->DefaultValue;
        $row['help_text'] = $this->help_text->DefaultValue;
        $row['field_width'] = $this->field_width->DefaultValue;
        $row['is_visible'] = $this->is_visible->DefaultValue;
        $row['section_name'] = $this->section_name->DefaultValue;
        $row['x_position'] = $this->x_position->DefaultValue;
        $row['y_position'] = $this->y_position->DefaultValue;
        $row['group_name'] = $this->group_name->DefaultValue;
        $row['conditional_display'] = $this->conditional_display->DefaultValue;
        $row['created_at'] = $this->created_at->DefaultValue;
        $row['section_id'] = $this->section_id->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = ExecuteQuery($sql, $conn);
            if ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // field_id
        $this->field_id->RowCssClass = "row";

        // template_id
        $this->template_id->RowCssClass = "row";

        // field_name
        $this->field_name->RowCssClass = "row";

        // field_label
        $this->field_label->RowCssClass = "row";

        // field_type
        $this->field_type->RowCssClass = "row";

        // field_options
        $this->field_options->RowCssClass = "row";

        // is_required
        $this->is_required->RowCssClass = "row";

        // placeholder
        $this->placeholder->RowCssClass = "row";

        // default_value
        $this->default_value->RowCssClass = "row";

        // field_order
        $this->field_order->RowCssClass = "row";

        // validation_rules
        $this->validation_rules->RowCssClass = "row";

        // help_text
        $this->help_text->RowCssClass = "row";

        // field_width
        $this->field_width->RowCssClass = "row";

        // is_visible
        $this->is_visible->RowCssClass = "row";

        // section_name
        $this->section_name->RowCssClass = "row";

        // x_position
        $this->x_position->RowCssClass = "row";

        // y_position
        $this->y_position->RowCssClass = "row";

        // group_name
        $this->group_name->RowCssClass = "row";

        // conditional_display
        $this->conditional_display->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // section_id
        $this->section_id->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // field_id
            $this->field_id->ViewValue = $this->field_id->CurrentValue;

            // template_id
            $this->template_id->ViewValue = $this->template_id->CurrentValue;
            $this->template_id->ViewValue = FormatNumber($this->template_id->ViewValue, $this->template_id->formatPattern());

            // field_name
            $this->field_name->ViewValue = $this->field_name->CurrentValue;

            // field_label
            $this->field_label->ViewValue = $this->field_label->CurrentValue;

            // field_type
            $this->field_type->ViewValue = $this->field_type->CurrentValue;

            // field_options
            $this->field_options->ViewValue = $this->field_options->CurrentValue;

            // is_required
            if (ConvertToBool($this->is_required->CurrentValue)) {
                $this->is_required->ViewValue = $this->is_required->tagCaption(1) != "" ? $this->is_required->tagCaption(1) : "Yes";
            } else {
                $this->is_required->ViewValue = $this->is_required->tagCaption(2) != "" ? $this->is_required->tagCaption(2) : "No";
            }

            // placeholder
            $this->placeholder->ViewValue = $this->placeholder->CurrentValue;

            // default_value
            $this->default_value->ViewValue = $this->default_value->CurrentValue;

            // field_order
            $this->field_order->ViewValue = $this->field_order->CurrentValue;
            $this->field_order->ViewValue = FormatNumber($this->field_order->ViewValue, $this->field_order->formatPattern());

            // validation_rules
            $this->validation_rules->ViewValue = $this->validation_rules->CurrentValue;

            // help_text
            $this->help_text->ViewValue = $this->help_text->CurrentValue;

            // field_width
            $this->field_width->ViewValue = $this->field_width->CurrentValue;

            // is_visible
            if (ConvertToBool($this->is_visible->CurrentValue)) {
                $this->is_visible->ViewValue = $this->is_visible->tagCaption(1) != "" ? $this->is_visible->tagCaption(1) : "Yes";
            } else {
                $this->is_visible->ViewValue = $this->is_visible->tagCaption(2) != "" ? $this->is_visible->tagCaption(2) : "No";
            }

            // section_name
            $this->section_name->ViewValue = $this->section_name->CurrentValue;

            // x_position
            $this->x_position->ViewValue = $this->x_position->CurrentValue;
            $this->x_position->ViewValue = FormatNumber($this->x_position->ViewValue, $this->x_position->formatPattern());

            // y_position
            $this->y_position->ViewValue = $this->y_position->CurrentValue;
            $this->y_position->ViewValue = FormatNumber($this->y_position->ViewValue, $this->y_position->formatPattern());

            // group_name
            $this->group_name->ViewValue = $this->group_name->CurrentValue;

            // conditional_display
            $this->conditional_display->ViewValue = $this->conditional_display->CurrentValue;

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // section_id
            $this->section_id->ViewValue = $this->section_id->CurrentValue;
            $this->section_id->ViewValue = FormatNumber($this->section_id->ViewValue, $this->section_id->formatPattern());

            // template_id
            $this->template_id->HrefValue = "";

            // field_name
            $this->field_name->HrefValue = "";

            // field_label
            $this->field_label->HrefValue = "";

            // field_type
            $this->field_type->HrefValue = "";

            // field_options
            $this->field_options->HrefValue = "";

            // is_required
            $this->is_required->HrefValue = "";

            // placeholder
            $this->placeholder->HrefValue = "";

            // default_value
            $this->default_value->HrefValue = "";

            // field_order
            $this->field_order->HrefValue = "";

            // validation_rules
            $this->validation_rules->HrefValue = "";

            // help_text
            $this->help_text->HrefValue = "";

            // field_width
            $this->field_width->HrefValue = "";

            // is_visible
            $this->is_visible->HrefValue = "";

            // section_name
            $this->section_name->HrefValue = "";

            // x_position
            $this->x_position->HrefValue = "";

            // y_position
            $this->y_position->HrefValue = "";

            // group_name
            $this->group_name->HrefValue = "";

            // conditional_display
            $this->conditional_display->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // section_id
            $this->section_id->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // template_id
            $this->template_id->setupEditAttributes();
            $this->template_id->EditValue = $this->template_id->CurrentValue;
            $this->template_id->PlaceHolder = RemoveHtml($this->template_id->caption());
            if (strval($this->template_id->EditValue) != "" && is_numeric($this->template_id->EditValue)) {
                $this->template_id->EditValue = FormatNumber($this->template_id->EditValue, null);
            }

            // field_name
            $this->field_name->setupEditAttributes();
            if (!$this->field_name->Raw) {
                $this->field_name->CurrentValue = HtmlDecode($this->field_name->CurrentValue);
            }
            $this->field_name->EditValue = HtmlEncode($this->field_name->CurrentValue);
            $this->field_name->PlaceHolder = RemoveHtml($this->field_name->caption());

            // field_label
            $this->field_label->setupEditAttributes();
            if (!$this->field_label->Raw) {
                $this->field_label->CurrentValue = HtmlDecode($this->field_label->CurrentValue);
            }
            $this->field_label->EditValue = HtmlEncode($this->field_label->CurrentValue);
            $this->field_label->PlaceHolder = RemoveHtml($this->field_label->caption());

            // field_type
            $this->field_type->setupEditAttributes();
            if (!$this->field_type->Raw) {
                $this->field_type->CurrentValue = HtmlDecode($this->field_type->CurrentValue);
            }
            $this->field_type->EditValue = HtmlEncode($this->field_type->CurrentValue);
            $this->field_type->PlaceHolder = RemoveHtml($this->field_type->caption());

            // field_options
            $this->field_options->setupEditAttributes();
            $this->field_options->EditValue = HtmlEncode($this->field_options->CurrentValue);
            $this->field_options->PlaceHolder = RemoveHtml($this->field_options->caption());

            // is_required
            $this->is_required->EditValue = $this->is_required->options(false);
            $this->is_required->PlaceHolder = RemoveHtml($this->is_required->caption());

            // placeholder
            $this->placeholder->setupEditAttributes();
            $this->placeholder->EditValue = HtmlEncode($this->placeholder->CurrentValue);
            $this->placeholder->PlaceHolder = RemoveHtml($this->placeholder->caption());

            // default_value
            $this->default_value->setupEditAttributes();
            $this->default_value->EditValue = HtmlEncode($this->default_value->CurrentValue);
            $this->default_value->PlaceHolder = RemoveHtml($this->default_value->caption());

            // field_order
            $this->field_order->setupEditAttributes();
            $this->field_order->EditValue = $this->field_order->CurrentValue;
            $this->field_order->PlaceHolder = RemoveHtml($this->field_order->caption());
            if (strval($this->field_order->EditValue) != "" && is_numeric($this->field_order->EditValue)) {
                $this->field_order->EditValue = FormatNumber($this->field_order->EditValue, null);
            }

            // validation_rules
            $this->validation_rules->setupEditAttributes();
            $this->validation_rules->EditValue = HtmlEncode($this->validation_rules->CurrentValue);
            $this->validation_rules->PlaceHolder = RemoveHtml($this->validation_rules->caption());

            // help_text
            $this->help_text->setupEditAttributes();
            $this->help_text->EditValue = HtmlEncode($this->help_text->CurrentValue);
            $this->help_text->PlaceHolder = RemoveHtml($this->help_text->caption());

            // field_width
            $this->field_width->setupEditAttributes();
            if (!$this->field_width->Raw) {
                $this->field_width->CurrentValue = HtmlDecode($this->field_width->CurrentValue);
            }
            $this->field_width->EditValue = HtmlEncode($this->field_width->CurrentValue);
            $this->field_width->PlaceHolder = RemoveHtml($this->field_width->caption());

            // is_visible
            $this->is_visible->EditValue = $this->is_visible->options(false);
            $this->is_visible->PlaceHolder = RemoveHtml($this->is_visible->caption());

            // section_name
            $this->section_name->setupEditAttributes();
            if (!$this->section_name->Raw) {
                $this->section_name->CurrentValue = HtmlDecode($this->section_name->CurrentValue);
            }
            $this->section_name->EditValue = HtmlEncode($this->section_name->CurrentValue);
            $this->section_name->PlaceHolder = RemoveHtml($this->section_name->caption());

            // x_position
            $this->x_position->setupEditAttributes();
            $this->x_position->EditValue = $this->x_position->CurrentValue;
            $this->x_position->PlaceHolder = RemoveHtml($this->x_position->caption());
            if (strval($this->x_position->EditValue) != "" && is_numeric($this->x_position->EditValue)) {
                $this->x_position->EditValue = FormatNumber($this->x_position->EditValue, null);
            }

            // y_position
            $this->y_position->setupEditAttributes();
            $this->y_position->EditValue = $this->y_position->CurrentValue;
            $this->y_position->PlaceHolder = RemoveHtml($this->y_position->caption());
            if (strval($this->y_position->EditValue) != "" && is_numeric($this->y_position->EditValue)) {
                $this->y_position->EditValue = FormatNumber($this->y_position->EditValue, null);
            }

            // group_name
            $this->group_name->setupEditAttributes();
            if (!$this->group_name->Raw) {
                $this->group_name->CurrentValue = HtmlDecode($this->group_name->CurrentValue);
            }
            $this->group_name->EditValue = HtmlEncode($this->group_name->CurrentValue);
            $this->group_name->PlaceHolder = RemoveHtml($this->group_name->caption());

            // conditional_display
            $this->conditional_display->setupEditAttributes();
            $this->conditional_display->EditValue = HtmlEncode($this->conditional_display->CurrentValue);
            $this->conditional_display->PlaceHolder = RemoveHtml($this->conditional_display->caption());

            // created_at
            $this->created_at->setupEditAttributes();
            $this->created_at->EditValue = HtmlEncode(FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()));
            $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

            // section_id
            $this->section_id->setupEditAttributes();
            $this->section_id->EditValue = $this->section_id->CurrentValue;
            $this->section_id->PlaceHolder = RemoveHtml($this->section_id->caption());
            if (strval($this->section_id->EditValue) != "" && is_numeric($this->section_id->EditValue)) {
                $this->section_id->EditValue = FormatNumber($this->section_id->EditValue, null);
            }

            // Add refer script

            // template_id
            $this->template_id->HrefValue = "";

            // field_name
            $this->field_name->HrefValue = "";

            // field_label
            $this->field_label->HrefValue = "";

            // field_type
            $this->field_type->HrefValue = "";

            // field_options
            $this->field_options->HrefValue = "";

            // is_required
            $this->is_required->HrefValue = "";

            // placeholder
            $this->placeholder->HrefValue = "";

            // default_value
            $this->default_value->HrefValue = "";

            // field_order
            $this->field_order->HrefValue = "";

            // validation_rules
            $this->validation_rules->HrefValue = "";

            // help_text
            $this->help_text->HrefValue = "";

            // field_width
            $this->field_width->HrefValue = "";

            // is_visible
            $this->is_visible->HrefValue = "";

            // section_name
            $this->section_name->HrefValue = "";

            // x_position
            $this->x_position->HrefValue = "";

            // y_position
            $this->y_position->HrefValue = "";

            // group_name
            $this->group_name->HrefValue = "";

            // conditional_display
            $this->conditional_display->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // section_id
            $this->section_id->HrefValue = "";
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
            if ($this->template_id->Visible && $this->template_id->Required) {
                if (!$this->template_id->IsDetailKey && EmptyValue($this->template_id->FormValue)) {
                    $this->template_id->addErrorMessage(str_replace("%s", $this->template_id->caption(), $this->template_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->template_id->FormValue)) {
                $this->template_id->addErrorMessage($this->template_id->getErrorMessage(false));
            }
            if ($this->field_name->Visible && $this->field_name->Required) {
                if (!$this->field_name->IsDetailKey && EmptyValue($this->field_name->FormValue)) {
                    $this->field_name->addErrorMessage(str_replace("%s", $this->field_name->caption(), $this->field_name->RequiredErrorMessage));
                }
            }
            if ($this->field_label->Visible && $this->field_label->Required) {
                if (!$this->field_label->IsDetailKey && EmptyValue($this->field_label->FormValue)) {
                    $this->field_label->addErrorMessage(str_replace("%s", $this->field_label->caption(), $this->field_label->RequiredErrorMessage));
                }
            }
            if ($this->field_type->Visible && $this->field_type->Required) {
                if (!$this->field_type->IsDetailKey && EmptyValue($this->field_type->FormValue)) {
                    $this->field_type->addErrorMessage(str_replace("%s", $this->field_type->caption(), $this->field_type->RequiredErrorMessage));
                }
            }
            if ($this->field_options->Visible && $this->field_options->Required) {
                if (!$this->field_options->IsDetailKey && EmptyValue($this->field_options->FormValue)) {
                    $this->field_options->addErrorMessage(str_replace("%s", $this->field_options->caption(), $this->field_options->RequiredErrorMessage));
                }
            }
            if ($this->is_required->Visible && $this->is_required->Required) {
                if ($this->is_required->FormValue == "") {
                    $this->is_required->addErrorMessage(str_replace("%s", $this->is_required->caption(), $this->is_required->RequiredErrorMessage));
                }
            }
            if ($this->placeholder->Visible && $this->placeholder->Required) {
                if (!$this->placeholder->IsDetailKey && EmptyValue($this->placeholder->FormValue)) {
                    $this->placeholder->addErrorMessage(str_replace("%s", $this->placeholder->caption(), $this->placeholder->RequiredErrorMessage));
                }
            }
            if ($this->default_value->Visible && $this->default_value->Required) {
                if (!$this->default_value->IsDetailKey && EmptyValue($this->default_value->FormValue)) {
                    $this->default_value->addErrorMessage(str_replace("%s", $this->default_value->caption(), $this->default_value->RequiredErrorMessage));
                }
            }
            if ($this->field_order->Visible && $this->field_order->Required) {
                if (!$this->field_order->IsDetailKey && EmptyValue($this->field_order->FormValue)) {
                    $this->field_order->addErrorMessage(str_replace("%s", $this->field_order->caption(), $this->field_order->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->field_order->FormValue)) {
                $this->field_order->addErrorMessage($this->field_order->getErrorMessage(false));
            }
            if ($this->validation_rules->Visible && $this->validation_rules->Required) {
                if (!$this->validation_rules->IsDetailKey && EmptyValue($this->validation_rules->FormValue)) {
                    $this->validation_rules->addErrorMessage(str_replace("%s", $this->validation_rules->caption(), $this->validation_rules->RequiredErrorMessage));
                }
            }
            if ($this->help_text->Visible && $this->help_text->Required) {
                if (!$this->help_text->IsDetailKey && EmptyValue($this->help_text->FormValue)) {
                    $this->help_text->addErrorMessage(str_replace("%s", $this->help_text->caption(), $this->help_text->RequiredErrorMessage));
                }
            }
            if ($this->field_width->Visible && $this->field_width->Required) {
                if (!$this->field_width->IsDetailKey && EmptyValue($this->field_width->FormValue)) {
                    $this->field_width->addErrorMessage(str_replace("%s", $this->field_width->caption(), $this->field_width->RequiredErrorMessage));
                }
            }
            if ($this->is_visible->Visible && $this->is_visible->Required) {
                if ($this->is_visible->FormValue == "") {
                    $this->is_visible->addErrorMessage(str_replace("%s", $this->is_visible->caption(), $this->is_visible->RequiredErrorMessage));
                }
            }
            if ($this->section_name->Visible && $this->section_name->Required) {
                if (!$this->section_name->IsDetailKey && EmptyValue($this->section_name->FormValue)) {
                    $this->section_name->addErrorMessage(str_replace("%s", $this->section_name->caption(), $this->section_name->RequiredErrorMessage));
                }
            }
            if ($this->x_position->Visible && $this->x_position->Required) {
                if (!$this->x_position->IsDetailKey && EmptyValue($this->x_position->FormValue)) {
                    $this->x_position->addErrorMessage(str_replace("%s", $this->x_position->caption(), $this->x_position->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->x_position->FormValue)) {
                $this->x_position->addErrorMessage($this->x_position->getErrorMessage(false));
            }
            if ($this->y_position->Visible && $this->y_position->Required) {
                if (!$this->y_position->IsDetailKey && EmptyValue($this->y_position->FormValue)) {
                    $this->y_position->addErrorMessage(str_replace("%s", $this->y_position->caption(), $this->y_position->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->y_position->FormValue)) {
                $this->y_position->addErrorMessage($this->y_position->getErrorMessage(false));
            }
            if ($this->group_name->Visible && $this->group_name->Required) {
                if (!$this->group_name->IsDetailKey && EmptyValue($this->group_name->FormValue)) {
                    $this->group_name->addErrorMessage(str_replace("%s", $this->group_name->caption(), $this->group_name->RequiredErrorMessage));
                }
            }
            if ($this->conditional_display->Visible && $this->conditional_display->Required) {
                if (!$this->conditional_display->IsDetailKey && EmptyValue($this->conditional_display->FormValue)) {
                    $this->conditional_display->addErrorMessage(str_replace("%s", $this->conditional_display->caption(), $this->conditional_display->RequiredErrorMessage));
                }
            }
            if ($this->created_at->Visible && $this->created_at->Required) {
                if (!$this->created_at->IsDetailKey && EmptyValue($this->created_at->FormValue)) {
                    $this->created_at->addErrorMessage(str_replace("%s", $this->created_at->caption(), $this->created_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->created_at->FormValue, $this->created_at->formatPattern())) {
                $this->created_at->addErrorMessage($this->created_at->getErrorMessage(false));
            }
            if ($this->section_id->Visible && $this->section_id->Required) {
                if (!$this->section_id->IsDetailKey && EmptyValue($this->section_id->FormValue)) {
                    $this->section_id->addErrorMessage(str_replace("%s", $this->section_id->caption(), $this->section_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->section_id->FormValue)) {
                $this->section_id->addErrorMessage($this->section_id->getErrorMessage(false));
            }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Get new row
        $rsnew = $this->getAddRow();

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            } elseif (!EmptyValue($this->DbErrorMessage)) { // Show database error
                $this->setFailureMessage($this->DbErrorMessage);
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
        }
        return $addRow;
    }

    /**
     * Get add row
     *
     * @return array
     */
    protected function getAddRow()
    {
        global $Security;
        $rsnew = [];

        // template_id
        $this->template_id->setDbValueDef($rsnew, $this->template_id->CurrentValue, false);

        // field_name
        $this->field_name->setDbValueDef($rsnew, $this->field_name->CurrentValue, false);

        // field_label
        $this->field_label->setDbValueDef($rsnew, $this->field_label->CurrentValue, false);

        // field_type
        $this->field_type->setDbValueDef($rsnew, $this->field_type->CurrentValue, false);

        // field_options
        $this->field_options->setDbValueDef($rsnew, $this->field_options->CurrentValue, false);

        // is_required
        $tmpBool = $this->is_required->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_required->setDbValueDef($rsnew, $tmpBool, strval($this->is_required->CurrentValue) == "");

        // placeholder
        $this->placeholder->setDbValueDef($rsnew, $this->placeholder->CurrentValue, false);

        // default_value
        $this->default_value->setDbValueDef($rsnew, $this->default_value->CurrentValue, false);

        // field_order
        $this->field_order->setDbValueDef($rsnew, $this->field_order->CurrentValue, false);

        // validation_rules
        $this->validation_rules->setDbValueDef($rsnew, $this->validation_rules->CurrentValue, false);

        // help_text
        $this->help_text->setDbValueDef($rsnew, $this->help_text->CurrentValue, false);

        // field_width
        $this->field_width->setDbValueDef($rsnew, $this->field_width->CurrentValue, strval($this->field_width->CurrentValue) == "");

        // is_visible
        $tmpBool = $this->is_visible->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_visible->setDbValueDef($rsnew, $tmpBool, strval($this->is_visible->CurrentValue) == "");

        // section_name
        $this->section_name->setDbValueDef($rsnew, $this->section_name->CurrentValue, false);

        // x_position
        $this->x_position->setDbValueDef($rsnew, $this->x_position->CurrentValue, false);

        // y_position
        $this->y_position->setDbValueDef($rsnew, $this->y_position->CurrentValue, false);

        // group_name
        $this->group_name->setDbValueDef($rsnew, $this->group_name->CurrentValue, false);

        // conditional_display
        $this->conditional_display->setDbValueDef($rsnew, $this->conditional_display->CurrentValue, false);

        // created_at
        $this->created_at->setDbValueDef($rsnew, UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()), false);

        // section_id
        $this->section_id->setDbValueDef($rsnew, $this->section_id->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['template_id'])) { // template_id
            $this->template_id->setFormValue($row['template_id']);
        }
        if (isset($row['field_name'])) { // field_name
            $this->field_name->setFormValue($row['field_name']);
        }
        if (isset($row['field_label'])) { // field_label
            $this->field_label->setFormValue($row['field_label']);
        }
        if (isset($row['field_type'])) { // field_type
            $this->field_type->setFormValue($row['field_type']);
        }
        if (isset($row['field_options'])) { // field_options
            $this->field_options->setFormValue($row['field_options']);
        }
        if (isset($row['is_required'])) { // is_required
            $this->is_required->setFormValue($row['is_required']);
        }
        if (isset($row['placeholder'])) { // placeholder
            $this->placeholder->setFormValue($row['placeholder']);
        }
        if (isset($row['default_value'])) { // default_value
            $this->default_value->setFormValue($row['default_value']);
        }
        if (isset($row['field_order'])) { // field_order
            $this->field_order->setFormValue($row['field_order']);
        }
        if (isset($row['validation_rules'])) { // validation_rules
            $this->validation_rules->setFormValue($row['validation_rules']);
        }
        if (isset($row['help_text'])) { // help_text
            $this->help_text->setFormValue($row['help_text']);
        }
        if (isset($row['field_width'])) { // field_width
            $this->field_width->setFormValue($row['field_width']);
        }
        if (isset($row['is_visible'])) { // is_visible
            $this->is_visible->setFormValue($row['is_visible']);
        }
        if (isset($row['section_name'])) { // section_name
            $this->section_name->setFormValue($row['section_name']);
        }
        if (isset($row['x_position'])) { // x_position
            $this->x_position->setFormValue($row['x_position']);
        }
        if (isset($row['y_position'])) { // y_position
            $this->y_position->setFormValue($row['y_position']);
        }
        if (isset($row['group_name'])) { // group_name
            $this->group_name->setFormValue($row['group_name']);
        }
        if (isset($row['conditional_display'])) { // conditional_display
            $this->conditional_display->setFormValue($row['conditional_display']);
        }
        if (isset($row['created_at'])) { // created_at
            $this->created_at->setFormValue($row['created_at']);
        }
        if (isset($row['section_id'])) { // section_id
            $this->section_id->setFormValue($row['section_id']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("TemplateFieldsList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_is_required":
                    break;
                case "x_is_visible":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == "success") {
            //$msg = "your success message";
        } elseif ($type == "failure") {
            //$msg = "your failure message";
        } elseif ($type == "warning") {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
