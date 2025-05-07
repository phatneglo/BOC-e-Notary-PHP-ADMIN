<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentAttachmentsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fdocument_attachmentsedit" id="fdocument_attachmentsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_attachments: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fdocument_attachmentsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_attachmentsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["attachment_id", [fields.attachment_id.visible && fields.attachment_id.required ? ew.Validators.required(fields.attachment_id.caption) : null], fields.attachment_id.isInvalid],
            ["document_id", [fields.document_id.visible && fields.document_id.required ? ew.Validators.required(fields.document_id.caption) : null, ew.Validators.integer], fields.document_id.isInvalid],
            ["file_name", [fields.file_name.visible && fields.file_name.required ? ew.Validators.required(fields.file_name.caption) : null], fields.file_name.isInvalid],
            ["file_path", [fields.file_path.visible && fields.file_path.required ? ew.Validators.required(fields.file_path.caption) : null], fields.file_path.isInvalid],
            ["file_type", [fields.file_type.visible && fields.file_type.required ? ew.Validators.required(fields.file_type.caption) : null], fields.file_type.isInvalid],
            ["file_size", [fields.file_size.visible && fields.file_size.required ? ew.Validators.required(fields.file_size.caption) : null, ew.Validators.integer], fields.file_size.isInvalid],
            ["uploaded_at", [fields.uploaded_at.visible && fields.uploaded_at.required ? ew.Validators.required(fields.uploaded_at.caption) : null, ew.Validators.datetime(fields.uploaded_at.clientFormatPattern)], fields.uploaded_at.isInvalid],
            ["uploaded_by", [fields.uploaded_by.visible && fields.uploaded_by.required ? ew.Validators.required(fields.uploaded_by.caption) : null, ew.Validators.integer], fields.uploaded_by.isInvalid],
            ["description", [fields.description.visible && fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid],
            ["is_supporting", [fields.is_supporting.visible && fields.is_supporting.required ? ew.Validators.required(fields.is_supporting.caption) : null], fields.is_supporting.isInvalid]
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
            "is_supporting": <?= $Page->is_supporting->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="document_attachments">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->attachment_id->Visible) { // attachment_id ?>
    <div id="r_attachment_id"<?= $Page->attachment_id->rowAttributes() ?>>
        <label id="elh_document_attachments_attachment_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->attachment_id->caption() ?><?= $Page->attachment_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->attachment_id->cellAttributes() ?>>
<span id="el_document_attachments_attachment_id">
<span<?= $Page->attachment_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->attachment_id->getDisplayValue($Page->attachment_id->EditValue))) ?>"></span>
<input type="hidden" data-table="document_attachments" data-field="x_attachment_id" data-hidden="1" name="x_attachment_id" id="x_attachment_id" value="<?= HtmlEncode($Page->attachment_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <div id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <label id="elh_document_attachments_document_id" for="x_document_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_id->caption() ?><?= $Page->document_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_id->cellAttributes() ?>>
<span id="el_document_attachments_document_id">
<input type="<?= $Page->document_id->getInputTextType() ?>" name="x_document_id" id="x_document_id" data-table="document_attachments" data-field="x_document_id" value="<?= $Page->document_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->document_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_id->formatPattern()) ?>"<?= $Page->document_id->editAttributes() ?> aria-describedby="x_document_id_help">
<?= $Page->document_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_name->Visible) { // file_name ?>
    <div id="r_file_name"<?= $Page->file_name->rowAttributes() ?>>
        <label id="elh_document_attachments_file_name" for="x_file_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_name->caption() ?><?= $Page->file_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->file_name->cellAttributes() ?>>
<span id="el_document_attachments_file_name">
<input type="<?= $Page->file_name->getInputTextType() ?>" name="x_file_name" id="x_file_name" data-table="document_attachments" data-field="x_file_name" value="<?= $Page->file_name->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->file_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->file_name->formatPattern()) ?>"<?= $Page->file_name->editAttributes() ?> aria-describedby="x_file_name_help">
<?= $Page->file_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
    <div id="r_file_path"<?= $Page->file_path->rowAttributes() ?>>
        <label id="elh_document_attachments_file_path" for="x_file_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_path->caption() ?><?= $Page->file_path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->file_path->cellAttributes() ?>>
<span id="el_document_attachments_file_path">
<input type="<?= $Page->file_path->getInputTextType() ?>" name="x_file_path" id="x_file_path" data-table="document_attachments" data-field="x_file_path" value="<?= $Page->file_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->file_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->file_path->formatPattern()) ?>"<?= $Page->file_path->editAttributes() ?> aria-describedby="x_file_path_help">
<?= $Page->file_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_type->Visible) { // file_type ?>
    <div id="r_file_type"<?= $Page->file_type->rowAttributes() ?>>
        <label id="elh_document_attachments_file_type" for="x_file_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_type->caption() ?><?= $Page->file_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->file_type->cellAttributes() ?>>
<span id="el_document_attachments_file_type">
<input type="<?= $Page->file_type->getInputTextType() ?>" name="x_file_type" id="x_file_type" data-table="document_attachments" data-field="x_file_type" value="<?= $Page->file_type->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->file_type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->file_type->formatPattern()) ?>"<?= $Page->file_type->editAttributes() ?> aria-describedby="x_file_type_help">
<?= $Page->file_type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_size->Visible) { // file_size ?>
    <div id="r_file_size"<?= $Page->file_size->rowAttributes() ?>>
        <label id="elh_document_attachments_file_size" for="x_file_size" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_size->caption() ?><?= $Page->file_size->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->file_size->cellAttributes() ?>>
<span id="el_document_attachments_file_size">
<input type="<?= $Page->file_size->getInputTextType() ?>" name="x_file_size" id="x_file_size" data-table="document_attachments" data-field="x_file_size" value="<?= $Page->file_size->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->file_size->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->file_size->formatPattern()) ?>"<?= $Page->file_size->editAttributes() ?> aria-describedby="x_file_size_help">
<?= $Page->file_size->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_size->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->uploaded_at->Visible) { // uploaded_at ?>
    <div id="r_uploaded_at"<?= $Page->uploaded_at->rowAttributes() ?>>
        <label id="elh_document_attachments_uploaded_at" for="x_uploaded_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->uploaded_at->caption() ?><?= $Page->uploaded_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->uploaded_at->cellAttributes() ?>>
<span id="el_document_attachments_uploaded_at">
<input type="<?= $Page->uploaded_at->getInputTextType() ?>" name="x_uploaded_at" id="x_uploaded_at" data-table="document_attachments" data-field="x_uploaded_at" value="<?= $Page->uploaded_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->uploaded_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->uploaded_at->formatPattern()) ?>"<?= $Page->uploaded_at->editAttributes() ?> aria-describedby="x_uploaded_at_help">
<?= $Page->uploaded_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->uploaded_at->getErrorMessage() ?></div>
<?php if (!$Page->uploaded_at->ReadOnly && !$Page->uploaded_at->Disabled && !isset($Page->uploaded_at->EditAttrs["readonly"]) && !isset($Page->uploaded_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocument_attachmentsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocument_attachmentsedit", "x_uploaded_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->uploaded_by->Visible) { // uploaded_by ?>
    <div id="r_uploaded_by"<?= $Page->uploaded_by->rowAttributes() ?>>
        <label id="elh_document_attachments_uploaded_by" for="x_uploaded_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->uploaded_by->caption() ?><?= $Page->uploaded_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->uploaded_by->cellAttributes() ?>>
<span id="el_document_attachments_uploaded_by">
<input type="<?= $Page->uploaded_by->getInputTextType() ?>" name="x_uploaded_by" id="x_uploaded_by" data-table="document_attachments" data-field="x_uploaded_by" value="<?= $Page->uploaded_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->uploaded_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->uploaded_by->formatPattern()) ?>"<?= $Page->uploaded_by->editAttributes() ?> aria-describedby="x_uploaded_by_help">
<?= $Page->uploaded_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->uploaded_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description"<?= $Page->description->rowAttributes() ?>>
        <label id="elh_document_attachments_description" for="x_description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->description->cellAttributes() ?>>
<span id="el_document_attachments_description">
<textarea data-table="document_attachments" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help"><?= $Page->description->EditValue ?></textarea>
<?= $Page->description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_supporting->Visible) { // is_supporting ?>
    <div id="r_is_supporting"<?= $Page->is_supporting->rowAttributes() ?>>
        <label id="elh_document_attachments_is_supporting" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_supporting->caption() ?><?= $Page->is_supporting->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_supporting->cellAttributes() ?>>
<span id="el_document_attachments_is_supporting">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_supporting->isInvalidClass() ?>" data-table="document_attachments" data-field="x_is_supporting" data-boolean name="x_is_supporting" id="x_is_supporting" value="1"<?= ConvertToBool($Page->is_supporting->CurrentValue) ? " checked" : "" ?><?= $Page->is_supporting->editAttributes() ?> aria-describedby="x_is_supporting_help">
    <div class="invalid-feedback"><?= $Page->is_supporting->getErrorMessage() ?></div>
</div>
<?= $Page->is_supporting->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdocument_attachmentsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdocument_attachmentsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("document_attachments");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
