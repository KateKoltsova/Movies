<?php

namespace Aigletter\Framework\Components;

use Aigletter\Framework\Exceptions\MethodNotAllowedException;
use Aigletter\Framework\Exceptions\NotFoundException;
use Aigletter\Framework\Exceptions\NotImplementedException;
use Aigletter\Framework\Exceptions\RequestException;
use Aigletter\Framework\Interfaces\RouterInterface;
use ReflectionMethod;

class Router implements RouterInterface
{
    public array $router = [];

    public array $args = [];

    public function __construct()
    {
    }

    public function route(string $uri): callable
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        $usableAction = array_filter($this->router,
            function ($path) use ($uri) {
                return $path === $uri;
            },
            ARRAY_FILTER_USE_KEY);

        if (empty($usableAction)) {
            throw new NotFoundException();
        }

        if (is_array($usableAction[$uri])) {

            [$className, $methodName] = $usableAction[$uri];

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
            return $usableAction[$uri];
        }
    }

    public function getMethodParameters($className, $methodName)
    {
        $reflection = new ReflectionMethod($className, $methodName);
        $parameters = $reflection->getParameters();

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $getType = $parameter->getType();

            if ($getType && !$getType->isBuiltin()) {
                $className = $getType->getName();
                $arg = new $className();
            } else {

                if (empty($_GET[$name])) {

                    if ($parameter->isOptional()) {
                        $arg = $parameter->getDefaultValue();
                    } else {
                        throw new RequestException();
                    }
                } else {
                    $arg = $_GET[$name];
                }

                if ($getType && $getType->getName() == 'array') {

                    if (!is_array($arg)) {
                        $arg = explode(",", $arg);
                    }

                } else {

                    if ($getType) {
                        settype($arg, $getType->getName());
                    }
                }
            }

            $this->args[] = $arg;
        }
    }
}