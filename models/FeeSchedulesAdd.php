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
class FeeSchedulesAdd extends FeeSchedules
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "FeeSchedulesAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "FeeSchedulesAdd";

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
        $this->fee_id->Visible = false;
        $this->template_id->setVisibility();
        $this->fee_name->setVisibility();
        $this->fee_amount->setVisibility();
        $this->fee_type->setVisibility();
        $this->currency->setVisibility();
        $this->effective_from->setVisibility();
        $this->effective_to->setVisibility();
        $this->created_at->setVisibility();
        $this->created_by->setVisibility();
        $this->updated_at->setVisibility();
        $this->updated_by->setVisibility();
        $this->is_active->setVisibility();
        $this->description->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'fee_schedules';
        $this->TableName = 'fee_schedules';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (fee_schedules)
        if (!isset($GLOBALS["fee_schedules"]) || $GLOBALS["fee_schedules"]::class == PROJECT_NAMESPACE . "fee_schedules") {
            $GLOBALS["fee_schedules"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'fee_schedules');
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
                        $result["view"] = SameString($pageName, "FeeSchedulesView"); // If View page, no primary button
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
            $key .= @$ar['fee_id'];
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
            $this->fee_id->Visible = false;
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
        $this->setupLookupOptions($this->is_active);

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
            if (($keyValue = Get("fee_id") ?? Route("fee_id")) !== null) {
                $this->fee_id->setQueryStringValue($keyValue);
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
                    $this->terminate("FeeSchedulesList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "FeeSchedulesList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "FeeSchedulesView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "FeeSchedulesList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "FeeSchedulesList"; // Return list page content
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
        $this->fee_type->DefaultValue = $this->fee_type->getDefault(); // PHP
        $this->fee_type->OldValue = $this->fee_type->DefaultValue;
        $this->currency->DefaultValue = $this->currency->getDefault(); // PHP
        $this->currency->OldValue = $this->currency->DefaultValue;
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

        // Check field name 'fee_name' first before field var 'x_fee_name'
        $val = $CurrentForm->hasValue("fee_name") ? $CurrentForm->getValue("fee_name") : $CurrentForm->getValue("x_fee_name");
        if (!$this->fee_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fee_name->Visible = false; // Disable update for API request
            } else {
                $this->fee_name->setFormValue($val);
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

        // Check field name 'fee_type' first before field var 'x_fee_type'
        $val = $CurrentForm->hasValue("fee_type") ? $CurrentForm->getValue("fee_type") : $CurrentForm->getValue("x_fee_type");
        if (!$this->fee_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fee_type->Visible = false; // Disable update for API request
            } else {
                $this->fee_type->setFormValue($val);
            }
        }

        // Check field name 'currency' first before field var 'x_currency'
        $val = $CurrentForm->hasValue("currency") ? $CurrentForm->getValue("currency") : $CurrentForm->getValue("x_currency");
        if (!$this->currency->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->currency->Visible = false; // Disable update for API request
            } else {
                $this->currency->setFormValue($val);
            }
        }

        // Check field name 'effective_from' first before field var 'x_effective_from'
        $val = $CurrentForm->hasValue("effective_from") ? $CurrentForm->getValue("effective_from") : $CurrentForm->getValue("x_effective_from");
        if (!$this->effective_from->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->effective_from->Visible = false; // Disable update for API request
            } else {
                $this->effective_from->setFormValue($val, true, $validate);
            }
            $this->effective_from->CurrentValue = UnFormatDateTime($this->effective_from->CurrentValue, $this->effective_from->formatPattern());
        }

        // Check field name 'effective_to' first before field var 'x_effective_to'
        $val = $CurrentForm->hasValue("effective_to") ? $CurrentForm->getValue("effective_to") : $CurrentForm->getValue("x_effective_to");
        if (!$this->effective_to->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->effective_to->Visible = false; // Disable update for API request
            } else {
                $this->effective_to->setFormValue($val, true, $validate);
            }
            $this->effective_to->CurrentValue = UnFormatDateTime($this->effective_to->CurrentValue, $this->effective_to->formatPattern());
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

        // Check field name 'is_active' first before field var 'x_is_active'
        $val = $CurrentForm->hasValue("is_active") ? $CurrentForm->getValue("is_active") : $CurrentForm->getValue("x_is_active");
        if (!$this->is_active->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_active->Visible = false; // Disable update for API request
            } else {
                $this->is_active->setFormValue($val);
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

        // Check field name 'fee_id' first before field var 'x_fee_id'
        $val = $CurrentForm->hasValue("fee_id") ? $CurrentForm->getValue("fee_id") : $CurrentForm->getValue("x_fee_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->template_id->CurrentValue = $this->template_id->FormValue;
        $this->fee_name->CurrentValue = $this->fee_name->FormValue;
        $this->fee_amount->CurrentValue = $this->fee_amount->FormValue;
        $this->fee_type->CurrentValue = $this->fee_type->FormValue;
        $this->currency->CurrentValue = $this->currency->FormValue;
        $this->effective_from->CurrentValue = $this->effective_from->FormValue;
        $this->effective_from->CurrentValue = UnFormatDateTime($this->effective_from->CurrentValue, $this->effective_from->formatPattern());
        $this->effective_to->CurrentValue = $this->effective_to->FormValue;
        $this->effective_to->CurrentValue = UnFormatDateTime($this->effective_to->CurrentValue, $this->effective_to->formatPattern());
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->created_by->CurrentValue = $this->created_by->FormValue;
        $this->updated_at->CurrentValue = $this->updated_at->FormValue;
        $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->updated_by->CurrentValue = $this->updated_by->FormValue;
        $this->is_active->CurrentValue = $this->is_active->FormValue;
        $this->description->CurrentValue = $this->description->FormValue;
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
        $this->fee_id->setDbValue($row['fee_id']);
        $this->template_id->setDbValue($row['template_id']);
        $this->fee_name->setDbValue($row['fee_name']);
        $this->fee_amount->setDbValue($row['fee_amount']);
        $this->fee_type->setDbValue($row['fee_type']);
        $this->currency->setDbValue($row['currency']);
        $this->effective_from->setDbValue($row['effective_from']);
        $this->effective_to->setDbValue($row['effective_to']);
        $this->created_at->setDbValue($row['created_at']);
        $this->created_by->setDbValue($row['created_by']);
        $this->updated_at->setDbValue($row['updated_at']);
        $this->updated_by->setDbValue($row['updated_by']);
        $this->is_active->setDbValue((ConvertToBool($row['is_active']) ? "1" : "0"));
        $this->description->setDbValue($row['description']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['fee_id'] = $this->fee_id->DefaultValue;
        $row['template_id'] = $this->template_id->DefaultValue;
        $row['fee_name'] = $this->fee_name->DefaultValue;
        $row['fee_amount'] = $this->fee_amount->DefaultValue;
        $row['fee_type'] = $this->fee_type->DefaultValue;
        $row['currency'] = $this->currency->DefaultValue;
        $row['effective_from'] = $this->effective_from->DefaultValue;
        $row['effective_to'] = $this->effective_to->DefaultValue;
        $row['created_at'] = $this->created_at->DefaultValue;
        $row['created_by'] = $this->created_by->DefaultValue;
        $row['updated_at'] = $this->updated_at->DefaultValue;
        $row['updated_by'] = $this->updated_by->DefaultValue;
        $row['is_active'] = $this->is_active->DefaultValue;
        $row['description'] = $this->description->DefaultValue;
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

        // fee_id
        $this->fee_id->RowCssClass = "row";

        // template_id
        $this->template_id->RowCssClass = "row";

        // fee_name
        $this->fee_name->RowCssClass = "row";

        // fee_amount
        $this->fee_amount->RowCssClass = "row";

        // fee_type
        $this->fee_type->RowCssClass = "row";

        // currency
        $this->currency->RowCssClass = "row";

        // effective_from
        $this->effective_from->RowCssClass = "row";

        // effective_to
        $this->effective_to->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // created_by
        $this->created_by->RowCssClass = "row";

        // updated_at
        $this->updated_at->RowCssClass = "row";

        // updated_by
        $this->updated_by->RowCssClass = "row";

        // is_active
        $this->is_active->RowCssClass = "row";

        // description
        $this->description->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // fee_id
            $this->fee_id->ViewValue = $this->fee_id->CurrentValue;

            // template_id
            $this->template_id->ViewValue = $this->template_id->CurrentValue;
            $this->template_id->ViewValue = FormatNumber($this->template_id->ViewValue, $this->template_id->formatPattern());

            // fee_name
            $this->fee_name->ViewValue = $this->fee_name->CurrentValue;

            // fee_amount
            $this->fee_amount->ViewValue = $this->fee_amount->CurrentValue;
            $this->fee_amount->ViewValue = FormatNumber($this->fee_amount->ViewValue, $this->fee_amount->formatPattern());

            // fee_type
            $this->fee_type->ViewValue = $this->fee_type->CurrentValue;

            // currency
            $this->currency->ViewValue = $this->currency->CurrentValue;

            // effective_from
            $this->effective_from->ViewValue = $this->effective_from->CurrentValue;
            $this->effective_from->ViewValue = FormatDateTime($this->effective_from->ViewValue, $this->effective_from->formatPattern());

            // effective_to
            $this->effective_to->ViewValue = $this->effective_to->CurrentValue;
            $this->effective_to->ViewValue = FormatDateTime($this->effective_to->ViewValue, $this->effective_to->formatPattern());

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

            // is_active
            if (ConvertToBool($this->is_active->CurrentValue)) {
                $this->is_active->ViewValue = $this->is_active->tagCaption(1) != "" ? $this->is_active->tagCaption(1) : "Yes";
            } else {
                $this->is_active->ViewValue = $this->is_active->tagCaption(2) != "" ? $this->is_active->tagCaption(2) : "No";
            }

            // description
            $this->description->ViewValue = $this->description->CurrentValue;

            // template_id
            $this->template_id->HrefValue = "";

            // fee_name
            $this->fee_name->HrefValue = "";

            // fee_amount
            $this->fee_amount->HrefValue = "";

            // fee_type
            $this->fee_type->HrefValue = "";

            // currency
            $this->currency->HrefValue = "";

            // effective_from
            $this->effective_from->HrefValue = "";

            // effective_to
            $this->effective_to->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // created_by
            $this->created_by->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // updated_by
            $this->updated_by->HrefValue = "";

            // is_active
            $this->is_active->HrefValue = "";

            // description
            $this->description->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // template_id
            $this->template_id->setupEditAttributes();
            $this->template_id->EditValue = $this->template_id->CurrentValue;
            $this->template_id->PlaceHolder = RemoveHtml($this->template_id->caption());
            if (strval($this->template_id->EditValue) != "" && is_numeric($this->template_id->EditValue)) {
                $this->template_id->EditValue = FormatNumber($this->template_id->EditValue, null);
            }

            // fee_name
            $this->fee_name->setupEditAttributes();
            if (!$this->fee_name->Raw) {
                $this->fee_name->CurrentValue = HtmlDecode($this->fee_name->CurrentValue);
            }
            $this->fee_name->EditValue = HtmlEncode($this->fee_name->CurrentValue);
            $this->fee_name->PlaceHolder = RemoveHtml($this->fee_name->caption());

            // fee_amount
            $this->fee_amount->setupEditAttributes();
            $this->fee_amount->EditValue = $this->fee_amount->CurrentValue;
            $this->fee_amount->PlaceHolder = RemoveHtml($this->fee_amount->caption());
            if (strval($this->fee_amount->EditValue) != "" && is_numeric($this->fee_amount->EditValue)) {
                $this->fee_amount->EditValue = FormatNumber($this->fee_amount->EditValue, null);
            }

            // fee_type
            $this->fee_type->setupEditAttributes();
            if (!$this->fee_type->Raw) {
                $this->fee_type->CurrentValue = HtmlDecode($this->fee_type->CurrentValue);
            }
            $this->fee_type->EditValue = HtmlEncode($this->fee_type->CurrentValue);
            $this->fee_type->PlaceHolder = RemoveHtml($this->fee_type->caption());

            // currency
            $this->currency->setupEditAttributes();
            if (!$this->currency->Raw) {
                $this->currency->CurrentValue = HtmlDecode($this->currency->CurrentValue);
            }
            $this->currency->EditValue = HtmlEncode($this->currency->CurrentValue);
            $this->currency->PlaceHolder = RemoveHtml($this->currency->caption());

            // effective_from
            $this->effective_from->setupEditAttributes();
            $this->effective_from->EditValue = HtmlEncode(FormatDateTime($this->effective_from->CurrentValue, $this->effective_from->formatPattern()));
            $this->effective_from->PlaceHolder = RemoveHtml($this->effective_from->caption());

            // effective_to
            $this->effective_to->setupEditAttributes();
            $this->effective_to->EditValue = HtmlEncode(FormatDateTime($this->effective_to->CurrentValue, $this->effective_to->formatPattern()));
            $this->effective_to->PlaceHolder = RemoveHtml($this->effective_to->caption());

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

            // is_active
            $this->is_active->EditValue = $this->is_active->options(false);
            $this->is_active->PlaceHolder = RemoveHtml($this->is_active->caption());

            // description
            $this->description->setupEditAttributes();
            $this->description->EditValue = HtmlEncode($this->description->CurrentValue);
            $this->description->PlaceHolder = RemoveHtml($this->description->caption());

            // Add refer script

            // template_id
            $this->template_id->HrefValue = "";

            // fee_name
            $this->fee_name->HrefValue = "";

            // fee_amount
            $this->fee_amount->HrefValue = "";

            // fee_type
            $this->fee_type->HrefValue = "";

            // currency
            $this->currency->HrefValue = "";

            // effective_from
            $this->effective_from->HrefValue = "";

            // effective_to
            $this->effective_to->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // created_by
            $this->created_by->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // updated_by
            $this->updated_by->HrefValue = "";

            // is_active
            $this->is_active->HrefValue = "";

            // description
            $this->description->HrefValue = "";
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
            if ($this->fee_name->Visible && $this->fee_name->Required) {
                if (!$this->fee_name->IsDetailKey && EmptyValue($this->fee_name->FormValue)) {
                    $this->fee_name->addErrorMessage(str_replace("%s", $this->fee_name->caption(), $this->fee_name->RequiredErrorMessage));
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
            if ($this->fee_type->Visible && $this->fee_type->Required) {
                if (!$this->fee_type->IsDetailKey && EmptyValue($this->fee_type->FormValue)) {
                    $this->fee_type->addErrorMessage(str_replace("%s", $this->fee_type->caption(), $this->fee_type->RequiredErrorMessage));
                }
            }
            if ($this->currency->Visible && $this->currency->Required) {
                if (!$this->currency->IsDetailKey && EmptyValue($this->currency->FormValue)) {
                    $this->currency->addErrorMessage(str_replace("%s", $this->currency->caption(), $this->currency->RequiredErrorMessage));
                }
            }
            if ($this->effective_from->Visible && $this->effective_from->Required) {
                if (!$this->effective_from->IsDetailKey && EmptyValue($this->effective_from->FormValue)) {
                    $this->effective_from->addErrorMessage(str_replace("%s", $this->effective_from->caption(), $this->effective_from->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->effective_from->FormValue, $this->effective_from->formatPattern())) {
                $this->effective_from->addErrorMessage($this->effective_from->getErrorMessage(false));
            }
            if ($this->effective_to->Visible && $this->effective_to->Required) {
                if (!$this->effective_to->IsDetailKey && EmptyValue($this->effective_to->FormValue)) {
                    $this->effective_to->addErrorMessage(str_replace("%s", $this->effective_to->caption(), $this->effective_to->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->effective_to->FormValue, $this->effective_to->formatPattern())) {
                $this->effective_to->addErrorMessage($this->effective_to->getErrorMessage(false));
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
            if ($this->is_active->Visible && $this->is_active->Required) {
                if ($this->is_active->FormValue == "") {
                    $this->is_active->addErrorMessage(str_replace("%s", $this->is_active->caption(), $this->is_active->RequiredErrorMessage));
                }
            }
            if ($this->description->Visible && $this->description->Required) {
                if (!$this->description->IsDetailKey && EmptyValue($this->description->FormValue)) {
                    $this->description->addErrorMessage(str_replace("%s", $this->description->caption(), $this->description->RequiredErrorMessage));
                }
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

        // fee_name
        $this->fee_name->setDbValueDef($rsnew, $this->fee_name->CurrentValue, false);

        // fee_amount
        $this->fee_amount->setDbValueDef($rsnew, $this->fee_amount->CurrentValue, false);

        // fee_type
        $this->fee_type->setDbValueDef($rsnew, $this->fee_type->CurrentValue, strval($this->fee_type->CurrentValue) == "");

        // currency
        $this->currency->setDbValueDef($rsnew, $this->currency->CurrentValue, strval($this->currency->CurrentValue) == "");

        // effective_from
        $this->effective_from->setDbValueDef($rsnew, UnFormatDateTime($this->effective_from->CurrentValue, $this->effective_from->formatPattern()), false);

        // effective_to
        $this->effective_to->setDbValueDef($rsnew, UnFormatDateTime($this->effective_to->CurrentValue, $this->effective_to->formatPattern()), false);

        // created_at
        $this->created_at->setDbValueDef($rsnew, UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()), false);

        // created_by
        $this->created_by->setDbValueDef($rsnew, $this->created_by->CurrentValue, false);

        // updated_at
        $this->updated_at->setDbValueDef($rsnew, UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()), false);

        // updated_by
        $this->updated_by->setDbValueDef($rsnew, $this->updated_by->CurrentValue, false);

        // is_active
        $tmpBool = $this->is_active->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_active->setDbValueDef($rsnew, $tmpBool, strval($this->is_active->CurrentValue) == "");

        // description
        $this->description->setDbValueDef($rsnew, $this->description->CurrentValue, false);
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
        if (isset($row['fee_name'])) { // fee_name
            $this->fee_name->setFormValue($row['fee_name']);
        }
        if (isset($row['fee_amount'])) { // fee_amount
            $this->fee_amount->setFormValue($row['fee_amount']);
        }
        if (isset($row['fee_type'])) { // fee_type
            $this->fee_type->setFormValue($row['fee_type']);
        }
        if (isset($row['currency'])) { // currency
            $this->currency->setFormValue($row['currency']);
        }
        if (isset($row['effective_from'])) { // effective_from
            $this->effective_from->setFormValue($row['effective_from']);
        }
        if (isset($row['effective_to'])) { // effective_to
            $this->effective_to->setFormValue($row['effective_to']);
        }
        if (isset($row['created_at'])) { // created_at
            $this->created_at->setFormValue($row['created_at']);
        }
        if (isset($row['created_by'])) { // created_by
            $this->created_by->setFormValue($row['created_by']);
        }
        if (isset($row['updated_at'])) { // updated_at
            $this->updated_at->setFormValue($row['updated_at']);
        }
        if (isset($row['updated_by'])) { // updated_by
            $this->updated_by->setFormValue($row['updated_by']);
        }
        if (isset($row['is_active'])) { // is_active
            $this->is_active->setFormValue($row['is_active']);
        }
        if (isset($row['description'])) { // description
            $this->description->setFormValue($row['description']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("FeeSchedulesList"), "", $this->TableVar, true);
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
                case "x_is_active":
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
