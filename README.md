<div align="center">

# SIR Acatlán - Sistema de Reporte de Incidentes
### Facultad de Estudios Superiores Acatlán

[![Version](https://img.shields.io/badge/version-1.0.0-blue)]()
[![PHP](https://img.shields.io/badge/backend-PHP_8.2-777BB4)]()
[![JS](https://img.shields.io/badge/frontend-Vanilla_JS-F7DF1E)]()

![banner](./docs/banner.png)

</div>

---

## 📌 Tabla de Contenido
- [Acerca del proyecto](#-acerca-del-proyecto)
- [Tecnologías](#-tecnologías)
- [Arquitectura](#-arquitectura)
- [Estructura del repositorio](#-estructura-del-repositorio)
- [Instalación y Despliegue](#-instalación-y-despliegue)
- [Scripts y Tareas](#-scripts-y-tareas)

---

## 📖 Acerca del proyecto
**SIR Acatlán** es un sistema web progresivo (PWA) diseñado para gestionar el reporte, seguimiento y resolución de incidentes (infraestructura, limpieza, seguridad, etc.) dentro de las instalaciones de la FES Acatlán.

El sistema permite:
- A usuarios reportar incidencias.
- A administradores asignar y dar seguimiento a los reportes.
- Generar métricas y evidencia de las resoluciones.

---

## 🧰 Tecnologías

### Backend (API REST)
- **Lenguaje**: PHP 8.2+
- **Gestión de dependencias**: Composer
- **Base de Datos**: MySQL / MariaDB (PDO)
- **Arquitectura**: MVC personalizado (Sin framework pesado) con Inyección de Dependencias.

### Frontend (SPA)
- **Lenguaje**: JavaScript (Vanilla ES6+)
- **Routing**: Router hash-based personalizado (`router.js`)
- **Estilos**: CSS3 Nativo
- **Componentes**: HTML templates cargados dinámicamente (`fetch`).

### Infraestructura
- **Servidor Web**: Apache / Nginx (requiere rewrite rules para SPA y API)

---

## 🏗 Arquitectura

El proyecto sigue una arquitectura **monolítica desacoplada**:

1.  **API (Backend)**: Expone endpoints RESTful. Sigue principios SOLID y Clean Architecture simplificada (Controller -> Service -> Repository -> Database).
2.  **Cliente (Frontend)**: SPA que consume la API.
    *   No requiere compilación (ni Webpack/Vite), funciona directamente en el navegador.

---

## 📁 Estructura del repositorio

```bash
/
├── API/
│   └── api_v1.0.0/       # Código fuente del Backend
│       ├── public/       # Entry point (index.php)
│       ├── src/          # Código de la aplicación (MVC)
│       └── config/       # Configuración (DB, DI Container)
├── public/               # Código fuente del Frontend
│   ├── components/       # Fragmentos HTML (Vistas)
│   ├── js/               # Lógica (Router, Estado)
│   ├── styles/           # CSS
│   └── index.html        # Entry point del Frontend
├── tasks/                # Gestión de tareas del proyecto
└── docs/                 # Documentación adicional
```

---

## ⚙ Instalación y Despliegue

### Requisitos previos
- PHP 8.2 o superior
- Composer
- MySQL/MariaDB
- Servidor Web (Apache/Nginx)

### Pasos
1.  **Clonar repositorio**:
    ```bash
    git clone <repo-url>
    ```

2.  **Configurar Backend**:
    ```bash
    cd API/api_v1.0.0
    cp .env.example .env
    # Configurar credenciales de BD en .env
    composer install
    ```

3.  **Configurar Base de Datos**:
    - Ejecutar scripts SQL de inicialización (si existen en `docs` o `migrations`).

4.  **Servidor Web**:
    - Apuntar el `DocumentRoot` a la carpeta raíz o configurar alias.
    - **Importante**: Configurar reglas de reescritura para que todas las peticiones a `/api` vayan a `API/api_v1.0.0/public/index.php`.

---

## 📜 Scripts y Tareas

El seguimiento de tareas se lleva en `tasks/tasks.org`.

Para ver detalles sobre cómo extender la API vea [API README](API/api_v1.0.0/README.md).
Para ver detalles sobre mejoras al Frontend vea [Docs Public](docs/public_improvements.md).


[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)]()
[![License](https://img.shields.io/badge/license-MIT-blue.svg)]()
[![Version](https://img.shields.io/badge/version-1.0.0-orange)]()

![banner](./docs/banner.png)

</div>

---
