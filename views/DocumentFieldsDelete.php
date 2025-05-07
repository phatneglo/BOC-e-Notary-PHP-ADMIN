<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentFieldsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_fields: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fdocument_fieldsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_fieldsdelete")
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
<form name="fdocument_fieldsdelete" id="fdocument_fieldsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="document_fields">
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
<?php if ($Page->document_field_id->Visible) { // document_field_id ?>
        <th class="<?= $Page->document_field_id->headerCellClass() ?>"><span id="elh_document_fields_document_field_id" class="document_fields_document_field_id"><?= $Page->document_field_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th class="<?= $Page->document_id->headerCellClass() ?>"><span id="elh_document_fields_document_id" class="document_fields_document_id"><?= $Page->document_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->field_id->Visible) { // field_id ?>
        <th class="<?= $Page->field_id->headerCellClass() ?>"><span id="elh_document_fields_field_id" class="document_fields_field_id"><?= $Page->field_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_document_fields_updated_at" class="document_fields_updated_at"><?= $Page->updated_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_verified->Visible) { // is_verified ?>
        <th class="<?= $Page->is_verified->headerCellClass() ?>"><span id="elh_document_fields_is_verified" class="document_fields_is_verified"><?= $Page->is_verified->caption() ?></span></th>
<?php } ?>
<?php if ($Page->verified_by->Visible) { // verified_by ?>
        <th class="<?= $Page->verified_by->headerCellClass() ?>"><span id="elh_document_fields_verified_by" class="document_fields_verified_by"><?= $Page->verified_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
        <th class="<?= $Page->verification_date->headerCellClass() ?>"><span id="elh_document_fields_verification_date" class="document_fields_verification_date"><?= $Page->verification_date->caption() ?></span></th>
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
<?php if ($Page->document_field_id->Visible) { // document_field_id ?>
        <td<?= $Page->document_field_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->document_field_id->viewAttributes() ?>>
<?= $Page->document_field_id->getViewValue() ?></span>
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
<?php if ($Page->field_id->Visible) { // field_id ?>
        <td<?= $Page->field_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->field_id->viewAttributes() ?>>
<?= $Page->field_id->getViewValue() ?></span>
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
<?php if ($Page->is_verified->Visible) { // is_verified ?>
        <td<?= $Page->is_verified->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_verified->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_verified->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->verified_by->Visible) { // verified_by ?>
        <td<?= $Page->verified_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->verified_by->viewAttributes() ?>>
<?= $Page->verified_by->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
        <td<?= $Page->verification_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->verification_date->viewAttributes() ?>>
<?= $Page->verification_date->getViewValue() ?></span>
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
