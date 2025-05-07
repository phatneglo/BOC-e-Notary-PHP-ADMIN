<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizationRequestsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarization_requests: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fnotarization_requestsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotarization_requestsdelete")
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
<form name="fnotarization_requestsdelete" id="fnotarization_requestsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notarization_requests">
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
<?php if ($Page->request_id->Visible) { // request_id ?>
        <th class="<?= $Page->request_id->headerCellClass() ?>"><span id="elh_notarization_requests_request_id" class="notarization_requests_request_id"><?= $Page->request_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th class="<?= $Page->document_id->headerCellClass() ?>"><span id="elh_notarization_requests_document_id" class="notarization_requests_document_id"><?= $Page->document_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th class="<?= $Page->user_id->headerCellClass() ?>"><span id="elh_notarization_requests_user_id" class="notarization_requests_user_id"><?= $Page->user_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->request_reference->Visible) { // request_reference ?>
        <th class="<?= $Page->request_reference->headerCellClass() ?>"><span id="elh_notarization_requests_request_reference" class="notarization_requests_request_reference"><?= $Page->request_reference->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_notarization_requests_status" class="notarization_requests_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->requested_at->Visible) { // requested_at ?>
        <th class="<?= $Page->requested_at->headerCellClass() ?>"><span id="elh_notarization_requests_requested_at" class="notarization_requests_requested_at"><?= $Page->requested_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <th class="<?= $Page->notary_id->headerCellClass() ?>"><span id="elh_notarization_requests_notary_id" class="notarization_requests_notary_id"><?= $Page->notary_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->assigned_at->Visible) { // assigned_at ?>
        <th class="<?= $Page->assigned_at->headerCellClass() ?>"><span id="elh_notarization_requests_assigned_at" class="notarization_requests_assigned_at"><?= $Page->assigned_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notarized_at->Visible) { // notarized_at ?>
        <th class="<?= $Page->notarized_at->headerCellClass() ?>"><span id="elh_notarization_requests_notarized_at" class="notarization_requests_notarized_at"><?= $Page->notarized_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rejected_at->Visible) { // rejected_at ?>
        <th class="<?= $Page->rejected_at->headerCellClass() ?>"><span id="elh_notarization_requests_rejected_at" class="notarization_requests_rejected_at"><?= $Page->rejected_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rejected_by->Visible) { // rejected_by ?>
        <th class="<?= $Page->rejected_by->headerCellClass() ?>"><span id="elh_notarization_requests_rejected_by" class="notarization_requests_rejected_by"><?= $Page->rejected_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->priority->Visible) { // priority ?>
        <th class="<?= $Page->priority->headerCellClass() ?>"><span id="elh_notarization_requests_priority" class="notarization_requests_priority"><?= $Page->priority->caption() ?></span></th>
<?php } ?>
<?php if ($Page->payment_status->Visible) { // payment_status ?>
        <th class="<?= $Page->payment_status->headerCellClass() ?>"><span id="elh_notarization_requests_payment_status" class="notarization_requests_payment_status"><?= $Page->payment_status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->payment_transaction_id->Visible) { // payment_transaction_id ?>
        <th class="<?= $Page->payment_transaction_id->headerCellClass() ?>"><span id="elh_notarization_requests_payment_transaction_id" class="notarization_requests_payment_transaction_id"><?= $Page->payment_transaction_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->modified_at->Visible) { // modified_at ?>
        <th class="<?= $Page->modified_at->headerCellClass() ?>"><span id="elh_notarization_requests_modified_at" class="notarization_requests_modified_at"><?= $Page->modified_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <th class="<?= $Page->ip_address->headerCellClass() ?>"><span id="elh_notarization_requests_ip_address" class="notarization_requests_ip_address"><?= $Page->ip_address->caption() ?></span></th>
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
<?php if ($Page->request_id->Visible) { // request_id ?>
        <td<?= $Page->request_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <td<?= $Page->document_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
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
<?php if ($Page->request_reference->Visible) { // request_reference ?>
        <td<?= $Page->request_reference->cellAttributes() ?>>
<span id="">
<span<?= $Page->request_reference->viewAttributes() ?>>
<?= $Page->request_reference->getViewValue() ?></span>
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
<?php if ($Page->requested_at->Visible) { // requested_at ?>
        <td<?= $Page->requested_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->requested_at->viewAttributes() ?>>
<?= $Page->requested_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <td<?= $Page->notary_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->assigned_at->Visible) { // assigned_at ?>
        <td<?= $Page->assigned_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->assigned_at->viewAttributes() ?>>
<?= $Page->assigned_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->notarized_at->Visible) { // notarized_at ?>
        <td<?= $Page->notarized_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->notarized_at->viewAttributes() ?>>
<?= $Page->notarized_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->rejected_at->Visible) { // rejected_at ?>
        <td<?= $Page->rejected_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->rejected_at->viewAttributes() ?>>
<?= $Page->rejected_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->rejected_by->Visible) { // rejected_by ?>
        <td<?= $Page->rejected_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->rejected_by->viewAttributes() ?>>
<?= $Page->rejected_by->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->priority->Visible) { // priority ?>
        <td<?= $Page->priority->cellAttributes() ?>>
<span id="">
<span<?= $Page->priority->viewAttributes() ?>>
<?= $Page->priority->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->payment_status->Visible) { // payment_status ?>
        <td<?= $Page->payment_status->cellAttributes() ?>>
<span id="">
<span<?= $Page->payment_status->viewAttributes() ?>>
<?= $Page->payment_status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->payment_transaction_id->Visible) { // payment_transaction_id ?>
        <td<?= $Page->payment_transaction_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->payment_transaction_id->viewAttributes() ?>>
<?= $Page->payment_transaction_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->modified_at->Visible) { // modified_at ?>
        <td<?= $Page->modified_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->modified_at->viewAttributes() ?>>
<?= $Page->modified_at->getViewValue() ?></span>
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
