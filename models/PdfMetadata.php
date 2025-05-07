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
 * Table class for pdf_metadata
 */
class PdfMetadata extends DbTable
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
    public $metadata_id;
    public $document_id;
    public $notarized_id;
    public $pdf_type;
    public $file_path;
    public $file_size;
    public $page_count;
    public $generated_at;
    public $generated_by;
    public $expires_at;
    public $is_final;
    public $processing_options;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "pdf_metadata";
        $this->TableName = 'pdf_metadata';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "pdf_metadata";
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

        // metadata_id
        $this->metadata_id = new DbField(
            $this, // Table
            'x_metadata_id', // Variable name
            'metadata_id', // Name
            '"metadata_id"', // Expression
            'CAST("metadata_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"metadata_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->metadata_id->InputTextType = "text";
        $this->metadata_id->Raw = true;
        $this->metadata_id->IsAutoIncrement = true; // Autoincrement field
        $this->metadata_id->IsPrimaryKey = true; // Primary key field
        $this->metadata_id->Nullable = false; // NOT NULL field
        $this->metadata_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->metadata_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['metadata_id'] = &$this->metadata_id;

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
            'TEXT' // Edit Tag
        );
        $this->document_id->InputTextType = "text";
        $this->document_id->Raw = true;
        $this->document_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->document_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['document_id'] = &$this->document_id;

        // notarized_id
        $this->notarized_id = new DbField(
            $this, // Table
            'x_notarized_id', // Variable name
            'notarized_id', // Name
            '"notarized_id"', // Expression
            'CAST("notarized_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"notarized_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->notarized_id->InputTextType = "text";
        $this->notarized_id->Raw = true;
        $this->notarized_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->notarized_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['notarized_id'] = &$this->notarized_id;

        // pdf_type
        $this->pdf_type = new DbField(
            $this, // Table
            'x_pdf_type', // Variable name
            'pdf_type', // Name
            '"pdf_type"', // Expression
            '"pdf_type"', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"pdf_type"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->pdf_type->InputTextType = "text";
        $this->pdf_type->Nullable = false; // NOT NULL field
        $this->pdf_type->Required = true; // Required field
        $this->pdf_type->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['pdf_type'] = &$this->pdf_type;

        // file_path
        $this->file_path = new DbField(
            $this, // Table
            'x_file_path', // Variable name
            'file_path', // Name
            '"file_path"', // Expression
            '"file_path"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"file_path"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->file_path->InputTextType = "text";
        $this->file_path->Nullable = false; // NOT NULL field
        $this->file_path->Required = true; // Required field
        $this->file_path->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['file_path'] = &$this->file_path;

        // file_size
        $this->file_size = new DbField(
            $this, // Table
            'x_file_size', // Variable name
            'file_size', // Name
            '"file_size"', // Expression
            'CAST("file_size" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"file_size"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->file_size->InputTextType = "text";
        $this->file_size->Raw = true;
        $this->file_size->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->file_size->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['file_size'] = &$this->file_size;

        // page_count
        $this->page_count = new DbField(
            $this, // Table
            'x_page_count', // Variable name
            'page_count', // Name
            '"page_count"', // Expression
            'CAST("page_count" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"page_count"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->page_count->InputTextType = "text";
        $this->page_count->Raw = true;
        $this->page_count->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->page_count->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['page_count'] = &$this->page_count;

        // generated_at
        $this->generated_at = new DbField(
            $this, // Table
            'x_generated_at', // Variable name
            'generated_at', // Name
            '"generated_at"', // Expression
            CastDateFieldForLike("\"generated_at\"", 0, "DB"), // Basic search expression
            135, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"generated_at"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->generated_at->InputTextType = "text";
        $this->generated_at->Raw = true;
        $this->generated_at->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->generated_at->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['generated_at'] = &$this->generated_at;

        // generated_by
        $this->generated_by = new DbField(
            $this, // Table
            'x_generated_by', // Variable name
            'generated_by', // Name
            '"generated_by"', // Expression
            'CAST("generated_by" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"generated_by"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->generated_by->InputTextType = "text";
        $this->generated_by->Raw = true;
        $this->generated_by->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->generated_by->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['generated_by'] = &$this->generated_by;

        // expires_at
        $this->expires_at = new DbField(
            $this, // Table
            'x_expires_at', // Variable name
            'expires_at', // Name
            '"expires_at"', // Expression
            CastDateFieldForLike("\"expires_at\"", 0, "DB"), // Basic search expression
            135, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"expires_at"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->expires_at->InputTextType = "text";
        $this->expires_at->Raw = true;
        $this->expires_at->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->expires_at->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['expires_at'] = &$this->expires_at;

        // is_final
        $this->is_final = new DbField(
            $this, // Table
            'x_is_final', // Variable name
            'is_final', // Name
            '"is_final"', // Expression
            'CAST("is_final" AS varchar(255))', // Basic search expression
            11, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"is_final"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->is_final->InputTextType = "text";
        $this->is_final->Raw = true;
        $this->is_final->setDataType(DataType::BOOLEAN);
        $this->is_final->Lookup = new Lookup($this->is_final, 'pdf_metadata', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->is_final->OptionCount = 2;
        $this->is_final->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['is_final'] = &$this->is_final;

        // processing_options
        $this->processing_options = new DbField(
            $this, // Table
            'x_processing_options', // Variable name
            'processing_options', // Name
            '"processing_options"', // Expression
            'CAST("processing_options" AS varchar(255))', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"processing_options"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->processing_options->InputTextType = "text";
        $this->processing_options->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['processing_options'] = &$this->processing_options;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "pdf_metadata";
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
                $queryBuilder->getSQL() . " RETURNING metadata_id",
                $queryBuilder->getParameters(),
                $queryBuilder->getParameterTypes()
            )->fetchOne();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->metadata_id->setDbValue($result);
            $rs['metadata_id'] = $this->metadata_id->DbValue;
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
            if (!isset($rs['metadata_id']) && !EmptyValue($this->metadata_id->CurrentValue)) {
                $rs['metadata_id'] = $this->metadata_id->CurrentValue;
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
            if (array_key_exists('metadata_id', $rs)) {
                AddFilter($where, QuotedName('metadata_id', $this->Dbid) . '=' . QuotedValue($rs['metadata_id'], $this->metadata_id->DataType, $this->Dbid));
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
        $this->metadata_id->DbValue = $row['metadata_id'];
        $this->document_id->DbValue = $row['document_id'];
        $this->notarized_id->DbValue = $row['notarized_id'];
        $this->pdf_type->DbValue = $row['pdf_type'];
        $this->file_path->DbValue = $row['file_path'];
        $this->file_size->DbValue = $row['file_size'];
        $this->page_count->DbValue = $row['page_count'];
        $this->generated_at->DbValue = $row['generated_at'];
        $this->generated_by->DbValue = $row['generated_by'];
        $this->expires_at->DbValue = $row['expires_at'];
        $this->is_final->DbValue = (ConvertToBool($row['is_final']) ? "1" : "0");
        $this->processing_options->DbValue = $row['processing_options'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "\"metadata_id\" = @metadata_id@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->metadata_id->CurrentValue : $this->metadata_id->OldValue;
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
                $this->metadata_id->CurrentValue = $keys[0];
            } else {
                $this->metadata_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('metadata_id', $row) ? $row['metadata_id'] : null;
        } else {
            $val = !EmptyValue($this->metadata_id->OldValue) && !$current ? $this->metadata_id->OldValue : $this->metadata_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@metadata_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("PdfMetadataList");
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
            "PdfMetadataView" => $Language->phrase("View"),
            "PdfMetadataEdit" => $Language->phrase("Edit"),
            "PdfMetadataAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "PdfMetadataList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "PdfMetadataView",
            Config("API_ADD_ACTION") => "PdfMetadataAdd",
            Config("API_EDIT_ACTION") => "PdfMetadataEdit",
            Config("API_DELETE_ACTION") => "PdfMetadataDelete",
            Config("API_LIST_ACTION") => "PdfMetadataList",
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
        return "PdfMetadataList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PdfMetadataView", $parm);
        } else {
            $url = $this->keyUrl("PdfMetadataView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "PdfMetadataAdd?" . $parm;
        } else {
            $url = "PdfMetadataAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("PdfMetadataEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("PdfMetadataList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("PdfMetadataAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("PdfMetadataList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("PdfMetadataDelete", $parm);
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
        $json .= "\"metadata_id\":" . VarToJson($this->metadata_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->metadata_id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->metadata_id->CurrentValue);
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
            if (($keyValue = Param("metadata_id") ?? Route("metadata_id")) !== null) {
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
                $this->metadata_id->CurrentValue = $key;
            } else {
                $this->metadata_id->OldValue = $key;
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
        $this->is_final->setDbValue(ConvertToBool($row['is_final']) ? "1" : "0");
        $this->processing_options->setDbValue($row['processing_options']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "PdfMetadataList";
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

        // metadata_id

        // document_id

        // notarized_id

        // pdf_type

        // file_path

        // file_size

        // page_count

        // generated_at

        // generated_by

        // expires_at

        // is_final

        // processing_options

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
        $this->metadata_id->TooltipValue = "";

        // document_id
        $this->document_id->HrefValue = "";
        $this->document_id->TooltipValue = "";

        // notarized_id
        $this->notarized_id->HrefValue = "";
        $this->notarized_id->TooltipValue = "";

        // pdf_type
        $this->pdf_type->HrefValue = "";
        $this->pdf_type->TooltipValue = "";

        // file_path
        $this->file_path->HrefValue = "";
        $this->file_path->TooltipValue = "";

        // file_size
        $this->file_size->HrefValue = "";
        $this->file_size->TooltipValue = "";

        // page_count
        $this->page_count->HrefValue = "";
        $this->page_count->TooltipValue = "";

        // generated_at
        $this->generated_at->HrefValue = "";
        $this->generated_at->TooltipValue = "";

        // generated_by
        $this->generated_by->HrefValue = "";
        $this->generated_by->TooltipValue = "";

        // expires_at
        $this->expires_at->HrefValue = "";
        $this->expires_at->TooltipValue = "";

        // is_final
        $this->is_final->HrefValue = "";
        $this->is_final->TooltipValue = "";

        // processing_options
        $this->processing_options->HrefValue = "";
        $this->processing_options->TooltipValue = "";

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
        $this->pdf_type->EditValue = $this->pdf_type->CurrentValue;
        $this->pdf_type->PlaceHolder = RemoveHtml($this->pdf_type->caption());

        // file_path
        $this->file_path->setupEditAttributes();
        if (!$this->file_path->Raw) {
            $this->file_path->CurrentValue = HtmlDecode($this->file_path->CurrentValue);
        }
        $this->file_path->EditValue = $this->file_path->CurrentValue;
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
        $this->generated_at->EditValue = FormatDateTime($this->generated_at->CurrentValue, $this->generated_at->formatPattern());
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
        $this->expires_at->EditValue = FormatDateTime($this->expires_at->CurrentValue, $this->expires_at->formatPattern());
        $this->expires_at->PlaceHolder = RemoveHtml($this->expires_at->caption());

        // is_final
        $this->is_final->EditValue = $this->is_final->options(false);
        $this->is_final->PlaceHolder = RemoveHtml($this->is_final->caption());

        // processing_options
        $this->processing_options->setupEditAttributes();
        $this->processing_options->EditValue = $this->processing_options->CurrentValue;
        $this->processing_options->PlaceHolder = RemoveHtml($this->processing_options->caption());

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
                    $doc->exportCaption($this->metadata_id);
                    $doc->exportCaption($this->document_id);
                    $doc->exportCaption($this->notarized_id);
                    $doc->exportCaption($this->pdf_type);
                    $doc->exportCaption($this->file_path);
                    $doc->exportCaption($this->file_size);
                    $doc->exportCaption($this->page_count);
                    $doc->exportCaption($this->generated_at);
                    $doc->exportCaption($this->generated_by);
                    $doc->exportCaption($this->expires_at);
                    $doc->exportCaption($this->is_final);
                    $doc->exportCaption($this->processing_options);
                } else {
                    $doc->exportCaption($this->metadata_id);
                    $doc->exportCaption($this->document_id);
                    $doc->exportCaption($this->notarized_id);
                    $doc->exportCaption($this->pdf_type);
                    $doc->exportCaption($this->file_path);
                    $doc->exportCaption($this->file_size);
                    $doc->exportCaption($this->page_count);
                    $doc->exportCaption($this->generated_at);
                    $doc->exportCaption($this->generated_by);
                    $doc->exportCaption($this->expires_at);
                    $doc->exportCaption($this->is_final);
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
                        $doc->exportField($this->metadata_id);
                        $doc->exportField($this->document_id);
                        $doc->exportField($this->notarized_id);
                        $doc->exportField($this->pdf_type);
                        $doc->exportField($this->file_path);
                        $doc->exportField($this->file_size);
                        $doc->exportField($this->page_count);
                        $doc->exportField($this->generated_at);
                        $doc->exportField($this->generated_by);
                        $doc->exportField($this->expires_at);
                        $doc->exportField($this->is_final);
                        $doc->exportField($this->processing_options);
                    } else {
                        $doc->exportField($this->metadata_id);
                        $doc->exportField($this->document_id);
                        $doc->exportField($this->notarized_id);
                        $doc->exportField($this->pdf_type);
                        $doc->exportField($this->file_path);
                        $doc->exportField($this->file_size);
                        $doc->exportField($this->page_count);
                        $doc->exportField($this->generated_at);
                        $doc->exportField($this->generated_by);
                        $doc->exportField($this->expires_at);
                        $doc->exportField($this->is_final);
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
