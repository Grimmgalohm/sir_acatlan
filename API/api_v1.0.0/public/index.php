<?php
// Desactivar warnings visuales en producción, pero útil verlos ahora
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;

// 1. Cargar Contenedor
$container = require __DIR__ . '/../config/bootstrap.php';

// 2. Inicializar Request y Router
$router = new Router($container);
$request = new Request();


$router->get('/api/status', function($req) {
    return Response::json(['status'=>'API Online', 'time' => time()]);
});

// 3. 
try {
    $response = $router->resolve($request);
} catch (\Exception $e) {
    Response::json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
}

?>
