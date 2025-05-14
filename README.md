# e-Notarize Application

A comprehensive electronic notarization platform built with PHPMaker for the eNotary System for BOC.

## System Overview

The e-Notarize application is a full-featured electronic notarization system that allows for secure document processing, verification, and notarization. Built on PHPMaker with a modern API architecture, the system handles document management, user authentication, payment processing, notifications, and more.

## Project Structure

```
├── api/                   # API endpoint handling
├── app/                   # Application core files
│   ├── api/               # API implementation
│   │   ├── lib/           # API libraries
│   │   ├── middlewares/   # API middlewares
│   │   ├── routes/        # API route definitions
│   │   └── services/      # API services
│   ├── lib/               # Core libraries
│   ├── pages/             # Page-specific logic
│   └── sql/               # SQL queries
├── controllers/           # MVC Controllers
├── models/                # Data models
├── views/                 # View templates
└── vendor/                # Composer dependencies
```

## API Architecture

The e-Notarize API is built using a modular, RESTful architecture. It uses middleware for authentication and authorization, and implements well-structured routes for different functional areas of the application.

### API Initialization

The API is initialized in api.php, which:
- Loads all service files from services
- Loads middleware components from middlewares
- Loads routes from index.routes.php

### API Routes

The routes are organized by functional areas in separate files:

- `auth.routes.php`: Authentication endpoints (login, logout, token refresh)
- `user.routes.php`: User management
- notary.routes.php: Notary-specific functions
- `templates.routes.php`: Document template management
- document-status.routes.php: Document status tracking
- documents.routes.php: Document operations
- `requests.routes.php`: Notarization request management
- notarized.routes.php: Completed notarization handling
- `payments.routes.php`: Payment processing
- `verify.routes.php`: Document verification
- notification.routes.php: System notifications
- `qrcode.routes.php`: QR code generation
- `system.routes.php`: System-wide operations
- fee-schedules.routes.php: Fee management

### Authentication & Security

The API uses JWT (JSON Web Tokens) for authentication via the `jwtMiddleware` which:

1. Extracts the JWT from the Authorization header
2. Validates the token
3. Attaches the user_id to the request for downstream handlers
4. Returns a 401 error if the token is invalid

Additional middleware includes:
- accessMiddleware.php: Role-based access control
- adminOnlyMiddleware.php: Restricts endpoints to admin users

## Creating New API Endpoints

To add new API functionality to the e-Notarize system, follow these steps:

### 1. Create a Service

Create a new service file in services to handle business logic:

```php
<?php
namespace PHPMaker2024\eNotary;

class YourNewService
{
    public function doSomething($param1, $param2)
    {
        // Implement your business logic here
        return [
            'success' => true,
            'data' => $result
        ];
    }
}
```

### 2. Create Routes

Create a new route file in routes (e.g., `your-feature.routes.php`):

```php
<?php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /your-feature Get your feature
 * @apiName GetYourFeature
 * @apiGroup YourFeature
 */
$app->get("/your-feature", function ($request, $response, $args) {
    $service = new YourNewService();
    $userId = $request->getAttribute('user_id');
    return $response->withJson($service->doSomething($userId));
})->add($jwtMiddleware);

/**
 * @api {post} /your-feature Create your feature
 * @apiName CreateYourFeature
 * @apiGroup YourFeature
 */
$app->post("/your-feature", function ($request, $response, $args) {
    $service = new YourNewService();
    $userId = $request->getAttribute('user_id');
    $data = $request->getParsedBody();
    return $response->withJson($service->createSomething($userId, $data));
})->add($jwtMiddleware);
```

### 3. Register Your Routes

Add your route file to index.routes.php:

```php
// Add this line to the existing file
require_once __DIR__ . '/your-feature.routes.php';
```

## API Documentation

The API endpoints use JSDoc-style annotations that can be processed by documentation generators like apiDoc. These annotations provide important information about:

- The HTTP method
- The endpoint URL
- Required parameters
- Response formats
- Authentication requirements

## File Storage

The application uses AWS S3-compatible storage (Digital Ocean Spaces) for file storage. The configuration is handled in global.php.

## Authentication Flow

1. Users authenticate via the login endpoint
2. A JWT token is issued
3. The token must be included in the `Authorization` header of all subsequent requests
4. The `jwtMiddleware` validates the token and attaches user information to the request

## Environment Setup

The application relies on environment-specific configuration. Copy and modify the example configuration file:

```bash
cp config.example.php config.php
```

Then update the values in `config.php` according to your environment.

## Development Guidelines

When extending the API:

1. Follow the established pattern of service classes and route definitions
2. Use middleware for cross-cutting concerns like authentication
3. Document API endpoints using JSDoc-style comments
4. Use meaningful route grouping and HTTP methods
5. Validate all inputs to prevent security vulnerabilities
6. Follow a consistent response format

## Installation

To set up the e-Notarize application:

1. Clone the repository to your web server directory
2. Create a PostgreSQL database named `boc_enotary`
3. Import the database schema:
   ```bash
   psql -U your_username -d boc_enotary -f app/sql/boc_enotary.sql
   ```
4. Configure your database connection:
   ```bash
   cp config.example.php config.development.php
   ```
5. Edit `config.development.php` with your database credentials and other environment settings
6. Set appropriate file permissions
7. Set up your web server (Apache/Nginx) to point to the public directory

### System Requirements

- PHP 7.4 or higher
- PostgreSQL 10 or higher
- Apache/Nginx web server
- Composer (for dependency management)

## Logging

The application logs to the log directory. Monitor these logs for errors and debugging information.

## Debugging

For debugging API issues:

1. Check the response status code
2. Examine the response body for error messages
3. Check the application logs in log
4. Use the API debug mode for more verbose output

## Contact

For questions or support, contact the development team.
