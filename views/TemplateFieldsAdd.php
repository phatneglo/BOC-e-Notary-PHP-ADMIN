<?php

namespace PHPMaker2024\eNotary;

// Page object
$TemplateFieldsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { template_fields: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var ftemplate_fieldsadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftemplate_fieldsadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["template_id", [fields.template_id.visible && fields.template_id.required ? ew.Validators.required(fields.template_id.caption) : null, ew.Validators.integer], fields.template_id.isInvalid],
            ["field_name", [fields.field_name.visible && fields.field_name.required ? ew.Validators.required(fields.field_name.caption) : null], fields.field_name.isInvalid],
            ["field_label", [fields.field_label.visible && fields.field_label.required ? ew.Validators.required(fields.field_label.caption) : null], fields.field_label.isInvalid],
            ["field_type", [fields.field_type.visible && fields.field_type.required ? ew.Validators.required(fields.field_type.caption) : null], fields.field_type.isInvalid],
            ["field_options", [fields.field_options.visible && fields.field_options.required ? ew.Validators.required(fields.field_options.caption) : null], fields.field_options.isInvalid],
            ["is_required", [fields.is_required.visible && fields.is_required.required ? ew.Validators.required(fields.is_required.caption) : null], fields.is_required.isInvalid],
            ["placeholder", [fields.placeholder.visible && fields.placeholder.required ? ew.Validators.required(fields.placeholder.caption) : null], fields.placeholder.isInvalid],
            ["default_value", [fields.default_value.visible && fields.default_value.required ? ew.Validators.required(fields.default_value.caption) : null], fields.default_value.isInvalid],
            ["field_order", [fields.field_order.visible && fields.field_order.required ? ew.Validators.required(fields.field_order.caption) : null, ew.Validators.integer], fields.field_order.isInvalid],
            ["validation_rules", [fields.validation_rules.visible && fields.validation_rules.required ? ew.Validators.required(fields.validation_rules.caption) : null], fields.validation_rules.isInvalid],
            ["help_text", [fields.help_text.visible && fields.help_text.required ? ew.Validators.required(fields.help_text.caption) : null], fields.help_text.isInvalid],
            ["field_width", [fields.field_width.visible && fields.field_width.required ? ew.Validators.required(fields.field_width.caption) : null], fields.field_width.isInvalid],
            ["is_visible", [fields.is_visible.visible && fields.is_visible.required ? ew.Validators.required(fields.is_visible.caption) : null], fields.is_visible.isInvalid],
            ["section_name", [fields.section_name.visible && fields.section_name.required ? ew.Validators.required(fields.section_name.caption) : null], fields.section_name.isInvalid],
            ["x_position", [fields.x_position.visible && fields.x_position.required ? ew.Validators.required(fields.x_position.caption) : null, ew.Validators.integer], fields.x_position.isInvalid],
            ["y_position", [fields.y_position.visible && fields.y_position.required ? ew.Validators.required(fields.y_position.caption) : null, ew.Validators.integer], fields.y_position.isInvalid],
            ["group_name", [fields.group_name.visible && fields.group_name.required ? ew.Validators.required(fields.group_name.caption) : null], fields.group_name.isInvalid],
            ["conditional_display", [fields.conditional_display.visible && fields.conditional_display.required ? ew.Validators.required(fields.conditional_display.caption) : null], fields.conditional_display.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code in JAVASCRIPT here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "is_required": <?= $Page->is_required->toClientList($Page) ?>,
            "is_visible": <?= $Page->is_visible->toClientList($Page) ?>,
        })
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
<form name="ftemplate_fieldsadd" id="ftemplate_fieldsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="template_fields">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->template_id->Visible) { // template_id ?>
    <div id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <label id="elh_template_fields_template_id" for="x_template_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->template_id->caption() ?><?= $Page->template_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->template_id->cellAttributes() ?>>
