-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 17-02-2017 a las 02:57:06
-- Versión del servidor: 5.6.31
-- Versión de PHP: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `flexcms`
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
(23, 1, 6, 7, 'index', 1, 0, NULL, NULL, '{"structure":[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[20]}]}]}', 0, 0, 'page', '2015-09-11 17:53:09', '2017-01-19 20:41:10', NULL, 0),
(28, 1, 17, 20, NULL, 1, 0, NULL, NULL, NULL, 0, NULL, 'catalog', '2016-08-01 21:38:22', '2016-08-01 22:21:55', NULL, NULL),
(30, 1, 18, 19, NULL, 1, 0, NULL, NULL, NULL, 0, NULL, 'catalog', '2016-08-01 22:21:39', '2016-09-15 02:08:59', NULL, 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`id`, `key`, `value`, `group`, `updated_at`) VALUES
(1, 'site_name', 'FlexCMS', 'general', '2016-07-27 21:44:56'),
(2, 'index_page_id', '23', 'general', '2016-12-27 21:31:04'),
(3, 'theme', 'destiny', 'general', '2017-01-20 01:39:40'),
(4, 'environment', 'development', 'general', '2017-01-19 23:35:51'),
(5, 'debug_bar', 'false', 'general', '2017-01-19 23:31:36'),
(9, 'indent_html', 'false', 'general', '2017-01-19 23:35:51'),
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
(20, 'twitter_consumer_secret', '', 'auth', '2016-07-28 22:41:29'),
(21, 'menu_show_categories', '1', 'catalog', '2016-09-15 07:36:17'),
(22, 'menu_show_products', '1', 'catalog', '2016-09-15 07:36:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL,
  `css_class` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `temporary` tinyint(1) DEFAULT '1',
  `important` tinyint(1) DEFAULT '0',
  `timezone` varchar(45) DEFAULT NULL,
  `publication_start` datetime DEFAULT NULL,
  `publication_end` datetime DEFAULT NULL,
  `module` varchar(45) DEFAULT NULL,
  `data` varchar(45) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `content`
--

INSERT INTO `content` (`id`, `css_class`, `category_id`, `enabled`, `temporary`, `important`, `timezone`, `publication_start`, `publication_end`, `module`, `data`, `position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(60, '', 23, 1, 1, 0, 'America/Guayaquil', NULL, NULL, NULL, NULL, 9, '2017-01-19 22:18:27', '2017-02-17 00:39:31', NULL),
(61, '', 23, 1, 1, 0, 'America/Guayaquil', NULL, NULL, NULL, NULL, 8, '2017-01-19 22:20:06', '2017-02-17 00:39:31', NULL),
(62, '', 23, 1, 1, 0, 'America/Guayaquil', NULL, NULL, NULL, NULL, 7, '2017-01-19 22:35:44', '2017-02-17 00:39:31', NULL),
(63, '', 23, 1, 1, 0, 'America/Guayaquil', NULL, NULL, NULL, NULL, 6, '2017-01-19 22:40:20', '2017-02-17 00:39:31', NULL),
(64, '', 23, 1, 1, 0, 'America/Guayaquil', NULL, NULL, NULL, NULL, 5, '2017-01-19 22:41:39', '2017-01-28 02:15:28', NULL),
(65, '', 23, 1, 1, 0, 'America/Guayaquil', '2017-01-19 17:18:27', '2017-01-19 17:18:27', NULL, NULL, 4, '2017-01-19 22:42:17', '2017-01-28 02:15:28', NULL),
(66, '', 23, 1, 1, 0, 'America/Guayaquil', NULL, NULL, NULL, NULL, 3, '2017-01-28 01:24:54', '2017-01-28 02:15:28', NULL),
(67, '', 23, 1, 1, 0, 'America/Guayaquil', '2017-01-19 17:18:27', '2017-01-19 17:18:27', NULL, NULL, 1, '2017-01-28 01:26:31', '2017-02-17 07:56:38', NULL),
(68, '', 23, 1, 1, 0, 'America/Guayaquil', '2017-01-19 17:18:27', '2017-01-19 17:18:27', NULL, NULL, 2, '2017-01-28 02:10:52', '2017-02-17 02:57:56', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `fields`
--

INSERT INTO `fields` (`id`, `input_id`, `parent_id`, `position`, `css_class`, `section`, `name`, `label_enabled`, `required`, `validation`, `data`, `view_in`, `enabled`, `created_at`, `updated_at`, `deleted_at`) VALUES
(15, 13, '1', 1, NULL, 'form', NULL, 1, 1, 'integer', NULL, NULL, 1, '2017-01-13 21:18:42', '2017-01-18 20:36:41', NULL),
(16, 13, '1', 3, NULL, 'form', NULL, 1, 1, 'email', NULL, NULL, 1, '2017-01-13 22:23:19', '2017-01-18 20:36:41', NULL),
(17, 11, '1', 4, NULL, 'form', NULL, 0, 1, NULL, NULL, NULL, 1, '2017-01-16 19:04:42', '2017-01-18 20:36:41', NULL),
(19, 13, '11', 2, NULL, 'form', NULL, 0, 0, NULL, NULL, NULL, 1, '2017-01-17 17:03:58', '2017-01-17 17:03:58', NULL),
(21, 13, '13', 2, NULL, 'form', NULL, 0, 0, NULL, NULL, NULL, 1, '2017-01-17 17:09:27', '2017-01-17 17:09:27', NULL),
(23, 13, '14', 2, NULL, 'form', NULL, 0, 0, NULL, NULL, NULL, 1, '2017-01-17 17:09:57', '2017-01-17 17:09:57', NULL),
(24, 13, '14', 3, NULL, 'form', NULL, 0, 0, NULL, NULL, NULL, 1, '2017-01-17 17:09:58', '2017-01-17 17:09:58', NULL),
(25, 13, '15', 1, NULL, 'form', NULL, 0, 0, NULL, NULL, NULL, 1, '2017-01-17 20:58:47', '2017-01-17 20:58:47', NULL),
(26, 13, '15', 2, NULL, 'form', NULL, 0, 0, NULL, NULL, NULL, 1, '2017-01-17 20:58:47', '2017-01-17 20:58:47', NULL),
(27, 13, '15', 3, NULL, 'form', NULL, 0, 0, NULL, NULL, NULL, 1, '2017-01-17 20:58:47', '2017-01-17 20:58:47', NULL),
(37, 43, '1', 2, NULL, 'form', NULL, 0, 0, NULL, NULL, NULL, 1, '2017-01-18 16:34:22', '2017-01-18 20:36:41', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `section_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `position` int(11) DEFAULT '0',
  `data` text,
  `link` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `type` varchar(10) DEFAULT NULL,
  `mime_type` varchar(45) DEFAULT NULL,
  `file_ext` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `files`
--

INSERT INTO `files` (`id`, `parent_id`, `section_id`, `name`, `position`, `data`, `link`, `date`, `enabled`, `type`, `mime_type`, `file_ext`, `created_at`, `updated_at`, `deleted_at`) VALUES
(20, 67, 1, 'rude-500', 1, '{"coords":{"canvasSize":{"w":450,"h":450},"areaCoords":{"x":0,"y":112.5,"w":450,"h":225},"cropWidth":450,"cropHeight":225,"cropTop":112.5,"cropLeft":0,"cropImageWidth":500,"cropImageHeight":250,"cropImageTop":125,"cropImageLeft":0},"colors":{"dominantColor":[49,40,59],"paletteColor":[[205,190,210],[39,31,47],[122,103,135],[93,77,113],[146,127,168],[81,65,91],[156,156,172],[94,92,90],[164,116,100]],"textColor":"light"},"image_alt":"image"}', NULL, NULL, 1, 'image', 'image/jpeg', '.jpg', '2017-02-17 07:56:38', '2017-02-17 07:56:38', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forms`
--

CREATE TABLE IF NOT EXISTS `forms` (
  `id` int(10) unsigned NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `forms`
--

INSERT INTO `forms` (`id`, `email`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'miguel@dejabu.ec', 'Contactos', '2016-09-18 08:30:57', '2017-01-13 23:26:46', NULL),
(15, 'dsa', 'asd', '2017-01-18 01:57:16', '2017-01-18 01:57:16', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images_config`
--

CREATE TABLE IF NOT EXISTS `images_config` (
  `id` int(11) NOT NULL,
  `image_section_id` int(11) unsigned NOT NULL,
  `sufix` varchar(45) DEFAULT '_huge',
  `width` smallint(6) DEFAULT '500',
  `height` smallint(6) DEFAULT '300',
  `name` varchar(45) DEFAULT NULL,
  `position` tinyint(3) DEFAULT NULL,
  `crop` tinyint(1) DEFAULT '0',
  `force_jpg` tinyint(1) DEFAULT NULL,
  `optimize_original` tinyint(1) DEFAULT NULL,
  `background_color` varchar(45) DEFAULT NULL,
  `quality` decimal(3,0) DEFAULT NULL,
  `restrict_proportions` tinyint(1) DEFAULT NULL,
  `watermark` tinyint(1) DEFAULT NULL,
  `watermark_file_id` int(11) DEFAULT NULL,
  `watermark_position` varchar(45) DEFAULT NULL,
  `watermark_alpha` decimal(3,0) DEFAULT NULL,
  `watermark_repeat` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `images_config`
--

INSERT INTO `images_config` (`id`, `image_section_id`, `sufix`, `width`, `height`, `name`, `position`, `crop`, `force_jpg`, `optimize_original`, `background_color`, `quality`, `restrict_proportions`, `watermark`, `watermark_file_id`, `watermark_position`, `watermark_alpha`, `watermark_repeat`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '_large', 400, 200, 'Large', 1, 1, 1, 1, NULL, '80', 1, NULL, NULL, NULL, NULL, NULL, '2017-01-27 22:53:20', '2017-02-03 22:13:20', NULL),
(2, 1, '_medium', NULL, NULL, 'Medium', 2, 0, 0, 1, NULL, '80', 0, NULL, NULL, NULL, NULL, NULL, '2017-01-28 00:12:05', '2017-02-03 22:13:35', NULL),
(3, 2, '3', 500, 300, '3', 4, 0, 1, 1, NULL, '80', 1, NULL, NULL, NULL, NULL, NULL, '2017-01-28 00:18:04', '2017-01-28 03:20:52', NULL),
(4, 1, '_small', NULL, NULL, 'Small', 5, 0, 1, 1, NULL, '80', 0, NULL, NULL, NULL, NULL, NULL, '2017-01-28 00:19:01', '2017-02-03 22:13:47', NULL),
(5, 2, '5', NULL, NULL, '5', 6, 0, 1, 1, NULL, '80', 0, NULL, NULL, NULL, NULL, NULL, '2017-01-28 00:20:00', '2017-01-28 00:53:35', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image_sections`
--

CREATE TABLE IF NOT EXISTS `image_sections` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `section` varchar(45) NOT NULL,
  `multiple_upload` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `image_sections`
--

INSERT INTO `image_sections` (`id`, `name`, `section`, `multiple_upload`) VALUES
(1, 'Main', 'content', 0),
(2, 'Gallery', 'content', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inputs`
--

CREATE TABLE IF NOT EXISTS `inputs` (
  `id` int(11) NOT NULL,
  `content` text CHARACTER SET latin1 NOT NULL,
  `input_type_id` int(11) NOT NULL,
  `section` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT 'donde se mostrara el input contacto , producto o ambos'
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `inputs`
--

INSERT INTO `inputs` (`id`, `content`, `input_type_id`, `section`) VALUES
(8, 'numero', 1, 'form'),
(9, 'texto', 1, 'slider'),
(10, 'texto multilinea', 3, 'slider'),
(11, 'texto multilinea', 3, 'form'),
(12, 'texto multilinea', 3, 'product'),
(13, 'texto', 1, 'form'),
(14, 'texto', 1, 'product'),
(16, 'link', 1, 'product'),
(17, 'link', 1, 'form'),
(18, 'tabla', 5, 'product'),
(20, 'archivos', 7, 'product'),
(22, 'precio', 1, 'product'),
(23, 'checkbox', 9, 'product'),
(24, 'checkbox', 9, 'form'),
(25, 'texto', 1, 'user'),
(26, 'texto multilinea', 3, 'user'),
(27, 'texto', 1, 'mapas'),
(28, 'texto multilinea', 3, 'mapas'),
(29, 'listado', 12, 'product'),
(30, 'listado predefinido', 12, 'product'),
(31, 'fecha', 13, 'form'),
(32, 'fecha', 13, 'user'),
(33, 'país', 12, 'user'),
(37, 'texto', 1, 'calendario'),
(38, 'texto multilinea', 3, 'calendario'),
(40, 'imágenes', 6, 'calendario'),
(41, 'archivos', 7, 'calendario'),
(42, 'tabla', 5, 'calendario'),
(43, 'archivo', 7, 'form'),
(44, 'nombre', 1, 'form');

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
  `id` int(11) NOT NULL,
  `name` varchar(25) CHARACTER SET latin1 NOT NULL,
  `slug` varchar(45) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `languages`
--

INSERT INTO `languages` (`id`, `name`, `slug`, `position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Spanish', 'es', '1', '2017-01-24 16:35:58', '2017-01-24 21:35:58', NULL),
(2, 'English', 'en', '2', '2017-01-24 16:35:58', '2017-01-24 21:35:58', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

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
(21, 1, '20FGNyFPPGjHRWANeolaFTDxaNFKA5dX', '2016-07-27 22:19:54', '2016-07-27 22:19:54'),
(22, 1, 'TdGu3NZqGVBAzMz7eAGbFC5OCUVFCAxK', '2016-09-04 00:17:14', '2016-09-04 00:17:14'),
(23, 1, 'dubwgrGdDbrp1kIsyZiAinJaOHZk8YGd', '2016-11-12 03:10:40', '2016-11-12 03:10:40'),
(24, 1, 'EUyhr12QKCqblkEx3PM2749KZb9z6Yb2', '2016-11-17 23:40:43', '2016-11-17 23:40:43'),
(25, 1, 'qShPBvkYC7K5F7H22B6B50Z2MINeK0z2', '2016-11-22 21:59:43', '2016-11-22 21:59:43'),
(26, 1, 'cWEocr5lsBIobcnVQY2snKrggSHEF29M', '2016-12-16 04:03:09', '2016-12-16 04:03:09'),
(27, 1, 'uwAKRHpQskLEA1Boe4deuYBd9IdM9PYH', '2016-12-20 20:53:33', '2016-12-20 20:53:33'),
(28, 1, 'KVbruOxXw2z49yyPTsrYkW1bwWRMKHdd', '2016-12-27 19:26:25', '2016-12-27 19:26:25'),
(29, 1, 'upmeNImq5ZUCt5UfUU1OzgcO8xP2isdL', '2017-01-04 00:41:46', '2017-01-04 00:41:46'),
(30, 1, 'ron3jhFVuXvYbtcNgSKUmAC2oFRtYItx', '2017-01-11 00:52:40', '2017-01-11 00:52:40'),
(31, 1, '8TdTegOjH2AhnNVVc8EXWf96kvm1rYVv', '2017-01-21 02:18:00', '2017-01-21 02:18:00'),
(32, 1, 'JZUtqng2RVFNerBgkOm4U4k9UsT4XINy', '2017-01-27 20:56:57', '2017-01-27 20:56:57'),
(33, 1, '8Pa6WV7Dkw5sim1mb4AGqR4BomNczdob', '2017-01-27 20:56:59', '2017-01-27 20:56:59'),
(34, 1, 'wJSySudVwJvciSgL9M8X8KPpaEjP0FLL', '2017-02-01 19:53:38', '2017-02-01 19:53:38'),
(35, 1, 'IRlv6chDxBE8LYprdoialXD5oQWiOUpm', '2017-02-17 00:33:01', '2017-02-17 00:33:01');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sliders`
--

INSERT INTO `sliders` (`id`, `name`, `class`, `type`, `width`, `height`, `enabled`, `temporary`, `config`, `created_at`, `updated_at`, `deleted_at`) VALUES
(12, 'Swiper', '', 'Swiper', 1024, 261, 1, 0, '{\n    "initialSlide": 0,\n    "direction": "horizontal",\n    "speed": 300,\n    "autoplay": 0,\n    "autoplayDisableOnInteraction": true,\n    "watchSlidesProgress": false,\n    "watchVisibility": false,\n    "freeMode": false,\n    "freeModeMomentum": true,\n    "freeModeMomentumRatio": 1,\n    "freeModeMomentumBounce": true,\n    "freeModeMomentumBounceRatio": 1,\n    "effect": "coverflow",\n    "cube": {\n        "slideShadows": 1,\n        "shadow": 1,\n        "shadowOffset": 20,\n        "shadowScale": 0.94\n    },\n    "coverflow": {\n        "rotate": 50,\n        "stretch": 0,\n        "depth": 100,\n        "modifier": 1,\n        "slideShadows": 1\n    },\n    "spaceBetween": 0,\n    "slidesPerView": 3,\n    "slidesPerColumn": 1,\n    "slidesPerColumnFill": "column",\n    "slidesPerGroup": 1,\n    "centeredSlides": false,\n    "grabCursor": false,\n    "touchRatio": 1,\n    "touchAngle": 45,\n    "simulateTouch": true,\n    "shortSwipes": true,\n    "longSwipes": true,\n    "longSwipesRatio": 0.5,\n    "longSwipesMs": 300,\n    "followFinger": true,\n    "onlyExternal": false,\n    "threshold": 0,\n    "touchMoveStopPropagation": true,\n    "resistance": true,\n    "resistanceRatio": 0.85,\n    "preventClicks": true,\n    "preventClicksPropagation": true,\n    "releaseFormElements": true,\n    "slideToClickedSlide": false,\n    "allowSwipeToPrev": true,\n    "allowSwipeToNext": true,\n    "noSwiping": true,\n    "noSwipingClass": "swiper-no-swiping",\n    "swipeHandler": null,\n    "pagination": null,\n    "paginationHide": true,\n    "paginationClickable": false,\n    "nextButton": null,\n    "prevButton": null,\n    "scrollbar": null,\n    "scrollbarHide": true,\n    "keyboardControl": false,\n    "mousewheelControl": false,\n    "mousewheelForceToAxis": false,\n    "hashnav": false,\n    "updateOnImagesReady": true,\n    "loop": false,\n    "loopAdditionalSlides": 0,\n    "loopedSlides": null,\n    "control": null,\n    "controlInverse": false,\n    "observer": false,\n    "observeParents": false,\n    "slideClass": "swiper-slide",\n    "slideActiveClass": "swiper-slide-active",\n    "slideVisibleClass": "swiper-slide-visible",\n    "slideDuplicateClass": "swiper-slide-duplicate",\n    "slideNextClass": "swiper-slide-next",\n    "slidePrevClass": "swiper-slide-prev",\n    "wrapperClass": "swiper-wrapper",\n    "bulletClass": "swiper-pagination-bullet",\n    "bulletActiveClass": "swiper-pagination-bullet-active",\n    "paginationHiddenClass": "swiper-pagination-hidden",\n    "buttonDisabledClass": "swiper-button-disabled"\n}', NULL, '2016-07-26 19:59:33', NULL),
(73, 'bxSlider', '', 'bxSlider', 200, 200, 1, 0, '{\n    "mode": "horizontal",\n    "speed": 700,\n    "slideMargin": 0,\n    "startSlide": 0,\n    "randomStart": false,\n    "infiniteLoop": true,\n    "hideControlOnEnd": false,\n    "easing": "linear",\n    "captions": false,\n    "ticker": false,\n    "tickerHover": false,\n    "adaptiveHeight": true,\n    "adaptiveHeightSpeed": 500,\n    "video": false,\n    "preloadImages": "all",\n    "pager": true,\n    "pagerType": "full",\n    "pagerShortSeparator": " \\/ ",\n    "controls": true,\n    "nextText": "Next",\n    "prevText": "Prev",\n    "autoControls": false,\n    "startText": "Start",\n    "stopText": "Stop",\n    "auto": true,\n    "pause": 8000,\n    "autoStart": true,\n    "autoDirection": "next",\n    "autoHover": false,\n    "autoDelay": 0,\n    "minSlides": 1,\n    "maxSlides": 1,\n    "moveSlides": 0,\n    "slideWidth": 0\n}', NULL, NULL, NULL),
(75, 'Stack', '', 'StackGallery', 500, 500, 1, 0, '{\n    "slideshowLayout": "horizontalLeft",\n    "slideshowDirection": "forward",\n    "controlsAlignment": "rightCenter",\n    "fullSize": 1,\n    "slideshowDelay": 3000,\n    "slideshowOn": 1,\n    "useRotation": 1,\n    "swipeOn": 0\n}', NULL, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `throttle`
--

INSERT INTO `throttle` (`id`, `user_id`, `type`, `ip`, `created_at`, `updated_at`) VALUES
(1, NULL, 'global', NULL, '2016-09-04 00:13:59', '2016-09-04 00:13:59'),
(2, NULL, 'ip', '::1', '2016-09-04 00:13:59', '2016-09-04 00:13:59'),
(3, 1, 'user', NULL, '2016-09-04 00:13:59', '2016-09-04 00:13:59'),
(4, NULL, 'global', NULL, '2016-11-17 23:40:03', '2016-11-17 23:40:03'),
(5, NULL, 'ip', '::1', '2016-11-17 23:40:03', '2016-11-17 23:40:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL COMMENT 'widget, content, field',
  `data` mediumtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `translations`
--

INSERT INTO `translations` (`id`, `parent_id`, `language_id`, `type`, `data`, `created_at`, `updated_at`, `deleted_at`) VALUES
(79, 15, 1, 'content', '{"name":"1","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-11-17 22:04:04', '2017-01-19 17:33:23', NULL),
(87, 23, 1, 'page', '{"name":"Pagina Inicial","menu_name":"Index","meta_keywords":["asd","dsa","asdsad"],"meta_description":""}', '2016-11-21 14:34:38', '2016-11-25 21:54:21', NULL),
(111, 45, 1, 'content', '{"name":"2","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 00:06:41', '2016-12-02 00:06:41', NULL),
(114, 48, 1, 'content', '{"name":"dsasss","content":"<ul>\\n<li>sadas<\\/li>\\n<li>dsa<\\/li>\\n<li>dsa<\\/li>\\n<li>dsad<\\/li>\\n<\\/ul>","meta_keywords":["asd","das"],"meta_description":"dsss","meta_title":"d"}', '2016-12-02 00:12:06', '2017-01-19 17:33:05', NULL),
(115, 49, 1, 'content', '{"name":"Iure quasi quisquam velit id aut aut quaerat consequatur","content":"","meta_keywords":[],"meta_description":"Maiores sint consequatur consequatur? In provident, eius nisi accusamus pariatur? Amet, quaerat dolor nesciunt.","meta_title":"Nulla dolor excepteur iure ex nemo et sit porro sit aliquid aperiam nihil quidem commodo qui"}', '2016-12-02 18:16:05', '2016-12-02 18:16:05', NULL),
(116, 50, 1, 'content', '{"name":"Iure quasi quisquam velit id aut aut quaerat consequatur","content":"","meta_keywords":[],"meta_description":"Maiores sint consequatur consequatur? In provident, eius nisi accusamus pariatur? Amet, quaerat dolor nesciunt.","meta_title":"Nulla dolor excepteur iure ex nemo et sit porro sit aliquid aperiam nihil quidem commodo qui"}', '2016-12-02 18:16:19', '2016-12-02 18:16:19', NULL),
(119, 53, 1, 'content', '{"name":"123","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 18:16:52', '2016-12-02 18:16:52', NULL),
(120, 54, 1, 'content', '{"name":"123","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 18:16:54', '2016-12-02 18:16:54', NULL),
(121, 55, 1, 'content', '{"name":"123","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 18:16:55', '2016-12-02 18:16:55', NULL),
(122, 56, 1, 'content', '{"name":"123","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 18:16:56', '2016-12-02 18:16:56', NULL),
(123, 57, 1, 'content', '{"name":"123","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 18:16:57', '2016-12-02 18:16:57', NULL),
(124, 58, 1, 'content', '{"name":"123","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 18:16:58', '2016-12-02 18:16:58', NULL),
(125, 59, 1, 'content', '{"name":"asd","content":"<p>dsa<\\/p>","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 18:16:58', '2017-01-19 17:15:04', NULL),
(126, 60, 1, 'content', '{"name":"1","content":"<p>2<\\/p>","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 18:16:59', '2017-01-19 17:18:27', NULL),
(127, 61, 1, 'content', '{"name":"3","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2016-12-02 18:17:00', '2017-01-19 17:20:38', NULL),
(133, 15, 1, 'form_field', '{"name":"Nombre","placeholder":"Nombre"}', '2017-01-13 21:18:42', '2017-01-13 22:24:08', NULL),
(134, 16, 1, 'form_field', '{"name":"Email","placeholder":""}', '2017-01-13 22:23:19', '2017-01-13 22:23:19', NULL),
(135, 17, 1, 'form_field', '{"name":"Mensaje","placeholder":""}', '2017-01-16 19:04:42', '2017-01-16 19:04:42', NULL),
(137, 19, 1, 'form_field', '{"name":"campo 2","placeholder":""}', '2017-01-17 17:03:58', '2017-01-17 17:03:58', NULL),
(139, 21, 1, 'form_field', '{"name":"campo 2","placeholder":""}', '2017-01-17 17:09:27', '2017-01-17 17:09:27', NULL),
(141, 23, 1, 'form_field', '{"name":"campo 2","placeholder":""}', '2017-01-17 17:09:57', '2017-01-17 17:09:57', NULL),
(142, 24, 1, 'form_field', '{"name":"campo 3","placeholder":""}', '2017-01-17 17:09:58', '2017-01-17 17:09:58', NULL),
(143, 25, 1, 'form_field', '{"name":"campo 1","placeholder":""}', '2017-01-17 20:58:47', '2017-01-17 20:58:47', NULL),
(144, 26, 1, 'form_field', '{"name":"campo 2","placeholder":""}', '2017-01-17 20:58:47', '2017-01-17 20:58:47', NULL),
(145, 27, 1, 'form_field', '{"name":"campo 3","placeholder":""}', '2017-01-17 20:58:47', '2017-01-17 20:58:47', NULL),
(155, 37, 1, 'form_field', '{"name":"Archivo","placeholder":""}', '2017-01-18 16:34:22', '2017-01-18 16:34:22', NULL),
(156, 48, 1, NULL, '{"name":"dsasss","content":"<ul>\\n<li>sadas<\\/li>\\n<li>dsa<\\/li>\\n<li>dsa<\\/li>\\n<li>dsad<\\/li>\\n<\\/ul>","meta_keywords":["asd","das"],"meta_description":"dsss","meta_title":"d"}', '2017-01-18 17:50:15', '2017-01-18 21:39:41', NULL),
(157, 62, 1, NULL, '{"name":"1","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-18 22:44:08', '2017-01-18 22:44:08', NULL),
(158, 63, 1, NULL, '{"name":"2","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-18 22:45:28', '2017-01-18 22:45:28', NULL),
(159, 23, 1, 'base', '{"name":null,"menu_name":null,"meta_keywords":[],"meta_description":""}', '2017-01-19 17:11:41', '2017-01-19 20:41:10', NULL),
(160, 62, 1, 'content', '{"name":"2","content":"<p>3<\\/p>","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-19 17:35:44', '2017-01-19 17:35:44', NULL),
(161, 63, 1, 'content', '{"name":"6","content":"<p>6<\\/p>","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-19 17:40:20', '2017-01-19 17:40:20', NULL),
(162, 64, 1, 'content', '{"name":"7","content":"<p>7<\\/p>","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-19 17:41:39', '2017-01-19 17:41:39', NULL),
(163, 65, 1, 'content', '{"name":"8","content":"<p>8<\\/p>","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-19 17:42:17', '2017-01-19 17:42:17', NULL),
(164, 23, 2, 'base', '{"name":null,"menu_name":null,"meta_keywords":[]}', '2017-01-19 20:41:10', '2017-01-19 20:41:10', NULL),
(165, 65, 2, 'content', '{"name":"Eight","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-27 20:24:29', '2017-01-27 20:24:29', NULL),
(166, 66, 1, 'content', '{"name":"asd","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-27 20:24:54', '2017-01-27 20:24:54', NULL),
(167, 66, 2, 'content', '{"name":"das","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-27 20:24:54', '2017-01-27 20:24:54', NULL),
(168, 67, 1, 'content', '{"name":"fds","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-27 20:26:31', '2017-01-27 20:26:31', NULL),
(169, 67, 2, 'content', '{"name":"ffff","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-27 20:26:31', '2017-01-27 20:26:31', NULL),
(170, 68, 1, 'content', '{"name":"eee","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-27 21:10:52', '2017-01-27 21:10:52', NULL),
(171, 68, 2, 'content', '{"name":"33","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-27 21:10:52', '2017-01-27 21:10:52', NULL),
(172, 69, 1, 'content', '{"name":"1ewe","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-27 21:14:22', '2017-01-27 21:14:22', NULL),
(173, 69, 2, 'content', '{"name":"eeee","content":"","meta_keywords":[],"meta_description":"","meta_title":""}', '2017-01-27 21:14:22', '2017-01-27 21:14:22', NULL);

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
(1, 'miguel@dejabu.ec', '$2y$10$PWH1K0k81TJTa.INQpYBruRkcR71WuWyxW.h4sVrigadCgv240bKu', NULL, '2017-02-17 00:33:01', 'Miguel', 'Suarez', NULL, NULL, 0, '2015-11-25 22:41:58', '2017-02-17 00:33:01');

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
(20, 23, 'default_view.php', NULL, 1, 'Content', '{"content_type":"content","settings":{"list_view":"list_news_view.php","detail_view":"list_index_view.php","order":"date_desc","pagination":true,"quantity":6}}', '2016-07-30 00:06:48', '2017-01-20 01:41:10', NULL);

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
-- Indices de la tabla `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indices de la tabla `images_config`
--
ALTER TABLE `images_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_images_config_image_section_id_idx` (`image_section_id`);

--
-- Indices de la tabla `image_sections`
--
ALTER TABLE `image_sections`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de la tabla `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT de la tabla `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT de la tabla `field_data`
--
ALTER TABLE `field_data`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `images_config`
--
ALTER TABLE `images_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `image_sections`
--
ALTER TABLE `image_sections`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `inputs`
--
ALTER TABLE `inputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT de la tabla `input_type`
--
ALTER TABLE `input_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
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
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT de la tabla `predefined_lists`
--
ALTER TABLE `predefined_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT de la tabla `throttle`
--
ALTER TABLE `throttle`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=174;
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
-- Filtros para la tabla `images_config`
--
ALTER TABLE `images_config`
  ADD CONSTRAINT `fk_images_config_image_section_id` FOREIGN KEY (`image_section_id`) REFERENCES `image_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `fk_translations_language_id` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
