<?php

use App\Core\Container;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Controller\UserController;
use App\Repository\ReportRepository;
use App\Service\ReportService;
use App\Controller\ReportController;

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

//REPORT WHIRING
// 1. La base de datos
$container->bind(ReportRepository::class, function($c){
    return new ReportRepository($c->get(PDO::class));
});

$container->bind(ReportService::class, function($c){
    return new ReportService($c->get(ReportRepository::class));
});

$container->bind(ReportController::class, function($c){
    return new ReportController($c->get(ReportService::class));
});

// EXAMPLE MODULE WIRING (Nuevo Módulo de Ejemplo)
$container->bind(App\Repository\ExampleRepository::class, function($c){
    return new App\Repository\ExampleRepository($c->get(PDO::class));
});

$container->bind(App\Service\ExampleService::class, function($c){
    return new App\Service\ExampleService($c->get(App\Repository\ExampleRepository::class));
});

$container->bind(App\Controller\ExampleController::class, function($c){
    return new App\Controller\ExampleController($c->get(App\Service\ExampleService::class));
});


return $container;
?>
