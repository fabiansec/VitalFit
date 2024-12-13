-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-03-2024 a las 22:55:12
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gimnasio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acl_roles`
--

CREATE TABLE `acl_roles` (
  `cod_acl_role` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `perm1` tinyint(1) NOT NULL DEFAULT 0,
  `perm2` tinyint(1) NOT NULL DEFAULT 0,
  `perm3` tinyint(1) NOT NULL DEFAULT 0,
  `perm4` tinyint(1) NOT NULL DEFAULT 0,
  `perm5` tinyint(1) NOT NULL DEFAULT 0,
  `perm6` tinyint(1) NOT NULL DEFAULT 0,
  `perm7` tinyint(1) NOT NULL DEFAULT 0,
  `perm8` tinyint(1) NOT NULL DEFAULT 0,
  `perm9` tinyint(1) NOT NULL DEFAULT 0,
  `perm10` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `acl_roles`
--

INSERT INTO `acl_roles` (`cod_acl_role`, `nombre`, `perm1`, `perm2`, `perm3`, `perm4`, `perm5`, `perm6`, `perm7`, `perm8`, `perm9`, `perm10`) VALUES
(1, 'administrador', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(2, 'cliente', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acl_usuarios`
--

CREATE TABLE `acl_usuarios` (
  `cod_acl_usuario` int(11) NOT NULL,
  `cod_acl_role` int(11) NOT NULL,
  `nick` varchar(30) NOT NULL,
  `contrasenia` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `acl_usuarios`
--

INSERT INTO `acl_usuarios` (`cod_acl_usuario`, `cod_acl_role`, `nick`, `contrasenia`, `nombre`, `borrado`) VALUES
(1, 1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin', 0),
(2, 2, 'fabian', '644850cb8939d255ed752957b7c4458c52900f75', 'fabian', 0),
(3, 2, 'miguel', '75004f149038473757da0be07ef76dd4a9bdbc8d', 'miguel', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `cod_actividad` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(60) NOT NULL,
  `hora` time NOT NULL,
  `aforo` int(11) NOT NULL,
  `borrado` tinyint(1) NOT NULL,
  `cod_sala` int(11) NOT NULL,
  `cod_categoria` int(11) NOT NULL,
  `foto` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`cod_actividad`, `nombre`, `descripcion`, `hora`, `aforo`, `borrado`, `cod_sala`, `cod_categoria`, `foto`) VALUES
(1, 'Zumba', 'Es una actividad fitness que f', '19:00:00', 26, 0, 1, 1, 'zumba.png'),
(2, 'HipHop', 'Es una forma de expresión artí', '10:00:00', 11, 0, 1, 1, 'hiphop.png'),
(4, 'Kickboxing', 'Es una disciplina de fitness q', '21:20:48', 30, 0, 1, 1, 'kick.png'),
(5, 'Entrenamiento con pesas', 'Es una forma efectiva y dinámi', '21:25:15', 30, 0, 2, 6, 'circuitoP.png'),
(6, 'Levantamiento de pesas', 'Es una forma de entrenamiento ', '21:26:36', 30, 0, 2, 6, 'pesasL.png'),
(7, 'Yoga tradicional', 'Es una antigua disciplina físi', '17:00:00', 7, 0, 1, 1, 'yogaT.png'),
(8, 'Yoga caliente', 'Combina los beneficios terapéu', '19:00:00', 15, 0, 1, 1, 'yogaC.png'),
(9, 'Pilates con maquinas', 'Es una modalidad de ejercicio ', '13:00:00', 30, 0, 3, 3, 'pilates.png'),
(10, 'Natación ', 'Ofrece una amplia gama de bene', '17:00:00', 15, 0, 6, 7, 'natacion.png'),
(11, 'Aerobicos acuaticos', 'Combina movimientos coreografi', '18:30:00', 30, 1, 1, 1, 'aeAcuatico.png'),
(12, 'Futbol sala', 'La velocidad y la destreza son', '09:00:00', 26, 0, 5, 4, 'sala.png'),
(13, 'TaiChi', ' Combina movimientos fluidos y', '13:00:00', 30, 0, 4, 5, 'taichi.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `cod_categoria` int(11) NOT NULL,
  `descripcion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`cod_categoria`, `descripcion`) VALUES
(1, 'Baile'),
(2, 'Spinning'),
(3, 'Yoga'),
(4, 'Grupo'),
(5, 'Relajacion'),
(6, 'Pesas'),
(7, 'Acuaticas');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_actividades`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_actividades` (
`cod_actividad` int(11)
,`nombre` varchar(30)
,`descripcion` varchar(60)
,`hora` time
,`aforo` int(11)
,`borrado` tinyint(1)
,`cod_sala` int(11)
,`cod_categoria` int(11)
,`foto` varchar(30)
,`categoria` varchar(30)
,`sala` varchar(30)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cons_reservas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cons_reservas` (
`cod_usuario` int(11)
,`nombre_usuario` varchar(30)
,`cod_actividad` int(11)
,`nombre_actividad` varchar(30)
,`metodo` int(11)
,`fecha` date
,`hora` time
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `cod_reserva` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL,
  `cod_actividad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `metodo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`cod_reserva`, `cod_usuario`, `cod_actividad`, `fecha`, `metodo`) VALUES
(73, 2, 1, '2024-03-01', 1),
(74, 2, 7, '2024-03-01', 1),
(75, 3, 4, '2024-03-01', 3),
(76, 3, 1, '2024-03-01', 1),
(77, 2, 1, '2024-03-02', 1),
(78, 3, 4, '2024-03-02', 1),
(79, 2, 4, '2024-03-02', 1),
(80, 3, 4, '2024-03-03', 2),
(81, 2, 4, '2024-03-03', 1),
(82, 3, 4, '2024-03-07', 1),
(83, 3, 8, '2024-03-07', 2),
(84, 2, 4, '2024-03-07', 2),
(85, 2, 8, '2024-03-07', 2),
(86, 2, 4, '2024-03-08', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sala`
--

CREATE TABLE `sala` (
  `cod_sala` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `foto` varchar(30) NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `sala`
--

INSERT INTO `sala` (`cod_sala`, `nombre`, `descripcion`, `foto`, `borrado`) VALUES
(1, 'Fitnes', 'Entrenamiento de Fitness', 'defectoF.png', 1),
(2, 'Pesas', 'Entrenamiento con pesas', 'defectoP.png', 1),
(3, 'Yoga', 'Yoga y pilates', 'defectoY.png', 1),
(4, 'Relajacion', 'Clase de relajacion', 'defecto.png', 1),
(5, 'Grupo', 'Deportes en grupo', 'defectoG.png', 1),
(6, 'Acuaticas', 'Clases acuaticas', 'defectoA.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `cod_usuario` int(11) NOT NULL,
  `nick` varchar(30) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`cod_usuario`, `nick`, `nombre`, `borrado`) VALUES
(1, 'admin', 'admin', 0),
(2, 'fabian', 'fabian', 0),
(3, 'miguel', 'miguel', 0);

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_actividades`
--
DROP TABLE IF EXISTS `cons_actividades`;

CREATE VIEW `cons_actividades`  AS SELECT `a`.`cod_actividad` AS `cod_actividad`, `a`.`nombre` AS `nombre`, `a`.`descripcion` AS `descripcion`, `a`.`hora` AS `hora`, `a`.`aforo` AS `aforo`, `a`.`borrado` AS `borrado`, `a`.`cod_sala` AS `cod_sala`, `a`.`cod_categoria` AS `cod_categoria`, `a`.`foto` AS `foto`, `c`.`descripcion` AS `categoria`, `s`.`nombre` AS `sala` FROM ((`actividades` `a` join `categorias` `c` on(`a`.`cod_categoria` = `c`.`cod_categoria`)) join `sala` `s` on(`a`.`cod_sala` = `s`.`cod_sala`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cons_reservas`
--
DROP TABLE IF EXISTS `cons_reservas`;

CREATE VIEW `cons_reservas`  AS SELECT `r`.`cod_usuario` AS `cod_usuario`, `u`.`nick` AS `nombre_usuario`, `r`.`cod_actividad` AS `cod_actividad`, `a`.`nombre` AS `nombre_actividad`, `r`.`metodo` AS `metodo`, `r`.`fecha` AS `fecha`, `a`.`hora` AS `hora` FROM ((`reservas` `r` join `usuarios` `u` on(`r`.`cod_usuario` = `u`.`cod_usuario`)) join `actividades` `a` on(`r`.`cod_actividad` = `a`.`cod_actividad`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acl_roles`
--
ALTER TABLE `acl_roles`
  ADD PRIMARY KEY (`cod_acl_role`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `acl_usuarios`
--
ALTER TABLE `acl_usuarios`
  ADD PRIMARY KEY (`cod_acl_usuario`),
  ADD UNIQUE KEY `nick` (`nick`),
  ADD KEY `fl_acl_roles` (`cod_acl_role`);

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`cod_actividad`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `Fk_cod_sala` (`cod_sala`),
  ADD KEY `Fk_cod_categoria` (`cod_categoria`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`cod_categoria`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`cod_reserva`),
  ADD KEY `Fk_cod_actividad` (`cod_actividad`),
  ADD KEY `Fk_cod_usuario` (`cod_usuario`);

--
-- Indices de la tabla `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`cod_sala`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cod_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acl_roles`
--
ALTER TABLE `acl_roles`
  MODIFY `cod_acl_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `acl_usuarios`
--
ALTER TABLE `acl_usuarios`
  MODIFY `cod_acl_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `cod_actividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `cod_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `cod_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT de la tabla `sala`
--
ALTER TABLE `sala`
  MODIFY `cod_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `cod_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acl_usuarios`
--
ALTER TABLE `acl_usuarios`
  ADD CONSTRAINT `fl_acl_roles` FOREIGN KEY (`cod_acl_role`) REFERENCES `acl_roles` (`cod_acl_role`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `Fk_cod_categoria` FOREIGN KEY (`cod_categoria`) REFERENCES `categorias` (`cod_categoria`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Fk_cod_sala` FOREIGN KEY (`cod_sala`) REFERENCES `sala` (`cod_sala`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `Fk_cod_actividad` FOREIGN KEY (`cod_actividad`) REFERENCES `actividades` (`cod_actividad`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Fk_cod_usuario` FOREIGN KEY (`cod_usuario`) REFERENCES `usuarios` (`cod_usuario`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
