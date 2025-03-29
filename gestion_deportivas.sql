-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-03-2025 a las 01:02:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_deportiva`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`id_curso`, `nombre_curso`, `descripcion`) VALUES
(2724, 'Tecnologia en Desarrollo de Software', 'TDS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encargado`
--

CREATE TABLE `encargado` (
  `id_encargado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encargado`
--

INSERT INTO `encargado` (`id_encargado`, `id_usuario`, `nombre`, `correo`, `telefono`) VALUES
(4, 15, 'daniel hurtado', 'daniel.hurtado@correo.com', '3125262728'),
(6, 17, 'andre palomino', 'andrea@correo.com', '314156287'),
(7, 22, 'camila', 'camila@gmail.com', '3175578892');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id_estudiante` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo_institucional` varchar(100) NOT NULL,
  `telefono` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id_estudiante`, `id_usuario`, `id_curso`, `nombre`, `correo_institucional`, `telefono`) VALUES
(4, 12, 2724, 'ana sofia', 'ana.sofia@correo.com', '3152672384'),
(5, 13, 2724, 'leidy jhoa cano ', 'leidy.cano@correo.com', '3141526666'),
(6, 14, 2724, 'esteban galvis', 'esteban.galvis@correo.com', '3245151679'),
(9, 21, 2724, 'oliver bueno', 'oliver@gmail.com', '3124567890');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `implemento`
--

CREATE TABLE `implemento` (
  `id_implemento` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL CHECK (`cantidad` >= 0),
  `estado` enum('Disponible','No Disponible') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `implemento`
--

INSERT INTO `implemento` (`id_implemento`, `nombre`, `tipo`, `cantidad`, `estado`) VALUES
(9, 'ping pong', 'mesa', 5, 'Disponible'),
(10, 'raqueta', 'ping pong', 1, 'Disponible'),
(12, 'Futbol', 'Balon', 6, 'Disponible'),
(30, 'baloncesto', 'Balon', 8, 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `id_prestamo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_implemento` int(11) NOT NULL,
  `cantidad` int(50) NOT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `estado` enum('Pendiente','Aceptado','Cancelado') DEFAULT NULL,
  `observaciones_Est` text DEFAULT NULL,
  `observaciones_Generales` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`id_prestamo`, `id_usuario`, `id_implemento`, `cantidad`, `fecha_prestamo`, `fecha_devolucion`, `estado`, `observaciones_Est`, `observaciones_Generales`) VALUES
(2022, 14, 12, 0, '2024-12-09', '2024-12-11', 'Cancelado', 'ya no lo necesito', 'su prestamo ha sido aceptado'),
(2024, 12, 10, 0, '2024-12-11', '2024-12-13', 'Aceptado', 'necesito 2 raquetas', ''),
(2025, 14, 9, 0, '2024-12-11', '2024-12-13', 'Aceptado', 'necesito 1 mesa', ''),
(2026, 17, 30, 0, '2024-12-19', '2024-12-20', 'Aceptado', 'necesita 3 balones', ''),
(2027, 19, 9, 0, '2025-03-02', '2025-03-04', 'Aceptado', 'necesito 1 mesa', ''),
(2028, 19, 12, 0, '2025-03-03', '2025-03-10', 'Pendiente', 'para jugar', ''),
(2029, 19, 10, 0, '2025-03-28', NULL, NULL, 'Necesito una raqueta', NULL),
(2030, 19, 9, 0, '2025-03-30', NULL, NULL, 'necesito una mesa', NULL),
(2031, 19, 30, 0, '2025-03-31', NULL, NULL, 'necesito un balon', NULL),
(2032, 19, 12, 0, '2025-02-23', NULL, NULL, 'Con urgencia se necesitan 2', NULL),
(2033, 19, 10, 0, '2025-07-13', NULL, NULL, 'Se necesitan raquetas', NULL),
(2034, 19, 10, 2, '2025-04-12', NULL, NULL, 'aja', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `rol` enum('Administrador','Encargado','Estudiante') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `correo`, `password`, `rol`) VALUES
(1, 'admin@example.com', '1234567', 'Administrador'),
(12, 'ana.sofia@correo.com', '$2y$10$hHQdwOeK8OAGeAFgZX6n9Om9DZdB4EhELg4hI/DzY1vE8q6G5UgTm', 'Estudiante'),
(13, 'leidy.cano@correo.com', '$2y$10$eMwJQf7wLIaq3XAhsQnyJOsgYMC8EuTHvtaS0SA4BBxLfbcZBiB8i', 'Estudiante'),
(14, 'esteban.galvis@correo.com', '$2y$10$RGS/brJQpOddzHD66Q8gaeACuOKwvy8tiedBp.G/sOlnfx5gv9Fdu', 'Estudiante'),
(15, 'daniel.hurtado@correo.com', '$2y$10$EiZssAv8CwJ50EAE7JubbOO0g7h0Cp4bNvax5zEKkAfHyG.5vvQIm', 'Encargado'),
(17, 'andrea@correo.com', '$2y$10$bh/AZiBULdupt0Amwkq/Beq596hU82zFcj4uc6bLIVH2C73Wx.TWu', 'Encargado'),
(19, 'danna@gmail.com', '$2y$10$zAXREi9lsiwfPX9QwV6Ype/MDMVQFOYdwHhBgUIzL0uGYd57AmJPq', 'Estudiante'),
(21, 'oliver@gmail.com', '$2y$10$fQXQT/3eT5davo5akVIDzeseQfX07pU1U7GHn/lG4HVcMP80Z3Rqi', 'Estudiante'),
(22, 'camila@gmail.com', '$2y$10$AefMzzkfTzCt72.prK8BG.UZDx1F/XIH5UXHKarsHiAwF/JG3fI22', 'Encargado');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `encargado`
--
ALTER TABLE `encargado`
  ADD PRIMARY KEY (`id_encargado`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD UNIQUE KEY `correo_institucional` (`correo_institucional`),
  ADD KEY `fk_curso` (`id_curso`);

--
-- Indices de la tabla `implemento`
--
ALTER TABLE `implemento`
  ADD PRIMARY KEY (`id_implemento`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_implemento` (`id_implemento`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2725;

--
-- AUTO_INCREMENT de la tabla `encargado`
--
ALTER TABLE `encargado`
  MODIFY `id_encargado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `implemento`
--
ALTER TABLE `implemento`
  MODIFY `id_implemento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2035;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `encargado`
--
ALTER TABLE `encargado`
  ADD CONSTRAINT `encargado_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `prestamo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `prestamo_ibfk_2` FOREIGN KEY (`id_implemento`) REFERENCES `implemento` (`id_implemento`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
