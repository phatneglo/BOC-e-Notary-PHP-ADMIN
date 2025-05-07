<?php

namespace PHPMaker2024\eNotary;

// Page object
$VerificationAttemptsView = &$Page;
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
<form name="fverification_attemptsview" id="fverification_attemptsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { verification_attempts: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fverification_attemptsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fverification_attemptsview")
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
<input type="hidden" name="t" value="verification_attempts">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->attempt_id->Visible) { // attempt_id ?>
    <tr id="r_attempt_id"<?= $Page->attempt_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_attempt_id"><?= $Page->attempt_id->caption() ?></span></td>
        <td data-name="attempt_id"<?= $Page->attempt_id->cellAttributes() ?>>
<span id="el_verification_attempts_attempt_id">
<span<?= $Page->attempt_id->viewAttributes() ?>>
<?= $Page->attempt_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->verification_id->Visible) { // verification_id ?>
    <tr id="r_verification_id"<?= $Page->verification_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_verification_id"><?= $Page->verification_id->caption() ?></span></td>
        <td data-name="verification_id"<?= $Page->verification_id->cellAttributes() ?>>
<span id="el_verification_attempts_verification_id">
<span<?= $Page->verification_id->viewAttributes() ?>>
<?= $Page->verification_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
    <tr id="r_document_number"<?= $Page->document_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_document_number"><?= $Page->document_number->caption() ?></span></td>
        <td data-name="document_number"<?= $Page->document_number->cellAttributes() ?>>
<span id="el_verification_attempts_document_number">
<span<?= $Page->document_number->viewAttributes() ?>>
<?= $Page->document_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->keycode->Visible) { // keycode ?>
    <tr id="r_keycode"<?= $Page->keycode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_keycode"><?= $Page->keycode->caption() ?></span></td>
        <td data-name="keycode"<?= $Page->keycode->cellAttributes() ?>>
<span id="el_verification_attempts_keycode">
<span<?= $Page->keycode->viewAttributes() ?>>
<?= $Page->keycode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <tr id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_ip_address"><?= $Page->ip_address->caption() ?></span></td>
        <td data-name="ip_address"<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_verification_attempts_ip_address">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_agent->Visible) { // user_agent ?>
    <tr id="r_user_agent"<?= $Page->user_agent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_user_agent"><?= $Page->user_agent->caption() ?></span></td>
        <td data-name="user_agent"<?= $Page->user_agent->cellAttributes() ?>>
<span id="el_verification_attempts_user_agent">
<span<?= $Page->user_agent->viewAttributes() ?>>
<?= $Page->user_agent->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
    <tr id="r_verification_date"<?= $Page->verification_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_verification_date"><?= $Page->verification_date->caption() ?></span></td>
        <td data-name="verification_date"<?= $Page->verification_date->cellAttributes() ?>>
<span id="el_verification_attempts_verification_date">
<span<?= $Page->verification_date->viewAttributes() ?>>
<?= $Page->verification_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_successful->Visible) { // is_successful ?>
    <tr id="r_is_successful"<?= $Page->is_successful->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_is_successful"><?= $Page->is_successful->caption() ?></span></td>
        <td data-name="is_successful"<?= $Page->is_successful->cellAttributes() ?>>
<span id="el_verification_attempts_is_successful">
<span<?= $Page->is_successful->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_successful->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->failure_reason->Visible) { // failure_reason ?>
    <tr id="r_failure_reason"<?= $Page->failure_reason->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_failure_reason"><?= $Page->failure_reason->caption() ?></span></td>
        <td data-name="failure_reason"<?= $Page->failure_reason->cellAttributes() ?>>
<span id="el_verification_attempts_failure_reason">
<span<?= $Page->failure_reason->viewAttributes() ?>>
<?= $Page->failure_reason->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->location->Visible) { // location ?>
    <tr id="r_location"<?= $Page->location->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_location"><?= $Page->location->caption() ?></span></td>
        <td data-name="location"<?= $Page->location->cellAttributes() ?>>
<span id="el_verification_attempts_location">
<span<?= $Page->location->viewAttributes() ?>>
<?= $Page->location->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->device_info->Visible) { // device_info ?>
    <tr id="r_device_info"<?= $Page->device_info->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_device_info"><?= $Page->device_info->caption() ?></span></td>
        <td data-name="device_info"<?= $Page->device_info->cellAttributes() ?>>
<span id="el_verification_attempts_device_info">
<span<?= $Page->device_info->viewAttributes() ?>>
<?= $Page->device_info->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->browser_info->Visible) { // browser_info ?>
    <tr id="r_browser_info"<?= $Page->browser_info->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_verification_attempts_browser_info"><?= $Page->browser_info->caption() ?></span></td>
        <td data-name="browser_info"<?= $Page->browser_info->cellAttributes() ?>>
<span id="el_verification_attempts_browser_info">
<span<?= $Page->browser_info->viewAttributes() ?>>
<?= $Page->browser_info->getViewValue() ?></span>
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
