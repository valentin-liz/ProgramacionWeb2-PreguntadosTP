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
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE `pregunta`
(
    `id`           int(11) NOT NULL,
    `enunciado`    text NOT NULL,
    `categoria_id` int(11) NOT NULL,
    `dificultad`   tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta`
(
    `id`           int(11) NOT NULL,
    `pregunta_id`  int(11) NOT NULL,
    `texto_opcion` varchar(255) NOT NULL,
    `correcta`     tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Indices de la tabla `categoria`
--

ALTER TABLE `categoria`
    ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

--
-- Indices de la tabla `pregunta`
--

ALTER TABLE `pregunta`
    ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

-- --------------------------------------------------------

--
-- Indices de la tabla `respuesta`
--

ALTER TABLE `respuesta`
    ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

-- --------------------------------------------------------

--
-- AUTO_INCREMENT de la tabla `categoria`
--

ALTER TABLE `categoria`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- AUTO_INCREMENT de la tabla `pregunta`
--

ALTER TABLE `pregunta`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Filtros para la tabla `pregunta`
--

ALTER TABLE `pregunta`
    ADD CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

--
-- Filtros para la tabla `respuesta`
--

ALTER TABLE `respuesta`
    ADD CONSTRAINT `respuesta_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `pregunta` (`id`) ON DELETE CASCADE;
COMMIT;

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
-- Cambiar 'correcta' de tinyint a boolean para la tabla `pregunta`
--

ALTER TABLE respuesta
    MODIFY COLUMN correcta BOOLEAN DEFAULT FALSE;


-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `pregunta`
--

-- 🧪 CATEGORÍA: CIENCIA
INSERT INTO pregunta (id, enunciado, categoria_id, dificultad)
VALUES (1, '¿Cuál es el planeta más grande del sistema solar?', 1, 1);

INSERT INTO respuesta (id, pregunta_id, texto_opcion, correcta)
VALUES
    (1, 1, 'Júpiter', TRUE),
    (2, 1, 'Saturno', FALSE),
    (3, 1, 'Marte', FALSE),
    (4, 1, 'Tierra', FALSE);

-- ⚽ CATEGORÍA: DEPORTES
INSERT INTO pregunta (id, enunciado, categoria_id, dificultad)
VALUES (2, '¿Cuántos jugadores tiene un equipo de fútbol en el campo?', 2, 1);

INSERT INTO respuesta (id, pregunta_id, texto_opcion, correcta)
VALUES
    (5, 2, '11', TRUE),
    (6, 2, '10', FALSE),
    (7, 2, '12', FALSE),
    (8, 2, '9', FALSE);

-- 🏛️ CATEGORÍA: HISTORIA
INSERT INTO pregunta (id, enunciado, categoria_id, dificultad)
VALUES (3, '¿En qué año comenzó la Primera Guerra Mundial?', 3, 1);

INSERT INTO respuesta (id, pregunta_id, texto_opcion, correcta)
VALUES
    (9, 3, '1914', TRUE),
    (10, 3, '1939', FALSE),
    (11, 3, '1920', FALSE),
    (12, 3, '1905', FALSE);

-- 🌍 CATEGORÍA: GEOGRAFÍA
INSERT INTO pregunta (id, enunciado, categoria_id, dificultad)
VALUES (4, '¿Cuál es el río más largo del mundo?', 4, 1);

INSERT INTO respuesta (id, pregunta_id, texto_opcion, correcta)
VALUES
    (13, 4, 'Nilo', TRUE),
    (14, 4, 'Amazonas', FALSE),
    (15, 4, 'Yangtsé', FALSE),
    (16, 4, 'Misisipi', FALSE);

-- --------------------------------------------------------

--
-- Agregado de columna partidas_jugadas a la tabla `usuarios`
--

ALTER TABLE usuarios
    ADD COLUMN partidas_jugadas INT DEFAULT 0;

-- Base para las preguntas
CREATE TABLE preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(50) NOT NULL,
    pregunta TEXT NOT NULL,
    opcion_a VARCHAR(255) NOT NULL,
    opcion_b VARCHAR(255) NOT NULL,
    opcion_c VARCHAR(255) NOT NULL,
    opcion_d VARCHAR(255) NOT NULL,
    correcta CHAR(1) NOT NULL
);

--Preguntas Geografia

