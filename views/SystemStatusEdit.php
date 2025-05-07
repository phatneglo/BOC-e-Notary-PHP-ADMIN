<?php

namespace PHPMaker2024\eNotary;

// Page object
$SystemStatusEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fsystem_statusedit" id="fsystem_statusedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { system_status: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fsystem_statusedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsystem_statusedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["status_id", [fields.status_id.visible && fields.status_id.required ? ew.Validators.required(fields.status_id.caption) : null], fields.status_id.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["_message", [fields._message.visible && fields._message.required ? ew.Validators.required(fields._message.caption) : null], fields._message.isInvalid],
            ["uptime", [fields.uptime.visible && fields.uptime.required ? ew.Validators.required(fields.uptime.caption) : null, ew.Validators.integer], fields.uptime.isInvalid],
            ["active_users", [fields.active_users.visible && fields.active_users.required ? ew.Validators.required(fields.active_users.caption) : null, ew.Validators.integer], fields.active_users.isInvalid],
            ["queue_size", [fields.queue_size.visible && fields.queue_size.required ? ew.Validators.required(fields.queue_size.caption) : null, ew.Validators.integer], fields.queue_size.isInvalid],
            ["average_processing_time", [fields.average_processing_time.visible && fields.average_processing_time.required ? ew.Validators.required(fields.average_processing_time.caption) : null, ew.Validators.float], fields.average_processing_time.isInvalid],
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
<input type="hidden" name="t" value="system_status">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->status_id->Visible) { // status_id ?>
    <div id="r_status_id"<?= $Page->status_id->rowAttributes() ?>>
        <label id="elh_system_status_status_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status_id->caption() ?><?= $Page->status_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status_id->cellAttributes() ?>>
<span id="el_system_status_status_id">
<span<?= $Page->status_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->status_id->getDisplayValue($Page->status_id->EditValue))) ?>"></span>
<input type="hidden" data-table="system_status" data-field="x_status_id" data-hidden="1" name="x_status_id" id="x_status_id" value="<?= HtmlEncode($Page->status_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_system_status_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_system_status_status">
<input type="<?= $Page->status->getInputTextType() ?>" name="x_status" id="x_status" data-table="system_status" data-field="x_status" value="<?= $Page->status->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->status->formatPattern()) ?>"<?= $Page->status->editAttributes() ?> aria-describedby="x_status_help">
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_message->Visible) { // message ?>
    <div id="r__message"<?= $Page->_message->rowAttributes() ?>>
        <label id="elh_system_status__message" for="x__message" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_message->caption() ?><?= $Page->_message->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_message->cellAttributes() ?>>
<span id="el_system_status__message">
<textarea data-table="system_status" data-field="x__message" name="x__message" id="x__message" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->_message->getPlaceHolder()) ?>"<?= $Page->_message->editAttributes() ?> aria-describedby="x__message_help"><?= $Page->_message->EditValue ?></textarea>
<?= $Page->_message->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_message->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->uptime->Visible) { // uptime ?>
    <div id="r_uptime"<?= $Page->uptime->rowAttributes() ?>>
        <label id="elh_system_status_uptime" for="x_uptime" class="<?= $Page->LeftColumnClass ?>"><?= $Page->uptime->caption() ?><?= $Page->uptime->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->uptime->cellAttributes() ?>>
<span id="el_system_status_uptime">
<input type="<?= $Page->uptime->getInputTextType() ?>" name="x_uptime" id="x_uptime" data-table="system_status" data-field="x_uptime" value="<?= $Page->uptime->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->uptime->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->uptime->formatPattern()) ?>"<?= $Page->uptime->editAttributes() ?> aria-describedby="x_uptime_help">
<?= $Page->uptime->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->uptime->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->active_users->Visible) { // active_users ?>
    <div id="r_active_users"<?= $Page->active_users->rowAttributes() ?>>
        <label id="elh_system_status_active_users" for="x_active_users" class="<?= $Page->LeftColumnClass ?>"><?= $Page->active_users->caption() ?><?= $Page->active_users->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->active_users->cellAttributes() ?>>
<span id="el_system_status_active_users">
<input type="<?= $Page->active_users->getInputTextType() ?>" name="x_active_users" id="x_active_users" data-table="system_status" data-field="x_active_users" value="<?= $Page->active_users->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->active_users->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->active_users->formatPattern()) ?>"<?= $Page->active_users->editAttributes() ?> aria-describedby="x_active_users_help">
<?= $Page->active_users->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->active_users->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->queue_size->Visible) { // queue_size ?>
    <div id="r_queue_size"<?= $Page->queue_size->rowAttributes() ?>>
        <label id="elh_system_status_queue_size" for="x_queue_size" class="<?= $Page->LeftColumnClass ?>"><?= $Page->queue_size->caption() ?><?= $Page->queue_size->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->queue_size->cellAttributes() ?>>
<span id="el_system_status_queue_size">
<input type="<?= $Page->queue_size->getInputTextType() ?>" name="x_queue_size" id="x_queue_size" data-table="system_status" data-field="x_queue_size" value="<?= $Page->queue_size->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->queue_size->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->queue_size->formatPattern()) ?>"<?= $Page->queue_size->editAttributes() ?> aria-describedby="x_queue_size_help">
<?= $Page->queue_size->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->queue_size->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->average_processing_time->Visible) { // average_processing_time ?>
    <div id="r_average_processing_time"<?= $Page->average_processing_time->rowAttributes() ?>>
        <label id="elh_system_status_average_processing_time" for="x_average_processing_time" class="<?= $Page->LeftColumnClass ?>"><?= $Page->average_processing_time->caption() ?><?= $Page->average_processing_time->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->average_processing_time->cellAttributes() ?>>
<span id="el_system_status_average_processing_time">
<input type="<?= $Page->average_processing_time->getInputTextType() ?>" name="x_average_processing_time" id="x_average_processing_time" data-table="system_status" data-field="x_average_processing_time" value="<?= $Page->average_processing_time->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->average_processing_time->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->average_processing_time->formatPattern()) ?>"<?= $Page->average_processing_time->editAttributes() ?> aria-describedby="x_average_processing_time_help">
<?= $Page->average_processing_time->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->average_processing_time->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_system_status_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_system_status_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="system_status" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsystem_statusedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fsystem_statusedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fsystem_statusedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fsystem_statusedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("system_status");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
