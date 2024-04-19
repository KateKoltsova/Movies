<?php

function autoload($classFullName)
{
    $className = str_replace('\\', '/', $classFullName);
    $filename = __DIR__ . '/' . $className . '.php';
    if (file_exists($filename)) {
        require_once $filename;
    }
}

spl_autoload_register('autoload');