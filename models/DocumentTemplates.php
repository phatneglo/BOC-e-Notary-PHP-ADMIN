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
 * Table class for document_templates
 */
class DocumentTemplates extends DbTable
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
    public $template_id;
    public $template_name;
    public $template_code;
    public $category_id;
    public $description;
    public $html_content;
    public $is_active;
    public $created_at;
    public $created_by;
    public $updated_at;
    public $updated_by;
    public $version;
    public $notary_required;
    public $fee_amount;
    public $approval_workflow;
    public $template_type;
    public $header_text;
    public $footer_text;
    public $preview_image_path;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "document_templates";
        $this->TableName = 'document_templates';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "document_templates";
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
            'NO' // Edit Tag
        );
        $this->template_id->InputTextType = "text";
        $this->template_id->Raw = true;
        $this->template_id->IsAutoIncrement = true; // Autoincrement field
        $this->template_id->IsPrimaryKey = true; // Primary key field
        $this->template_id->Nullable = false; // NOT NULL field
        $this->template_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->template_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['template_id'] = &$this->template_id;

        // template_name
        $this->template_name = new DbField(
            $this, // Table
            'x_template_name', // Variable name
            'template_name', // Name
            '"template_name"', // Expression
            '"template_name"', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"template_name"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->template_name->InputTextType = "text";
        $this->template_name->Nullable = false; // NOT NULL field
        $this->template_name->Required = true; // Required field
        $this->template_name->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['template_name'] = &$this->template_name;

        // template_code
        $this->template_code = new DbField(
            $this, // Table
            'x_template_code', // Variable name
            'template_code', // Name
            '"template_code"', // Expression
            '"template_code"', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"template_code"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->template_code->InputTextType = "text";
        $this->template_code->Nullable = false; // NOT NULL field
        $this->template_code->Required = true; // Required field
        $this->template_code->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['template_code'] = &$this->template_code;

        // category_id
        $this->category_id = new DbField(
            $this, // Table
            'x_category_id', // Variable name
            'category_id', // Name
            '"category_id"', // Expression
            'CAST("category_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"category_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->category_id->InputTextType = "text";
        $this->category_id->Raw = true;
        $this->category_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->category_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['category_id'] = &$this->category_id;

        // description
        $this->description = new DbField(
            $this, // Table
            'x_description', // Variable name
            'description', // Name
            '"description"', // Expression
            '"description"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"description"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->description->InputTextType = "text";
        $this->description->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['description'] = &$this->description;

        // html_content
        $this->html_content = new DbField(
            $this, // Table
            'x_html_content', // Variable name
            'html_content', // Name
            '"html_content"', // Expression
            '"html_content"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"html_content"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->html_content->InputTextType = "text";
        $this->html_content->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['html_content'] = &$this->html_content;

        // is_active
        $this->is_active = new DbField(
            $this, // Table
            'x_is_active', // Variable name
            'is_active', // Name
            '"is_active"', // Expression
            'CAST("is_active" AS varchar(255))', // Basic search expression
            11, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"is_active"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->is_active->InputTextType = "text";
        $this->is_active->Raw = true;
        $this->is_active->setDataType(DataType::BOOLEAN);
        $this->is_active->Lookup = new Lookup($this->is_active, 'document_templates', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->is_active->OptionCount = 2;
        $this->is_active->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['is_active'] = &$this->is_active;

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

        // created_by
        $this->created_by = new DbField(
            $this, // Table
            'x_created_by', // Variable name
            'created_by', // Name
            '"created_by"', // Expression
            'CAST("created_by" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"created_by"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->created_by->InputTextType = "text";
        $this->created_by->Raw = true;
        $this->created_by->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->created_by->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['created_by'] = &$this->created_by;

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

        // updated_by
        $this->updated_by = new DbField(
            $this, // Table
            'x_updated_by', // Variable name
            'updated_by', // Name
            '"updated_by"', // Expression
            'CAST("updated_by" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"updated_by"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->updated_by->InputTextType = "text";
        $this->updated_by->Raw = true;
        $this->updated_by->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->updated_by->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['updated_by'] = &$this->updated_by;

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

        // notary_required
        $this->notary_required = new DbField(
            $this, // Table
            'x_notary_required', // Variable name
            'notary_required', // Name
            '"notary_required"', // Expression
            'CAST("notary_required" AS varchar(255))', // Basic search expression
            11, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"notary_required"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->notary_required->InputTextType = "text";
        $this->notary_required->Raw = true;
        $this->notary_required->setDataType(DataType::BOOLEAN);
        $this->notary_required->Lookup = new Lookup($this->notary_required, 'document_templates', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->notary_required->OptionCount = 2;
        $this->notary_required->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['notary_required'] = &$this->notary_required;

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
        $this->fee_amount->InputTextType = "text";
        $this->fee_amount->Raw = true;
        $this->fee_amount->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->fee_amount->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fee_amount'] = &$this->fee_amount;

        // approval_workflow
        $this->approval_workflow = new DbField(
            $this, // Table
            'x_approval_workflow', // Variable name
            'approval_workflow', // Name
            '"approval_workflow"', // Expression
            'CAST("approval_workflow" AS varchar(255))', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"approval_workflow"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->approval_workflow->InputTextType = "text";
        $this->approval_workflow->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['approval_workflow'] = &$this->approval_workflow;

        // template_type
        $this->template_type = new DbField(
            $this, // Table
            'x_template_type', // Variable name
            'template_type', // Name
            '"template_type"', // Expression
            '"template_type"', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"template_type"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->template_type->InputTextType = "text";
        $this->template_type->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['template_type'] = &$this->template_type;

        // header_text
        $this->header_text = new DbField(
            $this, // Table
            'x_header_text', // Variable name
            'header_text', // Name
            '"header_text"', // Expression
            '"header_text"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"header_text"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->header_text->InputTextType = "text";
        $this->header_text->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['header_text'] = &$this->header_text;

        // footer_text
        $this->footer_text = new DbField(
            $this, // Table
            'x_footer_text', // Variable name
            'footer_text', // Name
            '"footer_text"', // Expression
            '"footer_text"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"footer_text"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->footer_text->InputTextType = "text";
        $this->footer_text->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['footer_text'] = &$this->footer_text;

        // preview_image_path
        $this->preview_image_path = new DbField(
            $this, // Table
            'x_preview_image_path', // Variable name
            'preview_image_path', // Name
            '"preview_image_path"', // Expression
            '"preview_image_path"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"preview_image_path"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->preview_image_path->InputTextType = "text";
        $this->preview_image_path->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['preview_image_path'] = &$this->preview_image_path;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "document_templates";
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
                $queryBuilder->getSQL() . " RETURNING template_id",
                $queryBuilder->getParameters(),
                $queryBuilder->getParameterTypes()
            )->fetchOne();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->template_id->setDbValue($result);
            $rs['template_id'] = $this->template_id->DbValue;
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
            if (!isset($rs['template_id']) && !EmptyValue($this->template_id->CurrentValue)) {
                $rs['template_id'] = $this->template_id->CurrentValue;
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
            if (array_key_exists('template_id', $rs)) {
                AddFilter($where, QuotedName('template_id', $this->Dbid) . '=' . QuotedValue($rs['template_id'], $this->template_id->DataType, $this->Dbid));
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
        $this->template_id->DbValue = $row['template_id'];
        $this->template_name->DbValue = $row['template_name'];
        $this->template_code->DbValue = $row['template_code'];
        $this->category_id->DbValue = $row['category_id'];
        $this->description->DbValue = $row['description'];
        $this->html_content->DbValue = $row['html_content'];
        $this->is_active->DbValue = (ConvertToBool($row['is_active']) ? "1" : "0");
        $this->created_at->DbValue = $row['created_at'];
        $this->created_by->DbValue = $row['created_by'];
        $this->updated_at->DbValue = $row['updated_at'];
        $this->updated_by->DbValue = $row['updated_by'];
        $this->version->DbValue = $row['version'];
        $this->notary_required->DbValue = (ConvertToBool($row['notary_required']) ? "1" : "0");
        $this->fee_amount->DbValue = $row['fee_amount'];
        $this->approval_workflow->DbValue = $row['approval_workflow'];
        $this->template_type->DbValue = $row['template_type'];
        $this->header_text->DbValue = $row['header_text'];
        $this->footer_text->DbValue = $row['footer_text'];
        $this->preview_image_path->DbValue = $row['preview_image_path'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "\"template_id\" = @template_id@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->template_id->CurrentValue : $this->template_id->OldValue;
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
                $this->template_id->CurrentValue = $keys[0];
            } else {
                $this->template_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('template_id', $row) ? $row['template_id'] : null;
        } else {
            $val = !EmptyValue($this->template_id->OldValue) && !$current ? $this->template_id->OldValue : $this->template_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@template_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("DocumentTemplatesList");
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
            "DocumentTemplatesView" => $Language->phrase("View"),
            "DocumentTemplatesEdit" => $Language->phrase("Edit"),
            "DocumentTemplatesAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "DocumentTemplatesList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "DocumentTemplatesView",
            Config("API_ADD_ACTION") => "DocumentTemplatesAdd",
            Config("API_EDIT_ACTION") => "DocumentTemplatesEdit",
            Config("API_DELETE_ACTION") => "DocumentTemplatesDelete",
            Config("API_LIST_ACTION") => "DocumentTemplatesList",
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
        return "DocumentTemplatesList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("DocumentTemplatesView", $parm);
        } else {
            $url = $this->keyUrl("DocumentTemplatesView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "DocumentTemplatesAdd?" . $parm;
        } else {
            $url = "DocumentTemplatesAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("DocumentTemplatesEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("DocumentTemplatesList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("DocumentTemplatesAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("DocumentTemplatesList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("DocumentTemplatesDelete", $parm);
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
        $json .= "\"template_id\":" . VarToJson($this->template_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->template_id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->template_id->CurrentValue);
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
            if (($keyValue = Param("template_id") ?? Route("template_id")) !== null) {
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
                $this->template_id->CurrentValue = $key;
            } else {
                $this->template_id->OldValue = $key;
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
        $this->template_id->setDbValue($row['template_id']);
        $this->template_name->setDbValue($row['template_name']);
        $this->template_code->setDbValue($row['template_code']);
        $this->category_id->setDbValue($row['category_id']);
        $this->description->setDbValue($row['description']);
        $this->html_content->setDbValue($row['html_content']);
        $this->is_active->setDbValue(ConvertToBool($row['is_active']) ? "1" : "0");
        $this->created_at->setDbValue($row['created_at']);
        $this->created_by->setDbValue($row['created_by']);
        $this->updated_at->setDbValue($row['updated_at']);
        $this->updated_by->setDbValue($row['updated_by']);
        $this->version->setDbValue($row['version']);
        $this->notary_required->setDbValue(ConvertToBool($row['notary_required']) ? "1" : "0");
        $this->fee_amount->setDbValue($row['fee_amount']);
        $this->approval_workflow->setDbValue($row['approval_workflow']);
        $this->template_type->setDbValue($row['template_type']);
        $this->header_text->setDbValue($row['header_text']);
        $this->footer_text->setDbValue($row['footer_text']);
        $this->preview_image_path->setDbValue($row['preview_image_path']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "DocumentTemplatesList";
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

        // template_id

        // template_name

        // template_code

        // category_id

        // description

        // html_content

        // is_active

        // created_at

        // created_by

        // updated_at

        // updated_by

        // version

        // notary_required

        // fee_amount

        // approval_workflow

        // template_type

        // header_text

        // footer_text

        // preview_image_path

        // template_id
        $this->template_id->ViewValue = $this->template_id->CurrentValue;

        // template_name
        $this->template_name->ViewValue = $this->template_name->CurrentValue;

        // template_code
        $this->template_code->ViewValue = $this->template_code->CurrentValue;

        // category_id
        $this->category_id->ViewValue = $this->category_id->CurrentValue;
        $this->category_id->ViewValue = FormatNumber($this->category_id->ViewValue, $this->category_id->formatPattern());

        // description
        $this->description->ViewValue = $this->description->CurrentValue;

        // html_content
        $this->html_content->ViewValue = $this->html_content->CurrentValue;

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

        // version
        $this->version->ViewValue = $this->version->CurrentValue;
        $this->version->ViewValue = FormatNumber($this->version->ViewValue, $this->version->formatPattern());

        // notary_required
        if (ConvertToBool($this->notary_required->CurrentValue)) {
            $this->notary_required->ViewValue = $this->notary_required->tagCaption(1) != "" ? $this->notary_required->tagCaption(1) : "Yes";
        } else {
            $this->notary_required->ViewValue = $this->notary_required->tagCaption(2) != "" ? $this->notary_required->tagCaption(2) : "No";
        }

        // fee_amount
        $this->fee_amount->ViewValue = $this->fee_amount->CurrentValue;
        $this->fee_amount->ViewValue = FormatNumber($this->fee_amount->ViewValue, $this->fee_amount->formatPattern());

        // approval_workflow
        $this->approval_workflow->ViewValue = $this->approval_workflow->CurrentValue;

        // template_type
        $this->template_type->ViewValue = $this->template_type->CurrentValue;

        // header_text
        $this->header_text->ViewValue = $this->header_text->CurrentValue;

        // footer_text
        $this->footer_text->ViewValue = $this->footer_text->CurrentValue;

        // preview_image_path
        $this->preview_image_path->ViewValue = $this->preview_image_path->CurrentValue;

        // template_id
        $this->template_id->HrefValue = "";
        $this->template_id->TooltipValue = "";

        // template_name
        $this->template_name->HrefValue = "";
        $this->template_name->TooltipValue = "";

        // template_code
        $this->template_code->HrefValue = "";
        $this->template_code->TooltipValue = "";

        // category_id
        $this->category_id->HrefValue = "";
        $this->category_id->TooltipValue = "";

        // description
        $this->description->HrefValue = "";
        $this->description->TooltipValue = "";

        // html_content
        $this->html_content->HrefValue = "";
        $this->html_content->TooltipValue = "";

        // is_active
        $this->is_active->HrefValue = "";
        $this->is_active->TooltipValue = "";

        // created_at
        $this->created_at->HrefValue = "";
        $this->created_at->TooltipValue = "";

        // created_by
        $this->created_by->HrefValue = "";
        $this->created_by->TooltipValue = "";

        // updated_at
        $this->updated_at->HrefValue = "";
        $this->updated_at->TooltipValue = "";

        // updated_by
        $this->updated_by->HrefValue = "";
        $this->updated_by->TooltipValue = "";

        // version
        $this->version->HrefValue = "";
        $this->version->TooltipValue = "";

        // notary_required
        $this->notary_required->HrefValue = "";
        $this->notary_required->TooltipValue = "";

        // fee_amount
        $this->fee_amount->HrefValue = "";
        $this->fee_amount->TooltipValue = "";

        // approval_workflow
        $this->approval_workflow->HrefValue = "";
        $this->approval_workflow->TooltipValue = "";

        // template_type
        $this->template_type->HrefValue = "";
        $this->template_type->TooltipValue = "";

        // header_text
        $this->header_text->HrefValue = "";
        $this->header_text->TooltipValue = "";

        // footer_text
        $this->footer_text->HrefValue = "";
        $this->footer_text->TooltipValue = "";

        // preview_image_path
        $this->preview_image_path->HrefValue = "";
        $this->preview_image_path->TooltipValue = "";

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

        // template_id
        $this->template_id->setupEditAttributes();
        $this->template_id->EditValue = $this->template_id->CurrentValue;

        // template_name
        $this->template_name->setupEditAttributes();
        if (!$this->template_name->Raw) {
            $this->template_name->CurrentValue = HtmlDecode($this->template_name->CurrentValue);
        }
        $this->template_name->EditValue = $this->template_name->CurrentValue;
        $this->template_name->PlaceHolder = RemoveHtml($this->template_name->caption());

        // template_code
        $this->template_code->setupEditAttributes();
        if (!$this->template_code->Raw) {
            $this->template_code->CurrentValue = HtmlDecode($this->template_code->CurrentValue);
        }
        $this->template_code->EditValue = $this->template_code->CurrentValue;
        $this->template_code->PlaceHolder = RemoveHtml($this->template_code->caption());

        // category_id
        $this->category_id->setupEditAttributes();
        $this->category_id->EditValue = $this->category_id->CurrentValue;
        $this->category_id->PlaceHolder = RemoveHtml($this->category_id->caption());
        if (strval($this->category_id->EditValue) != "" && is_numeric($this->category_id->EditValue)) {
            $this->category_id->EditValue = FormatNumber($this->category_id->EditValue, null);
        }

        // description
        $this->description->setupEditAttributes();
        $this->description->EditValue = $this->description->CurrentValue;
        $this->description->PlaceHolder = RemoveHtml($this->description->caption());

        // html_content
        $this->html_content->setupEditAttributes();
        $this->html_content->EditValue = $this->html_content->CurrentValue;
        $this->html_content->PlaceHolder = RemoveHtml($this->html_content->caption());

        // is_active
        $this->is_active->EditValue = $this->is_active->options(false);
        $this->is_active->PlaceHolder = RemoveHtml($this->is_active->caption());

        // created_at
        $this->created_at->setupEditAttributes();
        $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
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
        $this->updated_at->EditValue = FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

        // updated_by
        $this->updated_by->setupEditAttributes();
        $this->updated_by->EditValue = $this->updated_by->CurrentValue;
        $this->updated_by->PlaceHolder = RemoveHtml($this->updated_by->caption());
        if (strval($this->updated_by->EditValue) != "" && is_numeric($this->updated_by->EditValue)) {
            $this->updated_by->EditValue = FormatNumber($this->updated_by->EditValue, null);
        }

        // version
        $this->version->setupEditAttributes();
        $this->version->EditValue = $this->version->CurrentValue;
        $this->version->PlaceHolder = RemoveHtml($this->version->caption());
        if (strval($this->version->EditValue) != "" && is_numeric($this->version->EditValue)) {
            $this->version->EditValue = FormatNumber($this->version->EditValue, null);
        }

        // notary_required
        $this->notary_required->EditValue = $this->notary_required->options(false);
        $this->notary_required->PlaceHolder = RemoveHtml($this->notary_required->caption());

        // fee_amount
        $this->fee_amount->setupEditAttributes();
        $this->fee_amount->EditValue = $this->fee_amount->CurrentValue;
        $this->fee_amount->PlaceHolder = RemoveHtml($this->fee_amount->caption());
        if (strval($this->fee_amount->EditValue) != "" && is_numeric($this->fee_amount->EditValue)) {
            $this->fee_amount->EditValue = FormatNumber($this->fee_amount->EditValue, null);
        }

        // approval_workflow
        $this->approval_workflow->setupEditAttributes();
        $this->approval_workflow->EditValue = $this->approval_workflow->CurrentValue;
        $this->approval_workflow->PlaceHolder = RemoveHtml($this->approval_workflow->caption());

        // template_type
        $this->template_type->setupEditAttributes();
        if (!$this->template_type->Raw) {
            $this->template_type->CurrentValue = HtmlDecode($this->template_type->CurrentValue);
        }
        $this->template_type->EditValue = $this->template_type->CurrentValue;
        $this->template_type->PlaceHolder = RemoveHtml($this->template_type->caption());

        // header_text
        $this->header_text->setupEditAttributes();
        $this->header_text->EditValue = $this->header_text->CurrentValue;
        $this->header_text->PlaceHolder = RemoveHtml($this->header_text->caption());

        // footer_text
        $this->footer_text->setupEditAttributes();
        $this->footer_text->EditValue = $this->footer_text->CurrentValue;
        $this->footer_text->PlaceHolder = RemoveHtml($this->footer_text->caption());

        // preview_image_path
        $this->preview_image_path->setupEditAttributes();
        if (!$this->preview_image_path->Raw) {
            $this->preview_image_path->CurrentValue = HtmlDecode($this->preview_image_path->CurrentValue);
        }
        $this->preview_image_path->EditValue = $this->preview_image_path->CurrentValue;
        $this->preview_image_path->PlaceHolder = RemoveHtml($this->preview_image_path->caption());

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
                    $doc->exportCaption($this->template_id);
                    $doc->exportCaption($this->template_name);
                    $doc->exportCaption($this->template_code);
                    $doc->exportCaption($this->category_id);
                    $doc->exportCaption($this->description);
                    $doc->exportCaption($this->html_content);
                    $doc->exportCaption($this->is_active);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->created_by);
                    $doc->exportCaption($this->updated_at);
                    $doc->exportCaption($this->updated_by);
                    $doc->exportCaption($this->version);
                    $doc->exportCaption($this->notary_required);
                    $doc->exportCaption($this->fee_amount);
                    $doc->exportCaption($this->approval_workflow);
                    $doc->exportCaption($this->template_type);
                    $doc->exportCaption($this->header_text);
                    $doc->exportCaption($this->footer_text);
                    $doc->exportCaption($this->preview_image_path);
                } else {
                    $doc->exportCaption($this->template_id);
                    $doc->exportCaption($this->template_name);
                    $doc->exportCaption($this->template_code);
                    $doc->exportCaption($this->category_id);
                    $doc->exportCaption($this->is_active);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->created_by);
                    $doc->exportCaption($this->updated_at);
                    $doc->exportCaption($this->updated_by);
                    $doc->exportCaption($this->version);
                    $doc->exportCaption($this->notary_required);
                    $doc->exportCaption($this->fee_amount);
                    $doc->exportCaption($this->template_type);
                    $doc->exportCaption($this->preview_image_path);
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
                        $doc->exportField($this->template_id);
                        $doc->exportField($this->template_name);
                        $doc->exportField($this->template_code);
                        $doc->exportField($this->category_id);
                        $doc->exportField($this->description);
                        $doc->exportField($this->html_content);
                        $doc->exportField($this->is_active);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->created_by);
                        $doc->exportField($this->updated_at);
                        $doc->exportField($this->updated_by);
                        $doc->exportField($this->version);
                        $doc->exportField($this->notary_required);
                        $doc->exportField($this->fee_amount);
                        $doc->exportField($this->approval_workflow);
                        $doc->exportField($this->template_type);
                        $doc->exportField($this->header_text);
                        $doc->exportField($this->footer_text);
                        $doc->exportField($this->preview_image_path);
                    } else {
                        $doc->exportField($this->template_id);
                        $doc->exportField($this->template_name);
                        $doc->exportField($this->template_code);
                        $doc->exportField($this->category_id);
                        $doc->exportField($this->is_active);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->created_by);
                        $doc->exportField($this->updated_at);
                        $doc->exportField($this->updated_by);
                        $doc->exportField($this->version);
                        $doc->exportField($this->notary_required);
                        $doc->exportField($this->fee_amount);
                        $doc->exportField($this->template_type);
                        $doc->exportField($this->preview_image_path);
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
