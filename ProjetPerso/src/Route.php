<?php

namespace App;

class Route {

    public $pattern;
    public $target;
    public $params;
    public $name;

    public function __construct($definition, $pattern, $target, $params, $name = null)
    {
        $this->definition = $definition;
        $this->pattern = $pattern;
        $this->target = $target;
        $this->params = $params;
        $this->name = $name;
    }
}