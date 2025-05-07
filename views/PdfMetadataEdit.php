<?php

namespace PHPMaker2024\eNotary;

// Page object
$PdfMetadataEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fpdf_metadataedit" id="fpdf_metadataedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pdf_metadata: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fpdf_metadataedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpdf_metadataedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["metadata_id", [fields.metadata_id.visible && fields.metadata_id.required ? ew.Validators.required(fields.metadata_id.caption) : null], fields.metadata_id.isInvalid],
            ["document_id", [fields.document_id.visible && fields.document_id.required ? ew.Validators.required(fields.document_id.caption) : null, ew.Validators.integer], fields.document_id.isInvalid],
            ["notarized_id", [fields.notarized_id.visible && fields.notarized_id.required ? ew.Validators.required(fields.notarized_id.caption) : null, ew.Validators.integer], fields.notarized_id.isInvalid],
            ["pdf_type", [fields.pdf_type.visible && fields.pdf_type.required ? ew.Validators.required(fields.pdf_type.caption) : null], fields.pdf_type.isInvalid],
            ["file_path", [fields.file_path.visible && fields.file_path.required ? ew.Validators.required(fields.file_path.caption) : null], fields.file_path.isInvalid],
            ["file_size", [fields.file_size.visible && fields.file_size.required ? ew.Validators.required(fields.file_size.caption) : null, ew.Validators.integer], fields.file_size.isInvalid],
            ["page_count", [fields.page_count.visible && fields.page_count.required ? ew.Validators.required(fields.page_count.caption) : null, ew.Validators.integer], fields.page_count.isInvalid],
            ["generated_at", [fields.generated_at.visible && fields.generated_at.required ? ew.Validators.required(fields.generated_at.caption) : null, ew.Validators.datetime(fields.generated_at.clientFormatPattern)], fields.generated_at.isInvalid],
            ["generated_by", [fields.generated_by.visible && fields.generated_by.required ? ew.Validators.required(fields.generated_by.caption) : null, ew.Validators.integer], fields.generated_by.isInvalid],
            ["expires_at", [fields.expires_at.visible && fields.expires_at.required ? ew.Validators.required(fields.expires_at.caption) : null, ew.Validators.datetime(fields.expires_at.clientFormatPattern)], fields.expires_at.isInvalid],
            ["is_final", [fields.is_final.visible && fields.is_final.required ? ew.Validators.required(fields.is_final.caption) : null], fields.is_final.isInvalid],
            ["processing_options", [fields.processing_options.visible && fields.processing_options.required ? ew.Validators.required(fields.processing_options.caption) : null], fields.processing_options.isInvalid]
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
            "is_final": <?= $Page->is_final->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="pdf_metadata">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->metadata_id->Visible) { // metadata_id ?>
    <div id="r_metadata_id"<?= $Page->metadata_id->rowAttributes() ?>>
        <label id="elh_pdf_metadata_metadata_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->metadata_id->caption() ?><?= $Page->metadata_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->metadata_id->cellAttributes() ?>>
<span id="el_pdf_metadata_metadata_id">
<span<?= $Page->metadata_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->metadata_id->getDisplayValue($Page->metadata_id->EditValue))) ?>"></span>
<input type="hidden" data-table="pdf_metadata" data-field="x_metadata_id" data-hidden="1" name="x_metadata_id" id="x_metadata_id" value="<?= HtmlEncode($Page->metadata_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <div id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <label id="elh_pdf_metadata_document_id" for="x_document_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_id->caption() ?><?= $Page->document_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_id->cellAttributes() ?>>
<span id="el_pdf_metadata_document_id">
<input type="<?= $Page->document_id->getInputTextType() ?>" name="x_document_id" id="x_document_id" data-table="pdf_metadata" data-field="x_document_id" value="<?= $Page->document_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->document_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_id->formatPattern()) ?>"<?= $Page->document_id->editAttributes() ?> aria-describedby="x_document_id_help">
<?= $Page->document_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
    <div id="r_notarized_id"<?= $Page->notarized_id->rowAttributes() ?>>
        <label id="elh_pdf_metadata_notarized_id" for="x_notarized_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notarized_id->caption() ?><?= $Page->notarized_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notarized_id->cellAttributes() ?>>
