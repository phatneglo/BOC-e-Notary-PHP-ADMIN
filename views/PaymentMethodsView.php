<?php

namespace PHPMaker2024\eNotary;

// Page object
$PaymentMethodsView = &$Page;
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
<form name="fpayment_methodsview" id="fpayment_methodsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { payment_methods: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fpayment_methodsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpayment_methodsview")
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
<input type="hidden" name="t" value="payment_methods">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->method_id->Visible) { // method_id ?>
    <tr id="r_method_id"<?= $Page->method_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_method_id"><?= $Page->method_id->caption() ?></span></td>
        <td data-name="method_id"<?= $Page->method_id->cellAttributes() ?>>
<span id="el_payment_methods_method_id">
<span<?= $Page->method_id->viewAttributes() ?>>
<?= $Page->method_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->method_name->Visible) { // method_name ?>
    <tr id="r_method_name"<?= $Page->method_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_method_name"><?= $Page->method_name->caption() ?></span></td>
        <td data-name="method_name"<?= $Page->method_name->cellAttributes() ?>>
<span id="el_payment_methods_method_name">
<span<?= $Page->method_name->viewAttributes() ?>>
<?= $Page->method_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->method_code->Visible) { // method_code ?>
    <tr id="r_method_code"<?= $Page->method_code->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_method_code"><?= $Page->method_code->caption() ?></span></td>
        <td data-name="method_code"<?= $Page->method_code->cellAttributes() ?>>
<span id="el_payment_methods_method_code">
<span<?= $Page->method_code->viewAttributes() ?>>
<?= $Page->method_code->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <tr id="r_description"<?= $Page->description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_description"><?= $Page->description->caption() ?></span></td>
        <td data-name="description"<?= $Page->description->cellAttributes() ?>>
<span id="el_payment_methods_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <tr id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_is_active"><?= $Page->is_active->caption() ?></span></td>
        <td data-name="is_active"<?= $Page->is_active->cellAttributes() ?>>
<span id="el_payment_methods_is_active">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->requires_verification->Visible) { // requires_verification ?>
    <tr id="r_requires_verification"<?= $Page->requires_verification->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_requires_verification"><?= $Page->requires_verification->caption() ?></span></td>
        <td data-name="requires_verification"<?= $Page->requires_verification->cellAttributes() ?>>
<span id="el_payment_methods_requires_verification">
<span<?= $Page->requires_verification->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->requires_verification->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->additional_fields->Visible) { // additional_fields ?>
    <tr id="r_additional_fields"<?= $Page->additional_fields->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_additional_fields"><?= $Page->additional_fields->caption() ?></span></td>
        <td data-name="additional_fields"<?= $Page->additional_fields->cellAttributes() ?>>
<span id="el_payment_methods_additional_fields">
<span<?= $Page->additional_fields->viewAttributes() ?>>
<?= $Page->additional_fields->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_payment_methods_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
    <tr id="r_created_by"<?= $Page->created_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_created_by"><?= $Page->created_by->caption() ?></span></td>
        <td data-name="created_by"<?= $Page->created_by->cellAttributes() ?>>
<span id="el_payment_methods_created_by">
<span<?= $Page->created_by->viewAttributes() ?>>
<?= $Page->created_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_payment_methods_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
    <tr id="r_updated_by"<?= $Page->updated_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_payment_methods_updated_by"><?= $Page->updated_by->caption() ?></span></td>
        <td data-name="updated_by"<?= $Page->updated_by->cellAttributes() ?>>
<span id="el_payment_methods_updated_by">
<span<?= $Page->updated_by->viewAttributes() ?>>
<?= $Page->updated_by->getViewValue() ?></span>
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
