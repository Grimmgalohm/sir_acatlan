# Mejoras para la carpeta Public

Este documento detalla las razones detrás de las tareas de mejora registradas para la carpeta `public`.

## 1. SEO y Metadatos
- **Robots.txt y Sitemap.xml**: Esenciales para que los buscadores indexen correctamente el sitio (o ignoren partes privadas).
- **Open Graph**: Ya planeado, pero crucial para que al compartir enlaces en redes sociales se vea una previsualización correcta.

## 2. Performance (Rendimiento)
- **Cache Busting**: El navegador guarda en caché los archivos CSS y JS. Si actualizas el código pero el nombre del archivo es igual, el usuario no verá los cambios.
    - *Solución*: Agregar `?v=1.0.1` al final de los imports o usar nombres like `styles.v123.css` (requiere build process, por ahora query param es suficiente).
- **Defer/Async en Scripts**: `router.js` debe cargar sin bloquear el renderizado inicial del HTML.
- **Optimización de Imágenes**: Asegurar que las imágenes estén en formatos modernos (WebP) y comprimidas.

## 3. Accesibilidad (A11y)
- **Atributos Alt**: Todas las imágenes deben tener texto alternativo para lectores de pantalla.
- **ARIA Labels**: Elementos interactivos sin texto visible (iconos) necesitan etiquetas para ser comprensibles por tecnologías de asistencia.

## 4. PWA (Progressive Web App)
- **Manifest.json**: Permite instalar la aplicación en el móvil y define colores de tema, iconos, etc.
- **Service Workers**: (Futuro) Permitiría cachear la app para que funcione offline o cargue instantáneamente.

## 5. Seguridad
- **Content Security Policy (CSP)**: Previene ataques XSS restringiendo de dónde se pueden cargar scripts y estilos.

## 6. Estructura
- **Carpetas**: Mantener orden. Si los componentes HTML crecen, considerar subcarpetas por módulo.
