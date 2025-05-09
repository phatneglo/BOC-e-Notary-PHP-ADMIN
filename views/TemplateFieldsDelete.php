<?php

namespace PHPMaker2024\eNotary;

// Page object
$TemplateFieldsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { template_fields: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var ftemplate_fieldsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftemplate_fieldsdelete")
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
<form name="ftemplate_fieldsdelete" id="ftemplate_fieldsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="template_fields">
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
<?php if ($Page->field_id->Visible) { // field_id ?>
        <th class="<?= $Page->field_id->headerCellClass() ?>"><span id="elh_template_fields_field_id" class="template_fields_field_id"><?= $Page->field_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
        <th class="<?= $Page->template_id->headerCellClass() ?>"><span id="elh_template_fields_template_id" class="template_fields_template_id"><?= $Page->template_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->field_name->Visible) { // field_name ?>
        <th class="<?= $Page->field_name->headerCellClass() ?>"><span id="elh_template_fields_field_name" class="template_fields_field_name"><?= $Page->field_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->field_label->Visible) { // field_label ?>
        <th class="<?= $Page->field_label->headerCellClass() ?>"><span id="elh_template_fields_field_label" class="template_fields_field_label"><?= $Page->field_label->caption() ?></span></th>
<?php } ?>
<?php if ($Page->field_type->Visible) { // field_type ?>
        <th class="<?= $Page->field_type->headerCellClass() ?>"><span id="elh_template_fields_field_type" class="template_fields_field_type"><?= $Page->field_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_required->Visible) { // is_required ?>
        <th class="<?= $Page->is_required->headerCellClass() ?>"><span id="elh_template_fields_is_required" class="template_fields_is_required"><?= $Page->is_required->caption() ?></span></th>
<?php } ?>
<?php if ($Page->field_order->Visible) { // field_order ?>
        <th class="<?= $Page->field_order->headerCellClass() ?>"><span id="elh_template_fields_field_order" class="template_fields_field_order"><?= $Page->field_order->caption() ?></span></th>
<?php } ?>
<?php if ($Page->field_width->Visible) { // field_width ?>
        <th class="<?= $Page->field_width->headerCellClass() ?>"><span id="elh_template_fields_field_width" class="template_fields_field_width"><?= $Page->field_width->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_visible->Visible) { // is_visible ?>
        <th class="<?= $Page->is_visible->headerCellClass() ?>"><span id="elh_template_fields_is_visible" class="template_fields_is_visible"><?= $Page->is_visible->caption() ?></span></th>
<?php } ?>
<?php if ($Page->section_name->Visible) { // section_name ?>
        <th class="<?= $Page->section_name->headerCellClass() ?>"><span id="elh_template_fields_section_name" class="template_fields_section_name"><?= $Page->section_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->x_position->Visible) { // x_position ?>
        <th class="<?= $Page->x_position->headerCellClass() ?>"><span id="elh_template_fields_x_position" class="template_fields_x_position"><?= $Page->x_position->caption() ?></span></th>
<?php } ?>
<?php if ($Page->y_position->Visible) { // y_position ?>
        <th class="<?= $Page->y_position->headerCellClass() ?>"><span id="elh_template_fields_y_position" class="template_fields_y_position"><?= $Page->y_position->caption() ?></span></th>
<?php } ?>
<?php if ($Page->group_name->Visible) { // group_name ?>
        <th class="<?= $Page->group_name->headerCellClass() ?>"><span id="elh_template_fields_group_name" class="template_fields_group_name"><?= $Page->group_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_template_fields_created_at" class="template_fields_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->section_id->Visible) { // section_id ?>
        <th class="<?= $Page->section_id->headerCellClass() ?>"><span id="elh_template_fields_section_id" class="template_fields_section_id"><?= $Page->section_id->caption() ?></span></th>
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
<?php if ($Page->field_id->Visible) { // field_id ?>
        <td<?= $Page->field_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->field_id->viewAttributes() ?>>
<?= $Page->field_id->getViewValue() ?></span>
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
<?php if ($Page->field_name->Visible) { // field_name ?>
        <td<?= $Page->field_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->field_name->viewAttributes() ?>>
<?= $Page->field_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->field_label->Visible) { // field_label ?>
        <td<?= $Page->field_label->cellAttributes() ?>>
<span id="">
<span<?= $Page->field_label->viewAttributes() ?>>
<?= $Page->field_label->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->field_type->Visible) { // field_type ?>
        <td<?= $Page->field_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->field_type->viewAttributes() ?>>
<?= $Page->field_type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_required->Visible) { // is_required ?>
        <td<?= $Page->is_required->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_required->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_required->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->field_order->Visible) { // field_order ?>
        <td<?= $Page->field_order->cellAttributes() ?>>
<span id="">
<span<?= $Page->field_order->viewAttributes() ?>>
<?= $Page->field_order->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->field_width->Visible) { // field_width ?>
        <td<?= $Page->field_width->cellAttributes() ?>>
<span id="">
<span<?= $Page->field_width->viewAttributes() ?>>
<?= $Page->field_width->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_visible->Visible) { // is_visible ?>
        <td<?= $Page->is_visible->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_visible->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_visible->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->section_name->Visible) { // section_name ?>
        <td<?= $Page->section_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->section_name->viewAttributes() ?>>
<?= $Page->section_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->x_position->Visible) { // x_position ?>
        <td<?= $Page->x_position->cellAttributes() ?>>
<span id="">
<span<?= $Page->x_position->viewAttributes() ?>>
<?= $Page->x_position->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->y_position->Visible) { // y_position ?>
        <td<?= $Page->y_position->cellAttributes() ?>>
<span id="">
<span<?= $Page->y_position->viewAttributes() ?>>
<?= $Page->y_position->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->group_name->Visible) { // group_name ?>
        <td<?= $Page->group_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->group_name->viewAttributes() ?>>
<?= $Page->group_name->getViewValue() ?></span>
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
<?php if ($Page->section_id->Visible) { // section_id ?>
        <td<?= $Page->section_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->section_id->viewAttributes() ?>>
<?= $Page->section_id->getViewValue() ?></span>
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
