<?php

/**
 * PHPMaker 2024 configuration file (Development)
 */

return [
    "Databases" => [
        "DB" => ["id" => "DB", "type" => "POSTGRESQL", "qs" => "\"", "qe" => "\"", "host" => "localhost", "port" => "5432", "user" => "postgres", "password" => "0yq5h3to9", "dbname" => "lwua_wms_uac", "schema" => "public"]
    ],
    "SMTP" => [
        "PHPMAILER_MAILER" => "smtp", // PHPMailer mailer
        "SERVER" => "localhost", // SMTP server
        "SERVER_PORT" => 25, // SMTP server port 
        "SECURE_OPTION" => "",
        "SERVER_USERNAME" => "", // SMTP server user name
        "SERVER_PASSWORD" => "", // SMTP server password
    ],
    "JWT" => [
        "SECRET_KEY" => "0yq5h3to9", // JWT secret key
        "ALGORITHM" => "HS512", // JWT algorithm
        "AUTH_HEADER" => "X-Authorization", // API authentication header (Note: The "Authorization" header is removed by IIS, use "X-Authorization" instead.)
        "NOT_BEFORE_TIME" => 0, // API access time before login
        "EXPIRE_TIME" => 600 // API expire time
    ],
    "EMQX" => [
        "API_URL" => "http://152.42.249.97:18083",  // EMQX API endpoint
        "API_KEY" => "EMQX API KEY",                // EMQX API key
        "API_SECRET" => "EMQX API SECRET  KEY"      // EMQX API secret
    ],    
    "DO" => [
        "SPACES" => [
            "END_POINT" => "https://sgp1.digitaloceanspaces.com", // S3 bucket name
            "BUCKET" => "pg-itbs-dev", // S3 bucket name
            "REGION" => "sgp1", // S3 region
            "KEY" => "DO00WVEWRUJZDU2XGJRB", // S3 access
            "SECRET" => "7kAG4m6BfRE07mnnZygCwQSTqn+hpKmK0o9zVGT0D+4" // S3 secret
        ],
    ],
    "UPLOAD_TEMP_PATH" => "D:/Projects/DOF/ARCHIVE-GIS-PHP/temp_uploads/", // Upload temp path (absolute local physical path)
    "UPLOAD_TEMP_HREF_PATH" => "//dof-gis-archive.local/temp_uploads/", // Upload temp href path (absolute URL path for download)
    "UPLOAD_DEST_PATH" => "s3://pg-itbs-dev/DOF-LPAMS/" // Upload destination path (relative to app root)

];
