-- Crear base de datos
CREATE DATABASE IF NOT EXISTS REDSOCIAL;
USE REDSOCIAL;

-- Tabla de usuarios
CREATE TABLE users (
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'admin') DEFAULT 'usuario',
    fechaNacimiento DATE,
    genero VARCHAR(20),
    ciudad VARCHAR(100),
    pais VARCHAR(100),
    fotoPerfil VARCHAR(255),
    biografia TEXT,
    estado ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimaActividad TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de publicaciones
CREATE TABLE publicaciones (
    idPublicacion INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    texto VARCHAR(500),
    tipoContenido VARCHAR(10),
    rutamulti VARCHAR(255),
    likes INT DEFAULT 0,
    estado ENUM('publico', 'privado', 'bloqueado') DEFAULT 'publico',
    postdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUsuario) REFERENCES users(idUsuario) ON DELETE CASCADE
);

-- Tabla de likes
CREATE TABLE likes (
    idLike INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    idPublicacion INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (idUsuario, idPublicacion),
    FOREIGN KEY (idUsuario) REFERENCES users(idUsuario) ON DELETE CASCADE,
    FOREIGN KEY (idPublicacion) REFERENCES publicaciones(idPublicacion) ON DELETE CASCADE
);

-- Insertar usuario admin de prueba
INSERT INTO users (Nombre, email, username, contrasena, rol, pais)
VALUES ('Admin', 'admin@mundialfan.com', 'admin', SHA2('admin123', 256), 'admin', 'México');

-- Insertar usuario normal de prueba
INSERT INTO users (Nombre, email, username, contrasena, rol, pais)
VALUES ('Izaak', 'cesarisaac2004@gmail.com', 'izaak', SHA2('1442', 256), 'usuario', 'México');

-- Insertar publicación de prueba
INSERT INTO publicaciones (idUsuario, texto, tipoContenido, rutamulti)
VALUES (2, 'Esta es una publicación de prueba del mundial', 'imagen', 'imagenes/balon.png');

-- Índices para mejor rendimiento
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_username ON users(username);
CREATE INDEX idx_usuario_publicacion ON publicaciones(idUsuario);
CREATE INDEX idx_usuario_like ON likes(idUsuario);