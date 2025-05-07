<?php

namespace PHPMaker2024\eNotary;

// Page object
$FeeSchedulesEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="ffee_schedulesedit" id="ffee_schedulesedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fee_schedules: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var ffee_schedulesedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffee_schedulesedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["fee_id", [fields.fee_id.visible && fields.fee_id.required ? ew.Validators.required(fields.fee_id.caption) : null], fields.fee_id.isInvalid],
            ["template_id", [fields.template_id.visible && fields.template_id.required ? ew.Validators.required(fields.template_id.caption) : null, ew.Validators.integer], fields.template_id.isInvalid],
            ["fee_name", [fields.fee_name.visible && fields.fee_name.required ? ew.Validators.required(fields.fee_name.caption) : null], fields.fee_name.isInvalid],
            ["fee_amount", [fields.fee_amount.visible && fields.fee_amount.required ? ew.Validators.required(fields.fee_amount.caption) : null, ew.Validators.float], fields.fee_amount.isInvalid],
            ["fee_type", [fields.fee_type.visible && fields.fee_type.required ? ew.Validators.required(fields.fee_type.caption) : null], fields.fee_type.isInvalid],
            ["currency", [fields.currency.visible && fields.currency.required ? ew.Validators.required(fields.currency.caption) : null], fields.currency.isInvalid],
            ["effective_from", [fields.effective_from.visible && fields.effective_from.required ? ew.Validators.required(fields.effective_from.caption) : null, ew.Validators.datetime(fields.effective_from.clientFormatPattern)], fields.effective_from.isInvalid],
            ["effective_to", [fields.effective_to.visible && fields.effective_to.required ? ew.Validators.required(fields.effective_to.caption) : null, ew.Validators.datetime(fields.effective_to.clientFormatPattern)], fields.effective_to.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["created_by", [fields.created_by.visible && fields.created_by.required ? ew.Validators.required(fields.created_by.caption) : null, ew.Validators.integer], fields.created_by.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid],
            ["updated_by", [fields.updated_by.visible && fields.updated_by.required ? ew.Validators.required(fields.updated_by.caption) : null, ew.Validators.integer], fields.updated_by.isInvalid],
            ["is_active", [fields.is_active.visible && fields.is_active.required ? ew.Validators.required(fields.is_active.caption) : null], fields.is_active.isInvalid],
            ["description", [fields.description.visible && fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid]
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
            "is_active": <?= $Page->is_active->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="fee_schedules">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->fee_id->Visible) { // fee_id ?>
    <div id="r_fee_id"<?= $Page->fee_id->rowAttributes() ?>>
        <label id="elh_fee_schedules_fee_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fee_id->caption() ?><?= $Page->fee_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fee_id->cellAttributes() ?>>
<span id="el_fee_schedules_fee_id">
<span<?= $Page->fee_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fee_id->getDisplayValue($Page->fee_id->EditValue))) ?>"></span>
<input type="hidden" data-table="fee_schedules" data-field="x_fee_id" data-hidden="1" name="x_fee_id" id="x_fee_id" value="<?= HtmlEncode($Page->fee_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
    <div id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <label id="elh_fee_schedules_template_id" for="x_template_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->template_id->caption() ?><?= $Page->template_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->template_id->cellAttributes() ?>>
