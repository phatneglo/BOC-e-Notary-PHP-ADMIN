<?php

namespace PHPMaker2024\eNotary;

// Page object
$SystemStatusDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { system_status: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fsystem_statusdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsystem_statusdelete")
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
<form name="fsystem_statusdelete" id="fsystem_statusdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="system_status">
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
<?php if ($Page->status_id->Visible) { // status_id ?>
        <th class="<?= $Page->status_id->headerCellClass() ?>"><span id="elh_system_status_status_id" class="system_status_status_id"><?= $Page->status_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_system_status_status" class="system_status_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->uptime->Visible) { // uptime ?>
        <th class="<?= $Page->uptime->headerCellClass() ?>"><span id="elh_system_status_uptime" class="system_status_uptime"><?= $Page->uptime->caption() ?></span></th>
<?php } ?>
<?php if ($Page->active_users->Visible) { // active_users ?>
        <th class="<?= $Page->active_users->headerCellClass() ?>"><span id="elh_system_status_active_users" class="system_status_active_users"><?= $Page->active_users->caption() ?></span></th>
<?php } ?>
<?php if ($Page->queue_size->Visible) { // queue_size ?>
        <th class="<?= $Page->queue_size->headerCellClass() ?>"><span id="elh_system_status_queue_size" class="system_status_queue_size"><?= $Page->queue_size->caption() ?></span></th>
<?php } ?>
<?php if ($Page->average_processing_time->Visible) { // average_processing_time ?>
        <th class="<?= $Page->average_processing_time->headerCellClass() ?>"><span id="elh_system_status_average_processing_time" class="system_status_average_processing_time"><?= $Page->average_processing_time->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_system_status_created_at" class="system_status_created_at"><?= $Page->created_at->caption() ?></span></th>
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
<?php if ($Page->status_id->Visible) { // status_id ?>
        <td<?= $Page->status_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->status_id->viewAttributes() ?>>
<?= $Page->status_id->getViewValue() ?></span>
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
<?php if ($Page->uptime->Visible) { // uptime ?>
        <td<?= $Page->uptime->cellAttributes() ?>>
<span id="">
<span<?= $Page->uptime->viewAttributes() ?>>
<?= $Page->uptime->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->active_users->Visible) { // active_users ?>
        <td<?= $Page->active_users->cellAttributes() ?>>
<span id="">
<span<?= $Page->active_users->viewAttributes() ?>>
<?= $Page->active_users->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->queue_size->Visible) { // queue_size ?>
        <td<?= $Page->queue_size->cellAttributes() ?>>
<span id="">
<span<?= $Page->queue_size->viewAttributes() ?>>
<?= $Page->queue_size->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->average_processing_time->Visible) { // average_processing_time ?>
        <td<?= $Page->average_processing_time->cellAttributes() ?>>
<span id="">
<span<?= $Page->average_processing_time->viewAttributes() ?>>
<?= $Page->average_processing_time->getViewValue() ?></span>
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
