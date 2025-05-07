<?php

namespace PHPMaker2024\eNotary;

// Page object
$FaqItemsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="ffaq_itemsedit" id="ffaq_itemsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { faq_items: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var ffaq_itemsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffaq_itemsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["faq_id", [fields.faq_id.visible && fields.faq_id.required ? ew.Validators.required(fields.faq_id.caption) : null], fields.faq_id.isInvalid],
            ["category_id", [fields.category_id.visible && fields.category_id.required ? ew.Validators.required(fields.category_id.caption) : null, ew.Validators.integer], fields.category_id.isInvalid],
            ["question", [fields.question.visible && fields.question.required ? ew.Validators.required(fields.question.caption) : null], fields.question.isInvalid],
            ["answer", [fields.answer.visible && fields.answer.required ? ew.Validators.required(fields.answer.caption) : null], fields.answer.isInvalid],
            ["display_order", [fields.display_order.visible && fields.display_order.required ? ew.Validators.required(fields.display_order.caption) : null, ew.Validators.integer], fields.display_order.isInvalid],
            ["is_active", [fields.is_active.visible && fields.is_active.required ? ew.Validators.required(fields.is_active.caption) : null], fields.is_active.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid],
            ["created_by", [fields.created_by.visible && fields.created_by.required ? ew.Validators.required(fields.created_by.caption) : null, ew.Validators.integer], fields.created_by.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid],
            ["updated_by", [fields.updated_by.visible && fields.updated_by.required ? ew.Validators.required(fields.updated_by.caption) : null, ew.Validators.integer], fields.updated_by.isInvalid],
            ["view_count", [fields.view_count.visible && fields.view_count.required ? ew.Validators.required(fields.view_count.caption) : null, ew.Validators.integer], fields.view_count.isInvalid],
            ["tags", [fields.tags.visible && fields.tags.required ? ew.Validators.required(fields.tags.caption) : null], fields.tags.isInvalid]
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
            "is_active": <?= $Page->is_active->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="faq_items">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->faq_id->Visible) { // faq_id ?>
    <div id="r_faq_id"<?= $Page->faq_id->rowAttributes() ?>>
        <label id="elh_faq_items_faq_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->faq_id->caption() ?><?= $Page->faq_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->faq_id->cellAttributes() ?>>
<span id="el_faq_items_faq_id">
<span<?= $Page->faq_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->faq_id->getDisplayValue($Page->faq_id->EditValue))) ?>"></span>
<input type="hidden" data-table="faq_items" data-field="x_faq_id" data-hidden="1" name="x_faq_id" id="x_faq_id" value="<?= HtmlEncode($Page->faq_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->category_id->Visible) { // category_id ?>
    <div id="r_category_id"<?= $Page->category_id->rowAttributes() ?>>
        <label id="elh_faq_items_category_id" for="x_category_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->category_id->caption() ?><?= $Page->category_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->category_id->cellAttributes() ?>>
