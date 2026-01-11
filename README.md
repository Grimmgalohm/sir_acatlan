<div align="center">

# SIR Acatlán - Sistema de Reporte de Incidentes
### Facultad de Estudios Superiores Acatlán

[![Version](https://img.shields.io/badge/versión-1.0.0-blue)]()
[![PHP](https://img.shields.io/badge/backend-PHP_8.2-777BB4)]()
[![JS](https://img.shields.io/badge/frontend-Vanilla_JS-F7DF1E)]()

![banner](./docs/banner.png)

</div>

---

## 📌 Tabla de Contenido
- [¿Qué onda con este proyecto?](#-qué-onda-con-este-proyecto)
- [Tecnologías](#-tecnologías)
- [Arquitectura del Sistema](#-arquitectura-del-sistema)
- [¿Cómo está armado el repositorio?](#-cómo-está-armado-el-repositorio)
- [Guía para Desarrolladores (Cómo agregar nuevas rutas)](#-guía-para-desarrolladores)
- [Instalación y Puesta en Marcha](#-instalación-y-puesta-en-marcha)

---

## 📖 ¿Qué onda con este proyecto?
**SIR Acatlán** es una Progressive Web App (PWA) pensada para que la banda de la FES Acatlán pueda reportar broncas de infraestructura, limpieza o seguridad de volada.

El sistema permite:
- Reportar incidencias con evidencia.
- Que los admins asignen y den seguimiento a los reportes.
- Sacar métricas para ver cómo anda el servicio.

---

## 🧰 Tecnologías

### Backend (API REST)
- **Lenguaje**: PHP 8.2+
- **Dependencias**: Composer (manejando `vlucas/phpdotenv` y otras librerías chidas).
- **Base de Datos**: MySQL / MariaDB con PDO.
- **Arquitectura**: MVC modular con Inyección de Dependencias (DI Container) y Enrutamiento personalizado. Nada de frameworks pesados, puro código eficiente.

### Frontend (SPA)
- **Lenguaje**: JavaScript (Vanilla ES6+).
- **Routing**: Router propio basado en hash (`#`).
- **Estilos**: CSS3 nativo.
- **Componentes**: Carga dinámica con `fetch`.

---

## 🏗 Arquitectura del Sistema

El backend ya no es un espagueti de `switch` cases. Ahora manejamos una arquitectura limpia y escalable:

1.  **Entry Point (`index.php`)**: Carga el entorno, el contenedor de dependencias e inicia el Router.
2.  **Router (`Core\Router`)**: Sistema de rutas dinámicas (Regex) que soporta verbos HTTP (GET, POST, PUT, DELETE) y mapeo de parámetros.
3.  **Dependency Injection (`Config\bootstrap.php`)**: Aquí "conectamos" todo. Definimos qué repositorio va con qué servicio y qué servicio va con qué controlador.
4.  **Capas**:
    *   **Controller**: Recibe la petición, valida datos básicos y llama al Servicio.
    *   **Service**: Aquí vive la lógica de negocio (validaciones complejas, reglas del sistema).
    *   **Repository**: Es el único que toca la base de datos (SQL).
    *   **Model/DTO**: Objetos simples para transportar datos.

---

## 🧑‍💻 Guía para Desarrolladores

### ¿Cómo agregar nuevas rutas sin morir en el intento?

Si quieres armar un nuevo módulo (por ejemplo, "Eventos"), sigue estos pasos y no habrá falla:

#### 1. Prepara tus Capas
Crea los archivos en `src/`:
- `Model/Event.php` (Tu objeto de datos)
- `Repository/EventRepository.php` (Tus queries SQL)
- `Service/EventService.php` (Tu lógica chida)
- `Controller/EventController.php` (Tus endpoints)

#### 2. Conecta todo en `config/bootstrap.php`
Tienes que decirle al Container cómo armar tus clases. Agrega esto antes del `return $container;`:

```php
// ... otros bindings

$container->bind(EventRepository::class, function($c){
    return new EventRepository($c->get(PDO::class));
});

$container->bind(EventService::class, function($c){
    return new EventService($c->get(EventRepository::class));
});

$container->bind(EventController::class, function($c){
    return new EventController($c->get(EventService::class));
});
```

#### 3. Registra la Ruta en `public/index.php`
Ve al archivo principal y dile al Router que escuche tu nueva ruta:

```php
// ... otras rutas

// Ejemplo GET
$router->get('/api/events', [App\Controller\EventController::class, 'getAll']);

// Ejemplo POST
$router->post('/api/events', [App\Controller\EventController::class, 'create']);
```

¡Y listo! Ya tienes tu endpoint jalando al 100.

---

## ⚙ Instalación y Puesta en Marcha

### Requisitos
- PHP 8.2 o superior
- Composer instalado
- MySQL/MariaDB

### Pasos
1.  **Clona el repo**:
    ```bash
    git clone <url-del-repo>
    ```

2.  **Configura el Backend**:
    ```bash
    cd API/api_v1.0.0
    cp .env.example .env
    # Edita el .env con tus credenciales de BD
    composer install
    ```

3.  **Levanta el servidor** (para desarrollo):
    ```bash
    # Desde la carpeta API/api_v1.0.0/public
    php -S localhost:8000
    ```

4.  **Prueba**: Abre `http://localhost:8000/api/status` en tu navegador.

---
<div align="center">
Hecho con código y café ☕
</div>
