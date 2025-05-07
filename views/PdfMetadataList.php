<?php

namespace PHPMaker2024\eNotary;

// Page object
$PdfMetadataList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pdf_metadata: currentTable } });
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
<form name="fpdf_metadatasrch" id="fpdf_metadatasrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fpdf_metadatasrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pdf_metadata: currentTable } });
var currentForm;
var fpdf_metadatasrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fpdf_metadatasrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fpdf_metadatasrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fpdf_metadatasrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fpdf_metadatasrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fpdf_metadatasrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="pdf_metadata">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_pdf_metadata" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_pdf_metadatalist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->metadata_id->Visible) { // metadata_id ?>
        <th data-name="metadata_id" class="<?= $Page->metadata_id->headerCellClass() ?>"><div id="elh_pdf_metadata_metadata_id" class="pdf_metadata_metadata_id"><?= $Page->renderFieldHeader($Page->metadata_id) ?></div></th>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th data-name="document_id" class="<?= $Page->document_id->headerCellClass() ?>"><div id="elh_pdf_metadata_document_id" class="pdf_metadata_document_id"><?= $Page->renderFieldHeader($Page->document_id) ?></div></th>
<?php } ?>
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <th data-name="notarized_id" class="<?= $Page->notarized_id->headerCellClass() ?>"><div id="elh_pdf_metadata_notarized_id" class="pdf_metadata_notarized_id"><?= $Page->renderFieldHeader($Page->notarized_id) ?></div></th>
<?php } ?>
<?php if ($Page->pdf_type->Visible) { // pdf_type ?>
        <th data-name="pdf_type" class="<?= $Page->pdf_type->headerCellClass() ?>"><div id="elh_pdf_metadata_pdf_type" class="pdf_metadata_pdf_type"><?= $Page->renderFieldHeader($Page->pdf_type) ?></div></th>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
        <th data-name="file_path" class="<?= $Page->file_path->headerCellClass() ?>"><div id="elh_pdf_metadata_file_path" class="pdf_metadata_file_path"><?= $Page->renderFieldHeader($Page->file_path) ?></div></th>
<?php } ?>
<?php if ($Page->file_size->Visible) { // file_size ?>
        <th data-name="file_size" class="<?= $Page->file_size->headerCellClass() ?>"><div id="elh_pdf_metadata_file_size" class="pdf_metadata_file_size"><?= $Page->renderFieldHeader($Page->file_size) ?></div></th>
<?php } ?>
<?php if ($Page->page_count->Visible) { // page_count ?>
        <th data-name="page_count" class="<?= $Page->page_count->headerCellClass() ?>"><div id="elh_pdf_metadata_page_count" class="pdf_metadata_page_count"><?= $Page->renderFieldHeader($Page->page_count) ?></div></th>
<?php } ?>
<?php if ($Page->generated_at->Visible) { // generated_at ?>
        <th data-name="generated_at" class="<?= $Page->generated_at->headerCellClass() ?>"><div id="elh_pdf_metadata_generated_at" class="pdf_metadata_generated_at"><?= $Page->renderFieldHeader($Page->generated_at) ?></div></th>
<?php } ?>
<?php if ($Page->generated_by->Visible) { // generated_by ?>
        <th data-name="generated_by" class="<?= $Page->generated_by->headerCellClass() ?>"><div id="elh_pdf_metadata_generated_by" class="pdf_metadata_generated_by"><?= $Page->renderFieldHeader($Page->generated_by) ?></div></th>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
        <th data-name="expires_at" class="<?= $Page->expires_at->headerCellClass() ?>"><div id="elh_pdf_metadata_expires_at" class="pdf_metadata_expires_at"><?= $Page->renderFieldHeader($Page->expires_at) ?></div></th>
<?php } ?>
<?php if ($Page->is_final->Visible) { // is_final ?>
        <th data-name="is_final" class="<?= $Page->is_final->headerCellClass() ?>"><div id="elh_pdf_metadata_is_final" class="pdf_metadata_is_final"><?= $Page->renderFieldHeader($Page->is_final) ?></div></th>
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
    <?php if ($Page->metadata_id->Visible) { // metadata_id ?>
        <td data-name="metadata_id"<?= $Page->metadata_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_metadata_id" class="el_pdf_metadata_metadata_id">
<span<?= $Page->metadata_id->viewAttributes() ?>>
<?= $Page->metadata_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->document_id->Visible) { // document_id ?>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_document_id" class="el_pdf_metadata_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <td data-name="notarized_id"<?= $Page->notarized_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_notarized_id" class="el_pdf_metadata_notarized_id">
<span<?= $Page->notarized_id->viewAttributes() ?>>
<?= $Page->notarized_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pdf_type->Visible) { // pdf_type ?>
        <td data-name="pdf_type"<?= $Page->pdf_type->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_pdf_type" class="el_pdf_metadata_pdf_type">
<span<?= $Page->pdf_type->viewAttributes() ?>>
<?= $Page->pdf_type->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_path->Visible) { // file_path ?>
        <td data-name="file_path"<?= $Page->file_path->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_file_path" class="el_pdf_metadata_file_path">
<span<?= $Page->file_path->viewAttributes() ?>>
<?= $Page->file_path->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_size->Visible) { // file_size ?>
        <td data-name="file_size"<?= $Page->file_size->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_file_size" class="el_pdf_metadata_file_size">
<span<?= $Page->file_size->viewAttributes() ?>>
<?= $Page->file_size->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->page_count->Visible) { // page_count ?>
        <td data-name="page_count"<?= $Page->page_count->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_page_count" class="el_pdf_metadata_page_count">
<span<?= $Page->page_count->viewAttributes() ?>>
<?= $Page->page_count->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->generated_at->Visible) { // generated_at ?>
        <td data-name="generated_at"<?= $Page->generated_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_generated_at" class="el_pdf_metadata_generated_at">
<span<?= $Page->generated_at->viewAttributes() ?>>
<?= $Page->generated_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->generated_by->Visible) { // generated_by ?>
        <td data-name="generated_by"<?= $Page->generated_by->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_generated_by" class="el_pdf_metadata_generated_by">
<span<?= $Page->generated_by->viewAttributes() ?>>
<?= $Page->generated_by->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->expires_at->Visible) { // expires_at ?>
        <td data-name="expires_at"<?= $Page->expires_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_expires_at" class="el_pdf_metadata_expires_at">
<span<?= $Page->expires_at->viewAttributes() ?>>
<?= $Page->expires_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->is_final->Visible) { // is_final ?>
        <td data-name="is_final"<?= $Page->is_final->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_pdf_metadata_is_final" class="el_pdf_metadata_is_final">
<span<?= $Page->is_final->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_final->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
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
    ew.addEventHandlers("pdf_metadata");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
