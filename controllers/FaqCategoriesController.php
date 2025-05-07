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

class FaqCategoriesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/FaqCategoriesList[/{category_id}]", [PermissionMiddleware::class], "list.faq_categories")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqCategoriesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/FaqCategoriesAdd[/{category_id}]", [PermissionMiddleware::class], "add.faq_categories")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqCategoriesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/FaqCategoriesView[/{category_id}]", [PermissionMiddleware::class], "view.faq_categories")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqCategoriesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/FaqCategoriesEdit[/{category_id}]", [PermissionMiddleware::class], "edit.faq_categories")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqCategoriesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/FaqCategoriesDelete[/{category_id}]", [PermissionMiddleware::class], "delete.faq_categories")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqCategoriesDelete");
    }
}
