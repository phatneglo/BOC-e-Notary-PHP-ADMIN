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

class NotaryQrSettingsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/NotaryQrSettingsList[/{settings_id}]", [PermissionMiddleware::class], "list.notary_qr_settings")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaryQrSettingsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/NotaryQrSettingsAdd[/{settings_id}]", [PermissionMiddleware::class], "add.notary_qr_settings")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaryQrSettingsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/NotaryQrSettingsView[/{settings_id}]", [PermissionMiddleware::class], "view.notary_qr_settings")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaryQrSettingsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/NotaryQrSettingsEdit[/{settings_id}]", [PermissionMiddleware::class], "edit.notary_qr_settings")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaryQrSettingsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/NotaryQrSettingsDelete[/{settings_id}]", [PermissionMiddleware::class], "delete.notary_qr_settings")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaryQrSettingsDelete");
    }
}
