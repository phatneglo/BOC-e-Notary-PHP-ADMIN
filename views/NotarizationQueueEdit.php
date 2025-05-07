<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizationQueueEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fnotarization_queueedit" id="fnotarization_queueedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarization_queue: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fnotarization_queueedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotarization_queueedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["queue_id", [fields.queue_id.visible && fields.queue_id.required ? ew.Validators.required(fields.queue_id.caption) : null], fields.queue_id.isInvalid],
            ["request_id", [fields.request_id.visible && fields.request_id.required ? ew.Validators.required(fields.request_id.caption) : null, ew.Validators.integer], fields.request_id.isInvalid],
            ["notary_id", [fields.notary_id.visible && fields.notary_id.required ? ew.Validators.required(fields.notary_id.caption) : null, ew.Validators.integer], fields.notary_id.isInvalid],
            ["queue_position", [fields.queue_position.visible && fields.queue_position.required ? ew.Validators.required(fields.queue_position.caption) : null, ew.Validators.integer], fields.queue_position.isInvalid],
            ["entry_time", [fields.entry_time.visible && fields.entry_time.required ? ew.Validators.required(fields.entry_time.caption) : null, ew.Validators.datetime(fields.entry_time.clientFormatPattern)], fields.entry_time.isInvalid],
            ["processing_started_at", [fields.processing_started_at.visible && fields.processing_started_at.required ? ew.Validators.required(fields.processing_started_at.caption) : null, ew.Validators.datetime(fields.processing_started_at.clientFormatPattern)], fields.processing_started_at.isInvalid],
            ["completed_at", [fields.completed_at.visible && fields.completed_at.required ? ew.Validators.required(fields.completed_at.caption) : null, ew.Validators.datetime(fields.completed_at.clientFormatPattern)], fields.completed_at.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["estimated_wait_time", [fields.estimated_wait_time.visible && fields.estimated_wait_time.required ? ew.Validators.required(fields.estimated_wait_time.caption) : null, ew.Validators.integer], fields.estimated_wait_time.isInvalid]
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
<input type="hidden" name="t" value="notarization_queue">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->queue_id->Visible) { // queue_id ?>
    <div id="r_queue_id"<?= $Page->queue_id->rowAttributes() ?>>
        <label id="elh_notarization_queue_queue_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->queue_id->caption() ?><?= $Page->queue_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->queue_id->cellAttributes() ?>>
<span id="el_notarization_queue_queue_id">
<span<?= $Page->queue_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->queue_id->getDisplayValue($Page->queue_id->EditValue))) ?>"></span>
<input type="hidden" data-table="notarization_queue" data-field="x_queue_id" data-hidden="1" name="x_queue_id" id="x_queue_id" value="<?= HtmlEncode($Page->queue_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
    <div id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <label id="elh_notarization_queue_request_id" for="x_request_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->request_id->caption() ?><?= $Page->request_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->request_id->cellAttributes() ?>>
<span id="el_notarization_queue_request_id">
<input type="<?= $Page->request_id->getInputTextType() ?>" name="x_request_id" id="x_request_id" data-table="notarization_queue" data-field="x_request_id" value="<?= $Page->request_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->request_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->request_id->formatPattern()) ?>"<?= $Page->request_id->editAttributes() ?> aria-describedby="x_request_id_help">
<?= $Page->request_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->request_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
    <div id="r_notary_id"<?= $Page->notary_id->rowAttributes() ?>>
        <label id="elh_notarization_queue_notary_id" for="x_notary_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notary_id->caption() ?><?= $Page->notary_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notary_id->cellAttributes() ?>>
