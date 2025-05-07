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

class DocumentTemplatesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/DocumentTemplatesList[/{template_id}]", [PermissionMiddleware::class], "list.document_templates")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentTemplatesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/DocumentTemplatesAdd[/{template_id}]", [PermissionMiddleware::class], "add.document_templates")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentTemplatesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/DocumentTemplatesView[/{template_id}]", [PermissionMiddleware::class], "view.document_templates")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentTemplatesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/DocumentTemplatesEdit[/{template_id}]", [PermissionMiddleware::class], "edit.document_templates")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentTemplatesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/DocumentTemplatesDelete[/{template_id}]", [PermissionMiddleware::class], "delete.document_templates")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentTemplatesDelete");
    }
}
