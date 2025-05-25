-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 25-05-2025 a las 21:38:01
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
-- Base de datos: `alan_turing`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id_administrador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `DNI` varchar(8) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id_administrador`, `nombre`, `apellido`, `celular`, `email`, `clave`, `DNI`, `direccion`) VALUES
(9, 'Pedro', 'Marmol', '999999977', 'adminxxx@gmail.com', '$2y$10$uLwpKOwYMamox6fnhBVag.cauvAfb1Ig/80UDN9GXjyb66p7Zgfly', '73737373', 'xxsaaaaaa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id_asistencia` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Presente','Ausente') DEFAULT 'Presente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id_asistencia`, `id_estudiante`, `id_curso`, `fecha`, `estado`) VALUES
(8, 40, 7, '2025-05-22 05:00:00', 'Presente'),
(10, 40, 7, '2025-05-24 05:00:00', 'Ausente'),
(12, 39, 7, '2025-05-24 05:00:00', 'Presente'),
(13, 39, 7, '2025-05-23 05:00:00', 'Presente'),
(14, 40, 7, '2025-05-23 05:00:00', 'Presente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id_calificacion` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `calificacion` decimal(5,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calificaciones`
--

INSERT INTO `calificaciones` (`id_calificacion`, `id_estudiante`, `id_curso`, `id_programa`, `id_docente`, `calificacion`, `descripcion`, `fecha_registro`) VALUES
(33, 40, 7, 49, 10, 15.00, 'Examen 1', '2025-05-25 00:23:51'),
(34, 39, 7, 24, 10, 10.00, 'Examen 2', '2025-05-25 00:56:14'),
(35, 40, 7, 24, 10, 11.00, 'Examen 2', '2025-05-25 00:56:14'),
(36, 39, 7, 24, 10, 10.00, 'Participacion', '2025-05-25 01:04:17'),
(37, 40, 7, 24, 10, 13.00, 'Participacion', '2025-05-25 01:04:17'),
(38, 39, 7, 24, 10, 15.00, 'Actitudinal', '2025-05-25 01:04:55'),
(39, 40, 7, 24, 10, 8.00, 'Actitudinal', '2025-05-25 01:04:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `id_docente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre_curso`, `descripcion`, `id_docente`) VALUES
(1, 'Geometría', 'Medicion en su maximo explendor', 3),
(5, 'Quimica', 'frfrrfrf', 1),
(6, 'Raz Verbal', 'ssssssssssssss', 2),
(7, 'Ciencias Sociales', 'Historia y todo lo demás', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id_docente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `DNI` varchar(8) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `edad` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id_docente`, `nombre`, `apellido`, `email`, `especialidad`, `celular`, `DNI`, `direccion`, `clave`, `edad`) VALUES
(1, 'Luis', 'Ramírez', 'docente1@correo.com', 'Matemáticas', '923456789', '33333333', 'Jr. Docente 789', '12345', 35),
(2, 'Ana', 'Torres', 'docente2@correo.com', 'Lenguaje', '924567890', '44444444', 'Jr. Palabras 123', '12345', 40),
(3, 'Amador', 'Sanchez', 'chatgpt.plus.realstore.03@gmail.com', 'Matematica', '985621452', '20202020', 'San jeronimo', '$2y$10$.VHjamEfzBkeQMnWzcrqResVkvCvNlcsLPs3W/KSG2rZ5hRM4SCY.', 15),
(10, 'Ramon', 'Sosa', '123_4@gmail.com', 'Ciencias Sociales', '999999977', '98398398', 'zzz sueños zzzz', '$2y$10$moqEqbSRW6daNoZZBNm7k.A1ZMfBhu2qnfB5VEEXaZ.S01wXqJ5bu', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL,
  `numero_matricula` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `DNI` varchar(20) DEFAULT NULL,
  `edad` int(10) UNSIGNED DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `numero_matricula`, `nombre`, `apellido`, `DNI`, `edad`, `direccion`, `celular`, `email`, `clave`) VALUES
