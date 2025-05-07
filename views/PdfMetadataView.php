<?php

namespace PHPMaker2024\eNotary;

// Page object
$PdfMetadataView = &$Page;
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
<form name="fpdf_metadataview" id="fpdf_metadataview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pdf_metadata: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fpdf_metadataview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpdf_metadataview")
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
<input type="hidden" name="t" value="pdf_metadata">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->metadata_id->Visible) { // metadata_id ?>
    <tr id="r_metadata_id"<?= $Page->metadata_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_metadata_id"><?= $Page->metadata_id->caption() ?></span></td>
        <td data-name="metadata_id"<?= $Page->metadata_id->cellAttributes() ?>>
<span id="el_pdf_metadata_metadata_id">
<span<?= $Page->metadata_id->viewAttributes() ?>>
<?= $Page->metadata_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <tr id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_document_id"><?= $Page->document_id->caption() ?></span></td>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el_pdf_metadata_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
    <tr id="r_notarized_id"<?= $Page->notarized_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_notarized_id"><?= $Page->notarized_id->caption() ?></span></td>
        <td data-name="notarized_id"<?= $Page->notarized_id->cellAttributes() ?>>
<span id="el_pdf_metadata_notarized_id">
<span<?= $Page->notarized_id->viewAttributes() ?>>
<?= $Page->notarized_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pdf_type->Visible) { // pdf_type ?>
    <tr id="r_pdf_type"<?= $Page->pdf_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_pdf_type"><?= $Page->pdf_type->caption() ?></span></td>
        <td data-name="pdf_type"<?= $Page->pdf_type->cellAttributes() ?>>
<span id="el_pdf_metadata_pdf_type">
<span<?= $Page->pdf_type->viewAttributes() ?>>
<?= $Page->pdf_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
    <tr id="r_file_path"<?= $Page->file_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_file_path"><?= $Page->file_path->caption() ?></span></td>
        <td data-name="file_path"<?= $Page->file_path->cellAttributes() ?>>
<span id="el_pdf_metadata_file_path">
<span<?= $Page->file_path->viewAttributes() ?>>
<?= $Page->file_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_size->Visible) { // file_size ?>
    <tr id="r_file_size"<?= $Page->file_size->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_file_size"><?= $Page->file_size->caption() ?></span></td>
        <td data-name="file_size"<?= $Page->file_size->cellAttributes() ?>>
<span id="el_pdf_metadata_file_size">
<span<?= $Page->file_size->viewAttributes() ?>>
<?= $Page->file_size->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->page_count->Visible) { // page_count ?>
    <tr id="r_page_count"<?= $Page->page_count->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_page_count"><?= $Page->page_count->caption() ?></span></td>
        <td data-name="page_count"<?= $Page->page_count->cellAttributes() ?>>
<span id="el_pdf_metadata_page_count">
<span<?= $Page->page_count->viewAttributes() ?>>
<?= $Page->page_count->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->generated_at->Visible) { // generated_at ?>
    <tr id="r_generated_at"<?= $Page->generated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_generated_at"><?= $Page->generated_at->caption() ?></span></td>
        <td data-name="generated_at"<?= $Page->generated_at->cellAttributes() ?>>
<span id="el_pdf_metadata_generated_at">
<span<?= $Page->generated_at->viewAttributes() ?>>
<?= $Page->generated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->generated_by->Visible) { // generated_by ?>
    <tr id="r_generated_by"<?= $Page->generated_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_generated_by"><?= $Page->generated_by->caption() ?></span></td>
        <td data-name="generated_by"<?= $Page->generated_by->cellAttributes() ?>>
<span id="el_pdf_metadata_generated_by">
<span<?= $Page->generated_by->viewAttributes() ?>>
<?= $Page->generated_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
    <tr id="r_expires_at"<?= $Page->expires_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_expires_at"><?= $Page->expires_at->caption() ?></span></td>
        <td data-name="expires_at"<?= $Page->expires_at->cellAttributes() ?>>
<span id="el_pdf_metadata_expires_at">
<span<?= $Page->expires_at->viewAttributes() ?>>
<?= $Page->expires_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_final->Visible) { // is_final ?>
    <tr id="r_is_final"<?= $Page->is_final->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_is_final"><?= $Page->is_final->caption() ?></span></td>
        <td data-name="is_final"<?= $Page->is_final->cellAttributes() ?>>
<span id="el_pdf_metadata_is_final">
<span<?= $Page->is_final->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_final->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->processing_options->Visible) { // processing_options ?>
    <tr id="r_processing_options"<?= $Page->processing_options->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pdf_metadata_processing_options"><?= $Page->processing_options->caption() ?></span></td>
        <td data-name="processing_options"<?= $Page->processing_options->cellAttributes() ?>>
<span id="el_pdf_metadata_processing_options">
<span<?= $Page->processing_options->viewAttributes() ?>>
<?= $Page->processing_options->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
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
