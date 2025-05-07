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
 * Table class for payment_transactions
 */
class PaymentTransactions extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $DbErrorMessage = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Ajax / Modal
    public $UseAjaxActions = false;
    public $ModalSearch = false;
    public $ModalView = false;
    public $ModalAdd = false;
    public $ModalEdit = false;
    public $ModalUpdate = false;
    public $InlineDelete = false;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;

    // Fields
    public $transaction_id;
    public $request_id;
    public $user_id;
    public $payment_method_id;
    public $transaction_reference;
    public $amount;
    public $currency;
    public $status;
    public $payment_date;
    public $gateway_reference;
    public $gateway_response;
    public $fee_amount;
    public $total_amount;
    public $payment_receipt_url;
    public $qr_code_path;
    public $created_at;
    public $updated_at;
    public $ip_address;
    public $user_agent;
    public $notes;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "payment_transactions";
        $this->TableName = 'payment_transactions';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "payment_transactions";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)

        // PDF
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)

        // PhpSpreadsheet
        $this->ExportExcelPageOrientation = null; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = null; // Page size (PhpSpreadsheet only)

        // PHPWord
        $this->ExportWordPageOrientation = ""; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = ""; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this);

        // transaction_id
        $this->transaction_id = new DbField(
            $this, // Table
            'x_transaction_id', // Variable name
            'transaction_id', // Name
            '"transaction_id"', // Expression
            'CAST("transaction_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"transaction_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->transaction_id->InputTextType = "text";
        $this->transaction_id->Raw = true;
        $this->transaction_id->IsAutoIncrement = true; // Autoincrement field
        $this->transaction_id->IsPrimaryKey = true; // Primary key field
        $this->transaction_id->Nullable = false; // NOT NULL field
        $this->transaction_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->transaction_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['transaction_id'] = &$this->transaction_id;

        // request_id
        $this->request_id = new DbField(
            $this, // Table
            'x_request_id', // Variable name
            'request_id', // Name
            '"request_id"', // Expression
            'CAST("request_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"request_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->request_id->InputTextType = "text";
        $this->request_id->Raw = true;
        $this->request_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->request_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['request_id'] = &$this->request_id;

        // user_id
        $this->user_id = new DbField(
            $this, // Table
            'x_user_id', // Variable name
            'user_id', // Name
            '"user_id"', // Expression
            'CAST("user_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"user_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->user_id->InputTextType = "text";
        $this->user_id->Raw = true;
        $this->user_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->user_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['user_id'] = &$this->user_id;

        // payment_method_id
        $this->payment_method_id = new DbField(
            $this, // Table
            'x_payment_method_id', // Variable name
            'payment_method_id', // Name
            '"payment_method_id"', // Expression
            'CAST("payment_method_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"payment_method_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->payment_method_id->InputTextType = "text";
        $this->payment_method_id->Raw = true;
        $this->payment_method_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->payment_method_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['payment_method_id'] = &$this->payment_method_id;

        // transaction_reference
        $this->transaction_reference = new DbField(
            $this, // Table
            'x_transaction_reference', // Variable name
            'transaction_reference', // Name
            '"transaction_reference"', // Expression
            '"transaction_reference"', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"transaction_reference"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->transaction_reference->InputTextType = "text";
        $this->transaction_reference->Nullable = false; // NOT NULL field
        $this->transaction_reference->Required = true; // Required field
        $this->transaction_reference->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['transaction_reference'] = &$this->transaction_reference;

        // amount
        $this->amount = new DbField(
            $this, // Table
            'x_amount', // Variable name
            'amount', // Name
            '"amount"', // Expression
            'CAST("amount" AS varchar(255))', // Basic search expression
            14, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"amount"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->amount->InputTextType = "text";
        $this->amount->Raw = true;
        $this->amount->Nullable = false; // NOT NULL field
        $this->amount->Required = true; // Required field
        $this->amount->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->amount->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['amount'] = &$this->amount;

        // currency
        $this->currency = new DbField(
            $this, // Table
            'x_currency', // Variable name
            'currency', // Name
            '"currency"', // Expression
            '"currency"', // Basic search expression
            200, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"currency"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->currency->addMethod("getDefault", fn() => "php");
        $this->currency->InputTextType = "text";
        $this->currency->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['currency'] = &$this->currency;

        // status
        $this->status = new DbField(
            $this, // Table
            'x_status', // Variable name
            'status', // Name
            '"status"', // Expression
            '"status"', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"status"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->status->addMethod("getDefault", fn() => "pending");
        $this->status->InputTextType = "text";
        $this->status->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['status'] = &$this->status;

        // payment_date
        $this->payment_date = new DbField(
            $this, // Table
            'x_payment_date', // Variable name
            'payment_date', // Name
            '"payment_date"', // Expression
            CastDateFieldForLike("\"payment_date\"", 0, "DB"), // Basic search expression
            135, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"payment_date"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->payment_date->InputTextType = "text";
        $this->payment_date->Raw = true;
        $this->payment_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->payment_date->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['payment_date'] = &$this->payment_date;

        // gateway_reference
        $this->gateway_reference = new DbField(
            $this, // Table
            'x_gateway_reference', // Variable name
            'gateway_reference', // Name
            '"gateway_reference"', // Expression
            '"gateway_reference"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"gateway_reference"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->gateway_reference->InputTextType = "text";
        $this->gateway_reference->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['gateway_reference'] = &$this->gateway_reference;

        // gateway_response
        $this->gateway_response = new DbField(
            $this, // Table
            'x_gateway_response', // Variable name
            'gateway_response', // Name
            '"gateway_response"', // Expression
            '"gateway_response"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"gateway_response"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->gateway_response->InputTextType = "text";
        $this->gateway_response->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['gateway_response'] = &$this->gateway_response;

        // fee_amount
        $this->fee_amount = new DbField(
            $this, // Table
            'x_fee_amount', // Variable name
            'fee_amount', // Name
            '"fee_amount"', // Expression
            'CAST("fee_amount" AS varchar(255))', // Basic search expression
            14, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"fee_amount"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fee_amount->addMethod("getDefault", fn() => 0);
        $this->fee_amount->InputTextType = "text";
        $this->fee_amount->Raw = true;
        $this->fee_amount->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->fee_amount->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fee_amount'] = &$this->fee_amount;

        // total_amount
        $this->total_amount = new DbField(
            $this, // Table
            'x_total_amount', // Variable name
            'total_amount', // Name
            '"total_amount"', // Expression
            'CAST("total_amount" AS varchar(255))', // Basic search expression
            14, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"total_amount"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->total_amount->addMethod("getDefault", fn() => (amount + fee_amount));
        $this->total_amount->InputTextType = "text";
        $this->total_amount->Raw = true;
        $this->total_amount->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total_amount->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['total_amount'] = &$this->total_amount;

        // payment_receipt_url
        $this->payment_receipt_url = new DbField(
            $this, // Table
            'x_payment_receipt_url', // Variable name
            'payment_receipt_url', // Name
            '"payment_receipt_url"', // Expression
            '"payment_receipt_url"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"payment_receipt_url"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->payment_receipt_url->InputTextType = "text";
        $this->payment_receipt_url->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['payment_receipt_url'] = &$this->payment_receipt_url;

        // qr_code_path
        $this->qr_code_path = new DbField(
            $this, // Table
            'x_qr_code_path', // Variable name
            'qr_code_path', // Name
            '"qr_code_path"', // Expression
            '"qr_code_path"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"qr_code_path"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->qr_code_path->InputTextType = "text";
        $this->qr_code_path->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['qr_code_path'] = &$this->qr_code_path;

        // created_at
        $this->created_at = new DbField(
            $this, // Table
            'x_created_at', // Variable name
            'created_at', // Name
            '"created_at"', // Expression
            CastDateFieldForLike("\"created_at\"", 0, "DB"), // Basic search expression
            135, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"created_at"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->created_at->InputTextType = "text";
        $this->created_at->Raw = true;
        $this->created_at->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->created_at->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['created_at'] = &$this->created_at;

        // updated_at
        $this->updated_at = new DbField(
            $this, // Table
            'x_updated_at', // Variable name
            'updated_at', // Name
            '"updated_at"', // Expression
            CastDateFieldForLike("\"updated_at\"", 0, "DB"), // Basic search expression
            135, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"updated_at"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->updated_at->InputTextType = "text";
        $this->updated_at->Raw = true;
        $this->updated_at->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->updated_at->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['updated_at'] = &$this->updated_at;

        // ip_address
        $this->ip_address = new DbField(
            $this, // Table
            'x_ip_address', // Variable name
            'ip_address', // Name
            '"ip_address"', // Expression
            '"ip_address"', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"ip_address"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ip_address->InputTextType = "text";
        $this->ip_address->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ip_address'] = &$this->ip_address;

        // user_agent
        $this->user_agent = new DbField(
            $this, // Table
            'x_user_agent', // Variable name
            'user_agent', // Name
            '"user_agent"', // Expression
            '"user_agent"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"user_agent"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->user_agent->InputTextType = "text";
        $this->user_agent->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['user_agent'] = &$this->user_agent;

        // notes
        $this->notes = new DbField(
            $this, // Table
            'x_notes', // Variable name
            'notes', // Name
            '"notes"', // Expression
            '"notes"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"notes"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->notes->InputTextType = "text";
        $this->notes->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['notes'] = &$this->notes;

        // Add Doctrine Cache
        $this->Cache = new \Symfony\Component\Cache\Adapter\ArrayAdapter();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);

        // Call Table Load event
        $this->tableLoad();
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Get FROM clause
    public function getSqlFrom()
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "payment_transactions";
    }

    // Get FROM clause (for backward compatibility)
    public function sqlFrom()
    {
        return $this->getSqlFrom();
    }

    // Set FROM clause
    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    // Get SELECT clause
    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select($this->sqlSelectFields());
    }

    // Get list of fields
    private function sqlSelectFields()
    {
        $useFieldNames = false;
        $fieldNames = [];
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($this->Fields as $field) {
            $expr = $field->Expression;
            $customExpr = $field->CustomDataType?->convertToPHPValueSQL($expr, $platform) ?? $expr;
            if ($customExpr != $expr) {
                $fieldNames[] = $customExpr . " AS " . QuotedName($field->Name, $this->Dbid);
                $useFieldNames = true;
            } else {
                $fieldNames[] = $expr;
            }
        }
        return $useFieldNames ? implode(", ", $fieldNames) : "*";
    }

    // Get SELECT clause (for backward compatibility)
    public function sqlSelect()
    {
        return $this->getSqlSelect();
    }

    // Set SELECT clause
    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    // Get WHERE clause
    public function getSqlWhere()
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    // Get WHERE clause (for backward compatibility)
    public function sqlWhere()
    {
        return $this->getSqlWhere();
    }

    // Set WHERE clause
    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    // Get GROUP BY clause
    public function getSqlGroupBy()
    {
        return $this->SqlGroupBy != "" ? $this->SqlGroupBy : "";
    }

    // Get GROUP BY clause (for backward compatibility)
    public function sqlGroupBy()
    {
        return $this->getSqlGroupBy();
    }

    // set GROUP BY clause
    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    // Get HAVING clause
    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    // Get HAVING clause (for backward compatibility)
    public function sqlHaving()
    {
        return $this->getSqlHaving();
    }

    // Set HAVING clause
    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    // Get ORDER BY clause
    public function getSqlOrderBy()
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    // Get ORDER BY clause (for backward compatibility)
    public function sqlOrderBy()
    {
        return $this->getSqlOrderBy();
    }

    // set ORDER BY clause
    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return ($allow & Allow::ADD->value) == Allow::ADD->value;
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return ($allow & Allow::EDIT->value) == Allow::EDIT->value;
            case "delete":
                return ($allow & Allow::DELETE->value) == Allow::DELETE->value;
            case "view":
                return ($allow & Allow::VIEW->value) == Allow::VIEW->value;
            case "search":
                return ($allow & Allow::SEARCH->value) == Allow::SEARCH->value;
            case "lookup":
                return ($allow & Allow::LOOKUP->value) == Allow::LOOKUP->value;
            default:
                return ($allow & Allow::LIST->value) == Allow::LIST->value;
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $sqlwrk = $sql instanceof QueryBuilder // Query builder
            ? (clone $sql)->resetQueryPart("orderBy")->getSQL()
            : $sql;
        $pattern = '/^SELECT\s([\s\S]+?)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            in_array($this->TableType, ["TABLE", "VIEW", "LINKTABLE"]) &&
            preg_match($pattern, $sqlwrk) &&
            !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*SELECT\s+DISTINCT\s+/i', $sqlwrk) &&
            !preg_match('/\s+ORDER\s+BY\s+/i', $sqlwrk)
        ) {
            $sqlcnt = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlcnt = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlcnt);
        if ($cnt !== false) {
            return (int)$cnt;
        }
        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        $result = $conn->executeQuery($sqlwrk);
        $cnt = $result->rowCount();
        if ($cnt == 0) { // Unable to get record count, count directly
            while ($result->fetch()) {
                $cnt++;
            }
        }
        return $cnt;
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->getSqlAsQueryBuilder($where, $orderBy)->getSQL();
    }

    // Get QueryBuilder
    public function getSqlAsQueryBuilder($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        );
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $isCustomView = $this->TableType == "CUSTOMVIEW";
        $select = $isCustomView ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $isCustomView ? $this->getSqlGroupBy() : "";
        $having = $isCustomView ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $isCustomView = $this->TableType == "CUSTOMVIEW";
        $select = $isCustomView ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $isCustomView ? $this->getSqlGroupBy() : "";
        $having = $isCustomView ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $field = $this->Fields[$name];
            $parm = $queryBuilder->createPositionalParameter($value, $field->getParameterType());
            $parm = $field->CustomDataType?->convertToDatabaseValueSQL($parm, $platform) ?? $parm; // Convert database SQL
            $queryBuilder->setValue($field->Expression, $parm);
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        try {
            $queryBuilder = $this->insertSql($rs);
            $result = $conn->executeQuery(
                $queryBuilder->getSQL() . " RETURNING transaction_id",
                $queryBuilder->getParameters(),
                $queryBuilder->getParameterTypes()
            )->fetchOne();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->transaction_id->setDbValue($result);
            $rs['transaction_id'] = $this->transaction_id->DbValue;
        }
        return $result !== false ? 1 : false;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $field = $this->Fields[$name];
            $parm = $queryBuilder->createPositionalParameter($value, $field->getParameterType());
            $parm = $field->CustomDataType?->convertToDatabaseValueSQL($parm, $platform) ?? $parm; // Convert database SQL
            $queryBuilder->set($field->Expression, $parm);
        }
        $filter = $curfilter ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        try {
            $success = $this->updateSql($rs, $where, $curfilter)->executeStatement();
            $success = $success > 0 ? $success : true;
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }

        // Return auto increment field
        if ($success) {
            if (!isset($rs['transaction_id']) && !EmptyValue($this->transaction_id->CurrentValue)) {
                $rs['transaction_id'] = $this->transaction_id->CurrentValue;
            }
        }
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('transaction_id', $rs)) {
                AddFilter($where, QuotedName('transaction_id', $this->Dbid) . '=' . QuotedValue($rs['transaction_id'], $this->transaction_id->DataType, $this->Dbid));
            }
        }
        $filter = $curfilter ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            try {
                $success = $this->deleteSql($rs, $where, $curfilter)->executeStatement();
                $this->DbErrorMessage = "";
            } catch (\Exception $e) {
                $success = false;
                $this->DbErrorMessage = $e->getMessage();
            }
        }
        return $success;
    }

    // Load DbValue from result set or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->transaction_id->DbValue = $row['transaction_id'];
        $this->request_id->DbValue = $row['request_id'];
        $this->user_id->DbValue = $row['user_id'];
        $this->payment_method_id->DbValue = $row['payment_method_id'];
        $this->transaction_reference->DbValue = $row['transaction_reference'];
        $this->amount->DbValue = $row['amount'];
        $this->currency->DbValue = $row['currency'];
        $this->status->DbValue = $row['status'];
        $this->payment_date->DbValue = $row['payment_date'];
        $this->gateway_reference->DbValue = $row['gateway_reference'];
        $this->gateway_response->DbValue = $row['gateway_response'];
        $this->fee_amount->DbValue = $row['fee_amount'];
        $this->total_amount->DbValue = $row['total_amount'];
        $this->payment_receipt_url->DbValue = $row['payment_receipt_url'];
        $this->qr_code_path->DbValue = $row['qr_code_path'];
        $this->created_at->DbValue = $row['created_at'];
        $this->updated_at->DbValue = $row['updated_at'];
        $this->ip_address->DbValue = $row['ip_address'];
        $this->user_agent->DbValue = $row['user_agent'];
        $this->notes->DbValue = $row['notes'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "\"transaction_id\" = @transaction_id@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->transaction_id->CurrentValue : $this->transaction_id->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        return implode($keySeparator, $keys);
    }

    // Set Key
    public function setKey($key, $current = false, $keySeparator = null)
    {
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        $this->OldKey = strval($key);
        $keys = explode($keySeparator, $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->transaction_id->CurrentValue = $keys[0];
            } else {
                $this->transaction_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('transaction_id', $row) ? $row['transaction_id'] : null;
        } else {
            $val = !EmptyValue($this->transaction_id->OldValue) && !$current ? $this->transaction_id->OldValue : $this->transaction_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@transaction_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("PaymentTransactionsList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        return match ($pageName) {
            "PaymentTransactionsView" => $Language->phrase("View"),
            "PaymentTransactionsEdit" => $Language->phrase("Edit"),
            "PaymentTransactionsAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "PaymentTransactionsList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "PaymentTransactionsView",
            Config("API_ADD_ACTION") => "PaymentTransactionsAdd",
            Config("API_EDIT_ACTION") => "PaymentTransactionsEdit",
            Config("API_DELETE_ACTION") => "PaymentTransactionsDelete",
            Config("API_LIST_ACTION") => "PaymentTransactionsList",
            default => ""
        };
    }

    // Current URL
    public function getCurrentUrl($parm = "")
    {
        $url = CurrentPageUrl(false);
        if ($parm != "") {
            $url = $this->keyUrl($url, $parm);
        } else {
            $url = $this->keyUrl($url, Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // List URL
    public function getListUrl()
    {
        return "PaymentTransactionsList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PaymentTransactionsView", $parm);
        } else {
            $url = $this->keyUrl("PaymentTransactionsView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "PaymentTransactionsAdd?" . $parm;
        } else {
            $url = "PaymentTransactionsAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("PaymentTransactionsEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("PaymentTransactionsList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("PaymentTransactionsAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("PaymentTransactionsList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("PaymentTransactionsDelete", $parm);
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"transaction_id\":" . VarToJson($this->transaction_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->transaction_id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->transaction_id->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language;
        $sortUrl = "";
        $attrs = "";
        if ($this->PageID != "grid" && $fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-ew-action="sort" data-ajax="' . ($this->UseAjaxActions ? "true" : "false") . '" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
            if ($this->ContextClass) { // Add context
                $attrs .= ' data-context="' . HtmlEncode($this->ContextClass) . '"';
            }
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($this->PageID != "grid" && !$this->isExport() && $fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") .
                (is_array($fld->EditValue) ? str_replace("%c", count($fld->EditValue), $Language->phrase("FilterCount")) : '') .
                '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = "order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort();
            if ($DashboardReport) {
                $urlParm .= "&amp;" . Config("PAGE_DASHBOARD") . "=" . $DashboardReport;
            }
            return $this->addMasterUrl($this->CurrentPageName . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            $isApi = IsApi();
            $keyValues = $isApi
                ? (Route(0) == "export"
                    ? array_map(fn ($i) => Route($i + 3), range(0, 0))  // Export API
                    : array_map(fn ($i) => Route($i + 2), range(0, 0))) // Other API
                : []; // Non-API
            if (($keyValue = Param("transaction_id") ?? Route("transaction_id")) !== null) {
                $arKeys[] = $keyValue;
            } elseif ($isApi && (($keyValue = Key(0) ?? $keyValues[0] ?? null) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from records
    public function getFilterFromRecords($rows)
    {
        return implode(" OR ", array_map(fn($row) => "(" . $this->getRecordFilter($row) . ")", $rows));
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->transaction_id->CurrentValue = $key;
            } else {
                $this->transaction_id->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load result set based on filter/sort
    public function loadRs($filter, $sort = "")
    {
        $sql = $this->getSql($filter, $sort); // Set up filter (WHERE Clause) / sort (ORDER BY Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
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

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "PaymentTransactionsList";
        $listClass = PROJECT_NAMESPACE . $listPage;
        $page = new $listClass();
        $page->loadRecordsetFromFilter($filter);
        $view = Container("app.view");
        $template = $listPage . ".php"; // View
        $GLOBALS["Title"] ??= $page->Title; // Title
        try {
            $Response = $view->render($Response, $template, $GLOBALS);
        } finally {
            $page->terminate(); // Terminate page and clean up
        }
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // transaction_id

        // request_id

        // user_id

        // payment_method_id

        // transaction_reference

        // amount

        // currency

        // status

        // payment_date

        // gateway_reference

        // gateway_response

        // fee_amount

        // total_amount

        // payment_receipt_url

        // qr_code_path

        // created_at

        // updated_at

        // ip_address

        // user_agent

        // notes

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

        // transaction_id
        $this->transaction_id->HrefValue = "";
        $this->transaction_id->TooltipValue = "";

        // request_id
        $this->request_id->HrefValue = "";
        $this->request_id->TooltipValue = "";

        // user_id
        $this->user_id->HrefValue = "";
        $this->user_id->TooltipValue = "";

        // payment_method_id
        $this->payment_method_id->HrefValue = "";
        $this->payment_method_id->TooltipValue = "";

        // transaction_reference
        $this->transaction_reference->HrefValue = "";
        $this->transaction_reference->TooltipValue = "";

        // amount
        $this->amount->HrefValue = "";
        $this->amount->TooltipValue = "";

        // currency
        $this->currency->HrefValue = "";
        $this->currency->TooltipValue = "";

        // status
        $this->status->HrefValue = "";
        $this->status->TooltipValue = "";

        // payment_date
        $this->payment_date->HrefValue = "";
        $this->payment_date->TooltipValue = "";

        // gateway_reference
        $this->gateway_reference->HrefValue = "";
        $this->gateway_reference->TooltipValue = "";

        // gateway_response
        $this->gateway_response->HrefValue = "";
        $this->gateway_response->TooltipValue = "";

        // fee_amount
        $this->fee_amount->HrefValue = "";
        $this->fee_amount->TooltipValue = "";

        // total_amount
        $this->total_amount->HrefValue = "";
        $this->total_amount->TooltipValue = "";

        // payment_receipt_url
        $this->payment_receipt_url->HrefValue = "";
        $this->payment_receipt_url->TooltipValue = "";

        // qr_code_path
        $this->qr_code_path->HrefValue = "";
        $this->qr_code_path->TooltipValue = "";

        // created_at
        $this->created_at->HrefValue = "";
        $this->created_at->TooltipValue = "";

        // updated_at
        $this->updated_at->HrefValue = "";
        $this->updated_at->TooltipValue = "";

        // ip_address
        $this->ip_address->HrefValue = "";
        $this->ip_address->TooltipValue = "";

        // user_agent
        $this->user_agent->HrefValue = "";
        $this->user_agent->TooltipValue = "";

        // notes
        $this->notes->HrefValue = "";
        $this->notes->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // transaction_id
        $this->transaction_id->setupEditAttributes();
        $this->transaction_id->EditValue = $this->transaction_id->CurrentValue;

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
        $this->transaction_reference->EditValue = $this->transaction_reference->CurrentValue;
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
        $this->currency->EditValue = $this->currency->CurrentValue;
        $this->currency->PlaceHolder = RemoveHtml($this->currency->caption());

        // status
        $this->status->setupEditAttributes();
        if (!$this->status->Raw) {
            $this->status->CurrentValue = HtmlDecode($this->status->CurrentValue);
        }
        $this->status->EditValue = $this->status->CurrentValue;
        $this->status->PlaceHolder = RemoveHtml($this->status->caption());

        // payment_date
        $this->payment_date->setupEditAttributes();
        $this->payment_date->EditValue = FormatDateTime($this->payment_date->CurrentValue, $this->payment_date->formatPattern());
        $this->payment_date->PlaceHolder = RemoveHtml($this->payment_date->caption());

        // gateway_reference
        $this->gateway_reference->setupEditAttributes();
        if (!$this->gateway_reference->Raw) {
            $this->gateway_reference->CurrentValue = HtmlDecode($this->gateway_reference->CurrentValue);
        }
        $this->gateway_reference->EditValue = $this->gateway_reference->CurrentValue;
        $this->gateway_reference->PlaceHolder = RemoveHtml($this->gateway_reference->caption());

        // gateway_response
        $this->gateway_response->setupEditAttributes();
        $this->gateway_response->EditValue = $this->gateway_response->CurrentValue;
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
        $this->payment_receipt_url->EditValue = $this->payment_receipt_url->CurrentValue;
        $this->payment_receipt_url->PlaceHolder = RemoveHtml($this->payment_receipt_url->caption());

        // qr_code_path
        $this->qr_code_path->setupEditAttributes();
        if (!$this->qr_code_path->Raw) {
            $this->qr_code_path->CurrentValue = HtmlDecode($this->qr_code_path->CurrentValue);
        }
        $this->qr_code_path->EditValue = $this->qr_code_path->CurrentValue;
        $this->qr_code_path->PlaceHolder = RemoveHtml($this->qr_code_path->caption());

        // created_at
        $this->created_at->setupEditAttributes();
        $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

        // updated_at
        $this->updated_at->setupEditAttributes();
        $this->updated_at->EditValue = FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

        // ip_address
        $this->ip_address->setupEditAttributes();
        if (!$this->ip_address->Raw) {
            $this->ip_address->CurrentValue = HtmlDecode($this->ip_address->CurrentValue);
        }
        $this->ip_address->EditValue = $this->ip_address->CurrentValue;
        $this->ip_address->PlaceHolder = RemoveHtml($this->ip_address->caption());

        // user_agent
        $this->user_agent->setupEditAttributes();
        $this->user_agent->EditValue = $this->user_agent->CurrentValue;
        $this->user_agent->PlaceHolder = RemoveHtml($this->user_agent->caption());

        // notes
        $this->notes->setupEditAttributes();
        $this->notes->EditValue = $this->notes->CurrentValue;
        $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $result, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$result || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->transaction_id);
                    $doc->exportCaption($this->request_id);
                    $doc->exportCaption($this->user_id);
                    $doc->exportCaption($this->payment_method_id);
                    $doc->exportCaption($this->transaction_reference);
                    $doc->exportCaption($this->amount);
                    $doc->exportCaption($this->currency);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->payment_date);
                    $doc->exportCaption($this->gateway_reference);
                    $doc->exportCaption($this->gateway_response);
                    $doc->exportCaption($this->fee_amount);
                    $doc->exportCaption($this->total_amount);
                    $doc->exportCaption($this->payment_receipt_url);
                    $doc->exportCaption($this->qr_code_path);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->updated_at);
                    $doc->exportCaption($this->ip_address);
                    $doc->exportCaption($this->user_agent);
                    $doc->exportCaption($this->notes);
                } else {
                    $doc->exportCaption($this->transaction_id);
                    $doc->exportCaption($this->request_id);
                    $doc->exportCaption($this->user_id);
                    $doc->exportCaption($this->payment_method_id);
                    $doc->exportCaption($this->transaction_reference);
                    $doc->exportCaption($this->amount);
                    $doc->exportCaption($this->currency);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->payment_date);
                    $doc->exportCaption($this->gateway_reference);
                    $doc->exportCaption($this->fee_amount);
                    $doc->exportCaption($this->total_amount);
                    $doc->exportCaption($this->payment_receipt_url);
                    $doc->exportCaption($this->qr_code_path);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->updated_at);
                    $doc->exportCaption($this->ip_address);
                }
                $doc->endExportRow();
            }
        }
        $recCnt = $startRec - 1;
        $stopRec = $stopRec > 0 ? $stopRec : PHP_INT_MAX;
        while (($row = $result->fetch()) && $recCnt < $stopRec) {
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = RowType::VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->transaction_id);
                        $doc->exportField($this->request_id);
                        $doc->exportField($this->user_id);
                        $doc->exportField($this->payment_method_id);
                        $doc->exportField($this->transaction_reference);
                        $doc->exportField($this->amount);
                        $doc->exportField($this->currency);
                        $doc->exportField($this->status);
                        $doc->exportField($this->payment_date);
                        $doc->exportField($this->gateway_reference);
                        $doc->exportField($this->gateway_response);
                        $doc->exportField($this->fee_amount);
                        $doc->exportField($this->total_amount);
                        $doc->exportField($this->payment_receipt_url);
                        $doc->exportField($this->qr_code_path);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->updated_at);
                        $doc->exportField($this->ip_address);
                        $doc->exportField($this->user_agent);
                        $doc->exportField($this->notes);
                    } else {
                        $doc->exportField($this->transaction_id);
                        $doc->exportField($this->request_id);
                        $doc->exportField($this->user_id);
                        $doc->exportField($this->payment_method_id);
                        $doc->exportField($this->transaction_reference);
                        $doc->exportField($this->amount);
                        $doc->exportField($this->currency);
                        $doc->exportField($this->status);
                        $doc->exportField($this->payment_date);
                        $doc->exportField($this->gateway_reference);
                        $doc->exportField($this->fee_amount);
                        $doc->exportField($this->total_amount);
                        $doc->exportField($this->payment_receipt_url);
                        $doc->exportField($this->qr_code_path);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->updated_at);
                        $doc->exportField($this->ip_address);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($doc, $row);
            }
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;

        // No binary fields
        return false;
    }

    // Table level events

    // Table Load event
    public function tableLoad()
    {
        // Enter your code here
    }

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected($rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, $rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, $rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted($rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, $args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
