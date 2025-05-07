<?php

namespace PHPMaker2024\eNotary;

// Page object
$FeeSchedulesDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fee_schedules: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var ffee_schedulesdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffee_schedulesdelete")
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
<form name="ffee_schedulesdelete" id="ffee_schedulesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="fee_schedules">
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
<?php if ($Page->fee_id->Visible) { // fee_id ?>
        <th class="<?= $Page->fee_id->headerCellClass() ?>"><span id="elh_fee_schedules_fee_id" class="fee_schedules_fee_id"><?= $Page->fee_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
        <th class="<?= $Page->template_id->headerCellClass() ?>"><span id="elh_fee_schedules_template_id" class="fee_schedules_template_id"><?= $Page->template_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fee_name->Visible) { // fee_name ?>
        <th class="<?= $Page->fee_name->headerCellClass() ?>"><span id="elh_fee_schedules_fee_name" class="fee_schedules_fee_name"><?= $Page->fee_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
        <th class="<?= $Page->fee_amount->headerCellClass() ?>"><span id="elh_fee_schedules_fee_amount" class="fee_schedules_fee_amount"><?= $Page->fee_amount->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fee_type->Visible) { // fee_type ?>
        <th class="<?= $Page->fee_type->headerCellClass() ?>"><span id="elh_fee_schedules_fee_type" class="fee_schedules_fee_type"><?= $Page->fee_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->currency->Visible) { // currency ?>
        <th class="<?= $Page->currency->headerCellClass() ?>"><span id="elh_fee_schedules_currency" class="fee_schedules_currency"><?= $Page->currency->caption() ?></span></th>
<?php } ?>
<?php if ($Page->effective_from->Visible) { // effective_from ?>
        <th class="<?= $Page->effective_from->headerCellClass() ?>"><span id="elh_fee_schedules_effective_from" class="fee_schedules_effective_from"><?= $Page->effective_from->caption() ?></span></th>
<?php } ?>
<?php if ($Page->effective_to->Visible) { // effective_to ?>
        <th class="<?= $Page->effective_to->headerCellClass() ?>"><span id="elh_fee_schedules_effective_to" class="fee_schedules_effective_to"><?= $Page->effective_to->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_fee_schedules_created_at" class="fee_schedules_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
        <th class="<?= $Page->created_by->headerCellClass() ?>"><span id="elh_fee_schedules_created_by" class="fee_schedules_created_by"><?= $Page->created_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_fee_schedules_updated_at" class="fee_schedules_updated_at"><?= $Page->updated_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
        <th class="<?= $Page->updated_by->headerCellClass() ?>"><span id="elh_fee_schedules_updated_by" class="fee_schedules_updated_by"><?= $Page->updated_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
        <th class="<?= $Page->is_active->headerCellClass() ?>"><span id="elh_fee_schedules_is_active" class="fee_schedules_is_active"><?= $Page->is_active->caption() ?></span></th>
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
<?php if ($Page->fee_id->Visible) { // fee_id ?>
        <td<?= $Page->fee_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->fee_id->viewAttributes() ?>>
<?= $Page->fee_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
        <td<?= $Page->template_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fee_name->Visible) { // fee_name ?>
        <td<?= $Page->fee_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->fee_name->viewAttributes() ?>>
<?= $Page->fee_name->getViewValue() ?></span>
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
<?php if ($Page->fee_type->Visible) { // fee_type ?>
        <td<?= $Page->fee_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->fee_type->viewAttributes() ?>>
<?= $Page->fee_type->getViewValue() ?></span>
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
<?php if ($Page->effective_from->Visible) { // effective_from ?>
        <td<?= $Page->effective_from->cellAttributes() ?>>
<span id="">
<span<?= $Page->effective_from->viewAttributes() ?>>
<?= $Page->effective_from->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->effective_to->Visible) { // effective_to ?>
        <td<?= $Page->effective_to->cellAttributes() ?>>
<span id="">
<span<?= $Page->effective_to->viewAttributes() ?>>
<?= $Page->effective_to->getViewValue() ?></span>
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
<?php if ($Page->created_by->Visible) { // created_by ?>
        <td<?= $Page->created_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->created_by->viewAttributes() ?>>
<?= $Page->created_by->getViewValue() ?></span>
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
<?php if ($Page->updated_by->Visible) { // updated_by ?>
        <td<?= $Page->updated_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->updated_by->viewAttributes() ?>>
<?= $Page->updated_by->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
        <td<?= $Page->is_active->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
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
