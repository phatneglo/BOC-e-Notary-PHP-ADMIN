<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentFieldsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fdocument_fieldsedit" id="fdocument_fieldsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_fields: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fdocument_fieldsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_fieldsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["document_field_id", [fields.document_field_id.visible && fields.document_field_id.required ? ew.Validators.required(fields.document_field_id.caption) : null], fields.document_field_id.isInvalid],
            ["document_id", [fields.document_id.visible && fields.document_id.required ? ew.Validators.required(fields.document_id.caption) : null, ew.Validators.integer], fields.document_id.isInvalid],
            ["field_id", [fields.field_id.visible && fields.field_id.required ? ew.Validators.required(fields.field_id.caption) : null, ew.Validators.integer], fields.field_id.isInvalid],
            ["field_value", [fields.field_value.visible && fields.field_value.required ? ew.Validators.required(fields.field_value.caption) : null], fields.field_value.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid],
            ["is_verified", [fields.is_verified.visible && fields.is_verified.required ? ew.Validators.required(fields.is_verified.caption) : null], fields.is_verified.isInvalid],
            ["verified_by", [fields.verified_by.visible && fields.verified_by.required ? ew.Validators.required(fields.verified_by.caption) : null, ew.Validators.integer], fields.verified_by.isInvalid],
            ["verification_date", [fields.verification_date.visible && fields.verification_date.required ? ew.Validators.required(fields.verification_date.caption) : null, ew.Validators.datetime(fields.verification_date.clientFormatPattern)], fields.verification_date.isInvalid]
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
            "is_verified": <?= $Page->is_verified->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="document_fields">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->document_field_id->Visible) { // document_field_id ?>
    <div id="r_document_field_id"<?= $Page->document_field_id->rowAttributes() ?>>
        <label id="elh_document_fields_document_field_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_field_id->caption() ?><?= $Page->document_field_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_field_id->cellAttributes() ?>>
<span id="el_document_fields_document_field_id">
<span<?= $Page->document_field_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->document_field_id->getDisplayValue($Page->document_field_id->EditValue))) ?>"></span>
<input type="hidden" data-table="document_fields" data-field="x_document_field_id" data-hidden="1" name="x_document_field_id" id="x_document_field_id" value="<?= HtmlEncode($Page->document_field_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <div id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <label id="elh_document_fields_document_id" for="x_document_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_id->caption() ?><?= $Page->document_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_id->cellAttributes() ?>>
<span id="el_document_fields_document_id">
<input type="<?= $Page->document_id->getInputTextType() ?>" name="x_document_id" id="x_document_id" data-table="document_fields" data-field="x_document_id" value="<?= $Page->document_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->document_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_id->formatPattern()) ?>"<?= $Page->document_id->editAttributes() ?> aria-describedby="x_document_id_help">
<?= $Page->document_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field_id->Visible) { // field_id ?>
    <div id="r_field_id"<?= $Page->field_id->rowAttributes() ?>>
        <label id="elh_document_fields_field_id" for="x_field_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field_id->caption() ?><?= $Page->field_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field_id->cellAttributes() ?>>
<span id="el_document_fields_field_id">
<input type="<?= $Page->field_id->getInputTextType() ?>" name="x_field_id" id="x_field_id" data-table="document_fields" data-field="x_field_id" value="<?= $Page->field_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->field_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->field_id->formatPattern()) ?>"<?= $Page->field_id->editAttributes() ?> aria-describedby="x_field_id_help">
<?= $Page->field_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field_value->Visible) { // field_value ?>
    <div id="r_field_value"<?= $Page->field_value->rowAttributes() ?>>
        <label id="elh_document_fields_field_value" for="x_field_value" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field_value->caption() ?><?= $Page->field_value->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field_value->cellAttributes() ?>>
<span id="el_document_fields_field_value">
<textarea data-table="document_fields" data-field="x_field_value" name="x_field_value" id="x_field_value" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->field_value->getPlaceHolder()) ?>"<?= $Page->field_value->editAttributes() ?> aria-describedby="x_field_value_help"><?= $Page->field_value->EditValue ?></textarea>
<?= $Page->field_value->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field_value->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_document_fields_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_document_fields_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="document_fields" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocument_fieldsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocument_fieldsedit", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_verified->Visible) { // is_verified ?>
    <div id="r_is_verified"<?= $Page->is_verified->rowAttributes() ?>>
        <label id="elh_document_fields_is_verified" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_verified->caption() ?><?= $Page->is_verified->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_verified->cellAttributes() ?>>
<span id="el_document_fields_is_verified">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_verified->isInvalidClass() ?>" data-table="document_fields" data-field="x_is_verified" data-boolean name="x_is_verified" id="x_is_verified" value="1"<?= ConvertToBool($Page->is_verified->CurrentValue) ? " checked" : "" ?><?= $Page->is_verified->editAttributes() ?> aria-describedby="x_is_verified_help">
    <div class="invalid-feedback"><?= $Page->is_verified->getErrorMessage() ?></div>
</div>
<?= $Page->is_verified->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->verified_by->Visible) { // verified_by ?>
    <div id="r_verified_by"<?= $Page->verified_by->rowAttributes() ?>>
        <label id="elh_document_fields_verified_by" for="x_verified_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->verified_by->caption() ?><?= $Page->verified_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->verified_by->cellAttributes() ?>>
<span id="el_document_fields_verified_by">
<input type="<?= $Page->verified_by->getInputTextType() ?>" name="x_verified_by" id="x_verified_by" data-table="document_fields" data-field="x_verified_by" value="<?= $Page->verified_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->verified_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->verified_by->formatPattern()) ?>"<?= $Page->verified_by->editAttributes() ?> aria-describedby="x_verified_by_help">
<?= $Page->verified_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->verified_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
    <div id="r_verification_date"<?= $Page->verification_date->rowAttributes() ?>>
        <label id="elh_document_fields_verification_date" for="x_verification_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->verification_date->caption() ?><?= $Page->verification_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->verification_date->cellAttributes() ?>>
<span id="el_document_fields_verification_date">
<input type="<?= $Page->verification_date->getInputTextType() ?>" name="x_verification_date" id="x_verification_date" data-table="document_fields" data-field="x_verification_date" value="<?= $Page->verification_date->EditValue ?>" placeholder="<?= HtmlEncode($Page->verification_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->verification_date->formatPattern()) ?>"<?= $Page->verification_date->editAttributes() ?> aria-describedby="x_verification_date_help">
<?= $Page->verification_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->verification_date->getErrorMessage() ?></div>
<?php if (!$Page->verification_date->ReadOnly && !$Page->verification_date->Disabled && !isset($Page->verification_date->EditAttrs["readonly"]) && !isset($Page->verification_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocument_fieldsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocument_fieldsedit", "x_verification_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdocument_fieldsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdocument_fieldsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("document_fields");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
