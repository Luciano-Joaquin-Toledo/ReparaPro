-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-04-2025 a las 17:30:09
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
-- Base de datos: `sistema_reparaciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `proveedor` varchar(100) NOT NULL,
  `ubicacion` varchar(100) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `nombre`, `categoria`, `cantidad`, `precio`, `proveedor`, `ubicacion`, `fecha_ingreso`, `creado_en`) VALUES
(1, 'Fuente de poder 500W', 'Repuestos', 14, 4500.00, 'TecnoPower SRL', 'Estantería B2', '2025-04-22', '2025-04-23 02:00:28'),
(2, 'Pantalla 16.5\"', 'Repuestos', 19, 80000.00, 'TecnoPower SRL', 'Estantería 17A', '2025-04-14', '2025-04-23 14:32:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `tipo_cliente` enum('Cliente','Empresa') NOT NULL,
  `notas` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `telefono`, `email`, `direccion`, `dni`, `tipo_cliente`, `notas`, `fecha_creacion`) VALUES
(1, 'Luciano Joaquín Toledo', '+54 9 11 5691-1720', 'ljtoledo@itel.edu.ar', 'Lomas de Zamora, Muzzilli 849', '47018320', 'Cliente', 'Cliente Frecuente', '2025-04-22 20:55:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entregas`
--