INSERT INTO preguntas (categoria, pregunta, opcion_a, opcion_b, opcion_c, opcion_d, correcta) VALUES
('Geografía','¿Cuál es el río más largo del mundo?','Amazonas','Nilo','Yangtsé','Misisipi','A'),
('Geografía','¿En qué país se encuentra la Torre Eiffel?','Italia','Alemania','Francia','España','C'),
('Geografía','¿Cuál es la capital de Australia?','Sídney','Melbourne','Canberra','Perth','C'),
('Geografía','¿Qué océano es el más grande?','Atlántico','Índico','Pacífico','Ártico','C'),
('Geografía','¿En qué continente está Egipto?','Asia','Europa','África','Oceanía','C'),
('Geografía','¿Qué país tiene mayor población?','India','China','Estados Unidos','Indonesia','A'),
('Geografía','¿Dónde está el Monte Everest?','China','Nepal','Tíbet','India','B'),
('Geografía','¿Capital de Canadá?','Toronto','Ottawa','Vancouver','Montreal','B'),
('Geografía','¿Qué país tiene forma de bota?','Chile','Portugal','Italia','Grecia','C'),
('Geografía','¿Capital de Japón?','Osaka','Kioto','Tokio','Nara','C'),
('Geografía','¿Cuál es el país más grande del mundo?','China','Canadá','Estados Unidos','Rusia','D'),
('Geografía','¿Cuál es el desierto más grande?','Sahara','Gobi','Atacama','Kalahari','A'),
('Geografía','¿En qué país queda Machu Picchu?','México','Perú','Colombia','Bolivia','B'),
('Geografía','¿Qué país tiene más islas?','España','Suecia','Japón','Filipinas','B'),
('Geografía','¿Cuál es el mar más salado del mundo?','Mar Rojo','Mar Muerto','Mar Caspio','Mar Egeo','B'),
('Geografía','¿Cuál es la capital de Brasil?','Río de Janeiro','São Paulo','Brasilia','Salvador','C'),
('Geografía','¿Qué país está entre México y Guatemala?','Belice','Honduras','Cuba','Panamá','A'),
('Geografía','¿En qué país se encuentra el río Danubio?','Alemania','España','Francia','Italia','A'),
('Geografía','¿Cuál es la capital de Islandia?','Reikiavik','Oslo','Copenhague','Estocolmo','A'),
('Geografía','¿Cuál es el lago más grande del mundo?','Titicaca','Baikal','Caspio','Victoria','C');

--Preguntas Ciencia

INSERT INTO preguntas (categoria, pregunta, opcion_a, opcion_b, opcion_c, opcion_d, correcta) VALUES
('Ciencia','¿Cuál es el planeta más grande del sistema solar?','Tierra','Júpiter','Saturno','Urano','B'),
('Ciencia','¿Qué gas respiramos principalmente?','Nitrógeno','Oxígeno','Helio','Dióxido de carbono','A'),
('Ciencia','¿Qué órgano bombea sangre?','Pulmón','Corazón','Hígado','Riñón','B'),
('Ciencia','¿Cuál es el metal más ligero?','Plata','Litio','Sodio','Aluminio','B'),
('Ciencia','¿Cuántos huesos tiene el cuerpo humano adulto?','106','206','306','406','B'),
('Ciencia','¿Qué célula transporta oxígeno?','Neurona','Glóbulo rojo','Plaqueta','Glóbulo blanco','B'),
('Ciencia','¿Cuál es la velocidad de la luz?','300 km/s','300.000 km/s','30.000 km/s','3000 km/s','B'),
('Ciencia','¿Qué vitamina produce el sol?','Vitamina C','Vitamina D','Vitamina A','Vitamina K','B'),
('Ciencia','¿Cuál es el ácido del estómago?','Ácido láctico','Ácido clorhídrico','Ácido úrico','Ácido acético','B'),
('Ciencia','¿Cuál es el planeta rojo?','Mercurio','Venus','Marte','Saturno','C'),
('Ciencia','¿Qué animal pone huevos?','Gato','Perro','Gallina','Cabra','C'),
('Ciencia','¿Cuál es el órgano más grande del cuerpo?','Cerebro','Hígado','Piel','Intestino','C'),
('Ciencia','¿Qué tipo de sangre es donante universal?','A+','B+','AB+','O-','D'),
('Ciencia','¿Cuál es la unidad básica de la vida?','Tejido','Célula','Molécula','Átomo','B'),
('Ciencia','¿Cuál es el animal más rápido en tierra?','León','Guepardo','Tigre','Lobo','B'),
('Ciencia','¿Qué fuerza nos mantiene en la tierra?','Electricidad','Inercia','Gravedad','Magnetismo','C'),
('Ciencia','¿Qué gas necesitan las plantas para fotosíntesis?','CO2','O2','H2','N2','A'),
('Ciencia','¿Cuál es el elemento químico H?','Hidrógeno','Helio','Mercurio','Hafnio','A'),
('Ciencia','¿Cuántos planetas tiene el sistema solar?','7','8','9','6','B'),
('Ciencia','¿Cuál es el órgano que filtra la sangre?','Riñón','Pulmón','Estómago','Intestino','A');


--Preguntas Historia

