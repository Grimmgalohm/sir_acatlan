# SIR ACATLÁN - API v1.0.0

Esta es la documentación técnica de la API de SIR Acatlán. Aquí encontrarás cómo funciona el núcleo del framework casero, su arquitectura y cómo extenderlo sin romper nada.

## Arquitectura del Proyecto

El sistema utiliza una arquitectura **MVC Modular** con **Inyección de Dependencias** y un **Front Controller**. 
No usamos frameworks pesados (Laravel/Symfony), sino componentes nativos optimizados.

### Estructura de Directorios

```
api_v1.0.0/
├─ public/
│  └─ index.php           # Front Controller. Define las rutas y arranca la app.
├─ config/
│  └─ bootstrap.php       # "Wiring". Configura el contenedor de dependencias.
├─ src/
│  ├─ Core/               # El Framework
│  │  ├─ Router.php       # Enrutador Dinámico (Soporta Regex y Verbos HTTP)
│  │  ├─ Request.php      # Maneja $_GET, $_POST, Body JSON y Query Params
│  │  └─ Response.php     # Estandariza respuestas JSON
│  ├─ Controller/         # Reciben HTTP -> Llaman Servicio -> Retornan JSON
│  ├─ Service/            # Lógica de Negocio (Validaciones, Reglas)
│  ├─ Repository/         # Acceso a Datos (SQL / PDO)
│  └─ Model/              # Entidades / DTOs
└─ .env                   # Configuración de entorno
```

---

## El Router Dinámico

A diferencia de versiones anteriores, el Router ahora soporta **rutas dinámicas** y **verbos HTTP**.

### Definición de Rutas (`public/index.php`)

```php
// GET simple
$router->get('/api/status', [StatusController::class, 'check']);

// Rutas con Parámetros ({id})
$router->get('/api/users/{id}', [UserController::class, 'getOne']);
$router->put('/api/users/{id}', [UserController::class, 'update']);
$router->delete('/api/users/{id}', [UserController::class, 'delete']);

// Manejo de Query Params (en el Controlador)
// URL: /api/users?role=admin
public function getAll(Request $request) {
    $role = $request->getQuery('role'); // "admin"
}
```

---

## 🧑‍💻 Guía para Desarrolladores: Creando un Nuevo Módulo

Si quieres agregar una nueva funcionalidad (ej. "Eventos"), sigue el patrón del módulo **Example** incluido en el código.

### Paso 1: El Modelo (`src/Model/Event.php`)
Define la estructura de tu objeto.
```php
namespace App\Model;
class Event {
    public function __construct(
        public int $id,
        public string $title
    ) {}
}
```

### Paso 2: El Repositorio (`src/Repository/EventRepository.php`)
Encárgate del SQL. Inyecta `PDO` en el constructor.
```php
namespace App\Repository;
class EventRepository {
    public function __construct(private \PDO $db) {}
    
    public function find(int $id) { /* SQL ... */ }
}
```

### Paso 3: El Servicio (`src/Service/EventService.php`)
Aquí va la lógica. Inyecta el Repositorio.
```php
namespace App\Service;
class EventService {
    public function __construct(private EventRepository $repo) {}
    
    public function getEvent(int $id) {
        // Valida reglas de negocio aquí
        return $this->repo->find($id);
    }
}
```

### Paso 4: El Controlador (`src/Controller/EventController.php`)
Recibe HTTP, devuelve JSON. Inyecta el Servicio.
```php
namespace App\Controller;
class EventController {
    public function __construct(private EventService $service) {}

    public function getOne(Request $request, $id) {
        $data = $this->service->getEvent($id);
        Response::json($data);
    }
}
```

### Paso 5: El Wiring (`config/bootstrap.php`)
**CRÍTICO:** Debes registrar tus clases en el contenedor de dependencias para que el Router pueda construirlas automáticamente.

```php
// ... en config/bootstrap.php

// 1. Registrar Repo
$container->bind(EventRepository::class, function($c){
    return new EventRepository($c->get(\PDO::class));
});

// 2. Registrar Servicio
$container->bind(EventService::class, function($c){
    return new EventService($c->get(EventRepository::class));
});

// 3. Registrar Controlador
$container->bind(EventController::class, function($c){
    return new EventController($c->get(EventService::class));
});
```

### Paso 6: La Ruta (`public/index.php`)
Finalmente, expón tu endpoint.

```php
$router->get('/api/events/{id}', [App\Controller\EventController::class, 'getOne']);
```

---

## Instalación

1.  `cd API/api_v1.0.0`
2.  `composer install`
3.  Copia `.env.example` a `.env` y configura la BD.
4.  Levanta el server: `php -S localhost:8000 -t public`