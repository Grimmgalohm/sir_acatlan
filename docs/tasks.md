# Tareas del Proyecto

### Pendientes
- [ ] **Diseño de Sistema de Logs Robustos (Eventos y Errores)**
    - [ ] Investigar/Seleccionar librería (recomendación: Monolog).
    - [ ] Definir manejadores (StreamHandler para archivos, tal vez Slack/Email para críticos).
    - [ ] Crear clase envoltorio (Wrapper) o Servicio de Logs para inyectar en el contenedor.
    - [ ] Implementar rotación de logs (diario/mensual).
    - [ ] Agregar manejo de `try-catch` global en el Router para atrapar excepciones no controladas y loguearlas.
    
### Mantenimiento
- [ ] Revisar permisos de escritura en carpeta de logs.
