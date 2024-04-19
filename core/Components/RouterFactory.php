<?php

namespace Aigletter\Framework\Components;

use Exception;

class RouterFactory extends ComponentFactoryAbstract
{
    protected function createConcreteComponent()
    {
//        return new Router();
        $router = new Router();

        if (empty($this->arguments['routes'])) {
            throw new Exception('Choose routes directory!');
        } else {
            require_once $this->arguments['routes'];
        }

        return $router;
    }
}