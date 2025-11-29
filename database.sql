CREATE
DATABASE IF NOT EXISTS preguntados;
USE
preguntados;

--
-- Base de datos: `preguntados`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios`
(
    `id`               int(11) NOT NULL,
    `nombre`           varchar(100) NOT NULL,
    `apellido`         varchar(100) NOT NULL,
    `anio_nacimiento` year(4) NOT NULL,
    `sexo`             enum('Masculino','Femenino','Prefiero no cargarlo') DEFAULT 'Prefiero no cargarlo',
    `pais_ciudad`      varchar(150) DEFAULT NULL,
    `email`            varchar(150) NOT NULL,
    `usuario`          varchar(100) NOT NULL,
    `contrasenia_hash` varchar(255) NOT NULL,
    `foto_perfil`      varchar(255) DEFAULT NULL,
    `creado_en`        timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `anio_nacimiento`, `sexo`, `pais_ciudad`, `email`, `usuario`,
                        `contrasenia_hash`, `foto_perfil`, `creado_en`)
VALUES (1, 'Sebastian Angel', 'Diaz', '2000', 'Masculino', 'Buenos Aires', 'diaz-sebastian@hotmail.com', 'sebas',
        '$2y$10$UqBShhO2TXTvPfyOSObTvOPO4FUBnCQZ8Ayo1v0.zAl77R4Ggo7aa', 'uploads_perfiles/68f570363c0f9.png',
        '2025-10-19 23:11:50'),
       (2, 'Thais', 'Mairotti', '2004', 'Femenino', 'Buenos Aires', 'tati@gmail.com', 'tati',
        '$2y$10$h6eXUDUOaSNBCzkvcUGZFu6oJ7ciJ6ViASfpr4K2HcHLkEPiDUdzi', 'uploads_perfiles/68f68f3b2bcce.webp',
        '2025-10-20 19:36:27'),
       (3, 'nahuel', 'tacacho', '2002', 'Masculino', 'argentina', 'tacachoguille@gmail.com', 'nahuel',
        '$2y$10$U5eJXsvX4xaPwze2EG0SdOfzWo5yntL7D20xqqqdo6fbBPb8xI5aC', NULL, '2025-10-20 23:16:17');

-- --------------------------------------------------------

--
-- Indices de la tabla `usuarios`
--

ALTER TABLE `usuarios`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

-- --------------------------------------------------------

--
-- AUTO_INCREMENT de la tabla `usuarios`
--

ALTER TABLE `usuarios`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria`
(
    `id`     int(11) NOT NULL,
    `nombre` varchar(50) NOT NULL,
    `color`  varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Indices de la tabla `categoria`
--

ALTER TABLE `categoria`
    ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

--
-- AUTO_INCREMENT de la tabla `categoria`
--

ALTER TABLE `categoria`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Agregado de columna puntos para la tabla `usuarios`
--

ALTER TABLE usuarios
    ADD COLUMN puntos INT DEFAULT 0;

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO categoria (id, nombre, color)
VALUES (1, 'Ciencia', '#31C950'),
       (2, 'Deportes', '#FF692A'),
       (3, 'Historia', '#FDC745'),
       (4, 'Geografía', '#51A2FF');

-- --------------------------------------------------------

--
-- Agregado de columna partidas_jugadas a la tabla `usuarios`
--
--
-- ALTER TABLE usuarios
--     ADD COLUMN partidas_jugadas INT DEFAULT 0;

-- --------------------------------------------------------

--
-- Creo partidas_usuario para guardar los datos de las partidas por usuarios y verlo en mi perfil
--

-- CREATE TABLE partidas_usuario
-- (
--     id                  INT AUTO_INCREMENT PRIMARY KEY,
--     usuario             VARCHAR(100) NOT NULL,
--     pregunta_id         INT          NOT NULL,
--     respondida_correcta BOOLEAN      NOT NULL,
--     fecha               DATETIME DEFAULT CURRENT_TIMESTAMP
-- );

-- Inserto datos para test en mi perfil

-- INSERT INTO partidas_usuario (usuario, pregunta_id, respondida_correcta)
-- VALUES ('sebas', 1, 1),
--        ('sebas', 2, 1),
--        ('sebas', 3, 0),
--        ('sebas', 4, 1),
--        ('sebas', 5, 0),
--        ('sebas', 6, 1),
--        ('sebas', 7, 0),
--        ('sebas', 8, 1),
--        ('tati', 1, 0),
--        ('tati', 2, 1),
--        ('nahuel', 3, 0);

-- Sumo puntos por lo mismo

-- UPDATE usuarios
-- SET puntos = 50
-- WHERE usuario = 'sebas';
-- UPDATE usuarios
-- SET puntos = 10
-- WHERE usuario = 'tati';
-- UPDATE usuarios
-- SET puntos = 0
-- WHERE usuario = 'nahuel';

-- --------------------------------------------------------

--
-- Agregado de columna rol a la tabla `usuarios`
--

ALTER TABLE usuarios
    ADD COLUMN rol ENUM('jugador', 'editor', 'administrador') DEFAULT 'jugador';

-- --------------------------------------------------------

--
-- Creado de tabla `preguntas`
--

CREATE TABLE preguntas
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id   INT(11) NOT NULL,
    pregunta       TEXT         NOT NULL,
    opcion_a       VARCHAR(255) NOT NULL,
    opcion_b       VARCHAR(255) NOT NULL,
    opcion_c       VARCHAR(255) NOT NULL,
    opcion_d       VARCHAR(255) NOT NULL,
    correcta       CHAR(1)      NOT NULL,
    veces_vista    INT(50) DEFAULT 0 NOT NULL,
    veces_acertada INT(50) DEFAULT 0 NOT NULL,
    CONSTRAINT fk_preguntas_categoria FOREIGN KEY (categoria_id) REFERENCES categoria (id)
);

