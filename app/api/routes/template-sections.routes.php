<?php
// app/api/routes/template-sections.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /templates-sections/{template_id}/sections Get template sections
 * @apiName GetTemplateSections
 * @apiGroup TemplateSections
 */
$app->get("/templates-sections/{template_id}/sections", function ($request, $response, $args) {
    $service = new TemplateSectionService();
    $templateId = isset($args['template_id']) ? (int)$args['template_id'] : 0;
    return $response->withJson($service->getTemplateSections($templateId));
})->add($jwtMiddleware);

/**
 * @api {post} /templates-sections/{template_id}/sections Add template section
 * @apiName AddTemplateSection
 * @apiGroup TemplateSections
 */
$app->post("/templates-sections/{template_id}/sections", function ($request, $response, $args) {
    $service = new TemplateSectionService();
    $templateId = isset($args['template_id']) ? (int)$args['template_id'] : 0;
    $sectionData = $request->getParsedBody();
    return $response->withJson($service->addTemplateSection($templateId, $sectionData));
})->add($jwtMiddleware);

/**
 * @api {put} /templates-sections/sections/{section_id} Update template section
 * @apiName UpdateTemplateSection
 * @apiGroup TemplateSections
 */
$app->put("/templates-sections/sections/{section_id}", function ($request, $response, $args) {
    $service = new TemplateSectionService();
    $sectionId = isset($args['section_id']) ? (int)$args['section_id'] : 0;
    $sectionData = $request->getParsedBody();
    return $response->withJson($service->updateTemplateSection($sectionId, $sectionData));
})->add($jwtMiddleware);

/**
 * @api {delete} /templates-sections/sections/{section_id} Delete template section
 * @apiName DeleteTemplateSection
 * @apiGroup TemplateSections
 */
$app->delete("/templates-sections/sections/{section_id}", function ($request, $response, $args) {
    $service = new TemplateSectionService();
    $sectionId = isset($args['section_id']) ? (int)$args['section_id'] : 0;
    return $response->withJson($service->deleteTemplateSection($sectionId));
})->add($jwtMiddleware);

/**
 * @api {put} /templates-sections/{template_id}/sections/reorder Reorder template sections
 * @apiName ReorderTemplateSections
 * @apiGroup TemplateSections
 */
$app->put("/templates-sections/{template_id}/sections/reorder", function ($request, $response, $args) {
    $service = new TemplateSectionService();
    $templateId = isset($args['template_id']) ? (int)$args['template_id'] : 0;
    $sectionOrder = $request->getParsedBody()['section_order'] ?? [];
    return $response->withJson($service->reorderTemplateSections($templateId, $sectionOrder));
})->add($jwtMiddleware);
