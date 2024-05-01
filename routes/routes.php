<?php

/** @var \Framework\Components\Router $router */

$router->get('/movies', [\App\Controllers\MovieController::class, 'index']);
$router->post('/movies', [\App\Controllers\MovieController::class, 'store']);
$router->get('/movies/{id}', [\App\Controllers\MovieController::class, 'show']);
$router->patch('/movies/{id}', [\App\Controllers\MovieController::class, 'update']);
$router->delete('/movies/{id}', [\App\Controllers\MovieController::class, 'destroy']);