<span id="el_notarization_queue_notary_id">
<input type="<?= $Page->notary_id->getInputTextType() ?>" name="x_notary_id" id="x_notary_id" data-table="notarization_queue" data-field="x_notary_id" value="<?= $Page->notary_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->notary_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notary_id->formatPattern()) ?>"<?= $Page->notary_id->editAttributes() ?> aria-describedby="x_notary_id_help">
<?= $Page->notary_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notary_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->queue_position->Visible) { // queue_position ?>
    <div id="r_queue_position"<?= $Page->queue_position->rowAttributes() ?>>
        <label id="elh_notarization_queue_queue_position" for="x_queue_position" class="<?= $Page->LeftColumnClass ?>"><?= $Page->queue_position->caption() ?><?= $Page->queue_position->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->queue_position->cellAttributes() ?>>
<span id="el_notarization_queue_queue_position">
<input type="<?= $Page->queue_position->getInputTextType() ?>" name="x_queue_position" id="x_queue_position" data-table="notarization_queue" data-field="x_queue_position" value="<?= $Page->queue_position->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->queue_position->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->queue_position->formatPattern()) ?>"<?= $Page->queue_position->editAttributes() ?> aria-describedby="x_queue_position_help">
<?= $Page->queue_position->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->queue_position->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->entry_time->Visible) { // entry_time ?>
    <div id="r_entry_time"<?= $Page->entry_time->rowAttributes() ?>>
        <label id="elh_notarization_queue_entry_time" for="x_entry_time" class="<?= $Page->LeftColumnClass ?>"><?= $Page->entry_time->caption() ?><?= $Page->entry_time->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->entry_time->cellAttributes() ?>>
<span id="el_notarization_queue_entry_time">
<input type="<?= $Page->entry_time->getInputTextType() ?>" name="x_entry_time" id="x_entry_time" data-table="notarization_queue" data-field="x_entry_time" value="<?= $Page->entry_time->EditValue ?>" placeholder="<?= HtmlEncode($Page->entry_time->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->entry_time->formatPattern()) ?>"<?= $Page->entry_time->editAttributes() ?> aria-describedby="x_entry_time_help">
<?= $Page->entry_time->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->entry_time->getErrorMessage() ?></div>
<?php if (!$Page->entry_time->ReadOnly && !$Page->entry_time->Disabled && !isset($Page->entry_time->EditAttrs["readonly"]) && !isset($Page->entry_time->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarization_queueedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarization_queueedit", "x_entry_time", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->processing_started_at->Visible) { // processing_started_at ?>
    <div id="r_processing_started_at"<?= $Page->processing_started_at->rowAttributes() ?>>
        <label id="elh_notarization_queue_processing_started_at" for="x_processing_started_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->processing_started_at->caption() ?><?= $Page->processing_started_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->processing_started_at->cellAttributes() ?>>
<span id="el_notarization_queue_processing_started_at">
<input type="<?= $Page->processing_started_at->getInputTextType() ?>" name="x_processing_started_at" id="x_processing_started_at" data-table="notarization_queue" data-field="x_processing_started_at" value="<?= $Page->processing_started_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->processing_started_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->processing_started_at->formatPattern()) ?>"<?= $Page->processing_started_at->editAttributes() ?> aria-describedby="x_processing_started_at_help">
<?= $Page->processing_started_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->processing_started_at->getErrorMessage() ?></div>
<?php if (!$Page->processing_started_at->ReadOnly && !$Page->processing_started_at->Disabled && !isset($Page->processing_started_at->EditAttrs["readonly"]) && !isset($Page->processing_started_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarization_queueedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarization_queueedit", "x_processing_started_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->completed_at->Visible) { // completed_at ?>
    <div id="r_completed_at"<?= $Page->completed_at->rowAttributes() ?>>
        <label id="elh_notarization_queue_completed_at" for="x_completed_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->completed_at->caption() ?><?= $Page->completed_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->completed_at->cellAttributes() ?>>
<span id="el_notarization_queue_completed_at">
<input type="<?= $Page->completed_at->getInputTextType() ?>" name="x_completed_at" id="x_completed_at" data-table="notarization_queue" data-field="x_completed_at" value="<?= $Page->completed_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->completed_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->completed_at->formatPattern()) ?>"<?= $Page->completed_at->editAttributes() ?> aria-describedby="x_completed_at_help">
<?= $Page->completed_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->completed_at->getErrorMessage() ?></div>
<?php if (!$Page->completed_at->ReadOnly && !$Page->completed_at->Disabled && !isset($Page->completed_at->EditAttrs["readonly"]) && !isset($Page->completed_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarization_queueedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarization_queueedit", "x_completed_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_notarization_queue_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_notarization_queue_status">
<input type="<?= $Page->status->getInputTextType() ?>" name="x_status" id="x_status" data-table="notarization_queue" data-field="x_status" value="<?= $Page->status->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->status->formatPattern()) ?>"<?= $Page->status->editAttributes() ?> aria-describedby="x_status_help">
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estimated_wait_time->Visible) { // estimated_wait_time ?>
    <div id="r_estimated_wait_time"<?= $Page->estimated_wait_time->rowAttributes() ?>>
        <label id="elh_notarization_queue_estimated_wait_time" for="x_estimated_wait_time" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estimated_wait_time->caption() ?><?= $Page->estimated_wait_time->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estimated_wait_time->cellAttributes() ?>>
<span id="el_notarization_queue_estimated_wait_time">
<input type="<?= $Page->estimated_wait_time->getInputTextType() ?>" name="x_estimated_wait_time" id="x_estimated_wait_time" data-table="notarization_queue" data-field="x_estimated_wait_time" value="<?= $Page->estimated_wait_time->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->estimated_wait_time->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->estimated_wait_time->formatPattern()) ?>"<?= $Page->estimated_wait_time->editAttributes() ?> aria-describedby="x_estimated_wait_time_help">
<?= $Page->estimated_wait_time->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->estimated_wait_time->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fnotarization_queueedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fnotarization_queueedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("notarization_queue");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
