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

class RefreshTokensController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/RefreshTokensList[/{token_id}]", [PermissionMiddleware::class], "list.refresh_tokens")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RefreshTokensList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/RefreshTokensAdd[/{token_id}]", [PermissionMiddleware::class], "add.refresh_tokens")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RefreshTokensAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/RefreshTokensView[/{token_id}]", [PermissionMiddleware::class], "view.refresh_tokens")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RefreshTokensView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/RefreshTokensEdit[/{token_id}]", [PermissionMiddleware::class], "edit.refresh_tokens")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RefreshTokensEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/RefreshTokensDelete[/{token_id}]", [PermissionMiddleware::class], "delete.refresh_tokens")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RefreshTokensDelete");
    }
}
