<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentAttachmentsView = &$Page;
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
<form name="fdocument_attachmentsview" id="fdocument_attachmentsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_attachments: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fdocument_attachmentsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_attachmentsview")
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
<input type="hidden" name="t" value="document_attachments">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->attachment_id->Visible) { // attachment_id ?>
    <tr id="r_attachment_id"<?= $Page->attachment_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_attachment_id"><?= $Page->attachment_id->caption() ?></span></td>
        <td data-name="attachment_id"<?= $Page->attachment_id->cellAttributes() ?>>
<span id="el_document_attachments_attachment_id">
<span<?= $Page->attachment_id->viewAttributes() ?>>
<?= $Page->attachment_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <tr id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_document_id"><?= $Page->document_id->caption() ?></span></td>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el_document_attachments_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_name->Visible) { // file_name ?>
    <tr id="r_file_name"<?= $Page->file_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_file_name"><?= $Page->file_name->caption() ?></span></td>
        <td data-name="file_name"<?= $Page->file_name->cellAttributes() ?>>
<span id="el_document_attachments_file_name">
<span<?= $Page->file_name->viewAttributes() ?>>
<?= $Page->file_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
    <tr id="r_file_path"<?= $Page->file_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_file_path"><?= $Page->file_path->caption() ?></span></td>
        <td data-name="file_path"<?= $Page->file_path->cellAttributes() ?>>
<span id="el_document_attachments_file_path">
<span<?= $Page->file_path->viewAttributes() ?>>
<?= $Page->file_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_type->Visible) { // file_type ?>
    <tr id="r_file_type"<?= $Page->file_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_file_type"><?= $Page->file_type->caption() ?></span></td>
        <td data-name="file_type"<?= $Page->file_type->cellAttributes() ?>>
<span id="el_document_attachments_file_type">
<span<?= $Page->file_type->viewAttributes() ?>>
<?= $Page->file_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_size->Visible) { // file_size ?>
    <tr id="r_file_size"<?= $Page->file_size->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_file_size"><?= $Page->file_size->caption() ?></span></td>
        <td data-name="file_size"<?= $Page->file_size->cellAttributes() ?>>
<span id="el_document_attachments_file_size">
<span<?= $Page->file_size->viewAttributes() ?>>
<?= $Page->file_size->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->uploaded_at->Visible) { // uploaded_at ?>
    <tr id="r_uploaded_at"<?= $Page->uploaded_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_uploaded_at"><?= $Page->uploaded_at->caption() ?></span></td>
        <td data-name="uploaded_at"<?= $Page->uploaded_at->cellAttributes() ?>>
<span id="el_document_attachments_uploaded_at">
<span<?= $Page->uploaded_at->viewAttributes() ?>>
<?= $Page->uploaded_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->uploaded_by->Visible) { // uploaded_by ?>
    <tr id="r_uploaded_by"<?= $Page->uploaded_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_uploaded_by"><?= $Page->uploaded_by->caption() ?></span></td>
        <td data-name="uploaded_by"<?= $Page->uploaded_by->cellAttributes() ?>>
<span id="el_document_attachments_uploaded_by">
<span<?= $Page->uploaded_by->viewAttributes() ?>>
<?= $Page->uploaded_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <tr id="r_description"<?= $Page->description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_description"><?= $Page->description->caption() ?></span></td>
        <td data-name="description"<?= $Page->description->cellAttributes() ?>>
<span id="el_document_attachments_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_supporting->Visible) { // is_supporting ?>
    <tr id="r_is_supporting"<?= $Page->is_supporting->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_attachments_is_supporting"><?= $Page->is_supporting->caption() ?></span></td>
        <td data-name="is_supporting"<?= $Page->is_supporting->cellAttributes() ?>>
<span id="el_document_attachments_is_supporting">
<span<?= $Page->is_supporting->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_supporting->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
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
