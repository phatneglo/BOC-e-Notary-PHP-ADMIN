<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotaryQrSettingsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fnotary_qr_settingsedit" id="fnotary_qr_settingsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notary_qr_settings: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fnotary_qr_settingsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotary_qr_settingsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["settings_id", [fields.settings_id.visible && fields.settings_id.required ? ew.Validators.required(fields.settings_id.caption) : null], fields.settings_id.isInvalid],
            ["notary_id", [fields.notary_id.visible && fields.notary_id.required ? ew.Validators.required(fields.notary_id.caption) : null, ew.Validators.integer], fields.notary_id.isInvalid],
            ["default_size", [fields.default_size.visible && fields.default_size.required ? ew.Validators.required(fields.default_size.caption) : null, ew.Validators.integer], fields.default_size.isInvalid],
            ["foreground_color", [fields.foreground_color.visible && fields.foreground_color.required ? ew.Validators.required(fields.foreground_color.caption) : null], fields.foreground_color.isInvalid],
            ["background_color", [fields.background_color.visible && fields.background_color.required ? ew.Validators.required(fields.background_color.caption) : null], fields.background_color.isInvalid],
            ["logo_path", [fields.logo_path.visible && fields.logo_path.required ? ew.Validators.required(fields.logo_path.caption) : null], fields.logo_path.isInvalid],
            ["logo_size_percent", [fields.logo_size_percent.visible && fields.logo_size_percent.required ? ew.Validators.required(fields.logo_size_percent.caption) : null, ew.Validators.integer], fields.logo_size_percent.isInvalid],
            ["error_correction", [fields.error_correction.visible && fields.error_correction.required ? ew.Validators.required(fields.error_correction.caption) : null], fields.error_correction.isInvalid],
            ["corner_radius_percent", [fields.corner_radius_percent.visible && fields.corner_radius_percent.required ? ew.Validators.required(fields.corner_radius_percent.caption) : null, ew.Validators.integer], fields.corner_radius_percent.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid]
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
<input type="hidden" name="t" value="notary_qr_settings">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->settings_id->Visible) { // settings_id ?>
    <div id="r_settings_id"<?= $Page->settings_id->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_settings_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->settings_id->caption() ?><?= $Page->settings_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->settings_id->cellAttributes() ?>>
<span id="el_notary_qr_settings_settings_id">
<span<?= $Page->settings_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->settings_id->getDisplayValue($Page->settings_id->EditValue))) ?>"></span>
<input type="hidden" data-table="notary_qr_settings" data-field="x_settings_id" data-hidden="1" name="x_settings_id" id="x_settings_id" value="<?= HtmlEncode($Page->settings_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
    <div id="r_notary_id"<?= $Page->notary_id->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_notary_id" for="x_notary_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notary_id->caption() ?><?= $Page->notary_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notary_id->cellAttributes() ?>>