<span id="el_fee_schedules_template_id">
<input type="<?= $Page->template_id->getInputTextType() ?>" name="x_template_id" id="x_template_id" data-table="fee_schedules" data-field="x_template_id" value="<?= $Page->template_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->template_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->template_id->formatPattern()) ?>"<?= $Page->template_id->editAttributes() ?> aria-describedby="x_template_id_help">
<?= $Page->template_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->template_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fee_name->Visible) { // fee_name ?>
    <div id="r_fee_name"<?= $Page->fee_name->rowAttributes() ?>>
        <label id="elh_fee_schedules_fee_name" for="x_fee_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fee_name->caption() ?><?= $Page->fee_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fee_name->cellAttributes() ?>>
<span id="el_fee_schedules_fee_name">
<input type="<?= $Page->fee_name->getInputTextType() ?>" name="x_fee_name" id="x_fee_name" data-table="fee_schedules" data-field="x_fee_name" value="<?= $Page->fee_name->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->fee_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fee_name->formatPattern()) ?>"<?= $Page->fee_name->editAttributes() ?> aria-describedby="x_fee_name_help">
<?= $Page->fee_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fee_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
    <div id="r_fee_amount"<?= $Page->fee_amount->rowAttributes() ?>>
        <label id="elh_fee_schedules_fee_amount" for="x_fee_amount" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fee_amount->caption() ?><?= $Page->fee_amount->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fee_amount->cellAttributes() ?>>
<span id="el_fee_schedules_fee_amount">
<input type="<?= $Page->fee_amount->getInputTextType() ?>" name="x_fee_amount" id="x_fee_amount" data-table="fee_schedules" data-field="x_fee_amount" value="<?= $Page->fee_amount->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->fee_amount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fee_amount->formatPattern()) ?>"<?= $Page->fee_amount->editAttributes() ?> aria-describedby="x_fee_amount_help">
<?= $Page->fee_amount->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fee_amount->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fee_type->Visible) { // fee_type ?>
    <div id="r_fee_type"<?= $Page->fee_type->rowAttributes() ?>>
        <label id="elh_fee_schedules_fee_type" for="x_fee_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fee_type->caption() ?><?= $Page->fee_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fee_type->cellAttributes() ?>>
<span id="el_fee_schedules_fee_type">
<input type="<?= $Page->fee_type->getInputTextType() ?>" name="x_fee_type" id="x_fee_type" data-table="fee_schedules" data-field="x_fee_type" value="<?= $Page->fee_type->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->fee_type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fee_type->formatPattern()) ?>"<?= $Page->fee_type->editAttributes() ?> aria-describedby="x_fee_type_help">
<?= $Page->fee_type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fee_type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->currency->Visible) { // currency ?>
    <div id="r_currency"<?= $Page->currency->rowAttributes() ?>>
        <label id="elh_fee_schedules_currency" for="x_currency" class="<?= $Page->LeftColumnClass ?>"><?= $Page->currency->caption() ?><?= $Page->currency->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->currency->cellAttributes() ?>>
<span id="el_fee_schedules_currency">
<input type="<?= $Page->currency->getInputTextType() ?>" name="x_currency" id="x_currency" data-table="fee_schedules" data-field="x_currency" value="<?= $Page->currency->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->currency->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->currency->formatPattern()) ?>"<?= $Page->currency->editAttributes() ?> aria-describedby="x_currency_help">
<?= $Page->currency->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->currency->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->effective_from->Visible) { // effective_from ?>
    <div id="r_effective_from"<?= $Page->effective_from->rowAttributes() ?>>
        <label id="elh_fee_schedules_effective_from" for="x_effective_from" class="<?= $Page->LeftColumnClass ?>"><?= $Page->effective_from->caption() ?><?= $Page->effective_from->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->effective_from->cellAttributes() ?>>
<span id="el_fee_schedules_effective_from">
<input type="<?= $Page->effective_from->getInputTextType() ?>" name="x_effective_from" id="x_effective_from" data-table="fee_schedules" data-field="x_effective_from" value="<?= $Page->effective_from->EditValue ?>" placeholder="<?= HtmlEncode($Page->effective_from->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->effective_from->formatPattern()) ?>"<?= $Page->effective_from->editAttributes() ?> aria-describedby="x_effective_from_help">
<?= $Page->effective_from->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->effective_from->getErrorMessage() ?></div>
<?php if (!$Page->effective_from->ReadOnly && !$Page->effective_from->Disabled && !isset($Page->effective_from->EditAttrs["readonly"]) && !isset($Page->effective_from->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffee_schedulesedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("ffee_schedulesedit", "x_effective_from", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->effective_to->Visible) { // effective_to ?>
    <div id="r_effective_to"<?= $Page->effective_to->rowAttributes() ?>>
        <label id="elh_fee_schedules_effective_to" for="x_effective_to" class="<?= $Page->LeftColumnClass ?>"><?= $Page->effective_to->caption() ?><?= $Page->effective_to->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->effective_to->cellAttributes() ?>>
<span id="el_fee_schedules_effective_to">
<input type="<?= $Page->effective_to->getInputTextType() ?>" name="x_effective_to" id="x_effective_to" data-table="fee_schedules" data-field="x_effective_to" value="<?= $Page->effective_to->EditValue ?>" placeholder="<?= HtmlEncode($Page->effective_to->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->effective_to->formatPattern()) ?>"<?= $Page->effective_to->editAttributes() ?> aria-describedby="x_effective_to_help">
<?= $Page->effective_to->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->effective_to->getErrorMessage() ?></div>
<?php if (!$Page->effective_to->ReadOnly && !$Page->effective_to->Disabled && !isset($Page->effective_to->EditAttrs["readonly"]) && !isset($Page->effective_to->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffee_schedulesedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("ffee_schedulesedit", "x_effective_to", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_fee_schedules_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_fee_schedules_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="fee_schedules" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffee_schedulesedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("ffee_schedulesedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
    <div id="r_created_by"<?= $Page->created_by->rowAttributes() ?>>
        <label id="elh_fee_schedules_created_by" for="x_created_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_by->caption() ?><?= $Page->created_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_by->cellAttributes() ?>>
<span id="el_fee_schedules_created_by">
<input type="<?= $Page->created_by->getInputTextType() ?>" name="x_created_by" id="x_created_by" data-table="fee_schedules" data-field="x_created_by" value="<?= $Page->created_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->created_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_by->formatPattern()) ?>"<?= $Page->created_by->editAttributes() ?> aria-describedby="x_created_by_help">
<?= $Page->created_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_fee_schedules_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_fee_schedules_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="fee_schedules" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffee_schedulesedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("ffee_schedulesedit", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
    <div id="r_updated_by"<?= $Page->updated_by->rowAttributes() ?>>
        <label id="elh_fee_schedules_updated_by" for="x_updated_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_by->caption() ?><?= $Page->updated_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_by->cellAttributes() ?>>
<span id="el_fee_schedules_updated_by">
<input type="<?= $Page->updated_by->getInputTextType() ?>" name="x_updated_by" id="x_updated_by" data-table="fee_schedules" data-field="x_updated_by" value="<?= $Page->updated_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->updated_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_by->formatPattern()) ?>"<?= $Page->updated_by->editAttributes() ?> aria-describedby="x_updated_by_help">
<?= $Page->updated_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <div id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <label id="elh_fee_schedules_is_active" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_active->caption() ?><?= $Page->is_active->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_active->cellAttributes() ?>>
<span id="el_fee_schedules_is_active">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_active->isInvalidClass() ?>" data-table="fee_schedules" data-field="x_is_active" data-boolean name="x_is_active" id="x_is_active" value="1"<?= ConvertToBool($Page->is_active->CurrentValue) ? " checked" : "" ?><?= $Page->is_active->editAttributes() ?> aria-describedby="x_is_active_help">
    <div class="invalid-feedback"><?= $Page->is_active->getErrorMessage() ?></div>
</div>
<?= $Page->is_active->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description"<?= $Page->description->rowAttributes() ?>>
        <label id="elh_fee_schedules_description" for="x_description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->description->cellAttributes() ?>>
<span id="el_fee_schedules_description">
<textarea data-table="fee_schedules" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help"><?= $Page->description->EditValue ?></textarea>
<?= $Page->description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ffee_schedulesedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ffee_schedulesedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("fee_schedules");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
