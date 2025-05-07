<?php

namespace PHPMaker2024\eNotary;

// Page object
$UserTemplatesView = &$Page;
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
<form name="fuser_templatesview" id="fuser_templatesview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { user_templates: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fuser_templatesview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fuser_templatesview")
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
<input type="hidden" name="t" value="user_templates">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->user_template_id->Visible) { // user_template_id ?>
    <tr id="r_user_template_id"<?= $Page->user_template_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_templates_user_template_id"><?= $Page->user_template_id->caption() ?></span></td>
        <td data-name="user_template_id"<?= $Page->user_template_id->cellAttributes() ?>>
<span id="el_user_templates_user_template_id">
<span<?= $Page->user_template_id->viewAttributes() ?>>
<?= $Page->user_template_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_templates_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_user_templates_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->template_id->Visible) { // template_id ?>
    <tr id="r_template_id"<?= $Page->template_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_templates_template_id"><?= $Page->template_id->caption() ?></span></td>
        <td data-name="template_id"<?= $Page->template_id->cellAttributes() ?>>
<span id="el_user_templates_template_id">
<span<?= $Page->template_id->viewAttributes() ?>>
<?= $Page->template_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->custom_name->Visible) { // custom_name ?>
    <tr id="r_custom_name"<?= $Page->custom_name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_templates_custom_name"><?= $Page->custom_name->caption() ?></span></td>
        <td data-name="custom_name"<?= $Page->custom_name->cellAttributes() ?>>
<span id="el_user_templates_custom_name">
<span<?= $Page->custom_name->viewAttributes() ?>>
<?= $Page->custom_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->custom_content->Visible) { // custom_content ?>
    <tr id="r_custom_content"<?= $Page->custom_content->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_templates_custom_content"><?= $Page->custom_content->caption() ?></span></td>
        <td data-name="custom_content"<?= $Page->custom_content->cellAttributes() ?>>
<span id="el_user_templates_custom_content">
<span<?= $Page->custom_content->viewAttributes() ?>>
<?= $Page->custom_content->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_custom->Visible) { // is_custom ?>
    <tr id="r_is_custom"<?= $Page->is_custom->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_templates_is_custom"><?= $Page->is_custom->caption() ?></span></td>
        <td data-name="is_custom"<?= $Page->is_custom->cellAttributes() ?>>
<span id="el_user_templates_is_custom">
<span<?= $Page->is_custom->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_custom->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_templates_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_user_templates_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_templates_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_user_templates_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
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
