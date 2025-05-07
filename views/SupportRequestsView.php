<?php

namespace PHPMaker2024\eNotary;

// Page object
$SupportRequestsView = &$Page;
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
<form name="fsupport_requestsview" id="fsupport_requestsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { support_requests: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fsupport_requestsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsupport_requestsview")
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
<input type="hidden" name="t" value="support_requests">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->request_id->Visible) { // request_id ?>
    <tr id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_request_id"><?= $Page->request_id->caption() ?></span></td>
        <td data-name="request_id"<?= $Page->request_id->cellAttributes() ?>>
<span id="el_support_requests_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_support_requests_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <tr id="r_name"<?= $Page->name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_name"><?= $Page->name->caption() ?></span></td>
        <td data-name="name"<?= $Page->name->cellAttributes() ?>>
<span id="el_support_requests_name">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
    <tr id="r__email"<?= $Page->_email->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests__email"><?= $Page->_email->caption() ?></span></td>
        <td data-name="_email"<?= $Page->_email->cellAttributes() ?>>
<span id="el_support_requests__email">
<span<?= $Page->_email->viewAttributes() ?>>
<?= $Page->_email->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->subject->Visible) { // subject ?>
    <tr id="r_subject"<?= $Page->subject->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_subject"><?= $Page->subject->caption() ?></span></td>
        <td data-name="subject"<?= $Page->subject->cellAttributes() ?>>
<span id="el_support_requests_subject">
<span<?= $Page->subject->viewAttributes() ?>>
<?= $Page->subject->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_message->Visible) { // message ?>
    <tr id="r__message"<?= $Page->_message->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests__message"><?= $Page->_message->caption() ?></span></td>
        <td data-name="_message"<?= $Page->_message->cellAttributes() ?>>
<span id="el_support_requests__message">
<span<?= $Page->_message->viewAttributes() ?>>
<?= $Page->_message->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->request_type->Visible) { // request_type ?>
    <tr id="r_request_type"<?= $Page->request_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_request_type"><?= $Page->request_type->caption() ?></span></td>
        <td data-name="request_type"<?= $Page->request_type->cellAttributes() ?>>
<span id="el_support_requests_request_type">
<span<?= $Page->request_type->viewAttributes() ?>>
<?= $Page->request_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reference_number->Visible) { // reference_number ?>
    <tr id="r_reference_number"<?= $Page->reference_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_reference_number"><?= $Page->reference_number->caption() ?></span></td>
        <td data-name="reference_number"<?= $Page->reference_number->cellAttributes() ?>>
<span id="el_support_requests_reference_number">
<span<?= $Page->reference_number->viewAttributes() ?>>
<?= $Page->reference_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status"<?= $Page->status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el_support_requests_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_support_requests_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <tr id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_updated_at"><?= $Page->updated_at->caption() ?></span></td>
        <td data-name="updated_at"<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_support_requests_updated_at">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->assigned_to->Visible) { // assigned_to ?>
    <tr id="r_assigned_to"<?= $Page->assigned_to->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_assigned_to"><?= $Page->assigned_to->caption() ?></span></td>
        <td data-name="assigned_to"<?= $Page->assigned_to->cellAttributes() ?>>
<span id="el_support_requests_assigned_to">
<span<?= $Page->assigned_to->viewAttributes() ?>>
<?= $Page->assigned_to->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->resolved_at->Visible) { // resolved_at ?>
    <tr id="r_resolved_at"<?= $Page->resolved_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_resolved_at"><?= $Page->resolved_at->caption() ?></span></td>
        <td data-name="resolved_at"<?= $Page->resolved_at->cellAttributes() ?>>
<span id="el_support_requests_resolved_at">
<span<?= $Page->resolved_at->viewAttributes() ?>>
<?= $Page->resolved_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_response->Visible) { // response ?>
    <tr id="r__response"<?= $Page->_response->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests__response"><?= $Page->_response->caption() ?></span></td>
        <td data-name="_response"<?= $Page->_response->cellAttributes() ?>>
<span id="el_support_requests__response">
<span<?= $Page->_response->viewAttributes() ?>>
<?= $Page->_response->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <tr id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_ip_address"><?= $Page->ip_address->caption() ?></span></td>
        <td data-name="ip_address"<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_support_requests_ip_address">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_agent->Visible) { // user_agent ?>
    <tr id="r_user_agent"<?= $Page->user_agent->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_support_requests_user_agent"><?= $Page->user_agent->caption() ?></span></td>
        <td data-name="user_agent"<?= $Page->user_agent->cellAttributes() ?>>
<span id="el_support_requests_user_agent">
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
