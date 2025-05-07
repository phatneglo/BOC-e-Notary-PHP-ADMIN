<?php

namespace PHPMaker2024\eNotary;

// Page object
$PaymentTransactionsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fpayment_transactionsedit" id="fpayment_transactionsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { payment_transactions: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fpayment_transactionsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpayment_transactionsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["transaction_id", [fields.transaction_id.visible && fields.transaction_id.required ? ew.Validators.required(fields.transaction_id.caption) : null], fields.transaction_id.isInvalid],
            ["request_id", [fields.request_id.visible && fields.request_id.required ? ew.Validators.required(fields.request_id.caption) : null, ew.Validators.integer], fields.request_id.isInvalid],
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null, ew.Validators.integer], fields.user_id.isInvalid],
            ["payment_method_id", [fields.payment_method_id.visible && fields.payment_method_id.required ? ew.Validators.required(fields.payment_method_id.caption) : null, ew.Validators.integer], fields.payment_method_id.isInvalid],
            ["transaction_reference", [fields.transaction_reference.visible && fields.transaction_reference.required ? ew.Validators.required(fields.transaction_reference.caption) : null], fields.transaction_reference.isInvalid],
            ["amount", [fields.amount.visible && fields.amount.required ? ew.Validators.required(fields.amount.caption) : null, ew.Validators.float], fields.amount.isInvalid],
            ["currency", [fields.currency.visible && fields.currency.required ? ew.Validators.required(fields.currency.caption) : null], fields.currency.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["payment_date", [fields.payment_date.visible && fields.payment_date.required ? ew.Validators.required(fields.payment_date.caption) : null, ew.Validators.datetime(fields.payment_date.clientFormatPattern)], fields.payment_date.isInvalid],
            ["gateway_reference", [fields.gateway_reference.visible && fields.gateway_reference.required ? ew.Validators.required(fields.gateway_reference.caption) : null], fields.gateway_reference.isInvalid],
            ["gateway_response", [fields.gateway_response.visible && fields.gateway_response.required ? ew.Validators.required(fields.gateway_response.caption) : null], fields.gateway_response.isInvalid],
            ["fee_amount", [fields.fee_amount.visible && fields.fee_amount.required ? ew.Validators.required(fields.fee_amount.caption) : null, ew.Validators.float], fields.fee_amount.isInvalid],
            ["total_amount", [fields.total_amount.visible && fields.total_amount.required ? ew.Validators.required(fields.total_amount.caption) : null, ew.Validators.float], fields.total_amount.isInvalid],
            ["payment_receipt_url", [fields.payment_receipt_url.visible && fields.payment_receipt_url.required ? ew.Validators.required(fields.payment_receipt_url.caption) : null], fields.payment_receipt_url.isInvalid],
            ["qr_code_path", [fields.qr_code_path.visible && fields.qr_code_path.required ? ew.Validators.required(fields.qr_code_path.caption) : null], fields.qr_code_path.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid],
            ["ip_address", [fields.ip_address.visible && fields.ip_address.required ? ew.Validators.required(fields.ip_address.caption) : null], fields.ip_address.isInvalid],
            ["user_agent", [fields.user_agent.visible && fields.user_agent.required ? ew.Validators.required(fields.user_agent.caption) : null], fields.user_agent.isInvalid],
            ["notes", [fields.notes.visible && fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code in JAVASCRIPT here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
        })
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="payment_transactions">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->transaction_id->Visible) { // transaction_id ?>
    <div id="r_transaction_id"<?= $Page->transaction_id->rowAttributes() ?>>
        <label id="elh_payment_transactions_transaction_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->transaction_id->caption() ?><?= $Page->transaction_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->transaction_id->cellAttributes() ?>>
<span id="el_payment_transactions_transaction_id">
<span<?= $Page->transaction_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->transaction_id->getDisplayValue($Page->transaction_id->EditValue))) ?>"></span>
<input type="hidden" data-table="payment_transactions" data-field="x_transaction_id" data-hidden="1" name="x_transaction_id" id="x_transaction_id" value="<?= HtmlEncode($Page->transaction_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->request_id->Visible) { // request_id ?>
    <div id="r_request_id"<?= $Page->request_id->rowAttributes() ?>>
        <label id="elh_payment_transactions_request_id" for="x_request_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->request_id->caption() ?><?= $Page->request_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->request_id->cellAttributes() ?>>
