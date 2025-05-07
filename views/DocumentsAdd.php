<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fdocumentsadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocumentsadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null, ew.Validators.integer], fields.user_id.isInvalid],
            ["template_id", [fields.template_id.visible && fields.template_id.required ? ew.Validators.required(fields.template_id.caption) : null, ew.Validators.integer], fields.template_id.isInvalid],
            ["document_title", [fields.document_title.visible && fields.document_title.required ? ew.Validators.required(fields.document_title.caption) : null], fields.document_title.isInvalid],
            ["document_reference", [fields.document_reference.visible && fields.document_reference.required ? ew.Validators.required(fields.document_reference.caption) : null], fields.document_reference.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid],
            ["submitted_at", [fields.submitted_at.visible && fields.submitted_at.required ? ew.Validators.required(fields.submitted_at.caption) : null, ew.Validators.datetime(fields.submitted_at.clientFormatPattern)], fields.submitted_at.isInvalid],
            ["company_name", [fields.company_name.visible && fields.company_name.required ? ew.Validators.required(fields.company_name.caption) : null], fields.company_name.isInvalid],
            ["customs_entry_number", [fields.customs_entry_number.visible && fields.customs_entry_number.required ? ew.Validators.required(fields.customs_entry_number.caption) : null], fields.customs_entry_number.isInvalid],
            ["date_of_entry", [fields.date_of_entry.visible && fields.date_of_entry.required ? ew.Validators.required(fields.date_of_entry.caption) : null, ew.Validators.datetime(fields.date_of_entry.clientFormatPattern)], fields.date_of_entry.isInvalid],
            ["document_html", [fields.document_html.visible && fields.document_html.required ? ew.Validators.required(fields.document_html.caption) : null], fields.document_html.isInvalid],
            ["document_data", [fields.document_data.visible && fields.document_data.required ? ew.Validators.required(fields.document_data.caption) : null], fields.document_data.isInvalid],
            ["is_deleted", [fields.is_deleted.visible && fields.is_deleted.required ? ew.Validators.required(fields.is_deleted.caption) : null], fields.is_deleted.isInvalid],
            ["deletion_date", [fields.deletion_date.visible && fields.deletion_date.required ? ew.Validators.required(fields.deletion_date.caption) : null, ew.Validators.datetime(fields.deletion_date.clientFormatPattern)], fields.deletion_date.isInvalid],
            ["deleted_by", [fields.deleted_by.visible && fields.deleted_by.required ? ew.Validators.required(fields.deleted_by.caption) : null, ew.Validators.integer], fields.deleted_by.isInvalid],
            ["parent_document_id", [fields.parent_document_id.visible && fields.parent_document_id.required ? ew.Validators.required(fields.parent_document_id.caption) : null, ew.Validators.integer], fields.parent_document_id.isInvalid],
            ["version", [fields.version.visible && fields.version.required ? ew.Validators.required(fields.version.caption) : null, ew.Validators.integer], fields.version.isInvalid],
            ["notes", [fields.notes.visible && fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid]
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
            "is_deleted": <?= $Page->is_deleted->toClientList($Page) ?>,
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fdocumentsadd" id="fdocumentsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="documents">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->user_id->Visible) { // user_id ?>
    <div id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <label id="elh_documents_user_id" for="x_user_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_id->caption() ?><?= $Page->user_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_id->cellAttributes() ?>>
<span id="el_documents_user_id">
<input type="<?= $Page->user_id->getInputTextType() ?>" name="x_user_id" id="x_user_id" data-table="documents" data-field="x_user_id" value="<?= $Page->user_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->user_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user_id->formatPattern()) ?>"<?= $Page->user_id->editAttributes() ?> aria-describedby="x_user_id_help">
<?= $Page->user_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
    <div id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <label id="elh_documents_template_id" for="x_template_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->template_id->caption() ?><?= $Page->template_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->template_id->cellAttributes() ?>>
<span id="el_documents_template_id">
<input type="<?= $Page->template_id->getInputTextType() ?>" name="x_template_id" id="x_template_id" data-table="documents" data-field="x_template_id" value="<?= $Page->template_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->template_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->template_id->formatPattern()) ?>"<?= $Page->template_id->editAttributes() ?> aria-describedby="x_template_id_help">
<?= $Page->template_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->template_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_title->Visible) { // document_title ?>
    <div id="r_document_title"<?= $Page->document_title->rowAttributes() ?>>
        <label id="elh_documents_document_title" for="x_document_title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_title->caption() ?><?= $Page->document_title->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_title->cellAttributes() ?>>
<span id="el_documents_document_title">
<input type="<?= $Page->document_title->getInputTextType() ?>" name="x_document_title" id="x_document_title" data-table="documents" data-field="x_document_title" value="<?= $Page->document_title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->document_title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_title->formatPattern()) ?>"<?= $Page->document_title->editAttributes() ?> aria-describedby="x_document_title_help">
<?= $Page->document_title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_title->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_reference->Visible) { // document_reference ?>
    <div id="r_document_reference"<?= $Page->document_reference->rowAttributes() ?>>
        <label id="elh_documents_document_reference" for="x_document_reference" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_reference->caption() ?><?= $Page->document_reference->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_reference->cellAttributes() ?>>
<span id="el_documents_document_reference">
<input type="<?= $Page->document_reference->getInputTextType() ?>" name="x_document_reference" id="x_document_reference" data-table="documents" data-field="x_document_reference" value="<?= $Page->document_reference->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->document_reference->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->document_reference->formatPattern()) ?>"<?= $Page->document_reference->editAttributes() ?> aria-describedby="x_document_reference_help">
<?= $Page->document_reference->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_reference->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_documents_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_documents_status">
<input type="<?= $Page->status->getInputTextType() ?>" name="x_status" id="x_status" data-table="documents" data-field="x_status" value="<?= $Page->status->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->status->formatPattern()) ?>"<?= $Page->status->editAttributes() ?> aria-describedby="x_status_help">
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_documents_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_documents_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="documents" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocumentsadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocumentsadd", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_documents_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_documents_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="documents" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocumentsadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocumentsadd", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
    <div id="r_submitted_at"<?= $Page->submitted_at->rowAttributes() ?>>
        <label id="elh_documents_submitted_at" for="x_submitted_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->submitted_at->caption() ?><?= $Page->submitted_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->submitted_at->cellAttributes() ?>>
<span id="el_documents_submitted_at">
<input type="<?= $Page->submitted_at->getInputTextType() ?>" name="x_submitted_at" id="x_submitted_at" data-table="documents" data-field="x_submitted_at" value="<?= $Page->submitted_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->submitted_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->submitted_at->formatPattern()) ?>"<?= $Page->submitted_at->editAttributes() ?> aria-describedby="x_submitted_at_help">
<?= $Page->submitted_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->submitted_at->getErrorMessage() ?></div>
<?php if (!$Page->submitted_at->ReadOnly && !$Page->submitted_at->Disabled && !isset($Page->submitted_at->EditAttrs["readonly"]) && !isset($Page->submitted_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocumentsadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocumentsadd", "x_submitted_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->company_name->Visible) { // company_name ?>
    <div id="r_company_name"<?= $Page->company_name->rowAttributes() ?>>
        <label id="elh_documents_company_name" for="x_company_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->company_name->caption() ?><?= $Page->company_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->company_name->cellAttributes() ?>>
<span id="el_documents_company_name">
<input type="<?= $Page->company_name->getInputTextType() ?>" name="x_company_name" id="x_company_name" data-table="documents" data-field="x_company_name" value="<?= $Page->company_name->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->company_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->company_name->formatPattern()) ?>"<?= $Page->company_name->editAttributes() ?> aria-describedby="x_company_name_help">
<?= $Page->company_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->company_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->customs_entry_number->Visible) { // customs_entry_number ?>
    <div id="r_customs_entry_number"<?= $Page->customs_entry_number->rowAttributes() ?>>
        <label id="elh_documents_customs_entry_number" for="x_customs_entry_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->customs_entry_number->caption() ?><?= $Page->customs_entry_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->customs_entry_number->cellAttributes() ?>>
<span id="el_documents_customs_entry_number">
<input type="<?= $Page->customs_entry_number->getInputTextType() ?>" name="x_customs_entry_number" id="x_customs_entry_number" data-table="documents" data-field="x_customs_entry_number" value="<?= $Page->customs_entry_number->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->customs_entry_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->customs_entry_number->formatPattern()) ?>"<?= $Page->customs_entry_number->editAttributes() ?> aria-describedby="x_customs_entry_number_help">
<?= $Page->customs_entry_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->customs_entry_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->date_of_entry->Visible) { // date_of_entry ?>
    <div id="r_date_of_entry"<?= $Page->date_of_entry->rowAttributes() ?>>
        <label id="elh_documents_date_of_entry" for="x_date_of_entry" class="<?= $Page->LeftColumnClass ?>"><?= $Page->date_of_entry->caption() ?><?= $Page->date_of_entry->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->date_of_entry->cellAttributes() ?>>
<span id="el_documents_date_of_entry">
<input type="<?= $Page->date_of_entry->getInputTextType() ?>" name="x_date_of_entry" id="x_date_of_entry" data-table="documents" data-field="x_date_of_entry" value="<?= $Page->date_of_entry->EditValue ?>" placeholder="<?= HtmlEncode($Page->date_of_entry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->date_of_entry->formatPattern()) ?>"<?= $Page->date_of_entry->editAttributes() ?> aria-describedby="x_date_of_entry_help">
<?= $Page->date_of_entry->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->date_of_entry->getErrorMessage() ?></div>
<?php if (!$Page->date_of_entry->ReadOnly && !$Page->date_of_entry->Disabled && !isset($Page->date_of_entry->EditAttrs["readonly"]) && !isset($Page->date_of_entry->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocumentsadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocumentsadd", "x_date_of_entry", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_html->Visible) { // document_html ?>
    <div id="r_document_html"<?= $Page->document_html->rowAttributes() ?>>
        <label id="elh_documents_document_html" for="x_document_html" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_html->caption() ?><?= $Page->document_html->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_html->cellAttributes() ?>>
<span id="el_documents_document_html">
<textarea data-table="documents" data-field="x_document_html" name="x_document_html" id="x_document_html" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->document_html->getPlaceHolder()) ?>"<?= $Page->document_html->editAttributes() ?> aria-describedby="x_document_html_help"><?= $Page->document_html->EditValue ?></textarea>
<?= $Page->document_html->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_html->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_data->Visible) { // document_data ?>
    <div id="r_document_data"<?= $Page->document_data->rowAttributes() ?>>
        <label id="elh_documents_document_data" for="x_document_data" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_data->caption() ?><?= $Page->document_data->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_data->cellAttributes() ?>>
<span id="el_documents_document_data">
<textarea data-table="documents" data-field="x_document_data" name="x_document_data" id="x_document_data" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->document_data->getPlaceHolder()) ?>"<?= $Page->document_data->editAttributes() ?> aria-describedby="x_document_data_help"><?= $Page->document_data->EditValue ?></textarea>
<?= $Page->document_data->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_data->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_deleted->Visible) { // is_deleted ?>
    <div id="r_is_deleted"<?= $Page->is_deleted->rowAttributes() ?>>
        <label id="elh_documents_is_deleted" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_deleted->caption() ?><?= $Page->is_deleted->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_deleted->cellAttributes() ?>>
<span id="el_documents_is_deleted">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_deleted->isInvalidClass() ?>" data-table="documents" data-field="x_is_deleted" data-boolean name="x_is_deleted" id="x_is_deleted" value="1"<?= ConvertToBool($Page->is_deleted->CurrentValue) ? " checked" : "" ?><?= $Page->is_deleted->editAttributes() ?> aria-describedby="x_is_deleted_help">
    <div class="invalid-feedback"><?= $Page->is_deleted->getErrorMessage() ?></div>
</div>
<?= $Page->is_deleted->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->deletion_date->Visible) { // deletion_date ?>
    <div id="r_deletion_date"<?= $Page->deletion_date->rowAttributes() ?>>
        <label id="elh_documents_deletion_date" for="x_deletion_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->deletion_date->caption() ?><?= $Page->deletion_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->deletion_date->cellAttributes() ?>>
<span id="el_documents_deletion_date">
<input type="<?= $Page->deletion_date->getInputTextType() ?>" name="x_deletion_date" id="x_deletion_date" data-table="documents" data-field="x_deletion_date" value="<?= $Page->deletion_date->EditValue ?>" placeholder="<?= HtmlEncode($Page->deletion_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->deletion_date->formatPattern()) ?>"<?= $Page->deletion_date->editAttributes() ?> aria-describedby="x_deletion_date_help">
<?= $Page->deletion_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->deletion_date->getErrorMessage() ?></div>
<?php if (!$Page->deletion_date->ReadOnly && !$Page->deletion_date->Disabled && !isset($Page->deletion_date->EditAttrs["readonly"]) && !isset($Page->deletion_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocumentsadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocumentsadd", "x_deletion_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->deleted_by->Visible) { // deleted_by ?>
    <div id="r_deleted_by"<?= $Page->deleted_by->rowAttributes() ?>>
        <label id="elh_documents_deleted_by" for="x_deleted_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->deleted_by->caption() ?><?= $Page->deleted_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->deleted_by->cellAttributes() ?>>
<span id="el_documents_deleted_by">
<input type="<?= $Page->deleted_by->getInputTextType() ?>" name="x_deleted_by" id="x_deleted_by" data-table="documents" data-field="x_deleted_by" value="<?= $Page->deleted_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->deleted_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->deleted_by->formatPattern()) ?>"<?= $Page->deleted_by->editAttributes() ?> aria-describedby="x_deleted_by_help">
<?= $Page->deleted_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->deleted_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->parent_document_id->Visible) { // parent_document_id ?>
    <div id="r_parent_document_id"<?= $Page->parent_document_id->rowAttributes() ?>>
        <label id="elh_documents_parent_document_id" for="x_parent_document_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->parent_document_id->caption() ?><?= $Page->parent_document_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->parent_document_id->cellAttributes() ?>>
<span id="el_documents_parent_document_id">
<input type="<?= $Page->parent_document_id->getInputTextType() ?>" name="x_parent_document_id" id="x_parent_document_id" data-table="documents" data-field="x_parent_document_id" value="<?= $Page->parent_document_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->parent_document_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->parent_document_id->formatPattern()) ?>"<?= $Page->parent_document_id->editAttributes() ?> aria-describedby="x_parent_document_id_help">
<?= $Page->parent_document_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->parent_document_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->version->Visible) { // version ?>
    <div id="r_version"<?= $Page->version->rowAttributes() ?>>
        <label id="elh_documents_version" for="x_version" class="<?= $Page->LeftColumnClass ?>"><?= $Page->version->caption() ?><?= $Page->version->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->version->cellAttributes() ?>>
<span id="el_documents_version">
<input type="<?= $Page->version->getInputTextType() ?>" name="x_version" id="x_version" data-table="documents" data-field="x_version" value="<?= $Page->version->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->version->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->version->formatPattern()) ?>"<?= $Page->version->editAttributes() ?> aria-describedby="x_version_help">
<?= $Page->version->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->version->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <div id="r_notes"<?= $Page->notes->rowAttributes() ?>>
        <label id="elh_documents_notes" for="x_notes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notes->caption() ?><?= $Page->notes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notes->cellAttributes() ?>>
<span id="el_documents_notes">
<textarea data-table="documents" data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>"<?= $Page->notes->editAttributes() ?> aria-describedby="x_notes_help"><?= $Page->notes->EditValue ?></textarea>
<?= $Page->notes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdocumentsadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdocumentsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("documents");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
