<?php

namespace PHPMaker2024\eNotary;

// Page object
$NotarizedDocumentsView = &$Page;
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
<form name="fnotarized_documentsview" id="fnotarized_documentsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notarized_documents: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fnotarized_documentsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotarized_documentsview")
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
<input type="hidden" name="t" value="notarized_documents">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->notarized_id->Visible) { // notarized_id ?>
    <tr id="r_notarized_id"<?= $Page->notarized_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_notarized_id"><?= $Page->notarized_id->caption() ?></span></td>
        <td data-name="notarized_id"<?= $Page->notarized_id->cellAttributes() ?>>
<span id="el_notarized_documents_notarized_id">
<span<?= $Page->notarized_id->viewAttributes() ?>>
<?= $Page->notarized_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
    <tr id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_request_id"><?= $Page->request_id->caption() ?></span></td>
        <td data-name="request_id"<?= $Page->request_id->cellAttributes() ?>>
<span id="el_notarized_documents_request_id">
<span<?= $Page->request_id->viewAttributes() ?>>
<?= $Page->request_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_id->Visible) { // document_id ?>
    <tr id="r_document_id"<?= $Page->document_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_document_id"><?= $Page->document_id->caption() ?></span></td>
        <td data-name="document_id"<?= $Page->document_id->cellAttributes() ?>>
<span id="el_notarized_documents_document_id">
<span<?= $Page->document_id->viewAttributes() ?>>
<?= $Page->document_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notary_id->Visible) { // notary_id ?>
    <tr id="r_notary_id"<?= $Page->notary_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_notary_id"><?= $Page->notary_id->caption() ?></span></td>
        <td data-name="notary_id"<?= $Page->notary_id->cellAttributes() ?>>
<span id="el_notarized_documents_notary_id">
<span<?= $Page->notary_id->viewAttributes() ?>>
<?= $Page->notary_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_number->Visible) { // document_number ?>
    <tr id="r_document_number"<?= $Page->document_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_document_number"><?= $Page->document_number->caption() ?></span></td>
        <td data-name="document_number"<?= $Page->document_number->cellAttributes() ?>>
<span id="el_notarized_documents_document_number">
<span<?= $Page->document_number->viewAttributes() ?>>
<?= $Page->document_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->page_number->Visible) { // page_number ?>
    <tr id="r_page_number"<?= $Page->page_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_page_number"><?= $Page->page_number->caption() ?></span></td>
        <td data-name="page_number"<?= $Page->page_number->cellAttributes() ?>>
<span id="el_notarized_documents_page_number">
<span<?= $Page->page_number->viewAttributes() ?>>
<?= $Page->page_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->book_number->Visible) { // book_number ?>
    <tr id="r_book_number"<?= $Page->book_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_book_number"><?= $Page->book_number->caption() ?></span></td>
        <td data-name="book_number"<?= $Page->book_number->cellAttributes() ?>>
<span id="el_notarized_documents_book_number">
<span<?= $Page->book_number->viewAttributes() ?>>
<?= $Page->book_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->series_of->Visible) { // series_of ?>
    <tr id="r_series_of"<?= $Page->series_of->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_series_of"><?= $Page->series_of->caption() ?></span></td>
        <td data-name="series_of"<?= $Page->series_of->cellAttributes() ?>>
<span id="el_notarized_documents_series_of">
<span<?= $Page->series_of->viewAttributes() ?>>
<?= $Page->series_of->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_keycode->Visible) { // doc_keycode ?>
    <tr id="r_doc_keycode"<?= $Page->doc_keycode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_doc_keycode"><?= $Page->doc_keycode->caption() ?></span></td>
        <td data-name="doc_keycode"<?= $Page->doc_keycode->cellAttributes() ?>>
<span id="el_notarized_documents_doc_keycode">
<span<?= $Page->doc_keycode->viewAttributes() ?>>
<?= $Page->doc_keycode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notary_location->Visible) { // notary_location ?>
    <tr id="r_notary_location"<?= $Page->notary_location->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_notary_location"><?= $Page->notary_location->caption() ?></span></td>
        <td data-name="notary_location"<?= $Page->notary_location->cellAttributes() ?>>
<span id="el_notarized_documents_notary_location">
<span<?= $Page->notary_location->viewAttributes() ?>>
<?= $Page->notary_location->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notarization_date->Visible) { // notarization_date ?>
    <tr id="r_notarization_date"<?= $Page->notarization_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_notarization_date"><?= $Page->notarization_date->caption() ?></span></td>
        <td data-name="notarization_date"<?= $Page->notarization_date->cellAttributes() ?>>
<span id="el_notarized_documents_notarization_date">
<span<?= $Page->notarization_date->viewAttributes() ?>>
<?= $Page->notarization_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->digital_signature->Visible) { // digital_signature ?>
    <tr id="r_digital_signature"<?= $Page->digital_signature->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_digital_signature"><?= $Page->digital_signature->caption() ?></span></td>
        <td data-name="digital_signature"<?= $Page->digital_signature->cellAttributes() ?>>
<span id="el_notarized_documents_digital_signature">
<span<?= $Page->digital_signature->viewAttributes() ?>>
<?= $Page->digital_signature->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->digital_seal->Visible) { // digital_seal ?>
    <tr id="r_digital_seal"<?= $Page->digital_seal->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_digital_seal"><?= $Page->digital_seal->caption() ?></span></td>
        <td data-name="digital_seal"<?= $Page->digital_seal->cellAttributes() ?>>
<span id="el_notarized_documents_digital_seal">
<span<?= $Page->digital_seal->viewAttributes() ?>>
<?= $Page->digital_seal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->certificate_text->Visible) { // certificate_text ?>
    <tr id="r_certificate_text"<?= $Page->certificate_text->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_certificate_text"><?= $Page->certificate_text->caption() ?></span></td>
        <td data-name="certificate_text"<?= $Page->certificate_text->cellAttributes() ?>>
<span id="el_notarized_documents_certificate_text">
<span<?= $Page->certificate_text->viewAttributes() ?>>
<?= $Page->certificate_text->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->certificate_type->Visible) { // certificate_type ?>
    <tr id="r_certificate_type"<?= $Page->certificate_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_certificate_type"><?= $Page->certificate_type->caption() ?></span></td>
        <td data-name="certificate_type"<?= $Page->certificate_type->cellAttributes() ?>>
<span id="el_notarized_documents_certificate_type">
<span<?= $Page->certificate_type->viewAttributes() ?>>
<?= $Page->certificate_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
    <tr id="r_qr_code_path"<?= $Page->qr_code_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_qr_code_path"><?= $Page->qr_code_path->caption() ?></span></td>
        <td data-name="qr_code_path"<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="el_notarized_documents_qr_code_path">
<span<?= $Page->qr_code_path->viewAttributes() ?>>
<?= $Page->qr_code_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notarized_document_path->Visible) { // notarized_document_path ?>
    <tr id="r_notarized_document_path"<?= $Page->notarized_document_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_notarized_document_path"><?= $Page->notarized_document_path->caption() ?></span></td>
        <td data-name="notarized_document_path"<?= $Page->notarized_document_path->cellAttributes() ?>>
<span id="el_notarized_documents_notarized_document_path">
<span<?= $Page->notarized_document_path->viewAttributes() ?>>
<?= $Page->notarized_document_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->expires_at->Visible) { // expires_at ?>
    <tr id="r_expires_at"<?= $Page->expires_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_expires_at"><?= $Page->expires_at->caption() ?></span></td>
        <td data-name="expires_at"<?= $Page->expires_at->cellAttributes() ?>>
<span id="el_notarized_documents_expires_at">
<span<?= $Page->expires_at->viewAttributes() ?>>
<?= $Page->expires_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->revoked->Visible) { // revoked ?>
    <tr id="r_revoked"<?= $Page->revoked->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_revoked"><?= $Page->revoked->caption() ?></span></td>
        <td data-name="revoked"<?= $Page->revoked->cellAttributes() ?>>
<span id="el_notarized_documents_revoked">
<span<?= $Page->revoked->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->revoked->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->revoked_at->Visible) { // revoked_at ?>
    <tr id="r_revoked_at"<?= $Page->revoked_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_revoked_at"><?= $Page->revoked_at->caption() ?></span></td>
        <td data-name="revoked_at"<?= $Page->revoked_at->cellAttributes() ?>>
<span id="el_notarized_documents_revoked_at">
<span<?= $Page->revoked_at->viewAttributes() ?>>
<?= $Page->revoked_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->revoked_by->Visible) { // revoked_by ?>
    <tr id="r_revoked_by"<?= $Page->revoked_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_revoked_by"><?= $Page->revoked_by->caption() ?></span></td>
        <td data-name="revoked_by"<?= $Page->revoked_by->cellAttributes() ?>>
<span id="el_notarized_documents_revoked_by">
<span<?= $Page->revoked_by->viewAttributes() ?>>
<?= $Page->revoked_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->revocation_reason->Visible) { // revocation_reason ?>
    <tr id="r_revocation_reason"<?= $Page->revocation_reason->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notarized_documents_revocation_reason"><?= $Page->revocation_reason->caption() ?></span></td>
        <td data-name="revocation_reason"<?= $Page->revocation_reason->cellAttributes() ?>>
<span id="el_notarized_documents_revocation_reason">
<span<?= $Page->revocation_reason->viewAttributes() ?>>
<?= $Page->revocation_reason->getViewValue() ?></span>
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
