<?php

namespace PHPMaker2024\eNotary;

// Page object
$UsersView = &$Page;
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
<form name="fusersview" id="fusersview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { users: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fusersview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fusersview")
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
<input type="hidden" name="t" value="users">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_users_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->department_id->Visible) { // department_id ?>
    <tr id="r_department_id"<?= $Page->department_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_department_id"><?= $Page->department_id->caption() ?></span></td>
        <td data-name="department_id"<?= $Page->department_id->cellAttributes() ?>>
<span id="el_users_department_id">
<span<?= $Page->department_id->viewAttributes() ?>>
<?= $Page->department_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el_users__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
    <tr id="r__email"<?= $Page->_email->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users__email"><?= $Page->_email->caption() ?></span></td>
        <td data-name="_email"<?= $Page->_email->cellAttributes() ?>>
<span id="el_users__email">
<span<?= $Page->_email->viewAttributes() ?>>
<?= $Page->_email->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->password_hash->Visible) { // password_hash ?>
    <tr id="r_password_hash"<?= $Page->password_hash->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_password_hash"><?= $Page->password_hash->caption() ?></span></td>
        <td data-name="password_hash"<?= $Page->password_hash->cellAttributes() ?>>
<span id="el_users_password_hash">
<span<?= $Page->password_hash->viewAttributes() ?>>
<?= $Page->password_hash->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mobile_number->Visible) { // mobile_number ?>
    <tr id="r_mobile_number"<?= $Page->mobile_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_mobile_number"><?= $Page->mobile_number->caption() ?></span></td>
        <td data-name="mobile_number"<?= $Page->mobile_number->cellAttributes() ?>>
<span id="el_users_mobile_number">
<span<?= $Page->mobile_number->viewAttributes() ?>>
<?= $Page->mobile_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->first_name->Visible) { // first_name ?>
    <tr id="r_first_name"<?= $Page->first_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_first_name"><?= $Page->first_name->caption() ?></span></td>
        <td data-name="first_name"<?= $Page->first_name->cellAttributes() ?>>
<span id="el_users_first_name">
<span<?= $Page->first_name->viewAttributes() ?>>
<?= $Page->first_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->middle_name->Visible) { // middle_name ?>
    <tr id="r_middle_name"<?= $Page->middle_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_middle_name"><?= $Page->middle_name->caption() ?></span></td>
        <td data-name="middle_name"<?= $Page->middle_name->cellAttributes() ?>>
<span id="el_users_middle_name">
<span<?= $Page->middle_name->viewAttributes() ?>>
<?= $Page->middle_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->last_name->Visible) { // last_name ?>
    <tr id="r_last_name"<?= $Page->last_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_last_name"><?= $Page->last_name->caption() ?></span></td>
        <td data-name="last_name"<?= $Page->last_name->cellAttributes() ?>>
<span id="el_users_last_name">
<span<?= $Page->last_name->viewAttributes() ?>>
<?= $Page->last_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->date_created->Visible) { // date_created ?>
    <tr id="r_date_created"<?= $Page->date_created->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_date_created"><?= $Page->date_created->caption() ?></span></td>
        <td data-name="date_created"<?= $Page->date_created->cellAttributes() ?>>
<span id="el_users_date_created">
<span<?= $Page->date_created->viewAttributes() ?>>
<?= $Page->date_created->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->last_login->Visible) { // last_login ?>
    <tr id="r_last_login"<?= $Page->last_login->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_last_login"><?= $Page->last_login->caption() ?></span></td>
        <td data-name="last_login"<?= $Page->last_login->cellAttributes() ?>>
<span id="el_users_last_login">
<span<?= $Page->last_login->viewAttributes() ?>>
<?= $Page->last_login->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <tr id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_is_active"><?= $Page->is_active->caption() ?></span></td>
        <td data-name="is_active"<?= $Page->is_active->cellAttributes() ?>>
<span id="el_users_is_active">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_level_id->Visible) { // user_level_id ?>
    <tr id="r_user_level_id"<?= $Page->user_level_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_user_level_id"><?= $Page->user_level_id->caption() ?></span></td>
        <td data-name="user_level_id"<?= $Page->user_level_id->cellAttributes() ?>>
<span id="el_users_user_level_id">
<span<?= $Page->user_level_id->viewAttributes() ?>>
<?= $Page->user_level_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reports_to_user_id->Visible) { // reports_to_user_id ?>
    <tr id="r_reports_to_user_id"<?= $Page->reports_to_user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_reports_to_user_id"><?= $Page->reports_to_user_id->caption() ?></span></td>
        <td data-name="reports_to_user_id"<?= $Page->reports_to_user_id->cellAttributes() ?>>
<span id="el_users_reports_to_user_id">
<span<?= $Page->reports_to_user_id->viewAttributes() ?>>
<?= $Page->reports_to_user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->photo->Visible) { // photo ?>
    <tr id="r_photo"<?= $Page->photo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_photo"><?= $Page->photo->caption() ?></span></td>
        <td data-name="photo"<?= $Page->photo->cellAttributes() ?>>
<span id="el_users_photo">
<span<?= $Page->photo->viewAttributes() ?>>
<?= GetFileViewTag($Page->photo, $Page->photo->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_notary->Visible) { // is_notary ?>
    <tr id="r_is_notary"<?= $Page->is_notary->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_is_notary"><?= $Page->is_notary->caption() ?></span></td>
        <td data-name="is_notary"<?= $Page->is_notary->cellAttributes() ?>>
<span id="el_users_is_notary">
<span<?= $Page->is_notary->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_notary->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notary_commission_number->Visible) { // notary_commission_number ?>
    <tr id="r_notary_commission_number"<?= $Page->notary_commission_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_notary_commission_number"><?= $Page->notary_commission_number->caption() ?></span></td>
        <td data-name="notary_commission_number"<?= $Page->notary_commission_number->cellAttributes() ?>>
<span id="el_users_notary_commission_number">
<span<?= $Page->notary_commission_number->viewAttributes() ?>>
<?= $Page->notary_commission_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notary_commission_expiry->Visible) { // notary_commission_expiry ?>
    <tr id="r_notary_commission_expiry"<?= $Page->notary_commission_expiry->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_notary_commission_expiry"><?= $Page->notary_commission_expiry->caption() ?></span></td>
        <td data-name="notary_commission_expiry"<?= $Page->notary_commission_expiry->cellAttributes() ?>>
<span id="el_users_notary_commission_expiry">
<span<?= $Page->notary_commission_expiry->viewAttributes() ?>>
<?= $Page->notary_commission_expiry->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->digital_signature->Visible) { // digital_signature ?>
    <tr id="r_digital_signature"<?= $Page->digital_signature->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_digital_signature"><?= $Page->digital_signature->caption() ?></span></td>
        <td data-name="digital_signature"<?= $Page->digital_signature->cellAttributes() ?>>
<span id="el_users_digital_signature">
<span<?= $Page->digital_signature->viewAttributes() ?>>
<?= $Page->digital_signature->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <tr id="r_address"<?= $Page->address->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_address"><?= $Page->address->caption() ?></span></td>
        <td data-name="address"<?= $Page->address->cellAttributes() ?>>
<span id="el_users_address">
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->government_id_type->Visible) { // government_id_type ?>
    <tr id="r_government_id_type"<?= $Page->government_id_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_government_id_type"><?= $Page->government_id_type->caption() ?></span></td>
        <td data-name="government_id_type"<?= $Page->government_id_type->cellAttributes() ?>>
<span id="el_users_government_id_type">
<span<?= $Page->government_id_type->viewAttributes() ?>>
<?= $Page->government_id_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->government_id_number->Visible) { // government_id_number ?>
    <tr id="r_government_id_number"<?= $Page->government_id_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_government_id_number"><?= $Page->government_id_number->caption() ?></span></td>
        <td data-name="government_id_number"<?= $Page->government_id_number->cellAttributes() ?>>
<span id="el_users_government_id_number">
<span<?= $Page->government_id_number->viewAttributes() ?>>
<?= $Page->government_id_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->privacy_agreement_accepted->Visible) { // privacy_agreement_accepted ?>
    <tr id="r_privacy_agreement_accepted"<?= $Page->privacy_agreement_accepted->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_privacy_agreement_accepted"><?= $Page->privacy_agreement_accepted->caption() ?></span></td>
        <td data-name="privacy_agreement_accepted"<?= $Page->privacy_agreement_accepted->cellAttributes() ?>>
<span id="el_users_privacy_agreement_accepted">
<span<?= $Page->privacy_agreement_accepted->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->privacy_agreement_accepted->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->government_id_path->Visible) { // government_id_path ?>
    <tr id="r_government_id_path"<?= $Page->government_id_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_users_government_id_path"><?= $Page->government_id_path->caption() ?></span></td>
        <td data-name="government_id_path"<?= $Page->government_id_path->cellAttributes() ?>>
<span id="el_users_government_id_path">
<span<?= $Page->government_id_path->viewAttributes() ?>>
<?= $Page->government_id_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("user_level_assignments", explode(",", $Page->getCurrentDetailTable())) && $user_level_assignments->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("user_level_assignments", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "UserLevelAssignmentsGrid.php" ?>
<?php } ?>
<?php
    if (in_array("aggregated_audit_logs", explode(",", $Page->getCurrentDetailTable())) && $aggregated_audit_logs->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("aggregated_audit_logs", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AggregatedAuditLogsGrid.php" ?>
<?php } ?>
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
