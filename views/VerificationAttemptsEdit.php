<?php

namespace PHPMaker2024\eNotary;

// Page object
$VerificationAttemptsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fverification_attemptsedit" id="fverification_attemptsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { verification_attempts: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fverification_attemptsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fverification_attemptsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["attempt_id", [fields.attempt_id.visible && fields.attempt_id.required ? ew.Validators.required(fields.attempt_id.caption) : null], fields.attempt_id.isInvalid],
            ["verification_id", [fields.verification_id.visible && fields.verification_id.required ? ew.Validators.required(fields.verification_id.caption) : null, ew.Validators.integer], fields.verification_id.isInvalid],
            ["document_number", [fields.document_number.visible && fields.document_number.required ? ew.Validators.required(fields.document_number.caption) : null], fields.document_number.isInvalid],
            ["keycode", [fields.keycode.visible && fields.keycode.required ? ew.Validators.required(fields.keycode.caption) : null], fields.keycode.isInvalid],
            ["ip_address", [fields.ip_address.visible && fields.ip_address.required ? ew.Validators.required(fields.ip_address.caption) : null], fields.ip_address.isInvalid],
            ["user_agent", [fields.user_agent.visible && fields.user_agent.required ? ew.Validators.required(fields.user_agent.caption) : null], fields.user_agent.isInvalid],
            ["verification_date", [fields.verification_date.visible && fields.verification_date.required ? ew.Validators.required(fields.verification_date.caption) : null, ew.Validators.datetime(fields.verification_date.clientFormatPattern)], fields.verification_date.isInvalid],
            ["is_successful", [fields.is_successful.visible && fields.is_successful.required ? ew.Validators.required(fields.is_successful.caption) : null], fields.is_successful.isInvalid],
            ["failure_reason", [fields.failure_reason.visible && fields.failure_reason.required ? ew.Validators.required(fields.failure_reason.caption) : null], fields.failure_reason.isInvalid],
            ["location", [fields.location.visible && fields.location.required ? ew.Validators.required(fields.location.caption) : null], fields.location.isInvalid],
            ["device_info", [fields.device_info.visible && fields.device_info.required ? ew.Validators.required(fields.device_info.caption) : null], fields.device_info.isInvalid],
            ["browser_info", [fields.browser_info.visible && fields.browser_info.required ? ew.Validators.required(fields.browser_info.caption) : null], fields.browser_info.isInvalid]
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
            "is_successful": <?= $Page->is_successful->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="verification_attempts">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->attempt_id->Visible) { // attempt_id ?>
    <div id="r_attempt_id"<?= $Page->attempt_id->rowAttributes() ?>>
        <label id="elh_verification_attempts_attempt_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->attempt_id->caption() ?><?= $Page->attempt_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->attempt_id->cellAttributes() ?>>
<span id="el_verification_attempts_attempt_id">
<span<?= $Page->attempt_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->attempt_id->getDisplayValue($Page->attempt_id->EditValue))) ?>"></span>
<input type="hidden" data-table="verification_attempts" data-field="x_attempt_id" data-hidden="1" name="x_attempt_id" id="x_attempt_id" value="<?= HtmlEncode($Page->attempt_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->verification_id->Visible) { // verification_id ?>
    <div id="r_verification_id"<?= $Page->verification_id->rowAttributes() ?>>
        <label id="elh_verification_attempts_verification_id" for="x_verification_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->verification_id->caption() ?><?= $Page->verification_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->verification_id->cellAttributes() ?>>
<span id="el_verification_attempts_verification_id">
<input type="<?= $Page->verification_id->getInputTextType() ?>" name="x_verification_id" id="x_verification_id" data-table="verification_attempts" data-field="x_verification_id" value="<?= $Page->verification_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->verification_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->verification_id->formatPattern()) ?>"<?= $Page->verification_id->editAttributes() ?> aria-describedby="x_verification_id_help">
<?= $Page->verification_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->verification_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
    <div id="r_document_number"<?= $Page->document_number->rowAttributes() ?>>
        <label id="elh_verification_attempts_document_number" for="x_document_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_number->caption() ?><?= $Page->document_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_number->cellAttributes() ?>>
