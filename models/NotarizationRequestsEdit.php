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
class NotarizationRequestsEdit extends NotarizationRequests
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "NotarizationRequestsEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "NotarizationRequestsEdit";

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
        $this->request_id->setVisibility();
        $this->document_id->setVisibility();
        $this->user_id->setVisibility();
        $this->request_reference->setVisibility();
        $this->status->setVisibility();
        $this->requested_at->setVisibility();
        $this->notary_id->setVisibility();
        $this->assigned_at->setVisibility();
        $this->notarized_at->setVisibility();
        $this->rejection_reason->setVisibility();
        $this->rejected_at->setVisibility();
        $this->rejected_by->setVisibility();
        $this->priority->setVisibility();
        $this->payment_status->setVisibility();
        $this->payment_transaction_id->setVisibility();
        $this->modified_at->setVisibility();
        $this->ip_address->setVisibility();
        $this->browser_info->setVisibility();
        $this->device_info->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'notarization_requests';
        $this->TableName = 'notarization_requests';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (notarization_requests)
        if (!isset($GLOBALS["notarization_requests"]) || $GLOBALS["notarization_requests"]::class == PROJECT_NAMESPACE . "notarization_requests") {
            $GLOBALS["notarization_requests"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'notarization_requests');
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
                        $result["view"] = SameString($pageName, "NotarizationRequestsView"); // If View page, no primary button
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
            $key .= @$ar['request_id'];
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
            $this->request_id->Visible = false;
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
            if (($keyValue = Get("request_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->request_id->setQueryStringValue($keyValue);
                $this->request_id->setOldValue($this->request_id->QueryStringValue);
            } elseif (Post("request_id") !== null) {
                $this->request_id->setFormValue(Post("request_id"));
                $this->request_id->setOldValue($this->request_id->FormValue);
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
                if (($keyValue = Get("request_id") ?? Route("request_id")) !== null) {
                    $this->request_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->request_id->CurrentValue = null;
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
                        $this->terminate("NotarizationRequestsList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "NotarizationRequestsList") {
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
                        if (GetPageName($returnUrl) != "NotarizationRequestsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "NotarizationRequestsList"; // Return list page content
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

        // Check field name 'request_id' first before field var 'x_request_id'
        $val = $CurrentForm->hasValue("request_id") ? $CurrentForm->getValue("request_id") : $CurrentForm->getValue("x_request_id");
        if (!$this->request_id->IsDetailKey) {
            $this->request_id->setFormValue($val);
        }

        // Check field name 'document_id' first before field var 'x_document_id'
        $val = $CurrentForm->hasValue("document_id") ? $CurrentForm->getValue("document_id") : $CurrentForm->getValue("x_document_id");
        if (!$this->document_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->document_id->Visible = false; // Disable update for API request
            } else {
                $this->document_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'user_id' first before field var 'x_user_id'
        $val = $CurrentForm->hasValue("user_id") ? $CurrentForm->getValue("user_id") : $CurrentForm->getValue("x_user_id");
        if (!$this->user_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->user_id->Visible = false; // Disable update for API request
            } else {
                $this->user_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'request_reference' first before field var 'x_request_reference'
        $val = $CurrentForm->hasValue("request_reference") ? $CurrentForm->getValue("request_reference") : $CurrentForm->getValue("x_request_reference");
        if (!$this->request_reference->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->request_reference->Visible = false; // Disable update for API request
            } else {
                $this->request_reference->setFormValue($val);
            }
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

        // Check field name 'requested_at' first before field var 'x_requested_at'
        $val = $CurrentForm->hasValue("requested_at") ? $CurrentForm->getValue("requested_at") : $CurrentForm->getValue("x_requested_at");
        if (!$this->requested_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->requested_at->Visible = false; // Disable update for API request
            } else {
                $this->requested_at->setFormValue($val, true, $validate);
            }
            $this->requested_at->CurrentValue = UnFormatDateTime($this->requested_at->CurrentValue, $this->requested_at->formatPattern());
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

        // Check field name 'assigned_at' first before field var 'x_assigned_at'
        $val = $CurrentForm->hasValue("assigned_at") ? $CurrentForm->getValue("assigned_at") : $CurrentForm->getValue("x_assigned_at");
        if (!$this->assigned_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->assigned_at->Visible = false; // Disable update for API request
            } else {
                $this->assigned_at->setFormValue($val, true, $validate);
            }
            $this->assigned_at->CurrentValue = UnFormatDateTime($this->assigned_at->CurrentValue, $this->assigned_at->formatPattern());
        }

        // Check field name 'notarized_at' first before field var 'x_notarized_at'
        $val = $CurrentForm->hasValue("notarized_at") ? $CurrentForm->getValue("notarized_at") : $CurrentForm->getValue("x_notarized_at");
        if (!$this->notarized_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notarized_at->Visible = false; // Disable update for API request
            } else {
                $this->notarized_at->setFormValue($val, true, $validate);
            }
            $this->notarized_at->CurrentValue = UnFormatDateTime($this->notarized_at->CurrentValue, $this->notarized_at->formatPattern());
        }

        // Check field name 'rejection_reason' first before field var 'x_rejection_reason'
        $val = $CurrentForm->hasValue("rejection_reason") ? $CurrentForm->getValue("rejection_reason") : $CurrentForm->getValue("x_rejection_reason");
        if (!$this->rejection_reason->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->rejection_reason->Visible = false; // Disable update for API request
            } else {
                $this->rejection_reason->setFormValue($val);
            }
        }

        // Check field name 'rejected_at' first before field var 'x_rejected_at'
        $val = $CurrentForm->hasValue("rejected_at") ? $CurrentForm->getValue("rejected_at") : $CurrentForm->getValue("x_rejected_at");
        if (!$this->rejected_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->rejected_at->Visible = false; // Disable update for API request
            } else {
                $this->rejected_at->setFormValue($val, true, $validate);
            }
            $this->rejected_at->CurrentValue = UnFormatDateTime($this->rejected_at->CurrentValue, $this->rejected_at->formatPattern());
        }

        // Check field name 'rejected_by' first before field var 'x_rejected_by'
        $val = $CurrentForm->hasValue("rejected_by") ? $CurrentForm->getValue("rejected_by") : $CurrentForm->getValue("x_rejected_by");
        if (!$this->rejected_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->rejected_by->Visible = false; // Disable update for API request
            } else {
                $this->rejected_by->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'priority' first before field var 'x_priority'
        $val = $CurrentForm->hasValue("priority") ? $CurrentForm->getValue("priority") : $CurrentForm->getValue("x_priority");
        if (!$this->priority->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->priority->Visible = false; // Disable update for API request
            } else {
                $this->priority->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'payment_status' first before field var 'x_payment_status'
        $val = $CurrentForm->hasValue("payment_status") ? $CurrentForm->getValue("payment_status") : $CurrentForm->getValue("x_payment_status");
        if (!$this->payment_status->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->payment_status->Visible = false; // Disable update for API request
            } else {
                $this->payment_status->setFormValue($val);
            }
        }

        // Check field name 'payment_transaction_id' first before field var 'x_payment_transaction_id'
        $val = $CurrentForm->hasValue("payment_transaction_id") ? $CurrentForm->getValue("payment_transaction_id") : $CurrentForm->getValue("x_payment_transaction_id");
        if (!$this->payment_transaction_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->payment_transaction_id->Visible = false; // Disable update for API request
            } else {
                $this->payment_transaction_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'modified_at' first before field var 'x_modified_at'
        $val = $CurrentForm->hasValue("modified_at") ? $CurrentForm->getValue("modified_at") : $CurrentForm->getValue("x_modified_at");
        if (!$this->modified_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->modified_at->Visible = false; // Disable update for API request
            } else {
                $this->modified_at->setFormValue($val, true, $validate);
            }
            $this->modified_at->CurrentValue = UnFormatDateTime($this->modified_at->CurrentValue, $this->modified_at->formatPattern());
        }

        // Check field name 'ip_address' first before field var 'x_ip_address'
        $val = $CurrentForm->hasValue("ip_address") ? $CurrentForm->getValue("ip_address") : $CurrentForm->getValue("x_ip_address");
        if (!$this->ip_address->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ip_address->Visible = false; // Disable update for API request
            } else {
                $this->ip_address->setFormValue($val);
            }
        }

        // Check field name 'browser_info' first before field var 'x_browser_info'
        $val = $CurrentForm->hasValue("browser_info") ? $CurrentForm->getValue("browser_info") : $CurrentForm->getValue("x_browser_info");
        if (!$this->browser_info->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->browser_info->Visible = false; // Disable update for API request
            } else {
                $this->browser_info->setFormValue($val);
            }
        }

        // Check field name 'device_info' first before field var 'x_device_info'
        $val = $CurrentForm->hasValue("device_info") ? $CurrentForm->getValue("device_info") : $CurrentForm->getValue("x_device_info");
        if (!$this->device_info->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->device_info->Visible = false; // Disable update for API request
            } else {
                $this->device_info->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->request_id->CurrentValue = $this->request_id->FormValue;
        $this->document_id->CurrentValue = $this->document_id->FormValue;
        $this->user_id->CurrentValue = $this->user_id->FormValue;
        $this->request_reference->CurrentValue = $this->request_reference->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
        $this->requested_at->CurrentValue = $this->requested_at->FormValue;
        $this->requested_at->CurrentValue = UnFormatDateTime($this->requested_at->CurrentValue, $this->requested_at->formatPattern());
        $this->notary_id->CurrentValue = $this->notary_id->FormValue;
        $this->assigned_at->CurrentValue = $this->assigned_at->FormValue;
        $this->assigned_at->CurrentValue = UnFormatDateTime($this->assigned_at->CurrentValue, $this->assigned_at->formatPattern());
        $this->notarized_at->CurrentValue = $this->notarized_at->FormValue;
        $this->notarized_at->CurrentValue = UnFormatDateTime($this->notarized_at->CurrentValue, $this->notarized_at->formatPattern());
        $this->rejection_reason->CurrentValue = $this->rejection_reason->FormValue;
        $this->rejected_at->CurrentValue = $this->rejected_at->FormValue;
        $this->rejected_at->CurrentValue = UnFormatDateTime($this->rejected_at->CurrentValue, $this->rejected_at->formatPattern());
        $this->rejected_by->CurrentValue = $this->rejected_by->FormValue;
        $this->priority->CurrentValue = $this->priority->FormValue;
        $this->payment_status->CurrentValue = $this->payment_status->FormValue;
        $this->payment_transaction_id->CurrentValue = $this->payment_transaction_id->FormValue;
        $this->modified_at->CurrentValue = $this->modified_at->FormValue;
        $this->modified_at->CurrentValue = UnFormatDateTime($this->modified_at->CurrentValue, $this->modified_at->formatPattern());
        $this->ip_address->CurrentValue = $this->ip_address->FormValue;
        $this->browser_info->CurrentValue = $this->browser_info->FormValue;
        $this->device_info->CurrentValue = $this->device_info->FormValue;
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
        $this->request_id->setDbValue($row['request_id']);
        $this->document_id->setDbValue($row['document_id']);
        $this->user_id->setDbValue($row['user_id']);
        $this->request_reference->setDbValue($row['request_reference']);
        $this->status->setDbValue($row['status']);
        $this->requested_at->setDbValue($row['requested_at']);
        $this->notary_id->setDbValue($row['notary_id']);
        $this->assigned_at->setDbValue($row['assigned_at']);
        $this->notarized_at->setDbValue($row['notarized_at']);
        $this->rejection_reason->setDbValue($row['rejection_reason']);
        $this->rejected_at->setDbValue($row['rejected_at']);
        $this->rejected_by->setDbValue($row['rejected_by']);
        $this->priority->setDbValue($row['priority']);
        $this->payment_status->setDbValue($row['payment_status']);
        $this->payment_transaction_id->setDbValue($row['payment_transaction_id']);
        $this->modified_at->setDbValue($row['modified_at']);
        $this->ip_address->setDbValue($row['ip_address']);
        $this->browser_info->setDbValue($row['browser_info']);
        $this->device_info->setDbValue($row['device_info']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['request_id'] = $this->request_id->DefaultValue;
        $row['document_id'] = $this->document_id->DefaultValue;
        $row['user_id'] = $this->user_id->DefaultValue;
        $row['request_reference'] = $this->request_reference->DefaultValue;
        $row['status'] = $this->status->DefaultValue;
        $row['requested_at'] = $this->requested_at->DefaultValue;
        $row['notary_id'] = $this->notary_id->DefaultValue;
        $row['assigned_at'] = $this->assigned_at->DefaultValue;
        $row['notarized_at'] = $this->notarized_at->DefaultValue;
        $row['rejection_reason'] = $this->rejection_reason->DefaultValue;
        $row['rejected_at'] = $this->rejected_at->DefaultValue;
        $row['rejected_by'] = $this->rejected_by->DefaultValue;
        $row['priority'] = $this->priority->DefaultValue;
        $row['payment_status'] = $this->payment_status->DefaultValue;
        $row['payment_transaction_id'] = $this->payment_transaction_id->DefaultValue;
        $row['modified_at'] = $this->modified_at->DefaultValue;
        $row['ip_address'] = $this->ip_address->DefaultValue;
        $row['browser_info'] = $this->browser_info->DefaultValue;
        $row['device_info'] = $this->device_info->DefaultValue;
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

        // request_id
        $this->request_id->RowCssClass = "row";

        // document_id
        $this->document_id->RowCssClass = "row";

        // user_id
        $this->user_id->RowCssClass = "row";

        // request_reference
        $this->request_reference->RowCssClass = "row";

        // status
        $this->status->RowCssClass = "row";

        // requested_at
        $this->requested_at->RowCssClass = "row";

        // notary_id
        $this->notary_id->RowCssClass = "row";

        // assigned_at
        $this->assigned_at->RowCssClass = "row";

        // notarized_at
        $this->notarized_at->RowCssClass = "row";

        // rejection_reason
        $this->rejection_reason->RowCssClass = "row";

        // rejected_at
        $this->rejected_at->RowCssClass = "row";

        // rejected_by
        $this->rejected_by->RowCssClass = "row";

        // priority
        $this->priority->RowCssClass = "row";

        // payment_status
        $this->payment_status->RowCssClass = "row";

        // payment_transaction_id
        $this->payment_transaction_id->RowCssClass = "row";

        // modified_at
        $this->modified_at->RowCssClass = "row";

        // ip_address
        $this->ip_address->RowCssClass = "row";

        // browser_info
        $this->browser_info->RowCssClass = "row";

        // device_info
        $this->device_info->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // request_id
            $this->request_id->ViewValue = $this->request_id->CurrentValue;

            // document_id
            $this->document_id->ViewValue = $this->document_id->CurrentValue;
            $this->document_id->ViewValue = FormatNumber($this->document_id->ViewValue, $this->document_id->formatPattern());

            // user_id
            $this->user_id->ViewValue = $this->user_id->CurrentValue;
            $this->user_id->ViewValue = FormatNumber($this->user_id->ViewValue, $this->user_id->formatPattern());

            // request_reference
            $this->request_reference->ViewValue = $this->request_reference->CurrentValue;

            // status
            $this->status->ViewValue = $this->status->CurrentValue;

            // requested_at
            $this->requested_at->ViewValue = $this->requested_at->CurrentValue;
            $this->requested_at->ViewValue = FormatDateTime($this->requested_at->ViewValue, $this->requested_at->formatPattern());

            // notary_id
            $this->notary_id->ViewValue = $this->notary_id->CurrentValue;
            $this->notary_id->ViewValue = FormatNumber($this->notary_id->ViewValue, $this->notary_id->formatPattern());

            // assigned_at
            $this->assigned_at->ViewValue = $this->assigned_at->CurrentValue;
            $this->assigned_at->ViewValue = FormatDateTime($this->assigned_at->ViewValue, $this->assigned_at->formatPattern());

            // notarized_at
            $this->notarized_at->ViewValue = $this->notarized_at->CurrentValue;
            $this->notarized_at->ViewValue = FormatDateTime($this->notarized_at->ViewValue, $this->notarized_at->formatPattern());

            // rejection_reason
            $this->rejection_reason->ViewValue = $this->rejection_reason->CurrentValue;

            // rejected_at
            $this->rejected_at->ViewValue = $this->rejected_at->CurrentValue;
            $this->rejected_at->ViewValue = FormatDateTime($this->rejected_at->ViewValue, $this->rejected_at->formatPattern());

            // rejected_by
            $this->rejected_by->ViewValue = $this->rejected_by->CurrentValue;
            $this->rejected_by->ViewValue = FormatNumber($this->rejected_by->ViewValue, $this->rejected_by->formatPattern());

            // priority
            $this->priority->ViewValue = $this->priority->CurrentValue;
            $this->priority->ViewValue = FormatNumber($this->priority->ViewValue, $this->priority->formatPattern());

            // payment_status
            $this->payment_status->ViewValue = $this->payment_status->CurrentValue;

            // payment_transaction_id
            $this->payment_transaction_id->ViewValue = $this->payment_transaction_id->CurrentValue;
            $this->payment_transaction_id->ViewValue = FormatNumber($this->payment_transaction_id->ViewValue, $this->payment_transaction_id->formatPattern());

            // modified_at
            $this->modified_at->ViewValue = $this->modified_at->CurrentValue;
            $this->modified_at->ViewValue = FormatDateTime($this->modified_at->ViewValue, $this->modified_at->formatPattern());

            // ip_address
            $this->ip_address->ViewValue = $this->ip_address->CurrentValue;

            // browser_info
            $this->browser_info->ViewValue = $this->browser_info->CurrentValue;

            // device_info
            $this->device_info->ViewValue = $this->device_info->CurrentValue;

            // request_id
            $this->request_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // request_reference
            $this->request_reference->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // requested_at
            $this->requested_at->HrefValue = "";

            // notary_id
            $this->notary_id->HrefValue = "";

            // assigned_at
            $this->assigned_at->HrefValue = "";

            // notarized_at
            $this->notarized_at->HrefValue = "";

            // rejection_reason
            $this->rejection_reason->HrefValue = "";

            // rejected_at
            $this->rejected_at->HrefValue = "";

            // rejected_by
            $this->rejected_by->HrefValue = "";

            // priority
            $this->priority->HrefValue = "";

            // payment_status
            $this->payment_status->HrefValue = "";

            // payment_transaction_id
            $this->payment_transaction_id->HrefValue = "";

            // modified_at
            $this->modified_at->HrefValue = "";

            // ip_address
            $this->ip_address->HrefValue = "";

            // browser_info
            $this->browser_info->HrefValue = "";

            // device_info
            $this->device_info->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // request_id
            $this->request_id->setupEditAttributes();
            $this->request_id->EditValue = $this->request_id->CurrentValue;

            // document_id
            $this->document_id->setupEditAttributes();
            $this->document_id->EditValue = $this->document_id->CurrentValue;
            $this->document_id->PlaceHolder = RemoveHtml($this->document_id->caption());
            if (strval($this->document_id->EditValue) != "" && is_numeric($this->document_id->EditValue)) {
                $this->document_id->EditValue = FormatNumber($this->document_id->EditValue, null);
            }

            // user_id
            $this->user_id->setupEditAttributes();
            $this->user_id->EditValue = $this->user_id->CurrentValue;
            $this->user_id->PlaceHolder = RemoveHtml($this->user_id->caption());
            if (strval($this->user_id->EditValue) != "" && is_numeric($this->user_id->EditValue)) {
                $this->user_id->EditValue = FormatNumber($this->user_id->EditValue, null);
            }

            // request_reference
            $this->request_reference->setupEditAttributes();
            if (!$this->request_reference->Raw) {
                $this->request_reference->CurrentValue = HtmlDecode($this->request_reference->CurrentValue);
            }
            $this->request_reference->EditValue = HtmlEncode($this->request_reference->CurrentValue);
            $this->request_reference->PlaceHolder = RemoveHtml($this->request_reference->caption());

            // status
            $this->status->setupEditAttributes();
            if (!$this->status->Raw) {
                $this->status->CurrentValue = HtmlDecode($this->status->CurrentValue);
            }
            $this->status->EditValue = HtmlEncode($this->status->CurrentValue);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // requested_at
            $this->requested_at->setupEditAttributes();
            $this->requested_at->EditValue = HtmlEncode(FormatDateTime($this->requested_at->CurrentValue, $this->requested_at->formatPattern()));
            $this->requested_at->PlaceHolder = RemoveHtml($this->requested_at->caption());

            // notary_id
            $this->notary_id->setupEditAttributes();
            $this->notary_id->EditValue = $this->notary_id->CurrentValue;
            $this->notary_id->PlaceHolder = RemoveHtml($this->notary_id->caption());
            if (strval($this->notary_id->EditValue) != "" && is_numeric($this->notary_id->EditValue)) {
                $this->notary_id->EditValue = FormatNumber($this->notary_id->EditValue, null);
            }

            // assigned_at
            $this->assigned_at->setupEditAttributes();
            $this->assigned_at->EditValue = HtmlEncode(FormatDateTime($this->assigned_at->CurrentValue, $this->assigned_at->formatPattern()));
            $this->assigned_at->PlaceHolder = RemoveHtml($this->assigned_at->caption());

            // notarized_at
            $this->notarized_at->setupEditAttributes();
            $this->notarized_at->EditValue = HtmlEncode(FormatDateTime($this->notarized_at->CurrentValue, $this->notarized_at->formatPattern()));
            $this->notarized_at->PlaceHolder = RemoveHtml($this->notarized_at->caption());

            // rejection_reason
            $this->rejection_reason->setupEditAttributes();
            $this->rejection_reason->EditValue = HtmlEncode($this->rejection_reason->CurrentValue);
            $this->rejection_reason->PlaceHolder = RemoveHtml($this->rejection_reason->caption());

            // rejected_at
            $this->rejected_at->setupEditAttributes();
            $this->rejected_at->EditValue = HtmlEncode(FormatDateTime($this->rejected_at->CurrentValue, $this->rejected_at->formatPattern()));
            $this->rejected_at->PlaceHolder = RemoveHtml($this->rejected_at->caption());

            // rejected_by
            $this->rejected_by->setupEditAttributes();
            $this->rejected_by->EditValue = $this->rejected_by->CurrentValue;
            $this->rejected_by->PlaceHolder = RemoveHtml($this->rejected_by->caption());
            if (strval($this->rejected_by->EditValue) != "" && is_numeric($this->rejected_by->EditValue)) {
                $this->rejected_by->EditValue = FormatNumber($this->rejected_by->EditValue, null);
            }

            // priority
            $this->priority->setupEditAttributes();
            $this->priority->EditValue = $this->priority->CurrentValue;
            $this->priority->PlaceHolder = RemoveHtml($this->priority->caption());
            if (strval($this->priority->EditValue) != "" && is_numeric($this->priority->EditValue)) {
                $this->priority->EditValue = FormatNumber($this->priority->EditValue, null);
            }

            // payment_status
            $this->payment_status->setupEditAttributes();
            if (!$this->payment_status->Raw) {
                $this->payment_status->CurrentValue = HtmlDecode($this->payment_status->CurrentValue);
            }
            $this->payment_status->EditValue = HtmlEncode($this->payment_status->CurrentValue);
            $this->payment_status->PlaceHolder = RemoveHtml($this->payment_status->caption());

            // payment_transaction_id
            $this->payment_transaction_id->setupEditAttributes();
            $this->payment_transaction_id->EditValue = $this->payment_transaction_id->CurrentValue;
            $this->payment_transaction_id->PlaceHolder = RemoveHtml($this->payment_transaction_id->caption());
            if (strval($this->payment_transaction_id->EditValue) != "" && is_numeric($this->payment_transaction_id->EditValue)) {
                $this->payment_transaction_id->EditValue = FormatNumber($this->payment_transaction_id->EditValue, null);
            }

            // modified_at
            $this->modified_at->setupEditAttributes();
            $this->modified_at->EditValue = HtmlEncode(FormatDateTime($this->modified_at->CurrentValue, $this->modified_at->formatPattern()));
            $this->modified_at->PlaceHolder = RemoveHtml($this->modified_at->caption());

            // ip_address
            $this->ip_address->setupEditAttributes();
            if (!$this->ip_address->Raw) {
                $this->ip_address->CurrentValue = HtmlDecode($this->ip_address->CurrentValue);
            }
            $this->ip_address->EditValue = HtmlEncode($this->ip_address->CurrentValue);
            $this->ip_address->PlaceHolder = RemoveHtml($this->ip_address->caption());

            // browser_info
            $this->browser_info->setupEditAttributes();
            $this->browser_info->EditValue = HtmlEncode($this->browser_info->CurrentValue);
            $this->browser_info->PlaceHolder = RemoveHtml($this->browser_info->caption());

            // device_info
            $this->device_info->setupEditAttributes();
            $this->device_info->EditValue = HtmlEncode($this->device_info->CurrentValue);
            $this->device_info->PlaceHolder = RemoveHtml($this->device_info->caption());

            // Edit refer script

            // request_id
            $this->request_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // request_reference
            $this->request_reference->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // requested_at
            $this->requested_at->HrefValue = "";

            // notary_id
            $this->notary_id->HrefValue = "";

            // assigned_at
            $this->assigned_at->HrefValue = "";

            // notarized_at
            $this->notarized_at->HrefValue = "";

            // rejection_reason
            $this->rejection_reason->HrefValue = "";

            // rejected_at
            $this->rejected_at->HrefValue = "";

            // rejected_by
            $this->rejected_by->HrefValue = "";

            // priority
            $this->priority->HrefValue = "";

            // payment_status
            $this->payment_status->HrefValue = "";

            // payment_transaction_id
            $this->payment_transaction_id->HrefValue = "";

            // modified_at
            $this->modified_at->HrefValue = "";

            // ip_address
            $this->ip_address->HrefValue = "";

            // browser_info
            $this->browser_info->HrefValue = "";

            // device_info
            $this->device_info->HrefValue = "";
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
            if ($this->document_id->Visible && $this->document_id->Required) {
                if (!$this->document_id->IsDetailKey && EmptyValue($this->document_id->FormValue)) {
                    $this->document_id->addErrorMessage(str_replace("%s", $this->document_id->caption(), $this->document_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->document_id->FormValue)) {
                $this->document_id->addErrorMessage($this->document_id->getErrorMessage(false));
            }
            if ($this->user_id->Visible && $this->user_id->Required) {
                if (!$this->user_id->IsDetailKey && EmptyValue($this->user_id->FormValue)) {
                    $this->user_id->addErrorMessage(str_replace("%s", $this->user_id->caption(), $this->user_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->user_id->FormValue)) {
                $this->user_id->addErrorMessage($this->user_id->getErrorMessage(false));
            }
            if ($this->request_reference->Visible && $this->request_reference->Required) {
                if (!$this->request_reference->IsDetailKey && EmptyValue($this->request_reference->FormValue)) {
                    $this->request_reference->addErrorMessage(str_replace("%s", $this->request_reference->caption(), $this->request_reference->RequiredErrorMessage));
                }
            }
            if ($this->status->Visible && $this->status->Required) {
                if (!$this->status->IsDetailKey && EmptyValue($this->status->FormValue)) {
                    $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
                }
            }
            if ($this->requested_at->Visible && $this->requested_at->Required) {
                if (!$this->requested_at->IsDetailKey && EmptyValue($this->requested_at->FormValue)) {
                    $this->requested_at->addErrorMessage(str_replace("%s", $this->requested_at->caption(), $this->requested_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->requested_at->FormValue, $this->requested_at->formatPattern())) {
                $this->requested_at->addErrorMessage($this->requested_at->getErrorMessage(false));
            }
            if ($this->notary_id->Visible && $this->notary_id->Required) {
                if (!$this->notary_id->IsDetailKey && EmptyValue($this->notary_id->FormValue)) {
                    $this->notary_id->addErrorMessage(str_replace("%s", $this->notary_id->caption(), $this->notary_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->notary_id->FormValue)) {
                $this->notary_id->addErrorMessage($this->notary_id->getErrorMessage(false));
            }
            if ($this->assigned_at->Visible && $this->assigned_at->Required) {
                if (!$this->assigned_at->IsDetailKey && EmptyValue($this->assigned_at->FormValue)) {
                    $this->assigned_at->addErrorMessage(str_replace("%s", $this->assigned_at->caption(), $this->assigned_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->assigned_at->FormValue, $this->assigned_at->formatPattern())) {
                $this->assigned_at->addErrorMessage($this->assigned_at->getErrorMessage(false));
            }
            if ($this->notarized_at->Visible && $this->notarized_at->Required) {
                if (!$this->notarized_at->IsDetailKey && EmptyValue($this->notarized_at->FormValue)) {
                    $this->notarized_at->addErrorMessage(str_replace("%s", $this->notarized_at->caption(), $this->notarized_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->notarized_at->FormValue, $this->notarized_at->formatPattern())) {
                $this->notarized_at->addErrorMessage($this->notarized_at->getErrorMessage(false));
            }
            if ($this->rejection_reason->Visible && $this->rejection_reason->Required) {
                if (!$this->rejection_reason->IsDetailKey && EmptyValue($this->rejection_reason->FormValue)) {
                    $this->rejection_reason->addErrorMessage(str_replace("%s", $this->rejection_reason->caption(), $this->rejection_reason->RequiredErrorMessage));
                }
            }
            if ($this->rejected_at->Visible && $this->rejected_at->Required) {
                if (!$this->rejected_at->IsDetailKey && EmptyValue($this->rejected_at->FormValue)) {
                    $this->rejected_at->addErrorMessage(str_replace("%s", $this->rejected_at->caption(), $this->rejected_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->rejected_at->FormValue, $this->rejected_at->formatPattern())) {
                $this->rejected_at->addErrorMessage($this->rejected_at->getErrorMessage(false));
            }
            if ($this->rejected_by->Visible && $this->rejected_by->Required) {
                if (!$this->rejected_by->IsDetailKey && EmptyValue($this->rejected_by->FormValue)) {
                    $this->rejected_by->addErrorMessage(str_replace("%s", $this->rejected_by->caption(), $this->rejected_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->rejected_by->FormValue)) {
                $this->rejected_by->addErrorMessage($this->rejected_by->getErrorMessage(false));
            }
            if ($this->priority->Visible && $this->priority->Required) {
                if (!$this->priority->IsDetailKey && EmptyValue($this->priority->FormValue)) {
                    $this->priority->addErrorMessage(str_replace("%s", $this->priority->caption(), $this->priority->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->priority->FormValue)) {
                $this->priority->addErrorMessage($this->priority->getErrorMessage(false));
            }
            if ($this->payment_status->Visible && $this->payment_status->Required) {
                if (!$this->payment_status->IsDetailKey && EmptyValue($this->payment_status->FormValue)) {
                    $this->payment_status->addErrorMessage(str_replace("%s", $this->payment_status->caption(), $this->payment_status->RequiredErrorMessage));
                }
            }
            if ($this->payment_transaction_id->Visible && $this->payment_transaction_id->Required) {
                if (!$this->payment_transaction_id->IsDetailKey && EmptyValue($this->payment_transaction_id->FormValue)) {
                    $this->payment_transaction_id->addErrorMessage(str_replace("%s", $this->payment_transaction_id->caption(), $this->payment_transaction_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->payment_transaction_id->FormValue)) {
                $this->payment_transaction_id->addErrorMessage($this->payment_transaction_id->getErrorMessage(false));
            }
            if ($this->modified_at->Visible && $this->modified_at->Required) {
                if (!$this->modified_at->IsDetailKey && EmptyValue($this->modified_at->FormValue)) {
                    $this->modified_at->addErrorMessage(str_replace("%s", $this->modified_at->caption(), $this->modified_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->modified_at->FormValue, $this->modified_at->formatPattern())) {
                $this->modified_at->addErrorMessage($this->modified_at->getErrorMessage(false));
            }
            if ($this->ip_address->Visible && $this->ip_address->Required) {
                if (!$this->ip_address->IsDetailKey && EmptyValue($this->ip_address->FormValue)) {
                    $this->ip_address->addErrorMessage(str_replace("%s", $this->ip_address->caption(), $this->ip_address->RequiredErrorMessage));
                }
            }
            if ($this->browser_info->Visible && $this->browser_info->Required) {
                if (!$this->browser_info->IsDetailKey && EmptyValue($this->browser_info->FormValue)) {
                    $this->browser_info->addErrorMessage(str_replace("%s", $this->browser_info->caption(), $this->browser_info->RequiredErrorMessage));
                }
            }
            if ($this->device_info->Visible && $this->device_info->Required) {
                if (!$this->device_info->IsDetailKey && EmptyValue($this->device_info->FormValue)) {
                    $this->device_info->addErrorMessage(str_replace("%s", $this->device_info->caption(), $this->device_info->RequiredErrorMessage));
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

        // Check field with unique index (request_reference)
        if ($this->request_reference->CurrentValue != "") {
            $filterChk = "(\"request_reference\" = '" . AdjustSql($this->request_reference->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->request_reference->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->request_reference->CurrentValue, $idxErrMsg);
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

        // document_id
        $this->document_id->setDbValueDef($rsnew, $this->document_id->CurrentValue, $this->document_id->ReadOnly);

        // user_id
        $this->user_id->setDbValueDef($rsnew, $this->user_id->CurrentValue, $this->user_id->ReadOnly);

        // request_reference
        $this->request_reference->setDbValueDef($rsnew, $this->request_reference->CurrentValue, $this->request_reference->ReadOnly);

        // status
        $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, $this->status->ReadOnly);

        // requested_at
        $this->requested_at->setDbValueDef($rsnew, UnFormatDateTime($this->requested_at->CurrentValue, $this->requested_at->formatPattern()), $this->requested_at->ReadOnly);

        // notary_id
        $this->notary_id->setDbValueDef($rsnew, $this->notary_id->CurrentValue, $this->notary_id->ReadOnly);

        // assigned_at
        $this->assigned_at->setDbValueDef($rsnew, UnFormatDateTime($this->assigned_at->CurrentValue, $this->assigned_at->formatPattern()), $this->assigned_at->ReadOnly);

        // notarized_at
        $this->notarized_at->setDbValueDef($rsnew, UnFormatDateTime($this->notarized_at->CurrentValue, $this->notarized_at->formatPattern()), $this->notarized_at->ReadOnly);

        // rejection_reason
        $this->rejection_reason->setDbValueDef($rsnew, $this->rejection_reason->CurrentValue, $this->rejection_reason->ReadOnly);

        // rejected_at
        $this->rejected_at->setDbValueDef($rsnew, UnFormatDateTime($this->rejected_at->CurrentValue, $this->rejected_at->formatPattern()), $this->rejected_at->ReadOnly);

        // rejected_by
        $this->rejected_by->setDbValueDef($rsnew, $this->rejected_by->CurrentValue, $this->rejected_by->ReadOnly);

        // priority
        $this->priority->setDbValueDef($rsnew, $this->priority->CurrentValue, $this->priority->ReadOnly);

        // payment_status
        $this->payment_status->setDbValueDef($rsnew, $this->payment_status->CurrentValue, $this->payment_status->ReadOnly);

        // payment_transaction_id
        $this->payment_transaction_id->setDbValueDef($rsnew, $this->payment_transaction_id->CurrentValue, $this->payment_transaction_id->ReadOnly);

        // modified_at
        $this->modified_at->setDbValueDef($rsnew, UnFormatDateTime($this->modified_at->CurrentValue, $this->modified_at->formatPattern()), $this->modified_at->ReadOnly);

        // ip_address
        $this->ip_address->setDbValueDef($rsnew, $this->ip_address->CurrentValue, $this->ip_address->ReadOnly);

        // browser_info
        $this->browser_info->setDbValueDef($rsnew, $this->browser_info->CurrentValue, $this->browser_info->ReadOnly);

        // device_info
        $this->device_info->setDbValueDef($rsnew, $this->device_info->CurrentValue, $this->device_info->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['document_id'])) { // document_id
            $this->document_id->CurrentValue = $row['document_id'];
        }
        if (isset($row['user_id'])) { // user_id
            $this->user_id->CurrentValue = $row['user_id'];
        }
        if (isset($row['request_reference'])) { // request_reference
            $this->request_reference->CurrentValue = $row['request_reference'];
        }
        if (isset($row['status'])) { // status
            $this->status->CurrentValue = $row['status'];
        }
        if (isset($row['requested_at'])) { // requested_at
            $this->requested_at->CurrentValue = $row['requested_at'];
        }
        if (isset($row['notary_id'])) { // notary_id
            $this->notary_id->CurrentValue = $row['notary_id'];
        }
        if (isset($row['assigned_at'])) { // assigned_at
            $this->assigned_at->CurrentValue = $row['assigned_at'];
        }
        if (isset($row['notarized_at'])) { // notarized_at
            $this->notarized_at->CurrentValue = $row['notarized_at'];
        }
        if (isset($row['rejection_reason'])) { // rejection_reason
            $this->rejection_reason->CurrentValue = $row['rejection_reason'];
        }
        if (isset($row['rejected_at'])) { // rejected_at
            $this->rejected_at->CurrentValue = $row['rejected_at'];
        }
        if (isset($row['rejected_by'])) { // rejected_by
            $this->rejected_by->CurrentValue = $row['rejected_by'];
        }
        if (isset($row['priority'])) { // priority
            $this->priority->CurrentValue = $row['priority'];
        }
        if (isset($row['payment_status'])) { // payment_status
            $this->payment_status->CurrentValue = $row['payment_status'];
        }
        if (isset($row['payment_transaction_id'])) { // payment_transaction_id
            $this->payment_transaction_id->CurrentValue = $row['payment_transaction_id'];
        }
        if (isset($row['modified_at'])) { // modified_at
            $this->modified_at->CurrentValue = $row['modified_at'];
        }
        if (isset($row['ip_address'])) { // ip_address
            $this->ip_address->CurrentValue = $row['ip_address'];
        }
        if (isset($row['browser_info'])) { // browser_info
            $this->browser_info->CurrentValue = $row['browser_info'];
        }
        if (isset($row['device_info'])) { // device_info
            $this->device_info->CurrentValue = $row['device_info'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("NotarizationRequestsList"), "", $this->TableVar, true);
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
