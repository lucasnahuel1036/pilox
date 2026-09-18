CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(15) NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('alumno', 'docente', 'admin') DEFAULT 'alumno',
    estado ENUM('activo', 'inactivo', 'moroso') DEFAULT 'activo',
    telefono VARCHAR(20),
    fecha_nacimiento DATE,
    especialidad VARCHAR(100),
    cargo VARCHAR(100)
);

CREATE TABLE sucursales (
    id_sucursal INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    telefono VARCHAR(20)
);

CREATE TABLE actividades (
    id_actividad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

CREATE TABLE turnos (
    id_turno INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    horario TIME NOT NULL,
    capacidad INT NOT NULL,
    cupos_disponibles INT NOT NULL,
    id_sucursal INT,
    id_actividad INT,
    id_docente INT,
    FOREIGN KEY (id_sucursal) REFERENCES sucursales(id_sucursal),
    FOREIGN KEY (id_actividad) REFERENCES actividades(id_actividad),
    FOREIGN KEY (id_docente) REFERENCES usuarios(id_usuario)
);

CREATE TABLE abonos (
    id_abono INT AUTO_INCREMENT PRIMARY KEY,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    clases_totales INT NOT NULL,
    clases_restantes INT NOT NULL,
    estado_pago ENUM('pagado', 'pendiente') DEFAULT 'pendiente',
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    fecha_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('confirmada', 'cancelada') DEFAULT 'confirmada',
    asistencia BOOLEAN DEFAULT FALSE,
    id_usuario INT,
    id_turno INT,
    id_abono INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_turno) REFERENCES turnos(id_turno),
    FOREIGN KEY (id_abono) REFERENCES abonos(id_abono)
);
-- --------------------------------------------------------
-- DATOS DE PRUEBA 
-- --------------------------------------------------------

-- Insertar Administrador 
INSERT INTO usuarios (nombre, apellido, dni, email, password, rol, estado) 
VALUES ('Administrador', 'Pilox', '11111111', 'admin@pilox.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'activo');

-- Insertar Docente para probar los turnos 
INSERT INTO usuarios (nombre, apellido, dni, email, password, rol, estado, especialidad) 
VALUES ('Juan', 'Pérez', '22222222', 'profe@pilox.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'docente', 'activo', 'Instructorado Múltiple');

-- Insertar Sucursal
INSERT INTO sucursales (nombre, direccion, telefono) 
VALUES ('Sede Central Córdoba', 'Av. Cornelio Saavedra 3663, Marqués de Sobremonte', '3510000000');

-- Insertar Actividades Base
INSERT INTO actividades (nombre, descripcion) VALUES 
('Pilates', 'Clases de Pilates con equipamiento completo.'),
('Yoga', 'Hatha Yoga para todos los niveles.'),
('GAP', 'Glúteos, Abdomen y Piernas.'),
('Pilates AFA', 'Pilates adaptado para rehabilitación.');