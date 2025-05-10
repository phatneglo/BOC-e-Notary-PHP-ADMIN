<?php
namespace PHPMaker2024\eNotary;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Admin Only Middleware
 * Ensures that only administrators can access the route
 */
$adminOnlyMiddleware = function (Request $request, RequestHandler $handler): Response {
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
    $user_id = $payload['user_id'] ?? $payload['userid'] ?? $payload['id'] ?? null;
    $request = $request->withAttribute('user_id', $user_id);
    
    // Check if user has admin privileges
    $authService = new AuthService();
    if (!$authService->hasAdminAccess($user_id)) {
        // Return 403 Forbidden if user is not an admin
        $response = new \Nyholm\Psr7\Response();
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['error' => 'Forbidden', 'message' => 'Admin privileges required']));
        return $response->withStatus(403);
    }

    // If the JWT is valid and user is admin, pass the request to the next middleware
    $response = $handler->handle($request);
    return $response;
};