-- Preguntas Geografia

INSERT INTO preguntas (categoria_id, pregunta, opcion_a, opcion_b, opcion_c, opcion_d, correcta)
VALUES ('4', '¿Cuál es el río más largo del mundo?', 'Amazonas', 'Nilo', 'Yangtsé', 'Misisipi', 'A'),
       ('4', '¿En qué país se encuentra la Torre Eiffel?', 'Italia', 'Alemania', 'Francia', 'España', 'C'),
       ('4', '¿Cuál es la capital de Australia?', 'Sídney', 'Melbourne', 'Canberra', 'Perth', 'C'),
       ('4', '¿Qué océano es el más grande?', 'Atlántico', 'Índico', 'Pacífico', 'Ártico', 'C'),
       ('4', '¿En qué continente está Egipto?', 'Asia', 'Europa', 'África', 'Oceanía', 'C'),
       ('4', '¿Qué país tiene mayor población?', 'India', 'China', 'Estados Unidos', 'Indonesia', 'A'),
       ('4', '¿Dónde está el Monte Everest?', 'China', 'Nepal', 'Tíbet', 'India', 'B'),
       ('4', '¿Capital de Canadá?', 'Toronto', 'Ottawa', 'Vancouver', 'Montreal', 'B'),
       ('4', '¿Qué país tiene forma de bota?', 'Chile', 'Portugal', 'Italia', 'Grecia', 'C'),
       ('4', '¿Capital de Japón?', 'Osaka', 'Kioto', 'Tokio', 'Nara', 'C'),
       ('4', '¿Cuál es el país más grande del mundo?', 'China', 'Canadá', 'Estados Unidos', 'Rusia', 'D'),
       ('4', '¿Cuál es el desierto más grande?', 'Sahara', 'Gobi', 'Atacama', 'Kalahari', 'A'),
       ('4', '¿En qué país queda Machu Picchu?', 'México', 'Perú', 'Colombia', 'Bolivia', 'B'),
       ('4', '¿Qué país tiene más islas?', 'España', 'Suecia', 'Japón', 'Filipinas', 'B'),
       ('4', '¿Cuál es el mar más salado del mundo?', 'Mar Rojo', 'Mar Muerto', 'Mar Caspio', 'Mar Egeo', 'B'),
       ('4', '¿Cuál es la capital de Brasil?', 'Río de Janeiro', 'São Paulo', 'Brasilia', 'Salvador', 'C'),
       ('4', '¿Qué país está entre México y Guatemala?', 'Belice', 'Honduras', 'Cuba', 'Panamá', 'A'),
       ('4', '¿En qué país se encuentra el río Danubio?', 'Alemania', 'España', 'Francia', 'Italia', 'A'),
       ('4', '¿Cuál es la capital de Islandia?', 'Reikiavik', 'Oslo', 'Copenhague', 'Estocolmo', 'A'),
       ('4', '¿Cuál es el lago más grande del mundo?', 'Titicaca', 'Baikal', 'Caspio', 'Victoria', 'C');