<span id="el_faq_items_category_id">
<input type="<?= $Page->category_id->getInputTextType() ?>" name="x_category_id" id="x_category_id" data-table="faq_items" data-field="x_category_id" value="<?= $Page->category_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->category_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->category_id->formatPattern()) ?>"<?= $Page->category_id->editAttributes() ?> aria-describedby="x_category_id_help">
<?= $Page->category_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->category_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->question->Visible) { // question ?>
    <div id="r_question"<?= $Page->question->rowAttributes() ?>>
        <label id="elh_faq_items_question" for="x_question" class="<?= $Page->LeftColumnClass ?>"><?= $Page->question->caption() ?><?= $Page->question->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->question->cellAttributes() ?>>
<span id="el_faq_items_question">
<textarea data-table="faq_items" data-field="x_question" name="x_question" id="x_question" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->question->getPlaceHolder()) ?>"<?= $Page->question->editAttributes() ?> aria-describedby="x_question_help"><?= $Page->question->EditValue ?></textarea>
<?= $Page->question->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->question->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->answer->Visible) { // answer ?>
    <div id="r_answer"<?= $Page->answer->rowAttributes() ?>>
        <label id="elh_faq_items_answer" for="x_answer" class="<?= $Page->LeftColumnClass ?>"><?= $Page->answer->caption() ?><?= $Page->answer->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->answer->cellAttributes() ?>>
<span id="el_faq_items_answer">
<textarea data-table="faq_items" data-field="x_answer" name="x_answer" id="x_answer" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->answer->getPlaceHolder()) ?>"<?= $Page->answer->editAttributes() ?> aria-describedby="x_answer_help"><?= $Page->answer->EditValue ?></textarea>
<?= $Page->answer->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->answer->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->display_order->Visible) { // display_order ?>
    <div id="r_display_order"<?= $Page->display_order->rowAttributes() ?>>
        <label id="elh_faq_items_display_order" for="x_display_order" class="<?= $Page->LeftColumnClass ?>"><?= $Page->display_order->caption() ?><?= $Page->display_order->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->display_order->cellAttributes() ?>>
<span id="el_faq_items_display_order">
<input type="<?= $Page->display_order->getInputTextType() ?>" name="x_display_order" id="x_display_order" data-table="faq_items" data-field="x_display_order" value="<?= $Page->display_order->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->display_order->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->display_order->formatPattern()) ?>"<?= $Page->display_order->editAttributes() ?> aria-describedby="x_display_order_help">
<?= $Page->display_order->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->display_order->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->is_active->Visible) { // is_active ?>
    <div id="r_is_active"<?= $Page->is_active->rowAttributes() ?>>
        <label id="elh_faq_items_is_active" class="<?= $Page->LeftColumnClass ?>"><?= $Page->is_active->caption() ?><?= $Page->is_active->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->is_active->cellAttributes() ?>>
<span id="el_faq_items_is_active">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->is_active->isInvalidClass() ?>" data-table="faq_items" data-field="x_is_active" data-boolean name="x_is_active" id="x_is_active" value="1"<?= ConvertToBool($Page->is_active->CurrentValue) ? " checked" : "" ?><?= $Page->is_active->editAttributes() ?> aria-describedby="x_is_active_help">
    <div class="invalid-feedback"><?= $Page->is_active->getErrorMessage() ?></div>
</div>
<?= $Page->is_active->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_faq_items_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_faq_items_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="faq_items" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffaq_itemsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("ffaq_itemsedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_by->Visible) { // created_by ?>
    <div id="r_created_by"<?= $Page->created_by->rowAttributes() ?>>
        <label id="elh_faq_items_created_by" for="x_created_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_by->caption() ?><?= $Page->created_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_by->cellAttributes() ?>>
<span id="el_faq_items_created_by">
<input type="<?= $Page->created_by->getInputTextType() ?>" name="x_created_by" id="x_created_by" data-table="faq_items" data-field="x_created_by" value="<?= $Page->created_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->created_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_by->formatPattern()) ?>"<?= $Page->created_by->editAttributes() ?> aria-describedby="x_created_by_help">
<?= $Page->created_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_faq_items_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_faq_items_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="faq_items" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffaq_itemsedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("ffaq_itemsedit", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_by->Visible) { // updated_by ?>
    <div id="r_updated_by"<?= $Page->updated_by->rowAttributes() ?>>
        <label id="elh_faq_items_updated_by" for="x_updated_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_by->caption() ?><?= $Page->updated_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_by->cellAttributes() ?>>
<span id="el_faq_items_updated_by">
<input type="<?= $Page->updated_by->getInputTextType() ?>" name="x_updated_by" id="x_updated_by" data-table="faq_items" data-field="x_updated_by" value="<?= $Page->updated_by->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->updated_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_by->formatPattern()) ?>"<?= $Page->updated_by->editAttributes() ?> aria-describedby="x_updated_by_help">
<?= $Page->updated_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->view_count->Visible) { // view_count ?>
    <div id="r_view_count"<?= $Page->view_count->rowAttributes() ?>>
        <label id="elh_faq_items_view_count" for="x_view_count" class="<?= $Page->LeftColumnClass ?>"><?= $Page->view_count->caption() ?><?= $Page->view_count->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->view_count->cellAttributes() ?>>
<span id="el_faq_items_view_count">
<input type="<?= $Page->view_count->getInputTextType() ?>" name="x_view_count" id="x_view_count" data-table="faq_items" data-field="x_view_count" value="<?= $Page->view_count->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->view_count->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->view_count->formatPattern()) ?>"<?= $Page->view_count->editAttributes() ?> aria-describedby="x_view_count_help">
<?= $Page->view_count->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->view_count->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tags->Visible) { // tags ?>
    <div id="r_tags"<?= $Page->tags->rowAttributes() ?>>
        <label id="elh_faq_items_tags" for="x_tags" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tags->caption() ?><?= $Page->tags->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tags->cellAttributes() ?>>
<span id="el_faq_items_tags">
<textarea data-table="faq_items" data-field="x_tags" name="x_tags" id="x_tags" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->tags->getPlaceHolder()) ?>"<?= $Page->tags->editAttributes() ?> aria-describedby="x_tags_help"><?= $Page->tags->EditValue ?></textarea>
<?= $Page->tags->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tags->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ffaq_itemsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ffaq_itemsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("faq_items");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
