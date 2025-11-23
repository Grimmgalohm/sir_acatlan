<?php

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Service\UserService;

class UserController {

  public function __construct(private UserService $userService) {}

  public function register(Request $request): void {

    try {

      // Obtiene datos  del body (JSON)
      $data = $request->getBody();

      //Llama al servicio
      $result = $this->userService->registerNewUser($data);

      //Envía respuesta exitosa (201 Created)
      Response::json(result, 201);

    } catch (\InvalidArgumentException $e) {

      //Error de validación (400 Bad Request)
      Response::json(['error'=> $e->getMessage()], 400);

    } catch (\Exception $e) {

      Response::json(['error'=>$e->getMessage()], 500);

    }

  }

}

?>