<span id="el_verification_attempts_document_number">
<input type="<?= $Page->document_number->getInputTextType() ?>" name="x_document_number" id="x_document_number" data-table="verification_attempts" data-field="x_document_number" value="<?= $Page->document_number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->document_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_number->formatPattern()) ?>"<?= $Page->document_number->editAttributes() ?> aria-describedby="x_document_number_help">
<?= $Page->document_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->keycode->Visible) { // keycode ?>
    <div id="r_keycode"<?= $Page->keycode->rowAttributes() ?>>
        <label id="elh_verification_attempts_keycode" for="x_keycode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->keycode->caption() ?><?= $Page->keycode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->keycode->cellAttributes() ?>>
<span id="el_verification_attempts_keycode">
<input type="<?= $Page->keycode->getInputTextType() ?>" name="x_keycode" id="x_keycode" data-table="verification_attempts" data-field="x_keycode" value="<?= $Page->keycode->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->keycode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->keycode->formatPattern()) ?>"<?= $Page->keycode->editAttributes() ?> aria-describedby="x_keycode_help">
<?= $Page->keycode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->keycode->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <div id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <label id="elh_verification_attempts_ip_address" for="x_ip_address" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ip_address->caption() ?><?= $Page->ip_address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_verification_attempts_ip_address">
<input type="<?= $Page->ip_address->getInputTextType() ?>" name="x_ip_address" id="x_ip_address" data-table="verification_attempts" data-field="x_ip_address" value="<?= $Page->ip_address->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ip_address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ip_address->formatPattern()) ?>"<?= $Page->ip_address->editAttributes() ?> aria-describedby="x_ip_address_help">
<?= $Page->ip_address->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ip_address->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_agent->Visible) { // user_agent ?>
    <div id="r_user_agent"<?= $Page->user_agent->rowAttributes() ?>>
        <label id="elh_verification_attempts_user_agent" for="x_user_agent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_agent->caption() ?><?= $Page->user_agent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_agent->cellAttributes() ?>>
<span id="el_verification_attempts_user_agent">
<textarea data-table="verification_attempts" data-field="x_user_agent" name="x_user_agent" id="x_user_agent" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->user_agent->getPlaceHolder()) ?>"<?= $Page->user_agent->editAttributes() ?> aria-describedby="x_user_agent_help"><?= $Page->user_agent->EditValue ?></textarea>
<?= $Page->user_agent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_agent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
    <div id="r_verification_date"<?= $Page->verification_date->rowAttributes() ?>>
        <label id="elh_verification_attempts_verification_date" for="x_verification_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->verification_date->caption() ?><?= $Page->verification_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->verification_date->cellAttributes() ?>>
