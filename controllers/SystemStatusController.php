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

class SystemStatusController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/SystemStatusList[/{status_id}]", [PermissionMiddleware::class], "list.system_status")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SystemStatusList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/SystemStatusAdd[/{status_id}]", [PermissionMiddleware::class], "add.system_status")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SystemStatusAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/SystemStatusView[/{status_id}]", [PermissionMiddleware::class], "view.system_status")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SystemStatusView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/SystemStatusEdit[/{status_id}]", [PermissionMiddleware::class], "edit.system_status")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SystemStatusEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/SystemStatusDelete[/{status_id}]", [PermissionMiddleware::class], "delete.system_status")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SystemStatusDelete");
    }
}
