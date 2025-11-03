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



