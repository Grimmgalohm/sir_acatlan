# Explicación de Arquitectura - Sir Acatlán API

## 1. Arquitectura General
El proyecto sigue una **Arquitectura en Capas (Layered Architecture)** con **Inyección de Dependencias (DI)**.

**El objetivo** es separar responsabilidades:
1.  **Web/HTTP**: Recibir peticiones y devolver respuestas (Controller, Router).
2.  **Negocio**: La lógica "pensante" de la aplicación (Service).
3.  **Datos**: La comunicación con la base de datos (Repository).
4.  **Configuración**: Unir todas las piezas (Container, Bootstrap).

---

## 2. Estructura de Archivos (API/api_v1.0.0)
*   **`public/`**: Es la puerta de entrada. El servidor web apunta aquí.
    *   `index.php`: Recibe **todas** las peticiones, decide qué hacer (enrutamiento simple con `switch`) y devuelve la respuesta.
*   **`config/`**:
    *   `bootstrap.php`: Es el "electricista". Conecta los cables (Dependencias) antes de empezar. Define qué necesita cada clase (ej. "El Controlador necesita al Servicio").
*   **`src/`**: El código fuente real.
    *   `Core/`: Herramientas base (`Container` para dependencias, `Request`, `Response`).
    *   `Controller/`: Maneja HTTP (Validar inputs básicos, códigos de estado 200/404/500).
    *   `Service/`: Lógica de negocio (Reglas, validaciones complejas, cálculos).
    *   `Repository/`: SQL y base de datos. Se encarga de traducir arrays de BD a Objetos.
    *   `Model/`: Definición de datos (Entidades).
    *   `DTOs/`: Objetos de Transferencia de Datos (Contratos de entrada).

---

## 3. Flujo de una Petición (Ejemplo: `POST /api/register`)

1.  **Entrada (`public/index.php`)**:
    *   Carga el entorno y el **Contenedor** (`config/bootstrap.php`).
    *   Identifica la ruta y método.
2.  **El Contenedor (`src/Core/Container.php`)**:
    *   Construye las clases necesarias en cadena: `PDO` -> `UserRepository` -> `UserService` -> `UserController`.
3.  **El Controlador (`src/Controller/UserController.php`)**:
    *   Recibe la petición, extrae el JSON y llama al Servicio.
4.  **El Servicio (`src/Service/UserService.php`)**:
    *   Valida reglas de negocio (ej. "email válido", "no duplicado").
    *   Llama al Repositorio.
5.  **El Repositorio (`src/Repository/UserRepository.php`)**:
    *   Ejecuta el SQL (`INSERT`).
6.  **Respuesta**:
    *   Todo retorna hacia atrás y el Controller envía un JSON con código `201 Created`.

---

## 4. Model vs DTO vs Array

### El Model (`src/Model`)
Representa la **"Verdad Interna"** de tu aplicación. Es cómo se ven tus datos una vez que "existen" en tu sistema.
*   **Uso principal**: Comunicar desde la Base de Datos (Repository) hacia la aplicación (Service/Controller).
*   **Ventaja**: Evita errores de tipeo comúnes en arrays (ej. `$user->email` vs `$user['emial']`).

### El DTO (Data Transfer Object) (`src/DTOs`)
Representa el **"Contrato Externo"**. Es cómo esperas recibir los datos desde fuera (del usuario o frontend).
*   **Ejemplo**: `CreateReportDTO.php`.
*   **Uso**: Validar y estructurar lo que entra antes de que llegue a la lógica vital.
*   En tu código, el flujo de **Reportes** usa DTOs (más robusto), mientras que el de **Usuarios** usa Arrays simples (más flexible pero riesgoso).

### Resumen del Flujo de Datos
1.  **Input (Entrada)**: Usuario -> JSON -> **DTO** (o Array) -> Service.
2.  **Proceso**: Service aplica lógica.
3.  **Persistencia**: Service -> **Model** (datos listos) -> Repository -> SQL.
4.  **Output (Salida)**: SQL -> Repository -> **Model** -> Service -> Controller -> JSON.
