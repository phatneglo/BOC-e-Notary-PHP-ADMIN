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
class SupportRequestsEdit extends SupportRequests
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "SupportRequestsEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "SupportRequestsEdit";

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
        $this->user_id->setVisibility();
        $this->name->setVisibility();
        $this->_email->setVisibility();
        $this->subject->setVisibility();
        $this->_message->setVisibility();
        $this->request_type->setVisibility();
        $this->reference_number->setVisibility();
        $this->status->setVisibility();
        $this->created_at->setVisibility();
        $this->updated_at->setVisibility();
        $this->assigned_to->setVisibility();
        $this->resolved_at->setVisibility();
        $this->_response->setVisibility();
        $this->ip_address->setVisibility();
        $this->user_agent->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'support_requests';
        $this->TableName = 'support_requests';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (support_requests)
        if (!isset($GLOBALS["support_requests"]) || $GLOBALS["support_requests"]::class == PROJECT_NAMESPACE . "support_requests") {
            $GLOBALS["support_requests"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'support_requests');
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
                        $result["view"] = SameString($pageName, "SupportRequestsView"); // If View page, no primary button
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
                        $this->terminate("SupportRequestsList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "SupportRequestsList") {
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
                        if (GetPageName($returnUrl) != "SupportRequestsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "SupportRequestsList"; // Return list page content
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

        // Check field name 'user_id' first before field var 'x_user_id'
        $val = $CurrentForm->hasValue("user_id") ? $CurrentForm->getValue("user_id") : $CurrentForm->getValue("x_user_id");
        if (!$this->user_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->user_id->Visible = false; // Disable update for API request
            } else {
                $this->user_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'name' first before field var 'x_name'
        $val = $CurrentForm->hasValue("name") ? $CurrentForm->getValue("name") : $CurrentForm->getValue("x_name");
        if (!$this->name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->name->Visible = false; // Disable update for API request
            } else {
                $this->name->setFormValue($val);
            }
        }

        // Check field name 'email' first before field var 'x__email'
        $val = $CurrentForm->hasValue("email") ? $CurrentForm->getValue("email") : $CurrentForm->getValue("x__email");
        if (!$this->_email->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_email->Visible = false; // Disable update for API request
            } else {
                $this->_email->setFormValue($val);
            }
        }

        // Check field name 'subject' first before field var 'x_subject'
        $val = $CurrentForm->hasValue("subject") ? $CurrentForm->getValue("subject") : $CurrentForm->getValue("x_subject");
        if (!$this->subject->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->subject->Visible = false; // Disable update for API request
            } else {
                $this->subject->setFormValue($val);
            }
        }

        // Check field name 'message' first before field var 'x__message'
        $val = $CurrentForm->hasValue("message") ? $CurrentForm->getValue("message") : $CurrentForm->getValue("x__message");
        if (!$this->_message->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_message->Visible = false; // Disable update for API request
            } else {
                $this->_message->setFormValue($val);
            }
        }

        // Check field name 'request_type' first before field var 'x_request_type'
        $val = $CurrentForm->hasValue("request_type") ? $CurrentForm->getValue("request_type") : $CurrentForm->getValue("x_request_type");
        if (!$this->request_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->request_type->Visible = false; // Disable update for API request
            } else {
                $this->request_type->setFormValue($val);
            }
        }

        // Check field name 'reference_number' first before field var 'x_reference_number'
        $val = $CurrentForm->hasValue("reference_number") ? $CurrentForm->getValue("reference_number") : $CurrentForm->getValue("x_reference_number");
        if (!$this->reference_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reference_number->Visible = false; // Disable update for API request
            } else {
                $this->reference_number->setFormValue($val);
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

        // Check field name 'assigned_to' first before field var 'x_assigned_to'
        $val = $CurrentForm->hasValue("assigned_to") ? $CurrentForm->getValue("assigned_to") : $CurrentForm->getValue("x_assigned_to");
        if (!$this->assigned_to->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->assigned_to->Visible = false; // Disable update for API request
            } else {
                $this->assigned_to->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'resolved_at' first before field var 'x_resolved_at'
        $val = $CurrentForm->hasValue("resolved_at") ? $CurrentForm->getValue("resolved_at") : $CurrentForm->getValue("x_resolved_at");
        if (!$this->resolved_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->resolved_at->Visible = false; // Disable update for API request
            } else {
                $this->resolved_at->setFormValue($val, true, $validate);
            }
            $this->resolved_at->CurrentValue = UnFormatDateTime($this->resolved_at->CurrentValue, $this->resolved_at->formatPattern());
        }

        // Check field name 'response' first before field var 'x__response'
        $val = $CurrentForm->hasValue("response") ? $CurrentForm->getValue("response") : $CurrentForm->getValue("x__response");
        if (!$this->_response->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_response->Visible = false; // Disable update for API request
            } else {
                $this->_response->setFormValue($val);
            }
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

        // Check field name 'user_agent' first before field var 'x_user_agent'
        $val = $CurrentForm->hasValue("user_agent") ? $CurrentForm->getValue("user_agent") : $CurrentForm->getValue("x_user_agent");
        if (!$this->user_agent->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->user_agent->Visible = false; // Disable update for API request
            } else {
                $this->user_agent->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->request_id->CurrentValue = $this->request_id->FormValue;
        $this->user_id->CurrentValue = $this->user_id->FormValue;
        $this->name->CurrentValue = $this->name->FormValue;
        $this->_email->CurrentValue = $this->_email->FormValue;
        $this->subject->CurrentValue = $this->subject->FormValue;
        $this->_message->CurrentValue = $this->_message->FormValue;
        $this->request_type->CurrentValue = $this->request_type->FormValue;
        $this->reference_number->CurrentValue = $this->reference_number->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->updated_at->CurrentValue = $this->updated_at->FormValue;
        $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->assigned_to->CurrentValue = $this->assigned_to->FormValue;
        $this->resolved_at->CurrentValue = $this->resolved_at->FormValue;
        $this->resolved_at->CurrentValue = UnFormatDateTime($this->resolved_at->CurrentValue, $this->resolved_at->formatPattern());
        $this->_response->CurrentValue = $this->_response->FormValue;
        $this->ip_address->CurrentValue = $this->ip_address->FormValue;
        $this->user_agent->CurrentValue = $this->user_agent->FormValue;
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
        $this->user_id->setDbValue($row['user_id']);
        $this->name->setDbValue($row['name']);
        $this->_email->setDbValue($row['email']);
        $this->subject->setDbValue($row['subject']);
        $this->_message->setDbValue($row['message']);
        $this->request_type->setDbValue($row['request_type']);
        $this->reference_number->setDbValue($row['reference_number']);
        $this->status->setDbValue($row['status']);
        $this->created_at->setDbValue($row['created_at']);
        $this->updated_at->setDbValue($row['updated_at']);
        $this->assigned_to->setDbValue($row['assigned_to']);
        $this->resolved_at->setDbValue($row['resolved_at']);
        $this->_response->setDbValue($row['response']);
        $this->ip_address->setDbValue($row['ip_address']);
        $this->user_agent->setDbValue($row['user_agent']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['request_id'] = $this->request_id->DefaultValue;
        $row['user_id'] = $this->user_id->DefaultValue;
        $row['name'] = $this->name->DefaultValue;
        $row['email'] = $this->_email->DefaultValue;
        $row['subject'] = $this->subject->DefaultValue;
        $row['message'] = $this->_message->DefaultValue;
        $row['request_type'] = $this->request_type->DefaultValue;
        $row['reference_number'] = $this->reference_number->DefaultValue;
        $row['status'] = $this->status->DefaultValue;
        $row['created_at'] = $this->created_at->DefaultValue;
        $row['updated_at'] = $this->updated_at->DefaultValue;
        $row['assigned_to'] = $this->assigned_to->DefaultValue;
        $row['resolved_at'] = $this->resolved_at->DefaultValue;
        $row['response'] = $this->_response->DefaultValue;
        $row['ip_address'] = $this->ip_address->DefaultValue;
        $row['user_agent'] = $this->user_agent->DefaultValue;
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

        // user_id
        $this->user_id->RowCssClass = "row";

        // name
        $this->name->RowCssClass = "row";

        // email
        $this->_email->RowCssClass = "row";

        // subject
        $this->subject->RowCssClass = "row";

        // message
        $this->_message->RowCssClass = "row";

        // request_type
        $this->request_type->RowCssClass = "row";

        // reference_number
        $this->reference_number->RowCssClass = "row";

        // status
        $this->status->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // updated_at
        $this->updated_at->RowCssClass = "row";

        // assigned_to
        $this->assigned_to->RowCssClass = "row";

        // resolved_at
        $this->resolved_at->RowCssClass = "row";

        // response
        $this->_response->RowCssClass = "row";

        // ip_address
        $this->ip_address->RowCssClass = "row";

        // user_agent
        $this->user_agent->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // request_id
            $this->request_id->ViewValue = $this->request_id->CurrentValue;

            // user_id
            $this->user_id->ViewValue = $this->user_id->CurrentValue;
            $this->user_id->ViewValue = FormatNumber($this->user_id->ViewValue, $this->user_id->formatPattern());

            // name
            $this->name->ViewValue = $this->name->CurrentValue;

            // email
            $this->_email->ViewValue = $this->_email->CurrentValue;

            // subject
            $this->subject->ViewValue = $this->subject->CurrentValue;

            // message
            $this->_message->ViewValue = $this->_message->CurrentValue;

            // request_type
            $this->request_type->ViewValue = $this->request_type->CurrentValue;

            // reference_number
            $this->reference_number->ViewValue = $this->reference_number->CurrentValue;

            // status
            $this->status->ViewValue = $this->status->CurrentValue;

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // updated_at
            $this->updated_at->ViewValue = $this->updated_at->CurrentValue;
            $this->updated_at->ViewValue = FormatDateTime($this->updated_at->ViewValue, $this->updated_at->formatPattern());

            // assigned_to
            $this->assigned_to->ViewValue = $this->assigned_to->CurrentValue;
            $this->assigned_to->ViewValue = FormatNumber($this->assigned_to->ViewValue, $this->assigned_to->formatPattern());

            // resolved_at
            $this->resolved_at->ViewValue = $this->resolved_at->CurrentValue;
            $this->resolved_at->ViewValue = FormatDateTime($this->resolved_at->ViewValue, $this->resolved_at->formatPattern());

            // response
            $this->_response->ViewValue = $this->_response->CurrentValue;

            // ip_address
            $this->ip_address->ViewValue = $this->ip_address->CurrentValue;

            // user_agent
            $this->user_agent->ViewValue = $this->user_agent->CurrentValue;

            // request_id
            $this->request_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // name
            $this->name->HrefValue = "";

            // email
            $this->_email->HrefValue = "";

            // subject
            $this->subject->HrefValue = "";

            // message
            $this->_message->HrefValue = "";

            // request_type
            $this->request_type->HrefValue = "";

            // reference_number
            $this->reference_number->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // assigned_to
            $this->assigned_to->HrefValue = "";

            // resolved_at
            $this->resolved_at->HrefValue = "";

            // response
            $this->_response->HrefValue = "";

            // ip_address
            $this->ip_address->HrefValue = "";

            // user_agent
            $this->user_agent->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // request_id
            $this->request_id->setupEditAttributes();
            $this->request_id->EditValue = $this->request_id->CurrentValue;

            // user_id
            $this->user_id->setupEditAttributes();
            $this->user_id->EditValue = $this->user_id->CurrentValue;
            $this->user_id->PlaceHolder = RemoveHtml($this->user_id->caption());
            if (strval($this->user_id->EditValue) != "" && is_numeric($this->user_id->EditValue)) {
                $this->user_id->EditValue = FormatNumber($this->user_id->EditValue, null);
            }

            // name
            $this->name->setupEditAttributes();
            if (!$this->name->Raw) {
                $this->name->CurrentValue = HtmlDecode($this->name->CurrentValue);
            }
            $this->name->EditValue = HtmlEncode($this->name->CurrentValue);
            $this->name->PlaceHolder = RemoveHtml($this->name->caption());

            // email
            $this->_email->setupEditAttributes();
            if (!$this->_email->Raw) {
                $this->_email->CurrentValue = HtmlDecode($this->_email->CurrentValue);
            }
            $this->_email->EditValue = HtmlEncode($this->_email->CurrentValue);
            $this->_email->PlaceHolder = RemoveHtml($this->_email->caption());

            // subject
            $this->subject->setupEditAttributes();
            if (!$this->subject->Raw) {
                $this->subject->CurrentValue = HtmlDecode($this->subject->CurrentValue);
            }
            $this->subject->EditValue = HtmlEncode($this->subject->CurrentValue);
            $this->subject->PlaceHolder = RemoveHtml($this->subject->caption());

            // message
            $this->_message->setupEditAttributes();
            $this->_message->EditValue = HtmlEncode($this->_message->CurrentValue);
            $this->_message->PlaceHolder = RemoveHtml($this->_message->caption());

            // request_type
            $this->request_type->setupEditAttributes();
            if (!$this->request_type->Raw) {
                $this->request_type->CurrentValue = HtmlDecode($this->request_type->CurrentValue);
            }
            $this->request_type->EditValue = HtmlEncode($this->request_type->CurrentValue);
            $this->request_type->PlaceHolder = RemoveHtml($this->request_type->caption());

            // reference_number
            $this->reference_number->setupEditAttributes();
            if (!$this->reference_number->Raw) {
                $this->reference_number->CurrentValue = HtmlDecode($this->reference_number->CurrentValue);
            }
            $this->reference_number->EditValue = HtmlEncode($this->reference_number->CurrentValue);
            $this->reference_number->PlaceHolder = RemoveHtml($this->reference_number->caption());

            // status
            $this->status->setupEditAttributes();
            if (!$this->status->Raw) {
                $this->status->CurrentValue = HtmlDecode($this->status->CurrentValue);
            }
            $this->status->EditValue = HtmlEncode($this->status->CurrentValue);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // created_at
            $this->created_at->setupEditAttributes();
            $this->created_at->EditValue = HtmlEncode(FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()));
            $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

            // updated_at
            $this->updated_at->setupEditAttributes();
            $this->updated_at->EditValue = HtmlEncode(FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()));
            $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

            // assigned_to
            $this->assigned_to->setupEditAttributes();
            $this->assigned_to->EditValue = $this->assigned_to->CurrentValue;
            $this->assigned_to->PlaceHolder = RemoveHtml($this->assigned_to->caption());
            if (strval($this->assigned_to->EditValue) != "" && is_numeric($this->assigned_to->EditValue)) {
                $this->assigned_to->EditValue = FormatNumber($this->assigned_to->EditValue, null);
            }

            // resolved_at
            $this->resolved_at->setupEditAttributes();
            $this->resolved_at->EditValue = HtmlEncode(FormatDateTime($this->resolved_at->CurrentValue, $this->resolved_at->formatPattern()));
            $this->resolved_at->PlaceHolder = RemoveHtml($this->resolved_at->caption());

            // response
            $this->_response->setupEditAttributes();
            $this->_response->EditValue = HtmlEncode($this->_response->CurrentValue);
            $this->_response->PlaceHolder = RemoveHtml($this->_response->caption());

            // ip_address
            $this->ip_address->setupEditAttributes();
            if (!$this->ip_address->Raw) {
                $this->ip_address->CurrentValue = HtmlDecode($this->ip_address->CurrentValue);
            }
            $this->ip_address->EditValue = HtmlEncode($this->ip_address->CurrentValue);
            $this->ip_address->PlaceHolder = RemoveHtml($this->ip_address->caption());

            // user_agent
            $this->user_agent->setupEditAttributes();
            $this->user_agent->EditValue = HtmlEncode($this->user_agent->CurrentValue);
            $this->user_agent->PlaceHolder = RemoveHtml($this->user_agent->caption());

            // Edit refer script

            // request_id
            $this->request_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // name
            $this->name->HrefValue = "";

            // email
            $this->_email->HrefValue = "";

            // subject
            $this->subject->HrefValue = "";

            // message
            $this->_message->HrefValue = "";

            // request_type
            $this->request_type->HrefValue = "";

            // reference_number
            $this->reference_number->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // assigned_to
            $this->assigned_to->HrefValue = "";

            // resolved_at
            $this->resolved_at->HrefValue = "";

            // response
            $this->_response->HrefValue = "";

            // ip_address
            $this->ip_address->HrefValue = "";

            // user_agent
            $this->user_agent->HrefValue = "";
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
            if ($this->user_id->Visible && $this->user_id->Required) {
                if (!$this->user_id->IsDetailKey && EmptyValue($this->user_id->FormValue)) {
                    $this->user_id->addErrorMessage(str_replace("%s", $this->user_id->caption(), $this->user_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->user_id->FormValue)) {
                $this->user_id->addErrorMessage($this->user_id->getErrorMessage(false));
            }
            if ($this->name->Visible && $this->name->Required) {
                if (!$this->name->IsDetailKey && EmptyValue($this->name->FormValue)) {
                    $this->name->addErrorMessage(str_replace("%s", $this->name->caption(), $this->name->RequiredErrorMessage));
                }
            }
            if ($this->_email->Visible && $this->_email->Required) {
                if (!$this->_email->IsDetailKey && EmptyValue($this->_email->FormValue)) {
                    $this->_email->addErrorMessage(str_replace("%s", $this->_email->caption(), $this->_email->RequiredErrorMessage));
                }
            }
            if ($this->subject->Visible && $this->subject->Required) {
                if (!$this->subject->IsDetailKey && EmptyValue($this->subject->FormValue)) {
                    $this->subject->addErrorMessage(str_replace("%s", $this->subject->caption(), $this->subject->RequiredErrorMessage));
                }
            }
            if ($this->_message->Visible && $this->_message->Required) {
                if (!$this->_message->IsDetailKey && EmptyValue($this->_message->FormValue)) {
                    $this->_message->addErrorMessage(str_replace("%s", $this->_message->caption(), $this->_message->RequiredErrorMessage));
                }
            }
            if ($this->request_type->Visible && $this->request_type->Required) {
                if (!$this->request_type->IsDetailKey && EmptyValue($this->request_type->FormValue)) {
                    $this->request_type->addErrorMessage(str_replace("%s", $this->request_type->caption(), $this->request_type->RequiredErrorMessage));
                }
            }
            if ($this->reference_number->Visible && $this->reference_number->Required) {
                if (!$this->reference_number->IsDetailKey && EmptyValue($this->reference_number->FormValue)) {
                    $this->reference_number->addErrorMessage(str_replace("%s", $this->reference_number->caption(), $this->reference_number->RequiredErrorMessage));
                }
            }
            if ($this->status->Visible && $this->status->Required) {
                if (!$this->status->IsDetailKey && EmptyValue($this->status->FormValue)) {
                    $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
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
            if ($this->updated_at->Visible && $this->updated_at->Required) {
                if (!$this->updated_at->IsDetailKey && EmptyValue($this->updated_at->FormValue)) {
                    $this->updated_at->addErrorMessage(str_replace("%s", $this->updated_at->caption(), $this->updated_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->updated_at->FormValue, $this->updated_at->formatPattern())) {
                $this->updated_at->addErrorMessage($this->updated_at->getErrorMessage(false));
            }
            if ($this->assigned_to->Visible && $this->assigned_to->Required) {
                if (!$this->assigned_to->IsDetailKey && EmptyValue($this->assigned_to->FormValue)) {
                    $this->assigned_to->addErrorMessage(str_replace("%s", $this->assigned_to->caption(), $this->assigned_to->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->assigned_to->FormValue)) {
                $this->assigned_to->addErrorMessage($this->assigned_to->getErrorMessage(false));
            }
            if ($this->resolved_at->Visible && $this->resolved_at->Required) {
                if (!$this->resolved_at->IsDetailKey && EmptyValue($this->resolved_at->FormValue)) {
                    $this->resolved_at->addErrorMessage(str_replace("%s", $this->resolved_at->caption(), $this->resolved_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->resolved_at->FormValue, $this->resolved_at->formatPattern())) {
                $this->resolved_at->addErrorMessage($this->resolved_at->getErrorMessage(false));
            }
            if ($this->_response->Visible && $this->_response->Required) {
                if (!$this->_response->IsDetailKey && EmptyValue($this->_response->FormValue)) {
                    $this->_response->addErrorMessage(str_replace("%s", $this->_response->caption(), $this->_response->RequiredErrorMessage));
                }
            }
            if ($this->ip_address->Visible && $this->ip_address->Required) {
                if (!$this->ip_address->IsDetailKey && EmptyValue($this->ip_address->FormValue)) {
                    $this->ip_address->addErrorMessage(str_replace("%s", $this->ip_address->caption(), $this->ip_address->RequiredErrorMessage));
                }
            }
            if ($this->user_agent->Visible && $this->user_agent->Required) {
                if (!$this->user_agent->IsDetailKey && EmptyValue($this->user_agent->FormValue)) {
                    $this->user_agent->addErrorMessage(str_replace("%s", $this->user_agent->caption(), $this->user_agent->RequiredErrorMessage));
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

        // Check field with unique index (reference_number)
        if ($this->reference_number->CurrentValue != "") {
            $filterChk = "(\"reference_number\" = '" . AdjustSql($this->reference_number->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->reference_number->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->reference_number->CurrentValue, $idxErrMsg);
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

        // user_id
        $this->user_id->setDbValueDef($rsnew, $this->user_id->CurrentValue, $this->user_id->ReadOnly);

        // name
        $this->name->setDbValueDef($rsnew, $this->name->CurrentValue, $this->name->ReadOnly);

        // email
        $this->_email->setDbValueDef($rsnew, $this->_email->CurrentValue, $this->_email->ReadOnly);

        // subject
        $this->subject->setDbValueDef($rsnew, $this->subject->CurrentValue, $this->subject->ReadOnly);

        // message
        $this->_message->setDbValueDef($rsnew, $this->_message->CurrentValue, $this->_message->ReadOnly);

        // request_type
        $this->request_type->setDbValueDef($rsnew, $this->request_type->CurrentValue, $this->request_type->ReadOnly);

        // reference_number
        $this->reference_number->setDbValueDef($rsnew, $this->reference_number->CurrentValue, $this->reference_number->ReadOnly);

        // status
        $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, $this->status->ReadOnly);

        // created_at
        $this->created_at->setDbValueDef($rsnew, UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()), $this->created_at->ReadOnly);

        // updated_at
        $this->updated_at->setDbValueDef($rsnew, UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()), $this->updated_at->ReadOnly);

        // assigned_to
        $this->assigned_to->setDbValueDef($rsnew, $this->assigned_to->CurrentValue, $this->assigned_to->ReadOnly);

        // resolved_at
        $this->resolved_at->setDbValueDef($rsnew, UnFormatDateTime($this->resolved_at->CurrentValue, $this->resolved_at->formatPattern()), $this->resolved_at->ReadOnly);

        // response
        $this->_response->setDbValueDef($rsnew, $this->_response->CurrentValue, $this->_response->ReadOnly);

        // ip_address
        $this->ip_address->setDbValueDef($rsnew, $this->ip_address->CurrentValue, $this->ip_address->ReadOnly);

        // user_agent
        $this->user_agent->setDbValueDef($rsnew, $this->user_agent->CurrentValue, $this->user_agent->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['user_id'])) { // user_id
            $this->user_id->CurrentValue = $row['user_id'];
        }
        if (isset($row['name'])) { // name
            $this->name->CurrentValue = $row['name'];
        }
        if (isset($row['email'])) { // email
            $this->_email->CurrentValue = $row['email'];
        }
        if (isset($row['subject'])) { // subject
            $this->subject->CurrentValue = $row['subject'];
        }
        if (isset($row['message'])) { // message
            $this->_message->CurrentValue = $row['message'];
        }
        if (isset($row['request_type'])) { // request_type
            $this->request_type->CurrentValue = $row['request_type'];
        }
        if (isset($row['reference_number'])) { // reference_number
            $this->reference_number->CurrentValue = $row['reference_number'];
        }
        if (isset($row['status'])) { // status
            $this->status->CurrentValue = $row['status'];
        }
        if (isset($row['created_at'])) { // created_at
            $this->created_at->CurrentValue = $row['created_at'];
        }
        if (isset($row['updated_at'])) { // updated_at
            $this->updated_at->CurrentValue = $row['updated_at'];
        }
        if (isset($row['assigned_to'])) { // assigned_to
            $this->assigned_to->CurrentValue = $row['assigned_to'];
        }
        if (isset($row['resolved_at'])) { // resolved_at
            $this->resolved_at->CurrentValue = $row['resolved_at'];
        }
        if (isset($row['response'])) { // response
            $this->_response->CurrentValue = $row['response'];
        }
        if (isset($row['ip_address'])) { // ip_address
            $this->ip_address->CurrentValue = $row['ip_address'];
        }
        if (isset($row['user_agent'])) { // user_agent
            $this->user_agent->CurrentValue = $row['user_agent'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("SupportRequestsList"), "", $this->TableVar, true);
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
