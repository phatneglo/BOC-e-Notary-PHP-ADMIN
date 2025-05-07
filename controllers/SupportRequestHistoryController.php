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

class SupportRequestHistoryController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestHistoryList[/{history_id}]", [PermissionMiddleware::class], "list.support_request_history")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestHistoryList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestHistoryAdd[/{history_id}]", [PermissionMiddleware::class], "add.support_request_history")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestHistoryAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestHistoryView[/{history_id}]", [PermissionMiddleware::class], "view.support_request_history")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestHistoryView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestHistoryEdit[/{history_id}]", [PermissionMiddleware::class], "edit.support_request_history")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestHistoryEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/SupportRequestHistoryDelete[/{history_id}]", [PermissionMiddleware::class], "delete.support_request_history")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SupportRequestHistoryDelete");
    }
}
