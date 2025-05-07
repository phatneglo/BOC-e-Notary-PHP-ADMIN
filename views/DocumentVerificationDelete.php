<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentVerificationDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_verification: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fdocument_verificationdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_verificationdelete")
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
<form name="fdocument_verificationdelete" id="fdocument_verificationdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="document_verification">
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
<?php if ($Page->verification_id->Visible) { // verification_id ?>
        <th class="<?= $Page->verification_id->headerCellClass() ?>"><span id="elh_document_verification_verification_id" class="document_verification_verification_id"><?= $Page->verification_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <th class="<?= $Page->notarized_id->headerCellClass() ?>"><span id="elh_document_verification_notarized_id" class="document_verification_notarized_id"><?= $Page->notarized_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
        <th class="<?= $Page->document_number->headerCellClass() ?>"><span id="elh_document_verification_document_number" class="document_verification_document_number"><?= $Page->document_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->keycode->Visible) { // keycode ?>
        <th class="<?= $Page->keycode->headerCellClass() ?>"><span id="elh_document_verification_keycode" class="document_verification_keycode"><?= $Page->keycode->caption() ?></span></th>
<?php } ?>
<?php if ($Page->verification_url->Visible) { // verification_url ?>
        <th class="<?= $Page->verification_url->headerCellClass() ?>"><span id="elh_document_verification_verification_url" class="document_verification_verification_url"><?= $Page->verification_url->caption() ?></span></th>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <th class="<?= $Page->qr_code_path->headerCellClass() ?>"><span id="elh_document_verification_qr_code_path" class="document_verification_qr_code_path"><?= $Page->qr_code_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
        <th class="<?= $Page->is_active->headerCellClass() ?>"><span id="elh_document_verification_is_active" class="document_verification_is_active"><?= $Page->is_active->caption() ?></span></th>
<?php } ?>
<?php if ($Page->expiry_date->Visible) { // expiry_date ?>
        <th class="<?= $Page->expiry_date->headerCellClass() ?>"><span id="elh_document_verification_expiry_date" class="document_verification_expiry_date"><?= $Page->expiry_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_document_verification_created_at" class="document_verification_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->failed_attempts->Visible) { // failed_attempts ?>
        <th class="<?= $Page->failed_attempts->headerCellClass() ?>"><span id="elh_document_verification_failed_attempts" class="document_verification_failed_attempts"><?= $Page->failed_attempts->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blocked_until->Visible) { // blocked_until ?>
        <th class="<?= $Page->blocked_until->headerCellClass() ?>"><span id="elh_document_verification_blocked_until" class="document_verification_blocked_until"><?= $Page->blocked_until->caption() ?></span></th>
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
<?php if ($Page->verification_id->Visible) { // verification_id ?>
        <td<?= $Page->verification_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->verification_id->viewAttributes() ?>>
<?= $Page->verification_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <td<?= $Page->notarized_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->notarized_id->viewAttributes() ?>>
<?= $Page->notarized_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
        <td<?= $Page->document_number->cellAttributes() ?>>
<span id="">
<span<?= $Page->document_number->viewAttributes() ?>>
<?= $Page->document_number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->keycode->Visible) { // keycode ?>
        <td<?= $Page->keycode->cellAttributes() ?>>
<span id="">
<span<?= $Page->keycode->viewAttributes() ?>>
<?= $Page->keycode->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->verification_url->Visible) { // verification_url ?>
        <td<?= $Page->verification_url->cellAttributes() ?>>
<span id="">
<span<?= $Page->verification_url->viewAttributes() ?>>
<?= $Page->verification_url->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <td<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->qr_code_path->viewAttributes() ?>>
<?= $Page->qr_code_path->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
        <td<?= $Page->is_active->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->expiry_date->Visible) { // expiry_date ?>
        <td<?= $Page->expiry_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->expiry_date->viewAttributes() ?>>
<?= $Page->expiry_date->getViewValue() ?></span>
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
<?php if ($Page->failed_attempts->Visible) { // failed_attempts ?>
        <td<?= $Page->failed_attempts->cellAttributes() ?>>
<span id="">
<span<?= $Page->failed_attempts->viewAttributes() ?>>
<?= $Page->failed_attempts->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blocked_until->Visible) { // blocked_until ?>
        <td<?= $Page->blocked_until->cellAttributes() ?>>
<span id="">
<span<?= $Page->blocked_until->viewAttributes() ?>>
<?= $Page->blocked_until->getViewValue() ?></span>
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