(19, 'MATR-1744081981', 'Robert', 'Hongow', '00000000', 15, 'Manzanos 1234', '985621458', 'aaa@gmail.com', '$2y$10$VEtqRUWuvJqDj3xw44bcAurtcSDLx9oN4fUUPXyHrk5QyUsTtgw8G'),
(24, 'MATR-1744412582', 'Robert', 'Vela', '25252525', 15, 'Manzanos 123', '985621453', '123@gmail.com', '$2y$10$KOCYyTV.oKzWeMM4Y/yelOLkmXL3oHqtHWOgFRCATJIufNnURWczK'),
(39, 'MAT003', 'Carlos', 'Sanchez', '12312312', 17, 'San jeronimo', '985621452', 'admin@gmail.com', '$2y$10$23yAOvJr/HmKBs9SuPn1.eGb7pEKLGz8iS7OjKo6/WExxqc.j/PRq'),
(40, 'MAT004', 'Lili', 'Vasquez', '98989898', 16, 'zzz sueños', '956532127', 'chatgptplusrealstore@gmail.com', '$2y$10$Ws10cAl7d4hTG3LM27ssouBhs8tc8KJXnm3TU.9CdXUMWKBl9cMdO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matriculas`
--

CREATE TABLE `matriculas` (
  `id_estudiante` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `fecha_matricula` timestamp NOT NULL DEFAULT current_timestamp(),
  `monto_matricula` decimal(10,2) DEFAULT 0.00,
  `estado_matricula` enum('Pagado','Pendiente') DEFAULT 'Pendiente',
  `descuento` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `matriculas`
--

INSERT INTO `matriculas` (`id_estudiante`, `id_programa`, `fecha_matricula`, `monto_matricula`, `estado_matricula`, `descuento`) VALUES
(39, 24, '2025-05-21 03:11:06', 20.00, 'Pagado', 0.00),
(40, 24, '2025-05-23 04:56:17', 20.00, 'Pagado', 0.00),
(40, 49, '2025-05-16 21:40:10', 100.00, 'Pagado', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pension`
--

CREATE TABLE `pension` (
  `id_pension` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_programa` int(11) NOT NULL,
  `numero_cuota` int(11) NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pension` decimal(10,2) DEFAULT 0.00,
  `estado_pension` enum('Pagado','Pendiente') DEFAULT 'Pendiente',
  `beca` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pension`
--

INSERT INTO `pension` (`id_pension`, `id_estudiante`, `id_programa`, `numero_cuota`, `fecha_pago`, `monto_pension`, `estado_pension`, `beca`) VALUES
(153, 40, 49, 1, '2025-06-16', 20.00, 'Pagado', 0.00),
(154, 40, 49, 2, '2025-07-16', 20.00, 'Pagado', 0.00),
(155, 40, 49, 3, '2025-08-16', 20.00, 'Pendiente', 0.00),
(156, 40, 49, 4, '2025-09-16', 20.00, 'Pendiente', 0.00),
(157, 40, 49, 5, '2025-10-16', 20.00, 'Pendiente', 0.00),
(158, 40, 49, 6, '2025-11-16', 20.00, 'Pendiente', 0.00),
(159, 40, 49, 7, '2025-12-16', 20.00, 'Pendiente', 0.00),
(160, 40, 49, 8, '2026-01-16', 20.00, 'Pendiente', 0.00),
(161, 39, 24, 1, '2025-06-21', 50.00, 'Pagado', 0.00),
(162, 39, 24, 2, '2025-07-21', 50.00, 'Pagado', 0.00),
(163, 39, 24, 3, '2025-08-21', 50.00, 'Pagado', 0.00),
(164, 39, 24, 4, '2025-09-21', 50.00, 'Pendiente', 0.00),
(165, 39, 24, 5, '2025-10-21', 50.00, 'Pendiente', 0.00),
(166, 39, 24, 6, '2025-11-21', 50.00, 'Pendiente', 0.00),
(167, 39, 24, 7, '2025-12-21', 50.00, 'Pendiente', 0.00),
(168, 40, 24, 1, '2025-06-23', 50.00, 'Pagado', 0.00),
(169, 40, 24, 2, '2025-07-23', 50.00, 'Pendiente', 0.00),
(170, 40, 24, 3, '2025-08-23', 50.00, 'Pendiente', 0.00),
(171, 40, 24, 4, '2025-09-23', 50.00, 'Pendiente', 0.00),
(172, 40, 24, 5, '2025-10-23', 50.00, 'Pendiente', 0.00),
(173, 40, 24, 6, '2025-11-23', 50.00, 'Pendiente', 0.00),
(174, 40, 24, 7, '2025-12-23', 50.00, 'Pendiente', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `costo` float DEFAULT NULL,
  `pension` float NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `duracion` int(11) NOT NULL,
  `pensiones` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas`
--

INSERT INTO `programas` (`id_programa`, `nombre_programa`, `descripcion`, `costo`, `pension`, `fecha_inicio`, `fecha_fin`, `duracion`, `pensiones`) VALUES
(24, 'Cursos Primero', '....', 20, 50, '2025-04-07', '2025-06-02', 7, 7),
(49, 'Local', 'cccccccccccccccc', 100, 20, '2025-05-01', '2025-06-21', 8, 2),
(52, 'Artes', 'dcdcdcdcd', 50, 50, '2025-05-26', '2025-06-28', 5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa_curso`
--

CREATE TABLE `programa_curso` (
  `id_programa` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programa_curso`
--

INSERT INTO `programa_curso` (`id_programa`, `id_curso`) VALUES
(24, 1),
(24, 5),
(24, 6),
(24, 7),
(49, 6),
(49, 7),
(52, 1),
(52, 6);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_administrador`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_docente` (`id_docente`),
  ADD KEY `fk_calificaciones_programa` (`id_programa`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `id_docente` (`id_docente`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id_docente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `numero_matricula` (`numero_matricula`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `DNI` (`DNI`);

--
-- Indices de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id_estudiante`,`id_programa`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indices de la tabla `pension`
--
ALTER TABLE `pension`
  ADD PRIMARY KEY (`id_pension`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_programa` (`id_programa`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id_programa`);

--
-- Indices de la tabla `programa_curso`
--
ALTER TABLE `programa_curso`
  ADD PRIMARY KEY (`id_programa`,`id_curso`),
  ADD KEY `id_curso` (`id_curso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `pension`
--
ALTER TABLE `pension`
  MODIFY `id_pension` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`),
  ADD CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  ADD CONSTRAINT `fk_calificaciones_programa` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`);

--
-- Filtros para la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`);

--
-- Filtros para la tabla `pension`
--
ALTER TABLE `pension`
  ADD CONSTRAINT `pension_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `pension_ibfk_2` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`) ON DELETE CASCADE;

--
-- Filtros para la tabla `programa_curso`
--
ALTER TABLE `programa_curso`
  ADD CONSTRAINT `programa_curso_ibfk_1` FOREIGN KEY (`id_programa`) REFERENCES `programas` (`id_programa`),
  ADD CONSTRAINT `programa_curso_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
