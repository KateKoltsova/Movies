<?php

namespace Framework\Components;

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

    public function route(string $uri, string $requestMethod): callable
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        if (!isset($this->router[$uri][$requestMethod])) {
            throw new NotFoundException("Route not found for URI $uri and method $requestMethod");
        }

//        $usableAction = array_filter($this->router,
//            function ($path) use ($uri) {
//                return $path === $uri;
//            },
//            ARRAY_FILTER_USE_KEY);
//
//        if (empty($usableAction)) {
//            throw new NotFoundException();
//        }
        $method = $this->router[$uri][$requestMethod];

        if (is_array($method)) {

            [$className, $methodName] = $method;

            if (!class_exists($className)) {
                throw new NotImplementedException();
            }

            if (!method_exists($className, $methodName)) {
                throw new MethodNotAllowedException();
            }

            $this->getMethodParameters($className, $methodName, $requestMethod);

            return function () use ($className, $methodName) {
                $class = new $className;
                return call_user_func_array([$class, $methodName], $this->args);
            };

        } else {
            return $method;
        }
    }

    public function getMethodParameters($className, $methodName, $requestMethod)
    {
        switch ($requestMethod) {
            case 'GET':
                $requestMethodParams = $_GET;
                break;
            case 'POST':
                $requestMethodParams = $_POST;
                break;
            default:
                $requestMethodParams = null;
        }

        $reflection = new ReflectionMethod($className, $methodName);
        $parameters = $reflection->getParameters();

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $getType = $parameter->getType();

            if ($name == 'request') {
                $this->args[] = $requestMethodParams;
                continue;
            }

            if ($getType && !$getType->isBuiltin()) {
                $className = $getType->getName();
                $arg = new $className();
            } else {
                $requestParam = $requestMethodParams[$name] ?? null;

                if (empty($requestParam) && !$parameter->isOptional()) {
                    throw new RequestException("Missing required parameter: $name");
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

    public function addRoute(string $uri, $method, string $requestMethod): string
    {
        if (empty($uri) || empty($method)) {
            throw new \Exception("Routing parameters can't be empty!" . '</br>');
        } else {
            $this->router[$uri][$requestMethod] = $method;
            return "Adding action for uri $uri successful!" . '</br>';
        }
    }
}