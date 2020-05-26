<?php

namespace App;

use AltoRouter;
use App\Security\ForbiddenException;

class Router {

    /**
     * @var string
     */
    private $viewPath;

    /**
     * @var AltoRouter
     */
    private $router;

    // public $layout = 'layouts/default';
    
    public function __construct(string $viewPath) {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null): self {
        $this->router->map('GET', $url, $view, $name);

        return $this;
    }
    public function post(string $url, string $view, ?string $name = null): self {
        $this->router->map('POST', $url, $view, $name);

        return $this;
    }   
    
    public function match(string $url, string $view, ?string $name = null): self {
        $this->router->map('POST|GET', $url, $view, $name);

        return $this;
    }

    public function run(): self{
        
        $match = $this->router->match();
        if(!$match) {
            $view = 'e404';
        } else {
            $view = $match['target'];
            $params = $match['params'];
        }
        $router = $this;
        // strpos renvoit le premier caractère de la chaine trouvée ou false
        $isAdmin = strpos($view,'admin/') !== false;
        $layout = $isAdmin ? 'admin/layouts/default' : 'layouts/default';

        try {
            ob_start();
            require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
            $content = ob_get_clean();
            require $this->viewPath . DIRECTORY_SEPARATOR . $layout . '.php';
        } catch (ForbiddenException $e) {
            Header('Location: ' . $router->url('login') . '?forbidden=1&uri=' . $e->getUri());
            exit();
        }

        return $this;
    }

    public function url(string $route, array $params = []) {
        return $this->router->generate($route, $params);
    }
}