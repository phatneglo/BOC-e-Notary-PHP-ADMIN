<?php
// app/api.php

namespace PHPMaker2024\eNotary;

// Include all service files first
$serviceBasePath = __DIR__ . "/api/services/";
$serviceFiles = glob($serviceBasePath . "*.php");

// Loop through each service file and include it
foreach ($serviceFiles as $serviceFile) {
    require_once $serviceFile;
}

$middleWareBasePath = __DIR__ . "/api/middlewares/";
$middleWareFiles = glob($middleWareBasePath . "*.php");

// Loop through each service file and include it
foreach ($middleWareFiles as $middleWareFile) {
    require_once $middleWareFile;
}


// Include the routes index file (which includes all route files)
require_once __DIR__ . "/api/routes/index.routes.php";