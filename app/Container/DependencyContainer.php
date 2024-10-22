<?php

namespace App\Container;

class DependencyContainer
{
    private $services = [];

    public function register($name, $callback)
    {
        $this->services[$name] = $callback;
    }

    public function resolve($name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name]();
        }
        throw new \Exception("Service not found: $name");
    }
}
