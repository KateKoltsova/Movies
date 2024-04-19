<?php

use Koltsova\Router\Exceptions\HttpException;

ini_set('display_errors', '1');

require_once __DIR__ . '/../autoloader.php';

$config = require_once __DIR__ . '/../config/main.php';

$app = Aigletter\Framework\Application::getApp($config);

try {
    $app->run();
} catch (HttpException $httpException) {
    echo $httpException->getMessage();
} catch (Exception $exception) {
    echo "<h1>".$exception->getCode()." ".$exception->getMessage()."</h1>";

}
