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
class NotarizationQueueAdd extends NotarizationQueue
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "NotarizationQueueAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "NotarizationQueueAdd";

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
        $this->queue_id->Visible = false;
        $this->request_id->setVisibility();
        $this->notary_id->setVisibility();
        $this->queue_position->setVisibility();
        $this->entry_time->setVisibility();
        $this->processing_started_at->setVisibility();
        $this->completed_at->setVisibility();
        $this->status->setVisibility();
        $this->estimated_wait_time->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'notarization_queue';
        $this->TableName = 'notarization_queue';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (notarization_queue)
        if (!isset($GLOBALS["notarization_queue"]) || $GLOBALS["notarization_queue"]::class == PROJECT_NAMESPACE . "notarization_queue") {
            $GLOBALS["notarization_queue"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'notarization_queue');
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
                        $result["view"] = SameString($pageName, "NotarizationQueueView"); // If View page, no primary button
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
            $key .= @$ar['queue_id'];
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
            $this->queue_id->Visible = false;
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
            if (($keyValue = Get("queue_id") ?? Route("queue_id")) !== null) {
                $this->queue_id->setQueryStringValue($keyValue);
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
                    $this->terminate("NotarizationQueueList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "NotarizationQueueList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "NotarizationQueueView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "NotarizationQueueList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "NotarizationQueueList"; // Return list page content
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
        $this->status->DefaultValue = $this->status->getDefault(); // PHP
        $this->status->OldValue = $this->status->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'request_id' first before field var 'x_request_id'
        $val = $CurrentForm->hasValue("request_id") ? $CurrentForm->getValue("request_id") : $CurrentForm->getValue("x_request_id");
        if (!$this->request_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->request_id->Visible = false; // Disable update for API request
            } else {
                $this->request_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'notary_id' first before field var 'x_notary_id'
        $val = $CurrentForm->hasValue("notary_id") ? $CurrentForm->getValue("notary_id") : $CurrentForm->getValue("x_notary_id");
        if (!$this->notary_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notary_id->Visible = false; // Disable update for API request
            } else {
                $this->notary_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'queue_position' first before field var 'x_queue_position'
        $val = $CurrentForm->hasValue("queue_position") ? $CurrentForm->getValue("queue_position") : $CurrentForm->getValue("x_queue_position");
        if (!$this->queue_position->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->queue_position->Visible = false; // Disable update for API request
            } else {
                $this->queue_position->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'entry_time' first before field var 'x_entry_time'
        $val = $CurrentForm->hasValue("entry_time") ? $CurrentForm->getValue("entry_time") : $CurrentForm->getValue("x_entry_time");
        if (!$this->entry_time->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->entry_time->Visible = false; // Disable update for API request
            } else {
                $this->entry_time->setFormValue($val, true, $validate);
            }
            $this->entry_time->CurrentValue = UnFormatDateTime($this->entry_time->CurrentValue, $this->entry_time->formatPattern());
        }

        // Check field name 'processing_started_at' first before field var 'x_processing_started_at'
        $val = $CurrentForm->hasValue("processing_started_at") ? $CurrentForm->getValue("processing_started_at") : $CurrentForm->getValue("x_processing_started_at");
        if (!$this->processing_started_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->processing_started_at->Visible = false; // Disable update for API request
            } else {
                $this->processing_started_at->setFormValue($val, true, $validate);
            }
            $this->processing_started_at->CurrentValue = UnFormatDateTime($this->processing_started_at->CurrentValue, $this->processing_started_at->formatPattern());
        }

        // Check field name 'completed_at' first before field var 'x_completed_at'
        $val = $CurrentForm->hasValue("completed_at") ? $CurrentForm->getValue("completed_at") : $CurrentForm->getValue("x_completed_at");
        if (!$this->completed_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->completed_at->Visible = false; // Disable update for API request
            } else {
                $this->completed_at->setFormValue($val, true, $validate);
            }
            $this->completed_at->CurrentValue = UnFormatDateTime($this->completed_at->CurrentValue, $this->completed_at->formatPattern());
        }

        // Check field name 'status' first before field var 'x_status'
        $val = $CurrentForm->hasValue("status") ? $CurrentForm->getValue("status") : $CurrentForm->getValue("x_status");
        if (!$this->status->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->status->Visible = false; // Disable update for API request
            } else {
                $this->status->setFormValue($val);
            }
        }

        // Check field name 'estimated_wait_time' first before field var 'x_estimated_wait_time'
        $val = $CurrentForm->hasValue("estimated_wait_time") ? $CurrentForm->getValue("estimated_wait_time") : $CurrentForm->getValue("x_estimated_wait_time");
        if (!$this->estimated_wait_time->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->estimated_wait_time->Visible = false; // Disable update for API request
            } else {
                $this->estimated_wait_time->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'queue_id' first before field var 'x_queue_id'
        $val = $CurrentForm->hasValue("queue_id") ? $CurrentForm->getValue("queue_id") : $CurrentForm->getValue("x_queue_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->request_id->CurrentValue = $this->request_id->FormValue;
        $this->notary_id->CurrentValue = $this->notary_id->FormValue;
        $this->queue_position->CurrentValue = $this->queue_position->FormValue;
        $this->entry_time->CurrentValue = $this->entry_time->FormValue;
        $this->entry_time->CurrentValue = UnFormatDateTime($this->entry_time->CurrentValue, $this->entry_time->formatPattern());
        $this->processing_started_at->CurrentValue = $this->processing_started_at->FormValue;
        $this->processing_started_at->CurrentValue = UnFormatDateTime($this->processing_started_at->CurrentValue, $this->processing_started_at->formatPattern());
        $this->completed_at->CurrentValue = $this->completed_at->FormValue;
        $this->completed_at->CurrentValue = UnFormatDateTime($this->completed_at->CurrentValue, $this->completed_at->formatPattern());
        $this->status->CurrentValue = $this->status->FormValue;
        $this->estimated_wait_time->CurrentValue = $this->estimated_wait_time->FormValue;
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
        $this->queue_id->setDbValue($row['queue_id']);
        $this->request_id->setDbValue($row['request_id']);
        $this->notary_id->setDbValue($row['notary_id']);
        $this->queue_position->setDbValue($row['queue_position']);
        $this->entry_time->setDbValue($row['entry_time']);
        $this->processing_started_at->setDbValue($row['processing_started_at']);
        $this->completed_at->setDbValue($row['completed_at']);
        $this->status->setDbValue($row['status']);
        $this->estimated_wait_time->setDbValue($row['estimated_wait_time']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['queue_id'] = $this->queue_id->DefaultValue;
        $row['request_id'] = $this->request_id->DefaultValue;
        $row['notary_id'] = $this->notary_id->DefaultValue;
        $row['queue_position'] = $this->queue_position->DefaultValue;
        $row['entry_time'] = $this->entry_time->DefaultValue;
        $row['processing_started_at'] = $this->processing_started_at->DefaultValue;
        $row['completed_at'] = $this->completed_at->DefaultValue;
        $row['status'] = $this->status->DefaultValue;
        $row['estimated_wait_time'] = $this->estimated_wait_time->DefaultValue;
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

        // queue_id
        $this->queue_id->RowCssClass = "row";

        // request_id
        $this->request_id->RowCssClass = "row";

        // notary_id
        $this->notary_id->RowCssClass = "row";

        // queue_position
        $this->queue_position->RowCssClass = "row";

        // entry_time
        $this->entry_time->RowCssClass = "row";

        // processing_started_at
        $this->processing_started_at->RowCssClass = "row";

        // completed_at
        $this->completed_at->RowCssClass = "row";

        // status
        $this->status->RowCssClass = "row";

        // estimated_wait_time
        $this->estimated_wait_time->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // queue_id
            $this->queue_id->ViewValue = $this->queue_id->CurrentValue;

            // request_id
            $this->request_id->ViewValue = $this->request_id->CurrentValue;
            $this->request_id->ViewValue = FormatNumber($this->request_id->ViewValue, $this->request_id->formatPattern());

            // notary_id
            $this->notary_id->ViewValue = $this->notary_id->CurrentValue;
            $this->notary_id->ViewValue = FormatNumber($this->notary_id->ViewValue, $this->notary_id->formatPattern());

            // queue_position
            $this->queue_position->ViewValue = $this->queue_position->CurrentValue;
            $this->queue_position->ViewValue = FormatNumber($this->queue_position->ViewValue, $this->queue_position->formatPattern());

            // entry_time
            $this->entry_time->ViewValue = $this->entry_time->CurrentValue;
            $this->entry_time->ViewValue = FormatDateTime($this->entry_time->ViewValue, $this->entry_time->formatPattern());

            // processing_started_at
            $this->processing_started_at->ViewValue = $this->processing_started_at->CurrentValue;
            $this->processing_started_at->ViewValue = FormatDateTime($this->processing_started_at->ViewValue, $this->processing_started_at->formatPattern());

            // completed_at
            $this->completed_at->ViewValue = $this->completed_at->CurrentValue;
            $this->completed_at->ViewValue = FormatDateTime($this->completed_at->ViewValue, $this->completed_at->formatPattern());

            // status
            $this->status->ViewValue = $this->status->CurrentValue;

            // estimated_wait_time
            $this->estimated_wait_time->ViewValue = $this->estimated_wait_time->CurrentValue;
            $this->estimated_wait_time->ViewValue = FormatNumber($this->estimated_wait_time->ViewValue, $this->estimated_wait_time->formatPattern());

            // request_id
            $this->request_id->HrefValue = "";

            // notary_id
            $this->notary_id->HrefValue = "";

            // queue_position
            $this->queue_position->HrefValue = "";

            // entry_time
            $this->entry_time->HrefValue = "";

            // processing_started_at
            $this->processing_started_at->HrefValue = "";

            // completed_at
            $this->completed_at->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // estimated_wait_time
            $this->estimated_wait_time->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // request_id
            $this->request_id->setupEditAttributes();
            $this->request_id->EditValue = $this->request_id->CurrentValue;
            $this->request_id->PlaceHolder = RemoveHtml($this->request_id->caption());
            if (strval($this->request_id->EditValue) != "" && is_numeric($this->request_id->EditValue)) {
                $this->request_id->EditValue = FormatNumber($this->request_id->EditValue, null);
            }

            // notary_id
            $this->notary_id->setupEditAttributes();
            $this->notary_id->EditValue = $this->notary_id->CurrentValue;
            $this->notary_id->PlaceHolder = RemoveHtml($this->notary_id->caption());
            if (strval($this->notary_id->EditValue) != "" && is_numeric($this->notary_id->EditValue)) {
                $this->notary_id->EditValue = FormatNumber($this->notary_id->EditValue, null);
            }

            // queue_position
            $this->queue_position->setupEditAttributes();
            $this->queue_position->EditValue = $this->queue_position->CurrentValue;
            $this->queue_position->PlaceHolder = RemoveHtml($this->queue_position->caption());
            if (strval($this->queue_position->EditValue) != "" && is_numeric($this->queue_position->EditValue)) {
                $this->queue_position->EditValue = FormatNumber($this->queue_position->EditValue, null);
            }

            // entry_time
            $this->entry_time->setupEditAttributes();
            $this->entry_time->EditValue = HtmlEncode(FormatDateTime($this->entry_time->CurrentValue, $this->entry_time->formatPattern()));
            $this->entry_time->PlaceHolder = RemoveHtml($this->entry_time->caption());

            // processing_started_at
            $this->processing_started_at->setupEditAttributes();
            $this->processing_started_at->EditValue = HtmlEncode(FormatDateTime($this->processing_started_at->CurrentValue, $this->processing_started_at->formatPattern()));
            $this->processing_started_at->PlaceHolder = RemoveHtml($this->processing_started_at->caption());

            // completed_at
            $this->completed_at->setupEditAttributes();
            $this->completed_at->EditValue = HtmlEncode(FormatDateTime($this->completed_at->CurrentValue, $this->completed_at->formatPattern()));
            $this->completed_at->PlaceHolder = RemoveHtml($this->completed_at->caption());

            // status
            $this->status->setupEditAttributes();
            if (!$this->status->Raw) {
                $this->status->CurrentValue = HtmlDecode($this->status->CurrentValue);
            }
            $this->status->EditValue = HtmlEncode($this->status->CurrentValue);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // estimated_wait_time
            $this->estimated_wait_time->setupEditAttributes();
            $this->estimated_wait_time->EditValue = $this->estimated_wait_time->CurrentValue;
            $this->estimated_wait_time->PlaceHolder = RemoveHtml($this->estimated_wait_time->caption());
            if (strval($this->estimated_wait_time->EditValue) != "" && is_numeric($this->estimated_wait_time->EditValue)) {
                $this->estimated_wait_time->EditValue = FormatNumber($this->estimated_wait_time->EditValue, null);
            }

            // Add refer script

            // request_id
            $this->request_id->HrefValue = "";

            // notary_id
            $this->notary_id->HrefValue = "";

            // queue_position
            $this->queue_position->HrefValue = "";

            // entry_time
            $this->entry_time->HrefValue = "";

            // processing_started_at
            $this->processing_started_at->HrefValue = "";

            // completed_at
            $this->completed_at->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // estimated_wait_time
            $this->estimated_wait_time->HrefValue = "";
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
            if ($this->request_id->Visible && $this->request_id->Required) {
                if (!$this->request_id->IsDetailKey && EmptyValue($this->request_id->FormValue)) {
                    $this->request_id->addErrorMessage(str_replace("%s", $this->request_id->caption(), $this->request_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->request_id->FormValue)) {
                $this->request_id->addErrorMessage($this->request_id->getErrorMessage(false));
            }
            if ($this->notary_id->Visible && $this->notary_id->Required) {
                if (!$this->notary_id->IsDetailKey && EmptyValue($this->notary_id->FormValue)) {
                    $this->notary_id->addErrorMessage(str_replace("%s", $this->notary_id->caption(), $this->notary_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->notary_id->FormValue)) {
                $this->notary_id->addErrorMessage($this->notary_id->getErrorMessage(false));
            }
            if ($this->queue_position->Visible && $this->queue_position->Required) {
                if (!$this->queue_position->IsDetailKey && EmptyValue($this->queue_position->FormValue)) {
                    $this->queue_position->addErrorMessage(str_replace("%s", $this->queue_position->caption(), $this->queue_position->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->queue_position->FormValue)) {
                $this->queue_position->addErrorMessage($this->queue_position->getErrorMessage(false));
            }
            if ($this->entry_time->Visible && $this->entry_time->Required) {
                if (!$this->entry_time->IsDetailKey && EmptyValue($this->entry_time->FormValue)) {
                    $this->entry_time->addErrorMessage(str_replace("%s", $this->entry_time->caption(), $this->entry_time->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->entry_time->FormValue, $this->entry_time->formatPattern())) {
                $this->entry_time->addErrorMessage($this->entry_time->getErrorMessage(false));
            }
            if ($this->processing_started_at->Visible && $this->processing_started_at->Required) {
                if (!$this->processing_started_at->IsDetailKey && EmptyValue($this->processing_started_at->FormValue)) {
                    $this->processing_started_at->addErrorMessage(str_replace("%s", $this->processing_started_at->caption(), $this->processing_started_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->processing_started_at->FormValue, $this->processing_started_at->formatPattern())) {
                $this->processing_started_at->addErrorMessage($this->processing_started_at->getErrorMessage(false));
            }
            if ($this->completed_at->Visible && $this->completed_at->Required) {
                if (!$this->completed_at->IsDetailKey && EmptyValue($this->completed_at->FormValue)) {
                    $this->completed_at->addErrorMessage(str_replace("%s", $this->completed_at->caption(), $this->completed_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->completed_at->FormValue, $this->completed_at->formatPattern())) {
                $this->completed_at->addErrorMessage($this->completed_at->getErrorMessage(false));
            }
            if ($this->status->Visible && $this->status->Required) {
                if (!$this->status->IsDetailKey && EmptyValue($this->status->FormValue)) {
                    $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
                }
            }
            if ($this->estimated_wait_time->Visible && $this->estimated_wait_time->Required) {
                if (!$this->estimated_wait_time->IsDetailKey && EmptyValue($this->estimated_wait_time->FormValue)) {
                    $this->estimated_wait_time->addErrorMessage(str_replace("%s", $this->estimated_wait_time->caption(), $this->estimated_wait_time->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->estimated_wait_time->FormValue)) {
                $this->estimated_wait_time->addErrorMessage($this->estimated_wait_time->getErrorMessage(false));
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
        if ($this->request_id->CurrentValue != "") { // Check field with unique index
            $filter = "(\"request_id\" = " . AdjustSql($this->request_id->CurrentValue, $this->Dbid) . ")";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->request_id->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->request_id->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
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

        // request_id
        $this->request_id->setDbValueDef($rsnew, $this->request_id->CurrentValue, false);

        // notary_id
        $this->notary_id->setDbValueDef($rsnew, $this->notary_id->CurrentValue, false);

        // queue_position
        $this->queue_position->setDbValueDef($rsnew, $this->queue_position->CurrentValue, false);

        // entry_time
        $this->entry_time->setDbValueDef($rsnew, UnFormatDateTime($this->entry_time->CurrentValue, $this->entry_time->formatPattern()), false);

        // processing_started_at
        $this->processing_started_at->setDbValueDef($rsnew, UnFormatDateTime($this->processing_started_at->CurrentValue, $this->processing_started_at->formatPattern()), false);

        // completed_at
        $this->completed_at->setDbValueDef($rsnew, UnFormatDateTime($this->completed_at->CurrentValue, $this->completed_at->formatPattern()), false);

        // status
        $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, strval($this->status->CurrentValue) == "");

        // estimated_wait_time
        $this->estimated_wait_time->setDbValueDef($rsnew, $this->estimated_wait_time->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['request_id'])) { // request_id
            $this->request_id->setFormValue($row['request_id']);
        }
        if (isset($row['notary_id'])) { // notary_id
            $this->notary_id->setFormValue($row['notary_id']);
        }
        if (isset($row['queue_position'])) { // queue_position
            $this->queue_position->setFormValue($row['queue_position']);
        }
        if (isset($row['entry_time'])) { // entry_time
            $this->entry_time->setFormValue($row['entry_time']);
        }
        if (isset($row['processing_started_at'])) { // processing_started_at
            $this->processing_started_at->setFormValue($row['processing_started_at']);
        }
        if (isset($row['completed_at'])) { // completed_at
            $this->completed_at->setFormValue($row['completed_at']);
        }
        if (isset($row['status'])) { // status
            $this->status->setFormValue($row['status']);
        }
        if (isset($row['estimated_wait_time'])) { // estimated_wait_time
            $this->estimated_wait_time->setFormValue($row['estimated_wait_time']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("NotarizationQueueList"), "", $this->TableVar, true);
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
