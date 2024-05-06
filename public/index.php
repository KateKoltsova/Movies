<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../autoloader.php';

$config = require_once __DIR__ . '/../config/main.php';

$app = Framework\Application::getApp($config);

try {
    $app->run();
} catch (Exception $e) {
    $_SESSION['response']['error'] = "Error: " . $e->getMessage();
    header('Location: /');
    exit();
}
