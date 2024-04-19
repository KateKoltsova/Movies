<?php

namespace Aigletter\Framework;

use Exception;
use ReflectionClass;

abstract class Container
{
    protected $bindings;

    protected function __construct(array $bindings)
    {
        $this->bindings = $bindings;
    }

    public function __get(string $name)
    {
        return $this->getComponent($name);
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement __call() method.
    }

    public function getComponent($key)
    {
        $class = $this->bindings;

        if (isset($class[$key])) {
            if (isset($class[$key]['factory'])
                && class_exists($class[$key]['factory'])) {
                return $this->makeByFactory($key);
            }

            if (isset($class[$key]['class'])
                && class_exists($class[$key]['class'])) {
                return $this->makeObject($class[$key]['class'], $class[$key]['arguments'] ?? []);
            }

            throw new Exception($class[$key]['factory'] ?? $class[$key]['class'] . ' not found');

        }

        throw new Exception('Component not found');
    }

    protected function makeByFactory($key)
    {
        $factoryClass = $this->bindings[$key]['factory'];
        $arguments = $this->bindings[$key]['arguments'] ?? [];

        if (!class_exists($factoryClass)) {
            throw new Exception('Factory not found');
        }

        $factory = new $factoryClass($arguments);

        $instance = $factory->createComponent();

        return $instance;
    }

    public function makeObject(string $class, $arguments = []): object
    {
        $reflectionClass = new ReflectionClass($class);

        $dependencies = $this->resolveDependencies($class, '__construct', $arguments);

        $instance = $reflectionClass->newInstanceArgs($dependencies);

        return $instance;
    }

    public function resolveDependencies($class, $method, $arguments = [])
    {
        $reflectionClass = new ReflectionClass($class);
        $dependencies = [];

        if ($reflectionClass->hasMethod($method)) {
            $method = $reflectionClass->getMethod($method);

            foreach ($method->getParameters() as $parameter) {
                $name = $parameter->getName();
                $type = $parameter->getType();

                if ($type && !$type->isBuiltin()) {
                    $dependencies[$name] = $this->getComponent($type->getName());
                } else {
                    $dependencies[$name] = $arguments[$name] ?? null;
                }
            }
        }

        return $dependencies;
    }
}