<?php

namespace PHPMaker2024\eNotary;

// Page object
$SupportRequestHistoryEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fsupport_request_historyedit" id="fsupport_request_historyedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { support_request_history: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fsupport_request_historyedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsupport_request_historyedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["history_id", [fields.history_id.visible && fields.history_id.required ? ew.Validators.required(fields.history_id.caption) : null], fields.history_id.isInvalid],
            ["request_id", [fields.request_id.visible && fields.request_id.required ? ew.Validators.required(fields.request_id.caption) : null, ew.Validators.integer], fields.request_id.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["comment", [fields.comment.visible && fields.comment.required ? ew.Validators.required(fields.comment.caption) : null], fields.comment.isInvalid],
            ["created_by", [fields.created_by.visible && fields.created_by.required ? ew.Validators.required(fields.created_by.caption) : null, ew.Validators.integer], fields.created_by.isInvalid],
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
<input type="hidden" name="t" value="support_request_history">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->history_id->Visible) { // history_id ?>
    <div id="r_history_id"<?= $Page->history_id->rowAttributes() ?>>
        <label id="elh_support_request_history_history_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->history_id->caption() ?><?= $Page->history_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->history_id->cellAttributes() ?>>
<span id="el_support_request_history_history_id">
<span<?= $Page->history_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->history_id->getDisplayValue($Page->history_id->EditValue))) ?>"></span>
<input type="hidden" data-table="support_request_history" data-field="x_history_id" data-hidden="1" name="x_history_id" id="x_history_id" value="<?= HtmlEncode($Page->history_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
    <div id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <label id="elh_support_request_history_request_id" for="x_request_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->request_id->caption() ?><?= $Page->request_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->request_id->cellAttributes() ?>>
<span id="el_support_request_history_request_id">
<input type="<?= $Page->request_id->getInputTextType() ?>" name="x_request_id" id="x_request_id" data-table="support_request_history" data-field="x_request_id" value="<?= $Page->request_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->request_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->request_id->formatPattern()) ?>"<?= $Page->request_id->editAttributes() ?> aria-describedby="x_request_id_help">
<?= $Page->request_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->request_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_support_request_history_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_support_request_history_status">
<input type="<?= $Page->status->getInputTextType() ?>" name="x_status" id="x_status" data-table="support_request_history" data-field="x_status" value="<?= $Page->status->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->status->formatPattern()) ?>"<?= $Page->status->editAttributes() ?> aria-describedby="x_status_help">
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->comment->Visible) { // comment ?>
    <div id="r_comment"<?= $Page->comment->rowAttributes() ?>>
        <label id="elh_support_request_history_comment" for="x_comment" class="<?= $Page->LeftColumnClass ?>"><?= $Page->comment->caption() ?><?= $Page->comment->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->comment->cellAttributes() ?>>
<span id="el_support_request_history_comment">
<textarea data-table="support_request_history" data-field="x_comment" name="x_comment" id="x_comment" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->comment->getPlaceHolder()) ?>"<?= $Page->comment->editAttributes() ?> aria-describedby="x_comment_help"><?= $Page->comment->EditValue ?></textarea>
<?= $Page->comment->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->comment->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
    <div id="r_created_by"<?= $Page->created_by->rowAttributes() ?>>
        <label id="elh_support_request_history_created_by" for="x_created_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_by->caption() ?><?= $Page->created_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_by->cellAttributes() ?>>
<span id="el_support_request_history_created_by">
<input type="<?= $Page->created_by->getInputTextType() ?>" name="x_created_by" id="x_created_by" data-table="support_request_history" data-field="x_created_by" value="<?= $Page->created_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->created_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_by->formatPattern()) ?>"<?= $Page->created_by->editAttributes() ?> aria-describedby="x_created_by_help">
<?= $Page->created_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_support_request_history_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_support_request_history_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="support_request_history" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsupport_request_historyedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fsupport_request_historyedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fsupport_request_historyedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fsupport_request_historyedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("support_request_history");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
