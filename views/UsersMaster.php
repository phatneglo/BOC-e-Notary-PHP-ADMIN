<?php

namespace PHPMaker2024\eNotary;

// Table
$users = Container("users");
$users->TableClass = "table table-bordered table-hover table-sm ew-table ew-master-table";
?>
<?php if ($users->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_usersmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($users->user_id->Visible) { // user_id ?>
        <tr id="r_user_id"<?= $users->user_id->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->user_id->caption() ?></td>
            <td<?= $users->user_id->cellAttributes() ?>>
<span id="el_users_user_id">
<span<?= $users->user_id->viewAttributes() ?>>
<?= $users->user_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->department_id->Visible) { // department_id ?>
        <tr id="r_department_id"<?= $users->department_id->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->department_id->caption() ?></td>
            <td<?= $users->department_id->cellAttributes() ?>>
<span id="el_users_department_id">
<span<?= $users->department_id->viewAttributes() ?>>
<?= $users->department_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->_username->Visible) { // username ?>
        <tr id="r__username"<?= $users->_username->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->_username->caption() ?></td>
            <td<?= $users->_username->cellAttributes() ?>>
<span id="el_users__username">
<span<?= $users->_username->viewAttributes() ?>>
<?= $users->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
        <tr id="r__email"<?= $users->_email->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->_email->caption() ?></td>
            <td<?= $users->_email->cellAttributes() ?>>
<span id="el_users__email">
<span<?= $users->_email->viewAttributes() ?>>
<?= $users->_email->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->first_name->Visible) { // first_name ?>
        <tr id="r_first_name"<?= $users->first_name->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->first_name->caption() ?></td>
            <td<?= $users->first_name->cellAttributes() ?>>
<span id="el_users_first_name">
<span<?= $users->first_name->viewAttributes() ?>>
<?= $users->first_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->last_name->Visible) { // last_name ?>
        <tr id="r_last_name"<?= $users->last_name->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->last_name->caption() ?></td>
            <td<?= $users->last_name->cellAttributes() ?>>
<span id="el_users_last_name">
<span<?= $users->last_name->viewAttributes() ?>>
<?= $users->last_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->is_active->Visible) { // is_active ?>
        <tr id="r_is_active"<?= $users->is_active->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->is_active->caption() ?></td>
            <td<?= $users->is_active->cellAttributes() ?>>
<span id="el_users_is_active">
<span<?= $users->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($users->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->user_level_id->Visible) { // user_level_id ?>
        <tr id="r_user_level_id"<?= $users->user_level_id->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->user_level_id->caption() ?></td>
            <td<?= $users->user_level_id->cellAttributes() ?>>
<span id="el_users_user_level_id">
<span<?= $users->user_level_id->viewAttributes() ?>>
<?= $users->user_level_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->is_notary->Visible) { // is_notary ?>
        <tr id="r_is_notary"<?= $users->is_notary->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->is_notary->caption() ?></td>
            <td<?= $users->is_notary->cellAttributes() ?>>
<span id="el_users_is_notary">
<span<?= $users->is_notary->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($users->is_notary->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->notary_commission_number->Visible) { // notary_commission_number ?>
        <tr id="r_notary_commission_number"<?= $users->notary_commission_number->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->notary_commission_number->caption() ?></td>
            <td<?= $users->notary_commission_number->cellAttributes() ?>>
<span id="el_users_notary_commission_number">
<span<?= $users->notary_commission_number->viewAttributes() ?>>
<?= $users->notary_commission_number->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->notary_commission_expiry->Visible) { // notary_commission_expiry ?>
        <tr id="r_notary_commission_expiry"<?= $users->notary_commission_expiry->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->notary_commission_expiry->caption() ?></td>
            <td<?= $users->notary_commission_expiry->cellAttributes() ?>>
<span id="el_users_notary_commission_expiry">
<span<?= $users->notary_commission_expiry->viewAttributes() ?>>
<?= $users->notary_commission_expiry->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->government_id_type->Visible) { // government_id_type ?>
        <tr id="r_government_id_type"<?= $users->government_id_type->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->government_id_type->caption() ?></td>
            <td<?= $users->government_id_type->cellAttributes() ?>>
<span id="el_users_government_id_type">
<span<?= $users->government_id_type->viewAttributes() ?>>
<?= $users->government_id_type->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->government_id_number->Visible) { // government_id_number ?>
        <tr id="r_government_id_number"<?= $users->government_id_number->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->government_id_number->caption() ?></td>
            <td<?= $users->government_id_number->cellAttributes() ?>>
<span id="el_users_government_id_number">
<span<?= $users->government_id_number->viewAttributes() ?>>
<?= $users->government_id_number->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->privacy_agreement_accepted->Visible) { // privacy_agreement_accepted ?>
        <tr id="r_privacy_agreement_accepted"<?= $users->privacy_agreement_accepted->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->privacy_agreement_accepted->caption() ?></td>
            <td<?= $users->privacy_agreement_accepted->cellAttributes() ?>>
<span id="el_users_privacy_agreement_accepted">
<span<?= $users->privacy_agreement_accepted->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($users->privacy_agreement_accepted->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($users->government_id_path->Visible) { // government_id_path ?>
        <tr id="r_government_id_path"<?= $users->government_id_path->rowAttributes() ?>>
            <td class="<?= $users->TableLeftColumnClass ?>"><?= $users->government_id_path->caption() ?></td>
            <td<?= $users->government_id_path->cellAttributes() ?>>
<span id="el_users_government_id_path">
<span<?= $users->government_id_path->viewAttributes() ?>>
<?= $users->government_id_path->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
