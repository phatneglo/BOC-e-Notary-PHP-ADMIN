<?php

namespace PHPMaker2024\eNotary;

// Set up and run Grid object
$Grid = Container("UsersGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fusersgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { users: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fusersgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null], fields.user_id.isInvalid],
            ["department_id", [fields.department_id.visible && fields.department_id.required ? ew.Validators.required(fields.department_id.caption) : null], fields.department_id.isInvalid],
            ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
            ["_email", [fields._email.visible && fields._email.required ? ew.Validators.required(fields._email.caption) : null], fields._email.isInvalid],
            ["first_name", [fields.first_name.visible && fields.first_name.required ? ew.Validators.required(fields.first_name.caption) : null], fields.first_name.isInvalid],
            ["last_name", [fields.last_name.visible && fields.last_name.required ? ew.Validators.required(fields.last_name.caption) : null], fields.last_name.isInvalid],
            ["is_active", [fields.is_active.visible && fields.is_active.required ? ew.Validators.required(fields.is_active.caption) : null], fields.is_active.isInvalid],
            ["user_level_id", [fields.user_level_id.visible && fields.user_level_id.required ? ew.Validators.required(fields.user_level_id.caption) : null], fields.user_level_id.isInvalid],
            ["is_notary", [fields.is_notary.visible && fields.is_notary.required ? ew.Validators.required(fields.is_notary.caption) : null], fields.is_notary.isInvalid],
            ["notary_commission_number", [fields.notary_commission_number.visible && fields.notary_commission_number.required ? ew.Validators.required(fields.notary_commission_number.caption) : null], fields.notary_commission_number.isInvalid],
            ["notary_commission_expiry", [fields.notary_commission_expiry.visible && fields.notary_commission_expiry.required ? ew.Validators.required(fields.notary_commission_expiry.caption) : null, ew.Validators.datetime(fields.notary_commission_expiry.clientFormatPattern)], fields.notary_commission_expiry.isInvalid],
            ["government_id_type", [fields.government_id_type.visible && fields.government_id_type.required ? ew.Validators.required(fields.government_id_type.caption) : null], fields.government_id_type.isInvalid],
            ["government_id_number", [fields.government_id_number.visible && fields.government_id_number.required ? ew.Validators.required(fields.government_id_number.caption) : null], fields.government_id_number.isInvalid],
            ["privacy_agreement_accepted", [fields.privacy_agreement_accepted.visible && fields.privacy_agreement_accepted.required ? ew.Validators.required(fields.privacy_agreement_accepted.caption) : null], fields.privacy_agreement_accepted.isInvalid],
            ["government_id_path", [fields.government_id_path.visible && fields.government_id_path.required ? ew.Validators.required(fields.government_id_path.caption) : null], fields.government_id_path.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["department_id",false],["_username",false],["_email",false],["first_name",false],["last_name",false],["is_active",true],["user_level_id[]",false],["is_notary",true],["notary_commission_number",false],["notary_commission_expiry",false],["government_id_type",false],["government_id_number",false],["privacy_agreement_accepted",true],["government_id_path",false]];
                if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
                    return false;
                return true;
            }
        )

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
            "department_id": <?= $Grid->department_id->toClientList($Grid) ?>,
            "is_active": <?= $Grid->is_active->toClientList($Grid) ?>,
            "user_level_id": <?= $Grid->user_level_id->toClientList($Grid) ?>,
            "is_notary": <?= $Grid->is_notary->toClientList($Grid) ?>,
            "privacy_agreement_accepted": <?= $Grid->privacy_agreement_accepted->toClientList($Grid) ?>,
        })
        .build();
    window[form.id] = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<main class="list">
