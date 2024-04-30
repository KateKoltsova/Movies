<?php

namespace Framework\Components;

use Exception;
use Framework\Exceptions\MethodNotAllowedException;
use Framework\Exceptions\NotFoundException;
use Framework\Exceptions\NotImplementedException;
use Framework\Exceptions\RequestException;
use Framework\Interfaces\RouterInterface;
use ReflectionMethod;

class Router implements RouterInterface
{
    public array $router = [];

    public array $args = [];

    public function __construct()
    {
    }

    public function __call($method, $args)
    {
        $requestMethod = strtoupper($method);

        if (in_array($requestMethod, ['GET', 'POST', 'PATCH', 'DELETE'])) {
            $uri = $args[0];
            $action = $args[1];

            return $this->addRoute($uri, $action, $requestMethod);
        }

        throw new Exception("Method $method not supported", 500);
    }

    public function route(string $uri, string $requestMethod): callable
    {
        $requestMethod = $_REQUEST['_method'] ?? $requestMethod;
        unset($_REQUEST['_method']);

        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->router as $route => $methods) {
            if (preg_match($route, $uri, $matches) && isset($methods[$requestMethod])) {
                foreach ($matches as $key => $value) {
                    if (!is_numeric($key)) {
                        $_REQUEST[$key] = $value;
                    }
                }

                $method = $methods[$requestMethod];

                if (is_array($method)) {

                    [$className, $methodName] = $method;

                    if (!class_exists($className)) {
                        throw new NotImplementedException();
                    }

                    if (!method_exists($className, $methodName)) {
                        throw new MethodNotAllowedException();
                    }

                    $this->getMethodParameters($className, $methodName);

                    return function () use ($className, $methodName) {
                        $class = new $className;
                        return call_user_func_array([$class, $methodName], $this->args);
                    };

                } else {
                    return $method;
                }
            }
        }
        throw new NotFoundException("Route not found for URI $uri and method $requestMethod", 500);
    }

    public function getMethodParameters($className, $methodName)
    {

        $reflection = new ReflectionMethod($className, $methodName);
        $parameters = $reflection->getParameters();

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $getType = $parameter->getType();

            if ($name == 'request') {
                $this->args[] = $_REQUEST;
                continue;
            }

            if ($getType && !$getType->isBuiltin()) {
                $className = $getType->getName();
                $arg = new $className();
            } else {
                $requestParam = $_REQUEST[$name] ?? null;

                if (empty($requestParam) && !$parameter->isOptional()) {
                    throw new RequestException("Missing required parameter: $name", 500);
                }

                $arg = $requestParam ?? $parameter->getDefaultValue();

                if ($getType && $getType->getName() == 'array' && !is_array($arg)) {
                    $arg = explode(",", $arg);
                }

                if ($getType && $getType->getName() !== 'array') {
                    settype($arg, $getType->getName());
                }
            }
            $this->args[] = $arg;
        }
    }

    private function addRoute(string $uri, $method, string $requestMethod): void
    {
        if (empty($uri) || empty($method)) {
            throw new Exception("Routing parameters can't be empty!", 500);
        }

        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>\d+)', $uri);
        $regex = str_replace('/', '\/', $regex);
        $this->router['/^' . $regex . '$/'][$requestMethod] = $method;
    }
}