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

class DocumentVerificationController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/DocumentVerificationList[/{verification_id}]", [PermissionMiddleware::class], "list.document_verification")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentVerificationList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/DocumentVerificationAdd[/{verification_id}]", [PermissionMiddleware::class], "add.document_verification")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentVerificationAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/DocumentVerificationView[/{verification_id}]", [PermissionMiddleware::class], "view.document_verification")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentVerificationView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/DocumentVerificationEdit[/{verification_id}]", [PermissionMiddleware::class], "edit.document_verification")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentVerificationEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/DocumentVerificationDelete[/{verification_id}]", [PermissionMiddleware::class], "delete.document_verification")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentVerificationDelete");
    }
}
