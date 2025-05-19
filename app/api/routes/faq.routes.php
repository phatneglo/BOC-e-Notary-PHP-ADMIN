<?php
// app/api/routes/faq.routes.php
namespace PHPMaker2024\eNotary;

/**
 * @api {get} /faqs/categories Get FAQ categories
 * @apiName GetFaqCategories
 * @apiGroup FAQ
 * @apiSuccess {Boolean} success Operation status
 * @apiSuccess {Array} data List of FAQ categories
 */
$app->get("/faqs/categories", function ($request, $response, $args) {
    $service = new FaqService();
    $result = $service->getCategories();
    return $response->withJson($result);
});

/**
 * @api {get} /faqs/categories/:categoryId Get FAQs by category
 * @apiName GetFaqsByCategory
 * @apiGroup FAQ
 * @apiParam {Number} categoryId Category ID
 * @apiSuccess {Boolean} success Operation status
 * @apiSuccess {Array} data List of FAQs for the category
 */
$app->get("/faqs/categories/{categoryId}", function ($request, $response, $args) {
    $service = new FaqService();
    $categoryId = $args['categoryId'];
    $result = $service->getFaqsByCategory($categoryId);
    return $response->withJson($result);
});

/**
 * @api {get} /faqs/search Search FAQs
 * @apiName SearchFaqs
 * @apiGroup FAQ
 * @apiParam {String} query Search query (in query string)
 * @apiSuccess {Boolean} success Operation status
 * @apiSuccess {Array} data List of FAQs matching the search query
 */
$app->get("/faqs/search", function ($request, $response, $args) {
    $service = new FaqService();
    $params = $request->getQueryParams();
    $query = isset($params['query']) ? $params['query'] : '';
    $result = $service->searchFaqs($query);
    return $response->withJson($result);
});
