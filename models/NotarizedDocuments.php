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
 * Table class for notarized_documents
 */
class NotarizedDocuments extends DbTable
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
    public $notarized_id;
    public $request_id;
    public $document_id;
    public $notary_id;
    public $document_number;
    public $page_number;
    public $book_number;
    public $series_of;
    public $doc_keycode;
    public $notary_location;
    public $notarization_date;
    public $digital_signature;
    public $digital_seal;
    public $certificate_text;
    public $certificate_type;
    public $qr_code_path;
    public $notarized_document_path;
    public $expires_at;
    public $revoked;
    public $revoked_at;
    public $revoked_by;
    public $revocation_reason;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "notarized_documents";
        $this->TableName = 'notarized_documents';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "notarized_documents";
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
            'NO' // Edit Tag
        );
        $this->notarized_id->InputTextType = "text";
        $this->notarized_id->Raw = true;
        $this->notarized_id->IsAutoIncrement = true; // Autoincrement field
        $this->notarized_id->IsPrimaryKey = true; // Primary key field
        $this->notarized_id->Nullable = false; // NOT NULL field
        $this->notarized_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->notarized_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['notarized_id'] = &$this->notarized_id;

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
        $this->notary_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->notary_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['notary_id'] = &$this->notary_id;

        // document_number
        $this->document_number = new DbField(
            $this, // Table
            'x_document_number', // Variable name
            'document_number', // Name
            '"document_number"', // Expression
            '"document_number"', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"document_number"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->document_number->InputTextType = "text";
        $this->document_number->Nullable = false; // NOT NULL field
        $this->document_number->Required = true; // Required field
        $this->document_number->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['document_number'] = &$this->document_number;

        // page_number
        $this->page_number = new DbField(
            $this, // Table
            'x_page_number', // Variable name
            'page_number', // Name
            '"page_number"', // Expression
            'CAST("page_number" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"page_number"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->page_number->InputTextType = "text";
        $this->page_number->Raw = true;
        $this->page_number->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->page_number->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['page_number'] = &$this->page_number;

        // book_number
        $this->book_number = new DbField(
            $this, // Table
            'x_book_number', // Variable name
            'book_number', // Name
            '"book_number"', // Expression
            '"book_number"', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"book_number"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->book_number->InputTextType = "text";
        $this->book_number->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['book_number'] = &$this->book_number;

        // series_of
        $this->series_of = new DbField(
            $this, // Table
            'x_series_of', // Variable name
            'series_of', // Name
            '"series_of"', // Expression
            '"series_of"', // Basic search expression
            200, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"series_of"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->series_of->InputTextType = "text";
        $this->series_of->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['series_of'] = &$this->series_of;

        // doc_keycode
        $this->doc_keycode = new DbField(
            $this, // Table
            'x_doc_keycode', // Variable name
            'doc_keycode', // Name
            '"doc_keycode"', // Expression
            '"doc_keycode"', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"doc_keycode"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->doc_keycode->InputTextType = "text";
        $this->doc_keycode->Nullable = false; // NOT NULL field
        $this->doc_keycode->Required = true; // Required field
        $this->doc_keycode->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['doc_keycode'] = &$this->doc_keycode;

        // notary_location
        $this->notary_location = new DbField(
            $this, // Table
            'x_notary_location', // Variable name
            'notary_location', // Name
            '"notary_location"', // Expression
            '"notary_location"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"notary_location"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->notary_location->InputTextType = "text";
        $this->notary_location->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['notary_location'] = &$this->notary_location;

        // notarization_date
        $this->notarization_date = new DbField(
            $this, // Table
            'x_notarization_date', // Variable name
            'notarization_date', // Name
            '"notarization_date"', // Expression
            CastDateFieldForLike("\"notarization_date\"", 0, "DB"), // Basic search expression
            135, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"notarization_date"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->notarization_date->InputTextType = "text";
        $this->notarization_date->Raw = true;
        $this->notarization_date->Nullable = false; // NOT NULL field
        $this->notarization_date->Required = true; // Required field
        $this->notarization_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->notarization_date->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['notarization_date'] = &$this->notarization_date;

        // digital_signature
        $this->digital_signature = new DbField(
            $this, // Table
            'x_digital_signature', // Variable name
            'digital_signature', // Name
            '"digital_signature"', // Expression
            '"digital_signature"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"digital_signature"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->digital_signature->InputTextType = "text";
        $this->digital_signature->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['digital_signature'] = &$this->digital_signature;

        // digital_seal
        $this->digital_seal = new DbField(
            $this, // Table
            'x_digital_seal', // Variable name
            'digital_seal', // Name
            '"digital_seal"', // Expression
            '"digital_seal"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"digital_seal"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->digital_seal->InputTextType = "text";
        $this->digital_seal->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['digital_seal'] = &$this->digital_seal;

        // certificate_text
        $this->certificate_text = new DbField(
            $this, // Table
            'x_certificate_text', // Variable name
            'certificate_text', // Name
            '"certificate_text"', // Expression
            '"certificate_text"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"certificate_text"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->certificate_text->InputTextType = "text";
        $this->certificate_text->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['certificate_text'] = &$this->certificate_text;

        // certificate_type
        $this->certificate_type = new DbField(
            $this, // Table
            'x_certificate_type', // Variable name
            'certificate_type', // Name
            '"certificate_type"', // Expression
            '"certificate_type"', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"certificate_type"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->certificate_type->InputTextType = "text";
        $this->certificate_type->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['certificate_type'] = &$this->certificate_type;

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

        // notarized_document_path
        $this->notarized_document_path = new DbField(
            $this, // Table
            'x_notarized_document_path', // Variable name
            'notarized_document_path', // Name
            '"notarized_document_path"', // Expression
            '"notarized_document_path"', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"notarized_document_path"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->notarized_document_path->InputTextType = "text";
        $this->notarized_document_path->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['notarized_document_path'] = &$this->notarized_document_path;

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

        // revoked
        $this->revoked = new DbField(
            $this, // Table
            'x_revoked', // Variable name
            'revoked', // Name
            '"revoked"', // Expression
            'CAST("revoked" AS varchar(255))', // Basic search expression
            11, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"revoked"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->revoked->InputTextType = "text";
        $this->revoked->Raw = true;
        $this->revoked->setDataType(DataType::BOOLEAN);
        $this->revoked->Lookup = new Lookup($this->revoked, 'notarized_documents', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->revoked->OptionCount = 2;
        $this->revoked->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['revoked'] = &$this->revoked;

        // revoked_at
        $this->revoked_at = new DbField(
            $this, // Table
            'x_revoked_at', // Variable name
            'revoked_at', // Name
            '"revoked_at"', // Expression
            CastDateFieldForLike("\"revoked_at\"", 0, "DB"), // Basic search expression
            135, // Type
            0, // Size
            0, // Date/Time format
            false, // Is upload field
            '"revoked_at"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->revoked_at->InputTextType = "text";
        $this->revoked_at->Raw = true;
        $this->revoked_at->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->revoked_at->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['revoked_at'] = &$this->revoked_at;

        // revoked_by
        $this->revoked_by = new DbField(
            $this, // Table
            'x_revoked_by', // Variable name
            'revoked_by', // Name
            '"revoked_by"', // Expression
            'CAST("revoked_by" AS varchar(255))', // Basic search expression
            3, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"revoked_by"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->revoked_by->InputTextType = "text";
        $this->revoked_by->Raw = true;
        $this->revoked_by->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->revoked_by->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['revoked_by'] = &$this->revoked_by;

        // revocation_reason
        $this->revocation_reason = new DbField(
            $this, // Table
            'x_revocation_reason', // Variable name
            'revocation_reason', // Name
            '"revocation_reason"', // Expression
            '"revocation_reason"', // Basic search expression
            201, // Type
            0, // Size
            -1, // Date/Time format
            false, // Is upload field
            '"revocation_reason"', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->revocation_reason->InputTextType = "text";
        $this->revocation_reason->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['revocation_reason'] = &$this->revocation_reason;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "notarized_documents";
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
                $queryBuilder->getSQL() . " RETURNING notarized_id",
                $queryBuilder->getParameters(),
                $queryBuilder->getParameterTypes()
            )->fetchOne();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->notarized_id->setDbValue($result);
            $rs['notarized_id'] = $this->notarized_id->DbValue;
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
            if (!isset($rs['notarized_id']) && !EmptyValue($this->notarized_id->CurrentValue)) {
                $rs['notarized_id'] = $this->notarized_id->CurrentValue;
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
            if (array_key_exists('notarized_id', $rs)) {
                AddFilter($where, QuotedName('notarized_id', $this->Dbid) . '=' . QuotedValue($rs['notarized_id'], $this->notarized_id->DataType, $this->Dbid));
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
        $this->notarized_id->DbValue = $row['notarized_id'];
        $this->request_id->DbValue = $row['request_id'];
        $this->document_id->DbValue = $row['document_id'];
        $this->notary_id->DbValue = $row['notary_id'];
        $this->document_number->DbValue = $row['document_number'];
        $this->page_number->DbValue = $row['page_number'];
        $this->book_number->DbValue = $row['book_number'];
        $this->series_of->DbValue = $row['series_of'];
        $this->doc_keycode->DbValue = $row['doc_keycode'];
        $this->notary_location->DbValue = $row['notary_location'];
        $this->notarization_date->DbValue = $row['notarization_date'];
        $this->digital_signature->DbValue = $row['digital_signature'];
        $this->digital_seal->DbValue = $row['digital_seal'];
        $this->certificate_text->DbValue = $row['certificate_text'];
        $this->certificate_type->DbValue = $row['certificate_type'];
        $this->qr_code_path->DbValue = $row['qr_code_path'];
        $this->notarized_document_path->DbValue = $row['notarized_document_path'];
        $this->expires_at->DbValue = $row['expires_at'];
        $this->revoked->DbValue = (ConvertToBool($row['revoked']) ? "1" : "0");
        $this->revoked_at->DbValue = $row['revoked_at'];
        $this->revoked_by->DbValue = $row['revoked_by'];
        $this->revocation_reason->DbValue = $row['revocation_reason'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "\"notarized_id\" = @notarized_id@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->notarized_id->CurrentValue : $this->notarized_id->OldValue;
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
                $this->notarized_id->CurrentValue = $keys[0];
            } else {
                $this->notarized_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('notarized_id', $row) ? $row['notarized_id'] : null;
        } else {
            $val = !EmptyValue($this->notarized_id->OldValue) && !$current ? $this->notarized_id->OldValue : $this->notarized_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@notarized_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("NotarizedDocumentsList");
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
            "NotarizedDocumentsView" => $Language->phrase("View"),
            "NotarizedDocumentsEdit" => $Language->phrase("Edit"),
            "NotarizedDocumentsAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "NotarizedDocumentsList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "NotarizedDocumentsView",
            Config("API_ADD_ACTION") => "NotarizedDocumentsAdd",
            Config("API_EDIT_ACTION") => "NotarizedDocumentsEdit",
            Config("API_DELETE_ACTION") => "NotarizedDocumentsDelete",
            Config("API_LIST_ACTION") => "NotarizedDocumentsList",
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
        return "NotarizedDocumentsList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("NotarizedDocumentsView", $parm);
        } else {
            $url = $this->keyUrl("NotarizedDocumentsView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "NotarizedDocumentsAdd?" . $parm;
        } else {
            $url = "NotarizedDocumentsAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("NotarizedDocumentsEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("NotarizedDocumentsList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("NotarizedDocumentsAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("NotarizedDocumentsList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("NotarizedDocumentsDelete", $parm);
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
        $json .= "\"notarized_id\":" . VarToJson($this->notarized_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->notarized_id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->notarized_id->CurrentValue);
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
            if (($keyValue = Param("notarized_id") ?? Route("notarized_id")) !== null) {
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
                $this->notarized_id->CurrentValue = $key;
            } else {
                $this->notarized_id->OldValue = $key;
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
        $this->revoked->setDbValue(ConvertToBool($row['revoked']) ? "1" : "0");
        $this->revoked_at->setDbValue($row['revoked_at']);
        $this->revoked_by->setDbValue($row['revoked_by']);
        $this->revocation_reason->setDbValue($row['revocation_reason']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "NotarizedDocumentsList";
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

        // notarized_id

        // request_id

        // document_id

        // notary_id

        // document_number

        // page_number

        // book_number

        // series_of

        // doc_keycode

        // notary_location

        // notarization_date

        // digital_signature

        // digital_seal

        // certificate_text

        // certificate_type

        // qr_code_path

        // notarized_document_path

        // expires_at

        // revoked

        // revoked_at

        // revoked_by

        // revocation_reason

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
        $this->notarized_id->TooltipValue = "";

        // request_id
        $this->request_id->HrefValue = "";
        $this->request_id->TooltipValue = "";

        // document_id
        $this->document_id->HrefValue = "";
        $this->document_id->TooltipValue = "";

        // notary_id
        $this->notary_id->HrefValue = "";
        $this->notary_id->TooltipValue = "";

        // document_number
        $this->document_number->HrefValue = "";
        $this->document_number->TooltipValue = "";

        // page_number
        $this->page_number->HrefValue = "";
        $this->page_number->TooltipValue = "";

        // book_number
        $this->book_number->HrefValue = "";
        $this->book_number->TooltipValue = "";

        // series_of
        $this->series_of->HrefValue = "";
        $this->series_of->TooltipValue = "";

        // doc_keycode
        $this->doc_keycode->HrefValue = "";
        $this->doc_keycode->TooltipValue = "";

        // notary_location
        $this->notary_location->HrefValue = "";
        $this->notary_location->TooltipValue = "";

        // notarization_date
        $this->notarization_date->HrefValue = "";
        $this->notarization_date->TooltipValue = "";

        // digital_signature
        $this->digital_signature->HrefValue = "";
        $this->digital_signature->TooltipValue = "";

        // digital_seal
        $this->digital_seal->HrefValue = "";
        $this->digital_seal->TooltipValue = "";

        // certificate_text
        $this->certificate_text->HrefValue = "";
        $this->certificate_text->TooltipValue = "";

        // certificate_type
        $this->certificate_type->HrefValue = "";
        $this->certificate_type->TooltipValue = "";

        // qr_code_path
        $this->qr_code_path->HrefValue = "";
        $this->qr_code_path->TooltipValue = "";

        // notarized_document_path
        $this->notarized_document_path->HrefValue = "";
        $this->notarized_document_path->TooltipValue = "";

        // expires_at
        $this->expires_at->HrefValue = "";
        $this->expires_at->TooltipValue = "";

        // revoked
        $this->revoked->HrefValue = "";
        $this->revoked->TooltipValue = "";

        // revoked_at
        $this->revoked_at->HrefValue = "";
        $this->revoked_at->TooltipValue = "";

        // revoked_by
        $this->revoked_by->HrefValue = "";
        $this->revoked_by->TooltipValue = "";

        // revocation_reason
        $this->revocation_reason->HrefValue = "";
        $this->revocation_reason->TooltipValue = "";

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
        $this->document_number->EditValue = $this->document_number->CurrentValue;
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
        $this->book_number->EditValue = $this->book_number->CurrentValue;
        $this->book_number->PlaceHolder = RemoveHtml($this->book_number->caption());

        // series_of
        $this->series_of->setupEditAttributes();
        if (!$this->series_of->Raw) {
            $this->series_of->CurrentValue = HtmlDecode($this->series_of->CurrentValue);
        }
        $this->series_of->EditValue = $this->series_of->CurrentValue;
        $this->series_of->PlaceHolder = RemoveHtml($this->series_of->caption());

        // doc_keycode
        $this->doc_keycode->setupEditAttributes();
        if (!$this->doc_keycode->Raw) {
            $this->doc_keycode->CurrentValue = HtmlDecode($this->doc_keycode->CurrentValue);
        }
        $this->doc_keycode->EditValue = $this->doc_keycode->CurrentValue;
        $this->doc_keycode->PlaceHolder = RemoveHtml($this->doc_keycode->caption());

        // notary_location
        $this->notary_location->setupEditAttributes();
        if (!$this->notary_location->Raw) {
            $this->notary_location->CurrentValue = HtmlDecode($this->notary_location->CurrentValue);
        }
        $this->notary_location->EditValue = $this->notary_location->CurrentValue;
        $this->notary_location->PlaceHolder = RemoveHtml($this->notary_location->caption());

        // notarization_date
        $this->notarization_date->setupEditAttributes();
        $this->notarization_date->EditValue = FormatDateTime($this->notarization_date->CurrentValue, $this->notarization_date->formatPattern());
        $this->notarization_date->PlaceHolder = RemoveHtml($this->notarization_date->caption());

        // digital_signature
        $this->digital_signature->setupEditAttributes();
        $this->digital_signature->EditValue = $this->digital_signature->CurrentValue;
        $this->digital_signature->PlaceHolder = RemoveHtml($this->digital_signature->caption());

        // digital_seal
        $this->digital_seal->setupEditAttributes();
        $this->digital_seal->EditValue = $this->digital_seal->CurrentValue;
        $this->digital_seal->PlaceHolder = RemoveHtml($this->digital_seal->caption());

        // certificate_text
        $this->certificate_text->setupEditAttributes();
        $this->certificate_text->EditValue = $this->certificate_text->CurrentValue;
        $this->certificate_text->PlaceHolder = RemoveHtml($this->certificate_text->caption());

        // certificate_type
        $this->certificate_type->setupEditAttributes();
        if (!$this->certificate_type->Raw) {
            $this->certificate_type->CurrentValue = HtmlDecode($this->certificate_type->CurrentValue);
        }
        $this->certificate_type->EditValue = $this->certificate_type->CurrentValue;
        $this->certificate_type->PlaceHolder = RemoveHtml($this->certificate_type->caption());

        // qr_code_path
        $this->qr_code_path->setupEditAttributes();
        if (!$this->qr_code_path->Raw) {
            $this->qr_code_path->CurrentValue = HtmlDecode($this->qr_code_path->CurrentValue);
        }
        $this->qr_code_path->EditValue = $this->qr_code_path->CurrentValue;
        $this->qr_code_path->PlaceHolder = RemoveHtml($this->qr_code_path->caption());

        // notarized_document_path
        $this->notarized_document_path->setupEditAttributes();
        if (!$this->notarized_document_path->Raw) {
            $this->notarized_document_path->CurrentValue = HtmlDecode($this->notarized_document_path->CurrentValue);
        }
        $this->notarized_document_path->EditValue = $this->notarized_document_path->CurrentValue;
        $this->notarized_document_path->PlaceHolder = RemoveHtml($this->notarized_document_path->caption());

        // expires_at
        $this->expires_at->setupEditAttributes();
        $this->expires_at->EditValue = FormatDateTime($this->expires_at->CurrentValue, $this->expires_at->formatPattern());
        $this->expires_at->PlaceHolder = RemoveHtml($this->expires_at->caption());

        // revoked
        $this->revoked->EditValue = $this->revoked->options(false);
        $this->revoked->PlaceHolder = RemoveHtml($this->revoked->caption());

        // revoked_at
        $this->revoked_at->setupEditAttributes();
        $this->revoked_at->EditValue = FormatDateTime($this->revoked_at->CurrentValue, $this->revoked_at->formatPattern());
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
        $this->revocation_reason->EditValue = $this->revocation_reason->CurrentValue;
        $this->revocation_reason->PlaceHolder = RemoveHtml($this->revocation_reason->caption());

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
                    $doc->exportCaption($this->notarized_id);
                    $doc->exportCaption($this->request_id);
                    $doc->exportCaption($this->document_id);
                    $doc->exportCaption($this->notary_id);
                    $doc->exportCaption($this->document_number);
                    $doc->exportCaption($this->page_number);
                    $doc->exportCaption($this->book_number);
                    $doc->exportCaption($this->series_of);
                    $doc->exportCaption($this->doc_keycode);
                    $doc->exportCaption($this->notary_location);
                    $doc->exportCaption($this->notarization_date);
                    $doc->exportCaption($this->digital_signature);
                    $doc->exportCaption($this->digital_seal);
                    $doc->exportCaption($this->certificate_text);
                    $doc->exportCaption($this->certificate_type);
                    $doc->exportCaption($this->qr_code_path);
                    $doc->exportCaption($this->notarized_document_path);
                    $doc->exportCaption($this->expires_at);
                    $doc->exportCaption($this->revoked);
                    $doc->exportCaption($this->revoked_at);
                    $doc->exportCaption($this->revoked_by);
                    $doc->exportCaption($this->revocation_reason);
                } else {
                    $doc->exportCaption($this->notarized_id);
                    $doc->exportCaption($this->request_id);
                    $doc->exportCaption($this->document_id);
                    $doc->exportCaption($this->notary_id);
                    $doc->exportCaption($this->document_number);
                    $doc->exportCaption($this->page_number);
                    $doc->exportCaption($this->book_number);
                    $doc->exportCaption($this->series_of);
                    $doc->exportCaption($this->doc_keycode);
                    $doc->exportCaption($this->notary_location);
                    $doc->exportCaption($this->notarization_date);
                    $doc->exportCaption($this->certificate_type);
                    $doc->exportCaption($this->qr_code_path);
                    $doc->exportCaption($this->notarized_document_path);
                    $doc->exportCaption($this->expires_at);
                    $doc->exportCaption($this->revoked);
                    $doc->exportCaption($this->revoked_at);
                    $doc->exportCaption($this->revoked_by);
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
                        $doc->exportField($this->notarized_id);
                        $doc->exportField($this->request_id);
                        $doc->exportField($this->document_id);
                        $doc->exportField($this->notary_id);
                        $doc->exportField($this->document_number);
                        $doc->exportField($this->page_number);
                        $doc->exportField($this->book_number);
                        $doc->exportField($this->series_of);
                        $doc->exportField($this->doc_keycode);
                        $doc->exportField($this->notary_location);
                        $doc->exportField($this->notarization_date);
                        $doc->exportField($this->digital_signature);
                        $doc->exportField($this->digital_seal);
                        $doc->exportField($this->certificate_text);
                        $doc->exportField($this->certificate_type);
                        $doc->exportField($this->qr_code_path);
                        $doc->exportField($this->notarized_document_path);
                        $doc->exportField($this->expires_at);
                        $doc->exportField($this->revoked);
                        $doc->exportField($this->revoked_at);
                        $doc->exportField($this->revoked_by);
                        $doc->exportField($this->revocation_reason);
                    } else {
                        $doc->exportField($this->notarized_id);
                        $doc->exportField($this->request_id);
                        $doc->exportField($this->document_id);
                        $doc->exportField($this->notary_id);
                        $doc->exportField($this->document_number);
                        $doc->exportField($this->page_number);
                        $doc->exportField($this->book_number);
                        $doc->exportField($this->series_of);
                        $doc->exportField($this->doc_keycode);
                        $doc->exportField($this->notary_location);
                        $doc->exportField($this->notarization_date);
                        $doc->exportField($this->certificate_type);
                        $doc->exportField($this->qr_code_path);
                        $doc->exportField($this->notarized_document_path);
                        $doc->exportField($this->expires_at);
                        $doc->exportField($this->revoked);
                        $doc->exportField($this->revoked_at);
                        $doc->exportField($this->revoked_by);
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
