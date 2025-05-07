<?php

namespace PHPMaker2024\eNotary;

// Page object
$PaymentMethodsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { payment_methods: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fpayment_methodsadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpayment_methodsadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["method_name", [fields.method_name.visible && fields.method_name.required ? ew.Validators.required(fields.method_name.caption) : null], fields.method_name.isInvalid],
            ["method_code", [fields.method_code.visible && fields.method_code.required ? ew.Validators.required(fields.method_code.caption) : null], fields.method_code.isInvalid],
            ["description", [fields.description.visible && fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid],
            ["is_active", [fields.is_active.visible && fields.is_active.required ? ew.Validators.required(fields.is_active.caption) : null], fields.is_active.isInvalid],
            ["requires_verification", [fields.requires_verification.visible && fields.requires_verification.required ? ew.Validators.required(fields.requires_verification.caption) : null], fields.requires_verification.isInvalid],
            ["additional_fields", [fields.additional_fields.visible && fields.additional_fields.required ? ew.Validators.required(fields.additional_fields.caption) : null], fields.additional_fields.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["created_by", [fields.created_by.visible && fields.created_by.required ? ew.Validators.required(fields.created_by.caption) : null, ew.Validators.integer], fields.created_by.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid],
            ["updated_by", [fields.updated_by.visible && fields.updated_by.required ? ew.Validators.required(fields.updated_by.caption) : null, ew.Validators.integer], fields.updated_by.isInvalid]
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
            "requires_verification": <?= $Page->requires_verification->toClientList($Page) ?>,
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
<form name="fpayment_methodsadd" id="fpayment_methodsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="payment_methods">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->method_name->Visible) { // method_name ?>
    <div id="r_method_name"<?= $Page->method_name->rowAttributes() ?>>
        <label id="elh_payment_methods_method_name" for="x_method_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->method_name->caption() ?><?= $Page->method_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->method_name->cellAttributes() ?>>
<span id="el_payment_methods_method_name">
<input type="<?= $Page->method_name->getInputTextType() ?>" name="x_method_name" id="x_method_name" data-table="payment_methods" data-field="x_method_name" value="<?= $Page->method_name->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->method_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->method_name->formatPattern()) ?>"<?= $Page->method_name->editAttributes() ?> aria-describedby="x_method_name_help">
<?= $Page->method_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->method_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->method_code->Visible) { // method_code ?>
    <div id="r_method_code"<?= $Page->method_code->rowAttributes() ?>>
        <label id="elh_payment_methods_method_code" for="x_method_code" class="<?= $Page->LeftColumnClass ?>"><?= $Page->method_code->caption() ?><?= $Page->method_code->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->method_code->cellAttributes() ?>>
<span id="el_payment_methods_method_code">
<input type="<?= $Page->method_code->getInputTextType() ?>" name="x_method_code" id="x_method_code" data-table="payment_methods" data-field="x_method_code" value="<?= $Page->method_code->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->method_code->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->method_code->formatPattern()) ?>"<?= $Page->method_code->editAttributes() ?> aria-describedby="x_method_code_help">
<?= $Page->method_code->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->method_code->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description"<?= $Page->description->rowAttributes() ?>>
        <label id="elh_payment_methods_description" for="x_description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->description->cellAttributes() ?>>
<span id="el_payment_methods_description">
<textarea data-table="payment_methods" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help"><?= $Page->description->EditValue ?></textarea>
<?= $Page->description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <div id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <label id="elh_payment_methods_is_active" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_active->caption() ?><?= $Page->is_active->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_active->cellAttributes() ?>>
<span id="el_payment_methods_is_active">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_active->isInvalidClass() ?>" data-table="payment_methods" data-field="x_is_active" data-boolean name="x_is_active" id="x_is_active" value="1"<?= ConvertToBool($Page->is_active->CurrentValue) ? " checked" : "" ?><?= $Page->is_active->editAttributes() ?> aria-describedby="x_is_active_help">
    <div class="invalid-feedback"><?= $Page->is_active->getErrorMessage() ?></div>
</div>
<?= $Page->is_active->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->requires_verification->Visible) { // requires_verification ?>
    <div id="r_requires_verification"<?= $Page->requires_verification->rowAttributes() ?>>
        <label id="elh_payment_methods_requires_verification" class="<?= $Page->LeftColumnClass ?>"><?= $Page->requires_verification->caption() ?><?= $Page->requires_verification->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->requires_verification->cellAttributes() ?>>
<span id="el_payment_methods_requires_verification">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->requires_verification->isInvalidClass() ?>" data-table="payment_methods" data-field="x_requires_verification" data-boolean name="x_requires_verification" id="x_requires_verification" value="1"<?= ConvertToBool($Page->requires_verification->CurrentValue) ? " checked" : "" ?><?= $Page->requires_verification->editAttributes() ?> aria-describedby="x_requires_verification_help">
    <div class="invalid-feedback"><?= $Page->requires_verification->getErrorMessage() ?></div>
</div>
<?= $Page->requires_verification->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->additional_fields->Visible) { // additional_fields ?>
    <div id="r_additional_fields"<?= $Page->additional_fields->rowAttributes() ?>>
        <label id="elh_payment_methods_additional_fields" for="x_additional_fields" class="<?= $Page->LeftColumnClass ?>"><?= $Page->additional_fields->caption() ?><?= $Page->additional_fields->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->additional_fields->cellAttributes() ?>>
<span id="el_payment_methods_additional_fields">
<textarea data-table="payment_methods" data-field="x_additional_fields" name="x_additional_fields" id="x_additional_fields" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->additional_fields->getPlaceHolder()) ?>"<?= $Page->additional_fields->editAttributes() ?> aria-describedby="x_additional_fields_help"><?= $Page->additional_fields->EditValue ?></textarea>
<?= $Page->additional_fields->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->additional_fields->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_payment_methods_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_payment_methods_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="payment_methods" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpayment_methodsadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpayment_methodsadd", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
    <div id="r_created_by"<?= $Page->created_by->rowAttributes() ?>>
        <label id="elh_payment_methods_created_by" for="x_created_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_by->caption() ?><?= $Page->created_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_by->cellAttributes() ?>>
<span id="el_payment_methods_created_by">
<input type="<?= $Page->created_by->getInputTextType() ?>" name="x_created_by" id="x_created_by" data-table="payment_methods" data-field="x_created_by" value="<?= $Page->created_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->created_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_by->formatPattern()) ?>"<?= $Page->created_by->editAttributes() ?> aria-describedby="x_created_by_help">
<?= $Page->created_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_payment_methods_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_payment_methods_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="payment_methods" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpayment_methodsadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpayment_methodsadd", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
    <div id="r_updated_by"<?= $Page->updated_by->rowAttributes() ?>>
        <label id="elh_payment_methods_updated_by" for="x_updated_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_by->caption() ?><?= $Page->updated_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_by->cellAttributes() ?>>
<span id="el_payment_methods_updated_by">
<input type="<?= $Page->updated_by->getInputTextType() ?>" name="x_updated_by" id="x_updated_by" data-table="payment_methods" data-field="x_updated_by" value="<?= $Page->updated_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->updated_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_by->formatPattern()) ?>"<?= $Page->updated_by->editAttributes() ?> aria-describedby="x_updated_by_help">
<?= $Page->updated_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpayment_methodsadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpayment_methodsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("payment_methods");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
