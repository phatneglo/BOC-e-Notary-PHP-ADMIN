<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentTemplatesView = &$Page;
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
<form name="fdocument_templatesview" id="fdocument_templatesview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_templates: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fdocument_templatesview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_templatesview")
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
<input type="hidden" name="t" value="document_templates">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->template_id->Visible) { // template_id ?>
    <tr id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_template_id"><?= $Page->template_id->caption() ?></span></td>
        <td data-name="template_id"<?= $Page->template_id->cellAttributes() ?>>
<span id="el_document_templates_template_id">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->template_name->Visible) { // template_name ?>
    <tr id="r_template_name"<?= $Page->template_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_template_name"><?= $Page->template_name->caption() ?></span></td>
        <td data-name="template_name"<?= $Page->template_name->cellAttributes() ?>>
<span id="el_document_templates_template_name">
<span<?= $Page->template_name->viewAttributes() ?>>
<?= $Page->template_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->template_code->Visible) { // template_code ?>
    <tr id="r_template_code"<?= $Page->template_code->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_template_code"><?= $Page->template_code->caption() ?></span></td>
        <td data-name="template_code"<?= $Page->template_code->cellAttributes() ?>>
<span id="el_document_templates_template_code">
<span<?= $Page->template_code->viewAttributes() ?>>
<?= $Page->template_code->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->category_id->Visible) { // category_id ?>
    <tr id="r_category_id"<?= $Page->category_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_category_id"><?= $Page->category_id->caption() ?></span></td>
        <td data-name="category_id"<?= $Page->category_id->cellAttributes() ?>>
<span id="el_document_templates_category_id">
<span<?= $Page->category_id->viewAttributes() ?>>
<?= $Page->category_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <tr id="r_description"<?= $Page->description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_description"><?= $Page->description->caption() ?></span></td>
        <td data-name="description"<?= $Page->description->cellAttributes() ?>>
<span id="el_document_templates_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->html_content->Visible) { // html_content ?>
    <tr id="r_html_content"<?= $Page->html_content->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_html_content"><?= $Page->html_content->caption() ?></span></td>
        <td data-name="html_content"<?= $Page->html_content->cellAttributes() ?>>
<span id="el_document_templates_html_content">
<span<?= $Page->html_content->viewAttributes() ?>>
<?= $Page->html_content->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <tr id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_is_active"><?= $Page->is_active->caption() ?></span></td>
        <td data-name="is_active"<?= $Page->is_active->cellAttributes() ?>>
<span id="el_document_templates_is_active">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_document_templates_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
    <tr id="r_created_by"<?= $Page->created_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_created_by"><?= $Page->created_by->caption() ?></span></td>
        <td data-name="created_by"<?= $Page->created_by->cellAttributes() ?>>
<span id="el_document_templates_created_by">
<span<?= $Page->created_by->viewAttributes() ?>>
<?= $Page->created_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_document_templates_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
    <tr id="r_updated_by"<?= $Page->updated_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_updated_by"><?= $Page->updated_by->caption() ?></span></td>
        <td data-name="updated_by"<?= $Page->updated_by->cellAttributes() ?>>
<span id="el_document_templates_updated_by">
<span<?= $Page->updated_by->viewAttributes() ?>>
<?= $Page->updated_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->version->Visible) { // version ?>
    <tr id="r_version"<?= $Page->version->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_version"><?= $Page->version->caption() ?></span></td>
        <td data-name="version"<?= $Page->version->cellAttributes() ?>>
<span id="el_document_templates_version">
<span<?= $Page->version->viewAttributes() ?>>
<?= $Page->version->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notary_required->Visible) { // notary_required ?>
    <tr id="r_notary_required"<?= $Page->notary_required->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_notary_required"><?= $Page->notary_required->caption() ?></span></td>
        <td data-name="notary_required"<?= $Page->notary_required->cellAttributes() ?>>
<span id="el_document_templates_notary_required">
<span<?= $Page->notary_required->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->notary_required->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
    <tr id="r_fee_amount"<?= $Page->fee_amount->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_fee_amount"><?= $Page->fee_amount->caption() ?></span></td>
        <td data-name="fee_amount"<?= $Page->fee_amount->cellAttributes() ?>>
<span id="el_document_templates_fee_amount">
<span<?= $Page->fee_amount->viewAttributes() ?>>
<?= $Page->fee_amount->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->approval_workflow->Visible) { // approval_workflow ?>
    <tr id="r_approval_workflow"<?= $Page->approval_workflow->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_approval_workflow"><?= $Page->approval_workflow->caption() ?></span></td>
        <td data-name="approval_workflow"<?= $Page->approval_workflow->cellAttributes() ?>>
<span id="el_document_templates_approval_workflow">
<span<?= $Page->approval_workflow->viewAttributes() ?>>
<?= $Page->approval_workflow->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->template_type->Visible) { // template_type ?>
    <tr id="r_template_type"<?= $Page->template_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_template_type"><?= $Page->template_type->caption() ?></span></td>
        <td data-name="template_type"<?= $Page->template_type->cellAttributes() ?>>
<span id="el_document_templates_template_type">
<span<?= $Page->template_type->viewAttributes() ?>>
<?= $Page->template_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->header_text->Visible) { // header_text ?>
    <tr id="r_header_text"<?= $Page->header_text->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_header_text"><?= $Page->header_text->caption() ?></span></td>
        <td data-name="header_text"<?= $Page->header_text->cellAttributes() ?>>
<span id="el_document_templates_header_text">
<span<?= $Page->header_text->viewAttributes() ?>>
<?= $Page->header_text->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->footer_text->Visible) { // footer_text ?>
    <tr id="r_footer_text"<?= $Page->footer_text->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_footer_text"><?= $Page->footer_text->caption() ?></span></td>
        <td data-name="footer_text"<?= $Page->footer_text->cellAttributes() ?>>
<span id="el_document_templates_footer_text">
<span<?= $Page->footer_text->viewAttributes() ?>>
<?= $Page->footer_text->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->preview_image_path->Visible) { // preview_image_path ?>
    <tr id="r_preview_image_path"<?= $Page->preview_image_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_templates_preview_image_path"><?= $Page->preview_image_path->caption() ?></span></td>
        <td data-name="preview_image_path"<?= $Page->preview_image_path->cellAttributes() ?>>
<span id="el_document_templates_preview_image_path">
<span<?= $Page->preview_image_path->viewAttributes() ?>>
<?= $Page->preview_image_path->getViewValue() ?></span>
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
