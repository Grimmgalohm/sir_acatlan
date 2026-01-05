# Propuesta de Arquitectura de Ruteo Profesional

Para elevar tu arquitectura actual (MVC plano con Inyección de Dependencias manual y sin framework) al nivel profesional, el paso lógico es **desacoplar el enrutamiento del `index.php`**.

Actualmente usas un `switch` que mezcla la *definición* de la ruta con la *lógica de instanciación*. Lo profesional es usar un patrón de **Router/Dispatcher**.

> [!IMPORTANT]
> **Requisito Previo:** Asegúrate de que tu archivo `config/bootstrap.php` devuelva la instancia del contenedor al final (`return $container;`). Sin esto, el `index.php` no podrá recibir el contenedor.

## Análisis de Migración: public/index.php

### Lo que SE MANTIENE 🟢
- **Configuración de errores:** `error_reporting`, `ini_set`.
- **Carga de librerías:** `require vendor/autoload.php`.
- **Variables de entorno:** `Dotenv`.
- **Inicialización del Core:** `$container = require ...` y `$request = new Request()`.
- **Manejo global de errores:** El bloque `try-catch` que envuelve la ejecución principal.

### Lo que SE ELIMINA 🔴
- **Imports de Controladores:** `use App\Controller\UserController;` (ya no se necesitan aquí).
- **Extracción manual de path/method:** `$path = ...`, `$method = ...` (el Router lo hará internamente).
- **El bloque `switch` gigante:** Toda la lógica condicional de rutas desaparece de este archivo.

---

## 1. El Nuevo `public/index.php` (Limpio y escalable)

Este archivo pasa de ser un "controlador de tráfico" a un simple "punto de entrada".

```php
<?php
// 1. Configuración básica (SE QUEDA)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;

// 2. Cargar Contenedor (IMPORTANTE: bootstrap.php debe hacer 'return $container;')
$container = require __DIR__ . '/../config/bootstrap.php';

// 3. Inicializar Router y Request
$router = new Router($container);
$request = new Request();

// ---------------------------------------------------------
// 4. DEFINICIÓN DE RUTAS (El switch se reemplaza por esto)
// ---------------------------------------------------------

// Ejemplo A: Ruta GET simple con cierre (Closure)
$router->get('/api/status', function($req) {
    // Útil para health checks o pruebas rápidas sin controlador
    return Response::json(['status' => 'API Online', 'time' => time()]);
});

// Ejemplo B: Ruta POST a un Controlador (Lazy Loading)
// No instanciamos UserController aquí. Pasamos la CLASE y el MÉTODO.
$router->post('/api/register', [App\Controller\UserController::class, 'register']);

// Ejemplo C: Ruta GET a reportes
$router->get('/api/reports', [App\Controller\ReportController::class, 'get']);


// 5. DESPACHO (El Router toma el control)
try {
    // El router busca la ruta que coincida con el Request y la ejecuta
    $response = $router->resolve($request); 
} catch (\Exception $e) {
    Response::json(['error' => 'Error interno: ' . $e->getMessage()], 500);
}
```

---

## 2. La clase `src/Core/Router.php` (El motor)

Este es el nuevo archivo que necesitas crear.

```php
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
        $this->routes['POST'][$path] = $callback;
    }

    // Resuelve la petición actual
    public function resolve(Request $request) {
        $path = $request->getPath();
        $method = $request->getMethod();
        
        // Busca si existe una ruta para este método y path
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            // Puedes lanzar excepción o retornar un 404 directamente
             Response::json(['error' => 'Ruta no encontrada'], 404);
             return;
        }

        // CASO 1: Callback es un array [Clase, Método] (Controlador)
        if (is_array($callback)) {
            $controllerClass = $callback[0];
            $controllerMethod = $callback[1];
            
            // Magia del Container: Crea el controlador e inyecta dependencias
            $controller = $this->container->get($controllerClass);
            
            // Ejecuta el método del controlador pasando el Request
            return call_user_func([$controller, $controllerMethod], $request);
        }

        // CASO 2: Callback es una función (Closure)
        if (is_callable($callback)){
             return call_user_func($callback, $request);
        }
    }
}
```

### Resumen de Cambios en Estructura
- **[MODIFICADO]** `public/index.php`: Se eliminó el `switch`.
- **[MODIFICADO]** `config/bootstrap.php`: Debe añadir `return $container;` al final.
- **[NUEVO]** `src/Core/Router.php`: Nueva clase para manejar rutas.
