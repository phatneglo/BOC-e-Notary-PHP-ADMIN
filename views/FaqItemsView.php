<?php

namespace PHPMaker2024\eNotary;

// Page object
$FaqItemsView = &$Page;
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
<form name="ffaq_itemsview" id="ffaq_itemsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { faq_items: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ffaq_itemsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffaq_itemsview")
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
<input type="hidden" name="t" value="faq_items">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->faq_id->Visible) { // faq_id ?>
    <tr id="r_faq_id"<?= $Page->faq_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_faq_id"><?= $Page->faq_id->caption() ?></span></td>
        <td data-name="faq_id"<?= $Page->faq_id->cellAttributes() ?>>
<span id="el_faq_items_faq_id">
<span<?= $Page->faq_id->viewAttributes() ?>>
<?= $Page->faq_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->category_id->Visible) { // category_id ?>
    <tr id="r_category_id"<?= $Page->category_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_category_id"><?= $Page->category_id->caption() ?></span></td>
        <td data-name="category_id"<?= $Page->category_id->cellAttributes() ?>>
<span id="el_faq_items_category_id">
<span<?= $Page->category_id->viewAttributes() ?>>
<?= $Page->category_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->question->Visible) { // question ?>
    <tr id="r_question"<?= $Page->question->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_question"><?= $Page->question->caption() ?></span></td>
        <td data-name="question"<?= $Page->question->cellAttributes() ?>>
<span id="el_faq_items_question">
<span<?= $Page->question->viewAttributes() ?>>
<?= $Page->question->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->answer->Visible) { // answer ?>
    <tr id="r_answer"<?= $Page->answer->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_answer"><?= $Page->answer->caption() ?></span></td>
        <td data-name="answer"<?= $Page->answer->cellAttributes() ?>>
<span id="el_faq_items_answer">
<span<?= $Page->answer->viewAttributes() ?>>
<?= $Page->answer->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->display_order->Visible) { // display_order ?>
    <tr id="r_display_order"<?= $Page->display_order->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_display_order"><?= $Page->display_order->caption() ?></span></td>
        <td data-name="display_order"<?= $Page->display_order->cellAttributes() ?>>
<span id="el_faq_items_display_order">
<span<?= $Page->display_order->viewAttributes() ?>>
<?= $Page->display_order->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <tr id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_is_active"><?= $Page->is_active->caption() ?></span></td>
        <td data-name="is_active"<?= $Page->is_active->cellAttributes() ?>>
<span id="el_faq_items_is_active">
<span<?= $Page->is_active->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->is_active->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_faq_items_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
    <tr id="r_created_by"<?= $Page->created_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_created_by"><?= $Page->created_by->caption() ?></span></td>
        <td data-name="created_by"<?= $Page->created_by->cellAttributes() ?>>
<span id="el_faq_items_created_by">
<span<?= $Page->created_by->viewAttributes() ?>>
<?= $Page->created_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_faq_items_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
    <tr id="r_updated_by"<?= $Page->updated_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_updated_by"><?= $Page->updated_by->caption() ?></span></td>
        <td data-name="updated_by"<?= $Page->updated_by->cellAttributes() ?>>
<span id="el_faq_items_updated_by">
<span<?= $Page->updated_by->viewAttributes() ?>>
<?= $Page->updated_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->view_count->Visible) { // view_count ?>
    <tr id="r_view_count"<?= $Page->view_count->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_view_count"><?= $Page->view_count->caption() ?></span></td>
        <td data-name="view_count"<?= $Page->view_count->cellAttributes() ?>>
<span id="el_faq_items_view_count">
<span<?= $Page->view_count->viewAttributes() ?>>
<?= $Page->view_count->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tags->Visible) { // tags ?>
    <tr id="r_tags"<?= $Page->tags->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faq_items_tags"><?= $Page->tags->caption() ?></span></td>
        <td data-name="tags"<?= $Page->tags->cellAttributes() ?>>
<span id="el_faq_items_tags">
<span<?= $Page->tags->viewAttributes() ?>>
<?= $Page->tags->getViewValue() ?></span>
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
