<?php

namespace PHPMaker2024\eNotary;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\eNotary\Attributes\Delete;
use PHPMaker2024\eNotary\Attributes\Get;
use PHPMaker2024\eNotary\Attributes\Map;
use PHPMaker2024\eNotary\Attributes\Options;
use PHPMaker2024\eNotary\Attributes\Patch;
use PHPMaker2024\eNotary\Attributes\Post;
use PHPMaker2024\eNotary\Attributes\Put;

/**
 * TransactionDashboard controller
 */
class TransactionDashboardController extends ControllerBase
{
    // custom
    #[Map(["GET", "POST", "OPTIONS"], "/TransactionDashboard[/{params:.*}]", [PermissionMiddleware::class], "custom.TransactionDashboard")]
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TransactionDashboard");
    }
}
