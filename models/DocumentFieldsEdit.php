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
class DocumentFieldsEdit extends DocumentFields
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "DocumentFieldsEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "DocumentFieldsEdit";

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
        $this->document_field_id->setVisibility();
        $this->document_id->setVisibility();
        $this->field_id->setVisibility();
        $this->field_value->setVisibility();
        $this->updated_at->setVisibility();
        $this->is_verified->setVisibility();
        $this->verified_by->setVisibility();
        $this->verification_date->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'document_fields';
        $this->TableName = 'document_fields';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (document_fields)
        if (!isset($GLOBALS["document_fields"]) || $GLOBALS["document_fields"]::class == PROJECT_NAMESPACE . "document_fields") {
            $GLOBALS["document_fields"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'document_fields');
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
                        $result["view"] = SameString($pageName, "DocumentFieldsView"); // If View page, no primary button
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
            $key .= @$ar['document_field_id'];
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
            $this->document_field_id->Visible = false;
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
        $this->setupLookupOptions($this->is_verified);

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
            if (($keyValue = Get("document_field_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->document_field_id->setQueryStringValue($keyValue);
                $this->document_field_id->setOldValue($this->document_field_id->QueryStringValue);
            } elseif (Post("document_field_id") !== null) {
                $this->document_field_id->setFormValue(Post("document_field_id"));
                $this->document_field_id->setOldValue($this->document_field_id->FormValue);
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
                if (($keyValue = Get("document_field_id") ?? Route("document_field_id")) !== null) {
                    $this->document_field_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->document_field_id->CurrentValue = null;
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
                        $this->terminate("DocumentFieldsList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "DocumentFieldsList") {
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
                        if (GetPageName($returnUrl) != "DocumentFieldsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "DocumentFieldsList"; // Return list page content
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

        // Check field name 'document_field_id' first before field var 'x_document_field_id'
        $val = $CurrentForm->hasValue("document_field_id") ? $CurrentForm->getValue("document_field_id") : $CurrentForm->getValue("x_document_field_id");
        if (!$this->document_field_id->IsDetailKey) {
            $this->document_field_id->setFormValue($val);
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

        // Check field name 'field_id' first before field var 'x_field_id'
        $val = $CurrentForm->hasValue("field_id") ? $CurrentForm->getValue("field_id") : $CurrentForm->getValue("x_field_id");
        if (!$this->field_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->field_id->Visible = false; // Disable update for API request
            } else {
                $this->field_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'field_value' first before field var 'x_field_value'
        $val = $CurrentForm->hasValue("field_value") ? $CurrentForm->getValue("field_value") : $CurrentForm->getValue("x_field_value");
        if (!$this->field_value->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->field_value->Visible = false; // Disable update for API request
            } else {
                $this->field_value->setFormValue($val);
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

        // Check field name 'is_verified' first before field var 'x_is_verified'
        $val = $CurrentForm->hasValue("is_verified") ? $CurrentForm->getValue("is_verified") : $CurrentForm->getValue("x_is_verified");
        if (!$this->is_verified->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_verified->Visible = false; // Disable update for API request
            } else {
                $this->is_verified->setFormValue($val);
            }
        }

        // Check field name 'verified_by' first before field var 'x_verified_by'
        $val = $CurrentForm->hasValue("verified_by") ? $CurrentForm->getValue("verified_by") : $CurrentForm->getValue("x_verified_by");
        if (!$this->verified_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->verified_by->Visible = false; // Disable update for API request
            } else {
                $this->verified_by->setFormValue($val, true, $validate);
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
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->document_field_id->CurrentValue = $this->document_field_id->FormValue;
        $this->document_id->CurrentValue = $this->document_id->FormValue;
        $this->field_id->CurrentValue = $this->field_id->FormValue;
        $this->field_value->CurrentValue = $this->field_value->FormValue;
        $this->updated_at->CurrentValue = $this->updated_at->FormValue;
        $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->is_verified->CurrentValue = $this->is_verified->FormValue;
        $this->verified_by->CurrentValue = $this->verified_by->FormValue;
        $this->verification_date->CurrentValue = $this->verification_date->FormValue;
        $this->verification_date->CurrentValue = UnFormatDateTime($this->verification_date->CurrentValue, $this->verification_date->formatPattern());
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
        $this->document_field_id->setDbValue($row['document_field_id']);
        $this->document_id->setDbValue($row['document_id']);
        $this->field_id->setDbValue($row['field_id']);
        $this->field_value->setDbValue($row['field_value']);
        $this->updated_at->setDbValue($row['updated_at']);
        $this->is_verified->setDbValue((ConvertToBool($row['is_verified']) ? "1" : "0"));
        $this->verified_by->setDbValue($row['verified_by']);
        $this->verification_date->setDbValue($row['verification_date']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['document_field_id'] = $this->document_field_id->DefaultValue;
        $row['document_id'] = $this->document_id->DefaultValue;
        $row['field_id'] = $this->field_id->DefaultValue;
        $row['field_value'] = $this->field_value->DefaultValue;
        $row['updated_at'] = $this->updated_at->DefaultValue;
        $row['is_verified'] = $this->is_verified->DefaultValue;
        $row['verified_by'] = $this->verified_by->DefaultValue;
        $row['verification_date'] = $this->verification_date->DefaultValue;
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

        // document_field_id
        $this->document_field_id->RowCssClass = "row";

        // document_id
        $this->document_id->RowCssClass = "row";

        // field_id
        $this->field_id->RowCssClass = "row";

        // field_value
        $this->field_value->RowCssClass = "row";

        // updated_at
        $this->updated_at->RowCssClass = "row";

        // is_verified
        $this->is_verified->RowCssClass = "row";

        // verified_by
        $this->verified_by->RowCssClass = "row";

        // verification_date
        $this->verification_date->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // document_field_id
            $this->document_field_id->ViewValue = $this->document_field_id->CurrentValue;

            // document_id
            $this->document_id->ViewValue = $this->document_id->CurrentValue;
            $this->document_id->ViewValue = FormatNumber($this->document_id->ViewValue, $this->document_id->formatPattern());

            // field_id
            $this->field_id->ViewValue = $this->field_id->CurrentValue;
            $this->field_id->ViewValue = FormatNumber($this->field_id->ViewValue, $this->field_id->formatPattern());

            // field_value
            $this->field_value->ViewValue = $this->field_value->CurrentValue;

            // updated_at
            $this->updated_at->ViewValue = $this->updated_at->CurrentValue;
            $this->updated_at->ViewValue = FormatDateTime($this->updated_at->ViewValue, $this->updated_at->formatPattern());

            // is_verified
            if (ConvertToBool($this->is_verified->CurrentValue)) {
                $this->is_verified->ViewValue = $this->is_verified->tagCaption(1) != "" ? $this->is_verified->tagCaption(1) : "Yes";
            } else {
                $this->is_verified->ViewValue = $this->is_verified->tagCaption(2) != "" ? $this->is_verified->tagCaption(2) : "No";
            }

            // verified_by
            $this->verified_by->ViewValue = $this->verified_by->CurrentValue;
            $this->verified_by->ViewValue = FormatNumber($this->verified_by->ViewValue, $this->verified_by->formatPattern());

            // verification_date
            $this->verification_date->ViewValue = $this->verification_date->CurrentValue;
            $this->verification_date->ViewValue = FormatDateTime($this->verification_date->ViewValue, $this->verification_date->formatPattern());

            // document_field_id
            $this->document_field_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // field_id
            $this->field_id->HrefValue = "";

            // field_value
            $this->field_value->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // is_verified
            $this->is_verified->HrefValue = "";

            // verified_by
            $this->verified_by->HrefValue = "";

            // verification_date
            $this->verification_date->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // document_field_id
            $this->document_field_id->setupEditAttributes();
            $this->document_field_id->EditValue = $this->document_field_id->CurrentValue;

            // document_id
            $this->document_id->setupEditAttributes();
            $this->document_id->EditValue = $this->document_id->CurrentValue;
            $this->document_id->PlaceHolder = RemoveHtml($this->document_id->caption());
            if (strval($this->document_id->EditValue) != "" && is_numeric($this->document_id->EditValue)) {
                $this->document_id->EditValue = FormatNumber($this->document_id->EditValue, null);
            }

            // field_id
            $this->field_id->setupEditAttributes();
            $this->field_id->EditValue = $this->field_id->CurrentValue;
            $this->field_id->PlaceHolder = RemoveHtml($this->field_id->caption());
            if (strval($this->field_id->EditValue) != "" && is_numeric($this->field_id->EditValue)) {
                $this->field_id->EditValue = FormatNumber($this->field_id->EditValue, null);
            }

            // field_value
            $this->field_value->setupEditAttributes();
            $this->field_value->EditValue = HtmlEncode($this->field_value->CurrentValue);
            $this->field_value->PlaceHolder = RemoveHtml($this->field_value->caption());

            // updated_at
            $this->updated_at->setupEditAttributes();
            $this->updated_at->EditValue = HtmlEncode(FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()));
            $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

            // is_verified
            $this->is_verified->EditValue = $this->is_verified->options(false);
            $this->is_verified->PlaceHolder = RemoveHtml($this->is_verified->caption());

            // verified_by
            $this->verified_by->setupEditAttributes();
            $this->verified_by->EditValue = $this->verified_by->CurrentValue;
            $this->verified_by->PlaceHolder = RemoveHtml($this->verified_by->caption());
            if (strval($this->verified_by->EditValue) != "" && is_numeric($this->verified_by->EditValue)) {
                $this->verified_by->EditValue = FormatNumber($this->verified_by->EditValue, null);
            }

            // verification_date
            $this->verification_date->setupEditAttributes();
            $this->verification_date->EditValue = HtmlEncode(FormatDateTime($this->verification_date->CurrentValue, $this->verification_date->formatPattern()));
            $this->verification_date->PlaceHolder = RemoveHtml($this->verification_date->caption());

            // Edit refer script

            // document_field_id
            $this->document_field_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // field_id
            $this->field_id->HrefValue = "";

            // field_value
            $this->field_value->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // is_verified
            $this->is_verified->HrefValue = "";

            // verified_by
            $this->verified_by->HrefValue = "";

            // verification_date
            $this->verification_date->HrefValue = "";
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
            if ($this->document_field_id->Visible && $this->document_field_id->Required) {
                if (!$this->document_field_id->IsDetailKey && EmptyValue($this->document_field_id->FormValue)) {
                    $this->document_field_id->addErrorMessage(str_replace("%s", $this->document_field_id->caption(), $this->document_field_id->RequiredErrorMessage));
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
            if ($this->field_id->Visible && $this->field_id->Required) {
                if (!$this->field_id->IsDetailKey && EmptyValue($this->field_id->FormValue)) {
                    $this->field_id->addErrorMessage(str_replace("%s", $this->field_id->caption(), $this->field_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->field_id->FormValue)) {
                $this->field_id->addErrorMessage($this->field_id->getErrorMessage(false));
            }
            if ($this->field_value->Visible && $this->field_value->Required) {
                if (!$this->field_value->IsDetailKey && EmptyValue($this->field_value->FormValue)) {
                    $this->field_value->addErrorMessage(str_replace("%s", $this->field_value->caption(), $this->field_value->RequiredErrorMessage));
                }
            }
            if ($this->updated_at->Visible && $this->updated_at->Required) {
                if (!$this->updated_at->IsDetailKey && EmptyValue($this->updated_at->FormValue)) {
                    $this->updated_at->addErrorMessage(str_replace("%s", $this->updated_at->caption(), $this->updated_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->updated_at->FormValue, $this->updated_at->formatPattern())) {
                $this->updated_at->addErrorMessage($this->updated_at->getErrorMessage(false));
            }
            if ($this->is_verified->Visible && $this->is_verified->Required) {
                if ($this->is_verified->FormValue == "") {
                    $this->is_verified->addErrorMessage(str_replace("%s", $this->is_verified->caption(), $this->is_verified->RequiredErrorMessage));
                }
            }
            if ($this->verified_by->Visible && $this->verified_by->Required) {
                if (!$this->verified_by->IsDetailKey && EmptyValue($this->verified_by->FormValue)) {
                    $this->verified_by->addErrorMessage(str_replace("%s", $this->verified_by->caption(), $this->verified_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->verified_by->FormValue)) {
                $this->verified_by->addErrorMessage($this->verified_by->getErrorMessage(false));
            }
            if ($this->verification_date->Visible && $this->verification_date->Required) {
                if (!$this->verification_date->IsDetailKey && EmptyValue($this->verification_date->FormValue)) {
                    $this->verification_date->addErrorMessage(str_replace("%s", $this->verification_date->caption(), $this->verification_date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->verification_date->FormValue, $this->verification_date->formatPattern())) {
                $this->verification_date->addErrorMessage($this->verification_date->getErrorMessage(false));
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

        // document_id
        $this->document_id->setDbValueDef($rsnew, $this->document_id->CurrentValue, $this->document_id->ReadOnly);

        // field_id
        $this->field_id->setDbValueDef($rsnew, $this->field_id->CurrentValue, $this->field_id->ReadOnly);

        // field_value
        $this->field_value->setDbValueDef($rsnew, $this->field_value->CurrentValue, $this->field_value->ReadOnly);

        // updated_at
        $this->updated_at->setDbValueDef($rsnew, UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()), $this->updated_at->ReadOnly);

        // is_verified
        $tmpBool = $this->is_verified->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_verified->setDbValueDef($rsnew, $tmpBool, $this->is_verified->ReadOnly);

        // verified_by
        $this->verified_by->setDbValueDef($rsnew, $this->verified_by->CurrentValue, $this->verified_by->ReadOnly);

        // verification_date
        $this->verification_date->setDbValueDef($rsnew, UnFormatDateTime($this->verification_date->CurrentValue, $this->verification_date->formatPattern()), $this->verification_date->ReadOnly);
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
        if (isset($row['field_id'])) { // field_id
            $this->field_id->CurrentValue = $row['field_id'];
        }
        if (isset($row['field_value'])) { // field_value
            $this->field_value->CurrentValue = $row['field_value'];
        }
        if (isset($row['updated_at'])) { // updated_at
            $this->updated_at->CurrentValue = $row['updated_at'];
        }
        if (isset($row['is_verified'])) { // is_verified
            $this->is_verified->CurrentValue = $row['is_verified'];
        }
        if (isset($row['verified_by'])) { // verified_by
            $this->verified_by->CurrentValue = $row['verified_by'];
        }
        if (isset($row['verification_date'])) { // verification_date
            $this->verification_date->CurrentValue = $row['verification_date'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("DocumentFieldsList"), "", $this->TableVar, true);
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
                case "x_is_verified":
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
