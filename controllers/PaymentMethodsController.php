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

class PaymentMethodsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PaymentMethodsList[/{method_id}]", [PermissionMiddleware::class], "list.payment_methods")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentMethodsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PaymentMethodsAdd[/{method_id}]", [PermissionMiddleware::class], "add.payment_methods")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentMethodsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PaymentMethodsView[/{method_id}]", [PermissionMiddleware::class], "view.payment_methods")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentMethodsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PaymentMethodsEdit[/{method_id}]", [PermissionMiddleware::class], "edit.payment_methods")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentMethodsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PaymentMethodsDelete[/{method_id}]", [PermissionMiddleware::class], "delete.payment_methods")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentMethodsDelete");
    }
}
