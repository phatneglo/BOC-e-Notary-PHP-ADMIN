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

class DocumentActivityLogsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/DocumentActivityLogsList[/{log_id}]", [PermissionMiddleware::class], "list.document_activity_logs")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentActivityLogsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/DocumentActivityLogsAdd[/{log_id}]", [PermissionMiddleware::class], "add.document_activity_logs")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentActivityLogsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/DocumentActivityLogsView[/{log_id}]", [PermissionMiddleware::class], "view.document_activity_logs")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentActivityLogsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/DocumentActivityLogsEdit[/{log_id}]", [PermissionMiddleware::class], "edit.document_activity_logs")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentActivityLogsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/DocumentActivityLogsDelete[/{log_id}]", [PermissionMiddleware::class], "delete.document_activity_logs")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentActivityLogsDelete");
    }
}