<span id="el_payment_transactions_request_id">
<input type="<?= $Page->request_id->getInputTextType() ?>" name="x_request_id" id="x_request_id" data-table="payment_transactions" data-field="x_request_id" value="<?= $Page->request_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->request_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->request_id->formatPattern()) ?>"<?= $Page->request_id->editAttributes() ?> aria-describedby="x_request_id_help">
<?= $Page->request_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->request_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <div id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <label id="elh_payment_transactions_user_id" for="x_user_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_id->caption() ?><?= $Page->user_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_id->cellAttributes() ?>>
<span id="el_payment_transactions_user_id">
<input type="<?= $Page->user_id->getInputTextType() ?>" name="x_user_id" id="x_user_id" data-table="payment_transactions" data-field="x_user_id" value="<?= $Page->user_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->user_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user_id->formatPattern()) ?>"<?= $Page->user_id->editAttributes() ?> aria-describedby="x_user_id_help">
<?= $Page->user_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->payment_method_id->Visible) { // payment_method_id ?>
    <div id="r_payment_method_id"<?= $Page->payment_method_id->rowAttributes() ?>>
        <label id="elh_payment_transactions_payment_method_id" for="x_payment_method_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->payment_method_id->caption() ?><?= $Page->payment_method_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->payment_method_id->cellAttributes() ?>>
<span id="el_payment_transactions_payment_method_id">
<input type="<?= $Page->payment_method_id->getInputTextType() ?>" name="x_payment_method_id" id="x_payment_method_id" data-table="payment_transactions" data-field="x_payment_method_id" value="<?= $Page->payment_method_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->payment_method_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->payment_method_id->formatPattern()) ?>"<?= $Page->payment_method_id->editAttributes() ?> aria-describedby="x_payment_method_id_help">
<?= $Page->payment_method_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->payment_method_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->transaction_reference->Visible) { // transaction_reference ?>
    <div id="r_transaction_reference"<?= $Page->transaction_reference->rowAttributes() ?>>
        <label id="elh_payment_transactions_transaction_reference" for="x_transaction_reference" class="<?= $Page->LeftColumnClass ?>"><?= $Page->transaction_reference->caption() ?><?= $Page->transaction_reference->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->transaction_reference->cellAttributes() ?>>
<span id="el_payment_transactions_transaction_reference">
<input type="<?= $Page->transaction_reference->getInputTextType() ?>" name="x_transaction_reference" id="x_transaction_reference" data-table="payment_transactions" data-field="x_transaction_reference" value="<?= $Page->transaction_reference->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->transaction_reference->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->transaction_reference->formatPattern()) ?>"<?= $Page->transaction_reference->editAttributes() ?> aria-describedby="x_transaction_reference_help">
<?= $Page->transaction_reference->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->transaction_reference->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->amount->Visible) { // amount ?>
    <div id="r_amount"<?= $Page->amount->rowAttributes() ?>>
        <label id="elh_payment_transactions_amount" for="x_amount" class="<?= $Page->LeftColumnClass ?>"><?= $Page->amount->caption() ?><?= $Page->amount->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->amount->cellAttributes() ?>>
<span id="el_payment_transactions_amount">
<input type="<?= $Page->amount->getInputTextType() ?>" name="x_amount" id="x_amount" data-table="payment_transactions" data-field="x_amount" value="<?= $Page->amount->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->amount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->amount->formatPattern()) ?>"<?= $Page->amount->editAttributes() ?> aria-describedby="x_amount_help">
<?= $Page->amount->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->amount->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->currency->Visible) { // currency ?>
    <div id="r_currency"<?= $Page->currency->rowAttributes() ?>>
        <label id="elh_payment_transactions_currency" for="x_currency" class="<?= $Page->LeftColumnClass ?>"><?= $Page->currency->caption() ?><?= $Page->currency->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->currency->cellAttributes() ?>>
<span id="el_payment_transactions_currency">
<input type="<?= $Page->currency->getInputTextType() ?>" name="x_currency" id="x_currency" data-table="payment_transactions" data-field="x_currency" value="<?= $Page->currency->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->currency->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->currency->formatPattern()) ?>"<?= $Page->currency->editAttributes() ?> aria-describedby="x_currency_help">
<?= $Page->currency->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->currency->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_payment_transactions_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_payment_transactions_status">
<input type="<?= $Page->status->getInputTextType() ?>" name="x_status" id="x_status" data-table="payment_transactions" data-field="x_status" value="<?= $Page->status->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->status->formatPattern()) ?>"<?= $Page->status->editAttributes() ?> aria-describedby="x_status_help">
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->payment_date->Visible) { // payment_date ?>
    <div id="r_payment_date"<?= $Page->payment_date->rowAttributes() ?>>
        <label id="elh_payment_transactions_payment_date" for="x_payment_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->payment_date->caption() ?><?= $Page->payment_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->payment_date->cellAttributes() ?>>
<span id="el_payment_transactions_payment_date">
<input type="<?= $Page->payment_date->getInputTextType() ?>" name="x_payment_date" id="x_payment_date" data-table="payment_transactions" data-field="x_payment_date" value="<?= $Page->payment_date->EditValue ?>" placeholder="<?= HtmlEncode($Page->payment_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->payment_date->formatPattern()) ?>"<?= $Page->payment_date->editAttributes() ?> aria-describedby="x_payment_date_help">
<?= $Page->payment_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->payment_date->getErrorMessage() ?></div>
<?php if (!$Page->payment_date->ReadOnly && !$Page->payment_date->Disabled && !isset($Page->payment_date->EditAttrs["readonly"]) && !isset($Page->payment_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpayment_transactionsedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("fpayment_transactionsedit", "x_payment_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->gateway_reference->Visible) { // gateway_reference ?>
    <div id="r_gateway_reference"<?= $Page->gateway_reference->rowAttributes() ?>>
        <label id="elh_payment_transactions_gateway_reference" for="x_gateway_reference" class="<?= $Page->LeftColumnClass ?>"><?= $Page->gateway_reference->caption() ?><?= $Page->gateway_reference->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->gateway_reference->cellAttributes() ?>>
<span id="el_payment_transactions_gateway_reference">
<input type="<?= $Page->gateway_reference->getInputTextType() ?>" name="x_gateway_reference" id="x_gateway_reference" data-table="payment_transactions" data-field="x_gateway_reference" value="<?= $Page->gateway_reference->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->gateway_reference->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->gateway_reference->formatPattern()) ?>"<?= $Page->gateway_reference->editAttributes() ?> aria-describedby="x_gateway_reference_help">
<?= $Page->gateway_reference->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->gateway_reference->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->gateway_response->Visible) { // gateway_response ?>
    <div id="r_gateway_response"<?= $Page->gateway_response->rowAttributes() ?>>
        <label id="elh_payment_transactions_gateway_response" for="x_gateway_response" class="<?= $Page->LeftColumnClass ?>"><?= $Page->gateway_response->caption() ?><?= $Page->gateway_response->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->gateway_response->cellAttributes() ?>>
<span id="el_payment_transactions_gateway_response">
<textarea data-table="payment_transactions" data-field="x_gateway_response" name="x_gateway_response" id="x_gateway_response" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->gateway_response->getPlaceHolder()) ?>"<?= $Page->gateway_response->editAttributes() ?> aria-describedby="x_gateway_response_help"><?= $Page->gateway_response->EditValue ?></textarea>
<?= $Page->gateway_response->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->gateway_response->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fee_amount->Visible) { // fee_amount ?>
    <div id="r_fee_amount"<?= $Page->fee_amount->rowAttributes() ?>>
        <label id="elh_payment_transactions_fee_amount" for="x_fee_amount" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fee_amount->caption() ?><?= $Page->fee_amount->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fee_amount->cellAttributes() ?>>
<span id="el_payment_transactions_fee_amount">
<input type="<?= $Page->fee_amount->getInputTextType() ?>" name="x_fee_amount" id="x_fee_amount" data-table="payment_transactions" data-field="x_fee_amount" value="<?= $Page->fee_amount->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->fee_amount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fee_amount->formatPattern()) ?>"<?= $Page->fee_amount->editAttributes() ?> aria-describedby="x_fee_amount_help">
<?= $Page->fee_amount->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fee_amount->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total_amount->Visible) { // total_amount ?>
    <div id="r_total_amount"<?= $Page->total_amount->rowAttributes() ?>>
        <label id="elh_payment_transactions_total_amount" for="x_total_amount" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total_amount->caption() ?><?= $Page->total_amount->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->total_amount->cellAttributes() ?>>
<span id="el_payment_transactions_total_amount">
<input type="<?= $Page->total_amount->getInputTextType() ?>" name="x_total_amount" id="x_total_amount" data-table="payment_transactions" data-field="x_total_amount" value="<?= $Page->total_amount->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->total_amount->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->total_amount->formatPattern()) ?>"<?= $Page->total_amount->editAttributes() ?> aria-describedby="x_total_amount_help">
<?= $Page->total_amount->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->total_amount->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->payment_receipt_url->Visible) { // payment_receipt_url ?>
    <div id="r_payment_receipt_url"<?= $Page->payment_receipt_url->rowAttributes() ?>>
        <label id="elh_payment_transactions_payment_receipt_url" for="x_payment_receipt_url" class="<?= $Page->LeftColumnClass ?>"><?= $Page->payment_receipt_url->caption() ?><?= $Page->payment_receipt_url->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->payment_receipt_url->cellAttributes() ?>>
<span id="el_payment_transactions_payment_receipt_url">
<input type="<?= $Page->payment_receipt_url->getInputTextType() ?>" name="x_payment_receipt_url" id="x_payment_receipt_url" data-table="payment_transactions" data-field="x_payment_receipt_url" value="<?= $Page->payment_receipt_url->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->payment_receipt_url->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->payment_receipt_url->formatPattern()) ?>"<?= $Page->payment_receipt_url->editAttributes() ?> aria-describedby="x_payment_receipt_url_help">
<?= $Page->payment_receipt_url->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->payment_receipt_url->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->qr_code_path->Visible) { // qr_code_path ?>
    <div id="r_qr_code_path"<?= $Page->qr_code_path->rowAttributes() ?>>
        <label id="elh_payment_transactions_qr_code_path" for="x_qr_code_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->qr_code_path->caption() ?><?= $Page->qr_code_path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->qr_code_path->cellAttributes() ?>>
<span id="el_payment_transactions_qr_code_path">
<input type="<?= $Page->qr_code_path->getInputTextType() ?>" name="x_qr_code_path" id="x_qr_code_path" data-table="payment_transactions" data-field="x_qr_code_path" value="<?= $Page->qr_code_path->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->qr_code_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->qr_code_path->formatPattern()) ?>"<?= $Page->qr_code_path->editAttributes() ?> aria-describedby="x_qr_code_path_help">
<?= $Page->qr_code_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->qr_code_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_payment_transactions_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_payment_transactions_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="payment_transactions" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpayment_transactionsedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("fpayment_transactionsedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_payment_transactions_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_payment_transactions_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="payment_transactions" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpayment_transactionsedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("fpayment_transactionsedit", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ip_address->Visible) { // ip_address ?>
    <div id="r_ip_address"<?= $Page->ip_address->rowAttributes() ?>>
        <label id="elh_payment_transactions_ip_address" for="x_ip_address" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ip_address->caption() ?><?= $Page->ip_address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ip_address->cellAttributes() ?>>
<span id="el_payment_transactions_ip_address">
<input type="<?= $Page->ip_address->getInputTextType() ?>" name="x_ip_address" id="x_ip_address" data-table="payment_transactions" data-field="x_ip_address" value="<?= $Page->ip_address->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ip_address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ip_address->formatPattern()) ?>"<?= $Page->ip_address->editAttributes() ?> aria-describedby="x_ip_address_help">
<?= $Page->ip_address->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ip_address->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_agent->Visible) { // user_agent ?>
    <div id="r_user_agent"<?= $Page->user_agent->rowAttributes() ?>>
        <label id="elh_payment_transactions_user_agent" for="x_user_agent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_agent->caption() ?><?= $Page->user_agent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_agent->cellAttributes() ?>>
<span id="el_payment_transactions_user_agent">
<textarea data-table="payment_transactions" data-field="x_user_agent" name="x_user_agent" id="x_user_agent" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->user_agent->getPlaceHolder()) ?>"<?= $Page->user_agent->editAttributes() ?> aria-describedby="x_user_agent_help"><?= $Page->user_agent->EditValue ?></textarea>
<?= $Page->user_agent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_agent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <div id="r_notes"<?= $Page->notes->rowAttributes() ?>>
        <label id="elh_payment_transactions_notes" for="x_notes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notes->caption() ?><?= $Page->notes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notes->cellAttributes() ?>>
<span id="el_payment_transactions_notes">
<textarea data-table="payment_transactions" data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>"<?= $Page->notes->editAttributes() ?> aria-describedby="x_notes_help"><?= $Page->notes->EditValue ?></textarea>
<?= $Page->notes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpayment_transactionsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpayment_transactionsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("payment_transactions");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
