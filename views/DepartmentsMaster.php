<?php

namespace PHPMaker2024\eNotary;

// Table
$departments = Container("departments");
$departments->TableClass = "table table-bordered table-hover table-sm ew-table ew-master-table";
?>
<?php if ($departments->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_departmentsmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($departments->department_id->Visible) { // department_id ?>
        <tr id="r_department_id"<?= $departments->department_id->rowAttributes() ?>>
            <td class="<?= $departments->TableLeftColumnClass ?>"><?= $departments->department_id->caption() ?></td>
            <td<?= $departments->department_id->cellAttributes() ?>>
<span id="el_departments_department_id">
<span<?= $departments->department_id->viewAttributes() ?>>
<?= $departments->department_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($departments->department_name->Visible) { // department_name ?>
        <tr id="r_department_name"<?= $departments->department_name->rowAttributes() ?>>
            <td class="<?= $departments->TableLeftColumnClass ?>"><?= $departments->department_name->caption() ?></td>
            <td<?= $departments->department_name->cellAttributes() ?>>
<span id="el_departments_department_name">
<span<?= $departments->department_name->viewAttributes() ?>>
<?= $departments->department_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
