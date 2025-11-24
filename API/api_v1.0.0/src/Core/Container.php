<?php
namespace App\Core;

class Container {

  // Aquí se guardan las funciones para crear objetos
  private array $bindings = [];

  // Aquí se guardan los objetos que ya creamos
  private array $instances = [];


  /**
  * Registrar una función (bind)
  * @param string $key El nombre del servicio (ej: 'UserService')
  * @param callable $resolver La función que crea el objeto
  */
  public function bind(string $key, callable $resolver): void {
    $this->bindings[$key] = $resolver;
  }

  /**
  * Pedir un objeto (resolve)
  */
  public function get(string $key) {
      // 1. Si no tenemos la receta, error
      if (!array_key_exists($key, $this->bindings)) {
          throw new \Exception("No se encontró el servicio para: $key");
      }
      // 2. Si ya creamos el objeto antes, devuélvelo (ahorra memoria)
      if (array_key_exists($key, $this->instances)) {
          return $this->instances[$key];
      }

      // 3. Si no, ejecuta la función
      $resolver = $this->bindings[$key];
      $object = $resolver($this); // Le pasamos el contenedor de la función por si necesita otras cosas

      // 4. Guárdalo para la próxima
      $this->instances[$key] = $object;

      return $object;
  }
}

?>