CREATE TABLE `entregas` (
  `id` int(11) NOT NULL,
  `orden_reparacion_id` int(11) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `metodo_entrega` enum('recogido_cliente','entregado_empresa','envio_domicilio') NOT NULL,
  `ubicacion_entrega` varchar(255) DEFAULT NULL,
  `observaciones_generales` text DEFAULT NULL,
  `comentarios_tecnicos` text DEFAULT NULL,
  `estado_pago_entrega` enum('pendiente','parcial','pagada') NOT NULL DEFAULT 'pendiente',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entregas`
--

INSERT INTO `entregas` (`id`, `orden_reparacion_id`, `fecha_entrega`, `metodo_entrega`, `ubicacion_entrega`, `observaciones_generales`, `comentarios_tecnicos`, `estado_pago_entrega`, `creado_en`) VALUES
(3, 5, '2025-04-23', 'envio_domicilio', 'Lomas de Zamora', 'Ninguna', 'Ninguna', 'pendiente', '2025-04-23 14:37:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `tipo` enum('pc','laptop','telefono','consola') NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `numero_serie` varchar(100) DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `cliente_id`, `tipo`, `marca`, `modelo`, `numero_serie`, `fecha_ingreso`, `observaciones`, `fecha_registro`) VALUES
(3, 1, 'laptop', 'HP', '15-FC0041WM', 'SN-2025-XG7H-9Q2M', '2025-04-22', '-', '2025-04-22 23:12:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos_consola`
--

CREATE TABLE `equipos_consola` (
  `equipo_id` int(11) NOT NULL,
  `os` varchar(100) DEFAULT NULL,
  `almacenamiento` varchar(100) DEFAULT NULL,
  `puertos` text DEFAULT NULL,
  `conectividad` varchar(100) DEFAULT NULL,
  `mandos` varchar(100) DEFAULT NULL,
  `red` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos_laptop`
--

CREATE TABLE `equipos_laptop` (
  `equipo_id` int(11) NOT NULL,
  `procesador` varchar(100) DEFAULT NULL,
  `ram` varchar(100) DEFAULT NULL,
  `almacenamiento` varchar(100) DEFAULT NULL,
  `pantalla` varchar(100) DEFAULT NULL,
  `gpu` varchar(100) DEFAULT NULL,
  `mother` varchar(100) DEFAULT NULL,
  `bateria` varchar(100) DEFAULT NULL,
  `os` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos_laptop`
--

INSERT INTO `equipos_laptop` (`equipo_id`, `procesador`, `ram`, `almacenamiento`, `pantalla`, `gpu`, `mother`, `bateria`, `os`) VALUES
(3, 'AMD Ryzen 5 7520U', '8 GB DDR5', '512 GB SSD', '15.6 \"', 'Integrated Radeon Graphics', '-', '3.500 mAh', 'Windows 11 Home');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos_pc`
--

CREATE TABLE `equipos_pc` (
  `equipo_id` int(11) NOT NULL,
  `procesador` varchar(100) DEFAULT NULL,
  `ram` varchar(100) DEFAULT NULL,
  `almacenamiento` varchar(100) DEFAULT NULL,
  `gpu` varchar(100) DEFAULT NULL,
  `mother` varchar(100) DEFAULT NULL,
  `os` varchar(100) DEFAULT NULL,
  `puertos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos_telefono`
--

CREATE TABLE `equipos_telefono` (
  `equipo_id` int(11) NOT NULL,
  `os` varchar(100) DEFAULT NULL,
  `pantalla` varchar(100) DEFAULT NULL,
  `camara` varchar(100) DEFAULT NULL,
  `procesador` varchar(100) DEFAULT NULL,
  `ram` varchar(100) DEFAULT NULL,
  `almacenamiento` varchar(100) DEFAULT NULL,
  `bateria` varchar(100) DEFAULT NULL,
  `red` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `orden_reparacion_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','transferencia','tarjeta') NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `estado_pago` enum('pagada','pendiente') NOT NULL DEFAULT 'pendiente',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `orden_reparacion_id`, `total`, `metodo_pago`, `monto_pagado`, `estado_pago`, `fecha`) VALUES
(3, 5, 118224.00, 'efectivo', 118224.00, 'pagada', '2025-04-23 19:36:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_reparacion`
--

CREATE TABLE `ordenes_reparacion` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `tecnico_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `estado_reparacion` enum('pendiente','en_reparacion','finalizado') NOT NULL DEFAULT 'pendiente',
  `fecha_ingreso` date NOT NULL,
  `fecha_finalizacion` date DEFAULT NULL,
  `total_servicios` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_repuestos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `precio_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes_reparacion`
--

INSERT INTO `ordenes_reparacion` (`id`, `cliente_id`, `equipo_id`, `tecnico_id`, `descripcion`, `estado_reparacion`, `fecha_ingreso`, `fecha_finalizacion`, `total_servicios`, `total_repuestos`, `precio_total`, `creado_en`) VALUES
(5, 1, 3, 2, 'No enciende', 'finalizado', '2025-04-23', NULL, 38224.00, 80000.00, 118224.00, '2025-04-23 14:31:14'),
(6, 1, 3, 2, 'Limpieza General', 'finalizado', '2025-04-23', NULL, 28958.00, 0.00, 28958.00, '2025-04-23 14:35:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_repuestos`
--

CREATE TABLE `ordenes_repuestos` (
  `id` int(11) NOT NULL,
  `orden_reparacion_id` int(11) NOT NULL,
  `repuesto_nombre` varchar(255) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes_repuestos`
--

INSERT INTO `ordenes_repuestos` (`id`, `orden_reparacion_id`, `repuesto_nombre`, `cantidad`, `costo`) VALUES
(3, 5, 'Pantalla 16.5\"', 1, 80000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_servicios`
--

CREATE TABLE `ordenes_servicios` (
  `id` int(11) NOT NULL,
  `orden_reparacion_id` int(11) NOT NULL,
  `servicio_nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes_servicios`
--

INSERT INTO `ordenes_servicios` (`id`, `orden_reparacion_id`, `servicio_nombre`, `precio`) VALUES
(3, 5, 'Cambio de pantalla Notebook', 38224.00),
(4, 6, 'Limpieza + Pasta térmica PC', 28958.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `especialidad` varchar(100) NOT NULL,
  `contacto` varchar(100) NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id`, `nombre`, `especialidad`, `contacto`, `estado`, `contraseña`) VALUES
(2, 'Luciano Joaquín Toledo', 'Hardware', '1156917205', 'activo', '$2y$10$dgwC7Ysq1/kg/gLd3YU2ieYTsraXEkvfLfgjm.B3irHfAurABODI2'),
(3, 'Tole', 'Hardware', '1156917205', 'activo', '$2y$10$bhcVyVWQ7vTRQNMl0eJjeuimNmPVIoIKyrrAkwbCJhGf7k1ktSpq.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entregas`
--
ALTER TABLE `entregas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orden_entrega` (`orden_reparacion_id`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `equipos_consola`
--
ALTER TABLE `equipos_consola`
  ADD PRIMARY KEY (`equipo_id`);

--
-- Indices de la tabla `equipos_laptop`
--
ALTER TABLE `equipos_laptop`
  ADD PRIMARY KEY (`equipo_id`);

--
-- Indices de la tabla `equipos_pc`
--
ALTER TABLE `equipos_pc`
  ADD PRIMARY KEY (`equipo_id`);

--
-- Indices de la tabla `equipos_telefono`
--
ALTER TABLE `equipos_telefono`
  ADD PRIMARY KEY (`equipo_id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facturas_ibfk_1` (`orden_reparacion_id`);

--
-- Indices de la tabla `ordenes_reparacion`
--
ALTER TABLE `ordenes_reparacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `equipo_id` (`equipo_id`),
  ADD KEY `tecnico_id` (`tecnico_id`);

--
-- Indices de la tabla `ordenes_repuestos`
--
ALTER TABLE `ordenes_repuestos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_reparacion_id` (`orden_reparacion_id`);

--
-- Indices de la tabla `ordenes_servicios`
--
ALTER TABLE `ordenes_servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_reparacion_id` (`orden_reparacion_id`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `entregas`
--
ALTER TABLE `entregas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ordenes_reparacion`
--
ALTER TABLE `ordenes_reparacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ordenes_repuestos`
--
ALTER TABLE `ordenes_repuestos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ordenes_servicios`
--
ALTER TABLE `ordenes_servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entregas`
--
ALTER TABLE `entregas`
  ADD CONSTRAINT `fk_entrega_orden` FOREIGN KEY (`orden_reparacion_id`) REFERENCES `ordenes_reparacion` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `equipos_consola`
--
ALTER TABLE `equipos_consola`
  ADD CONSTRAINT `equipos_consola_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `equipos_laptop`
--
ALTER TABLE `equipos_laptop`
  ADD CONSTRAINT `equipos_laptop_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `equipos_pc`
--
ALTER TABLE `equipos_pc`
  ADD CONSTRAINT `equipos_pc_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `equipos_telefono`
--
ALTER TABLE `equipos_telefono`
  ADD CONSTRAINT `equipos_telefono_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`orden_reparacion_id`) REFERENCES `ordenes_reparacion` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ordenes_reparacion`
--
ALTER TABLE `ordenes_reparacion`
  ADD CONSTRAINT `ordenes_reparacion_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordenes_reparacion_ibfk_2` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordenes_reparacion_ibfk_3` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnicos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ordenes_repuestos`
--
ALTER TABLE `ordenes_repuestos`
  ADD CONSTRAINT `ordenes_repuestos_ibfk_1` FOREIGN KEY (`orden_reparacion_id`) REFERENCES `ordenes_reparacion` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ordenes_servicios`
--
ALTER TABLE `ordenes_servicios`
  ADD CONSTRAINT `ordenes_servicios_ibfk_1` FOREIGN KEY (`orden_reparacion_id`) REFERENCES `ordenes_reparacion` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
