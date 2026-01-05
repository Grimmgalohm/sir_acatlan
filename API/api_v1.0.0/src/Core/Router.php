<?php
// Core/Router.php

namespace App\Core;

class Router {

    private array $routes = [];
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    // Registra una ruta GET
    public function get(string $path, callable|array $callback): void {
        $this->routes['GET'][$path] = $callback;
    }

    // Registra una ruta POST
    public function post(string $path, callable|array $callback): void {
        
    }
}

?>
