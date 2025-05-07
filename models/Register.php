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
class Register extends Users
{
    use MessagesTrait;

    // Page ID
    public $PageID = "register";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "Register";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "register";

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

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'users';
        $this->TableName = 'users';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-register-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (users)
        if (!isset($GLOBALS["users"]) || $GLOBALS["users"]::class == PROJECT_NAMESPACE . "users") {
            $GLOBALS["users"] = &$this;
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
                WriteJson(["url" => $url]);
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
                $this->photo->OldUploadPath = $this->photo->getUploadPath(); // PHP
                $this->photo->UploadPath = $this->photo->OldUploadPath;
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
            $key .= @$ar['user_id'];
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
            $this->user_id->Visible = false;
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
    public $FormClassName = "ew-form ew-register-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm, $UserTable, $CurrentLanguage, $Breadcrumb, $SkipHeaderFooter;

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
        }

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action

        // Global Page Loading event (in userfn*.php)
        DispatchEvent(new PageLoadingEvent($this), PageLoadingEvent::NAME);

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Set up Breadcrumb
        $Breadcrumb = Breadcrumb::create("index")->add("register", "RegisterPage", CurrentUrl(), "", "", true);
        $this->Heading = $Language->phrase("RegisterPage");

        // Load default values
        $this->loadRowValues();

        // Get action
        $action = "";
        if (IsApi()) {
            $action = "insert";
        } elseif (Post("action") != "") {
            $action = Post("action");
        }

