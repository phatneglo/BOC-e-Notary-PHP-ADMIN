<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentAttachmentsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_attachments: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fdocument_attachmentsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_attachmentsdelete")
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
<form name="fdocument_attachmentsdelete" id="fdocument_attachmentsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="document_attachments">
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
<?php if ($Page->attachment_id->Visible) { // attachment_id ?>
        <th class="<?= $Page->attachment_id->headerCellClass() ?>"><span id="elh_document_attachments_attachment_id" class="document_attachments_attachment_id"><?= $Page->attachment_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th class="<?= $Page->document_id->headerCellClass() ?>"><span id="elh_document_attachments_document_id" class="document_attachments_document_id"><?= $Page->document_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_name->Visible) { // file_name ?>
        <th class="<?= $Page->file_name->headerCellClass() ?>"><span id="elh_document_attachments_file_name" class="document_attachments_file_name"><?= $Page->file_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
        <th class="<?= $Page->file_path->headerCellClass() ?>"><span id="elh_document_attachments_file_path" class="document_attachments_file_path"><?= $Page->file_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_type->Visible) { // file_type ?>
        <th class="<?= $Page->file_type->headerCellClass() ?>"><span id="elh_document_attachments_file_type" class="document_attachments_file_type"><?= $Page->file_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_size->Visible) { // file_size ?>
        <th class="<?= $Page->file_size->headerCellClass() ?>"><span id="elh_document_attachments_file_size" class="document_attachments_file_size"><?= $Page->file_size->caption() ?></span></th>
<?php } ?>
<?php if ($Page->uploaded_at->Visible) { // uploaded_at ?>
        <th class="<?= $Page->uploaded_at->headerCellClass() ?>"><span id="elh_document_attachments_uploaded_at" class="document_attachments_uploaded_at"><?= $Page->uploaded_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->uploaded_by->Visible) { // uploaded_by ?>
        <th class="<?= $Page->uploaded_by->headerCellClass() ?>"><span id="elh_document_attachments_uploaded_by" class="document_attachments_uploaded_by"><?= $Page->uploaded_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_supporting->Visible) { // is_supporting ?>
        <th class="<?= $Page->is_supporting->headerCellClass() ?>"><span id="elh_document_attachments_is_supporting" class="document_attachments_is_supporting"><?= $Page->is_supporting->caption() ?></span></th>
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
<?php if ($Page->attachment_id->Visible) { // attachment_id ?>
        <td<?= $Page->attachment_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->attachment_id->viewAttributes() ?>>
<?= $Page->attachment_id->getViewValue() ?></span>
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
<?php if ($Page->file_name->Visible) { // file_name ?>
        <td<?= $Page->file_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->file_name->viewAttributes() ?>>
<?= $Page->file_name->getViewValue() ?></span>
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
<?php if ($Page->file_type->Visible) { // file_type ?>
        <td<?= $Page->file_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->file_type->viewAttributes() ?>>
<?= $Page->file_type->getViewValue() ?></span>
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
<?php if ($Page->uploaded_at->Visible) { // uploaded_at ?>
        <td<?= $Page->uploaded_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->uploaded_at->viewAttributes() ?>>
<?= $Page->uploaded_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->uploaded_by->Visible) { // uploaded_by ?>
        <td<?= $Page->uploaded_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->uploaded_by->viewAttributes() ?>>
<?= $Page->uploaded_by->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_supporting->Visible) { // is_supporting ?>
        <td<?= $Page->is_supporting->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_supporting->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_supporting->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
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
