<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizedDocumentsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarized_documents: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fnotarized_documentsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotarized_documentsdelete")
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
<form name="fnotarized_documentsdelete" id="fnotarized_documentsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notarized_documents">
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
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <th class="<?= $Page->notarized_id->headerCellClass() ?>"><span id="elh_notarized_documents_notarized_id" class="notarized_documents_notarized_id"><?= $Page->notarized_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
        <th class="<?= $Page->request_id->headerCellClass() ?>"><span id="elh_notarized_documents_request_id" class="notarized_documents_request_id"><?= $Page->request_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <th class="<?= $Page->document_id->headerCellClass() ?>"><span id="elh_notarized_documents_document_id" class="notarized_documents_document_id"><?= $Page->document_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <th class="<?= $Page->notary_id->headerCellClass() ?>"><span id="elh_notarized_documents_notary_id" class="notarized_documents_notary_id"><?= $Page->notary_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
        <th class="<?= $Page->document_number->headerCellClass() ?>"><span id="elh_notarized_documents_document_number" class="notarized_documents_document_number"><?= $Page->document_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->page_number->Visible) { // page_number ?>
        <th class="<?= $Page->page_number->headerCellClass() ?>"><span id="elh_notarized_documents_page_number" class="notarized_documents_page_number"><?= $Page->page_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->book_number->Visible) { // book_number ?>
        <th class="<?= $Page->book_number->headerCellClass() ?>"><span id="elh_notarized_documents_book_number" class="notarized_documents_book_number"><?= $Page->book_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->series_of->Visible) { // series_of ?>
        <th class="<?= $Page->series_of->headerCellClass() ?>"><span id="elh_notarized_documents_series_of" class="notarized_documents_series_of"><?= $Page->series_of->caption() ?></span></th>
<?php } ?>
<?php if ($Page->doc_keycode->Visible) { // doc_keycode ?>
        <th class="<?= $Page->doc_keycode->headerCellClass() ?>"><span id="elh_notarized_documents_doc_keycode" class="notarized_documents_doc_keycode"><?= $Page->doc_keycode->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notary_location->Visible) { // notary_location ?>
        <th class="<?= $Page->notary_location->headerCellClass() ?>"><span id="elh_notarized_documents_notary_location" class="notarized_documents_notary_location"><?= $Page->notary_location->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notarization_date->Visible) { // notarization_date ?>
        <th class="<?= $Page->notarization_date->headerCellClass() ?>"><span id="elh_notarized_documents_notarization_date" class="notarized_documents_notarization_date"><?= $Page->notarization_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->certificate_type->Visible) { // certificate_type ?>
        <th class="<?= $Page->certificate_type->headerCellClass() ?>"><span id="elh_notarized_documents_certificate_type" class="notarized_documents_certificate_type"><?= $Page->certificate_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <th class="<?= $Page->qr_code_path->headerCellClass() ?>"><span id="elh_notarized_documents_qr_code_path" class="notarized_documents_qr_code_path"><?= $Page->qr_code_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notarized_document_path->Visible) { // notarized_document_path ?>
        <th class="<?= $Page->notarized_document_path->headerCellClass() ?>"><span id="elh_notarized_documents_notarized_document_path" class="notarized_documents_notarized_document_path"><?= $Page->notarized_document_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
        <th class="<?= $Page->expires_at->headerCellClass() ?>"><span id="elh_notarized_documents_expires_at" class="notarized_documents_expires_at"><?= $Page->expires_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->revoked->Visible) { // revoked ?>
        <th class="<?= $Page->revoked->headerCellClass() ?>"><span id="elh_notarized_documents_revoked" class="notarized_documents_revoked"><?= $Page->revoked->caption() ?></span></th>
<?php } ?>
<?php if ($Page->revoked_at->Visible) { // revoked_at ?>
        <th class="<?= $Page->revoked_at->headerCellClass() ?>"><span id="elh_notarized_documents_revoked_at" class="notarized_documents_revoked_at"><?= $Page->revoked_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->revoked_by->Visible) { // revoked_by ?>
        <th class="<?= $Page->revoked_by->headerCellClass() ?>"><span id="elh_notarized_documents_revoked_by" class="notarized_documents_revoked_by"><?= $Page->revoked_by->caption() ?></span></th>
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
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
        <td<?= $Page->notarized_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->notarized_id->viewAttributes() ?>>
<?= $Page->notarized_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
        <td<?= $Page->request_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
        <td<?= $Page->document_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
        <td<?= $Page->notary_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
        <td<?= $Page->document_number->cellAttributes() ?>>
<span id="">
<span<?= $Page->document_number->viewAttributes() ?>>
<?= $Page->document_number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->page_number->Visible) { // page_number ?>
        <td<?= $Page->page_number->cellAttributes() ?>>
<span id="">
<span<?= $Page->page_number->viewAttributes() ?>>
<?= $Page->page_number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->book_number->Visible) { // book_number ?>
        <td<?= $Page->book_number->cellAttributes() ?>>
<span id="">
<span<?= $Page->book_number->viewAttributes() ?>>
<?= $Page->book_number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->series_of->Visible) { // series_of ?>
        <td<?= $Page->series_of->cellAttributes() ?>>
<span id="">
<span<?= $Page->series_of->viewAttributes() ?>>
<?= $Page->series_of->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->doc_keycode->Visible) { // doc_keycode ?>
        <td<?= $Page->doc_keycode->cellAttributes() ?>>
<span id="">
<span<?= $Page->doc_keycode->viewAttributes() ?>>
<?= $Page->doc_keycode->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->notary_location->Visible) { // notary_location ?>
        <td<?= $Page->notary_location->cellAttributes() ?>>
<span id="">
<span<?= $Page->notary_location->viewAttributes() ?>>
<?= $Page->notary_location->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->notarization_date->Visible) { // notarization_date ?>
        <td<?= $Page->notarization_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->notarization_date->viewAttributes() ?>>
<?= $Page->notarization_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->certificate_type->Visible) { // certificate_type ?>
        <td<?= $Page->certificate_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->certificate_type->viewAttributes() ?>>
<?= $Page->certificate_type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
        <td<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->qr_code_path->viewAttributes() ?>>
<?= $Page->qr_code_path->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->notarized_document_path->Visible) { // notarized_document_path ?>
        <td<?= $Page->notarized_document_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->notarized_document_path->viewAttributes() ?>>
<?= $Page->notarized_document_path->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
        <td<?= $Page->expires_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->expires_at->viewAttributes() ?>>
<?= $Page->expires_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->revoked->Visible) { // revoked ?>
        <td<?= $Page->revoked->cellAttributes() ?>>
<span id="">
<span<?= $Page->revoked->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->revoked->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->revoked_at->Visible) { // revoked_at ?>
        <td<?= $Page->revoked_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->revoked_at->viewAttributes() ?>>
<?= $Page->revoked_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->revoked_by->Visible) { // revoked_by ?>
        <td<?= $Page->revoked_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->revoked_by->viewAttributes() ?>>
<?= $Page->revoked_by->getViewValue() ?></span>
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
