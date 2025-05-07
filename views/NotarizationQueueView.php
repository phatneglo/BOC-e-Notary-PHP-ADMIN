<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizationQueueView = &$Page;
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
<form name="fnotarization_queueview" id="fnotarization_queueview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarization_queue: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fnotarization_queueview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotarization_queueview")
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
<input type="hidden" name="t" value="notarization_queue">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->queue_id->Visible) { // queue_id ?>
    <tr id="r_queue_id"<?= $Page->queue_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_queue_queue_id"><?= $Page->queue_id->caption() ?></span></td>
        <td data-name="queue_id"<?= $Page->queue_id->cellAttributes() ?>>
<span id="el_notarization_queue_queue_id">
<span<?= $Page->queue_id->viewAttributes() ?>>
<?= $Page->queue_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
    <tr id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_queue_request_id"><?= $Page->request_id->caption() ?></span></td>
        <td data-name="request_id"<?= $Page->request_id->cellAttributes() ?>>
<span id="el_notarization_queue_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
    <tr id="r_notary_id"<?= $Page->notary_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_queue_notary_id"><?= $Page->notary_id->caption() ?></span></td>
        <td data-name="notary_id"<?= $Page->notary_id->cellAttributes() ?>>
<span id="el_notarization_queue_notary_id">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->queue_position->Visible) { // queue_position ?>
    <tr id="r_queue_position"<?= $Page->queue_position->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_queue_queue_position"><?= $Page->queue_position->caption() ?></span></td>
        <td data-name="queue_position"<?= $Page->queue_position->cellAttributes() ?>>
<span id="el_notarization_queue_queue_position">
<span<?= $Page->queue_position->viewAttributes() ?>>
<?= $Page->queue_position->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->entry_time->Visible) { // entry_time ?>
    <tr id="r_entry_time"<?= $Page->entry_time->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_queue_entry_time"><?= $Page->entry_time->caption() ?></span></td>
        <td data-name="entry_time"<?= $Page->entry_time->cellAttributes() ?>>
<span id="el_notarization_queue_entry_time">
<span<?= $Page->entry_time->viewAttributes() ?>>
<?= $Page->entry_time->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->processing_started_at->Visible) { // processing_started_at ?>
    <tr id="r_processing_started_at"<?= $Page->processing_started_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_queue_processing_started_at"><?= $Page->processing_started_at->caption() ?></span></td>
        <td data-name="processing_started_at"<?= $Page->processing_started_at->cellAttributes() ?>>
<span id="el_notarization_queue_processing_started_at">
<span<?= $Page->processing_started_at->viewAttributes() ?>>
<?= $Page->processing_started_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->completed_at->Visible) { // completed_at ?>
    <tr id="r_completed_at"<?= $Page->completed_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_queue_completed_at"><?= $Page->completed_at->caption() ?></span></td>
        <td data-name="completed_at"<?= $Page->completed_at->cellAttributes() ?>>
<span id="el_notarization_queue_completed_at">
<span<?= $Page->completed_at->viewAttributes() ?>>
<?= $Page->completed_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status"<?= $Page->status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_queue_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el_notarization_queue_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estimated_wait_time->Visible) { // estimated_wait_time ?>
    <tr id="r_estimated_wait_time"<?= $Page->estimated_wait_time->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarization_queue_estimated_wait_time"><?= $Page->estimated_wait_time->caption() ?></span></td>
        <td data-name="estimated_wait_time"<?= $Page->estimated_wait_time->cellAttributes() ?>>
<span id="el_notarization_queue_estimated_wait_time">
<span<?= $Page->estimated_wait_time->viewAttributes() ?>>
<?= $Page->estimated_wait_time->getViewValue() ?></span>
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
