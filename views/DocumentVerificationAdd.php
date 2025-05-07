<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentVerificationAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_verification: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fdocument_verificationadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_verificationadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["notarized_id", [fields.notarized_id.visible && fields.notarized_id.required ? ew.Validators.required(fields.notarized_id.caption) : null, ew.Validators.integer], fields.notarized_id.isInvalid],
            ["document_number", [fields.document_number.visible && fields.document_number.required ? ew.Validators.required(fields.document_number.caption) : null], fields.document_number.isInvalid],
            ["keycode", [fields.keycode.visible && fields.keycode.required ? ew.Validators.required(fields.keycode.caption) : null], fields.keycode.isInvalid],
            ["verification_url", [fields.verification_url.visible && fields.verification_url.required ? ew.Validators.required(fields.verification_url.caption) : null], fields.verification_url.isInvalid],
            ["qr_code_path", [fields.qr_code_path.visible && fields.qr_code_path.required ? ew.Validators.required(fields.qr_code_path.caption) : null], fields.qr_code_path.isInvalid],
            ["is_active", [fields.is_active.visible && fields.is_active.required ? ew.Validators.required(fields.is_active.caption) : null], fields.is_active.isInvalid],
            ["expiry_date", [fields.expiry_date.visible && fields.expiry_date.required ? ew.Validators.required(fields.expiry_date.caption) : null, ew.Validators.datetime(fields.expiry_date.clientFormatPattern)], fields.expiry_date.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["failed_attempts", [fields.failed_attempts.visible && fields.failed_attempts.required ? ew.Validators.required(fields.failed_attempts.caption) : null, ew.Validators.integer], fields.failed_attempts.isInvalid],
            ["blocked_until", [fields.blocked_until.visible && fields.blocked_until.required ? ew.Validators.required(fields.blocked_until.caption) : null, ew.Validators.datetime(fields.blocked_until.clientFormatPattern)], fields.blocked_until.isInvalid]
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
<form name="fdocument_verificationadd" id="fdocument_verificationadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="document_verification">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
    <div id="r_notarized_id"<?= $Page->notarized_id->rowAttributes() ?>>
        <label id="elh_document_verification_notarized_id" for="x_notarized_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notarized_id->caption() ?><?= $Page->notarized_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notarized_id->cellAttributes() ?>>
<span id="el_document_verification_notarized_id">
<input type="<?= $Page->notarized_id->getInputTextType() ?>" name="x_notarized_id" id="x_notarized_id" data-table="document_verification" data-field="x_notarized_id" value="<?= $Page->notarized_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->notarized_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notarized_id->formatPattern()) ?>"<?= $Page->notarized_id->editAttributes() ?> aria-describedby="x_notarized_id_help">
<?= $Page->notarized_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notarized_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
    <div id="r_document_number"<?= $Page->document_number->rowAttributes() ?>>
        <label id="elh_document_verification_document_number" for="x_document_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_number->caption() ?><?= $Page->document_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_number->cellAttributes() ?>>
<span id="el_document_verification_document_number">
<input type="<?= $Page->document_number->getInputTextType() ?>" name="x_document_number" id="x_document_number" data-table="document_verification" data-field="x_document_number" value="<?= $Page->document_number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->document_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_number->formatPattern()) ?>"<?= $Page->document_number->editAttributes() ?> aria-describedby="x_document_number_help">
<?= $Page->document_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->keycode->Visible) { // keycode ?>
    <div id="r_keycode"<?= $Page->keycode->rowAttributes() ?>>
        <label id="elh_document_verification_keycode" for="x_keycode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->keycode->caption() ?><?= $Page->keycode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->keycode->cellAttributes() ?>>
<span id="el_document_verification_keycode">
<input type="<?= $Page->keycode->getInputTextType() ?>" name="x_keycode" id="x_keycode" data-table="document_verification" data-field="x_keycode" value="<?= $Page->keycode->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->keycode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->keycode->formatPattern()) ?>"<?= $Page->keycode->editAttributes() ?> aria-describedby="x_keycode_help">
<?= $Page->keycode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->keycode->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->verification_url->Visible) { // verification_url ?>
    <div id="r_verification_url"<?= $Page->verification_url->rowAttributes() ?>>
        <label id="elh_document_verification_verification_url" for="x_verification_url" class="<?= $Page->LeftColumnClass ?>"><?= $Page->verification_url->caption() ?><?= $Page->verification_url->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->verification_url->cellAttributes() ?>>
<span id="el_document_verification_verification_url">
<input type="<?= $Page->verification_url->getInputTextType() ?>" name="x_verification_url" id="x_verification_url" data-table="document_verification" data-field="x_verification_url" value="<?= $Page->verification_url->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->verification_url->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->verification_url->formatPattern()) ?>"<?= $Page->verification_url->editAttributes() ?> aria-describedby="x_verification_url_help">
<?= $Page->verification_url->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->verification_url->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
    <div id="r_qr_code_path"<?= $Page->qr_code_path->rowAttributes() ?>>
        <label id="elh_document_verification_qr_code_path" for="x_qr_code_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->qr_code_path->caption() ?><?= $Page->qr_code_path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="el_document_verification_qr_code_path">
<input type="<?= $Page->qr_code_path->getInputTextType() ?>" name="x_qr_code_path" id="x_qr_code_path" data-table="document_verification" data-field="x_qr_code_path" value="<?= $Page->qr_code_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->qr_code_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->qr_code_path->formatPattern()) ?>"<?= $Page->qr_code_path->editAttributes() ?> aria-describedby="x_qr_code_path_help">
<?= $Page->qr_code_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->qr_code_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <div id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <label id="elh_document_verification_is_active" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_active->caption() ?><?= $Page->is_active->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_active->cellAttributes() ?>>
<span id="el_document_verification_is_active">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_active->isInvalidClass() ?>" data-table="document_verification" data-field="x_is_active" data-boolean name="x_is_active" id="x_is_active" value="1"<?= ConvertToBool($Page->is_active->CurrentValue) ? " checked" : "" ?><?= $Page->is_active->editAttributes() ?> aria-describedby="x_is_active_help">
    <div class="invalid-feedback"><?= $Page->is_active->getErrorMessage() ?></div>
</div>
<?= $Page->is_active->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->expiry_date->Visible) { // expiry_date ?>
    <div id="r_expiry_date"<?= $Page->expiry_date->rowAttributes() ?>>
        <label id="elh_document_verification_expiry_date" for="x_expiry_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->expiry_date->caption() ?><?= $Page->expiry_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->expiry_date->cellAttributes() ?>>
<span id="el_document_verification_expiry_date">
<input type="<?= $Page->expiry_date->getInputTextType() ?>" name="x_expiry_date" id="x_expiry_date" data-table="document_verification" data-field="x_expiry_date" value="<?= $Page->expiry_date->EditValue ?>" placeholder="<?= HtmlEncode($Page->expiry_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->expiry_date->formatPattern()) ?>"<?= $Page->expiry_date->editAttributes() ?> aria-describedby="x_expiry_date_help">
<?= $Page->expiry_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->expiry_date->getErrorMessage() ?></div>
<?php if (!$Page->expiry_date->ReadOnly && !$Page->expiry_date->Disabled && !isset($Page->expiry_date->EditAttrs["readonly"]) && !isset($Page->expiry_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocument_verificationadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocument_verificationadd", "x_expiry_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_document_verification_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_document_verification_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="document_verification" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocument_verificationadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocument_verificationadd", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->failed_attempts->Visible) { // failed_attempts ?>
    <div id="r_failed_attempts"<?= $Page->failed_attempts->rowAttributes() ?>>
        <label id="elh_document_verification_failed_attempts" for="x_failed_attempts" class="<?= $Page->LeftColumnClass ?>"><?= $Page->failed_attempts->caption() ?><?= $Page->failed_attempts->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->failed_attempts->cellAttributes() ?>>
<span id="el_document_verification_failed_attempts">
<input type="<?= $Page->failed_attempts->getInputTextType() ?>" name="x_failed_attempts" id="x_failed_attempts" data-table="document_verification" data-field="x_failed_attempts" value="<?= $Page->failed_attempts->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->failed_attempts->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->failed_attempts->formatPattern()) ?>"<?= $Page->failed_attempts->editAttributes() ?> aria-describedby="x_failed_attempts_help">
<?= $Page->failed_attempts->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->failed_attempts->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->blocked_until->Visible) { // blocked_until ?>
    <div id="r_blocked_until"<?= $Page->blocked_until->rowAttributes() ?>>
        <label id="elh_document_verification_blocked_until" for="x_blocked_until" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blocked_until->caption() ?><?= $Page->blocked_until->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blocked_until->cellAttributes() ?>>
<span id="el_document_verification_blocked_until">
<input type="<?= $Page->blocked_until->getInputTextType() ?>" name="x_blocked_until" id="x_blocked_until" data-table="document_verification" data-field="x_blocked_until" value="<?= $Page->blocked_until->EditValue ?>" placeholder="<?= HtmlEncode($Page->blocked_until->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->blocked_until->formatPattern()) ?>"<?= $Page->blocked_until->editAttributes() ?> aria-describedby="x_blocked_until_help">
<?= $Page->blocked_until->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->blocked_until->getErrorMessage() ?></div>
<?php if (!$Page->blocked_until->ReadOnly && !$Page->blocked_until->Disabled && !isset($Page->blocked_until->EditAttrs["readonly"]) && !isset($Page->blocked_until->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocument_verificationadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocument_verificationadd", "x_blocked_until", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdocument_verificationadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdocument_verificationadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("document_verification");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
