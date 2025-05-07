<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizedDocumentsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarized_documents: currentTable } });
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
<form name="fnotarized_documentssrch" id="fnotarized_documentssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fnotarized_documentssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarized_documents: currentTable } });
var currentForm;
var fnotarized_documentssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fnotarized_documentssrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fnotarized_documentssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fnotarized_documentssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fnotarized_documentssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fnotarized_documentssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="notarized_documents">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_notarized_documents" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_notarized_documentslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <th data-name="notarized_id" class="<?= $Page->notarized_id->headerCellClass() ?>"><div id="elh_notarized_documents_notarized_id" class="notarized_documents_notarized_id"><?= $Page->renderFieldHeader($Page->notarized_id) ?></div></th>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
        <th data-name="request_id" class="<?= $Page->request_id->headerCellClass() ?>"><div id="elh_notarized_documents_request_id" class="notarized_documents_request_id"><?= $Page->renderFieldHeader($Page->request_id) ?></div></th>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th data-name="document_id" class="<?= $Page->document_id->headerCellClass() ?>"><div id="elh_notarized_documents_document_id" class="notarized_documents_document_id"><?= $Page->renderFieldHeader($Page->document_id) ?></div></th>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <th data-name="notary_id" class="<?= $Page->notary_id->headerCellClass() ?>"><div id="elh_notarized_documents_notary_id" class="notarized_documents_notary_id"><?= $Page->renderFieldHeader($Page->notary_id) ?></div></th>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
        <th data-name="document_number" class="<?= $Page->document_number->headerCellClass() ?>"><div id="elh_notarized_documents_document_number" class="notarized_documents_document_number"><?= $Page->renderFieldHeader($Page->document_number) ?></div></th>
