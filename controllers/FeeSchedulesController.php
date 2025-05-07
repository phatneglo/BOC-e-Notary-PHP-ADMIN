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

class FeeSchedulesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/FeeSchedulesList[/{fee_id}]", [PermissionMiddleware::class], "list.fee_schedules")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FeeSchedulesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/FeeSchedulesAdd[/{fee_id}]", [PermissionMiddleware::class], "add.fee_schedules")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FeeSchedulesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/FeeSchedulesView[/{fee_id}]", [PermissionMiddleware::class], "view.fee_schedules")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FeeSchedulesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/FeeSchedulesEdit[/{fee_id}]", [PermissionMiddleware::class], "edit.fee_schedules")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FeeSchedulesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/FeeSchedulesDelete[/{fee_id}]", [PermissionMiddleware::class], "delete.fee_schedules")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FeeSchedulesDelete");
    }
}
