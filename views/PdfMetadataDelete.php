<?php

namespace PHPMaker2024\eNotary;

// Page object
$PdfMetadataDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pdf_metadata: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fpdf_metadatadelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpdf_metadatadelete")
        .setPageId("delete")
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpdf_metadatadelete" id="fpdf_metadatadelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pdf_metadata">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->metadata_id->Visible) { // metadata_id ?>
        <th class="<?= $Page->metadata_id->headerCellClass() ?>"><span id="elh_pdf_metadata_metadata_id" class="pdf_metadata_metadata_id"><?= $Page->metadata_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th class="<?= $Page->document_id->headerCellClass() ?>"><span id="elh_pdf_metadata_document_id" class="pdf_metadata_document_id"><?= $Page->document_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <th class="<?= $Page->notarized_id->headerCellClass() ?>"><span id="elh_pdf_metadata_notarized_id" class="pdf_metadata_notarized_id"><?= $Page->notarized_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pdf_type->Visible) { // pdf_type ?>
        <th class="<?= $Page->pdf_type->headerCellClass() ?>"><span id="elh_pdf_metadata_pdf_type" class="pdf_metadata_pdf_type"><?= $Page->pdf_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
        <th class="<?= $Page->file_path->headerCellClass() ?>"><span id="elh_pdf_metadata_file_path" class="pdf_metadata_file_path"><?= $Page->file_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_size->Visible) { // file_size ?>
        <th class="<?= $Page->file_size->headerCellClass() ?>"><span id="elh_pdf_metadata_file_size" class="pdf_metadata_file_size"><?= $Page->file_size->caption() ?></span></th>
<?php } ?>
<?php if ($Page->page_count->Visible) { // page_count ?>
        <th class="<?= $Page->page_count->headerCellClass() ?>"><span id="elh_pdf_metadata_page_count" class="pdf_metadata_page_count"><?= $Page->page_count->caption() ?></span></th>
<?php } ?>
<?php if ($Page->generated_at->Visible) { // generated_at ?>
        <th class="<?= $Page->generated_at->headerCellClass() ?>"><span id="elh_pdf_metadata_generated_at" class="pdf_metadata_generated_at"><?= $Page->generated_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->generated_by->Visible) { // generated_by ?>
        <th class="<?= $Page->generated_by->headerCellClass() ?>"><span id="elh_pdf_metadata_generated_by" class="pdf_metadata_generated_by"><?= $Page->generated_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
        <th class="<?= $Page->expires_at->headerCellClass() ?>"><span id="elh_pdf_metadata_expires_at" class="pdf_metadata_expires_at"><?= $Page->expires_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_final->Visible) { // is_final ?>
        <th class="<?= $Page->is_final->headerCellClass() ?>"><span id="elh_pdf_metadata_is_final" class="pdf_metadata_is_final"><?= $Page->is_final->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while ($Page->fetch()) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = RowType::VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->CurrentRow);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->metadata_id->Visible) { // metadata_id ?>
        <td<?= $Page->metadata_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->metadata_id->viewAttributes() ?>>
<?= $Page->metadata_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <td<?= $Page->document_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <td<?= $Page->notarized_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->notarized_id->viewAttributes() ?>>
<?= $Page->notarized_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pdf_type->Visible) { // pdf_type ?>
        <td<?= $Page->pdf_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->pdf_type->viewAttributes() ?>>
<?= $Page->pdf_type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
        <td<?= $Page->file_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->file_path->viewAttributes() ?>>
<?= $Page->file_path->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_size->Visible) { // file_size ?>
        <td<?= $Page->file_size->cellAttributes() ?>>
<span id="">
<span<?= $Page->file_size->viewAttributes() ?>>
<?= $Page->file_size->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->page_count->Visible) { // page_count ?>
        <td<?= $Page->page_count->cellAttributes() ?>>
<span id="">
<span<?= $Page->page_count->viewAttributes() ?>>
<?= $Page->page_count->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->generated_at->Visible) { // generated_at ?>
        <td<?= $Page->generated_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->generated_at->viewAttributes() ?>>
<?= $Page->generated_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->generated_by->Visible) { // generated_by ?>
        <td<?= $Page->generated_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->generated_by->viewAttributes() ?>>
<?= $Page->generated_by->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
        <td<?= $Page->expires_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->expires_at->viewAttributes() ?>>
<?= $Page->expires_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_final->Visible) { // is_final ?>
        <td<?= $Page->is_final->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_final->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_final->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
    </tr>
<?php
}
$Page->Recordset?->free();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
