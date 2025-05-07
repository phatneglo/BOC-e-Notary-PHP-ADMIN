<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizationRequestsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarization_requests: currentTable } });
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
<form name="fnotarization_requestssrch" id="fnotarization_requestssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fnotarization_requestssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarization_requests: currentTable } });
var currentForm;
var fnotarization_requestssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fnotarization_requestssrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fnotarization_requestssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fnotarization_requestssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fnotarization_requestssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fnotarization_requestssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="notarization_requests">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_notarization_requests" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_notarization_requestslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->request_id->Visible) { // request_id ?>
        <th data-name="request_id" class="<?= $Page->request_id->headerCellClass() ?>"><div id="elh_notarization_requests_request_id" class="notarization_requests_request_id"><?= $Page->renderFieldHeader($Page->request_id) ?></div></th>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th data-name="document_id" class="<?= $Page->document_id->headerCellClass() ?>"><div id="elh_notarization_requests_document_id" class="notarization_requests_document_id"><?= $Page->renderFieldHeader($Page->document_id) ?></div></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th data-name="user_id" class="<?= $Page->user_id->headerCellClass() ?>"><div id="elh_notarization_requests_user_id" class="notarization_requests_user_id"><?= $Page->renderFieldHeader($Page->user_id) ?></div></th>
