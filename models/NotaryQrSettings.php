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
 * Table class for notary_qr_settings
 */
class NotaryQrSettings extends DbTable
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
    public $settings_id;
    public $notary_id;
    public $default_size;
    public $foreground_color;
    public $background_color;
    public $logo_path;
    public $logo_size_percent;
    public $error_correction;
    public $corner_radius_percent;
    public $created_at;
    public $updated_at;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "notary_qr_settings";
        $this->TableName = 'notary_qr_settings';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "notary_qr_settings";
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

        // settings_id
        $this->settings_id = new DbField(
            $this, // Table
            'x_settings_id', // Variable name
            'settings_id', // Name
            '"settings_id"', // Expression
            'CAST("settings_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"settings_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->settings_id->InputTextType = "text";
        $this->settings_id->Raw = true;
        $this->settings_id->IsAutoIncrement = true; // Autoincrement field
        $this->settings_id->IsPrimaryKey = true; // Primary key field
        $this->settings_id->Nullable = false; // NOT NULL field
        $this->settings_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->settings_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['settings_id'] = &$this->settings_id;

        // notary_id
        $this->notary_id = new DbField(
            $this, // Table
            'x_notary_id', // Variable name
            'notary_id', // Name
            '"notary_id"', // Expression
            'CAST("notary_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"notary_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->notary_id->InputTextType = "text";
        $this->notary_id->Raw = true;
        $this->notary_id->Nullable = false; // NOT NULL field
        $this->notary_id->Required = true; // Required field
        $this->notary_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->notary_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['notary_id'] = &$this->notary_id;

        // default_size
        $this->default_size = new DbField(
            $this, // Table
            'x_default_size', // Variable name
            'default_size', // Name
            '"default_size"', // Expression
            'CAST("default_size" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"default_size"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->default_size->addMethod("getDefault", fn() => 250);
        $this->default_size->InputTextType = "text";
        $this->default_size->Raw = true;
        $this->default_size->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->default_size->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['default_size'] = &$this->default_size;

        // foreground_color
        $this->foreground_color = new DbField(
            $this, // Table
            'x_foreground_color', // Variable name
            'foreground_color', // Name
            '"foreground_color"', // Expression
            '"foreground_color"', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"foreground_color"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->foreground_color->addMethod("getDefault", fn() => "#000000");
        $this->foreground_color->InputTextType = "text";
        $this->foreground_color->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['foreground_color'] = &$this->foreground_color;

        // background_color
        $this->background_color = new DbField(
            $this, // Table
            'x_background_color', // Variable name
            'background_color', // Name
            '"background_color"', // Expression
            '"background_color"', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"background_color"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->background_color->addMethod("getDefault", fn() => "#ffffff");
        $this->background_color->InputTextType = "text";
        $this->background_color->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['background_color'] = &$this->background_color;

        // logo_path
        $this->logo_path = new DbField(
            $this, // Table
            'x_logo_path', // Variable name
            'logo_path', // Name
            '"logo_path"', // Expression
            '"logo_path"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"logo_path"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->logo_path->InputTextType = "text";
        $this->logo_path->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['logo_path'] = &$this->logo_path;

        // logo_size_percent
        $this->logo_size_percent = new DbField(
            $this, // Table
            'x_logo_size_percent', // Variable name
            'logo_size_percent', // Name
            '"logo_size_percent"', // Expression
            'CAST("logo_size_percent" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"logo_size_percent"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->logo_size_percent->addMethod("getDefault", fn() => 20);
        $this->logo_size_percent->InputTextType = "text";
        $this->logo_size_percent->Raw = true;
        $this->logo_size_percent->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->logo_size_percent->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['logo_size_percent'] = &$this->logo_size_percent;

        // error_correction
        $this->error_correction = new DbField(
            $this, // Table
            'x_error_correction', // Variable name
            'error_correction', // Name
            '"error_correction"', // Expression
            '"error_correction"', // Basic search expression
            200, // Type
            5, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"error_correction"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->error_correction->addMethod("getDefault", fn() => "m");
        $this->error_correction->InputTextType = "text";
        $this->error_correction->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['error_correction'] = &$this->error_correction;

        // corner_radius_percent
        $this->corner_radius_percent = new DbField(
            $this, // Table
            'x_corner_radius_percent', // Variable name
            'corner_radius_percent', // Name
            '"corner_radius_percent"', // Expression
            'CAST("corner_radius_percent" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"corner_radius_percent"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->corner_radius_percent->addMethod("getDefault", fn() => 0);
        $this->corner_radius_percent->InputTextType = "text";
        $this->corner_radius_percent->Raw = true;
        $this->corner_radius_percent->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->corner_radius_percent->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['corner_radius_percent'] = &$this->corner_radius_percent;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "notary_qr_settings";
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
                $queryBuilder->getSQL() . " RETURNING settings_id",
                $queryBuilder->getParameters(),
                $queryBuilder->getParameterTypes()
            )->fetchOne();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->settings_id->setDbValue($result);
            $rs['settings_id'] = $this->settings_id->DbValue;
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
            if (!isset($rs['settings_id']) && !EmptyValue($this->settings_id->CurrentValue)) {
                $rs['settings_id'] = $this->settings_id->CurrentValue;
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
            if (array_key_exists('settings_id', $rs)) {
                AddFilter($where, QuotedName('settings_id', $this->Dbid) . '=' . QuotedValue($rs['settings_id'], $this->settings_id->DataType, $this->Dbid));
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
        $this->settings_id->DbValue = $row['settings_id'];
        $this->notary_id->DbValue = $row['notary_id'];
        $this->default_size->DbValue = $row['default_size'];
        $this->foreground_color->DbValue = $row['foreground_color'];
        $this->background_color->DbValue = $row['background_color'];
        $this->logo_path->DbValue = $row['logo_path'];
        $this->logo_size_percent->DbValue = $row['logo_size_percent'];
        $this->error_correction->DbValue = $row['error_correction'];
        $this->corner_radius_percent->DbValue = $row['corner_radius_percent'];
        $this->created_at->DbValue = $row['created_at'];
        $this->updated_at->DbValue = $row['updated_at'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "\"settings_id\" = @settings_id@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->settings_id->CurrentValue : $this->settings_id->OldValue;
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
                $this->settings_id->CurrentValue = $keys[0];
            } else {
                $this->settings_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('settings_id', $row) ? $row['settings_id'] : null;
        } else {
            $val = !EmptyValue($this->settings_id->OldValue) && !$current ? $this->settings_id->OldValue : $this->settings_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@settings_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("NotaryQrSettingsList");
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
            "NotaryQrSettingsView" => $Language->phrase("View"),
            "NotaryQrSettingsEdit" => $Language->phrase("Edit"),
            "NotaryQrSettingsAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "NotaryQrSettingsList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "NotaryQrSettingsView",
            Config("API_ADD_ACTION") => "NotaryQrSettingsAdd",
            Config("API_EDIT_ACTION") => "NotaryQrSettingsEdit",
            Config("API_DELETE_ACTION") => "NotaryQrSettingsDelete",
            Config("API_LIST_ACTION") => "NotaryQrSettingsList",
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
        return "NotaryQrSettingsList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("NotaryQrSettingsView", $parm);
        } else {
            $url = $this->keyUrl("NotaryQrSettingsView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "NotaryQrSettingsAdd?" . $parm;
        } else {
            $url = "NotaryQrSettingsAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("NotaryQrSettingsEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("NotaryQrSettingsList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("NotaryQrSettingsAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("NotaryQrSettingsList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("NotaryQrSettingsDelete", $parm);
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
        $json .= "\"settings_id\":" . VarToJson($this->settings_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->settings_id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->settings_id->CurrentValue);
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
            if (($keyValue = Param("settings_id") ?? Route("settings_id")) !== null) {
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
                $this->settings_id->CurrentValue = $key;
            } else {
                $this->settings_id->OldValue = $key;
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
        $this->settings_id->setDbValue($row['settings_id']);
        $this->notary_id->setDbValue($row['notary_id']);
        $this->default_size->setDbValue($row['default_size']);
        $this->foreground_color->setDbValue($row['foreground_color']);
        $this->background_color->setDbValue($row['background_color']);
        $this->logo_path->setDbValue($row['logo_path']);
        $this->logo_size_percent->setDbValue($row['logo_size_percent']);
        $this->error_correction->setDbValue($row['error_correction']);
        $this->corner_radius_percent->setDbValue($row['corner_radius_percent']);
        $this->created_at->setDbValue($row['created_at']);
        $this->updated_at->setDbValue($row['updated_at']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "NotaryQrSettingsList";
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

        // settings_id

        // notary_id

        // default_size

        // foreground_color

        // background_color

        // logo_path

        // logo_size_percent

        // error_correction

        // corner_radius_percent

        // created_at

        // updated_at

        // settings_id
        $this->settings_id->ViewValue = $this->settings_id->CurrentValue;

        // notary_id
        $this->notary_id->ViewValue = $this->notary_id->CurrentValue;
        $this->notary_id->ViewValue = FormatNumber($this->notary_id->ViewValue, $this->notary_id->formatPattern());

        // default_size
        $this->default_size->ViewValue = $this->default_size->CurrentValue;
        $this->default_size->ViewValue = FormatNumber($this->default_size->ViewValue, $this->default_size->formatPattern());

        // foreground_color
        $this->foreground_color->ViewValue = $this->foreground_color->CurrentValue;

        // background_color
        $this->background_color->ViewValue = $this->background_color->CurrentValue;

        // logo_path
        $this->logo_path->ViewValue = $this->logo_path->CurrentValue;

        // logo_size_percent
        $this->logo_size_percent->ViewValue = $this->logo_size_percent->CurrentValue;
        $this->logo_size_percent->ViewValue = FormatNumber($this->logo_size_percent->ViewValue, $this->logo_size_percent->formatPattern());

        // error_correction
        $this->error_correction->ViewValue = $this->error_correction->CurrentValue;

        // corner_radius_percent
        $this->corner_radius_percent->ViewValue = $this->corner_radius_percent->CurrentValue;
        $this->corner_radius_percent->ViewValue = FormatNumber($this->corner_radius_percent->ViewValue, $this->corner_radius_percent->formatPattern());

        // created_at
        $this->created_at->ViewValue = $this->created_at->CurrentValue;
        $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

        // updated_at
        $this->updated_at->ViewValue = $this->updated_at->CurrentValue;
        $this->updated_at->ViewValue = FormatDateTime($this->updated_at->ViewValue, $this->updated_at->formatPattern());

        // settings_id
        $this->settings_id->HrefValue = "";
        $this->settings_id->TooltipValue = "";

        // notary_id
        $this->notary_id->HrefValue = "";
        $this->notary_id->TooltipValue = "";

        // default_size
        $this->default_size->HrefValue = "";
        $this->default_size->TooltipValue = "";

        // foreground_color
        $this->foreground_color->HrefValue = "";
        $this->foreground_color->TooltipValue = "";

        // background_color
        $this->background_color->HrefValue = "";
        $this->background_color->TooltipValue = "";

        // logo_path
        $this->logo_path->HrefValue = "";
        $this->logo_path->TooltipValue = "";

        // logo_size_percent
        $this->logo_size_percent->HrefValue = "";
        $this->logo_size_percent->TooltipValue = "";

        // error_correction
        $this->error_correction->HrefValue = "";
        $this->error_correction->TooltipValue = "";

        // corner_radius_percent
        $this->corner_radius_percent->HrefValue = "";
        $this->corner_radius_percent->TooltipValue = "";

        // created_at
        $this->created_at->HrefValue = "";
        $this->created_at->TooltipValue = "";

        // updated_at
        $this->updated_at->HrefValue = "";
        $this->updated_at->TooltipValue = "";

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

        // settings_id
        $this->settings_id->setupEditAttributes();
        $this->settings_id->EditValue = $this->settings_id->CurrentValue;

        // notary_id
        $this->notary_id->setupEditAttributes();
        $this->notary_id->EditValue = $this->notary_id->CurrentValue;
        $this->notary_id->PlaceHolder = RemoveHtml($this->notary_id->caption());
        if (strval($this->notary_id->EditValue) != "" && is_numeric($this->notary_id->EditValue)) {
            $this->notary_id->EditValue = FormatNumber($this->notary_id->EditValue, null);
        }

        // default_size
        $this->default_size->setupEditAttributes();
        $this->default_size->EditValue = $this->default_size->CurrentValue;
        $this->default_size->PlaceHolder = RemoveHtml($this->default_size->caption());
        if (strval($this->default_size->EditValue) != "" && is_numeric($this->default_size->EditValue)) {
            $this->default_size->EditValue = FormatNumber($this->default_size->EditValue, null);
        }

        // foreground_color
        $this->foreground_color->setupEditAttributes();
        if (!$this->foreground_color->Raw) {
            $this->foreground_color->CurrentValue = HtmlDecode($this->foreground_color->CurrentValue);
        }
        $this->foreground_color->EditValue = $this->foreground_color->CurrentValue;
        $this->foreground_color->PlaceHolder = RemoveHtml($this->foreground_color->caption());

        // background_color
        $this->background_color->setupEditAttributes();
        if (!$this->background_color->Raw) {
            $this->background_color->CurrentValue = HtmlDecode($this->background_color->CurrentValue);
        }
        $this->background_color->EditValue = $this->background_color->CurrentValue;
        $this->background_color->PlaceHolder = RemoveHtml($this->background_color->caption());

        // logo_path
        $this->logo_path->setupEditAttributes();
        if (!$this->logo_path->Raw) {
            $this->logo_path->CurrentValue = HtmlDecode($this->logo_path->CurrentValue);
        }
        $this->logo_path->EditValue = $this->logo_path->CurrentValue;
        $this->logo_path->PlaceHolder = RemoveHtml($this->logo_path->caption());

        // logo_size_percent
        $this->logo_size_percent->setupEditAttributes();
        $this->logo_size_percent->EditValue = $this->logo_size_percent->CurrentValue;
        $this->logo_size_percent->PlaceHolder = RemoveHtml($this->logo_size_percent->caption());
        if (strval($this->logo_size_percent->EditValue) != "" && is_numeric($this->logo_size_percent->EditValue)) {
            $this->logo_size_percent->EditValue = FormatNumber($this->logo_size_percent->EditValue, null);
        }

        // error_correction
        $this->error_correction->setupEditAttributes();
        if (!$this->error_correction->Raw) {
            $this->error_correction->CurrentValue = HtmlDecode($this->error_correction->CurrentValue);
        }
        $this->error_correction->EditValue = $this->error_correction->CurrentValue;
        $this->error_correction->PlaceHolder = RemoveHtml($this->error_correction->caption());

        // corner_radius_percent
        $this->corner_radius_percent->setupEditAttributes();
        $this->corner_radius_percent->EditValue = $this->corner_radius_percent->CurrentValue;
        $this->corner_radius_percent->PlaceHolder = RemoveHtml($this->corner_radius_percent->caption());
        if (strval($this->corner_radius_percent->EditValue) != "" && is_numeric($this->corner_radius_percent->EditValue)) {
            $this->corner_radius_percent->EditValue = FormatNumber($this->corner_radius_percent->EditValue, null);
        }

        // created_at
        $this->created_at->setupEditAttributes();
        $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

        // updated_at
        $this->updated_at->setupEditAttributes();
        $this->updated_at->EditValue = FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

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
                    $doc->exportCaption($this->settings_id);
                    $doc->exportCaption($this->notary_id);
                    $doc->exportCaption($this->default_size);
                    $doc->exportCaption($this->foreground_color);
                    $doc->exportCaption($this->background_color);
                    $doc->exportCaption($this->logo_path);
                    $doc->exportCaption($this->logo_size_percent);
                    $doc->exportCaption($this->error_correction);
                    $doc->exportCaption($this->corner_radius_percent);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->updated_at);
                } else {
                    $doc->exportCaption($this->settings_id);
                    $doc->exportCaption($this->notary_id);
                    $doc->exportCaption($this->default_size);
                    $doc->exportCaption($this->foreground_color);
                    $doc->exportCaption($this->background_color);
                    $doc->exportCaption($this->logo_path);
                    $doc->exportCaption($this->logo_size_percent);
                    $doc->exportCaption($this->error_correction);
                    $doc->exportCaption($this->corner_radius_percent);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->updated_at);
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
                        $doc->exportField($this->settings_id);
                        $doc->exportField($this->notary_id);
                        $doc->exportField($this->default_size);
                        $doc->exportField($this->foreground_color);
                        $doc->exportField($this->background_color);
                        $doc->exportField($this->logo_path);
                        $doc->exportField($this->logo_size_percent);
                        $doc->exportField($this->error_correction);
                        $doc->exportField($this->corner_radius_percent);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->updated_at);
                    } else {
                        $doc->exportField($this->settings_id);
                        $doc->exportField($this->notary_id);
                        $doc->exportField($this->default_size);
                        $doc->exportField($this->foreground_color);
                        $doc->exportField($this->background_color);
                        $doc->exportField($this->logo_path);
                        $doc->exportField($this->logo_size_percent);
                        $doc->exportField($this->error_correction);
                        $doc->exportField($this->corner_radius_percent);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->updated_at);
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
