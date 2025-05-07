<?php

namespace PHPMaker2024\eNotary;

// Page object
$VerificationAttemptsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { verification_attempts: currentTable } });
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
<form name="fverification_attemptssrch" id="fverification_attemptssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fverification_attemptssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { verification_attempts: currentTable } });
var currentForm;
var fverification_attemptssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fverification_attemptssrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fverification_attemptssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fverification_attemptssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fverification_attemptssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fverification_attemptssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="verification_attempts">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_verification_attempts" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_verification_attemptslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->attempt_id->Visible) { // attempt_id ?>
        <th data-name="attempt_id" class="<?= $Page->attempt_id->headerCellClass() ?>"><div id="elh_verification_attempts_attempt_id" class="verification_attempts_attempt_id"><?= $Page->renderFieldHeader($Page->attempt_id) ?></div></th>
<?php } ?>
<?php if ($Page->verification_id->Visible) { // verification_id ?>
        <th data-name="verification_id" class="<?= $Page->verification_id->headerCellClass() ?>"><div id="elh_verification_attempts_verification_id" class="verification_attempts_verification_id"><?= $Page->renderFieldHeader($Page->verification_id) ?></div></th>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
        <th data-name="document_number" class="<?= $Page->document_number->headerCellClass() ?>"><div id="elh_verification_attempts_document_number" class="verification_attempts_document_number"><?= $Page->renderFieldHeader($Page->document_number) ?></div></th>
<?php } ?>
<?php if ($Page->keycode->Visible) { // keycode ?>
        <th data-name="keycode" class="<?= $Page->keycode->headerCellClass() ?>"><div id="elh_verification_attempts_keycode" class="verification_attempts_keycode"><?= $Page->renderFieldHeader($Page->keycode) ?></div></th>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <th data-name="ip_address" class="<?= $Page->ip_address->headerCellClass() ?>"><div id="elh_verification_attempts_ip_address" class="verification_attempts_ip_address"><?= $Page->renderFieldHeader($Page->ip_address) ?></div></th>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
        <th data-name="verification_date" class="<?= $Page->verification_date->headerCellClass() ?>"><div id="elh_verification_attempts_verification_date" class="verification_attempts_verification_date"><?= $Page->renderFieldHeader($Page->verification_date) ?></div></th>
<?php } ?>
<?php if ($Page->is_successful->Visible) { // is_successful ?>
        <th data-name="is_successful" class="<?= $Page->is_successful->headerCellClass() ?>"><div id="elh_verification_attempts_is_successful" class="verification_attempts_is_successful"><?= $Page->renderFieldHeader($Page->is_successful) ?></div></th>
<?php } ?>
<?php if ($Page->location->Visible) { // location ?>
        <th data-name="location" class="<?= $Page->location->headerCellClass() ?>"><div id="elh_verification_attempts_location" class="verification_attempts_location"><?= $Page->renderFieldHeader($Page->location) ?></div></th>
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
    <?php if ($Page->attempt_id->Visible) { // attempt_id ?>
        <td data-name="attempt_id"<?= $Page->attempt_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_verification_attempts_attempt_id" class="el_verification_attempts_attempt_id">
<span<?= $Page->attempt_id->viewAttributes() ?>>
<?= $Page->attempt_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->verification_id->Visible) { // verification_id ?>
        <td data-name="verification_id"<?= $Page->verification_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_verification_attempts_verification_id" class="el_verification_attempts_verification_id">
<span<?= $Page->verification_id->viewAttributes() ?>>
<?= $Page->verification_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->document_number->Visible) { // document_number ?>
        <td data-name="document_number"<?= $Page->document_number->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_verification_attempts_document_number" class="el_verification_attempts_document_number">
<span<?= $Page->document_number->viewAttributes() ?>>
<?= $Page->document_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->keycode->Visible) { // keycode ?>
        <td data-name="keycode"<?= $Page->keycode->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_verification_attempts_keycode" class="el_verification_attempts_keycode">
<span<?= $Page->keycode->viewAttributes() ?>>
<?= $Page->keycode->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ip_address->Visible) { // ip_address ?>
        <td data-name="ip_address"<?= $Page->ip_address->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_verification_attempts_ip_address" class="el_verification_attempts_ip_address">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->verification_date->Visible) { // verification_date ?>
        <td data-name="verification_date"<?= $Page->verification_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_verification_attempts_verification_date" class="el_verification_attempts_verification_date">
<span<?= $Page->verification_date->viewAttributes() ?>>
<?= $Page->verification_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->is_successful->Visible) { // is_successful ?>
        <td data-name="is_successful"<?= $Page->is_successful->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_verification_attempts_is_successful" class="el_verification_attempts_is_successful">
<span<?= $Page->is_successful->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_successful->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->location->Visible) { // location ?>
        <td data-name="location"<?= $Page->location->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_verification_attempts_location" class="el_verification_attempts_location">
<span<?= $Page->location->viewAttributes() ?>>
<?= $Page->location->getViewValue() ?></span>
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
    ew.addEventHandlers("verification_attempts");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