<span id="el_pdf_metadata_notarized_id">
<input type="<?= $Page->notarized_id->getInputTextType() ?>" name="x_notarized_id" id="x_notarized_id" data-table="pdf_metadata" data-field="x_notarized_id" value="<?= $Page->notarized_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->notarized_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notarized_id->formatPattern()) ?>"<?= $Page->notarized_id->editAttributes() ?> aria-describedby="x_notarized_id_help">
<?= $Page->notarized_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notarized_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pdf_type->Visible) { // pdf_type ?>
    <div id="r_pdf_type"<?= $Page->pdf_type->rowAttributes() ?>>
        <label id="elh_pdf_metadata_pdf_type" for="x_pdf_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pdf_type->caption() ?><?= $Page->pdf_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pdf_type->cellAttributes() ?>>
<span id="el_pdf_metadata_pdf_type">
<input type="<?= $Page->pdf_type->getInputTextType() ?>" name="x_pdf_type" id="x_pdf_type" data-table="pdf_metadata" data-field="x_pdf_type" value="<?= $Page->pdf_type->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->pdf_type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pdf_type->formatPattern()) ?>"<?= $Page->pdf_type->editAttributes() ?> aria-describedby="x_pdf_type_help">
<?= $Page->pdf_type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pdf_type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
    <div id="r_file_path"<?= $Page->file_path->rowAttributes() ?>>
        <label id="elh_pdf_metadata_file_path" for="x_file_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_path->caption() ?><?= $Page->file_path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->file_path->cellAttributes() ?>>
<span id="el_pdf_metadata_file_path">
<input type="<?= $Page->file_path->getInputTextType() ?>" name="x_file_path" id="x_file_path" data-table="pdf_metadata" data-field="x_file_path" value="<?= $Page->file_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->file_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->file_path->formatPattern()) ?>"<?= $Page->file_path->editAttributes() ?> aria-describedby="x_file_path_help">
<?= $Page->file_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_size->Visible) { // file_size ?>
    <div id="r_file_size"<?= $Page->file_size->rowAttributes() ?>>
        <label id="elh_pdf_metadata_file_size" for="x_file_size" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_size->caption() ?><?= $Page->file_size->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->file_size->cellAttributes() ?>>
<span id="el_pdf_metadata_file_size">
<input type="<?= $Page->file_size->getInputTextType() ?>" name="x_file_size" id="x_file_size" data-table="pdf_metadata" data-field="x_file_size" value="<?= $Page->file_size->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->file_size->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->file_size->formatPattern()) ?>"<?= $Page->file_size->editAttributes() ?> aria-describedby="x_file_size_help">
<?= $Page->file_size->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_size->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->page_count->Visible) { // page_count ?>
    <div id="r_page_count"<?= $Page->page_count->rowAttributes() ?>>
        <label id="elh_pdf_metadata_page_count" for="x_page_count" class="<?= $Page->LeftColumnClass ?>"><?= $Page->page_count->caption() ?><?= $Page->page_count->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->page_count->cellAttributes() ?>>
<span id="el_pdf_metadata_page_count">
<input type="<?= $Page->page_count->getInputTextType() ?>" name="x_page_count" id="x_page_count" data-table="pdf_metadata" data-field="x_page_count" value="<?= $Page->page_count->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->page_count->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->page_count->formatPattern()) ?>"<?= $Page->page_count->editAttributes() ?> aria-describedby="x_page_count_help">
<?= $Page->page_count->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->page_count->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->generated_at->Visible) { // generated_at ?>
    <div id="r_generated_at"<?= $Page->generated_at->rowAttributes() ?>>
        <label id="elh_pdf_metadata_generated_at" for="x_generated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->generated_at->caption() ?><?= $Page->generated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->generated_at->cellAttributes() ?>>
<span id="el_pdf_metadata_generated_at">
<input type="<?= $Page->generated_at->getInputTextType() ?>" name="x_generated_at" id="x_generated_at" data-table="pdf_metadata" data-field="x_generated_at" value="<?= $Page->generated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->generated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->generated_at->formatPattern()) ?>"<?= $Page->generated_at->editAttributes() ?> aria-describedby="x_generated_at_help">
<?= $Page->generated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->generated_at->getErrorMessage() ?></div>
<?php if (!$Page->generated_at->ReadOnly && !$Page->generated_at->Disabled && !isset($Page->generated_at->EditAttrs["readonly"]) && !isset($Page->generated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpdf_metadataedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpdf_metadataedit", "x_generated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->generated_by->Visible) { // generated_by ?>
    <div id="r_generated_by"<?= $Page->generated_by->rowAttributes() ?>>
        <label id="elh_pdf_metadata_generated_by" for="x_generated_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->generated_by->caption() ?><?= $Page->generated_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->generated_by->cellAttributes() ?>>
<span id="el_pdf_metadata_generated_by">
<input type="<?= $Page->generated_by->getInputTextType() ?>" name="x_generated_by" id="x_generated_by" data-table="pdf_metadata" data-field="x_generated_by" value="<?= $Page->generated_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->generated_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->generated_by->formatPattern()) ?>"<?= $Page->generated_by->editAttributes() ?> aria-describedby="x_generated_by_help">
<?= $Page->generated_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->generated_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
    <div id="r_expires_at"<?= $Page->expires_at->rowAttributes() ?>>
        <label id="elh_pdf_metadata_expires_at" for="x_expires_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->expires_at->caption() ?><?= $Page->expires_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->expires_at->cellAttributes() ?>>
<span id="el_pdf_metadata_expires_at">
<input type="<?= $Page->expires_at->getInputTextType() ?>" name="x_expires_at" id="x_expires_at" data-table="pdf_metadata" data-field="x_expires_at" value="<?= $Page->expires_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->expires_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->expires_at->formatPattern()) ?>"<?= $Page->expires_at->editAttributes() ?> aria-describedby="x_expires_at_help">
<?= $Page->expires_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->expires_at->getErrorMessage() ?></div>
<?php if (!$Page->expires_at->ReadOnly && !$Page->expires_at->Disabled && !isset($Page->expires_at->EditAttrs["readonly"]) && !isset($Page->expires_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpdf_metadataedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpdf_metadataedit", "x_expires_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_final->Visible) { // is_final ?>
    <div id="r_is_final"<?= $Page->is_final->rowAttributes() ?>>
        <label id="elh_pdf_metadata_is_final" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_final->caption() ?><?= $Page->is_final->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_final->cellAttributes() ?>>
<span id="el_pdf_metadata_is_final">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_final->isInvalidClass() ?>" data-table="pdf_metadata" data-field="x_is_final" data-boolean name="x_is_final" id="x_is_final" value="1"<?= ConvertToBool($Page->is_final->CurrentValue) ? " checked" : "" ?><?= $Page->is_final->editAttributes() ?> aria-describedby="x_is_final_help">
    <div class="invalid-feedback"><?= $Page->is_final->getErrorMessage() ?></div>
</div>
<?= $Page->is_final->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->processing_options->Visible) { // processing_options ?>
    <div id="r_processing_options"<?= $Page->processing_options->rowAttributes() ?>>
        <label id="elh_pdf_metadata_processing_options" for="x_processing_options" class="<?= $Page->LeftColumnClass ?>"><?= $Page->processing_options->caption() ?><?= $Page->processing_options->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->processing_options->cellAttributes() ?>>
<span id="el_pdf_metadata_processing_options">
<textarea data-table="pdf_metadata" data-field="x_processing_options" name="x_processing_options" id="x_processing_options" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->processing_options->getPlaceHolder()) ?>"<?= $Page->processing_options->editAttributes() ?> aria-describedby="x_processing_options_help"><?= $Page->processing_options->EditValue ?></textarea>
<?= $Page->processing_options->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->processing_options->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpdf_metadataedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpdf_metadataedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("pdf_metadata");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
