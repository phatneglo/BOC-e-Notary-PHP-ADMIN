<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizationRequestsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fnotarization_requestsedit" id="fnotarization_requestsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarization_requests: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fnotarization_requestsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotarization_requestsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["request_id", [fields.request_id.visible && fields.request_id.required ? ew.Validators.required(fields.request_id.caption) : null], fields.request_id.isInvalid],
            ["document_id", [fields.document_id.visible && fields.document_id.required ? ew.Validators.required(fields.document_id.caption) : null, ew.Validators.integer], fields.document_id.isInvalid],
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null, ew.Validators.integer], fields.user_id.isInvalid],
            ["request_reference", [fields.request_reference.visible && fields.request_reference.required ? ew.Validators.required(fields.request_reference.caption) : null], fields.request_reference.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["requested_at", [fields.requested_at.visible && fields.requested_at.required ? ew.Validators.required(fields.requested_at.caption) : null, ew.Validators.datetime(fields.requested_at.clientFormatPattern)], fields.requested_at.isInvalid],
            ["notary_id", [fields.notary_id.visible && fields.notary_id.required ? ew.Validators.required(fields.notary_id.caption) : null, ew.Validators.integer], fields.notary_id.isInvalid],
            ["assigned_at", [fields.assigned_at.visible && fields.assigned_at.required ? ew.Validators.required(fields.assigned_at.caption) : null, ew.Validators.datetime(fields.assigned_at.clientFormatPattern)], fields.assigned_at.isInvalid],
            ["notarized_at", [fields.notarized_at.visible && fields.notarized_at.required ? ew.Validators.required(fields.notarized_at.caption) : null, ew.Validators.datetime(fields.notarized_at.clientFormatPattern)], fields.notarized_at.isInvalid],
            ["rejection_reason", [fields.rejection_reason.visible && fields.rejection_reason.required ? ew.Validators.required(fields.rejection_reason.caption) : null], fields.rejection_reason.isInvalid],
            ["rejected_at", [fields.rejected_at.visible && fields.rejected_at.required ? ew.Validators.required(fields.rejected_at.caption) : null, ew.Validators.datetime(fields.rejected_at.clientFormatPattern)], fields.rejected_at.isInvalid],
            ["rejected_by", [fields.rejected_by.visible && fields.rejected_by.required ? ew.Validators.required(fields.rejected_by.caption) : null, ew.Validators.integer], fields.rejected_by.isInvalid],
            ["priority", [fields.priority.visible && fields.priority.required ? ew.Validators.required(fields.priority.caption) : null, ew.Validators.integer], fields.priority.isInvalid],
            ["payment_status", [fields.payment_status.visible && fields.payment_status.required ? ew.Validators.required(fields.payment_status.caption) : null], fields.payment_status.isInvalid],
            ["payment_transaction_id", [fields.payment_transaction_id.visible && fields.payment_transaction_id.required ? ew.Validators.required(fields.payment_transaction_id.caption) : null, ew.Validators.integer], fields.payment_transaction_id.isInvalid],
            ["modified_at", [fields.modified_at.visible && fields.modified_at.required ? ew.Validators.required(fields.modified_at.caption) : null, ew.Validators.datetime(fields.modified_at.clientFormatPattern)], fields.modified_at.isInvalid],
            ["ip_address", [fields.ip_address.visible && fields.ip_address.required ? ew.Validators.required(fields.ip_address.caption) : null], fields.ip_address.isInvalid],
            ["browser_info", [fields.browser_info.visible && fields.browser_info.required ? ew.Validators.required(fields.browser_info.caption) : null], fields.browser_info.isInvalid],
            ["device_info", [fields.device_info.visible && fields.device_info.required ? ew.Validators.required(fields.device_info.caption) : null], fields.device_info.isInvalid]
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
<input type="hidden" name="t" value="notarization_requests">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->request_id->Visible) { // request_id ?>
    <div id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <label id="elh_notarization_requests_request_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->request_id->caption() ?><?= $Page->request_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->request_id->cellAttributes() ?>>
<span id="el_notarization_requests_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->request_id->getDisplayValue($Page->request_id->EditValue))) ?>"></span>
<input type="hidden" data-table="notarization_requests" data-field="x_request_id" data-hidden="1" name="x_request_id" id="x_request_id" value="<?= HtmlEncode($Page->request_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <div id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <label id="elh_notarization_requests_document_id" for="x_document_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_id->caption() ?><?= $Page->document_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_id->cellAttributes() ?>>
<span id="el_notarization_requests_document_id">
<input type="<?= $Page->document_id->getInputTextType() ?>" name="x_document_id" id="x_document_id" data-table="notarization_requests" data-field="x_document_id" value="<?= $Page->document_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->document_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_id->formatPattern()) ?>"<?= $Page->document_id->editAttributes() ?> aria-describedby="x_document_id_help">
<?= $Page->document_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <div id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <label id="elh_notarization_requests_user_id" for="x_user_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_id->caption() ?><?= $Page->user_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_id->cellAttributes() ?>>
<span id="el_notarization_requests_user_id">
<input type="<?= $Page->user_id->getInputTextType() ?>" name="x_user_id" id="x_user_id" data-table="notarization_requests" data-field="x_user_id" value="<?= $Page->user_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->user_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user_id->formatPattern()) ?>"<?= $Page->user_id->editAttributes() ?> aria-describedby="x_user_id_help">
<?= $Page->user_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->request_reference->Visible) { // request_reference ?>
    <div id="r_request_reference"<?= $Page->request_reference->rowAttributes() ?>>
        <label id="elh_notarization_requests_request_reference" for="x_request_reference" class="<?= $Page->LeftColumnClass ?>"><?= $Page->request_reference->caption() ?><?= $Page->request_reference->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->request_reference->cellAttributes() ?>>
<span id="el_notarization_requests_request_reference">
<input type="<?= $Page->request_reference->getInputTextType() ?>" name="x_request_reference" id="x_request_reference" data-table="notarization_requests" data-field="x_request_reference" value="<?= $Page->request_reference->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->request_reference->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->request_reference->formatPattern()) ?>"<?= $Page->request_reference->editAttributes() ?> aria-describedby="x_request_reference_help">
<?= $Page->request_reference->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->request_reference->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_notarization_requests_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_notarization_requests_status">
<input type="<?= $Page->status->getInputTextType() ?>" name="x_status" id="x_status" data-table="notarization_requests" data-field="x_status" value="<?= $Page->status->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->status->formatPattern()) ?>"<?= $Page->status->editAttributes() ?> aria-describedby="x_status_help">
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->requested_at->Visible) { // requested_at ?>
    <div id="r_requested_at"<?= $Page->requested_at->rowAttributes() ?>>
        <label id="elh_notarization_requests_requested_at" for="x_requested_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->requested_at->caption() ?><?= $Page->requested_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->requested_at->cellAttributes() ?>>
<span id="el_notarization_requests_requested_at">
<input type="<?= $Page->requested_at->getInputTextType() ?>" name="x_requested_at" id="x_requested_at" data-table="notarization_requests" data-field="x_requested_at" value="<?= $Page->requested_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->requested_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->requested_at->formatPattern()) ?>"<?= $Page->requested_at->editAttributes() ?> aria-describedby="x_requested_at_help">
<?= $Page->requested_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->requested_at->getErrorMessage() ?></div>
<?php if (!$Page->requested_at->ReadOnly && !$Page->requested_at->Disabled && !isset($Page->requested_at->EditAttrs["readonly"]) && !isset($Page->requested_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarization_requestsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarization_requestsedit", "x_requested_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
    <div id="r_notary_id"<?= $Page->notary_id->rowAttributes() ?>>
        <label id="elh_notarization_requests_notary_id" for="x_notary_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notary_id->caption() ?><?= $Page->notary_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notary_id->cellAttributes() ?>>
<span id="el_notarization_requests_notary_id">
<input type="<?= $Page->notary_id->getInputTextType() ?>" name="x_notary_id" id="x_notary_id" data-table="notarization_requests" data-field="x_notary_id" value="<?= $Page->notary_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->notary_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notary_id->formatPattern()) ?>"<?= $Page->notary_id->editAttributes() ?> aria-describedby="x_notary_id_help">
<?= $Page->notary_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notary_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->assigned_at->Visible) { // assigned_at ?>
    <div id="r_assigned_at"<?= $Page->assigned_at->rowAttributes() ?>>
        <label id="elh_notarization_requests_assigned_at" for="x_assigned_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->assigned_at->caption() ?><?= $Page->assigned_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->assigned_at->cellAttributes() ?>>
<span id="el_notarization_requests_assigned_at">
<input type="<?= $Page->assigned_at->getInputTextType() ?>" name="x_assigned_at" id="x_assigned_at" data-table="notarization_requests" data-field="x_assigned_at" value="<?= $Page->assigned_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->assigned_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->assigned_at->formatPattern()) ?>"<?= $Page->assigned_at->editAttributes() ?> aria-describedby="x_assigned_at_help">
<?= $Page->assigned_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->assigned_at->getErrorMessage() ?></div>
<?php if (!$Page->assigned_at->ReadOnly && !$Page->assigned_at->Disabled && !isset($Page->assigned_at->EditAttrs["readonly"]) && !isset($Page->assigned_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarization_requestsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarization_requestsedit", "x_assigned_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notarized_at->Visible) { // notarized_at ?>
    <div id="r_notarized_at"<?= $Page->notarized_at->rowAttributes() ?>>
        <label id="elh_notarization_requests_notarized_at" for="x_notarized_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notarized_at->caption() ?><?= $Page->notarized_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notarized_at->cellAttributes() ?>>
<span id="el_notarization_requests_notarized_at">
<input type="<?= $Page->notarized_at->getInputTextType() ?>" name="x_notarized_at" id="x_notarized_at" data-table="notarization_requests" data-field="x_notarized_at" value="<?= $Page->notarized_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->notarized_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notarized_at->formatPattern()) ?>"<?= $Page->notarized_at->editAttributes() ?> aria-describedby="x_notarized_at_help">
<?= $Page->notarized_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notarized_at->getErrorMessage() ?></div>
<?php if (!$Page->notarized_at->ReadOnly && !$Page->notarized_at->Disabled && !isset($Page->notarized_at->EditAttrs["readonly"]) && !isset($Page->notarized_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarization_requestsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarization_requestsedit", "x_notarized_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rejection_reason->Visible) { // rejection_reason ?>
    <div id="r_rejection_reason"<?= $Page->rejection_reason->rowAttributes() ?>>
        <label id="elh_notarization_requests_rejection_reason" for="x_rejection_reason" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rejection_reason->caption() ?><?= $Page->rejection_reason->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->rejection_reason->cellAttributes() ?>>
<span id="el_notarization_requests_rejection_reason">
<textarea data-table="notarization_requests" data-field="x_rejection_reason" name="x_rejection_reason" id="x_rejection_reason" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->rejection_reason->getPlaceHolder()) ?>"<?= $Page->rejection_reason->editAttributes() ?> aria-describedby="x_rejection_reason_help"><?= $Page->rejection_reason->EditValue ?></textarea>
<?= $Page->rejection_reason->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rejection_reason->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rejected_at->Visible) { // rejected_at ?>
    <div id="r_rejected_at"<?= $Page->rejected_at->rowAttributes() ?>>
        <label id="elh_notarization_requests_rejected_at" for="x_rejected_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rejected_at->caption() ?><?= $Page->rejected_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->rejected_at->cellAttributes() ?>>
<span id="el_notarization_requests_rejected_at">
<input type="<?= $Page->rejected_at->getInputTextType() ?>" name="x_rejected_at" id="x_rejected_at" data-table="notarization_requests" data-field="x_rejected_at" value="<?= $Page->rejected_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->rejected_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->rejected_at->formatPattern()) ?>"<?= $Page->rejected_at->editAttributes() ?> aria-describedby="x_rejected_at_help">
<?= $Page->rejected_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rejected_at->getErrorMessage() ?></div>
<?php if (!$Page->rejected_at->ReadOnly && !$Page->rejected_at->Disabled && !isset($Page->rejected_at->EditAttrs["readonly"]) && !isset($Page->rejected_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarization_requestsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarization_requestsedit", "x_rejected_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rejected_by->Visible) { // rejected_by ?>
    <div id="r_rejected_by"<?= $Page->rejected_by->rowAttributes() ?>>
        <label id="elh_notarization_requests_rejected_by" for="x_rejected_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rejected_by->caption() ?><?= $Page->rejected_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->rejected_by->cellAttributes() ?>>
<span id="el_notarization_requests_rejected_by">
<input type="<?= $Page->rejected_by->getInputTextType() ?>" name="x_rejected_by" id="x_rejected_by" data-table="notarization_requests" data-field="x_rejected_by" value="<?= $Page->rejected_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->rejected_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->rejected_by->formatPattern()) ?>"<?= $Page->rejected_by->editAttributes() ?> aria-describedby="x_rejected_by_help">
<?= $Page->rejected_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rejected_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->priority->Visible) { // priority ?>
    <div id="r_priority"<?= $Page->priority->rowAttributes() ?>>
        <label id="elh_notarization_requests_priority" for="x_priority" class="<?= $Page->LeftColumnClass ?>"><?= $Page->priority->caption() ?><?= $Page->priority->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->priority->cellAttributes() ?>>
<span id="el_notarization_requests_priority">
<input type="<?= $Page->priority->getInputTextType() ?>" name="x_priority" id="x_priority" data-table="notarization_requests" data-field="x_priority" value="<?= $Page->priority->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->priority->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->priority->formatPattern()) ?>"<?= $Page->priority->editAttributes() ?> aria-describedby="x_priority_help">
<?= $Page->priority->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->priority->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->payment_status->Visible) { // payment_status ?>
    <div id="r_payment_status"<?= $Page->payment_status->rowAttributes() ?>>
        <label id="elh_notarization_requests_payment_status" for="x_payment_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->payment_status->caption() ?><?= $Page->payment_status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->payment_status->cellAttributes() ?>>
<span id="el_notarization_requests_payment_status">
<input type="<?= $Page->payment_status->getInputTextType() ?>" name="x_payment_status" id="x_payment_status" data-table="notarization_requests" data-field="x_payment_status" value="<?= $Page->payment_status->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->payment_status->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->payment_status->formatPattern()) ?>"<?= $Page->payment_status->editAttributes() ?> aria-describedby="x_payment_status_help">
<?= $Page->payment_status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->payment_status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->payment_transaction_id->Visible) { // payment_transaction_id ?>
    <div id="r_payment_transaction_id"<?= $Page->payment_transaction_id->rowAttributes() ?>>
        <label id="elh_notarization_requests_payment_transaction_id" for="x_payment_transaction_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->payment_transaction_id->caption() ?><?= $Page->payment_transaction_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->payment_transaction_id->cellAttributes() ?>>
<span id="el_notarization_requests_payment_transaction_id">
<input type="<?= $Page->payment_transaction_id->getInputTextType() ?>" name="x_payment_transaction_id" id="x_payment_transaction_id" data-table="notarization_requests" data-field="x_payment_transaction_id" value="<?= $Page->payment_transaction_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->payment_transaction_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->payment_transaction_id->formatPattern()) ?>"<?= $Page->payment_transaction_id->editAttributes() ?> aria-describedby="x_payment_transaction_id_help">
<?= $Page->payment_transaction_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->payment_transaction_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->modified_at->Visible) { // modified_at ?>
    <div id="r_modified_at"<?= $Page->modified_at->rowAttributes() ?>>
        <label id="elh_notarization_requests_modified_at" for="x_modified_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->modified_at->caption() ?><?= $Page->modified_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->modified_at->cellAttributes() ?>>
<span id="el_notarization_requests_modified_at">
<input type="<?= $Page->modified_at->getInputTextType() ?>" name="x_modified_at" id="x_modified_at" data-table="notarization_requests" data-field="x_modified_at" value="<?= $Page->modified_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->modified_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->modified_at->formatPattern()) ?>"<?= $Page->modified_at->editAttributes() ?> aria-describedby="x_modified_at_help">
<?= $Page->modified_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->modified_at->getErrorMessage() ?></div>
<?php if (!$Page->modified_at->ReadOnly && !$Page->modified_at->Disabled && !isset($Page->modified_at->EditAttrs["readonly"]) && !isset($Page->modified_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarization_requestsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarization_requestsedit", "x_modified_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <div id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <label id="elh_notarization_requests_ip_address" for="x_ip_address" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ip_address->caption() ?><?= $Page->ip_address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_notarization_requests_ip_address">
<input type="<?= $Page->ip_address->getInputTextType() ?>" name="x_ip_address" id="x_ip_address" data-table="notarization_requests" data-field="x_ip_address" value="<?= $Page->ip_address->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ip_address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ip_address->formatPattern()) ?>"<?= $Page->ip_address->editAttributes() ?> aria-describedby="x_ip_address_help">
<?= $Page->ip_address->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ip_address->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->browser_info->Visible) { // browser_info ?>
    <div id="r_browser_info"<?= $Page->browser_info->rowAttributes() ?>>
        <label id="elh_notarization_requests_browser_info" for="x_browser_info" class="<?= $Page->LeftColumnClass ?>"><?= $Page->browser_info->caption() ?><?= $Page->browser_info->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->browser_info->cellAttributes() ?>>
<span id="el_notarization_requests_browser_info">
<textarea data-table="notarization_requests" data-field="x_browser_info" name="x_browser_info" id="x_browser_info" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->browser_info->getPlaceHolder()) ?>"<?= $Page->browser_info->editAttributes() ?> aria-describedby="x_browser_info_help"><?= $Page->browser_info->EditValue ?></textarea>
<?= $Page->browser_info->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->browser_info->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->device_info->Visible) { // device_info ?>
    <div id="r_device_info"<?= $Page->device_info->rowAttributes() ?>>
        <label id="elh_notarization_requests_device_info" for="x_device_info" class="<?= $Page->LeftColumnClass ?>"><?= $Page->device_info->caption() ?><?= $Page->device_info->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->device_info->cellAttributes() ?>>
<span id="el_notarization_requests_device_info">
<textarea data-table="notarization_requests" data-field="x_device_info" name="x_device_info" id="x_device_info" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->device_info->getPlaceHolder()) ?>"<?= $Page->device_info->editAttributes() ?> aria-describedby="x_device_info_help"><?= $Page->device_info->EditValue ?></textarea>
<?= $Page->device_info->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->device_info->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fnotarization_requestsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fnotarization_requestsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("notarization_requests");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
