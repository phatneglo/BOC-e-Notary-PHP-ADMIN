<?php

namespace PHPMaker2024\eNotary;

// Page object
$UsersList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { users: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")
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
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "_user_levels") {
    if ($Page->MasterRecordExists) {
        include_once "views/UserLevelsMaster.php";
    }
}
?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "departments") {
    if ($Page->MasterRecordExists) {
        include_once "views/DepartmentsMaster.php";
    }
}
?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="fuserssrch" id="fuserssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fuserssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { users: currentTable } });
var currentForm;
var fuserssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fuserssrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Dynamic selection lists
        .setLists({
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fuserssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fuserssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fuserssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fuserssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-header-options">
<?php $Page->HeaderOptions?->render("body") ?>
</div>
<div id="ew-list">
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="users">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "_user_levels" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="_user_levels">
<input type="hidden" name="fk_user_level_id" value="<?= HtmlEncode($Page->user_level_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "departments" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="departments">
<input type="hidden" name="fk_department_id" value="<?= HtmlEncode($Page->department_id->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_users" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_userslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = RowType::HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th data-name="user_id" class="<?= $Page->user_id->headerCellClass() ?>"><div id="elh_users_user_id" class="users_user_id"><?= $Page->renderFieldHeader($Page->user_id) ?></div></th>
<?php } ?>
<?php if ($Page->department_id->Visible) { // department_id ?>
        <th data-name="department_id" class="<?= $Page->department_id->headerCellClass() ?>"><div id="elh_users_department_id" class="users_department_id"><?= $Page->renderFieldHeader($Page->department_id) ?></div></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th data-name="_username" class="<?= $Page->_username->headerCellClass() ?>"><div id="elh_users__username" class="users__username"><?= $Page->renderFieldHeader($Page->_username) ?></div></th>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
        <th data-name="_email" class="<?= $Page->_email->headerCellClass() ?>"><div id="elh_users__email" class="users__email"><?= $Page->renderFieldHeader($Page->_email) ?></div></th>
<?php } ?>
<?php if ($Page->first_name->Visible) { // first_name ?>
        <th data-name="first_name" class="<?= $Page->first_name->headerCellClass() ?>"><div id="elh_users_first_name" class="users_first_name"><?= $Page->renderFieldHeader($Page->first_name) ?></div></th>
<?php } ?>
<?php if ($Page->last_name->Visible) { // last_name ?>
        <th data-name="last_name" class="<?= $Page->last_name->headerCellClass() ?>"><div id="elh_users_last_name" class="users_last_name"><?= $Page->renderFieldHeader($Page->last_name) ?></div></th>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
        <th data-name="is_active" class="<?= $Page->is_active->headerCellClass() ?>"><div id="elh_users_is_active" class="users_is_active"><?= $Page->renderFieldHeader($Page->is_active) ?></div></th>
<?php } ?>
<?php if ($Page->user_level_id->Visible) { // user_level_id ?>
        <th data-name="user_level_id" class="<?= $Page->user_level_id->headerCellClass() ?>"><div id="elh_users_user_level_id" class="users_user_level_id"><?= $Page->renderFieldHeader($Page->user_level_id) ?></div></th>
<?php } ?>
<?php if ($Page->is_notary->Visible) { // is_notary ?>
        <th data-name="is_notary" class="<?= $Page->is_notary->headerCellClass() ?>"><div id="elh_users_is_notary" class="users_is_notary"><?= $Page->renderFieldHeader($Page->is_notary) ?></div></th>
<?php } ?>
<?php if ($Page->notary_commission_number->Visible) { // notary_commission_number ?>
        <th data-name="notary_commission_number" class="<?= $Page->notary_commission_number->headerCellClass() ?>"><div id="elh_users_notary_commission_number" class="users_notary_commission_number"><?= $Page->renderFieldHeader($Page->notary_commission_number) ?></div></th>
<?php } ?>
<?php if ($Page->notary_commission_expiry->Visible) { // notary_commission_expiry ?>
        <th data-name="notary_commission_expiry" class="<?= $Page->notary_commission_expiry->headerCellClass() ?>"><div id="elh_users_notary_commission_expiry" class="users_notary_commission_expiry"><?= $Page->renderFieldHeader($Page->notary_commission_expiry) ?></div></th>
<?php } ?>
<?php if ($Page->government_id_type->Visible) { // government_id_type ?>
        <th data-name="government_id_type" class="<?= $Page->government_id_type->headerCellClass() ?>"><div id="elh_users_government_id_type" class="users_government_id_type"><?= $Page->renderFieldHeader($Page->government_id_type) ?></div></th>
<?php } ?>
<?php if ($Page->government_id_number->Visible) { // government_id_number ?>
        <th data-name="government_id_number" class="<?= $Page->government_id_number->headerCellClass() ?>"><div id="elh_users_government_id_number" class="users_government_id_number"><?= $Page->renderFieldHeader($Page->government_id_number) ?></div></th>
<?php } ?>
<?php if ($Page->privacy_agreement_accepted->Visible) { // privacy_agreement_accepted ?>
        <th data-name="privacy_agreement_accepted" class="<?= $Page->privacy_agreement_accepted->headerCellClass() ?>"><div id="elh_users_privacy_agreement_accepted" class="users_privacy_agreement_accepted"><?= $Page->renderFieldHeader($Page->privacy_agreement_accepted) ?></div></th>
<?php } ?>
<?php if ($Page->government_id_path->Visible) { // government_id_path ?>
        <th data-name="government_id_path" class="<?= $Page->government_id_path->headerCellClass() ?>"><div id="elh_users_government_id_path" class="users_government_id_path"><?= $Page->renderFieldHeader($Page->government_id_path) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
$isInlineAddOrCopy = ($Page->isCopy() || $Page->isAdd());
while ($Page->RecordCount < $Page->StopRecord || $Page->RowIndex === '$rowindex$' || $isInlineAddOrCopy && $Page->RowIndex == 0) {
    if (
        $Page->CurrentRow !== false &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        !($isInlineAddOrCopy && $Page->RowIndex == 0)
    ) {
        $Page->fetch();
    }
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->user_id->Visible) { // user_id ?>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_user_id" class="el_users_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->department_id->Visible) { // department_id ?>
        <td data-name="department_id"<?= $Page->department_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_department_id" class="el_users_department_id">
<span<?= $Page->department_id->viewAttributes() ?>>
<?= $Page->department_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_username->Visible) { // username ?>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users__username" class="el_users__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_email->Visible) { // email ?>
        <td data-name="_email"<?= $Page->_email->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users__email" class="el_users__email">
<span<?= $Page->_email->viewAttributes() ?>>
<?= $Page->_email->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->first_name->Visible) { // first_name ?>
        <td data-name="first_name"<?= $Page->first_name->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_first_name" class="el_users_first_name">
<span<?= $Page->first_name->viewAttributes() ?>>
<?= $Page->first_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->last_name->Visible) { // last_name ?>
        <td data-name="last_name"<?= $Page->last_name->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_last_name" class="el_users_last_name">
<span<?= $Page->last_name->viewAttributes() ?>>
<?= $Page->last_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->is_active->Visible) { // is_active ?>
        <td data-name="is_active"<?= $Page->is_active->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_is_active" class="el_users_is_active">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->user_level_id->Visible) { // user_level_id ?>
        <td data-name="user_level_id"<?= $Page->user_level_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_user_level_id" class="el_users_user_level_id">
<span<?= $Page->user_level_id->viewAttributes() ?>>
<?= $Page->user_level_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->is_notary->Visible) { // is_notary ?>
        <td data-name="is_notary"<?= $Page->is_notary->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_is_notary" class="el_users_is_notary">
<span<?= $Page->is_notary->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_notary->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notary_commission_number->Visible) { // notary_commission_number ?>
        <td data-name="notary_commission_number"<?= $Page->notary_commission_number->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_notary_commission_number" class="el_users_notary_commission_number">
<span<?= $Page->notary_commission_number->viewAttributes() ?>>
<?= $Page->notary_commission_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notary_commission_expiry->Visible) { // notary_commission_expiry ?>
        <td data-name="notary_commission_expiry"<?= $Page->notary_commission_expiry->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_notary_commission_expiry" class="el_users_notary_commission_expiry">
<span<?= $Page->notary_commission_expiry->viewAttributes() ?>>
<?= $Page->notary_commission_expiry->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->government_id_type->Visible) { // government_id_type ?>
        <td data-name="government_id_type"<?= $Page->government_id_type->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_government_id_type" class="el_users_government_id_type">
<span<?= $Page->government_id_type->viewAttributes() ?>>
<?= $Page->government_id_type->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->government_id_number->Visible) { // government_id_number ?>
        <td data-name="government_id_number"<?= $Page->government_id_number->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_government_id_number" class="el_users_government_id_number">
<span<?= $Page->government_id_number->viewAttributes() ?>>
<?= $Page->government_id_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->privacy_agreement_accepted->Visible) { // privacy_agreement_accepted ?>
        <td data-name="privacy_agreement_accepted"<?= $Page->privacy_agreement_accepted->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_privacy_agreement_accepted" class="el_users_privacy_agreement_accepted">
<span<?= $Page->privacy_agreement_accepted->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->privacy_agreement_accepted->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->government_id_path->Visible) { // government_id_path ?>
        <td data-name="government_id_path"<?= $Page->government_id_path->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_users_government_id_path" class="el_users_government_id_path">
<span<?= $Page->government_id_path->viewAttributes() ?>>
<?= $Page->government_id_path->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }

    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close result set
$Page->Recordset?->free();
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
<div id="ew-footer-options">
<?php $Page->FooterOptions?->render("body") ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
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
