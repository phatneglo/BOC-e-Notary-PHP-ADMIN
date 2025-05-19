<?php

namespace PHPMaker2024\eNotary;

// Page object
$TransactionDashboard = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php include(dirname(__DIR__, 1) . "/app/pages/transaction-dashboard.php"); ?>

<?= GetDebugMessage() ?>