-- Preguntas Ciencia

INSERT INTO preguntas (categoria_id, pregunta, opcion_a, opcion_b, opcion_c, opcion_d, correcta)
VALUES ('1', '¿Cuál es el planeta más grande del sistema solar?', 'Tierra', 'Júpiter', 'Saturno', 'Urano', 'B'),
       ('1', '¿Qué gas respiramos principalmente?', 'Nitrógeno', 'Oxígeno', 'Helio', 'Dióxido de carbono', 'A'),
       ('1', '¿Qué órgano bombea sangre?', 'Pulmón', 'Corazón', 'Hígado', 'Riñón', 'B'),
       ('1', '¿Cuál es el metal más ligero?', 'Plata', 'Litio', 'Sodio', 'Aluminio', 'B'),
       ('1', '¿Cuántos huesos tiene el cuerpo humano adulto?', '106', '206', '306', '406', 'B'),
       ('1', '¿Qué célula transporta oxígeno?', 'Neurona', 'Glóbulo rojo', 'Plaqueta', 'Glóbulo blanco', 'B'),
       ('1', '¿Cuál es la velocidad de la luz?', '300 km/s', '300.000 km/s', '30.000 km/s', '3000 km/s', 'B'),
       ('1', '¿Qué vitamina produce el sol?', 'Vitamina C', 'Vitamina D', 'Vitamina A', 'Vitamina K', 'B'),
       ('1', '¿Cuál es el ácido del estómago?', 'Ácido láctico', 'Ácido clorhídrico', 'Ácido úrico',
        'Ácido acético', 'B'),
       ('1', '¿Cuál es el planeta rojo?', 'Mercurio', 'Venus', 'Marte', 'Saturno', 'C'),
       ('1', '¿Qué animal pone huevos?', 'Gato', 'Perro', 'Gallina', 'Cabra', 'C'),
       ('1', '¿Cuál es el órgano más grande del cuerpo?', 'Cerebro', 'Hígado', 'Piel', 'Intestino', 'C'),
       ('1', '¿Qué tipo de sangre es donante universal?', 'A+', 'B+', 'AB+', 'O-', 'D'),
       ('1', '¿Cuál es la unidad básica de la vida?', 'Tejido', 'Célula', 'Molécula', 'Átomo', 'B'),
       ('1', '¿Cuál es el animal más rápido en tierra?', 'León', 'Guepardo', 'Tigre', 'Lobo', 'B'),
       ('1', '¿Qué fuerza nos mantiene en la tierra?', 'Electricidad', 'Inercia', 'Gravedad', 'Magnetismo', 'C'),
       ('1', '¿Qué gas necesitan las plantas para fotosíntesis?', 'CO2', 'O2', 'H2', 'N2', 'A'),
       ('1', '¿Cuál es el elemento químico H?', 'Hidrógeno', 'Helio', 'Mercurio', 'Hafnio', 'A'),
       ('1', '¿Cuántos planetas tiene el sistema solar?', '7', '8', '9', '6', 'B'),
       ('1', '¿Cuál es el órgano que filtra la sangre?', 'Riñón', 'Pulmón', 'Estómago', 'Intestino', 'A');


-- Preguntas Historia

