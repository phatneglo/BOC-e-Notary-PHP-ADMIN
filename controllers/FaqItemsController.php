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

class FaqItemsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/FaqItemsList[/{faq_id}]", [PermissionMiddleware::class], "list.faq_items")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqItemsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/FaqItemsAdd[/{faq_id}]", [PermissionMiddleware::class], "add.faq_items")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqItemsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/FaqItemsView[/{faq_id}]", [PermissionMiddleware::class], "view.faq_items")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqItemsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/FaqItemsEdit[/{faq_id}]", [PermissionMiddleware::class], "edit.faq_items")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqItemsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/FaqItemsDelete[/{faq_id}]", [PermissionMiddleware::class], "delete.faq_items")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FaqItemsDelete");
    }
}
