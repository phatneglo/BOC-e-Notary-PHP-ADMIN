<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotaryQrSettingsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notary_qr_settings: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fnotary_qr_settingsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotary_qr_settingsdelete")
        .setPageId("delete")
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
<form name="fnotary_qr_settingsdelete" id="fnotary_qr_settingsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notary_qr_settings">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->settings_id->Visible) { // settings_id ?>
        <th class="<?= $Page->settings_id->headerCellClass() ?>"><span id="elh_notary_qr_settings_settings_id" class="notary_qr_settings_settings_id"><?= $Page->settings_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <th class="<?= $Page->notary_id->headerCellClass() ?>"><span id="elh_notary_qr_settings_notary_id" class="notary_qr_settings_notary_id"><?= $Page->notary_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->default_size->Visible) { // default_size ?>
        <th class="<?= $Page->default_size->headerCellClass() ?>"><span id="elh_notary_qr_settings_default_size" class="notary_qr_settings_default_size"><?= $Page->default_size->caption() ?></span></th>
<?php } ?>
<?php if ($Page->foreground_color->Visible) { // foreground_color ?>
        <th class="<?= $Page->foreground_color->headerCellClass() ?>"><span id="elh_notary_qr_settings_foreground_color" class="notary_qr_settings_foreground_color"><?= $Page->foreground_color->caption() ?></span></th>
<?php } ?>
<?php if ($Page->background_color->Visible) { // background_color ?>
        <th class="<?= $Page->background_color->headerCellClass() ?>"><span id="elh_notary_qr_settings_background_color" class="notary_qr_settings_background_color"><?= $Page->background_color->caption() ?></span></th>
<?php } ?>
<?php if ($Page->logo_path->Visible) { // logo_path ?>
        <th class="<?= $Page->logo_path->headerCellClass() ?>"><span id="elh_notary_qr_settings_logo_path" class="notary_qr_settings_logo_path"><?= $Page->logo_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->logo_size_percent->Visible) { // logo_size_percent ?>
        <th class="<?= $Page->logo_size_percent->headerCellClass() ?>"><span id="elh_notary_qr_settings_logo_size_percent" class="notary_qr_settings_logo_size_percent"><?= $Page->logo_size_percent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->error_correction->Visible) { // error_correction ?>
        <th class="<?= $Page->error_correction->headerCellClass() ?>"><span id="elh_notary_qr_settings_error_correction" class="notary_qr_settings_error_correction"><?= $Page->error_correction->caption() ?></span></th>
<?php } ?>
<?php if ($Page->corner_radius_percent->Visible) { // corner_radius_percent ?>
        <th class="<?= $Page->corner_radius_percent->headerCellClass() ?>"><span id="elh_notary_qr_settings_corner_radius_percent" class="notary_qr_settings_corner_radius_percent"><?= $Page->corner_radius_percent->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_notary_qr_settings_created_at" class="notary_qr_settings_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_notary_qr_settings_updated_at" class="notary_qr_settings_updated_at"><?= $Page->updated_at->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while ($Page->fetch()) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = RowType::VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->CurrentRow);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->settings_id->Visible) { // settings_id ?>
        <td<?= $Page->settings_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->settings_id->viewAttributes() ?>>
<?= $Page->settings_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <td<?= $Page->notary_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->default_size->Visible) { // default_size ?>
        <td<?= $Page->default_size->cellAttributes() ?>>
<span id="">
<span<?= $Page->default_size->viewAttributes() ?>>
<?= $Page->default_size->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->foreground_color->Visible) { // foreground_color ?>
        <td<?= $Page->foreground_color->cellAttributes() ?>>
<span id="">
<span<?= $Page->foreground_color->viewAttributes() ?>>
<?= $Page->foreground_color->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->background_color->Visible) { // background_color ?>
        <td<?= $Page->background_color->cellAttributes() ?>>
<span id="">
<span<?= $Page->background_color->viewAttributes() ?>>
<?= $Page->background_color->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->logo_path->Visible) { // logo_path ?>
        <td<?= $Page->logo_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->logo_path->viewAttributes() ?>>
<?= $Page->logo_path->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->logo_size_percent->Visible) { // logo_size_percent ?>
        <td<?= $Page->logo_size_percent->cellAttributes() ?>>
<span id="">
<span<?= $Page->logo_size_percent->viewAttributes() ?>>
<?= $Page->logo_size_percent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->error_correction->Visible) { // error_correction ?>
        <td<?= $Page->error_correction->cellAttributes() ?>>
<span id="">
<span<?= $Page->error_correction->viewAttributes() ?>>
<?= $Page->error_correction->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->corner_radius_percent->Visible) { // corner_radius_percent ?>
        <td<?= $Page->corner_radius_percent->cellAttributes() ?>>
<span id="">
<span<?= $Page->corner_radius_percent->viewAttributes() ?>>
<?= $Page->corner_radius_percent->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <td<?= $Page->created_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td<?= $Page->updated_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
}
$Page->Recordset?->free();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
