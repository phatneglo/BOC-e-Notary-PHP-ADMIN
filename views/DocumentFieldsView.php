<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentFieldsView = &$Page;
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
<form name="fdocument_fieldsview" id="fdocument_fieldsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_fields: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fdocument_fieldsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_fieldsview")
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
<input type="hidden" name="t" value="document_fields">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->document_field_id->Visible) { // document_field_id ?>
    <tr id="r_document_field_id"<?= $Page->document_field_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_fields_document_field_id"><?= $Page->document_field_id->caption() ?></span></td>
        <td data-name="document_field_id"<?= $Page->document_field_id->cellAttributes() ?>>
<span id="el_document_fields_document_field_id">
<span<?= $Page->document_field_id->viewAttributes() ?>>
<?= $Page->document_field_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <tr id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_fields_document_id"><?= $Page->document_id->caption() ?></span></td>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el_document_fields_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->field_id->Visible) { // field_id ?>
    <tr id="r_field_id"<?= $Page->field_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_fields_field_id"><?= $Page->field_id->caption() ?></span></td>
        <td data-name="field_id"<?= $Page->field_id->cellAttributes() ?>>
<span id="el_document_fields_field_id">
<span<?= $Page->field_id->viewAttributes() ?>>
<?= $Page->field_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->field_value->Visible) { // field_value ?>
    <tr id="r_field_value"<?= $Page->field_value->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_fields_field_value"><?= $Page->field_value->caption() ?></span></td>
        <td data-name="field_value"<?= $Page->field_value->cellAttributes() ?>>
<span id="el_document_fields_field_value">
<span<?= $Page->field_value->viewAttributes() ?>>
<?= $Page->field_value->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_fields_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_document_fields_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_verified->Visible) { // is_verified ?>
    <tr id="r_is_verified"<?= $Page->is_verified->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_fields_is_verified"><?= $Page->is_verified->caption() ?></span></td>
        <td data-name="is_verified"<?= $Page->is_verified->cellAttributes() ?>>
<span id="el_document_fields_is_verified">
<span<?= $Page->is_verified->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_verified->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->verified_by->Visible) { // verified_by ?>
    <tr id="r_verified_by"<?= $Page->verified_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_fields_verified_by"><?= $Page->verified_by->caption() ?></span></td>
        <td data-name="verified_by"<?= $Page->verified_by->cellAttributes() ?>>
<span id="el_document_fields_verified_by">
<span<?= $Page->verified_by->viewAttributes() ?>>
<?= $Page->verified_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
    <tr id="r_verification_date"<?= $Page->verification_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_fields_verification_date"><?= $Page->verification_date->caption() ?></span></td>
        <td data-name="verification_date"<?= $Page->verification_date->cellAttributes() ?>>
<span id="el_document_fields_verification_date">
<span<?= $Page->verification_date->viewAttributes() ?>>
<?= $Page->verification_date->getViewValue() ?></span>
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
