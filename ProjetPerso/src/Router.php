<?php

namespace App;

use App\Route;

class Router {

    private $routes = [];

    private $attributes = [
        'str' => '([a-z]*)',
        'int' => '([0-9]*)',
        'strnum' => '([a-z0-9-]*)'
    ];

    public function map(string $route, string $target, string $name = null) : void
    {
        // dump("Initiale $route");
        $definition = $route;
        $params = [];
        if(preg_match_all('/\[([a-z]*):([a-z]*)\]/', $route, $matches)) {
            // Patterns trouvés
            foreach($matches[0] as $k => $pattern) {

                // Je construis ma nouvelle regex et je l'implemente dans la propriété de ma route
                $attribut = $matches[1][$k];
                $regex = $this->attributes[$attribut];
                                
                $params[] = $matches[2][$k];

                $route = str_replace($pattern,$regex,$route);
                // dump("Traitement $route");
            }
            
        } 
        $this->routes[] = new Route($definition, $route, $target, $params, $name);
    } 


    public function match(): ?array
    {
        $url = $_SERVER['PATH_INFO'] ?? "/";
    
        foreach($this->routes as $route) {
            // dump($route->pattern);
            if(preg_match("#^$route->pattern$#", $url, $matches)) {

                // dump($route);
                $results = ['target' => $route->target];
                
                // dump($matches);

                foreach($matches as $k => $match) {
                    if($k != 0) {
                        $key = $route->params[$k - 1];
                        $results['params'][$key] = $match;
                    }
                }
                return $results;
            }
        }   

        return null;
    }

    public function url(string $routeName, array $params = null) 
    {
        foreach($this->routes as $route) {

            if($route->name == $routeName) {

                if(preg_match_all('/\[([a-z]*):([a-z]*)\]/', $route->definition, $matches)) {
                    $url = $route->definition;
                    foreach($matches[0] as $k => $pattern) {
                        $keyToChange = $matches[2][$k];
                        $value = $params[$keyToChange];
                        $url = str_replace($pattern, $value, $url);
                        return $url;
                    }
                } else {
                    return $route->definition;
                }
                 

            }
        }
        return new \Exception('Le nom de cette route n\'existe pas');
    }

}

