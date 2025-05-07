<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentTemplatesDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_templates: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fdocument_templatesdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_templatesdelete")
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
<form name="fdocument_templatesdelete" id="fdocument_templatesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="document_templates">
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
<?php if ($Page->template_id->Visible) { // template_id ?>
        <th class="<?= $Page->template_id->headerCellClass() ?>"><span id="elh_document_templates_template_id" class="document_templates_template_id"><?= $Page->template_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->template_name->Visible) { // template_name ?>
        <th class="<?= $Page->template_name->headerCellClass() ?>"><span id="elh_document_templates_template_name" class="document_templates_template_name"><?= $Page->template_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->template_code->Visible) { // template_code ?>
        <th class="<?= $Page->template_code->headerCellClass() ?>"><span id="elh_document_templates_template_code" class="document_templates_template_code"><?= $Page->template_code->caption() ?></span></th>
<?php } ?>
<?php if ($Page->category_id->Visible) { // category_id ?>
        <th class="<?= $Page->category_id->headerCellClass() ?>"><span id="elh_document_templates_category_id" class="document_templates_category_id"><?= $Page->category_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
        <th class="<?= $Page->is_active->headerCellClass() ?>"><span id="elh_document_templates_is_active" class="document_templates_is_active"><?= $Page->is_active->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_document_templates_created_at" class="document_templates_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
        <th class="<?= $Page->created_by->headerCellClass() ?>"><span id="elh_document_templates_created_by" class="document_templates_created_by"><?= $Page->created_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_document_templates_updated_at" class="document_templates_updated_at"><?= $Page->updated_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
        <th class="<?= $Page->updated_by->headerCellClass() ?>"><span id="elh_document_templates_updated_by" class="document_templates_updated_by"><?= $Page->updated_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->version->Visible) { // version ?>
        <th class="<?= $Page->version->headerCellClass() ?>"><span id="elh_document_templates_version" class="document_templates_version"><?= $Page->version->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notary_required->Visible) { // notary_required ?>
        <th class="<?= $Page->notary_required->headerCellClass() ?>"><span id="elh_document_templates_notary_required" class="document_templates_notary_required"><?= $Page->notary_required->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
        <th class="<?= $Page->fee_amount->headerCellClass() ?>"><span id="elh_document_templates_fee_amount" class="document_templates_fee_amount"><?= $Page->fee_amount->caption() ?></span></th>
<?php } ?>
<?php if ($Page->template_type->Visible) { // template_type ?>
        <th class="<?= $Page->template_type->headerCellClass() ?>"><span id="elh_document_templates_template_type" class="document_templates_template_type"><?= $Page->template_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->preview_image_path->Visible) { // preview_image_path ?>
        <th class="<?= $Page->preview_image_path->headerCellClass() ?>"><span id="elh_document_templates_preview_image_path" class="document_templates_preview_image_path"><?= $Page->preview_image_path->caption() ?></span></th>
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
<?php if ($Page->template_id->Visible) { // template_id ?>
        <td<?= $Page->template_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->template_name->Visible) { // template_name ?>
        <td<?= $Page->template_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->template_name->viewAttributes() ?>>
<?= $Page->template_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->template_code->Visible) { // template_code ?>
        <td<?= $Page->template_code->cellAttributes() ?>>
<span id="">
<span<?= $Page->template_code->viewAttributes() ?>>
<?= $Page->template_code->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->category_id->Visible) { // category_id ?>
        <td<?= $Page->category_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->category_id->viewAttributes() ?>>
<?= $Page->category_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
        <td<?= $Page->is_active->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
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
<?php if ($Page->created_by->Visible) { // created_by ?>
        <td<?= $Page->created_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->created_by->viewAttributes() ?>>
<?= $Page->created_by->getViewValue() ?></span>
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
<?php if ($Page->updated_by->Visible) { // updated_by ?>
        <td<?= $Page->updated_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->updated_by->viewAttributes() ?>>
<?= $Page->updated_by->getViewValue() ?></span>
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
<?php if ($Page->notary_required->Visible) { // notary_required ?>
        <td<?= $Page->notary_required->cellAttributes() ?>>
<span id="">
<span<?= $Page->notary_required->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->notary_required->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
        <td<?= $Page->fee_amount->cellAttributes() ?>>
<span id="">
<span<?= $Page->fee_amount->viewAttributes() ?>>
<?= $Page->fee_amount->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->template_type->Visible) { // template_type ?>
        <td<?= $Page->template_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->template_type->viewAttributes() ?>>
<?= $Page->template_type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->preview_image_path->Visible) { // preview_image_path ?>
        <td<?= $Page->preview_image_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->preview_image_path->viewAttributes() ?>>
<?= $Page->preview_image_path->getViewValue() ?></span>
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
