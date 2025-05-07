<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentTemplatesEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fdocument_templatesedit" id="fdocument_templatesedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_templates: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fdocument_templatesedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_templatesedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["template_id", [fields.template_id.visible && fields.template_id.required ? ew.Validators.required(fields.template_id.caption) : null], fields.template_id.isInvalid],
            ["template_name", [fields.template_name.visible && fields.template_name.required ? ew.Validators.required(fields.template_name.caption) : null], fields.template_name.isInvalid],
            ["template_code", [fields.template_code.visible && fields.template_code.required ? ew.Validators.required(fields.template_code.caption) : null], fields.template_code.isInvalid],
            ["category_id", [fields.category_id.visible && fields.category_id.required ? ew.Validators.required(fields.category_id.caption) : null, ew.Validators.integer], fields.category_id.isInvalid],
            ["description", [fields.description.visible && fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid],
            ["html_content", [fields.html_content.visible && fields.html_content.required ? ew.Validators.required(fields.html_content.caption) : null], fields.html_content.isInvalid],
            ["is_active", [fields.is_active.visible && fields.is_active.required ? ew.Validators.required(fields.is_active.caption) : null], fields.is_active.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["created_by", [fields.created_by.visible && fields.created_by.required ? ew.Validators.required(fields.created_by.caption) : null, ew.Validators.integer], fields.created_by.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid],
            ["updated_by", [fields.updated_by.visible && fields.updated_by.required ? ew.Validators.required(fields.updated_by.caption) : null, ew.Validators.integer], fields.updated_by.isInvalid],
            ["version", [fields.version.visible && fields.version.required ? ew.Validators.required(fields.version.caption) : null, ew.Validators.integer], fields.version.isInvalid],
            ["notary_required", [fields.notary_required.visible && fields.notary_required.required ? ew.Validators.required(fields.notary_required.caption) : null], fields.notary_required.isInvalid],
            ["fee_amount", [fields.fee_amount.visible && fields.fee_amount.required ? ew.Validators.required(fields.fee_amount.caption) : null, ew.Validators.float], fields.fee_amount.isInvalid],
            ["approval_workflow", [fields.approval_workflow.visible && fields.approval_workflow.required ? ew.Validators.required(fields.approval_workflow.caption) : null], fields.approval_workflow.isInvalid],
            ["template_type", [fields.template_type.visible && fields.template_type.required ? ew.Validators.required(fields.template_type.caption) : null], fields.template_type.isInvalid],
            ["header_text", [fields.header_text.visible && fields.header_text.required ? ew.Validators.required(fields.header_text.caption) : null], fields.header_text.isInvalid],
            ["footer_text", [fields.footer_text.visible && fields.footer_text.required ? ew.Validators.required(fields.footer_text.caption) : null], fields.footer_text.isInvalid],
            ["preview_image_path", [fields.preview_image_path.visible && fields.preview_image_path.required ? ew.Validators.required(fields.preview_image_path.caption) : null], fields.preview_image_path.isInvalid]
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
            "is_active": <?= $Page->is_active->toClientList($Page) ?>,
            "notary_required": <?= $Page->notary_required->toClientList($Page) ?>,
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="document_templates">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->template_id->Visible) { // template_id ?>
    <div id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <label id="elh_document_templates_template_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->template_id->caption() ?><?= $Page->template_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->template_id->cellAttributes() ?>>
<span id="el_document_templates_template_id">
<span<?= $Page->template_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->template_id->getDisplayValue($Page->template_id->EditValue))) ?>"></span>
<input type="hidden" data-table="document_templates" data-field="x_template_id" data-hidden="1" name="x_template_id" id="x_template_id" value="<?= HtmlEncode($Page->template_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->template_name->Visible) { // template_name ?>
    <div id="r_template_name"<?= $Page->template_name->rowAttributes() ?>>
        <label id="elh_document_templates_template_name" for="x_template_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->template_name->caption() ?><?= $Page->template_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->template_name->cellAttributes() ?>>
