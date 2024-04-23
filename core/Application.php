<?php

namespace Framework;

use Framework\Interfaces\RunnableInterface;

class Application extends Container implements RunnableInterface
{
    protected $config;

    protected static $instance;

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
        parent::__construct($bindings);
    }

    public function run()
    {
        $router = $this->getComponent('router');
        $action = $router->route($_SERVER['REQUEST_URI']);

        return $action();
    }

//    public function getComponent($key)
//    {
//        if (isset($this->config['components'][$key]['factory'])) {
//
//            $factoryClass = $this->config['components'][$key]['factory'];
//
//            $arguments = $this->config['components'][$key]['arguments'] ?? [];
//
//            if (!class_exists($factoryClass)) {
//                $factory = new $factoryClass($arguments);
//
//                $instance = $factory->createComponent();
//
//                return $instance;
//            }
//
////            throw new GetComponentException('Factory class not found');
//            throw new Exception('Factory class not found');
//        }
//
////        throw new GetComponentException('Component not found');
//        throw new Exception('Component not found');
//    }
}
