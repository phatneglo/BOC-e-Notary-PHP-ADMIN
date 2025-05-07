<?php

namespace PHPMaker2024\eNotary;

// Page object
$PaymentTransactionsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { payment_transactions: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fpayment_transactionsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpayment_transactionsdelete")
        .setPageId("delete")
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpayment_transactionsdelete" id="fpayment_transactionsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="payment_transactions">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->transaction_id->Visible) { // transaction_id ?>
        <th class="<?= $Page->transaction_id->headerCellClass() ?>"><span id="elh_payment_transactions_transaction_id" class="payment_transactions_transaction_id"><?= $Page->transaction_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
        <th class="<?= $Page->request_id->headerCellClass() ?>"><span id="elh_payment_transactions_request_id" class="payment_transactions_request_id"><?= $Page->request_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th class="<?= $Page->user_id->headerCellClass() ?>"><span id="elh_payment_transactions_user_id" class="payment_transactions_user_id"><?= $Page->user_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->payment_method_id->Visible) { // payment_method_id ?>
        <th class="<?= $Page->payment_method_id->headerCellClass() ?>"><span id="elh_payment_transactions_payment_method_id" class="payment_transactions_payment_method_id"><?= $Page->payment_method_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->transaction_reference->Visible) { // transaction_reference ?>
        <th class="<?= $Page->transaction_reference->headerCellClass() ?>"><span id="elh_payment_transactions_transaction_reference" class="payment_transactions_transaction_reference"><?= $Page->transaction_reference->caption() ?></span></th>
<?php } ?>
<?php if ($Page->amount->Visible) { // amount ?>
        <th class="<?= $Page->amount->headerCellClass() ?>"><span id="elh_payment_transactions_amount" class="payment_transactions_amount"><?= $Page->amount->caption() ?></span></th>
<?php } ?>
<?php if ($Page->currency->Visible) { // currency ?>
        <th class="<?= $Page->currency->headerCellClass() ?>"><span id="elh_payment_transactions_currency" class="payment_transactions_currency"><?= $Page->currency->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_payment_transactions_status" class="payment_transactions_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->payment_date->Visible) { // payment_date ?>
        <th class="<?= $Page->payment_date->headerCellClass() ?>"><span id="elh_payment_transactions_payment_date" class="payment_transactions_payment_date"><?= $Page->payment_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->gateway_reference->Visible) { // gateway_reference ?>
        <th class="<?= $Page->gateway_reference->headerCellClass() ?>"><span id="elh_payment_transactions_gateway_reference" class="payment_transactions_gateway_reference"><?= $Page->gateway_reference->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
        <th class="<?= $Page->fee_amount->headerCellClass() ?>"><span id="elh_payment_transactions_fee_amount" class="payment_transactions_fee_amount"><?= $Page->fee_amount->caption() ?></span></th>
<?php } ?>
<?php if ($Page->total_amount->Visible) { // total_amount ?>
        <th class="<?= $Page->total_amount->headerCellClass() ?>"><span id="elh_payment_transactions_total_amount" class="payment_transactions_total_amount"><?= $Page->total_amount->caption() ?></span></th>
<?php } ?>
<?php if ($Page->payment_receipt_url->Visible) { // payment_receipt_url ?>
        <th class="<?= $Page->payment_receipt_url->headerCellClass() ?>"><span id="elh_payment_transactions_payment_receipt_url" class="payment_transactions_payment_receipt_url"><?= $Page->payment_receipt_url->caption() ?></span></th>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <th class="<?= $Page->qr_code_path->headerCellClass() ?>"><span id="elh_payment_transactions_qr_code_path" class="payment_transactions_qr_code_path"><?= $Page->qr_code_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_payment_transactions_created_at" class="payment_transactions_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_payment_transactions_updated_at" class="payment_transactions_updated_at"><?= $Page->updated_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <th class="<?= $Page->ip_address->headerCellClass() ?>"><span id="elh_payment_transactions_ip_address" class="payment_transactions_ip_address"><?= $Page->ip_address->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while ($Page->fetch()) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = RowType::VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->CurrentRow);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->transaction_id->Visible) { // transaction_id ?>
        <td<?= $Page->transaction_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->transaction_id->viewAttributes() ?>>
<?= $Page->transaction_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
        <td<?= $Page->request_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <td<?= $Page->user_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->payment_method_id->Visible) { // payment_method_id ?>
        <td<?= $Page->payment_method_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->payment_method_id->viewAttributes() ?>>
<?= $Page->payment_method_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->transaction_reference->Visible) { // transaction_reference ?>
        <td<?= $Page->transaction_reference->cellAttributes() ?>>
<span id="">
<span<?= $Page->transaction_reference->viewAttributes() ?>>
<?= $Page->transaction_reference->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->amount->Visible) { // amount ?>
        <td<?= $Page->amount->cellAttributes() ?>>
<span id="">
<span<?= $Page->amount->viewAttributes() ?>>
<?= $Page->amount->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->currency->Visible) { // currency ?>
        <td<?= $Page->currency->cellAttributes() ?>>
<span id="">
<span<?= $Page->currency->viewAttributes() ?>>
<?= $Page->currency->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <td<?= $Page->status->cellAttributes() ?>>
<span id="">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->payment_date->Visible) { // payment_date ?>
        <td<?= $Page->payment_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->payment_date->viewAttributes() ?>>
<?= $Page->payment_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->gateway_reference->Visible) { // gateway_reference ?>
        <td<?= $Page->gateway_reference->cellAttributes() ?>>
<span id="">
<span<?= $Page->gateway_reference->viewAttributes() ?>>
<?= $Page->gateway_reference->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
        <td<?= $Page->fee_amount->cellAttributes() ?>>
<span id="">
<span<?= $Page->fee_amount->viewAttributes() ?>>
<?= $Page->fee_amount->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->total_amount->Visible) { // total_amount ?>
        <td<?= $Page->total_amount->cellAttributes() ?>>
<span id="">
<span<?= $Page->total_amount->viewAttributes() ?>>
<?= $Page->total_amount->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->payment_receipt_url->Visible) { // payment_receipt_url ?>
        <td<?= $Page->payment_receipt_url->cellAttributes() ?>>
<span id="">
<span<?= $Page->payment_receipt_url->viewAttributes() ?>>
<?= $Page->payment_receipt_url->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <td<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->qr_code_path->viewAttributes() ?>>
<?= $Page->qr_code_path->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <td<?= $Page->created_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td<?= $Page->updated_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <td<?= $Page->ip_address->cellAttributes() ?>>
<span id="">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
}
$Page->Recordset?->free();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
