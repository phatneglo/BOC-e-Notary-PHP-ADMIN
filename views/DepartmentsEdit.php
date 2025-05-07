<?php

namespace PHPMaker2024\eNotary;

// Page object
$DepartmentsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fdepartmentsedit" id="fdepartmentsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { departments: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fdepartmentsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdepartmentsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["department_id", [fields.department_id.visible && fields.department_id.required ? ew.Validators.required(fields.department_id.caption) : null], fields.department_id.isInvalid],
            ["department_name", [fields.department_name.visible && fields.department_name.required ? ew.Validators.required(fields.department_name.caption) : null], fields.department_name.isInvalid],
            ["description", [fields.description.visible && fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid]
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
<input type="hidden" name="t" value="departments">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->department_id->Visible) { // department_id ?>
    <div id="r_department_id"<?= $Page->department_id->rowAttributes() ?>>
        <label id="elh_departments_department_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->department_id->caption() ?><?= $Page->department_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->department_id->cellAttributes() ?>>
<span id="el_departments_department_id">
<span<?= $Page->department_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->department_id->getDisplayValue($Page->department_id->EditValue))) ?>"></span>
<input type="hidden" data-table="departments" data-field="x_department_id" data-hidden="1" name="x_department_id" id="x_department_id" value="<?= HtmlEncode($Page->department_id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->department_name->Visible) { // department_name ?>
    <div id="r_department_name"<?= $Page->department_name->rowAttributes() ?>>
        <label id="elh_departments_department_name" for="x_department_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->department_name->caption() ?><?= $Page->department_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->department_name->cellAttributes() ?>>
<span id="el_departments_department_name">
<input type="<?= $Page->department_name->getInputTextType() ?>" name="x_department_name" id="x_department_name" data-table="departments" data-field="x_department_name" value="<?= $Page->department_name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->department_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->department_name->formatPattern()) ?>"<?= $Page->department_name->editAttributes() ?> aria-describedby="x_department_name_help">
<?= $Page->department_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->department_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description"<?= $Page->description->rowAttributes() ?>>
        <label id="elh_departments_description" for="x_description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->description->cellAttributes() ?>>
<span id="el_departments_description">
<textarea data-table="departments" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help"><?= $Page->description->EditValue ?></textarea>
<?= $Page->description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("users", explode(",", $Page->getCurrentDetailTable())) && $users->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("users", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "UsersGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdepartmentsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdepartmentsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("departments");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
