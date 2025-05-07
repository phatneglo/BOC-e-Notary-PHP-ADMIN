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

class TemplateCategoriesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TemplateCategoriesList[/{category_id}]", [PermissionMiddleware::class], "list.template_categories")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateCategoriesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TemplateCategoriesAdd[/{category_id}]", [PermissionMiddleware::class], "add.template_categories")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateCategoriesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TemplateCategoriesView[/{category_id}]", [PermissionMiddleware::class], "view.template_categories")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateCategoriesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TemplateCategoriesEdit[/{category_id}]", [PermissionMiddleware::class], "edit.template_categories")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateCategoriesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/TemplateCategoriesDelete[/{category_id}]", [PermissionMiddleware::class], "delete.template_categories")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TemplateCategoriesDelete");
    }
}
