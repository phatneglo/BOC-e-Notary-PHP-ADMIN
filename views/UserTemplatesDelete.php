<?php

namespace PHPMaker2024\eNotary;

// Page object
$UserTemplatesDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { user_templates: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fuser_templatesdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fuser_templatesdelete")
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
<form name="fuser_templatesdelete" id="fuser_templatesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="user_templates">
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
<?php if ($Page->user_template_id->Visible) { // user_template_id ?>
        <th class="<?= $Page->user_template_id->headerCellClass() ?>"><span id="elh_user_templates_user_template_id" class="user_templates_user_template_id"><?= $Page->user_template_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th class="<?= $Page->user_id->headerCellClass() ?>"><span id="elh_user_templates_user_id" class="user_templates_user_id"><?= $Page->user_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
        <th class="<?= $Page->template_id->headerCellClass() ?>"><span id="elh_user_templates_template_id" class="user_templates_template_id"><?= $Page->template_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->custom_name->Visible) { // custom_name ?>
        <th class="<?= $Page->custom_name->headerCellClass() ?>"><span id="elh_user_templates_custom_name" class="user_templates_custom_name"><?= $Page->custom_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->is_custom->Visible) { // is_custom ?>
        <th class="<?= $Page->is_custom->headerCellClass() ?>"><span id="elh_user_templates_is_custom" class="user_templates_is_custom"><?= $Page->is_custom->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_user_templates_created_at" class="user_templates_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_user_templates_updated_at" class="user_templates_updated_at"><?= $Page->updated_at->caption() ?></span></th>
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
<?php if ($Page->user_template_id->Visible) { // user_template_id ?>
        <td<?= $Page->user_template_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->user_template_id->viewAttributes() ?>>
<?= $Page->user_template_id->getViewValue() ?></span>
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
<?php if ($Page->custom_name->Visible) { // custom_name ?>
        <td<?= $Page->custom_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->custom_name->viewAttributes() ?>>
<?= $Page->custom_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->is_custom->Visible) { // is_custom ?>
        <td<?= $Page->is_custom->cellAttributes() ?>>
<span id="">
<span<?= $Page->is_custom->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_custom->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
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
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td<?= $Page->updated_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
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
