<?php

namespace PHPMaker2024\eNotary;

// Page object
$PaymentTransactionsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { payment_transactions: currentTable } });
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
<form name="fpayment_transactionssrch" id="fpayment_transactionssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fpayment_transactionssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { payment_transactions: currentTable } });
var currentForm;
var fpayment_transactionssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fpayment_transactionssrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fpayment_transactionssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fpayment_transactionssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fpayment_transactionssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fpayment_transactionssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="payment_transactions">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_payment_transactions" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_payment_transactionslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->transaction_id->Visible) { // transaction_id ?>
        <th data-name="transaction_id" class="<?= $Page->transaction_id->headerCellClass() ?>"><div id="elh_payment_transactions_transaction_id" class="payment_transactions_transaction_id"><?= $Page->renderFieldHeader($Page->transaction_id) ?></div></th>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
        <th data-name="request_id" class="<?= $Page->request_id->headerCellClass() ?>"><div id="elh_payment_transactions_request_id" class="payment_transactions_request_id"><?= $Page->renderFieldHeader($Page->request_id) ?></div></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th data-name="user_id" class="<?= $Page->user_id->headerCellClass() ?>"><div id="elh_payment_transactions_user_id" class="payment_transactions_user_id"><?= $Page->renderFieldHeader($Page->user_id) ?></div></th>
