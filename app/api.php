<?php
// app/api.php

namespace PHPMaker2024\eNotary;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * JWT Authentication Middleware
 * Validates JWT tokens and attaches user information to the request
 */
$jwtMiddleware = function (Request $request, RequestHandler $handler): Response {
    $authHeader = $request->getHeaderLine('Authorization');
    if (!$authHeader) {
        $authHeader = $request->getHeaderLine('X-Authorization');
    }
    if (!$authHeader) {
        $authHeader = GetJwtToken();
    }

    $jwt = str_replace('Bearer ', '', $authHeader);

    // Decode the JWT
    $payload = DecodeJwt($jwt);
    // If the JWT is invalid, return a 401 Unauthorized response
    if (isset($payload['failureMessage']) || !isset($jwt) || empty($jwt)) {
        $response = new \Nyholm\Psr7\Response();
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $response->withStatus(401);
    }

    // Attach user_id to the request
    $user_id = $payload['userid'] ?? null;
    $request = $request->withAttribute('user_id', $user_id);

    // If the JWT is valid, pass the request to the next middleware
    $response = $handler->handle($request);
    return $response;
};

/**
 * Access Control Middleware
 * Validates user permissions for specific routes
 */
$accessMiddleware = function (Request $request, RequestHandler $handler): Response {
    $authHeader = $request->getHeaderLine('Authorization');
    if (!$authHeader) {
        $authHeader = $request->getHeaderLine('X-Authorization');
    }
    if (!$authHeader) {
        $authHeader = GetJwtToken();
    }
    $jwt = str_replace('Bearer ', '', $authHeader);
    
    // Decode the JWT
    $payload = DecodeJwt($jwt);
    
    // Get user level from payload
    $userLevel = $payload['userlevel'] ?? -2; // Default to -2 if not found

    if (preg_match('/-1/', $userLevel)) return $handler->handle($request);

    // Get the current route path
    $path = $request->getUri()->getPath();
    
    // Determine which page to check based on the route
    $pageName = '';
    if (strpos($path, '/access') !== false) {
        $pageName = '{UAC}UserAccess.php';
    } elseif (strpos($path, '/permission') !== false) {
        $pageName = '{UAC}UserManagement.php';
    }
    
    if ($pageName) {
        // Check permission in database
        $sql = "SELECT permission FROM user_level_permissions 
            WHERE user_level_id = ANY(STRING_TO_ARRAY(" . QuotedValue($userLevel, DataType::STRING) . ", ',')::integer[]) 
            AND table_name = " . QuotedValue($pageName, DataType::STRING) . "
            ORDER BY permission DESC 
            LIMIT 1";
        $permission = ExecuteScalar($sql, "DB");
        
        // If no permission or permission is 0, return unauthorized
        if (!$permission || $permission <= 0) {
            $response = new \Nyholm\Psr7\Response();
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'You do not have permission to access this resource'
            ]));
            return $response->withStatus(403);
        }
    }

    // If we get here, the user has permission
    $response = $handler->handle($request);
    return $response;
};

// Include all service files first
$serviceBasePath = __DIR__ . "/api/services/";
$serviceFiles = glob($serviceBasePath . "*.php");

// Loop through each service file and include it
foreach ($serviceFiles as $serviceFile) {
    require_once $serviceFile;
}

// Include the routes index file (which includes all route files)
require_once __DIR__ . "/api/routes/index.routes.php";