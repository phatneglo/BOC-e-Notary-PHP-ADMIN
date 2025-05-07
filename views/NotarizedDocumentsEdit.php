<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizedDocumentsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fnotarized_documentsedit" id="fnotarized_documentsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarized_documents: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fnotarized_documentsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotarized_documentsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["notarized_id", [fields.notarized_id.visible && fields.notarized_id.required ? ew.Validators.required(fields.notarized_id.caption) : null], fields.notarized_id.isInvalid],
            ["request_id", [fields.request_id.visible && fields.request_id.required ? ew.Validators.required(fields.request_id.caption) : null, ew.Validators.integer], fields.request_id.isInvalid],
            ["document_id", [fields.document_id.visible && fields.document_id.required ? ew.Validators.required(fields.document_id.caption) : null, ew.Validators.integer], fields.document_id.isInvalid],
            ["notary_id", [fields.notary_id.visible && fields.notary_id.required ? ew.Validators.required(fields.notary_id.caption) : null, ew.Validators.integer], fields.notary_id.isInvalid],
            ["document_number", [fields.document_number.visible && fields.document_number.required ? ew.Validators.required(fields.document_number.caption) : null], fields.document_number.isInvalid],
            ["page_number", [fields.page_number.visible && fields.page_number.required ? ew.Validators.required(fields.page_number.caption) : null, ew.Validators.integer], fields.page_number.isInvalid],
            ["book_number", [fields.book_number.visible && fields.book_number.required ? ew.Validators.required(fields.book_number.caption) : null], fields.book_number.isInvalid],
            ["series_of", [fields.series_of.visible && fields.series_of.required ? ew.Validators.required(fields.series_of.caption) : null], fields.series_of.isInvalid],
            ["doc_keycode", [fields.doc_keycode.visible && fields.doc_keycode.required ? ew.Validators.required(fields.doc_keycode.caption) : null], fields.doc_keycode.isInvalid],
            ["notary_location", [fields.notary_location.visible && fields.notary_location.required ? ew.Validators.required(fields.notary_location.caption) : null], fields.notary_location.isInvalid],
            ["notarization_date", [fields.notarization_date.visible && fields.notarization_date.required ? ew.Validators.required(fields.notarization_date.caption) : null, ew.Validators.datetime(fields.notarization_date.clientFormatPattern)], fields.notarization_date.isInvalid],
            ["digital_signature", [fields.digital_signature.visible && fields.digital_signature.required ? ew.Validators.required(fields.digital_signature.caption) : null], fields.digital_signature.isInvalid],
            ["digital_seal", [fields.digital_seal.visible && fields.digital_seal.required ? ew.Validators.required(fields.digital_seal.caption) : null], fields.digital_seal.isInvalid],
            ["certificate_text", [fields.certificate_text.visible && fields.certificate_text.required ? ew.Validators.required(fields.certificate_text.caption) : null], fields.certificate_text.isInvalid],
            ["certificate_type", [fields.certificate_type.visible && fields.certificate_type.required ? ew.Validators.required(fields.certificate_type.caption) : null], fields.certificate_type.isInvalid],
            ["qr_code_path", [fields.qr_code_path.visible && fields.qr_code_path.required ? ew.Validators.required(fields.qr_code_path.caption) : null], fields.qr_code_path.isInvalid],
            ["notarized_document_path", [fields.notarized_document_path.visible && fields.notarized_document_path.required ? ew.Validators.required(fields.notarized_document_path.caption) : null], fields.notarized_document_path.isInvalid],
            ["expires_at", [fields.expires_at.visible && fields.expires_at.required ? ew.Validators.required(fields.expires_at.caption) : null, ew.Validators.datetime(fields.expires_at.clientFormatPattern)], fields.expires_at.isInvalid],
            ["revoked", [fields.revoked.visible && fields.revoked.required ? ew.Validators.required(fields.revoked.caption) : null], fields.revoked.isInvalid],
            ["revoked_at", [fields.revoked_at.visible && fields.revoked_at.required ? ew.Validators.required(fields.revoked_at.caption) : null, ew.Validators.datetime(fields.revoked_at.clientFormatPattern)], fields.revoked_at.isInvalid],
            ["revoked_by", [fields.revoked_by.visible && fields.revoked_by.required ? ew.Validators.required(fields.revoked_by.caption) : null, ew.Validators.integer], fields.revoked_by.isInvalid],
            ["revocation_reason", [fields.revocation_reason.visible && fields.revocation_reason.required ? ew.Validators.required(fields.revocation_reason.caption) : null], fields.revocation_reason.isInvalid]
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
            "revoked": <?= $Page->revoked->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="notarized_documents">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
    <div id="r_notarized_id"<?= $Page->notarized_id->rowAttributes() ?>>
        <label id="elh_notarized_documents_notarized_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notarized_id->caption() ?><?= $Page->notarized_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notarized_id->cellAttributes() ?>>
