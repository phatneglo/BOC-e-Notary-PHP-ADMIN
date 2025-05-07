<?php
// app/api/routes/service.routes.php
namespace PHPMaker2024\eNotary;

/**
 * API Service Routes
 */
$app->get("/Service", function ($request, $response, $args) {
        $apiService = new Service\ApiService();
        $result = $apiService->generateOAI();
        return $response->write($result);
    }); // ->add($jwtMiddleware)
