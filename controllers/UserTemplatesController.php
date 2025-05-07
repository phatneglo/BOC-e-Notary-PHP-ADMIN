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

class UserTemplatesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/UserTemplatesList[/{user_template_id}]", [PermissionMiddleware::class], "list.user_templates")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserTemplatesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/UserTemplatesAdd[/{user_template_id}]", [PermissionMiddleware::class], "add.user_templates")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserTemplatesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/UserTemplatesView[/{user_template_id}]", [PermissionMiddleware::class], "view.user_templates")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserTemplatesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/UserTemplatesEdit[/{user_template_id}]", [PermissionMiddleware::class], "edit.user_templates")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserTemplatesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/UserTemplatesDelete[/{user_template_id}]", [PermissionMiddleware::class], "delete.user_templates")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UserTemplatesDelete");
    }
}
