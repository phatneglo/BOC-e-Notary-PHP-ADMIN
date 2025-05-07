<?php

namespace PHPMaker2024\eNotary;

// Page object
$Register = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { users: currentTable } });
var currentPageID = ew.PAGE_ID = "register";
var currentForm;
var fregister;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fregister")
        .setPageId("register")

        // Add fields
        .setFields([
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null], fields.user_id.isInvalid],
            ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null, ew.Validators.username(fields._username.raw)], fields._username.isInvalid],
            ["_email", [fields._email.visible && fields._email.required ? ew.Validators.required(fields._email.caption) : null], fields._email.isInvalid],
            ["c_password_hash", [ew.Validators.required(ew.language.phrase("ConfirmPassword")), ew.Validators.mismatchPassword], fields.password_hash.isInvalid],
            ["password_hash", [fields.password_hash.visible && fields.password_hash.required ? ew.Validators.required(fields.password_hash.caption) : null, ew.Validators.password(fields.password_hash.raw)], fields.password_hash.isInvalid],
            ["mobile_number", [fields.mobile_number.visible && fields.mobile_number.required ? ew.Validators.required(fields.mobile_number.caption) : null], fields.mobile_number.isInvalid],
            ["first_name", [fields.first_name.visible && fields.first_name.required ? ew.Validators.required(fields.first_name.caption) : null], fields.first_name.isInvalid],
            ["middle_name", [fields.middle_name.visible && fields.middle_name.required ? ew.Validators.required(fields.middle_name.caption) : null], fields.middle_name.isInvalid],
            ["last_name", [fields.last_name.visible && fields.last_name.required ? ew.Validators.required(fields.last_name.caption) : null], fields.last_name.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)
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
    // Write your client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fregister" id="fregister" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="t" value="users">
<input type="hidden" name="action" id="action" value="insert">
<div class="ew-register-div"><!-- page* -->
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <label id="elh_users__username" for="x__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_username->cellAttributes() ?>>
<span id="el_users__username">
<input type="<?= $Page->_username->getInputTextType() ?>" name="x__username" id="x__username" data-table="users" data-field="x__username" value="<?= $Page->_username->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_username->formatPattern()) ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
    <div id="r__email"<?= $Page->_email->rowAttributes() ?>>
        <label id="elh_users__email" for="x__email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_email->caption() ?><?= $Page->_email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_email->cellAttributes() ?>>
<span id="el_users__email">
<input type="<?= $Page->_email->getInputTextType() ?>" name="x__email" id="x__email" data-table="users" data-field="x__email" value="<?= $Page->_email->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->_email->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_email->formatPattern()) ?>"<?= $Page->_email->editAttributes() ?> aria-describedby="x__email_help">
<?= $Page->_email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_email->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->password_hash->Visible) { // password_hash ?>
    <div id="r_password_hash"<?= $Page->password_hash->rowAttributes() ?>>
        <label id="elh_users_password_hash" for="x_password_hash" class="<?= $Page->LeftColumnClass ?>"><?= $Page->password_hash->caption() ?><?= $Page->password_hash->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->password_hash->cellAttributes() ?>>
<span id="el_users_password_hash">
<div class="input-group">
    <input type="password" name="x_password_hash" id="x_password_hash" autocomplete="new-password" data-table="users" data-field="x_password_hash" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->password_hash->getPlaceHolder()) ?>"<?= $Page->password_hash->editAttributes() ?> aria-describedby="x_password_hash_help">
    <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fa-solid fa-eye"></i></button>
</div>
<?= $Page->password_hash->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->password_hash->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->password_hash->Visible) { // password_hash ?>
    <div id="r_c_password_hash" class="row">
        <label id="elh_c_users_password_hash" for="c_password_hash" class="<?= $Page->LeftColumnClass ?>"><?= $Language->phrase("Confirm") ?> <?= $Page->password_hash->caption() ?><?= $Page->password_hash->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->password_hash->cellAttributes() ?>>
<span id="el_c_users_password_hash">
<div class="input-group">
    <input type="password" name="c_password_hash" id="c_password_hash" autocomplete="new-password" data-table="users" data-field="c_password_hash" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->password_hash->getPlaceHolder()) ?>"<?= $Page->password_hash->editAttributes() ?> aria-describedby="x_password_hash_help">
    <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fa-solid fa-eye"></i></button>
</div>
<?= $Page->password_hash->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->password_hash->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mobile_number->Visible) { // mobile_number ?>
    <div id="r_mobile_number"<?= $Page->mobile_number->rowAttributes() ?>>
        <label id="elh_users_mobile_number" for="x_mobile_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mobile_number->caption() ?><?= $Page->mobile_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mobile_number->cellAttributes() ?>>
<span id="el_users_mobile_number">
<input type="<?= $Page->mobile_number->getInputTextType() ?>" name="x_mobile_number" id="x_mobile_number" data-table="users" data-field="x_mobile_number" value="<?= $Page->mobile_number->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->mobile_number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->mobile_number->formatPattern()) ?>"<?= $Page->mobile_number->editAttributes() ?> aria-describedby="x_mobile_number_help">
<?= $Page->mobile_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mobile_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->first_name->Visible) { // first_name ?>
    <div id="r_first_name"<?= $Page->first_name->rowAttributes() ?>>
        <label id="elh_users_first_name" for="x_first_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->first_name->caption() ?><?= $Page->first_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->first_name->cellAttributes() ?>>
<span id="el_users_first_name">
<input type="<?= $Page->first_name->getInputTextType() ?>" name="x_first_name" id="x_first_name" data-table="users" data-field="x_first_name" value="<?= $Page->first_name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->first_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->first_name->formatPattern()) ?>"<?= $Page->first_name->editAttributes() ?> aria-describedby="x_first_name_help">
<?= $Page->first_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->first_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->middle_name->Visible) { // middle_name ?>
    <div id="r_middle_name"<?= $Page->middle_name->rowAttributes() ?>>
        <label id="elh_users_middle_name" for="x_middle_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->middle_name->caption() ?><?= $Page->middle_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->middle_name->cellAttributes() ?>>
<span id="el_users_middle_name">
<input type="<?= $Page->middle_name->getInputTextType() ?>" name="x_middle_name" id="x_middle_name" data-table="users" data-field="x_middle_name" value="<?= $Page->middle_name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->middle_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->middle_name->formatPattern()) ?>"<?= $Page->middle_name->editAttributes() ?> aria-describedby="x_middle_name_help">
<?= $Page->middle_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->middle_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->last_name->Visible) { // last_name ?>
    <div id="r_last_name"<?= $Page->last_name->rowAttributes() ?>>
        <label id="elh_users_last_name" for="x_last_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->last_name->caption() ?><?= $Page->last_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->last_name->cellAttributes() ?>>
<span id="el_users_last_name">
<input type="<?= $Page->last_name->getInputTextType() ?>" name="x_last_name" id="x_last_name" data-table="users" data-field="x_last_name" value="<?= $Page->last_name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->last_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->last_name->formatPattern()) ?>"<?= $Page->last_name->editAttributes() ?> aria-describedby="x_last_name_help">
<?= $Page->last_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->last_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn disabled enable-on-init" name="btn-action" id="btn-action" type="submit" form="fregister"><?= $Language->phrase("RegisterBtn") ?></button>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("users");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your startup script here, no need to add script tags.
});
</script>
