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
class VerificationAttemptsEdit extends VerificationAttempts
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "VerificationAttemptsEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "VerificationAttemptsEdit";

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
        $this->attempt_id->setVisibility();
        $this->verification_id->setVisibility();
        $this->document_number->setVisibility();
        $this->keycode->setVisibility();
        $this->ip_address->setVisibility();
        $this->user_agent->setVisibility();
        $this->verification_date->setVisibility();
        $this->is_successful->setVisibility();
        $this->failure_reason->setVisibility();
        $this->location->setVisibility();
        $this->device_info->setVisibility();
        $this->browser_info->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'verification_attempts';
        $this->TableName = 'verification_attempts';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (verification_attempts)
        if (!isset($GLOBALS["verification_attempts"]) || $GLOBALS["verification_attempts"]::class == PROJECT_NAMESPACE . "verification_attempts") {
            $GLOBALS["verification_attempts"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'verification_attempts');
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
                        $result["view"] = SameString($pageName, "VerificationAttemptsView"); // If View page, no primary button
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
            $key .= @$ar['attempt_id'];
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
            $this->attempt_id->Visible = false;
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
        $this->setupLookupOptions($this->is_successful);

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
            if (($keyValue = Get("attempt_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->attempt_id->setQueryStringValue($keyValue);
                $this->attempt_id->setOldValue($this->attempt_id->QueryStringValue);
            } elseif (Post("attempt_id") !== null) {
                $this->attempt_id->setFormValue(Post("attempt_id"));
                $this->attempt_id->setOldValue($this->attempt_id->FormValue);
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
                if (($keyValue = Get("attempt_id") ?? Route("attempt_id")) !== null) {
                    $this->attempt_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->attempt_id->CurrentValue = null;
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
                        $this->terminate("VerificationAttemptsList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "VerificationAttemptsList") {
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
                        if (GetPageName($returnUrl) != "VerificationAttemptsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "VerificationAttemptsList"; // Return list page content
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

        // Check field name 'attempt_id' first before field var 'x_attempt_id'
        $val = $CurrentForm->hasValue("attempt_id") ? $CurrentForm->getValue("attempt_id") : $CurrentForm->getValue("x_attempt_id");
        if (!$this->attempt_id->IsDetailKey) {
            $this->attempt_id->setFormValue($val);
        }

        // Check field name 'verification_id' first before field var 'x_verification_id'
        $val = $CurrentForm->hasValue("verification_id") ? $CurrentForm->getValue("verification_id") : $CurrentForm->getValue("x_verification_id");
        if (!$this->verification_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->verification_id->Visible = false; // Disable update for API request
            } else {
                $this->verification_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'document_number' first before field var 'x_document_number'
        $val = $CurrentForm->hasValue("document_number") ? $CurrentForm->getValue("document_number") : $CurrentForm->getValue("x_document_number");
        if (!$this->document_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->document_number->Visible = false; // Disable update for API request
            } else {
                $this->document_number->setFormValue($val);
            }
        }

        // Check field name 'keycode' first before field var 'x_keycode'
        $val = $CurrentForm->hasValue("keycode") ? $CurrentForm->getValue("keycode") : $CurrentForm->getValue("x_keycode");
        if (!$this->keycode->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->keycode->Visible = false; // Disable update for API request
            } else {
                $this->keycode->setFormValue($val);
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

        // Check field name 'verification_date' first before field var 'x_verification_date'
        $val = $CurrentForm->hasValue("verification_date") ? $CurrentForm->getValue("verification_date") : $CurrentForm->getValue("x_verification_date");
        if (!$this->verification_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->verification_date->Visible = false; // Disable update for API request
            } else {
                $this->verification_date->setFormValue($val, true, $validate);
            }
            $this->verification_date->CurrentValue = UnFormatDateTime($this->verification_date->CurrentValue, $this->verification_date->formatPattern());
        }

        // Check field name 'is_successful' first before field var 'x_is_successful'
        $val = $CurrentForm->hasValue("is_successful") ? $CurrentForm->getValue("is_successful") : $CurrentForm->getValue("x_is_successful");
        if (!$this->is_successful->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_successful->Visible = false; // Disable update for API request
            } else {
                $this->is_successful->setFormValue($val);
            }
        }

        // Check field name 'failure_reason' first before field var 'x_failure_reason'
        $val = $CurrentForm->hasValue("failure_reason") ? $CurrentForm->getValue("failure_reason") : $CurrentForm->getValue("x_failure_reason");
        if (!$this->failure_reason->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->failure_reason->Visible = false; // Disable update for API request
            } else {
                $this->failure_reason->setFormValue($val);
            }
        }

        // Check field name 'location' first before field var 'x_location'
        $val = $CurrentForm->hasValue("location") ? $CurrentForm->getValue("location") : $CurrentForm->getValue("x_location");
        if (!$this->location->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->location->Visible = false; // Disable update for API request
            } else {
                $this->location->setFormValue($val);
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

        // Check field name 'browser_info' first before field var 'x_browser_info'
        $val = $CurrentForm->hasValue("browser_info") ? $CurrentForm->getValue("browser_info") : $CurrentForm->getValue("x_browser_info");
        if (!$this->browser_info->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->browser_info->Visible = false; // Disable update for API request
            } else {
                $this->browser_info->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->attempt_id->CurrentValue = $this->attempt_id->FormValue;
        $this->verification_id->CurrentValue = $this->verification_id->FormValue;
        $this->document_number->CurrentValue = $this->document_number->FormValue;
        $this->keycode->CurrentValue = $this->keycode->FormValue;
        $this->ip_address->CurrentValue = $this->ip_address->FormValue;
        $this->user_agent->CurrentValue = $this->user_agent->FormValue;
        $this->verification_date->CurrentValue = $this->verification_date->FormValue;
        $this->verification_date->CurrentValue = UnFormatDateTime($this->verification_date->CurrentValue, $this->verification_date->formatPattern());
        $this->is_successful->CurrentValue = $this->is_successful->FormValue;
        $this->failure_reason->CurrentValue = $this->failure_reason->FormValue;
        $this->location->CurrentValue = $this->location->FormValue;
        $this->device_info->CurrentValue = $this->device_info->FormValue;
        $this->browser_info->CurrentValue = $this->browser_info->FormValue;
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
        $this->attempt_id->setDbValue($row['attempt_id']);
        $this->verification_id->setDbValue($row['verification_id']);
        $this->document_number->setDbValue($row['document_number']);
        $this->keycode->setDbValue($row['keycode']);
        $this->ip_address->setDbValue($row['ip_address']);
        $this->user_agent->setDbValue($row['user_agent']);
        $this->verification_date->setDbValue($row['verification_date']);
        $this->is_successful->setDbValue((ConvertToBool($row['is_successful']) ? "1" : "0"));
        $this->failure_reason->setDbValue($row['failure_reason']);
        $this->location->setDbValue($row['location']);
        $this->device_info->setDbValue($row['device_info']);
        $this->browser_info->setDbValue($row['browser_info']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['attempt_id'] = $this->attempt_id->DefaultValue;
        $row['verification_id'] = $this->verification_id->DefaultValue;
        $row['document_number'] = $this->document_number->DefaultValue;
        $row['keycode'] = $this->keycode->DefaultValue;
        $row['ip_address'] = $this->ip_address->DefaultValue;
        $row['user_agent'] = $this->user_agent->DefaultValue;
        $row['verification_date'] = $this->verification_date->DefaultValue;
        $row['is_successful'] = $this->is_successful->DefaultValue;
        $row['failure_reason'] = $this->failure_reason->DefaultValue;
        $row['location'] = $this->location->DefaultValue;
        $row['device_info'] = $this->device_info->DefaultValue;
        $row['browser_info'] = $this->browser_info->DefaultValue;
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

        // attempt_id
        $this->attempt_id->RowCssClass = "row";

        // verification_id
        $this->verification_id->RowCssClass = "row";

        // document_number
        $this->document_number->RowCssClass = "row";

        // keycode
        $this->keycode->RowCssClass = "row";

        // ip_address
        $this->ip_address->RowCssClass = "row";

        // user_agent
        $this->user_agent->RowCssClass = "row";

        // verification_date
        $this->verification_date->RowCssClass = "row";

        // is_successful
        $this->is_successful->RowCssClass = "row";

        // failure_reason
        $this->failure_reason->RowCssClass = "row";

        // location
        $this->location->RowCssClass = "row";

        // device_info
        $this->device_info->RowCssClass = "row";

        // browser_info
        $this->browser_info->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // attempt_id
            $this->attempt_id->ViewValue = $this->attempt_id->CurrentValue;

            // verification_id
            $this->verification_id->ViewValue = $this->verification_id->CurrentValue;
            $this->verification_id->ViewValue = FormatNumber($this->verification_id->ViewValue, $this->verification_id->formatPattern());

            // document_number
            $this->document_number->ViewValue = $this->document_number->CurrentValue;

            // keycode
            $this->keycode->ViewValue = $this->keycode->CurrentValue;

            // ip_address
            $this->ip_address->ViewValue = $this->ip_address->CurrentValue;

            // user_agent
            $this->user_agent->ViewValue = $this->user_agent->CurrentValue;

            // verification_date
            $this->verification_date->ViewValue = $this->verification_date->CurrentValue;
            $this->verification_date->ViewValue = FormatDateTime($this->verification_date->ViewValue, $this->verification_date->formatPattern());

            // is_successful
            if (ConvertToBool($this->is_successful->CurrentValue)) {
                $this->is_successful->ViewValue = $this->is_successful->tagCaption(1) != "" ? $this->is_successful->tagCaption(1) : "Yes";
            } else {
                $this->is_successful->ViewValue = $this->is_successful->tagCaption(2) != "" ? $this->is_successful->tagCaption(2) : "No";
            }

            // failure_reason
            $this->failure_reason->ViewValue = $this->failure_reason->CurrentValue;

            // location
            $this->location->ViewValue = $this->location->CurrentValue;

            // device_info
            $this->device_info->ViewValue = $this->device_info->CurrentValue;

            // browser_info
            $this->browser_info->ViewValue = $this->browser_info->CurrentValue;

            // attempt_id
            $this->attempt_id->HrefValue = "";

            // verification_id
            $this->verification_id->HrefValue = "";

            // document_number
            $this->document_number->HrefValue = "";

            // keycode
            $this->keycode->HrefValue = "";

            // ip_address
            $this->ip_address->HrefValue = "";

            // user_agent
            $this->user_agent->HrefValue = "";

            // verification_date
            $this->verification_date->HrefValue = "";

            // is_successful
            $this->is_successful->HrefValue = "";

            // failure_reason
            $this->failure_reason->HrefValue = "";

            // location
            $this->location->HrefValue = "";

            // device_info
            $this->device_info->HrefValue = "";

            // browser_info
            $this->browser_info->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // attempt_id
            $this->attempt_id->setupEditAttributes();
            $this->attempt_id->EditValue = $this->attempt_id->CurrentValue;

            // verification_id
            $this->verification_id->setupEditAttributes();
            $this->verification_id->EditValue = $this->verification_id->CurrentValue;
            $this->verification_id->PlaceHolder = RemoveHtml($this->verification_id->caption());
            if (strval($this->verification_id->EditValue) != "" && is_numeric($this->verification_id->EditValue)) {
                $this->verification_id->EditValue = FormatNumber($this->verification_id->EditValue, null);
            }

            // document_number
            $this->document_number->setupEditAttributes();
            if (!$this->document_number->Raw) {
                $this->document_number->CurrentValue = HtmlDecode($this->document_number->CurrentValue);
            }
            $this->document_number->EditValue = HtmlEncode($this->document_number->CurrentValue);
            $this->document_number->PlaceHolder = RemoveHtml($this->document_number->caption());

            // keycode
            $this->keycode->setupEditAttributes();
            if (!$this->keycode->Raw) {
                $this->keycode->CurrentValue = HtmlDecode($this->keycode->CurrentValue);
            }
            $this->keycode->EditValue = HtmlEncode($this->keycode->CurrentValue);
            $this->keycode->PlaceHolder = RemoveHtml($this->keycode->caption());

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

            // verification_date
            $this->verification_date->setupEditAttributes();
            $this->verification_date->EditValue = HtmlEncode(FormatDateTime($this->verification_date->CurrentValue, $this->verification_date->formatPattern()));
            $this->verification_date->PlaceHolder = RemoveHtml($this->verification_date->caption());

            // is_successful
            $this->is_successful->EditValue = $this->is_successful->options(false);
            $this->is_successful->PlaceHolder = RemoveHtml($this->is_successful->caption());

            // failure_reason
            $this->failure_reason->setupEditAttributes();
            $this->failure_reason->EditValue = HtmlEncode($this->failure_reason->CurrentValue);
            $this->failure_reason->PlaceHolder = RemoveHtml($this->failure_reason->caption());

            // location
            $this->location->setupEditAttributes();
            if (!$this->location->Raw) {
                $this->location->CurrentValue = HtmlDecode($this->location->CurrentValue);
            }
            $this->location->EditValue = HtmlEncode($this->location->CurrentValue);
            $this->location->PlaceHolder = RemoveHtml($this->location->caption());

            // device_info
            $this->device_info->setupEditAttributes();
            $this->device_info->EditValue = HtmlEncode($this->device_info->CurrentValue);
            $this->device_info->PlaceHolder = RemoveHtml($this->device_info->caption());

            // browser_info
            $this->browser_info->setupEditAttributes();
            $this->browser_info->EditValue = HtmlEncode($this->browser_info->CurrentValue);
            $this->browser_info->PlaceHolder = RemoveHtml($this->browser_info->caption());

            // Edit refer script

            // attempt_id
            $this->attempt_id->HrefValue = "";

            // verification_id
            $this->verification_id->HrefValue = "";

            // document_number
            $this->document_number->HrefValue = "";

            // keycode
            $this->keycode->HrefValue = "";

            // ip_address
            $this->ip_address->HrefValue = "";

            // user_agent
            $this->user_agent->HrefValue = "";

            // verification_date
            $this->verification_date->HrefValue = "";

            // is_successful
            $this->is_successful->HrefValue = "";

            // failure_reason
            $this->failure_reason->HrefValue = "";

            // location
            $this->location->HrefValue = "";

            // device_info
            $this->device_info->HrefValue = "";

            // browser_info
            $this->browser_info->HrefValue = "";
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
            if ($this->attempt_id->Visible && $this->attempt_id->Required) {
                if (!$this->attempt_id->IsDetailKey && EmptyValue($this->attempt_id->FormValue)) {
                    $this->attempt_id->addErrorMessage(str_replace("%s", $this->attempt_id->caption(), $this->attempt_id->RequiredErrorMessage));
                }
            }
            if ($this->verification_id->Visible && $this->verification_id->Required) {
                if (!$this->verification_id->IsDetailKey && EmptyValue($this->verification_id->FormValue)) {
                    $this->verification_id->addErrorMessage(str_replace("%s", $this->verification_id->caption(), $this->verification_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->verification_id->FormValue)) {
                $this->verification_id->addErrorMessage($this->verification_id->getErrorMessage(false));
            }
            if ($this->document_number->Visible && $this->document_number->Required) {
                if (!$this->document_number->IsDetailKey && EmptyValue($this->document_number->FormValue)) {
                    $this->document_number->addErrorMessage(str_replace("%s", $this->document_number->caption(), $this->document_number->RequiredErrorMessage));
                }
            }
            if ($this->keycode->Visible && $this->keycode->Required) {
                if (!$this->keycode->IsDetailKey && EmptyValue($this->keycode->FormValue)) {
                    $this->keycode->addErrorMessage(str_replace("%s", $this->keycode->caption(), $this->keycode->RequiredErrorMessage));
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
            if ($this->verification_date->Visible && $this->verification_date->Required) {
                if (!$this->verification_date->IsDetailKey && EmptyValue($this->verification_date->FormValue)) {
                    $this->verification_date->addErrorMessage(str_replace("%s", $this->verification_date->caption(), $this->verification_date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->verification_date->FormValue, $this->verification_date->formatPattern())) {
                $this->verification_date->addErrorMessage($this->verification_date->getErrorMessage(false));
            }
            if ($this->is_successful->Visible && $this->is_successful->Required) {
                if ($this->is_successful->FormValue == "") {
                    $this->is_successful->addErrorMessage(str_replace("%s", $this->is_successful->caption(), $this->is_successful->RequiredErrorMessage));
                }
            }
            if ($this->failure_reason->Visible && $this->failure_reason->Required) {
                if (!$this->failure_reason->IsDetailKey && EmptyValue($this->failure_reason->FormValue)) {
                    $this->failure_reason->addErrorMessage(str_replace("%s", $this->failure_reason->caption(), $this->failure_reason->RequiredErrorMessage));
                }
            }
            if ($this->location->Visible && $this->location->Required) {
                if (!$this->location->IsDetailKey && EmptyValue($this->location->FormValue)) {
                    $this->location->addErrorMessage(str_replace("%s", $this->location->caption(), $this->location->RequiredErrorMessage));
                }
            }
            if ($this->device_info->Visible && $this->device_info->Required) {
                if (!$this->device_info->IsDetailKey && EmptyValue($this->device_info->FormValue)) {
                    $this->device_info->addErrorMessage(str_replace("%s", $this->device_info->caption(), $this->device_info->RequiredErrorMessage));
                }
            }
            if ($this->browser_info->Visible && $this->browser_info->Required) {
                if (!$this->browser_info->IsDetailKey && EmptyValue($this->browser_info->FormValue)) {
                    $this->browser_info->addErrorMessage(str_replace("%s", $this->browser_info->caption(), $this->browser_info->RequiredErrorMessage));
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

        // verification_id
        $this->verification_id->setDbValueDef($rsnew, $this->verification_id->CurrentValue, $this->verification_id->ReadOnly);

        // document_number
        $this->document_number->setDbValueDef($rsnew, $this->document_number->CurrentValue, $this->document_number->ReadOnly);

        // keycode
        $this->keycode->setDbValueDef($rsnew, $this->keycode->CurrentValue, $this->keycode->ReadOnly);

        // ip_address
        $this->ip_address->setDbValueDef($rsnew, $this->ip_address->CurrentValue, $this->ip_address->ReadOnly);

        // user_agent
        $this->user_agent->setDbValueDef($rsnew, $this->user_agent->CurrentValue, $this->user_agent->ReadOnly);

        // verification_date
        $this->verification_date->setDbValueDef($rsnew, UnFormatDateTime($this->verification_date->CurrentValue, $this->verification_date->formatPattern()), $this->verification_date->ReadOnly);

        // is_successful
        $tmpBool = $this->is_successful->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_successful->setDbValueDef($rsnew, $tmpBool, $this->is_successful->ReadOnly);

        // failure_reason
        $this->failure_reason->setDbValueDef($rsnew, $this->failure_reason->CurrentValue, $this->failure_reason->ReadOnly);

        // location
        $this->location->setDbValueDef($rsnew, $this->location->CurrentValue, $this->location->ReadOnly);

        // device_info
        $this->device_info->setDbValueDef($rsnew, $this->device_info->CurrentValue, $this->device_info->ReadOnly);

        // browser_info
        $this->browser_info->setDbValueDef($rsnew, $this->browser_info->CurrentValue, $this->browser_info->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['verification_id'])) { // verification_id
            $this->verification_id->CurrentValue = $row['verification_id'];
        }
        if (isset($row['document_number'])) { // document_number
            $this->document_number->CurrentValue = $row['document_number'];
        }
        if (isset($row['keycode'])) { // keycode
            $this->keycode->CurrentValue = $row['keycode'];
        }
        if (isset($row['ip_address'])) { // ip_address
            $this->ip_address->CurrentValue = $row['ip_address'];
        }
        if (isset($row['user_agent'])) { // user_agent
            $this->user_agent->CurrentValue = $row['user_agent'];
        }
        if (isset($row['verification_date'])) { // verification_date
            $this->verification_date->CurrentValue = $row['verification_date'];
        }
        if (isset($row['is_successful'])) { // is_successful
            $this->is_successful->CurrentValue = $row['is_successful'];
        }
        if (isset($row['failure_reason'])) { // failure_reason
            $this->failure_reason->CurrentValue = $row['failure_reason'];
        }
        if (isset($row['location'])) { // location
            $this->location->CurrentValue = $row['location'];
        }
        if (isset($row['device_info'])) { // device_info
            $this->device_info->CurrentValue = $row['device_info'];
        }
        if (isset($row['browser_info'])) { // browser_info
            $this->browser_info->CurrentValue = $row['browser_info'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("VerificationAttemptsList"), "", $this->TableVar, true);
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
                case "x_is_successful":
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
