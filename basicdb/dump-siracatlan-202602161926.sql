CREATE SCHEMA IF NOT EXISTS `siracatlan` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_spanish_ci;

create table siracatlan.zonas (
id int auto_increment PRIMARY KEY not null,
nombre varchar(100) unique not null,
descripcion varchar(200) null
)ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;
select * from siracatlan.zonas;

CREATE TABLE siracatlan.edificios (
  id INT AUTO_INCREMENT PRIMARY KEY not null,
  codigo VARCHAR(10) NOT NULL UNIQUE,
  nombre VARCHAR(100) NOT NULL,
  zona_id INT NOT NULL,
  INDEX idx_edificios_zona (zona_id),
  CONSTRAINT fk_edificios_zona
    FOREIGN KEY (zona_id)
    REFERENCES zonas(id)
) ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

create table siracatlan.banos (
id int auto_increment primary key not null,
edificio_id int not null,
tipo enum('H', 'M', 'X'),
codigo_interno varchar(10) UNIQUE NOT NULL,
activo bool,
INDEX idx_banos_edificio (edificio_id),
CONSTRAINT fk_banos_edificio
FOREIGN KEY (edificio_id)
REFERENCES edificios(id)
)ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

create table siracatlan.cat_incidente_categoria (
id int NOT NULL PRIMARY KEY auto_increment,
clave varchar(50) UNIQUE NOT NULL,
nombre_es varchar(100) NOT NULL,
descripcion varchar(200) NULL,
activo bool not null
)ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

create table siracatlan.cat_incidente_estado (
id int auto_increment not null primary key,
clave varchar(50) unique not NULL,
nombre_es varchar(100) not null,
orden int not null,
es_final bool not null
)ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

create table siracatlan.cat_asignacion_resultado(
id int not null primary key auto_increment,
clave varchar(50) unique not NULL,
nombre_es varchar(100) not null,
cierra_incidente bool not null
)ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

create table siracatlan.usuarios (
id int not null primary key auto_increment,
email varchar(255) unique not null,
nombre varchar(255) not null,
nombre_dos varchar(255) null,
apellido_p varchar(255) not null,
apellido_m varchar(255) null,
creado_en timestamp default current_timestamp,
ultimo_login_en timestamp default null
)ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

create table siracatlan.roles (
id int not null primary key auto_increment,
clave varchar(50) unique not null,
nombre_es varchar(100) not null,
descripcion varchar(200) null
)ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