<span id="el_verification_attempts_verification_date">
<input type="<?= $Page->verification_date->getInputTextType() ?>" name="x_verification_date" id="x_verification_date" data-table="verification_attempts" data-field="x_verification_date" value="<?= $Page->verification_date->EditValue ?>" placeholder="<?= HtmlEncode($Page->verification_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->verification_date->formatPattern()) ?>"<?= $Page->verification_date->editAttributes() ?> aria-describedby="x_verification_date_help">
<?= $Page->verification_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->verification_date->getErrorMessage() ?></div>
<?php if (!$Page->verification_date->ReadOnly && !$Page->verification_date->Disabled && !isset($Page->verification_date->EditAttrs["readonly"]) && !isset($Page->verification_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fverification_attemptsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fverification_attemptsedit", "x_verification_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_successful->Visible) { // is_successful ?>
    <div id="r_is_successful"<?= $Page->is_successful->rowAttributes() ?>>
        <label id="elh_verification_attempts_is_successful" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_successful->caption() ?><?= $Page->is_successful->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_successful->cellAttributes() ?>>
<span id="el_verification_attempts_is_successful">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_successful->isInvalidClass() ?>" data-table="verification_attempts" data-field="x_is_successful" data-boolean name="x_is_successful" id="x_is_successful" value="1"<?= ConvertToBool($Page->is_successful->CurrentValue) ? " checked" : "" ?><?= $Page->is_successful->editAttributes() ?> aria-describedby="x_is_successful_help">
    <div class="invalid-feedback"><?= $Page->is_successful->getErrorMessage() ?></div>
</div>
<?= $Page->is_successful->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->failure_reason->Visible) { // failure_reason ?>
    <div id="r_failure_reason"<?= $Page->failure_reason->rowAttributes() ?>>
        <label id="elh_verification_attempts_failure_reason" for="x_failure_reason" class="<?= $Page->LeftColumnClass ?>"><?= $Page->failure_reason->caption() ?><?= $Page->failure_reason->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->failure_reason->cellAttributes() ?>>
<span id="el_verification_attempts_failure_reason">
<textarea data-table="verification_attempts" data-field="x_failure_reason" name="x_failure_reason" id="x_failure_reason" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->failure_reason->getPlaceHolder()) ?>"<?= $Page->failure_reason->editAttributes() ?> aria-describedby="x_failure_reason_help"><?= $Page->failure_reason->EditValue ?></textarea>
<?= $Page->failure_reason->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->failure_reason->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->location->Visible) { // location ?>
    <div id="r_location"<?= $Page->location->rowAttributes() ?>>
        <label id="elh_verification_attempts_location" for="x_location" class="<?= $Page->LeftColumnClass ?>"><?= $Page->location->caption() ?><?= $Page->location->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->location->cellAttributes() ?>>
<span id="el_verification_attempts_location">
<input type="<?= $Page->location->getInputTextType() ?>" name="x_location" id="x_location" data-table="verification_attempts" data-field="x_location" value="<?= $Page->location->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->location->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->location->formatPattern()) ?>"<?= $Page->location->editAttributes() ?> aria-describedby="x_location_help">
<?= $Page->location->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->location->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->device_info->Visible) { // device_info ?>
    <div id="r_device_info"<?= $Page->device_info->rowAttributes() ?>>
        <label id="elh_verification_attempts_device_info" for="x_device_info" class="<?= $Page->LeftColumnClass ?>"><?= $Page->device_info->caption() ?><?= $Page->device_info->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->device_info->cellAttributes() ?>>
<span id="el_verification_attempts_device_info">
<textarea data-table="verification_attempts" data-field="x_device_info" name="x_device_info" id="x_device_info" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->device_info->getPlaceHolder()) ?>"<?= $Page->device_info->editAttributes() ?> aria-describedby="x_device_info_help"><?= $Page->device_info->EditValue ?></textarea>
<?= $Page->device_info->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->device_info->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->browser_info->Visible) { // browser_info ?>
    <div id="r_browser_info"<?= $Page->browser_info->rowAttributes() ?>>
        <label id="elh_verification_attempts_browser_info" for="x_browser_info" class="<?= $Page->LeftColumnClass ?>"><?= $Page->browser_info->caption() ?><?= $Page->browser_info->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->browser_info->cellAttributes() ?>>
<span id="el_verification_attempts_browser_info">
<textarea data-table="verification_attempts" data-field="x_browser_info" name="x_browser_info" id="x_browser_info" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->browser_info->getPlaceHolder()) ?>"<?= $Page->browser_info->editAttributes() ?> aria-describedby="x_browser_info_help"><?= $Page->browser_info->EditValue ?></textarea>
<?= $Page->browser_info->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->browser_info->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fverification_attemptsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fverification_attemptsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("verification_attempts");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
