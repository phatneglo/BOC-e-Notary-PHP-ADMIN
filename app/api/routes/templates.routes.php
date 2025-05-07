<?php
// app/api/routes/templates.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /templates/categories Get template categories
 * @apiName GetTemplateCategories
 * @apiGroup Templates
 */
$app->get("/templates/categories", function ($request, $response, $args) {
    $service = new TemplateService();
    return $response->withJson($service->getCategories());
})->add($jwtMiddleware);

/**
 * @api {get} /templates List templates
 * @apiName ListTemplates
 * @apiGroup Templates
 */
$app->get("/templates", function ($request, $response, $args) {
    $service = new TemplateService();
    $params = $request->getQueryParams();
    return $response->withJson($service->listTemplates($params));
})->add($jwtMiddleware);

/**
 * @api {get} /templates/{template_id} Get template details
 * @apiName GetTemplateDetails
 * @apiGroup Templates
 */
$app->get("/templates/{template_id}", function ($request, $response, $args) {
    $service = new TemplateService();
    $templateId = isset($args['template_id']) ? (int)$args['template_id'] : 0;
    return $response->withJson($service->getTemplateDetails($templateId));
})->add($jwtMiddleware);

/**
 * @api {get} /user/templates Get user templates
 * @apiName GetUserTemplates
 * @apiGroup Templates
 */
$app->get("/user/templates", function ($request, $response, $args) {
    $service = new TemplateService();
    $userId = $request->getAttribute('user_id');

    $params = $request->getQueryParams();
    return $response->withJson($service->getUserTemplates($userId, $params));
})->add($jwtMiddleware);

/**
 * @api {post} /user/templates Save user template
 * @apiName SaveUserTemplate
 * @apiGroup Templates
 */
$app->post("/user/templates", function ($request, $response, $args) {
    $service = new TemplateService();
    $userId = $request->getAttribute('user_id');

    $templateData = $request->getParsedBody();
    return $response->withJson($service->saveUserTemplate($userId, $templateData));
})->add($jwtMiddleware);

/**
 * @api {post} /user/templates/upload Upload custom template
 * @apiName UploadCustomTemplate
 * @apiGroup Templates
 */
$app->post("/user/templates/upload", function ($request, $response, $args) {
    $service = new TemplateService();
    $userId = $request->getAttribute('user_id');

    $templateData = array_merge(
        $request->getParsedBody() ?? [],
        $request->getUploadedFiles() ?? []
    );
    return $response->withJson($service->uploadCustomTemplate($userId, $templateData));
})->add($jwtMiddleware);

/**
 * @api {get} /user/templates/{user_template_id} Get user template details
 * @apiName GetUserTemplateDetails
 * @apiGroup Templates
 */
$app->get("/user/templates/{user_template_id}", function ($request, $response, $args) {
    $service = new TemplateService();
    $userTemplateId = isset($args['user_template_id']) ? (int)$args['user_template_id'] : 0;
    return $response->withJson($service->getUserTemplateDetails($userTemplateId));
})->add($jwtMiddleware);

/**
 * @api {delete} /user/templates/{user_template_id} Delete user template
 * @apiName DeleteUserTemplate
 * @apiGroup Templates
 */
$app->delete("/user/templates/{user_template_id}", function ($request, $response, $args) {
    $service = new TemplateService();
    $userTemplateId = isset($args['user_template_id']) ? (int)$args['user_template_id'] : 0;
    return $response->withJson($service->deleteUserTemplate($userTemplateId));
})->add($jwtMiddleware);

/**
 * @api {post} /templates/{template_id}/generate-pdf Generate template PDF
 * @apiName GenerateTemplatePdf
 * @apiGroup Templates
 */
$app->post("/templates/{template_id}/generate-pdf", function ($request, $response, $args) {
    $service = new TemplateService();
    $templateId = isset($args['template_id']) ? (int)$args['template_id'] : 0;
    $options = $request->getParsedBody();
    return $response->withJson($service->convertTemplateToPdf($templateId, $options));
})->add($jwtMiddleware);
