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
class NotarizedDocumentsEdit extends NotarizedDocuments
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "NotarizedDocumentsEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "NotarizedDocumentsEdit";

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
        $this->notarized_id->setVisibility();
        $this->request_id->setVisibility();
        $this->document_id->setVisibility();
        $this->notary_id->setVisibility();
        $this->document_number->setVisibility();
        $this->page_number->setVisibility();
        $this->book_number->setVisibility();
        $this->series_of->setVisibility();
        $this->doc_keycode->setVisibility();
        $this->notary_location->setVisibility();
        $this->notarization_date->setVisibility();
        $this->digital_signature->setVisibility();
        $this->digital_seal->setVisibility();
        $this->certificate_text->setVisibility();
        $this->certificate_type->setVisibility();
        $this->qr_code_path->setVisibility();
        $this->notarized_document_path->setVisibility();
        $this->expires_at->setVisibility();
        $this->revoked->setVisibility();
        $this->revoked_at->setVisibility();
        $this->revoked_by->setVisibility();
        $this->revocation_reason->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'notarized_documents';
        $this->TableName = 'notarized_documents';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (notarized_documents)
        if (!isset($GLOBALS["notarized_documents"]) || $GLOBALS["notarized_documents"]::class == PROJECT_NAMESPACE . "notarized_documents") {
            $GLOBALS["notarized_documents"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'notarized_documents');
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
                        $result["view"] = SameString($pageName, "NotarizedDocumentsView"); // If View page, no primary button
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
            $key .= @$ar['notarized_id'];
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
            $this->notarized_id->Visible = false;
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
        $this->setupLookupOptions($this->revoked);

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
            if (($keyValue = Get("notarized_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->notarized_id->setQueryStringValue($keyValue);
                $this->notarized_id->setOldValue($this->notarized_id->QueryStringValue);
            } elseif (Post("notarized_id") !== null) {
                $this->notarized_id->setFormValue(Post("notarized_id"));
                $this->notarized_id->setOldValue($this->notarized_id->FormValue);
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
                if (($keyValue = Get("notarized_id") ?? Route("notarized_id")) !== null) {
                    $this->notarized_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->notarized_id->CurrentValue = null;
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
                        $this->terminate("NotarizedDocumentsList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "NotarizedDocumentsList") {
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
                        if (GetPageName($returnUrl) != "NotarizedDocumentsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "NotarizedDocumentsList"; // Return list page content
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

        // Check field name 'notarized_id' first before field var 'x_notarized_id'
        $val = $CurrentForm->hasValue("notarized_id") ? $CurrentForm->getValue("notarized_id") : $CurrentForm->getValue("x_notarized_id");
        if (!$this->notarized_id->IsDetailKey) {
            $this->notarized_id->setFormValue($val);
        }

        // Check field name 'request_id' first before field var 'x_request_id'
        $val = $CurrentForm->hasValue("request_id") ? $CurrentForm->getValue("request_id") : $CurrentForm->getValue("x_request_id");
        if (!$this->request_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->request_id->Visible = false; // Disable update for API request
            } else {
                $this->request_id->setFormValue($val, true, $validate);
            }
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

        // Check field name 'notary_id' first before field var 'x_notary_id'
        $val = $CurrentForm->hasValue("notary_id") ? $CurrentForm->getValue("notary_id") : $CurrentForm->getValue("x_notary_id");
        if (!$this->notary_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notary_id->Visible = false; // Disable update for API request
            } else {
                $this->notary_id->setFormValue($val, true, $validate);
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

        // Check field name 'page_number' first before field var 'x_page_number'
        $val = $CurrentForm->hasValue("page_number") ? $CurrentForm->getValue("page_number") : $CurrentForm->getValue("x_page_number");
        if (!$this->page_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->page_number->Visible = false; // Disable update for API request
            } else {
                $this->page_number->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'book_number' first before field var 'x_book_number'
        $val = $CurrentForm->hasValue("book_number") ? $CurrentForm->getValue("book_number") : $CurrentForm->getValue("x_book_number");
        if (!$this->book_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->book_number->Visible = false; // Disable update for API request
            } else {
                $this->book_number->setFormValue($val);
            }
        }

        // Check field name 'series_of' first before field var 'x_series_of'
        $val = $CurrentForm->hasValue("series_of") ? $CurrentForm->getValue("series_of") : $CurrentForm->getValue("x_series_of");
        if (!$this->series_of->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->series_of->Visible = false; // Disable update for API request
            } else {
                $this->series_of->setFormValue($val);
            }
        }

        // Check field name 'doc_keycode' first before field var 'x_doc_keycode'
        $val = $CurrentForm->hasValue("doc_keycode") ? $CurrentForm->getValue("doc_keycode") : $CurrentForm->getValue("x_doc_keycode");
        if (!$this->doc_keycode->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->doc_keycode->Visible = false; // Disable update for API request
            } else {
                $this->doc_keycode->setFormValue($val);
            }
        }

        // Check field name 'notary_location' first before field var 'x_notary_location'
        $val = $CurrentForm->hasValue("notary_location") ? $CurrentForm->getValue("notary_location") : $CurrentForm->getValue("x_notary_location");
        if (!$this->notary_location->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notary_location->Visible = false; // Disable update for API request
            } else {
                $this->notary_location->setFormValue($val);
            }
        }

        // Check field name 'notarization_date' first before field var 'x_notarization_date'
        $val = $CurrentForm->hasValue("notarization_date") ? $CurrentForm->getValue("notarization_date") : $CurrentForm->getValue("x_notarization_date");
        if (!$this->notarization_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notarization_date->Visible = false; // Disable update for API request
            } else {
                $this->notarization_date->setFormValue($val, true, $validate);
            }
            $this->notarization_date->CurrentValue = UnFormatDateTime($this->notarization_date->CurrentValue, $this->notarization_date->formatPattern());
        }

        // Check field name 'digital_signature' first before field var 'x_digital_signature'
        $val = $CurrentForm->hasValue("digital_signature") ? $CurrentForm->getValue("digital_signature") : $CurrentForm->getValue("x_digital_signature");
        if (!$this->digital_signature->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->digital_signature->Visible = false; // Disable update for API request
            } else {
                $this->digital_signature->setFormValue($val);
            }
        }

        // Check field name 'digital_seal' first before field var 'x_digital_seal'
        $val = $CurrentForm->hasValue("digital_seal") ? $CurrentForm->getValue("digital_seal") : $CurrentForm->getValue("x_digital_seal");
        if (!$this->digital_seal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->digital_seal->Visible = false; // Disable update for API request
            } else {
                $this->digital_seal->setFormValue($val);
            }
        }

        // Check field name 'certificate_text' first before field var 'x_certificate_text'
        $val = $CurrentForm->hasValue("certificate_text") ? $CurrentForm->getValue("certificate_text") : $CurrentForm->getValue("x_certificate_text");
        if (!$this->certificate_text->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->certificate_text->Visible = false; // Disable update for API request
            } else {
                $this->certificate_text->setFormValue($val);
            }
        }

        // Check field name 'certificate_type' first before field var 'x_certificate_type'
        $val = $CurrentForm->hasValue("certificate_type") ? $CurrentForm->getValue("certificate_type") : $CurrentForm->getValue("x_certificate_type");
        if (!$this->certificate_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->certificate_type->Visible = false; // Disable update for API request
            } else {
                $this->certificate_type->setFormValue($val);
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

        // Check field name 'notarized_document_path' first before field var 'x_notarized_document_path'
        $val = $CurrentForm->hasValue("notarized_document_path") ? $CurrentForm->getValue("notarized_document_path") : $CurrentForm->getValue("x_notarized_document_path");
        if (!$this->notarized_document_path->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notarized_document_path->Visible = false; // Disable update for API request
            } else {
                $this->notarized_document_path->setFormValue($val);
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

        // Check field name 'revoked' first before field var 'x_revoked'
        $val = $CurrentForm->hasValue("revoked") ? $CurrentForm->getValue("revoked") : $CurrentForm->getValue("x_revoked");
        if (!$this->revoked->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->revoked->Visible = false; // Disable update for API request
            } else {
                $this->revoked->setFormValue($val);
            }
        }

        // Check field name 'revoked_at' first before field var 'x_revoked_at'
        $val = $CurrentForm->hasValue("revoked_at") ? $CurrentForm->getValue("revoked_at") : $CurrentForm->getValue("x_revoked_at");
        if (!$this->revoked_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->revoked_at->Visible = false; // Disable update for API request
            } else {
                $this->revoked_at->setFormValue($val, true, $validate);
            }
            $this->revoked_at->CurrentValue = UnFormatDateTime($this->revoked_at->CurrentValue, $this->revoked_at->formatPattern());
        }

        // Check field name 'revoked_by' first before field var 'x_revoked_by'
        $val = $CurrentForm->hasValue("revoked_by") ? $CurrentForm->getValue("revoked_by") : $CurrentForm->getValue("x_revoked_by");
        if (!$this->revoked_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->revoked_by->Visible = false; // Disable update for API request
            } else {
                $this->revoked_by->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'revocation_reason' first before field var 'x_revocation_reason'
        $val = $CurrentForm->hasValue("revocation_reason") ? $CurrentForm->getValue("revocation_reason") : $CurrentForm->getValue("x_revocation_reason");
        if (!$this->revocation_reason->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->revocation_reason->Visible = false; // Disable update for API request
            } else {
                $this->revocation_reason->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->notarized_id->CurrentValue = $this->notarized_id->FormValue;
        $this->request_id->CurrentValue = $this->request_id->FormValue;
        $this->document_id->CurrentValue = $this->document_id->FormValue;
        $this->notary_id->CurrentValue = $this->notary_id->FormValue;
        $this->document_number->CurrentValue = $this->document_number->FormValue;
        $this->page_number->CurrentValue = $this->page_number->FormValue;
        $this->book_number->CurrentValue = $this->book_number->FormValue;
        $this->series_of->CurrentValue = $this->series_of->FormValue;
        $this->doc_keycode->CurrentValue = $this->doc_keycode->FormValue;
        $this->notary_location->CurrentValue = $this->notary_location->FormValue;
        $this->notarization_date->CurrentValue = $this->notarization_date->FormValue;
        $this->notarization_date->CurrentValue = UnFormatDateTime($this->notarization_date->CurrentValue, $this->notarization_date->formatPattern());
        $this->digital_signature->CurrentValue = $this->digital_signature->FormValue;
        $this->digital_seal->CurrentValue = $this->digital_seal->FormValue;
        $this->certificate_text->CurrentValue = $this->certificate_text->FormValue;
        $this->certificate_type->CurrentValue = $this->certificate_type->FormValue;
        $this->qr_code_path->CurrentValue = $this->qr_code_path->FormValue;
        $this->notarized_document_path->CurrentValue = $this->notarized_document_path->FormValue;
        $this->expires_at->CurrentValue = $this->expires_at->FormValue;
        $this->expires_at->CurrentValue = UnFormatDateTime($this->expires_at->CurrentValue, $this->expires_at->formatPattern());
        $this->revoked->CurrentValue = $this->revoked->FormValue;
        $this->revoked_at->CurrentValue = $this->revoked_at->FormValue;
        $this->revoked_at->CurrentValue = UnFormatDateTime($this->revoked_at->CurrentValue, $this->revoked_at->formatPattern());
        $this->revoked_by->CurrentValue = $this->revoked_by->FormValue;
        $this->revocation_reason->CurrentValue = $this->revocation_reason->FormValue;
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
        $this->notarized_id->setDbValue($row['notarized_id']);
        $this->request_id->setDbValue($row['request_id']);
        $this->document_id->setDbValue($row['document_id']);
        $this->notary_id->setDbValue($row['notary_id']);
        $this->document_number->setDbValue($row['document_number']);
        $this->page_number->setDbValue($row['page_number']);
        $this->book_number->setDbValue($row['book_number']);
        $this->series_of->setDbValue($row['series_of']);
        $this->doc_keycode->setDbValue($row['doc_keycode']);
        $this->notary_location->setDbValue($row['notary_location']);
        $this->notarization_date->setDbValue($row['notarization_date']);
        $this->digital_signature->setDbValue($row['digital_signature']);
        $this->digital_seal->setDbValue($row['digital_seal']);
        $this->certificate_text->setDbValue($row['certificate_text']);
        $this->certificate_type->setDbValue($row['certificate_type']);
        $this->qr_code_path->setDbValue($row['qr_code_path']);
        $this->notarized_document_path->setDbValue($row['notarized_document_path']);
        $this->expires_at->setDbValue($row['expires_at']);
        $this->revoked->setDbValue((ConvertToBool($row['revoked']) ? "1" : "0"));
        $this->revoked_at->setDbValue($row['revoked_at']);
        $this->revoked_by->setDbValue($row['revoked_by']);
        $this->revocation_reason->setDbValue($row['revocation_reason']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['notarized_id'] = $this->notarized_id->DefaultValue;
        $row['request_id'] = $this->request_id->DefaultValue;
        $row['document_id'] = $this->document_id->DefaultValue;
        $row['notary_id'] = $this->notary_id->DefaultValue;
        $row['document_number'] = $this->document_number->DefaultValue;
        $row['page_number'] = $this->page_number->DefaultValue;
        $row['book_number'] = $this->book_number->DefaultValue;
        $row['series_of'] = $this->series_of->DefaultValue;
        $row['doc_keycode'] = $this->doc_keycode->DefaultValue;
        $row['notary_location'] = $this->notary_location->DefaultValue;
        $row['notarization_date'] = $this->notarization_date->DefaultValue;
        $row['digital_signature'] = $this->digital_signature->DefaultValue;
        $row['digital_seal'] = $this->digital_seal->DefaultValue;
        $row['certificate_text'] = $this->certificate_text->DefaultValue;
        $row['certificate_type'] = $this->certificate_type->DefaultValue;
        $row['qr_code_path'] = $this->qr_code_path->DefaultValue;
        $row['notarized_document_path'] = $this->notarized_document_path->DefaultValue;
        $row['expires_at'] = $this->expires_at->DefaultValue;
        $row['revoked'] = $this->revoked->DefaultValue;
        $row['revoked_at'] = $this->revoked_at->DefaultValue;
        $row['revoked_by'] = $this->revoked_by->DefaultValue;
        $row['revocation_reason'] = $this->revocation_reason->DefaultValue;
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

        // notarized_id
        $this->notarized_id->RowCssClass = "row";

        // request_id
        $this->request_id->RowCssClass = "row";

        // document_id
        $this->document_id->RowCssClass = "row";

        // notary_id
        $this->notary_id->RowCssClass = "row";

        // document_number
        $this->document_number->RowCssClass = "row";

        // page_number
        $this->page_number->RowCssClass = "row";

        // book_number
        $this->book_number->RowCssClass = "row";

        // series_of
        $this->series_of->RowCssClass = "row";

        // doc_keycode
        $this->doc_keycode->RowCssClass = "row";

        // notary_location
        $this->notary_location->RowCssClass = "row";

        // notarization_date
        $this->notarization_date->RowCssClass = "row";

        // digital_signature
        $this->digital_signature->RowCssClass = "row";

        // digital_seal
        $this->digital_seal->RowCssClass = "row";

        // certificate_text
        $this->certificate_text->RowCssClass = "row";

        // certificate_type
        $this->certificate_type->RowCssClass = "row";

        // qr_code_path
        $this->qr_code_path->RowCssClass = "row";

        // notarized_document_path
        $this->notarized_document_path->RowCssClass = "row";

        // expires_at
        $this->expires_at->RowCssClass = "row";

        // revoked
        $this->revoked->RowCssClass = "row";

        // revoked_at
        $this->revoked_at->RowCssClass = "row";

        // revoked_by
        $this->revoked_by->RowCssClass = "row";

        // revocation_reason
        $this->revocation_reason->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // notarized_id
            $this->notarized_id->ViewValue = $this->notarized_id->CurrentValue;

            // request_id
            $this->request_id->ViewValue = $this->request_id->CurrentValue;
            $this->request_id->ViewValue = FormatNumber($this->request_id->ViewValue, $this->request_id->formatPattern());

            // document_id
            $this->document_id->ViewValue = $this->document_id->CurrentValue;
            $this->document_id->ViewValue = FormatNumber($this->document_id->ViewValue, $this->document_id->formatPattern());

            // notary_id
            $this->notary_id->ViewValue = $this->notary_id->CurrentValue;
            $this->notary_id->ViewValue = FormatNumber($this->notary_id->ViewValue, $this->notary_id->formatPattern());

            // document_number
            $this->document_number->ViewValue = $this->document_number->CurrentValue;

            // page_number
            $this->page_number->ViewValue = $this->page_number->CurrentValue;
            $this->page_number->ViewValue = FormatNumber($this->page_number->ViewValue, $this->page_number->formatPattern());

            // book_number
            $this->book_number->ViewValue = $this->book_number->CurrentValue;

            // series_of
            $this->series_of->ViewValue = $this->series_of->CurrentValue;

            // doc_keycode
            $this->doc_keycode->ViewValue = $this->doc_keycode->CurrentValue;

            // notary_location
            $this->notary_location->ViewValue = $this->notary_location->CurrentValue;

            // notarization_date
            $this->notarization_date->ViewValue = $this->notarization_date->CurrentValue;
            $this->notarization_date->ViewValue = FormatDateTime($this->notarization_date->ViewValue, $this->notarization_date->formatPattern());

            // digital_signature
            $this->digital_signature->ViewValue = $this->digital_signature->CurrentValue;

            // digital_seal
            $this->digital_seal->ViewValue = $this->digital_seal->CurrentValue;

            // certificate_text
            $this->certificate_text->ViewValue = $this->certificate_text->CurrentValue;

            // certificate_type
            $this->certificate_type->ViewValue = $this->certificate_type->CurrentValue;

            // qr_code_path
            $this->qr_code_path->ViewValue = $this->qr_code_path->CurrentValue;

            // notarized_document_path
            $this->notarized_document_path->ViewValue = $this->notarized_document_path->CurrentValue;

            // expires_at
            $this->expires_at->ViewValue = $this->expires_at->CurrentValue;
            $this->expires_at->ViewValue = FormatDateTime($this->expires_at->ViewValue, $this->expires_at->formatPattern());

            // revoked
            if (ConvertToBool($this->revoked->CurrentValue)) {
                $this->revoked->ViewValue = $this->revoked->tagCaption(1) != "" ? $this->revoked->tagCaption(1) : "Yes";
            } else {
                $this->revoked->ViewValue = $this->revoked->tagCaption(2) != "" ? $this->revoked->tagCaption(2) : "No";
            }

            // revoked_at
            $this->revoked_at->ViewValue = $this->revoked_at->CurrentValue;
            $this->revoked_at->ViewValue = FormatDateTime($this->revoked_at->ViewValue, $this->revoked_at->formatPattern());

            // revoked_by
            $this->revoked_by->ViewValue = $this->revoked_by->CurrentValue;
            $this->revoked_by->ViewValue = FormatNumber($this->revoked_by->ViewValue, $this->revoked_by->formatPattern());

            // revocation_reason
            $this->revocation_reason->ViewValue = $this->revocation_reason->CurrentValue;

            // notarized_id
            $this->notarized_id->HrefValue = "";

            // request_id
            $this->request_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // notary_id
            $this->notary_id->HrefValue = "";

            // document_number
            $this->document_number->HrefValue = "";

            // page_number
            $this->page_number->HrefValue = "";

            // book_number
            $this->book_number->HrefValue = "";

            // series_of
            $this->series_of->HrefValue = "";

            // doc_keycode
            $this->doc_keycode->HrefValue = "";

            // notary_location
            $this->notary_location->HrefValue = "";

            // notarization_date
            $this->notarization_date->HrefValue = "";

            // digital_signature
            $this->digital_signature->HrefValue = "";

            // digital_seal
            $this->digital_seal->HrefValue = "";

            // certificate_text
            $this->certificate_text->HrefValue = "";

            // certificate_type
            $this->certificate_type->HrefValue = "";

            // qr_code_path
            $this->qr_code_path->HrefValue = "";

            // notarized_document_path
            $this->notarized_document_path->HrefValue = "";

            // expires_at
            $this->expires_at->HrefValue = "";

            // revoked
            $this->revoked->HrefValue = "";

            // revoked_at
            $this->revoked_at->HrefValue = "";

            // revoked_by
            $this->revoked_by->HrefValue = "";

            // revocation_reason
            $this->revocation_reason->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // notarized_id
            $this->notarized_id->setupEditAttributes();
            $this->notarized_id->EditValue = $this->notarized_id->CurrentValue;

            // request_id
            $this->request_id->setupEditAttributes();
            $this->request_id->EditValue = $this->request_id->CurrentValue;
            $this->request_id->PlaceHolder = RemoveHtml($this->request_id->caption());
            if (strval($this->request_id->EditValue) != "" && is_numeric($this->request_id->EditValue)) {
                $this->request_id->EditValue = FormatNumber($this->request_id->EditValue, null);
            }

            // document_id
            $this->document_id->setupEditAttributes();
            $this->document_id->EditValue = $this->document_id->CurrentValue;
            $this->document_id->PlaceHolder = RemoveHtml($this->document_id->caption());
            if (strval($this->document_id->EditValue) != "" && is_numeric($this->document_id->EditValue)) {
                $this->document_id->EditValue = FormatNumber($this->document_id->EditValue, null);
            }

            // notary_id
            $this->notary_id->setupEditAttributes();
            $this->notary_id->EditValue = $this->notary_id->CurrentValue;
            $this->notary_id->PlaceHolder = RemoveHtml($this->notary_id->caption());
            if (strval($this->notary_id->EditValue) != "" && is_numeric($this->notary_id->EditValue)) {
                $this->notary_id->EditValue = FormatNumber($this->notary_id->EditValue, null);
            }

            // document_number
            $this->document_number->setupEditAttributes();
            if (!$this->document_number->Raw) {
                $this->document_number->CurrentValue = HtmlDecode($this->document_number->CurrentValue);
            }
            $this->document_number->EditValue = HtmlEncode($this->document_number->CurrentValue);
            $this->document_number->PlaceHolder = RemoveHtml($this->document_number->caption());

            // page_number
            $this->page_number->setupEditAttributes();
            $this->page_number->EditValue = $this->page_number->CurrentValue;
            $this->page_number->PlaceHolder = RemoveHtml($this->page_number->caption());
            if (strval($this->page_number->EditValue) != "" && is_numeric($this->page_number->EditValue)) {
                $this->page_number->EditValue = FormatNumber($this->page_number->EditValue, null);
            }

            // book_number
            $this->book_number->setupEditAttributes();
            if (!$this->book_number->Raw) {
                $this->book_number->CurrentValue = HtmlDecode($this->book_number->CurrentValue);
            }
            $this->book_number->EditValue = HtmlEncode($this->book_number->CurrentValue);
            $this->book_number->PlaceHolder = RemoveHtml($this->book_number->caption());

            // series_of
            $this->series_of->setupEditAttributes();
            if (!$this->series_of->Raw) {
                $this->series_of->CurrentValue = HtmlDecode($this->series_of->CurrentValue);
            }
            $this->series_of->EditValue = HtmlEncode($this->series_of->CurrentValue);
            $this->series_of->PlaceHolder = RemoveHtml($this->series_of->caption());

            // doc_keycode
            $this->doc_keycode->setupEditAttributes();
            if (!$this->doc_keycode->Raw) {
                $this->doc_keycode->CurrentValue = HtmlDecode($this->doc_keycode->CurrentValue);
            }
            $this->doc_keycode->EditValue = HtmlEncode($this->doc_keycode->CurrentValue);
            $this->doc_keycode->PlaceHolder = RemoveHtml($this->doc_keycode->caption());

            // notary_location
            $this->notary_location->setupEditAttributes();
            if (!$this->notary_location->Raw) {
                $this->notary_location->CurrentValue = HtmlDecode($this->notary_location->CurrentValue);
            }
            $this->notary_location->EditValue = HtmlEncode($this->notary_location->CurrentValue);
            $this->notary_location->PlaceHolder = RemoveHtml($this->notary_location->caption());

            // notarization_date
            $this->notarization_date->setupEditAttributes();
            $this->notarization_date->EditValue = HtmlEncode(FormatDateTime($this->notarization_date->CurrentValue, $this->notarization_date->formatPattern()));
            $this->notarization_date->PlaceHolder = RemoveHtml($this->notarization_date->caption());

            // digital_signature
            $this->digital_signature->setupEditAttributes();
            $this->digital_signature->EditValue = HtmlEncode($this->digital_signature->CurrentValue);
            $this->digital_signature->PlaceHolder = RemoveHtml($this->digital_signature->caption());

            // digital_seal
            $this->digital_seal->setupEditAttributes();
            $this->digital_seal->EditValue = HtmlEncode($this->digital_seal->CurrentValue);
            $this->digital_seal->PlaceHolder = RemoveHtml($this->digital_seal->caption());

            // certificate_text
            $this->certificate_text->setupEditAttributes();
            $this->certificate_text->EditValue = HtmlEncode($this->certificate_text->CurrentValue);
            $this->certificate_text->PlaceHolder = RemoveHtml($this->certificate_text->caption());

            // certificate_type
            $this->certificate_type->setupEditAttributes();
            if (!$this->certificate_type->Raw) {
                $this->certificate_type->CurrentValue = HtmlDecode($this->certificate_type->CurrentValue);
            }
            $this->certificate_type->EditValue = HtmlEncode($this->certificate_type->CurrentValue);
            $this->certificate_type->PlaceHolder = RemoveHtml($this->certificate_type->caption());

            // qr_code_path
            $this->qr_code_path->setupEditAttributes();
            if (!$this->qr_code_path->Raw) {
                $this->qr_code_path->CurrentValue = HtmlDecode($this->qr_code_path->CurrentValue);
            }
            $this->qr_code_path->EditValue = HtmlEncode($this->qr_code_path->CurrentValue);
            $this->qr_code_path->PlaceHolder = RemoveHtml($this->qr_code_path->caption());

            // notarized_document_path
            $this->notarized_document_path->setupEditAttributes();
            if (!$this->notarized_document_path->Raw) {
                $this->notarized_document_path->CurrentValue = HtmlDecode($this->notarized_document_path->CurrentValue);
            }
            $this->notarized_document_path->EditValue = HtmlEncode($this->notarized_document_path->CurrentValue);
            $this->notarized_document_path->PlaceHolder = RemoveHtml($this->notarized_document_path->caption());

            // expires_at
            $this->expires_at->setupEditAttributes();
            $this->expires_at->EditValue = HtmlEncode(FormatDateTime($this->expires_at->CurrentValue, $this->expires_at->formatPattern()));
            $this->expires_at->PlaceHolder = RemoveHtml($this->expires_at->caption());

            // revoked
            $this->revoked->EditValue = $this->revoked->options(false);
            $this->revoked->PlaceHolder = RemoveHtml($this->revoked->caption());

            // revoked_at
            $this->revoked_at->setupEditAttributes();
            $this->revoked_at->EditValue = HtmlEncode(FormatDateTime($this->revoked_at->CurrentValue, $this->revoked_at->formatPattern()));
            $this->revoked_at->PlaceHolder = RemoveHtml($this->revoked_at->caption());

            // revoked_by
            $this->revoked_by->setupEditAttributes();
            $this->revoked_by->EditValue = $this->revoked_by->CurrentValue;
            $this->revoked_by->PlaceHolder = RemoveHtml($this->revoked_by->caption());
            if (strval($this->revoked_by->EditValue) != "" && is_numeric($this->revoked_by->EditValue)) {
                $this->revoked_by->EditValue = FormatNumber($this->revoked_by->EditValue, null);
            }

            // revocation_reason
            $this->revocation_reason->setupEditAttributes();
            $this->revocation_reason->EditValue = HtmlEncode($this->revocation_reason->CurrentValue);
            $this->revocation_reason->PlaceHolder = RemoveHtml($this->revocation_reason->caption());

            // Edit refer script

            // notarized_id
            $this->notarized_id->HrefValue = "";

            // request_id
            $this->request_id->HrefValue = "";

            // document_id
            $this->document_id->HrefValue = "";

            // notary_id
            $this->notary_id->HrefValue = "";

            // document_number
            $this->document_number->HrefValue = "";

            // page_number
            $this->page_number->HrefValue = "";

            // book_number
            $this->book_number->HrefValue = "";

            // series_of
            $this->series_of->HrefValue = "";

            // doc_keycode
            $this->doc_keycode->HrefValue = "";

            // notary_location
            $this->notary_location->HrefValue = "";

            // notarization_date
            $this->notarization_date->HrefValue = "";

            // digital_signature
            $this->digital_signature->HrefValue = "";

            // digital_seal
            $this->digital_seal->HrefValue = "";

            // certificate_text
            $this->certificate_text->HrefValue = "";

            // certificate_type
            $this->certificate_type->HrefValue = "";

            // qr_code_path
            $this->qr_code_path->HrefValue = "";

            // notarized_document_path
            $this->notarized_document_path->HrefValue = "";

            // expires_at
            $this->expires_at->HrefValue = "";

            // revoked
            $this->revoked->HrefValue = "";

            // revoked_at
            $this->revoked_at->HrefValue = "";

            // revoked_by
            $this->revoked_by->HrefValue = "";

            // revocation_reason
            $this->revocation_reason->HrefValue = "";
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
            if ($this->notarized_id->Visible && $this->notarized_id->Required) {
                if (!$this->notarized_id->IsDetailKey && EmptyValue($this->notarized_id->FormValue)) {
                    $this->notarized_id->addErrorMessage(str_replace("%s", $this->notarized_id->caption(), $this->notarized_id->RequiredErrorMessage));
                }
            }
            if ($this->request_id->Visible && $this->request_id->Required) {
                if (!$this->request_id->IsDetailKey && EmptyValue($this->request_id->FormValue)) {
                    $this->request_id->addErrorMessage(str_replace("%s", $this->request_id->caption(), $this->request_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->request_id->FormValue)) {
                $this->request_id->addErrorMessage($this->request_id->getErrorMessage(false));
            }
            if ($this->document_id->Visible && $this->document_id->Required) {
                if (!$this->document_id->IsDetailKey && EmptyValue($this->document_id->FormValue)) {
                    $this->document_id->addErrorMessage(str_replace("%s", $this->document_id->caption(), $this->document_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->document_id->FormValue)) {
                $this->document_id->addErrorMessage($this->document_id->getErrorMessage(false));
            }
            if ($this->notary_id->Visible && $this->notary_id->Required) {
                if (!$this->notary_id->IsDetailKey && EmptyValue($this->notary_id->FormValue)) {
                    $this->notary_id->addErrorMessage(str_replace("%s", $this->notary_id->caption(), $this->notary_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->notary_id->FormValue)) {
                $this->notary_id->addErrorMessage($this->notary_id->getErrorMessage(false));
            }
            if ($this->document_number->Visible && $this->document_number->Required) {
                if (!$this->document_number->IsDetailKey && EmptyValue($this->document_number->FormValue)) {
                    $this->document_number->addErrorMessage(str_replace("%s", $this->document_number->caption(), $this->document_number->RequiredErrorMessage));
                }
            }
            if ($this->page_number->Visible && $this->page_number->Required) {
                if (!$this->page_number->IsDetailKey && EmptyValue($this->page_number->FormValue)) {
                    $this->page_number->addErrorMessage(str_replace("%s", $this->page_number->caption(), $this->page_number->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->page_number->FormValue)) {
                $this->page_number->addErrorMessage($this->page_number->getErrorMessage(false));
            }
            if ($this->book_number->Visible && $this->book_number->Required) {
                if (!$this->book_number->IsDetailKey && EmptyValue($this->book_number->FormValue)) {
                    $this->book_number->addErrorMessage(str_replace("%s", $this->book_number->caption(), $this->book_number->RequiredErrorMessage));
                }
            }
            if ($this->series_of->Visible && $this->series_of->Required) {
                if (!$this->series_of->IsDetailKey && EmptyValue($this->series_of->FormValue)) {
                    $this->series_of->addErrorMessage(str_replace("%s", $this->series_of->caption(), $this->series_of->RequiredErrorMessage));
                }
            }
            if ($this->doc_keycode->Visible && $this->doc_keycode->Required) {
                if (!$this->doc_keycode->IsDetailKey && EmptyValue($this->doc_keycode->FormValue)) {
                    $this->doc_keycode->addErrorMessage(str_replace("%s", $this->doc_keycode->caption(), $this->doc_keycode->RequiredErrorMessage));
                }
            }
            if ($this->notary_location->Visible && $this->notary_location->Required) {
                if (!$this->notary_location->IsDetailKey && EmptyValue($this->notary_location->FormValue)) {
                    $this->notary_location->addErrorMessage(str_replace("%s", $this->notary_location->caption(), $this->notary_location->RequiredErrorMessage));
                }
            }
            if ($this->notarization_date->Visible && $this->notarization_date->Required) {
                if (!$this->notarization_date->IsDetailKey && EmptyValue($this->notarization_date->FormValue)) {
                    $this->notarization_date->addErrorMessage(str_replace("%s", $this->notarization_date->caption(), $this->notarization_date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->notarization_date->FormValue, $this->notarization_date->formatPattern())) {
                $this->notarization_date->addErrorMessage($this->notarization_date->getErrorMessage(false));
            }
            if ($this->digital_signature->Visible && $this->digital_signature->Required) {
                if (!$this->digital_signature->IsDetailKey && EmptyValue($this->digital_signature->FormValue)) {
                    $this->digital_signature->addErrorMessage(str_replace("%s", $this->digital_signature->caption(), $this->digital_signature->RequiredErrorMessage));
                }
            }
            if ($this->digital_seal->Visible && $this->digital_seal->Required) {
                if (!$this->digital_seal->IsDetailKey && EmptyValue($this->digital_seal->FormValue)) {
                    $this->digital_seal->addErrorMessage(str_replace("%s", $this->digital_seal->caption(), $this->digital_seal->RequiredErrorMessage));
                }
            }
            if ($this->certificate_text->Visible && $this->certificate_text->Required) {
                if (!$this->certificate_text->IsDetailKey && EmptyValue($this->certificate_text->FormValue)) {
                    $this->certificate_text->addErrorMessage(str_replace("%s", $this->certificate_text->caption(), $this->certificate_text->RequiredErrorMessage));
                }
            }
            if ($this->certificate_type->Visible && $this->certificate_type->Required) {
                if (!$this->certificate_type->IsDetailKey && EmptyValue($this->certificate_type->FormValue)) {
                    $this->certificate_type->addErrorMessage(str_replace("%s", $this->certificate_type->caption(), $this->certificate_type->RequiredErrorMessage));
                }
            }
            if ($this->qr_code_path->Visible && $this->qr_code_path->Required) {
                if (!$this->qr_code_path->IsDetailKey && EmptyValue($this->qr_code_path->FormValue)) {
                    $this->qr_code_path->addErrorMessage(str_replace("%s", $this->qr_code_path->caption(), $this->qr_code_path->RequiredErrorMessage));
                }
            }
            if ($this->notarized_document_path->Visible && $this->notarized_document_path->Required) {
                if (!$this->notarized_document_path->IsDetailKey && EmptyValue($this->notarized_document_path->FormValue)) {
                    $this->notarized_document_path->addErrorMessage(str_replace("%s", $this->notarized_document_path->caption(), $this->notarized_document_path->RequiredErrorMessage));
                }
            }
            if ($this->expires_at->Visible && $this->expires_at->Required) {
                if (!$this->expires_at->IsDetailKey && EmptyValue($this->expires_at->FormValue)) {
                    $this->expires_at->addErrorMessage(str_replace("%s", $this->expires_at->caption(), $this->expires_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->expires_at->FormValue, $this->expires_at->formatPattern())) {
                $this->expires_at->addErrorMessage($this->expires_at->getErrorMessage(false));
            }
            if ($this->revoked->Visible && $this->revoked->Required) {
                if ($this->revoked->FormValue == "") {
                    $this->revoked->addErrorMessage(str_replace("%s", $this->revoked->caption(), $this->revoked->RequiredErrorMessage));
                }
            }
            if ($this->revoked_at->Visible && $this->revoked_at->Required) {
                if (!$this->revoked_at->IsDetailKey && EmptyValue($this->revoked_at->FormValue)) {
                    $this->revoked_at->addErrorMessage(str_replace("%s", $this->revoked_at->caption(), $this->revoked_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->revoked_at->FormValue, $this->revoked_at->formatPattern())) {
                $this->revoked_at->addErrorMessage($this->revoked_at->getErrorMessage(false));
            }
            if ($this->revoked_by->Visible && $this->revoked_by->Required) {
                if (!$this->revoked_by->IsDetailKey && EmptyValue($this->revoked_by->FormValue)) {
                    $this->revoked_by->addErrorMessage(str_replace("%s", $this->revoked_by->caption(), $this->revoked_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->revoked_by->FormValue)) {
                $this->revoked_by->addErrorMessage($this->revoked_by->getErrorMessage(false));
            }
            if ($this->revocation_reason->Visible && $this->revocation_reason->Required) {
                if (!$this->revocation_reason->IsDetailKey && EmptyValue($this->revocation_reason->FormValue)) {
                    $this->revocation_reason->addErrorMessage(str_replace("%s", $this->revocation_reason->caption(), $this->revocation_reason->RequiredErrorMessage));
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

        // Check field with unique index (doc_keycode)
        if ($this->doc_keycode->CurrentValue != "") {
            $filterChk = "(\"doc_keycode\" = '" . AdjustSql($this->doc_keycode->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->doc_keycode->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->doc_keycode->CurrentValue, $idxErrMsg);
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

        // request_id
        $this->request_id->setDbValueDef($rsnew, $this->request_id->CurrentValue, $this->request_id->ReadOnly);

        // document_id
        $this->document_id->setDbValueDef($rsnew, $this->document_id->CurrentValue, $this->document_id->ReadOnly);

        // notary_id
        $this->notary_id->setDbValueDef($rsnew, $this->notary_id->CurrentValue, $this->notary_id->ReadOnly);

        // document_number
        $this->document_number->setDbValueDef($rsnew, $this->document_number->CurrentValue, $this->document_number->ReadOnly);

        // page_number
        $this->page_number->setDbValueDef($rsnew, $this->page_number->CurrentValue, $this->page_number->ReadOnly);

        // book_number
        $this->book_number->setDbValueDef($rsnew, $this->book_number->CurrentValue, $this->book_number->ReadOnly);

        // series_of
        $this->series_of->setDbValueDef($rsnew, $this->series_of->CurrentValue, $this->series_of->ReadOnly);

        // doc_keycode
        $this->doc_keycode->setDbValueDef($rsnew, $this->doc_keycode->CurrentValue, $this->doc_keycode->ReadOnly);

        // notary_location
        $this->notary_location->setDbValueDef($rsnew, $this->notary_location->CurrentValue, $this->notary_location->ReadOnly);

        // notarization_date
        $this->notarization_date->setDbValueDef($rsnew, UnFormatDateTime($this->notarization_date->CurrentValue, $this->notarization_date->formatPattern()), $this->notarization_date->ReadOnly);

        // digital_signature
        $this->digital_signature->setDbValueDef($rsnew, $this->digital_signature->CurrentValue, $this->digital_signature->ReadOnly);

        // digital_seal
        $this->digital_seal->setDbValueDef($rsnew, $this->digital_seal->CurrentValue, $this->digital_seal->ReadOnly);

        // certificate_text
        $this->certificate_text->setDbValueDef($rsnew, $this->certificate_text->CurrentValue, $this->certificate_text->ReadOnly);

        // certificate_type
        $this->certificate_type->setDbValueDef($rsnew, $this->certificate_type->CurrentValue, $this->certificate_type->ReadOnly);

        // qr_code_path
        $this->qr_code_path->setDbValueDef($rsnew, $this->qr_code_path->CurrentValue, $this->qr_code_path->ReadOnly);

        // notarized_document_path
        $this->notarized_document_path->setDbValueDef($rsnew, $this->notarized_document_path->CurrentValue, $this->notarized_document_path->ReadOnly);

        // expires_at
        $this->expires_at->setDbValueDef($rsnew, UnFormatDateTime($this->expires_at->CurrentValue, $this->expires_at->formatPattern()), $this->expires_at->ReadOnly);

        // revoked
        $tmpBool = $this->revoked->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->revoked->setDbValueDef($rsnew, $tmpBool, $this->revoked->ReadOnly);

        // revoked_at
        $this->revoked_at->setDbValueDef($rsnew, UnFormatDateTime($this->revoked_at->CurrentValue, $this->revoked_at->formatPattern()), $this->revoked_at->ReadOnly);

        // revoked_by
        $this->revoked_by->setDbValueDef($rsnew, $this->revoked_by->CurrentValue, $this->revoked_by->ReadOnly);

        // revocation_reason
        $this->revocation_reason->setDbValueDef($rsnew, $this->revocation_reason->CurrentValue, $this->revocation_reason->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['request_id'])) { // request_id
            $this->request_id->CurrentValue = $row['request_id'];
        }
        if (isset($row['document_id'])) { // document_id
            $this->document_id->CurrentValue = $row['document_id'];
        }
        if (isset($row['notary_id'])) { // notary_id
            $this->notary_id->CurrentValue = $row['notary_id'];
        }
        if (isset($row['document_number'])) { // document_number
            $this->document_number->CurrentValue = $row['document_number'];
        }
        if (isset($row['page_number'])) { // page_number
            $this->page_number->CurrentValue = $row['page_number'];
        }
        if (isset($row['book_number'])) { // book_number
            $this->book_number->CurrentValue = $row['book_number'];
        }
        if (isset($row['series_of'])) { // series_of
            $this->series_of->CurrentValue = $row['series_of'];
        }
        if (isset($row['doc_keycode'])) { // doc_keycode
            $this->doc_keycode->CurrentValue = $row['doc_keycode'];
        }
        if (isset($row['notary_location'])) { // notary_location
            $this->notary_location->CurrentValue = $row['notary_location'];
        }
        if (isset($row['notarization_date'])) { // notarization_date
            $this->notarization_date->CurrentValue = $row['notarization_date'];
        }
        if (isset($row['digital_signature'])) { // digital_signature
            $this->digital_signature->CurrentValue = $row['digital_signature'];
        }
        if (isset($row['digital_seal'])) { // digital_seal
            $this->digital_seal->CurrentValue = $row['digital_seal'];
        }
        if (isset($row['certificate_text'])) { // certificate_text
            $this->certificate_text->CurrentValue = $row['certificate_text'];
        }
        if (isset($row['certificate_type'])) { // certificate_type
            $this->certificate_type->CurrentValue = $row['certificate_type'];
        }
        if (isset($row['qr_code_path'])) { // qr_code_path
            $this->qr_code_path->CurrentValue = $row['qr_code_path'];
        }
        if (isset($row['notarized_document_path'])) { // notarized_document_path
            $this->notarized_document_path->CurrentValue = $row['notarized_document_path'];
        }
        if (isset($row['expires_at'])) { // expires_at
            $this->expires_at->CurrentValue = $row['expires_at'];
        }
        if (isset($row['revoked'])) { // revoked
            $this->revoked->CurrentValue = $row['revoked'];
        }
        if (isset($row['revoked_at'])) { // revoked_at
            $this->revoked_at->CurrentValue = $row['revoked_at'];
        }
        if (isset($row['revoked_by'])) { // revoked_by
            $this->revoked_by->CurrentValue = $row['revoked_by'];
        }
        if (isset($row['revocation_reason'])) { // revocation_reason
            $this->revocation_reason->CurrentValue = $row['revocation_reason'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("NotarizedDocumentsList"), "", $this->TableVar, true);
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
                case "x_revoked":
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
