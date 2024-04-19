<?php

namespace Aigletter\Framework\Interfaces;

interface RouterInterface
{
    public function route(string $uri): callable;
}