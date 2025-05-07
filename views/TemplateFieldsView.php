<?php

namespace PHPMaker2024\eNotary;

// Page object
$TemplateFieldsView = &$Page;
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
<form name="ftemplate_fieldsview" id="ftemplate_fieldsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { template_fields: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ftemplate_fieldsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftemplate_fieldsview")
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
<input type="hidden" name="t" value="template_fields">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->field_id->Visible) { // field_id ?>
    <tr id="r_field_id"<?= $Page->field_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_field_id"><?= $Page->field_id->caption() ?></span></td>
        <td data-name="field_id"<?= $Page->field_id->cellAttributes() ?>>
<span id="el_template_fields_field_id">
<span<?= $Page->field_id->viewAttributes() ?>>
<?= $Page->field_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
    <tr id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_template_id"><?= $Page->template_id->caption() ?></span></td>
        <td data-name="template_id"<?= $Page->template_id->cellAttributes() ?>>
<span id="el_template_fields_template_id">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->field_name->Visible) { // field_name ?>
    <tr id="r_field_name"<?= $Page->field_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_field_name"><?= $Page->field_name->caption() ?></span></td>
        <td data-name="field_name"<?= $Page->field_name->cellAttributes() ?>>
<span id="el_template_fields_field_name">
<span<?= $Page->field_name->viewAttributes() ?>>
<?= $Page->field_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->field_label->Visible) { // field_label ?>
    <tr id="r_field_label"<?= $Page->field_label->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_field_label"><?= $Page->field_label->caption() ?></span></td>
        <td data-name="field_label"<?= $Page->field_label->cellAttributes() ?>>
<span id="el_template_fields_field_label">
<span<?= $Page->field_label->viewAttributes() ?>>
<?= $Page->field_label->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->field_type->Visible) { // field_type ?>
    <tr id="r_field_type"<?= $Page->field_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_field_type"><?= $Page->field_type->caption() ?></span></td>
        <td data-name="field_type"<?= $Page->field_type->cellAttributes() ?>>
<span id="el_template_fields_field_type">
<span<?= $Page->field_type->viewAttributes() ?>>
<?= $Page->field_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->field_options->Visible) { // field_options ?>
    <tr id="r_field_options"<?= $Page->field_options->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_field_options"><?= $Page->field_options->caption() ?></span></td>
        <td data-name="field_options"<?= $Page->field_options->cellAttributes() ?>>
<span id="el_template_fields_field_options">
<span<?= $Page->field_options->viewAttributes() ?>>
<?= $Page->field_options->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_required->Visible) { // is_required ?>
    <tr id="r_is_required"<?= $Page->is_required->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_is_required"><?= $Page->is_required->caption() ?></span></td>
        <td data-name="is_required"<?= $Page->is_required->cellAttributes() ?>>
<span id="el_template_fields_is_required">
<span<?= $Page->is_required->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_required->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->placeholder->Visible) { // placeholder ?>
    <tr id="r_placeholder"<?= $Page->placeholder->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_placeholder"><?= $Page->placeholder->caption() ?></span></td>
        <td data-name="placeholder"<?= $Page->placeholder->cellAttributes() ?>>
<span id="el_template_fields_placeholder">
<span<?= $Page->placeholder->viewAttributes() ?>>
<?= $Page->placeholder->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->default_value->Visible) { // default_value ?>
    <tr id="r_default_value"<?= $Page->default_value->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_default_value"><?= $Page->default_value->caption() ?></span></td>
        <td data-name="default_value"<?= $Page->default_value->cellAttributes() ?>>
<span id="el_template_fields_default_value">
<span<?= $Page->default_value->viewAttributes() ?>>
<?= $Page->default_value->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->field_order->Visible) { // field_order ?>
    <tr id="r_field_order"<?= $Page->field_order->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_field_order"><?= $Page->field_order->caption() ?></span></td>
        <td data-name="field_order"<?= $Page->field_order->cellAttributes() ?>>
<span id="el_template_fields_field_order">
<span<?= $Page->field_order->viewAttributes() ?>>
<?= $Page->field_order->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->validation_rules->Visible) { // validation_rules ?>
    <tr id="r_validation_rules"<?= $Page->validation_rules->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_validation_rules"><?= $Page->validation_rules->caption() ?></span></td>
        <td data-name="validation_rules"<?= $Page->validation_rules->cellAttributes() ?>>
<span id="el_template_fields_validation_rules">
<span<?= $Page->validation_rules->viewAttributes() ?>>
<?= $Page->validation_rules->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->help_text->Visible) { // help_text ?>
    <tr id="r_help_text"<?= $Page->help_text->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_help_text"><?= $Page->help_text->caption() ?></span></td>
        <td data-name="help_text"<?= $Page->help_text->cellAttributes() ?>>
<span id="el_template_fields_help_text">
<span<?= $Page->help_text->viewAttributes() ?>>
<?= $Page->help_text->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->field_width->Visible) { // field_width ?>
    <tr id="r_field_width"<?= $Page->field_width->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_field_width"><?= $Page->field_width->caption() ?></span></td>
        <td data-name="field_width"<?= $Page->field_width->cellAttributes() ?>>
<span id="el_template_fields_field_width">
<span<?= $Page->field_width->viewAttributes() ?>>
<?= $Page->field_width->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_visible->Visible) { // is_visible ?>
    <tr id="r_is_visible"<?= $Page->is_visible->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_is_visible"><?= $Page->is_visible->caption() ?></span></td>
        <td data-name="is_visible"<?= $Page->is_visible->cellAttributes() ?>>
<span id="el_template_fields_is_visible">
<span<?= $Page->is_visible->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_visible->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->section_name->Visible) { // section_name ?>
    <tr id="r_section_name"<?= $Page->section_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_section_name"><?= $Page->section_name->caption() ?></span></td>
        <td data-name="section_name"<?= $Page->section_name->cellAttributes() ?>>
<span id="el_template_fields_section_name">
<span<?= $Page->section_name->viewAttributes() ?>>
<?= $Page->section_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->x_position->Visible) { // x_position ?>
    <tr id="r_x_position"<?= $Page->x_position->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_x_position"><?= $Page->x_position->caption() ?></span></td>
        <td data-name="x_position"<?= $Page->x_position->cellAttributes() ?>>
<span id="el_template_fields_x_position">
<span<?= $Page->x_position->viewAttributes() ?>>
<?= $Page->x_position->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->y_position->Visible) { // y_position ?>
    <tr id="r_y_position"<?= $Page->y_position->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_y_position"><?= $Page->y_position->caption() ?></span></td>
        <td data-name="y_position"<?= $Page->y_position->cellAttributes() ?>>
<span id="el_template_fields_y_position">
<span<?= $Page->y_position->viewAttributes() ?>>
<?= $Page->y_position->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->group_name->Visible) { // group_name ?>
    <tr id="r_group_name"<?= $Page->group_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_group_name"><?= $Page->group_name->caption() ?></span></td>
        <td data-name="group_name"<?= $Page->group_name->cellAttributes() ?>>
<span id="el_template_fields_group_name">
<span<?= $Page->group_name->viewAttributes() ?>>
<?= $Page->group_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->conditional_display->Visible) { // conditional_display ?>
    <tr id="r_conditional_display"<?= $Page->conditional_display->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_conditional_display"><?= $Page->conditional_display->caption() ?></span></td>
        <td data-name="conditional_display"<?= $Page->conditional_display->cellAttributes() ?>>
<span id="el_template_fields_conditional_display">
<span<?= $Page->conditional_display->viewAttributes() ?>>
<?= $Page->conditional_display->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_template_fields_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_template_fields_created_at">
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
