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
class DocumentAttachmentsEdit extends DocumentAttachments
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "DocumentAttachmentsEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "DocumentAttachmentsEdit";

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
        $this->attachment_id->setVisibility();
        $this->document_id->setVisibility();
        $this->file_name->setVisibility();
        $this->file_path->setVisibility();
        $this->file_type->setVisibility();
        $this->file_size->setVisibility();
        $this->uploaded_at->setVisibility();
        $this->uploaded_by->setVisibility();
        $this->description->setVisibility();
        $this->is_supporting->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'document_attachments';
        $this->TableName = 'document_attachments';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (document_attachments)
        if (!isset($GLOBALS["document_attachments"]) || $GLOBALS["document_attachments"]::class == PROJECT_NAMESPACE . "document_attachments") {
            $GLOBALS["document_attachments"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'document_attachments');
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
                        $result["view"] = SameString($pageName, "DocumentAttachmentsView"); // If View page, no primary button
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
            $key .= @$ar['attachment_id'];
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
            $this->attachment_id->Visible = false;
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
        $this->setupLookupOptions($this->is_supporting);

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
            if (($keyValue = Get("attachment_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->attachment_id->setQueryStringValue($keyValue);
                $this->attachment_id->setOldValue($this->attachment_id->QueryStringValue);
            } elseif (Post("attachment_id") !== null) {
                $this->attachment_id->setFormValue(Post("attachment_id"));
                $this->attachment_id->setOldValue($this->attachment_id->FormValue);
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
                if (($keyValue = Get("attachment_id") ?? Route("attachment_id")) !== null) {
                    $this->attachment_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->attachment_id->CurrentValue = null;
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
                        $this->terminate("DocumentAttachmentsList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "DocumentAttachmentsList") {
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
                        if (GetPageName($returnUrl) != "DocumentAttachmentsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "DocumentAttachmentsList"; // Return list page content
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

        // Check field name 'attachment_id' first before field var 'x_attachment_id'
        $val = $CurrentForm->hasValue("attachment_id") ? $CurrentForm->getValue("attachment_id") : $CurrentForm->getValue("x_attachment_id");
        if (!$this->attachment_id->IsDetailKey) {
            $this->attachment_id->setFormValue($val);
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

        // Check field name 'file_name' first before field var 'x_file_name'
        $val = $CurrentForm->hasValue("file_name") ? $CurrentForm->getValue("file_name") : $CurrentForm->getValue("x_file_name");
        if (!$this->file_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_name->Visible = false; // Disable update for API request
            } else {
                $this->file_name->setFormValue($val);
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

        // Check field name 'file_type' first before field var 'x_file_type'
        $val = $CurrentForm->hasValue("file_type") ? $CurrentForm->getValue("file_type") : $CurrentForm->getValue("x_file_type");
        if (!$this->file_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_type->Visible = false; // Disable update for API request
            } else {
                $this->file_type->setFormValue($val);
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

        // Check field name 'uploaded_at' first before field var 'x_uploaded_at'
        $val = $CurrentForm->hasValue("uploaded_at") ? $CurrentForm->getValue("uploaded_at") : $CurrentForm->getValue("x_uploaded_at");
        if (!$this->uploaded_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->uploaded_at->Visible = false; // Disable update for API request
            } else {
                $this->uploaded_at->setFormValue($val, true, $validate);
            }
            $this->uploaded_at->CurrentValue = UnFormatDateTime($this->uploaded_at->CurrentValue, $this->uploaded_at->formatPattern());
        }

        // Check field name 'uploaded_by' first before field var 'x_uploaded_by'
        $val = $CurrentForm->hasValue("uploaded_by") ? $CurrentForm->getValue("uploaded_by") : $CurrentForm->getValue("x_uploaded_by");
        if (!$this->uploaded_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->uploaded_by->Visible = false; // Disable update for API request
            } else {
                $this->uploaded_by->setFormValue($val, true, $validate);
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

        // Check field name 'is_supporting' first before field var 'x_is_supporting'
        $val = $CurrentForm->hasValue("is_supporting") ? $CurrentForm->getValue("is_supporting") : $CurrentForm->getValue("x_is_supporting");
        if (!$this->is_supporting->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->is_supporting->Visible = false; // Disable update for API request
            } else {
                $this->is_supporting->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->attachment_id->CurrentValue = $this->attachment_id->FormValue;
        $this->document_id->CurrentValue = $this->document_id->FormValue;
        $this->file_name->CurrentValue = $this->file_name->FormValue;
        $this->file_path->CurrentValue = $this->file_path->FormValue;
        $this->file_type->CurrentValue = $this->file_type->FormValue;
        $this->file_size->CurrentValue = $this->file_size->FormValue;
        $this->uploaded_at->CurrentValue = $this->uploaded_at->FormValue;
        $this->uploaded_at->CurrentValue = UnFormatDateTime($this->uploaded_at->CurrentValue, $this->uploaded_at->formatPattern());
        $this->uploaded_by->CurrentValue = $this->uploaded_by->FormValue;
        $this->description->CurrentValue = $this->description->FormValue;
        $this->is_supporting->CurrentValue = $this->is_supporting->FormValue;
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
        $this->attachment_id->setDbValue($row['attachment_id']);
        $this->document_id->setDbValue($row['document_id']);
        $this->file_name->setDbValue($row['file_name']);
        $this->file_path->setDbValue($row['file_path']);
        $this->file_type->setDbValue($row['file_type']);
        $this->file_size->setDbValue($row['file_size']);
        $this->uploaded_at->setDbValue($row['uploaded_at']);
        $this->uploaded_by->setDbValue($row['uploaded_by']);
        $this->description->setDbValue($row['description']);
        $this->is_supporting->setDbValue((ConvertToBool($row['is_supporting']) ? "1" : "0"));
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['attachment_id'] = $this->attachment_id->DefaultValue;
        $row['document_id'] = $this->document_id->DefaultValue;
        $row['file_name'] = $this->file_name->DefaultValue;
        $row['file_path'] = $this->file_path->DefaultValue;
        $row['file_type'] = $this->file_type->DefaultValue;
        $row['file_size'] = $this->file_size->DefaultValue;
        $row['uploaded_at'] = $this->uploaded_at->DefaultValue;
        $row['uploaded_by'] = $this->uploaded_by->DefaultValue;
        $row['description'] = $this->description->DefaultValue;
        $row['is_supporting'] = $this->is_supporting->DefaultValue;
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

        // attachment_id
        $this->attachment_id->RowCssClass = "row";

        // document_id
        $this->document_id->RowCssClass = "row";

        // file_name
        $this->file_name->RowCssClass = "row";

        // file_path
        $this->file_path->RowCssClass = "row";

        // file_type
        $this->file_type->RowCssClass = "row";

        // file_size
        $this->file_size->RowCssClass = "row";

        // uploaded_at
        $this->uploaded_at->RowCssClass = "row";

        // uploaded_by
        $this->uploaded_by->RowCssClass = "row";

        // description
        $this->description->RowCssClass = "row";

        // is_supporting
        $this->is_supporting->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // attachment_id
            $this->attachment_id->ViewValue = $this->attachment_id->CurrentValue;

            // document_id
            $this->document_id->ViewValue = $this->document_id->CurrentValue;
            $this->document_id->ViewValue = FormatNumber($this->document_id->ViewValue, $this->document_id->formatPattern());

            // file_name
            $this->file_name->ViewValue = $this->file_name->CurrentValue;

            // file_path
            $this->file_path->ViewValue = $this->file_path->CurrentValue;

            // file_type
            $this->file_type->ViewValue = $this->file_type->CurrentValue;

            // file_size
            $this->file_size->ViewValue = $this->file_size->CurrentValue;
            $this->file_size->ViewValue = FormatNumber($this->file_size->ViewValue, $this->file_size->formatPattern());

            // uploaded_at
            $this->uploaded_at->ViewValue = $this->uploaded_at->CurrentValue;
            $this->uploaded_at->ViewValue = FormatDateTime($this->uploaded_at->ViewValue, $this->uploaded_at->formatPattern());

            // uploaded_by
            $this->uploaded_by->ViewValue = $this->uploaded_by->CurrentValue;
            $this->uploaded_by->ViewValue = FormatNumber($this->uploaded_by->ViewValue, $this->uploaded_by->formatPattern());

            // description
            $this->description->ViewValue = $this->description->CurrentValue;

            // is_supporting
            if (ConvertToBool($this->is_supporting->CurrentValue)) {
                $this->is_supporting->ViewValue = $this->is_supporting->tagCaption(1) != "" ? $this->is_supporting->tagCaption(1) : "Yes";
            } else {
                $this->is_supporting->ViewValue = $this->is_supporting->tagCaption(2) != "" ? $this->is_supporting->tagCaption(2) : "No";
            }

            // attachment_id
            $this->attachment_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // file_name
            $this->file_name->HrefValue = "";

            // file_path
            $this->file_path->HrefValue = "";

            // file_type
            $this->file_type->HrefValue = "";

            // file_size
            $this->file_size->HrefValue = "";

            // uploaded_at
            $this->uploaded_at->HrefValue = "";

            // uploaded_by
            $this->uploaded_by->HrefValue = "";

            // description
            $this->description->HrefValue = "";

            // is_supporting
            $this->is_supporting->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // attachment_id
            $this->attachment_id->setupEditAttributes();
            $this->attachment_id->EditValue = $this->attachment_id->CurrentValue;

            // document_id
            $this->document_id->setupEditAttributes();
            $this->document_id->EditValue = $this->document_id->CurrentValue;
            $this->document_id->PlaceHolder = RemoveHtml($this->document_id->caption());
            if (strval($this->document_id->EditValue) != "" && is_numeric($this->document_id->EditValue)) {
                $this->document_id->EditValue = FormatNumber($this->document_id->EditValue, null);
            }

            // file_name
            $this->file_name->setupEditAttributes();
            if (!$this->file_name->Raw) {
                $this->file_name->CurrentValue = HtmlDecode($this->file_name->CurrentValue);
            }
            $this->file_name->EditValue = HtmlEncode($this->file_name->CurrentValue);
            $this->file_name->PlaceHolder = RemoveHtml($this->file_name->caption());

            // file_path
            $this->file_path->setupEditAttributes();
            if (!$this->file_path->Raw) {
                $this->file_path->CurrentValue = HtmlDecode($this->file_path->CurrentValue);
            }
            $this->file_path->EditValue = HtmlEncode($this->file_path->CurrentValue);
            $this->file_path->PlaceHolder = RemoveHtml($this->file_path->caption());

            // file_type
            $this->file_type->setupEditAttributes();
            if (!$this->file_type->Raw) {
                $this->file_type->CurrentValue = HtmlDecode($this->file_type->CurrentValue);
            }
            $this->file_type->EditValue = HtmlEncode($this->file_type->CurrentValue);
            $this->file_type->PlaceHolder = RemoveHtml($this->file_type->caption());

            // file_size
            $this->file_size->setupEditAttributes();
            $this->file_size->EditValue = $this->file_size->CurrentValue;
            $this->file_size->PlaceHolder = RemoveHtml($this->file_size->caption());
            if (strval($this->file_size->EditValue) != "" && is_numeric($this->file_size->EditValue)) {
                $this->file_size->EditValue = FormatNumber($this->file_size->EditValue, null);
            }

            // uploaded_at
            $this->uploaded_at->setupEditAttributes();
            $this->uploaded_at->EditValue = HtmlEncode(FormatDateTime($this->uploaded_at->CurrentValue, $this->uploaded_at->formatPattern()));
            $this->uploaded_at->PlaceHolder = RemoveHtml($this->uploaded_at->caption());

            // uploaded_by
            $this->uploaded_by->setupEditAttributes();
            $this->uploaded_by->EditValue = $this->uploaded_by->CurrentValue;
            $this->uploaded_by->PlaceHolder = RemoveHtml($this->uploaded_by->caption());
            if (strval($this->uploaded_by->EditValue) != "" && is_numeric($this->uploaded_by->EditValue)) {
                $this->uploaded_by->EditValue = FormatNumber($this->uploaded_by->EditValue, null);
            }

            // description
            $this->description->setupEditAttributes();
            $this->description->EditValue = HtmlEncode($this->description->CurrentValue);
            $this->description->PlaceHolder = RemoveHtml($this->description->caption());

            // is_supporting
            $this->is_supporting->EditValue = $this->is_supporting->options(false);
            $this->is_supporting->PlaceHolder = RemoveHtml($this->is_supporting->caption());

            // Edit refer script

            // attachment_id
            $this->attachment_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // file_name
            $this->file_name->HrefValue = "";

            // file_path
            $this->file_path->HrefValue = "";

            // file_type
            $this->file_type->HrefValue = "";

            // file_size
            $this->file_size->HrefValue = "";

            // uploaded_at
            $this->uploaded_at->HrefValue = "";

            // uploaded_by
            $this->uploaded_by->HrefValue = "";

            // description
            $this->description->HrefValue = "";

            // is_supporting
            $this->is_supporting->HrefValue = "";
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
            if ($this->attachment_id->Visible && $this->attachment_id->Required) {
                if (!$this->attachment_id->IsDetailKey && EmptyValue($this->attachment_id->FormValue)) {
                    $this->attachment_id->addErrorMessage(str_replace("%s", $this->attachment_id->caption(), $this->attachment_id->RequiredErrorMessage));
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
            if ($this->file_name->Visible && $this->file_name->Required) {
                if (!$this->file_name->IsDetailKey && EmptyValue($this->file_name->FormValue)) {
                    $this->file_name->addErrorMessage(str_replace("%s", $this->file_name->caption(), $this->file_name->RequiredErrorMessage));
                }
            }
            if ($this->file_path->Visible && $this->file_path->Required) {
                if (!$this->file_path->IsDetailKey && EmptyValue($this->file_path->FormValue)) {
                    $this->file_path->addErrorMessage(str_replace("%s", $this->file_path->caption(), $this->file_path->RequiredErrorMessage));
                }
            }
            if ($this->file_type->Visible && $this->file_type->Required) {
                if (!$this->file_type->IsDetailKey && EmptyValue($this->file_type->FormValue)) {
                    $this->file_type->addErrorMessage(str_replace("%s", $this->file_type->caption(), $this->file_type->RequiredErrorMessage));
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
            if ($this->uploaded_at->Visible && $this->uploaded_at->Required) {
                if (!$this->uploaded_at->IsDetailKey && EmptyValue($this->uploaded_at->FormValue)) {
                    $this->uploaded_at->addErrorMessage(str_replace("%s", $this->uploaded_at->caption(), $this->uploaded_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->uploaded_at->FormValue, $this->uploaded_at->formatPattern())) {
                $this->uploaded_at->addErrorMessage($this->uploaded_at->getErrorMessage(false));
            }
            if ($this->uploaded_by->Visible && $this->uploaded_by->Required) {
                if (!$this->uploaded_by->IsDetailKey && EmptyValue($this->uploaded_by->FormValue)) {
                    $this->uploaded_by->addErrorMessage(str_replace("%s", $this->uploaded_by->caption(), $this->uploaded_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->uploaded_by->FormValue)) {
                $this->uploaded_by->addErrorMessage($this->uploaded_by->getErrorMessage(false));
            }
            if ($this->description->Visible && $this->description->Required) {
                if (!$this->description->IsDetailKey && EmptyValue($this->description->FormValue)) {
                    $this->description->addErrorMessage(str_replace("%s", $this->description->caption(), $this->description->RequiredErrorMessage));
                }
            }
            if ($this->is_supporting->Visible && $this->is_supporting->Required) {
                if ($this->is_supporting->FormValue == "") {
                    $this->is_supporting->addErrorMessage(str_replace("%s", $this->is_supporting->caption(), $this->is_supporting->RequiredErrorMessage));
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

        // file_name
        $this->file_name->setDbValueDef($rsnew, $this->file_name->CurrentValue, $this->file_name->ReadOnly);

        // file_path
        $this->file_path->setDbValueDef($rsnew, $this->file_path->CurrentValue, $this->file_path->ReadOnly);

        // file_type
        $this->file_type->setDbValueDef($rsnew, $this->file_type->CurrentValue, $this->file_type->ReadOnly);

        // file_size
        $this->file_size->setDbValueDef($rsnew, $this->file_size->CurrentValue, $this->file_size->ReadOnly);

        // uploaded_at
        $this->uploaded_at->setDbValueDef($rsnew, UnFormatDateTime($this->uploaded_at->CurrentValue, $this->uploaded_at->formatPattern()), $this->uploaded_at->ReadOnly);

        // uploaded_by
        $this->uploaded_by->setDbValueDef($rsnew, $this->uploaded_by->CurrentValue, $this->uploaded_by->ReadOnly);

        // description
        $this->description->setDbValueDef($rsnew, $this->description->CurrentValue, $this->description->ReadOnly);

        // is_supporting
        $tmpBool = $this->is_supporting->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_supporting->setDbValueDef($rsnew, $tmpBool, $this->is_supporting->ReadOnly);
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
        if (isset($row['file_name'])) { // file_name
            $this->file_name->CurrentValue = $row['file_name'];
        }
        if (isset($row['file_path'])) { // file_path
            $this->file_path->CurrentValue = $row['file_path'];
        }
        if (isset($row['file_type'])) { // file_type
            $this->file_type->CurrentValue = $row['file_type'];
        }
        if (isset($row['file_size'])) { // file_size
            $this->file_size->CurrentValue = $row['file_size'];
        }
        if (isset($row['uploaded_at'])) { // uploaded_at
            $this->uploaded_at->CurrentValue = $row['uploaded_at'];
        }
        if (isset($row['uploaded_by'])) { // uploaded_by
            $this->uploaded_by->CurrentValue = $row['uploaded_by'];
        }
        if (isset($row['description'])) { // description
            $this->description->CurrentValue = $row['description'];
        }
        if (isset($row['is_supporting'])) { // is_supporting
            $this->is_supporting->CurrentValue = $row['is_supporting'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("DocumentAttachmentsList"), "", $this->TableVar, true);
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
                case "x_is_supporting":
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
