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
class PaymentTransactionsAdd extends PaymentTransactions
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "PaymentTransactionsAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "PaymentTransactionsAdd";

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
        $this->transaction_id->Visible = false;
        $this->request_id->setVisibility();
        $this->user_id->setVisibility();
        $this->payment_method_id->setVisibility();
        $this->transaction_reference->setVisibility();
        $this->amount->setVisibility();
        $this->currency->setVisibility();
        $this->status->setVisibility();
        $this->payment_date->setVisibility();
        $this->gateway_reference->setVisibility();
        $this->gateway_response->setVisibility();
        $this->fee_amount->setVisibility();
        $this->total_amount->setVisibility();
        $this->payment_receipt_url->setVisibility();
        $this->qr_code_path->setVisibility();
        $this->created_at->setVisibility();
        $this->updated_at->setVisibility();
        $this->ip_address->setVisibility();
        $this->user_agent->setVisibility();
        $this->notes->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'payment_transactions';
        $this->TableName = 'payment_transactions';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (payment_transactions)
        if (!isset($GLOBALS["payment_transactions"]) || $GLOBALS["payment_transactions"]::class == PROJECT_NAMESPACE . "payment_transactions") {
            $GLOBALS["payment_transactions"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'payment_transactions');
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
                        $result["view"] = SameString($pageName, "PaymentTransactionsView"); // If View page, no primary button
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
            $key .= @$ar['transaction_id'];
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
            $this->transaction_id->Visible = false;
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
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $CopyRecord;

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

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action", "") !== "") {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("transaction_id") ?? Route("transaction_id")) !== null) {
                $this->transaction_id->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
                $this->setKey($this->OldKey); // Set up record key
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record or default values
        $rsold = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$rsold) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("PaymentTransactionsList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($rsold)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "PaymentTransactionsList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "PaymentTransactionsView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "PaymentTransactionsList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "PaymentTransactionsList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
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
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = RowType::ADD; // Render add type

        // Render row
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

    // Load default values
    protected function loadDefaultValues()
    {
        $this->currency->DefaultValue = $this->currency->getDefault(); // PHP
        $this->currency->OldValue = $this->currency->DefaultValue;
        $this->status->DefaultValue = $this->status->getDefault(); // PHP
        $this->status->OldValue = $this->status->DefaultValue;
        $this->fee_amount->DefaultValue = $this->fee_amount->getDefault(); // PHP
        $this->fee_amount->OldValue = $this->fee_amount->DefaultValue;
        $this->total_amount->DefaultValue = $this->total_amount->getDefault(); // PHP
        $this->total_amount->OldValue = $this->total_amount->DefaultValue;
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
            if (IsApi() && $val === null) {
                $this->request_id->Visible = false; // Disable update for API request
            } else {
                $this->request_id->setFormValue($val, true, $validate);
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

        // Check field name 'payment_method_id' first before field var 'x_payment_method_id'
        $val = $CurrentForm->hasValue("payment_method_id") ? $CurrentForm->getValue("payment_method_id") : $CurrentForm->getValue("x_payment_method_id");
        if (!$this->payment_method_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->payment_method_id->Visible = false; // Disable update for API request
            } else {
                $this->payment_method_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'transaction_reference' first before field var 'x_transaction_reference'
        $val = $CurrentForm->hasValue("transaction_reference") ? $CurrentForm->getValue("transaction_reference") : $CurrentForm->getValue("x_transaction_reference");
        if (!$this->transaction_reference->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->transaction_reference->Visible = false; // Disable update for API request
            } else {
                $this->transaction_reference->setFormValue($val);
            }
        }

        // Check field name 'amount' first before field var 'x_amount'
        $val = $CurrentForm->hasValue("amount") ? $CurrentForm->getValue("amount") : $CurrentForm->getValue("x_amount");
        if (!$this->amount->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->amount->Visible = false; // Disable update for API request
            } else {
                $this->amount->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'currency' first before field var 'x_currency'
        $val = $CurrentForm->hasValue("currency") ? $CurrentForm->getValue("currency") : $CurrentForm->getValue("x_currency");
        if (!$this->currency->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->currency->Visible = false; // Disable update for API request
            } else {
                $this->currency->setFormValue($val);
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

        // Check field name 'payment_date' first before field var 'x_payment_date'
        $val = $CurrentForm->hasValue("payment_date") ? $CurrentForm->getValue("payment_date") : $CurrentForm->getValue("x_payment_date");
        if (!$this->payment_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->payment_date->Visible = false; // Disable update for API request
            } else {
                $this->payment_date->setFormValue($val, true, $validate);
            }
            $this->payment_date->CurrentValue = UnFormatDateTime($this->payment_date->CurrentValue, $this->payment_date->formatPattern());
        }

        // Check field name 'gateway_reference' first before field var 'x_gateway_reference'
        $val = $CurrentForm->hasValue("gateway_reference") ? $CurrentForm->getValue("gateway_reference") : $CurrentForm->getValue("x_gateway_reference");
        if (!$this->gateway_reference->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->gateway_reference->Visible = false; // Disable update for API request
            } else {
                $this->gateway_reference->setFormValue($val);
            }
        }

        // Check field name 'gateway_response' first before field var 'x_gateway_response'
        $val = $CurrentForm->hasValue("gateway_response") ? $CurrentForm->getValue("gateway_response") : $CurrentForm->getValue("x_gateway_response");
        if (!$this->gateway_response->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->gateway_response->Visible = false; // Disable update for API request
            } else {
                $this->gateway_response->setFormValue($val);
            }
        }

        // Check field name 'fee_amount' first before field var 'x_fee_amount'
        $val = $CurrentForm->hasValue("fee_amount") ? $CurrentForm->getValue("fee_amount") : $CurrentForm->getValue("x_fee_amount");
        if (!$this->fee_amount->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fee_amount->Visible = false; // Disable update for API request
            } else {
                $this->fee_amount->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'total_amount' first before field var 'x_total_amount'
        $val = $CurrentForm->hasValue("total_amount") ? $CurrentForm->getValue("total_amount") : $CurrentForm->getValue("x_total_amount");
        if (!$this->total_amount->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->total_amount->Visible = false; // Disable update for API request
            } else {
                $this->total_amount->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'payment_receipt_url' first before field var 'x_payment_receipt_url'
        $val = $CurrentForm->hasValue("payment_receipt_url") ? $CurrentForm->getValue("payment_receipt_url") : $CurrentForm->getValue("x_payment_receipt_url");
        if (!$this->payment_receipt_url->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->payment_receipt_url->Visible = false; // Disable update for API request
            } else {
                $this->payment_receipt_url->setFormValue($val);
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

        // Check field name 'notes' first before field var 'x_notes'
        $val = $CurrentForm->hasValue("notes") ? $CurrentForm->getValue("notes") : $CurrentForm->getValue("x_notes");
        if (!$this->notes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notes->Visible = false; // Disable update for API request
            } else {
                $this->notes->setFormValue($val);
            }
        }

        // Check field name 'transaction_id' first before field var 'x_transaction_id'
        $val = $CurrentForm->hasValue("transaction_id") ? $CurrentForm->getValue("transaction_id") : $CurrentForm->getValue("x_transaction_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->request_id->CurrentValue = $this->request_id->FormValue;
        $this->user_id->CurrentValue = $this->user_id->FormValue;
        $this->payment_method_id->CurrentValue = $this->payment_method_id->FormValue;
        $this->transaction_reference->CurrentValue = $this->transaction_reference->FormValue;
        $this->amount->CurrentValue = $this->amount->FormValue;
        $this->currency->CurrentValue = $this->currency->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
        $this->payment_date->CurrentValue = $this->payment_date->FormValue;
        $this->payment_date->CurrentValue = UnFormatDateTime($this->payment_date->CurrentValue, $this->payment_date->formatPattern());
        $this->gateway_reference->CurrentValue = $this->gateway_reference->FormValue;
        $this->gateway_response->CurrentValue = $this->gateway_response->FormValue;
        $this->fee_amount->CurrentValue = $this->fee_amount->FormValue;
        $this->total_amount->CurrentValue = $this->total_amount->FormValue;
        $this->payment_receipt_url->CurrentValue = $this->payment_receipt_url->FormValue;
        $this->qr_code_path->CurrentValue = $this->qr_code_path->FormValue;
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->updated_at->CurrentValue = $this->updated_at->FormValue;
        $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->ip_address->CurrentValue = $this->ip_address->FormValue;
        $this->user_agent->CurrentValue = $this->user_agent->FormValue;
        $this->notes->CurrentValue = $this->notes->FormValue;
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
        $this->transaction_id->setDbValue($row['transaction_id']);
        $this->request_id->setDbValue($row['request_id']);
        $this->user_id->setDbValue($row['user_id']);
        $this->payment_method_id->setDbValue($row['payment_method_id']);
        $this->transaction_reference->setDbValue($row['transaction_reference']);
        $this->amount->setDbValue($row['amount']);
        $this->currency->setDbValue($row['currency']);
        $this->status->setDbValue($row['status']);
        $this->payment_date->setDbValue($row['payment_date']);
        $this->gateway_reference->setDbValue($row['gateway_reference']);
        $this->gateway_response->setDbValue($row['gateway_response']);
        $this->fee_amount->setDbValue($row['fee_amount']);
        $this->total_amount->setDbValue($row['total_amount']);
        $this->payment_receipt_url->setDbValue($row['payment_receipt_url']);
        $this->qr_code_path->setDbValue($row['qr_code_path']);
        $this->created_at->setDbValue($row['created_at']);
        $this->updated_at->setDbValue($row['updated_at']);
        $this->ip_address->setDbValue($row['ip_address']);
        $this->user_agent->setDbValue($row['user_agent']);
        $this->notes->setDbValue($row['notes']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['transaction_id'] = $this->transaction_id->DefaultValue;
        $row['request_id'] = $this->request_id->DefaultValue;
        $row['user_id'] = $this->user_id->DefaultValue;
        $row['payment_method_id'] = $this->payment_method_id->DefaultValue;
        $row['transaction_reference'] = $this->transaction_reference->DefaultValue;
        $row['amount'] = $this->amount->DefaultValue;
        $row['currency'] = $this->currency->DefaultValue;
        $row['status'] = $this->status->DefaultValue;
        $row['payment_date'] = $this->payment_date->DefaultValue;
        $row['gateway_reference'] = $this->gateway_reference->DefaultValue;
        $row['gateway_response'] = $this->gateway_response->DefaultValue;
        $row['fee_amount'] = $this->fee_amount->DefaultValue;
        $row['total_amount'] = $this->total_amount->DefaultValue;
        $row['payment_receipt_url'] = $this->payment_receipt_url->DefaultValue;
        $row['qr_code_path'] = $this->qr_code_path->DefaultValue;
        $row['created_at'] = $this->created_at->DefaultValue;
        $row['updated_at'] = $this->updated_at->DefaultValue;
        $row['ip_address'] = $this->ip_address->DefaultValue;
        $row['user_agent'] = $this->user_agent->DefaultValue;
        $row['notes'] = $this->notes->DefaultValue;
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

        // transaction_id
        $this->transaction_id->RowCssClass = "row";

        // request_id
        $this->request_id->RowCssClass = "row";

        // user_id
        $this->user_id->RowCssClass = "row";

        // payment_method_id
        $this->payment_method_id->RowCssClass = "row";

        // transaction_reference
        $this->transaction_reference->RowCssClass = "row";

        // amount
        $this->amount->RowCssClass = "row";

        // currency
        $this->currency->RowCssClass = "row";

        // status
        $this->status->RowCssClass = "row";

        // payment_date
        $this->payment_date->RowCssClass = "row";

        // gateway_reference
        $this->gateway_reference->RowCssClass = "row";

        // gateway_response
        $this->gateway_response->RowCssClass = "row";

        // fee_amount
        $this->fee_amount->RowCssClass = "row";

        // total_amount
        $this->total_amount->RowCssClass = "row";

        // payment_receipt_url
        $this->payment_receipt_url->RowCssClass = "row";

        // qr_code_path
        $this->qr_code_path->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // updated_at
        $this->updated_at->RowCssClass = "row";

        // ip_address
        $this->ip_address->RowCssClass = "row";

        // user_agent
        $this->user_agent->RowCssClass = "row";

        // notes
        $this->notes->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // transaction_id
            $this->transaction_id->ViewValue = $this->transaction_id->CurrentValue;

            // request_id
            $this->request_id->ViewValue = $this->request_id->CurrentValue;
            $this->request_id->ViewValue = FormatNumber($this->request_id->ViewValue, $this->request_id->formatPattern());

            // user_id
            $this->user_id->ViewValue = $this->user_id->CurrentValue;
            $this->user_id->ViewValue = FormatNumber($this->user_id->ViewValue, $this->user_id->formatPattern());

            // payment_method_id
            $this->payment_method_id->ViewValue = $this->payment_method_id->CurrentValue;
            $this->payment_method_id->ViewValue = FormatNumber($this->payment_method_id->ViewValue, $this->payment_method_id->formatPattern());

            // transaction_reference
            $this->transaction_reference->ViewValue = $this->transaction_reference->CurrentValue;

            // amount
            $this->amount->ViewValue = $this->amount->CurrentValue;
            $this->amount->ViewValue = FormatNumber($this->amount->ViewValue, $this->amount->formatPattern());

            // currency
            $this->currency->ViewValue = $this->currency->CurrentValue;

            // status
            $this->status->ViewValue = $this->status->CurrentValue;

            // payment_date
            $this->payment_date->ViewValue = $this->payment_date->CurrentValue;
            $this->payment_date->ViewValue = FormatDateTime($this->payment_date->ViewValue, $this->payment_date->formatPattern());

            // gateway_reference
            $this->gateway_reference->ViewValue = $this->gateway_reference->CurrentValue;

            // gateway_response
            $this->gateway_response->ViewValue = $this->gateway_response->CurrentValue;

            // fee_amount
            $this->fee_amount->ViewValue = $this->fee_amount->CurrentValue;
            $this->fee_amount->ViewValue = FormatNumber($this->fee_amount->ViewValue, $this->fee_amount->formatPattern());

            // total_amount
            $this->total_amount->ViewValue = $this->total_amount->CurrentValue;
            $this->total_amount->ViewValue = FormatNumber($this->total_amount->ViewValue, $this->total_amount->formatPattern());

            // payment_receipt_url
            $this->payment_receipt_url->ViewValue = $this->payment_receipt_url->CurrentValue;

            // qr_code_path
            $this->qr_code_path->ViewValue = $this->qr_code_path->CurrentValue;

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // updated_at
            $this->updated_at->ViewValue = $this->updated_at->CurrentValue;
            $this->updated_at->ViewValue = FormatDateTime($this->updated_at->ViewValue, $this->updated_at->formatPattern());

            // ip_address
            $this->ip_address->ViewValue = $this->ip_address->CurrentValue;

            // user_agent
            $this->user_agent->ViewValue = $this->user_agent->CurrentValue;

            // notes
            $this->notes->ViewValue = $this->notes->CurrentValue;

            // request_id
            $this->request_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // payment_method_id
            $this->payment_method_id->HrefValue = "";

            // transaction_reference
            $this->transaction_reference->HrefValue = "";

            // amount
            $this->amount->HrefValue = "";

            // currency
            $this->currency->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // payment_date
            $this->payment_date->HrefValue = "";

            // gateway_reference
            $this->gateway_reference->HrefValue = "";

            // gateway_response
            $this->gateway_response->HrefValue = "";

            // fee_amount
            $this->fee_amount->HrefValue = "";

            // total_amount
            $this->total_amount->HrefValue = "";

            // payment_receipt_url
            $this->payment_receipt_url->HrefValue = "";

            // qr_code_path
            $this->qr_code_path->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // ip_address
            $this->ip_address->HrefValue = "";

            // user_agent
            $this->user_agent->HrefValue = "";

            // notes
            $this->notes->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // request_id
            $this->request_id->setupEditAttributes();
            $this->request_id->EditValue = $this->request_id->CurrentValue;
            $this->request_id->PlaceHolder = RemoveHtml($this->request_id->caption());
            if (strval($this->request_id->EditValue) != "" && is_numeric($this->request_id->EditValue)) {
                $this->request_id->EditValue = FormatNumber($this->request_id->EditValue, null);
            }

            // user_id
            $this->user_id->setupEditAttributes();
            $this->user_id->EditValue = $this->user_id->CurrentValue;
            $this->user_id->PlaceHolder = RemoveHtml($this->user_id->caption());
            if (strval($this->user_id->EditValue) != "" && is_numeric($this->user_id->EditValue)) {
                $this->user_id->EditValue = FormatNumber($this->user_id->EditValue, null);
            }

            // payment_method_id
            $this->payment_method_id->setupEditAttributes();
            $this->payment_method_id->EditValue = $this->payment_method_id->CurrentValue;
            $this->payment_method_id->PlaceHolder = RemoveHtml($this->payment_method_id->caption());
            if (strval($this->payment_method_id->EditValue) != "" && is_numeric($this->payment_method_id->EditValue)) {
                $this->payment_method_id->EditValue = FormatNumber($this->payment_method_id->EditValue, null);
            }

            // transaction_reference
            $this->transaction_reference->setupEditAttributes();
            if (!$this->transaction_reference->Raw) {
                $this->transaction_reference->CurrentValue = HtmlDecode($this->transaction_reference->CurrentValue);
            }
            $this->transaction_reference->EditValue = HtmlEncode($this->transaction_reference->CurrentValue);
            $this->transaction_reference->PlaceHolder = RemoveHtml($this->transaction_reference->caption());

            // amount
            $this->amount->setupEditAttributes();
            $this->amount->EditValue = $this->amount->CurrentValue;
            $this->amount->PlaceHolder = RemoveHtml($this->amount->caption());
            if (strval($this->amount->EditValue) != "" && is_numeric($this->amount->EditValue)) {
                $this->amount->EditValue = FormatNumber($this->amount->EditValue, null);
            }

            // currency
            $this->currency->setupEditAttributes();
            if (!$this->currency->Raw) {
                $this->currency->CurrentValue = HtmlDecode($this->currency->CurrentValue);
            }
            $this->currency->EditValue = HtmlEncode($this->currency->CurrentValue);
            $this->currency->PlaceHolder = RemoveHtml($this->currency->caption());

            // status
            $this->status->setupEditAttributes();
            if (!$this->status->Raw) {
                $this->status->CurrentValue = HtmlDecode($this->status->CurrentValue);
            }
            $this->status->EditValue = HtmlEncode($this->status->CurrentValue);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // payment_date
            $this->payment_date->setupEditAttributes();
            $this->payment_date->EditValue = HtmlEncode(FormatDateTime($this->payment_date->CurrentValue, $this->payment_date->formatPattern()));
            $this->payment_date->PlaceHolder = RemoveHtml($this->payment_date->caption());

            // gateway_reference
            $this->gateway_reference->setupEditAttributes();
            if (!$this->gateway_reference->Raw) {
                $this->gateway_reference->CurrentValue = HtmlDecode($this->gateway_reference->CurrentValue);
            }
            $this->gateway_reference->EditValue = HtmlEncode($this->gateway_reference->CurrentValue);
            $this->gateway_reference->PlaceHolder = RemoveHtml($this->gateway_reference->caption());

            // gateway_response
            $this->gateway_response->setupEditAttributes();
            $this->gateway_response->EditValue = HtmlEncode($this->gateway_response->CurrentValue);
            $this->gateway_response->PlaceHolder = RemoveHtml($this->gateway_response->caption());

            // fee_amount
            $this->fee_amount->setupEditAttributes();
            $this->fee_amount->EditValue = $this->fee_amount->CurrentValue;
            $this->fee_amount->PlaceHolder = RemoveHtml($this->fee_amount->caption());
            if (strval($this->fee_amount->EditValue) != "" && is_numeric($this->fee_amount->EditValue)) {
                $this->fee_amount->EditValue = FormatNumber($this->fee_amount->EditValue, null);
            }

            // total_amount
            $this->total_amount->setupEditAttributes();
            $this->total_amount->EditValue = $this->total_amount->CurrentValue;
            $this->total_amount->PlaceHolder = RemoveHtml($this->total_amount->caption());
            if (strval($this->total_amount->EditValue) != "" && is_numeric($this->total_amount->EditValue)) {
                $this->total_amount->EditValue = FormatNumber($this->total_amount->EditValue, null);
            }

            // payment_receipt_url
            $this->payment_receipt_url->setupEditAttributes();
            if (!$this->payment_receipt_url->Raw) {
                $this->payment_receipt_url->CurrentValue = HtmlDecode($this->payment_receipt_url->CurrentValue);
            }
            $this->payment_receipt_url->EditValue = HtmlEncode($this->payment_receipt_url->CurrentValue);
            $this->payment_receipt_url->PlaceHolder = RemoveHtml($this->payment_receipt_url->caption());

            // qr_code_path
            $this->qr_code_path->setupEditAttributes();
            if (!$this->qr_code_path->Raw) {
                $this->qr_code_path->CurrentValue = HtmlDecode($this->qr_code_path->CurrentValue);
            }
            $this->qr_code_path->EditValue = HtmlEncode($this->qr_code_path->CurrentValue);
            $this->qr_code_path->PlaceHolder = RemoveHtml($this->qr_code_path->caption());

            // created_at
            $this->created_at->setupEditAttributes();
            $this->created_at->EditValue = HtmlEncode(FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()));
            $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

            // updated_at
            $this->updated_at->setupEditAttributes();
            $this->updated_at->EditValue = HtmlEncode(FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()));
            $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

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

            // notes
            $this->notes->setupEditAttributes();
            $this->notes->EditValue = HtmlEncode($this->notes->CurrentValue);
            $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());

            // Add refer script

            // request_id
            $this->request_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // payment_method_id
            $this->payment_method_id->HrefValue = "";

            // transaction_reference
            $this->transaction_reference->HrefValue = "";

            // amount
            $this->amount->HrefValue = "";

            // currency
            $this->currency->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // payment_date
            $this->payment_date->HrefValue = "";

            // gateway_reference
            $this->gateway_reference->HrefValue = "";

            // gateway_response
            $this->gateway_response->HrefValue = "";

            // fee_amount
            $this->fee_amount->HrefValue = "";

            // total_amount
            $this->total_amount->HrefValue = "";

            // payment_receipt_url
            $this->payment_receipt_url->HrefValue = "";

            // qr_code_path
            $this->qr_code_path->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // ip_address
            $this->ip_address->HrefValue = "";

            // user_agent
            $this->user_agent->HrefValue = "";

            // notes
            $this->notes->HrefValue = "";
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
            if (!CheckInteger($this->request_id->FormValue)) {
                $this->request_id->addErrorMessage($this->request_id->getErrorMessage(false));
            }
            if ($this->user_id->Visible && $this->user_id->Required) {
                if (!$this->user_id->IsDetailKey && EmptyValue($this->user_id->FormValue)) {
                    $this->user_id->addErrorMessage(str_replace("%s", $this->user_id->caption(), $this->user_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->user_id->FormValue)) {
                $this->user_id->addErrorMessage($this->user_id->getErrorMessage(false));
            }
            if ($this->payment_method_id->Visible && $this->payment_method_id->Required) {
                if (!$this->payment_method_id->IsDetailKey && EmptyValue($this->payment_method_id->FormValue)) {
                    $this->payment_method_id->addErrorMessage(str_replace("%s", $this->payment_method_id->caption(), $this->payment_method_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->payment_method_id->FormValue)) {
                $this->payment_method_id->addErrorMessage($this->payment_method_id->getErrorMessage(false));
            }
            if ($this->transaction_reference->Visible && $this->transaction_reference->Required) {
                if (!$this->transaction_reference->IsDetailKey && EmptyValue($this->transaction_reference->FormValue)) {
                    $this->transaction_reference->addErrorMessage(str_replace("%s", $this->transaction_reference->caption(), $this->transaction_reference->RequiredErrorMessage));
                }
            }
            if ($this->amount->Visible && $this->amount->Required) {
                if (!$this->amount->IsDetailKey && EmptyValue($this->amount->FormValue)) {
                    $this->amount->addErrorMessage(str_replace("%s", $this->amount->caption(), $this->amount->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->amount->FormValue)) {
                $this->amount->addErrorMessage($this->amount->getErrorMessage(false));
            }
            if ($this->currency->Visible && $this->currency->Required) {
                if (!$this->currency->IsDetailKey && EmptyValue($this->currency->FormValue)) {
                    $this->currency->addErrorMessage(str_replace("%s", $this->currency->caption(), $this->currency->RequiredErrorMessage));
                }
            }
            if ($this->status->Visible && $this->status->Required) {
                if (!$this->status->IsDetailKey && EmptyValue($this->status->FormValue)) {
                    $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
                }
            }
            if ($this->payment_date->Visible && $this->payment_date->Required) {
                if (!$this->payment_date->IsDetailKey && EmptyValue($this->payment_date->FormValue)) {
                    $this->payment_date->addErrorMessage(str_replace("%s", $this->payment_date->caption(), $this->payment_date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->payment_date->FormValue, $this->payment_date->formatPattern())) {
                $this->payment_date->addErrorMessage($this->payment_date->getErrorMessage(false));
            }
            if ($this->gateway_reference->Visible && $this->gateway_reference->Required) {
                if (!$this->gateway_reference->IsDetailKey && EmptyValue($this->gateway_reference->FormValue)) {
                    $this->gateway_reference->addErrorMessage(str_replace("%s", $this->gateway_reference->caption(), $this->gateway_reference->RequiredErrorMessage));
                }
            }
            if ($this->gateway_response->Visible && $this->gateway_response->Required) {
                if (!$this->gateway_response->IsDetailKey && EmptyValue($this->gateway_response->FormValue)) {
                    $this->gateway_response->addErrorMessage(str_replace("%s", $this->gateway_response->caption(), $this->gateway_response->RequiredErrorMessage));
                }
            }
            if ($this->fee_amount->Visible && $this->fee_amount->Required) {
                if (!$this->fee_amount->IsDetailKey && EmptyValue($this->fee_amount->FormValue)) {
                    $this->fee_amount->addErrorMessage(str_replace("%s", $this->fee_amount->caption(), $this->fee_amount->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->fee_amount->FormValue)) {
                $this->fee_amount->addErrorMessage($this->fee_amount->getErrorMessage(false));
            }
            if ($this->total_amount->Visible && $this->total_amount->Required) {
                if (!$this->total_amount->IsDetailKey && EmptyValue($this->total_amount->FormValue)) {
                    $this->total_amount->addErrorMessage(str_replace("%s", $this->total_amount->caption(), $this->total_amount->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->total_amount->FormValue)) {
                $this->total_amount->addErrorMessage($this->total_amount->getErrorMessage(false));
            }
            if ($this->payment_receipt_url->Visible && $this->payment_receipt_url->Required) {
                if (!$this->payment_receipt_url->IsDetailKey && EmptyValue($this->payment_receipt_url->FormValue)) {
                    $this->payment_receipt_url->addErrorMessage(str_replace("%s", $this->payment_receipt_url->caption(), $this->payment_receipt_url->RequiredErrorMessage));
                }
            }
            if ($this->qr_code_path->Visible && $this->qr_code_path->Required) {
                if (!$this->qr_code_path->IsDetailKey && EmptyValue($this->qr_code_path->FormValue)) {
                    $this->qr_code_path->addErrorMessage(str_replace("%s", $this->qr_code_path->caption(), $this->qr_code_path->RequiredErrorMessage));
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
            if ($this->notes->Visible && $this->notes->Required) {
                if (!$this->notes->IsDetailKey && EmptyValue($this->notes->FormValue)) {
                    $this->notes->addErrorMessage(str_replace("%s", $this->notes->caption(), $this->notes->RequiredErrorMessage));
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Get new row
        $rsnew = $this->getAddRow();

        // Update current values
        $this->setCurrentValues($rsnew);
        if ($this->transaction_reference->CurrentValue != "") { // Check field with unique index
            $filter = "(\"transaction_reference\" = '" . AdjustSql($this->transaction_reference->CurrentValue, $this->Dbid) . "')";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->transaction_reference->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->transaction_reference->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            } elseif (!EmptyValue($this->DbErrorMessage)) { // Show database error
                $this->setFailureMessage($this->DbErrorMessage);
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
        }
        return $addRow;
    }

    /**
     * Get add row
     *
     * @return array
     */
    protected function getAddRow()
    {
        global $Security;
        $rsnew = [];

        // request_id
        $this->request_id->setDbValueDef($rsnew, $this->request_id->CurrentValue, false);

        // user_id
        $this->user_id->setDbValueDef($rsnew, $this->user_id->CurrentValue, false);

        // payment_method_id
        $this->payment_method_id->setDbValueDef($rsnew, $this->payment_method_id->CurrentValue, false);

        // transaction_reference
        $this->transaction_reference->setDbValueDef($rsnew, $this->transaction_reference->CurrentValue, false);

        // amount
        $this->amount->setDbValueDef($rsnew, $this->amount->CurrentValue, false);

        // currency
        $this->currency->setDbValueDef($rsnew, $this->currency->CurrentValue, strval($this->currency->CurrentValue) == "");

        // status
        $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, strval($this->status->CurrentValue) == "");

        // payment_date
        $this->payment_date->setDbValueDef($rsnew, UnFormatDateTime($this->payment_date->CurrentValue, $this->payment_date->formatPattern()), false);

        // gateway_reference
        $this->gateway_reference->setDbValueDef($rsnew, $this->gateway_reference->CurrentValue, false);

        // gateway_response
        $this->gateway_response->setDbValueDef($rsnew, $this->gateway_response->CurrentValue, false);

        // fee_amount
        $this->fee_amount->setDbValueDef($rsnew, $this->fee_amount->CurrentValue, strval($this->fee_amount->CurrentValue) == "");

        // total_amount
        $this->total_amount->setDbValueDef($rsnew, $this->total_amount->CurrentValue, strval($this->total_amount->CurrentValue) == "");

        // payment_receipt_url
        $this->payment_receipt_url->setDbValueDef($rsnew, $this->payment_receipt_url->CurrentValue, false);

        // qr_code_path
        $this->qr_code_path->setDbValueDef($rsnew, $this->qr_code_path->CurrentValue, false);

        // created_at
        $this->created_at->setDbValueDef($rsnew, UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern()), false);

        // updated_at
        $this->updated_at->setDbValueDef($rsnew, UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()), false);

        // ip_address
        $this->ip_address->setDbValueDef($rsnew, $this->ip_address->CurrentValue, false);

        // user_agent
        $this->user_agent->setDbValueDef($rsnew, $this->user_agent->CurrentValue, false);

        // notes
        $this->notes->setDbValueDef($rsnew, $this->notes->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['request_id'])) { // request_id
            $this->request_id->setFormValue($row['request_id']);
        }
        if (isset($row['user_id'])) { // user_id
            $this->user_id->setFormValue($row['user_id']);
        }
        if (isset($row['payment_method_id'])) { // payment_method_id
            $this->payment_method_id->setFormValue($row['payment_method_id']);
        }
        if (isset($row['transaction_reference'])) { // transaction_reference
            $this->transaction_reference->setFormValue($row['transaction_reference']);
        }
        if (isset($row['amount'])) { // amount
            $this->amount->setFormValue($row['amount']);
        }
        if (isset($row['currency'])) { // currency
            $this->currency->setFormValue($row['currency']);
        }
        if (isset($row['status'])) { // status
            $this->status->setFormValue($row['status']);
        }
        if (isset($row['payment_date'])) { // payment_date
            $this->payment_date->setFormValue($row['payment_date']);
        }
        if (isset($row['gateway_reference'])) { // gateway_reference
            $this->gateway_reference->setFormValue($row['gateway_reference']);
        }
        if (isset($row['gateway_response'])) { // gateway_response
            $this->gateway_response->setFormValue($row['gateway_response']);
        }
        if (isset($row['fee_amount'])) { // fee_amount
            $this->fee_amount->setFormValue($row['fee_amount']);
        }
        if (isset($row['total_amount'])) { // total_amount
            $this->total_amount->setFormValue($row['total_amount']);
        }
        if (isset($row['payment_receipt_url'])) { // payment_receipt_url
            $this->payment_receipt_url->setFormValue($row['payment_receipt_url']);
        }
        if (isset($row['qr_code_path'])) { // qr_code_path
            $this->qr_code_path->setFormValue($row['qr_code_path']);
        }
        if (isset($row['created_at'])) { // created_at
            $this->created_at->setFormValue($row['created_at']);
        }
        if (isset($row['updated_at'])) { // updated_at
            $this->updated_at->setFormValue($row['updated_at']);
        }
        if (isset($row['ip_address'])) { // ip_address
            $this->ip_address->setFormValue($row['ip_address']);
        }
        if (isset($row['user_agent'])) { // user_agent
            $this->user_agent->setFormValue($row['user_agent']);
        }
        if (isset($row['notes'])) { // notes
            $this->notes->setFormValue($row['notes']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("PaymentTransactionsList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
