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

class DocumentStatusesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/DocumentStatusesList[/{status_id}]", [PermissionMiddleware::class], "list.document_statuses")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentStatusesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/DocumentStatusesAdd[/{status_id}]", [PermissionMiddleware::class], "add.document_statuses")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentStatusesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/DocumentStatusesView[/{status_id}]", [PermissionMiddleware::class], "view.document_statuses")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentStatusesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/DocumentStatusesEdit[/{status_id}]", [PermissionMiddleware::class], "edit.document_statuses")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentStatusesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/DocumentStatusesDelete[/{status_id}]", [PermissionMiddleware::class], "delete.document_statuses")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentStatusesDelete");
    }
}
