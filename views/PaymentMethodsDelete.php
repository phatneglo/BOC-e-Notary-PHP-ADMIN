<?php

namespace PHPMaker2024\eNotary;

// Page object
$PaymentMethodsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { payment_methods: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fpayment_methodsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpayment_methodsdelete")
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
<form name="fpayment_methodsdelete" id="fpayment_methodsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="payment_methods">
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
<?php if ($Page->method_id->Visible) { // method_id ?>
        <th class="<?= $Page->method_id->headerCellClass() ?>"><span id="elh_payment_methods_method_id" class="payment_methods_method_id"><?= $Page->method_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->method_name->Visible) { // method_name ?>
        <th class="<?= $Page->method_name->headerCellClass() ?>"><span id="elh_payment_methods_method_name" class="payment_methods_method_name"><?= $Page->method_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->method_code->Visible) { // method_code ?>
        <th class="<?= $Page->method_code->headerCellClass() ?>"><span id="elh_payment_methods_method_code" class="payment_methods_method_code"><?= $Page->method_code->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
        <th class="<?= $Page->is_active->headerCellClass() ?>"><span id="elh_payment_methods_is_active" class="payment_methods_is_active"><?= $Page->is_active->caption() ?></span></th>
<?php } ?>
<?php if ($Page->requires_verification->Visible) { // requires_verification ?>
        <th class="<?= $Page->requires_verification->headerCellClass() ?>"><span id="elh_payment_methods_requires_verification" class="payment_methods_requires_verification"><?= $Page->requires_verification->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_payment_methods_created_at" class="payment_methods_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
        <th class="<?= $Page->created_by->headerCellClass() ?>"><span id="elh_payment_methods_created_by" class="payment_methods_created_by"><?= $Page->created_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_payment_methods_updated_at" class="payment_methods_updated_at"><?= $Page->updated_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
        <th class="<?= $Page->updated_by->headerCellClass() ?>"><span id="elh_payment_methods_updated_by" class="payment_methods_updated_by"><?= $Page->updated_by->caption() ?></span></th>
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
<?php if ($Page->method_id->Visible) { // method_id ?>
        <td<?= $Page->method_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->method_id->viewAttributes() ?>>
<?= $Page->method_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->method_name->Visible) { // method_name ?>
        <td<?= $Page->method_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->method_name->viewAttributes() ?>>
<?= $Page->method_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->method_code->Visible) { // method_code ?>
        <td<?= $Page->method_code->cellAttributes() ?>>
<span id="">
<span<?= $Page->method_code->viewAttributes() ?>>
<?= $Page->method_code->getViewValue() ?></span>
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
<?php if ($Page->requires_verification->Visible) { // requires_verification ?>
        <td<?= $Page->requires_verification->cellAttributes() ?>>
<span id="">
<span<?= $Page->requires_verification->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->requires_verification->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
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
