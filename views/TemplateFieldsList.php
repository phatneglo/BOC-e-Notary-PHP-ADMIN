<?php

namespace PHPMaker2024\eNotary;

// Page object
$TemplateFieldsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { template_fields: currentTable } });
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
<?php if (!$Page->IsModal) { ?>
<form name="ftemplate_fieldssrch" id="ftemplate_fieldssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="ftemplate_fieldssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { template_fields: currentTable } });
var currentForm;
var ftemplate_fieldssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("ftemplate_fieldssrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

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
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="ftemplate_fieldssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="ftemplate_fieldssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="ftemplate_fieldssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="ftemplate_fieldssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="template_fields">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_template_fields" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_template_fieldslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->field_id->Visible) { // field_id ?>
        <th data-name="field_id" class="<?= $Page->field_id->headerCellClass() ?>"><div id="elh_template_fields_field_id" class="template_fields_field_id"><?= $Page->renderFieldHeader($Page->field_id) ?></div></th>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
        <th data-name="template_id" class="<?= $Page->template_id->headerCellClass() ?>"><div id="elh_template_fields_template_id" class="template_fields_template_id"><?= $Page->renderFieldHeader($Page->template_id) ?></div></th>
<?php } ?>
<?php if ($Page->field_name->Visible) { // field_name ?>
        <th data-name="field_name" class="<?= $Page->field_name->headerCellClass() ?>"><div id="elh_template_fields_field_name" class="template_fields_field_name"><?= $Page->renderFieldHeader($Page->field_name) ?></div></th>
<?php } ?>
<?php if ($Page->field_label->Visible) { // field_label ?>
        <th data-name="field_label" class="<?= $Page->field_label->headerCellClass() ?>"><div id="elh_template_fields_field_label" class="template_fields_field_label"><?= $Page->renderFieldHeader($Page->field_label) ?></div></th>
<?php } ?>
<?php if ($Page->field_type->Visible) { // field_type ?>
        <th data-name="field_type" class="<?= $Page->field_type->headerCellClass() ?>"><div id="elh_template_fields_field_type" class="template_fields_field_type"><?= $Page->renderFieldHeader($Page->field_type) ?></div></th>
<?php } ?>
<?php if ($Page->is_required->Visible) { // is_required ?>
        <th data-name="is_required" class="<?= $Page->is_required->headerCellClass() ?>"><div id="elh_template_fields_is_required" class="template_fields_is_required"><?= $Page->renderFieldHeader($Page->is_required) ?></div></th>
<?php } ?>
<?php if ($Page->field_order->Visible) { // field_order ?>
        <th data-name="field_order" class="<?= $Page->field_order->headerCellClass() ?>"><div id="elh_template_fields_field_order" class="template_fields_field_order"><?= $Page->renderFieldHeader($Page->field_order) ?></div></th>
<?php } ?>
<?php if ($Page->field_width->Visible) { // field_width ?>
        <th data-name="field_width" class="<?= $Page->field_width->headerCellClass() ?>"><div id="elh_template_fields_field_width" class="template_fields_field_width"><?= $Page->renderFieldHeader($Page->field_width) ?></div></th>
<?php } ?>
<?php if ($Page->is_visible->Visible) { // is_visible ?>
        <th data-name="is_visible" class="<?= $Page->is_visible->headerCellClass() ?>"><div id="elh_template_fields_is_visible" class="template_fields_is_visible"><?= $Page->renderFieldHeader($Page->is_visible) ?></div></th>
<?php } ?>
<?php if ($Page->section_name->Visible) { // section_name ?>
        <th data-name="section_name" class="<?= $Page->section_name->headerCellClass() ?>"><div id="elh_template_fields_section_name" class="template_fields_section_name"><?= $Page->renderFieldHeader($Page->section_name) ?></div></th>
<?php } ?>
<?php if ($Page->x_position->Visible) { // x_position ?>
        <th data-name="x_position" class="<?= $Page->x_position->headerCellClass() ?>"><div id="elh_template_fields_x_position" class="template_fields_x_position"><?= $Page->renderFieldHeader($Page->x_position) ?></div></th>
<?php } ?>
<?php if ($Page->y_position->Visible) { // y_position ?>
        <th data-name="y_position" class="<?= $Page->y_position->headerCellClass() ?>"><div id="elh_template_fields_y_position" class="template_fields_y_position"><?= $Page->renderFieldHeader($Page->y_position) ?></div></th>
<?php } ?>
<?php if ($Page->group_name->Visible) { // group_name ?>
        <th data-name="group_name" class="<?= $Page->group_name->headerCellClass() ?>"><div id="elh_template_fields_group_name" class="template_fields_group_name"><?= $Page->renderFieldHeader($Page->group_name) ?></div></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th data-name="created_at" class="<?= $Page->created_at->headerCellClass() ?>"><div id="elh_template_fields_created_at" class="template_fields_created_at"><?= $Page->renderFieldHeader($Page->created_at) ?></div></th>
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
    <?php if ($Page->field_id->Visible) { // field_id ?>
        <td data-name="field_id"<?= $Page->field_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_field_id" class="el_template_fields_field_id">
<span<?= $Page->field_id->viewAttributes() ?>>
<?= $Page->field_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->template_id->Visible) { // template_id ?>
        <td data-name="template_id"<?= $Page->template_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_template_id" class="el_template_fields_template_id">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->field_name->Visible) { // field_name ?>
        <td data-name="field_name"<?= $Page->field_name->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_field_name" class="el_template_fields_field_name">
<span<?= $Page->field_name->viewAttributes() ?>>
<?= $Page->field_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->field_label->Visible) { // field_label ?>
        <td data-name="field_label"<?= $Page->field_label->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_field_label" class="el_template_fields_field_label">
<span<?= $Page->field_label->viewAttributes() ?>>
<?= $Page->field_label->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->field_type->Visible) { // field_type ?>
        <td data-name="field_type"<?= $Page->field_type->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_field_type" class="el_template_fields_field_type">
<span<?= $Page->field_type->viewAttributes() ?>>
<?= $Page->field_type->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->is_required->Visible) { // is_required ?>
        <td data-name="is_required"<?= $Page->is_required->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_is_required" class="el_template_fields_is_required">
<span<?= $Page->is_required->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_required->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->field_order->Visible) { // field_order ?>
        <td data-name="field_order"<?= $Page->field_order->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_field_order" class="el_template_fields_field_order">
<span<?= $Page->field_order->viewAttributes() ?>>
<?= $Page->field_order->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->field_width->Visible) { // field_width ?>
        <td data-name="field_width"<?= $Page->field_width->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_field_width" class="el_template_fields_field_width">
<span<?= $Page->field_width->viewAttributes() ?>>
<?= $Page->field_width->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->is_visible->Visible) { // is_visible ?>
        <td data-name="is_visible"<?= $Page->is_visible->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_is_visible" class="el_template_fields_is_visible">
<span<?= $Page->is_visible->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_visible->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->section_name->Visible) { // section_name ?>
        <td data-name="section_name"<?= $Page->section_name->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_section_name" class="el_template_fields_section_name">
<span<?= $Page->section_name->viewAttributes() ?>>
<?= $Page->section_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->x_position->Visible) { // x_position ?>
        <td data-name="x_position"<?= $Page->x_position->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_x_position" class="el_template_fields_x_position">
<span<?= $Page->x_position->viewAttributes() ?>>
<?= $Page->x_position->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->y_position->Visible) { // y_position ?>
        <td data-name="y_position"<?= $Page->y_position->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_y_position" class="el_template_fields_y_position">
<span<?= $Page->y_position->viewAttributes() ?>>
<?= $Page->y_position->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->group_name->Visible) { // group_name ?>
        <td data-name="group_name"<?= $Page->group_name->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_group_name" class="el_template_fields_group_name">
<span<?= $Page->group_name->viewAttributes() ?>>
<?= $Page->group_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->created_at->Visible) { // created_at ?>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_template_fields_created_at" class="el_template_fields_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
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
    ew.addEventHandlers("template_fields");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