<span id="el_notarized_documents_notarized_id">
<span<?= $Page->notarized_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->notarized_id->getDisplayValue($Page->notarized_id->EditValue))) ?>"></span>
<input type="hidden" data-table="notarized_documents" data-field="x_notarized_id" data-hidden="1" name="x_notarized_id" id="x_notarized_id" value="<?= HtmlEncode($Page->notarized_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
    <div id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <label id="elh_notarized_documents_request_id" for="x_request_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->request_id->caption() ?><?= $Page->request_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->request_id->cellAttributes() ?>>
<span id="el_notarized_documents_request_id">
<input type="<?= $Page->request_id->getInputTextType() ?>" name="x_request_id" id="x_request_id" data-table="notarized_documents" data-field="x_request_id" value="<?= $Page->request_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->request_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->request_id->formatPattern()) ?>"<?= $Page->request_id->editAttributes() ?> aria-describedby="x_request_id_help">
<?= $Page->request_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->request_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <div id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <label id="elh_notarized_documents_document_id" for="x_document_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_id->caption() ?><?= $Page->document_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_id->cellAttributes() ?>>
<span id="el_notarized_documents_document_id">
<input type="<?= $Page->document_id->getInputTextType() ?>" name="x_document_id" id="x_document_id" data-table="notarized_documents" data-field="x_document_id" value="<?= $Page->document_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->document_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_id->formatPattern()) ?>"<?= $Page->document_id->editAttributes() ?> aria-describedby="x_document_id_help">
<?= $Page->document_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
    <div id="r_notary_id"<?= $Page->notary_id->rowAttributes() ?>>
        <label id="elh_notarized_documents_notary_id" for="x_notary_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notary_id->caption() ?><?= $Page->notary_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notary_id->cellAttributes() ?>>
<span id="el_notarized_documents_notary_id">
<input type="<?= $Page->notary_id->getInputTextType() ?>" name="x_notary_id" id="x_notary_id" data-table="notarized_documents" data-field="x_notary_id" value="<?= $Page->notary_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->notary_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notary_id->formatPattern()) ?>"<?= $Page->notary_id->editAttributes() ?> aria-describedby="x_notary_id_help">
<?= $Page->notary_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notary_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
    <div id="r_document_number"<?= $Page->document_number->rowAttributes() ?>>
        <label id="elh_notarized_documents_document_number" for="x_document_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_number->caption() ?><?= $Page->document_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_number->cellAttributes() ?>>
