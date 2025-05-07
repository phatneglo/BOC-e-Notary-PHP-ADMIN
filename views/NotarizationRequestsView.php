<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizationRequestsView = &$Page;
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
<form name="fnotarization_requestsview" id="fnotarization_requestsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarization_requests: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fnotarization_requestsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotarization_requestsview")
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
<input type="hidden" name="t" value="notarization_requests">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->request_id->Visible) { // request_id ?>
    <tr id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_request_id"><?= $Page->request_id->caption() ?></span></td>
        <td data-name="request_id"<?= $Page->request_id->cellAttributes() ?>>
<span id="el_notarization_requests_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <tr id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_document_id"><?= $Page->document_id->caption() ?></span></td>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el_notarization_requests_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_notarization_requests_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->request_reference->Visible) { // request_reference ?>
    <tr id="r_request_reference"<?= $Page->request_reference->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_request_reference"><?= $Page->request_reference->caption() ?></span></td>
        <td data-name="request_reference"<?= $Page->request_reference->cellAttributes() ?>>
<span id="el_notarization_requests_request_reference">
<span<?= $Page->request_reference->viewAttributes() ?>>
<?= $Page->request_reference->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status"<?= $Page->status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el_notarization_requests_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->requested_at->Visible) { // requested_at ?>
    <tr id="r_requested_at"<?= $Page->requested_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_requested_at"><?= $Page->requested_at->caption() ?></span></td>
        <td data-name="requested_at"<?= $Page->requested_at->cellAttributes() ?>>
<span id="el_notarization_requests_requested_at">
<span<?= $Page->requested_at->viewAttributes() ?>>
<?= $Page->requested_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
    <tr id="r_notary_id"<?= $Page->notary_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_notary_id"><?= $Page->notary_id->caption() ?></span></td>
        <td data-name="notary_id"<?= $Page->notary_id->cellAttributes() ?>>
<span id="el_notarization_requests_notary_id">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->assigned_at->Visible) { // assigned_at ?>
    <tr id="r_assigned_at"<?= $Page->assigned_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_assigned_at"><?= $Page->assigned_at->caption() ?></span></td>
        <td data-name="assigned_at"<?= $Page->assigned_at->cellAttributes() ?>>
<span id="el_notarization_requests_assigned_at">
<span<?= $Page->assigned_at->viewAttributes() ?>>
<?= $Page->assigned_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notarized_at->Visible) { // notarized_at ?>
    <tr id="r_notarized_at"<?= $Page->notarized_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_notarized_at"><?= $Page->notarized_at->caption() ?></span></td>
        <td data-name="notarized_at"<?= $Page->notarized_at->cellAttributes() ?>>
<span id="el_notarization_requests_notarized_at">
<span<?= $Page->notarized_at->viewAttributes() ?>>
<?= $Page->notarized_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rejection_reason->Visible) { // rejection_reason ?>
    <tr id="r_rejection_reason"<?= $Page->rejection_reason->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_rejection_reason"><?= $Page->rejection_reason->caption() ?></span></td>
        <td data-name="rejection_reason"<?= $Page->rejection_reason->cellAttributes() ?>>
<span id="el_notarization_requests_rejection_reason">
<span<?= $Page->rejection_reason->viewAttributes() ?>>
<?= $Page->rejection_reason->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rejected_at->Visible) { // rejected_at ?>
    <tr id="r_rejected_at"<?= $Page->rejected_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_rejected_at"><?= $Page->rejected_at->caption() ?></span></td>
        <td data-name="rejected_at"<?= $Page->rejected_at->cellAttributes() ?>>
<span id="el_notarization_requests_rejected_at">
<span<?= $Page->rejected_at->viewAttributes() ?>>
<?= $Page->rejected_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rejected_by->Visible) { // rejected_by ?>
    <tr id="r_rejected_by"<?= $Page->rejected_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_rejected_by"><?= $Page->rejected_by->caption() ?></span></td>
        <td data-name="rejected_by"<?= $Page->rejected_by->cellAttributes() ?>>
<span id="el_notarization_requests_rejected_by">
<span<?= $Page->rejected_by->viewAttributes() ?>>
<?= $Page->rejected_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->priority->Visible) { // priority ?>
    <tr id="r_priority"<?= $Page->priority->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_priority"><?= $Page->priority->caption() ?></span></td>
        <td data-name="priority"<?= $Page->priority->cellAttributes() ?>>
<span id="el_notarization_requests_priority">
<span<?= $Page->priority->viewAttributes() ?>>
<?= $Page->priority->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->payment_status->Visible) { // payment_status ?>
    <tr id="r_payment_status"<?= $Page->payment_status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_payment_status"><?= $Page->payment_status->caption() ?></span></td>
        <td data-name="payment_status"<?= $Page->payment_status->cellAttributes() ?>>
<span id="el_notarization_requests_payment_status">
<span<?= $Page->payment_status->viewAttributes() ?>>
<?= $Page->payment_status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->payment_transaction_id->Visible) { // payment_transaction_id ?>
    <tr id="r_payment_transaction_id"<?= $Page->payment_transaction_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_payment_transaction_id"><?= $Page->payment_transaction_id->caption() ?></span></td>
        <td data-name="payment_transaction_id"<?= $Page->payment_transaction_id->cellAttributes() ?>>
<span id="el_notarization_requests_payment_transaction_id">
<span<?= $Page->payment_transaction_id->viewAttributes() ?>>
<?= $Page->payment_transaction_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->modified_at->Visible) { // modified_at ?>
    <tr id="r_modified_at"<?= $Page->modified_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_modified_at"><?= $Page->modified_at->caption() ?></span></td>
        <td data-name="modified_at"<?= $Page->modified_at->cellAttributes() ?>>
<span id="el_notarization_requests_modified_at">
<span<?= $Page->modified_at->viewAttributes() ?>>
<?= $Page->modified_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <tr id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_ip_address"><?= $Page->ip_address->caption() ?></span></td>
        <td data-name="ip_address"<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_notarization_requests_ip_address">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->browser_info->Visible) { // browser_info ?>
    <tr id="r_browser_info"<?= $Page->browser_info->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_browser_info"><?= $Page->browser_info->caption() ?></span></td>
        <td data-name="browser_info"<?= $Page->browser_info->cellAttributes() ?>>
<span id="el_notarization_requests_browser_info">
<span<?= $Page->browser_info->viewAttributes() ?>>
<?= $Page->browser_info->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->device_info->Visible) { // device_info ?>
    <tr id="r_device_info"<?= $Page->device_info->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_requests_device_info"><?= $Page->device_info->caption() ?></span></td>
        <td data-name="device_info"<?= $Page->device_info->cellAttributes() ?>>
<span id="el_notarization_requests_device_info">
<span<?= $Page->device_info->viewAttributes() ?>>
<?= $Page->device_info->getViewValue() ?></span>
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