<span id="el_notary_qr_settings_notary_id">
<input type="<?= $Page->notary_id->getInputTextType() ?>" name="x_notary_id" id="x_notary_id" data-table="notary_qr_settings" data-field="x_notary_id" value="<?= $Page->notary_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->notary_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->notary_id->formatPattern()) ?>"<?= $Page->notary_id->editAttributes() ?> aria-describedby="x_notary_id_help">
<?= $Page->notary_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notary_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->default_size->Visible) { // default_size ?>
    <div id="r_default_size"<?= $Page->default_size->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_default_size" for="x_default_size" class="<?= $Page->LeftColumnClass ?>"><?= $Page->default_size->caption() ?><?= $Page->default_size->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->default_size->cellAttributes() ?>>
<span id="el_notary_qr_settings_default_size">
<input type="<?= $Page->default_size->getInputTextType() ?>" name="x_default_size" id="x_default_size" data-table="notary_qr_settings" data-field="x_default_size" value="<?= $Page->default_size->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->default_size->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->default_size->formatPattern()) ?>"<?= $Page->default_size->editAttributes() ?> aria-describedby="x_default_size_help">
<?= $Page->default_size->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->default_size->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->foreground_color->Visible) { // foreground_color ?>
    <div id="r_foreground_color"<?= $Page->foreground_color->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_foreground_color" for="x_foreground_color" class="<?= $Page->LeftColumnClass ?>"><?= $Page->foreground_color->caption() ?><?= $Page->foreground_color->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->foreground_color->cellAttributes() ?>>
<span id="el_notary_qr_settings_foreground_color">
<input type="<?= $Page->foreground_color->getInputTextType() ?>" name="x_foreground_color" id="x_foreground_color" data-table="notary_qr_settings" data-field="x_foreground_color" value="<?= $Page->foreground_color->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->foreground_color->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->foreground_color->formatPattern()) ?>"<?= $Page->foreground_color->editAttributes() ?> aria-describedby="x_foreground_color_help">
<?= $Page->foreground_color->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->foreground_color->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->background_color->Visible) { // background_color ?>
    <div id="r_background_color"<?= $Page->background_color->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_background_color" for="x_background_color" class="<?= $Page->LeftColumnClass ?>"><?= $Page->background_color->caption() ?><?= $Page->background_color->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->background_color->cellAttributes() ?>>
<span id="el_notary_qr_settings_background_color">
<input type="<?= $Page->background_color->getInputTextType() ?>" name="x_background_color" id="x_background_color" data-table="notary_qr_settings" data-field="x_background_color" value="<?= $Page->background_color->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->background_color->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->background_color->formatPattern()) ?>"<?= $Page->background_color->editAttributes() ?> aria-describedby="x_background_color_help">
<?= $Page->background_color->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->background_color->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->logo_path->Visible) { // logo_path ?>
    <div id="r_logo_path"<?= $Page->logo_path->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_logo_path" for="x_logo_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->logo_path->caption() ?><?= $Page->logo_path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->logo_path->cellAttributes() ?>>
<span id="el_notary_qr_settings_logo_path">
<input type="<?= $Page->logo_path->getInputTextType() ?>" name="x_logo_path" id="x_logo_path" data-table="notary_qr_settings" data-field="x_logo_path" value="<?= $Page->logo_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->logo_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->logo_path->formatPattern()) ?>"<?= $Page->logo_path->editAttributes() ?> aria-describedby="x_logo_path_help">
<?= $Page->logo_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->logo_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->logo_size_percent->Visible) { // logo_size_percent ?>
    <div id="r_logo_size_percent"<?= $Page->logo_size_percent->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_logo_size_percent" for="x_logo_size_percent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->logo_size_percent->caption() ?><?= $Page->logo_size_percent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->logo_size_percent->cellAttributes() ?>>
<span id="el_notary_qr_settings_logo_size_percent">
<input type="<?= $Page->logo_size_percent->getInputTextType() ?>" name="x_logo_size_percent" id="x_logo_size_percent" data-table="notary_qr_settings" data-field="x_logo_size_percent" value="<?= $Page->logo_size_percent->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->logo_size_percent->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->logo_size_percent->formatPattern()) ?>"<?= $Page->logo_size_percent->editAttributes() ?> aria-describedby="x_logo_size_percent_help">
<?= $Page->logo_size_percent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->logo_size_percent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->error_correction->Visible) { // error_correction ?>
    <div id="r_error_correction"<?= $Page->error_correction->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_error_correction" for="x_error_correction" class="<?= $Page->LeftColumnClass ?>"><?= $Page->error_correction->caption() ?><?= $Page->error_correction->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->error_correction->cellAttributes() ?>>
<span id="el_notary_qr_settings_error_correction">
<input type="<?= $Page->error_correction->getInputTextType() ?>" name="x_error_correction" id="x_error_correction" data-table="notary_qr_settings" data-field="x_error_correction" value="<?= $Page->error_correction->EditValue ?>" size="30" maxlength="5" placeholder="<?= HtmlEncode($Page->error_correction->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->error_correction->formatPattern()) ?>"<?= $Page->error_correction->editAttributes() ?> aria-describedby="x_error_correction_help">
<?= $Page->error_correction->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->error_correction->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->corner_radius_percent->Visible) { // corner_radius_percent ?>
    <div id="r_corner_radius_percent"<?= $Page->corner_radius_percent->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_corner_radius_percent" for="x_corner_radius_percent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->corner_radius_percent->caption() ?><?= $Page->corner_radius_percent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->corner_radius_percent->cellAttributes() ?>>
<span id="el_notary_qr_settings_corner_radius_percent">
<input type="<?= $Page->corner_radius_percent->getInputTextType() ?>" name="x_corner_radius_percent" id="x_corner_radius_percent" data-table="notary_qr_settings" data-field="x_corner_radius_percent" value="<?= $Page->corner_radius_percent->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->corner_radius_percent->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->corner_radius_percent->formatPattern()) ?>"<?= $Page->corner_radius_percent->editAttributes() ?> aria-describedby="x_corner_radius_percent_help">
<?= $Page->corner_radius_percent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->corner_radius_percent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_notary_qr_settings_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="notary_qr_settings" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotary_qr_settingsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotary_qr_settingsedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_notary_qr_settings_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_notary_qr_settings_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="notary_qr_settings" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnotary_qr_settingsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnotary_qr_settingsedit", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fnotary_qr_settingsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fnotary_qr_settingsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("notary_qr_settings");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
