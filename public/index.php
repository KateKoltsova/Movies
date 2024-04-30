<?php

function dd($var)
{
    var_dump($var);
    die();
}

function jsonResponse($data, $statusCode = 200)
{
    header('Content-Type: application/json');
    http_response_code($statusCode);
    print_r(json_encode($data));
//    return (json_encode($data));
//    exit;
}


ini_set('display_errors', '1');

require_once __DIR__ . '/../autoloader.php';

$config = require_once __DIR__ . '/../config/main.php';

$app = Framework\Application::getApp($config);

try {
    $app->run();
} catch (Exception $e) {
    return jsonResponse([
        'success' => false,
        'message' => $e->getMessage()
    ], $e->getCode());
}
