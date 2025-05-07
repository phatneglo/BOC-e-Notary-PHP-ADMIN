<?php

namespace PHPMaker2024\eNotary;

// Page object
$VerificationAttemptsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { verification_attempts: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fverification_attemptsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fverification_attemptsdelete")
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
<form name="fverification_attemptsdelete" id="fverification_attemptsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="verification_attempts">
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
<?php if ($Page->attempt_id->Visible) { // attempt_id ?>
        <th class="<?= $Page->attempt_id->headerCellClass() ?>"><span id="elh_verification_attempts_attempt_id" class="verification_attempts_attempt_id"><?= $Page->attempt_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->verification_id->Visible) { // verification_id ?>
        <th class="<?= $Page->verification_id->headerCellClass() ?>"><span id="elh_verification_attempts_verification_id" class="verification_attempts_verification_id"><?= $Page->verification_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
        <th class="<?= $Page->document_number->headerCellClass() ?>"><span id="elh_verification_attempts_document_number" class="verification_attempts_document_number"><?= $Page->document_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->keycode->Visible) { // keycode ?>
        <th class="<?= $Page->keycode->headerCellClass() ?>"><span id="elh_verification_attempts_keycode" class="verification_attempts_keycode"><?= $Page->keycode->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <th class="<?= $Page->ip_address->headerCellClass() ?>"><span id="elh_verification_attempts_ip_address" class="verification_attempts_ip_address"><?= $Page->ip_address->caption() ?></span></th>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
        <th class="<?= $Page->verification_date->headerCellClass() ?>"><span id="elh_verification_attempts_verification_date" class="verification_attempts_verification_date"><?= $Page->verification_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_successful->Visible) { // is_successful ?>
        <th class="<?= $Page->is_successful->headerCellClass() ?>"><span id="elh_verification_attempts_is_successful" class="verification_attempts_is_successful"><?= $Page->is_successful->caption() ?></span></th>
<?php } ?>
<?php if ($Page->location->Visible) { // location ?>
        <th class="<?= $Page->location->headerCellClass() ?>"><span id="elh_verification_attempts_location" class="verification_attempts_location"><?= $Page->location->caption() ?></span></th>
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
<?php if ($Page->attempt_id->Visible) { // attempt_id ?>
        <td<?= $Page->attempt_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->attempt_id->viewAttributes() ?>>
<?= $Page->attempt_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->verification_id->Visible) { // verification_id ?>
        <td<?= $Page->verification_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->verification_id->viewAttributes() ?>>
<?= $Page->verification_id->getViewValue() ?></span>
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
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <td<?= $Page->ip_address->cellAttributes() ?>>
<span id="">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
        <td<?= $Page->verification_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->verification_date->viewAttributes() ?>>
<?= $Page->verification_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_successful->Visible) { // is_successful ?>
        <td<?= $Page->is_successful->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_successful->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_successful->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->location->Visible) { // location ?>
        <td<?= $Page->location->cellAttributes() ?>>
<span id="">
<span<?= $Page->location->viewAttributes() ?>>
<?= $Page->location->getViewValue() ?></span>
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
