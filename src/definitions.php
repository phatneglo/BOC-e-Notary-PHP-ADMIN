<?php

namespace PHPMaker2024\eNotary;

use Slim\Views\PhpRenderer;
use Slim\Csrf\Guard;
use Slim\HttpCache\CacheProvider;
use Slim\Flash\Messages;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\DBAL\Platforms;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Mime\MimeTypes;
use FastRoute\RouteParser\Std;
use Illuminate\Encryption\Encrypter;
use HTMLPurifier_Config;
use HTMLPurifier;

// Connections and entity managers
$definitions = [];
$dbids = array_keys(Config("Databases"));
foreach ($dbids as $dbid) {
    $definitions["connection." . $dbid] = \DI\factory(function (string $dbid) {
        return ConnectDb(Db($dbid));
    })->parameter("dbid", $dbid);
    $definitions["entitymanager." . $dbid] = \DI\factory(function (ContainerInterface $c, string $dbid) {
        $cache = IsDevelopment()
            ? DoctrineProvider::wrap(new ArrayAdapter())
            : DoctrineProvider::wrap(new FilesystemAdapter(directory: Config("DOCTRINE.CACHE_DIR")));
        $config = Setup::createAttributeMetadataConfiguration(
            Config("DOCTRINE.METADATA_DIRS"),
            IsDevelopment(),
            null,
            $cache
        );
        $conn = $c->get("connection." . $dbid);
        return new EntityManager($conn, $config);
    })->parameter("dbid", $dbid);
}

return [
    "app.cache" => \DI\create(CacheProvider::class),
    "app.flash" => fn(ContainerInterface $c) => new Messages(),
    "app.view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "views/"),
    "email.view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "lang/"),
    "sms.view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "lang/"),
    "app.audit" => fn(ContainerInterface $c) => (new Logger("audit"))->pushHandler(new AuditTrailHandler($GLOBALS["RELATIVE_PATH"] . "log/audit.log")), // For audit trail
    "app.logger" => fn(ContainerInterface $c) => (new Logger("log"))->pushHandler(new RotatingFileHandler($GLOBALS["RELATIVE_PATH"] . "log/log.log")),
    "sql.logger" => function (ContainerInterface $c) {
        $loggers = [];
        if (Config("DEBUG")) {
            $loggers[] = $c->get("debug.stack");
        }
        return (count($loggers) > 0) ? new LoggerChain($loggers) : null;
    },
    "app.csrf" => fn(ContainerInterface $c) => new Guard($GLOBALS["ResponseFactory"], Config("CSRF_PREFIX")),
    "html.purifier.config" => fn(ContainerInterface $c) => HTMLPurifier_Config::createDefault(),
    "html.purifier" => fn(ContainerInterface $c) => new HTMLPurifier($c->get("html.purifier.config")),
    "debug.stack" => \DI\create(DebugStack::class),
    "debug.sql.logger" => \DI\create(DebugSqlLogger::class),
    "debug.timer" => \DI\create(Timer::class),
    "app.security" => \DI\create(AdvancedSecurity::class),
    "user.profile" => \DI\create(UserProfile::class),
    "app.session" => \DI\create(HttpSession::class),
    "mime.types" => \DI\create(MimeTypes::class),
    "app.language" => \DI\create(Language::class),
    PermissionMiddleware::class => \DI\create(PermissionMiddleware::class),
    ApiPermissionMiddleware::class => \DI\create(ApiPermissionMiddleware::class),
    JwtMiddleware::class => \DI\create(JwtMiddleware::class),
    Std::class => \DI\create(Std::class),
    Encrypter::class => fn(ContainerInterface $c) => new Encrypter(AesEncryptionKey(base64_decode(Config("AES_ENCRYPTION_KEY"))), Config("AES_ENCRYPTION_CIPHER")),

    // Tables
    "audit_logs" => \DI\create(AuditLogs::class),
    "systems" => \DI\create(Systems::class),
    "user_level_assignments" => \DI\create(UserLevelAssignments::class),
    "_user_levels" => \DI\create(UserLevels::class),
    "users" => \DI\create(Users::class),
    "aggregated_audit_logs" => \DI\create(AggregatedAuditLogs::class),
    "MainDashboard" => \DI\create(MainDashboard::class),
    "departments" => \DI\create(Departments::class),
    "notifications" => \DI\create(Notifications::class),
    "UserManagement" => \DI\create(UserManagement::class),
    "UserAccess" => \DI\create(UserAccess::class),
    "psgc" => \DI\create(Psgc::class),
    "document_activity_logs" => \DI\create(DocumentActivityLogs::class),
    "document_attachments" => \DI\create(DocumentAttachments::class),
    "document_fields" => \DI\create(DocumentFields::class),
    "document_templates" => \DI\create(DocumentTemplates::class),
    "document_verification" => \DI\create(DocumentVerification::class),
    "documents" => \DI\create(Documents::class),
    "faq_categories" => \DI\create(FaqCategories::class),
    "faq_items" => \DI\create(FaqItems::class),
    "fee_schedules" => \DI\create(FeeSchedules::class),
    "notarization_queue" => \DI\create(NotarizationQueue::class),
    "notarization_requests" => \DI\create(NotarizationRequests::class),
    "notarized_documents" => \DI\create(NotarizedDocuments::class),
    "notary_qr_settings" => \DI\create(NotaryQrSettings::class),
    "payment_methods" => \DI\create(PaymentMethods::class),
    "payment_transactions" => \DI\create(PaymentTransactions::class),
    "pdf_metadata" => \DI\create(PdfMetadata::class),
    "support_request_history" => \DI\create(SupportRequestHistory::class),
    "support_requests" => \DI\create(SupportRequests::class),
    "system_status" => \DI\create(SystemStatus::class),
    "template_categories" => \DI\create(TemplateCategories::class),
    "template_fields" => \DI\create(TemplateFields::class),
    "user_templates" => \DI\create(UserTemplates::class),
    "verification_attempts" => \DI\create(VerificationAttempts::class),
    "refresh_tokens" => \DI\create(RefreshTokens::class),

    // User table
    "usertable" => \DI\get("users"),
] + $definitions;
