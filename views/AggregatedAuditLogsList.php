<?php

namespace PHPMaker2024\eNotary;

// Page object
$AggregatedAuditLogsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { aggregated_audit_logs: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "users") {
    if ($Page->MasterRecordExists) {
        include_once "views/UsersMaster.php";
    }
}
?>
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="faggregated_audit_logssrch" id="faggregated_audit_logssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="faggregated_audit_logssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { aggregated_audit_logs: currentTable } });
var currentForm;
var faggregated_audit_logssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("faggregated_audit_logssrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["action_date", [], fields.action_date.isInvalid],
            ["y_action_date", [ew.Validators.between], false]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
                return true;
            }
        )

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code in JAVASCRIPT here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = RowType::SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->action_date->Visible) { // action_date ?>
<?php
if (!$Page->action_date->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_action_date" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->action_date->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_action_date" class="ew-search-caption ew-label"><?= $Page->action_date->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_action_date" id="z_action_date" value="BETWEEN">
</div>
        </div>
        <div id="el_aggregated_audit_logs_action_date" class="ew-search-field">
<input type="<?= $Page->action_date->getInputTextType() ?>" name="x_action_date" id="x_action_date" data-table="aggregated_audit_logs" data-field="x_action_date" value="<?= $Page->action_date->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->action_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->action_date->formatPattern()) ?>"<?= $Page->action_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->action_date->getErrorMessage(false) ?></div>
<?php if (!$Page->action_date->ReadOnly && !$Page->action_date->Disabled && !isset($Page->action_date->EditAttrs["readonly"]) && !isset($Page->action_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["faggregated_audit_logssrch", "datetimepicker"], function () {
    let format = "<?= "yyyy-MM-dd" ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("faggregated_audit_logssrch", "x_action_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_aggregated_audit_logs_action_date" class="ew-search-field2">
<input type="<?= $Page->action_date->getInputTextType() ?>" name="y_action_date" id="y_action_date" data-table="aggregated_audit_logs" data-field="x_action_date" value="<?= $Page->action_date->EditValue2 ?>" size="20" placeholder="<?= HtmlEncode($Page->action_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->action_date->formatPattern()) ?>"<?= $Page->action_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->action_date->getErrorMessage(false) ?></div>
<?php if (!$Page->action_date->ReadOnly && !$Page->action_date->Disabled && !isset($Page->action_date->EditAttrs["readonly"]) && !isset($Page->action_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["faggregated_audit_logssrch", "datetimepicker"], function () {
    let format = "<?= "yyyy-MM-dd" ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("faggregated_audit_logssrch", "y_action_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
</div><!-- /.row -->
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="faggregated_audit_logssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="faggregated_audit_logssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="faggregated_audit_logssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="faggregated_audit_logssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-header-options">
<?php $Page->HeaderOptions?->render("body") ?>
</div>
<div id="ew-list">
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="aggregated_audit_logs">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "users" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="users">
<input type="hidden" name="fk_user_id" value="<?= HtmlEncode($Page->user->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_aggregated_audit_logs" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_aggregated_audit_logslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = RowType::HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->aggregated_id->Visible) { // aggregated_id ?>
        <th data-name="aggregated_id" class="<?= $Page->aggregated_id->headerCellClass() ?>"><div id="elh_aggregated_audit_logs_aggregated_id" class="aggregated_audit_logs_aggregated_id"><?= $Page->renderFieldHeader($Page->aggregated_id) ?></div></th>
<?php } ?>
<?php if ($Page->action_date->Visible) { // action_date ?>
        <th data-name="action_date" class="<?= $Page->action_date->headerCellClass() ?>"><div id="elh_aggregated_audit_logs_action_date" class="aggregated_audit_logs_action_date"><?= $Page->renderFieldHeader($Page->action_date) ?></div></th>
<?php } ?>
<?php if ($Page->script->Visible) { // script ?>
        <th data-name="script" class="<?= $Page->script->headerCellClass() ?>"><div id="elh_aggregated_audit_logs_script" class="aggregated_audit_logs_script"><?= $Page->renderFieldHeader($Page->script) ?></div></th>
<?php } ?>
<?php if ($Page->user->Visible) { // user ?>
        <th data-name="user" class="<?= $Page->user->headerCellClass() ?>"><div id="elh_aggregated_audit_logs_user" class="aggregated_audit_logs_user"><?= $Page->renderFieldHeader($Page->user) ?></div></th>
<?php } ?>
<?php if ($Page->_table->Visible) { // table ?>
        <th data-name="_table" class="<?= $Page->_table->headerCellClass() ?>"><div id="elh_aggregated_audit_logs__table" class="aggregated_audit_logs__table"><?= $Page->renderFieldHeader($Page->_table) ?></div></th>
<?php } ?>
<?php if ($Page->action_type->Visible) { // action_type ?>
        <th data-name="action_type" class="<?= $Page->action_type->headerCellClass() ?>"><div id="elh_aggregated_audit_logs_action_type" class="aggregated_audit_logs_action_type"><?= $Page->renderFieldHeader($Page->action_type) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
$isInlineAddOrCopy = ($Page->isCopy() || $Page->isAdd());
while ($Page->RecordCount < $Page->StopRecord || $Page->RowIndex === '$rowindex$' || $isInlineAddOrCopy && $Page->RowIndex == 0) {
    if (
        $Page->CurrentRow !== false &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        !($isInlineAddOrCopy && $Page->RowIndex == 0)
    ) {
        $Page->fetch();
    }
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->aggregated_id->Visible) { // aggregated_id ?>
        <td data-name="aggregated_id"<?= $Page->aggregated_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_aggregated_audit_logs_aggregated_id" class="el_aggregated_audit_logs_aggregated_id">
<span<?= $Page->aggregated_id->viewAttributes() ?>>
<?= $Page->aggregated_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->action_date->Visible) { // action_date ?>
        <td data-name="action_date"<?= $Page->action_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_aggregated_audit_logs_action_date" class="el_aggregated_audit_logs_action_date">
<span<?= $Page->action_date->viewAttributes() ?>>
<?= $Page->action_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->script->Visible) { // script ?>
        <td data-name="script"<?= $Page->script->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_aggregated_audit_logs_script" class="el_aggregated_audit_logs_script">
<span<?= $Page->script->viewAttributes() ?>>
<?= $Page->script->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->user->Visible) { // user ?>
        <td data-name="user"<?= $Page->user->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_aggregated_audit_logs_user" class="el_aggregated_audit_logs_user">
<span<?= $Page->user->viewAttributes() ?>>
<?= $Page->user->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_table->Visible) { // table ?>
        <td data-name="_table"<?= $Page->_table->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_aggregated_audit_logs__table" class="el_aggregated_audit_logs__table">
<span<?= $Page->_table->viewAttributes() ?>>
<?= $Page->_table->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->action_type->Visible) { // action_type ?>
        <td data-name="action_type"<?= $Page->action_type->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_aggregated_audit_logs_action_type" class="el_aggregated_audit_logs_action_type">
<span<?= $Page->action_type->viewAttributes() ?>>
<?= $Page->action_type->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }

    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close result set
$Page->Recordset?->free();
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
<div id="ew-footer-options">
<?php $Page->FooterOptions?->render("body") ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("aggregated_audit_logs");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
