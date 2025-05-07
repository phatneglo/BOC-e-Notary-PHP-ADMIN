<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentFieldsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_fields: currentTable } });
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
</div>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
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
<input type="hidden" name="t" value="document_fields">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_document_fields" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_document_fieldslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->document_field_id->Visible) { // document_field_id ?>
        <th data-name="document_field_id" class="<?= $Page->document_field_id->headerCellClass() ?>"><div id="elh_document_fields_document_field_id" class="document_fields_document_field_id"><?= $Page->renderFieldHeader($Page->document_field_id) ?></div></th>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th data-name="document_id" class="<?= $Page->document_id->headerCellClass() ?>"><div id="elh_document_fields_document_id" class="document_fields_document_id"><?= $Page->renderFieldHeader($Page->document_id) ?></div></th>
<?php } ?>
<?php if ($Page->field_id->Visible) { // field_id ?>
        <th data-name="field_id" class="<?= $Page->field_id->headerCellClass() ?>"><div id="elh_document_fields_field_id" class="document_fields_field_id"><?= $Page->renderFieldHeader($Page->field_id) ?></div></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th data-name="updated_at" class="<?= $Page->updated_at->headerCellClass() ?>"><div id="elh_document_fields_updated_at" class="document_fields_updated_at"><?= $Page->renderFieldHeader($Page->updated_at) ?></div></th>
<?php } ?>
<?php if ($Page->is_verified->Visible) { // is_verified ?>
        <th data-name="is_verified" class="<?= $Page->is_verified->headerCellClass() ?>"><div id="elh_document_fields_is_verified" class="document_fields_is_verified"><?= $Page->renderFieldHeader($Page->is_verified) ?></div></th>
<?php } ?>
<?php if ($Page->verified_by->Visible) { // verified_by ?>
        <th data-name="verified_by" class="<?= $Page->verified_by->headerCellClass() ?>"><div id="elh_document_fields_verified_by" class="document_fields_verified_by"><?= $Page->renderFieldHeader($Page->verified_by) ?></div></th>
<?php } ?>
<?php if ($Page->verification_date->Visible) { // verification_date ?>
        <th data-name="verification_date" class="<?= $Page->verification_date->headerCellClass() ?>"><div id="elh_document_fields_verification_date" class="document_fields_verification_date"><?= $Page->renderFieldHeader($Page->verification_date) ?></div></th>
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
    <?php if ($Page->document_field_id->Visible) { // document_field_id ?>
        <td data-name="document_field_id"<?= $Page->document_field_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_fields_document_field_id" class="el_document_fields_document_field_id">
<span<?= $Page->document_field_id->viewAttributes() ?>>
<?= $Page->document_field_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->document_id->Visible) { // document_id ?>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_fields_document_id" class="el_document_fields_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->field_id->Visible) { // field_id ?>
        <td data-name="field_id"<?= $Page->field_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_fields_field_id" class="el_document_fields_field_id">
<span<?= $Page->field_id->viewAttributes() ?>>
<?= $Page->field_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_fields_updated_at" class="el_document_fields_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->is_verified->Visible) { // is_verified ?>
        <td data-name="is_verified"<?= $Page->is_verified->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_fields_is_verified" class="el_document_fields_is_verified">
<span<?= $Page->is_verified->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_verified->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->verified_by->Visible) { // verified_by ?>
        <td data-name="verified_by"<?= $Page->verified_by->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_fields_verified_by" class="el_document_fields_verified_by">
<span<?= $Page->verified_by->viewAttributes() ?>>
<?= $Page->verified_by->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->verification_date->Visible) { // verification_date ?>
        <td data-name="verification_date"<?= $Page->verification_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_document_fields_verification_date" class="el_document_fields_verification_date">
<span<?= $Page->verification_date->viewAttributes() ?>>
<?= $Page->verification_date->getViewValue() ?></span>
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
    ew.addEventHandlers("document_fields");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
