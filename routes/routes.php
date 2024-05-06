<?php

/** @var \Framework\Components\Router $router */

use Framework\Application;

$router->get('/favicon.ico', function () {
    $viewPath = __DIR__ . 'favicon.ico';

    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    echo $content;
});

$router->get('/', function () {
    $viewPath = Application::getApp()->resources['views'] . 'start.php';

    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    echo $content;
});

$router->get('/movies', [\App\Controllers\MovieController::class, 'index']);
$router->get('/movies/create', [\App\Controllers\MovieController::class, 'create']);
$router->post('/movies', [\App\Controllers\MovieController::class, 'store']);
$router->get('/movies/{id}', [\App\Controllers\MovieController::class, 'show']);
$router->get('/movies/{id}/edit', [\App\Controllers\MovieController::class, 'edit']);
$router->patch('/movies/{id}', [\App\Controllers\MovieController::class, 'update']);
$router->delete('/movies/{id}', [\App\Controllers\MovieController::class, 'destroy']);
