<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fdocumentsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocumentsdelete")
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
<form name="fdocumentsdelete" id="fdocumentsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="documents">
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
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th class="<?= $Page->document_id->headerCellClass() ?>"><span id="elh_documents_document_id" class="documents_document_id"><?= $Page->document_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th class="<?= $Page->user_id->headerCellClass() ?>"><span id="elh_documents_user_id" class="documents_user_id"><?= $Page->user_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
        <th class="<?= $Page->template_id->headerCellClass() ?>"><span id="elh_documents_template_id" class="documents_template_id"><?= $Page->template_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_title->Visible) { // document_title ?>
        <th class="<?= $Page->document_title->headerCellClass() ?>"><span id="elh_documents_document_title" class="documents_document_title"><?= $Page->document_title->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_reference->Visible) { // document_reference ?>
        <th class="<?= $Page->document_reference->headerCellClass() ?>"><span id="elh_documents_document_reference" class="documents_document_reference"><?= $Page->document_reference->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_documents_status" class="documents_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_documents_created_at" class="documents_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_documents_updated_at" class="documents_updated_at"><?= $Page->updated_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
        <th class="<?= $Page->submitted_at->headerCellClass() ?>"><span id="elh_documents_submitted_at" class="documents_submitted_at"><?= $Page->submitted_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->company_name->Visible) { // company_name ?>
        <th class="<?= $Page->company_name->headerCellClass() ?>"><span id="elh_documents_company_name" class="documents_company_name"><?= $Page->company_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->customs_entry_number->Visible) { // customs_entry_number ?>
        <th class="<?= $Page->customs_entry_number->headerCellClass() ?>"><span id="elh_documents_customs_entry_number" class="documents_customs_entry_number"><?= $Page->customs_entry_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->date_of_entry->Visible) { // date_of_entry ?>
        <th class="<?= $Page->date_of_entry->headerCellClass() ?>"><span id="elh_documents_date_of_entry" class="documents_date_of_entry"><?= $Page->date_of_entry->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_deleted->Visible) { // is_deleted ?>
        <th class="<?= $Page->is_deleted->headerCellClass() ?>"><span id="elh_documents_is_deleted" class="documents_is_deleted"><?= $Page->is_deleted->caption() ?></span></th>
<?php } ?>
<?php if ($Page->deletion_date->Visible) { // deletion_date ?>
        <th class="<?= $Page->deletion_date->headerCellClass() ?>"><span id="elh_documents_deletion_date" class="documents_deletion_date"><?= $Page->deletion_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->deleted_by->Visible) { // deleted_by ?>
        <th class="<?= $Page->deleted_by->headerCellClass() ?>"><span id="elh_documents_deleted_by" class="documents_deleted_by"><?= $Page->deleted_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->parent_document_id->Visible) { // parent_document_id ?>
        <th class="<?= $Page->parent_document_id->headerCellClass() ?>"><span id="elh_documents_parent_document_id" class="documents_parent_document_id"><?= $Page->parent_document_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->version->Visible) { // version ?>
        <th class="<?= $Page->version->headerCellClass() ?>"><span id="elh_documents_version" class="documents_version"><?= $Page->version->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
        <th class="<?= $Page->status_id->headerCellClass() ?>"><span id="elh_documents_status_id" class="documents_status_id"><?= $Page->status_id->caption() ?></span></th>
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
<?php if ($Page->document_id->Visible) { // document_id ?>
        <td<?= $Page->document_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <td<?= $Page->user_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
        <td<?= $Page->template_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->document_title->Visible) { // document_title ?>
        <td<?= $Page->document_title->cellAttributes() ?>>
<span id="">
<span<?= $Page->document_title->viewAttributes() ?>>
<?= $Page->document_title->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->document_reference->Visible) { // document_reference ?>
        <td<?= $Page->document_reference->cellAttributes() ?>>
<span id="">
<span<?= $Page->document_reference->viewAttributes() ?>>
<?= $Page->document_reference->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <td<?= $Page->status->cellAttributes() ?>>
<span id="">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <td<?= $Page->created_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td<?= $Page->updated_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
        <td<?= $Page->submitted_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->submitted_at->viewAttributes() ?>>
<?= $Page->submitted_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->company_name->Visible) { // company_name ?>
        <td<?= $Page->company_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->company_name->viewAttributes() ?>>
<?= $Page->company_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->customs_entry_number->Visible) { // customs_entry_number ?>
        <td<?= $Page->customs_entry_number->cellAttributes() ?>>
<span id="">
<span<?= $Page->customs_entry_number->viewAttributes() ?>>
<?= $Page->customs_entry_number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->date_of_entry->Visible) { // date_of_entry ?>
        <td<?= $Page->date_of_entry->cellAttributes() ?>>
<span id="">
<span<?= $Page->date_of_entry->viewAttributes() ?>>
<?= $Page->date_of_entry->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_deleted->Visible) { // is_deleted ?>
        <td<?= $Page->is_deleted->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_deleted->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_deleted->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->deletion_date->Visible) { // deletion_date ?>
        <td<?= $Page->deletion_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->deletion_date->viewAttributes() ?>>
<?= $Page->deletion_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->deleted_by->Visible) { // deleted_by ?>
        <td<?= $Page->deleted_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->deleted_by->viewAttributes() ?>>
<?= $Page->deleted_by->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->parent_document_id->Visible) { // parent_document_id ?>
        <td<?= $Page->parent_document_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->parent_document_id->viewAttributes() ?>>
<?= $Page->parent_document_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->version->Visible) { // version ?>
        <td<?= $Page->version->cellAttributes() ?>>
<span id="">
<span<?= $Page->version->viewAttributes() ?>>
<?= $Page->version->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
        <td<?= $Page->status_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->status_id->viewAttributes() ?>>
<?= $Page->status_id->getViewValue() ?></span>
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
