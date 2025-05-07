<?php

namespace PHPMaker2024\eNotary;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\eNotary\Attributes\Delete;
use PHPMaker2024\eNotary\Attributes\Get;
use PHPMaker2024\eNotary\Attributes\Map;
use PHPMaker2024\eNotary\Attributes\Options;
use PHPMaker2024\eNotary\Attributes\Patch;
use PHPMaker2024\eNotary\Attributes\Post;
use PHPMaker2024\eNotary\Attributes\Put;

class VerificationAttemptsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/VerificationAttemptsList[/{attempt_id}]", [PermissionMiddleware::class], "list.verification_attempts")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VerificationAttemptsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/VerificationAttemptsAdd[/{attempt_id}]", [PermissionMiddleware::class], "add.verification_attempts")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VerificationAttemptsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/VerificationAttemptsView[/{attempt_id}]", [PermissionMiddleware::class], "view.verification_attempts")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VerificationAttemptsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/VerificationAttemptsEdit[/{attempt_id}]", [PermissionMiddleware::class], "edit.verification_attempts")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VerificationAttemptsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/VerificationAttemptsDelete[/{attempt_id}]", [PermissionMiddleware::class], "delete.verification_attempts")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VerificationAttemptsDelete");
    }
}