<div id="ew-header-options">
<?php $Grid->HeaderOptions?->render("body") ?>
</div>
<div id="ew-list">
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Grid->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Grid->TableGridClass ?>">
<div id="fusersgrid" class="ew-form ew-list-form">
<div id="gmp_users" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_usersgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = RowType::HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->user_id->Visible) { // user_id ?>
        <th data-name="user_id" class="<?= $Grid->user_id->headerCellClass() ?>"><div id="elh_users_user_id" class="users_user_id"><?= $Grid->renderFieldHeader($Grid->user_id) ?></div></th>
<?php } ?>
<?php if ($Grid->department_id->Visible) { // department_id ?>
        <th data-name="department_id" class="<?= $Grid->department_id->headerCellClass() ?>"><div id="elh_users_department_id" class="users_department_id"><?= $Grid->renderFieldHeader($Grid->department_id) ?></div></th>
<?php } ?>
<?php if ($Grid->_username->Visible) { // username ?>
        <th data-name="_username" class="<?= $Grid->_username->headerCellClass() ?>"><div id="elh_users__username" class="users__username"><?= $Grid->renderFieldHeader($Grid->_username) ?></div></th>
<?php } ?>
<?php if ($Grid->_email->Visible) { // email ?>
        <th data-name="_email" class="<?= $Grid->_email->headerCellClass() ?>"><div id="elh_users__email" class="users__email"><?= $Grid->renderFieldHeader($Grid->_email) ?></div></th>
<?php } ?>
<?php if ($Grid->first_name->Visible) { // first_name ?>
        <th data-name="first_name" class="<?= $Grid->first_name->headerCellClass() ?>"><div id="elh_users_first_name" class="users_first_name"><?= $Grid->renderFieldHeader($Grid->first_name) ?></div></th>
<?php } ?>
<?php if ($Grid->last_name->Visible) { // last_name ?>
        <th data-name="last_name" class="<?= $Grid->last_name->headerCellClass() ?>"><div id="elh_users_last_name" class="users_last_name"><?= $Grid->renderFieldHeader($Grid->last_name) ?></div></th>
<?php } ?>
<?php if ($Grid->is_active->Visible) { // is_active ?>
        <th data-name="is_active" class="<?= $Grid->is_active->headerCellClass() ?>"><div id="elh_users_is_active" class="users_is_active"><?= $Grid->renderFieldHeader($Grid->is_active) ?></div></th>
<?php } ?>
<?php if ($Grid->user_level_id->Visible) { // user_level_id ?>
        <th data-name="user_level_id" class="<?= $Grid->user_level_id->headerCellClass() ?>"><div id="elh_users_user_level_id" class="users_user_level_id"><?= $Grid->renderFieldHeader($Grid->user_level_id) ?></div></th>
<?php } ?>
<?php if ($Grid->is_notary->Visible) { // is_notary ?>
        <th data-name="is_notary" class="<?= $Grid->is_notary->headerCellClass() ?>"><div id="elh_users_is_notary" class="users_is_notary"><?= $Grid->renderFieldHeader($Grid->is_notary) ?></div></th>
<?php } ?>
<?php if ($Grid->notary_commission_number->Visible) { // notary_commission_number ?>
        <th data-name="notary_commission_number" class="<?= $Grid->notary_commission_number->headerCellClass() ?>"><div id="elh_users_notary_commission_number" class="users_notary_commission_number"><?= $Grid->renderFieldHeader($Grid->notary_commission_number) ?></div></th>
<?php } ?>
<?php if ($Grid->notary_commission_expiry->Visible) { // notary_commission_expiry ?>
        <th data-name="notary_commission_expiry" class="<?= $Grid->notary_commission_expiry->headerCellClass() ?>"><div id="elh_users_notary_commission_expiry" class="users_notary_commission_expiry"><?= $Grid->renderFieldHeader($Grid->notary_commission_expiry) ?></div></th>
<?php } ?>
<?php if ($Grid->government_id_type->Visible) { // government_id_type ?>
        <th data-name="government_id_type" class="<?= $Grid->government_id_type->headerCellClass() ?>"><div id="elh_users_government_id_type" class="users_government_id_type"><?= $Grid->renderFieldHeader($Grid->government_id_type) ?></div></th>
<?php } ?>
<?php if ($Grid->government_id_number->Visible) { // government_id_number ?>
        <th data-name="government_id_number" class="<?= $Grid->government_id_number->headerCellClass() ?>"><div id="elh_users_government_id_number" class="users_government_id_number"><?= $Grid->renderFieldHeader($Grid->government_id_number) ?></div></th>
<?php } ?>
<?php if ($Grid->privacy_agreement_accepted->Visible) { // privacy_agreement_accepted ?>
        <th data-name="privacy_agreement_accepted" class="<?= $Grid->privacy_agreement_accepted->headerCellClass() ?>"><div id="elh_users_privacy_agreement_accepted" class="users_privacy_agreement_accepted"><?= $Grid->renderFieldHeader($Grid->privacy_agreement_accepted) ?></div></th>
<?php } ?>
<?php if ($Grid->government_id_path->Visible) { // government_id_path ?>
        <th data-name="government_id_path" class="<?= $Grid->government_id_path->headerCellClass() ?>"><div id="elh_users_government_id_path" class="users_government_id_path"><?= $Grid->renderFieldHeader($Grid->government_id_path) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Grid->getPageNumber() ?>">
<?php
$Grid->setupGrid();
$isInlineAddOrCopy = ($Grid->isCopy() || $Grid->isAdd());
while ($Grid->RecordCount < $Grid->StopRecord || $Grid->RowIndex === '$rowindex$' || $isInlineAddOrCopy && $Grid->RowIndex == 0) {
    if (
        $Grid->CurrentRow !== false &&
        $Grid->RowIndex !== '$rowindex$' &&
        (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy") &&
        !($isInlineAddOrCopy && $Grid->RowIndex == 0)
    ) {
        $Grid->fetch();
    }
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->setupRow();

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Grid->RowAction != "delete" &&
            $Grid->RowAction != "insertdelete" &&
            !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow()) &&
            $Grid->RowAction != "hide"
        ) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->user_id->Visible) { // user_id ?>
        <td data-name="user_id"<?= $Grid->user_id->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_id" class="el_users_user_id"></span>
<input type="hidden" data-table="users" data-field="x_user_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_user_id" id="o<?= $Grid->RowIndex ?>_user_id" value="<?= HtmlEncode($Grid->user_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_id" class="el_users_user_id">
<span<?= $Grid->user_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->user_id->getDisplayValue($Grid->user_id->EditValue))) ?>"></span>
<input type="hidden" data-table="users" data-field="x_user_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_user_id" id="x<?= $Grid->RowIndex ?>_user_id" value="<?= HtmlEncode($Grid->user_id->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_id" class="el_users_user_id">
<span<?= $Grid->user_id->viewAttributes() ?>>
<?= $Grid->user_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_user_id" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_user_id" id="fusersgrid$x<?= $Grid->RowIndex ?>_user_id" value="<?= HtmlEncode($Grid->user_id->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_user_id" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_user_id" id="fusersgrid$o<?= $Grid->RowIndex ?>_user_id" value="<?= HtmlEncode($Grid->user_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="users" data-field="x_user_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_user_id" id="x<?= $Grid->RowIndex ?>_user_id" value="<?= HtmlEncode($Grid->user_id->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->department_id->Visible) { // department_id ?>
        <td data-name="department_id"<?= $Grid->department_id->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<?php if ($Grid->department_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_department_id" class="el_users_department_id">
<span<?= $Grid->department_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->department_id->getDisplayValue($Grid->department_id->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_department_id" name="x<?= $Grid->RowIndex ?>_department_id" value="<?= HtmlEncode($Grid->department_id->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_department_id" class="el_users_department_id">
    <select
        id="x<?= $Grid->RowIndex ?>_department_id"
        name="x<?= $Grid->RowIndex ?>_department_id"
        class="form-select ew-select<?= $Grid->department_id->isInvalidClass() ?>"
        <?php if (!$Grid->department_id->IsNativeSelect) { ?>
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_department_id"
        <?php } ?>
        data-table="users"
        data-field="x_department_id"
        data-value-separator="<?= $Grid->department_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->department_id->getPlaceHolder()) ?>"
        <?= $Grid->department_id->editAttributes() ?>>
        <?= $Grid->department_id->selectOptionListHtml("x{$Grid->RowIndex}_department_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->department_id->getErrorMessage() ?></div>
<?= $Grid->department_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_department_id") ?>
<?php if (!$Grid->department_id->IsNativeSelect) { ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_department_id", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_department_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.department_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_department_id", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_department_id", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.department_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<input type="hidden" data-table="users" data-field="x_department_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_department_id" id="o<?= $Grid->RowIndex ?>_department_id" value="<?= HtmlEncode($Grid->department_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<?php if ($Grid->department_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_department_id" class="el_users_department_id">
<span<?= $Grid->department_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->department_id->getDisplayValue($Grid->department_id->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_department_id" name="x<?= $Grid->RowIndex ?>_department_id" value="<?= HtmlEncode($Grid->department_id->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_department_id" class="el_users_department_id">
    <select
        id="x<?= $Grid->RowIndex ?>_department_id"
        name="x<?= $Grid->RowIndex ?>_department_id"
        class="form-select ew-select<?= $Grid->department_id->isInvalidClass() ?>"
        <?php if (!$Grid->department_id->IsNativeSelect) { ?>
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_department_id"
        <?php } ?>
        data-table="users"
        data-field="x_department_id"
        data-value-separator="<?= $Grid->department_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->department_id->getPlaceHolder()) ?>"
        <?= $Grid->department_id->editAttributes() ?>>
        <?= $Grid->department_id->selectOptionListHtml("x{$Grid->RowIndex}_department_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->department_id->getErrorMessage() ?></div>
<?= $Grid->department_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_department_id") ?>
<?php if (!$Grid->department_id->IsNativeSelect) { ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_department_id", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_department_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.department_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_department_id", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_department_id", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.department_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_department_id" class="el_users_department_id">
<span<?= $Grid->department_id->viewAttributes() ?>>
<?= $Grid->department_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_department_id" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_department_id" id="fusersgrid$x<?= $Grid->RowIndex ?>_department_id" value="<?= HtmlEncode($Grid->department_id->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_department_id" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_department_id" id="fusersgrid$o<?= $Grid->RowIndex ?>_department_id" value="<?= HtmlEncode($Grid->department_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->_username->Visible) { // username ?>
        <td data-name="_username"<?= $Grid->_username->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users__username" class="el_users__username">
<input type="<?= $Grid->_username->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__username" id="x<?= $Grid->RowIndex ?>__username" data-table="users" data-field="x__username" value="<?= $Grid->_username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->_username->formatPattern()) ?>"<?= $Grid->_username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_username->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x__username" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>__username" id="o<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users__username" class="el_users__username">
<input type="<?= $Grid->_username->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__username" id="x<?= $Grid->RowIndex ?>__username" data-table="users" data-field="x__username" value="<?= $Grid->_username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->_username->formatPattern()) ?>"<?= $Grid->_username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_username->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users__username" class="el_users__username">
<span<?= $Grid->_username->viewAttributes() ?>>
<?= $Grid->_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x__username" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>__username" id="fusersgrid$x<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x__username" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>__username" id="fusersgrid$o<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->_email->Visible) { // email ?>
        <td data-name="_email"<?= $Grid->_email->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users__email" class="el_users__email">
<input type="<?= $Grid->_email->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__email" id="x<?= $Grid->RowIndex ?>__email" data-table="users" data-field="x__email" value="<?= $Grid->_email->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->_email->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->_email->formatPattern()) ?>"<?= $Grid->_email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_email->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x__email" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>__email" id="o<?= $Grid->RowIndex ?>__email" value="<?= HtmlEncode($Grid->_email->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users__email" class="el_users__email">
<input type="<?= $Grid->_email->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__email" id="x<?= $Grid->RowIndex ?>__email" data-table="users" data-field="x__email" value="<?= $Grid->_email->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->_email->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->_email->formatPattern()) ?>"<?= $Grid->_email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_email->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users__email" class="el_users__email">
<span<?= $Grid->_email->viewAttributes() ?>>
<?= $Grid->_email->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x__email" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>__email" id="fusersgrid$x<?= $Grid->RowIndex ?>__email" value="<?= HtmlEncode($Grid->_email->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x__email" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>__email" id="fusersgrid$o<?= $Grid->RowIndex ?>__email" value="<?= HtmlEncode($Grid->_email->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->first_name->Visible) { // first_name ?>
        <td data-name="first_name"<?= $Grid->first_name->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_first_name" class="el_users_first_name">
<input type="<?= $Grid->first_name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_first_name" id="x<?= $Grid->RowIndex ?>_first_name" data-table="users" data-field="x_first_name" value="<?= $Grid->first_name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->first_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->first_name->formatPattern()) ?>"<?= $Grid->first_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->first_name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x_first_name" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_first_name" id="o<?= $Grid->RowIndex ?>_first_name" value="<?= HtmlEncode($Grid->first_name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_first_name" class="el_users_first_name">
<input type="<?= $Grid->first_name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_first_name" id="x<?= $Grid->RowIndex ?>_first_name" data-table="users" data-field="x_first_name" value="<?= $Grid->first_name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->first_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->first_name->formatPattern()) ?>"<?= $Grid->first_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->first_name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_first_name" class="el_users_first_name">
<span<?= $Grid->first_name->viewAttributes() ?>>
<?= $Grid->first_name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_first_name" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_first_name" id="fusersgrid$x<?= $Grid->RowIndex ?>_first_name" value="<?= HtmlEncode($Grid->first_name->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_first_name" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_first_name" id="fusersgrid$o<?= $Grid->RowIndex ?>_first_name" value="<?= HtmlEncode($Grid->first_name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->last_name->Visible) { // last_name ?>
        <td data-name="last_name"<?= $Grid->last_name->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_last_name" class="el_users_last_name">
<input type="<?= $Grid->last_name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_last_name" id="x<?= $Grid->RowIndex ?>_last_name" data-table="users" data-field="x_last_name" value="<?= $Grid->last_name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->last_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->last_name->formatPattern()) ?>"<?= $Grid->last_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->last_name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x_last_name" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_last_name" id="o<?= $Grid->RowIndex ?>_last_name" value="<?= HtmlEncode($Grid->last_name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_last_name" class="el_users_last_name">
<input type="<?= $Grid->last_name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_last_name" id="x<?= $Grid->RowIndex ?>_last_name" data-table="users" data-field="x_last_name" value="<?= $Grid->last_name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->last_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->last_name->formatPattern()) ?>"<?= $Grid->last_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->last_name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_last_name" class="el_users_last_name">
<span<?= $Grid->last_name->viewAttributes() ?>>
<?= $Grid->last_name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_last_name" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_last_name" id="fusersgrid$x<?= $Grid->RowIndex ?>_last_name" value="<?= HtmlEncode($Grid->last_name->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_last_name" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_last_name" id="fusersgrid$o<?= $Grid->RowIndex ?>_last_name" value="<?= HtmlEncode($Grid->last_name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->is_active->Visible) { // is_active ?>
        <td data-name="is_active"<?= $Grid->is_active->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_is_active" class="el_users_is_active">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->is_active->isInvalidClass() ?>" data-table="users" data-field="x_is_active" data-boolean name="x<?= $Grid->RowIndex ?>_is_active" id="x<?= $Grid->RowIndex ?>_is_active" value="1"<?= ConvertToBool($Grid->is_active->CurrentValue) ? " checked" : "" ?><?= $Grid->is_active->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->is_active->getErrorMessage() ?></div>
</div>
</span>
<input type="hidden" data-table="users" data-field="x_is_active" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_is_active" id="o<?= $Grid->RowIndex ?>_is_active" value="<?= HtmlEncode($Grid->is_active->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_is_active" class="el_users_is_active">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->is_active->isInvalidClass() ?>" data-table="users" data-field="x_is_active" data-boolean name="x<?= $Grid->RowIndex ?>_is_active" id="x<?= $Grid->RowIndex ?>_is_active" value="1"<?= ConvertToBool($Grid->is_active->CurrentValue) ? " checked" : "" ?><?= $Grid->is_active->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->is_active->getErrorMessage() ?></div>
</div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_is_active" class="el_users_is_active">
<span<?= $Grid->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Grid->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_is_active" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_is_active" id="fusersgrid$x<?= $Grid->RowIndex ?>_is_active" value="<?= HtmlEncode($Grid->is_active->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_is_active" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_is_active" id="fusersgrid$o<?= $Grid->RowIndex ?>_is_active" value="<?= HtmlEncode($Grid->is_active->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->user_level_id->Visible) { // user_level_id ?>
        <td data-name="user_level_id"<?= $Grid->user_level_id->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<?php if ($Grid->user_level_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_level_id" class="el_users_user_level_id">
<span<?= $Grid->user_level_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->user_level_id->getDisplayValue($Grid->user_level_id->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_user_level_id" name="x<?= $Grid->RowIndex ?>_user_level_id" value="<?= HtmlEncode($Grid->user_level_id->CurrentValue) ?>" data-hidden="1">
</span>
<?php } elseif (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_level_id" class="el_users_user_level_id">
<span class="form-control-plaintext"><?= $Grid->user_level_id->getDisplayValue($Grid->user_level_id->EditValue) ?></span>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_level_id" class="el_users_user_level_id">
    <select
        id="x<?= $Grid->RowIndex ?>_user_level_id[]"
        name="x<?= $Grid->RowIndex ?>_user_level_id[]"
        class="form-select ew-select<?= $Grid->user_level_id->isInvalidClass() ?>"
        <?php if (!$Grid->user_level_id->IsNativeSelect) { ?>
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_user_level_id[]"
        <?php } ?>
        data-table="users"
        data-field="x_user_level_id"
        multiple
        size="1"
        data-value-separator="<?= $Grid->user_level_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->user_level_id->getPlaceHolder()) ?>"
        <?= $Grid->user_level_id->editAttributes() ?>>
        <?= $Grid->user_level_id->selectOptionListHtml("x{$Grid->RowIndex}_user_level_id[]") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->user_level_id->getErrorMessage() ?></div>
<?= $Grid->user_level_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_user_level_id") ?>
<?php if (!$Grid->user_level_id->IsNativeSelect) { ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_user_level_id[]", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_user_level_id[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.user_level_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_user_level_id[]", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_user_level_id[]", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.user_level_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<input type="hidden" data-table="users" data-field="x_user_level_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_user_level_id[]" id="o<?= $Grid->RowIndex ?>_user_level_id[]" value="<?= HtmlEncode($Grid->user_level_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<?php if ($Grid->user_level_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_level_id" class="el_users_user_level_id">
<span<?= $Grid->user_level_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->user_level_id->getDisplayValue($Grid->user_level_id->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_user_level_id" name="x<?= $Grid->RowIndex ?>_user_level_id" value="<?= HtmlEncode($Grid->user_level_id->CurrentValue) ?>" data-hidden="1">
</span>
<?php } elseif (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_level_id" class="el_users_user_level_id">
<span class="form-control-plaintext"><?= $Grid->user_level_id->getDisplayValue($Grid->user_level_id->EditValue) ?></span>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_level_id" class="el_users_user_level_id">
    <select
        id="x<?= $Grid->RowIndex ?>_user_level_id[]"
        name="x<?= $Grid->RowIndex ?>_user_level_id[]"
        class="form-select ew-select<?= $Grid->user_level_id->isInvalidClass() ?>"
        <?php if (!$Grid->user_level_id->IsNativeSelect) { ?>
        data-select2-id="fusersgrid_x<?= $Grid->RowIndex ?>_user_level_id[]"
        <?php } ?>
        data-table="users"
        data-field="x_user_level_id"
        multiple
        size="1"
        data-value-separator="<?= $Grid->user_level_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->user_level_id->getPlaceHolder()) ?>"
        <?= $Grid->user_level_id->editAttributes() ?>>
        <?= $Grid->user_level_id->selectOptionListHtml("x{$Grid->RowIndex}_user_level_id[]") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->user_level_id->getErrorMessage() ?></div>
<?= $Grid->user_level_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_user_level_id") ?>
<?php if (!$Grid->user_level_id->IsNativeSelect) { ?>
<script>
loadjs.ready("fusersgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_user_level_id[]", selectId: "fusersgrid_x<?= $Grid->RowIndex ?>_user_level_id[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersgrid.lists.user_level_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_user_level_id[]", form: "fusersgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_user_level_id[]", form: "fusersgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields.user_level_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_user_level_id" class="el_users_user_level_id">
<span<?= $Grid->user_level_id->viewAttributes() ?>>
<?= $Grid->user_level_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_user_level_id" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_user_level_id" id="fusersgrid$x<?= $Grid->RowIndex ?>_user_level_id" value="<?= HtmlEncode($Grid->user_level_id->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_user_level_id" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_user_level_id[]" id="fusersgrid$o<?= $Grid->RowIndex ?>_user_level_id[]" value="<?= HtmlEncode($Grid->user_level_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->is_notary->Visible) { // is_notary ?>
        <td data-name="is_notary"<?= $Grid->is_notary->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_is_notary" class="el_users_is_notary">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->is_notary->isInvalidClass() ?>" data-table="users" data-field="x_is_notary" data-boolean name="x<?= $Grid->RowIndex ?>_is_notary" id="x<?= $Grid->RowIndex ?>_is_notary" value="1"<?= ConvertToBool($Grid->is_notary->CurrentValue) ? " checked" : "" ?><?= $Grid->is_notary->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->is_notary->getErrorMessage() ?></div>
</div>
</span>
<input type="hidden" data-table="users" data-field="x_is_notary" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_is_notary" id="o<?= $Grid->RowIndex ?>_is_notary" value="<?= HtmlEncode($Grid->is_notary->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_is_notary" class="el_users_is_notary">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->is_notary->isInvalidClass() ?>" data-table="users" data-field="x_is_notary" data-boolean name="x<?= $Grid->RowIndex ?>_is_notary" id="x<?= $Grid->RowIndex ?>_is_notary" value="1"<?= ConvertToBool($Grid->is_notary->CurrentValue) ? " checked" : "" ?><?= $Grid->is_notary->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->is_notary->getErrorMessage() ?></div>
</div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_is_notary" class="el_users_is_notary">
<span<?= $Grid->is_notary->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Grid->is_notary->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_is_notary" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_is_notary" id="fusersgrid$x<?= $Grid->RowIndex ?>_is_notary" value="<?= HtmlEncode($Grid->is_notary->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_is_notary" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_is_notary" id="fusersgrid$o<?= $Grid->RowIndex ?>_is_notary" value="<?= HtmlEncode($Grid->is_notary->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->notary_commission_number->Visible) { // notary_commission_number ?>
        <td data-name="notary_commission_number"<?= $Grid->notary_commission_number->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_notary_commission_number" class="el_users_notary_commission_number">
<input type="<?= $Grid->notary_commission_number->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_notary_commission_number" id="x<?= $Grid->RowIndex ?>_notary_commission_number" data-table="users" data-field="x_notary_commission_number" value="<?= $Grid->notary_commission_number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->notary_commission_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->notary_commission_number->formatPattern()) ?>"<?= $Grid->notary_commission_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->notary_commission_number->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x_notary_commission_number" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_notary_commission_number" id="o<?= $Grid->RowIndex ?>_notary_commission_number" value="<?= HtmlEncode($Grid->notary_commission_number->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_notary_commission_number" class="el_users_notary_commission_number">
<input type="<?= $Grid->notary_commission_number->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_notary_commission_number" id="x<?= $Grid->RowIndex ?>_notary_commission_number" data-table="users" data-field="x_notary_commission_number" value="<?= $Grid->notary_commission_number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->notary_commission_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->notary_commission_number->formatPattern()) ?>"<?= $Grid->notary_commission_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->notary_commission_number->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_notary_commission_number" class="el_users_notary_commission_number">
<span<?= $Grid->notary_commission_number->viewAttributes() ?>>
<?= $Grid->notary_commission_number->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_notary_commission_number" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_notary_commission_number" id="fusersgrid$x<?= $Grid->RowIndex ?>_notary_commission_number" value="<?= HtmlEncode($Grid->notary_commission_number->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_notary_commission_number" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_notary_commission_number" id="fusersgrid$o<?= $Grid->RowIndex ?>_notary_commission_number" value="<?= HtmlEncode($Grid->notary_commission_number->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->notary_commission_expiry->Visible) { // notary_commission_expiry ?>
        <td data-name="notary_commission_expiry"<?= $Grid->notary_commission_expiry->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_notary_commission_expiry" class="el_users_notary_commission_expiry">
<input type="<?= $Grid->notary_commission_expiry->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_notary_commission_expiry" id="x<?= $Grid->RowIndex ?>_notary_commission_expiry" data-table="users" data-field="x_notary_commission_expiry" value="<?= $Grid->notary_commission_expiry->EditValue ?>" placeholder="<?= HtmlEncode($Grid->notary_commission_expiry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->notary_commission_expiry->formatPattern()) ?>"<?= $Grid->notary_commission_expiry->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->notary_commission_expiry->getErrorMessage() ?></div>
<?php if (!$Grid->notary_commission_expiry->ReadOnly && !$Grid->notary_commission_expiry->Disabled && !isset($Grid->notary_commission_expiry->EditAttrs["readonly"]) && !isset($Grid->notary_commission_expiry->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fusersgrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fusersgrid", "x<?= $Grid->RowIndex ?>_notary_commission_expiry", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="users" data-field="x_notary_commission_expiry" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_notary_commission_expiry" id="o<?= $Grid->RowIndex ?>_notary_commission_expiry" value="<?= HtmlEncode($Grid->notary_commission_expiry->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_notary_commission_expiry" class="el_users_notary_commission_expiry">
<input type="<?= $Grid->notary_commission_expiry->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_notary_commission_expiry" id="x<?= $Grid->RowIndex ?>_notary_commission_expiry" data-table="users" data-field="x_notary_commission_expiry" value="<?= $Grid->notary_commission_expiry->EditValue ?>" placeholder="<?= HtmlEncode($Grid->notary_commission_expiry->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->notary_commission_expiry->formatPattern()) ?>"<?= $Grid->notary_commission_expiry->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->notary_commission_expiry->getErrorMessage() ?></div>
<?php if (!$Grid->notary_commission_expiry->ReadOnly && !$Grid->notary_commission_expiry->Disabled && !isset($Grid->notary_commission_expiry->EditAttrs["readonly"]) && !isset($Grid->notary_commission_expiry->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fusersgrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fusersgrid", "x<?= $Grid->RowIndex ?>_notary_commission_expiry", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_notary_commission_expiry" class="el_users_notary_commission_expiry">
<span<?= $Grid->notary_commission_expiry->viewAttributes() ?>>
<?= $Grid->notary_commission_expiry->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_notary_commission_expiry" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_notary_commission_expiry" id="fusersgrid$x<?= $Grid->RowIndex ?>_notary_commission_expiry" value="<?= HtmlEncode($Grid->notary_commission_expiry->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_notary_commission_expiry" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_notary_commission_expiry" id="fusersgrid$o<?= $Grid->RowIndex ?>_notary_commission_expiry" value="<?= HtmlEncode($Grid->notary_commission_expiry->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->government_id_type->Visible) { // government_id_type ?>
        <td data-name="government_id_type"<?= $Grid->government_id_type->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_government_id_type" class="el_users_government_id_type">
<input type="<?= $Grid->government_id_type->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_government_id_type" id="x<?= $Grid->RowIndex ?>_government_id_type" data-table="users" data-field="x_government_id_type" value="<?= $Grid->government_id_type->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->government_id_type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->government_id_type->formatPattern()) ?>"<?= $Grid->government_id_type->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->government_id_type->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x_government_id_type" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_government_id_type" id="o<?= $Grid->RowIndex ?>_government_id_type" value="<?= HtmlEncode($Grid->government_id_type->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_government_id_type" class="el_users_government_id_type">
<input type="<?= $Grid->government_id_type->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_government_id_type" id="x<?= $Grid->RowIndex ?>_government_id_type" data-table="users" data-field="x_government_id_type" value="<?= $Grid->government_id_type->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->government_id_type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->government_id_type->formatPattern()) ?>"<?= $Grid->government_id_type->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->government_id_type->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_government_id_type" class="el_users_government_id_type">
<span<?= $Grid->government_id_type->viewAttributes() ?>>
<?= $Grid->government_id_type->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_government_id_type" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_government_id_type" id="fusersgrid$x<?= $Grid->RowIndex ?>_government_id_type" value="<?= HtmlEncode($Grid->government_id_type->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_government_id_type" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_government_id_type" id="fusersgrid$o<?= $Grid->RowIndex ?>_government_id_type" value="<?= HtmlEncode($Grid->government_id_type->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->government_id_number->Visible) { // government_id_number ?>
        <td data-name="government_id_number"<?= $Grid->government_id_number->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_government_id_number" class="el_users_government_id_number">
<input type="<?= $Grid->government_id_number->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_government_id_number" id="x<?= $Grid->RowIndex ?>_government_id_number" data-table="users" data-field="x_government_id_number" value="<?= $Grid->government_id_number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->government_id_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->government_id_number->formatPattern()) ?>"<?= $Grid->government_id_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->government_id_number->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x_government_id_number" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_government_id_number" id="o<?= $Grid->RowIndex ?>_government_id_number" value="<?= HtmlEncode($Grid->government_id_number->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_government_id_number" class="el_users_government_id_number">
<input type="<?= $Grid->government_id_number->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_government_id_number" id="x<?= $Grid->RowIndex ?>_government_id_number" data-table="users" data-field="x_government_id_number" value="<?= $Grid->government_id_number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->government_id_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->government_id_number->formatPattern()) ?>"<?= $Grid->government_id_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->government_id_number->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_government_id_number" class="el_users_government_id_number">
<span<?= $Grid->government_id_number->viewAttributes() ?>>
<?= $Grid->government_id_number->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_government_id_number" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_government_id_number" id="fusersgrid$x<?= $Grid->RowIndex ?>_government_id_number" value="<?= HtmlEncode($Grid->government_id_number->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_government_id_number" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_government_id_number" id="fusersgrid$o<?= $Grid->RowIndex ?>_government_id_number" value="<?= HtmlEncode($Grid->government_id_number->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->privacy_agreement_accepted->Visible) { // privacy_agreement_accepted ?>
        <td data-name="privacy_agreement_accepted"<?= $Grid->privacy_agreement_accepted->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_privacy_agreement_accepted" class="el_users_privacy_agreement_accepted">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->privacy_agreement_accepted->isInvalidClass() ?>" data-table="users" data-field="x_privacy_agreement_accepted" data-boolean name="x<?= $Grid->RowIndex ?>_privacy_agreement_accepted" id="x<?= $Grid->RowIndex ?>_privacy_agreement_accepted" value="1"<?= ConvertToBool($Grid->privacy_agreement_accepted->CurrentValue) ? " checked" : "" ?><?= $Grid->privacy_agreement_accepted->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->privacy_agreement_accepted->getErrorMessage() ?></div>
</div>
</span>
<input type="hidden" data-table="users" data-field="x_privacy_agreement_accepted" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_privacy_agreement_accepted" id="o<?= $Grid->RowIndex ?>_privacy_agreement_accepted" value="<?= HtmlEncode($Grid->privacy_agreement_accepted->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_privacy_agreement_accepted" class="el_users_privacy_agreement_accepted">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Grid->privacy_agreement_accepted->isInvalidClass() ?>" data-table="users" data-field="x_privacy_agreement_accepted" data-boolean name="x<?= $Grid->RowIndex ?>_privacy_agreement_accepted" id="x<?= $Grid->RowIndex ?>_privacy_agreement_accepted" value="1"<?= ConvertToBool($Grid->privacy_agreement_accepted->CurrentValue) ? " checked" : "" ?><?= $Grid->privacy_agreement_accepted->editAttributes() ?>>
    <div class="invalid-feedback"><?= $Grid->privacy_agreement_accepted->getErrorMessage() ?></div>
</div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_privacy_agreement_accepted" class="el_users_privacy_agreement_accepted">
<span<?= $Grid->privacy_agreement_accepted->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Grid->privacy_agreement_accepted->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_privacy_agreement_accepted" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_privacy_agreement_accepted" id="fusersgrid$x<?= $Grid->RowIndex ?>_privacy_agreement_accepted" value="<?= HtmlEncode($Grid->privacy_agreement_accepted->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_privacy_agreement_accepted" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_privacy_agreement_accepted" id="fusersgrid$o<?= $Grid->RowIndex ?>_privacy_agreement_accepted" value="<?= HtmlEncode($Grid->privacy_agreement_accepted->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->government_id_path->Visible) { // government_id_path ?>
        <td data-name="government_id_path"<?= $Grid->government_id_path->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_government_id_path" class="el_users_government_id_path">
<input type="<?= $Grid->government_id_path->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_government_id_path" id="x<?= $Grid->RowIndex ?>_government_id_path" data-table="users" data-field="x_government_id_path" value="<?= $Grid->government_id_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->government_id_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->government_id_path->formatPattern()) ?>"<?= $Grid->government_id_path->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->government_id_path->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="users" data-field="x_government_id_path" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_government_id_path" id="o<?= $Grid->RowIndex ?>_government_id_path" value="<?= HtmlEncode($Grid->government_id_path->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_government_id_path" class="el_users_government_id_path">
<input type="<?= $Grid->government_id_path->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_government_id_path" id="x<?= $Grid->RowIndex ?>_government_id_path" data-table="users" data-field="x_government_id_path" value="<?= $Grid->government_id_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->government_id_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->government_id_path->formatPattern()) ?>"<?= $Grid->government_id_path->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->government_id_path->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_users_government_id_path" class="el_users_government_id_path">
<span<?= $Grid->government_id_path->viewAttributes() ?>>
<?= $Grid->government_id_path->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="users" data-field="x_government_id_path" data-hidden="1" name="fusersgrid$x<?= $Grid->RowIndex ?>_government_id_path" id="fusersgrid$x<?= $Grid->RowIndex ?>_government_id_path" value="<?= HtmlEncode($Grid->government_id_path->FormValue) ?>">
<input type="hidden" data-table="users" data-field="x_government_id_path" data-hidden="1" data-old name="fusersgrid$o<?= $Grid->RowIndex ?>_government_id_path" id="fusersgrid$o<?= $Grid->RowIndex ?>_government_id_path" value="<?= HtmlEncode($Grid->government_id_path->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == RowType::ADD || $Grid->RowType == RowType::EDIT) { ?>
<script data-rowindex="<?= $Grid->RowIndex ?>">
loadjs.ready(["fusersgrid","load"], () => fusersgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking

    // Reset for template row
    if ($Grid->RowIndex === '$rowindex$') {
        $Grid->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Grid->isCopy() || $Grid->isAdd()) && $Grid->RowIndex == 0) {
        $Grid->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fusersgrid">
</div><!-- /.ew-list-form -->
<?php
// Close result set
$Grid->Recordset?->free();
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
<div id="ew-footer-options">
<?php $Grid->FooterOptions?->render("body") ?>
</div>
</main>
<?php if (!$Grid->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("users");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
