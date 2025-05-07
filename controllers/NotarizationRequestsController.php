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

class NotarizationRequestsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/NotarizationRequestsList[/{request_id}]", [PermissionMiddleware::class], "list.notarization_requests")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationRequestsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/NotarizationRequestsAdd[/{request_id}]", [PermissionMiddleware::class], "add.notarization_requests")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationRequestsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/NotarizationRequestsView[/{request_id}]", [PermissionMiddleware::class], "view.notarization_requests")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationRequestsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/NotarizationRequestsEdit[/{request_id}]", [PermissionMiddleware::class], "edit.notarization_requests")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationRequestsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/NotarizationRequestsDelete[/{request_id}]", [PermissionMiddleware::class], "delete.notarization_requests")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationRequestsDelete");
    }
}
