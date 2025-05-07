<?php

namespace PHPMaker2024\eNotary;

// Page object
$UserTemplatesAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { user_templates: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fuser_templatesadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fuser_templatesadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null, ew.Validators.integer], fields.user_id.isInvalid],
            ["template_id", [fields.template_id.visible && fields.template_id.required ? ew.Validators.required(fields.template_id.caption) : null, ew.Validators.integer], fields.template_id.isInvalid],
            ["custom_name", [fields.custom_name.visible && fields.custom_name.required ? ew.Validators.required(fields.custom_name.caption) : null], fields.custom_name.isInvalid],
            ["custom_content", [fields.custom_content.visible && fields.custom_content.required ? ew.Validators.required(fields.custom_content.caption) : null], fields.custom_content.isInvalid],
            ["is_custom", [fields.is_custom.visible && fields.is_custom.required ? ew.Validators.required(fields.is_custom.caption) : null], fields.is_custom.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid]
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
            "is_custom": <?= $Page->is_custom->toClientList($Page) ?>,
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
<form name="fuser_templatesadd" id="fuser_templatesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="user_templates">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->user_id->Visible) { // user_id ?>
    <div id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <label id="elh_user_templates_user_id" for="x_user_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_id->caption() ?><?= $Page->user_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_id->cellAttributes() ?>>
<span id="el_user_templates_user_id">
<input type="<?= $Page->user_id->getInputTextType() ?>" name="x_user_id" id="x_user_id" data-table="user_templates" data-field="x_user_id" value="<?= $Page->user_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->user_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user_id->formatPattern()) ?>"<?= $Page->user_id->editAttributes() ?> aria-describedby="x_user_id_help">
<?= $Page->user_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
    <div id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <label id="elh_user_templates_template_id" for="x_template_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->template_id->caption() ?><?= $Page->template_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->template_id->cellAttributes() ?>>
<span id="el_user_templates_template_id">
<input type="<?= $Page->template_id->getInputTextType() ?>" name="x_template_id" id="x_template_id" data-table="user_templates" data-field="x_template_id" value="<?= $Page->template_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->template_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->template_id->formatPattern()) ?>"<?= $Page->template_id->editAttributes() ?> aria-describedby="x_template_id_help">
<?= $Page->template_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->template_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->custom_name->Visible) { // custom_name ?>
    <div id="r_custom_name"<?= $Page->custom_name->rowAttributes() ?>>
        <label id="elh_user_templates_custom_name" for="x_custom_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->custom_name->caption() ?><?= $Page->custom_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->custom_name->cellAttributes() ?>>
<span id="el_user_templates_custom_name">
<input type="<?= $Page->custom_name->getInputTextType() ?>" name="x_custom_name" id="x_custom_name" data-table="user_templates" data-field="x_custom_name" value="<?= $Page->custom_name->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->custom_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->custom_name->formatPattern()) ?>"<?= $Page->custom_name->editAttributes() ?> aria-describedby="x_custom_name_help">
<?= $Page->custom_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->custom_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->custom_content->Visible) { // custom_content ?>
    <div id="r_custom_content"<?= $Page->custom_content->rowAttributes() ?>>
        <label id="elh_user_templates_custom_content" for="x_custom_content" class="<?= $Page->LeftColumnClass ?>"><?= $Page->custom_content->caption() ?><?= $Page->custom_content->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->custom_content->cellAttributes() ?>>
<span id="el_user_templates_custom_content">
<textarea data-table="user_templates" data-field="x_custom_content" name="x_custom_content" id="x_custom_content" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->custom_content->getPlaceHolder()) ?>"<?= $Page->custom_content->editAttributes() ?> aria-describedby="x_custom_content_help"><?= $Page->custom_content->EditValue ?></textarea>
<?= $Page->custom_content->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->custom_content->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_custom->Visible) { // is_custom ?>
    <div id="r_is_custom"<?= $Page->is_custom->rowAttributes() ?>>
        <label id="elh_user_templates_is_custom" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_custom->caption() ?><?= $Page->is_custom->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_custom->cellAttributes() ?>>
<span id="el_user_templates_is_custom">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_custom->isInvalidClass() ?>" data-table="user_templates" data-field="x_is_custom" data-boolean name="x_is_custom" id="x_is_custom" value="1"<?= ConvertToBool($Page->is_custom->CurrentValue) ? " checked" : "" ?><?= $Page->is_custom->editAttributes() ?> aria-describedby="x_is_custom_help">
    <div class="invalid-feedback"><?= $Page->is_custom->getErrorMessage() ?></div>
</div>
<?= $Page->is_custom->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_user_templates_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_user_templates_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="user_templates" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fuser_templatesadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fuser_templatesadd", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_user_templates_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_user_templates_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="user_templates" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fuser_templatesadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fuser_templatesadd", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fuser_templatesadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fuser_templatesadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("user_templates");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