<?php } ?>
<?php if ($Page->page_number->Visible) { // page_number ?>
        <th data-name="page_number" class="<?= $Page->page_number->headerCellClass() ?>"><div id="elh_notarized_documents_page_number" class="notarized_documents_page_number"><?= $Page->renderFieldHeader($Page->page_number) ?></div></th>
<?php } ?>
<?php if ($Page->book_number->Visible) { // book_number ?>
        <th data-name="book_number" class="<?= $Page->book_number->headerCellClass() ?>"><div id="elh_notarized_documents_book_number" class="notarized_documents_book_number"><?= $Page->renderFieldHeader($Page->book_number) ?></div></th>
<?php } ?>
<?php if ($Page->series_of->Visible) { // series_of ?>
        <th data-name="series_of" class="<?= $Page->series_of->headerCellClass() ?>"><div id="elh_notarized_documents_series_of" class="notarized_documents_series_of"><?= $Page->renderFieldHeader($Page->series_of) ?></div></th>
<?php } ?>
<?php if ($Page->doc_keycode->Visible) { // doc_keycode ?>
        <th data-name="doc_keycode" class="<?= $Page->doc_keycode->headerCellClass() ?>"><div id="elh_notarized_documents_doc_keycode" class="notarized_documents_doc_keycode"><?= $Page->renderFieldHeader($Page->doc_keycode) ?></div></th>
<?php } ?>
<?php if ($Page->notary_location->Visible) { // notary_location ?>
        <th data-name="notary_location" class="<?= $Page->notary_location->headerCellClass() ?>"><div id="elh_notarized_documents_notary_location" class="notarized_documents_notary_location"><?= $Page->renderFieldHeader($Page->notary_location) ?></div></th>
<?php } ?>
<?php if ($Page->notarization_date->Visible) { // notarization_date ?>
        <th data-name="notarization_date" class="<?= $Page->notarization_date->headerCellClass() ?>"><div id="elh_notarized_documents_notarization_date" class="notarized_documents_notarization_date"><?= $Page->renderFieldHeader($Page->notarization_date) ?></div></th>
<?php } ?>
<?php if ($Page->certificate_type->Visible) { // certificate_type ?>
        <th data-name="certificate_type" class="<?= $Page->certificate_type->headerCellClass() ?>"><div id="elh_notarized_documents_certificate_type" class="notarized_documents_certificate_type"><?= $Page->renderFieldHeader($Page->certificate_type) ?></div></th>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <th data-name="qr_code_path" class="<?= $Page->qr_code_path->headerCellClass() ?>"><div id="elh_notarized_documents_qr_code_path" class="notarized_documents_qr_code_path"><?= $Page->renderFieldHeader($Page->qr_code_path) ?></div></th>
<?php } ?>
<?php if ($Page->notarized_document_path->Visible) { // notarized_document_path ?>
        <th data-name="notarized_document_path" class="<?= $Page->notarized_document_path->headerCellClass() ?>"><div id="elh_notarized_documents_notarized_document_path" class="notarized_documents_notarized_document_path"><?= $Page->renderFieldHeader($Page->notarized_document_path) ?></div></th>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
        <th data-name="expires_at" class="<?= $Page->expires_at->headerCellClass() ?>"><div id="elh_notarized_documents_expires_at" class="notarized_documents_expires_at"><?= $Page->renderFieldHeader($Page->expires_at) ?></div></th>
<?php } ?>
<?php if ($Page->revoked->Visible) { // revoked ?>
        <th data-name="revoked" class="<?= $Page->revoked->headerCellClass() ?>"><div id="elh_notarized_documents_revoked" class="notarized_documents_revoked"><?= $Page->renderFieldHeader($Page->revoked) ?></div></th>
<?php } ?>
<?php if ($Page->revoked_at->Visible) { // revoked_at ?>
        <th data-name="revoked_at" class="<?= $Page->revoked_at->headerCellClass() ?>"><div id="elh_notarized_documents_revoked_at" class="notarized_documents_revoked_at"><?= $Page->renderFieldHeader($Page->revoked_at) ?></div></th>
<?php } ?>
<?php if ($Page->revoked_by->Visible) { // revoked_by ?>
        <th data-name="revoked_by" class="<?= $Page->revoked_by->headerCellClass() ?>"><div id="elh_notarized_documents_revoked_by" class="notarized_documents_revoked_by"><?= $Page->renderFieldHeader($Page->revoked_by) ?></div></th>
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
    <?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <td data-name="notarized_id"<?= $Page->notarized_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_notarized_id" class="el_notarized_documents_notarized_id">
<span<?= $Page->notarized_id->viewAttributes() ?>>
<?= $Page->notarized_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->request_id->Visible) { // request_id ?>
        <td data-name="request_id"<?= $Page->request_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_request_id" class="el_notarized_documents_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->document_id->Visible) { // document_id ?>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_document_id" class="el_notarized_documents_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notary_id->Visible) { // notary_id ?>
        <td data-name="notary_id"<?= $Page->notary_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_notary_id" class="el_notarized_documents_notary_id">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->document_number->Visible) { // document_number ?>
        <td data-name="document_number"<?= $Page->document_number->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_document_number" class="el_notarized_documents_document_number">
<span<?= $Page->document_number->viewAttributes() ?>>
<?= $Page->document_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->page_number->Visible) { // page_number ?>
        <td data-name="page_number"<?= $Page->page_number->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_page_number" class="el_notarized_documents_page_number">
<span<?= $Page->page_number->viewAttributes() ?>>
<?= $Page->page_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->book_number->Visible) { // book_number ?>
        <td data-name="book_number"<?= $Page->book_number->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_book_number" class="el_notarized_documents_book_number">
<span<?= $Page->book_number->viewAttributes() ?>>
<?= $Page->book_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->series_of->Visible) { // series_of ?>
        <td data-name="series_of"<?= $Page->series_of->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_series_of" class="el_notarized_documents_series_of">
<span<?= $Page->series_of->viewAttributes() ?>>
<?= $Page->series_of->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->doc_keycode->Visible) { // doc_keycode ?>
        <td data-name="doc_keycode"<?= $Page->doc_keycode->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_doc_keycode" class="el_notarized_documents_doc_keycode">
<span<?= $Page->doc_keycode->viewAttributes() ?>>
<?= $Page->doc_keycode->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notary_location->Visible) { // notary_location ?>
        <td data-name="notary_location"<?= $Page->notary_location->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_notary_location" class="el_notarized_documents_notary_location">
<span<?= $Page->notary_location->viewAttributes() ?>>
<?= $Page->notary_location->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notarization_date->Visible) { // notarization_date ?>
        <td data-name="notarization_date"<?= $Page->notarization_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_notarization_date" class="el_notarized_documents_notarization_date">
<span<?= $Page->notarization_date->viewAttributes() ?>>
<?= $Page->notarization_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->certificate_type->Visible) { // certificate_type ?>
        <td data-name="certificate_type"<?= $Page->certificate_type->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_certificate_type" class="el_notarized_documents_certificate_type">
<span<?= $Page->certificate_type->viewAttributes() ?>>
<?= $Page->certificate_type->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <td data-name="qr_code_path"<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_qr_code_path" class="el_notarized_documents_qr_code_path">
<span<?= $Page->qr_code_path->viewAttributes() ?>>
<?= $Page->qr_code_path->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notarized_document_path->Visible) { // notarized_document_path ?>
        <td data-name="notarized_document_path"<?= $Page->notarized_document_path->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_notarized_document_path" class="el_notarized_documents_notarized_document_path">
<span<?= $Page->notarized_document_path->viewAttributes() ?>>
<?= $Page->notarized_document_path->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->expires_at->Visible) { // expires_at ?>
        <td data-name="expires_at"<?= $Page->expires_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_expires_at" class="el_notarized_documents_expires_at">
<span<?= $Page->expires_at->viewAttributes() ?>>
<?= $Page->expires_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->revoked->Visible) { // revoked ?>
        <td data-name="revoked"<?= $Page->revoked->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_revoked" class="el_notarized_documents_revoked">
<span<?= $Page->revoked->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->revoked->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->revoked_at->Visible) { // revoked_at ?>
        <td data-name="revoked_at"<?= $Page->revoked_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_revoked_at" class="el_notarized_documents_revoked_at">
<span<?= $Page->revoked_at->viewAttributes() ?>>
<?= $Page->revoked_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->revoked_by->Visible) { // revoked_by ?>
        <td data-name="revoked_by"<?= $Page->revoked_by->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarized_documents_revoked_by" class="el_notarized_documents_revoked_by">
<span<?= $Page->revoked_by->viewAttributes() ?>>
<?= $Page->revoked_by->getViewValue() ?></span>
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
    ew.addEventHandlers("notarized_documents");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
