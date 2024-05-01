<?php

$configNamespaces = require_once __DIR__ . '/config/autoload.php';

function autoload($classFullName, $configNamespaces)
{
    if (isset($configNamespaces['autoload']) && is_array($configNamespaces['autoload'])) {
        foreach ($configNamespaces['autoload'] as $namespace => $path) {
            if (strpos($classFullName, $namespace) === 0) {
                $relativeClass = substr($classFullName, strlen($namespace));
                $filePath = $path . str_replace('\\', '/', $relativeClass);
            }
        }
    } else {
        $filePath = str_replace('\\', '/', $classFullName);
    }

    $filename = __DIR__ . '/' . $filePath . '.php';
    if (file_exists($filename)) {
        require_once $filename;
    }
}

//spl_autoload_register('autoload');
spl_autoload_register(function ($classFullName) use ($configNamespaces) {
    autoload($classFullName, $configNamespaces);
});
