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

class DocumentFieldsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/DocumentFieldsList[/{document_field_id}]", [PermissionMiddleware::class], "list.document_fields")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentFieldsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/DocumentFieldsAdd[/{document_field_id}]", [PermissionMiddleware::class], "add.document_fields")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentFieldsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/DocumentFieldsView[/{document_field_id}]", [PermissionMiddleware::class], "view.document_fields")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentFieldsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/DocumentFieldsEdit[/{document_field_id}]", [PermissionMiddleware::class], "edit.document_fields")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentFieldsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/DocumentFieldsDelete[/{document_field_id}]", [PermissionMiddleware::class], "delete.document_fields")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentFieldsDelete");
    }
}
