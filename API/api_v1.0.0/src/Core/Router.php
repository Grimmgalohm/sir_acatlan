<?php
// Core/Router.php
namespace App\Core;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

class Router {

    private array $routes = [];
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function get(string $path, callable | array $callback): void {
        $this->addRoute('GET', $path, $callback);
    }

    public function post(string $path, callable | array $callback): void {
        $this->addRoute('POST', $path, $callback);
    }

    public function put(string $path, callable | array $callback): void {
        $this->addRoute('PUT', $path, $callback);
    }

    public function delete(string $path, callable | array $callback): void {
        $this->addRoute('DELETE', $path, $callback);
    }

    public function patch(string $path, callable | array $callback): void {
        $this->addRoute('PATCH', $path, $callback);
    }

    private function addRoute(string $method, string $path, callable | array $callback): void {
        // Convertimos la ruta /api/users/{id} a una regex /api/users/([^/]+)
        // Escapamos las barras, y reemplazamos los parámetros dinámicos {algo}
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $path);
        // Agregamos delimitadores y flag de case-insensitive si quisieras
        $pattern = "#^" . $pattern . "$#";
        
        $this->routes[$method][] = [
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    public function resolve(Request $request) {
        $path = $request->getPath();
        $method = $request->getMethod();
        
        // Obtenemos las rutas registradas para este método
        $methodRoutes = $this->routes[$method] ?? [];

        foreach ($methodRoutes as $route) {
            
            // Verificamos si la ruta actual hace match con algún patrón registrado
            if (preg_match($route['pattern'], $path, $matches)) {
                
                // Eliminamos la coincidencia global (el primer elemento)
                array_shift($matches);
                // $matches ahora contiene solo los valores capturados (params)
                
                $callback = $route['callback'];

                // CASO 1: Callback es un array [Clase, Método]
                if (is_array($callback)) {
                    $controllerClass = $callback[0];
                    $controllerMethod = $callback[1];

                    // Obtenemos el controlador desde el Contenedor
                    $controller = $this->container->get($controllerClass);

                    // Llamamos al método pasando Request como 1er arg, y luego los params de la URL
                    return call_user_func_array([$controller, $controllerMethod], array_merge([$request], $matches));
                }

                // CASO 2: Callback es una función suelta (closure)
                if (is_callable($callback)) {
                    return call_user_func_array($callback, array_merge([$request], $matches));
                }
            }
        }

        // Si salimos del loop sin retornar, no hubo coincidencia
        Response::json(['error' => 'Ruta no encontrada'], 404);
    }
}

?>
