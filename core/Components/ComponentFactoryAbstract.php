<?php

namespace Aigletter\Framework\Components;

abstract class ComponentFactoryAbstract
{
    protected $arguments;

    public function __construct($arguments = [])
    {
        $this->arguments = $arguments;
    }

    public function createComponent()
    {
        return $this->createConcreteComponent();
    }

    abstract protected function createConcreteComponent();
}