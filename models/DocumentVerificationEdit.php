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
class DocumentVerificationEdit extends DocumentVerification
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "DocumentVerificationEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "DocumentVerificationEdit";

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
        $this->verification_id->setVisibility();
        $this->notarized_id->setVisibility();
        $this->document_number->setVisibility();
        $this->keycode->setVisibility();
        $this->verification_url->setVisibility();
        $this->qr_code_path->setVisibility();
        $this->is_active->setVisibility();
        $this->expiry_date->setVisibility();
        $this->created_at->setVisibility();
        $this->failed_attempts->setVisibility();
        $this->blocked_until->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'document_verification';
        $this->TableName = 'document_verification';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (document_verification)
        if (!isset($GLOBALS["document_verification"]) || $GLOBALS["document_verification"]::class == PROJECT_NAMESPACE . "document_verification") {
            $GLOBALS["document_verification"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'document_verification');
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
                        $result["view"] = SameString($pageName, "DocumentVerificationView"); // If View page, no primary button
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
            $key .= @$ar['verification_id'];
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
            $this->verification_id->Visible = false;
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
            if (($keyValue = Get("verification_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->verification_id->setQueryStringValue($keyValue);
                $this->verification_id->setOldValue($this->verification_id->QueryStringValue);
            } elseif (Post("verification_id") !== null) {
                $this->verification_id->setFormValue(Post("verification_id"));
                $this->verification_id->setOldValue($this->verification_id->FormValue);
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
                if (($keyValue = Get("verification_id") ?? Route("verification_id")) !== null) {
                    $this->verification_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->verification_id->CurrentValue = null;
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
                        $this->terminate("DocumentVerificationList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "DocumentVerificationList") {
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
                        if (GetPageName($returnUrl) != "DocumentVerificationList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "DocumentVerificationList"; // Return list page content
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

        // Check field name 'verification_id' first before field var 'x_verification_id'
        $val = $CurrentForm->hasValue("verification_id") ? $CurrentForm->getValue("verification_id") : $CurrentForm->getValue("x_verification_id");
        if (!$this->verification_id->IsDetailKey) {
            $this->verification_id->setFormValue($val);
        }

        // Check field name 'notarized_id' first before field var 'x_notarized_id'
        $val = $CurrentForm->hasValue("notarized_id") ? $CurrentForm->getValue("notarized_id") : $CurrentForm->getValue("x_notarized_id");
        if (!$this->notarized_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notarized_id->Visible = false; // Disable update for API request
            } else {
                $this->notarized_id->setFormValue($val, true, $validate);
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

        // Check field name 'verification_url' first before field var 'x_verification_url'
        $val = $CurrentForm->hasValue("verification_url") ? $CurrentForm->getValue("verification_url") : $CurrentForm->getValue("x_verification_url");
        if (!$this->verification_url->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->verification_url->Visible = false; // Disable update for API request
            } else {
                $this->verification_url->setFormValue($val);
            }
        }

        // Check field name 'qr_code_path' first before field var 'x_qr_code_path'
        $val = $CurrentForm->hasValue("qr_code_path") ? $CurrentForm->getValue("qr_code_path") : $CurrentForm->getValue("x_qr_code_path");
        if (!$this->qr_code_path->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->qr_code_path->Visible = false; // Disable update for API request
            } else {
                $this->qr_code_path->setFormValue($val);
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

        // Check field name 'expiry_date' first before field var 'x_expiry_date'
        $val = $CurrentForm->hasValue("expiry_date") ? $CurrentForm->getValue("expiry_date") : $CurrentForm->getValue("x_expiry_date");
        if (!$this->expiry_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->expiry_date->Visible = false; // Disable update for API request
            } else {
                $this->expiry_date->setFormValue($val, true, $validate);
            }
            $this->expiry_date->CurrentValue = UnFormatDateTime($this->expiry_date->CurrentValue, $this->expiry_date->formatPattern());
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

        // Check field name 'failed_attempts' first before field var 'x_failed_attempts'
        $val = $CurrentForm->hasValue("failed_attempts") ? $CurrentForm->getValue("failed_attempts") : $CurrentForm->getValue("x_failed_attempts");
        if (!$this->failed_attempts->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->failed_attempts->Visible = false; // Disable update for API request
            } else {
                $this->failed_attempts->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'blocked_until' first before field var 'x_blocked_until'
        $val = $CurrentForm->hasValue("blocked_until") ? $CurrentForm->getValue("blocked_until") : $CurrentForm->getValue("x_blocked_until");
        if (!$this->blocked_until->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->blocked_until->Visible = false; // Disable update for API request
            } else {
                $this->blocked_until->setFormValue($val, true, $validate);
            }
            $this->blocked_until->CurrentValue = UnFormatDateTime($this->blocked_until->CurrentValue, $this->blocked_until->formatPattern());
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->verification_id->CurrentValue = $this->verification_id->FormValue;
        $this->notarized_id->CurrentValue = $this->notarized_id->FormValue;
        $this->document_number->CurrentValue = $this->document_number->FormValue;
        $this->keycode->CurrentValue = $this->keycode->FormValue;
        $this->verification_url->CurrentValue = $this->verification_url->FormValue;
        $this->qr_code_path->CurrentValue = $this->qr_code_path->FormValue;
        $this->is_active->CurrentValue = $this->is_active->FormValue;
        $this->expiry_date->CurrentValue = $this->expiry_date->FormValue;
        $this->expiry_date->CurrentValue = UnFormatDateTime($this->expiry_date->CurrentValue, $this->expiry_date->formatPattern());
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->failed_attempts->CurrentValue = $this->failed_attempts->FormValue;
        $this->blocked_until->CurrentValue = $this->blocked_until->FormValue;
        $this->blocked_until->CurrentValue = UnFormatDateTime($this->blocked_until->CurrentValue, $this->blocked_until->formatPattern());
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
        $this->verification_id->setDbValue($row['verification_id']);
        $this->notarized_id->setDbValue($row['notarized_id']);
        $this->document_number->setDbValue($row['document_number']);
        $this->keycode->setDbValue($row['keycode']);
        $this->verification_url->setDbValue($row['verification_url']);
        $this->qr_code_path->setDbValue($row['qr_code_path']);
        $this->is_active->setDbValue((ConvertToBool($row['is_active']) ? "1" : "0"));
        $this->expiry_date->setDbValue($row['expiry_date']);
        $this->created_at->setDbValue($row['created_at']);
        $this->failed_attempts->setDbValue($row['failed_attempts']);
        $this->blocked_until->setDbValue($row['blocked_until']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['verification_id'] = $this->verification_id->DefaultValue;
        $row['notarized_id'] = $this->notarized_id->DefaultValue;
        $row['document_number'] = $this->document_number->DefaultValue;
        $row['keycode'] = $this->keycode->DefaultValue;
        $row['verification_url'] = $this->verification_url->DefaultValue;
        $row['qr_code_path'] = $this->qr_code_path->DefaultValue;
        $row['is_active'] = $this->is_active->DefaultValue;
        $row['expiry_date'] = $this->expiry_date->DefaultValue;
        $row['created_at'] = $this->created_at->DefaultValue;
        $row['failed_attempts'] = $this->failed_attempts->DefaultValue;
        $row['blocked_until'] = $this->blocked_until->DefaultValue;
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

        // verification_id
        $this->verification_id->RowCssClass = "row";

        // notarized_id
        $this->notarized_id->RowCssClass = "row";

        // document_number
        $this->document_number->RowCssClass = "row";

        // keycode
        $this->keycode->RowCssClass = "row";

        // verification_url
        $this->verification_url->RowCssClass = "row";

        // qr_code_path
        $this->qr_code_path->RowCssClass = "row";

        // is_active
        $this->is_active->RowCssClass = "row";

        // expiry_date
        $this->expiry_date->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // failed_attempts
        $this->failed_attempts->RowCssClass = "row";

        // blocked_until
        $this->blocked_until->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // verification_id
            $this->verification_id->ViewValue = $this->verification_id->CurrentValue;

            // notarized_id
            $this->notarized_id->ViewValue = $this->notarized_id->CurrentValue;
            $this->notarized_id->ViewValue = FormatNumber($this->notarized_id->ViewValue, $this->notarized_id->formatPattern());

            // document_number
            $this->document_number->ViewValue = $this->document_number->CurrentValue;

            // keycode
            $this->keycode->ViewValue = $this->keycode->CurrentValue;

            // verification_url
            $this->verification_url->ViewValue = $this->verification_url->CurrentValue;

            // qr_code_path
            $this->qr_code_path->ViewValue = $this->qr_code_path->CurrentValue;

            // is_active
            if (ConvertToBool($this->is_active->CurrentValue)) {
                $this->is_active->ViewValue = $this->is_active->tagCaption(1) != "" ? $this->is_active->tagCaption(1) : "Yes";
            } else {
                $this->is_active->ViewValue = $this->is_active->tagCaption(2) != "" ? $this->is_active->tagCaption(2) : "No";
            }

            // expiry_date
            $this->expiry_date->ViewValue = $this->expiry_date->CurrentValue;
            $this->expiry_date->ViewValue = FormatDateTime($this->expiry_date->ViewValue, $this->expiry_date->formatPattern());

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // failed_attempts
            $this->failed_attempts->ViewValue = $this->failed_attempts->CurrentValue;
            $this->failed_attempts->ViewValue = FormatNumber($this->failed_attempts->ViewValue, $this->failed_attempts->formatPattern());

            // blocked_until
            $this->blocked_until->ViewValue = $this->blocked_until->CurrentValue;
            $this->blocked_until->ViewValue = FormatDateTime($this->blocked_until->ViewValue, $this->blocked_until->formatPattern());

            // verification_id
            $this->verification_id->HrefValue = "";

            // notarized_id
            $this->notarized_id->HrefValue = "";

            // document_number
            $this->document_number->HrefValue = "";

            // keycode
            $this->keycode->HrefValue = "";

            // verification_url
            $this->verification_url->HrefValue = "";

            // qr_code_path
            $this->qr_code_path->HrefValue = "";

            // is_active
            $this->is_active->HrefValue = "";

            // expiry_date
            $this->expiry_date->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // failed_attempts
            $this->failed_attempts->HrefValue = "";

            // blocked_until
            $this->blocked_until->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // verification_id
            $this->verification_id->setupEditAttributes();
            $this->verification_id->EditValue = $this->verification_id->CurrentValue;

            // notarized_id
            $this->notarized_id->setupEditAttributes();
            $this->notarized_id->EditValue = $this->notarized_id->CurrentValue;
            $this->notarized_id->PlaceHolder = RemoveHtml($this->notarized_id->caption());
            if (strval($this->notarized_id->EditValue) != "" && is_numeric($this->notarized_id->EditValue)) {
                $this->notarized_id->EditValue = FormatNumber($this->notarized_id->EditValue, null);
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

            // verification_url
            $this->verification_url->setupEditAttributes();
            if (!$this->verification_url->Raw) {
                $this->verification_url->CurrentValue = HtmlDecode($this->verification_url->CurrentValue);
            }
            $this->verification_url->EditValue = HtmlEncode($this->verification_url->CurrentValue);
            $this->verification_url->PlaceHolder = RemoveHtml($this->verification_url->caption());

            // qr_code_path
            $this->qr_code_path->setupEditAttributes();
            if (!$this->qr_code_path->Raw) {
                $this->qr_code_path->CurrentValue = HtmlDecode($this->qr_code_path->CurrentValue);
            }
            $this->qr_code_path->EditValue = HtmlEncode($this->qr_code_path->CurrentValue);
            $this->qr_code_path->PlaceHolder = RemoveHtml($this->qr_code_path->caption());

            // is_active
            $this->is_active->EditValue = $this->is_active->options(false);
            $this->is_active->PlaceHolder = RemoveHtml($this->is_active->caption());

            // expiry_date
            $this->expiry_date->setupEditAttributes();
            $this->expiry_date->EditValue = HtmlEncode(FormatDateTime($this->expiry_date->CurrentValue, $this->expiry_date->formatPattern()));
            $this->expiry_date->PlaceHolder = RemoveHtml($this->expiry_date->caption());

            // created_at
            $this->created_at->setupEditAttributes();
            $this->created_at->EditValue = HtmlEncode(FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()));
            $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

            // failed_attempts
            $this->failed_attempts->setupEditAttributes();
            $this->failed_attempts->EditValue = $this->failed_attempts->CurrentValue;
            $this->failed_attempts->PlaceHolder = RemoveHtml($this->failed_attempts->caption());
            if (strval($this->failed_attempts->EditValue) != "" && is_numeric($this->failed_attempts->EditValue)) {
                $this->failed_attempts->EditValue = FormatNumber($this->failed_attempts->EditValue, null);
            }

            // blocked_until
            $this->blocked_until->setupEditAttributes();
            $this->blocked_until->EditValue = HtmlEncode(FormatDateTime($this->blocked_until->CurrentValue, $this->blocked_until->formatPattern()));
            $this->blocked_until->PlaceHolder = RemoveHtml($this->blocked_until->caption());

            // Edit refer script

            // verification_id
            $this->verification_id->HrefValue = "";

            // notarized_id
            $this->notarized_id->HrefValue = "";

            // document_number
            $this->document_number->HrefValue = "";

            // keycode
            $this->keycode->HrefValue = "";

            // verification_url
            $this->verification_url->HrefValue = "";

            // qr_code_path
            $this->qr_code_path->HrefValue = "";

            // is_active
            $this->is_active->HrefValue = "";

            // expiry_date
            $this->expiry_date->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // failed_attempts
            $this->failed_attempts->HrefValue = "";

            // blocked_until
            $this->blocked_until->HrefValue = "";
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
            if ($this->verification_id->Visible && $this->verification_id->Required) {
                if (!$this->verification_id->IsDetailKey && EmptyValue($this->verification_id->FormValue)) {
                    $this->verification_id->addErrorMessage(str_replace("%s", $this->verification_id->caption(), $this->verification_id->RequiredErrorMessage));
                }
            }
            if ($this->notarized_id->Visible && $this->notarized_id->Required) {
                if (!$this->notarized_id->IsDetailKey && EmptyValue($this->notarized_id->FormValue)) {
                    $this->notarized_id->addErrorMessage(str_replace("%s", $this->notarized_id->caption(), $this->notarized_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->notarized_id->FormValue)) {
                $this->notarized_id->addErrorMessage($this->notarized_id->getErrorMessage(false));
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
            if ($this->verification_url->Visible && $this->verification_url->Required) {
                if (!$this->verification_url->IsDetailKey && EmptyValue($this->verification_url->FormValue)) {
                    $this->verification_url->addErrorMessage(str_replace("%s", $this->verification_url->caption(), $this->verification_url->RequiredErrorMessage));
                }
            }
            if ($this->qr_code_path->Visible && $this->qr_code_path->Required) {
                if (!$this->qr_code_path->IsDetailKey && EmptyValue($this->qr_code_path->FormValue)) {
                    $this->qr_code_path->addErrorMessage(str_replace("%s", $this->qr_code_path->caption(), $this->qr_code_path->RequiredErrorMessage));
                }
            }
            if ($this->is_active->Visible && $this->is_active->Required) {
                if ($this->is_active->FormValue == "") {
                    $this->is_active->addErrorMessage(str_replace("%s", $this->is_active->caption(), $this->is_active->RequiredErrorMessage));
                }
            }
            if ($this->expiry_date->Visible && $this->expiry_date->Required) {
                if (!$this->expiry_date->IsDetailKey && EmptyValue($this->expiry_date->FormValue)) {
                    $this->expiry_date->addErrorMessage(str_replace("%s", $this->expiry_date->caption(), $this->expiry_date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->expiry_date->FormValue, $this->expiry_date->formatPattern())) {
                $this->expiry_date->addErrorMessage($this->expiry_date->getErrorMessage(false));
            }
            if ($this->created_at->Visible && $this->created_at->Required) {
                if (!$this->created_at->IsDetailKey && EmptyValue($this->created_at->FormValue)) {
                    $this->created_at->addErrorMessage(str_replace("%s", $this->created_at->caption(), $this->created_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->created_at->FormValue, $this->created_at->formatPattern())) {
                $this->created_at->addErrorMessage($this->created_at->getErrorMessage(false));
            }
            if ($this->failed_attempts->Visible && $this->failed_attempts->Required) {
                if (!$this->failed_attempts->IsDetailKey && EmptyValue($this->failed_attempts->FormValue)) {
                    $this->failed_attempts->addErrorMessage(str_replace("%s", $this->failed_attempts->caption(), $this->failed_attempts->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->failed_attempts->FormValue)) {
                $this->failed_attempts->addErrorMessage($this->failed_attempts->getErrorMessage(false));
            }
            if ($this->blocked_until->Visible && $this->blocked_until->Required) {
                if (!$this->blocked_until->IsDetailKey && EmptyValue($this->blocked_until->FormValue)) {
                    $this->blocked_until->addErrorMessage(str_replace("%s", $this->blocked_until->caption(), $this->blocked_until->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->blocked_until->FormValue, $this->blocked_until->formatPattern())) {
                $this->blocked_until->addErrorMessage($this->blocked_until->getErrorMessage(false));
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

        // notarized_id
        $this->notarized_id->setDbValueDef($rsnew, $this->notarized_id->CurrentValue, $this->notarized_id->ReadOnly);

        // document_number
        $this->document_number->setDbValueDef($rsnew, $this->document_number->CurrentValue, $this->document_number->ReadOnly);

        // keycode
        $this->keycode->setDbValueDef($rsnew, $this->keycode->CurrentValue, $this->keycode->ReadOnly);

        // verification_url
        $this->verification_url->setDbValueDef($rsnew, $this->verification_url->CurrentValue, $this->verification_url->ReadOnly);

        // qr_code_path
        $this->qr_code_path->setDbValueDef($rsnew, $this->qr_code_path->CurrentValue, $this->qr_code_path->ReadOnly);

        // is_active
        $tmpBool = $this->is_active->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_active->setDbValueDef($rsnew, $tmpBool, $this->is_active->ReadOnly);

        // expiry_date
        $this->expiry_date->setDbValueDef($rsnew, UnFormatDateTime($this->expiry_date->CurrentValue, $this->expiry_date->formatPattern()), $this->expiry_date->ReadOnly);

        // created_at
        $this->created_at->setDbValueDef($rsnew, UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()), $this->created_at->ReadOnly);

        // failed_attempts
        $this->failed_attempts->setDbValueDef($rsnew, $this->failed_attempts->CurrentValue, $this->failed_attempts->ReadOnly);

        // blocked_until
        $this->blocked_until->setDbValueDef($rsnew, UnFormatDateTime($this->blocked_until->CurrentValue, $this->blocked_until->formatPattern()), $this->blocked_until->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['notarized_id'])) { // notarized_id
            $this->notarized_id->CurrentValue = $row['notarized_id'];
        }
        if (isset($row['document_number'])) { // document_number
            $this->document_number->CurrentValue = $row['document_number'];
        }
        if (isset($row['keycode'])) { // keycode
            $this->keycode->CurrentValue = $row['keycode'];
        }
        if (isset($row['verification_url'])) { // verification_url
            $this->verification_url->CurrentValue = $row['verification_url'];
        }
        if (isset($row['qr_code_path'])) { // qr_code_path
            $this->qr_code_path->CurrentValue = $row['qr_code_path'];
        }
        if (isset($row['is_active'])) { // is_active
            $this->is_active->CurrentValue = $row['is_active'];
        }
        if (isset($row['expiry_date'])) { // expiry_date
            $this->expiry_date->CurrentValue = $row['expiry_date'];
        }
        if (isset($row['created_at'])) { // created_at
            $this->created_at->CurrentValue = $row['created_at'];
        }
        if (isset($row['failed_attempts'])) { // failed_attempts
            $this->failed_attempts->CurrentValue = $row['failed_attempts'];
        }
        if (isset($row['blocked_until'])) { // blocked_until
            $this->blocked_until->CurrentValue = $row['blocked_until'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("DocumentVerificationList"), "", $this->TableVar, true);
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
