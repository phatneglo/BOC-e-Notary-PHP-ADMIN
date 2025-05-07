<?php

namespace PHPMaker2024\eNotary;

// Page object
$SupportRequestsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { support_requests: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fsupport_requestsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsupport_requestsdelete")
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
<form name="fsupport_requestsdelete" id="fsupport_requestsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="support_requests">
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
<?php if ($Page->request_id->Visible) { // request_id ?>
        <th class="<?= $Page->request_id->headerCellClass() ?>"><span id="elh_support_requests_request_id" class="support_requests_request_id"><?= $Page->request_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th class="<?= $Page->user_id->headerCellClass() ?>"><span id="elh_support_requests_user_id" class="support_requests_user_id"><?= $Page->user_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
        <th class="<?= $Page->name->headerCellClass() ?>"><span id="elh_support_requests_name" class="support_requests_name"><?= $Page->name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
        <th class="<?= $Page->_email->headerCellClass() ?>"><span id="elh_support_requests__email" class="support_requests__email"><?= $Page->_email->caption() ?></span></th>
<?php } ?>
<?php if ($Page->subject->Visible) { // subject ?>
        <th class="<?= $Page->subject->headerCellClass() ?>"><span id="elh_support_requests_subject" class="support_requests_subject"><?= $Page->subject->caption() ?></span></th>
<?php } ?>
<?php if ($Page->request_type->Visible) { // request_type ?>
        <th class="<?= $Page->request_type->headerCellClass() ?>"><span id="elh_support_requests_request_type" class="support_requests_request_type"><?= $Page->request_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->reference_number->Visible) { // reference_number ?>
        <th class="<?= $Page->reference_number->headerCellClass() ?>"><span id="elh_support_requests_reference_number" class="support_requests_reference_number"><?= $Page->reference_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_support_requests_status" class="support_requests_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_support_requests_created_at" class="support_requests_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_support_requests_updated_at" class="support_requests_updated_at"><?= $Page->updated_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->assigned_to->Visible) { // assigned_to ?>
        <th class="<?= $Page->assigned_to->headerCellClass() ?>"><span id="elh_support_requests_assigned_to" class="support_requests_assigned_to"><?= $Page->assigned_to->caption() ?></span></th>
<?php } ?>
<?php if ($Page->resolved_at->Visible) { // resolved_at ?>
        <th class="<?= $Page->resolved_at->headerCellClass() ?>"><span id="elh_support_requests_resolved_at" class="support_requests_resolved_at"><?= $Page->resolved_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <th class="<?= $Page->ip_address->headerCellClass() ?>"><span id="elh_support_requests_ip_address" class="support_requests_ip_address"><?= $Page->ip_address->caption() ?></span></th>
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
<?php if ($Page->request_id->Visible) { // request_id ?>
        <td<?= $Page->request_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
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
<?php if ($Page->name->Visible) { // name ?>
        <td<?= $Page->name->cellAttributes() ?>>
<span id="">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
        <td<?= $Page->_email->cellAttributes() ?>>
<span id="">
<span<?= $Page->_email->viewAttributes() ?>>
<?= $Page->_email->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->subject->Visible) { // subject ?>
        <td<?= $Page->subject->cellAttributes() ?>>
<span id="">
<span<?= $Page->subject->viewAttributes() ?>>
<?= $Page->subject->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->request_type->Visible) { // request_type ?>
        <td<?= $Page->request_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->request_type->viewAttributes() ?>>
<?= $Page->request_type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->reference_number->Visible) { // reference_number ?>
        <td<?= $Page->reference_number->cellAttributes() ?>>
<span id="">
<span<?= $Page->reference_number->viewAttributes() ?>>
<?= $Page->reference_number->getViewValue() ?></span>
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
<?php if ($Page->assigned_to->Visible) { // assigned_to ?>
        <td<?= $Page->assigned_to->cellAttributes() ?>>
<span id="">
<span<?= $Page->assigned_to->viewAttributes() ?>>
<?= $Page->assigned_to->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->resolved_at->Visible) { // resolved_at ?>
        <td<?= $Page->resolved_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->resolved_at->viewAttributes() ?>>
<?= $Page->resolved_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
        <td<?= $Page->ip_address->cellAttributes() ?>>
<span id="">
<span<?= $Page->ip_address->viewAttributes() ?>>
<?= $Page->ip_address->getViewValue() ?></span>
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
