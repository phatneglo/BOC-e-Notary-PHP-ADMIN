<?php

namespace PHPMaker2024\eNotary;

// Page object
$SystemStatusView = &$Page;
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
<form name="fsystem_statusview" id="fsystem_statusview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { system_status: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fsystem_statusview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsystem_statusview")
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
<input type="hidden" name="t" value="system_status">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->status_id->Visible) { // status_id ?>
    <tr id="r_status_id"<?= $Page->status_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_system_status_status_id"><?= $Page->status_id->caption() ?></span></td>
        <td data-name="status_id"<?= $Page->status_id->cellAttributes() ?>>
<span id="el_system_status_status_id">
<span<?= $Page->status_id->viewAttributes() ?>>
<?= $Page->status_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status"<?= $Page->status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_system_status_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el_system_status_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_message->Visible) { // message ?>
    <tr id="r__message"<?= $Page->_message->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_system_status__message"><?= $Page->_message->caption() ?></span></td>
        <td data-name="_message"<?= $Page->_message->cellAttributes() ?>>
<span id="el_system_status__message">
<span<?= $Page->_message->viewAttributes() ?>>
<?= $Page->_message->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->uptime->Visible) { // uptime ?>
    <tr id="r_uptime"<?= $Page->uptime->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_system_status_uptime"><?= $Page->uptime->caption() ?></span></td>
        <td data-name="uptime"<?= $Page->uptime->cellAttributes() ?>>
<span id="el_system_status_uptime">
<span<?= $Page->uptime->viewAttributes() ?>>
<?= $Page->uptime->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->active_users->Visible) { // active_users ?>
    <tr id="r_active_users"<?= $Page->active_users->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_system_status_active_users"><?= $Page->active_users->caption() ?></span></td>
        <td data-name="active_users"<?= $Page->active_users->cellAttributes() ?>>
<span id="el_system_status_active_users">
<span<?= $Page->active_users->viewAttributes() ?>>
<?= $Page->active_users->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->queue_size->Visible) { // queue_size ?>
    <tr id="r_queue_size"<?= $Page->queue_size->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_system_status_queue_size"><?= $Page->queue_size->caption() ?></span></td>
        <td data-name="queue_size"<?= $Page->queue_size->cellAttributes() ?>>
<span id="el_system_status_queue_size">
<span<?= $Page->queue_size->viewAttributes() ?>>
<?= $Page->queue_size->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->average_processing_time->Visible) { // average_processing_time ?>
    <tr id="r_average_processing_time"<?= $Page->average_processing_time->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_system_status_average_processing_time"><?= $Page->average_processing_time->caption() ?></span></td>
        <td data-name="average_processing_time"<?= $Page->average_processing_time->cellAttributes() ?>>
<span id="el_system_status_average_processing_time">
<span<?= $Page->average_processing_time->viewAttributes() ?>>
<?= $Page->average_processing_time->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_system_status_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_system_status_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
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
