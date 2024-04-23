<?php

function dd($var)
{
    var_dump($var);
    die();
}

ini_set('display_errors', '1');

require_once __DIR__ . '/../autoloader.php';

$config = require_once __DIR__ . '/../config/main.php';

$app = Framework\Application::getApp($config);

try {
    $app->run();
} catch (Exception $exception) {
    echo "<h1>" . $exception->getCode() . " " . $exception->getMessage() . "</h1>";

}