<span id="el_notarized_documents_document_number">
<input type="<?= $Page->document_number->getInputTextType() ?>" name="x_document_number" id="x_document_number" data-table="notarized_documents" data-field="x_document_number" value="<?= $Page->document_number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->document_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_number->formatPattern()) ?>"<?= $Page->document_number->editAttributes() ?> aria-describedby="x_document_number_help">
<?= $Page->document_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->page_number->Visible) { // page_number ?>
    <div id="r_page_number"<?= $Page->page_number->rowAttributes() ?>>
        <label id="elh_notarized_documents_page_number" for="x_page_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->page_number->caption() ?><?= $Page->page_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->page_number->cellAttributes() ?>>
<span id="el_notarized_documents_page_number">
<input type="<?= $Page->page_number->getInputTextType() ?>" name="x_page_number" id="x_page_number" data-table="notarized_documents" data-field="x_page_number" value="<?= $Page->page_number->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->page_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->page_number->formatPattern()) ?>"<?= $Page->page_number->editAttributes() ?> aria-describedby="x_page_number_help">
<?= $Page->page_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->page_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->book_number->Visible) { // book_number ?>
    <div id="r_book_number"<?= $Page->book_number->rowAttributes() ?>>
        <label id="elh_notarized_documents_book_number" for="x_book_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->book_number->caption() ?><?= $Page->book_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->book_number->cellAttributes() ?>>
<span id="el_notarized_documents_book_number">
<input type="<?= $Page->book_number->getInputTextType() ?>" name="x_book_number" id="x_book_number" data-table="notarized_documents" data-field="x_book_number" value="<?= $Page->book_number->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->book_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->book_number->formatPattern()) ?>"<?= $Page->book_number->editAttributes() ?> aria-describedby="x_book_number_help">
<?= $Page->book_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->book_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->series_of->Visible) { // series_of ?>
    <div id="r_series_of"<?= $Page->series_of->rowAttributes() ?>>
        <label id="elh_notarized_documents_series_of" for="x_series_of" class="<?= $Page->LeftColumnClass ?>"><?= $Page->series_of->caption() ?><?= $Page->series_of->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->series_of->cellAttributes() ?>>
<span id="el_notarized_documents_series_of">
<input type="<?= $Page->series_of->getInputTextType() ?>" name="x_series_of" id="x_series_of" data-table="notarized_documents" data-field="x_series_of" value="<?= $Page->series_of->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->series_of->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->series_of->formatPattern()) ?>"<?= $Page->series_of->editAttributes() ?> aria-describedby="x_series_of_help">
<?= $Page->series_of->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->series_of->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->doc_keycode->Visible) { // doc_keycode ?>
    <div id="r_doc_keycode"<?= $Page->doc_keycode->rowAttributes() ?>>
        <label id="elh_notarized_documents_doc_keycode" for="x_doc_keycode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->doc_keycode->caption() ?><?= $Page->doc_keycode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->doc_keycode->cellAttributes() ?>>
<span id="el_notarized_documents_doc_keycode">
<input type="<?= $Page->doc_keycode->getInputTextType() ?>" name="x_doc_keycode" id="x_doc_keycode" data-table="notarized_documents" data-field="x_doc_keycode" value="<?= $Page->doc_keycode->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->doc_keycode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->doc_keycode->formatPattern()) ?>"<?= $Page->doc_keycode->editAttributes() ?> aria-describedby="x_doc_keycode_help">
<?= $Page->doc_keycode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->doc_keycode->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notary_location->Visible) { // notary_location ?>
    <div id="r_notary_location"<?= $Page->notary_location->rowAttributes() ?>>
        <label id="elh_notarized_documents_notary_location" for="x_notary_location" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notary_location->caption() ?><?= $Page->notary_location->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notary_location->cellAttributes() ?>>
<span id="el_notarized_documents_notary_location">
<input type="<?= $Page->notary_location->getInputTextType() ?>" name="x_notary_location" id="x_notary_location" data-table="notarized_documents" data-field="x_notary_location" value="<?= $Page->notary_location->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->notary_location->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notary_location->formatPattern()) ?>"<?= $Page->notary_location->editAttributes() ?> aria-describedby="x_notary_location_help">
<?= $Page->notary_location->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notary_location->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notarization_date->Visible) { // notarization_date ?>
    <div id="r_notarization_date"<?= $Page->notarization_date->rowAttributes() ?>>
        <label id="elh_notarized_documents_notarization_date" for="x_notarization_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notarization_date->caption() ?><?= $Page->notarization_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notarization_date->cellAttributes() ?>>
<span id="el_notarized_documents_notarization_date">
<input type="<?= $Page->notarization_date->getInputTextType() ?>" name="x_notarization_date" id="x_notarization_date" data-table="notarized_documents" data-field="x_notarization_date" value="<?= $Page->notarization_date->EditValue ?>" placeholder="<?= HtmlEncode($Page->notarization_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notarization_date->formatPattern()) ?>"<?= $Page->notarization_date->editAttributes() ?> aria-describedby="x_notarization_date_help">
<?= $Page->notarization_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notarization_date->getErrorMessage() ?></div>
<?php if (!$Page->notarization_date->ReadOnly && !$Page->notarization_date->Disabled && !isset($Page->notarization_date->EditAttrs["readonly"]) && !isset($Page->notarization_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarized_documentsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarized_documentsedit", "x_notarization_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->digital_signature->Visible) { // digital_signature ?>
    <div id="r_digital_signature"<?= $Page->digital_signature->rowAttributes() ?>>
        <label id="elh_notarized_documents_digital_signature" for="x_digital_signature" class="<?= $Page->LeftColumnClass ?>"><?= $Page->digital_signature->caption() ?><?= $Page->digital_signature->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->digital_signature->cellAttributes() ?>>
<span id="el_notarized_documents_digital_signature">
<textarea data-table="notarized_documents" data-field="x_digital_signature" name="x_digital_signature" id="x_digital_signature" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->digital_signature->getPlaceHolder()) ?>"<?= $Page->digital_signature->editAttributes() ?> aria-describedby="x_digital_signature_help"><?= $Page->digital_signature->EditValue ?></textarea>
<?= $Page->digital_signature->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->digital_signature->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->digital_seal->Visible) { // digital_seal ?>
    <div id="r_digital_seal"<?= $Page->digital_seal->rowAttributes() ?>>
        <label id="elh_notarized_documents_digital_seal" for="x_digital_seal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->digital_seal->caption() ?><?= $Page->digital_seal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->digital_seal->cellAttributes() ?>>
<span id="el_notarized_documents_digital_seal">
<textarea data-table="notarized_documents" data-field="x_digital_seal" name="x_digital_seal" id="x_digital_seal" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->digital_seal->getPlaceHolder()) ?>"<?= $Page->digital_seal->editAttributes() ?> aria-describedby="x_digital_seal_help"><?= $Page->digital_seal->EditValue ?></textarea>
<?= $Page->digital_seal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->digital_seal->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->certificate_text->Visible) { // certificate_text ?>
    <div id="r_certificate_text"<?= $Page->certificate_text->rowAttributes() ?>>
        <label id="elh_notarized_documents_certificate_text" for="x_certificate_text" class="<?= $Page->LeftColumnClass ?>"><?= $Page->certificate_text->caption() ?><?= $Page->certificate_text->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->certificate_text->cellAttributes() ?>>
<span id="el_notarized_documents_certificate_text">
<textarea data-table="notarized_documents" data-field="x_certificate_text" name="x_certificate_text" id="x_certificate_text" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->certificate_text->getPlaceHolder()) ?>"<?= $Page->certificate_text->editAttributes() ?> aria-describedby="x_certificate_text_help"><?= $Page->certificate_text->EditValue ?></textarea>
<?= $Page->certificate_text->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->certificate_text->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->certificate_type->Visible) { // certificate_type ?>
    <div id="r_certificate_type"<?= $Page->certificate_type->rowAttributes() ?>>
        <label id="elh_notarized_documents_certificate_type" for="x_certificate_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->certificate_type->caption() ?><?= $Page->certificate_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->certificate_type->cellAttributes() ?>>
<span id="el_notarized_documents_certificate_type">
<input type="<?= $Page->certificate_type->getInputTextType() ?>" name="x_certificate_type" id="x_certificate_type" data-table="notarized_documents" data-field="x_certificate_type" value="<?= $Page->certificate_type->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->certificate_type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->certificate_type->formatPattern()) ?>"<?= $Page->certificate_type->editAttributes() ?> aria-describedby="x_certificate_type_help">
<?= $Page->certificate_type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->certificate_type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
    <div id="r_qr_code_path"<?= $Page->qr_code_path->rowAttributes() ?>>
        <label id="elh_notarized_documents_qr_code_path" for="x_qr_code_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->qr_code_path->caption() ?><?= $Page->qr_code_path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="el_notarized_documents_qr_code_path">
<input type="<?= $Page->qr_code_path->getInputTextType() ?>" name="x_qr_code_path" id="x_qr_code_path" data-table="notarized_documents" data-field="x_qr_code_path" value="<?= $Page->qr_code_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->qr_code_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->qr_code_path->formatPattern()) ?>"<?= $Page->qr_code_path->editAttributes() ?> aria-describedby="x_qr_code_path_help">
<?= $Page->qr_code_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->qr_code_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notarized_document_path->Visible) { // notarized_document_path ?>
    <div id="r_notarized_document_path"<?= $Page->notarized_document_path->rowAttributes() ?>>
        <label id="elh_notarized_documents_notarized_document_path" for="x_notarized_document_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notarized_document_path->caption() ?><?= $Page->notarized_document_path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notarized_document_path->cellAttributes() ?>>
<span id="el_notarized_documents_notarized_document_path">
<input type="<?= $Page->notarized_document_path->getInputTextType() ?>" name="x_notarized_document_path" id="x_notarized_document_path" data-table="notarized_documents" data-field="x_notarized_document_path" value="<?= $Page->notarized_document_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->notarized_document_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notarized_document_path->formatPattern()) ?>"<?= $Page->notarized_document_path->editAttributes() ?> aria-describedby="x_notarized_document_path_help">
<?= $Page->notarized_document_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notarized_document_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
    <div id="r_expires_at"<?= $Page->expires_at->rowAttributes() ?>>
        <label id="elh_notarized_documents_expires_at" for="x_expires_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->expires_at->caption() ?><?= $Page->expires_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->expires_at->cellAttributes() ?>>
<span id="el_notarized_documents_expires_at">
<input type="<?= $Page->expires_at->getInputTextType() ?>" name="x_expires_at" id="x_expires_at" data-table="notarized_documents" data-field="x_expires_at" value="<?= $Page->expires_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->expires_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->expires_at->formatPattern()) ?>"<?= $Page->expires_at->editAttributes() ?> aria-describedby="x_expires_at_help">
<?= $Page->expires_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->expires_at->getErrorMessage() ?></div>
<?php if (!$Page->expires_at->ReadOnly && !$Page->expires_at->Disabled && !isset($Page->expires_at->EditAttrs["readonly"]) && !isset($Page->expires_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarized_documentsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarized_documentsedit", "x_expires_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->revoked->Visible) { // revoked ?>
    <div id="r_revoked"<?= $Page->revoked->rowAttributes() ?>>
        <label id="elh_notarized_documents_revoked" class="<?= $Page->LeftColumnClass ?>"><?= $Page->revoked->caption() ?><?= $Page->revoked->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->revoked->cellAttributes() ?>>
<span id="el_notarized_documents_revoked">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->revoked->isInvalidClass() ?>" data-table="notarized_documents" data-field="x_revoked" data-boolean name="x_revoked" id="x_revoked" value="1"<?= ConvertToBool($Page->revoked->CurrentValue) ? " checked" : "" ?><?= $Page->revoked->editAttributes() ?> aria-describedby="x_revoked_help">
    <div class="invalid-feedback"><?= $Page->revoked->getErrorMessage() ?></div>
</div>
<?= $Page->revoked->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->revoked_at->Visible) { // revoked_at ?>
    <div id="r_revoked_at"<?= $Page->revoked_at->rowAttributes() ?>>
        <label id="elh_notarized_documents_revoked_at" for="x_revoked_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->revoked_at->caption() ?><?= $Page->revoked_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->revoked_at->cellAttributes() ?>>
<span id="el_notarized_documents_revoked_at">
<input type="<?= $Page->revoked_at->getInputTextType() ?>" name="x_revoked_at" id="x_revoked_at" data-table="notarized_documents" data-field="x_revoked_at" value="<?= $Page->revoked_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->revoked_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->revoked_at->formatPattern()) ?>"<?= $Page->revoked_at->editAttributes() ?> aria-describedby="x_revoked_at_help">
<?= $Page->revoked_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->revoked_at->getErrorMessage() ?></div>
<?php if (!$Page->revoked_at->ReadOnly && !$Page->revoked_at->Disabled && !isset($Page->revoked_at->EditAttrs["readonly"]) && !isset($Page->revoked_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotarized_documentsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotarized_documentsedit", "x_revoked_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->revoked_by->Visible) { // revoked_by ?>
    <div id="r_revoked_by"<?= $Page->revoked_by->rowAttributes() ?>>
        <label id="elh_notarized_documents_revoked_by" for="x_revoked_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->revoked_by->caption() ?><?= $Page->revoked_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->revoked_by->cellAttributes() ?>>
<span id="el_notarized_documents_revoked_by">
<input type="<?= $Page->revoked_by->getInputTextType() ?>" name="x_revoked_by" id="x_revoked_by" data-table="notarized_documents" data-field="x_revoked_by" value="<?= $Page->revoked_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->revoked_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->revoked_by->formatPattern()) ?>"<?= $Page->revoked_by->editAttributes() ?> aria-describedby="x_revoked_by_help">
<?= $Page->revoked_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->revoked_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->revocation_reason->Visible) { // revocation_reason ?>
    <div id="r_revocation_reason"<?= $Page->revocation_reason->rowAttributes() ?>>
        <label id="elh_notarized_documents_revocation_reason" for="x_revocation_reason" class="<?= $Page->LeftColumnClass ?>"><?= $Page->revocation_reason->caption() ?><?= $Page->revocation_reason->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->revocation_reason->cellAttributes() ?>>
<span id="el_notarized_documents_revocation_reason">
<textarea data-table="notarized_documents" data-field="x_revocation_reason" name="x_revocation_reason" id="x_revocation_reason" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->revocation_reason->getPlaceHolder()) ?>"<?= $Page->revocation_reason->editAttributes() ?> aria-describedby="x_revocation_reason_help"><?= $Page->revocation_reason->EditValue ?></textarea>
<?= $Page->revocation_reason->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->revocation_reason->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fnotarized_documentsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fnotarized_documentsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("notarized_documents");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