INSERT INTO preguntas (categoria, pregunta, opcion_a, opcion_b, opcion_c, opcion_d, correcta) VALUES
('Historia','¿Quién descubrió América?','Magallanes','Cristóbal Colón','Vespucci','Pizarro','B'),
('Historia','¿En qué año cayó el Imperio Romano?','476 d.C.','1066 d.C.','1492 d.C.','320 d.C.','A'),
('Historia','¿Quién fue el primer presidente de EE.UU.?','Lincoln','Washington','Jefferson','Adams','B'),
('Historia','¿Qué civilización construyó las pirámides?','Romanos','Griegos','Egipcios','Mayas','C'),
('Historia','¿Qué guerra fue entre EEUU y la URSS?','Guerra fría','Primera guerra','Segunda guerra','Guerra napoleónica','A'),
('Historia','¿Quién fue el líder nazi?','Lenin','Mussolini','Hitler','Stalin','C'),
('Historia','¿Qué imperio construyó Machu Picchu?','Azteca','Maya','Inca','Olmeca','C'),
('Historia','¿Cuál fue la primera civilización?','Egipto','Mesopotamia','Roma','Grecia','B'),
('Historia','¿Qué barco se hundió en 1912?','Titanic','Britannic','Lusitania','Victoria','A'),
('Historia','¿Quién escribió la Ilíada?','Sófocles','Homero','Platón','Aristóteles','B'),
('Historia','¿Quién liberó Argentina?','San Martín','Belgrano','Sarmiento','Rosas','A'),
('Historia','¿Qué revolución fue en 1789?','Industrial','Rusa','Francesa','China','C'),
('Historia','¿Quién inventó la bombilla?','Edison','Tesla','Newton','Einstein','A'),
('Historia','¿Quién fue Cleopatra?','Reina romana','Reina egipcia','Reina griega','Reina persa','B'),
('Historia','¿Qué imperio usaba samuráis?','China','Japón','Mongolia','Corea','B'),
('Historia','¿Qué país inició la Primera Guerra Mundial?','Serbia','Alemania','Austria-Hungría','Francia','C'),
('Historia','¿Quién conquistó gran parte del mundo con su ejército?','Julio César','Alejandro Magno','Napoleón','Aníbal','B'),
('Historia','¿Qué muro cayó en 1989?','Muro de París','Muro de Tokio','Muro de Berlín','Muro de Moscú','C'),
('Historia','¿Qué civilización creó el calendario solar?','Aztecas','Mayas','Incas','Vikingos','B');


--Preguntas Deportes

INSERT INTO preguntas (categoria, pregunta, opcion_a, opcion_b, opcion_c, opcion_d, correcta) VALUES
('Deportes','¿Cuántos jugadores hay en un equipo de fútbol?','9','10','11','12','C'),
('Deportes','¿Quién ganó el Mundial 2022?','Brasil','Francia','Argentina','Alemania','C'),
('Deportes','¿Qué deporte practica Messi?','Básquet','Fútbol','Tenis','Rugby','B'),
('Deportes','¿Dónde se originó el judo?','China','Corea','Japón','Tailandia','C'),
('Deportes','¿Cuántos sets se juegan en tenis?','3 o 5','2','4','6','A'),
('Deportes','¿Qué deporte usa tabla y olas?','Handball','Surf','Esgrima','Hockey','B'),
('Deportes','¿Quién tiene más títulos de Fórmula 1?','Senna','Hamilton','Vettel','Schumacher','B'),
('Deportes','¿Qué selección ganó más mundiales?','Brasil','Alemania','Italia','Argentina','A'),
('Deportes','¿Quién es el “mejor basquetbolista de la historia”?','Jordan','Kobe','LeBron','Curry','A'),
('Deportes','¿Qué país inventó el rugby?','Australia','Irlanda','Inglaterra','Estados Unidos','C'),
('Deportes','¿Qué deporte se juega en Wimbledon?','Tenis','Fútbol','Golf','Hockey','A'),
('Deportes','¿Qué corredor fue apodado “Bolt”?','Tyson Gay','Usain Bolt','Mo Farah','Gatlin','B'),
('Deportes','¿Qué deporte usa arco y flecha?','Tiro','Esgrima','Arquería','Triatlón','C'),
('Deportes','¿Qué seleccionador ganó la Copa América 2021?','Scaloni','Bielsa','Sampaoli','Martino','A'),
('Deportes','¿En qué deporte se usa tatami?','Natación','Judo','Ciclismo','Boxeo','B'),
('Deportes','¿Cuál es el deporte más popular del mundo?','Tenis','Fútbol','Críquet','Rugby','B'),
('Deportes','¿Qué país organiza el Tour de Francia?','España','Italia','Francia','Alemania','C'),
('Deportes','¿Quién ganó 6 anillos con Chicago Bulls?','Jordan','Pippen','Rodman','Kerr','A'),
('Deportes','¿Qué boxeador decía “soy el más grande”?','Ali','Tyson','Frazier','Pacquiao','A'),
('Deportes','¿Qué deporte combina correr, nadar y pedalear?','CrossFit','Triatlón','Pentatlón','Ironman','B');
