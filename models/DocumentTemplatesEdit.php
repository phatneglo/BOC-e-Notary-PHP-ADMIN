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
class DocumentTemplatesEdit extends DocumentTemplates
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "DocumentTemplatesEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "DocumentTemplatesEdit";

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
        $this->template_id->setVisibility();
        $this->template_name->setVisibility();
        $this->template_code->setVisibility();
        $this->category_id->setVisibility();
        $this->description->setVisibility();
        $this->html_content->setVisibility();
        $this->is_active->setVisibility();
        $this->created_at->setVisibility();
        $this->created_by->setVisibility();
        $this->updated_at->setVisibility();
        $this->updated_by->setVisibility();
        $this->version->setVisibility();
        $this->notary_required->setVisibility();
        $this->fee_amount->setVisibility();
        $this->approval_workflow->setVisibility();
        $this->template_type->setVisibility();
        $this->header_text->setVisibility();
        $this->footer_text->setVisibility();
        $this->preview_image_path->setVisibility();
        $this->is_system->setVisibility();
        $this->owner_id->setVisibility();
        $this->original_template_id->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'document_templates';
        $this->TableName = 'document_templates';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (document_templates)
        if (!isset($GLOBALS["document_templates"]) || $GLOBALS["document_templates"]::class == PROJECT_NAMESPACE . "document_templates") {
            $GLOBALS["document_templates"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'document_templates');
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
                        $result["view"] = SameString($pageName, "DocumentTemplatesView"); // If View page, no primary button
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
            $key .= @$ar['template_id'];
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
            $this->template_id->Visible = false;
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

    // Properties
    public $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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
        $this->setupLookupOptions($this->is_active);
        $this->setupLookupOptions($this->notary_required);
        $this->setupLookupOptions($this->is_system);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("template_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->template_id->setQueryStringValue($keyValue);
                $this->template_id->setOldValue($this->template_id->QueryStringValue);
            } elseif (Post("template_id") !== null) {
                $this->template_id->setFormValue(Post("template_id"));
                $this->template_id->setOldValue($this->template_id->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action", "") !== "") {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("template_id") ?? Route("template_id")) !== null) {
                    $this->template_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->template_id->CurrentValue = null;
                }
            }

            // Load result set
            if ($this->isShow()) {
                    // Load current record
                    $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("DocumentTemplatesList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "DocumentTemplatesList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "DocumentTemplatesList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "DocumentTemplatesList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
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
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = RowType::EDIT; // Render as Edit
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

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'template_id' first before field var 'x_template_id'
        $val = $CurrentForm->hasValue("template_id") ? $CurrentForm->getValue("template_id") : $CurrentForm->getValue("x_template_id");
        if (!$this->template_id->IsDetailKey) {
            $this->template_id->setFormValue($val);
        }

        // Check field name 'template_name' first before field var 'x_template_name'
        $val = $CurrentForm->hasValue("template_name") ? $CurrentForm->getValue("template_name") : $CurrentForm->getValue("x_template_name");
        if (!$this->template_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->template_name->Visible = false; // Disable update for API request
            } else {
                $this->template_name->setFormValue($val);
            }
        }

        // Check field name 'template_code' first before field var 'x_template_code'
        $val = $CurrentForm->hasValue("template_code") ? $CurrentForm->getValue("template_code") : $CurrentForm->getValue("x_template_code");
        if (!$this->template_code->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->template_code->Visible = false; // Disable update for API request
            } else {
                $this->template_code->setFormValue($val);
            }
        }

        // Check field name 'category_id' first before field var 'x_category_id'
        $val = $CurrentForm->hasValue("category_id") ? $CurrentForm->getValue("category_id") : $CurrentForm->getValue("x_category_id");
        if (!$this->category_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->category_id->Visible = false; // Disable update for API request
            } else {
                $this->category_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'description' first before field var 'x_description'
        $val = $CurrentForm->hasValue("description") ? $CurrentForm->getValue("description") : $CurrentForm->getValue("x_description");
        if (!$this->description->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->description->Visible = false; // Disable update for API request
            } else {
                $this->description->setFormValue($val);
            }
        }

        // Check field name 'html_content' first before field var 'x_html_content'
        $val = $CurrentForm->hasValue("html_content") ? $CurrentForm->getValue("html_content") : $CurrentForm->getValue("x_html_content");
        if (!$this->html_content->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->html_content->Visible = false; // Disable update for API request
            } else {
                $this->html_content->setFormValue($val);
            }
        }

        // Check field name 'is_active' first before field var 'x_is_active'
        $val = $CurrentForm->hasValue("is_active") ? $CurrentForm->getValue("is_active") : $CurrentForm->getValue("x_is_active");
        if (!$this->is_active->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_active->Visible = false; // Disable update for API request
            } else {
                $this->is_active->setFormValue($val);
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

        // Check field name 'created_by' first before field var 'x_created_by'
        $val = $CurrentForm->hasValue("created_by") ? $CurrentForm->getValue("created_by") : $CurrentForm->getValue("x_created_by");
        if (!$this->created_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->created_by->Visible = false; // Disable update for API request
            } else {
                $this->created_by->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'updated_at' first before field var 'x_updated_at'
        $val = $CurrentForm->hasValue("updated_at") ? $CurrentForm->getValue("updated_at") : $CurrentForm->getValue("x_updated_at");
        if (!$this->updated_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->updated_at->Visible = false; // Disable update for API request
            } else {
                $this->updated_at->setFormValue($val, true, $validate);
            }
            $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        }

        // Check field name 'updated_by' first before field var 'x_updated_by'
        $val = $CurrentForm->hasValue("updated_by") ? $CurrentForm->getValue("updated_by") : $CurrentForm->getValue("x_updated_by");
        if (!$this->updated_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->updated_by->Visible = false; // Disable update for API request
            } else {
                $this->updated_by->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'version' first before field var 'x_version'
        $val = $CurrentForm->hasValue("version") ? $CurrentForm->getValue("version") : $CurrentForm->getValue("x_version");
        if (!$this->version->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->version->Visible = false; // Disable update for API request
            } else {
                $this->version->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'notary_required' first before field var 'x_notary_required'
        $val = $CurrentForm->hasValue("notary_required") ? $CurrentForm->getValue("notary_required") : $CurrentForm->getValue("x_notary_required");
        if (!$this->notary_required->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notary_required->Visible = false; // Disable update for API request
            } else {
                $this->notary_required->setFormValue($val);
            }
        }

        // Check field name 'fee_amount' first before field var 'x_fee_amount'
        $val = $CurrentForm->hasValue("fee_amount") ? $CurrentForm->getValue("fee_amount") : $CurrentForm->getValue("x_fee_amount");
        if (!$this->fee_amount->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fee_amount->Visible = false; // Disable update for API request
            } else {
                $this->fee_amount->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'approval_workflow' first before field var 'x_approval_workflow'
        $val = $CurrentForm->hasValue("approval_workflow") ? $CurrentForm->getValue("approval_workflow") : $CurrentForm->getValue("x_approval_workflow");
        if (!$this->approval_workflow->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->approval_workflow->Visible = false; // Disable update for API request
            } else {
                $this->approval_workflow->setFormValue($val);
            }
        }

        // Check field name 'template_type' first before field var 'x_template_type'
        $val = $CurrentForm->hasValue("template_type") ? $CurrentForm->getValue("template_type") : $CurrentForm->getValue("x_template_type");
        if (!$this->template_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->template_type->Visible = false; // Disable update for API request
            } else {
                $this->template_type->setFormValue($val);
            }
        }

        // Check field name 'header_text' first before field var 'x_header_text'
        $val = $CurrentForm->hasValue("header_text") ? $CurrentForm->getValue("header_text") : $CurrentForm->getValue("x_header_text");
        if (!$this->header_text->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->header_text->Visible = false; // Disable update for API request
            } else {
                $this->header_text->setFormValue($val);
            }
        }

        // Check field name 'footer_text' first before field var 'x_footer_text'
        $val = $CurrentForm->hasValue("footer_text") ? $CurrentForm->getValue("footer_text") : $CurrentForm->getValue("x_footer_text");
        if (!$this->footer_text->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->footer_text->Visible = false; // Disable update for API request
            } else {
                $this->footer_text->setFormValue($val);
            }
        }

        // Check field name 'preview_image_path' first before field var 'x_preview_image_path'
        $val = $CurrentForm->hasValue("preview_image_path") ? $CurrentForm->getValue("preview_image_path") : $CurrentForm->getValue("x_preview_image_path");
        if (!$this->preview_image_path->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->preview_image_path->Visible = false; // Disable update for API request
            } else {
                $this->preview_image_path->setFormValue($val);
            }
        }

        // Check field name 'is_system' first before field var 'x_is_system'
        $val = $CurrentForm->hasValue("is_system") ? $CurrentForm->getValue("is_system") : $CurrentForm->getValue("x_is_system");
        if (!$this->is_system->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_system->Visible = false; // Disable update for API request
            } else {
                $this->is_system->setFormValue($val);
            }
        }

        // Check field name 'owner_id' first before field var 'x_owner_id'
        $val = $CurrentForm->hasValue("owner_id") ? $CurrentForm->getValue("owner_id") : $CurrentForm->getValue("x_owner_id");
        if (!$this->owner_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->owner_id->Visible = false; // Disable update for API request
            } else {
                $this->owner_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'original_template_id' first before field var 'x_original_template_id'
        $val = $CurrentForm->hasValue("original_template_id") ? $CurrentForm->getValue("original_template_id") : $CurrentForm->getValue("x_original_template_id");
        if (!$this->original_template_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->original_template_id->Visible = false; // Disable update for API request
            } else {
                $this->original_template_id->setFormValue($val, true, $validate);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->template_id->CurrentValue = $this->template_id->FormValue;
        $this->template_name->CurrentValue = $this->template_name->FormValue;
        $this->template_code->CurrentValue = $this->template_code->FormValue;
        $this->category_id->CurrentValue = $this->category_id->FormValue;
        $this->description->CurrentValue = $this->description->FormValue;
        $this->html_content->CurrentValue = $this->html_content->FormValue;
        $this->is_active->CurrentValue = $this->is_active->FormValue;
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->created_by->CurrentValue = $this->created_by->FormValue;
        $this->updated_at->CurrentValue = $this->updated_at->FormValue;
        $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->updated_by->CurrentValue = $this->updated_by->FormValue;
        $this->version->CurrentValue = $this->version->FormValue;
        $this->notary_required->CurrentValue = $this->notary_required->FormValue;
        $this->fee_amount->CurrentValue = $this->fee_amount->FormValue;
        $this->approval_workflow->CurrentValue = $this->approval_workflow->FormValue;
        $this->template_type->CurrentValue = $this->template_type->FormValue;
        $this->header_text->CurrentValue = $this->header_text->FormValue;
        $this->footer_text->CurrentValue = $this->footer_text->FormValue;
        $this->preview_image_path->CurrentValue = $this->preview_image_path->FormValue;
        $this->is_system->CurrentValue = $this->is_system->FormValue;
        $this->owner_id->CurrentValue = $this->owner_id->FormValue;
        $this->original_template_id->CurrentValue = $this->original_template_id->FormValue;
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
        $this->template_id->setDbValue($row['template_id']);
        $this->template_name->setDbValue($row['template_name']);
        $this->template_code->setDbValue($row['template_code']);
        $this->category_id->setDbValue($row['category_id']);
        $this->description->setDbValue($row['description']);
        $this->html_content->setDbValue($row['html_content']);
        $this->is_active->setDbValue((ConvertToBool($row['is_active']) ? "1" : "0"));
        $this->created_at->setDbValue($row['created_at']);
        $this->created_by->setDbValue($row['created_by']);
        $this->updated_at->setDbValue($row['updated_at']);
        $this->updated_by->setDbValue($row['updated_by']);
        $this->version->setDbValue($row['version']);
        $this->notary_required->setDbValue((ConvertToBool($row['notary_required']) ? "1" : "0"));
        $this->fee_amount->setDbValue($row['fee_amount']);
        $this->approval_workflow->setDbValue($row['approval_workflow']);
        $this->template_type->setDbValue($row['template_type']);
        $this->header_text->setDbValue($row['header_text']);
        $this->footer_text->setDbValue($row['footer_text']);
        $this->preview_image_path->setDbValue($row['preview_image_path']);
        $this->is_system->setDbValue((ConvertToBool($row['is_system']) ? "1" : "0"));
        $this->owner_id->setDbValue($row['owner_id']);
        $this->original_template_id->setDbValue($row['original_template_id']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['template_id'] = $this->template_id->DefaultValue;
        $row['template_name'] = $this->template_name->DefaultValue;
        $row['template_code'] = $this->template_code->DefaultValue;
        $row['category_id'] = $this->category_id->DefaultValue;
        $row['description'] = $this->description->DefaultValue;
        $row['html_content'] = $this->html_content->DefaultValue;
        $row['is_active'] = $this->is_active->DefaultValue;
        $row['created_at'] = $this->created_at->DefaultValue;
        $row['created_by'] = $this->created_by->DefaultValue;
        $row['updated_at'] = $this->updated_at->DefaultValue;
        $row['updated_by'] = $this->updated_by->DefaultValue;
        $row['version'] = $this->version->DefaultValue;
        $row['notary_required'] = $this->notary_required->DefaultValue;
        $row['fee_amount'] = $this->fee_amount->DefaultValue;
        $row['approval_workflow'] = $this->approval_workflow->DefaultValue;
        $row['template_type'] = $this->template_type->DefaultValue;
        $row['header_text'] = $this->header_text->DefaultValue;
        $row['footer_text'] = $this->footer_text->DefaultValue;
        $row['preview_image_path'] = $this->preview_image_path->DefaultValue;
        $row['is_system'] = $this->is_system->DefaultValue;
        $row['owner_id'] = $this->owner_id->DefaultValue;
        $row['original_template_id'] = $this->original_template_id->DefaultValue;
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

        // template_id
        $this->template_id->RowCssClass = "row";

        // template_name
        $this->template_name->RowCssClass = "row";

        // template_code
        $this->template_code->RowCssClass = "row";

        // category_id
        $this->category_id->RowCssClass = "row";

        // description
        $this->description->RowCssClass = "row";

        // html_content
        $this->html_content->RowCssClass = "row";

        // is_active
        $this->is_active->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // created_by
        $this->created_by->RowCssClass = "row";

        // updated_at
        $this->updated_at->RowCssClass = "row";

        // updated_by
        $this->updated_by->RowCssClass = "row";

        // version
        $this->version->RowCssClass = "row";

        // notary_required
        $this->notary_required->RowCssClass = "row";

        // fee_amount
        $this->fee_amount->RowCssClass = "row";

        // approval_workflow
        $this->approval_workflow->RowCssClass = "row";

        // template_type
        $this->template_type->RowCssClass = "row";

        // header_text
        $this->header_text->RowCssClass = "row";

        // footer_text
        $this->footer_text->RowCssClass = "row";

        // preview_image_path
        $this->preview_image_path->RowCssClass = "row";

        // is_system
        $this->is_system->RowCssClass = "row";

        // owner_id
        $this->owner_id->RowCssClass = "row";

        // original_template_id
        $this->original_template_id->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // template_id
            $this->template_id->ViewValue = $this->template_id->CurrentValue;

            // template_name
            $this->template_name->ViewValue = $this->template_name->CurrentValue;

            // template_code
            $this->template_code->ViewValue = $this->template_code->CurrentValue;

            // category_id
            $this->category_id->ViewValue = $this->category_id->CurrentValue;
            $this->category_id->ViewValue = FormatNumber($this->category_id->ViewValue, $this->category_id->formatPattern());

            // description
            $this->description->ViewValue = $this->description->CurrentValue;

            // html_content
            $this->html_content->ViewValue = $this->html_content->CurrentValue;

            // is_active
            if (ConvertToBool($this->is_active->CurrentValue)) {
                $this->is_active->ViewValue = $this->is_active->tagCaption(1) != "" ? $this->is_active->tagCaption(1) : "Yes";
            } else {
                $this->is_active->ViewValue = $this->is_active->tagCaption(2) != "" ? $this->is_active->tagCaption(2) : "No";
            }

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // created_by
            $this->created_by->ViewValue = $this->created_by->CurrentValue;
            $this->created_by->ViewValue = FormatNumber($this->created_by->ViewValue, $this->created_by->formatPattern());

            // updated_at
            $this->updated_at->ViewValue = $this->updated_at->CurrentValue;
            $this->updated_at->ViewValue = FormatDateTime($this->updated_at->ViewValue, $this->updated_at->formatPattern());

            // updated_by
            $this->updated_by->ViewValue = $this->updated_by->CurrentValue;
            $this->updated_by->ViewValue = FormatNumber($this->updated_by->ViewValue, $this->updated_by->formatPattern());

            // version
            $this->version->ViewValue = $this->version->CurrentValue;
            $this->version->ViewValue = FormatNumber($this->version->ViewValue, $this->version->formatPattern());

            // notary_required
            if (ConvertToBool($this->notary_required->CurrentValue)) {
                $this->notary_required->ViewValue = $this->notary_required->tagCaption(1) != "" ? $this->notary_required->tagCaption(1) : "Yes";
            } else {
                $this->notary_required->ViewValue = $this->notary_required->tagCaption(2) != "" ? $this->notary_required->tagCaption(2) : "No";
            }

            // fee_amount
            $this->fee_amount->ViewValue = $this->fee_amount->CurrentValue;
            $this->fee_amount->ViewValue = FormatNumber($this->fee_amount->ViewValue, $this->fee_amount->formatPattern());

            // approval_workflow
            $this->approval_workflow->ViewValue = $this->approval_workflow->CurrentValue;

            // template_type
            $this->template_type->ViewValue = $this->template_type->CurrentValue;

            // header_text
            $this->header_text->ViewValue = $this->header_text->CurrentValue;

            // footer_text
            $this->footer_text->ViewValue = $this->footer_text->CurrentValue;

            // preview_image_path
            $this->preview_image_path->ViewValue = $this->preview_image_path->CurrentValue;

            // is_system
            if (ConvertToBool($this->is_system->CurrentValue)) {
                $this->is_system->ViewValue = $this->is_system->tagCaption(1) != "" ? $this->is_system->tagCaption(1) : "Yes";
            } else {
                $this->is_system->ViewValue = $this->is_system->tagCaption(2) != "" ? $this->is_system->tagCaption(2) : "No";
            }

            // owner_id
            $this->owner_id->ViewValue = $this->owner_id->CurrentValue;
            $this->owner_id->ViewValue = FormatNumber($this->owner_id->ViewValue, $this->owner_id->formatPattern());

            // original_template_id
            $this->original_template_id->ViewValue = $this->original_template_id->CurrentValue;
            $this->original_template_id->ViewValue = FormatNumber($this->original_template_id->ViewValue, $this->original_template_id->formatPattern());

            // template_id
            $this->template_id->HrefValue = "";

            // template_name
            $this->template_name->HrefValue = "";

            // template_code
            $this->template_code->HrefValue = "";

            // category_id
            $this->category_id->HrefValue = "";

            // description
            $this->description->HrefValue = "";

            // html_content
            $this->html_content->HrefValue = "";

            // is_active
            $this->is_active->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // created_by
            $this->created_by->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // updated_by
            $this->updated_by->HrefValue = "";

            // version
            $this->version->HrefValue = "";

            // notary_required
            $this->notary_required->HrefValue = "";

            // fee_amount
            $this->fee_amount->HrefValue = "";

            // approval_workflow
            $this->approval_workflow->HrefValue = "";

            // template_type
            $this->template_type->HrefValue = "";

            // header_text
            $this->header_text->HrefValue = "";

            // footer_text
            $this->footer_text->HrefValue = "";

            // preview_image_path
            $this->preview_image_path->HrefValue = "";

            // is_system
            $this->is_system->HrefValue = "";

            // owner_id
            $this->owner_id->HrefValue = "";

            // original_template_id
            $this->original_template_id->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // template_id
            $this->template_id->setupEditAttributes();
            $this->template_id->EditValue = $this->template_id->CurrentValue;

            // template_name
            $this->template_name->setupEditAttributes();
            if (!$this->template_name->Raw) {
                $this->template_name->CurrentValue = HtmlDecode($this->template_name->CurrentValue);
            }
            $this->template_name->EditValue = HtmlEncode($this->template_name->CurrentValue);
            $this->template_name->PlaceHolder = RemoveHtml($this->template_name->caption());

            // template_code
            $this->template_code->setupEditAttributes();
            if (!$this->template_code->Raw) {
                $this->template_code->CurrentValue = HtmlDecode($this->template_code->CurrentValue);
            }
            $this->template_code->EditValue = HtmlEncode($this->template_code->CurrentValue);
            $this->template_code->PlaceHolder = RemoveHtml($this->template_code->caption());

            // category_id
            $this->category_id->setupEditAttributes();
            $this->category_id->EditValue = $this->category_id->CurrentValue;
            $this->category_id->PlaceHolder = RemoveHtml($this->category_id->caption());
            if (strval($this->category_id->EditValue) != "" && is_numeric($this->category_id->EditValue)) {
                $this->category_id->EditValue = FormatNumber($this->category_id->EditValue, null);
            }

            // description
            $this->description->setupEditAttributes();
            $this->description->EditValue = HtmlEncode($this->description->CurrentValue);
            $this->description->PlaceHolder = RemoveHtml($this->description->caption());

            // html_content
            $this->html_content->setupEditAttributes();
            $this->html_content->EditValue = HtmlEncode($this->html_content->CurrentValue);
            $this->html_content->PlaceHolder = RemoveHtml($this->html_content->caption());

            // is_active
            $this->is_active->EditValue = $this->is_active->options(false);
            $this->is_active->PlaceHolder = RemoveHtml($this->is_active->caption());

            // created_at
            $this->created_at->setupEditAttributes();
            $this->created_at->EditValue = HtmlEncode(FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()));
            $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

            // created_by
            $this->created_by->setupEditAttributes();
            $this->created_by->EditValue = $this->created_by->CurrentValue;
            $this->created_by->PlaceHolder = RemoveHtml($this->created_by->caption());
            if (strval($this->created_by->EditValue) != "" && is_numeric($this->created_by->EditValue)) {
                $this->created_by->EditValue = FormatNumber($this->created_by->EditValue, null);
            }

            // updated_at
            $this->updated_at->setupEditAttributes();
            $this->updated_at->EditValue = HtmlEncode(FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()));
            $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

            // updated_by
            $this->updated_by->setupEditAttributes();
            $this->updated_by->EditValue = $this->updated_by->CurrentValue;
            $this->updated_by->PlaceHolder = RemoveHtml($this->updated_by->caption());
            if (strval($this->updated_by->EditValue) != "" && is_numeric($this->updated_by->EditValue)) {
                $this->updated_by->EditValue = FormatNumber($this->updated_by->EditValue, null);
            }

            // version
            $this->version->setupEditAttributes();
            $this->version->EditValue = $this->version->CurrentValue;
            $this->version->PlaceHolder = RemoveHtml($this->version->caption());
            if (strval($this->version->EditValue) != "" && is_numeric($this->version->EditValue)) {
                $this->version->EditValue = FormatNumber($this->version->EditValue, null);
            }

            // notary_required
            $this->notary_required->EditValue = $this->notary_required->options(false);
            $this->notary_required->PlaceHolder = RemoveHtml($this->notary_required->caption());

            // fee_amount
            $this->fee_amount->setupEditAttributes();
            $this->fee_amount->EditValue = $this->fee_amount->CurrentValue;
            $this->fee_amount->PlaceHolder = RemoveHtml($this->fee_amount->caption());
            if (strval($this->fee_amount->EditValue) != "" && is_numeric($this->fee_amount->EditValue)) {
                $this->fee_amount->EditValue = FormatNumber($this->fee_amount->EditValue, null);
            }

            // approval_workflow
            $this->approval_workflow->setupEditAttributes();
            $this->approval_workflow->EditValue = HtmlEncode($this->approval_workflow->CurrentValue);
            $this->approval_workflow->PlaceHolder = RemoveHtml($this->approval_workflow->caption());

            // template_type
            $this->template_type->setupEditAttributes();
            if (!$this->template_type->Raw) {
                $this->template_type->CurrentValue = HtmlDecode($this->template_type->CurrentValue);
            }
            $this->template_type->EditValue = HtmlEncode($this->template_type->CurrentValue);
            $this->template_type->PlaceHolder = RemoveHtml($this->template_type->caption());

            // header_text
            $this->header_text->setupEditAttributes();
            $this->header_text->EditValue = HtmlEncode($this->header_text->CurrentValue);
            $this->header_text->PlaceHolder = RemoveHtml($this->header_text->caption());

            // footer_text
            $this->footer_text->setupEditAttributes();
            $this->footer_text->EditValue = HtmlEncode($this->footer_text->CurrentValue);
            $this->footer_text->PlaceHolder = RemoveHtml($this->footer_text->caption());

            // preview_image_path
            $this->preview_image_path->setupEditAttributes();
            if (!$this->preview_image_path->Raw) {
                $this->preview_image_path->CurrentValue = HtmlDecode($this->preview_image_path->CurrentValue);
            }
            $this->preview_image_path->EditValue = HtmlEncode($this->preview_image_path->CurrentValue);
            $this->preview_image_path->PlaceHolder = RemoveHtml($this->preview_image_path->caption());

            // is_system
            $this->is_system->EditValue = $this->is_system->options(false);
            $this->is_system->PlaceHolder = RemoveHtml($this->is_system->caption());

            // owner_id
            $this->owner_id->setupEditAttributes();
            $this->owner_id->EditValue = $this->owner_id->CurrentValue;
            $this->owner_id->PlaceHolder = RemoveHtml($this->owner_id->caption());
            if (strval($this->owner_id->EditValue) != "" && is_numeric($this->owner_id->EditValue)) {
                $this->owner_id->EditValue = FormatNumber($this->owner_id->EditValue, null);
            }

            // original_template_id
            $this->original_template_id->setupEditAttributes();
            $this->original_template_id->EditValue = $this->original_template_id->CurrentValue;
            $this->original_template_id->PlaceHolder = RemoveHtml($this->original_template_id->caption());
            if (strval($this->original_template_id->EditValue) != "" && is_numeric($this->original_template_id->EditValue)) {
                $this->original_template_id->EditValue = FormatNumber($this->original_template_id->EditValue, null);
            }

            // Edit refer script

            // template_id
            $this->template_id->HrefValue = "";

            // template_name
            $this->template_name->HrefValue = "";

            // template_code
            $this->template_code->HrefValue = "";

            // category_id
            $this->category_id->HrefValue = "";

            // description
            $this->description->HrefValue = "";

            // html_content
            $this->html_content->HrefValue = "";

            // is_active
            $this->is_active->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // created_by
            $this->created_by->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // updated_by
            $this->updated_by->HrefValue = "";

            // version
            $this->version->HrefValue = "";

            // notary_required
            $this->notary_required->HrefValue = "";

            // fee_amount
            $this->fee_amount->HrefValue = "";

            // approval_workflow
            $this->approval_workflow->HrefValue = "";

            // template_type
            $this->template_type->HrefValue = "";

            // header_text
            $this->header_text->HrefValue = "";

            // footer_text
            $this->footer_text->HrefValue = "";

            // preview_image_path
            $this->preview_image_path->HrefValue = "";

            // is_system
            $this->is_system->HrefValue = "";

            // owner_id
            $this->owner_id->HrefValue = "";

            // original_template_id
            $this->original_template_id->HrefValue = "";
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
            if ($this->template_name->Visible && $this->template_name->Required) {
                if (!$this->template_name->IsDetailKey && EmptyValue($this->template_name->FormValue)) {
                    $this->template_name->addErrorMessage(str_replace("%s", $this->template_name->caption(), $this->template_name->RequiredErrorMessage));
                }
            }
            if ($this->template_code->Visible && $this->template_code->Required) {
                if (!$this->template_code->IsDetailKey && EmptyValue($this->template_code->FormValue)) {
                    $this->template_code->addErrorMessage(str_replace("%s", $this->template_code->caption(), $this->template_code->RequiredErrorMessage));
                }
            }
            if ($this->category_id->Visible && $this->category_id->Required) {
                if (!$this->category_id->IsDetailKey && EmptyValue($this->category_id->FormValue)) {
                    $this->category_id->addErrorMessage(str_replace("%s", $this->category_id->caption(), $this->category_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->category_id->FormValue)) {
                $this->category_id->addErrorMessage($this->category_id->getErrorMessage(false));
            }
            if ($this->description->Visible && $this->description->Required) {
                if (!$this->description->IsDetailKey && EmptyValue($this->description->FormValue)) {
                    $this->description->addErrorMessage(str_replace("%s", $this->description->caption(), $this->description->RequiredErrorMessage));
                }
            }
            if ($this->html_content->Visible && $this->html_content->Required) {
                if (!$this->html_content->IsDetailKey && EmptyValue($this->html_content->FormValue)) {
                    $this->html_content->addErrorMessage(str_replace("%s", $this->html_content->caption(), $this->html_content->RequiredErrorMessage));
                }
            }
            if ($this->is_active->Visible && $this->is_active->Required) {
                if ($this->is_active->FormValue == "") {
                    $this->is_active->addErrorMessage(str_replace("%s", $this->is_active->caption(), $this->is_active->RequiredErrorMessage));
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
            if ($this->created_by->Visible && $this->created_by->Required) {
                if (!$this->created_by->IsDetailKey && EmptyValue($this->created_by->FormValue)) {
                    $this->created_by->addErrorMessage(str_replace("%s", $this->created_by->caption(), $this->created_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->created_by->FormValue)) {
                $this->created_by->addErrorMessage($this->created_by->getErrorMessage(false));
            }
            if ($this->updated_at->Visible && $this->updated_at->Required) {
                if (!$this->updated_at->IsDetailKey && EmptyValue($this->updated_at->FormValue)) {
                    $this->updated_at->addErrorMessage(str_replace("%s", $this->updated_at->caption(), $this->updated_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->updated_at->FormValue, $this->updated_at->formatPattern())) {
                $this->updated_at->addErrorMessage($this->updated_at->getErrorMessage(false));
            }
            if ($this->updated_by->Visible && $this->updated_by->Required) {
                if (!$this->updated_by->IsDetailKey && EmptyValue($this->updated_by->FormValue)) {
                    $this->updated_by->addErrorMessage(str_replace("%s", $this->updated_by->caption(), $this->updated_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->updated_by->FormValue)) {
                $this->updated_by->addErrorMessage($this->updated_by->getErrorMessage(false));
            }
            if ($this->version->Visible && $this->version->Required) {
                if (!$this->version->IsDetailKey && EmptyValue($this->version->FormValue)) {
                    $this->version->addErrorMessage(str_replace("%s", $this->version->caption(), $this->version->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->version->FormValue)) {
                $this->version->addErrorMessage($this->version->getErrorMessage(false));
            }
            if ($this->notary_required->Visible && $this->notary_required->Required) {
                if ($this->notary_required->FormValue == "") {
                    $this->notary_required->addErrorMessage(str_replace("%s", $this->notary_required->caption(), $this->notary_required->RequiredErrorMessage));
                }
            }
            if ($this->fee_amount->Visible && $this->fee_amount->Required) {
                if (!$this->fee_amount->IsDetailKey && EmptyValue($this->fee_amount->FormValue)) {
                    $this->fee_amount->addErrorMessage(str_replace("%s", $this->fee_amount->caption(), $this->fee_amount->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->fee_amount->FormValue)) {
                $this->fee_amount->addErrorMessage($this->fee_amount->getErrorMessage(false));
            }
            if ($this->approval_workflow->Visible && $this->approval_workflow->Required) {
                if (!$this->approval_workflow->IsDetailKey && EmptyValue($this->approval_workflow->FormValue)) {
                    $this->approval_workflow->addErrorMessage(str_replace("%s", $this->approval_workflow->caption(), $this->approval_workflow->RequiredErrorMessage));
                }
            }
            if ($this->template_type->Visible && $this->template_type->Required) {
                if (!$this->template_type->IsDetailKey && EmptyValue($this->template_type->FormValue)) {
                    $this->template_type->addErrorMessage(str_replace("%s", $this->template_type->caption(), $this->template_type->RequiredErrorMessage));
                }
            }
            if ($this->header_text->Visible && $this->header_text->Required) {
                if (!$this->header_text->IsDetailKey && EmptyValue($this->header_text->FormValue)) {
                    $this->header_text->addErrorMessage(str_replace("%s", $this->header_text->caption(), $this->header_text->RequiredErrorMessage));
                }
            }
            if ($this->footer_text->Visible && $this->footer_text->Required) {
                if (!$this->footer_text->IsDetailKey && EmptyValue($this->footer_text->FormValue)) {
                    $this->footer_text->addErrorMessage(str_replace("%s", $this->footer_text->caption(), $this->footer_text->RequiredErrorMessage));
                }
            }
            if ($this->preview_image_path->Visible && $this->preview_image_path->Required) {
                if (!$this->preview_image_path->IsDetailKey && EmptyValue($this->preview_image_path->FormValue)) {
                    $this->preview_image_path->addErrorMessage(str_replace("%s", $this->preview_image_path->caption(), $this->preview_image_path->RequiredErrorMessage));
                }
            }
            if ($this->is_system->Visible && $this->is_system->Required) {
                if ($this->is_system->FormValue == "") {
                    $this->is_system->addErrorMessage(str_replace("%s", $this->is_system->caption(), $this->is_system->RequiredErrorMessage));
                }
            }
            if ($this->owner_id->Visible && $this->owner_id->Required) {
                if (!$this->owner_id->IsDetailKey && EmptyValue($this->owner_id->FormValue)) {
                    $this->owner_id->addErrorMessage(str_replace("%s", $this->owner_id->caption(), $this->owner_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->owner_id->FormValue)) {
                $this->owner_id->addErrorMessage($this->owner_id->getErrorMessage(false));
            }
            if ($this->original_template_id->Visible && $this->original_template_id->Required) {
                if (!$this->original_template_id->IsDetailKey && EmptyValue($this->original_template_id->FormValue)) {
                    $this->original_template_id->addErrorMessage(str_replace("%s", $this->original_template_id->caption(), $this->original_template_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->original_template_id->FormValue)) {
                $this->original_template_id->addErrorMessage($this->original_template_id->getErrorMessage(false));
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

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Load old values
            $this->loadDbValues($rsold);
        }

        // Get new row
        $rsnew = $this->getEditRow($rsold);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check field with unique index (template_code)
        if ($this->template_code->CurrentValue != "") {
            $filterChk = "(\"template_code\" = '" . AdjustSql($this->template_code->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->template_code->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->template_code->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    /**
     * Get edit row
     *
     * @return array
     */
    protected function getEditRow($rsold)
    {
        global $Security;
        $rsnew = [];

        // template_name
        $this->template_name->setDbValueDef($rsnew, $this->template_name->CurrentValue, $this->template_name->ReadOnly);

        // template_code
        $this->template_code->setDbValueDef($rsnew, $this->template_code->CurrentValue, $this->template_code->ReadOnly);

        // category_id
        $this->category_id->setDbValueDef($rsnew, $this->category_id->CurrentValue, $this->category_id->ReadOnly);

        // description
        $this->description->setDbValueDef($rsnew, $this->description->CurrentValue, $this->description->ReadOnly);

        // html_content
        $this->html_content->setDbValueDef($rsnew, $this->html_content->CurrentValue, $this->html_content->ReadOnly);

        // is_active
        $tmpBool = $this->is_active->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_active->setDbValueDef($rsnew, $tmpBool, $this->is_active->ReadOnly);

        // created_at
        $this->created_at->setDbValueDef($rsnew, UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()), $this->created_at->ReadOnly);

        // created_by
        $this->created_by->setDbValueDef($rsnew, $this->created_by->CurrentValue, $this->created_by->ReadOnly);

        // updated_at
        $this->updated_at->setDbValueDef($rsnew, UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()), $this->updated_at->ReadOnly);

        // updated_by
        $this->updated_by->setDbValueDef($rsnew, $this->updated_by->CurrentValue, $this->updated_by->ReadOnly);

        // version
        $this->version->setDbValueDef($rsnew, $this->version->CurrentValue, $this->version->ReadOnly);

        // notary_required
        $tmpBool = $this->notary_required->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->notary_required->setDbValueDef($rsnew, $tmpBool, $this->notary_required->ReadOnly);

        // fee_amount
        $this->fee_amount->setDbValueDef($rsnew, $this->fee_amount->CurrentValue, $this->fee_amount->ReadOnly);

        // approval_workflow
        $this->approval_workflow->setDbValueDef($rsnew, $this->approval_workflow->CurrentValue, $this->approval_workflow->ReadOnly);

        // template_type
        $this->template_type->setDbValueDef($rsnew, $this->template_type->CurrentValue, $this->template_type->ReadOnly);

        // header_text
        $this->header_text->setDbValueDef($rsnew, $this->header_text->CurrentValue, $this->header_text->ReadOnly);

        // footer_text
        $this->footer_text->setDbValueDef($rsnew, $this->footer_text->CurrentValue, $this->footer_text->ReadOnly);

        // preview_image_path
        $this->preview_image_path->setDbValueDef($rsnew, $this->preview_image_path->CurrentValue, $this->preview_image_path->ReadOnly);

        // is_system
        $tmpBool = $this->is_system->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_system->setDbValueDef($rsnew, $tmpBool, $this->is_system->ReadOnly);

        // owner_id
        $this->owner_id->setDbValueDef($rsnew, $this->owner_id->CurrentValue, $this->owner_id->ReadOnly);

        // original_template_id
        $this->original_template_id->setDbValueDef($rsnew, $this->original_template_id->CurrentValue, $this->original_template_id->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['template_name'])) { // template_name
            $this->template_name->CurrentValue = $row['template_name'];
        }
        if (isset($row['template_code'])) { // template_code
            $this->template_code->CurrentValue = $row['template_code'];
        }
        if (isset($row['category_id'])) { // category_id
            $this->category_id->CurrentValue = $row['category_id'];
        }
        if (isset($row['description'])) { // description
            $this->description->CurrentValue = $row['description'];
        }
        if (isset($row['html_content'])) { // html_content
            $this->html_content->CurrentValue = $row['html_content'];
        }
        if (isset($row['is_active'])) { // is_active
            $this->is_active->CurrentValue = $row['is_active'];
        }
        if (isset($row['created_at'])) { // created_at
            $this->created_at->CurrentValue = $row['created_at'];
        }
        if (isset($row['created_by'])) { // created_by
            $this->created_by->CurrentValue = $row['created_by'];
        }
        if (isset($row['updated_at'])) { // updated_at
            $this->updated_at->CurrentValue = $row['updated_at'];
        }
        if (isset($row['updated_by'])) { // updated_by
            $this->updated_by->CurrentValue = $row['updated_by'];
        }
        if (isset($row['version'])) { // version
            $this->version->CurrentValue = $row['version'];
        }
        if (isset($row['notary_required'])) { // notary_required
            $this->notary_required->CurrentValue = $row['notary_required'];
        }
        if (isset($row['fee_amount'])) { // fee_amount
            $this->fee_amount->CurrentValue = $row['fee_amount'];
        }
        if (isset($row['approval_workflow'])) { // approval_workflow
            $this->approval_workflow->CurrentValue = $row['approval_workflow'];
        }
        if (isset($row['template_type'])) { // template_type
            $this->template_type->CurrentValue = $row['template_type'];
        }
        if (isset($row['header_text'])) { // header_text
            $this->header_text->CurrentValue = $row['header_text'];
        }
        if (isset($row['footer_text'])) { // footer_text
            $this->footer_text->CurrentValue = $row['footer_text'];
        }
        if (isset($row['preview_image_path'])) { // preview_image_path
            $this->preview_image_path->CurrentValue = $row['preview_image_path'];
        }
        if (isset($row['is_system'])) { // is_system
            $this->is_system->CurrentValue = $row['is_system'];
        }
        if (isset($row['owner_id'])) { // owner_id
            $this->owner_id->CurrentValue = $row['owner_id'];
        }
        if (isset($row['original_template_id'])) { // original_template_id
            $this->original_template_id->CurrentValue = $row['original_template_id'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("DocumentTemplatesList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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
                case "x_is_active":
                    break;
                case "x_notary_required":
                    break;
                case "x_is_system":
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount() {
        return ceil($this->TotalRecords / $this->DisplayRecords);
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
