<?php

namespace PHPMaker2024\eNotary;

// Page object
$DocumentActivityLogsView = &$Page;
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
<form name="fdocument_activity_logsview" id="fdocument_activity_logsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { document_activity_logs: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fdocument_activity_logsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocument_activity_logsview")
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
<input type="hidden" name="t" value="document_activity_logs">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->log_id->Visible) { // log_id ?>
    <tr id="r_log_id"<?= $Page->log_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_activity_logs_log_id"><?= $Page->log_id->caption() ?></span></td>
        <td data-name="log_id"<?= $Page->log_id->cellAttributes() ?>>
<span id="el_document_activity_logs_log_id">
<span<?= $Page->log_id->viewAttributes() ?>>
<?= $Page->log_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <tr id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_activity_logs_document_id"><?= $Page->document_id->caption() ?></span></td>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el_document_activity_logs_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_activity_logs_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_document_activity_logs_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <tr id="r__action"<?= $Page->_action->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_activity_logs__action"><?= $Page->_action->caption() ?></span></td>
        <td data-name="_action"<?= $Page->_action->cellAttributes() ?>>
<span id="el_document_activity_logs__action">
<span<?= $Page->_action->viewAttributes() ?>>
<?= $Page->_action->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->details->Visible) { // details ?>
    <tr id="r_details"<?= $Page->details->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_activity_logs_details"><?= $Page->details->caption() ?></span></td>
        <td data-name="details"<?= $Page->details->cellAttributes() ?>>
<span id="el_document_activity_logs_details">
<span<?= $Page->details->viewAttributes() ?>>
<?= $Page->details->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_activity_logs_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_document_activity_logs_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <tr id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_activity_logs_ip_address"><?= $Page->ip_address->caption() ?></span></td>
        <td data-name="ip_address"<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_document_activity_logs_ip_address">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_agent->Visible) { // user_agent ?>
    <tr id="r_user_agent"<?= $Page->user_agent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_document_activity_logs_user_agent"><?= $Page->user_agent->caption() ?></span></td>
        <td data-name="user_agent"<?= $Page->user_agent->cellAttributes() ?>>
<span id="el_document_activity_logs_user_agent">
<span<?= $Page->user_agent->viewAttributes() ?>>
<?= $Page->user_agent->getViewValue() ?></span>
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