CREATE TABLE siracatlan.usuario_rol (
  usuario_id INT NOT NULL,
  rol_id INT NOT NULL,
  PRIMARY KEY (usuario_id, rol_id),
  INDEX idx_usuario_rol_rol (rol_id),
  CONSTRAINT fk_usuario_rol_usuario
    FOREIGN KEY (usuario_id)
    REFERENCES usuarios(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_usuario_rol_rol
    FOREIGN KEY (rol_id)
    REFERENCES roles(id)
    ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

create table siracatlan.incidentes (
id int not null auto_increment primary key,
tracking_code varchar(255) unique not NULL,
bano_id int not null,
categoria_id int not null,
estado_id int not null,
descripcion varchar(255),
prioridad int null,
canal_reporte varchar(50) null,
creado_en timestamp default CURRENT_TIMESTAMP(),
actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
cerrado_en timestamp,
creado_por_usuario_id varchar(255) null,
contacto_opcional varchar(255) null,
INDEX idx_incidentes_bano_id(bano_id),
CONSTRAINT fk_incidentes_bano_id
FOREIGN KEY (bano_id)
REFERENCES banos(id),
INDEX idx_incidentes_categoria_id (categoria_id),
CONSTRAINT fk_incidentes_categoria_id
FOREIGN KEY (categoria_id)
REFERENCES cat_incidente_categoria(id),
INDEX idx_incidentes_estado_id (estado_id),
CONSTRAINT fk_incidentes_estado_id
FOREIGN KEY (estado_id)
REFERENCES cat_incidente_estado(id)
)ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

create table siracatlan.incidente_estado_historial(
id int not null primary key auto_increment,
incidentes_id int not null,
estado_anterior_id int not null,
estado_nuevo_id int not null,
cambiado_por_usuario_id int not null,
razon varchar(50) null,
nota varchar(255) null,
cambiado_en timestamp default current_timestamp()
)ENGINE=InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_spanish_ci;

CREATE TABLE siracatlan.asignaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  incidente_id INT NOT NULL,
  agente_usuario_id INT NOT NULL,
  asignado_por_usuario_id INT NULL,
  estado VARCHAR(20) NOT NULL,         -- PENDIENTE | ACEPTADA | EN_PROCESO | CERRADA
  asignado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  aceptado_en TIMESTAMP NULL,
  iniciado_en TIMESTAMP NULL,
  cerrado_en TIMESTAMP NULL,
  resultado_id INT NULL,
  comentario_cierre VARCHAR(255) NULL,
  INDEX idx_asignaciones_incidente (incidente_id),
  INDEX idx_asignaciones_agente (agente_usuario_id),
  INDEX idx_asignaciones_asignado_por (asignado_por_usuario_id),
  INDEX idx_asignaciones_resultado (resultado_id),
  CONSTRAINT fk_asignaciones_incidente
    FOREIGN KEY (incidente_id) REFERENCES siracatlan.incidentes(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_asignaciones_agente
    FOREIGN KEY (agente_usuario_id) REFERENCES siracatlan.usuarios(id)
    ON DELETE RESTRICT,
  CONSTRAINT fk_asignaciones_asignado_por
    FOREIGN KEY (asignado_por_usuario_id) REFERENCES siracatlan.usuarios(id)
    ON DELETE SET NULL,
  CONSTRAINT fk_asignaciones_resultado
    FOREIGN KEY (resultado_id) REFERENCES siracatlan.cat_asignacion_resultado(id)
    ON DELETE SET NULL
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_spanish_ci;

CREATE TABLE siracatlan.evidencias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  incidente_id INT NULL,
  asignacion_id INT NULL,
  origen VARCHAR(10) NOT NULL,          -- ALUMNO | AGENTE | ADMIN
  tipo VARCHAR(20) NOT NULL,            -- FOTO_REPORTE | FOTO_ANTES | FOTO_DESPUES | VIDEO | PDF | OTRO
  url VARCHAR(2048) NOT NULL,
  mime_type VARCHAR(100) NULL,
  tamano_bytes BIGINT NULL,
  hash_archivo VARCHAR(128) NULL,
  subido_por_usuario_id INT NULL,       -- NULL si ALUMNO (anónimo)
  subido_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_evidencias_incidente (incidente_id),
  INDEX idx_evidencias_asignacion (asignacion_id),
  INDEX idx_evidencias_subido_por (subido_por_usuario_id),
  CONSTRAINT fk_evidencias_incidente
    FOREIGN KEY (incidente_id) REFERENCES siracatlan.incidentes(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_evidencias_asignacion
    FOREIGN KEY (asignacion_id) REFERENCES siracatlan.asignaciones(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_evidencias_subido_por
    FOREIGN KEY (subido_por_usuario_id) REFERENCES siracatlan.usuarios(id)
    ON DELETE SET NULL,
  -- Regla: EXACTAMENTE uno de los dos debe tener valor
  CONSTRAINT chk_evidencia_parent
    CHECK (
      (incidente_id IS NOT NULL AND asignacion_id IS NULL)
      OR
      (incidente_id IS NULL AND asignacion_id IS NOT NULL)
    )
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_spanish_ci;

CREATE TABLE siracatlan.comentarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  incidente_id INT NOT NULL,
  autor_usuario_id INT NOT NULL,
  es_interno TINYINT(1) NOT NULL DEFAULT 1,
  texto VARCHAR(1000) NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_comentarios_incidente (incidente_id),
  INDEX idx_comentarios_autor (autor_usuario_id),
  CONSTRAINT fk_comentarios_incidente
    FOREIGN KEY (incidente_id) REFERENCES siracatlan.incidentes(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_comentarios_autor
    FOREIGN KEY (autor_usuario_id) REFERENCES siracatlan.usuarios(id)
    ON DELETE RESTRICT
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_spanish_ci;

ALTER TABLE siracatlan.incidente_estado_historial
ADD INDEX idx_ieh_incidente (incidentes_id),
ADD INDEX idx_ieh_estado_anterior (estado_anterior_id),
ADD INDEX idx_ieh_estado_nuevo (estado_nuevo_id),
ADD INDEX idx_ieh_cambiado_por (cambiado_por_usuario_id);

ALTER TABLE siracatlan.incidente_estado_historial
  MODIFY cambiado_por_usuario_id INT NULL;

ALTER TABLE siracatlan.incidente_estado_historial
  ADD CONSTRAINT fk_ieh_incidente
    FOREIGN KEY (incidentes_id) REFERENCES siracatlan.incidentes(id)
    ON DELETE CASCADE,
  ADD CONSTRAINT fk_ieh_estado_anterior
    FOREIGN KEY (estado_anterior_id) REFERENCES siracatlan.cat_incidente_estado(id)
    ON DELETE RESTRICT,
  ADD CONSTRAINT fk_ieh_estado_nuevo
    FOREIGN KEY (estado_nuevo_id) REFERENCES siracatlan.cat_incidente_estado(id)
    ON DELETE RESTRICT,
  ADD CONSTRAINT fk_ieh_cambiado_por
    FOREIGN KEY (cambiado_por_usuario_id) REFERENCES siracatlan.usuarios(id)
    ON DELETE SET NULL;

SELECT h.*
FROM siracatlan.incidente_estado_historial h
LEFT JOIN siracatlan.incidentes i ON i.id = h.incidentes_id
WHERE i.id IS NULL;

/*Comentario random para empezar una query :D*/
INSERT INTO siracatlan.zonas (id, nombre, descripcion) VALUES
(1, 'Zona Norte', 'Edificios del lado norte del campus'),
(2, 'Zona Sur', 'Edificios del lado sur del campus'),
(3, 'Zona Centro', 'Área administrativa y aulas centrales'),
(4, 'Zona Laboratorios', 'Laboratorios y talleres'),
(5, 'Zona Deportiva', 'Gimnasio y canchas');

INSERT INTO siracatlan.edificios (id, codigo, nombre, zona_id) VALUES
(1, 'A1', 'Edificio A1', 1),
(2, 'A2', 'Edificio A2', 1),
(3, 'B1', 'Edificio B1', 2),
(4, 'C1', 'Edificio Central', 3),
(5, 'LAB', 'Laboratorios', 4);

INSERT INTO siracatlan.banos (id, edificio_id, tipo, codigo_interno, activo) VALUES
(1, 1, 'H', 'A1-H', 1),
(2, 1, 'M', 'A1-M', 1),
(3, 2, 'H', 'A2-H', 1),
(4, 2, 'M', 'A2-M', 1),
(5, 5, 'X', 'LAB-X', 1);

INSERT INTO siracatlan.cat_incidente_categoria (id, clave, nombre_es, descripcion, activo) VALUES
(1, 'SIN_PAPEL', 'Sin papel', 'No hay papel higiénico', 1),
(2, 'SIN_JABON', 'Sin jabón', 'No hay jabón para manos', 1),
(3, 'BAÑO_SUCIO', 'Baño sucio', 'El baño está en malas condiciones', 1),
(4, 'BAÑO_CERRADO', 'Baño cerrado', 'El baño está cerrado', 1),
(5, 'FUGA_AGUA', 'Fuga de agua', 'Hay una fuga de agua', 1);

INSERT INTO siracatlan.cat_incidente_estado (id, clave, nombre_es, orden, es_final) VALUES
(1, 'NUEVO', 'Nuevo', 1, 0),
(2, 'TRIAGE', 'En revisión', 2, 0),
(3, 'ASIGNADO', 'Asignado', 3, 0),
(4, 'EN_PROCESO', 'En proceso', 4, 0),
(5, 'RESUELTO', 'Resuelto', 5, 1),
(6, 'NO_PROCEDE', 'No procede', 6, 1);


ALTER table siracatlan.usuarios ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1 AFTER apellido_p;

INSERT INTO siracatlan.usuarios (id, email, nombre, apellido_p, activo, creado_en) VALUES
(1, 'admin1@uni.mx', 'Genaro', 'Martinez', 1, NOW()),
(2, 'admin2@uni.mx', 'Durango', 'Gacía', 1, NOW()),
(3, 'agente1@uni.mx', 'Salma', 'Marquez', 1, NOW()),
(4, 'agente2@uni.mx', 'Diego', 'Gutierrez', 1, NOW()),
(5, 'agente3@uni.mx', 'Sabrina', 'Spellman', 1, NOW());

INSERT INTO siracatlan.roles (id, clave, nombre_es, descripcion) VALUES
(1, 'ADMIN_DISPATCH', 'Administrador Asignador', 'Puede asignar y cerrar incidentes'),
(2, 'ADMIN_VIEWER', 'Administrador Visualizador', 'Solo lectura y dashboards'),
(3, 'AGENT', 'Agente de Limpieza', 'Atiende incidentes');

INSERT INTO siracatlan.usuario_rol (usuario_id, rol_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 3),
(5, 3);

INSERT INTO siracatlan.incidentes (
  id, tracking_code, bano_id, categoria_id, estado_id,
  descripcion, prioridad, canal_reporte,
  creado_en, contacto_opcional
) VALUES
(1, 'INC-0001', 1, 3, 1, 'Baño muy sucio', 2, 'WEB', NOW(), NULL),
(2, 'INC-0002', 2, 1, 1, 'No hay papel', 1, 'QR', NULL, NULL),
(3, 'INC-0003', 3, 2, 2, 'No hay jabón', 1, 'WEB', NOW(), NULL),
(4, 'INC-0004', 4, 4, 3, 'Baño cerrado', 3, 'KIOSKO', NOW(), NULL),
(5, 'INC-0005', 5, 5, 3, 'Fuga de agua', 3, 'WEB', NOW(), 'correo@opcional.mx');

INSERT INTO siracatlan.asignaciones (
  id, incidente_id, agente_usuario_id, asignado_por_usuario_id,
  estado, asignado_en
) VALUES
(1, 1, 3, 1, 'PENDIENTE', NOW()),
(2, 2, 3, 1, 'EN_PROCESO', NOW()),
(3, 3, 4, 1, 'PENDIENTE', NOW()),
(4, 4, 4, 1, 'EN_PROCESO', NOW()),
(5, 5, 5, 1, 'PENDIENTE', NOW());

INSERT INTO siracatlan.evidencias (
  id, incidente_id, asignacion_id, origen, tipo,
  url, subido_por_usuario_id, subido_en
) VALUES
(1, 1, NULL, 'ALUMNO', 'FOTO_REPORTE', 'https://dummy/rep1.jpg', NULL, NOW()),
(2, NULL, 1, 'AGENTE', 'FOTO_ANTES', 'https://dummy/a1_antes.jpg', 3, NOW()),
(3, NULL, 2, 'AGENTE', 'FOTO_DESPUES', 'https://dummy/a2_despues.jpg', 3, NOW()),
(4, NULL, 4, 'AGENTE', 'FOTO_ANTES', 'https://dummy/a4_antes.jpg', 4, NOW()),
(5, NULL, 5, 'AGENTE', 'FOTO_ANTES', 'https://dummy/a5_antes.jpg', 5, NOW());

INSERT INTO siracatlan.comentarios (
  id, incidente_id, autor_usuario_id, es_interno, texto, creado_en
) VALUES
(1, 1, 1, 1, 'Asignado al agente de zona norte', NOW()),
(2, 2, 1, 1, 'Incidente prioritario', NOW()),
(3, 3, 2, 1, 'Pendiente revisión', NOW()),
(4, 4, 1, 1, 'Verificar si es temporal', NOW()),
(5, 5, 1, 1, 'Posible mantenimiento', NOW());

INSERT INTO siracatlan.incidente_estado_historial (
  id, incidentes_id, estado_anterior_id, estado_nuevo_id,
  cambiado_por_usuario_id, razon, cambiado_en
) VALUESk
(1, 1, 1, 1, NULL, 'Reporte inicial', NOW()),
(2, 2, 1, 3, 1, 'Asignado a agente', NOW()),
(3, 3, 1, 2, 1, 'En revisión', NOW()),
(4, 4, 2, 3, 1, 'Asignado', NOW()),
(5, 5, 3, 4, 1, 'Trabajo iniciado', NOW());