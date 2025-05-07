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
class PdfMetadataEdit extends PdfMetadata
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "PdfMetadataEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "PdfMetadataEdit";

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
        $this->metadata_id->setVisibility();
        $this->document_id->setVisibility();
        $this->notarized_id->setVisibility();
        $this->pdf_type->setVisibility();
        $this->file_path->setVisibility();
        $this->file_size->setVisibility();
        $this->page_count->setVisibility();
        $this->generated_at->setVisibility();
        $this->generated_by->setVisibility();
        $this->expires_at->setVisibility();
        $this->is_final->setVisibility();
        $this->processing_options->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'pdf_metadata';
        $this->TableName = 'pdf_metadata';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (pdf_metadata)
        if (!isset($GLOBALS["pdf_metadata"]) || $GLOBALS["pdf_metadata"]::class == PROJECT_NAMESPACE . "pdf_metadata") {
            $GLOBALS["pdf_metadata"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'pdf_metadata');
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
                        $result["view"] = SameString($pageName, "PdfMetadataView"); // If View page, no primary button
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
            $key .= @$ar['metadata_id'];
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
            $this->metadata_id->Visible = false;
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
        $this->setupLookupOptions($this->is_final);

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
            if (($keyValue = Get("metadata_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->metadata_id->setQueryStringValue($keyValue);
                $this->metadata_id->setOldValue($this->metadata_id->QueryStringValue);
            } elseif (Post("metadata_id") !== null) {
                $this->metadata_id->setFormValue(Post("metadata_id"));
                $this->metadata_id->setOldValue($this->metadata_id->FormValue);
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
                if (($keyValue = Get("metadata_id") ?? Route("metadata_id")) !== null) {
                    $this->metadata_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->metadata_id->CurrentValue = null;
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
                        $this->terminate("PdfMetadataList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "PdfMetadataList") {
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
                        if (GetPageName($returnUrl) != "PdfMetadataList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "PdfMetadataList"; // Return list page content
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

        // Check field name 'metadata_id' first before field var 'x_metadata_id'
        $val = $CurrentForm->hasValue("metadata_id") ? $CurrentForm->getValue("metadata_id") : $CurrentForm->getValue("x_metadata_id");
        if (!$this->metadata_id->IsDetailKey) {
            $this->metadata_id->setFormValue($val);
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

        // Check field name 'notarized_id' first before field var 'x_notarized_id'
        $val = $CurrentForm->hasValue("notarized_id") ? $CurrentForm->getValue("notarized_id") : $CurrentForm->getValue("x_notarized_id");
        if (!$this->notarized_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notarized_id->Visible = false; // Disable update for API request
            } else {
                $this->notarized_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'pdf_type' first before field var 'x_pdf_type'
        $val = $CurrentForm->hasValue("pdf_type") ? $CurrentForm->getValue("pdf_type") : $CurrentForm->getValue("x_pdf_type");
        if (!$this->pdf_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pdf_type->Visible = false; // Disable update for API request
            } else {
                $this->pdf_type->setFormValue($val);
            }
        }

        // Check field name 'file_path' first before field var 'x_file_path'
        $val = $CurrentForm->hasValue("file_path") ? $CurrentForm->getValue("file_path") : $CurrentForm->getValue("x_file_path");
        if (!$this->file_path->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_path->Visible = false; // Disable update for API request
            } else {
                $this->file_path->setFormValue($val);
            }
        }

        // Check field name 'file_size' first before field var 'x_file_size'
        $val = $CurrentForm->hasValue("file_size") ? $CurrentForm->getValue("file_size") : $CurrentForm->getValue("x_file_size");
        if (!$this->file_size->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_size->Visible = false; // Disable update for API request
            } else {
                $this->file_size->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'page_count' first before field var 'x_page_count'
        $val = $CurrentForm->hasValue("page_count") ? $CurrentForm->getValue("page_count") : $CurrentForm->getValue("x_page_count");
        if (!$this->page_count->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->page_count->Visible = false; // Disable update for API request
            } else {
                $this->page_count->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'generated_at' first before field var 'x_generated_at'
        $val = $CurrentForm->hasValue("generated_at") ? $CurrentForm->getValue("generated_at") : $CurrentForm->getValue("x_generated_at");
        if (!$this->generated_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->generated_at->Visible = false; // Disable update for API request
            } else {
                $this->generated_at->setFormValue($val, true, $validate);
            }
            $this->generated_at->CurrentValue = UnFormatDateTime($this->generated_at->CurrentValue, $this->generated_at->formatPattern());
        }

        // Check field name 'generated_by' first before field var 'x_generated_by'
        $val = $CurrentForm->hasValue("generated_by") ? $CurrentForm->getValue("generated_by") : $CurrentForm->getValue("x_generated_by");
        if (!$this->generated_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->generated_by->Visible = false; // Disable update for API request
            } else {
                $this->generated_by->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'expires_at' first before field var 'x_expires_at'
        $val = $CurrentForm->hasValue("expires_at") ? $CurrentForm->getValue("expires_at") : $CurrentForm->getValue("x_expires_at");
        if (!$this->expires_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->expires_at->Visible = false; // Disable update for API request
            } else {
                $this->expires_at->setFormValue($val, true, $validate);
            }
            $this->expires_at->CurrentValue = UnFormatDateTime($this->expires_at->CurrentValue, $this->expires_at->formatPattern());
        }

        // Check field name 'is_final' first before field var 'x_is_final'
        $val = $CurrentForm->hasValue("is_final") ? $CurrentForm->getValue("is_final") : $CurrentForm->getValue("x_is_final");
        if (!$this->is_final->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_final->Visible = false; // Disable update for API request
            } else {
                $this->is_final->setFormValue($val);
            }
        }

        // Check field name 'processing_options' first before field var 'x_processing_options'
        $val = $CurrentForm->hasValue("processing_options") ? $CurrentForm->getValue("processing_options") : $CurrentForm->getValue("x_processing_options");
        if (!$this->processing_options->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->processing_options->Visible = false; // Disable update for API request
            } else {
                $this->processing_options->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->metadata_id->CurrentValue = $this->metadata_id->FormValue;
        $this->document_id->CurrentValue = $this->document_id->FormValue;
        $this->notarized_id->CurrentValue = $this->notarized_id->FormValue;
        $this->pdf_type->CurrentValue = $this->pdf_type->FormValue;
        $this->file_path->CurrentValue = $this->file_path->FormValue;
        $this->file_size->CurrentValue = $this->file_size->FormValue;
        $this->page_count->CurrentValue = $this->page_count->FormValue;
        $this->generated_at->CurrentValue = $this->generated_at->FormValue;
        $this->generated_at->CurrentValue = UnFormatDateTime($this->generated_at->CurrentValue, $this->generated_at->formatPattern());
        $this->generated_by->CurrentValue = $this->generated_by->FormValue;
        $this->expires_at->CurrentValue = $this->expires_at->FormValue;
        $this->expires_at->CurrentValue = UnFormatDateTime($this->expires_at->CurrentValue, $this->expires_at->formatPattern());
        $this->is_final->CurrentValue = $this->is_final->FormValue;
        $this->processing_options->CurrentValue = $this->processing_options->FormValue;
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
        $this->metadata_id->setDbValue($row['metadata_id']);
        $this->document_id->setDbValue($row['document_id']);
        $this->notarized_id->setDbValue($row['notarized_id']);
        $this->pdf_type->setDbValue($row['pdf_type']);
        $this->file_path->setDbValue($row['file_path']);
        $this->file_size->setDbValue($row['file_size']);
        $this->page_count->setDbValue($row['page_count']);
        $this->generated_at->setDbValue($row['generated_at']);
        $this->generated_by->setDbValue($row['generated_by']);
        $this->expires_at->setDbValue($row['expires_at']);
        $this->is_final->setDbValue((ConvertToBool($row['is_final']) ? "1" : "0"));
        $this->processing_options->setDbValue($row['processing_options']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['metadata_id'] = $this->metadata_id->DefaultValue;
        $row['document_id'] = $this->document_id->DefaultValue;
        $row['notarized_id'] = $this->notarized_id->DefaultValue;
        $row['pdf_type'] = $this->pdf_type->DefaultValue;
        $row['file_path'] = $this->file_path->DefaultValue;
        $row['file_size'] = $this->file_size->DefaultValue;
        $row['page_count'] = $this->page_count->DefaultValue;
        $row['generated_at'] = $this->generated_at->DefaultValue;
        $row['generated_by'] = $this->generated_by->DefaultValue;
        $row['expires_at'] = $this->expires_at->DefaultValue;
        $row['is_final'] = $this->is_final->DefaultValue;
        $row['processing_options'] = $this->processing_options->DefaultValue;
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

        // metadata_id
        $this->metadata_id->RowCssClass = "row";

        // document_id
        $this->document_id->RowCssClass = "row";

        // notarized_id
        $this->notarized_id->RowCssClass = "row";

        // pdf_type
        $this->pdf_type->RowCssClass = "row";

        // file_path
        $this->file_path->RowCssClass = "row";

        // file_size
        $this->file_size->RowCssClass = "row";

        // page_count
        $this->page_count->RowCssClass = "row";

        // generated_at
        $this->generated_at->RowCssClass = "row";

        // generated_by
        $this->generated_by->RowCssClass = "row";

        // expires_at
        $this->expires_at->RowCssClass = "row";

        // is_final
        $this->is_final->RowCssClass = "row";

        // processing_options
        $this->processing_options->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // metadata_id
            $this->metadata_id->ViewValue = $this->metadata_id->CurrentValue;

            // document_id
            $this->document_id->ViewValue = $this->document_id->CurrentValue;
            $this->document_id->ViewValue = FormatNumber($this->document_id->ViewValue, $this->document_id->formatPattern());

            // notarized_id
            $this->notarized_id->ViewValue = $this->notarized_id->CurrentValue;
            $this->notarized_id->ViewValue = FormatNumber($this->notarized_id->ViewValue, $this->notarized_id->formatPattern());

            // pdf_type
            $this->pdf_type->ViewValue = $this->pdf_type->CurrentValue;

            // file_path
            $this->file_path->ViewValue = $this->file_path->CurrentValue;

            // file_size
            $this->file_size->ViewValue = $this->file_size->CurrentValue;
            $this->file_size->ViewValue = FormatNumber($this->file_size->ViewValue, $this->file_size->formatPattern());

            // page_count
            $this->page_count->ViewValue = $this->page_count->CurrentValue;
            $this->page_count->ViewValue = FormatNumber($this->page_count->ViewValue, $this->page_count->formatPattern());

            // generated_at
            $this->generated_at->ViewValue = $this->generated_at->CurrentValue;
            $this->generated_at->ViewValue = FormatDateTime($this->generated_at->ViewValue, $this->generated_at->formatPattern());

            // generated_by
            $this->generated_by->ViewValue = $this->generated_by->CurrentValue;
            $this->generated_by->ViewValue = FormatNumber($this->generated_by->ViewValue, $this->generated_by->formatPattern());

            // expires_at
            $this->expires_at->ViewValue = $this->expires_at->CurrentValue;
            $this->expires_at->ViewValue = FormatDateTime($this->expires_at->ViewValue, $this->expires_at->formatPattern());

            // is_final
            if (ConvertToBool($this->is_final->CurrentValue)) {
                $this->is_final->ViewValue = $this->is_final->tagCaption(1) != "" ? $this->is_final->tagCaption(1) : "Yes";
            } else {
                $this->is_final->ViewValue = $this->is_final->tagCaption(2) != "" ? $this->is_final->tagCaption(2) : "No";
            }

            // processing_options
            $this->processing_options->ViewValue = $this->processing_options->CurrentValue;

            // metadata_id
            $this->metadata_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // notarized_id
            $this->notarized_id->HrefValue = "";

            // pdf_type
            $this->pdf_type->HrefValue = "";

            // file_path
            $this->file_path->HrefValue = "";

            // file_size
            $this->file_size->HrefValue = "";

            // page_count
            $this->page_count->HrefValue = "";

            // generated_at
            $this->generated_at->HrefValue = "";

            // generated_by
            $this->generated_by->HrefValue = "";

            // expires_at
            $this->expires_at->HrefValue = "";

            // is_final
            $this->is_final->HrefValue = "";

            // processing_options
            $this->processing_options->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // metadata_id
            $this->metadata_id->setupEditAttributes();
            $this->metadata_id->EditValue = $this->metadata_id->CurrentValue;

            // document_id
            $this->document_id->setupEditAttributes();
            $this->document_id->EditValue = $this->document_id->CurrentValue;
            $this->document_id->PlaceHolder = RemoveHtml($this->document_id->caption());
            if (strval($this->document_id->EditValue) != "" && is_numeric($this->document_id->EditValue)) {
                $this->document_id->EditValue = FormatNumber($this->document_id->EditValue, null);
            }

            // notarized_id
            $this->notarized_id->setupEditAttributes();
            $this->notarized_id->EditValue = $this->notarized_id->CurrentValue;
            $this->notarized_id->PlaceHolder = RemoveHtml($this->notarized_id->caption());
            if (strval($this->notarized_id->EditValue) != "" && is_numeric($this->notarized_id->EditValue)) {
                $this->notarized_id->EditValue = FormatNumber($this->notarized_id->EditValue, null);
            }

            // pdf_type
            $this->pdf_type->setupEditAttributes();
            if (!$this->pdf_type->Raw) {
                $this->pdf_type->CurrentValue = HtmlDecode($this->pdf_type->CurrentValue);
            }
            $this->pdf_type->EditValue = HtmlEncode($this->pdf_type->CurrentValue);
            $this->pdf_type->PlaceHolder = RemoveHtml($this->pdf_type->caption());

            // file_path
            $this->file_path->setupEditAttributes();
            if (!$this->file_path->Raw) {
                $this->file_path->CurrentValue = HtmlDecode($this->file_path->CurrentValue);
            }
            $this->file_path->EditValue = HtmlEncode($this->file_path->CurrentValue);
            $this->file_path->PlaceHolder = RemoveHtml($this->file_path->caption());

            // file_size
            $this->file_size->setupEditAttributes();
            $this->file_size->EditValue = $this->file_size->CurrentValue;
            $this->file_size->PlaceHolder = RemoveHtml($this->file_size->caption());
            if (strval($this->file_size->EditValue) != "" && is_numeric($this->file_size->EditValue)) {
                $this->file_size->EditValue = FormatNumber($this->file_size->EditValue, null);
            }

            // page_count
            $this->page_count->setupEditAttributes();
            $this->page_count->EditValue = $this->page_count->CurrentValue;
            $this->page_count->PlaceHolder = RemoveHtml($this->page_count->caption());
            if (strval($this->page_count->EditValue) != "" && is_numeric($this->page_count->EditValue)) {
                $this->page_count->EditValue = FormatNumber($this->page_count->EditValue, null);
            }

            // generated_at
            $this->generated_at->setupEditAttributes();
            $this->generated_at->EditValue = HtmlEncode(FormatDateTime($this->generated_at->CurrentValue, $this->generated_at->formatPattern()));
            $this->generated_at->PlaceHolder = RemoveHtml($this->generated_at->caption());

            // generated_by
            $this->generated_by->setupEditAttributes();
            $this->generated_by->EditValue = $this->generated_by->CurrentValue;
            $this->generated_by->PlaceHolder = RemoveHtml($this->generated_by->caption());
            if (strval($this->generated_by->EditValue) != "" && is_numeric($this->generated_by->EditValue)) {
                $this->generated_by->EditValue = FormatNumber($this->generated_by->EditValue, null);
            }

            // expires_at
            $this->expires_at->setupEditAttributes();
            $this->expires_at->EditValue = HtmlEncode(FormatDateTime($this->expires_at->CurrentValue, $this->expires_at->formatPattern()));
            $this->expires_at->PlaceHolder = RemoveHtml($this->expires_at->caption());

            // is_final
            $this->is_final->EditValue = $this->is_final->options(false);
            $this->is_final->PlaceHolder = RemoveHtml($this->is_final->caption());

            // processing_options
            $this->processing_options->setupEditAttributes();
            $this->processing_options->EditValue = HtmlEncode($this->processing_options->CurrentValue);
            $this->processing_options->PlaceHolder = RemoveHtml($this->processing_options->caption());

            // Edit refer script

            // metadata_id
            $this->metadata_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // notarized_id
            $this->notarized_id->HrefValue = "";

            // pdf_type
            $this->pdf_type->HrefValue = "";

            // file_path
            $this->file_path->HrefValue = "";

            // file_size
            $this->file_size->HrefValue = "";

            // page_count
            $this->page_count->HrefValue = "";

            // generated_at
            $this->generated_at->HrefValue = "";

            // generated_by
            $this->generated_by->HrefValue = "";

            // expires_at
            $this->expires_at->HrefValue = "";

            // is_final
            $this->is_final->HrefValue = "";

            // processing_options
            $this->processing_options->HrefValue = "";
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
            if ($this->metadata_id->Visible && $this->metadata_id->Required) {
                if (!$this->metadata_id->IsDetailKey && EmptyValue($this->metadata_id->FormValue)) {
                    $this->metadata_id->addErrorMessage(str_replace("%s", $this->metadata_id->caption(), $this->metadata_id->RequiredErrorMessage));
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
            if ($this->notarized_id->Visible && $this->notarized_id->Required) {
                if (!$this->notarized_id->IsDetailKey && EmptyValue($this->notarized_id->FormValue)) {
                    $this->notarized_id->addErrorMessage(str_replace("%s", $this->notarized_id->caption(), $this->notarized_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->notarized_id->FormValue)) {
                $this->notarized_id->addErrorMessage($this->notarized_id->getErrorMessage(false));
            }
            if ($this->pdf_type->Visible && $this->pdf_type->Required) {
                if (!$this->pdf_type->IsDetailKey && EmptyValue($this->pdf_type->FormValue)) {
                    $this->pdf_type->addErrorMessage(str_replace("%s", $this->pdf_type->caption(), $this->pdf_type->RequiredErrorMessage));
                }
            }
            if ($this->file_path->Visible && $this->file_path->Required) {
                if (!$this->file_path->IsDetailKey && EmptyValue($this->file_path->FormValue)) {
                    $this->file_path->addErrorMessage(str_replace("%s", $this->file_path->caption(), $this->file_path->RequiredErrorMessage));
                }
            }
            if ($this->file_size->Visible && $this->file_size->Required) {
                if (!$this->file_size->IsDetailKey && EmptyValue($this->file_size->FormValue)) {
                    $this->file_size->addErrorMessage(str_replace("%s", $this->file_size->caption(), $this->file_size->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->file_size->FormValue)) {
                $this->file_size->addErrorMessage($this->file_size->getErrorMessage(false));
            }
            if ($this->page_count->Visible && $this->page_count->Required) {
                if (!$this->page_count->IsDetailKey && EmptyValue($this->page_count->FormValue)) {
                    $this->page_count->addErrorMessage(str_replace("%s", $this->page_count->caption(), $this->page_count->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->page_count->FormValue)) {
                $this->page_count->addErrorMessage($this->page_count->getErrorMessage(false));
            }
            if ($this->generated_at->Visible && $this->generated_at->Required) {
                if (!$this->generated_at->IsDetailKey && EmptyValue($this->generated_at->FormValue)) {
                    $this->generated_at->addErrorMessage(str_replace("%s", $this->generated_at->caption(), $this->generated_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->generated_at->FormValue, $this->generated_at->formatPattern())) {
                $this->generated_at->addErrorMessage($this->generated_at->getErrorMessage(false));
            }
            if ($this->generated_by->Visible && $this->generated_by->Required) {
                if (!$this->generated_by->IsDetailKey && EmptyValue($this->generated_by->FormValue)) {
                    $this->generated_by->addErrorMessage(str_replace("%s", $this->generated_by->caption(), $this->generated_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->generated_by->FormValue)) {
                $this->generated_by->addErrorMessage($this->generated_by->getErrorMessage(false));
            }
            if ($this->expires_at->Visible && $this->expires_at->Required) {
                if (!$this->expires_at->IsDetailKey && EmptyValue($this->expires_at->FormValue)) {
                    $this->expires_at->addErrorMessage(str_replace("%s", $this->expires_at->caption(), $this->expires_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->expires_at->FormValue, $this->expires_at->formatPattern())) {
                $this->expires_at->addErrorMessage($this->expires_at->getErrorMessage(false));
            }
            if ($this->is_final->Visible && $this->is_final->Required) {
                if ($this->is_final->FormValue == "") {
                    $this->is_final->addErrorMessage(str_replace("%s", $this->is_final->caption(), $this->is_final->RequiredErrorMessage));
                }
            }
            if ($this->processing_options->Visible && $this->processing_options->Required) {
                if (!$this->processing_options->IsDetailKey && EmptyValue($this->processing_options->FormValue)) {
                    $this->processing_options->addErrorMessage(str_replace("%s", $this->processing_options->caption(), $this->processing_options->RequiredErrorMessage));
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

        // document_id
        $this->document_id->setDbValueDef($rsnew, $this->document_id->CurrentValue, $this->document_id->ReadOnly);

        // notarized_id
        $this->notarized_id->setDbValueDef($rsnew, $this->notarized_id->CurrentValue, $this->notarized_id->ReadOnly);

        // pdf_type
        $this->pdf_type->setDbValueDef($rsnew, $this->pdf_type->CurrentValue, $this->pdf_type->ReadOnly);

        // file_path
        $this->file_path->setDbValueDef($rsnew, $this->file_path->CurrentValue, $this->file_path->ReadOnly);

        // file_size
        $this->file_size->setDbValueDef($rsnew, $this->file_size->CurrentValue, $this->file_size->ReadOnly);

        // page_count
        $this->page_count->setDbValueDef($rsnew, $this->page_count->CurrentValue, $this->page_count->ReadOnly);

        // generated_at
        $this->generated_at->setDbValueDef($rsnew, UnFormatDateTime($this->generated_at->CurrentValue, $this->generated_at->formatPattern()), $this->generated_at->ReadOnly);

        // generated_by
        $this->generated_by->setDbValueDef($rsnew, $this->generated_by->CurrentValue, $this->generated_by->ReadOnly);

        // expires_at
        $this->expires_at->setDbValueDef($rsnew, UnFormatDateTime($this->expires_at->CurrentValue, $this->expires_at->formatPattern()), $this->expires_at->ReadOnly);

        // is_final
        $tmpBool = $this->is_final->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_final->setDbValueDef($rsnew, $tmpBool, $this->is_final->ReadOnly);

        // processing_options
        $this->processing_options->setDbValueDef($rsnew, $this->processing_options->CurrentValue, $this->processing_options->ReadOnly);
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
        if (isset($row['notarized_id'])) { // notarized_id
            $this->notarized_id->CurrentValue = $row['notarized_id'];
        }
        if (isset($row['pdf_type'])) { // pdf_type
            $this->pdf_type->CurrentValue = $row['pdf_type'];
        }
        if (isset($row['file_path'])) { // file_path
            $this->file_path->CurrentValue = $row['file_path'];
        }
        if (isset($row['file_size'])) { // file_size
            $this->file_size->CurrentValue = $row['file_size'];
        }
        if (isset($row['page_count'])) { // page_count
            $this->page_count->CurrentValue = $row['page_count'];
        }
        if (isset($row['generated_at'])) { // generated_at
            $this->generated_at->CurrentValue = $row['generated_at'];
        }
        if (isset($row['generated_by'])) { // generated_by
            $this->generated_by->CurrentValue = $row['generated_by'];
        }
        if (isset($row['expires_at'])) { // expires_at
            $this->expires_at->CurrentValue = $row['expires_at'];
        }
        if (isset($row['is_final'])) { // is_final
            $this->is_final->CurrentValue = $row['is_final'];
        }
        if (isset($row['processing_options'])) { // processing_options
            $this->processing_options->CurrentValue = $row['processing_options'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("PdfMetadataList"), "", $this->TableVar, true);
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
                case "x_is_final":
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