<span id="el_template_fields_template_id">
<input type="<?= $Page->template_id->getInputTextType() ?>" name="x_template_id" id="x_template_id" data-table="template_fields" data-field="x_template_id" value="<?= $Page->template_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->template_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->template_id->formatPattern()) ?>"<?= $Page->template_id->editAttributes() ?> aria-describedby="x_template_id_help">
<?= $Page->template_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->template_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field_name->Visible) { // field_name ?>
    <div id="r_field_name"<?= $Page->field_name->rowAttributes() ?>>
        <label id="elh_template_fields_field_name" for="x_field_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field_name->caption() ?><?= $Page->field_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field_name->cellAttributes() ?>>
<span id="el_template_fields_field_name">
<input type="<?= $Page->field_name->getInputTextType() ?>" name="x_field_name" id="x_field_name" data-table="template_fields" data-field="x_field_name" value="<?= $Page->field_name->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->field_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->field_name->formatPattern()) ?>"<?= $Page->field_name->editAttributes() ?> aria-describedby="x_field_name_help">
<?= $Page->field_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field_label->Visible) { // field_label ?>
    <div id="r_field_label"<?= $Page->field_label->rowAttributes() ?>>
        <label id="elh_template_fields_field_label" for="x_field_label" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field_label->caption() ?><?= $Page->field_label->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field_label->cellAttributes() ?>>
<span id="el_template_fields_field_label">
<input type="<?= $Page->field_label->getInputTextType() ?>" name="x_field_label" id="x_field_label" data-table="template_fields" data-field="x_field_label" value="<?= $Page->field_label->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->field_label->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->field_label->formatPattern()) ?>"<?= $Page->field_label->editAttributes() ?> aria-describedby="x_field_label_help">
<?= $Page->field_label->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field_label->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field_type->Visible) { // field_type ?>
    <div id="r_field_type"<?= $Page->field_type->rowAttributes() ?>>
        <label id="elh_template_fields_field_type" for="x_field_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field_type->caption() ?><?= $Page->field_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field_type->cellAttributes() ?>>
<span id="el_template_fields_field_type">
<input type="<?= $Page->field_type->getInputTextType() ?>" name="x_field_type" id="x_field_type" data-table="template_fields" data-field="x_field_type" value="<?= $Page->field_type->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->field_type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->field_type->formatPattern()) ?>"<?= $Page->field_type->editAttributes() ?> aria-describedby="x_field_type_help">
<?= $Page->field_type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field_type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field_options->Visible) { // field_options ?>
    <div id="r_field_options"<?= $Page->field_options->rowAttributes() ?>>
        <label id="elh_template_fields_field_options" for="x_field_options" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field_options->caption() ?><?= $Page->field_options->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field_options->cellAttributes() ?>>
<span id="el_template_fields_field_options">
<textarea data-table="template_fields" data-field="x_field_options" name="x_field_options" id="x_field_options" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->field_options->getPlaceHolder()) ?>"<?= $Page->field_options->editAttributes() ?> aria-describedby="x_field_options_help"><?= $Page->field_options->EditValue ?></textarea>
<?= $Page->field_options->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field_options->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_required->Visible) { // is_required ?>
    <div id="r_is_required"<?= $Page->is_required->rowAttributes() ?>>
        <label id="elh_template_fields_is_required" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_required->caption() ?><?= $Page->is_required->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_required->cellAttributes() ?>>
<span id="el_template_fields_is_required">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_required->isInvalidClass() ?>" data-table="template_fields" data-field="x_is_required" data-boolean name="x_is_required" id="x_is_required" value="1"<?= ConvertToBool($Page->is_required->CurrentValue) ? " checked" : "" ?><?= $Page->is_required->editAttributes() ?> aria-describedby="x_is_required_help">
    <div class="invalid-feedback"><?= $Page->is_required->getErrorMessage() ?></div>
</div>
<?= $Page->is_required->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->placeholder->Visible) { // placeholder ?>
    <div id="r_placeholder"<?= $Page->placeholder->rowAttributes() ?>>
        <label id="elh_template_fields_placeholder" for="x_placeholder" class="<?= $Page->LeftColumnClass ?>"><?= $Page->placeholder->caption() ?><?= $Page->placeholder->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->placeholder->cellAttributes() ?>>
<span id="el_template_fields_placeholder">
<textarea data-table="template_fields" data-field="x_placeholder" name="x_placeholder" id="x_placeholder" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->placeholder->getPlaceHolder()) ?>"<?= $Page->placeholder->editAttributes() ?> aria-describedby="x_placeholder_help"><?= $Page->placeholder->EditValue ?></textarea>
<?= $Page->placeholder->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->placeholder->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->default_value->Visible) { // default_value ?>
    <div id="r_default_value"<?= $Page->default_value->rowAttributes() ?>>
        <label id="elh_template_fields_default_value" for="x_default_value" class="<?= $Page->LeftColumnClass ?>"><?= $Page->default_value->caption() ?><?= $Page->default_value->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->default_value->cellAttributes() ?>>
<span id="el_template_fields_default_value">
<textarea data-table="template_fields" data-field="x_default_value" name="x_default_value" id="x_default_value" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->default_value->getPlaceHolder()) ?>"<?= $Page->default_value->editAttributes() ?> aria-describedby="x_default_value_help"><?= $Page->default_value->EditValue ?></textarea>
<?= $Page->default_value->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->default_value->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field_order->Visible) { // field_order ?>
    <div id="r_field_order"<?= $Page->field_order->rowAttributes() ?>>
        <label id="elh_template_fields_field_order" for="x_field_order" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field_order->caption() ?><?= $Page->field_order->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field_order->cellAttributes() ?>>
<span id="el_template_fields_field_order">
<input type="<?= $Page->field_order->getInputTextType() ?>" name="x_field_order" id="x_field_order" data-table="template_fields" data-field="x_field_order" value="<?= $Page->field_order->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->field_order->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->field_order->formatPattern()) ?>"<?= $Page->field_order->editAttributes() ?> aria-describedby="x_field_order_help">
<?= $Page->field_order->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field_order->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->validation_rules->Visible) { // validation_rules ?>
    <div id="r_validation_rules"<?= $Page->validation_rules->rowAttributes() ?>>
        <label id="elh_template_fields_validation_rules" for="x_validation_rules" class="<?= $Page->LeftColumnClass ?>"><?= $Page->validation_rules->caption() ?><?= $Page->validation_rules->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->validation_rules->cellAttributes() ?>>
<span id="el_template_fields_validation_rules">
<textarea data-table="template_fields" data-field="x_validation_rules" name="x_validation_rules" id="x_validation_rules" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->validation_rules->getPlaceHolder()) ?>"<?= $Page->validation_rules->editAttributes() ?> aria-describedby="x_validation_rules_help"><?= $Page->validation_rules->EditValue ?></textarea>
<?= $Page->validation_rules->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->validation_rules->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->help_text->Visible) { // help_text ?>
    <div id="r_help_text"<?= $Page->help_text->rowAttributes() ?>>
        <label id="elh_template_fields_help_text" for="x_help_text" class="<?= $Page->LeftColumnClass ?>"><?= $Page->help_text->caption() ?><?= $Page->help_text->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->help_text->cellAttributes() ?>>
<span id="el_template_fields_help_text">
<textarea data-table="template_fields" data-field="x_help_text" name="x_help_text" id="x_help_text" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->help_text->getPlaceHolder()) ?>"<?= $Page->help_text->editAttributes() ?> aria-describedby="x_help_text_help"><?= $Page->help_text->EditValue ?></textarea>
<?= $Page->help_text->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->help_text->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field_width->Visible) { // field_width ?>
    <div id="r_field_width"<?= $Page->field_width->rowAttributes() ?>>
        <label id="elh_template_fields_field_width" for="x_field_width" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field_width->caption() ?><?= $Page->field_width->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field_width->cellAttributes() ?>>
<span id="el_template_fields_field_width">
<input type="<?= $Page->field_width->getInputTextType() ?>" name="x_field_width" id="x_field_width" data-table="template_fields" data-field="x_field_width" value="<?= $Page->field_width->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->field_width->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->field_width->formatPattern()) ?>"<?= $Page->field_width->editAttributes() ?> aria-describedby="x_field_width_help">
<?= $Page->field_width->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field_width->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_visible->Visible) { // is_visible ?>
    <div id="r_is_visible"<?= $Page->is_visible->rowAttributes() ?>>
        <label id="elh_template_fields_is_visible" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_visible->caption() ?><?= $Page->is_visible->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_visible->cellAttributes() ?>>
<span id="el_template_fields_is_visible">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_visible->isInvalidClass() ?>" data-table="template_fields" data-field="x_is_visible" data-boolean name="x_is_visible" id="x_is_visible" value="1"<?= ConvertToBool($Page->is_visible->CurrentValue) ? " checked" : "" ?><?= $Page->is_visible->editAttributes() ?> aria-describedby="x_is_visible_help">
    <div class="invalid-feedback"><?= $Page->is_visible->getErrorMessage() ?></div>
</div>
<?= $Page->is_visible->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->section_name->Visible) { // section_name ?>
    <div id="r_section_name"<?= $Page->section_name->rowAttributes() ?>>
        <label id="elh_template_fields_section_name" for="x_section_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->section_name->caption() ?><?= $Page->section_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->section_name->cellAttributes() ?>>
<span id="el_template_fields_section_name">
<input type="<?= $Page->section_name->getInputTextType() ?>" name="x_section_name" id="x_section_name" data-table="template_fields" data-field="x_section_name" value="<?= $Page->section_name->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->section_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->section_name->formatPattern()) ?>"<?= $Page->section_name->editAttributes() ?> aria-describedby="x_section_name_help">
<?= $Page->section_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->section_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->x_position->Visible) { // x_position ?>
    <div id="r_x_position"<?= $Page->x_position->rowAttributes() ?>>
        <label id="elh_template_fields_x_position" for="x_x_position" class="<?= $Page->LeftColumnClass ?>"><?= $Page->x_position->caption() ?><?= $Page->x_position->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->x_position->cellAttributes() ?>>
<span id="el_template_fields_x_position">
<input type="<?= $Page->x_position->getInputTextType() ?>" name="x_x_position" id="x_x_position" data-table="template_fields" data-field="x_x_position" value="<?= $Page->x_position->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->x_position->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->x_position->formatPattern()) ?>"<?= $Page->x_position->editAttributes() ?> aria-describedby="x_x_position_help">
<?= $Page->x_position->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->x_position->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->y_position->Visible) { // y_position ?>
    <div id="r_y_position"<?= $Page->y_position->rowAttributes() ?>>
        <label id="elh_template_fields_y_position" for="x_y_position" class="<?= $Page->LeftColumnClass ?>"><?= $Page->y_position->caption() ?><?= $Page->y_position->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->y_position->cellAttributes() ?>>
<span id="el_template_fields_y_position">
<input type="<?= $Page->y_position->getInputTextType() ?>" name="x_y_position" id="x_y_position" data-table="template_fields" data-field="x_y_position" value="<?= $Page->y_position->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->y_position->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->y_position->formatPattern()) ?>"<?= $Page->y_position->editAttributes() ?> aria-describedby="x_y_position_help">
<?= $Page->y_position->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->y_position->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->group_name->Visible) { // group_name ?>
    <div id="r_group_name"<?= $Page->group_name->rowAttributes() ?>>
        <label id="elh_template_fields_group_name" for="x_group_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->group_name->caption() ?><?= $Page->group_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->group_name->cellAttributes() ?>>
<span id="el_template_fields_group_name">
<input type="<?= $Page->group_name->getInputTextType() ?>" name="x_group_name" id="x_group_name" data-table="template_fields" data-field="x_group_name" value="<?= $Page->group_name->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->group_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->group_name->formatPattern()) ?>"<?= $Page->group_name->editAttributes() ?> aria-describedby="x_group_name_help">
<?= $Page->group_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->group_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->conditional_display->Visible) { // conditional_display ?>
    <div id="r_conditional_display"<?= $Page->conditional_display->rowAttributes() ?>>
        <label id="elh_template_fields_conditional_display" for="x_conditional_display" class="<?= $Page->LeftColumnClass ?>"><?= $Page->conditional_display->caption() ?><?= $Page->conditional_display->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->conditional_display->cellAttributes() ?>>
<span id="el_template_fields_conditional_display">
<textarea data-table="template_fields" data-field="x_conditional_display" name="x_conditional_display" id="x_conditional_display" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->conditional_display->getPlaceHolder()) ?>"<?= $Page->conditional_display->editAttributes() ?> aria-describedby="x_conditional_display_help"><?= $Page->conditional_display->EditValue ?></textarea>
<?= $Page->conditional_display->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->conditional_display->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_template_fields_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_template_fields_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="template_fields" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ftemplate_fieldsadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("ftemplate_fieldsadd", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftemplate_fieldsadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftemplate_fieldsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("template_fields");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
