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

class PdfMetadataController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PdfMetadataList[/{metadata_id}]", [PermissionMiddleware::class], "list.pdf_metadata")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PdfMetadataList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PdfMetadataAdd[/{metadata_id}]", [PermissionMiddleware::class], "add.pdf_metadata")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PdfMetadataAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PdfMetadataView[/{metadata_id}]", [PermissionMiddleware::class], "view.pdf_metadata")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PdfMetadataView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PdfMetadataEdit[/{metadata_id}]", [PermissionMiddleware::class], "edit.pdf_metadata")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PdfMetadataEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PdfMetadataDelete[/{metadata_id}]", [PermissionMiddleware::class], "delete.pdf_metadata")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PdfMetadataDelete");
    }
}
