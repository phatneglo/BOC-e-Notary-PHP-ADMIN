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
class FaqItemsEdit extends FaqItems
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "FaqItemsEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "FaqItemsEdit";

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
        $this->faq_id->setVisibility();
        $this->category_id->setVisibility();
        $this->question->setVisibility();
        $this->answer->setVisibility();
        $this->display_order->setVisibility();
        $this->is_active->setVisibility();
        $this->created_at->setVisibility();
        $this->created_by->setVisibility();
        $this->updated_at->setVisibility();
        $this->updated_by->setVisibility();
        $this->view_count->setVisibility();
        $this->tags->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'faq_items';
        $this->TableName = 'faq_items';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (faq_items)
        if (!isset($GLOBALS["faq_items"]) || $GLOBALS["faq_items"]::class == PROJECT_NAMESPACE . "faq_items") {
            $GLOBALS["faq_items"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'faq_items');
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
                        $result["view"] = SameString($pageName, "FaqItemsView"); // If View page, no primary button
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
            $key .= @$ar['faq_id'];
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
            $this->faq_id->Visible = false;
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
            if (($keyValue = Get("faq_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->faq_id->setQueryStringValue($keyValue);
                $this->faq_id->setOldValue($this->faq_id->QueryStringValue);
            } elseif (Post("faq_id") !== null) {
                $this->faq_id->setFormValue(Post("faq_id"));
                $this->faq_id->setOldValue($this->faq_id->FormValue);
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
                if (($keyValue = Get("faq_id") ?? Route("faq_id")) !== null) {
                    $this->faq_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->faq_id->CurrentValue = null;
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
                        $this->terminate("FaqItemsList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "FaqItemsList") {
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
                        if (GetPageName($returnUrl) != "FaqItemsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "FaqItemsList"; // Return list page content
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

        // Check field name 'faq_id' first before field var 'x_faq_id'
        $val = $CurrentForm->hasValue("faq_id") ? $CurrentForm->getValue("faq_id") : $CurrentForm->getValue("x_faq_id");
        if (!$this->faq_id->IsDetailKey) {
            $this->faq_id->setFormValue($val);
        }

        // Check field name 'category_id' first before field var 'x_category_id'
        $val = $CurrentForm->hasValue("category_id") ? $CurrentForm->getValue("category_id") : $CurrentForm->getValue("x_category_id");
        if (!$this->category_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->category_id->Visible = false; // Disable update for API request
            } else {
                $this->category_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'question' first before field var 'x_question'
        $val = $CurrentForm->hasValue("question") ? $CurrentForm->getValue("question") : $CurrentForm->getValue("x_question");
        if (!$this->question->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->question->Visible = false; // Disable update for API request
            } else {
                $this->question->setFormValue($val);
            }
        }

        // Check field name 'answer' first before field var 'x_answer'
        $val = $CurrentForm->hasValue("answer") ? $CurrentForm->getValue("answer") : $CurrentForm->getValue("x_answer");
        if (!$this->answer->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->answer->Visible = false; // Disable update for API request
            } else {
                $this->answer->setFormValue($val);
            }
        }

        // Check field name 'display_order' first before field var 'x_display_order'
        $val = $CurrentForm->hasValue("display_order") ? $CurrentForm->getValue("display_order") : $CurrentForm->getValue("x_display_order");
        if (!$this->display_order->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->display_order->Visible = false; // Disable update for API request
            } else {
                $this->display_order->setFormValue($val, true, $validate);
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

        // Check field name 'created_by' first before field var 'x_created_by'
        $val = $CurrentForm->hasValue("created_by") ? $CurrentForm->getValue("created_by") : $CurrentForm->getValue("x_created_by");
        if (!$this->created_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->created_by->Visible = false; // Disable update for API request
            } else {
                $this->created_by->setFormValue($val, true, $validate);
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

        // Check field name 'updated_by' first before field var 'x_updated_by'
        $val = $CurrentForm->hasValue("updated_by") ? $CurrentForm->getValue("updated_by") : $CurrentForm->getValue("x_updated_by");
        if (!$this->updated_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->updated_by->Visible = false; // Disable update for API request
            } else {
                $this->updated_by->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'view_count' first before field var 'x_view_count'
        $val = $CurrentForm->hasValue("view_count") ? $CurrentForm->getValue("view_count") : $CurrentForm->getValue("x_view_count");
        if (!$this->view_count->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->view_count->Visible = false; // Disable update for API request
            } else {
                $this->view_count->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'tags' first before field var 'x_tags'
        $val = $CurrentForm->hasValue("tags") ? $CurrentForm->getValue("tags") : $CurrentForm->getValue("x_tags");
        if (!$this->tags->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tags->Visible = false; // Disable update for API request
            } else {
                $this->tags->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->faq_id->CurrentValue = $this->faq_id->FormValue;
        $this->category_id->CurrentValue = $this->category_id->FormValue;
        $this->question->CurrentValue = $this->question->FormValue;
        $this->answer->CurrentValue = $this->answer->FormValue;
        $this->display_order->CurrentValue = $this->display_order->FormValue;
        $this->is_active->CurrentValue = $this->is_active->FormValue;
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->created_by->CurrentValue = $this->created_by->FormValue;
        $this->updated_at->CurrentValue = $this->updated_at->FormValue;
        $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->updated_by->CurrentValue = $this->updated_by->FormValue;
        $this->view_count->CurrentValue = $this->view_count->FormValue;
        $this->tags->CurrentValue = $this->tags->FormValue;
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
        $this->faq_id->setDbValue($row['faq_id']);
        $this->category_id->setDbValue($row['category_id']);
        $this->question->setDbValue($row['question']);
        $this->answer->setDbValue($row['answer']);
        $this->display_order->setDbValue($row['display_order']);
        $this->is_active->setDbValue((ConvertToBool($row['is_active']) ? "1" : "0"));
        $this->created_at->setDbValue($row['created_at']);
        $this->created_by->setDbValue($row['created_by']);
        $this->updated_at->setDbValue($row['updated_at']);
        $this->updated_by->setDbValue($row['updated_by']);
        $this->view_count->setDbValue($row['view_count']);
        $this->tags->setDbValue($row['tags']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['faq_id'] = $this->faq_id->DefaultValue;
        $row['category_id'] = $this->category_id->DefaultValue;
        $row['question'] = $this->question->DefaultValue;
        $row['answer'] = $this->answer->DefaultValue;
        $row['display_order'] = $this->display_order->DefaultValue;
        $row['is_active'] = $this->is_active->DefaultValue;
        $row['created_at'] = $this->created_at->DefaultValue;
        $row['created_by'] = $this->created_by->DefaultValue;
        $row['updated_at'] = $this->updated_at->DefaultValue;
        $row['updated_by'] = $this->updated_by->DefaultValue;
        $row['view_count'] = $this->view_count->DefaultValue;
        $row['tags'] = $this->tags->DefaultValue;
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

        // faq_id
        $this->faq_id->RowCssClass = "row";

        // category_id
        $this->category_id->RowCssClass = "row";

        // question
        $this->question->RowCssClass = "row";

        // answer
        $this->answer->RowCssClass = "row";

        // display_order
        $this->display_order->RowCssClass = "row";

        // is_active
        $this->is_active->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // created_by
        $this->created_by->RowCssClass = "row";

        // updated_at
        $this->updated_at->RowCssClass = "row";

        // updated_by
        $this->updated_by->RowCssClass = "row";

        // view_count
        $this->view_count->RowCssClass = "row";

        // tags
        $this->tags->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // faq_id
            $this->faq_id->ViewValue = $this->faq_id->CurrentValue;

            // category_id
            $this->category_id->ViewValue = $this->category_id->CurrentValue;
            $this->category_id->ViewValue = FormatNumber($this->category_id->ViewValue, $this->category_id->formatPattern());

            // question
            $this->question->ViewValue = $this->question->CurrentValue;

            // answer
            $this->answer->ViewValue = $this->answer->CurrentValue;

            // display_order
            $this->display_order->ViewValue = $this->display_order->CurrentValue;
            $this->display_order->ViewValue = FormatNumber($this->display_order->ViewValue, $this->display_order->formatPattern());

            // is_active
            if (ConvertToBool($this->is_active->CurrentValue)) {
                $this->is_active->ViewValue = $this->is_active->tagCaption(1) != "" ? $this->is_active->tagCaption(1) : "Yes";
            } else {
                $this->is_active->ViewValue = $this->is_active->tagCaption(2) != "" ? $this->is_active->tagCaption(2) : "No";
            }

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // created_by
            $this->created_by->ViewValue = $this->created_by->CurrentValue;
            $this->created_by->ViewValue = FormatNumber($this->created_by->ViewValue, $this->created_by->formatPattern());

            // updated_at
            $this->updated_at->ViewValue = $this->updated_at->CurrentValue;
            $this->updated_at->ViewValue = FormatDateTime($this->updated_at->ViewValue, $this->updated_at->formatPattern());

            // updated_by
            $this->updated_by->ViewValue = $this->updated_by->CurrentValue;
            $this->updated_by->ViewValue = FormatNumber($this->updated_by->ViewValue, $this->updated_by->formatPattern());

            // view_count
            $this->view_count->ViewValue = $this->view_count->CurrentValue;
            $this->view_count->ViewValue = FormatNumber($this->view_count->ViewValue, $this->view_count->formatPattern());

            // tags
            $this->tags->ViewValue = $this->tags->CurrentValue;

            // faq_id
            $this->faq_id->HrefValue = "";

            // category_id
            $this->category_id->HrefValue = "";

            // question
            $this->question->HrefValue = "";

            // answer
            $this->answer->HrefValue = "";

            // display_order
            $this->display_order->HrefValue = "";

            // is_active
            $this->is_active->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // created_by
            $this->created_by->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // updated_by
            $this->updated_by->HrefValue = "";

            // view_count
            $this->view_count->HrefValue = "";

            // tags
            $this->tags->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // faq_id
            $this->faq_id->setupEditAttributes();
            $this->faq_id->EditValue = $this->faq_id->CurrentValue;

            // category_id
            $this->category_id->setupEditAttributes();
            $this->category_id->EditValue = $this->category_id->CurrentValue;
            $this->category_id->PlaceHolder = RemoveHtml($this->category_id->caption());
            if (strval($this->category_id->EditValue) != "" && is_numeric($this->category_id->EditValue)) {
                $this->category_id->EditValue = FormatNumber($this->category_id->EditValue, null);
            }

            // question
            $this->question->setupEditAttributes();
            $this->question->EditValue = HtmlEncode($this->question->CurrentValue);
            $this->question->PlaceHolder = RemoveHtml($this->question->caption());

            // answer
            $this->answer->setupEditAttributes();
            $this->answer->EditValue = HtmlEncode($this->answer->CurrentValue);
            $this->answer->PlaceHolder = RemoveHtml($this->answer->caption());

            // display_order
            $this->display_order->setupEditAttributes();
            $this->display_order->EditValue = $this->display_order->CurrentValue;
            $this->display_order->PlaceHolder = RemoveHtml($this->display_order->caption());
            if (strval($this->display_order->EditValue) != "" && is_numeric($this->display_order->EditValue)) {
                $this->display_order->EditValue = FormatNumber($this->display_order->EditValue, null);
            }

            // is_active
            $this->is_active->EditValue = $this->is_active->options(false);
            $this->is_active->PlaceHolder = RemoveHtml($this->is_active->caption());

            // created_at
            $this->created_at->setupEditAttributes();
            $this->created_at->EditValue = HtmlEncode(FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()));
            $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

            // created_by
            $this->created_by->setupEditAttributes();
            $this->created_by->EditValue = $this->created_by->CurrentValue;
            $this->created_by->PlaceHolder = RemoveHtml($this->created_by->caption());
            if (strval($this->created_by->EditValue) != "" && is_numeric($this->created_by->EditValue)) {
                $this->created_by->EditValue = FormatNumber($this->created_by->EditValue, null);
            }

            // updated_at
            $this->updated_at->setupEditAttributes();
            $this->updated_at->EditValue = HtmlEncode(FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()));
            $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

            // updated_by
            $this->updated_by->setupEditAttributes();
            $this->updated_by->EditValue = $this->updated_by->CurrentValue;
            $this->updated_by->PlaceHolder = RemoveHtml($this->updated_by->caption());
            if (strval($this->updated_by->EditValue) != "" && is_numeric($this->updated_by->EditValue)) {
                $this->updated_by->EditValue = FormatNumber($this->updated_by->EditValue, null);
            }

            // view_count
            $this->view_count->setupEditAttributes();
            $this->view_count->EditValue = $this->view_count->CurrentValue;
            $this->view_count->PlaceHolder = RemoveHtml($this->view_count->caption());
            if (strval($this->view_count->EditValue) != "" && is_numeric($this->view_count->EditValue)) {
                $this->view_count->EditValue = FormatNumber($this->view_count->EditValue, null);
            }

            // tags
            $this->tags->setupEditAttributes();
            $this->tags->EditValue = HtmlEncode($this->tags->CurrentValue);
            $this->tags->PlaceHolder = RemoveHtml($this->tags->caption());

            // Edit refer script

            // faq_id
            $this->faq_id->HrefValue = "";

            // category_id
            $this->category_id->HrefValue = "";

            // question
            $this->question->HrefValue = "";

            // answer
            $this->answer->HrefValue = "";

            // display_order
            $this->display_order->HrefValue = "";

            // is_active
            $this->is_active->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // created_by
            $this->created_by->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // updated_by
            $this->updated_by->HrefValue = "";

            // view_count
            $this->view_count->HrefValue = "";

            // tags
            $this->tags->HrefValue = "";
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
            if ($this->faq_id->Visible && $this->faq_id->Required) {
                if (!$this->faq_id->IsDetailKey && EmptyValue($this->faq_id->FormValue)) {
                    $this->faq_id->addErrorMessage(str_replace("%s", $this->faq_id->caption(), $this->faq_id->RequiredErrorMessage));
                }
            }
            if ($this->category_id->Visible && $this->category_id->Required) {
                if (!$this->category_id->IsDetailKey && EmptyValue($this->category_id->FormValue)) {
                    $this->category_id->addErrorMessage(str_replace("%s", $this->category_id->caption(), $this->category_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->category_id->FormValue)) {
                $this->category_id->addErrorMessage($this->category_id->getErrorMessage(false));
            }
            if ($this->question->Visible && $this->question->Required) {
                if (!$this->question->IsDetailKey && EmptyValue($this->question->FormValue)) {
                    $this->question->addErrorMessage(str_replace("%s", $this->question->caption(), $this->question->RequiredErrorMessage));
                }
            }
            if ($this->answer->Visible && $this->answer->Required) {
                if (!$this->answer->IsDetailKey && EmptyValue($this->answer->FormValue)) {
                    $this->answer->addErrorMessage(str_replace("%s", $this->answer->caption(), $this->answer->RequiredErrorMessage));
                }
            }
            if ($this->display_order->Visible && $this->display_order->Required) {
                if (!$this->display_order->IsDetailKey && EmptyValue($this->display_order->FormValue)) {
                    $this->display_order->addErrorMessage(str_replace("%s", $this->display_order->caption(), $this->display_order->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->display_order->FormValue)) {
                $this->display_order->addErrorMessage($this->display_order->getErrorMessage(false));
            }
            if ($this->is_active->Visible && $this->is_active->Required) {
                if ($this->is_active->FormValue == "") {
                    $this->is_active->addErrorMessage(str_replace("%s", $this->is_active->caption(), $this->is_active->RequiredErrorMessage));
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
            if ($this->created_by->Visible && $this->created_by->Required) {
                if (!$this->created_by->IsDetailKey && EmptyValue($this->created_by->FormValue)) {
                    $this->created_by->addErrorMessage(str_replace("%s", $this->created_by->caption(), $this->created_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->created_by->FormValue)) {
                $this->created_by->addErrorMessage($this->created_by->getErrorMessage(false));
            }
            if ($this->updated_at->Visible && $this->updated_at->Required) {
                if (!$this->updated_at->IsDetailKey && EmptyValue($this->updated_at->FormValue)) {
                    $this->updated_at->addErrorMessage(str_replace("%s", $this->updated_at->caption(), $this->updated_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->updated_at->FormValue, $this->updated_at->formatPattern())) {
                $this->updated_at->addErrorMessage($this->updated_at->getErrorMessage(false));
            }
            if ($this->updated_by->Visible && $this->updated_by->Required) {
                if (!$this->updated_by->IsDetailKey && EmptyValue($this->updated_by->FormValue)) {
                    $this->updated_by->addErrorMessage(str_replace("%s", $this->updated_by->caption(), $this->updated_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->updated_by->FormValue)) {
                $this->updated_by->addErrorMessage($this->updated_by->getErrorMessage(false));
            }
            if ($this->view_count->Visible && $this->view_count->Required) {
                if (!$this->view_count->IsDetailKey && EmptyValue($this->view_count->FormValue)) {
                    $this->view_count->addErrorMessage(str_replace("%s", $this->view_count->caption(), $this->view_count->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->view_count->FormValue)) {
                $this->view_count->addErrorMessage($this->view_count->getErrorMessage(false));
            }
            if ($this->tags->Visible && $this->tags->Required) {
                if (!$this->tags->IsDetailKey && EmptyValue($this->tags->FormValue)) {
                    $this->tags->addErrorMessage(str_replace("%s", $this->tags->caption(), $this->tags->RequiredErrorMessage));
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

        // category_id
        $this->category_id->setDbValueDef($rsnew, $this->category_id->CurrentValue, $this->category_id->ReadOnly);

        // question
        $this->question->setDbValueDef($rsnew, $this->question->CurrentValue, $this->question->ReadOnly);

        // answer
        $this->answer->setDbValueDef($rsnew, $this->answer->CurrentValue, $this->answer->ReadOnly);

        // display_order
        $this->display_order->setDbValueDef($rsnew, $this->display_order->CurrentValue, $this->display_order->ReadOnly);

        // is_active
        $tmpBool = $this->is_active->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->is_active->setDbValueDef($rsnew, $tmpBool, $this->is_active->ReadOnly);

        // created_at
        $this->created_at->setDbValueDef($rsnew, UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()), $this->created_at->ReadOnly);

        // created_by
        $this->created_by->setDbValueDef($rsnew, $this->created_by->CurrentValue, $this->created_by->ReadOnly);

        // updated_at
        $this->updated_at->setDbValueDef($rsnew, UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()), $this->updated_at->ReadOnly);

        // updated_by
        $this->updated_by->setDbValueDef($rsnew, $this->updated_by->CurrentValue, $this->updated_by->ReadOnly);

        // view_count
        $this->view_count->setDbValueDef($rsnew, $this->view_count->CurrentValue, $this->view_count->ReadOnly);

        // tags
        $this->tags->setDbValueDef($rsnew, $this->tags->CurrentValue, $this->tags->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['category_id'])) { // category_id
            $this->category_id->CurrentValue = $row['category_id'];
        }
        if (isset($row['question'])) { // question
            $this->question->CurrentValue = $row['question'];
        }
        if (isset($row['answer'])) { // answer
            $this->answer->CurrentValue = $row['answer'];
        }
        if (isset($row['display_order'])) { // display_order
            $this->display_order->CurrentValue = $row['display_order'];
        }
        if (isset($row['is_active'])) { // is_active
            $this->is_active->CurrentValue = $row['is_active'];
        }
        if (isset($row['created_at'])) { // created_at
            $this->created_at->CurrentValue = $row['created_at'];
        }
        if (isset($row['created_by'])) { // created_by
            $this->created_by->CurrentValue = $row['created_by'];
        }
        if (isset($row['updated_at'])) { // updated_at
            $this->updated_at->CurrentValue = $row['updated_at'];
        }
        if (isset($row['updated_by'])) { // updated_by
            $this->updated_by->CurrentValue = $row['updated_by'];
        }
        if (isset($row['view_count'])) { // view_count
            $this->view_count->CurrentValue = $row['view_count'];
        }
        if (isset($row['tags'])) { // tags
            $this->tags->CurrentValue = $row['tags'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("FaqItemsList"), "", $this->TableVar, true);
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
