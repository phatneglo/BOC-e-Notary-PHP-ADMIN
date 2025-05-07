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

class NotarizedDocumentsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/NotarizedDocumentsList[/{notarized_id}]", [PermissionMiddleware::class], "list.notarized_documents")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizedDocumentsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/NotarizedDocumentsAdd[/{notarized_id}]", [PermissionMiddleware::class], "add.notarized_documents")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizedDocumentsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/NotarizedDocumentsView[/{notarized_id}]", [PermissionMiddleware::class], "view.notarized_documents")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizedDocumentsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/NotarizedDocumentsEdit[/{notarized_id}]", [PermissionMiddleware::class], "edit.notarized_documents")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizedDocumentsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/NotarizedDocumentsDelete[/{notarized_id}]", [PermissionMiddleware::class], "delete.notarized_documents")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizedDocumentsDelete");
    }
}
