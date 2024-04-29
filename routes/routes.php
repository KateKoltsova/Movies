<?php

/** @var \Framework\Components\Router $router */

//$router->addRoute('/', function () {
//    echo 'Callback is running<br>';
//});

$router->addRoute('/movies/add', [\App\Controllers\MovieController::class, 'store'], 'POST');
//$router->addRoute('/product/test', [\Aigletter\App\Controllers\ProductController::class, 'test']);
//$router->addRoute('/page', [\Aigletter\App\Controllers\PageController::class, 'infoClass']);
//$router->addRoute('/some', [\Aigletter\App\Controllers\SomeController::class, 'action']);
//$router->addRoute('/some/arr', [\Aigletter\App\Controllers\SomeController::class, 'arr']);
//$router->addRoute('/some/new', [\Aigletter\App\Controllers\SomeController::class, 'newObject']);