<span id="el_document_templates_template_name">
<input type="<?= $Page->template_name->getInputTextType() ?>" name="x_template_name" id="x_template_name" data-table="document_templates" data-field="x_template_name" value="<?= $Page->template_name->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->template_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->template_name->formatPattern()) ?>"<?= $Page->template_name->editAttributes() ?> aria-describedby="x_template_name_help">
<?= $Page->template_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->template_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->template_code->Visible) { // template_code ?>
    <div id="r_template_code"<?= $Page->template_code->rowAttributes() ?>>
        <label id="elh_document_templates_template_code" for="x_template_code" class="<?= $Page->LeftColumnClass ?>"><?= $Page->template_code->caption() ?><?= $Page->template_code->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->template_code->cellAttributes() ?>>
<span id="el_document_templates_template_code">
<input type="<?= $Page->template_code->getInputTextType() ?>" name="x_template_code" id="x_template_code" data-table="document_templates" data-field="x_template_code" value="<?= $Page->template_code->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->template_code->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->template_code->formatPattern()) ?>"<?= $Page->template_code->editAttributes() ?> aria-describedby="x_template_code_help">
<?= $Page->template_code->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->template_code->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->category_id->Visible) { // category_id ?>
    <div id="r_category_id"<?= $Page->category_id->rowAttributes() ?>>
        <label id="elh_document_templates_category_id" for="x_category_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->category_id->caption() ?><?= $Page->category_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->category_id->cellAttributes() ?>>
<span id="el_document_templates_category_id">
<input type="<?= $Page->category_id->getInputTextType() ?>" name="x_category_id" id="x_category_id" data-table="document_templates" data-field="x_category_id" value="<?= $Page->category_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->category_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->category_id->formatPattern()) ?>"<?= $Page->category_id->editAttributes() ?> aria-describedby="x_category_id_help">
<?= $Page->category_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->category_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description"<?= $Page->description->rowAttributes() ?>>
        <label id="elh_document_templates_description" for="x_description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->description->cellAttributes() ?>>
<span id="el_document_templates_description">
<textarea data-table="document_templates" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help"><?= $Page->description->EditValue ?></textarea>
<?= $Page->description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->html_content->Visible) { // html_content ?>
    <div id="r_html_content"<?= $Page->html_content->rowAttributes() ?>>
        <label id="elh_document_templates_html_content" for="x_html_content" class="<?= $Page->LeftColumnClass ?>"><?= $Page->html_content->caption() ?><?= $Page->html_content->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->html_content->cellAttributes() ?>>
<span id="el_document_templates_html_content">
<textarea data-table="document_templates" data-field="x_html_content" name="x_html_content" id="x_html_content" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->html_content->getPlaceHolder()) ?>"<?= $Page->html_content->editAttributes() ?> aria-describedby="x_html_content_help"><?= $Page->html_content->EditValue ?></textarea>
<?= $Page->html_content->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->html_content->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <div id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <label id="elh_document_templates_is_active" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_active->caption() ?><?= $Page->is_active->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_active->cellAttributes() ?>>
<span id="el_document_templates_is_active">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_active->isInvalidClass() ?>" data-table="document_templates" data-field="x_is_active" data-boolean name="x_is_active" id="x_is_active" value="1"<?= ConvertToBool($Page->is_active->CurrentValue) ? " checked" : "" ?><?= $Page->is_active->editAttributes() ?> aria-describedby="x_is_active_help">
    <div class="invalid-feedback"><?= $Page->is_active->getErrorMessage() ?></div>
</div>
<?= $Page->is_active->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_document_templates_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_document_templates_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="document_templates" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocument_templatesedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocument_templatesedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
    <div id="r_created_by"<?= $Page->created_by->rowAttributes() ?>>
        <label id="elh_document_templates_created_by" for="x_created_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_by->caption() ?><?= $Page->created_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_by->cellAttributes() ?>>
<span id="el_document_templates_created_by">
<input type="<?= $Page->created_by->getInputTextType() ?>" name="x_created_by" id="x_created_by" data-table="document_templates" data-field="x_created_by" value="<?= $Page->created_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->created_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_by->formatPattern()) ?>"<?= $Page->created_by->editAttributes() ?> aria-describedby="x_created_by_help">
<?= $Page->created_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_document_templates_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_document_templates_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="document_templates" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocument_templatesedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocument_templatesedit", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
    <div id="r_updated_by"<?= $Page->updated_by->rowAttributes() ?>>
        <label id="elh_document_templates_updated_by" for="x_updated_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_by->caption() ?><?= $Page->updated_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_by->cellAttributes() ?>>
