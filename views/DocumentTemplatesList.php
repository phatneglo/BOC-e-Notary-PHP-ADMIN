<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentTemplatesList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_templates: currentTable } });
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
<form name="fdocument_templatessrch" id="fdocument_templatessrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fdocument_templatessrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_templates: currentTable } });
var currentForm;
var fdocument_templatessrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fdocument_templatessrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fdocument_templatessrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fdocument_templatessrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fdocument_templatessrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fdocument_templatessrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="document_templates">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_document_templates" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_document_templateslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->template_id->Visible) { // template_id ?>
        <th data-name="template_id" class="<?= $Page->template_id->headerCellClass() ?>"><div id="elh_document_templates_template_id" class="document_templates_template_id"><?= $Page->renderFieldHeader($Page->template_id) ?></div></th>
<?php } ?>
<?php if ($Page->template_name->Visible) { // template_name ?>
        <th data-name="template_name" class="<?= $Page->template_name->headerCellClass() ?>"><div id="elh_document_templates_template_name" class="document_templates_template_name"><?= $Page->renderFieldHeader($Page->template_name) ?></div></th>
<?php } ?>
<?php if ($Page->template_code->Visible) { // template_code ?>
        <th data-name="template_code" class="<?= $Page->template_code->headerCellClass() ?>"><div id="elh_document_templates_template_code" class="document_templates_template_code"><?= $Page->renderFieldHeader($Page->template_code) ?></div></th>
<?php } ?>
<?php if ($Page->category_id->Visible) { // category_id ?>
        <th data-name="category_id" class="<?= $Page->category_id->headerCellClass() ?>"><div id="elh_document_templates_category_id" class="document_templates_category_id"><?= $Page->renderFieldHeader($Page->category_id) ?></div></th>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
        <th data-name="is_active" class="<?= $Page->is_active->headerCellClass() ?>"><div id="elh_document_templates_is_active" class="document_templates_is_active"><?= $Page->renderFieldHeader($Page->is_active) ?></div></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th data-name="created_at" class="<?= $Page->created_at->headerCellClass() ?>"><div id="elh_document_templates_created_at" class="document_templates_created_at"><?= $Page->renderFieldHeader($Page->created_at) ?></div></th>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
        <th data-name="created_by" class="<?= $Page->created_by->headerCellClass() ?>"><div id="elh_document_templates_created_by" class="document_templates_created_by"><?= $Page->renderFieldHeader($Page->created_by) ?></div></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th data-name="updated_at" class="<?= $Page->updated_at->headerCellClass() ?>"><div id="elh_document_templates_updated_at" class="document_templates_updated_at"><?= $Page->renderFieldHeader($Page->updated_at) ?></div></th>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
        <th data-name="updated_by" class="<?= $Page->updated_by->headerCellClass() ?>"><div id="elh_document_templates_updated_by" class="document_templates_updated_by"><?= $Page->renderFieldHeader($Page->updated_by) ?></div></th>
<?php } ?>
<?php if ($Page->version->Visible) { // version ?>
        <th data-name="version" class="<?= $Page->version->headerCellClass() ?>"><div id="elh_document_templates_version" class="document_templates_version"><?= $Page->renderFieldHeader($Page->version) ?></div></th>
<?php } ?>
<?php if ($Page->notary_required->Visible) { // notary_required ?>
        <th data-name="notary_required" class="<?= $Page->notary_required->headerCellClass() ?>"><div id="elh_document_templates_notary_required" class="document_templates_notary_required"><?= $Page->renderFieldHeader($Page->notary_required) ?></div></th>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
        <th data-name="fee_amount" class="<?= $Page->fee_amount->headerCellClass() ?>"><div id="elh_document_templates_fee_amount" class="document_templates_fee_amount"><?= $Page->renderFieldHeader($Page->fee_amount) ?></div></th>
<?php } ?>
<?php if ($Page->template_type->Visible) { // template_type ?>
        <th data-name="template_type" class="<?= $Page->template_type->headerCellClass() ?>"><div id="elh_document_templates_template_type" class="document_templates_template_type"><?= $Page->renderFieldHeader($Page->template_type) ?></div></th>
<?php } ?>
<?php if ($Page->preview_image_path->Visible) { // preview_image_path ?>
        <th data-name="preview_image_path" class="<?= $Page->preview_image_path->headerCellClass() ?>"><div id="elh_document_templates_preview_image_path" class="document_templates_preview_image_path"><?= $Page->renderFieldHeader($Page->preview_image_path) ?></div></th>
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
    <?php if ($Page->template_id->Visible) { // template_id ?>
        <td data-name="template_id"<?= $Page->template_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_template_id" class="el_document_templates_template_id">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->template_name->Visible) { // template_name ?>
        <td data-name="template_name"<?= $Page->template_name->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_template_name" class="el_document_templates_template_name">
<span<?= $Page->template_name->viewAttributes() ?>>
<?= $Page->template_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->template_code->Visible) { // template_code ?>
        <td data-name="template_code"<?= $Page->template_code->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_template_code" class="el_document_templates_template_code">
<span<?= $Page->template_code->viewAttributes() ?>>
<?= $Page->template_code->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->category_id->Visible) { // category_id ?>
        <td data-name="category_id"<?= $Page->category_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_category_id" class="el_document_templates_category_id">
<span<?= $Page->category_id->viewAttributes() ?>>
<?= $Page->category_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->is_active->Visible) { // is_active ?>
        <td data-name="is_active"<?= $Page->is_active->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_is_active" class="el_document_templates_is_active">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->created_at->Visible) { // created_at ?>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_created_at" class="el_document_templates_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->created_by->Visible) { // created_by ?>
        <td data-name="created_by"<?= $Page->created_by->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_created_by" class="el_document_templates_created_by">
<span<?= $Page->created_by->viewAttributes() ?>>
<?= $Page->created_by->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_updated_at" class="el_document_templates_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->updated_by->Visible) { // updated_by ?>
        <td data-name="updated_by"<?= $Page->updated_by->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_updated_by" class="el_document_templates_updated_by">
<span<?= $Page->updated_by->viewAttributes() ?>>
<?= $Page->updated_by->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->version->Visible) { // version ?>
        <td data-name="version"<?= $Page->version->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_version" class="el_document_templates_version">
<span<?= $Page->version->viewAttributes() ?>>
<?= $Page->version->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notary_required->Visible) { // notary_required ?>
        <td data-name="notary_required"<?= $Page->notary_required->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_notary_required" class="el_document_templates_notary_required">
<span<?= $Page->notary_required->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->notary_required->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fee_amount->Visible) { // fee_amount ?>
        <td data-name="fee_amount"<?= $Page->fee_amount->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_fee_amount" class="el_document_templates_fee_amount">
<span<?= $Page->fee_amount->viewAttributes() ?>>
<?= $Page->fee_amount->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->template_type->Visible) { // template_type ?>
        <td data-name="template_type"<?= $Page->template_type->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_template_type" class="el_document_templates_template_type">
<span<?= $Page->template_type->viewAttributes() ?>>
<?= $Page->template_type->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->preview_image_path->Visible) { // preview_image_path ?>
        <td data-name="preview_image_path"<?= $Page->preview_image_path->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_templates_preview_image_path" class="el_document_templates_preview_image_path">
<span<?= $Page->preview_image_path->viewAttributes() ?>>
<?= $Page->preview_image_path->getViewValue() ?></span>
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
    ew.addEventHandlers("document_templates");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
