-- phpMyAdmin SQL Dump
-- version 4.4.15.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-08-2016 a las 19:40:14
-- Versión del servidor: 5.6.28
-- Versión de PHP: 5.6.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `flexcms17`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activations`
--

CREATE TABLE IF NOT EXISTS `activations` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `activations`
--

INSERT INTO `activations` (`id`, `user_id`, `code`, `completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(6, 1, 'tN8O7WyAbtLjOG1qqxigRtMh12EWMo0i', 1, '2015-11-26 19:30:55', '2015-11-26 19:30:55', '2015-11-26 19:30:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `image` tinytext,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `addresses`
--

INSERT INTO `addresses` (`id`, `name`, `position`, `image`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Quito', 1, NULL, NULL, '2016-07-29 01:56:34', NULL),
(4, '3', 2, NULL, NULL, '2016-07-29 01:56:34', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adverts`
--

CREATE TABLE IF NOT EXISTS `adverts` (
  `id` int(10) unsigned NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `widget_id` int(11) DEFAULT NULL,
  `categories` varchar(255) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL,
  `file1` varchar(45) DEFAULT NULL,
  `file2` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `adverts`
--

INSERT INTO `adverts` (`id`, `type`, `widget_id`, `categories`, `name`, `date_start`, `date_end`, `enabled`, `css_class`, `file1`, `file2`) VALUES
(18, '3', NULL, '["163","164","165","166","172"]', 'Multiple', '2015-07-16 11:36:46', '2015-08-16 11:36:46', 1, '', '', NULL),
(19, '1', 45, 'null', 'Normal', '2015-07-16 11:39:25', '2015-08-16 11:39:25', 1, '', '', NULL),
(20, '2', 68, 'null', 'asd', '2015-07-16 11:50:56', '2015-08-16 11:50:56', 1, '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `date` date NOT NULL,
  `temporary` tinyint(1) DEFAULT '1',
  `css_class` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `calendar`
--

INSERT INTO `calendar` (`id`, `enabled`, `date`, `temporary`, `css_class`) VALUES
(1, 1, '2015-04-10', 1, NULL),
(2, 1, '2015-07-24', 1, NULL),
(4, 1, '2015-07-24', 1, NULL),
(5, 1, '1969-12-31', 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendar_activities`
--

CREATE TABLE IF NOT EXISTS `calendar_activities` (
  `id` int(11) NOT NULL,
  `time` time NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT NULL,
  `temporary` tinyint(1) DEFAULT '1',
  `enabled` tinyint(1) DEFAULT '1',
  `data` mediumtext COMMENT 'temporary field untill I finish translations and dynamic fields'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `css_class` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `private` tinyint(1) DEFAULT '0',
  `image` varchar(45) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `data` mediumtext,
  `temporary` tinyint(1) DEFAULT '1',
  `popup` tinyint(1) DEFAULT NULL,
  `type` varchar(45) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` tinyint(1) DEFAULT NULL,
  `group_visibility` int(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `tree`, `lft`, `rgt`, `css_class`, `enabled`, `private`, `image`, `url`, `data`, `temporary`, `popup`, `type`, `created_at`, `updated_at`, `deleted_at`, `group_visibility`) VALUES
(1, 1, 1, 22, NULL, 1, 0, NULL, NULL, NULL, 0, NULL, 'root', NULL, '2016-08-01 16:02:21', NULL, NULL),
(18, 1, 8, 21, '', 1, 0, '', '', '{"structure":[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[14],"modules":[]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[]}]}]}', 0, NULL, 'page', '2014-12-19 22:59:27', '2016-08-01 22:21:55', NULL, 0),
(23, 1, 6, 7, 'index', 1, 0, NULL, NULL, '{"structure":[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[20]}]}]}', 0, NULL, 'page', '2015-09-11 17:53:09', '2016-08-01 16:02:21', NULL, 0),
(28, 1, 17, 20, NULL, 1, 0, NULL, NULL, NULL, 0, NULL, 'catalog', '2016-08-01 21:38:22', '2016-08-01 22:21:55', NULL, NULL),
(30, 1, 18, 19, NULL, 1, 0, NULL, NULL, NULL, 0, NULL, 'catalog', '2016-08-01 22:21:39', '2016-08-01 22:21:55', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  `prevent_update` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL,
  `key` varchar(45) NOT NULL,
  `value` varchar(255) NOT NULL,
  `group` varchar(45) DEFAULT 'general',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`id`, `key`, `value`, `group`, `updated_at`) VALUES
(1, 'site_name', 'FlexCMS', 'general', '2016-07-27 21:44:56'),
(2, 'index_page_id', '18', 'general', '2016-07-27 21:44:56'),
(3, 'theme', 'destiny', 'general', '2016-07-27 21:44:56'),
(4, 'environment', 'development', 'general', '2016-07-27 21:44:56'),
(5, 'debug_bar', '1', 'general', '2016-07-27 21:44:56'),
(9, 'indent_html', '0', 'general', '2016-07-27 21:44:56'),
(10, 'automatic_activation', '1', 'auth', '2016-07-28 02:45:30'),
(11, 'login_identity', 'email', 'auth', '2016-07-27 21:44:56'),
(12, 'password_min_length', '1', 'auth', '2016-07-28 02:45:37'),
(13, 'password_max_length', '180', 'auth', '2016-07-28 02:45:37'),
(14, 'registered_role', 'registered', 'auth', '2016-07-28 02:45:30'),
(15, 'facebook_login', '0', 'auth', '2016-07-29 03:55:44'),
(16, 'facebook_app_id', '', 'auth', '2016-07-28 22:53:22'),
(17, 'facebook_app_secret', '', 'auth', '2016-07-28 22:53:22'),
(18, 'twitter_login', '0', 'auth', '2016-07-29 03:55:44'),
(19, 'twitter_consumer_key', '', 'auth', '2016-07-28 22:41:29'),
(20, 'twitter_consumer_secret', '', 'auth', '2016-07-28 22:41:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `image` tinytext,
  `temporary` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `image`, `temporary`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'Asd', 'asd@ass.com', NULL, 1, '2016-07-28 19:21:07', '2016-07-29 00:21:07', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL,
  `image` tinytext,
  `css_class` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `temporary` tinyint(1) DEFAULT '1',
  `important` tinyint(1) DEFAULT '0',
  `publication_start` datetime DEFAULT NULL,
  `publication_end` datetime DEFAULT NULL,
  `module` varchar(45) DEFAULT NULL,
  `data` varchar(45) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `content`
--

INSERT INTO `content` (`id`, `image`, `css_class`, `category_id`, `enabled`, `temporary`, `important`, `publication_start`, `publication_end`, `module`, `data`, `position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, NULL, '7', 23, 0, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, 2, '2016-07-30 04:16:55', '2016-08-01 20:46:00', NULL),
(3, NULL, '', 23, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, 1, '2016-08-01 20:35:09', '2016-08-01 20:46:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fields`
--

CREATE TABLE IF NOT EXISTS `fields` (
  `id` int(11) NOT NULL,
  `input_id` int(11) NOT NULL,
  `parent_id` varchar(45) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL,
  `section` varchar(45) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `label_enabled` tinyint(1) DEFAULT NULL,
  `required` tinyint(1) DEFAULT NULL,
  `validation` varchar(45) DEFAULT NULL,
  `data` tinytext,
  `view_in` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `fields`
--

INSERT INTO `fields` (`id`, `input_id`, `parent_id`, `position`, `css_class`, `section`, `name`, `label_enabled`, `required`, `validation`, `data`, `view_in`, `enabled`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 9, NULL, 1, '33', 'slider', '12', 1, NULL, NULL, NULL, NULL, 1, '2015-12-08 19:23:45', '2016-07-26 19:48:33', NULL),
(3, 9, NULL, 2, '3d', 'slider', '32', NULL, NULL, NULL, NULL, NULL, 1, '2015-12-08 20:07:03', '2016-07-26 19:48:33', NULL),
(15, 25, NULL, 1, '', 'user', 'Phone', 0, 0, '', '{"type":"profile","cart_order_col":"","two_checkout_name":""}', NULL, 0, '2016-07-27 17:58:46', '2016-07-28 18:12:19', NULL),
(20, 8, NULL, 1, '', 'contact', 'asd', NULL, 0, '', NULL, NULL, 0, '2016-07-28 22:26:06', '2016-07-28 22:27:40', NULL),
(21, 8, NULL, 2, '', 'contact', '2', NULL, 0, '', NULL, NULL, 0, '2016-07-28 22:26:19', '2016-07-28 22:27:40', NULL),
(29, 12, NULL, 1, '', 'product', 'Description', 1, NULL, NULL, '{"view_in_widget":true,"view_in_list":true,"view_in_cart":true,"view_in_filters":true}', NULL, 1, '2016-08-01 20:27:18', '2016-08-01 20:27:18', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `field_data`
--

CREATE TABLE IF NOT EXISTS `field_data` (
  `id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `field_id` int(11) NOT NULL,
  `section` varchar(45) NOT NULL,
  `data` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `field_data`
--

INSERT INTO `field_data` (`id`, `parent_id`, `field_id`, `section`, `data`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 1, 15, 'user', '33333aa', '2016-07-27 20:54:51', '2016-07-28 01:54:51', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `position` int(11) DEFAULT '0',
  `data` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `temporary` tinyint(1) DEFAULT NULL,
  `type` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL,
  `image_section_id` int(11) NOT NULL,
  `sufix` varchar(45) DEFAULT '_huge',
  `width` smallint(6) DEFAULT '500',
  `height` smallint(6) DEFAULT '300',
  `name` varchar(45) DEFAULT NULL,
  `position` tinyint(3) DEFAULT NULL,
  `crop` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image_sections`
--

CREATE TABLE IF NOT EXISTS `image_sections` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inputs`
--

CREATE TABLE IF NOT EXISTS `inputs` (
  `id` int(11) NOT NULL,
  `content` text CHARACTER SET latin1 NOT NULL,
  `input_type_id` int(11) NOT NULL,
  `section` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT 'donde se mostrara el input contacto , producto o ambos'
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `inputs`
--

INSERT INTO `inputs` (`id`, `content`, `input_type_id`, `section`) VALUES
(8, 'numero', 1, 'contact'),
(9, 'texto', 1, 'slider'),
(10, 'texto multilinea', 3, 'slider'),
(11, 'texto multilinea', 3, 'contact'),
(12, 'texto multilinea', 3, 'product'),
(13, 'texto', 1, 'contact'),
(14, 'texto', 1, 'product'),
(16, 'link', 1, 'product'),
(17, 'link', 1, 'contact'),
(18, 'tabla', 5, 'product'),
(20, 'archivos', 7, 'product'),
(22, 'precio', 1, 'product'),
(23, 'checkbox', 9, 'product'),
(24, 'checkbox', 9, 'contact'),
(25, 'texto', 1, 'user'),
(26, 'texto multilinea', 3, 'user'),
(27, 'texto', 1, 'mapas'),
(28, 'texto multilinea', 3, 'mapas'),
(29, 'listado', 12, 'product'),
(30, 'listado predefinido', 12, 'product'),
(31, 'fecha', 13, 'contact'),
(32, 'fecha', 13, 'user'),
(33, 'país', 12, 'user'),
(37, 'texto', 1, 'calendario'),
(38, 'texto multilinea', 3, 'calendario'),
(40, 'imágenes', 6, 'calendario'),
(41, 'archivos', 7, 'calendario'),
(42, 'tabla', 5, 'calendario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `input_type`
--

CREATE TABLE IF NOT EXISTS `input_type` (
  `id` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `input_type`
--

INSERT INTO `input_type` (`id`, `name`) VALUES
(1, 'input'),
(2, 'imagen'),
(3, 'textarea'),
(4, 'link'),
(5, 'tabla'),
(6, 'imagenes'),
(7, 'archivos'),
(8, 'mapa'),
(9, 'checkbox'),
(11, 'videos'),
(12, 'select'),
(13, 'fecha'),
(14, 'audio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` varchar(45) NOT NULL,
  `name` varchar(25) CHARACTER SET latin1 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `languages`
--

INSERT INTO `languages` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
('es', 'spanish', '2016-07-28 17:22:52', '2016-07-28 22:22:52', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maps`
--

CREATE TABLE IF NOT EXISTS `maps` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `temporary` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `maps`
--

INSERT INTO `maps` (`id`, `name`, `image`, `enabled`, `temporary`) VALUES
(1, 'World', 'jpg?1436827680', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `map_locations`
--

CREATE TABLE IF NOT EXISTS `map_locations` (
  `id` int(11) NOT NULL,
  `map_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `coords` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `temporary` tinyint(1) DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persistences`
--

CREATE TABLE IF NOT EXISTS `persistences` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `persistences`
--

INSERT INTO `persistences` (`id`, `user_id`, `code`, `created_at`, `updated_at`) VALUES
(1, 1, 'NYdVZKJf0OBVCuWprvSDHI2GDMoeZWgf', '2015-11-26 19:34:30', '2015-11-26 19:34:30'),
(2, 1, 'uS5iR0dSTqU7aCUOLpXv3ShfzIZwsPUL', '2015-11-26 19:34:59', '2015-11-26 19:34:59'),
(4, 1, 'vOnZWNpcCVe9pz48qDu8zcDIbk86rY6E', '2015-11-26 20:08:55', '2015-11-26 20:08:55'),
(5, 1, 'oDClqoTzIScou8jOfuOCA9fqSfT8BOkT', '2015-11-27 23:24:12', '2015-11-27 23:24:12'),
(6, 1, 'QJwFRcxnFJeBNY8rGSD8HIwxyFapi5lX', '2015-12-01 00:17:54', '2015-12-01 00:17:54'),
(7, 1, 'COKlkLPOX1es1UNEJC2dYj2xtvg5sKl4', '2015-12-01 20:03:21', '2015-12-01 20:03:21'),
(8, 1, 'z3je5UrYxUxM3Zkw9ssaKUMUutMXJDB8', '2015-12-02 21:02:12', '2015-12-02 21:02:12'),
(9, 1, 'ow1ZKkx1F1eUCD7RRAm7HWZkGAoq8jRm', '2015-12-03 03:58:52', '2015-12-03 03:58:52'),
(10, 1, 'auKfElvRrg56ZV2Gh22tFagkvyp4smf4', '2015-12-03 20:27:04', '2015-12-03 20:27:04'),
(11, 1, 'dXLpjFoKjVvHaUNOsp5MpTaslfoUzOy9', '2015-12-03 20:27:05', '2015-12-03 20:27:05'),
(12, 1, 'Yx73XpVCWhrpwVasBrTeXvHIlcEDpBXb', '2015-12-04 19:15:03', '2015-12-04 19:15:03'),
(13, 1, 'YB68pVZVceZfeFlApwCJO5XnR5R4bHcV', '2015-12-04 23:56:29', '2015-12-04 23:56:29'),
(14, 1, 'XYseWk4tDClJXWxg8URerzFs8z6HoRFk', '2015-12-08 03:49:13', '2015-12-08 03:49:13'),
(15, 1, 'pNqSj0YvV3ILlVEbdZT3NgLZvz54A491', '2015-12-08 22:56:34', '2015-12-08 22:56:34'),
(16, 1, '34QosvvXsONh5yUta3pGHDGcNO5MZwxo', '2016-05-20 04:05:57', '2016-05-20 04:05:57'),
(17, 1, 'BD4LvSr4nwJ3LT3MNvESDODIxPCUZlqE', '2016-07-26 21:03:13', '2016-07-26 21:03:13'),
(18, 1, 'Em8lD3b4oDVztJVzjv1VklmGa7NVajGC', '2016-07-27 22:18:08', '2016-07-27 22:18:08'),
(20, 1, 'qrcMLe8W6QTAdxzrH4tncb2DedSVgblf', '2016-07-27 22:18:56', '2016-07-27 22:18:56'),
(21, 1, '20FGNyFPPGjHRWANeolaFTDxaNFKA5dX', '2016-07-27 22:19:54', '2016-07-27 22:19:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `predefined_lists`
--

CREATE TABLE IF NOT EXISTS `predefined_lists` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `css_class` varchar(45) DEFAULT NULL,
  `position` tinyint(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL,
  `important` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 si es producto del dia 0 si no ',
  `category_id` int(11) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'si,no para mostrar consultas',
  `image` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `position` int(11) DEFAULT '0',
  `temporary` tinyint(1) DEFAULT NULL,
  `stock_quantity` smallint(5) DEFAULT '0',
  `stock_auto_allocate_status` tinyint(1) DEFAULT '1',
  `weight` double DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL,
  `visible_to` varchar(45) DEFAULT 'public',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reminders`
--

CREATE TABLE IF NOT EXISTS `reminders` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permissions` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'Superadmin', '{"admin":true,"module.structure":true,"module.advert":true,"module.auth":true,"module.contact":true,"module.mailchimp":true,"module.map":true,"module.slider":true,"module.theme":true,"module.language":true,"module.config":true}', '2015-11-26 15:05:41', '2016-07-28 23:08:55'),
(2, 'admin', 'Admin', '{"admin":true,"module.slider":true,"module.language":true,"module.config":true}', '2015-11-26 18:24:01', '2016-07-28 23:09:12'),
(3, 'registered', 'Registered', NULL, '2015-11-26 18:24:18', '2015-11-26 23:24:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_sections`
--

CREATE TABLE IF NOT EXISTS `role_sections` (
  `role_id` int(10) unsigned NOT NULL,
  `section_name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_users`
--

CREATE TABLE IF NOT EXISTS `role_users` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `role_users`
--

INSERT INTO `role_users` (`user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2015-11-27 18:49:32', '2015-11-27 18:49:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `controller` varchar(45) DEFAULT NULL,
  `position` tinyint(2) DEFAULT NULL,
  `view_menu` tinyint(1) DEFAULT '0',
  `desc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sections`
--

INSERT INTO `sections` (`id`, `name`, `controller`, `position`, `view_menu`, `desc`) VALUES
(1, 'Estructura', 'structure', 1, 1, 'Crear páginas, editar su estructura, añadir módulos'),
(2, 'Artículos', 'article', 2, 0, NULL),
(3, 'Preguntas Frecuentes', 'faq', 3, 0, NULL),
(4, 'Enlaces', 'link', 4, 0, NULL),
(5, 'Publicaciones', 'noticias', 5, 0, NULL),
(6, 'Banners', 'slideshow', 6, 1, 'Banners animados, slideshows'),
(7, 'Mapas', 'mapas', 7, 1, 'Mapas y ubicaciones'),
(8, 'Catálogo', 'catalog', 8, 0, NULL),
(9, 'Galería', 'gallery', 9, 0, NULL),
(10, 'Idiomas', 'idiomas', 10, 1, 'Editar idiomas para sitios multi-idiomas'),
(11, 'Contacto', 'contact', 11, 1, 'Formulario de contáctos, personas de contacto'),
(12, 'Usuarios', 'auth/admin/main', 12, 1, 'Usuarios del sistema: administradores, registrados, etc'),
(13, 'Estadísticas', 'estadisticas', 13, 1, 'Datos simples del uso del sitio web'),
(14, 'Configuración', 'config', 19, 1, 'Tamaños de imagenes, configuracion general'),
(15, 'Servicios', 'servicios', 14, 0, NULL),
(16, 'Publicidad', 'publicidad', 15, 1, 'Crear publicidad en varias secciones definidas'),
(17, 'Carrito de Compras', 'cart', 16, 0, NULL),
(18, 'Diseño', 'theme', 17, 1, 'Editar como se ve el sitio web'),
(19, 'Mailing', 'mailing', 18, 1, 'Enviar mails masivos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sliders`
--

CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT '800',
  `height` int(11) DEFAULT '600',
  `enabled` tinyint(1) DEFAULT '1',
  `temporary` tinyint(1) DEFAULT '1',
  `config` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sliders`
--

INSERT INTO `sliders` (`id`, `name`, `class`, `type`, `width`, `height`, `enabled`, `temporary`, `config`, `created_at`, `updated_at`, `deleted_at`) VALUES
(12, 'Swiper', '', 'Swiper', 1024, 261, 1, 0, '{\n    "initialSlide": 0,\n    "direction": "horizontal",\n    "speed": 300,\n    "autoplay": 0,\n    "autoplayDisableOnInteraction": true,\n    "watchSlidesProgress": false,\n    "watchVisibility": false,\n    "freeMode": false,\n    "freeModeMomentum": true,\n    "freeModeMomentumRatio": 1,\n    "freeModeMomentumBounce": true,\n    "freeModeMomentumBounceRatio": 1,\n    "effect": "coverflow",\n    "cube": {\n        "slideShadows": 1,\n        "shadow": 1,\n        "shadowOffset": 20,\n        "shadowScale": 0.94\n    },\n    "coverflow": {\n        "rotate": 50,\n        "stretch": 0,\n        "depth": 100,\n        "modifier": 1,\n        "slideShadows": 1\n    },\n    "spaceBetween": 0,\n    "slidesPerView": 3,\n    "slidesPerColumn": 1,\n    "slidesPerColumnFill": "column",\n    "slidesPerGroup": 1,\n    "centeredSlides": false,\n    "grabCursor": false,\n    "touchRatio": 1,\n    "touchAngle": 45,\n    "simulateTouch": true,\n    "shortSwipes": true,\n    "longSwipes": true,\n    "longSwipesRatio": 0.5,\n    "longSwipesMs": 300,\n    "followFinger": true,\n    "onlyExternal": false,\n    "threshold": 0,\n    "touchMoveStopPropagation": true,\n    "resistance": true,\n    "resistanceRatio": 0.85,\n    "preventClicks": true,\n    "preventClicksPropagation": true,\n    "releaseFormElements": true,\n    "slideToClickedSlide": false,\n    "allowSwipeToPrev": true,\n    "allowSwipeToNext": true,\n    "noSwiping": true,\n    "noSwipingClass": "swiper-no-swiping",\n    "swipeHandler": null,\n    "pagination": null,\n    "paginationHide": true,\n    "paginationClickable": false,\n    "nextButton": null,\n    "prevButton": null,\n    "scrollbar": null,\n    "scrollbarHide": true,\n    "keyboardControl": false,\n    "mousewheelControl": false,\n    "mousewheelForceToAxis": false,\n    "hashnav": false,\n    "updateOnImagesReady": true,\n    "loop": false,\n    "loopAdditionalSlides": 0,\n    "loopedSlides": null,\n    "control": null,\n    "controlInverse": false,\n    "observer": false,\n    "observeParents": false,\n    "slideClass": "swiper-slide",\n    "slideActiveClass": "swiper-slide-active",\n    "slideVisibleClass": "swiper-slide-visible",\n    "slideDuplicateClass": "swiper-slide-duplicate",\n    "slideNextClass": "swiper-slide-next",\n    "slidePrevClass": "swiper-slide-prev",\n    "wrapperClass": "swiper-wrapper",\n    "bulletClass": "swiper-pagination-bullet",\n    "bulletActiveClass": "swiper-pagination-bullet-active",\n    "paginationHiddenClass": "swiper-pagination-hidden",\n    "buttonDisabledClass": "swiper-button-disabled"\n}', NULL, '2016-07-26 19:59:33', NULL),
(73, 'bxSlider', '', 'bxSlider', 200, 200, 1, 0, '{\n    "mode": "horizontal",\n    "speed": 700,\n    "slideMargin": 0,\n    "startSlide": 0,\n    "randomStart": false,\n    "infiniteLoop": true,\n    "hideControlOnEnd": false,\n    "easing": "linear",\n    "captions": false,\n    "ticker": false,\n    "tickerHover": false,\n    "adaptiveHeight": true,\n    "adaptiveHeightSpeed": 500,\n    "video": false,\n    "preloadImages": "all",\n    "pager": true,\n    "pagerType": "full",\n    "pagerShortSeparator": " \\/ ",\n    "controls": true,\n    "nextText": "Next",\n    "prevText": "Prev",\n    "autoControls": false,\n    "startText": "Start",\n    "stopText": "Stop",\n    "auto": true,\n    "pause": 8000,\n    "autoStart": true,\n    "autoDirection": "next",\n    "autoHover": false,\n    "autoDelay": 0,\n    "minSlides": 1,\n    "maxSlides": 1,\n    "moveSlides": 0,\n    "slideWidth": 0\n}', NULL, NULL, NULL),
(75, 'Stack', '', 'StackGallery', 500, 500, 1, 0, '{\n    "slideshowLayout": "horizontalLeft",\n    "slideshowDirection": "forward",\n    "controlsAlignment": "rightCenter",\n    "fullSize": 1,\n    "slideshowDelay": 3000,\n    "slideshowOn": 1,\n    "useRotation": 1,\n    "swipeOn": 0\n}', NULL, NULL, NULL),
(87, NULL, NULL, NULL, 800, 600, 1, 1, NULL, '2016-07-28 17:00:03', '2016-07-28 17:00:03', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `category_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `throttle`
--

CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `throttle`
--

INSERT INTO `throttle` (`id`, `user_id`, `type`, `ip`, `created_at`, `updated_at`) VALUES
(1, NULL, 'global', NULL, '2015-11-25 23:06:01', '2015-11-25 23:06:01'),
(2, NULL, 'ip', '127.0.0.1', '2015-11-25 23:06:01', '2015-11-25 23:06:01'),
(3, NULL, 'global', NULL, '2015-11-25 23:06:38', '2015-11-25 23:06:38'),
(4, NULL, 'ip', '127.0.0.1', '2015-11-25 23:06:38', '2015-11-25 23:06:38'),
(5, NULL, 'global', NULL, '2015-11-25 23:07:32', '2015-11-25 23:07:32'),
(6, NULL, 'ip', '127.0.0.1', '2015-11-25 23:07:32', '2015-11-25 23:07:32'),
(7, NULL, 'global', NULL, '2015-11-25 23:07:41', '2015-11-25 23:07:41'),
(8, NULL, 'ip', '127.0.0.1', '2015-11-25 23:07:41', '2015-11-25 23:07:41'),
(9, NULL, 'global', NULL, '2015-11-25 23:09:19', '2015-11-25 23:09:19'),
(10, NULL, 'ip', '127.0.0.1', '2015-11-25 23:09:19', '2015-11-25 23:09:19'),
(11, NULL, 'global', NULL, '2015-11-25 23:12:04', '2015-11-25 23:12:04'),
(12, NULL, 'ip', '127.0.0.1', '2015-11-25 23:12:04', '2015-11-25 23:12:04'),
(13, NULL, 'global', NULL, '2015-11-26 00:08:27', '2015-11-26 00:08:27'),
(14, NULL, 'ip', '127.0.0.1', '2015-11-26 00:08:27', '2015-11-26 00:08:27'),
(15, NULL, 'global', NULL, '2015-11-26 00:42:40', '2015-11-26 00:42:40'),
(16, NULL, 'ip', '127.0.0.1', '2015-11-26 00:42:40', '2015-11-26 00:42:40'),
(17, NULL, 'global', NULL, '2015-11-26 00:43:06', '2015-11-26 00:43:06'),
(18, NULL, 'ip', '127.0.0.1', '2015-11-26 00:43:06', '2015-11-26 00:43:06'),
(19, NULL, 'global', NULL, '2015-11-26 00:52:09', '2015-11-26 00:52:09'),
(20, NULL, 'ip', '127.0.0.1', '2015-11-26 00:52:09', '2015-11-26 00:52:09'),
(21, NULL, 'global', NULL, '2015-11-26 00:53:06', '2015-11-26 00:53:06'),
(22, NULL, 'ip', '127.0.0.1', '2015-11-26 00:53:06', '2015-11-26 00:53:06'),
(23, NULL, 'global', NULL, '2015-11-26 00:58:04', '2015-11-26 00:58:04'),
(24, NULL, 'ip', '127.0.0.1', '2015-11-26 00:58:04', '2015-11-26 00:58:04'),
(25, NULL, 'global', NULL, '2015-11-26 00:59:00', '2015-11-26 00:59:00'),
(26, NULL, 'ip', '127.0.0.1', '2015-11-26 00:59:00', '2015-11-26 00:59:00'),
(27, NULL, 'global', NULL, '2015-11-26 00:59:12', '2015-11-26 00:59:12'),
(28, NULL, 'ip', '127.0.0.1', '2015-11-26 00:59:12', '2015-11-26 00:59:12'),
(29, NULL, 'global', NULL, '2015-11-26 01:06:59', '2015-11-26 01:06:59'),
(30, NULL, 'ip', '127.0.0.1', '2015-11-26 01:06:59', '2015-11-26 01:06:59'),
(31, NULL, 'global', NULL, '2015-11-26 01:07:16', '2015-11-26 01:07:16'),
(32, NULL, 'ip', '127.0.0.1', '2015-11-26 01:07:16', '2015-11-26 01:07:16'),
(33, NULL, 'global', NULL, '2015-11-26 01:08:39', '2015-11-26 01:08:39'),
(34, NULL, 'ip', '127.0.0.1', '2015-11-26 01:08:39', '2015-11-26 01:08:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `language_id` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL COMMENT 'widget, content, field',
  `data` mediumtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `translations`
--

INSERT INTO `translations` (`id`, `parent_id`, `language_id`, `type`, `data`, `created_at`, `updated_at`, `deleted_at`) VALUES
(34, 23, 'es', 'page', '{"name":"Inicio","menu_name":"Inicio","meta_keywords":["Meta","key","word","split work"],"meta_description":"Meta Description","meta_title":"Meta title"}', '2016-07-28 18:05:14', '2016-07-28 18:05:14', NULL),
(35, 18, 'es', 'page', '{"name":"Catalog","menu_name":"Catalog","meta_keywords":[""],"meta_description":"","meta_title":""}', '2016-07-28 18:06:28', '2016-08-01 16:13:49', NULL),
(36, 15, 'es', 'user_field', '{"label":"Phone","placeholder":""}', '2016-07-28 18:12:19', '2016-07-28 18:12:19', NULL),
(37, 1, 'es', 'address', '{"address":"asd"}', '2016-07-28 20:42:29', '2016-07-28 20:42:29', NULL),
(38, 7, 'es', 'address', '{"address":"dsa"}', '2016-07-28 21:11:43', '2016-07-28 21:11:43', NULL),
(39, 8, 'es', 'address', '{"address":"dd"}', '2016-07-28 21:12:15', '2016-07-28 21:12:15', NULL),
(43, 20, 'es', 'contact_field', '{"label":"","placeholder":""}', '2016-07-28 22:26:06', '2016-07-28 22:26:06', NULL),
(44, 21, 'es', 'contact_field', '{"label":"","placeholder":""}', '2016-07-28 22:26:19', '2016-07-28 22:26:19', NULL),
(45, 2, 'es', 'content', '{"name":"1","content":"2","meta_keywords":["key","word"],"meta_description":"6","meta_title":"5"}', '2016-07-29 23:16:55', '2016-08-01 15:33:50', NULL),
(46, 3, 'es', 'content', '{"name":"2","content":"","meta_keywords":[""],"meta_description":"","meta_title":""}', '2016-08-01 15:35:10', '2016-08-01 15:35:10', NULL),
(54, 29, 'es', 'product_field', '{"label":"Description"}', '2016-08-01 20:27:18', '2016-08-01 20:27:18', NULL),
(55, 28, 'es', 'catalog', '{"name":"1","description":"2a"}', '2016-08-01 21:38:23', '2016-08-01 22:04:15', NULL),
(57, 30, 'es', 'catalog', '{"name":"2","description":"2"}', '2016-08-01 22:21:39', '2016-08-01 22:21:39', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permissions` text,
  `last_login` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `image_extension` varchar(45) DEFAULT NULL,
  `image_coord` varchar(255) DEFAULT NULL,
  `temporary` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `last_login`, `first_name`, `last_name`, `image_extension`, `image_coord`, `temporary`, `created_at`, `updated_at`) VALUES
(1, 'miguel@dejabu.ec', '$2y$10$r9QMNsbMaBWJDMpg8v/P5OFRxo77fah0pZ02gPrKkYezJFXCqefu6', NULL, '2016-07-27 22:19:55', 'Miguel', 'Suarez', NULL, NULL, 0, '2015-11-25 22:41:58', '2016-07-28 01:57:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `widgets`
--

CREATE TABLE IF NOT EXISTS `widgets` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `view` varchar(45) DEFAULT 'default_view.php',
  `class` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `type` varchar(45) DEFAULT NULL,
  `data` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `widgets`
--

INSERT INTO `widgets` (`id`, `category_id`, `view`, `class`, `enabled`, `type`, `data`, `created_at`, `updated_at`, `deleted_at`) VALUES
(9, 22, 'default_view.php', NULL, 1, 'Content', NULL, '2015-12-02 00:36:20', '2015-12-02 00:36:20', NULL),
(10, 22, 'default_view.php', NULL, 1, 'Content', NULL, '2015-12-02 00:36:27', '2015-12-02 00:36:27', NULL),
(11, 22, 'default_view.php', NULL, 1, 'Content', NULL, '2015-12-02 00:36:31', '2015-12-02 00:36:31', NULL),
(14, 18, 'default_view.php', NULL, 1, 'Content', '{"content_type":"catalog"}', '2015-12-03 23:33:07', '2015-12-03 23:33:17', NULL),
(19, 22, 'default_view.php', NULL, 1, 'Content', 'null', '2015-12-04 00:30:16', '2015-12-04 00:30:16', NULL),
(20, 23, 'default_view.php', NULL, 1, 'Content', '{"content_type":"content","settings":{"list_view":"list_news_view.php","detail_view":"detail_news_view.php","order":"manual","pagination":false,"quantity":"8"}}', '2016-07-30 00:06:48', '2016-08-01 20:54:53', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activations`
--
ALTER TABLE `activations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_activations_user_id_idx` (`user_id`);

--
-- Indices de la tabla `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `adverts`
--
ALTER TABLE `adverts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publicidadTipoId_fk_idx` (`type`);

--
-- Indices de la tabla `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `calendar_activities`
--
ALTER TABLE `calendar_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_calendar_id` (`calendar_id`),
  ADD KEY `fk_activities_placeId_idx` (`place_id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indices de la tabla `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paginaId` (`category_id`),
  ADD KEY `publicacionHabilitado` (`enabled`);

--
-- Indices de la tabla `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campoId` (`input_id`),
  ADD KEY `inputId_bc_idx` (`input_id`);

--
-- Indices de la tabla `field_data`
--
ALTER TABLE `field_data`
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `key_unique_field_data` (`parent_id`,`field_id`,`section`),
  ADD KEY `fk_field_data_field_id_idx` (`field_id`);

--
-- Indices de la tabla `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `descargaCategoriaId_d` (`parent_id`),
  ADD KEY `descargaCategoriaId_idx` (`parent_id`);

--
-- Indices de la tabla `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seccionId_i` (`image_section_id`);

--
-- Indices de la tabla `image_sections`
--
ALTER TABLE `image_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adminSeccionId_imgr` (`section_id`) USING BTREE,
  ADD KEY `adminSeccionId_is` (`section_id`) USING BTREE;

--
-- Indices de la tabla `inputs`
--
ALTER TABLE `inputs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_input_contacto_inputs_rel1` (`id`),
  ADD KEY `inputTipoId` (`input_type_id`),
  ADD KEY `inputTipoId_i` (`input_type_id`);

--
-- Indices de la tabla `input_type`
--
ALTER TABLE `input_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_input_tipo_input1` (`id`);

--
-- Indices de la tabla `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indices de la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `maps`
--
ALTER TABLE `maps`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `map_locations`
--
ALTER TABLE `map_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapaId_idx` (`map_id`),
  ADD KEY `mapaId_mu` (`map_id`);

--
-- Indices de la tabla `persistences`
--
ALTER TABLE `persistences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_UNIQUE` (`code`),
  ADD KEY `fk_persistences_user_id_idx` (`user_id`);

--
-- Indices de la tabla `predefined_lists`
--
ALTER TABLE `predefined_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productoCampoId_pclp_idx` (`field_id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoriaId_idx` (`category_id`);

--
-- Indices de la tabla `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reminders_user_id_idx` (`user_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_UNIQUE` (`slug`);

--
-- Indices de la tabla `role_sections`
--
ALTER TABLE `role_sections`
  ADD PRIMARY KEY (`role_id`,`section_name`);

--
-- Indices de la tabla `role_users`
--
ALTER TABLE `role_users`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `fk_role_users_role_id_idx` (`role_id`);

--
-- Indices de la tabla `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estadisticaUserIP` (`ip`),
  ADD KEY `estadisticaFecha` (`date`),
  ADD KEY `paginaId_e_idx` (`category_id`);

--
-- Indices de la tabla `throttle`
--
ALTER TABLE `throttle`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_UNIQUE` (`user_id`);

--
-- Indices de la tabla `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_unique_translations` (`parent_id`,`language_id`,`type`),
  ADD KEY `fk_translations_1_idx` (`parent_id`),
  ADD KEY `fk_translations_language_id_idx` (`language_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_email_unique` (`email`);

--
-- Indices de la tabla `widgets`
--
ALTER TABLE `widgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paginaId_m` (`category_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activations`
--
ALTER TABLE `activations`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `adverts`
--
ALTER TABLE `adverts`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `calendar`
--
ALTER TABLE `calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `calendar_activities`
--
ALTER TABLE `calendar_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT de la tabla `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT de la tabla `field_data`
--
ALTER TABLE `field_data`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `image_sections`
--
ALTER TABLE `image_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `inputs`
--
ALTER TABLE `inputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT de la tabla `input_type`
--
ALTER TABLE `input_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `map_locations`
--
ALTER TABLE `map_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `persistences`
--
ALTER TABLE `persistences`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `predefined_lists`
--
ALTER TABLE `predefined_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT de la tabla `throttle`
--
ALTER TABLE `throttle`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT de la tabla `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `widgets`
--
ALTER TABLE `widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activations`
--
ALTER TABLE `activations`
  ADD CONSTRAINT `fk_activations_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `fk_category_id_content` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `field_data`
--
ALTER TABLE `field_data`
  ADD CONSTRAINT `fk_field_data_field_id` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `fk_language_translarions_id` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