INSERT INTO preguntas (categoria_id, pregunta, opcion_a, opcion_b, opcion_c, opcion_d, correcta)
VALUES ('3', '¿Quién descubrió América?', 'Magallanes', 'Cristóbal Colón', 'Vespucci', 'Pizarro', 'B'),
       ('3', '¿En qué año cayó el Imperio Romano?', '476 d.C.', '1066 d.C.', '1492 d.C.', '320 d.C.', 'A'),
       ('3', '¿Quién fue el primer presidente de EE.UU.?', 'Lincoln', 'Washington', 'Jefferson', 'Adams', 'B'),
       ('3', '¿Qué civilización construyó las pirámides?', 'Romanos', 'Griegos', 'Egipcios', 'Mayas', 'C'),
       ('3', '¿Qué guerra fue entre EEUU y la URSS?', 'Guerra fría', 'Primera guerra', 'Segunda guerra',
        'Guerra napoleónica', 'A'),
       ('3', '¿Quién fue el líder nazi?', 'Lenin', 'Mussolini', 'Hitler', 'Stalin', 'C'),
       ('3', '¿Qué imperio construyó Machu Picchu?', 'Azteca', 'Maya', 'Inca', 'Olmeca', 'C'),
       ('3', '¿Cuál fue la primera civilización?', 'Egipto', 'Mesopotamia', 'Roma', 'Grecia', 'B'),
       ('3', '¿Qué barco se hundió en 1912?', 'Titanic', 'Britannic', 'Lusitania', 'Victoria', 'A'),
       ('3', '¿Quién escribió la Ilíada?', 'Sófocles', 'Homero', 'Platón', 'Aristóteles', 'B'),
       ('3', '¿Quién liberó Argentina?', 'San Martín', 'Belgrano', 'Sarmiento', 'Rosas', 'A'),
       ('3', '¿Qué revolución fue en 1789?', 'Industrial', 'Rusa', 'Francesa', 'China', 'C'),
       ('3', '¿Quién inventó la bombilla?', 'Edison', 'Tesla', 'Newton', 'Einstein', 'A'),
       ('3', '¿Quién fue Cleopatra?', 'Reina romana', 'Reina egipcia', 'Reina griega', 'Reina persa', 'B'),
       ('3', '¿Qué imperio usaba samuráis?', 'China', 'Japón', 'Mongolia', 'Corea', 'B'),
       ('3', '¿Qué país inició la Primera Guerra Mundial?', 'Serbia', 'Alemania', 'Austria-Hungría', 'Francia',
        'C'),
       ('3', '¿Quién conquistó gran parte del mundo con su ejército?', 'Julio César', 'Alejandro Magno',
        'Napoleón', 'Aníbal', 'B'),
       ('3', '¿Qué muro cayó en 1989?', 'Muro de París', 'Muro de Tokio', 'Muro de Berlín', 'Muro de Moscú',
        'C'),
       ('3', '¿Qué civilización creó el calendario solar?', 'Aztecas', 'Mayas', 'Incas', 'Vikingos', 'B');


-- Preguntas Deportes

INSERT INTO preguntas (categoria_id, pregunta, opcion_a, opcion_b, opcion_c, opcion_d, correcta)
VALUES ('2', '¿Cuántos jugadores hay en un equipo de fútbol?', '9', '10', '11', '12', 'C'),
       ('2', '¿Quién ganó el Mundial 2022?', 'Brasil', 'Francia', 'Argentina', 'Alemania', 'C'),
       ('2', '¿Qué deporte practica Messi?', 'Básquet', 'Fútbol', 'Tenis', 'Rugby', 'B'),
       ('2', '¿Dónde se originó el judo?', 'China', 'Corea', 'Japón', 'Tailandia', 'C'),
       ('2', '¿Cuántos sets se juegan en tenis?', '3 o 5', '2', '4', '6', 'A'),
       ('2', '¿Qué deporte usa tabla y olas?', 'Handball', 'Surf', 'Esgrima', 'Hockey', 'B'),
       ('2', '¿Quién tiene más títulos de Fórmula 1?', 'Senna', 'Hamilton', 'Vettel', 'Schumacher', 'B'),
       ('2', '¿Qué selección ganó más mundiales?', 'Brasil', 'Alemania', 'Italia', 'Argentina', 'A'),
       ('2', '¿Quién es el “mejor basquetbolista de la historia”?', 'Jordan', 'Kobe', 'LeBron', 'Curry', 'A'),
       ('2', '¿Qué país inventó el rugby?', 'Australia', 'Irlanda', 'Inglaterra', 'Estados Unidos', 'C'),
       ('2', '¿Qué deporte se juega en Wimbledon?', 'Tenis', 'Fútbol', 'Golf', 'Hockey', 'A'),
       ('2', '¿Qué corredor fue apodado “Bolt”?', 'Tyson Gay', 'Usain Bolt', 'Mo Farah', 'Gatlin', 'B'),
       ('2', '¿Qué deporte usa arco y flecha?', 'Tiro', 'Esgrima', 'Arquería', 'Triatlón', 'C'),
       ('2', '¿Qué seleccionador ganó la Copa América 2021?', 'Scaloni', 'Bielsa', 'Sampaoli', 'Martino', 'A'),
       ('2', '¿En qué deporte se usa tatami?', 'Natación', 'Judo', 'Ciclismo', 'Boxeo', 'B'),
       ('2', '¿Cuál es el deporte más popular del mundo?', 'Tenis', 'Fútbol', 'Críquet', 'Rugby', 'B'),
       ('2', '¿Qué país organiza el Tour de Francia?', 'España', 'Italia', 'Francia', 'Alemania', 'C'),
       ('2', '¿Quién ganó 6 anillos con Chicago Bulls?', 'Jordan', 'Pippen', 'Rodman', 'Kerr', 'A'),
       ('2', '¿Qué boxeador decía “soy el más grande”?', 'Ali', 'Tyson', 'Frazier', 'Pacquiao', 'A'),
       ('2', '¿Qué deporte combina correr, nadar y pedalear?', 'CrossFit', 'Triatlón', 'Pentatlón', 'Ironman',
        'B');

