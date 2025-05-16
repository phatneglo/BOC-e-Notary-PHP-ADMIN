<?php
// app/api/routes/index.routes.php
namespace PHPMaker2024\eNotary;

// Load all route files
require_once __DIR__ . '/auth.routes.php';
require_once __DIR__ . '/user.routes.php';
require_once __DIR__ . '/notary.routes.php';
require_once __DIR__ . '/templates.routes.php';
require_once __DIR__ . '/template-sections.routes.php';
require_once __DIR__ . '/document-status.routes.php';
require_once __DIR__ . '/documents.routes.php';
require_once __DIR__ . '/requests.routes.php';
require_once __DIR__ . '/notarized.routes.php';
require_once __DIR__ . '/payments.routes.php';
require_once __DIR__ . '/verify.routes.php';
require_once __DIR__ . '/notification.routes.php';
require_once __DIR__ . '/qrcode.routes.php';
require_once __DIR__ . '/system.routes.php';
require_once __DIR__ . '/fee-schedules.routes.php';
require_once __DIR__ . '/permissions.routes.php';
require_once __DIR__ . '/maya-test.routes.php'; // Maya payment testing route
