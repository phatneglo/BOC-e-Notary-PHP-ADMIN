<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
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
<form name="fdocumentssrch" id="fdocumentssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fdocumentssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
var currentForm;
var fdocumentssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fdocumentssrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fdocumentssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fdocumentssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fdocumentssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fdocumentssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="documents">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_documents" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_documentslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th data-name="document_id" class="<?= $Page->document_id->headerCellClass() ?>"><div id="elh_documents_document_id" class="documents_document_id"><?= $Page->renderFieldHeader($Page->document_id) ?></div></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th data-name="user_id" class="<?= $Page->user_id->headerCellClass() ?>"><div id="elh_documents_user_id" class="documents_user_id"><?= $Page->renderFieldHeader($Page->user_id) ?></div></th>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
        <th data-name="template_id" class="<?= $Page->template_id->headerCellClass() ?>"><div id="elh_documents_template_id" class="documents_template_id"><?= $Page->renderFieldHeader($Page->template_id) ?></div></th>
<?php } ?>
<?php if ($Page->document_title->Visible) { // document_title ?>
        <th data-name="document_title" class="<?= $Page->document_title->headerCellClass() ?>"><div id="elh_documents_document_title" class="documents_document_title"><?= $Page->renderFieldHeader($Page->document_title) ?></div></th>
<?php } ?>
<?php if ($Page->document_reference->Visible) { // document_reference ?>
        <th data-name="document_reference" class="<?= $Page->document_reference->headerCellClass() ?>"><div id="elh_documents_document_reference" class="documents_document_reference"><?= $Page->renderFieldHeader($Page->document_reference) ?></div></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th data-name="status" class="<?= $Page->status->headerCellClass() ?>"><div id="elh_documents_status" class="documents_status"><?= $Page->renderFieldHeader($Page->status) ?></div></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th data-name="created_at" class="<?= $Page->created_at->headerCellClass() ?>"><div id="elh_documents_created_at" class="documents_created_at"><?= $Page->renderFieldHeader($Page->created_at) ?></div></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th data-name="updated_at" class="<?= $Page->updated_at->headerCellClass() ?>"><div id="elh_documents_updated_at" class="documents_updated_at"><?= $Page->renderFieldHeader($Page->updated_at) ?></div></th>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
        <th data-name="submitted_at" class="<?= $Page->submitted_at->headerCellClass() ?>"><div id="elh_documents_submitted_at" class="documents_submitted_at"><?= $Page->renderFieldHeader($Page->submitted_at) ?></div></th>
<?php } ?>
<?php if ($Page->company_name->Visible) { // company_name ?>
        <th data-name="company_name" class="<?= $Page->company_name->headerCellClass() ?>"><div id="elh_documents_company_name" class="documents_company_name"><?= $Page->renderFieldHeader($Page->company_name) ?></div></th>
<?php } ?>
<?php if ($Page->customs_entry_number->Visible) { // customs_entry_number ?>
        <th data-name="customs_entry_number" class="<?= $Page->customs_entry_number->headerCellClass() ?>"><div id="elh_documents_customs_entry_number" class="documents_customs_entry_number"><?= $Page->renderFieldHeader($Page->customs_entry_number) ?></div></th>
<?php } ?>
<?php if ($Page->date_of_entry->Visible) { // date_of_entry ?>
        <th data-name="date_of_entry" class="<?= $Page->date_of_entry->headerCellClass() ?>"><div id="elh_documents_date_of_entry" class="documents_date_of_entry"><?= $Page->renderFieldHeader($Page->date_of_entry) ?></div></th>
<?php } ?>
<?php if ($Page->is_deleted->Visible) { // is_deleted ?>
        <th data-name="is_deleted" class="<?= $Page->is_deleted->headerCellClass() ?>"><div id="elh_documents_is_deleted" class="documents_is_deleted"><?= $Page->renderFieldHeader($Page->is_deleted) ?></div></th>
<?php } ?>
<?php if ($Page->deletion_date->Visible) { // deletion_date ?>
        <th data-name="deletion_date" class="<?= $Page->deletion_date->headerCellClass() ?>"><div id="elh_documents_deletion_date" class="documents_deletion_date"><?= $Page->renderFieldHeader($Page->deletion_date) ?></div></th>
<?php } ?>
<?php if ($Page->deleted_by->Visible) { // deleted_by ?>
        <th data-name="deleted_by" class="<?= $Page->deleted_by->headerCellClass() ?>"><div id="elh_documents_deleted_by" class="documents_deleted_by"><?= $Page->renderFieldHeader($Page->deleted_by) ?></div></th>
<?php } ?>
<?php if ($Page->parent_document_id->Visible) { // parent_document_id ?>
        <th data-name="parent_document_id" class="<?= $Page->parent_document_id->headerCellClass() ?>"><div id="elh_documents_parent_document_id" class="documents_parent_document_id"><?= $Page->renderFieldHeader($Page->parent_document_id) ?></div></th>
<?php } ?>
<?php if ($Page->version->Visible) { // version ?>
        <th data-name="version" class="<?= $Page->version->headerCellClass() ?>"><div id="elh_documents_version" class="documents_version"><?= $Page->renderFieldHeader($Page->version) ?></div></th>
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
    <?php if ($Page->document_id->Visible) { // document_id ?>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_document_id" class="el_documents_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->user_id->Visible) { // user_id ?>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_user_id" class="el_documents_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->template_id->Visible) { // template_id ?>
        <td data-name="template_id"<?= $Page->template_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_template_id" class="el_documents_template_id">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->document_title->Visible) { // document_title ?>
        <td data-name="document_title"<?= $Page->document_title->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_document_title" class="el_documents_document_title">
<span<?= $Page->document_title->viewAttributes() ?>>
<?= $Page->document_title->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->document_reference->Visible) { // document_reference ?>
        <td data-name="document_reference"<?= $Page->document_reference->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_document_reference" class="el_documents_document_reference">
<span<?= $Page->document_reference->viewAttributes() ?>>
<?= $Page->document_reference->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_status" class="el_documents_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->created_at->Visible) { // created_at ?>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_created_at" class="el_documents_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_updated_at" class="el_documents_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->submitted_at->Visible) { // submitted_at ?>
        <td data-name="submitted_at"<?= $Page->submitted_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_submitted_at" class="el_documents_submitted_at">
<span<?= $Page->submitted_at->viewAttributes() ?>>
<?= $Page->submitted_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->company_name->Visible) { // company_name ?>
        <td data-name="company_name"<?= $Page->company_name->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_company_name" class="el_documents_company_name">
<span<?= $Page->company_name->viewAttributes() ?>>
<?= $Page->company_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->customs_entry_number->Visible) { // customs_entry_number ?>
        <td data-name="customs_entry_number"<?= $Page->customs_entry_number->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_customs_entry_number" class="el_documents_customs_entry_number">
<span<?= $Page->customs_entry_number->viewAttributes() ?>>
<?= $Page->customs_entry_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->date_of_entry->Visible) { // date_of_entry ?>
        <td data-name="date_of_entry"<?= $Page->date_of_entry->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_date_of_entry" class="el_documents_date_of_entry">
<span<?= $Page->date_of_entry->viewAttributes() ?>>
<?= $Page->date_of_entry->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->is_deleted->Visible) { // is_deleted ?>
        <td data-name="is_deleted"<?= $Page->is_deleted->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_is_deleted" class="el_documents_is_deleted">
<span<?= $Page->is_deleted->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_deleted->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->deletion_date->Visible) { // deletion_date ?>
        <td data-name="deletion_date"<?= $Page->deletion_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_deletion_date" class="el_documents_deletion_date">
<span<?= $Page->deletion_date->viewAttributes() ?>>
<?= $Page->deletion_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->deleted_by->Visible) { // deleted_by ?>
        <td data-name="deleted_by"<?= $Page->deleted_by->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_deleted_by" class="el_documents_deleted_by">
<span<?= $Page->deleted_by->viewAttributes() ?>>
<?= $Page->deleted_by->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->parent_document_id->Visible) { // parent_document_id ?>
        <td data-name="parent_document_id"<?= $Page->parent_document_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_parent_document_id" class="el_documents_parent_document_id">
<span<?= $Page->parent_document_id->viewAttributes() ?>>
<?= $Page->parent_document_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->version->Visible) { // version ?>
        <td data-name="version"<?= $Page->version->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_documents_version" class="el_documents_version">
<span<?= $Page->version->viewAttributes() ?>>
<?= $Page->version->getViewValue() ?></span>
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
    ew.addEventHandlers("documents");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
