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

class SupportRequestsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestsList[/{request_id}]", [PermissionMiddleware::class], "list.support_requests")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestsAdd[/{request_id}]", [PermissionMiddleware::class], "add.support_requests")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestsView[/{request_id}]", [PermissionMiddleware::class], "view.support_requests")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestsEdit[/{request_id}]", [PermissionMiddleware::class], "edit.support_requests")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestsDelete[/{request_id}]", [PermissionMiddleware::class], "delete.support_requests")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestsDelete");
    }
}
