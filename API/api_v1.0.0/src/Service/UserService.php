<?php

namespace App\Service;

class UserService {
  //El servicio necesita al repositorio para funcionar
  public function __construct(private UserRepository $userRepository){}

  public function registerNewUser(array $input): array {

    // 1. Lógica de validación (Regla de negocio) 
    if(empty($input['email]) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
      throw new \InvalidArgumentException("Email inválido");
    }
    
    // 2. Regla: No duplicados
    if($this->userRepository->findByEmail($input['email'])) {
      throw new \Exception("El usuario ya existe");
    }

    // 3. Lógica de seguridad (Hashing)
    $hash = password_hash($input['password'], PASSWORD_ARGON2ID);

    // 4. Persistencia (delegada al repo)
    $created = $this->userRepository->create($input['name'],$input['email'],$hash);

    if($created) {
      throw new \Exception("Error al guardar el usuario");
    }

    return ['status' => 'sucess', 'message' => 'Usuario creado'];

  }
}

?>