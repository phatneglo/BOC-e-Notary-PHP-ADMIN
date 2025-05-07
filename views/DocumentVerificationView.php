<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentVerificationView = &$Page;
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
<form name="fdocument_verificationview" id="fdocument_verificationview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_verification: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fdocument_verificationview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_verificationview")
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
<input type="hidden" name="t" value="document_verification">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->verification_id->Visible) { // verification_id ?>
    <tr id="r_verification_id"<?= $Page->verification_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_verification_id"><?= $Page->verification_id->caption() ?></span></td>
        <td data-name="verification_id"<?= $Page->verification_id->cellAttributes() ?>>
<span id="el_document_verification_verification_id">
<span<?= $Page->verification_id->viewAttributes() ?>>
<?= $Page->verification_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
    <tr id="r_notarized_id"<?= $Page->notarized_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_notarized_id"><?= $Page->notarized_id->caption() ?></span></td>
        <td data-name="notarized_id"<?= $Page->notarized_id->cellAttributes() ?>>
<span id="el_document_verification_notarized_id">
<span<?= $Page->notarized_id->viewAttributes() ?>>
<?= $Page->notarized_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
    <tr id="r_document_number"<?= $Page->document_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_document_number"><?= $Page->document_number->caption() ?></span></td>
        <td data-name="document_number"<?= $Page->document_number->cellAttributes() ?>>
<span id="el_document_verification_document_number">
<span<?= $Page->document_number->viewAttributes() ?>>
<?= $Page->document_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->keycode->Visible) { // keycode ?>
    <tr id="r_keycode"<?= $Page->keycode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_keycode"><?= $Page->keycode->caption() ?></span></td>
        <td data-name="keycode"<?= $Page->keycode->cellAttributes() ?>>
<span id="el_document_verification_keycode">
<span<?= $Page->keycode->viewAttributes() ?>>
<?= $Page->keycode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->verification_url->Visible) { // verification_url ?>
    <tr id="r_verification_url"<?= $Page->verification_url->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_verification_url"><?= $Page->verification_url->caption() ?></span></td>
        <td data-name="verification_url"<?= $Page->verification_url->cellAttributes() ?>>
<span id="el_document_verification_verification_url">
<span<?= $Page->verification_url->viewAttributes() ?>>
<?= $Page->verification_url->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
    <tr id="r_qr_code_path"<?= $Page->qr_code_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_qr_code_path"><?= $Page->qr_code_path->caption() ?></span></td>
        <td data-name="qr_code_path"<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="el_document_verification_qr_code_path">
<span<?= $Page->qr_code_path->viewAttributes() ?>>
<?= $Page->qr_code_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <tr id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_is_active"><?= $Page->is_active->caption() ?></span></td>
        <td data-name="is_active"<?= $Page->is_active->cellAttributes() ?>>
<span id="el_document_verification_is_active">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->expiry_date->Visible) { // expiry_date ?>
    <tr id="r_expiry_date"<?= $Page->expiry_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_expiry_date"><?= $Page->expiry_date->caption() ?></span></td>
        <td data-name="expiry_date"<?= $Page->expiry_date->cellAttributes() ?>>
<span id="el_document_verification_expiry_date">
<span<?= $Page->expiry_date->viewAttributes() ?>>
<?= $Page->expiry_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_document_verification_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->failed_attempts->Visible) { // failed_attempts ?>
    <tr id="r_failed_attempts"<?= $Page->failed_attempts->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_failed_attempts"><?= $Page->failed_attempts->caption() ?></span></td>
        <td data-name="failed_attempts"<?= $Page->failed_attempts->cellAttributes() ?>>
<span id="el_document_verification_failed_attempts">
<span<?= $Page->failed_attempts->viewAttributes() ?>>
<?= $Page->failed_attempts->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blocked_until->Visible) { // blocked_until ?>
    <tr id="r_blocked_until"<?= $Page->blocked_until->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_verification_blocked_until"><?= $Page->blocked_until->caption() ?></span></td>
        <td data-name="blocked_until"<?= $Page->blocked_until->cellAttributes() ?>>
<span id="el_document_verification_blocked_until">
<span<?= $Page->blocked_until->viewAttributes() ?>>
<?= $Page->blocked_until->getViewValue() ?></span>
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
