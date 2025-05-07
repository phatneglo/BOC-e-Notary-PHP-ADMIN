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

class PaymentTransactionsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PaymentTransactionsList[/{transaction_id}]", [PermissionMiddleware::class], "list.payment_transactions")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentTransactionsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PaymentTransactionsAdd[/{transaction_id}]", [PermissionMiddleware::class], "add.payment_transactions")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentTransactionsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PaymentTransactionsView[/{transaction_id}]", [PermissionMiddleware::class], "view.payment_transactions")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentTransactionsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PaymentTransactionsEdit[/{transaction_id}]", [PermissionMiddleware::class], "edit.payment_transactions")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentTransactionsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PaymentTransactionsDelete[/{transaction_id}]", [PermissionMiddleware::class], "delete.payment_transactions")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PaymentTransactionsDelete");
    }
}
