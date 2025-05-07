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
 * Table class for documents
 */
class Documents extends DbTable
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
    public $document_id;
    public $user_id;
    public $template_id;
    public $document_title;
    public $document_reference;
    public $status;
    public $created_at;
    public $updated_at;
    public $submitted_at;
    public $company_name;
    public $customs_entry_number;
    public $date_of_entry;
    public $document_html;
    public $document_data;
    public $is_deleted;
    public $deletion_date;
    public $deleted_by;
    public $parent_document_id;
    public $version;
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
        $this->TableVar = "documents";
        $this->TableName = 'documents';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "documents";
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

        // document_id
        $this->document_id = new DbField(
            $this, // Table
            'x_document_id', // Variable name
            'document_id', // Name
            '"document_id"', // Expression
            'CAST("document_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"document_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->document_id->InputTextType = "text";
        $this->document_id->Raw = true;
        $this->document_id->IsAutoIncrement = true; // Autoincrement field
        $this->document_id->IsPrimaryKey = true; // Primary key field
        $this->document_id->Nullable = false; // NOT NULL field
        $this->document_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->document_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['document_id'] = &$this->document_id;

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

        // template_id
        $this->template_id = new DbField(
            $this, // Table
            'x_template_id', // Variable name
            'template_id', // Name
            '"template_id"', // Expression
            'CAST("template_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"template_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->template_id->InputTextType = "text";
        $this->template_id->Raw = true;
        $this->template_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->template_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['template_id'] = &$this->template_id;

        // document_title
        $this->document_title = new DbField(
            $this, // Table
            'x_document_title', // Variable name
            'document_title', // Name
            '"document_title"', // Expression
            '"document_title"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"document_title"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->document_title->InputTextType = "text";
        $this->document_title->Nullable = false; // NOT NULL field
        $this->document_title->Required = true; // Required field
        $this->document_title->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['document_title'] = &$this->document_title;

        // document_reference
        $this->document_reference = new DbField(
            $this, // Table
            'x_document_reference', // Variable name
            'document_reference', // Name
            '"document_reference"', // Expression
            '"document_reference"', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"document_reference"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->document_reference->InputTextType = "text";
        $this->document_reference->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['document_reference'] = &$this->document_reference;

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
        $this->status->addMethod("getDefault", fn() => "draft");
        $this->status->InputTextType = "text";
        $this->status->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['status'] = &$this->status;

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

        // submitted_at
        $this->submitted_at = new DbField(
            $this, // Table
            'x_submitted_at', // Variable name
            'submitted_at', // Name
            '"submitted_at"', // Expression
            CastDateFieldForLike("\"submitted_at\"", 0, "DB"), // Basic search expression
            135, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"submitted_at"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->submitted_at->InputTextType = "text";
        $this->submitted_at->Raw = true;
        $this->submitted_at->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->submitted_at->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['submitted_at'] = &$this->submitted_at;

        // company_name
        $this->company_name = new DbField(
            $this, // Table
            'x_company_name', // Variable name
            'company_name', // Name
            '"company_name"', // Expression
            '"company_name"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"company_name"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->company_name->InputTextType = "text";
        $this->company_name->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['company_name'] = &$this->company_name;

        // customs_entry_number
        $this->customs_entry_number = new DbField(
            $this, // Table
            'x_customs_entry_number', // Variable name
            'customs_entry_number', // Name
            '"customs_entry_number"', // Expression
            '"customs_entry_number"', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"customs_entry_number"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->customs_entry_number->InputTextType = "text";
        $this->customs_entry_number->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['customs_entry_number'] = &$this->customs_entry_number;

        // date_of_entry
        $this->date_of_entry = new DbField(
            $this, // Table
            'x_date_of_entry', // Variable name
            'date_of_entry', // Name
            '"date_of_entry"', // Expression
            CastDateFieldForLike("\"date_of_entry\"", 0, "DB"), // Basic search expression
            133, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"date_of_entry"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->date_of_entry->InputTextType = "text";
        $this->date_of_entry->Raw = true;
        $this->date_of_entry->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->date_of_entry->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['date_of_entry'] = &$this->date_of_entry;

        // document_html
        $this->document_html = new DbField(
            $this, // Table
            'x_document_html', // Variable name
            'document_html', // Name
            '"document_html"', // Expression
            '"document_html"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"document_html"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->document_html->InputTextType = "text";
        $this->document_html->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['document_html'] = &$this->document_html;

        // document_data
        $this->document_data = new DbField(
            $this, // Table
            'x_document_data', // Variable name
            'document_data', // Name
            '"document_data"', // Expression
            'CAST("document_data" AS varchar(255))', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"document_data"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->document_data->InputTextType = "text";
        $this->document_data->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['document_data'] = &$this->document_data;

        // is_deleted
        $this->is_deleted = new DbField(
            $this, // Table
            'x_is_deleted', // Variable name
            'is_deleted', // Name
            '"is_deleted"', // Expression
            'CAST("is_deleted" AS varchar(255))', // Basic search expression
            11, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"is_deleted"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->is_deleted->InputTextType = "text";
        $this->is_deleted->Raw = true;
        $this->is_deleted->setDataType(DataType::BOOLEAN);
        $this->is_deleted->Lookup = new Lookup($this->is_deleted, 'documents', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->is_deleted->OptionCount = 2;
        $this->is_deleted->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['is_deleted'] = &$this->is_deleted;

        // deletion_date
        $this->deletion_date = new DbField(
            $this, // Table
            'x_deletion_date', // Variable name
            'deletion_date', // Name
            '"deletion_date"', // Expression
            CastDateFieldForLike("\"deletion_date\"", 0, "DB"), // Basic search expression
            135, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"deletion_date"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->deletion_date->InputTextType = "text";
        $this->deletion_date->Raw = true;
        $this->deletion_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->deletion_date->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['deletion_date'] = &$this->deletion_date;

        // deleted_by
        $this->deleted_by = new DbField(
            $this, // Table
            'x_deleted_by', // Variable name
            'deleted_by', // Name
            '"deleted_by"', // Expression
            'CAST("deleted_by" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"deleted_by"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->deleted_by->InputTextType = "text";
        $this->deleted_by->Raw = true;
        $this->deleted_by->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->deleted_by->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['deleted_by'] = &$this->deleted_by;

        // parent_document_id
        $this->parent_document_id = new DbField(
            $this, // Table
            'x_parent_document_id', // Variable name
            'parent_document_id', // Name
            '"parent_document_id"', // Expression
            'CAST("parent_document_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"parent_document_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->parent_document_id->InputTextType = "text";
        $this->parent_document_id->Raw = true;
        $this->parent_document_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->parent_document_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['parent_document_id'] = &$this->parent_document_id;

        // version
        $this->version = new DbField(
            $this, // Table
            'x_version', // Variable name
            'version', // Name
            '"version"', // Expression
            'CAST("version" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"version"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->version->addMethod("getDefault", fn() => 1);
        $this->version->InputTextType = "text";
        $this->version->Raw = true;
        $this->version->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->version->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['version'] = &$this->version;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "documents";
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
                $queryBuilder->getSQL() . " RETURNING document_id",
                $queryBuilder->getParameters(),
                $queryBuilder->getParameterTypes()
            )->fetchOne();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->document_id->setDbValue($result);
            $rs['document_id'] = $this->document_id->DbValue;
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
            if (!isset($rs['document_id']) && !EmptyValue($this->document_id->CurrentValue)) {
                $rs['document_id'] = $this->document_id->CurrentValue;
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
            if (array_key_exists('document_id', $rs)) {
                AddFilter($where, QuotedName('document_id', $this->Dbid) . '=' . QuotedValue($rs['document_id'], $this->document_id->DataType, $this->Dbid));
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
        $this->document_id->DbValue = $row['document_id'];
        $this->user_id->DbValue = $row['user_id'];
        $this->template_id->DbValue = $row['template_id'];
        $this->document_title->DbValue = $row['document_title'];
        $this->document_reference->DbValue = $row['document_reference'];
        $this->status->DbValue = $row['status'];
        $this->created_at->DbValue = $row['created_at'];
        $this->updated_at->DbValue = $row['updated_at'];
        $this->submitted_at->DbValue = $row['submitted_at'];
        $this->company_name->DbValue = $row['company_name'];
        $this->customs_entry_number->DbValue = $row['customs_entry_number'];
        $this->date_of_entry->DbValue = $row['date_of_entry'];
        $this->document_html->DbValue = $row['document_html'];
        $this->document_data->DbValue = $row['document_data'];
        $this->is_deleted->DbValue = (ConvertToBool($row['is_deleted']) ? "1" : "0");
        $this->deletion_date->DbValue = $row['deletion_date'];
        $this->deleted_by->DbValue = $row['deleted_by'];
        $this->parent_document_id->DbValue = $row['parent_document_id'];
        $this->version->DbValue = $row['version'];
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
        return "\"document_id\" = @document_id@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->document_id->CurrentValue : $this->document_id->OldValue;
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
                $this->document_id->CurrentValue = $keys[0];
            } else {
                $this->document_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('document_id', $row) ? $row['document_id'] : null;
        } else {
            $val = !EmptyValue($this->document_id->OldValue) && !$current ? $this->document_id->OldValue : $this->document_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@document_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("DocumentsList");
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
            "DocumentsView" => $Language->phrase("View"),
            "DocumentsEdit" => $Language->phrase("Edit"),
            "DocumentsAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "DocumentsList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "DocumentsView",
            Config("API_ADD_ACTION") => "DocumentsAdd",
            Config("API_EDIT_ACTION") => "DocumentsEdit",
            Config("API_DELETE_ACTION") => "DocumentsDelete",
            Config("API_LIST_ACTION") => "DocumentsList",
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
        return "DocumentsList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("DocumentsView", $parm);
        } else {
            $url = $this->keyUrl("DocumentsView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "DocumentsAdd?" . $parm;
        } else {
            $url = "DocumentsAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("DocumentsEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("DocumentsList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("DocumentsAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("DocumentsList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("DocumentsDelete", $parm);
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
        $json .= "\"document_id\":" . VarToJson($this->document_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->document_id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->document_id->CurrentValue);
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
            if (($keyValue = Param("document_id") ?? Route("document_id")) !== null) {
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
                $this->document_id->CurrentValue = $key;
            } else {
                $this->document_id->OldValue = $key;
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
        $this->is_deleted->setDbValue(ConvertToBool($row['is_deleted']) ? "1" : "0");
        $this->deletion_date->setDbValue($row['deletion_date']);
        $this->deleted_by->setDbValue($row['deleted_by']);
        $this->parent_document_id->setDbValue($row['parent_document_id']);
        $this->version->setDbValue($row['version']);
        $this->notes->setDbValue($row['notes']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "DocumentsList";
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

        // document_id

        // user_id

        // template_id

        // document_title

        // document_reference

        // status

        // created_at

        // updated_at

        // submitted_at

        // company_name

        // customs_entry_number

        // date_of_entry

        // document_html

        // document_data

        // is_deleted

        // deletion_date

        // deleted_by

        // parent_document_id

        // version

        // notes

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

        // document_id
        $this->document_id->HrefValue = "";
        $this->document_id->TooltipValue = "";

        // user_id
        $this->user_id->HrefValue = "";
        $this->user_id->TooltipValue = "";

        // template_id
        $this->template_id->HrefValue = "";
        $this->template_id->TooltipValue = "";

        // document_title
        $this->document_title->HrefValue = "";
        $this->document_title->TooltipValue = "";

        // document_reference
        $this->document_reference->HrefValue = "";
        $this->document_reference->TooltipValue = "";

        // status
        $this->status->HrefValue = "";
        $this->status->TooltipValue = "";

        // created_at
        $this->created_at->HrefValue = "";
        $this->created_at->TooltipValue = "";

        // updated_at
        $this->updated_at->HrefValue = "";
        $this->updated_at->TooltipValue = "";

        // submitted_at
        $this->submitted_at->HrefValue = "";
        $this->submitted_at->TooltipValue = "";

        // company_name
        $this->company_name->HrefValue = "";
        $this->company_name->TooltipValue = "";

        // customs_entry_number
        $this->customs_entry_number->HrefValue = "";
        $this->customs_entry_number->TooltipValue = "";

        // date_of_entry
        $this->date_of_entry->HrefValue = "";
        $this->date_of_entry->TooltipValue = "";

        // document_html
        $this->document_html->HrefValue = "";
        $this->document_html->TooltipValue = "";

        // document_data
        $this->document_data->HrefValue = "";
        $this->document_data->TooltipValue = "";

        // is_deleted
        $this->is_deleted->HrefValue = "";
        $this->is_deleted->TooltipValue = "";

        // deletion_date
        $this->deletion_date->HrefValue = "";
        $this->deletion_date->TooltipValue = "";

        // deleted_by
        $this->deleted_by->HrefValue = "";
        $this->deleted_by->TooltipValue = "";

        // parent_document_id
        $this->parent_document_id->HrefValue = "";
        $this->parent_document_id->TooltipValue = "";

        // version
        $this->version->HrefValue = "";
        $this->version->TooltipValue = "";

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
        $this->document_title->EditValue = $this->document_title->CurrentValue;
        $this->document_title->PlaceHolder = RemoveHtml($this->document_title->caption());

        // document_reference
        $this->document_reference->setupEditAttributes();
        if (!$this->document_reference->Raw) {
            $this->document_reference->CurrentValue = HtmlDecode($this->document_reference->CurrentValue);
        }
        $this->document_reference->EditValue = $this->document_reference->CurrentValue;
        $this->document_reference->PlaceHolder = RemoveHtml($this->document_reference->caption());

        // status
        $this->status->setupEditAttributes();
        if (!$this->status->Raw) {
            $this->status->CurrentValue = HtmlDecode($this->status->CurrentValue);
        }
        $this->status->EditValue = $this->status->CurrentValue;
        $this->status->PlaceHolder = RemoveHtml($this->status->caption());

        // created_at
        $this->created_at->setupEditAttributes();
        $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

        // updated_at
        $this->updated_at->setupEditAttributes();
        $this->updated_at->EditValue = FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

        // submitted_at
        $this->submitted_at->setupEditAttributes();
        $this->submitted_at->EditValue = FormatDateTime($this->submitted_at->CurrentValue, $this->submitted_at->formatPattern());
        $this->submitted_at->PlaceHolder = RemoveHtml($this->submitted_at->caption());

        // company_name
        $this->company_name->setupEditAttributes();
        if (!$this->company_name->Raw) {
            $this->company_name->CurrentValue = HtmlDecode($this->company_name->CurrentValue);
        }
        $this->company_name->EditValue = $this->company_name->CurrentValue;
        $this->company_name->PlaceHolder = RemoveHtml($this->company_name->caption());

        // customs_entry_number
        $this->customs_entry_number->setupEditAttributes();
        if (!$this->customs_entry_number->Raw) {
            $this->customs_entry_number->CurrentValue = HtmlDecode($this->customs_entry_number->CurrentValue);
        }
        $this->customs_entry_number->EditValue = $this->customs_entry_number->CurrentValue;
        $this->customs_entry_number->PlaceHolder = RemoveHtml($this->customs_entry_number->caption());

        // date_of_entry
        $this->date_of_entry->setupEditAttributes();
        $this->date_of_entry->EditValue = FormatDateTime($this->date_of_entry->CurrentValue, $this->date_of_entry->formatPattern());
        $this->date_of_entry->PlaceHolder = RemoveHtml($this->date_of_entry->caption());

        // document_html
        $this->document_html->setupEditAttributes();
        $this->document_html->EditValue = $this->document_html->CurrentValue;
        $this->document_html->PlaceHolder = RemoveHtml($this->document_html->caption());

        // document_data
        $this->document_data->setupEditAttributes();
        $this->document_data->EditValue = $this->document_data->CurrentValue;
        $this->document_data->PlaceHolder = RemoveHtml($this->document_data->caption());

        // is_deleted
        $this->is_deleted->EditValue = $this->is_deleted->options(false);
        $this->is_deleted->PlaceHolder = RemoveHtml($this->is_deleted->caption());

        // deletion_date
        $this->deletion_date->setupEditAttributes();
        $this->deletion_date->EditValue = FormatDateTime($this->deletion_date->CurrentValue, $this->deletion_date->formatPattern());
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
                    $doc->exportCaption($this->document_id);
                    $doc->exportCaption($this->user_id);
                    $doc->exportCaption($this->template_id);
                    $doc->exportCaption($this->document_title);
                    $doc->exportCaption($this->document_reference);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->updated_at);
                    $doc->exportCaption($this->submitted_at);
                    $doc->exportCaption($this->company_name);
                    $doc->exportCaption($this->customs_entry_number);
                    $doc->exportCaption($this->date_of_entry);
                    $doc->exportCaption($this->document_html);
                    $doc->exportCaption($this->document_data);
                    $doc->exportCaption($this->is_deleted);
                    $doc->exportCaption($this->deletion_date);
                    $doc->exportCaption($this->deleted_by);
                    $doc->exportCaption($this->parent_document_id);
                    $doc->exportCaption($this->version);
                    $doc->exportCaption($this->notes);
                } else {
                    $doc->exportCaption($this->document_id);
                    $doc->exportCaption($this->user_id);
                    $doc->exportCaption($this->template_id);
                    $doc->exportCaption($this->document_title);
                    $doc->exportCaption($this->document_reference);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->updated_at);
                    $doc->exportCaption($this->submitted_at);
                    $doc->exportCaption($this->company_name);
                    $doc->exportCaption($this->customs_entry_number);
                    $doc->exportCaption($this->date_of_entry);
                    $doc->exportCaption($this->is_deleted);
                    $doc->exportCaption($this->deletion_date);
                    $doc->exportCaption($this->deleted_by);
                    $doc->exportCaption($this->parent_document_id);
                    $doc->exportCaption($this->version);
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
                        $doc->exportField($this->document_id);
                        $doc->exportField($this->user_id);
                        $doc->exportField($this->template_id);
                        $doc->exportField($this->document_title);
                        $doc->exportField($this->document_reference);
                        $doc->exportField($this->status);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->updated_at);
                        $doc->exportField($this->submitted_at);
                        $doc->exportField($this->company_name);
                        $doc->exportField($this->customs_entry_number);
                        $doc->exportField($this->date_of_entry);
                        $doc->exportField($this->document_html);
                        $doc->exportField($this->document_data);
                        $doc->exportField($this->is_deleted);
                        $doc->exportField($this->deletion_date);
                        $doc->exportField($this->deleted_by);
                        $doc->exportField($this->parent_document_id);
                        $doc->exportField($this->version);
                        $doc->exportField($this->notes);
                    } else {
                        $doc->exportField($this->document_id);
                        $doc->exportField($this->user_id);
                        $doc->exportField($this->template_id);
                        $doc->exportField($this->document_title);
                        $doc->exportField($this->document_reference);
                        $doc->exportField($this->status);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->updated_at);
                        $doc->exportField($this->submitted_at);
                        $doc->exportField($this->company_name);
                        $doc->exportField($this->customs_entry_number);
                        $doc->exportField($this->date_of_entry);
                        $doc->exportField($this->is_deleted);
                        $doc->exportField($this->deletion_date);
                        $doc->exportField($this->deleted_by);
                        $doc->exportField($this->parent_document_id);
                        $doc->exportField($this->version);
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
