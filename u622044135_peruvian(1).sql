-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 08-05-2026 a las 14:27:42
-- Versión del servidor: 11.8.6-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u622044135_peruvian`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actas_reunion`
--

CREATE TABLE `actas_reunion` (
  `id` int(11) NOT NULL,
  `orden_dia` text DEFAULT NULL,
  `acuerdos` varchar(255) DEFAULT NULL,
  `asistentes` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_registro` date DEFAULT NULL,
  `duracion` varchar(20) DEFAULT NULL,
  `convoca` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afps`
--

CREATE TABLE `afps` (
  `id` int(11) NOT NULL,
  `afp` varchar(11) NOT NULL,
  `id_sistema_pensiones` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aptitud`
--

CREATE TABLE `aptitud` (
  `id` int(11) NOT NULL,
  `aptitud` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `area` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias_cursos`
--

CREATE TABLE `asistencias_cursos` (
  `id` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `asistentes` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_registro` date DEFAULT NULL,
  `horas_capacitacion` varchar(20) DEFAULT NULL,
  `capacitador` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistentes_capacitacion`
--

CREATE TABLE `asistentes_capacitacion` (
  `id` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `id_capacitacion` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aux`
--

CREATE TABLE `aux` (
  `i` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `tabla` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco_cuenta`
--

CREATE TABLE `banco_cuenta` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `nro_cuenta` varchar(255) DEFAULT NULL,
  `caja_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `biblioteca`
--

CREATE TABLE `biblioteca` (
  `id` int(11) NOT NULL,
  `nombre_carpeta` varchar(255) NOT NULL,
  `id_padre` int(11) DEFAULT NULL,
  `mostrar` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `box`
--

CREATE TABLE `box` (
  `id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_abono_mov`
--

CREATE TABLE `caja_abono_mov` (
  `id` int(11) NOT NULL,
  `caja_mov_id` int(11) NOT NULL,
  `caja_retiro_id` int(11) NOT NULL,
  `monto` double NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_kardex`
--

CREATE TABLE `caja_kardex` (
  `id` int(11) NOT NULL,
  `caja_id` int(11) NOT NULL,
  `kardex_tipo` int(1) NOT NULL,
  `caja_mov_id` int(11) NOT NULL,
  `abono_banco` varchar(255) NOT NULL,
  `abono_periodo` varchar(255) NOT NULL,
  `abono_fecha` date NOT NULL,
  `abono_monto` double NOT NULL,
  `cargo_fecha` varchar(255) NOT NULL,
  `cargo_concepto` varchar(255) NOT NULL,
  `cargo_periodo` varchar(255) NOT NULL,
  `cargo_monto` double NOT NULL,
  `cargo_saldo` double NOT NULL,
  `cargo_abono_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_mov`
--

CREATE TABLE `caja_mov` (
  `id` int(11) NOT NULL,
  `concepto` varchar(255) DEFAULT NULL,
  `periodo` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `prioridad` int(11) DEFAULT NULL,
  `id_retiro` int(11) DEFAULT NULL,
  `monto_retiro` double NOT NULL DEFAULT 0,
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `caja_id` int(11) DEFAULT NULL,
  `banco_cuenta_id` int(11) DEFAULT NULL,
  `saldo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capacitaciones`
--

CREATE TABLE `capacitaciones` (
  `id` int(11) NOT NULL,
  `curso` varchar(255) DEFAULT NULL,
  `fecha` varchar(255) DEFAULT NULL,
  `horas` varchar(20) DEFAULT NULL,
  `lugar` varchar(255) DEFAULT NULL,
  `capacitador` varchar(255) DEFAULT NULL,
  `id_colaborador` int(11) NOT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `eficacia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capacitaciones2`
--

CREATE TABLE `capacitaciones2` (
  `id` int(11) NOT NULL,
  `curso` varchar(255) DEFAULT NULL,
  `fecha` varchar(255) DEFAULT NULL,
  `horas` varchar(20) DEFAULT NULL,
  `lugar` varchar(255) DEFAULT NULL,
  `capacitador` varchar(255) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capacitacion_registro`
--

CREATE TABLE `capacitacion_registro` (
  `id` int(11) NOT NULL,
  `curso` varchar(255) NOT NULL,
  `areas` varchar(255) NOT NULL,
  `mes` varchar(25) DEFAULT NULL,
  `anio` varchar(4) NOT NULL,
  `responsable` varchar(255) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `eficacia` varchar(255) DEFAULT NULL,
  `id_tipo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capacitacion_registro_fecha`
--

CREATE TABLE `capacitacion_registro_fecha` (
  `id` int(11) NOT NULL,
  `id_capacitacion_registro` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  `mes` int(11) DEFAULT 0,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `id_referencia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE `clases` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_sunat`
--

CREATE TABLE `codigos_sunat` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `unidad` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colaboradores`
--

CREATE TABLE `colaboradores` (
  `id` int(11) NOT NULL,
  `dni` varchar(8) DEFAULT NULL,
  `dni_archivo` varchar(255) DEFAULT NULL,
  `nombres` varchar(50) DEFAULT NULL,
  `apellido_paterno` varchar(75) DEFAULT NULL,
  `apellido_materno` varchar(75) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `lugar_nacimiento` varchar(255) DEFAULT NULL,
  `id_estado_civil` int(11) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `brevette` varchar(25) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono_emergencia` varchar(15) DEFAULT NULL,
  `id_sistema_pension` int(11) DEFAULT 0,
  `id_entidad_pension` int(11) DEFAULT 0,
  `codigo` varchar(25) DEFAULT NULL,
  `asegurado` int(11) DEFAULT 0,
  `proceso` int(11) DEFAULT 0,
  `sueldo` decimal(10,2) DEFAULT NULL,
  `genero` varchar(5) DEFAULT NULL,
  `estado_laboral` int(11) DEFAULT 0,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` varchar(50) DEFAULT NULL,
  `id_cargo` int(11) DEFAULT 0,
  `linea` int(11) DEFAULT 0,
  `estado` int(11) DEFAULT 0,
  `archivo` varchar(255) DEFAULT NULL COMMENT 'Certificado Medico',
  `contrato` varchar(255) DEFAULT NULL,
  `sst` varchar(255) DEFAULT NULL,
  `competencias` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colaborador_horarios`
--

CREATE TABLE `colaborador_horarios` (
  `id` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `id_usuario_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `id_usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `complementos`
--

CREATE TABLE `complementos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `code_producto` varchar(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `serie` varchar(5) DEFAULT NULL,
  `numeracion` varchar(8) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `proveedor` varchar(50) DEFAULT NULL,
  `igv` decimal(10,2) DEFAULT NULL,
  `gravado` decimal(10,2) DEFAULT NULL,
  `exonerado` decimal(10,2) DEFAULT NULL,
  `otros_no_gravado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `tipo_documento` int(11) DEFAULT 3,
  `id_forma_pago` int(11) DEFAULT NULL,
  `fecha_detraccion` varchar(15) DEFAULT '',
  `numero_detraccion` varchar(15) DEFAULT '',
  `tipo_cambio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_comprobante` varchar(15) DEFAULT '',
  `serie_comprobante` varchar(15) DEFAULT '',
  `documento_comprobante` varchar(15) DEFAULT '',
  `fproceso` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras_detalle`
--

CREATE TABLE `compras_detalle` (
  `id` int(11) NOT NULL,
  `codigo_compra` varchar(50) DEFAULT NULL,
  `id_compra` int(11) NOT NULL,
  `id_insumo` varchar(50) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `unidad` varchar(25) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductores`
--

CREATE TABLE `conductores` (
  `id` int(11) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `ruc` varchar(15) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `ubigeo` varchar(6) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `tipoDocumento` int(11) NOT NULL,
  `licencia` varchar(25) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuration`
--

CREATE TABLE `configuration` (
  `id` int(11) NOT NULL,
  `short` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `kind` int(11) NOT NULL,
  `val` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenido_biblioteca`
--

CREATE TABLE `contenido_biblioteca` (
  `id` int(11) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `id_carpeta` int(11) NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `id` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `id_tipo_contrato` int(11) NOT NULL,
  `periodo` varchar(50) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costos`
--

CREATE TABLE `costos` (
  `id` int(11) NOT NULL,
  `costo_prenda` varchar(10) NOT NULL,
  `utilidad` varchar(10) NOT NULL,
  `valor_venta` varchar(10) NOT NULL,
  `igv` varchar(10) NOT NULL,
  `renta` varchar(10) NOT NULL,
  `precio_venta` varchar(10) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costos_materiales`
--

CREATE TABLE `costos_materiales` (
  `id` int(11) NOT NULL,
  `id_insumo` int(11) DEFAULT NULL,
  `unidad` varchar(10) DEFAULT NULL,
  `consumo_teorico` varchar(10) DEFAULT NULL,
  `merma` varchar(10) DEFAULT NULL,
  `consumo_real` varchar(10) DEFAULT NULL,
  `costo_unitario` varchar(10) DEFAULT NULL,
  `costo_total` decimal(10,2) DEFAULT NULL,
  `costo_total_porcentaje` varchar(10) DEFAULT NULL,
  `tipo_material` int(11) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costo_mano_directa`
--

CREATE TABLE `costo_mano_directa` (
  `id` int(11) NOT NULL,
  `proceso` varchar(255) DEFAULT NULL,
  `costo_minuto` varchar(10) DEFAULT NULL,
  `valor_prenda` varchar(10) DEFAULT NULL,
  `tiempo_produccion` varchar(10) DEFAULT NULL,
  `costo_total_porcentaje` varchar(10) DEFAULT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costo_servicio_externo`
--

CREATE TABLE `costo_servicio_externo` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `bordado` varchar(10) NOT NULL,
  `concepto` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `costo_uso_taller`
--

CREATE TABLE `costo_uso_taller` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `costo_minuto` varchar(10) NOT NULL,
  `tiempo_produccion` varchar(10) NOT NULL,
  `total` varchar(10) NOT NULL,
  `costo_total_porcentaje` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE `cotizacion` (
  `codigo` varchar(50) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `tiempo_entrega` text NOT NULL,
  `obervacion` text DEFAULT NULL,
  `servicios` varchar(255) DEFAULT NULL,
  `sub_total` varchar(10) DEFAULT NULL,
  `total` varchar(10) NOT NULL DEFAULT '0',
  `igv` varchar(10) NOT NULL DEFAULT '0',
  `person_id` int(11) NOT NULL,
  `cliente` varchar(50) DEFAULT NULL,
  `validez` varchar(255) DEFAULT NULL,
  `forma_pago` varchar(255) DEFAULT NULL,
  `tallas_especiales` varchar(255) DEFAULT NULL,
  `asesor_comercial` varchar(255) DEFAULT NULL,
  `asesor_celular` varchar(15) DEFAULT NULL,
  `igv_incluye` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_detalle`
--

CREATE TABLE `cotizacion_detalle` (
  `id` int(11) NOT NULL,
  `codigo_cotizacion` varchar(50) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 0,
  `imagen` text DEFAULT NULL,
  `imagen_2` varchar(100) DEFAULT NULL,
  `costo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `servicios` text DEFAULT NULL,
  `nombre_producto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cronograma_registro`
--

CREATE TABLE `cronograma_registro` (
  `id` int(11) NOT NULL,
  `curso` varchar(255) NOT NULL,
  `areas` varchar(255) NOT NULL,
  `mes` varchar(25) DEFAULT NULL,
  `anio` varchar(4) NOT NULL,
  `responsable` varchar(255) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `eficacia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cronograma_registro_fecha`
--

CREATE TABLE `cronograma_registro_fecha` (
  `id` int(11) NOT NULL,
  `id_cronograma_registro` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  `mes` int(11) DEFAULT 0,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_pagar`
--

CREATE TABLE `cuentas_pagar` (
  `id` int(11) NOT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `prioridad` int(11) DEFAULT NULL,
  `id_retiro` int(11) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `d`
--

CREATE TABLE `d` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_ingreso`
--

CREATE TABLE `datos_ingreso` (
  `id` int(11) NOT NULL,
  `di_por_capacidad` decimal(14,2) NOT NULL,
  `di_nro_operarios` decimal(14,2) NOT NULL,
  `di_tie_confeccion` decimal(14,2) NOT NULL,
  `di_hor_laboradas` decimal(14,2) NOT NULL,
  `di_tal_estimar` varchar(10) NOT NULL,
  `tarifa_corte` decimal(14,2) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `di_total_confeccion` decimal(14,2) NOT NULL,
  `di_confeccion_margen` decimal(14,2) NOT NULL,
  `di_margen` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `departamento` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispositivos`
--

CREATE TABLE `dispositivos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `responsable` varchar(255) DEFAULT NULL,
  `fecha` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distrito`
--

CREATE TABLE `distrito` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `distrito` varchar(50) NOT NULL,
  `provincia` varchar(10) NOT NULL,
  `departamento` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_civil`
--

CREATE TABLE `estado_civil` (
  `id` int(11) NOT NULL,
  `estado_civil` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etapas`
--

CREATE TABLE `etapas` (
  `id` int(11) NOT NULL,
  `etapa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes_medicos`
--

CREATE TABLE `examenes_medicos` (
  `id` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `periodo` varchar(25) NOT NULL,
  `fecha` date NOT NULL,
  `id_tipo_examen` int(11) NOT NULL,
  `id_aptitud` int(11) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencia_laboral`
--

CREATE TABLE `experiencia_laboral` (
  `id` int(11) NOT NULL,
  `empresa` varchar(50) NOT NULL,
  `cargo` varchar(255) NOT NULL,
  `responsabilidades` text NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_termino` date DEFAULT NULL,
  `tiempo_servicio` varchar(50) DEFAULT NULL,
  `id_colaborador` int(11) NOT NULL,
  `motivo_cese` text DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `f`
--

CREATE TABLE `f` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiares`
--

CREATE TABLE `familiares` (
  `id` int(11) NOT NULL,
  `dni` varchar(8) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `lugar_nacimiento` varchar(50) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `parentesco` varchar(20) NOT NULL,
  `id_colaborador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familias`
--

CREATE TABLE `familias` (
  `codigo` varchar(11) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familias2`
--

CREATE TABLE `familias2` (
  `id` int(11) NOT NULL DEFAULT 0,
  `descripcion` varchar(50) DEFAULT NULL,
  `codigo` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_tecnica_archivo`
--

CREATE TABLE `fecha_tecnica_archivo` (
  `id` int(11) NOT NULL,
  `id_producto` varchar(50) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feriados`
--

CREATE TABLE `feriados` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `id_usuario_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `id_usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha_tecnica`
--

CREATE TABLE `ficha_tecnica` (
  `id` int(11) NOT NULL,
  `tejido` varchar(50) NOT NULL DEFAULT '-',
  `cinta` varchar(50) NOT NULL DEFAULT '-',
  `etiqueta` varchar(50) NOT NULL DEFAULT '-',
  `estampado` varchar(50) NOT NULL DEFAULT '-',
  `code_producto` varchar(75) NOT NULL,
  `elaborado_por` varchar(255) DEFAULT NULL,
  `u_modificacion` varchar(255) DEFAULT NULL,
  `aprobado_por` varchar(255) DEFAULT NULL,
  `revisado_por` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formacion`
--

CREATE TABLE `formacion` (
  `id` int(11) NOT NULL,
  `formacion` varchar(255) NOT NULL,
  `lugar` varchar(255) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia_cabecera`
--

CREATE TABLE `guia_cabecera` (
  `id` int(11) NOT NULL,
  `num_guia` varchar(25) NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_traslado` date NOT NULL,
  `ruc_destinatario` varchar(15) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `ruc_transportista` varchar(15) NOT NULL,
  `ruc_conductor` varchar(15) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `total_bruto` decimal(14,2) NOT NULL,
  `total_neto` decimal(14,2) NOT NULL,
  `estado` int(11) NOT NULL,
  `origen` varchar(255) NOT NULL,
  `ubigeo` varchar(10) NOT NULL,
  `ubigeo_destino` varchar(10) NOT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  `modalidad_trasnporte` varchar(2) NOT NULL,
  `motivo_traslado` varchar(2) NOT NULL,
  `descripcion_motivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia_detalle`
--

CREATE TABLE `guia_detalle` (
  `id` int(11) NOT NULL,
  `id_guia` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `t_bruto` decimal(14,2) NOT NULL,
  `t_neto` decimal(14,2) NOT NULL,
  `unidad` varchar(25) NOT NULL,
  `descripcion_producto` text DEFAULT NULL,
  `pedido` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

CREATE TABLE `habilidades` (
  `id` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `elemento` varchar(50) NOT NULL,
  `habilidad` text NOT NULL,
  `tipo` int(11) DEFAULT NULL COMMENT '0 blanda 1 tecnica'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `id_usuario_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `id_usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `tolerancia_min` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_dias`
--

CREATE TABLE `horario_dias` (
  `id` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `dia_semana` tinyint(4) NOT NULL COMMENT '1=Lunes ... 7=Domingo',
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `id_usuario_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `id_usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `hora_inicio_refrigerio` time DEFAULT NULL,
  `hora_fin_refrigerio` time DEFAULT NULL,
  `descanso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `identificacion`
--

CREATE TABLE `identificacion` (
  `id` int(11) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `code_producto` varchar(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos`
--

CREATE TABLE `insumos` (
  `id` varchar(50) NOT NULL,
  `insumo` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `unidad` varchar(50) DEFAULT NULL,
  `familia` varchar(10) DEFAULT NULL,
  `clase` varchar(10) DEFAULT NULL,
  `subclase` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos_2`
--

CREATE TABLE `insumos_2` (
  `id` int(11) NOT NULL,
  `insumo` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `unidad` varchar(50) DEFAULT NULL,
  `familia` varchar(5) DEFAULT NULL,
  `clase` varchar(5) DEFAULT NULL,
  `subclase` varchar(15) DEFAULT NULL,
  `codigo` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumo_stock`
--

CREATE TABLE `insumo_stock` (
  `id` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `codigo_unidad` varchar(20) NOT NULL,
  `stock` decimal(10,2) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kind_doc`
--

CREATE TABLE `kind_doc` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(25) NOT NULL,
  `numero` int(11) NOT NULL,
  `modulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento_maquinas`
--

CREATE TABLE `mantenimiento_maquinas` (
  `id` int(11) NOT NULL,
  `id_maquina` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `mes` varchar(25) DEFAULT NULL,
  `anio` varchar(4) DEFAULT NULL,
  `responsable` varchar(255) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento_maquinas_fechas`
--

CREATE TABLE `mantenimiento_maquinas_fechas` (
  `id` int(11) NOT NULL,
  `id_mantenimiento` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maquinas`
--

CREATE TABLE `maquinas` (
  `id` int(11) NOT NULL,
  `code_producto` varchar(50) NOT NULL,
  `maquina` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcaciones`
--

CREATE TABLE `marcaciones` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `reloj_ip` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medidas`
--

CREATE TABLE `medidas` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `t_2` varchar(10) NOT NULL DEFAULT '',
  `t_4` varchar(10) NOT NULL DEFAULT '',
  `t_6` varchar(10) NOT NULL DEFAULT '',
  `t_8` varchar(10) NOT NULL DEFAULT '',
  `t_10` varchar(10) NOT NULL DEFAULT '',
  `t_12` varchar(10) NOT NULL DEFAULT '',
  `t_14` varchar(10) NOT NULL DEFAULT '',
  `t_16` varchar(10) NOT NULL DEFAULT '',
  `s` varchar(10) NOT NULL DEFAULT '',
  `m` varchar(10) NOT NULL DEFAULT '',
  `l` varchar(10) NOT NULL DEFAULT '',
  `xl` varchar(10) NOT NULL DEFAULT '',
  `xxl` varchar(10) NOT NULL DEFAULT '',
  `xxxl` varchar(10) NOT NULL DEFAULT '',
  `code_producto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `text` varchar(100) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus_entidades`
--

CREATE TABLE `menus_entidades` (
  `id` int(11) NOT NULL,
  `idMenu` int(11) DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `fechaCreacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `idUsuarioCreacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `user_from` int(11) NOT NULL,
  `user_to` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modificaciones`
--

CREATE TABLE `modificaciones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `fecha` varchar(15) DEFAULT NULL,
  `code_producto` varchar(75) NOT NULL,
  `aprobado_por` varchar(100) DEFAULT NULL,
  `ultima_modificacion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivos_traslado`
--

CREATE TABLE `motivos_traslado` (
  `id` int(11) NOT NULL,
  `motivo_traslado` varchar(255) NOT NULL,
  `codigo` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `observaciones`
--

CREATE TABLE `observaciones` (
  `id` int(11) NOT NULL,
  `observacion` varchar(250) NOT NULL,
  `detalle` varchar(255) NOT NULL,
  `code_producto` varchar(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operation`
--

CREATE TABLE `operation` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `stock_destination_id` int(11) DEFAULT NULL,
  `operation_from_id` int(11) DEFAULT NULL,
  `q` float NOT NULL,
  `price_in` double DEFAULT NULL,
  `price_out` double DEFAULT NULL,
  `operation_type_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `is_draft` tinyint(1) NOT NULL DEFAULT 0,
  `is_traspase` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operation_type`
--

CREATE TABLE `operation_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opiniones`
--

CREATE TABLE `opiniones` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `pedido` int(11) NOT NULL,
  `opinion` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_compra`
--

CREATE TABLE `orden_compra` (
  `id` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL COMMENT '1 anulado',
  `lugar_entrega` varchar(255) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `id_forma_pago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_compra_detalle`
--

CREATE TABLE `orden_compra_detalle` (
  `id` int(11) NOT NULL,
  `id_orden_compra` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `id_unidad` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_cabecera`
--

CREATE TABLE `order_cabecera` (
  `codigo` varchar(50) NOT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `tiempo_entrega` varchar(50) DEFAULT NULL,
  `fecha_entrega` varchar(15) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `person_id` int(11) NOT NULL,
  `comentario` text DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `num_contrato` varchar(255) DEFAULT NULL,
  `guia_remision` varchar(255) DEFAULT NULL,
  `fecha_entrega_real` date DEFAULT NULL,
  `imagen_alt` varchar(255) DEFAULT NULL,
  `nombre_modelo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_detalle`
--

CREATE TABLE `order_detalle` (
  `id` int(11) NOT NULL,
  `codigo_cabecera` varchar(50) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_detalle_2`
--

CREATE TABLE `order_detalle_2` (
  `id` int(11) NOT NULL,
  `codigo_cabecera` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT '',
  `nombre_modelo` varchar(255) DEFAULT NULL,
  `color` varchar(50) DEFAULT '',
  `_2` int(11) DEFAULT NULL,
  `_4` int(11) DEFAULT NULL,
  `_6` int(11) DEFAULT NULL,
  `_8` int(11) DEFAULT NULL,
  `_10` int(11) DEFAULT NULL,
  `_12` int(11) DEFAULT NULL,
  `_14` int(11) DEFAULT NULL,
  `_16` int(11) DEFAULT NULL,
  `s` int(11) DEFAULT NULL,
  `m` int(11) DEFAULT NULL,
  `l` int(11) DEFAULT NULL,
  `xl` int(11) DEFAULT NULL,
  `xxl` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `p2` varchar(11) DEFAULT '',
  `p4` varchar(11) DEFAULT '',
  `p6` varchar(11) DEFAULT '',
  `p8` varchar(11) DEFAULT '',
  `p10` varchar(11) DEFAULT '',
  `p12` varchar(11) DEFAULT '',
  `p14` varchar(11) DEFAULT '',
  `p16` varchar(11) DEFAULT '',
  `ps` varchar(11) DEFAULT '',
  `pm` varchar(11) DEFAULT '',
  `pl` varchar(11) DEFAULT '',
  `pxl` varchar(11) DEFAULT '',
  `pxxl` varchar(11) DEFAULT '',
  `ptotal` int(11) DEFAULT 0,
  `n1` varchar(10) DEFAULT NULL,
  `n2` varchar(10) DEFAULT NULL,
  `n3` varchar(10) DEFAULT NULL,
  `n4` varchar(10) DEFAULT NULL,
  `n5` varchar(10) DEFAULT NULL,
  `n6` varchar(10) DEFAULT NULL,
  `n7` varchar(10) DEFAULT NULL,
  `n8` varchar(10) DEFAULT NULL,
  `n9` varchar(10) DEFAULT NULL,
  `n10` varchar(10) DEFAULT NULL,
  `n11` varchar(10) DEFAULT NULL,
  `n12` varchar(10) DEFAULT NULL,
  `n13` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p`
--

CREATE TABLE `p` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `codigo_venta` varchar(50) DEFAULT NULL,
  `id_person` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `pago` decimal(10,2) DEFAULT NULL,
  `deuda` decimal(10,2) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `banco` varchar(50) NOT NULL DEFAULT '',
  `concepto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasos`
--

CREATE TABLE `pasos` (
  `id` int(11) NOT NULL,
  `id_etapa` int(11) NOT NULL,
  `paso` varchar(50) NOT NULL,
  `instruccion` text NOT NULL,
  `code_producto` varchar(50) NOT NULL,
  `orden` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `person_id` int(11) NOT NULL,
  `val` double DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_type`
--

CREATE TABLE `payment_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_puesto`
--

CREATE TABLE `perfil_puesto` (
  `id` int(11) NOT NULL,
  `id_puesto` int(11) NOT NULL,
  `reporta_a` varchar(250) DEFAULT NULL,
  `supervisa_a` text NOT NULL,
  `interactua_con` text NOT NULL,
  `reemplazado_por` varchar(250) DEFAULT NULL,
  `objetivo` varchar(255) NOT NULL,
  `funciones` text NOT NULL,
  `responsabilidades` text NOT NULL,
  `equipo_utilizado` text NOT NULL,
  `lugar_trabajo` varchar(255) NOT NULL,
  `requerimientos_fisicos` varchar(255) NOT NULL,
  `formacion_basica` text NOT NULL,
  `formacion_basica_optima` varchar(255) DEFAULT NULL,
  `conocimientos_especificos` text NOT NULL,
  `experiencia_requerida` varchar(255) NOT NULL,
  `experiencia_requerida_optima` varchar(255) DEFAULT NULL,
  `idioma` varchar(255) NOT NULL,
  `competencia_especifica` text NOT NULL,
  `elaborado_por` varchar(255) NOT NULL,
  `aprobado_por` varchar(255) NOT NULL,
  `fecha_aprobacion` date NOT NULL,
  `competencia_cardinal` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `id_usuario_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `id_usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `no` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `phone1` varchar(50) DEFAULT NULL,
  `phone2` varchar(50) DEFAULT NULL,
  `email1` varchar(50) DEFAULT NULL,
  `email2` varchar(50) DEFAULT NULL,
  `is_active_access` tinyint(1) DEFAULT 0,
  `has_credit` tinyint(1) DEFAULT 0,
  `credit_limit` double DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `kind` int(11) DEFAULT NULL,
  `kind_user` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `nro_cuenta` varchar(50) DEFAULT NULL,
  `tipo_cuenta` varchar(25) DEFAULT NULL,
  `tipo_moneda` varchar(5) DEFAULT NULL,
  `forma_envio` varchar(100) DEFAULT NULL,
  `banco` varchar(255) DEFAULT NULL,
  `wsp` varchar(50) DEFAULT NULL,
  `tipo_pago` varchar(10) DEFAULT NULL,
  `id_insumo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios`
--

CREATE TABLE `precios` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `id_person` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `price`
--

CREATE TABLE `price` (
  `id` int(11) NOT NULL,
  `price_out` double DEFAULT 0,
  `product_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion`
--

CREATE TABLE `produccion` (
  `id` int(11) NOT NULL,
  `orden` varchar(50) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_detalle`
--

CREATE TABLE `produccion_detalle` (
  `id` int(11) NOT NULL,
  `id_produccion` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `unidad` varchar(50) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_order_detalle`
--

CREATE TABLE `produccion_order_detalle` (
  `id` int(11) NOT NULL DEFAULT 0,
  `codigo_cabecera` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT '',
  `color` varchar(50) DEFAULT '',
  `_2` int(11) DEFAULT NULL,
  `_4` int(11) DEFAULT NULL,
  `_6` int(11) DEFAULT NULL,
  `_8` int(11) DEFAULT NULL,
  `_10` int(11) DEFAULT NULL,
  `_12` int(11) DEFAULT NULL,
  `_14` int(11) DEFAULT NULL,
  `_16` int(11) DEFAULT NULL,
  `s` int(11) DEFAULT NULL,
  `m` int(11) DEFAULT NULL,
  `l` int(11) DEFAULT NULL,
  `xl` int(11) DEFAULT NULL,
  `xxl` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `inventary_min` int(11) DEFAULT NULL,
  `price_in` float NOT NULL,
  `price_in_2` float DEFAULT NULL,
  `price_out` float DEFAULT NULL,
  `unit` varchar(255) NOT NULL,
  `presentation` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `width` varchar(20) DEFAULT NULL,
  `height` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `large` varchar(20) DEFAULT NULL,
  `expire_at` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `kind` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `cliente_id` int(11) DEFAULT NULL,
  `imgbordado` varchar(255) DEFAULT NULL,
  `prebor_in` float DEFAULT NULL,
  `prebor_out` float DEFAULT 0,
  `fecact` date DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `secuencia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE `provincia` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `departamento` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos`
--

CREATE TABLE `puestos` (
  `id` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `puesto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recomendaciones_sst`
--

CREATE TABLE `recomendaciones_sst` (
  `id` int(11) NOT NULL,
  `fecha_recomendacion` date NOT NULL,
  `fecha_capacitacion` date NOT NULL,
  `tipo_recomendacion` varchar(255) NOT NULL,
  `referencia_recomendacion` varchar(255) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `id_colaborador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_dispositivo`
--

CREATE TABLE `registro_dispositivo` (
  `id` int(11) NOT NULL,
  `id_dispositivo` int(11) DEFAULT NULL,
  `fecha_entrega` varchar(20) DEFAULT NULL,
  `recibido_por` varchar(255) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `responsable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relojes`
--

CREATE TABLE `relojes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `puerto` int(11) DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `id_usuario_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `id_usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_sunat_guias`
--

CREATE TABLE `respuesta_sunat_guias` (
  `id` int(11) NOT NULL,
  `id_guia` int(11) NOT NULL,
  `respuesta` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retiro`
--

CREATE TABLE `retiro` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `saldo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s`
--

CREATE TABLE `s` (
  `saldo` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `saving`
--

CREATE TABLE `saving` (
  `id` int(11) NOT NULL,
  `concept` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `date_at` date DEFAULT NULL,
  `kind` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sell`
--

CREATE TABLE `sell` (
  `id` int(11) NOT NULL,
  `invoice_code` varchar(255) DEFAULT NULL,
  `invoice_file` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `sell_from_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `operation_type_id` int(11) DEFAULT 2,
  `box_id` int(11) DEFAULT NULL,
  `p_id` int(11) DEFAULT NULL,
  `d_id` int(11) DEFAULT NULL,
  `f_id` int(11) DEFAULT NULL,
  `total` double DEFAULT NULL,
  `cash` double DEFAULT NULL,
  `iva` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `is_draft` tinyint(1) NOT NULL DEFAULT 0,
  `stock_to_id` int(11) DEFAULT NULL,
  `stock_from_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` datetime NOT NULL,
  `detraccion` double DEFAULT NULL,
  `fecpago` date DEFAULT NULL,
  `entidad` varchar(255) DEFAULT NULL,
  `fecdetrac` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sistema_pensiones`
--

CREATE TABLE `sistema_pensiones` (
  `id` int(11) NOT NULL,
  `sistema_pension` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `spend`
--

CREATE TABLE `spend` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` double DEFAULT NULL,
  `box_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_principal` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subclases`
--

CREATE TABLE `subclases` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `id_clase` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sunat`
--

CREATE TABLE `sunat` (
  `id` int(11) NOT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  `periodo` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblmarcaciones_mar`
--

CREATE TABLE `tblmarcaciones_mar` (
  `id` int(11) NOT NULL,
  `mar_codigo` int(11) DEFAULT NULL,
  `tra_codigo` int(11) NOT NULL,
  `mar_tipo` varchar(20) DEFAULT NULL,
  `mar_fechahora` timestamp NULL DEFAULT NULL,
  `rel_codigo` int(11) DEFAULT 0,
  `mar_usrcreacion` int(11) DEFAULT NULL,
  `mar_feccreacion` timestamp NULL DEFAULT current_timestamp(),
  `mar_usrmodificacion` int(11) DEFAULT NULL,
  `mar_fecmodificacion` timestamp NULL DEFAULT NULL,
  `hor_codigo` int(11) DEFAULT NULL,
  `mar_latitud` decimal(10,0) DEFAULT NULL,
  `mar_longitud` decimal(10,0) DEFAULT NULL,
  `mar_direccion` varchar(100) DEFAULT NULL,
  `mar_obsevaciones` varchar(30) DEFAULT NULL,
  `mar_masiva` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cif`
--

CREATE TABLE `tbl_cif` (
  `id` int(11) NOT NULL,
  `cif_concepto` varchar(255) NOT NULL,
  `cif_mensual` double(14,2) NOT NULL,
  `cif_asignacion_planta` decimal(14,2) NOT NULL,
  `cif_dia_mes` decimal(14,2) NOT NULL,
  `cif_horas_dia` decimal(14,2) NOT NULL,
  `asignacion_planta_so` decimal(14,2) NOT NULL,
  `consumo_dia` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_costos_fijos`
--

CREATE TABLE `tbl_costos_fijos` (
  `id` int(11) NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `monto` decimal(14,2) NOT NULL,
  `dias_mes` int(11) NOT NULL,
  `horas_dia` int(11) NOT NULL,
  `monto_mes` decimal(14,2) NOT NULL,
  `monto_dia` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_gaf`
--

CREATE TABLE `tbl_gaf` (
  `id` int(11) NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `monto` decimal(14,2) NOT NULL,
  `dias_mes` int(11) NOT NULL,
  `horas_dia` int(11) NOT NULL,
  `monto_mes` decimal(14,2) NOT NULL,
  `monto_dia` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_gvm`
--

CREATE TABLE `tbl_gvm` (
  `id` int(11) NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `monto` decimal(14,2) NOT NULL,
  `dias_mes` int(11) NOT NULL,
  `horas_dia` int(11) NOT NULL,
  `monto_mes` decimal(14,2) NOT NULL,
  `monto_dia` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_maquina`
--

CREATE TABLE `tbl_maquina` (
  `maquina_id` int(11) NOT NULL,
  `maquina_codigo` varchar(255) DEFAULT NULL,
  `maquina_descripcion` varchar(255) DEFAULT NULL,
  `maquina_marca` varchar(255) DEFAULT NULL,
  `maquina_modelo` varchar(255) DEFAULT NULL,
  `maquina_serie` varchar(255) DEFAULT NULL,
  `maquina_marca_motor` varchar(255) DEFAULT NULL,
  `maquina_serie_motor` varchar(255) DEFAULT NULL,
  `maquina_exigencias` varchar(255) DEFAULT NULL,
  `maquina_voltaje` varchar(255) DEFAULT NULL,
  `maquina_tipo_corriente` varchar(255) DEFAULT NULL,
  `maquina_anio_compra` varchar(255) DEFAULT NULL,
  `maquina_vida_util` varchar(255) DEFAULT NULL,
  `maquina_imagen` varchar(255) DEFAULT NULL,
  `maquina_fecha_registro` date DEFAULT NULL,
  `maquina_ubicacion` varchar(255) NOT NULL,
  `maquina_tipo` varchar(255) NOT NULL,
  `maquina_estado` char(1) NOT NULL DEFAULT '1',
  `factura_compra` varchar(255) DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `proveedor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_maq_mtto`
--

CREATE TABLE `tbl_maq_mtto` (
  `maq_mtto_id` int(11) NOT NULL,
  `maq_mtto_fecha` date DEFAULT NULL,
  `maq_mtto_reponsable` varchar(255) DEFAULT NULL,
  `maq_mtto_tipo` varchar(255) DEFAULT NULL,
  `maq_mtto_costo` decimal(10,2) DEFAULT NULL,
  `maq_mtto_observacion` text DEFAULT NULL,
  `maquina_id` int(11) DEFAULT NULL,
  `tipo_mantenimiento` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_mod`
--

CREATE TABLE `tbl_mod` (
  `id` int(11) NOT NULL,
  `mod_mod` varchar(255) NOT NULL,
  `mod_sueldo_mes` decimal(14,2) NOT NULL,
  `mod_dia_mes` decimal(14,2) NOT NULL,
  `mod_horas_dia` decimal(14,2) NOT NULL,
  `mod_factor` decimal(14,2) NOT NULL,
  `sueldo_mes` decimal(14,2) NOT NULL,
  `sueldo_dia` decimal(14,2) NOT NULL,
  `sueldo_hora` decimal(14,2) NOT NULL,
  `sueldo_minuto` decimal(14,2) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_moi`
--

CREATE TABLE `tbl_moi` (
  `id` int(11) NOT NULL,
  `moi_concepto` varchar(255) NOT NULL,
  `moi_sueldo_mes` decimal(14,2) NOT NULL,
  `moi_n_trabajador` decimal(14,2) NOT NULL,
  `moi_dia_mes` decimal(14,2) NOT NULL,
  `moi_horas_dia` decimal(14,2) NOT NULL,
  `sueldo_mes` decimal(14,2) NOT NULL,
  `sueldo_dia` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_feriados`
--

CREATE TABLE `tipos_feriados` (
  `id` int(11) NOT NULL,
  `tipo_feriado` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_maquinas`
--

CREATE TABLE `tipos_maquinas` (
  `id` int(11) NOT NULL,
  `tipo_maquina` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_permisos`
--

CREATE TABLE `tipos_permisos` (
  `id` int(11) NOT NULL,
  `tipo` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_contrato`
--

CREATE TABLE `tipo_contrato` (
  `id` int(11) NOT NULL,
  `tipo_contrato` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cronogramas`
--

CREATE TABLE `tipo_cronogramas` (
  `id` int(11) NOT NULL,
  `tipo_cronograma` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_examen`
--

CREATE TABLE `tipo_examen` (
  `id` int(11) NOT NULL,
  `tipo_examen` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transportistas`
--

CREATE TABLE `transportistas` (
  `id` int(11) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `ruc` varchar(15) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `ubigeo` varchar(6) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `tipoDocumento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

CREATE TABLE `unidades` (
  `codigo` varchar(25) NOT NULL,
  `unidad` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(60) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `comision` float DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `kind` int(11) NOT NULL DEFAULT 1,
  `stock_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `celular` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones`
--

CREATE TABLE `vacaciones` (
  `id` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `periodo` varchar(50) NOT NULL,
  `fecha_salida` varchar(15) NOT NULL,
  `fecha_retorno` varchar(15) NOT NULL,
  `dias` varchar(10) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_cabecera`
--

CREATE TABLE `ventas_cabecera` (
  `codigo_venta` varchar(50) NOT NULL DEFAULT '',
  `tipo_documento` int(11) DEFAULT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `guia` varchar(50) DEFAULT '',
  `pedido_cod` varchar(255) DEFAULT NULL,
  `id_person` int(11) DEFAULT NULL,
  `id_forma_pago` int(11) DEFAULT NULL,
  `id_estado_pago` int(11) DEFAULT NULL,
  `id_estado_entrega` int(11) DEFAULT NULL,
  `almacen` varchar(50) DEFAULT NULL,
  `descuento` decimal(10,2) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `detraccion` varchar(5) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `igv` decimal(10,2) DEFAULT NULL,
  `detraccion_p` decimal(10,2) DEFAULT NULL,
  `igv_p` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `valor_pagar` double NOT NULL,
  `pagado` decimal(10,2) DEFAULT NULL,
  `a_cuenta` decimal(10,2) DEFAULT NULL,
  `usuario_creacion` varchar(8) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_emision` varchar(50) DEFAULT '',
  `tercera` decimal(10,2) DEFAULT NULL,
  `val_pagar` decimal(10,2) DEFAULT NULL,
  `fecha_pago` varchar(50) DEFAULT '',
  `entidad` varchar(50) DEFAULT '',
  `fecha_detraccion` varchar(50) DEFAULT '',
  `envio_sunat` char(1) DEFAULT '0',
  `nota_cred` varchar(255) DEFAULT NULL,
  `estado_anulado` int(11) DEFAULT NULL,
  `ruc_add` varchar(20) DEFAULT NULL,
  `correlativo_nc` int(11) DEFAULT NULL,
  `subtotal_2` decimal(10,2) DEFAULT NULL,
  `igv_2` decimal(10,2) DEFAULT NULL,
  `total_2` decimal(10,2) DEFAULT NULL,
  `fecha_anulacion` varchar(15) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `detraccion_paga` int(11) DEFAULT 0 COMMENT '1 si se ha pagado la detraccion',
  `fecha_vencimiento` varchar(50) DEFAULT NULL,
  `n_cuotas` int(11) DEFAULT 0,
  `incluye_igv` int(11) DEFAULT 0,
  `desc_descuento` varchar(255) DEFAULT NULL,
  `codigo_sunat_nc` int(11) DEFAULT NULL,
  `descripcion_sunat_nc` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_detalle`
--

CREATE TABLE `ventas_detalle` (
  `id` int(11) NOT NULL,
  `codigo_venta_cabecera` varchar(50) DEFAULT NULL,
  `id_producto` varchar(25) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `codigo_unidad` varchar(50) DEFAULT NULL,
  `precio_unitario` decimal(14,6) DEFAULT NULL,
  `precio_bordado` decimal(10,4) DEFAULT 0.0000,
  `unidad` varchar(255) DEFAULT NULL,
  `tipo` text DEFAULT NULL,
  `pedido_cod` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verificacion_competencias`
--

CREATE TABLE `verificacion_competencias` (
  `id` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `periodo` varchar(25) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `xx`
--

CREATE TABLE `xx` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `yy`
--

CREATE TABLE `yy` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actas_reunion`
--
ALTER TABLE `actas_reunion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `afps`
--
ALTER TABLE `afps`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aptitud`
--
ALTER TABLE `aptitud`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asistencias_cursos`
--
ALTER TABLE `asistencias_cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asistentes_capacitacion`
--
ALTER TABLE `asistentes_capacitacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aux`
--
ALTER TABLE `aux`
  ADD PRIMARY KEY (`i`);

--
-- Indices de la tabla `banco_cuenta`
--
ALTER TABLE `banco_cuenta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `box`
--
ALTER TABLE `box`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `caja_abono_mov`
--
ALTER TABLE `caja_abono_mov`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `caja_kardex`
--
ALTER TABLE `caja_kardex`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `caja_mov`
--
ALTER TABLE `caja_mov`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `capacitaciones`
--
ALTER TABLE `capacitaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `capacitaciones2`
--
ALTER TABLE `capacitaciones2`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `capacitacion_registro`
--
ALTER TABLE `capacitacion_registro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `capacitacion_registro_fecha`
--
ALTER TABLE `capacitacion_registro_fecha`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clases`
--
ALTER TABLE `clases`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `codigos_sunat`
--
ALTER TABLE `codigos_sunat`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `colaborador_horarios`
--
ALTER TABLE `colaborador_horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `complementos`
--
ALTER TABLE `complementos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras_detalle`
--
ALTER TABLE `compras_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `conductores`
--
ALTER TABLE `conductores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`short`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `contenido_biblioteca`
--
ALTER TABLE `contenido_biblioteca`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `costos`
--
ALTER TABLE `costos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `costos_materiales`
--
ALTER TABLE `costos_materiales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `costo_mano_directa`
--
ALTER TABLE `costo_mano_directa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `costo_servicio_externo`
--
ALTER TABLE `costo_servicio_externo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `costo_uso_taller`
--
ALTER TABLE `costo_uso_taller`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `cotizacion_detalle`
--
ALTER TABLE `cotizacion_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cronograma_registro`
--
ALTER TABLE `cronograma_registro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cronograma_registro_fecha`
--
ALTER TABLE `cronograma_registro_fecha`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentas_pagar`
--
ALTER TABLE `cuentas_pagar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `d`
--
ALTER TABLE `d`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `datos_ingreso`
--
ALTER TABLE `datos_ingreso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_civil`
--
ALTER TABLE `estado_civil`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `etapas`
--
ALTER TABLE `etapas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `examenes_medicos`
--
ALTER TABLE `examenes_medicos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `experiencia_laboral`
--
ALTER TABLE `experiencia_laboral`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `f`
--
ALTER TABLE `f`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `familiares`
--
ALTER TABLE `familiares`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `familias`
--
ALTER TABLE `familias`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `fecha_tecnica_archivo`
--
ALTER TABLE `fecha_tecnica_archivo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `feriados`
--
ALTER TABLE `feriados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ficha_tecnica`
--
ALTER TABLE `ficha_tecnica`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `formacion`
--
ALTER TABLE `formacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `guia_cabecera`
--
ALTER TABLE `guia_cabecera`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `guia_detalle`
--
ALTER TABLE `guia_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horario_dias`
--
ALTER TABLE `horario_dias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `identificacion`
--
ALTER TABLE `identificacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `insumos` ADD FULLTEXT KEY `id` (`id`);
ALTER TABLE `insumos` ADD FULLTEXT KEY `id_2` (`id`);

--
-- Indices de la tabla `insumos_2`
--
ALTER TABLE `insumos_2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_insumos_familia_codigo` (`familia`);

--
-- Indices de la tabla `insumo_stock`
--
ALTER TABLE `insumo_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `kind_doc`
--
ALTER TABLE `kind_doc`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mantenimiento_maquinas`
--
ALTER TABLE `mantenimiento_maquinas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mantenimiento_maquinas_fechas`
--
ALTER TABLE `mantenimiento_maquinas_fechas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `maquinas`
--
ALTER TABLE `maquinas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcaciones`
--
ALTER TABLE `marcaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_marcacion` (`dni`,`fecha_hora`,`reloj_ip`);

--
-- Indices de la tabla `medidas`
--
ALTER TABLE `medidas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menus_entidades`
--
ALTER TABLE `menus_entidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_entidad_1` (`idUsuario`),
  ADD KEY `fk_menu_entidad_menu_1` (`idMenu`);

--
-- Indices de la tabla `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modificaciones`
--
ALTER TABLE `modificaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `motivos_traslado`
--
ALTER TABLE `motivos_traslado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `operation`
--
ALTER TABLE `operation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_id` (`stock_id`),
  ADD KEY `stock_destination_id` (`stock_destination_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `operation_type_id` (`operation_type_id`),
  ADD KEY `sell_id` (`sell_id`);

--
-- Indices de la tabla `operation_type`
--
ALTER TABLE `operation_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `opiniones`
--
ALTER TABLE `opiniones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden_compra`
--
ALTER TABLE `orden_compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden_compra_detalle`
--
ALTER TABLE `orden_compra_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `order_cabecera`
--
ALTER TABLE `order_cabecera`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `order_detalle`
--
ALTER TABLE `order_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `order_detalle_2`
--
ALTER TABLE `order_detalle_2`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `p`
--
ALTER TABLE `p`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pasos`
--
ALTER TABLE `pasos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `sell_id` (`sell_id`),
  ADD KEY `payment_type_id` (`payment_type_id`);

--
-- Indices de la tabla `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `perfil_puesto`
--
ALTER TABLE `perfil_puesto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `precios`
--
ALTER TABLE `precios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `price`
--
ALTER TABLE `price`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `stock_id` (`stock_id`);

--
-- Indices de la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_detalle`
--
ALTER TABLE `produccion_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recomendaciones_sst`
--
ALTER TABLE `recomendaciones_sst`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro_dispositivo`
--
ALTER TABLE `registro_dispositivo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `relojes`
--
ALTER TABLE `relojes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `respuesta_sunat_guias`
--
ALTER TABLE `respuesta_sunat_guias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `retiro`
--
ALTER TABLE `retiro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `saving`
--
ALTER TABLE `saving`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sell`
--
ALTER TABLE `sell`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `d_id` (`d_id`),
  ADD KEY `box_id` (`box_id`),
  ADD KEY `operation_type_id` (`operation_type_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indices de la tabla `sistema_pensiones`
--
ALTER TABLE `sistema_pensiones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `spend`
--
ALTER TABLE `spend`
  ADD PRIMARY KEY (`id`),
  ADD KEY `box_id` (`box_id`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subclases`
--
ALTER TABLE `subclases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_clase_subclase_1` (`id_clase`);

--
-- Indices de la tabla `sunat`
--
ALTER TABLE `sunat`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tblmarcaciones_mar`
--
ALTER TABLE `tblmarcaciones_mar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_cif`
--
ALTER TABLE `tbl_cif`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_costos_fijos`
--
ALTER TABLE `tbl_costos_fijos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_gaf`
--
ALTER TABLE `tbl_gaf`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_gvm`
--
ALTER TABLE `tbl_gvm`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_maquina`
--
ALTER TABLE `tbl_maquina`
  ADD PRIMARY KEY (`maquina_id`);

--
-- Indices de la tabla `tbl_maq_mtto`
--
ALTER TABLE `tbl_maq_mtto`
  ADD PRIMARY KEY (`maq_mtto_id`),
  ADD KEY `fk_maq_mtto_maquina_id` (`maquina_id`);

--
-- Indices de la tabla `tbl_mod`
--
ALTER TABLE `tbl_mod`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_moi`
--
ALTER TABLE `tbl_moi`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_feriados`
--
ALTER TABLE `tipos_feriados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_maquinas`
--
ALTER TABLE `tipos_maquinas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_permisos`
--
ALTER TABLE `tipos_permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_contrato`
--
ALTER TABLE `tipo_contrato`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_cronogramas`
--
ALTER TABLE `tipo_cronogramas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_examen`
--
ALTER TABLE `tipo_examen`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transportistas`
--
ALTER TABLE `transportistas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas_cabecera`
--
ALTER TABLE `ventas_cabecera`
  ADD PRIMARY KEY (`codigo_venta`);

--
-- Indices de la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `verificacion_competencias`
--
ALTER TABLE `verificacion_competencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `xx`
--
ALTER TABLE `xx`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `yy`
--
ALTER TABLE `yy`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actas_reunion`
--
ALTER TABLE `actas_reunion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `afps`
--
ALTER TABLE `afps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `aptitud`
--
ALTER TABLE `aptitud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencias_cursos`
--
ALTER TABLE `asistencias_cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistentes_capacitacion`
--
ALTER TABLE `asistentes_capacitacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `aux`
--
ALTER TABLE `aux`
  MODIFY `i` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `banco_cuenta`
--
ALTER TABLE `banco_cuenta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `box`
--
ALTER TABLE `box`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja_abono_mov`
--
ALTER TABLE `caja_abono_mov`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja_kardex`
--
ALTER TABLE `caja_kardex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja_mov`
--
ALTER TABLE `caja_mov`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `capacitaciones`
--
ALTER TABLE `capacitaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `capacitaciones2`
--
ALTER TABLE `capacitaciones2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `capacitacion_registro`
--
ALTER TABLE `capacitacion_registro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `capacitacion_registro_fecha`
--
ALTER TABLE `capacitacion_registro_fecha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clases`
--
ALTER TABLE `clases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `codigos_sunat`
--
ALTER TABLE `codigos_sunat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `colaboradores`
--
ALTER TABLE `colaboradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `colaborador_horarios`
--
ALTER TABLE `colaborador_horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `complementos`
--
ALTER TABLE `complementos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compras_detalle`
--
ALTER TABLE `compras_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conductores`
--
ALTER TABLE `conductores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuration`
--
ALTER TABLE `configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contenido_biblioteca`
--
ALTER TABLE `contenido_biblioteca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `costos`
--
ALTER TABLE `costos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `costos_materiales`
--
ALTER TABLE `costos_materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `costo_mano_directa`
--
ALTER TABLE `costo_mano_directa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `costo_servicio_externo`
--
ALTER TABLE `costo_servicio_externo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `costo_uso_taller`
--
ALTER TABLE `costo_uso_taller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cotizacion_detalle`
--
ALTER TABLE `cotizacion_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cronograma_registro`
--
ALTER TABLE `cronograma_registro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cronograma_registro_fecha`
--
ALTER TABLE `cronograma_registro_fecha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuentas_pagar`
--
ALTER TABLE `cuentas_pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `d`
--
ALTER TABLE `d`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `datos_ingreso`
--
ALTER TABLE `datos_ingreso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `distrito`
--
ALTER TABLE `distrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_civil`
--
ALTER TABLE `estado_civil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `etapas`
--
ALTER TABLE `etapas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `examenes_medicos`
--
ALTER TABLE `examenes_medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `experiencia_laboral`
--
ALTER TABLE `experiencia_laboral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `f`
--
ALTER TABLE `f`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `familiares`
--
ALTER TABLE `familiares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fecha_tecnica_archivo`
--
ALTER TABLE `fecha_tecnica_archivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `feriados`
--
ALTER TABLE `feriados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ficha_tecnica`
--
ALTER TABLE `ficha_tecnica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formacion`
--
ALTER TABLE `formacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `guia_cabecera`
--
ALTER TABLE `guia_cabecera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `guia_detalle`
--
ALTER TABLE `guia_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `habilidades`
--
ALTER TABLE `habilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horario_dias`
--
ALTER TABLE `horario_dias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `identificacion`
--
ALTER TABLE `identificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `insumos_2`
--
ALTER TABLE `insumos_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `insumo_stock`
--
ALTER TABLE `insumo_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `kind_doc`
--
ALTER TABLE `kind_doc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mantenimiento_maquinas`
--
ALTER TABLE `mantenimiento_maquinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mantenimiento_maquinas_fechas`
--
ALTER TABLE `mantenimiento_maquinas_fechas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maquinas`
--
ALTER TABLE `maquinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcaciones`
--
ALTER TABLE `marcaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medidas`
--
ALTER TABLE `medidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menus_entidades`
--
ALTER TABLE `menus_entidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modificaciones`
--
ALTER TABLE `modificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `motivos_traslado`
--
ALTER TABLE `motivos_traslado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `operation`
--
ALTER TABLE `operation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `operation_type`
--
ALTER TABLE `operation_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opiniones`
--
ALTER TABLE `opiniones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_compra`
--
ALTER TABLE `orden_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_compra_detalle`
--
ALTER TABLE `orden_compra_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `order_detalle`
--
ALTER TABLE `order_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `order_detalle_2`
--
ALTER TABLE `order_detalle_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p`
--
ALTER TABLE `p`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pasos`
--
ALTER TABLE `pasos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfil_puesto`
--
ALTER TABLE `perfil_puesto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `precios`
--
ALTER TABLE `precios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `price`
--
ALTER TABLE `price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `produccion`
--
ALTER TABLE `produccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `produccion_detalle`
--
ALTER TABLE `produccion_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `puestos`
--
ALTER TABLE `puestos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recomendaciones_sst`
--
ALTER TABLE `recomendaciones_sst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registro_dispositivo`
--
ALTER TABLE `registro_dispositivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `relojes`
--
ALTER TABLE `relojes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuesta_sunat_guias`
--
ALTER TABLE `respuesta_sunat_guias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `retiro`
--
ALTER TABLE `retiro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `saving`
--
ALTER TABLE `saving`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sell`
--
ALTER TABLE `sell`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sistema_pensiones`
--
ALTER TABLE `sistema_pensiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `spend`
--
ALTER TABLE `spend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subclases`
--
ALTER TABLE `subclases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sunat`
--
ALTER TABLE `sunat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblmarcaciones_mar`
--
ALTER TABLE `tblmarcaciones_mar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_cif`
--
ALTER TABLE `tbl_cif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_costos_fijos`
--
ALTER TABLE `tbl_costos_fijos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_gaf`
--
ALTER TABLE `tbl_gaf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_gvm`
--
ALTER TABLE `tbl_gvm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_maquina`
--
ALTER TABLE `tbl_maquina`
  MODIFY `maquina_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_maq_mtto`
--
ALTER TABLE `tbl_maq_mtto`
  MODIFY `maq_mtto_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_mod`
--
ALTER TABLE `tbl_mod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_moi`
--
ALTER TABLE `tbl_moi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_feriados`
--
ALTER TABLE `tipos_feriados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_maquinas`
--
ALTER TABLE `tipos_maquinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_permisos`
--
ALTER TABLE `tipos_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_contrato`
--
ALTER TABLE `tipo_contrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_cronogramas`
--
ALTER TABLE `tipo_cronogramas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_examen`
--
ALTER TABLE `tipo_examen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transportistas`
--
ALTER TABLE `transportistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `verificacion_competencias`
--
ALTER TABLE `verificacion_competencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `xx`
--
ALTER TABLE `xx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `yy`
--
ALTER TABLE `yy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `insumos_2`
--
ALTER TABLE `insumos_2`
  ADD CONSTRAINT `fk_insumos_familia_codigo` FOREIGN KEY (`familia`) REFERENCES `familias` (`codigo`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
