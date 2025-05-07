<?php

namespace PHPMaker2024\eNotary;

// Page object
$PaymentTransactionsView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<form name="fpayment_transactionsview" id="fpayment_transactionsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { payment_transactions: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fpayment_transactionsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpayment_transactionsview")
        .setPageId("view")
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="payment_transactions">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->transaction_id->Visible) { // transaction_id ?>
    <tr id="r_transaction_id"<?= $Page->transaction_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_transaction_id"><?= $Page->transaction_id->caption() ?></span></td>
        <td data-name="transaction_id"<?= $Page->transaction_id->cellAttributes() ?>>
<span id="el_payment_transactions_transaction_id">
<span<?= $Page->transaction_id->viewAttributes() ?>>
<?= $Page->transaction_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
    <tr id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_request_id"><?= $Page->request_id->caption() ?></span></td>
        <td data-name="request_id"<?= $Page->request_id->cellAttributes() ?>>
<span id="el_payment_transactions_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_payment_transactions_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->payment_method_id->Visible) { // payment_method_id ?>
    <tr id="r_payment_method_id"<?= $Page->payment_method_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_payment_method_id"><?= $Page->payment_method_id->caption() ?></span></td>
        <td data-name="payment_method_id"<?= $Page->payment_method_id->cellAttributes() ?>>
<span id="el_payment_transactions_payment_method_id">
<span<?= $Page->payment_method_id->viewAttributes() ?>>
<?= $Page->payment_method_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->transaction_reference->Visible) { // transaction_reference ?>
    <tr id="r_transaction_reference"<?= $Page->transaction_reference->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_transaction_reference"><?= $Page->transaction_reference->caption() ?></span></td>
        <td data-name="transaction_reference"<?= $Page->transaction_reference->cellAttributes() ?>>
<span id="el_payment_transactions_transaction_reference">
<span<?= $Page->transaction_reference->viewAttributes() ?>>
<?= $Page->transaction_reference->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->amount->Visible) { // amount ?>
    <tr id="r_amount"<?= $Page->amount->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_amount"><?= $Page->amount->caption() ?></span></td>
        <td data-name="amount"<?= $Page->amount->cellAttributes() ?>>
<span id="el_payment_transactions_amount">
<span<?= $Page->amount->viewAttributes() ?>>
<?= $Page->amount->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->currency->Visible) { // currency ?>
    <tr id="r_currency"<?= $Page->currency->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_currency"><?= $Page->currency->caption() ?></span></td>
        <td data-name="currency"<?= $Page->currency->cellAttributes() ?>>
<span id="el_payment_transactions_currency">
<span<?= $Page->currency->viewAttributes() ?>>
<?= $Page->currency->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status"<?= $Page->status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el_payment_transactions_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->payment_date->Visible) { // payment_date ?>
    <tr id="r_payment_date"<?= $Page->payment_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_payment_date"><?= $Page->payment_date->caption() ?></span></td>
        <td data-name="payment_date"<?= $Page->payment_date->cellAttributes() ?>>
<span id="el_payment_transactions_payment_date">
<span<?= $Page->payment_date->viewAttributes() ?>>
<?= $Page->payment_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->gateway_reference->Visible) { // gateway_reference ?>
    <tr id="r_gateway_reference"<?= $Page->gateway_reference->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_gateway_reference"><?= $Page->gateway_reference->caption() ?></span></td>
        <td data-name="gateway_reference"<?= $Page->gateway_reference->cellAttributes() ?>>
<span id="el_payment_transactions_gateway_reference">
<span<?= $Page->gateway_reference->viewAttributes() ?>>
<?= $Page->gateway_reference->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->gateway_response->Visible) { // gateway_response ?>
    <tr id="r_gateway_response"<?= $Page->gateway_response->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_gateway_response"><?= $Page->gateway_response->caption() ?></span></td>
        <td data-name="gateway_response"<?= $Page->gateway_response->cellAttributes() ?>>
<span id="el_payment_transactions_gateway_response">
<span<?= $Page->gateway_response->viewAttributes() ?>>
<?= $Page->gateway_response->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
    <tr id="r_fee_amount"<?= $Page->fee_amount->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_fee_amount"><?= $Page->fee_amount->caption() ?></span></td>
        <td data-name="fee_amount"<?= $Page->fee_amount->cellAttributes() ?>>
<span id="el_payment_transactions_fee_amount">
<span<?= $Page->fee_amount->viewAttributes() ?>>
<?= $Page->fee_amount->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total_amount->Visible) { // total_amount ?>
    <tr id="r_total_amount"<?= $Page->total_amount->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_total_amount"><?= $Page->total_amount->caption() ?></span></td>
        <td data-name="total_amount"<?= $Page->total_amount->cellAttributes() ?>>
<span id="el_payment_transactions_total_amount">
<span<?= $Page->total_amount->viewAttributes() ?>>
<?= $Page->total_amount->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->payment_receipt_url->Visible) { // payment_receipt_url ?>
    <tr id="r_payment_receipt_url"<?= $Page->payment_receipt_url->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_payment_receipt_url"><?= $Page->payment_receipt_url->caption() ?></span></td>
        <td data-name="payment_receipt_url"<?= $Page->payment_receipt_url->cellAttributes() ?>>
<span id="el_payment_transactions_payment_receipt_url">
<span<?= $Page->payment_receipt_url->viewAttributes() ?>>
<?= $Page->payment_receipt_url->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
    <tr id="r_qr_code_path"<?= $Page->qr_code_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_qr_code_path"><?= $Page->qr_code_path->caption() ?></span></td>
        <td data-name="qr_code_path"<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="el_payment_transactions_qr_code_path">
<span<?= $Page->qr_code_path->viewAttributes() ?>>
<?= $Page->qr_code_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_payment_transactions_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_payment_transactions_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <tr id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_ip_address"><?= $Page->ip_address->caption() ?></span></td>
        <td data-name="ip_address"<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_payment_transactions_ip_address">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_agent->Visible) { // user_agent ?>
    <tr id="r_user_agent"<?= $Page->user_agent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_user_agent"><?= $Page->user_agent->caption() ?></span></td>
        <td data-name="user_agent"<?= $Page->user_agent->cellAttributes() ?>>
<span id="el_payment_transactions_user_agent">
<span<?= $Page->user_agent->viewAttributes() ?>>
<?= $Page->user_agent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <tr id="r_notes"<?= $Page->notes->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_transactions_notes"><?= $Page->notes->caption() ?></span></td>
        <td data-name="notes"<?= $Page->notes->cellAttributes() ?>>
<span id="el_payment_transactions_notes">
<span<?= $Page->notes->viewAttributes() ?>>
<?= $Page->notes->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
