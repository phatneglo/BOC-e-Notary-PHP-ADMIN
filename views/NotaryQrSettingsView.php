<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotaryQrSettingsView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<form name="fnotary_qr_settingsview" id="fnotary_qr_settingsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notary_qr_settings: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fnotary_qr_settingsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotary_qr_settingsview")
        .setPageId("view")
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
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notary_qr_settings">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->settings_id->Visible) { // settings_id ?>
    <tr id="r_settings_id"<?= $Page->settings_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_settings_id"><?= $Page->settings_id->caption() ?></span></td>
        <td data-name="settings_id"<?= $Page->settings_id->cellAttributes() ?>>
<span id="el_notary_qr_settings_settings_id">
<span<?= $Page->settings_id->viewAttributes() ?>>
<?= $Page->settings_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
    <tr id="r_notary_id"<?= $Page->notary_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_notary_id"><?= $Page->notary_id->caption() ?></span></td>
        <td data-name="notary_id"<?= $Page->notary_id->cellAttributes() ?>>
<span id="el_notary_qr_settings_notary_id">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->default_size->Visible) { // default_size ?>
    <tr id="r_default_size"<?= $Page->default_size->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_default_size"><?= $Page->default_size->caption() ?></span></td>
        <td data-name="default_size"<?= $Page->default_size->cellAttributes() ?>>
<span id="el_notary_qr_settings_default_size">
<span<?= $Page->default_size->viewAttributes() ?>>
<?= $Page->default_size->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->foreground_color->Visible) { // foreground_color ?>
    <tr id="r_foreground_color"<?= $Page->foreground_color->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_foreground_color"><?= $Page->foreground_color->caption() ?></span></td>
        <td data-name="foreground_color"<?= $Page->foreground_color->cellAttributes() ?>>
<span id="el_notary_qr_settings_foreground_color">
<span<?= $Page->foreground_color->viewAttributes() ?>>
<?= $Page->foreground_color->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->background_color->Visible) { // background_color ?>
    <tr id="r_background_color"<?= $Page->background_color->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_background_color"><?= $Page->background_color->caption() ?></span></td>
        <td data-name="background_color"<?= $Page->background_color->cellAttributes() ?>>
<span id="el_notary_qr_settings_background_color">
<span<?= $Page->background_color->viewAttributes() ?>>
<?= $Page->background_color->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->logo_path->Visible) { // logo_path ?>
    <tr id="r_logo_path"<?= $Page->logo_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_logo_path"><?= $Page->logo_path->caption() ?></span></td>
        <td data-name="logo_path"<?= $Page->logo_path->cellAttributes() ?>>
<span id="el_notary_qr_settings_logo_path">
<span<?= $Page->logo_path->viewAttributes() ?>>
<?= $Page->logo_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->logo_size_percent->Visible) { // logo_size_percent ?>
    <tr id="r_logo_size_percent"<?= $Page->logo_size_percent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_logo_size_percent"><?= $Page->logo_size_percent->caption() ?></span></td>
        <td data-name="logo_size_percent"<?= $Page->logo_size_percent->cellAttributes() ?>>
<span id="el_notary_qr_settings_logo_size_percent">
<span<?= $Page->logo_size_percent->viewAttributes() ?>>
<?= $Page->logo_size_percent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->error_correction->Visible) { // error_correction ?>
    <tr id="r_error_correction"<?= $Page->error_correction->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_error_correction"><?= $Page->error_correction->caption() ?></span></td>
        <td data-name="error_correction"<?= $Page->error_correction->cellAttributes() ?>>
<span id="el_notary_qr_settings_error_correction">
<span<?= $Page->error_correction->viewAttributes() ?>>
<?= $Page->error_correction->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->corner_radius_percent->Visible) { // corner_radius_percent ?>
    <tr id="r_corner_radius_percent"<?= $Page->corner_radius_percent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_corner_radius_percent"><?= $Page->corner_radius_percent->caption() ?></span></td>
        <td data-name="corner_radius_percent"<?= $Page->corner_radius_percent->cellAttributes() ?>>
<span id="el_notary_qr_settings_corner_radius_percent">
<span<?= $Page->corner_radius_percent->viewAttributes() ?>>
<?= $Page->corner_radius_percent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_notary_qr_settings_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notary_qr_settings_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_notary_qr_settings_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
