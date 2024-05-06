<?php

namespace Framework;

use Exception;
use Framework\Interfaces\RunnableInterface;
use ReflectionClass;

class Application implements RunnableInterface
{
    protected $config;

    protected $bindings;

    protected static $instance;

    public array $resources = [];


    public static function getApp(array $config = [])
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    private function __construct(array $config)
    {
        $this->config = $config;
        $bindings = $config['components'] ?? [];
        $this->bindings = $bindings;
        $this->resources = $this->config['resources'] ?? [];
    }

    public function __get(string $name)
    {
        return $this->getComponent($name);
    }

    public function run()
    {
        $router = $this->getComponent('router');
        $action = $router->route($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

        return $action();
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
