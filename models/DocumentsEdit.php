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
class DocumentsEdit extends Documents
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "DocumentsEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "DocumentsEdit";

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
        $this->document_id->setVisibility();
        $this->user_id->setVisibility();
        $this->template_id->setVisibility();
        $this->document_title->setVisibility();
        $this->document_reference->setVisibility();
        $this->status->setVisibility();
        $this->created_at->setVisibility();
        $this->updated_at->setVisibility();
        $this->submitted_at->setVisibility();
        $this->company_name->setVisibility();
        $this->customs_entry_number->setVisibility();
        $this->date_of_entry->setVisibility();
        $this->document_html->setVisibility();
        $this->document_data->setVisibility();
        $this->is_deleted->setVisibility();
        $this->deletion_date->setVisibility();
        $this->deleted_by->setVisibility();
        $this->parent_document_id->setVisibility();
        $this->version->setVisibility();
        $this->notes->setVisibility();
        $this->status_id->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'documents';
        $this->TableName = 'documents';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (documents)
        if (!isset($GLOBALS["documents"]) || $GLOBALS["documents"]::class == PROJECT_NAMESPACE . "documents") {
            $GLOBALS["documents"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'documents');
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
                        $result["view"] = SameString($pageName, "DocumentsView"); // If View page, no primary button
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
            $key .= @$ar['document_id'];
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
            $this->document_id->Visible = false;
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
        $this->setupLookupOptions($this->is_deleted);

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
            if (($keyValue = Get("document_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->document_id->setQueryStringValue($keyValue);
                $this->document_id->setOldValue($this->document_id->QueryStringValue);
            } elseif (Post("document_id") !== null) {
                $this->document_id->setFormValue(Post("document_id"));
                $this->document_id->setOldValue($this->document_id->FormValue);
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
                if (($keyValue = Get("document_id") ?? Route("document_id")) !== null) {
                    $this->document_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->document_id->CurrentValue = null;
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
                        $this->terminate("DocumentsList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "DocumentsList") {
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
                        if (GetPageName($returnUrl) != "DocumentsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "DocumentsList"; // Return list page content
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

        // Check field name 'document_id' first before field var 'x_document_id'
        $val = $CurrentForm->hasValue("document_id") ? $CurrentForm->getValue("document_id") : $CurrentForm->getValue("x_document_id");
        if (!$this->document_id->IsDetailKey) {
            $this->document_id->setFormValue($val);
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

        // Check field name 'template_id' first before field var 'x_template_id'
        $val = $CurrentForm->hasValue("template_id") ? $CurrentForm->getValue("template_id") : $CurrentForm->getValue("x_template_id");
        if (!$this->template_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->template_id->Visible = false; // Disable update for API request
            } else {
                $this->template_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'document_title' first before field var 'x_document_title'
        $val = $CurrentForm->hasValue("document_title") ? $CurrentForm->getValue("document_title") : $CurrentForm->getValue("x_document_title");
        if (!$this->document_title->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->document_title->Visible = false; // Disable update for API request
            } else {
                $this->document_title->setFormValue($val);
            }
        }

        // Check field name 'document_reference' first before field var 'x_document_reference'
        $val = $CurrentForm->hasValue("document_reference") ? $CurrentForm->getValue("document_reference") : $CurrentForm->getValue("x_document_reference");
        if (!$this->document_reference->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->document_reference->Visible = false; // Disable update for API request
            } else {
                $this->document_reference->setFormValue($val);
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

        // Check field name 'submitted_at' first before field var 'x_submitted_at'
        $val = $CurrentForm->hasValue("submitted_at") ? $CurrentForm->getValue("submitted_at") : $CurrentForm->getValue("x_submitted_at");
        if (!$this->submitted_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->submitted_at->Visible = false; // Disable update for API request
            } else {
                $this->submitted_at->setFormValue($val, true, $validate);
            }
            $this->submitted_at->CurrentValue = UnFormatDateTime($this->submitted_at->CurrentValue, $this->submitted_at->formatPattern());
        }

        // Check field name 'company_name' first before field var 'x_company_name'
        $val = $CurrentForm->hasValue("company_name") ? $CurrentForm->getValue("company_name") : $CurrentForm->getValue("x_company_name");
        if (!$this->company_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->company_name->Visible = false; // Disable update for API request
            } else {
                $this->company_name->setFormValue($val);
            }
        }

        // Check field name 'customs_entry_number' first before field var 'x_customs_entry_number'
        $val = $CurrentForm->hasValue("customs_entry_number") ? $CurrentForm->getValue("customs_entry_number") : $CurrentForm->getValue("x_customs_entry_number");
        if (!$this->customs_entry_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->customs_entry_number->Visible = false; // Disable update for API request
            } else {
                $this->customs_entry_number->setFormValue($val);
            }
        }

        // Check field name 'date_of_entry' first before field var 'x_date_of_entry'
        $val = $CurrentForm->hasValue("date_of_entry") ? $CurrentForm->getValue("date_of_entry") : $CurrentForm->getValue("x_date_of_entry");
        if (!$this->date_of_entry->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->date_of_entry->Visible = false; // Disable update for API request
            } else {
                $this->date_of_entry->setFormValue($val, true, $validate);
            }
            $this->date_of_entry->CurrentValue = UnFormatDateTime($this->date_of_entry->CurrentValue, $this->date_of_entry->formatPattern());
        }

        // Check field name 'document_html' first before field var 'x_document_html'
        $val = $CurrentForm->hasValue("document_html") ? $CurrentForm->getValue("document_html") : $CurrentForm->getValue("x_document_html");
        if (!$this->document_html->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->document_html->Visible = false; // Disable update for API request
            } else {
                $this->document_html->setFormValue($val);
            }
        }

        // Check field name 'document_data' first before field var 'x_document_data'
        $val = $CurrentForm->hasValue("document_data") ? $CurrentForm->getValue("document_data") : $CurrentForm->getValue("x_document_data");
        if (!$this->document_data->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->document_data->Visible = false; // Disable update for API request
            } else {
                $this->document_data->setFormValue($val);
            }
        }

        // Check field name 'is_deleted' first before field var 'x_is_deleted'
        $val = $CurrentForm->hasValue("is_deleted") ? $CurrentForm->getValue("is_deleted") : $CurrentForm->getValue("x_is_deleted");
        if (!$this->is_deleted->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_deleted->Visible = false; // Disable update for API request
            } else {
                $this->is_deleted->setFormValue($val);
            }
        }

        // Check field name 'deletion_date' first before field var 'x_deletion_date'
        $val = $CurrentForm->hasValue("deletion_date") ? $CurrentForm->getValue("deletion_date") : $CurrentForm->getValue("x_deletion_date");
        if (!$this->deletion_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->deletion_date->Visible = false; // Disable update for API request
            } else {
                $this->deletion_date->setFormValue($val, true, $validate);
            }
            $this->deletion_date->CurrentValue = UnFormatDateTime($this->deletion_date->CurrentValue, $this->deletion_date->formatPattern());
        }

        // Check field name 'deleted_by' first before field var 'x_deleted_by'
        $val = $CurrentForm->hasValue("deleted_by") ? $CurrentForm->getValue("deleted_by") : $CurrentForm->getValue("x_deleted_by");
        if (!$this->deleted_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->deleted_by->Visible = false; // Disable update for API request
            } else {
                $this->deleted_by->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'parent_document_id' first before field var 'x_parent_document_id'
        $val = $CurrentForm->hasValue("parent_document_id") ? $CurrentForm->getValue("parent_document_id") : $CurrentForm->getValue("x_parent_document_id");
        if (!$this->parent_document_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->parent_document_id->Visible = false; // Disable update for API request
            } else {
                $this->parent_document_id->setFormValue($val, true, $validate);
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

        // Check field name 'notes' first before field var 'x_notes'
        $val = $CurrentForm->hasValue("notes") ? $CurrentForm->getValue("notes") : $CurrentForm->getValue("x_notes");
        if (!$this->notes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notes->Visible = false; // Disable update for API request
            } else {
                $this->notes->setFormValue($val);
            }
        }

        // Check field name 'status_id' first before field var 'x_status_id'
        $val = $CurrentForm->hasValue("status_id") ? $CurrentForm->getValue("status_id") : $CurrentForm->getValue("x_status_id");
        if (!$this->status_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->status_id->Visible = false; // Disable update for API request
            } else {
                $this->status_id->setFormValue($val, true, $validate);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->document_id->CurrentValue = $this->document_id->FormValue;
        $this->user_id->CurrentValue = $this->user_id->FormValue;
        $this->template_id->CurrentValue = $this->template_id->FormValue;
        $this->document_title->CurrentValue = $this->document_title->FormValue;
        $this->document_reference->CurrentValue = $this->document_reference->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->updated_at->CurrentValue = $this->updated_at->FormValue;
        $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->submitted_at->CurrentValue = $this->submitted_at->FormValue;
        $this->submitted_at->CurrentValue = UnFormatDateTime($this->submitted_at->CurrentValue, $this->submitted_at->formatPattern());
        $this->company_name->CurrentValue = $this->company_name->FormValue;
        $this->customs_entry_number->CurrentValue = $this->customs_entry_number->FormValue;
        $this->date_of_entry->CurrentValue = $this->date_of_entry->FormValue;
        $this->date_of_entry->CurrentValue = UnFormatDateTime($this->date_of_entry->CurrentValue, $this->date_of_entry->formatPattern());
        $this->document_html->CurrentValue = $this->document_html->FormValue;
        $this->document_data->CurrentValue = $this->document_data->FormValue;
        $this->is_deleted->CurrentValue = $this->is_deleted->FormValue;
        $this->deletion_date->CurrentValue = $this->deletion_date->FormValue;
        $this->deletion_date->CurrentValue = UnFormatDateTime($this->deletion_date->CurrentValue, $this->deletion_date->formatPattern());
        $this->deleted_by->CurrentValue = $this->deleted_by->FormValue;
        $this->parent_document_id->CurrentValue = $this->parent_document_id->FormValue;
        $this->version->CurrentValue = $this->version->FormValue;
        $this->notes->CurrentValue = $this->notes->FormValue;
        $this->status_id->CurrentValue = $this->status_id->FormValue;
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
        $this->document_id->setDbValue($row['document_id']);
        $this->user_id->setDbValue($row['user_id']);
        $this->template_id->setDbValue($row['template_id']);
        $this->document_title->setDbValue($row['document_title']);
        $this->document_reference->setDbValue($row['document_reference']);
        $this->status->setDbValue($row['status']);
        $this->created_at->setDbValue($row['created_at']);
        $this->updated_at->setDbValue($row['updated_at']);
        $this->submitted_at->setDbValue($row['submitted_at']);
        $this->company_name->setDbValue($row['company_name']);
        $this->customs_entry_number->setDbValue($row['customs_entry_number']);
        $this->date_of_entry->setDbValue($row['date_of_entry']);
        $this->document_html->setDbValue($row['document_html']);
        $this->document_data->setDbValue($row['document_data']);
        $this->is_deleted->setDbValue((ConvertToBool($row['is_deleted']) ? "1" : "0"));
        $this->deletion_date->setDbValue($row['deletion_date']);
        $this->deleted_by->setDbValue($row['deleted_by']);
        $this->parent_document_id->setDbValue($row['parent_document_id']);
        $this->version->setDbValue($row['version']);
        $this->notes->setDbValue($row['notes']);
        $this->status_id->setDbValue($row['status_id']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['document_id'] = $this->document_id->DefaultValue;
        $row['user_id'] = $this->user_id->DefaultValue;
        $row['template_id'] = $this->template_id->DefaultValue;
        $row['document_title'] = $this->document_title->DefaultValue;
        $row['document_reference'] = $this->document_reference->DefaultValue;
        $row['status'] = $this->status->DefaultValue;
        $row['created_at'] = $this->created_at->DefaultValue;
        $row['updated_at'] = $this->updated_at->DefaultValue;
        $row['submitted_at'] = $this->submitted_at->DefaultValue;
        $row['company_name'] = $this->company_name->DefaultValue;
        $row['customs_entry_number'] = $this->customs_entry_number->DefaultValue;
        $row['date_of_entry'] = $this->date_of_entry->DefaultValue;
        $row['document_html'] = $this->document_html->DefaultValue;
        $row['document_data'] = $this->document_data->DefaultValue;
        $row['is_deleted'] = $this->is_deleted->DefaultValue;
        $row['deletion_date'] = $this->deletion_date->DefaultValue;
        $row['deleted_by'] = $this->deleted_by->DefaultValue;
        $row['parent_document_id'] = $this->parent_document_id->DefaultValue;
        $row['version'] = $this->version->DefaultValue;
        $row['notes'] = $this->notes->DefaultValue;
        $row['status_id'] = $this->status_id->DefaultValue;
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

        // document_id
        $this->document_id->RowCssClass = "row";

        // user_id
        $this->user_id->RowCssClass = "row";

        // template_id
        $this->template_id->RowCssClass = "row";

        // document_title
        $this->document_title->RowCssClass = "row";

        // document_reference
        $this->document_reference->RowCssClass = "row";

        // status
        $this->status->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // updated_at
        $this->updated_at->RowCssClass = "row";

        // submitted_at
        $this->submitted_at->RowCssClass = "row";

        // company_name
        $this->company_name->RowCssClass = "row";

        // customs_entry_number
        $this->customs_entry_number->RowCssClass = "row";

        // date_of_entry
        $this->date_of_entry->RowCssClass = "row";

        // document_html
        $this->document_html->RowCssClass = "row";

        // document_data
        $this->document_data->RowCssClass = "row";

        // is_deleted
        $this->is_deleted->RowCssClass = "row";

        // deletion_date
        $this->deletion_date->RowCssClass = "row";

        // deleted_by
        $this->deleted_by->RowCssClass = "row";

        // parent_document_id
        $this->parent_document_id->RowCssClass = "row";

        // version
        $this->version->RowCssClass = "row";

        // notes
        $this->notes->RowCssClass = "row";

        // status_id
        $this->status_id->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // document_id
            $this->document_id->ViewValue = $this->document_id->CurrentValue;

            // user_id
            $this->user_id->ViewValue = $this->user_id->CurrentValue;
            $this->user_id->ViewValue = FormatNumber($this->user_id->ViewValue, $this->user_id->formatPattern());

            // template_id
            $this->template_id->ViewValue = $this->template_id->CurrentValue;
            $this->template_id->ViewValue = FormatNumber($this->template_id->ViewValue, $this->template_id->formatPattern());

            // document_title
            $this->document_title->ViewValue = $this->document_title->CurrentValue;

            // document_reference
            $this->document_reference->ViewValue = $this->document_reference->CurrentValue;

            // status
            $this->status->ViewValue = $this->status->CurrentValue;

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // updated_at
            $this->updated_at->ViewValue = $this->updated_at->CurrentValue;
            $this->updated_at->ViewValue = FormatDateTime($this->updated_at->ViewValue, $this->updated_at->formatPattern());

            // submitted_at
            $this->submitted_at->ViewValue = $this->submitted_at->CurrentValue;
            $this->submitted_at->ViewValue = FormatDateTime($this->submitted_at->ViewValue, $this->submitted_at->formatPattern());

            // company_name
            $this->company_name->ViewValue = $this->company_name->CurrentValue;

            // customs_entry_number
            $this->customs_entry_number->ViewValue = $this->customs_entry_number->CurrentValue;

            // date_of_entry
            $this->date_of_entry->ViewValue = $this->date_of_entry->CurrentValue;
            $this->date_of_entry->ViewValue = FormatDateTime($this->date_of_entry->ViewValue, $this->date_of_entry->formatPattern());

            // document_html
            $this->document_html->ViewValue = $this->document_html->CurrentValue;

            // document_data
            $this->document_data->ViewValue = $this->document_data->CurrentValue;

            // is_deleted
            if (ConvertToBool($this->is_deleted->CurrentValue)) {
                $this->is_deleted->ViewValue = $this->is_deleted->tagCaption(1) != "" ? $this->is_deleted->tagCaption(1) : "Yes";
            } else {
                $this->is_deleted->ViewValue = $this->is_deleted->tagCaption(2) != "" ? $this->is_deleted->tagCaption(2) : "No";
            }

            // deletion_date
            $this->deletion_date->ViewValue = $this->deletion_date->CurrentValue;
            $this->deletion_date->ViewValue = FormatDateTime($this->deletion_date->ViewValue, $this->deletion_date->formatPattern());

            // deleted_by
            $this->deleted_by->ViewValue = $this->deleted_by->CurrentValue;
            $this->deleted_by->ViewValue = FormatNumber($this->deleted_by->ViewValue, $this->deleted_by->formatPattern());

            // parent_document_id
            $this->parent_document_id->ViewValue = $this->parent_document_id->CurrentValue;
            $this->parent_document_id->ViewValue = FormatNumber($this->parent_document_id->ViewValue, $this->parent_document_id->formatPattern());

            // version
            $this->version->ViewValue = $this->version->CurrentValue;
            $this->version->ViewValue = FormatNumber($this->version->ViewValue, $this->version->formatPattern());

            // notes
            $this->notes->ViewValue = $this->notes->CurrentValue;

            // status_id
            $this->status_id->ViewValue = $this->status_id->CurrentValue;
            $this->status_id->ViewValue = FormatNumber($this->status_id->ViewValue, $this->status_id->formatPattern());

            // document_id
            $this->document_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // template_id
            $this->template_id->HrefValue = "";

            // document_title
            $this->document_title->HrefValue = "";

            // document_reference
            $this->document_reference->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // submitted_at
            $this->submitted_at->HrefValue = "";

            // company_name
            $this->company_name->HrefValue = "";

            // customs_entry_number
            $this->customs_entry_number->HrefValue = "";

            // date_of_entry
            $this->date_of_entry->HrefValue = "";

            // document_html
            $this->document_html->HrefValue = "";

            // document_data
            $this->document_data->HrefValue = "";

            // is_deleted
            $this->is_deleted->HrefValue = "";

            // deletion_date
            $this->deletion_date->HrefValue = "";

            // deleted_by
            $this->deleted_by->HrefValue = "";

            // parent_document_id
            $this->parent_document_id->HrefValue = "";

            // version
            $this->version->HrefValue = "";

            // notes
            $this->notes->HrefValue = "";

            // status_id
            $this->status_id->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // document_id
            $this->document_id->setupEditAttributes();
            $this->document_id->EditValue = $this->document_id->CurrentValue;

            // user_id
            $this->user_id->setupEditAttributes();
            $this->user_id->EditValue = $this->user_id->CurrentValue;
            $this->user_id->PlaceHolder = RemoveHtml($this->user_id->caption());
            if (strval($this->user_id->EditValue) != "" && is_numeric($this->user_id->EditValue)) {
                $this->user_id->EditValue = FormatNumber($this->user_id->EditValue, null);
            }

            // template_id
            $this->template_id->setupEditAttributes();
            $this->template_id->EditValue = $this->template_id->CurrentValue;
            $this->template_id->PlaceHolder = RemoveHtml($this->template_id->caption());
            if (strval($this->template_id->EditValue) != "" && is_numeric($this->template_id->EditValue)) {
                $this->template_id->EditValue = FormatNumber($this->template_id->EditValue, null);
            }

            // document_title
            $this->document_title->setupEditAttributes();
            if (!$this->document_title->Raw) {
                $this->document_title->CurrentValue = HtmlDecode($this->document_title->CurrentValue);
            }
            $this->document_title->EditValue = HtmlEncode($this->document_title->CurrentValue);
            $this->document_title->PlaceHolder = RemoveHtml($this->document_title->caption());

            // document_reference
            $this->document_reference->setupEditAttributes();
            if (!$this->document_reference->Raw) {
                $this->document_reference->CurrentValue = HtmlDecode($this->document_reference->CurrentValue);
            }
            $this->document_reference->EditValue = HtmlEncode($this->document_reference->CurrentValue);
            $this->document_reference->PlaceHolder = RemoveHtml($this->document_reference->caption());

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

            // submitted_at
            $this->submitted_at->setupEditAttributes();
            $this->submitted_at->EditValue = HtmlEncode(FormatDateTime($this->submitted_at->CurrentValue, $this->submitted_at->formatPattern()));
            $this->submitted_at->PlaceHolder = RemoveHtml($this->submitted_at->caption());

            // company_name
            $this->company_name->setupEditAttributes();
            if (!$this->company_name->Raw) {
                $this->company_name->CurrentValue = HtmlDecode($this->company_name->CurrentValue);
            }
            $this->company_name->EditValue = HtmlEncode($this->company_name->CurrentValue);
            $this->company_name->PlaceHolder = RemoveHtml($this->company_name->caption());

            // customs_entry_number
            $this->customs_entry_number->setupEditAttributes();
            if (!$this->customs_entry_number->Raw) {
                $this->customs_entry_number->CurrentValue = HtmlDecode($this->customs_entry_number->CurrentValue);
            }
            $this->customs_entry_number->EditValue = HtmlEncode($this->customs_entry_number->CurrentValue);
            $this->customs_entry_number->PlaceHolder = RemoveHtml($this->customs_entry_number->caption());

            // date_of_entry
            $this->date_of_entry->setupEditAttributes();
            $this->date_of_entry->EditValue = HtmlEncode(FormatDateTime($this->date_of_entry->CurrentValue, $this->date_of_entry->formatPattern()));
            $this->date_of_entry->PlaceHolder = RemoveHtml($this->date_of_entry->caption());

            // document_html
            $this->document_html->setupEditAttributes();
            $this->document_html->EditValue = HtmlEncode($this->document_html->CurrentValue);
            $this->document_html->PlaceHolder = RemoveHtml($this->document_html->caption());

            // document_data
            $this->document_data->setupEditAttributes();
            $this->document_data->EditValue = HtmlEncode($this->document_data->CurrentValue);
            $this->document_data->PlaceHolder = RemoveHtml($this->document_data->caption());

            // is_deleted
            $this->is_deleted->EditValue = $this->is_deleted->options(false);
            $this->is_deleted->PlaceHolder = RemoveHtml($this->is_deleted->caption());

            // deletion_date
            $this->deletion_date->setupEditAttributes();
            $this->deletion_date->EditValue = HtmlEncode(FormatDateTime($this->deletion_date->CurrentValue, $this->deletion_date->formatPattern()));
            $this->deletion_date->PlaceHolder = RemoveHtml($this->deletion_date->caption());

            // deleted_by
            $this->deleted_by->setupEditAttributes();
            $this->deleted_by->EditValue = $this->deleted_by->CurrentValue;
            $this->deleted_by->PlaceHolder = RemoveHtml($this->deleted_by->caption());
            if (strval($this->deleted_by->EditValue) != "" && is_numeric($this->deleted_by->EditValue)) {
                $this->deleted_by->EditValue = FormatNumber($this->deleted_by->EditValue, null);
            }

            // parent_document_id
            $this->parent_document_id->setupEditAttributes();
            $this->parent_document_id->EditValue = $this->parent_document_id->CurrentValue;
            $this->parent_document_id->PlaceHolder = RemoveHtml($this->parent_document_id->caption());
            if (strval($this->parent_document_id->EditValue) != "" && is_numeric($this->parent_document_id->EditValue)) {
                $this->parent_document_id->EditValue = FormatNumber($this->parent_document_id->EditValue, null);
            }

            // version
            $this->version->setupEditAttributes();
            $this->version->EditValue = $this->version->CurrentValue;
            $this->version->PlaceHolder = RemoveHtml($this->version->caption());
            if (strval($this->version->EditValue) != "" && is_numeric($this->version->EditValue)) {
                $this->version->EditValue = FormatNumber($this->version->EditValue, null);
            }

            // notes
            $this->notes->setupEditAttributes();
            $this->notes->EditValue = HtmlEncode($this->notes->CurrentValue);
            $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());

            // status_id
            $this->status_id->setupEditAttributes();
            $this->status_id->EditValue = $this->status_id->CurrentValue;
            $this->status_id->PlaceHolder = RemoveHtml($this->status_id->caption());
            if (strval($this->status_id->EditValue) != "" && is_numeric($this->status_id->EditValue)) {
                $this->status_id->EditValue = FormatNumber($this->status_id->EditValue, null);
            }

            // Edit refer script

            // document_id
            $this->document_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // template_id
            $this->template_id->HrefValue = "";

            // document_title
            $this->document_title->HrefValue = "";

            // document_reference
            $this->document_reference->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // submitted_at
            $this->submitted_at->HrefValue = "";

            // company_name
            $this->company_name->HrefValue = "";

            // customs_entry_number
            $this->customs_entry_number->HrefValue = "";

            // date_of_entry
            $this->date_of_entry->HrefValue = "";

            // document_html
            $this->document_html->HrefValue = "";

            // document_data
            $this->document_data->HrefValue = "";

            // is_deleted
            $this->is_deleted->HrefValue = "";

            // deletion_date
            $this->deletion_date->HrefValue = "";

            // deleted_by
            $this->deleted_by->HrefValue = "";

            // parent_document_id
            $this->parent_document_id->HrefValue = "";

            // version
            $this->version->HrefValue = "";

            // notes
            $this->notes->HrefValue = "";

            // status_id
            $this->status_id->HrefValue = "";
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
            if ($this->document_id->Visible && $this->document_id->Required) {
                if (!$this->document_id->IsDetailKey && EmptyValue($this->document_id->FormValue)) {
                    $this->document_id->addErrorMessage(str_replace("%s", $this->document_id->caption(), $this->document_id->RequiredErrorMessage));
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
            if ($this->template_id->Visible && $this->template_id->Required) {
                if (!$this->template_id->IsDetailKey && EmptyValue($this->template_id->FormValue)) {
                    $this->template_id->addErrorMessage(str_replace("%s", $this->template_id->caption(), $this->template_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->template_id->FormValue)) {
                $this->template_id->addErrorMessage($this->template_id->getErrorMessage(false));
            }
            if ($this->document_title->Visible && $this->document_title->Required) {
                if (!$this->document_title->IsDetailKey && EmptyValue($this->document_title->FormValue)) {
                    $this->document_title->addErrorMessage(str_replace("%s", $this->document_title->caption(), $this->document_title->RequiredErrorMessage));
                }
            }
            if ($this->document_reference->Visible && $this->document_reference->Required) {
                if (!$this->document_reference->IsDetailKey && EmptyValue($this->document_reference->FormValue)) {
                    $this->document_reference->addErrorMessage(str_replace("%s", $this->document_reference->caption(), $this->document_reference->RequiredErrorMessage));
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
            if ($this->submitted_at->Visible && $this->submitted_at->Required) {
                if (!$this->submitted_at->IsDetailKey && EmptyValue($this->submitted_at->FormValue)) {
                    $this->submitted_at->addErrorMessage(str_replace("%s", $this->submitted_at->caption(), $this->submitted_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->submitted_at->FormValue, $this->submitted_at->formatPattern())) {
                $this->submitted_at->addErrorMessage($this->submitted_at->getErrorMessage(false));
            }
            if ($this->company_name->Visible && $this->company_name->Required) {
                if (!$this->company_name->IsDetailKey && EmptyValue($this->company_name->FormValue)) {
                    $this->company_name->addErrorMessage(str_replace("%s", $this->company_name->caption(), $this->company_name->RequiredErrorMessage));
                }
            }
            if ($this->customs_entry_number->Visible && $this->customs_entry_number->Required) {
                if (!$this->customs_entry_number->IsDetailKey && EmptyValue($this->customs_entry_number->FormValue)) {
                    $this->customs_entry_number->addErrorMessage(str_replace("%s", $this->customs_entry_number->caption(), $this->customs_entry_number->RequiredErrorMessage));
                }
            }
            if ($this->date_of_entry->Visible && $this->date_of_entry->Required) {
                if (!$this->date_of_entry->IsDetailKey && EmptyValue($this->date_of_entry->FormValue)) {
                    $this->date_of_entry->addErrorMessage(str_replace("%s", $this->date_of_entry->caption(), $this->date_of_entry->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->date_of_entry->FormValue, $this->date_of_entry->formatPattern())) {
                $this->date_of_entry->addErrorMessage($this->date_of_entry->getErrorMessage(false));
            }
            if ($this->document_html->Visible && $this->document_html->Required) {
                if (!$this->document_html->IsDetailKey && EmptyValue($this->document_html->FormValue)) {
                    $this->document_html->addErrorMessage(str_replace("%s", $this->document_html->caption(), $this->document_html->RequiredErrorMessage));
                }
            }
            if ($this->document_data->Visible && $this->document_data->Required) {
                if (!$this->document_data->IsDetailKey && EmptyValue($this->document_data->FormValue)) {
                    $this->document_data->addErrorMessage(str_replace("%s", $this->document_data->caption(), $this->document_data->RequiredErrorMessage));
                }
            }
            if ($this->is_deleted->Visible && $this->is_deleted->Required) {
                if ($this->is_deleted->FormValue == "") {
                    $this->is_deleted->addErrorMessage(str_replace("%s", $this->is_deleted->caption(), $this->is_deleted->RequiredErrorMessage));
                }
            }
            if ($this->deletion_date->Visible && $this->deletion_date->Required) {
                if (!$this->deletion_date->IsDetailKey && EmptyValue($this->deletion_date->FormValue)) {
                    $this->deletion_date->addErrorMessage(str_replace("%s", $this->deletion_date->caption(), $this->deletion_date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->deletion_date->FormValue, $this->deletion_date->formatPattern())) {
                $this->deletion_date->addErrorMessage($this->deletion_date->getErrorMessage(false));
            }
            if ($this->deleted_by->Visible && $this->deleted_by->Required) {
                if (!$this->deleted_by->IsDetailKey && EmptyValue($this->deleted_by->FormValue)) {
                    $this->deleted_by->addErrorMessage(str_replace("%s", $this->deleted_by->caption(), $this->deleted_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->deleted_by->FormValue)) {
                $this->deleted_by->addErrorMessage($this->deleted_by->getErrorMessage(false));
            }
            if ($this->parent_document_id->Visible && $this->parent_document_id->Required) {
                if (!$this->parent_document_id->IsDetailKey && EmptyValue($this->parent_document_id->FormValue)) {
                    $this->parent_document_id->addErrorMessage(str_replace("%s", $this->parent_document_id->caption(), $this->parent_document_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->parent_document_id->FormValue)) {
                $this->parent_document_id->addErrorMessage($this->parent_document_id->getErrorMessage(false));
            }
            if ($this->version->Visible && $this->version->Required) {
                if (!$this->version->IsDetailKey && EmptyValue($this->version->FormValue)) {
                    $this->version->addErrorMessage(str_replace("%s", $this->version->caption(), $this->version->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->version->FormValue)) {
                $this->version->addErrorMessage($this->version->getErrorMessage(false));
            }
            if ($this->notes->Visible && $this->notes->Required) {
                if (!$this->notes->IsDetailKey && EmptyValue($this->notes->FormValue)) {
                    $this->notes->addErrorMessage(str_replace("%s", $this->notes->caption(), $this->notes->RequiredErrorMessage));
                }
            }
            if ($this->status_id->Visible && $this->status_id->Required) {
                if (!$this->status_id->IsDetailKey && EmptyValue($this->status_id->FormValue)) {
                    $this->status_id->addErrorMessage(str_replace("%s", $this->status_id->caption(), $this->status_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->status_id->FormValue)) {
                $this->status_id->addErrorMessage($this->status_id->getErrorMessage(false));
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

        // Check field with unique index (document_reference)
        if ($this->document_reference->CurrentValue != "") {
            $filterChk = "(\"document_reference\" = '" . AdjustSql($this->document_reference->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->document_reference->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->document_reference->CurrentValue, $idxErrMsg);
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

        // template_id
        $this->template_id->setDbValueDef($rsnew, $this->template_id->CurrentValue, $this->template_id->ReadOnly);

        // document_title
        $this->document_title->setDbValueDef($rsnew, $this->document_title->CurrentValue, $this->document_title->ReadOnly);

        // document_reference
        $this->document_reference->setDbValueDef($rsnew, $this->document_reference->CurrentValue, $this->document_reference->ReadOnly);

        // status
        $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, $this->status->ReadOnly);

        // created_at
        $this->created_at->setDbValueDef($rsnew, UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()), $this->created_at->ReadOnly);

        // updated_at
        $this->updated_at->setDbValueDef($rsnew, UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()), $this->updated_at->ReadOnly);

        // submitted_at
        $this->submitted_at->setDbValueDef($rsnew, UnFormatDateTime($this->submitted_at->CurrentValue, $this->submitted_at->formatPattern()), $this->submitted_at->ReadOnly);

        // company_name
        $this->company_name->setDbValueDef($rsnew, $this->company_name->CurrentValue, $this->company_name->ReadOnly);

        // customs_entry_number
        $this->customs_entry_number->setDbValueDef($rsnew, $this->customs_entry_number->CurrentValue, $this->customs_entry_number->ReadOnly);

        // date_of_entry
        $this->date_of_entry->setDbValueDef($rsnew, UnFormatDateTime($this->date_of_entry->CurrentValue, $this->date_of_entry->formatPattern()), $this->date_of_entry->ReadOnly);

        // document_html
        $this->document_html->setDbValueDef($rsnew, $this->document_html->CurrentValue, $this->document_html->ReadOnly);

        // document_data
        $this->document_data->setDbValueDef($rsnew, $this->document_data->CurrentValue, $this->document_data->ReadOnly);

        // is_deleted
        $tmpBool = $this->is_deleted->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_deleted->setDbValueDef($rsnew, $tmpBool, $this->is_deleted->ReadOnly);

        // deletion_date
        $this->deletion_date->setDbValueDef($rsnew, UnFormatDateTime($this->deletion_date->CurrentValue, $this->deletion_date->formatPattern()), $this->deletion_date->ReadOnly);

        // deleted_by
        $this->deleted_by->setDbValueDef($rsnew, $this->deleted_by->CurrentValue, $this->deleted_by->ReadOnly);

        // parent_document_id
        $this->parent_document_id->setDbValueDef($rsnew, $this->parent_document_id->CurrentValue, $this->parent_document_id->ReadOnly);

        // version
        $this->version->setDbValueDef($rsnew, $this->version->CurrentValue, $this->version->ReadOnly);

        // notes
        $this->notes->setDbValueDef($rsnew, $this->notes->CurrentValue, $this->notes->ReadOnly);

        // status_id
        $this->status_id->setDbValueDef($rsnew, $this->status_id->CurrentValue, $this->status_id->ReadOnly);
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
        if (isset($row['template_id'])) { // template_id
            $this->template_id->CurrentValue = $row['template_id'];
        }
        if (isset($row['document_title'])) { // document_title
            $this->document_title->CurrentValue = $row['document_title'];
        }
        if (isset($row['document_reference'])) { // document_reference
            $this->document_reference->CurrentValue = $row['document_reference'];
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
        if (isset($row['submitted_at'])) { // submitted_at
            $this->submitted_at->CurrentValue = $row['submitted_at'];
        }
        if (isset($row['company_name'])) { // company_name
            $this->company_name->CurrentValue = $row['company_name'];
        }
        if (isset($row['customs_entry_number'])) { // customs_entry_number
            $this->customs_entry_number->CurrentValue = $row['customs_entry_number'];
        }
        if (isset($row['date_of_entry'])) { // date_of_entry
            $this->date_of_entry->CurrentValue = $row['date_of_entry'];
        }
        if (isset($row['document_html'])) { // document_html
            $this->document_html->CurrentValue = $row['document_html'];
        }
        if (isset($row['document_data'])) { // document_data
            $this->document_data->CurrentValue = $row['document_data'];
        }
        if (isset($row['is_deleted'])) { // is_deleted
            $this->is_deleted->CurrentValue = $row['is_deleted'];
        }
        if (isset($row['deletion_date'])) { // deletion_date
            $this->deletion_date->CurrentValue = $row['deletion_date'];
        }
        if (isset($row['deleted_by'])) { // deleted_by
            $this->deleted_by->CurrentValue = $row['deleted_by'];
        }
        if (isset($row['parent_document_id'])) { // parent_document_id
            $this->parent_document_id->CurrentValue = $row['parent_document_id'];
        }
        if (isset($row['version'])) { // version
            $this->version->CurrentValue = $row['version'];
        }
        if (isset($row['notes'])) { // notes
            $this->notes->CurrentValue = $row['notes'];
        }
        if (isset($row['status_id'])) { // status_id
            $this->status_id->CurrentValue = $row['status_id'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("DocumentsList"), "", $this->TableVar, true);
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
                case "x_is_deleted":
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
