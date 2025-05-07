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

class TemplateFieldsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TemplateFieldsList[/{field_id}]", [PermissionMiddleware::class], "list.template_fields")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateFieldsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TemplateFieldsAdd[/{field_id}]", [PermissionMiddleware::class], "add.template_fields")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateFieldsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TemplateFieldsView[/{field_id}]", [PermissionMiddleware::class], "view.template_fields")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateFieldsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TemplateFieldsEdit[/{field_id}]", [PermissionMiddleware::class], "edit.template_fields")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateFieldsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/TemplateFieldsDelete[/{field_id}]", [PermissionMiddleware::class], "delete.template_fields")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateFieldsDelete");
    }
}
