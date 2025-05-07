<?php

namespace PHPMaker2024\eNotary;

// Page object
$SupportRequestsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fsupport_requestsedit" id="fsupport_requestsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { support_requests: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fsupport_requestsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsupport_requestsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["request_id", [fields.request_id.visible && fields.request_id.required ? ew.Validators.required(fields.request_id.caption) : null], fields.request_id.isInvalid],
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null, ew.Validators.integer], fields.user_id.isInvalid],
            ["name", [fields.name.visible && fields.name.required ? ew.Validators.required(fields.name.caption) : null], fields.name.isInvalid],
            ["_email", [fields._email.visible && fields._email.required ? ew.Validators.required(fields._email.caption) : null], fields._email.isInvalid],
            ["subject", [fields.subject.visible && fields.subject.required ? ew.Validators.required(fields.subject.caption) : null], fields.subject.isInvalid],
            ["_message", [fields._message.visible && fields._message.required ? ew.Validators.required(fields._message.caption) : null], fields._message.isInvalid],
            ["request_type", [fields.request_type.visible && fields.request_type.required ? ew.Validators.required(fields.request_type.caption) : null], fields.request_type.isInvalid],
            ["reference_number", [fields.reference_number.visible && fields.reference_number.required ? ew.Validators.required(fields.reference_number.caption) : null], fields.reference_number.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid],
            ["assigned_to", [fields.assigned_to.visible && fields.assigned_to.required ? ew.Validators.required(fields.assigned_to.caption) : null, ew.Validators.integer], fields.assigned_to.isInvalid],
            ["resolved_at", [fields.resolved_at.visible && fields.resolved_at.required ? ew.Validators.required(fields.resolved_at.caption) : null, ew.Validators.datetime(fields.resolved_at.clientFormatPattern)], fields.resolved_at.isInvalid],
            ["_response", [fields._response.visible && fields._response.required ? ew.Validators.required(fields._response.caption) : null], fields._response.isInvalid],
            ["ip_address", [fields.ip_address.visible && fields.ip_address.required ? ew.Validators.required(fields.ip_address.caption) : null], fields.ip_address.isInvalid],
            ["user_agent", [fields.user_agent.visible && fields.user_agent.required ? ew.Validators.required(fields.user_agent.caption) : null], fields.user_agent.isInvalid]
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
<input type="hidden" name="t" value="support_requests">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->request_id->Visible) { // request_id ?>
    <div id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <label id="elh_support_requests_request_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->request_id->caption() ?><?= $Page->request_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->request_id->cellAttributes() ?>>
<span id="el_support_requests_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->request_id->getDisplayValue($Page->request_id->EditValue))) ?>"></span>
<input type="hidden" data-table="support_requests" data-field="x_request_id" data-hidden="1" name="x_request_id" id="x_request_id" value="<?= HtmlEncode($Page->request_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <div id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <label id="elh_support_requests_user_id" for="x_user_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_id->caption() ?><?= $Page->user_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_id->cellAttributes() ?>>
<span id="el_support_requests_user_id">
<input type="<?= $Page->user_id->getInputTextType() ?>" name="x_user_id" id="x_user_id" data-table="support_requests" data-field="x_user_id" value="<?= $Page->user_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->user_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user_id->formatPattern()) ?>"<?= $Page->user_id->editAttributes() ?> aria-describedby="x_user_id_help">
<?= $Page->user_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <div id="r_name"<?= $Page->name->rowAttributes() ?>>
        <label id="elh_support_requests_name" for="x_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->name->caption() ?><?= $Page->name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->name->cellAttributes() ?>>
<span id="el_support_requests_name">
<input type="<?= $Page->name->getInputTextType() ?>" name="x_name" id="x_name" data-table="support_requests" data-field="x_name" value="<?= $Page->name->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->name->formatPattern()) ?>"<?= $Page->name->editAttributes() ?> aria-describedby="x_name_help">
<?= $Page->name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
    <div id="r__email"<?= $Page->_email->rowAttributes() ?>>
        <label id="elh_support_requests__email" for="x__email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_email->caption() ?><?= $Page->_email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_email->cellAttributes() ?>>
<span id="el_support_requests__email">
<input type="<?= $Page->_email->getInputTextType() ?>" name="x__email" id="x__email" data-table="support_requests" data-field="x__email" value="<?= $Page->_email->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->_email->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_email->formatPattern()) ?>"<?= $Page->_email->editAttributes() ?> aria-describedby="x__email_help">
<?= $Page->_email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_email->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->subject->Visible) { // subject ?>
    <div id="r_subject"<?= $Page->subject->rowAttributes() ?>>
        <label id="elh_support_requests_subject" for="x_subject" class="<?= $Page->LeftColumnClass ?>"><?= $Page->subject->caption() ?><?= $Page->subject->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->subject->cellAttributes() ?>>
<span id="el_support_requests_subject">
<input type="<?= $Page->subject->getInputTextType() ?>" name="x_subject" id="x_subject" data-table="support_requests" data-field="x_subject" value="<?= $Page->subject->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->subject->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->subject->formatPattern()) ?>"<?= $Page->subject->editAttributes() ?> aria-describedby="x_subject_help">
<?= $Page->subject->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->subject->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_message->Visible) { // message ?>
    <div id="r__message"<?= $Page->_message->rowAttributes() ?>>
        <label id="elh_support_requests__message" for="x__message" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_message->caption() ?><?= $Page->_message->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_message->cellAttributes() ?>>
<span id="el_support_requests__message">
<textarea data-table="support_requests" data-field="x__message" name="x__message" id="x__message" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->_message->getPlaceHolder()) ?>"<?= $Page->_message->editAttributes() ?> aria-describedby="x__message_help"><?= $Page->_message->EditValue ?></textarea>
<?= $Page->_message->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_message->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->request_type->Visible) { // request_type ?>
    <div id="r_request_type"<?= $Page->request_type->rowAttributes() ?>>
        <label id="elh_support_requests_request_type" for="x_request_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->request_type->caption() ?><?= $Page->request_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->request_type->cellAttributes() ?>>
<span id="el_support_requests_request_type">
<input type="<?= $Page->request_type->getInputTextType() ?>" name="x_request_type" id="x_request_type" data-table="support_requests" data-field="x_request_type" value="<?= $Page->request_type->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->request_type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->request_type->formatPattern()) ?>"<?= $Page->request_type->editAttributes() ?> aria-describedby="x_request_type_help">
<?= $Page->request_type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->request_type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reference_number->Visible) { // reference_number ?>
    <div id="r_reference_number"<?= $Page->reference_number->rowAttributes() ?>>
        <label id="elh_support_requests_reference_number" for="x_reference_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reference_number->caption() ?><?= $Page->reference_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reference_number->cellAttributes() ?>>
<span id="el_support_requests_reference_number">
<input type="<?= $Page->reference_number->getInputTextType() ?>" name="x_reference_number" id="x_reference_number" data-table="support_requests" data-field="x_reference_number" value="<?= $Page->reference_number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->reference_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->reference_number->formatPattern()) ?>"<?= $Page->reference_number->editAttributes() ?> aria-describedby="x_reference_number_help">
<?= $Page->reference_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->reference_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_support_requests_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_support_requests_status">
<input type="<?= $Page->status->getInputTextType() ?>" name="x_status" id="x_status" data-table="support_requests" data-field="x_status" value="<?= $Page->status->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->status->formatPattern()) ?>"<?= $Page->status->editAttributes() ?> aria-describedby="x_status_help">
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_support_requests_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_support_requests_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="support_requests" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsupport_requestsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fsupport_requestsedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_support_requests_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_support_requests_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="support_requests" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsupport_requestsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fsupport_requestsedit", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->assigned_to->Visible) { // assigned_to ?>
    <div id="r_assigned_to"<?= $Page->assigned_to->rowAttributes() ?>>
        <label id="elh_support_requests_assigned_to" for="x_assigned_to" class="<?= $Page->LeftColumnClass ?>"><?= $Page->assigned_to->caption() ?><?= $Page->assigned_to->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->assigned_to->cellAttributes() ?>>
<span id="el_support_requests_assigned_to">
<input type="<?= $Page->assigned_to->getInputTextType() ?>" name="x_assigned_to" id="x_assigned_to" data-table="support_requests" data-field="x_assigned_to" value="<?= $Page->assigned_to->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->assigned_to->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->assigned_to->formatPattern()) ?>"<?= $Page->assigned_to->editAttributes() ?> aria-describedby="x_assigned_to_help">
<?= $Page->assigned_to->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->assigned_to->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->resolved_at->Visible) { // resolved_at ?>
    <div id="r_resolved_at"<?= $Page->resolved_at->rowAttributes() ?>>
        <label id="elh_support_requests_resolved_at" for="x_resolved_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->resolved_at->caption() ?><?= $Page->resolved_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->resolved_at->cellAttributes() ?>>
<span id="el_support_requests_resolved_at">
<input type="<?= $Page->resolved_at->getInputTextType() ?>" name="x_resolved_at" id="x_resolved_at" data-table="support_requests" data-field="x_resolved_at" value="<?= $Page->resolved_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->resolved_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->resolved_at->formatPattern()) ?>"<?= $Page->resolved_at->editAttributes() ?> aria-describedby="x_resolved_at_help">
<?= $Page->resolved_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->resolved_at->getErrorMessage() ?></div>
<?php if (!$Page->resolved_at->ReadOnly && !$Page->resolved_at->Disabled && !isset($Page->resolved_at->EditAttrs["readonly"]) && !isset($Page->resolved_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsupport_requestsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fsupport_requestsedit", "x_resolved_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_response->Visible) { // response ?>
    <div id="r__response"<?= $Page->_response->rowAttributes() ?>>
        <label id="elh_support_requests__response" for="x__response" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_response->caption() ?><?= $Page->_response->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_response->cellAttributes() ?>>
<span id="el_support_requests__response">
<textarea data-table="support_requests" data-field="x__response" name="x__response" id="x__response" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->_response->getPlaceHolder()) ?>"<?= $Page->_response->editAttributes() ?> aria-describedby="x__response_help"><?= $Page->_response->EditValue ?></textarea>
<?= $Page->_response->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_response->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <div id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <label id="elh_support_requests_ip_address" for="x_ip_address" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ip_address->caption() ?><?= $Page->ip_address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_support_requests_ip_address">
<input type="<?= $Page->ip_address->getInputTextType() ?>" name="x_ip_address" id="x_ip_address" data-table="support_requests" data-field="x_ip_address" value="<?= $Page->ip_address->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ip_address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ip_address->formatPattern()) ?>"<?= $Page->ip_address->editAttributes() ?> aria-describedby="x_ip_address_help">
<?= $Page->ip_address->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ip_address->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_agent->Visible) { // user_agent ?>
    <div id="r_user_agent"<?= $Page->user_agent->rowAttributes() ?>>
        <label id="elh_support_requests_user_agent" for="x_user_agent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_agent->caption() ?><?= $Page->user_agent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_agent->cellAttributes() ?>>
<span id="el_support_requests_user_agent">
<textarea data-table="support_requests" data-field="x_user_agent" name="x_user_agent" id="x_user_agent" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->user_agent->getPlaceHolder()) ?>"<?= $Page->user_agent->editAttributes() ?> aria-describedby="x_user_agent_help"><?= $Page->user_agent->EditValue ?></textarea>
<?= $Page->user_agent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_agent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fsupport_requestsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fsupport_requestsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("support_requests");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
