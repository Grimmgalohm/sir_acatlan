<?php

use App\Core\Container;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Controller\UserController;

$container = new Container();


// 1. La base de datos
$container->bind(PDO::class, function(){
  return require __DIR__ . '/database.php'; //Mi archivo config PDO
});

// 2. El repositorio (Necesita PDO)
$container->bind(UserRepository::class, function($c){
  //$c es el contenedor. Le pedimos PDO
  return new UserRepository($c->get(PDO::class));
});

// 3. El servicio (Necesita el repo)
$container->bind(UserService::class, function($c){
  return new UserService($c->get(UserRepository::class));
});

// 4. El controlador (Necesita el servicio)
$container->bind(UserController::class, function($c){
  return new UserController($c->get(UserService::class));
});

return $container;

?>