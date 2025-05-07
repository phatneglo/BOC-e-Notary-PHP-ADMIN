<?php
// app/api/routes/index.routes.php
namespace PHPMaker2024\eNotary;

/**
 * API Routes Index
 * Central file to include all route files
 */

// Include all individual route files
require_once __DIR__ . "/access.routes.php";
require_once __DIR__ . "/notification.routes.php";
require_once __DIR__ . "/permissions.routes.php";
require_once __DIR__ . "/service.routes.php";
