<?php

namespace App;

use AltoRouter;

class Router {

    /**
     * @var string
     */
    private $viewPath;

    /**
     * @var AltoRouter
     */
    private $router;
    
    public function __construct(string $viewPath) {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null): self {
        $this->router->map('GET', $url, $view, $name);

        return $this;
    }

    public function run(): self{
        
        $match = $this->router->match();
        $view = $match['target'];
        $params = $match['params'];
        $router = $this;
        ob_start();
        require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
        $content = ob_get_clean();

        require $this->viewPath . DIRECTORY_SEPARATOR . 'layouts/default.php';

        return $this;
    }

    public function url(string $route, array $params = []) {
        return $this->router->generate($route, $params);
    }
}