<?php } ?>
<?php if ($Page->payment_method_id->Visible) { // payment_method_id ?>
        <th data-name="payment_method_id" class="<?= $Page->payment_method_id->headerCellClass() ?>"><div id="elh_payment_transactions_payment_method_id" class="payment_transactions_payment_method_id"><?= $Page->renderFieldHeader($Page->payment_method_id) ?></div></th>
<?php } ?>
<?php if ($Page->transaction_reference->Visible) { // transaction_reference ?>
        <th data-name="transaction_reference" class="<?= $Page->transaction_reference->headerCellClass() ?>"><div id="elh_payment_transactions_transaction_reference" class="payment_transactions_transaction_reference"><?= $Page->renderFieldHeader($Page->transaction_reference) ?></div></th>
<?php } ?>
<?php if ($Page->amount->Visible) { // amount ?>
        <th data-name="amount" class="<?= $Page->amount->headerCellClass() ?>"><div id="elh_payment_transactions_amount" class="payment_transactions_amount"><?= $Page->renderFieldHeader($Page->amount) ?></div></th>
<?php } ?>
<?php if ($Page->currency->Visible) { // currency ?>
        <th data-name="currency" class="<?= $Page->currency->headerCellClass() ?>"><div id="elh_payment_transactions_currency" class="payment_transactions_currency"><?= $Page->renderFieldHeader($Page->currency) ?></div></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th data-name="status" class="<?= $Page->status->headerCellClass() ?>"><div id="elh_payment_transactions_status" class="payment_transactions_status"><?= $Page->renderFieldHeader($Page->status) ?></div></th>
<?php } ?>
<?php if ($Page->payment_date->Visible) { // payment_date ?>
        <th data-name="payment_date" class="<?= $Page->payment_date->headerCellClass() ?>"><div id="elh_payment_transactions_payment_date" class="payment_transactions_payment_date"><?= $Page->renderFieldHeader($Page->payment_date) ?></div></th>
<?php } ?>
<?php if ($Page->gateway_reference->Visible) { // gateway_reference ?>
        <th data-name="gateway_reference" class="<?= $Page->gateway_reference->headerCellClass() ?>"><div id="elh_payment_transactions_gateway_reference" class="payment_transactions_gateway_reference"><?= $Page->renderFieldHeader($Page->gateway_reference) ?></div></th>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
        <th data-name="fee_amount" class="<?= $Page->fee_amount->headerCellClass() ?>"><div id="elh_payment_transactions_fee_amount" class="payment_transactions_fee_amount"><?= $Page->renderFieldHeader($Page->fee_amount) ?></div></th>
<?php } ?>
<?php if ($Page->total_amount->Visible) { // total_amount ?>
        <th data-name="total_amount" class="<?= $Page->total_amount->headerCellClass() ?>"><div id="elh_payment_transactions_total_amount" class="payment_transactions_total_amount"><?= $Page->renderFieldHeader($Page->total_amount) ?></div></th>
<?php } ?>
<?php if ($Page->payment_receipt_url->Visible) { // payment_receipt_url ?>
        <th data-name="payment_receipt_url" class="<?= $Page->payment_receipt_url->headerCellClass() ?>"><div id="elh_payment_transactions_payment_receipt_url" class="payment_transactions_payment_receipt_url"><?= $Page->renderFieldHeader($Page->payment_receipt_url) ?></div></th>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <th data-name="qr_code_path" class="<?= $Page->qr_code_path->headerCellClass() ?>"><div id="elh_payment_transactions_qr_code_path" class="payment_transactions_qr_code_path"><?= $Page->renderFieldHeader($Page->qr_code_path) ?></div></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th data-name="created_at" class="<?= $Page->created_at->headerCellClass() ?>"><div id="elh_payment_transactions_created_at" class="payment_transactions_created_at"><?= $Page->renderFieldHeader($Page->created_at) ?></div></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th data-name="updated_at" class="<?= $Page->updated_at->headerCellClass() ?>"><div id="elh_payment_transactions_updated_at" class="payment_transactions_updated_at"><?= $Page->renderFieldHeader($Page->updated_at) ?></div></th>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <th data-name="ip_address" class="<?= $Page->ip_address->headerCellClass() ?>"><div id="elh_payment_transactions_ip_address" class="payment_transactions_ip_address"><?= $Page->renderFieldHeader($Page->ip_address) ?></div></th>
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
    <?php if ($Page->transaction_id->Visible) { // transaction_id ?>
        <td data-name="transaction_id"<?= $Page->transaction_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_transaction_id" class="el_payment_transactions_transaction_id">
<span<?= $Page->transaction_id->viewAttributes() ?>>
<?= $Page->transaction_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->request_id->Visible) { // request_id ?>
        <td data-name="request_id"<?= $Page->request_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_request_id" class="el_payment_transactions_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->user_id->Visible) { // user_id ?>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_user_id" class="el_payment_transactions_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->payment_method_id->Visible) { // payment_method_id ?>
        <td data-name="payment_method_id"<?= $Page->payment_method_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_payment_method_id" class="el_payment_transactions_payment_method_id">
<span<?= $Page->payment_method_id->viewAttributes() ?>>
<?= $Page->payment_method_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->transaction_reference->Visible) { // transaction_reference ?>
        <td data-name="transaction_reference"<?= $Page->transaction_reference->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_transaction_reference" class="el_payment_transactions_transaction_reference">
<span<?= $Page->transaction_reference->viewAttributes() ?>>
<?= $Page->transaction_reference->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->amount->Visible) { // amount ?>
        <td data-name="amount"<?= $Page->amount->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_amount" class="el_payment_transactions_amount">
<span<?= $Page->amount->viewAttributes() ?>>
<?= $Page->amount->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->currency->Visible) { // currency ?>
        <td data-name="currency"<?= $Page->currency->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_currency" class="el_payment_transactions_currency">
<span<?= $Page->currency->viewAttributes() ?>>
<?= $Page->currency->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_status" class="el_payment_transactions_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->payment_date->Visible) { // payment_date ?>
        <td data-name="payment_date"<?= $Page->payment_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_payment_date" class="el_payment_transactions_payment_date">
<span<?= $Page->payment_date->viewAttributes() ?>>
<?= $Page->payment_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->gateway_reference->Visible) { // gateway_reference ?>
        <td data-name="gateway_reference"<?= $Page->gateway_reference->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_gateway_reference" class="el_payment_transactions_gateway_reference">
<span<?= $Page->gateway_reference->viewAttributes() ?>>
<?= $Page->gateway_reference->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fee_amount->Visible) { // fee_amount ?>
        <td data-name="fee_amount"<?= $Page->fee_amount->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_fee_amount" class="el_payment_transactions_fee_amount">
<span<?= $Page->fee_amount->viewAttributes() ?>>
<?= $Page->fee_amount->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total_amount->Visible) { // total_amount ?>
        <td data-name="total_amount"<?= $Page->total_amount->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_total_amount" class="el_payment_transactions_total_amount">
<span<?= $Page->total_amount->viewAttributes() ?>>
<?= $Page->total_amount->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->payment_receipt_url->Visible) { // payment_receipt_url ?>
        <td data-name="payment_receipt_url"<?= $Page->payment_receipt_url->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_payment_receipt_url" class="el_payment_transactions_payment_receipt_url">
<span<?= $Page->payment_receipt_url->viewAttributes() ?>>
<?= $Page->payment_receipt_url->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <td data-name="qr_code_path"<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_qr_code_path" class="el_payment_transactions_qr_code_path">
<span<?= $Page->qr_code_path->viewAttributes() ?>>
<?= $Page->qr_code_path->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->created_at->Visible) { // created_at ?>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_created_at" class="el_payment_transactions_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_updated_at" class="el_payment_transactions_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ip_address->Visible) { // ip_address ?>
        <td data-name="ip_address"<?= $Page->ip_address->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_payment_transactions_ip_address" class="el_payment_transactions_ip_address">
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
    ew.addEventHandlers("payment_transactions");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
