<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotaryQrSettingsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notary_qr_settings: currentTable } });
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
<?php if (!$Page->IsModal) { ?>
<form name="fnotary_qr_settingssrch" id="fnotary_qr_settingssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fnotary_qr_settingssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notary_qr_settings: currentTable } });
var currentForm;
var fnotary_qr_settingssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fnotary_qr_settingssrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fnotary_qr_settingssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fnotary_qr_settingssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fnotary_qr_settingssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fnotary_qr_settingssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="notary_qr_settings">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_notary_qr_settings" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_notary_qr_settingslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->settings_id->Visible) { // settings_id ?>
        <th data-name="settings_id" class="<?= $Page->settings_id->headerCellClass() ?>"><div id="elh_notary_qr_settings_settings_id" class="notary_qr_settings_settings_id"><?= $Page->renderFieldHeader($Page->settings_id) ?></div></th>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <th data-name="notary_id" class="<?= $Page->notary_id->headerCellClass() ?>"><div id="elh_notary_qr_settings_notary_id" class="notary_qr_settings_notary_id"><?= $Page->renderFieldHeader($Page->notary_id) ?></div></th>
<?php } ?>
<?php if ($Page->default_size->Visible) { // default_size ?>
        <th data-name="default_size" class="<?= $Page->default_size->headerCellClass() ?>"><div id="elh_notary_qr_settings_default_size" class="notary_qr_settings_default_size"><?= $Page->renderFieldHeader($Page->default_size) ?></div></th>
<?php } ?>
<?php if ($Page->foreground_color->Visible) { // foreground_color ?>
        <th data-name="foreground_color" class="<?= $Page->foreground_color->headerCellClass() ?>"><div id="elh_notary_qr_settings_foreground_color" class="notary_qr_settings_foreground_color"><?= $Page->renderFieldHeader($Page->foreground_color) ?></div></th>
<?php } ?>
<?php if ($Page->background_color->Visible) { // background_color ?>
        <th data-name="background_color" class="<?= $Page->background_color->headerCellClass() ?>"><div id="elh_notary_qr_settings_background_color" class="notary_qr_settings_background_color"><?= $Page->renderFieldHeader($Page->background_color) ?></div></th>
<?php } ?>
<?php if ($Page->logo_path->Visible) { // logo_path ?>
        <th data-name="logo_path" class="<?= $Page->logo_path->headerCellClass() ?>"><div id="elh_notary_qr_settings_logo_path" class="notary_qr_settings_logo_path"><?= $Page->renderFieldHeader($Page->logo_path) ?></div></th>
<?php } ?>
<?php if ($Page->logo_size_percent->Visible) { // logo_size_percent ?>
        <th data-name="logo_size_percent" class="<?= $Page->logo_size_percent->headerCellClass() ?>"><div id="elh_notary_qr_settings_logo_size_percent" class="notary_qr_settings_logo_size_percent"><?= $Page->renderFieldHeader($Page->logo_size_percent) ?></div></th>
<?php } ?>
<?php if ($Page->error_correction->Visible) { // error_correction ?>
        <th data-name="error_correction" class="<?= $Page->error_correction->headerCellClass() ?>"><div id="elh_notary_qr_settings_error_correction" class="notary_qr_settings_error_correction"><?= $Page->renderFieldHeader($Page->error_correction) ?></div></th>
<?php } ?>
<?php if ($Page->corner_radius_percent->Visible) { // corner_radius_percent ?>
        <th data-name="corner_radius_percent" class="<?= $Page->corner_radius_percent->headerCellClass() ?>"><div id="elh_notary_qr_settings_corner_radius_percent" class="notary_qr_settings_corner_radius_percent"><?= $Page->renderFieldHeader($Page->corner_radius_percent) ?></div></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th data-name="created_at" class="<?= $Page->created_at->headerCellClass() ?>"><div id="elh_notary_qr_settings_created_at" class="notary_qr_settings_created_at"><?= $Page->renderFieldHeader($Page->created_at) ?></div></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th data-name="updated_at" class="<?= $Page->updated_at->headerCellClass() ?>"><div id="elh_notary_qr_settings_updated_at" class="notary_qr_settings_updated_at"><?= $Page->renderFieldHeader($Page->updated_at) ?></div></th>
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
    <?php if ($Page->settings_id->Visible) { // settings_id ?>
        <td data-name="settings_id"<?= $Page->settings_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_settings_id" class="el_notary_qr_settings_settings_id">
<span<?= $Page->settings_id->viewAttributes() ?>>
<?= $Page->settings_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->notary_id->Visible) { // notary_id ?>
        <td data-name="notary_id"<?= $Page->notary_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_notary_id" class="el_notary_qr_settings_notary_id">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->default_size->Visible) { // default_size ?>
        <td data-name="default_size"<?= $Page->default_size->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_default_size" class="el_notary_qr_settings_default_size">
<span<?= $Page->default_size->viewAttributes() ?>>
<?= $Page->default_size->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->foreground_color->Visible) { // foreground_color ?>
        <td data-name="foreground_color"<?= $Page->foreground_color->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_foreground_color" class="el_notary_qr_settings_foreground_color">
<span<?= $Page->foreground_color->viewAttributes() ?>>
<?= $Page->foreground_color->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->background_color->Visible) { // background_color ?>
        <td data-name="background_color"<?= $Page->background_color->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_background_color" class="el_notary_qr_settings_background_color">
<span<?= $Page->background_color->viewAttributes() ?>>
<?= $Page->background_color->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->logo_path->Visible) { // logo_path ?>
        <td data-name="logo_path"<?= $Page->logo_path->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_logo_path" class="el_notary_qr_settings_logo_path">
<span<?= $Page->logo_path->viewAttributes() ?>>
<?= $Page->logo_path->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->logo_size_percent->Visible) { // logo_size_percent ?>
        <td data-name="logo_size_percent"<?= $Page->logo_size_percent->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_logo_size_percent" class="el_notary_qr_settings_logo_size_percent">
<span<?= $Page->logo_size_percent->viewAttributes() ?>>
<?= $Page->logo_size_percent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->error_correction->Visible) { // error_correction ?>
        <td data-name="error_correction"<?= $Page->error_correction->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_error_correction" class="el_notary_qr_settings_error_correction">
<span<?= $Page->error_correction->viewAttributes() ?>>
<?= $Page->error_correction->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->corner_radius_percent->Visible) { // corner_radius_percent ?>
        <td data-name="corner_radius_percent"<?= $Page->corner_radius_percent->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_corner_radius_percent" class="el_notary_qr_settings_corner_radius_percent">
<span<?= $Page->corner_radius_percent->viewAttributes() ?>>
<?= $Page->corner_radius_percent->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->created_at->Visible) { // created_at ?>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_created_at" class="el_notary_qr_settings_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_notary_qr_settings_updated_at" class="el_notary_qr_settings_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
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
    ew.addEventHandlers("notary_qr_settings");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
