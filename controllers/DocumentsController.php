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

class DocumentsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/DocumentsList[/{document_id}]", [PermissionMiddleware::class], "list.documents")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/DocumentsAdd[/{document_id}]", [PermissionMiddleware::class], "add.documents")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/DocumentsView[/{document_id}]", [PermissionMiddleware::class], "view.documents")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/DocumentsEdit[/{document_id}]", [PermissionMiddleware::class], "edit.documents")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/DocumentsDelete[/{document_id}]", [PermissionMiddleware::class], "delete.documents")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentsDelete");
    }
}
