<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentActivityLogsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fdocument_activity_logsedit" id="fdocument_activity_logsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_activity_logs: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fdocument_activity_logsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_activity_logsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["log_id", [fields.log_id.visible && fields.log_id.required ? ew.Validators.required(fields.log_id.caption) : null], fields.log_id.isInvalid],
            ["document_id", [fields.document_id.visible && fields.document_id.required ? ew.Validators.required(fields.document_id.caption) : null, ew.Validators.integer], fields.document_id.isInvalid],
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null, ew.Validators.integer], fields.user_id.isInvalid],
            ["_action", [fields._action.visible && fields._action.required ? ew.Validators.required(fields._action.caption) : null], fields._action.isInvalid],
            ["details", [fields.details.visible && fields.details.required ? ew.Validators.required(fields.details.caption) : null], fields.details.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
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
<input type="hidden" name="t" value="document_activity_logs">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->log_id->Visible) { // log_id ?>
    <div id="r_log_id"<?= $Page->log_id->rowAttributes() ?>>
        <label id="elh_document_activity_logs_log_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->log_id->caption() ?><?= $Page->log_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->log_id->cellAttributes() ?>>
<span id="el_document_activity_logs_log_id">
<span<?= $Page->log_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->log_id->getDisplayValue($Page->log_id->EditValue))) ?>"></span>
<input type="hidden" data-table="document_activity_logs" data-field="x_log_id" data-hidden="1" name="x_log_id" id="x_log_id" value="<?= HtmlEncode($Page->log_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <div id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <label id="elh_document_activity_logs_document_id" for="x_document_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_id->caption() ?><?= $Page->document_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_id->cellAttributes() ?>>
<span id="el_document_activity_logs_document_id">
<input type="<?= $Page->document_id->getInputTextType() ?>" name="x_document_id" id="x_document_id" data-table="document_activity_logs" data-field="x_document_id" value="<?= $Page->document_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->document_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_id->formatPattern()) ?>"<?= $Page->document_id->editAttributes() ?> aria-describedby="x_document_id_help">
<?= $Page->document_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <div id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <label id="elh_document_activity_logs_user_id" for="x_user_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_id->caption() ?><?= $Page->user_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_id->cellAttributes() ?>>
<span id="el_document_activity_logs_user_id">
<input type="<?= $Page->user_id->getInputTextType() ?>" name="x_user_id" id="x_user_id" data-table="document_activity_logs" data-field="x_user_id" value="<?= $Page->user_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->user_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user_id->formatPattern()) ?>"<?= $Page->user_id->editAttributes() ?> aria-describedby="x_user_id_help">
<?= $Page->user_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <div id="r__action"<?= $Page->_action->rowAttributes() ?>>
        <label id="elh_document_activity_logs__action" for="x__action" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_action->caption() ?><?= $Page->_action->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_action->cellAttributes() ?>>
<span id="el_document_activity_logs__action">
<input type="<?= $Page->_action->getInputTextType() ?>" name="x__action" id="x__action" data-table="document_activity_logs" data-field="x__action" value="<?= $Page->_action->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->_action->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_action->formatPattern()) ?>"<?= $Page->_action->editAttributes() ?> aria-describedby="x__action_help">
<?= $Page->_action->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_action->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->details->Visible) { // details ?>
    <div id="r_details"<?= $Page->details->rowAttributes() ?>>
        <label id="elh_document_activity_logs_details" for="x_details" class="<?= $Page->LeftColumnClass ?>"><?= $Page->details->caption() ?><?= $Page->details->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->details->cellAttributes() ?>>
<span id="el_document_activity_logs_details">
<textarea data-table="document_activity_logs" data-field="x_details" name="x_details" id="x_details" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->details->getPlaceHolder()) ?>"<?= $Page->details->editAttributes() ?> aria-describedby="x_details_help"><?= $Page->details->EditValue ?></textarea>
<?= $Page->details->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->details->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_document_activity_logs_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_document_activity_logs_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="document_activity_logs" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocument_activity_logsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocument_activity_logsedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <div id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <label id="elh_document_activity_logs_ip_address" for="x_ip_address" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ip_address->caption() ?><?= $Page->ip_address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_document_activity_logs_ip_address">
<input type="<?= $Page->ip_address->getInputTextType() ?>" name="x_ip_address" id="x_ip_address" data-table="document_activity_logs" data-field="x_ip_address" value="<?= $Page->ip_address->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ip_address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ip_address->formatPattern()) ?>"<?= $Page->ip_address->editAttributes() ?> aria-describedby="x_ip_address_help">
<?= $Page->ip_address->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ip_address->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_agent->Visible) { // user_agent ?>
    <div id="r_user_agent"<?= $Page->user_agent->rowAttributes() ?>>
        <label id="elh_document_activity_logs_user_agent" for="x_user_agent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_agent->caption() ?><?= $Page->user_agent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_agent->cellAttributes() ?>>
<span id="el_document_activity_logs_user_agent">
<textarea data-table="document_activity_logs" data-field="x_user_agent" name="x_user_agent" id="x_user_agent" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->user_agent->getPlaceHolder()) ?>"<?= $Page->user_agent->editAttributes() ?> aria-describedby="x_user_agent_help"><?= $Page->user_agent->EditValue ?></textarea>
<?= $Page->user_agent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_agent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdocument_activity_logsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdocument_activity_logsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("document_activity_logs");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
