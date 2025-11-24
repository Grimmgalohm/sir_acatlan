<?php
// Desactivar warnings visuales en producción, pero útil verlos ahora
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Request;
use App\Core\Response;
use App\Controller\UserController;

// 1. Cargar Contenedor
$container = require __DIR__ . '/../config/bootstrap.php';

// 2. Inicializar Request
$request = new Request();
$path = $request->getPath();
$method = $request->getMethod();

// 3. Router Simple (Switch)
try {
    switch ($path) {
        
        case '/api/register':
            if ($method === 'POST') {
                /** @var UserController $controller */
                $controller = $container->get(UserController::class);
                $controller->register($request);
            } else {
                Response::json(['error' => 'Método no permitido'], 405);
            }
            break;

        case '/api/status':
            Response::json(['status' => 'API Online', 'time' => time()]);
            break;

        default:
            Response::json(['error' => 'Ruta no encontrada'], 404);
            break;
    }

} catch (\Exception $e) {
    Response::json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
}

?>