<span id="el_document_templates_updated_by">
<input type="<?= $Page->updated_by->getInputTextType() ?>" name="x_updated_by" id="x_updated_by" data-table="document_templates" data-field="x_updated_by" value="<?= $Page->updated_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->updated_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_by->formatPattern()) ?>"<?= $Page->updated_by->editAttributes() ?> aria-describedby="x_updated_by_help">
<?= $Page->updated_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->version->Visible) { // version ?>
    <div id="r_version"<?= $Page->version->rowAttributes() ?>>
        <label id="elh_document_templates_version" for="x_version" class="<?= $Page->LeftColumnClass ?>"><?= $Page->version->caption() ?><?= $Page->version->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->version->cellAttributes() ?>>
<span id="el_document_templates_version">
<input type="<?= $Page->version->getInputTextType() ?>" name="x_version" id="x_version" data-table="document_templates" data-field="x_version" value="<?= $Page->version->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->version->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->version->formatPattern()) ?>"<?= $Page->version->editAttributes() ?> aria-describedby="x_version_help">
<?= $Page->version->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->version->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notary_required->Visible) { // notary_required ?>
    <div id="r_notary_required"<?= $Page->notary_required->rowAttributes() ?>>
        <label id="elh_document_templates_notary_required" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notary_required->caption() ?><?= $Page->notary_required->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notary_required->cellAttributes() ?>>
<span id="el_document_templates_notary_required">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->notary_required->isInvalidClass() ?>" data-table="document_templates" data-field="x_notary_required" data-boolean name="x_notary_required" id="x_notary_required" value="1"<?= ConvertToBool($Page->notary_required->CurrentValue) ? " checked" : "" ?><?= $Page->notary_required->editAttributes() ?> aria-describedby="x_notary_required_help">
    <div class="invalid-feedback"><?= $Page->notary_required->getErrorMessage() ?></div>
</div>
<?= $Page->notary_required->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
    <div id="r_fee_amount"<?= $Page->fee_amount->rowAttributes() ?>>
        <label id="elh_document_templates_fee_amount" for="x_fee_amount" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fee_amount->caption() ?><?= $Page->fee_amount->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fee_amount->cellAttributes() ?>>
<span id="el_document_templates_fee_amount">
<input type="<?= $Page->fee_amount->getInputTextType() ?>" name="x_fee_amount" id="x_fee_amount" data-table="document_templates" data-field="x_fee_amount" value="<?= $Page->fee_amount->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->fee_amount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fee_amount->formatPattern()) ?>"<?= $Page->fee_amount->editAttributes() ?> aria-describedby="x_fee_amount_help">
<?= $Page->fee_amount->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fee_amount->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->approval_workflow->Visible) { // approval_workflow ?>
    <div id="r_approval_workflow"<?= $Page->approval_workflow->rowAttributes() ?>>
        <label id="elh_document_templates_approval_workflow" for="x_approval_workflow" class="<?= $Page->LeftColumnClass ?>"><?= $Page->approval_workflow->caption() ?><?= $Page->approval_workflow->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->approval_workflow->cellAttributes() ?>>
<span id="el_document_templates_approval_workflow">
<textarea data-table="document_templates" data-field="x_approval_workflow" name="x_approval_workflow" id="x_approval_workflow" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->approval_workflow->getPlaceHolder()) ?>"<?= $Page->approval_workflow->editAttributes() ?> aria-describedby="x_approval_workflow_help"><?= $Page->approval_workflow->EditValue ?></textarea>
<?= $Page->approval_workflow->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->approval_workflow->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->template_type->Visible) { // template_type ?>
    <div id="r_template_type"<?= $Page->template_type->rowAttributes() ?>>
        <label id="elh_document_templates_template_type" for="x_template_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->template_type->caption() ?><?= $Page->template_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->template_type->cellAttributes() ?>>
<span id="el_document_templates_template_type">
<input type="<?= $Page->template_type->getInputTextType() ?>" name="x_template_type" id="x_template_type" data-table="document_templates" data-field="x_template_type" value="<?= $Page->template_type->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->template_type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->template_type->formatPattern()) ?>"<?= $Page->template_type->editAttributes() ?> aria-describedby="x_template_type_help">
<?= $Page->template_type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->template_type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->header_text->Visible) { // header_text ?>
    <div id="r_header_text"<?= $Page->header_text->rowAttributes() ?>>
        <label id="elh_document_templates_header_text" for="x_header_text" class="<?= $Page->LeftColumnClass ?>"><?= $Page->header_text->caption() ?><?= $Page->header_text->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->header_text->cellAttributes() ?>>
<span id="el_document_templates_header_text">
<textarea data-table="document_templates" data-field="x_header_text" name="x_header_text" id="x_header_text" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->header_text->getPlaceHolder()) ?>"<?= $Page->header_text->editAttributes() ?> aria-describedby="x_header_text_help"><?= $Page->header_text->EditValue ?></textarea>
<?= $Page->header_text->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->header_text->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->footer_text->Visible) { // footer_text ?>
    <div id="r_footer_text"<?= $Page->footer_text->rowAttributes() ?>>
        <label id="elh_document_templates_footer_text" for="x_footer_text" class="<?= $Page->LeftColumnClass ?>"><?= $Page->footer_text->caption() ?><?= $Page->footer_text->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->footer_text->cellAttributes() ?>>
<span id="el_document_templates_footer_text">
<textarea data-table="document_templates" data-field="x_footer_text" name="x_footer_text" id="x_footer_text" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->footer_text->getPlaceHolder()) ?>"<?= $Page->footer_text->editAttributes() ?> aria-describedby="x_footer_text_help"><?= $Page->footer_text->EditValue ?></textarea>
<?= $Page->footer_text->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->footer_text->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->preview_image_path->Visible) { // preview_image_path ?>
    <div id="r_preview_image_path"<?= $Page->preview_image_path->rowAttributes() ?>>
        <label id="elh_document_templates_preview_image_path" for="x_preview_image_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->preview_image_path->caption() ?><?= $Page->preview_image_path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->preview_image_path->cellAttributes() ?>>
<span id="el_document_templates_preview_image_path">
<input type="<?= $Page->preview_image_path->getInputTextType() ?>" name="x_preview_image_path" id="x_preview_image_path" data-table="document_templates" data-field="x_preview_image_path" value="<?= $Page->preview_image_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->preview_image_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->preview_image_path->formatPattern()) ?>"<?= $Page->preview_image_path->editAttributes() ?> aria-describedby="x_preview_image_path_help">
<?= $Page->preview_image_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->preview_image_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdocument_templatesedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdocument_templatesedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("document_templates");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
