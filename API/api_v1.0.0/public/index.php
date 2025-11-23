<?php
//Silence is golden

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Request;
use App\Core\Router;
use App\Controller\UserController;

// 1. Cargamos el contenedor ya configurado
$container = require __DIR__ . '/../config/bootstrap.php';

// 2. Inicializamos Router y Request
$router = new Router();
$request = new Request();

// 3. Enrutamiento
if($request->getPath() === 'api/register' && $request->getMethod() === 'POST') {

  // Le pedimos al contenedor el controlador listo para usar
  // El contenedor creará PDO -> Repo -> Service -> Controller automáticamente
  $controller = $container->get(UserController::class);
  $controller->register($request);

} else {

  echo json_encode(['Error'=> 'Ruta no encontrada', 404])

}

?>