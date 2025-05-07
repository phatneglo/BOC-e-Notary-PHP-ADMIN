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

class DocumentAttachmentsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/DocumentAttachmentsList[/{attachment_id}]", [PermissionMiddleware::class], "list.document_attachments")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentAttachmentsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/DocumentAttachmentsAdd[/{attachment_id}]", [PermissionMiddleware::class], "add.document_attachments")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentAttachmentsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/DocumentAttachmentsView[/{attachment_id}]", [PermissionMiddleware::class], "view.document_attachments")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentAttachmentsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/DocumentAttachmentsEdit[/{attachment_id}]", [PermissionMiddleware::class], "edit.document_attachments")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentAttachmentsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/DocumentAttachmentsDelete[/{attachment_id}]", [PermissionMiddleware::class], "delete.document_attachments")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DocumentAttachmentsDelete");
    }
}
