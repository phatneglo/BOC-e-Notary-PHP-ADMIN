<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizationQueueDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarization_queue: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fnotarization_queuedelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotarization_queuedelete")
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
<form name="fnotarization_queuedelete" id="fnotarization_queuedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notarization_queue">
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
<?php if ($Page->queue_id->Visible) { // queue_id ?>
        <th class="<?= $Page->queue_id->headerCellClass() ?>"><span id="elh_notarization_queue_queue_id" class="notarization_queue_queue_id"><?= $Page->queue_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
        <th class="<?= $Page->request_id->headerCellClass() ?>"><span id="elh_notarization_queue_request_id" class="notarization_queue_request_id"><?= $Page->request_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <th class="<?= $Page->notary_id->headerCellClass() ?>"><span id="elh_notarization_queue_notary_id" class="notarization_queue_notary_id"><?= $Page->notary_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->queue_position->Visible) { // queue_position ?>
        <th class="<?= $Page->queue_position->headerCellClass() ?>"><span id="elh_notarization_queue_queue_position" class="notarization_queue_queue_position"><?= $Page->queue_position->caption() ?></span></th>
<?php } ?>
<?php if ($Page->entry_time->Visible) { // entry_time ?>
        <th class="<?= $Page->entry_time->headerCellClass() ?>"><span id="elh_notarization_queue_entry_time" class="notarization_queue_entry_time"><?= $Page->entry_time->caption() ?></span></th>
<?php } ?>
<?php if ($Page->processing_started_at->Visible) { // processing_started_at ?>
        <th class="<?= $Page->processing_started_at->headerCellClass() ?>"><span id="elh_notarization_queue_processing_started_at" class="notarization_queue_processing_started_at"><?= $Page->processing_started_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->completed_at->Visible) { // completed_at ?>
        <th class="<?= $Page->completed_at->headerCellClass() ?>"><span id="elh_notarization_queue_completed_at" class="notarization_queue_completed_at"><?= $Page->completed_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_notarization_queue_status" class="notarization_queue_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->estimated_wait_time->Visible) { // estimated_wait_time ?>
        <th class="<?= $Page->estimated_wait_time->headerCellClass() ?>"><span id="elh_notarization_queue_estimated_wait_time" class="notarization_queue_estimated_wait_time"><?= $Page->estimated_wait_time->caption() ?></span></th>
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
<?php if ($Page->queue_id->Visible) { // queue_id ?>
        <td<?= $Page->queue_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->queue_id->viewAttributes() ?>>
<?= $Page->queue_id->getViewValue() ?></span>
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
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <td<?= $Page->notary_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->queue_position->Visible) { // queue_position ?>
        <td<?= $Page->queue_position->cellAttributes() ?>>
<span id="">
<span<?= $Page->queue_position->viewAttributes() ?>>
<?= $Page->queue_position->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->entry_time->Visible) { // entry_time ?>
        <td<?= $Page->entry_time->cellAttributes() ?>>
<span id="">
<span<?= $Page->entry_time->viewAttributes() ?>>
<?= $Page->entry_time->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->processing_started_at->Visible) { // processing_started_at ?>
        <td<?= $Page->processing_started_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->processing_started_at->viewAttributes() ?>>
<?= $Page->processing_started_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->completed_at->Visible) { // completed_at ?>
        <td<?= $Page->completed_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->completed_at->viewAttributes() ?>>
<?= $Page->completed_at->getViewValue() ?></span>
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
<?php if ($Page->estimated_wait_time->Visible) { // estimated_wait_time ?>
        <td<?= $Page->estimated_wait_time->cellAttributes() ?>>
<span id="">
<span<?= $Page->estimated_wait_time->viewAttributes() ?>>
<?= $Page->estimated_wait_time->getViewValue() ?></span>
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
