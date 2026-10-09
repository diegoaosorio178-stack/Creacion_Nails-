-- Creación de la base de datos si no existe
CREATE DATABASE IF NOT EXISTS bdnewcreacion DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bdnewcreacion;

-- 1. Tabla de Clientes
CREATE TABLE IF NOT EXISTS clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabla de Manicuristas
CREATE TABLE IF NOT EXISTS manicuristas (
    id_manicurista INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    estado_laboral ENUM('activo', 'inactivo', 'vacaciones') DEFAULT 'activo',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabla de Administradores
CREATE TABLE IF NOT EXISTS administradores (
    id_administrador INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tabla de Horarios
CREATE TABLE IF NOT EXISTS horarios (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_manicurista INT NOT NULL,
    dia_semana ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_manicurista) REFERENCES manicuristas(id_manicurista) ON DELETE CASCADE
);

-- 5. Tabla de Servicios
CREATE TABLE IF NOT EXISTS servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre_servicio VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    duracion_minutos INT NOT NULL,
    imagen_url VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE
);

-- 6. Tabla de Citas
CREATE TABLE IF NOT EXISTS citas (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_manicurista INT NOT NULL,
    id_servicio INT NOT NULL,
    fecha_cita DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    precio_cobrado DECIMAL(10,2),
    estado ENUM('pendiente', 'confirmada', 'rechazada', 'completada', 'cancelada') DEFAULT 'pendiente',
    motivo_rechazo TEXT,
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_finalizacion DATETIME NULL,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_manicurista) REFERENCES manicuristas(id_manicurista),
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio)
);

-- 7. Tabla de Pagos
CREATE TABLE IF NOT EXISTS pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_cita INT NOT NULL,
    monto_abono DECIMAL(10,2) NOT NULL,
    comprobante_url VARCHAR(255) NOT NULL,
    metodo_pago ENUM('nequi', 'daviplata', 'transferencia', 'efectivo') NOT NULL,
    estado_pago ENUM('pendiente_validacion', 'aprobado', 'rechazado') DEFAULT 'pendiente_validacion',
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cita) REFERENCES citas(id_cita) ON DELETE CASCADE
);

-- 8. Tabla de Calificaciones
CREATE TABLE IF NOT EXISTS calificaciones (
    id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_cita INT NOT NULL,
    id_manicurista INT NOT NULL,
    estrellas TINYINT NOT NULL CHECK (estrellas BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_calificacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cita) REFERENCES citas(id_cita) ON DELETE CASCADE,
    FOREIGN KEY (id_manicurista) REFERENCES manicuristas(id_manicurista) ON DELETE CASCADE
);
