-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-11-2015 a las 21:54:46
-- Versión del servidor: 5.5.44-37.3-log
-- Versión de PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `datalloy_potenciate`
--
CREATE DATABASE IF NOT EXISTS `datalloy_potenciate` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `datalloy_potenciate`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

DROP TABLE IF EXISTS `cursos`;
CREATE TABLE IF NOT EXISTS `cursos` (
  `CODIGO` int(255) NOT NULL AUTO_INCREMENT,
  `FECHA` date NOT NULL,
  `HORA_INICIO` time NOT NULL,
  `HORA_FIN` time NOT NULL,
  `DESCRIPCION` varchar(200) NOT NULL,
  `TIPO` varchar(50) DEFAULT NULL,
  `CUPO` int(11) DEFAULT NULL,
  `PONENTE` varchar(50) DEFAULT NULL,
  `LUGAR` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`CODIGO`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
CREATE TABLE IF NOT EXISTS `estudiantes` (
  `CI` varchar(13) NOT NULL,
  `NOMBRE` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `APELLIDO` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `FACULTAD` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `MAIL` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `FECHAI` datetime DEFAULT NULL,
  PRIMARY KEY (`CI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facultades`
--

DROP TABLE IF EXISTS `facultades`;
CREATE TABLE IF NOT EXISTS `facultades` (
  `COD_F` int(11) NOT NULL AUTO_INCREMENT,
  `FACUL` varchar(60) NOT NULL,
  PRIMARY KEY (`COD_F`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugares`
--

DROP TABLE IF EXISTS `lugares`;
CREATE TABLE IF NOT EXISTS `lugares` (
  `COD_L` bigint(20) NOT NULL AUTO_INCREMENT,
  `REFERENCIA` varchar(200) DEFAULT NULL,
  `DIRECCION` varchar(200) DEFAULT NULL,
  `LONGITUD` float DEFAULT NULL,
  `LATITUD` float DEFAULT NULL,
  PRIMARY KEY (`COD_L`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

DROP TABLE IF EXISTS `registros`;
CREATE TABLE IF NOT EXISTS `registros` (
  `CODIGO` int(11) NOT NULL,
  `CI` varchar(13) NOT NULL,
  `FECHA_INSCRIPCION` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ASISTENCIA` tinyint(1) DEFAULT NULL,
  KEY `FK_CONTIENE` (`CODIGO`),
  KEY `FK_SE` (`CI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `USUARIO` varchar(12) CHARACTER SET utf8 NOT NULL,
  `CONTRASENA` varchar(128) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `registros`
--
ALTER TABLE `registros`
  ADD CONSTRAINT `FK_CONTIENE` FOREIGN KEY (`CODIGO`) REFERENCES `cursos` (`CODIGO`),
  ADD CONSTRAINT `FK_SE` FOREIGN KEY (`CI`) REFERENCES `estudiantes` (`CI`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
