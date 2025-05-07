<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentsView = &$Page;
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
<form name="fdocumentsview" id="fdocumentsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fdocumentsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocumentsview")
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
<input type="hidden" name="t" value="documents">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->document_id->Visible) { // document_id ?>
    <tr id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_document_id"><?= $Page->document_id->caption() ?></span></td>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el_documents_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_documents_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
    <tr id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_template_id"><?= $Page->template_id->caption() ?></span></td>
        <td data-name="template_id"<?= $Page->template_id->cellAttributes() ?>>
<span id="el_documents_template_id">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_title->Visible) { // document_title ?>
    <tr id="r_document_title"<?= $Page->document_title->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_document_title"><?= $Page->document_title->caption() ?></span></td>
        <td data-name="document_title"<?= $Page->document_title->cellAttributes() ?>>
<span id="el_documents_document_title">
<span<?= $Page->document_title->viewAttributes() ?>>
<?= $Page->document_title->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_reference->Visible) { // document_reference ?>
    <tr id="r_document_reference"<?= $Page->document_reference->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_document_reference"><?= $Page->document_reference->caption() ?></span></td>
        <td data-name="document_reference"<?= $Page->document_reference->cellAttributes() ?>>
<span id="el_documents_document_reference">
<span<?= $Page->document_reference->viewAttributes() ?>>
<?= $Page->document_reference->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status"<?= $Page->status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el_documents_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_documents_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_documents_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
    <tr id="r_submitted_at"<?= $Page->submitted_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_submitted_at"><?= $Page->submitted_at->caption() ?></span></td>
        <td data-name="submitted_at"<?= $Page->submitted_at->cellAttributes() ?>>
<span id="el_documents_submitted_at">
<span<?= $Page->submitted_at->viewAttributes() ?>>
<?= $Page->submitted_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->company_name->Visible) { // company_name ?>
    <tr id="r_company_name"<?= $Page->company_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_company_name"><?= $Page->company_name->caption() ?></span></td>
        <td data-name="company_name"<?= $Page->company_name->cellAttributes() ?>>
<span id="el_documents_company_name">
<span<?= $Page->company_name->viewAttributes() ?>>
<?= $Page->company_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->customs_entry_number->Visible) { // customs_entry_number ?>
    <tr id="r_customs_entry_number"<?= $Page->customs_entry_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_customs_entry_number"><?= $Page->customs_entry_number->caption() ?></span></td>
        <td data-name="customs_entry_number"<?= $Page->customs_entry_number->cellAttributes() ?>>
<span id="el_documents_customs_entry_number">
<span<?= $Page->customs_entry_number->viewAttributes() ?>>
<?= $Page->customs_entry_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->date_of_entry->Visible) { // date_of_entry ?>
    <tr id="r_date_of_entry"<?= $Page->date_of_entry->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_date_of_entry"><?= $Page->date_of_entry->caption() ?></span></td>
        <td data-name="date_of_entry"<?= $Page->date_of_entry->cellAttributes() ?>>
<span id="el_documents_date_of_entry">
<span<?= $Page->date_of_entry->viewAttributes() ?>>
<?= $Page->date_of_entry->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_html->Visible) { // document_html ?>
    <tr id="r_document_html"<?= $Page->document_html->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_document_html"><?= $Page->document_html->caption() ?></span></td>
        <td data-name="document_html"<?= $Page->document_html->cellAttributes() ?>>
<span id="el_documents_document_html">
<span<?= $Page->document_html->viewAttributes() ?>>
<?= $Page->document_html->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_data->Visible) { // document_data ?>
    <tr id="r_document_data"<?= $Page->document_data->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_document_data"><?= $Page->document_data->caption() ?></span></td>
        <td data-name="document_data"<?= $Page->document_data->cellAttributes() ?>>
<span id="el_documents_document_data">
<span<?= $Page->document_data->viewAttributes() ?>>
<?= $Page->document_data->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_deleted->Visible) { // is_deleted ?>
    <tr id="r_is_deleted"<?= $Page->is_deleted->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_is_deleted"><?= $Page->is_deleted->caption() ?></span></td>
        <td data-name="is_deleted"<?= $Page->is_deleted->cellAttributes() ?>>
<span id="el_documents_is_deleted">
<span<?= $Page->is_deleted->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_deleted->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->deletion_date->Visible) { // deletion_date ?>
    <tr id="r_deletion_date"<?= $Page->deletion_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_deletion_date"><?= $Page->deletion_date->caption() ?></span></td>
        <td data-name="deletion_date"<?= $Page->deletion_date->cellAttributes() ?>>
<span id="el_documents_deletion_date">
<span<?= $Page->deletion_date->viewAttributes() ?>>
<?= $Page->deletion_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->deleted_by->Visible) { // deleted_by ?>
    <tr id="r_deleted_by"<?= $Page->deleted_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_deleted_by"><?= $Page->deleted_by->caption() ?></span></td>
        <td data-name="deleted_by"<?= $Page->deleted_by->cellAttributes() ?>>
<span id="el_documents_deleted_by">
<span<?= $Page->deleted_by->viewAttributes() ?>>
<?= $Page->deleted_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->parent_document_id->Visible) { // parent_document_id ?>
    <tr id="r_parent_document_id"<?= $Page->parent_document_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_parent_document_id"><?= $Page->parent_document_id->caption() ?></span></td>
        <td data-name="parent_document_id"<?= $Page->parent_document_id->cellAttributes() ?>>
<span id="el_documents_parent_document_id">
<span<?= $Page->parent_document_id->viewAttributes() ?>>
<?= $Page->parent_document_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->version->Visible) { // version ?>
    <tr id="r_version"<?= $Page->version->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_version"><?= $Page->version->caption() ?></span></td>
        <td data-name="version"<?= $Page->version->cellAttributes() ?>>
<span id="el_documents_version">
<span<?= $Page->version->viewAttributes() ?>>
<?= $Page->version->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <tr id="r_notes"<?= $Page->notes->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_notes"><?= $Page->notes->caption() ?></span></td>
        <td data-name="notes"<?= $Page->notes->cellAttributes() ?>>
<span id="el_documents_notes">
<span<?= $Page->notes->viewAttributes() ?>>
<?= $Page->notes->getViewValue() ?></span>
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