-- --------------------------------------------------------

--
-- Agregado de tabla `reportes`
--

CREATE TABLE reportes
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT  NOT NULL,
    mensaje     TEXT NOT NULL,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- --------------------------------------------------------

--
-- Volcado de datos de prueba a la tabla `reportes`
--

INSERT INTO reportes (pregunta_id, mensaje)
VALUES (1, 'La opción correcta parece estar mal, revisen esta pregunta.'),
       (2, 'Hay un error de ortografía en el enunciado.'),
       (3, 'La pregunta es confusa, no se entiende bien qué se está preguntando.');


-- --------------------------------------------------------

--
-- Agregado de tabla `sugerencias`
--

CREATE TABLE sugerencias
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    pregunta     VARCHAR(255) NOT NULL,
    categoria_id INT          NOT NULL,
    opcion_a     VARCHAR(255) NOT NULL,
    opcion_b     VARCHAR(255) NOT NULL,
    opcion_c     VARCHAR(255) NOT NULL,
    opcion_d     VARCHAR(255) NOT NULL,
    correcta     CHAR(1)      NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categoria (id)
);


-- --------------------------------------------------------

--
-- Volcado de datos de prueba a la tabla `sugerencias`
--

INSERT INTO sugerencias (pregunta, categoria_id, opcion_a, opcion_b, opcion_c, opcion_d, correcta)
VALUES ('¿Primera pregunta de sugerencia?', 1, 'Sugerencia a', 'Sugerencia b', 'Sugerencia c', 'Sugerencia d', 'B'),
       ('¿Segunda pregunta de sugerencia?', 2, 'Sugerencia a', 'Sugerencia b', 'Sugerencia c', 'Sugerencia d', 'A'),
       ('¿Tercera pregunta de sugerencia?', 3, 'Sugerencia a', 'Sugerencia b', 'Sugerencia c', 'Sugerencia d', 'B');



-- --------------------------------------------------------

--
-- Creo la tabla 'partida'
--

CREATE TABLE partida
(
    id                 INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id         INT,
    puntos             INT      DEFAULT 0,
    estado             ENUM('jugando','finalizada','abandonada') DEFAULT 'jugando',
    inicio             DATETIME DEFAULT CURRENT_TIMESTAMP,
    fin                DATETIME NULL,
    tiempo_total_seg   INT NULL, -- tiempo jugado o límite
    last_activity      DATETIME DEFAULT CURRENT_TIMESTAMP,
    pregunta_actual_id INT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id),
    FOREIGN KEY (pregunta_actual_id) REFERENCES preguntas (id)
);

-- --------------------------------------------------------

--
-- Creo la tabla 'preguntas_respondidas'
--

CREATE TABLE preguntas_respondidas
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT NOT NULL,
    pregunta_id INT NOT NULL,
    fecha       DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (usuario_id, pregunta_id),

    FOREIGN KEY (usuario_id) REFERENCES usuarios (id),
    FOREIGN KEY (pregunta_id) REFERENCES preguntas (id)
);

-- --------------------------------------------------------

--
-- Agrego columnas inicio_pregunta y timepo_limite_seg a la tabla 'partida'
--

ALTER TABLE partida
    ADD COLUMN inicio_pregunta DATETIME NULL,
    ADD COLUMN tiempo_limite_seg INT DEFAULT 35;

-- --------------------------------------------------------

--
-- Agrego columnas inicio_pregunta y timepo_limite_seg a la tabla 'partida'
--

ALTER TABLE partida
    ADD COLUMN ruleta_mostrada TINYINT(1) DEFAULT 0;
