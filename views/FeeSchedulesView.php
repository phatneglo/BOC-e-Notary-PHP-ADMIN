<?php

namespace PHPMaker2024\eNotary;

// Page object
$FeeSchedulesView = &$Page;
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
<form name="ffee_schedulesview" id="ffee_schedulesview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fee_schedules: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ffee_schedulesview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffee_schedulesview")
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
<input type="hidden" name="t" value="fee_schedules">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->fee_id->Visible) { // fee_id ?>
    <tr id="r_fee_id"<?= $Page->fee_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_fee_id"><?= $Page->fee_id->caption() ?></span></td>
        <td data-name="fee_id"<?= $Page->fee_id->cellAttributes() ?>>
<span id="el_fee_schedules_fee_id">
<span<?= $Page->fee_id->viewAttributes() ?>>
<?= $Page->fee_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
    <tr id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_template_id"><?= $Page->template_id->caption() ?></span></td>
        <td data-name="template_id"<?= $Page->template_id->cellAttributes() ?>>
<span id="el_fee_schedules_template_id">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fee_name->Visible) { // fee_name ?>
    <tr id="r_fee_name"<?= $Page->fee_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_fee_name"><?= $Page->fee_name->caption() ?></span></td>
        <td data-name="fee_name"<?= $Page->fee_name->cellAttributes() ?>>
<span id="el_fee_schedules_fee_name">
<span<?= $Page->fee_name->viewAttributes() ?>>
<?= $Page->fee_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
    <tr id="r_fee_amount"<?= $Page->fee_amount->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_fee_amount"><?= $Page->fee_amount->caption() ?></span></td>
        <td data-name="fee_amount"<?= $Page->fee_amount->cellAttributes() ?>>
<span id="el_fee_schedules_fee_amount">
<span<?= $Page->fee_amount->viewAttributes() ?>>
<?= $Page->fee_amount->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fee_type->Visible) { // fee_type ?>
    <tr id="r_fee_type"<?= $Page->fee_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_fee_type"><?= $Page->fee_type->caption() ?></span></td>
        <td data-name="fee_type"<?= $Page->fee_type->cellAttributes() ?>>
<span id="el_fee_schedules_fee_type">
<span<?= $Page->fee_type->viewAttributes() ?>>
<?= $Page->fee_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->currency->Visible) { // currency ?>
    <tr id="r_currency"<?= $Page->currency->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_currency"><?= $Page->currency->caption() ?></span></td>
        <td data-name="currency"<?= $Page->currency->cellAttributes() ?>>
<span id="el_fee_schedules_currency">
<span<?= $Page->currency->viewAttributes() ?>>
<?= $Page->currency->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->effective_from->Visible) { // effective_from ?>
    <tr id="r_effective_from"<?= $Page->effective_from->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_effective_from"><?= $Page->effective_from->caption() ?></span></td>
        <td data-name="effective_from"<?= $Page->effective_from->cellAttributes() ?>>
<span id="el_fee_schedules_effective_from">
<span<?= $Page->effective_from->viewAttributes() ?>>
<?= $Page->effective_from->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->effective_to->Visible) { // effective_to ?>
    <tr id="r_effective_to"<?= $Page->effective_to->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_effective_to"><?= $Page->effective_to->caption() ?></span></td>
        <td data-name="effective_to"<?= $Page->effective_to->cellAttributes() ?>>
<span id="el_fee_schedules_effective_to">
<span<?= $Page->effective_to->viewAttributes() ?>>
<?= $Page->effective_to->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_fee_schedules_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
    <tr id="r_created_by"<?= $Page->created_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_created_by"><?= $Page->created_by->caption() ?></span></td>
        <td data-name="created_by"<?= $Page->created_by->cellAttributes() ?>>
<span id="el_fee_schedules_created_by">
<span<?= $Page->created_by->viewAttributes() ?>>
<?= $Page->created_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_fee_schedules_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
    <tr id="r_updated_by"<?= $Page->updated_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_updated_by"><?= $Page->updated_by->caption() ?></span></td>
        <td data-name="updated_by"<?= $Page->updated_by->cellAttributes() ?>>
<span id="el_fee_schedules_updated_by">
<span<?= $Page->updated_by->viewAttributes() ?>>
<?= $Page->updated_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <tr id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_is_active"><?= $Page->is_active->caption() ?></span></td>
        <td data-name="is_active"<?= $Page->is_active->cellAttributes() ?>>
<span id="el_fee_schedules_is_active">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <tr id="r_description"<?= $Page->description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fee_schedules_description"><?= $Page->description->caption() ?></span></td>
        <td data-name="description"<?= $Page->description->cellAttributes() ?>>
<span id="el_fee_schedules_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
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