<?php } ?>
<?php if ($Page->request_reference->Visible) { // request_reference ?>
        <th data-name="request_reference" class="<?= $Page->request_reference->headerCellClass() ?>"><div id="elh_notarization_requests_request_reference" class="notarization_requests_request_reference"><?= $Page->renderFieldHeader($Page->request_reference) ?></div></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th data-name="status" class="<?= $Page->status->headerCellClass() ?>"><div id="elh_notarization_requests_status" class="notarization_requests_status"><?= $Page->renderFieldHeader($Page->status) ?></div></th>
<?php } ?>
<?php if ($Page->requested_at->Visible) { // requested_at ?>
        <th data-name="requested_at" class="<?= $Page->requested_at->headerCellClass() ?>"><div id="elh_notarization_requests_requested_at" class="notarization_requests_requested_at"><?= $Page->renderFieldHeader($Page->requested_at) ?></div></th>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <th data-name="notary_id" class="<?= $Page->notary_id->headerCellClass() ?>"><div id="elh_notarization_requests_notary_id" class="notarization_requests_notary_id"><?= $Page->renderFieldHeader($Page->notary_id) ?></div></th>
<?php } ?>
<?php if ($Page->assigned_at->Visible) { // assigned_at ?>
        <th data-name="assigned_at" class="<?= $Page->assigned_at->headerCellClass() ?>"><div id="elh_notarization_requests_assigned_at" class="notarization_requests_assigned_at"><?= $Page->renderFieldHeader($Page->assigned_at) ?></div></th>
<?php } ?>
<?php if ($Page->notarized_at->Visible) { // notarized_at ?>
        <th data-name="notarized_at" class="<?= $Page->notarized_at->headerCellClass() ?>"><div id="elh_notarization_requests_notarized_at" class="notarization_requests_notarized_at"><?= $Page->renderFieldHeader($Page->notarized_at) ?></div></th>
<?php } ?>
<?php if ($Page->rejected_at->Visible) { // rejected_at ?>
        <th data-name="rejected_at" class="<?= $Page->rejected_at->headerCellClass() ?>"><div id="elh_notarization_requests_rejected_at" class="notarization_requests_rejected_at"><?= $Page->renderFieldHeader($Page->rejected_at) ?></div></th>
<?php } ?>
<?php if ($Page->rejected_by->Visible) { // rejected_by ?>
        <th data-name="rejected_by" class="<?= $Page->rejected_by->headerCellClass() ?>"><div id="elh_notarization_requests_rejected_by" class="notarization_requests_rejected_by"><?= $Page->renderFieldHeader($Page->rejected_by) ?></div></th>
<?php } ?>
<?php if ($Page->priority->Visible) { // priority ?>
        <th data-name="priority" class="<?= $Page->priority->headerCellClass() ?>"><div id="elh_notarization_requests_priority" class="notarization_requests_priority"><?= $Page->renderFieldHeader($Page->priority) ?></div></th>
<?php } ?>
<?php if ($Page->payment_status->Visible) { // payment_status ?>
        <th data-name="payment_status" class="<?= $Page->payment_status->headerCellClass() ?>"><div id="elh_notarization_requests_payment_status" class="notarization_requests_payment_status"><?= $Page->renderFieldHeader($Page->payment_status) ?></div></th>
<?php } ?>
<?php if ($Page->payment_transaction_id->Visible) { // payment_transaction_id ?>
        <th data-name="payment_transaction_id" class="<?= $Page->payment_transaction_id->headerCellClass() ?>"><div id="elh_notarization_requests_payment_transaction_id" class="notarization_requests_payment_transaction_id"><?= $Page->renderFieldHeader($Page->payment_transaction_id) ?></div></th>
<?php } ?>
<?php if ($Page->modified_at->Visible) { // modified_at ?>
        <th data-name="modified_at" class="<?= $Page->modified_at->headerCellClass() ?>"><div id="elh_notarization_requests_modified_at" class="notarization_requests_modified_at"><?= $Page->renderFieldHeader($Page->modified_at) ?></div></th>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <th data-name="ip_address" class="<?= $Page->ip_address->headerCellClass() ?>"><div id="elh_notarization_requests_ip_address" class="notarization_requests_ip_address"><?= $Page->renderFieldHeader($Page->ip_address) ?></div></th>
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
    <?php if ($Page->request_id->Visible) { // request_id ?>
        <td data-name="request_id"<?= $Page->request_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_request_id" class="el_notarization_requests_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->document_id->Visible) { // document_id ?>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_document_id" class="el_notarization_requests_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->user_id->Visible) { // user_id ?>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_user_id" class="el_notarization_requests_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->request_reference->Visible) { // request_reference ?>
        <td data-name="request_reference"<?= $Page->request_reference->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_request_reference" class="el_notarization_requests_request_reference">
<span<?= $Page->request_reference->viewAttributes() ?>>
<?= $Page->request_reference->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_status" class="el_notarization_requests_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->requested_at->Visible) { // requested_at ?>
        <td data-name="requested_at"<?= $Page->requested_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_requested_at" class="el_notarization_requests_requested_at">
<span<?= $Page->requested_at->viewAttributes() ?>>
<?= $Page->requested_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notary_id->Visible) { // notary_id ?>
        <td data-name="notary_id"<?= $Page->notary_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_notary_id" class="el_notarization_requests_notary_id">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->assigned_at->Visible) { // assigned_at ?>
        <td data-name="assigned_at"<?= $Page->assigned_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_assigned_at" class="el_notarization_requests_assigned_at">
<span<?= $Page->assigned_at->viewAttributes() ?>>
<?= $Page->assigned_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notarized_at->Visible) { // notarized_at ?>
        <td data-name="notarized_at"<?= $Page->notarized_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_notarized_at" class="el_notarization_requests_notarized_at">
<span<?= $Page->notarized_at->viewAttributes() ?>>
<?= $Page->notarized_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rejected_at->Visible) { // rejected_at ?>
        <td data-name="rejected_at"<?= $Page->rejected_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_rejected_at" class="el_notarization_requests_rejected_at">
<span<?= $Page->rejected_at->viewAttributes() ?>>
<?= $Page->rejected_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rejected_by->Visible) { // rejected_by ?>
        <td data-name="rejected_by"<?= $Page->rejected_by->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_rejected_by" class="el_notarization_requests_rejected_by">
<span<?= $Page->rejected_by->viewAttributes() ?>>
<?= $Page->rejected_by->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->priority->Visible) { // priority ?>
        <td data-name="priority"<?= $Page->priority->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_priority" class="el_notarization_requests_priority">
<span<?= $Page->priority->viewAttributes() ?>>
<?= $Page->priority->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->payment_status->Visible) { // payment_status ?>
        <td data-name="payment_status"<?= $Page->payment_status->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_payment_status" class="el_notarization_requests_payment_status">
<span<?= $Page->payment_status->viewAttributes() ?>>
<?= $Page->payment_status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->payment_transaction_id->Visible) { // payment_transaction_id ?>
        <td data-name="payment_transaction_id"<?= $Page->payment_transaction_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_payment_transaction_id" class="el_notarization_requests_payment_transaction_id">
<span<?= $Page->payment_transaction_id->viewAttributes() ?>>
<?= $Page->payment_transaction_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->modified_at->Visible) { // modified_at ?>
        <td data-name="modified_at"<?= $Page->modified_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_modified_at" class="el_notarization_requests_modified_at">
<span<?= $Page->modified_at->viewAttributes() ?>>
<?= $Page->modified_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ip_address->Visible) { // ip_address ?>
        <td data-name="ip_address"<?= $Page->ip_address->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notarization_requests_ip_address" class="el_notarization_requests_ip_address">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
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
    ew.addEventHandlers("notarization_requests");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
