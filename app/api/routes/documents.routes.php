<?php
// app/api/routes/documents.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {post} /documents Create document
 * @apiName CreateDocument
 * @apiGroup Documents
 */
$app->post("/documents", function ($request, $response, $args) {
    $service = new DocumentService();
    $userId = Authentication::getUserId();
    $documentData = $request->getParsedBody();
    return $response->withJson($service->createDocument($userId, $documentData));
})->add($jwtMiddleware);

/**
 * @api {post} /documents/{document_id}/attachments Upload document attachment
 * @apiName UploadAttachment
 * @apiGroup Documents
 */
$app->post("/documents/{document_id}/attachments", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    $attachmentData = array_merge(
        $request->getParsedBody() ?? [],
        $request->getUploadedFiles() ?? []
    );
    return $response->withJson($service->uploadAttachment($documentId, $attachmentData));
})->add($jwtMiddleware);

/**
 * @api {get} /documents/{document_id}/attachments Get document attachments
 * @apiName GetAttachments
 * @apiGroup Documents
 */
$app->get("/documents/{document_id}/attachments", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    return $response->withJson($service->getAttachments($documentId));
})->add($jwtMiddleware);

/**
 * @api {delete} /documents/{document_id}/attachments/{attachment_id} Delete attachment
 * @apiName DeleteAttachment
 * @apiGroup Documents
 */
$app->delete("/documents/{document_id}/attachments/{attachment_id}", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    $attachmentId = isset($args['attachment_id']) ? (int)$args['attachment_id'] : 0;
    return $response->withJson($service->deleteAttachment($documentId, $attachmentId));
})->add($jwtMiddleware);

/**
 * @api {put} /documents/{document_id} Update document
 * @apiName UpdateDocument
 * @apiGroup Documents
 */
$app->put("/documents/{document_id}", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    $documentData = $request->getParsedBody();
    return $response->withJson($service->updateDocument($documentId, $documentData));
})->add($jwtMiddleware);

/**
 * @api {get} /documents/{document_id} Get document details
 * @apiName GetDocumentDetails
 * @apiGroup Documents
 */
$app->get("/documents/{document_id}", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    return $response->withJson($service->getDocumentDetails($documentId));
})->add($jwtMiddleware);

/**
 * @api {get} /documents/{document_id}/preview Get document preview
 * @apiName GetDocumentPreview
 * @apiGroup Documents
 */
$app->get("/documents/{document_id}/preview", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    return $response->withJson($service->getDocumentPreview($documentId));
})->add($jwtMiddleware);

/**
 * @api {get} /documents List user documents
 * @apiName ListDocuments
 * @apiGroup Documents
 */
$app->get("/documents", function ($request, $response, $args) {
    $service = new DocumentService();
    $userId = Authentication::getUserId();
    $params = $request->getQueryParams();
    return $response->withJson($service->listUserDocuments($userId, $params));
})->add($jwtMiddleware);

/**
 * @api {delete} /documents/{document_id} Delete document
 * @apiName DeleteDocument
 * @apiGroup Documents
 */
$app->delete("/documents/{document_id}", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    return $response->withJson($service->deleteDocument($documentId));
})->add($jwtMiddleware);

/**
 * @api {get} /documents/summary Get document summary
 * @apiName GetDocumentSummary
 * @apiGroup Documents
 */
$app->get("/documents/summary", function ($request, $response, $args) {
    $service = new DocumentService();
    $userId = Authentication::getUserId();
    return $response->withJson($service->getDocumentSummary($userId));
})->add($jwtMiddleware);

/**
 * @api {post} /documents/{document_id}/convert Convert document to PDF
 * @apiName ConvertToPdf
 * @apiGroup Documents
 */
$app->post("/documents/{document_id}/convert", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    $options = $request->getParsedBody();
    return $response->withJson($service->convertToPdf($documentId, $options));
})->add($jwtMiddleware);

/**
 * @api {post} /documents/{document_id}/render Render document
 * @apiName RenderDocument
 * @apiGroup Documents
 */
$app->post("/documents/{document_id}/render", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    $options = $request->getParsedBody();
    return $response->withJson($service->renderDocument($documentId, $options));
})->add($jwtMiddleware);

/**
 * @api {post} /documents/{document_id}/merge-attachments Merge attachments
 * @apiName MergeAttachments
 * @apiGroup Documents
 */
$app->post("/documents/{document_id}/merge-attachments", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    $options = $request->getParsedBody();
    return $response->withJson($service->mergeAttachments($documentId, $options));
})->add($jwtMiddleware);

/**
 * @api {get} /documents/{document_id}/activity Get document activity
 * @apiName GetDocumentActivity
 * @apiGroup Documents
 */
$app->get("/documents/{document_id}/activity", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    $params = $request->getQueryParams();
    return $response->withJson($service->getDocumentActivityHistory($documentId, $params));
})->add($jwtMiddleware);

/**
 * @api {post} /documents/{document_id}/activity Add document activity
 * @apiName AddDocumentActivity
 * @apiGroup Documents
 */
$app->post("/documents/{document_id}/activity", function ($request, $response, $args) {
    $service = new DocumentService();
    $documentId = isset($args['document_id']) ? (int)$args['document_id'] : 0;
    $activityData = $request->getParsedBody();
    return $response->withJson($service->addDocumentActivity($documentId, $activityData));
})->add($jwtMiddleware);
