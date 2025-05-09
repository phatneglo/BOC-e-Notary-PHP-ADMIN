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
 * Table class for template_fields
 */
class TemplateFields extends DbTable
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
    public $field_id;
    public $template_id;
    public $field_name;
    public $field_label;
    public $field_type;
    public $field_options;
    public $is_required;
    public $placeholder;
    public $default_value;
    public $field_order;
    public $validation_rules;
    public $help_text;
    public $field_width;
    public $is_visible;
    public $section_name;
    public $x_position;
    public $y_position;
    public $group_name;
    public $conditional_display;
    public $created_at;
    public $section_id;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "template_fields";
        $this->TableName = 'template_fields';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "template_fields";
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

        // field_id
        $this->field_id = new DbField(
            $this, // Table
            'x_field_id', // Variable name
            'field_id', // Name
            '"field_id"', // Expression
            'CAST("field_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"field_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->field_id->InputTextType = "text";
        $this->field_id->Raw = true;
        $this->field_id->IsAutoIncrement = true; // Autoincrement field
        $this->field_id->IsPrimaryKey = true; // Primary key field
        $this->field_id->Nullable = false; // NOT NULL field
        $this->field_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->field_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['field_id'] = &$this->field_id;

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

        // field_name
        $this->field_name = new DbField(
            $this, // Table
            'x_field_name', // Variable name
            'field_name', // Name
            '"field_name"', // Expression
            '"field_name"', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"field_name"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->field_name->InputTextType = "text";
        $this->field_name->Nullable = false; // NOT NULL field
        $this->field_name->Required = true; // Required field
        $this->field_name->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['field_name'] = &$this->field_name;

        // field_label
        $this->field_label = new DbField(
            $this, // Table
            'x_field_label', // Variable name
            'field_label', // Name
            '"field_label"', // Expression
            '"field_label"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"field_label"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->field_label->InputTextType = "text";
        $this->field_label->Nullable = false; // NOT NULL field
        $this->field_label->Required = true; // Required field
        $this->field_label->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['field_label'] = &$this->field_label;

        // field_type
        $this->field_type = new DbField(
            $this, // Table
            'x_field_type', // Variable name
            'field_type', // Name
            '"field_type"', // Expression
            '"field_type"', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"field_type"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->field_type->InputTextType = "text";
        $this->field_type->Nullable = false; // NOT NULL field
        $this->field_type->Required = true; // Required field
        $this->field_type->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['field_type'] = &$this->field_type;

        // field_options
        $this->field_options = new DbField(
            $this, // Table
            'x_field_options', // Variable name
            'field_options', // Name
            '"field_options"', // Expression
            '"field_options"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"field_options"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->field_options->InputTextType = "text";
        $this->field_options->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['field_options'] = &$this->field_options;

        // is_required
        $this->is_required = new DbField(
            $this, // Table
            'x_is_required', // Variable name
            'is_required', // Name
            '"is_required"', // Expression
            'CAST("is_required" AS varchar(255))', // Basic search expression
            11, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"is_required"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->is_required->InputTextType = "text";
        $this->is_required->Raw = true;
        $this->is_required->setDataType(DataType::BOOLEAN);
        $this->is_required->Lookup = new Lookup($this->is_required, 'template_fields', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->is_required->OptionCount = 2;
        $this->is_required->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['is_required'] = &$this->is_required;

        // placeholder
        $this->placeholder = new DbField(
            $this, // Table
            'x_placeholder', // Variable name
            'placeholder', // Name
            '"placeholder"', // Expression
            '"placeholder"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"placeholder"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->placeholder->InputTextType = "text";
        $this->placeholder->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['placeholder'] = &$this->placeholder;

        // default_value
        $this->default_value = new DbField(
            $this, // Table
            'x_default_value', // Variable name
            'default_value', // Name
            '"default_value"', // Expression
            '"default_value"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"default_value"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->default_value->InputTextType = "text";
        $this->default_value->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['default_value'] = &$this->default_value;

        // field_order
        $this->field_order = new DbField(
            $this, // Table
            'x_field_order', // Variable name
            'field_order', // Name
            '"field_order"', // Expression
            'CAST("field_order" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"field_order"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->field_order->InputTextType = "text";
        $this->field_order->Raw = true;
        $this->field_order->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->field_order->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['field_order'] = &$this->field_order;

        // validation_rules
        $this->validation_rules = new DbField(
            $this, // Table
            'x_validation_rules', // Variable name
            'validation_rules', // Name
            '"validation_rules"', // Expression
            '"validation_rules"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"validation_rules"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->validation_rules->InputTextType = "text";
        $this->validation_rules->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['validation_rules'] = &$this->validation_rules;

        // help_text
        $this->help_text = new DbField(
            $this, // Table
            'x_help_text', // Variable name
            'help_text', // Name
            '"help_text"', // Expression
            '"help_text"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"help_text"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->help_text->InputTextType = "text";
        $this->help_text->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['help_text'] = &$this->help_text;

        // field_width
        $this->field_width = new DbField(
            $this, // Table
            'x_field_width', // Variable name
            'field_width', // Name
            '"field_width"', // Expression
            '"field_width"', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"field_width"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->field_width->addMethod("getDefault", fn() => "full");
        $this->field_width->InputTextType = "text";
        $this->field_width->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['field_width'] = &$this->field_width;

        // is_visible
        $this->is_visible = new DbField(
            $this, // Table
            'x_is_visible', // Variable name
            'is_visible', // Name
            '"is_visible"', // Expression
            'CAST("is_visible" AS varchar(255))', // Basic search expression
            11, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"is_visible"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->is_visible->InputTextType = "text";
        $this->is_visible->Raw = true;
        $this->is_visible->setDataType(DataType::BOOLEAN);
        $this->is_visible->Lookup = new Lookup($this->is_visible, 'template_fields', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->is_visible->OptionCount = 2;
        $this->is_visible->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['is_visible'] = &$this->is_visible;

        // section_name
        $this->section_name = new DbField(
            $this, // Table
            'x_section_name', // Variable name
            'section_name', // Name
            '"section_name"', // Expression
            '"section_name"', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"section_name"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->section_name->InputTextType = "text";
        $this->section_name->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['section_name'] = &$this->section_name;

        // x_position
        $this->x_position = new DbField(
            $this, // Table
            'x_x_position', // Variable name
            'x_position', // Name
            '"x_position"', // Expression
            'CAST("x_position" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"x_position"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->x_position->InputTextType = "text";
        $this->x_position->Raw = true;
        $this->x_position->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->x_position->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['x_position'] = &$this->x_position;

        // y_position
        $this->y_position = new DbField(
            $this, // Table
            'x_y_position', // Variable name
            'y_position', // Name
            '"y_position"', // Expression
            'CAST("y_position" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"y_position"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->y_position->InputTextType = "text";
        $this->y_position->Raw = true;
        $this->y_position->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->y_position->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['y_position'] = &$this->y_position;

        // group_name
        $this->group_name = new DbField(
            $this, // Table
            'x_group_name', // Variable name
            'group_name', // Name
            '"group_name"', // Expression
            '"group_name"', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"group_name"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->group_name->InputTextType = "text";
        $this->group_name->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['group_name'] = &$this->group_name;

        // conditional_display
        $this->conditional_display = new DbField(
            $this, // Table
            'x_conditional_display', // Variable name
            'conditional_display', // Name
            '"conditional_display"', // Expression
            'CAST("conditional_display" AS varchar(255))', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"conditional_display"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->conditional_display->InputTextType = "text";
        $this->conditional_display->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['conditional_display'] = &$this->conditional_display;

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

        // section_id
        $this->section_id = new DbField(
            $this, // Table
            'x_section_id', // Variable name
            'section_id', // Name
            '"section_id"', // Expression
            'CAST("section_id" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"section_id"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->section_id->InputTextType = "text";
        $this->section_id->Raw = true;
        $this->section_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->section_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['section_id'] = &$this->section_id;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "template_fields";
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
                $queryBuilder->getSQL() . " RETURNING field_id",
                $queryBuilder->getParameters(),
                $queryBuilder->getParameterTypes()
            )->fetchOne();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->field_id->setDbValue($result);
            $rs['field_id'] = $this->field_id->DbValue;
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
            if (!isset($rs['field_id']) && !EmptyValue($this->field_id->CurrentValue)) {
                $rs['field_id'] = $this->field_id->CurrentValue;
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
            if (array_key_exists('field_id', $rs)) {
                AddFilter($where, QuotedName('field_id', $this->Dbid) . '=' . QuotedValue($rs['field_id'], $this->field_id->DataType, $this->Dbid));
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
        $this->field_id->DbValue = $row['field_id'];
        $this->template_id->DbValue = $row['template_id'];
        $this->field_name->DbValue = $row['field_name'];
        $this->field_label->DbValue = $row['field_label'];
        $this->field_type->DbValue = $row['field_type'];
        $this->field_options->DbValue = $row['field_options'];
        $this->is_required->DbValue = (ConvertToBool($row['is_required']) ? "1" : "0");
        $this->placeholder->DbValue = $row['placeholder'];
        $this->default_value->DbValue = $row['default_value'];
        $this->field_order->DbValue = $row['field_order'];
        $this->validation_rules->DbValue = $row['validation_rules'];
        $this->help_text->DbValue = $row['help_text'];
        $this->field_width->DbValue = $row['field_width'];
        $this->is_visible->DbValue = (ConvertToBool($row['is_visible']) ? "1" : "0");
        $this->section_name->DbValue = $row['section_name'];
        $this->x_position->DbValue = $row['x_position'];
        $this->y_position->DbValue = $row['y_position'];
        $this->group_name->DbValue = $row['group_name'];
        $this->conditional_display->DbValue = $row['conditional_display'];
        $this->created_at->DbValue = $row['created_at'];
        $this->section_id->DbValue = $row['section_id'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "\"field_id\" = @field_id@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->field_id->CurrentValue : $this->field_id->OldValue;
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
                $this->field_id->CurrentValue = $keys[0];
            } else {
                $this->field_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('field_id', $row) ? $row['field_id'] : null;
        } else {
            $val = !EmptyValue($this->field_id->OldValue) && !$current ? $this->field_id->OldValue : $this->field_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@field_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("TemplateFieldsList");
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
            "TemplateFieldsView" => $Language->phrase("View"),
            "TemplateFieldsEdit" => $Language->phrase("Edit"),
            "TemplateFieldsAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "TemplateFieldsList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "TemplateFieldsView",
            Config("API_ADD_ACTION") => "TemplateFieldsAdd",
            Config("API_EDIT_ACTION") => "TemplateFieldsEdit",
            Config("API_DELETE_ACTION") => "TemplateFieldsDelete",
            Config("API_LIST_ACTION") => "TemplateFieldsList",
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
        return "TemplateFieldsList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("TemplateFieldsView", $parm);
        } else {
            $url = $this->keyUrl("TemplateFieldsView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "TemplateFieldsAdd?" . $parm;
        } else {
            $url = "TemplateFieldsAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("TemplateFieldsEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("TemplateFieldsList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("TemplateFieldsAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("TemplateFieldsList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("TemplateFieldsDelete", $parm);
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
        $json .= "\"field_id\":" . VarToJson($this->field_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->field_id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->field_id->CurrentValue);
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
            if (($keyValue = Param("field_id") ?? Route("field_id")) !== null) {
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
                $this->field_id->CurrentValue = $key;
            } else {
                $this->field_id->OldValue = $key;
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
        $this->field_id->setDbValue($row['field_id']);
        $this->template_id->setDbValue($row['template_id']);
        $this->field_name->setDbValue($row['field_name']);
        $this->field_label->setDbValue($row['field_label']);
        $this->field_type->setDbValue($row['field_type']);
        $this->field_options->setDbValue($row['field_options']);
        $this->is_required->setDbValue(ConvertToBool($row['is_required']) ? "1" : "0");
        $this->placeholder->setDbValue($row['placeholder']);
        $this->default_value->setDbValue($row['default_value']);
        $this->field_order->setDbValue($row['field_order']);
        $this->validation_rules->setDbValue($row['validation_rules']);
        $this->help_text->setDbValue($row['help_text']);
        $this->field_width->setDbValue($row['field_width']);
        $this->is_visible->setDbValue(ConvertToBool($row['is_visible']) ? "1" : "0");
        $this->section_name->setDbValue($row['section_name']);
        $this->x_position->setDbValue($row['x_position']);
        $this->y_position->setDbValue($row['y_position']);
        $this->group_name->setDbValue($row['group_name']);
        $this->conditional_display->setDbValue($row['conditional_display']);
        $this->created_at->setDbValue($row['created_at']);
        $this->section_id->setDbValue($row['section_id']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "TemplateFieldsList";
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

        // field_id

        // template_id

        // field_name

        // field_label

        // field_type

        // field_options

        // is_required

        // placeholder

        // default_value

        // field_order

        // validation_rules

        // help_text

        // field_width

        // is_visible

        // section_name

        // x_position

        // y_position

        // group_name

        // conditional_display

        // created_at

        // section_id

        // field_id
        $this->field_id->ViewValue = $this->field_id->CurrentValue;

        // template_id
        $this->template_id->ViewValue = $this->template_id->CurrentValue;
        $this->template_id->ViewValue = FormatNumber($this->template_id->ViewValue, $this->template_id->formatPattern());

        // field_name
        $this->field_name->ViewValue = $this->field_name->CurrentValue;

        // field_label
        $this->field_label->ViewValue = $this->field_label->CurrentValue;

        // field_type
        $this->field_type->ViewValue = $this->field_type->CurrentValue;

        // field_options
        $this->field_options->ViewValue = $this->field_options->CurrentValue;

        // is_required
        if (ConvertToBool($this->is_required->CurrentValue)) {
            $this->is_required->ViewValue = $this->is_required->tagCaption(1) != "" ? $this->is_required->tagCaption(1) : "Yes";
        } else {
            $this->is_required->ViewValue = $this->is_required->tagCaption(2) != "" ? $this->is_required->tagCaption(2) : "No";
        }

        // placeholder
        $this->placeholder->ViewValue = $this->placeholder->CurrentValue;

        // default_value
        $this->default_value->ViewValue = $this->default_value->CurrentValue;

        // field_order
        $this->field_order->ViewValue = $this->field_order->CurrentValue;
        $this->field_order->ViewValue = FormatNumber($this->field_order->ViewValue, $this->field_order->formatPattern());

        // validation_rules
        $this->validation_rules->ViewValue = $this->validation_rules->CurrentValue;

        // help_text
        $this->help_text->ViewValue = $this->help_text->CurrentValue;

        // field_width
        $this->field_width->ViewValue = $this->field_width->CurrentValue;

        // is_visible
        if (ConvertToBool($this->is_visible->CurrentValue)) {
            $this->is_visible->ViewValue = $this->is_visible->tagCaption(1) != "" ? $this->is_visible->tagCaption(1) : "Yes";
        } else {
            $this->is_visible->ViewValue = $this->is_visible->tagCaption(2) != "" ? $this->is_visible->tagCaption(2) : "No";
        }

        // section_name
        $this->section_name->ViewValue = $this->section_name->CurrentValue;

        // x_position
        $this->x_position->ViewValue = $this->x_position->CurrentValue;
        $this->x_position->ViewValue = FormatNumber($this->x_position->ViewValue, $this->x_position->formatPattern());

        // y_position
        $this->y_position->ViewValue = $this->y_position->CurrentValue;
        $this->y_position->ViewValue = FormatNumber($this->y_position->ViewValue, $this->y_position->formatPattern());

        // group_name
        $this->group_name->ViewValue = $this->group_name->CurrentValue;

        // conditional_display
        $this->conditional_display->ViewValue = $this->conditional_display->CurrentValue;

        // created_at
        $this->created_at->ViewValue = $this->created_at->CurrentValue;
        $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

        // section_id
        $this->section_id->ViewValue = $this->section_id->CurrentValue;
        $this->section_id->ViewValue = FormatNumber($this->section_id->ViewValue, $this->section_id->formatPattern());

        // field_id
        $this->field_id->HrefValue = "";
        $this->field_id->TooltipValue = "";

        // template_id
        $this->template_id->HrefValue = "";
        $this->template_id->TooltipValue = "";

        // field_name
        $this->field_name->HrefValue = "";
        $this->field_name->TooltipValue = "";

        // field_label
        $this->field_label->HrefValue = "";
        $this->field_label->TooltipValue = "";

        // field_type
        $this->field_type->HrefValue = "";
        $this->field_type->TooltipValue = "";

        // field_options
        $this->field_options->HrefValue = "";
        $this->field_options->TooltipValue = "";

        // is_required
        $this->is_required->HrefValue = "";
        $this->is_required->TooltipValue = "";

        // placeholder
        $this->placeholder->HrefValue = "";
        $this->placeholder->TooltipValue = "";

        // default_value
        $this->default_value->HrefValue = "";
        $this->default_value->TooltipValue = "";

        // field_order
        $this->field_order->HrefValue = "";
        $this->field_order->TooltipValue = "";

        // validation_rules
        $this->validation_rules->HrefValue = "";
        $this->validation_rules->TooltipValue = "";

        // help_text
        $this->help_text->HrefValue = "";
        $this->help_text->TooltipValue = "";

        // field_width
        $this->field_width->HrefValue = "";
        $this->field_width->TooltipValue = "";

        // is_visible
        $this->is_visible->HrefValue = "";
        $this->is_visible->TooltipValue = "";

        // section_name
        $this->section_name->HrefValue = "";
        $this->section_name->TooltipValue = "";

        // x_position
        $this->x_position->HrefValue = "";
        $this->x_position->TooltipValue = "";

        // y_position
        $this->y_position->HrefValue = "";
        $this->y_position->TooltipValue = "";

        // group_name
        $this->group_name->HrefValue = "";
        $this->group_name->TooltipValue = "";

        // conditional_display
        $this->conditional_display->HrefValue = "";
        $this->conditional_display->TooltipValue = "";

        // created_at
        $this->created_at->HrefValue = "";
        $this->created_at->TooltipValue = "";

        // section_id
        $this->section_id->HrefValue = "";
        $this->section_id->TooltipValue = "";

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

        // field_id
        $this->field_id->setupEditAttributes();
        $this->field_id->EditValue = $this->field_id->CurrentValue;

        // template_id
        $this->template_id->setupEditAttributes();
        $this->template_id->EditValue = $this->template_id->CurrentValue;
        $this->template_id->PlaceHolder = RemoveHtml($this->template_id->caption());
        if (strval($this->template_id->EditValue) != "" && is_numeric($this->template_id->EditValue)) {
            $this->template_id->EditValue = FormatNumber($this->template_id->EditValue, null);
        }

        // field_name
        $this->field_name->setupEditAttributes();
        if (!$this->field_name->Raw) {
            $this->field_name->CurrentValue = HtmlDecode($this->field_name->CurrentValue);
        }
        $this->field_name->EditValue = $this->field_name->CurrentValue;
        $this->field_name->PlaceHolder = RemoveHtml($this->field_name->caption());

        // field_label
        $this->field_label->setupEditAttributes();
        if (!$this->field_label->Raw) {
            $this->field_label->CurrentValue = HtmlDecode($this->field_label->CurrentValue);
        }
        $this->field_label->EditValue = $this->field_label->CurrentValue;
        $this->field_label->PlaceHolder = RemoveHtml($this->field_label->caption());

        // field_type
        $this->field_type->setupEditAttributes();
        if (!$this->field_type->Raw) {
            $this->field_type->CurrentValue = HtmlDecode($this->field_type->CurrentValue);
        }
        $this->field_type->EditValue = $this->field_type->CurrentValue;
        $this->field_type->PlaceHolder = RemoveHtml($this->field_type->caption());

        // field_options
        $this->field_options->setupEditAttributes();
        $this->field_options->EditValue = $this->field_options->CurrentValue;
        $this->field_options->PlaceHolder = RemoveHtml($this->field_options->caption());

        // is_required
        $this->is_required->EditValue = $this->is_required->options(false);
        $this->is_required->PlaceHolder = RemoveHtml($this->is_required->caption());

        // placeholder
        $this->placeholder->setupEditAttributes();
        $this->placeholder->EditValue = $this->placeholder->CurrentValue;
        $this->placeholder->PlaceHolder = RemoveHtml($this->placeholder->caption());

        // default_value
        $this->default_value->setupEditAttributes();
        $this->default_value->EditValue = $this->default_value->CurrentValue;
        $this->default_value->PlaceHolder = RemoveHtml($this->default_value->caption());

        // field_order
        $this->field_order->setupEditAttributes();
        $this->field_order->EditValue = $this->field_order->CurrentValue;
        $this->field_order->PlaceHolder = RemoveHtml($this->field_order->caption());
        if (strval($this->field_order->EditValue) != "" && is_numeric($this->field_order->EditValue)) {
            $this->field_order->EditValue = FormatNumber($this->field_order->EditValue, null);
        }

        // validation_rules
        $this->validation_rules->setupEditAttributes();
        $this->validation_rules->EditValue = $this->validation_rules->CurrentValue;
        $this->validation_rules->PlaceHolder = RemoveHtml($this->validation_rules->caption());

        // help_text
        $this->help_text->setupEditAttributes();
        $this->help_text->EditValue = $this->help_text->CurrentValue;
        $this->help_text->PlaceHolder = RemoveHtml($this->help_text->caption());

        // field_width
        $this->field_width->setupEditAttributes();
        if (!$this->field_width->Raw) {
            $this->field_width->CurrentValue = HtmlDecode($this->field_width->CurrentValue);
        }
        $this->field_width->EditValue = $this->field_width->CurrentValue;
        $this->field_width->PlaceHolder = RemoveHtml($this->field_width->caption());

        // is_visible
        $this->is_visible->EditValue = $this->is_visible->options(false);
        $this->is_visible->PlaceHolder = RemoveHtml($this->is_visible->caption());

        // section_name
        $this->section_name->setupEditAttributes();
        if (!$this->section_name->Raw) {
            $this->section_name->CurrentValue = HtmlDecode($this->section_name->CurrentValue);
        }
        $this->section_name->EditValue = $this->section_name->CurrentValue;
        $this->section_name->PlaceHolder = RemoveHtml($this->section_name->caption());

        // x_position
        $this->x_position->setupEditAttributes();
        $this->x_position->EditValue = $this->x_position->CurrentValue;
        $this->x_position->PlaceHolder = RemoveHtml($this->x_position->caption());
        if (strval($this->x_position->EditValue) != "" && is_numeric($this->x_position->EditValue)) {
            $this->x_position->EditValue = FormatNumber($this->x_position->EditValue, null);
        }

        // y_position
        $this->y_position->setupEditAttributes();
        $this->y_position->EditValue = $this->y_position->CurrentValue;
        $this->y_position->PlaceHolder = RemoveHtml($this->y_position->caption());
        if (strval($this->y_position->EditValue) != "" && is_numeric($this->y_position->EditValue)) {
            $this->y_position->EditValue = FormatNumber($this->y_position->EditValue, null);
        }

        // group_name
        $this->group_name->setupEditAttributes();
        if (!$this->group_name->Raw) {
            $this->group_name->CurrentValue = HtmlDecode($this->group_name->CurrentValue);
        }
        $this->group_name->EditValue = $this->group_name->CurrentValue;
        $this->group_name->PlaceHolder = RemoveHtml($this->group_name->caption());

        // conditional_display
        $this->conditional_display->setupEditAttributes();
        $this->conditional_display->EditValue = $this->conditional_display->CurrentValue;
        $this->conditional_display->PlaceHolder = RemoveHtml($this->conditional_display->caption());

        // created_at
        $this->created_at->setupEditAttributes();
        $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

        // section_id
        $this->section_id->setupEditAttributes();
        $this->section_id->EditValue = $this->section_id->CurrentValue;
        $this->section_id->PlaceHolder = RemoveHtml($this->section_id->caption());
        if (strval($this->section_id->EditValue) != "" && is_numeric($this->section_id->EditValue)) {
            $this->section_id->EditValue = FormatNumber($this->section_id->EditValue, null);
        }

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
                    $doc->exportCaption($this->field_id);
                    $doc->exportCaption($this->template_id);
                    $doc->exportCaption($this->field_name);
                    $doc->exportCaption($this->field_label);
                    $doc->exportCaption($this->field_type);
                    $doc->exportCaption($this->field_options);
                    $doc->exportCaption($this->is_required);
                    $doc->exportCaption($this->placeholder);
                    $doc->exportCaption($this->default_value);
                    $doc->exportCaption($this->field_order);
                    $doc->exportCaption($this->validation_rules);
                    $doc->exportCaption($this->help_text);
                    $doc->exportCaption($this->field_width);
                    $doc->exportCaption($this->is_visible);
                    $doc->exportCaption($this->section_name);
                    $doc->exportCaption($this->x_position);
                    $doc->exportCaption($this->y_position);
                    $doc->exportCaption($this->group_name);
                    $doc->exportCaption($this->conditional_display);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->section_id);
                } else {
                    $doc->exportCaption($this->field_id);
                    $doc->exportCaption($this->template_id);
                    $doc->exportCaption($this->field_name);
                    $doc->exportCaption($this->field_label);
                    $doc->exportCaption($this->field_type);
                    $doc->exportCaption($this->is_required);
                    $doc->exportCaption($this->field_order);
                    $doc->exportCaption($this->field_width);
                    $doc->exportCaption($this->is_visible);
                    $doc->exportCaption($this->section_name);
                    $doc->exportCaption($this->x_position);
                    $doc->exportCaption($this->y_position);
                    $doc->exportCaption($this->group_name);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->section_id);
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
                        $doc->exportField($this->field_id);
                        $doc->exportField($this->template_id);
                        $doc->exportField($this->field_name);
                        $doc->exportField($this->field_label);
                        $doc->exportField($this->field_type);
                        $doc->exportField($this->field_options);
                        $doc->exportField($this->is_required);
                        $doc->exportField($this->placeholder);
                        $doc->exportField($this->default_value);
                        $doc->exportField($this->field_order);
                        $doc->exportField($this->validation_rules);
                        $doc->exportField($this->help_text);
                        $doc->exportField($this->field_width);
                        $doc->exportField($this->is_visible);
                        $doc->exportField($this->section_name);
                        $doc->exportField($this->x_position);
                        $doc->exportField($this->y_position);
                        $doc->exportField($this->group_name);
                        $doc->exportField($this->conditional_display);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->section_id);
                    } else {
                        $doc->exportField($this->field_id);
                        $doc->exportField($this->template_id);
                        $doc->exportField($this->field_name);
                        $doc->exportField($this->field_label);
                        $doc->exportField($this->field_type);
                        $doc->exportField($this->is_required);
                        $doc->exportField($this->field_order);
                        $doc->exportField($this->field_width);
                        $doc->exportField($this->is_visible);
                        $doc->exportField($this->section_name);
                        $doc->exportField($this->x_position);
                        $doc->exportField($this->y_position);
                        $doc->exportField($this->group_name);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->section_id);
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