        // Check action
        if ($action != "") {
            // Get action
            $this->CurrentAction = $action;
            $this->loadFormValues(); // Get form values

            // Validate form
            if (!$this->validateForm()) {
                if (IsApi()) {
                    WriteJson([
                        "success" => false,
                        "validation" => $this->getValidationErrors(),
                        "error" => $this->getFailureMessage()
                    ]);
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        } elseif (IsRegistering()) { // Return from 2FA
            $this->CurrentAction = "insert";
            $this->restoreAddFormFromRow(Session(SESSION_USER_PROFILE_RECORD)); // Restore add form values
        } else {
            $this->CurrentAction = "show"; // Display blank record
        }

        // Set up return page
        $returnPage = "";
        if (EmptyValue($returnPage)) {
            $returnPage = Config("REGISTER_AUTO_LOGIN") ? "index" : "login";
        }

        // Handle email activation
        $action = Get("action");
        if (Config("REGISTER_ACTIVATE") && !EmptyValue(Config("REGISTER_ACTIVATE_FIELD_NAME")) && SameText($action, "confirm")) {
            $user = Get("user", "");
            $token = Get("token", "");
            $userName = DecodeJwt($token)["username"] ?? "";
            if (!EmptyValue($userName) && $user == $userName) {
                if ($this->activateUser($userName)) { // Activate user
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("ActivateAccount")); // Set up message acount activated
                    }
                    if (Config("REGISTER_AUTO_LOGIN") && !EmptyValue(Config("LOGIN_USERNAME_FIELD_NAME")) && !EmptyValue(Config("LOGIN_PASSWORD_FIELD_NAME"))) {
                        if ($Security->validateUser($userName, $token, "token")) {
                            $this->terminate($returnPage); // Go to return page
                            return;
                        } else {
                            $this->setFailureMessage($Language->phrase("AutoLoginFailed")); // Set auto login failed message
                            $this->terminate("login"); // Go to login page
                            return;
                        }
                    } else {
                        $this->terminate("login"); // Go to login page
                        return;
                    }
                }
            }
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("ActivateFailed")); // Set activate failed message
            }
            $this->terminate("login"); // Go to login page
            return;
        }

        // Insert record
        if ($this->isInsert()) {
            // Check for duplicate User ID
            $user = FindUserByUserName($this->_username->CurrentValue);
            if ($user) {
                $this->restoreFormValues(); // Restore form values
                $this->setFailureMessage($Language->phrase("UserExists")); // Set user exist message
            }
            if (!$user) {
                // Handle two factor authentication
                if (
                    Config("USE_TWO_FACTOR_AUTHENTICATION") &&
                    Config("FORCE_TWO_FACTOR_AUTHENTICATION") &&
                    in_array(strtolower(Config("TWO_FACTOR_AUTHENTICATION_TYPE")), ["email", "sms"]) &&
                    !IsRegistering2FA() &&
                    !IsRegistering()
                ) {
                    $_SESSION[SESSION_USER_PROFILE_RECORD] = $this->getAddRow(); // Save record
                    $res = TwoFactorAuthenticationClass()::sendOneTimePassword($this->_username->CurrentValue); // Send one time password
                    if ($res === true) {
                        $_SESSION[SESSION_STATUS] = "registering2fa";
                        $_SESSION[SESSION_USER_PROFILE_USER_NAME] = $this->_username->CurrentValue;
                        if (Config("REGISTER_AUTO_LOGIN") && !EmptyValue(Config("LOGIN_USERNAME_FIELD_NAME")) && !EmptyValue(Config("LOGIN_PASSWORD_FIELD_NAME"))) {
                            $_SESSION[SESSION_USER_PROFILE_PASSWORD] = $this->password_hash->FormValue;
                        } else {
                            $_SESSION[SESSION_USER_PROFILE_PASSWORD] = ""; // DO NOT auto login
                        }
                        $this->terminate("login2fa"); // Go to two factor authentication
                        return;
                    } else {
                        $_SESSION[SESSION_USER_PROFILE_RECORD] = ""; // Clear record
                        $this->setFailureMessage($res);
                        $this->CurrentAction = "show"; // Reset action
                        $this->EventCancelled = true; // Event cancelled
                    }
                } else {
                    $res = true;
                }
                $this->SendEmail = true; // Send email on add success
                if ($res === true && $this->addRow()) { // Add record
                    if (IsRegistering()) { // Update user profile and clear status
                        $usr = $_SESSION[SESSION_USER_PROFILE_USER_NAME];
                        $code = $_SESSION[SESSION_USER_PROFILE_SECURITY_CODE];
                        $row = $_SESSION[SESSION_USER_PROFILE_RECORD];
                        $_SESSION[SESSION_USER_PROFILE_RECORD] = "";
                        $_SESSION[SESSION_USER_PROFILE_SECURITY_CODE] = "";
                        $_SESSION[SESSION_USER_PROFILE_USER_NAME] = "";
                        $_SESSION[SESSION_STATUS] = "";
                        $profile = new UserProfile($usr);
                        $account = SameText(Config("TWO_FACTOR_AUTHENTICATION_TYPE"), "email")
                            ? $row[Config("USER_EMAIL_FIELD_NAME")]
                            : $row[Config("USER_PHONE_FIELD_NAME")];
                        $profile->setOneTimePassword($account, $code);
                        $profile->verify2FACode($code);
                    }
                    if (Config("REGISTER_ACTIVATE") && !EmptyValue(Config("REGISTER_ACTIVATE_FIELD_NAME"))) {
                        if ($this->getSuccessMessage() == "") {
                            $this->setSuccessMessage($Language->phrase("RegisterSuccessActivate")); // Activate success
                        }
                    } else {
                        if ($this->getSuccessMessage() == "") {
                            $this->setSuccessMessage($Language->phrase("RegisterSuccess")); // Register success
                        }
                        // Auto login user after registration
                        if (Config("REGISTER_AUTO_LOGIN") && !EmptyValue(Config("LOGIN_USERNAME_FIELD_NAME")) && !EmptyValue(Config("LOGIN_PASSWORD_FIELD_NAME"))) {
                            if (!$Security->validateUser($this->_username->CurrentValue, $this->password_hash->FormValue, "register")) {
                                $this->setFailureMessage($Language->phrase("AutoLoginFailed")); // Set auto login failure message
                            }
                        }
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnPage); // Return
                        return;
                    }
                } else {
                    $this->restoreFormValues(); // Restore form values
                }
            }
        }

        // API request, return
        if (IsApi()) {
            $this->terminate();
            return;
        }

        // Render row
        $this->RowType = RowType::ADD; // Render add
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

    // Activate account based on user
    protected function activateUser($usr)
    {
        global $Language;
        if (!Config("REGISTER_ACTIVATE") || EmptyValue(Config("REGISTER_ACTIVATE_FIELD_NAME"))) {
            return false;
        }
        if ($this->UpdateTable != $this->TableName && $this->UpdateTable != $this->getSqlFrom()) { // Note: The username field name must be the same
            $entityClass = GetEntityClass($this->UpdateTable);
            if ($entityClass) {
                $user = GetUserEntityManager()->getRepository($entityClass)->findOneBy(["username" => $usr]);
            } else {
                throw new \Exception("Entity class for UpdateTable not found.");
            }
        } else {
            $user = FindUserByUserName($usr);
        }
        if ($user) {
            $this->loadRowValues($user->toArray()); // Load row values
            try {
                if (!ConvertToBool($user->get(Config("REGISTER_ACTIVATE_FIELD_NAME")))) {
                    $user->set(Config("REGISTER_ACTIVATE_FIELD_NAME"), Config("REGISTER_ACTIVATE_FIELD_VALUE")); // Auto register
                    GetUserEntityManager()->flush();

                    // Call User Activated event
                    $this->userActivated($user->toArray());
                    return true;
                } else {
                    $this->setFailureMessage($Language->phrase("ActivateAgain"));
                    return false;
                }
            } catch (\Exception $e) {
                $this->setFailureMessage($e->getMessage());
                return false;
            }
        } else {
            $this->setFailureMessage($Language->phrase("NoRecord"));
            return false;
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
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'user_id' first before field var 'x_user_id'
        $val = $CurrentForm->hasValue("user_id") ? $CurrentForm->getValue("user_id") : $CurrentForm->getValue("x_user_id");

        // Check field name 'username' first before field var 'x__username'
        $val = $CurrentForm->hasValue("username") ? $CurrentForm->getValue("username") : $CurrentForm->getValue("x__username");
        if (!$this->_username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_username->Visible = false; // Disable update for API request
            } else {
                $this->_username->setFormValue($val);
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

        // Check field name 'password_hash' first before field var 'x_password_hash'
        $val = $CurrentForm->hasValue("password_hash") ? $CurrentForm->getValue("password_hash") : $CurrentForm->getValue("x_password_hash");
        if (!$this->password_hash->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->password_hash->Visible = false; // Disable update for API request
            } else {
                $this->password_hash->setFormValue($val);
            }
        }

        // Note: ConfirmValue will be compared with FormValue
        if (Config("ENCRYPTED_PASSWORD")) { // Encrypted password, use raw value
            $this->password_hash->ConfirmValue = $CurrentForm->getValue("c_password_hash");
        } else {
            $this->password_hash->ConfirmValue = RemoveXss($CurrentForm->getValue("c_password_hash"));
        }

        // Check field name 'mobile_number' first before field var 'x_mobile_number'
        $val = $CurrentForm->hasValue("mobile_number") ? $CurrentForm->getValue("mobile_number") : $CurrentForm->getValue("x_mobile_number");
        if (!$this->mobile_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mobile_number->Visible = false; // Disable update for API request
            } else {
                $this->mobile_number->setFormValue($val);
            }
        }

        // Check field name 'first_name' first before field var 'x_first_name'
        $val = $CurrentForm->hasValue("first_name") ? $CurrentForm->getValue("first_name") : $CurrentForm->getValue("x_first_name");
        if (!$this->first_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->first_name->Visible = false; // Disable update for API request
            } else {
                $this->first_name->setFormValue($val);
            }
        }

        // Check field name 'middle_name' first before field var 'x_middle_name'
        $val = $CurrentForm->hasValue("middle_name") ? $CurrentForm->getValue("middle_name") : $CurrentForm->getValue("x_middle_name");
        if (!$this->middle_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->middle_name->Visible = false; // Disable update for API request
            } else {
                $this->middle_name->setFormValue($val);
            }
        }

        // Check field name 'last_name' first before field var 'x_last_name'
        $val = $CurrentForm->hasValue("last_name") ? $CurrentForm->getValue("last_name") : $CurrentForm->getValue("x_last_name");
        if (!$this->last_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->last_name->Visible = false; // Disable update for API request
            } else {
                $this->last_name->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->_username->CurrentValue = $this->_username->FormValue;
        $this->_email->CurrentValue = $this->_email->FormValue;
        $this->password_hash->CurrentValue = $this->password_hash->FormValue;
        $this->mobile_number->CurrentValue = $this->mobile_number->FormValue;
        $this->first_name->CurrentValue = $this->first_name->FormValue;
        $this->middle_name->CurrentValue = $this->middle_name->FormValue;
        $this->last_name->CurrentValue = $this->last_name->FormValue;
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
        $this->user_id->setDbValue($row['user_id']);
        $this->department_id->setDbValue($row['department_id']);
        $this->_username->setDbValue($row['username']);
        $this->_email->setDbValue($row['email']);
        $this->password_hash->setDbValue($row['password_hash']);
        $this->mobile_number->setDbValue($row['mobile_number']);
        $this->first_name->setDbValue($row['first_name']);
        $this->middle_name->setDbValue($row['middle_name']);
        $this->last_name->setDbValue($row['last_name']);
        $this->date_created->setDbValue($row['date_created']);
        $this->last_login->setDbValue($row['last_login']);
        $this->is_active->setDbValue((ConvertToBool($row['is_active']) ? "1" : "0"));
        $this->user_level_id->setDbValue($row['user_level_id']);
        $this->reports_to_user_id->setDbValue($row['reports_to_user_id']);
        $this->photo->Upload->DbValue = $row['photo'];
        $this->photo->setDbValue($this->photo->Upload->DbValue);
        $this->_profile->setDbValue($row['profile']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['user_id'] = $this->user_id->DefaultValue;
        $row['department_id'] = $this->department_id->DefaultValue;
        $row['username'] = $this->_username->DefaultValue;
        $row['email'] = $this->_email->DefaultValue;
        $row['password_hash'] = $this->password_hash->DefaultValue;
        $row['mobile_number'] = $this->mobile_number->DefaultValue;
        $row['first_name'] = $this->first_name->DefaultValue;
        $row['middle_name'] = $this->middle_name->DefaultValue;
        $row['last_name'] = $this->last_name->DefaultValue;
        $row['date_created'] = $this->date_created->DefaultValue;
        $row['last_login'] = $this->last_login->DefaultValue;
        $row['is_active'] = $this->is_active->DefaultValue;
        $row['user_level_id'] = $this->user_level_id->DefaultValue;
        $row['reports_to_user_id'] = $this->reports_to_user_id->DefaultValue;
        $row['photo'] = $this->photo->DefaultValue;
        $row['profile'] = $this->_profile->DefaultValue;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // user_id
        $this->user_id->RowCssClass = "row";

        // department_id
        $this->department_id->RowCssClass = "row";

        // username
        $this->_username->RowCssClass = "row";

        // email
        $this->_email->RowCssClass = "row";

        // password_hash
        $this->password_hash->RowCssClass = "row";

        // mobile_number
        $this->mobile_number->RowCssClass = "row";

        // first_name
        $this->first_name->RowCssClass = "row";

        // middle_name
        $this->middle_name->RowCssClass = "row";

        // last_name
        $this->last_name->RowCssClass = "row";

        // date_created
        $this->date_created->RowCssClass = "row";

        // last_login
        $this->last_login->RowCssClass = "row";

        // is_active
        $this->is_active->RowCssClass = "row";

        // user_level_id
        $this->user_level_id->RowCssClass = "row";

        // reports_to_user_id
        $this->reports_to_user_id->RowCssClass = "row";

        // photo
        $this->photo->RowCssClass = "row";

        // profile
        $this->_profile->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // user_id
            $this->user_id->ViewValue = $this->user_id->CurrentValue;

            // department_id
            $curVal = strval($this->department_id->CurrentValue);
            if ($curVal != "") {
                $this->department_id->ViewValue = $this->department_id->lookupCacheOption($curVal);
                if ($this->department_id->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->department_id->Lookup->getTable()->Fields["department_id"]->searchExpression(), "=", $curVal, $this->department_id->Lookup->getTable()->Fields["department_id"]->searchDataType(), "");
                    $sqlWrk = $this->department_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->department_id->Lookup->renderViewRow($rswrk[0]);
                        $this->department_id->ViewValue = $this->department_id->displayValue($arwrk);
                    } else {
                        $this->department_id->ViewValue = FormatNumber($this->department_id->CurrentValue, $this->department_id->formatPattern());
                    }
                }
            } else {
                $this->department_id->ViewValue = null;
            }

            // username
            $this->_username->ViewValue = $this->_username->CurrentValue;

            // email
            $this->_email->ViewValue = $this->_email->CurrentValue;

            // password_hash
            $this->password_hash->ViewValue = $Language->phrase("PasswordMask");

            // mobile_number
            $this->mobile_number->ViewValue = $this->mobile_number->CurrentValue;

            // first_name
            $this->first_name->ViewValue = $this->first_name->CurrentValue;

            // middle_name
            $this->middle_name->ViewValue = $this->middle_name->CurrentValue;

            // last_name
            $this->last_name->ViewValue = $this->last_name->CurrentValue;

            // date_created
            $this->date_created->ViewValue = $this->date_created->CurrentValue;
            $this->date_created->ViewValue = FormatDateTime($this->date_created->ViewValue, $this->date_created->formatPattern());

            // last_login
            $this->last_login->ViewValue = $this->last_login->CurrentValue;
            $this->last_login->ViewValue = FormatDateTime($this->last_login->ViewValue, $this->last_login->formatPattern());

            // is_active
            if (ConvertToBool($this->is_active->CurrentValue)) {
                $this->is_active->ViewValue = $this->is_active->tagCaption(1) != "" ? $this->is_active->tagCaption(1) : "Yes";
            } else {
                $this->is_active->ViewValue = $this->is_active->tagCaption(2) != "" ? $this->is_active->tagCaption(2) : "No";
            }

            // user_level_id
            if ($Security->canAdmin()) { // System admin
                $curVal = strval($this->user_level_id->CurrentValue);
                if ($curVal != "") {
                    $this->user_level_id->ViewValue = $this->user_level_id->lookupCacheOption($curVal);
                    if ($this->user_level_id->ViewValue === null) { // Lookup from database
                        $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $curVal);
                        $filterWrk = "";
                        foreach ($arwrk as $wrk) {
                            AddFilter($filterWrk, SearchFilter($this->user_level_id->Lookup->getTable()->Fields["user_level_id"]->searchExpression(), "=", trim($wrk), $this->user_level_id->Lookup->getTable()->Fields["user_level_id"]->searchDataType(), ""), "OR");
                        }
                        $sqlWrk = $this->user_level_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCache($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $this->user_level_id->ViewValue = new OptionValues();
                            foreach ($rswrk as $row) {
                                $arwrk = $this->user_level_id->Lookup->renderViewRow($row);
                                $this->user_level_id->ViewValue->add($this->user_level_id->displayValue($arwrk));
                            }
                        } else {
                            $this->user_level_id->ViewValue = $this->user_level_id->CurrentValue;
                        }
                    }
                } else {
                    $this->user_level_id->ViewValue = null;
                }
            } else {
                $this->user_level_id->ViewValue = $Language->phrase("PasswordMask");
            }

            // reports_to_user_id
            $curVal = strval($this->reports_to_user_id->CurrentValue);
            if ($curVal != "") {
                $this->reports_to_user_id->ViewValue = $this->reports_to_user_id->lookupCacheOption($curVal);
                if ($this->reports_to_user_id->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->reports_to_user_id->Lookup->getTable()->Fields["user_id"]->searchExpression(), "=", $curVal, $this->reports_to_user_id->Lookup->getTable()->Fields["user_id"]->searchDataType(), "");
                    $sqlWrk = $this->reports_to_user_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->reports_to_user_id->Lookup->renderViewRow($rswrk[0]);
                        $this->reports_to_user_id->ViewValue = $this->reports_to_user_id->displayValue($arwrk);
                    } else {
                        $this->reports_to_user_id->ViewValue = FormatNumber($this->reports_to_user_id->CurrentValue, $this->reports_to_user_id->formatPattern());
                    }
                }
            } else {
                $this->reports_to_user_id->ViewValue = null;
            }

            // photo
            $this->photo->UploadPath = $this->photo->getUploadPath(); // PHP
            if (!EmptyValue($this->photo->Upload->DbValue)) {
                $this->photo->ViewValue = $this->photo->Upload->DbValue;
            } else {
                $this->photo->ViewValue = "";
            }

            // user_id
            $this->user_id->HrefValue = "";
            $this->user_id->TooltipValue = "";

            // username
            $this->_username->HrefValue = "";
            $this->_username->TooltipValue = "";

            // email
            $this->_email->HrefValue = "";
            $this->_email->TooltipValue = "";

            // password_hash
            $this->password_hash->HrefValue = "";
            $this->password_hash->TooltipValue = "";

            // mobile_number
            $this->mobile_number->HrefValue = "";
            $this->mobile_number->TooltipValue = "";

            // first_name
            $this->first_name->HrefValue = "";
            $this->first_name->TooltipValue = "";

            // middle_name
            $this->middle_name->HrefValue = "";
            $this->middle_name->TooltipValue = "";

            // last_name
            $this->last_name->HrefValue = "";
            $this->last_name->TooltipValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // user_id

            // username
            $this->_username->setupEditAttributes();
            if (!$this->_username->Raw) {
                $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
            }
            $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

            // email
            $this->_email->setupEditAttributes();
            if (!$this->_email->Raw) {
                $this->_email->CurrentValue = HtmlDecode($this->_email->CurrentValue);
            }
            $this->_email->EditValue = HtmlEncode($this->_email->CurrentValue);
            $this->_email->PlaceHolder = RemoveHtml($this->_email->caption());

            // password_hash
            $this->password_hash->setupEditAttributes();
            $this->password_hash->PlaceHolder = RemoveHtml($this->password_hash->caption());

            // mobile_number
            $this->mobile_number->setupEditAttributes();
            if (!$this->mobile_number->Raw) {
                $this->mobile_number->CurrentValue = HtmlDecode($this->mobile_number->CurrentValue);
            }
            $this->mobile_number->EditValue = HtmlEncode($this->mobile_number->CurrentValue);
            $this->mobile_number->PlaceHolder = RemoveHtml($this->mobile_number->caption());

            // first_name
            $this->first_name->setupEditAttributes();
            if (!$this->first_name->Raw) {
                $this->first_name->CurrentValue = HtmlDecode($this->first_name->CurrentValue);
            }
            $this->first_name->EditValue = HtmlEncode($this->first_name->CurrentValue);
            $this->first_name->PlaceHolder = RemoveHtml($this->first_name->caption());

            // middle_name
            $this->middle_name->setupEditAttributes();
            if (!$this->middle_name->Raw) {
                $this->middle_name->CurrentValue = HtmlDecode($this->middle_name->CurrentValue);
            }
            $this->middle_name->EditValue = HtmlEncode($this->middle_name->CurrentValue);
            $this->middle_name->PlaceHolder = RemoveHtml($this->middle_name->caption());

            // last_name
            $this->last_name->setupEditAttributes();
            if (!$this->last_name->Raw) {
                $this->last_name->CurrentValue = HtmlDecode($this->last_name->CurrentValue);
            }
            $this->last_name->EditValue = HtmlEncode($this->last_name->CurrentValue);
            $this->last_name->PlaceHolder = RemoveHtml($this->last_name->caption());

            // Add refer script

            // user_id
            $this->user_id->HrefValue = "";

            // username
            $this->_username->HrefValue = "";

            // email
            $this->_email->HrefValue = "";

            // password_hash
            $this->password_hash->HrefValue = "";

            // mobile_number
            $this->mobile_number->HrefValue = "";

            // first_name
            $this->first_name->HrefValue = "";

            // middle_name
            $this->middle_name->HrefValue = "";

            // last_name
            $this->last_name->HrefValue = "";
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
            if ($this->user_id->Visible && $this->user_id->Required) {
                if (!$this->user_id->IsDetailKey && EmptyValue($this->user_id->FormValue)) {
                    $this->user_id->addErrorMessage(str_replace("%s", $this->user_id->caption(), $this->user_id->RequiredErrorMessage));
                }
            }
            if ($this->_username->Visible && $this->_username->Required) {
                if (!$this->_username->IsDetailKey && EmptyValue($this->_username->FormValue)) {
                    $this->_username->addErrorMessage($Language->phrase("EnterUserName"));
                }
            }
            if (!$this->_username->Raw && Config("REMOVE_XSS") && CheckUsername($this->_username->FormValue)) {
                $this->_username->addErrorMessage($Language->phrase("InvalidUsernameChars"));
            }
            if ($this->_email->Visible && $this->_email->Required) {
                if (!$this->_email->IsDetailKey && EmptyValue($this->_email->FormValue)) {
                    $this->_email->addErrorMessage(str_replace("%s", $this->_email->caption(), $this->_email->RequiredErrorMessage));
                }
            }
            if ($this->password_hash->Visible && $this->password_hash->Required) {
                if (!$this->password_hash->IsDetailKey && EmptyValue($this->password_hash->FormValue)) {
                    $this->password_hash->addErrorMessage($Language->phrase("EnterPassword"));
                }
            }
            if (!$this->password_hash->Raw && Config("REMOVE_XSS") && CheckPassword($this->password_hash->FormValue)) {
                $this->password_hash->addErrorMessage($Language->phrase("InvalidPasswordChars"));
            }
            if ($this->mobile_number->Visible && $this->mobile_number->Required) {
                if (!$this->mobile_number->IsDetailKey && EmptyValue($this->mobile_number->FormValue)) {
                    $this->mobile_number->addErrorMessage(str_replace("%s", $this->mobile_number->caption(), $this->mobile_number->RequiredErrorMessage));
                }
            }
            if ($this->first_name->Visible && $this->first_name->Required) {
                if (!$this->first_name->IsDetailKey && EmptyValue($this->first_name->FormValue)) {
                    $this->first_name->addErrorMessage(str_replace("%s", $this->first_name->caption(), $this->first_name->RequiredErrorMessage));
                }
            }
            if ($this->middle_name->Visible && $this->middle_name->Required) {
                if (!$this->middle_name->IsDetailKey && EmptyValue($this->middle_name->FormValue)) {
                    $this->middle_name->addErrorMessage(str_replace("%s", $this->middle_name->caption(), $this->middle_name->RequiredErrorMessage));
                }
            }
            if ($this->last_name->Visible && $this->last_name->Required) {
                if (!$this->last_name->IsDetailKey && EmptyValue($this->last_name->FormValue)) {
                    $this->last_name->addErrorMessage(str_replace("%s", $this->last_name->caption(), $this->last_name->RequiredErrorMessage));
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

        // Check if valid User ID
        if (
            !EmptyValue($Security->currentUserID()) &&
            !$Security->isAdmin() && // Non system admin
            !$Security->isValidUserID($this->user_id->CurrentValue)
        ) {
            $userIdMsg = str_replace("%c", CurrentUserID(), $Language->phrase("UnAuthorizedUserID"));
            $userIdMsg = str_replace("%u", strval($this->user_id->CurrentValue), $userIdMsg);
            $this->setFailureMessage($userIdMsg);
            return false;
        }

        // Check if valid Parent User ID
        if (
            !EmptyValue($Security->currentUserID()) &&
            !EmptyValue($this->reports_to_user_id->CurrentValue) && // Allow empty value
            !$Security->isAdmin() && // Non system admin
            !$Security->isValidUserID($this->reports_to_user_id->CurrentValue)
        ) {
            $parentUserIdMsg = str_replace("%c", CurrentUserID(), $Language->phrase("UnAuthorizedParentUserID"));
            $parentUserIdMsg = str_replace("%p", strval($this->reports_to_user_id->CurrentValue), $parentUserIdMsg);
            $this->setFailureMessage($parentUserIdMsg);
            return false;
        }
        if ($this->_username->CurrentValue != "") { // Check field with unique index
            $filter = "(\"username\" = '" . AdjustSql($this->_username->CurrentValue, $this->Dbid) . "')";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->_username->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->_username->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        if ($this->_email->CurrentValue != "") { // Check field with unique index
            $filter = "(\"email\" = '" . AdjustSql($this->_email->CurrentValue, $this->Dbid) . "')";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->_email->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->_email->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        $this->photo->OldUploadPath = $this->photo->getUploadPath(); // PHP
        $this->photo->UploadPath = $this->photo->OldUploadPath;

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

            // Call User Registered event
            $this->userRegistered($rsnew);
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

        // username
        $this->_username->setDbValueDef($rsnew, $this->_username->CurrentValue, false);

        // email
        $this->_email->setDbValueDef($rsnew, $this->_email->CurrentValue, false);

        // password_hash
        if (!IsMaskedPassword($this->password_hash->CurrentValue)) {
            $this->password_hash->setDbValueDef($rsnew, $this->password_hash->CurrentValue, false);
        }

        // mobile_number
        $this->mobile_number->setDbValueDef($rsnew, $this->mobile_number->CurrentValue, false);

        // first_name
        $this->first_name->setDbValueDef($rsnew, $this->first_name->CurrentValue, false);

        // middle_name
        $this->middle_name->setDbValueDef($rsnew, $this->middle_name->CurrentValue, false);

        // last_name
        $this->last_name->setDbValueDef($rsnew, $this->last_name->CurrentValue, false);

        // department_id
        if ($this->department_id->getSessionValue() != "") {
            $rsnew['department_id'] = $this->department_id->getSessionValue();
        }

        // user_level_id
        if ($this->user_level_id->getSessionValue() != "") {
            $rsnew['user_level_id'] = $this->user_level_id->getSessionValue();
        }

        // reports_to_user_id
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['username'])) { // username
            $this->_username->setFormValue($row['username']);
        }
        if (isset($row['email'])) { // email
            $this->_email->setFormValue($row['email']);
        }
        if (isset($row['password_hash'])) { // password_hash
            $this->password_hash->setFormValue($row['password_hash']);
        }
        if (isset($row['mobile_number'])) { // mobile_number
            $this->mobile_number->setFormValue($row['mobile_number']);
        }
        if (isset($row['first_name'])) { // first_name
            $this->first_name->setFormValue($row['first_name']);
        }
        if (isset($row['middle_name'])) { // middle_name
            $this->middle_name->setFormValue($row['middle_name']);
        }
        if (isset($row['last_name'])) { // last_name
            $this->last_name->setFormValue($row['last_name']);
        }
        if (isset($row['department_id'])) { // department_id
            $this->department_id->setFormValue($row['department_id']);
        }
        if (isset($row['user_level_id'])) { // user_level_id
            $this->user_level_id->setFormValue($row['user_level_id']);
        }
        if (isset($row['reports_to_user_id'])) { // reports_to_user_id
            $this->reports_to_user_id->setFormValue($row['reports_to_user_id']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
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
                case "x_department_id":
                    break;
                case "x_is_active":
                    break;
                case "x_user_level_id":
                    break;
                case "x_reports_to_user_id":
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
    // $type = ''|'success'|'failure'
    public function messageShowing(&$msg, $type)
    {
        // Example:
        //if ($type == "success") $msg = "your success message";
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

    // Email Sending event
    public function emailSending($email, $args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }

    // User Registered event
    public function userRegistered($rs)
    {
        //Log("User_Registered");
    }

    // User Activated event
    public function userActivated($rs)
    {
        //Log("User_Activated");
    }
}
