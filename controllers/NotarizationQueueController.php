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

class NotarizationQueueController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/NotarizationQueueList[/{queue_id}]", [PermissionMiddleware::class], "list.notarization_queue")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationQueueList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/NotarizationQueueAdd[/{queue_id}]", [PermissionMiddleware::class], "add.notarization_queue")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationQueueAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/NotarizationQueueView[/{queue_id}]", [PermissionMiddleware::class], "view.notarization_queue")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationQueueView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/NotarizationQueueEdit[/{queue_id}]", [PermissionMiddleware::class], "edit.notarization_queue")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationQueueEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/NotarizationQueueDelete[/{queue_id}]", [PermissionMiddleware::class], "delete.notarization_queue")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotarizationQueueDelete");
    }
}
