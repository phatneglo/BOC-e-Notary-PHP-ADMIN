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

class PsgcController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PsgcList[/{code_10:.*}]", [PermissionMiddleware::class], "list.psgc")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PsgcList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PsgcAdd[/{code_10:.*}]", [PermissionMiddleware::class], "add.psgc")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PsgcAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PsgcView[/{code_10:.*}]", [PermissionMiddleware::class], "view.psgc")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PsgcView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PsgcEdit[/{code_10:.*}]", [PermissionMiddleware::class], "edit.psgc")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PsgcEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PsgcDelete[/{code_10:.*}]", [PermissionMiddleware::class], "delete.psgc")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PsgcDelete");
    }
}
