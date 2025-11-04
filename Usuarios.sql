

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

-- ===============================
-- TABLA: CAMPEONATOS
-- ===============================
CREATE TABLE campeonatos (
    idCampeonato INT PRIMARY KEY AUTO_INCREMENT,
    anio YEAR NOT NULL,
    paisSede VARCHAR(100) NOT NULL,
    bandera VARCHAR(255),
    campeon VARCHAR(100),
    subcampeon VARCHAR(100),
    golesTotales INT,
    equiposParticipantes INT,
    descripcion TEXT
);

-- Datos iniciales
INSERT INTO campeonatos (anio, paisSede, bandera, campeon, subcampeon, golesTotales, equiposParticipantes, descripcion) VALUES
(1930, 'Uruguay', 'https://flagicons.lipis.dev/flags/4x3/uy.svg', 'Uruguay', 'Argentina', 70, 13, 'Primera Copa del Mundo organizada por la FIFA.'),
(1934, 'Italia', 'https://flagicons.lipis.dev/flags/4x3/it.svg', 'Italia', 'Checoslovaquia', 70, 16, 'Segunda edición, la primera con formato eliminatorio.'),
(1938, 'Francia', 'https://flagicons.lipis.dev/flags/4x3/fr.svg', 'Italia', 'Hungría', 84, 15, 'Último Mundial antes de la Segunda Guerra Mundial.'),
(1950, 'Brasil', 'https://flagicons.lipis.dev/flags/4x3/br.svg', 'Uruguay', 'Brasil', 88, 13, 'Famoso por el Maracanazo.'),
(1954, 'Suiza', 'https://flagicons.lipis.dev/flags/4x3/ch.svg', 'Alemania', 'Hungría', 140, 16, 'Primera final televisada.'),
(1958, 'Suecia', 'https://flagicons.lipis.dev/flags/4x3/se.svg', 'Brasil', 'Suecia', 126, 16, 'El primer Mundial ganado por Brasil con Pelé.'),
(1962, 'Chile', 'https://flagicons.lipis.dev/flags/4x3/cl.svg', 'Brasil', 'Checoslovaquia', 89, 16, 'Brasil repitió título mundial.'),
(1966, 'Inglaterra', 'https://flagicons.lipis.dev/flags/4x3/gb.svg', 'Inglaterra', 'Alemania', 89, 16, 'Inglaterra ganó en casa.'),
(1970, 'México', 'https://flagicons.lipis.dev/flags/4x3/mx.svg', 'Brasil', 'Italia', 95, 16, 'Pelé consiguió su tercer título mundial.'),
(1978, 'Argentina', 'https://flagicons.lipis.dev/flags/4x3/ar.svg', 'Argentina', 'Holanda', 102, 16, 'Primera Copa del Mundo ganada por Argentina.');

-- ===============================
-- TABLA: EQUIPOS EXITOSOS
-- ===============================
CREATE TABLE equipos_exitosos (
    idEquipo INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    bandera VARCHAR(255),
    titulos INT DEFAULT 0
);

INSERT INTO equipos_exitosos (nombre, bandera, titulos) VALUES
('Brasil', 'https://flagicons.lipis.dev/flags/4x3/br.svg', 5),
('Alemania', 'https://flagicons.lipis.dev/flags/4x3/de.svg', 4),
('Italia', 'https://flagicons.lipis.dev/flags/4x3/it.svg', 4),
('Argentina', 'https://flagicons.lipis.dev/flags/4x3/ar.svg', 3),
('Francia', 'https://flagicons.lipis.dev/flags/4x3/fr.svg', 2),
('Uruguay', 'https://flagicons.lipis.dev/flags/4x3/uy.svg', 2),
('Inglaterra', 'https://flagicons.lipis.dev/flags/4x3/gb.svg', 1),
('España', 'https://flagicons.lipis.dev/flags/4x3/es.svg', 1);

-- ===============================
-- TABLA: JUGADORES DESTACADOS
-- ===============================
CREATE TABLE jugadores_destacados (
    idJugador INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    pais VARCHAR(100),
    logros TEXT
);

INSERT INTO jugadores_destacados (nombre, pais, logros) VALUES
('Pelé', 'Brasil', 'Único jugador con 3 títulos mundiales (1958, 1962, 1970).'),
('Diego Maradona', 'Argentina', 'Líder y campeón del Mundial 1986 con el "Gol del Siglo".'),
('Zinedine Zidane', 'Francia', 'Campeón del Mundo 1998 y subcampeón en 2006.'),
('Ronaldo Nazário', 'Brasil', 'Máximo goleador del Mundial 2002 con 8 goles.'),
('Miroslav Klose', 'Alemania', 'Máximo goleador histórico de los Mundiales (16 goles).'),
('Lionel Messi', 'Argentina', 'Campeón del Mundo 2022 y Balón de Oro del torneo.');
