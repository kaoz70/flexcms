-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 30-06-2015 a las 12:23:55
-- Versión del servidor: 5.5.43-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `flexcms16`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` time NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `temporal` tinyint(1) DEFAULT '1',
  `enabled` tinyint(1) DEFAULT '1',
  `data` mediumtext COMMENT 'temporary field untill I finish translations and dynamic fields',
  PRIMARY KEY (`id`),
  KEY `fk_calendar_id` (`calendar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_fields`
--

CREATE TABLE IF NOT EXISTS `activity_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `input_id` int(11) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `enabled` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_input_id_af` (`input_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `activity_fields`
--

INSERT INTO `activity_fields` (`id`, `input_id`, `position`, `class`, `enabled`) VALUES
(1, 37, 2, '', 'on'),
(3, 38, 2, '', 'on');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_fields_rel`
--

CREATE TABLE IF NOT EXISTS `activity_fields_rel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) DEFAULT NULL,
  `field_id` int(10) unsigned DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `data` mediumtext,
  PRIMARY KEY (`id`),
  KEY `fk_activity_id_rel` (`activity_id`),
  KEY `fk_field_id_rel` (`field_id`),
  KEY `fk_language_id_rel` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_secciones`
--

CREATE TABLE IF NOT EXISTS `admin_secciones` (
  `adminSeccionId` int(11) NOT NULL AUTO_INCREMENT,
  `adminSeccionNombre` varchar(45) DEFAULT NULL,
  `adminSeccionController` varchar(45) DEFAULT NULL,
  `adminSeccionPosicion` tinyint(2) DEFAULT NULL,
  `view_menu` tinyint(1) DEFAULT '0',
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`adminSeccionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `admin_secciones`
--

INSERT INTO `admin_secciones` (`adminSeccionId`, `adminSeccionNombre`, `adminSeccionController`, `adminSeccionPosicion`, `view_menu`, `desc`) VALUES
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
(12, 'Usuarios', 'usuarios', 12, 1, 'Usuarios del sistema: administradores, registrados, etc'),
(13, 'Estadísticas', 'estadisticas', 13, 1, 'Datos simples del uso del sitio web'),
(14, 'Configuración', 'config', 19, 1, 'Tamaños de imagenes, configuracion general'),
(15, 'Servicios', 'servicios', 14, 0, NULL),
(16, 'Publicidad', 'publicidad', 15, 1, 'Crear publicidad en varias secciones definidas'),
(17, 'Carrito de Compras', 'cart', 16, 0, NULL),
(18, 'Diseño', 'theme', 17, 1, 'Editar como se ve el sitio web'),
(19, 'Mailing', 'mailing', 18, 1, 'Enviar mails masivos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_usuarios_secciones`
--

CREATE TABLE IF NOT EXISTS `admin_usuarios_secciones` (
  `adminUsuarioSeccionId` int(11) NOT NULL AUTO_INCREMENT,
  `grupoId` int(11) DEFAULT NULL,
  `adminSeccionId` int(11) DEFAULT NULL,
  PRIMARY KEY (`adminUsuarioSeccionId`),
  KEY `adminSeccionId_idx` (`adminSeccionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=70 ;

--
-- Volcado de datos para la tabla `admin_usuarios_secciones`
--

INSERT INTO `admin_usuarios_secciones` (`adminUsuarioSeccionId`, `grupoId`, `adminSeccionId`) VALUES
(65, 1, 6),
(66, 1, 11),
(67, 1, 12),
(68, 1, 16),
(69, 1, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE IF NOT EXISTS `articulos` (
  `articuloId` int(11) NOT NULL AUTO_INCREMENT,
  `paginaId` int(11) NOT NULL,
  `articuloHabilitado` varchar(2) DEFAULT 'on',
  `usuarioId` mediumint(8) DEFAULT NULL,
  `articuloPosicion` int(11) DEFAULT NULL COMMENT 'delete when finished editor',
  `articuloClase` varchar(45) DEFAULT NULL COMMENT 'delete when finished editor',
  PRIMARY KEY (`articuloId`),
  KEY `paginaId` (`paginaId`),
  KEY `paginaId_a` (`paginaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`articuloId`, `paginaId`, `articuloHabilitado`, `usuarioId`, `articuloPosicion`, `articuloClase`) VALUES
(1, 171, 'on', NULL, 1, ''),
(2, 171, 'on', NULL, 2, '3333'),
(3, 171, 'on', NULL, 3, 'ssss');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `bannerId` int(11) NOT NULL AUTO_INCREMENT,
  `bannerName` varchar(255) DEFAULT NULL,
  `bannerClass` varchar(255) DEFAULT NULL,
  `bannerEnabled` int(1) DEFAULT '1',
  `bannerType` varchar(255) DEFAULT NULL,
  `bannerWidth` int(11) DEFAULT '800',
  `bannerHeight` int(11) DEFAULT '600',
  `usuarioId` mediumint(8) DEFAULT NULL,
  `bannerTemporal` tinyint(1) DEFAULT NULL,
  `config` text,
  PRIMARY KEY (`bannerId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ;

--
-- Volcado de datos para la tabla `banners`
--

INSERT INTO `banners` (`bannerId`, `bannerName`, `bannerClass`, `bannerEnabled`, `bannerType`, `bannerWidth`, `bannerHeight`, `usuarioId`, `bannerTemporal`, `config`) VALUES
(12, 'Swiper', '', 1, 'Swiper', 1024, 261, NULL, 0, '{\n    "initialSlide": 0,\n    "direction": "horizontal",\n    "speed": 300,\n    "autoplay": 0,\n    "autoplayDisableOnInteraction": 1,\n    "watchSlidesProgress": false,\n    "watchVisibility": false,\n    "freeMode": false,\n    "freeModeMomentum": 1,\n    "freeModeMomentumRatio": 1,\n    "freeModeMomentumBounce": 1,\n    "freeModeMomentumBounceRatio": 1,\n    "effect": "coverflow",\n    "cube": {\n        "slideShadows": 1,\n        "shadow": 1,\n        "shadowOffset": 20,\n        "shadowScale": 0.94\n    },\n    "coverflow": {\n        "rotate": 50,\n        "stretch": 0,\n        "depth": 100,\n        "modifier": 1,\n        "slideShadows": 1\n    },\n    "spaceBetween": 0,\n    "slidesPerView": 3,\n    "slidesPerColumn": 1,\n    "slidesPerColumnFill": "column",\n    "slidesPerGroup": 1,\n    "centeredSlides": false,\n    "grabCursor": false,\n    "touchRatio": 1,\n    "touchAngle": 45,\n    "simulateTouch": 1,\n    "shortSwipes": 1,\n    "longSwipes": 1,\n    "longSwipesRatio": 0.5,\n    "longSwipesMs": 300,\n    "followFinger": 1,\n    "onlyExternal": false,\n    "threshold": 0,\n    "touchMoveStopPropagation": 1,\n    "resistance": 1,\n    "resistanceRatio": 0.85,\n    "preventClicks": 1,\n    "preventClicksPropagation": 1,\n    "releaseFormElements": 1,\n    "slideToClickedSlide": false,\n    "allowSwipeToPrev": 1,\n    "allowSwipeToNext": 1,\n    "noSwiping": 1,\n    "noSwipingClass": "swiper-no-swiping",\n    "swipeHandler": null,\n    "pagination": null,\n    "paginationHide": 1,\n    "paginationClickable": false,\n    "nextButton": null,\n    "prevButton": null,\n    "scrollbar": null,\n    "scrollbarHide": 1,\n    "keyboardControl": false,\n    "mousewheelControl": false,\n    "mousewheelForceToAxis": false,\n    "hashnav": false,\n    "updateOnImagesReady": 1,\n    "loop": false,\n    "loopAdditionalSlides": 0,\n    "loopedSlides": null,\n    "control": null,\n    "controlInverse": false,\n    "observer": false,\n    "observeParents": false,\n    "slideClass": "swiper-slide",\n    "slideActiveClass": "swiper-slide-active",\n    "slideVisibleClass": "swiper-slide-visible",\n    "slideDuplicateClass": "swiper-slide-duplicate",\n    "slideNextClass": "swiper-slide-next",\n    "slidePrevClass": "swiper-slide-prev",\n    "wrapperClass": "swiper-wrapper",\n    "bulletClass": "swiper-pagination-bullet",\n    "bulletActiveClass": "swiper-pagination-bullet-active",\n    "paginationHiddenClass": "swiper-pagination-hidden",\n    "buttonDisabledClass": "swiper-button-disabled"\n}'),
(73, 'bxSlider', '', 1, 'bxSlider', 200, 200, NULL, 0, '{\n    "mode": "horizontal",\n    "speed": 700,\n    "slideMargin": 0,\n    "startSlide": 0,\n    "randomStart": false,\n    "infiniteLoop": 1,\n    "hideControlOnEnd": false,\n    "easing": "linear",\n    "captions": false,\n    "ticker": false,\n    "tickerHover": false,\n    "adaptiveHeight": 1,\n    "adaptiveHeightSpeed": 500,\n    "video": false,\n    "preloadImages": "all",\n    "pager": 1,\n    "pagerType": "full",\n    "pagerShortSeparator": " \\/ ",\n    "controls": 1,\n    "nextText": "Next",\n    "prevText": "Prev",\n    "autoControls": false,\n    "startText": "Start",\n    "stopText": "Stop",\n    "auto": 1,\n    "pause": 8000,\n    "autoStart": 1,\n    "autoDirection": "next",\n    "autoHover": false,\n    "autoDelay": 0,\n    "minSlides": 1,\n    "maxSlides": 1,\n    "moveSlides": 0,\n    "slideWidth": 0\n}'),
(75, 'Stack', '', 1, 'StackGallery', 500, 500, NULL, 0, '{\n    "slideshowLayout": "horizontalLeft",\n    "slideshowDirection": "forward",\n    "controlsAlignment": "rightCenter",\n    "fullSize": 1,\n    "slideshowDelay": 3000,\n    "slideshowOn": 1,\n    "useRotation": 1,\n    "swipeOn": 0\n}'),
(76, NULL, NULL, 1, 'bxSlider', 800, 600, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banner_campos`
--

CREATE TABLE IF NOT EXISTS `banner_campos` (
  `bannerCampoId` int(11) NOT NULL AUTO_INCREMENT,
  `inputId` int(11) NOT NULL,
  `bannerCampoPosicion` int(11) DEFAULT NULL,
  `bannerCampoClase` varchar(45) DEFAULT NULL,
  `bannerCampoNombre` varchar(45) DEFAULT NULL,
  `bannerCampoLabelHabilitado` tinyint(1) DEFAULT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `bannerCampoTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`bannerCampoId`),
  KEY `campoId` (`inputId`),
  KEY `inputId_bc_idx` (`inputId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `banner_campos`
--

INSERT INTO `banner_campos` (`bannerCampoId`, `inputId`, `bannerCampoPosicion`, `bannerCampoClase`, `bannerCampoNombre`, `bannerCampoLabelHabilitado`, `usuarioId`, `bannerCampoTemporal`) VALUES
(3, 9, 2, 'caption_black wipedown', 'Caption Black', 0, NULL, NULL),
(4, 10, 4, 'aption_simple wipeup', 'Caption Simple', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banner_campos_rel`
--

CREATE TABLE IF NOT EXISTS `banner_campos_rel` (
  `bannerCampoRelId` int(11) NOT NULL AUTO_INCREMENT,
  `bannerCampoId` int(11) NOT NULL,
  `bannerCamposImagenId` int(11) NOT NULL,
  PRIMARY KEY (`bannerCampoRelId`),
  KEY `bannerCampoId_rel_idx` (`bannerCampoId`),
  KEY `bannerImagen_rel_idx` (`bannerCamposImagenId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `banner_campos_rel`
--

INSERT INTO `banner_campos_rel` (`bannerCampoRelId`, `bannerCampoId`, `bannerCamposImagenId`) VALUES
(1, 3, 141),
(2, 4, 141);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banner_images`
--

CREATE TABLE IF NOT EXISTS `banner_images` (
  `bannerImagesId` int(11) NOT NULL AUTO_INCREMENT,
  `bannerImageExtension` varchar(255) DEFAULT NULL,
  `bannerImagenCoord` varchar(150) DEFAULT NULL,
  `bannerId` int(11) NOT NULL,
  `bannerImageName` varchar(255) DEFAULT NULL,
  `bannerImageEnabled` int(1) DEFAULT '1',
  `bannerImagenPosicion` int(11) DEFAULT '1',
  `bannerImageLink` varchar(255) DEFAULT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `bannerImageTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`bannerImagesId`),
  KEY `bannerId_bi` (`bannerId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;

--
-- Volcado de datos para la tabla `banner_images`
--

INSERT INTO `banner_images` (`bannerImagesId`, `bannerImageExtension`, `bannerImagenCoord`, `bannerId`, `bannerImageName`, `bannerImageEnabled`, `bannerImagenPosicion`, `bannerImageLink`, `usuarioId`, `bannerImageTemporal`) VALUES
(22, NULL, NULL, 12, NULL, 1, 1, NULL, NULL, 1),
(25, '0', '{"top":0,"left":0,"width":1000,"height":500,"scale":0}', 12, '', 1, 1, NULL, NULL, NULL),
(26, 'jpg', '{"top":0,"left":0,"width":1000,"height":500,"scale":0}', 12, '', 1, 1, NULL, NULL, NULL),
(138, 'jpg?1423860869', '{"top":0,"left":0,"width":800,"height":500,"scale":0}', 75, 'placeholder', 1, 2, NULL, NULL, 0),
(139, 'jpg?1423860869', '{"top":0,"left":0,"width":666.66666666667,"height":500,"scale":0}', 75, 'placeholder 2', 1, 3, NULL, NULL, 0),
(140, 'jpg?1423860870', '{"top":0,"left":0,"width":800,"height":500,"scale":0}', 75, 'caution this is sparta', 1, 1, NULL, NULL, 0),
(141, 'jpg?1428618492', '{"top":0,"left":0,"width":1024,"height":640,"scale":0}', 12, 'caution this is sparta', 1, 2, '', NULL, 0),
(142, 'jpg?1423870411', '{"top":0,"left":0,"width":1024,"height":640,"scale":0}', 12, 'placeholder', 1, 3, NULL, NULL, 0),
(143, 'jpg?1423870411', '{"top":0,"left":0,"width":1024,"height":768,"scale":0}', 12, 'placeholder 2', 1, 4, NULL, NULL, 0),
(144, 'jpg?1423870422', '{"top":0,"left":0,"width":266.66666666667,"height":200,"scale":0}', 73, 'placeholder 2', 1, 1, NULL, NULL, 0),
(145, 'png?1423870423', '{"top":0,"left":0,"width":355.55555555556,"height":200,"scale":0}', 73, 'rammstein', 1, 1, NULL, NULL, 0),
(146, 'png?1428618510', '{"top":0,"left":0,"width":1024,"height":576,"scale":0}', 12, 'rammstein', 1, 1, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) DEFAULT '1',
  `date` date NOT NULL,
  `temporal` tinyint(1) DEFAULT '1',
  `class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `calendar`
--

INSERT INTO `calendar` (`id`, `enabled`, `date`, `temporal`, `class`) VALUES
(1, 1, '2015-04-10', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_config`
--

CREATE TABLE IF NOT EXISTS `cart_config` (
  `config_id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `config_order_number_prefix` varchar(50) NOT NULL DEFAULT '',
  `config_order_number_suffix` varchar(50) NOT NULL DEFAULT '',
  `config_increment_order_number` tinyint(1) NOT NULL DEFAULT '0',
  `config_min_order` smallint(5) NOT NULL DEFAULT '0',
  `config_quantity_decimals` tinyint(1) NOT NULL DEFAULT '0',
  `config_quantity_limited_by_stock` tinyint(1) NOT NULL DEFAULT '0',
  `config_increment_duplicate_items` tinyint(1) NOT NULL DEFAULT '0',
  `config_remove_no_stock_items` tinyint(1) NOT NULL DEFAULT '0',
  `config_auto_allocate_stock` tinyint(1) NOT NULL DEFAULT '0',
  `config_save_ban_shipping_items` tinyint(1) NOT NULL DEFAULT '0',
  `config_weight_type` varchar(25) NOT NULL DEFAULT '',
  `config_weight_decimals` tinyint(1) NOT NULL DEFAULT '0',
  `config_display_tax_prices` tinyint(1) NOT NULL DEFAULT '0',
  `config_price_inc_tax` tinyint(1) NOT NULL DEFAULT '0',
  `config_multi_row_duplicate_items` tinyint(1) NOT NULL DEFAULT '0',
  `config_dynamic_reward_points` tinyint(1) NOT NULL DEFAULT '0',
  `config_reward_point_multiplier` double(8,4) NOT NULL DEFAULT '0.0000',
  `config_reward_voucher_multiplier` double(8,4) NOT NULL DEFAULT '0.0000',
  `config_reward_voucher_ratio` smallint(5) NOT NULL DEFAULT '0',
  `config_reward_point_days_pending` smallint(5) NOT NULL DEFAULT '0',
  `config_reward_point_days_valid` smallint(5) NOT NULL DEFAULT '0',
  `config_reward_voucher_days_valid` smallint(5) NOT NULL DEFAULT '0',
  `config_custom_status_1` varchar(50) NOT NULL DEFAULT '',
  `config_custom_status_2` varchar(50) NOT NULL DEFAULT '',
  `config_custom_status_3` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`config_id`),
  KEY `config_id` (`config_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `cart_config`
--

INSERT INTO `cart_config` (`config_id`, `config_order_number_prefix`, `config_order_number_suffix`, `config_increment_order_number`, `config_min_order`, `config_quantity_decimals`, `config_quantity_limited_by_stock`, `config_increment_duplicate_items`, `config_remove_no_stock_items`, `config_auto_allocate_stock`, `config_save_ban_shipping_items`, `config_weight_type`, `config_weight_decimals`, `config_display_tax_prices`, `config_price_inc_tax`, `config_multi_row_duplicate_items`, `config_dynamic_reward_points`, `config_reward_point_multiplier`, `config_reward_voucher_multiplier`, `config_reward_voucher_ratio`, `config_reward_point_days_pending`, `config_reward_point_days_valid`, `config_reward_voucher_days_valid`, `config_custom_status_1`, `config_custom_status_2`, `config_custom_status_3`) VALUES
(1, '', '', 1, 0, 0, 1, 0, 0, 1, 0, 'gram', 0, 1, 1, 0, 1, 10.0000, 0.0100, 250, 14, 365, 365, '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_data`
--

CREATE TABLE IF NOT EXISTS `cart_data` (
  `cart_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_data_user_fk` int(11) NOT NULL DEFAULT '0',
  `cart_data_array` text NOT NULL,
  `cart_data_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cart_data_readonly_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cart_data_id`),
  UNIQUE KEY `cart_data_id` (`cart_data_id`) USING BTREE,
  KEY `cart_data_user_fk` (`cart_data_user_fk`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `cart_data`
--

INSERT INTO `cart_data` (`cart_data_id`, `cart_data_user_fk`, `cart_data_array`, `cart_data_date`, `cart_data_readonly_status`) VALUES
(5, 1, 'a:3:{s:5:"items";a:2:{s:32:"e2ef524fbf3d9fe611d5a8e90fefdc9c";a:18:{s:6:"row_id";s:32:"e2ef524fbf3d9fe611d5a8e90fefdc9c";s:2:"id";s:2:"97";s:4:"name";s:5:"tests";s:8:"quantity";d:1;s:5:"price";d:120;s:6:"weight";d:10;s:7:"options";a:1:{s:7:"Colores";N;}s:11:"option_data";a:1:{s:7:"Colores";a:2:{i:0;s:2:"1a";i:1;s:2:"2a";}}s:15:"image_extension";s:14:"jpg?1399505165";s:14:"stock_quantity";b:0;s:14:"internal_price";d:120;s:8:"tax_rate";b:0;s:13:"shipping_rate";b:0;s:17:"separate_shipping";b:0;s:13:"reward_points";b:0;s:9:"user_note";N;s:14:"status_message";a:0:{}s:3:"tax";d:20;}s:32:"ac627ab1ccbdb62ec96e702f07f6425b";a:18:{s:6:"row_id";s:32:"ac627ab1ccbdb62ec96e702f07f6425b";s:2:"id";s:2:"99";s:4:"name";s:5:"test1";s:8:"quantity";d:1;s:5:"price";d:150;s:6:"weight";d:5;s:7:"options";a:1:{s:7:"Colores";N;}s:11:"option_data";a:1:{s:7:"Colores";a:3:{i:0;s:2:"1a";i:1;s:2:"2a";i:2;s:2:"3a";}}s:15:"image_extension";s:14:"jpg?1399505172";s:14:"stock_quantity";b:0;s:14:"internal_price";d:150;s:8:"tax_rate";b:0;s:13:"shipping_rate";b:0;s:17:"separate_shipping";b:0;s:13:"reward_points";b:0;s:9:"user_note";N;s:14:"status_message";a:0:{}s:3:"tax";d:25;}}s:7:"summary";a:9:{s:10:"total_rows";i:2;s:11:"total_items";d:2;s:12:"total_weight";d:15;s:19:"total_reward_points";d:2700;s:18:"item_summary_total";d:270;s:14:"shipping_total";d:0;s:9:"tax_total";d:45;s:15:"surcharge_total";d:0;s:5:"total";d:270;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"GBP";s:13:"exchange_rate";i:1;s:6:"symbol";s:7:"&pound;";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";s:7:"default";a:5:{s:4:"name";s:3:"GBP";s:6:"symbol";s:7:"&pound;";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";}}s:8:"shipping";a:7:{s:2:"id";i:0;s:4:"name";b:0;s:11:"description";b:0;s:5:"value";i:0;s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";i:0;s:7:"zone_id";i:0;s:7:"type_id";i:0;s:9:"parent_id";i:0;s:4:"name";b:0;}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"VAT";s:4:"rate";i:20;s:13:"internal_rate";i:20;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";i:0;s:7:"zone_id";i:0;s:7:"type_id";i:0;s:9:"parent_id";i:0;s:4:"name";b:0;}}s:4:"data";a:9:{s:14:"item_total_tax";d:45;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";d:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";d:45;s:18:"cart_taxable_value";d:225;s:22:"cart_non_taxable_value";d:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";d:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:27:{s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:2:"25";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:1;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:2:"10";s:25:"reward_voucher_multiplier";s:4:"0.01";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";i:5;s:12:"order_number";b:0;}}}', '2014-05-08 15:04:15', 0),
(6, 1, 'a:3:{s:5:"items";a:2:{s:32:"e2ef524fbf3d9fe611d5a8e90fefdc9c";a:18:{s:6:"row_id";s:32:"e2ef524fbf3d9fe611d5a8e90fefdc9c";s:2:"id";s:2:"97";s:4:"name";s:5:"tests";s:8:"quantity";d:1;s:5:"price";d:120;s:6:"weight";d:10;s:7:"options";a:1:{s:7:"Colores";N;}s:11:"option_data";a:1:{s:7:"Colores";a:2:{i:0;s:2:"1a";i:1;s:2:"2a";}}s:15:"image_extension";s:14:"jpg?1399580256";s:14:"stock_quantity";b:0;s:14:"internal_price";d:120;s:8:"tax_rate";b:0;s:13:"shipping_rate";b:0;s:17:"separate_shipping";b:0;s:13:"reward_points";b:0;s:9:"user_note";N;s:14:"status_message";a:0:{}s:3:"tax";d:20;}s:32:"ac627ab1ccbdb62ec96e702f07f6425b";a:18:{s:6:"row_id";s:32:"ac627ab1ccbdb62ec96e702f07f6425b";s:2:"id";s:2:"99";s:4:"name";s:5:"test1";s:8:"quantity";d:1;s:5:"price";d:150;s:6:"weight";d:5;s:7:"options";a:1:{s:7:"Colores";N;}s:11:"option_data";a:1:{s:7:"Colores";a:3:{i:0;s:2:"1a";i:1;s:2:"2a";i:2;s:2:"3a";}}s:15:"image_extension";s:14:"jpg?1399580264";s:14:"stock_quantity";b:0;s:14:"internal_price";d:150;s:8:"tax_rate";b:0;s:13:"shipping_rate";b:0;s:17:"separate_shipping";b:0;s:13:"reward_points";b:0;s:9:"user_note";N;s:14:"status_message";a:0:{}s:3:"tax";d:25;}}s:7:"summary";a:9:{s:10:"total_rows";i:2;s:11:"total_items";d:2;s:12:"total_weight";d:15;s:19:"total_reward_points";d:2700;s:18:"item_summary_total";d:270;s:14:"shipping_total";d:0;s:9:"tax_total";d:45;s:15:"surcharge_total";d:0;s:5:"total";d:270;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"GBP";s:13:"exchange_rate";i:1;s:6:"symbol";s:7:"&pound;";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";s:7:"default";a:5:{s:4:"name";s:3:"GBP";s:6:"symbol";s:7:"&pound;";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";}}s:8:"shipping";a:7:{s:2:"id";i:0;s:4:"name";b:0;s:11:"description";b:0;s:5:"value";i:0;s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";i:0;s:7:"zone_id";i:0;s:7:"type_id";i:0;s:9:"parent_id";i:0;s:4:"name";b:0;}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"VAT";s:4:"rate";i:20;s:13:"internal_rate";i:20;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";i:0;s:7:"zone_id";i:0;s:7:"type_id";i:0;s:9:"parent_id";i:0;s:4:"name";b:0;}}s:4:"data";a:9:{s:14:"item_total_tax";d:45;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";d:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";d:45;s:18:"cart_taxable_value";d:225;s:22:"cart_non_taxable_value";d:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";d:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:27:{s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:2:"25";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:1;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:2:"10";s:25:"reward_voucher_multiplier";s:4:"0.01";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";i:6;s:12:"order_number";b:0;}}}', '2014-05-09 18:29:46', 0),
(8, 1, 'a:3:{s:5:"items";a:2:{s:32:"e2ef524fbf3d9fe611d5a8e90fefdc9c";a:18:{s:6:"row_id";s:32:"e2ef524fbf3d9fe611d5a8e90fefdc9c";s:2:"id";s:2:"97";s:4:"name";s:5:"tests";s:8:"quantity";d:1;s:5:"price";d:120;s:6:"weight";d:10;s:7:"options";a:1:{s:7:"Colores";N;}s:11:"option_data";a:1:{s:7:"Colores";a:2:{i:0;s:2:"1a";i:1;s:2:"2a";}}s:15:"image_extension";s:14:"jpg?1399580256";s:14:"stock_quantity";b:0;s:14:"internal_price";d:120;s:8:"tax_rate";b:0;s:13:"shipping_rate";b:0;s:17:"separate_shipping";b:0;s:13:"reward_points";b:0;s:9:"user_note";N;s:14:"status_message";a:0:{}s:3:"tax";d:20;}s:32:"ac627ab1ccbdb62ec96e702f07f6425b";a:18:{s:6:"row_id";s:32:"ac627ab1ccbdb62ec96e702f07f6425b";s:2:"id";s:2:"99";s:4:"name";s:5:"test1";s:8:"quantity";d:1;s:5:"price";d:150;s:6:"weight";d:5;s:7:"options";a:1:{s:7:"Colores";N;}s:11:"option_data";a:1:{s:7:"Colores";a:3:{i:0;s:2:"1a";i:1;s:2:"2a";i:2;s:2:"3a";}}s:15:"image_extension";s:14:"jpg?1399580264";s:14:"stock_quantity";b:0;s:14:"internal_price";d:150;s:8:"tax_rate";b:0;s:13:"shipping_rate";b:0;s:17:"separate_shipping";b:0;s:13:"reward_points";b:0;s:9:"user_note";N;s:14:"status_message";a:0:{}s:3:"tax";d:25;}}s:7:"summary";a:9:{s:10:"total_rows";i:2;s:11:"total_items";d:2;s:12:"total_weight";d:15;s:19:"total_reward_points";d:2700;s:18:"item_summary_total";d:270;s:14:"shipping_total";d:0;s:9:"tax_total";d:45;s:15:"surcharge_total";d:0;s:5:"total";d:270;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"GBP";s:13:"exchange_rate";i:1;s:6:"symbol";s:7:"&pound;";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";s:7:"default";a:5:{s:4:"name";s:3:"GBP";s:6:"symbol";s:7:"&pound;";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";}}s:8:"shipping";a:7:{s:2:"id";i:0;s:4:"name";b:0;s:11:"description";b:0;s:5:"value";i:0;s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";i:0;s:7:"zone_id";i:0;s:7:"type_id";i:0;s:9:"parent_id";i:0;s:4:"name";b:0;}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"VAT";s:4:"rate";i:20;s:13:"internal_rate";i:20;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";i:0;s:7:"zone_id";i:0;s:7:"type_id";i:0;s:9:"parent_id";i:0;s:4:"name";b:0;}}s:4:"data";a:9:{s:14:"item_total_tax";d:45;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";d:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";d:45;s:18:"cart_taxable_value";d:225;s:22:"cart_non_taxable_value";d:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";d:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:27:{s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:2:"25";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:1;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:2:"10";s:25:"reward_voucher_multiplier";s:4:"0.01";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";i:8;s:12:"order_number";s:8:"00000001";}}}', '2014-05-12 13:04:55', 1),
(9, 1, 'a:3:{s:5:"items";a:2:{s:32:"e2ef524fbf3d9fe611d5a8e90fefdc9c";a:18:{s:6:"row_id";s:32:"e2ef524fbf3d9fe611d5a8e90fefdc9c";s:2:"id";s:2:"97";s:4:"name";s:5:"tests";s:8:"quantity";d:1;s:5:"price";d:120;s:6:"weight";d:10;s:7:"options";a:1:{s:7:"Colores";N;}s:11:"option_data";a:1:{s:7:"Colores";a:2:{i:0;s:2:"1a";i:1;s:2:"2a";}}s:15:"image_extension";s:14:"jpg?1399580256";s:14:"stock_quantity";b:0;s:14:"internal_price";d:120;s:8:"tax_rate";b:0;s:13:"shipping_rate";b:0;s:17:"separate_shipping";b:0;s:13:"reward_points";b:0;s:9:"user_note";N;s:14:"status_message";a:0:{}s:3:"tax";d:20;}s:32:"ac627ab1ccbdb62ec96e702f07f6425b";a:18:{s:6:"row_id";s:32:"ac627ab1ccbdb62ec96e702f07f6425b";s:2:"id";s:2:"99";s:4:"name";s:5:"test1";s:8:"quantity";d:1;s:5:"price";d:150;s:6:"weight";d:5;s:7:"options";a:1:{s:7:"Colores";N;}s:11:"option_data";a:1:{s:7:"Colores";a:3:{i:0;s:2:"1a";i:1;s:2:"2a";i:2;s:2:"3a";}}s:15:"image_extension";s:14:"jpg?1399580264";s:14:"stock_quantity";b:0;s:14:"internal_price";d:150;s:8:"tax_rate";b:0;s:13:"shipping_rate";b:0;s:17:"separate_shipping";b:0;s:13:"reward_points";b:0;s:9:"user_note";N;s:14:"status_message";a:0:{}s:3:"tax";d:25;}}s:7:"summary";a:9:{s:10:"total_rows";i:2;s:11:"total_items";d:2;s:12:"total_weight";d:15;s:19:"total_reward_points";d:2700;s:18:"item_summary_total";d:270;s:14:"shipping_total";d:0;s:9:"tax_total";d:45;s:15:"surcharge_total";d:0;s:5:"total";d:270;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"GBP";s:13:"exchange_rate";i:1;s:6:"symbol";s:7:"&pound;";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";s:7:"default";a:5:{s:4:"name";s:3:"GBP";s:6:"symbol";s:7:"&pound;";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:",";s:17:"decimal_separator";s:1:".";}}s:8:"shipping";a:7:{s:2:"id";i:0;s:4:"name";b:0;s:11:"description";b:0;s:5:"value";i:0;s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";i:0;s:7:"zone_id";i:0;s:7:"type_id";i:0;s:9:"parent_id";i:0;s:4:"name";b:0;}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"VAT";s:4:"rate";i:20;s:13:"internal_rate";i:20;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";i:0;s:7:"zone_id";i:0;s:7:"type_id";i:0;s:9:"parent_id";i:0;s:4:"name";b:0;}}s:4:"data";a:9:{s:14:"item_total_tax";d:45;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";d:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";d:45;s:18:"cart_taxable_value";d:225;s:22:"cart_non_taxable_value";d:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";d:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:27:{s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:2:"25";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:1;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:2:"10";s:25:"reward_voucher_multiplier";s:4:"0.01";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";i:9;s:12:"order_number";s:8:"00000002";}}}', '2014-05-12 14:29:46', 1),
(10, 1, 'a:1:{s:8:"settings";a:1:{s:13:"configuration";a:1:{s:12:"cart_data_id";i:10;}}}', '2014-10-14 15:28:11', 0);

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
  `prevent_update` int(10) DEFAULT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`, `prevent_update`) VALUES
('0026853b0c9e7458a2209d269046db91', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429028702, '', NULL),
('0af52f02e11a97a6d4aa9f98000a45df', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429262900, '', NULL),
('0e64e3c0a7bebaac79269690a081bee9', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429107676, '', NULL),
('147bedfe319b3dbc714e3fafc7533a5c', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429608891, '', NULL),
('1c30bcb90cdd2d2ddd2805c6ec62882a', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429104074, '', NULL),
('2526f7bc954e367368a4ed2bb56d446b', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429262886, '', NULL),
('4402d09c0f59a5696df5811a4dcb5426', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1428930842, 'a:6:{s:10:"flexi_cart";a:3:{s:5:"items";a:0:{}s:7:"summary";a:9:{s:10:"total_rows";i:0;s:11:"total_items";i:0;s:12:"total_weight";i:0;s:19:"total_reward_points";i:0;s:18:"item_summary_total";i:0;s:14:"shipping_total";d:0;s:9:"tax_total";i:0;s:15:"surcharge_total";i:0;s:5:"total";i:0;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"USD";s:13:"exchange_rate";s:6:"1.0000";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";s:7:"default";a:5:{s:4:"name";s:3:"USD";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";}}s:8:"shipping";a:7:{s:2:"id";s:1:"1";s:4:"name";s:6:"7 dias";s:11:"description";s:1:"0";s:5:"value";s:4:"3.00";s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"IVA";s:4:"rate";s:7:"12.0000";s:13:"internal_rate";s:7:"12.0000";s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:9:{s:14:"item_total_tax";i:0;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";i:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";i:0;s:18:"cart_taxable_value";i:0;s:22:"cart_non_taxable_value";i:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";i:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:28:{s:2:"id";b:1;s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:1:"0";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:0;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:7:"10.0000";s:25:"reward_voucher_multiplier";s:6:"0.0100";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";b:0;s:12:"order_number";b:0;}}}s:8:"identity";s:16:"miguel@dejabu.ec";s:8:"username";N;s:5:"email";s:16:"miguel@dejabu.ec";s:7:"user_id";s:1:"1";s:14:"old_last_login";s:10:"1428934883";}', NULL),
('69af5844bfb23db1634bb6038faaa69c', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429096860, '', NULL),
('6df50edca048682b73e3732ee82f9803', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429117984, 'a:6:{s:8:"identity";s:16:"miguel@dejabu.ec";s:8:"username";N;s:5:"email";s:16:"miguel@dejabu.ec";s:7:"user_id";s:1:"1";s:14:"old_last_login";s:10:"1428956974";s:10:"flexi_cart";a:3:{s:5:"items";a:0:{}s:7:"summary";a:9:{s:10:"total_rows";i:0;s:11:"total_items";i:0;s:12:"total_weight";i:0;s:19:"total_reward_points";i:0;s:18:"item_summary_total";i:0;s:14:"shipping_total";d:0;s:9:"tax_total";i:0;s:15:"surcharge_total";i:0;s:5:"total";i:0;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"USD";s:13:"exchange_rate";s:6:"1.0000";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";s:7:"default";a:5:{s:4:"name";s:3:"USD";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";}}s:8:"shipping";a:7:{s:2:"id";s:1:"1";s:4:"name";s:6:"7 dias";s:11:"description";s:1:"0";s:5:"value";s:4:"3.00";s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"IVA";s:4:"rate";s:7:"12.0000";s:13:"internal_rate";s:7:"12.0000";s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:9:{s:14:"item_total_tax";i:0;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";i:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";i:0;s:18:"cart_taxable_value";i:0;s:22:"cart_non_taxable_value";i:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";i:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:28:{s:2:"id";b:1;s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:1:"0";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:0;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:7:"10.0000";s:25:"reward_voucher_multiplier";s:6:"0.0100";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";b:0;s:12:"order_number";b:0;}}}}', NULL),
('6f43b125b99c3f5327fb096a3e792c6d', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429114877, '', NULL),
('7133110c1cf728e32537cdfc9611eeda', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429270736, 'a:6:{s:8:"identity";s:16:"miguel@dejabu.ec";s:8:"username";N;s:5:"email";s:16:"miguel@dejabu.ec";s:7:"user_id";s:1:"1";s:14:"old_last_login";s:10:"1429134033";s:10:"flexi_cart";a:3:{s:5:"items";a:0:{}s:7:"summary";a:9:{s:10:"total_rows";i:0;s:11:"total_items";i:0;s:12:"total_weight";i:0;s:19:"total_reward_points";i:0;s:18:"item_summary_total";i:0;s:14:"shipping_total";d:0;s:9:"tax_total";i:0;s:15:"surcharge_total";i:0;s:5:"total";i:0;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"USD";s:13:"exchange_rate";s:6:"1.0000";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";s:7:"default";a:5:{s:4:"name";s:3:"USD";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";}}s:8:"shipping";a:7:{s:2:"id";s:1:"1";s:4:"name";s:6:"7 dias";s:11:"description";s:1:"0";s:5:"value";s:4:"3.00";s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"IVA";s:4:"rate";s:7:"12.0000";s:13:"internal_rate";s:7:"12.0000";s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:9:{s:14:"item_total_tax";i:0;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";i:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";i:0;s:18:"cart_taxable_value";i:0;s:22:"cart_non_taxable_value";i:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";i:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:28:{s:2:"id";b:1;s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:1:"0";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:0;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:7:"10.0000";s:25:"reward_voucher_multiplier";s:6:"0.0100";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";b:0;s:12:"order_number";b:0;}}}}', NULL),
('782b261e80a22f65045e1a28a1b2da54', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429006420, '', NULL),
('7fe1ac47dc7e4fb4abb6bf55035c8dfa', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1428944084, 'a:6:{s:8:"identity";s:16:"miguel@dejabu.ec";s:8:"username";N;s:5:"email";s:16:"miguel@dejabu.ec";s:7:"user_id";s:1:"1";s:14:"old_last_login";s:10:"1428935313";s:10:"flexi_cart";a:3:{s:5:"items";a:0:{}s:7:"summary";a:9:{s:10:"total_rows";i:0;s:11:"total_items";i:0;s:12:"total_weight";i:0;s:19:"total_reward_points";i:0;s:18:"item_summary_total";i:0;s:14:"shipping_total";d:0;s:9:"tax_total";i:0;s:15:"surcharge_total";i:0;s:5:"total";i:0;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"USD";s:13:"exchange_rate";s:6:"1.0000";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";s:7:"default";a:5:{s:4:"name";s:3:"USD";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";}}s:8:"shipping";a:7:{s:2:"id";s:1:"1";s:4:"name";s:6:"7 dias";s:11:"description";s:1:"0";s:5:"value";s:4:"3.00";s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"IVA";s:4:"rate";s:7:"12.0000";s:13:"internal_rate";s:7:"12.0000";s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:9:{s:14:"item_total_tax";i:0;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";i:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";i:0;s:18:"cart_taxable_value";i:0;s:22:"cart_non_taxable_value";i:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";i:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:28:{s:2:"id";b:1;s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:1:"0";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:0;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:7:"10.0000";s:25:"reward_voucher_multiplier";s:6:"0.0100";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";b:0;s:12:"order_number";b:0;}}}}', NULL),
('803eddce9365d8003eab64958fa81193', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429017892, '', NULL),
('814498e8b1011210891ecab5fd873ad8', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429017902, '', NULL),
('86b6bf96852e8f0e396c22054046d954', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429096860, 'a:1:{s:10:"flexi_cart";a:3:{s:5:"items";a:0:{}s:7:"summary";a:9:{s:10:"total_rows";i:0;s:11:"total_items";i:0;s:12:"total_weight";i:0;s:19:"total_reward_points";i:0;s:18:"item_summary_total";i:0;s:14:"shipping_total";d:0;s:9:"tax_total";i:0;s:15:"surcharge_total";i:0;s:5:"total";i:0;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"USD";s:13:"exchange_rate";s:6:"1.0000";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";s:7:"default";a:5:{s:4:"name";s:3:"USD";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";}}s:8:"shipping";a:7:{s:2:"id";s:1:"1";s:4:"name";s:6:"7 dias";s:11:"description";s:1:"0";s:5:"value";s:4:"3.00";s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"IVA";s:4:"rate";s:7:"12.0000";s:13:"internal_rate";s:7:"12.0000";s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:9:{s:14:"item_total_tax";i:0;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";i:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";i:0;s:18:"cart_taxable_value";i:0;s:22:"cart_non_taxable_value";i:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";i:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:28:{s:2:"id";b:1;s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:1:"0";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:0;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:7:"10.0000";s:25:"reward_voucher_multiplier";s:6:"0.0100";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";b:0;s:12:"order_number";b:0;}}}}', NULL),
('88305293577a02f604502c4d090537f5', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429111275, '', NULL),
('99f0a6a176757eaf13a4449662fb98d4', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429025101, '', NULL),
('aad3fb34f88383d7cd4e20476a3b65b4', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429100474, '', NULL),
('afbe1cd8436f22b57b0702f6d5a433a0', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429035903, '', NULL),
('b4f4a0449fd8c82cc09958b78811fde2', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429620313, 'a:6:{s:10:"flexi_cart";a:3:{s:5:"items";a:0:{}s:7:"summary";a:9:{s:10:"total_rows";i:0;s:11:"total_items";i:0;s:12:"total_weight";i:0;s:19:"total_reward_points";i:0;s:18:"item_summary_total";i:0;s:14:"shipping_total";d:0;s:9:"tax_total";i:0;s:15:"surcharge_total";i:0;s:5:"total";i:0;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"USD";s:13:"exchange_rate";s:6:"1.0000";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";s:7:"default";a:5:{s:4:"name";s:3:"USD";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";}}s:8:"shipping";a:7:{s:2:"id";s:1:"1";s:4:"name";s:6:"7 dias";s:11:"description";s:1:"0";s:5:"value";s:4:"3.00";s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"IVA";s:4:"rate";s:7:"12.0000";s:13:"internal_rate";s:7:"12.0000";s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:9:{s:14:"item_total_tax";i:0;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";i:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";i:0;s:18:"cart_taxable_value";i:0;s:22:"cart_non_taxable_value";i:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";i:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:28:{s:2:"id";b:1;s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:1:"0";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:0;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:7:"10.0000";s:25:"reward_voucher_multiplier";s:6:"0.0100";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";b:0;s:12:"order_number";b:0;}}}s:8:"identity";s:24:"miguelsuarez70@gmail.com";s:8:"username";s:24:"miguelsuarez70@gmail.com";s:5:"email";s:24:"miguelsuarez70@gmail.com";s:7:"user_id";s:2:"24";s:14:"old_last_login";s:10:"1429638384";}', NULL),
('be7016e82e4c515e8eb60ecff24bd889', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429010019, '', NULL),
('c964a8963390aeac682e90f18262a53c', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429002807, '', NULL),
('d49b7357d40b2df0717bec19f49d0fb4', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429620242, '', NULL),
('da458dd1568dc0eadde66062ec748150', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429032302, '', NULL),
('e4fe6d78612b51baad8ec4918e5dd97a', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429021502, '', NULL),
('e52535e8497270a640060f290e43d834', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429002819, '', NULL),
('ec767d033bd3e4e47983703b1d1f6b6e', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429096873, '', NULL),
('ee2b5f0ee21d9e39da090abae28037c1', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429291602, 'a:1:{s:10:"flexi_cart";a:3:{s:5:"items";a:0:{}s:7:"summary";a:9:{s:10:"total_rows";i:0;s:11:"total_items";i:0;s:12:"total_weight";i:0;s:19:"total_reward_points";i:0;s:18:"item_summary_total";i:0;s:14:"shipping_total";d:0;s:9:"tax_total";i:0;s:15:"surcharge_total";i:0;s:5:"total";i:0;}s:8:"settings";a:6:{s:8:"currency";a:7:{s:4:"name";s:3:"USD";s:13:"exchange_rate";s:6:"1.0000";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";s:7:"default";a:5:{s:4:"name";s:3:"USD";s:6:"symbol";s:1:"$";s:13:"symbol_suffix";b:0;s:18:"thousand_separator";s:1:".";s:17:"decimal_separator";s:1:",";}}s:8:"shipping";a:7:{s:2:"id";s:1:"1";s:4:"name";s:6:"7 dias";s:11:"description";s:1:"0";s:5:"value";s:4:"3.00";s:8:"tax_rate";b:0;s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:11:{s:9:"surcharge";i:0;s:23:"separate_shipping_value";i:0;s:14:"separate_items";i:0;s:14:"separate_value";i:0;s:15:"separate_weight";i:0;s:10:"free_items";i:0;s:10:"free_value";i:0;s:11:"free_weight";i:0;s:21:"banned_shipping_items";a:0:{}s:23:"separate_shipping_items";a:0:{}s:19:"item_shipping_rates";a:0:{}}}s:3:"tax";a:5:{s:4:"name";s:3:"IVA";s:4:"rate";s:7:"12.0000";s:13:"internal_rate";s:7:"12.0000";s:8:"location";a:1:{i:0;a:5:{s:11:"location_id";s:1:"4";s:7:"zone_id";s:1:"0";s:7:"type_id";s:2:"14";s:9:"parent_id";s:1:"0";s:4:"name";s:7:"Ecuador";}}s:4:"data";a:9:{s:14:"item_total_tax";i:0;s:12:"shipping_tax";i:0;s:17:"item_discount_tax";i:0;s:20:"summary_discount_tax";i:0;s:18:"reward_voucher_tax";i:0;s:13:"surcharge_tax";i:0;s:8:"cart_tax";i:0;s:18:"cart_taxable_value";i:0;s:22:"cart_non_taxable_value";i:0;}}s:9:"discounts";a:6:{s:5:"codes";a:0:{}s:6:"manual";a:0:{}s:12:"active_items";a:0:{}s:14:"active_summary";a:0:{}s:15:"reward_vouchers";a:0:{}s:4:"data";a:5:{s:21:"item_discount_savings";i:0;s:24:"summary_discount_savings";i:0;s:15:"reward_vouchers";i:0;s:23:"void_reward_point_items";a:0:{}s:18:"excluded_discounts";a:0:{}}}s:10:"surcharges";a:0:{}s:13:"configuration";a:28:{s:2:"id";b:1;s:19:"order_number_prefix";s:0:"";s:19:"order_number_suffix";s:0:"";s:22:"increment_order_number";b:1;s:13:"minimum_order";s:1:"0";s:17:"quantity_decimals";s:1:"0";s:33:"increment_duplicate_item_quantity";b:0;s:25:"quantity_limited_by_stock";b:1;s:21:"remove_no_stock_items";b:0;s:19:"auto_allocate_stock";b:1;s:26:"save_banned_shipping_items";b:0;s:11:"weight_type";s:4:"gram";s:15:"weight_decimals";s:1:"0";s:18:"display_tax_prices";b:1;s:13:"price_inc_tax";b:1;s:25:"multi_row_duplicate_items";b:0;s:21:"dynamic_reward_points";b:1;s:23:"reward_point_multiplier";s:7:"10.0000";s:25:"reward_voucher_multiplier";s:6:"0.0100";s:29:"reward_point_to_voucher_ratio";s:3:"250";s:25:"reward_point_days_pending";s:2:"14";s:23:"reward_point_days_valid";s:3:"365";s:25:"reward_voucher_days_valid";s:3:"365";s:15:"custom_status_1";b:0;s:15:"custom_status_2";b:0;s:15:"custom_status_3";b:0;s:12:"cart_data_id";b:0;s:12:"order_number";b:0;}}}}', NULL),
('f8111ab51ca5540e746c520fb2aecee8', '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429608891, '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(45) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `key`, `value`) VALUES
(1, 'site_name', 'FlexCMS'),
(2, 'index_page_id', '163'),
(3, 'theme', 'destiny'),
(4, 'environment', 'development'),
(5, 'debug_bar', '1'),
(6, 'facebook_app_id', '297589630375072'),
(7, 'facebook_app_secret', 'd8a3469a176c222335a1c9584d0e3578'),
(8, 'facebook_login', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE IF NOT EXISTS `contactos` (
  `contactoId` int(11) NOT NULL AUTO_INCREMENT,
  `contactoEmail` varchar(255) NOT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `contactoTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`contactoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`contactoId`, `contactoEmail`, `usuarioId`, `contactoTemporal`) VALUES
(3, 'asd@ass.com', NULL, NULL),
(4, 'asd@asd.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto_campos`
--

CREATE TABLE IF NOT EXISTS `contacto_campos` (
  `contactoCampoId` int(11) NOT NULL AUTO_INCREMENT,
  `inputId` int(11) NOT NULL,
  `contactoCampoPosicion` int(10) NOT NULL DEFAULT '0',
  `contactoCampoClase` varchar(100) CHARACTER SET latin1 NOT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `contactoCampoTemporal` tinyint(1) DEFAULT NULL,
  `contactoCampoValidacion` varchar(45) DEFAULT NULL,
  `contactoCampoRequerido` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`contactoCampoId`),
  KEY `inputId_cc` (`inputId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Volcado de datos para la tabla `contacto_campos`
--

INSERT INTO `contacto_campos` (`contactoCampoId`, `inputId`, `contactoCampoPosicion`, `contactoCampoClase`, `usuarioId`, `contactoCampoTemporal`, `contactoCampoValidacion`, `contactoCampoRequerido`) VALUES
(13, 13, 1, '', NULL, NULL, '', 0),
(14, 13, 4, '', NULL, NULL, NULL, 0),
(16, 13, 5, '', NULL, NULL, '', 0),
(17, 11, 6, '', NULL, NULL, NULL, 0),
(18, 13, 2, '', NULL, NULL, 'email', 0),
(19, 31, 0, '', NULL, NULL, 'date', 0),
(20, 24, 0, '', NULL, NULL, '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto_direcciones`
--

CREATE TABLE IF NOT EXISTS `contacto_direcciones` (
  `contactoDireccionId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contactoDireccionNombre` varchar(45) DEFAULT NULL,
  `contactoDireccionPosicion` int(11) DEFAULT NULL,
  `contactoDireccionImagen` varchar(45) DEFAULT NULL,
  `contactoDireccionCoord` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`contactoDireccionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `contacto_direcciones`
--

INSERT INTO `contacto_direcciones` (`contactoDireccionId`, `contactoDireccionNombre`, `contactoDireccionPosicion`, `contactoDireccionImagen`, `contactoDireccionCoord`) VALUES
(1, 'Quitoa', 2, 'jpg', '{"top":0,"left":0,"width":133,"height":100,"scale":0}'),
(4, '3', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto_redsocial`
--

CREATE TABLE IF NOT EXISTS `contacto_redsocial` (
  `rsId` int(11) NOT NULL AUTO_INCREMENT,
  `rsNombre` varchar(255) CHARACTER SET latin1 NOT NULL,
  `rsLink` varchar(255) CHARACTER SET latin1 NOT NULL,
  `rsClase` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`rsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `curr_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `curr_name` varchar(50) NOT NULL DEFAULT '',
  `curr_exchange_rate` double(8,4) NOT NULL DEFAULT '0.0000',
  `curr_symbol` varchar(25) NOT NULL DEFAULT '',
  `curr_symbol_suffix` tinyint(1) NOT NULL DEFAULT '0',
  `curr_thousand_separator` varchar(10) NOT NULL DEFAULT '',
  `curr_decimal_separator` varchar(10) NOT NULL DEFAULT '',
  `curr_status` tinyint(1) NOT NULL DEFAULT '0',
  `curr_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`curr_id`),
  KEY `curr_id` (`curr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `currency`
--

INSERT INTO `currency` (`curr_id`, `curr_name`, `curr_exchange_rate`, `curr_symbol`, `curr_symbol_suffix`, `curr_thousand_separator`, `curr_decimal_separator`, `curr_status`, `curr_default`) VALUES
(2, 'USD', 1.0000, '$', 0, '.', ',', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descargas`
--

CREATE TABLE IF NOT EXISTS `descargas` (
  `descargaId` int(11) NOT NULL AUTO_INCREMENT,
  `descargaCategoriaId` int(11) DEFAULT NULL,
  `descargaPosicion` int(11) DEFAULT '0',
  `descargaEnabled` tinyint(1) DEFAULT '1',
  `descargaArchivo` varchar(255) DEFAULT NULL,
  `descargaFecha` date DEFAULT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `descargaTemporal` tinyint(1) DEFAULT NULL,
  `descargaImagenCoord` varchar(150) DEFAULT NULL,
  `descargaTipo` int(11) DEFAULT NULL,
  `descargaEnlace` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`descargaId`),
  KEY `descargaCategoriaId_d` (`descargaCategoriaId`),
  KEY `descargaCategoriaId_idx` (`descargaCategoriaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=409 ;

--
-- Volcado de datos para la tabla `descargas`
--

INSERT INTO `descargas` (`descargaId`, `descargaCategoriaId`, `descargaPosicion`, `descargaEnabled`, `descargaArchivo`, `descargaFecha`, `usuarioId`, `descargaTemporal`, `descargaImagenCoord`, `descargaTipo`, `descargaEnlace`) VALUES
(398, 15, 5, 1, 'jpg?1428624896', '2015-02-24', NULL, NULL, '{"top":0,"left":0,"width":320,"height":200,"scale":0}', NULL, ''),
(399, 15, 1, 1, 'jpg?1424802252', '2015-02-24', NULL, NULL, '{"top":0,"left":0,"width":320,"height":200,"scale":0}', NULL, ''),
(400, 15, 2, 1, 'png?1424802252', '2015-02-24', NULL, NULL, '{"top":0,"left":0,"width":355.55555555556,"height":200,"scale":0}', NULL, ''),
(401, 15, 3, 1, 'jpg?1424802252', '2015-02-24', NULL, NULL, '{"top":0,"left":0,"width":266.66666666667,"height":200,"scale":0}', NULL, ''),
(402, 15, 4, 1, 'asas', NULL, NULL, NULL, '', NULL, NULL),
(403, 15, 0, 1, 'png?1429135158', '2015-04-15', NULL, NULL, '{"top":0,"left":0,"width":80,"height":80,"scale":0}', NULL, NULL),
(404, 15, 0, 1, 'png?1429135323', '2015-04-15', NULL, NULL, '{"top":0,"left":0,"width":80,"height":80,"scale":0}', NULL, NULL),
(405, 15, 0, 1, 'png?1429135622', '2015-04-15', NULL, NULL, '{"top":0,"left":0,"width":80,"height":80,"scale":0}', NULL, NULL),
(406, 15, 0, 1, 'png?1429135678', '2015-04-15', NULL, NULL, '{"top":0,"left":0,"width":80,"height":80,"scale":0}', NULL, NULL),
(407, 15, 0, 1, 'png?1429135984', '2015-04-15', NULL, NULL, '{"top":0,"left":0,"width":80,"height":80,"scale":0}', NULL, NULL),
(408, 15, 0, 1, 'jpg?1429135995', '2015-04-15', NULL, NULL, '{"top":0,"left":0,"width":1280,"height":960,"scale":0}', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descargas_categorias`
--

CREATE TABLE IF NOT EXISTS `descargas_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `descargaCategoriaClase` varchar(255) DEFAULT NULL,
  `descargaCategoriaPublicado` tinyint(1) DEFAULT '1',
  `descargaCategoriaPrivada` tinyint(1) DEFAULT '0',
  `usuarioId` mediumint(8) DEFAULT NULL,
  `descargaCategoriaImagen` varchar(45) DEFAULT NULL,
  `descargaCategoriaImagenCoord` varchar(150) DEFAULT NULL,
  `descargaCategoriaEnlace` varchar(255) DEFAULT NULL,
  `temporal` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `descargas_categorias`
--

INSERT INTO `descargas_categorias` (`id`, `tree`, `lft`, `rgt`, `descargaCategoriaClase`, `descargaCategoriaPublicado`, `descargaCategoriaPrivada`, `usuarioId`, `descargaCategoriaImagen`, `descargaCategoriaImagenCoord`, `descargaCategoriaEnlace`, `temporal`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 10, NULL, 1, 0, NULL, NULL, NULL, NULL, 0, NULL, '2015-01-07 21:41:51', NULL),
(15, 1, 2, 5, '', 1, 0, NULL, '', '', '', 0, '2014-12-19 22:05:12', '2015-01-07 21:41:51', NULL),
(16, 1, 3, 4, '', 1, 0, NULL, '', '', '', 0, '2014-12-19 22:25:12', '2015-01-07 21:41:51', NULL),
(18, 1, 6, 9, '', 1, 0, NULL, '', '', '', 0, '2014-12-19 22:59:27', '2015-01-07 21:41:51', NULL),
(19, 1, 7, 8, '', 1, 0, NULL, '', '', '', 0, '2014-12-19 23:00:41', '2015-01-07 21:41:51', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discounts`
--

CREATE TABLE IF NOT EXISTS `discounts` (
  `disc_id` int(11) NOT NULL AUTO_INCREMENT,
  `disc_type_fk` smallint(5) NOT NULL DEFAULT '0',
  `disc_method_fk` smallint(5) NOT NULL DEFAULT '0',
  `disc_tax_method_fk` tinyint(1) NOT NULL DEFAULT '0',
  `disc_user_acc_fk` int(11) NOT NULL DEFAULT '0',
  `disc_item_fk` int(11) NOT NULL DEFAULT '0' COMMENT 'Item / Product Id',
  `disc_group_fk` int(11) NOT NULL DEFAULT '0',
  `disc_location_fk` smallint(5) NOT NULL DEFAULT '0',
  `disc_zone_fk` smallint(5) NOT NULL DEFAULT '0',
  `disc_code` varchar(50) NOT NULL DEFAULT '' COMMENT 'Discount Code',
  `disc_description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name shown in cart when active',
  `disc_quantity_required` smallint(5) NOT NULL DEFAULT '0' COMMENT 'Quantity required for offer',
  `disc_quantity_discounted` smallint(5) NOT NULL DEFAULT '0' COMMENT 'Quantity affected by offer',
  `disc_value_required` double(8,2) NOT NULL DEFAULT '0.00',
  `disc_value_discounted` double(8,2) NOT NULL DEFAULT '0.00' COMMENT '% discount, flat fee discount, new set price - specified via calculation_fk',
  `disc_recursive` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Discount is repeatable multiple times on one item',
  `disc_non_combinable_discount` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Cannot be applied if any other discount is applied',
  `disc_void_reward_points` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Voids any current reward points',
  `disc_force_ship_discount` tinyint(1) NOT NULL DEFAULT '0',
  `disc_custom_status_1` varchar(50) NOT NULL DEFAULT '',
  `disc_custom_status_2` varchar(50) NOT NULL DEFAULT '',
  `disc_custom_status_3` varchar(50) NOT NULL DEFAULT '',
  `disc_usage_limit` smallint(5) NOT NULL DEFAULT '0' COMMENT 'Number of offers available',
  `disc_valid_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `disc_expire_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `disc_status` tinyint(1) NOT NULL DEFAULT '0',
  `disc_order_by` smallint(1) NOT NULL DEFAULT '100' COMMENT 'Default value of 100 to ensure non set ''order by'' values of zero are not before 1,2,3 etc.',
  PRIMARY KEY (`disc_id`),
  UNIQUE KEY `disc_id` (`disc_id`) USING BTREE,
  KEY `disc_item_fk` (`disc_item_fk`),
  KEY `disc_location_fk` (`disc_location_fk`),
  KEY `disc_zone_fk` (`disc_zone_fk`),
  KEY `disc_method_fk` (`disc_method_fk`) USING BTREE,
  KEY `disc_type_fk` (`disc_type_fk`),
  KEY `disc_group_fk` (`disc_group_fk`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `discounts`
--

INSERT INTO `discounts` (`disc_id`, `disc_type_fk`, `disc_method_fk`, `disc_tax_method_fk`, `disc_user_acc_fk`, `disc_item_fk`, `disc_group_fk`, `disc_location_fk`, `disc_zone_fk`, `disc_code`, `disc_description`, `disc_quantity_required`, `disc_quantity_discounted`, `disc_value_required`, `disc_value_discounted`, `disc_recursive`, `disc_non_combinable_discount`, `disc_void_reward_points`, `disc_force_ship_discount`, `disc_custom_status_1`, `disc_custom_status_2`, `disc_custom_status_3`, `disc_usage_limit`, `disc_valid_date`, `disc_expire_date`, `disc_status`, `disc_order_by`) VALUES
(1, 1, 2, 1, 0, 97, 0, 4, 4, 'asaas', 'asasasasasasasasas', 0, 0, 0.00, 0.00, 0, 0, 0, 0, '', '', '', 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discount_calculation`
--

CREATE TABLE IF NOT EXISTS `discount_calculation` (
  `disc_calculation_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_calculation` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`disc_calculation_id`),
  UNIQUE KEY `disc_calculation_id` (`disc_calculation_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Note: Do not alter the order or id''s of records in table.' AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `discount_calculation`
--

INSERT INTO `discount_calculation` (`disc_calculation_id`, `disc_calculation`) VALUES
(1, 'Percentage Based'),
(2, 'Flat Fee'),
(3, 'New Value');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discount_columns`
--

CREATE TABLE IF NOT EXISTS `discount_columns` (
  `disc_column_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_column` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`disc_column_id`),
  UNIQUE KEY `disc_column_id` (`disc_column_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Note: Do not alter the order or id''s of records in table.' AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `discount_columns`
--

INSERT INTO `discount_columns` (`disc_column_id`, `disc_column`) VALUES
(1, 'Item Price'),
(2, 'Item Shipping'),
(3, 'Summary Item Total'),
(4, 'Summary Shipping Total'),
(5, 'Summary Total'),
(6, 'Summary Total (Voucher)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discount_groups`
--

CREATE TABLE IF NOT EXISTS `discount_groups` (
  `disc_group_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_group` varchar(255) NOT NULL DEFAULT '',
  `disc_group_status` tinyint(1) NOT NULL DEFAULT '0',
  `disc_group_temporary` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`disc_group_id`),
  UNIQUE KEY `disc_group_id` (`disc_group_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Volcado de datos para la tabla `discount_groups`
--

INSERT INTO `discount_groups` (`disc_group_id`, `disc_group`, `disc_group_status`, `disc_group_temporary`) VALUES
(6, '', 1, 1),
(10, '', 1, 1),
(11, '', 1, 1),
(12, '', 1, 1),
(13, '', 1, 1),
(15, '', 1, 1),
(16, '', 1, 1),
(17, 'asdf', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discount_group_items`
--

CREATE TABLE IF NOT EXISTS `discount_group_items` (
  `disc_group_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `disc_group_item_group_fk` smallint(5) NOT NULL DEFAULT '0',
  `disc_group_item_item_fk` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`disc_group_item_id`),
  UNIQUE KEY `disc_group_item_id` (`disc_group_item_id`) USING BTREE,
  KEY `disc_group_item_group_fk` (`disc_group_item_group_fk`) USING BTREE,
  KEY `disc_group_item_item_fk` (`disc_group_item_item_fk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discount_methods`
--

CREATE TABLE IF NOT EXISTS `discount_methods` (
  `disc_method_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_method_type_fk` smallint(5) NOT NULL DEFAULT '0',
  `disc_method_column_fk` smallint(5) NOT NULL DEFAULT '0',
  `disc_method_calculation_fk` smallint(5) NOT NULL DEFAULT '0',
  `disc_method` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`disc_method_id`),
  UNIQUE KEY `disc_method_id` (`disc_method_id`) USING BTREE,
  KEY `disc_method_column_fk` (`disc_method_column_fk`) USING BTREE,
  KEY `disc_method_calculation_fk` (`disc_method_calculation_fk`) USING BTREE,
  KEY `disc_method_type_fk` (`disc_method_type_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Note: Do not alter the order or id''s of records in table.' AUTO_INCREMENT=15 ;

--
-- Volcado de datos para la tabla `discount_methods`
--

INSERT INTO `discount_methods` (`disc_method_id`, `disc_method_type_fk`, `disc_method_column_fk`, `disc_method_calculation_fk`, `disc_method`) VALUES
(1, 1, 1, 1, 'Item Price - Percentage Based'),
(2, 1, 1, 2, 'Item Price - Flat Fee'),
(3, 1, 1, 3, 'Item Price - New Value'),
(4, 1, 2, 1, 'Item Shipping - Percentage Based'),
(5, 1, 2, 2, 'Item Shipping - Flat Fee'),
(6, 1, 2, 3, 'Item Shipping - New Value'),
(7, 2, 3, 1, 'Summary Item Total - Percentage Based'),
(8, 2, 3, 2, 'Summary Item Total - Flat Fee'),
(9, 2, 4, 1, 'Summary Shipping Total - Percentage Based'),
(10, 2, 4, 2, 'Summary Shipping Total - Flat Fee'),
(11, 2, 4, 3, 'Summary Shipping Total - New Value'),
(12, 2, 5, 1, 'Summary Total - Percentage Based'),
(13, 2, 5, 2, 'Summary Total - Flat Fee'),
(14, 3, 6, 2, 'Summary Total - Flat Fee (Voucher)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discount_tax_methods`
--

CREATE TABLE IF NOT EXISTS `discount_tax_methods` (
  `disc_tax_method_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_tax_method` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`disc_tax_method_id`),
  UNIQUE KEY `disc_tax_method_id` (`disc_tax_method_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Note: Do not alter the order or id''s of records in table.' AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `discount_tax_methods`
--

INSERT INTO `discount_tax_methods` (`disc_tax_method_id`, `disc_tax_method`) VALUES
(1, 'Apply Tax Before Discount '),
(2, 'Apply Discount Before Tax'),
(3, 'Apply Discount Before Tax, Add Original Tax');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discount_types`
--

CREATE TABLE IF NOT EXISTS `discount_types` (
  `disc_type_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_type` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`disc_type_id`),
  UNIQUE KEY `disc_type_id` (`disc_type_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Note: Do not alter the order or id''s of records in table.' AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `discount_types`
--

INSERT INTO `discount_types` (`disc_type_id`, `disc_type`) VALUES
(1, 'Item Discount'),
(2, 'Summary Discount'),
(3, 'Reward Voucher');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enlaces`
--

CREATE TABLE IF NOT EXISTS `enlaces` (
  `enlaceId` int(11) NOT NULL AUTO_INCREMENT,
  `enlaceLink` varchar(255) DEFAULT NULL,
  `enlaceImagen` varchar(255) DEFAULT NULL,
  `enlaceImagenCoord` varchar(150) DEFAULT NULL,
  `enlaceClase` varchar(45) DEFAULT NULL,
  `enlacePublicado` tinyint(1) DEFAULT '1',
  `enlacePosicion` int(11) DEFAULT '1',
  `paginaId` int(11) NOT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`enlaceId`),
  KEY `paginaId_e` (`paginaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `enlaces`
--

INSERT INTO `enlaces` (`enlaceId`, `enlaceLink`, `enlaceImagen`, `enlaceImagenCoord`, `enlaceClase`, `enlacePublicado`, `enlacePosicion`, `paginaId`, `usuarioId`) VALUES
(1, '', '', '', '', 1, 1, 172, NULL),
(2, '', '', '', '', 1, 1, 172, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas`
--

CREATE TABLE IF NOT EXISTS `estadisticas` (
  `estadisticaId` int(11) NOT NULL AUTO_INCREMENT,
  `estadisticaUserIP` varchar(45) NOT NULL,
  `paginaId` int(11) NOT NULL,
  `estadisticaFecha` int(45) unsigned NOT NULL,
  `estadisticaUrl` varchar(255) NOT NULL,
  PRIMARY KEY (`estadisticaId`),
  KEY `estadisticaUserIP` (`estadisticaUserIP`),
  KEY `estadisticaFecha` (`estadisticaFecha`),
  KEY `paginaId_e_idx` (`paginaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `estadisticas`
--

INSERT INTO `estadisticas` (`estadisticaId`, `estadisticaUserIP`, `paginaId`, `estadisticaFecha`, `estadisticaUrl`) VALUES
(1, '::1', 163, 1426777352, 'es/publicaciones'),
(2, '::1', 163, 1426777536, 'es/publicaciones'),
(3, '::1', 163, 1426777544, 'es/publicaciones'),
(4, '::1', 163, 1426879880, 'es/publicaciones'),
(5, '::1', 163, 1426879908, 'es/publicaciones'),
(6, '::1', 163, 1426879964, 'es/publicaciones'),
(7, '::1', 163, 1426881229, 'es/publicaciones');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_activities`
--

CREATE TABLE IF NOT EXISTS `es_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `text` mediumtext,
  PRIMARY KEY (`id`),
  KEY `fk_es_activity_id` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_activity_fields`
--

CREATE TABLE IF NOT EXISTS `es_activity_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity_field_id` int(10) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_es_activity_field_id` (`activity_field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `es_activity_fields`
--

INSERT INTO `es_activity_fields` (`id`, `activity_field_id`, `name`) VALUES
(1, 1, 'Texto'),
(3, 3, 'Multilinea');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_articulos`
--

CREATE TABLE IF NOT EXISTS `es_articulos` (
  `es_articuloId` int(11) NOT NULL AUTO_INCREMENT,
  `articuloId` int(11) NOT NULL,
  `articuloHTML` mediumtext,
  `articuloTitulo` varchar(255) DEFAULT NULL COMMENT 'delete when finished editor',
  `articuloContenido` mediumtext COMMENT 'delete when finished editor',
  PRIMARY KEY (`es_articuloId`),
  KEY `es_articuloId` (`articuloId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `es_articulos`
--

INSERT INTO `es_articulos` (`es_articuloId`, `articuloId`, `articuloHTML`, `articuloTitulo`, `articuloContenido`) VALUES
(1, 1, NULL, 'asds', '<p>asasa</p>'),
(2, 2, NULL, 'dsa', '<p>sasas aassss</p>'),
(3, 3, NULL, 'ssssss', '<p>ssssss</p>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_banner_campos`
--

CREATE TABLE IF NOT EXISTS `es_banner_campos` (
  `es_bannerCampoId` int(11) NOT NULL AUTO_INCREMENT,
  `bannerCampoId` int(11) NOT NULL,
  `bannerCampoValor` text NOT NULL,
  `bannerCampoLabel` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`es_bannerCampoId`),
  KEY `es_bannerCampos` (`bannerCampoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `es_banner_campos`
--

INSERT INTO `es_banner_campos` (`es_bannerCampoId`, `bannerCampoId`, `bannerCampoValor`, `bannerCampoLabel`) VALUES
(1, 3, '', ''),
(2, 4, '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_banner_campos_rel`
--

CREATE TABLE IF NOT EXISTS `es_banner_campos_rel` (
  `es_bannerCamposRelId` int(11) NOT NULL AUTO_INCREMENT,
  `bannerCamposRelId` int(11) NOT NULL,
  `bannerCamposTexto` text,
  PRIMARY KEY (`es_bannerCamposRelId`),
  KEY `bannerCamposRelId_idx` (`bannerCamposRelId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `es_banner_campos_rel`
--

INSERT INTO `es_banner_campos_rel` (`es_bannerCamposRelId`, `bannerCamposRelId`, `bannerCamposTexto`) VALUES
(1, 1, ''),
(2, 2, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_contactos`
--

CREATE TABLE IF NOT EXISTS `es_contactos` (
  `es_contactoId` int(11) NOT NULL AUTO_INCREMENT,
  `contactoId` int(11) NOT NULL,
  `contactoNombre` varchar(255) NOT NULL,
  PRIMARY KEY (`es_contactoId`),
  KEY `es_contactoId` (`contactoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `es_contactos`
--

INSERT INTO `es_contactos` (`es_contactoId`, `contactoId`, `contactoNombre`) VALUES
(3, 3, '22d'),
(4, 4, 'asd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_contacto_campos`
--

CREATE TABLE IF NOT EXISTS `es_contacto_campos` (
  `es_contactoCampoId` int(11) NOT NULL AUTO_INCREMENT,
  `contactoCampoId` int(11) NOT NULL,
  `contactoCampoValor` varchar(255) NOT NULL,
  `contactoCampoPlaceholder` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`es_contactoCampoId`),
  KEY `es_contactoCampoId` (`contactoCampoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `es_contacto_campos`
--

INSERT INTO `es_contacto_campos` (`es_contactoCampoId`, `contactoCampoId`, `contactoCampoValor`, `contactoCampoPlaceholder`) VALUES
(8, 13, 'nombre', 'Jhon Doe'),
(9, 14, 'cargo', NULL),
(11, 16, 'teléfono', '(02) 2123 - 456'),
(12, 17, 'mensaje', NULL),
(13, 18, 'email', 'nombre@dominio.com'),
(14, 19, 'Fecha', ''),
(15, 20, '1', '2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_contacto_direcciones`
--

CREATE TABLE IF NOT EXISTS `es_contacto_direcciones` (
  `es_contactoDireccionId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contactoDireccionId` int(10) unsigned DEFAULT NULL,
  `contactoDireccion` mediumtext,
  PRIMARY KEY (`es_contactoDireccionId`),
  KEY `es_contactoDireccion_idx_idx` (`contactoDireccionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `es_contacto_direcciones`
--

INSERT INTO `es_contacto_direcciones` (`es_contactoDireccionId`, `contactoDireccionId`, `contactoDireccion`) VALUES
(1, 1, '<p>aa</p>'),
(4, 4, '<p>4</p>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_descargas`
--

CREATE TABLE IF NOT EXISTS `es_descargas` (
  `es_descargaId` int(11) NOT NULL AUTO_INCREMENT,
  `descargaId` int(11) NOT NULL,
  `descargaNombre` varchar(255) NOT NULL,
  `descargaUrl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`es_descargaId`),
  KEY `es_descargaId` (`descargaId`),
  KEY `es_descargaUrl` (`descargaUrl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=401 ;

--
-- Volcado de datos para la tabla `es_descargas`
--

INSERT INTO `es_descargas` (`es_descargaId`, `descargaId`, `descargaNombre`, `descargaUrl`) VALUES
(390, 398, 'caution this is sparta', 'caution-this-is-sparta'),
(391, 399, 'placeholder', NULL),
(392, 400, 'rammstein', NULL),
(393, 401, 'placeholder 2', NULL),
(394, 402, 'asas', 'asas'),
(395, 403, 'alpha', NULL),
(396, 404, 'alpha', NULL),
(397, 405, 'alpha', NULL),
(398, 406, 'alpha', NULL),
(399, 407, 'alpha', NULL),
(400, 408, 'placeholder 2', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_descargas_categorias`
--

CREATE TABLE IF NOT EXISTS `es_descargas_categorias` (
  `es_descargaCategoriaId` int(11) NOT NULL AUTO_INCREMENT,
  `descargaCategoriaId` int(11) NOT NULL,
  `descargaCategoriaNombre` varchar(255) DEFAULT NULL,
  `descargaCategoriaUrl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`es_descargaCategoriaId`),
  KEY `es_descargaCategoriaId_fk` (`descargaCategoriaId`),
  KEY `es_descargaCategoriaUrl` (`descargaCategoriaUrl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `es_descargas_categorias`
--

INSERT INTO `es_descargas_categorias` (`es_descargaCategoriaId`, `descargaCategoriaId`, `descargaCategoriaNombre`, `descargaCategoriaUrl`) VALUES
(1, 1, 'Root Tree', NULL),
(14, 15, 'cat1', 'cat1'),
(15, 16, 'cat2', 'cat2'),
(17, 18, 'cat3', 'cat3'),
(18, 19, 'cat4', 'cat4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_enlaces`
--

CREATE TABLE IF NOT EXISTS `es_enlaces` (
  `es_enlaceId` int(11) NOT NULL AUTO_INCREMENT,
  `enlaceId` int(11) NOT NULL,
  `enlaceTexto` varchar(255) NOT NULL,
  PRIMARY KEY (`es_enlaceId`),
  KEY `es_enlaceId` (`enlaceId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `es_enlaces`
--

INSERT INTO `es_enlaces` (`es_enlaceId`, `enlaceId`, `enlaceTexto`) VALUES
(1, 1, 'asd'),
(2, 2, 'AAAAA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_faq`
--

CREATE TABLE IF NOT EXISTS `es_faq` (
  `es_faqId` int(11) NOT NULL AUTO_INCREMENT,
  `faqId` int(11) NOT NULL,
  `faqPregunta` text NOT NULL,
  `faqRespuesta` mediumtext NOT NULL,
  PRIMARY KEY (`es_faqId`),
  KEY `es_faqId` (`faqId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `es_faq`
--

INSERT INTO `es_faq` (`es_faqId`, `faqId`, `faqPregunta`, `faqRespuesta`) VALUES
(3, 3, 'as1', '<p>asaas</p>'),
(4, 4, 'ds2a', ''),
(5, 5, 'asasas', ''),
(6, 6, 'fdaa', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_mapas_campos`
--

CREATE TABLE IF NOT EXISTS `es_mapas_campos` (
  `es_mapaCampoId` int(11) NOT NULL AUTO_INCREMENT,
  `mapaCampoLabel` varchar(45) DEFAULT NULL,
  `mapaCampoId` int(11) NOT NULL,
  PRIMARY KEY (`es_mapaCampoId`),
  KEY `es_mapaCampoId_mc_idx` (`mapaCampoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `es_mapas_campos`
--

INSERT INTO `es_mapas_campos` (`es_mapaCampoId`, `mapaCampoLabel`, `mapaCampoId`) VALUES
(1, 'asds', 1),
(2, 'asdddffff', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_mapa_campo_rel`
--

CREATE TABLE IF NOT EXISTS `es_mapa_campo_rel` (
  `es_mapaCampoRelId` int(11) NOT NULL AUTO_INCREMENT,
  `mapaCampoRelId` int(11) DEFAULT NULL,
  `mapaCampoTexto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`es_mapaCampoRelId`),
  KEY `es_mapaCampoRel_mcr_idx` (`mapaCampoRelId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `es_mapa_campo_rel`
--

INSERT INTO `es_mapa_campo_rel` (`es_mapaCampoRelId`, `mapaCampoRelId`, `mapaCampoTexto`) VALUES
(1, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_modulos`
--

CREATE TABLE IF NOT EXISTS `es_modulos` (
  `es_moduloId` int(11) NOT NULL AUTO_INCREMENT,
  `moduloId` int(11) NOT NULL,
  `moduloNombre` varchar(255) DEFAULT NULL,
  `moduloHtml` text,
  PRIMARY KEY (`es_moduloId`),
  KEY `moduloId` (`moduloId`),
  KEY `moduloId_m` (`moduloId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=166 ;

--
-- Volcado de datos para la tabla `es_modulos`
--

INSERT INTO `es_modulos` (`es_moduloId`, `moduloId`, `moduloNombre`, `moduloHtml`) VALUES
(16, 16, '', NULL),
(17, 17, '', NULL),
(18, 18, '', NULL),
(20, 20, '', NULL),
(30, 30, '', NULL),
(31, 31, '', NULL),
(32, 32, '', NULL),
(34, 34, '', NULL),
(35, 35, '', NULL),
(36, 36, '', NULL),
(37, 37, '', NULL),
(38, 38, '', NULL),
(39, 39, '', NULL),
(41, 41, '', NULL),
(45, 45, '', NULL),
(48, 48, 'Servicios Destacados', NULL),
(49, 49, '', NULL),
(50, 50, '', NULL),
(52, 52, 'Cat - Categorías', NULL),
(53, 53, 'Catalogo - Menu', NULL),
(54, 54, 'Productos al Azar', NULL),
(55, 55, 'Productos Destacados al Azar', NULL),
(56, 56, 'Productos Destacados', NULL),
(57, 57, 'HTML', 'HTML'),
(58, 58, 'Banner', NULL),
(59, 59, 'Preguntas Frecuentes', NULL),
(60, 60, 'Enlaces', NULL),
(61, 61, 'Galería', NULL),
(62, 62, 'Mapa', NULL),
(63, 63, 'Contacto - Direcciones', NULL),
(64, 64, 'Contacto - Formulario', NULL),
(65, 65, 'Articulo', NULL),
(66, 66, 'Servicios', NULL),
(67, 67, 'Servicios Destacados', NULL),
(68, 68, 'Publicidad', NULL),
(69, 69, NULL, NULL),
(70, 70, '', NULL),
(71, 71, '', NULL),
(72, 72, '', NULL),
(73, 73, '', NULL),
(96, 96, '', NULL),
(97, 97, '', NULL),
(98, 98, '', NULL),
(100, 100, '', NULL),
(101, 101, '', NULL),
(103, 103, '', NULL),
(110, 110, '', NULL),
(159, 159, '', NULL),
(160, 160, '', ''),
(161, 161, '', NULL),
(164, 164, 'FAQ2', NULL),
(165, 165, 'FAQ1', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_paginas`
--

CREATE TABLE IF NOT EXISTS `es_paginas` (
  `es_paginaId` int(11) NOT NULL AUTO_INCREMENT,
  `paginaId` int(11) NOT NULL,
  `paginaNombre` varchar(255) NOT NULL,
  `paginaNombreMenu` varchar(255) NOT NULL,
  `paginaNombreURL` varchar(255) NOT NULL,
  `paginaKeywords` varchar(255) DEFAULT NULL,
  `paginaDescripcion` varchar(255) DEFAULT NULL,
  `paginaTitulo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`es_paginaId`),
  KEY `es_paginaId` (`paginaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Volcado de datos para la tabla `es_paginas`
--

INSERT INTO `es_paginas` (`es_paginaId`, `paginaId`, `paginaNombre`, `paginaNombreMenu`, `paginaNombreURL`, `paginaKeywords`, `paginaDescripcion`, `paginaTitulo`) VALUES
(1, 1, 'Root Node', '', '', NULL, NULL, NULL),
(13, 163, 'Publicaciones', 'Publicaciones', 'publicaciones', '', '', ''),
(14, 164, 'Galeria', 'Galeria', 'galeria', '', '', ''),
(15, 165, 'Catalogo', 'Catalogo', 'catalogo', '', 'Pagina de catalogo', ''),
(16, 166, 'Servicios', 'Servicios', 'servicios', '', '', ''),
(21, 171, 'Articulo', 'Articulo', 'articulo', '', '', ''),
(22, 172, 'Links', 'Links', 'links', '', '', ''),
(23, 173, 'Carrito de Compras', 'Carrito de Compras', 'carrito-de-compras', '', '', ''),
(25, 175, 'Auth', 'Auth', 'auth', '', '', ''),
(26, 176, 'Noticias', 'Noticias', 'noticias', '', '', ''),
(27, 177, 'Calendario', 'Calendario', 'calendario', '', '', ''),
(28, 178, 'Contacto', 'Contacto', 'contacto', '', '', ''),
(29, 179, 'Modulos', 'Modulos', 'modulos', '', '', ''),
(30, 180, 'Servicios 2', 'Servicios 2', 'servicios-2', '', '', ''),
(31, 181, 'FAQ', 'FAQ', 'faq', '', '', ''),
(32, 182, 'test', 'test', 'test', '', '', ''),
(33, 183, 'FAQ2', 'FAQ2', 'faq2', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_productos`
--

CREATE TABLE IF NOT EXISTS `es_productos` (
  `es_productoId` int(11) NOT NULL AUTO_INCREMENT,
  `productoId` int(11) NOT NULL,
  `productoNombre` varchar(255) NOT NULL,
  `productoUrl` varchar(255) DEFAULT NULL,
  `productoKeywords` varchar(255) DEFAULT NULL,
  `productoDescripcion` varchar(255) DEFAULT NULL,
  `productoMetaTitulo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`es_productoId`),
  KEY `es_productoId` (`productoId`),
  KEY `es_productoUrl` (`productoUrl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;

--
-- Volcado de datos para la tabla `es_productos`
--

INSERT INTO `es_productos` (`es_productoId`, `productoId`, `productoNombre`, `productoUrl`, `productoKeywords`, `productoDescripcion`, `productoMetaTitulo`) VALUES
(127, 127, '', NULL, NULL, NULL, NULL),
(128, 128, '', NULL, NULL, NULL, NULL),
(129, 129, '', NULL, NULL, NULL, NULL),
(130, 130, '', NULL, NULL, NULL, NULL),
(131, 131, '', NULL, NULL, NULL, NULL),
(133, 133, '', NULL, NULL, NULL, NULL),
(136, 136, 'prod1', 'prod1', 'test', 'asa dsdsd sd', '12345'),
(137, 137, 'prod2', 'prod2', '', NULL, NULL),
(139, 139, '', NULL, NULL, NULL, NULL),
(140, 140, '', NULL, NULL, NULL, NULL),
(141, 141, 'prod3', 'prod3', '', NULL, NULL),
(143, 143, 'prod4', 'prod4', '', NULL, NULL),
(144, 144, '', NULL, NULL, NULL, NULL),
(146, 146, '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_producto_campos`
--

CREATE TABLE IF NOT EXISTS `es_producto_campos` (
  `es_productoCampoId` int(11) NOT NULL AUTO_INCREMENT,
  `productoCampoId` int(11) NOT NULL,
  `productoCampoValor` text NOT NULL,
  PRIMARY KEY (`es_productoCampoId`),
  KEY `es_productoCampoId_pc` (`productoCampoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Volcado de datos para la tabla `es_producto_campos`
--

INSERT INTO `es_producto_campos` (`es_productoCampoId`, `productoCampoId`, `productoCampoValor`) VALUES
(28, 17, 'Desc'),
(29, 18, 'Gallery'),
(30, 19, 'Listado'),
(31, 20, 'Colores'),
(32, 21, 'Tabla'),
(33, 22, 'codigo'),
(34, 23, 'Tabla 2'),
(35, 24, 'precio'),
(37, 26, 'Audios'),
(38, 27, 'asd'),
(39, 28, 'asd'),
(40, 29, 'asd'),
(41, 30, 'asd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_producto_campos_listado_predefinido`
--

CREATE TABLE IF NOT EXISTS `es_producto_campos_listado_predefinido` (
  `es_productoCamposListadoPredefinidoId` int(11) NOT NULL AUTO_INCREMENT,
  `productoCamposListadoPredefinidoId` int(11) DEFAULT NULL,
  `productoCamposListadoPredefinidoTexto` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`es_productoCamposListadoPredefinidoId`),
  KEY `es_productoCamposListadoPredefinidoId_pclpr_idx` (`productoCamposListadoPredefinidoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `es_producto_campos_listado_predefinido`
--

INSERT INTO `es_producto_campos_listado_predefinido` (`es_productoCamposListadoPredefinidoId`, `productoCamposListadoPredefinidoId`, `productoCamposListadoPredefinidoTexto`) VALUES
(1, 2, '1'),
(2, 3, '2'),
(3, 4, '3'),
(4, 5, '1a'),
(5, 6, '2a'),
(6, 7, '3a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_producto_campos_rel`
--

CREATE TABLE IF NOT EXISTS `es_producto_campos_rel` (
  `es_productoCampoRelId` int(11) NOT NULL AUTO_INCREMENT,
  `productoCampoRelId` int(11) NOT NULL,
  `productoCampoRelContenido` mediumtext,
  PRIMARY KEY (`es_productoCampoRelId`),
  KEY `es_productoCampoRelId` (`productoCampoRelId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=995 ;

--
-- Volcado de datos para la tabla `es_producto_campos_rel`
--

INSERT INTO `es_producto_campos_rel` (`es_productoCampoRelId`, `productoCampoRelId`, `productoCampoRelContenido`) VALUES
(791, 791, ''),
(794, 794, ''),
(795, 795, ''),
(796, 796, ''),
(797, 797, ''),
(798, 798, ''),
(799, 799, ''),
(800, 800, ''),
(801, 801, ''),
(804, 804, ''),
(805, 805, ''),
(806, 806, ''),
(807, 807, ''),
(808, 808, ''),
(809, 809, ''),
(810, 810, ''),
(811, 811, ''),
(814, 814, ''),
(815, 815, ''),
(816, 816, ''),
(817, 817, ''),
(818, 818, ''),
(819, 819, ''),
(820, 820, ''),
(821, 821, ''),
(824, 824, ''),
(825, 825, ''),
(826, 826, ''),
(827, 827, ''),
(828, 828, ''),
(829, 829, ''),
(830, 830, ''),
(831, 831, ''),
(834, 834, ''),
(835, 835, ''),
(836, 836, ''),
(837, 837, ''),
(838, 838, ''),
(839, 839, ''),
(840, 840, ''),
(851, 851, ''),
(854, 854, ''),
(855, 855, ''),
(856, 856, ''),
(857, 857, ''),
(858, 858, ''),
(859, 859, ''),
(860, 860, ''),
(881, 881, ''),
(884, 884, ''),
(885, 885, NULL),
(886, 886, NULL),
(887, 887, NULL),
(888, 888, '<table id="es_editor_grid_21" class="tableGrid">\n                                                        <tbody>\n                                                        <tr>\n                                                            <th>nombre cabecera</th>\n                                                        </tr>\n                                                        <tr>\n                                                            <td></td>\n                                                        </tr>\n                                                        </tbody>\n                                                    </table>'),
(889, 889, '<table id="es_editor_grid_23" class="tableGrid">\n                                                        <tbody>\n                                                        <tr>\n                                                            <th>nombre cabecera</th>\n                                                        </tr>\n                                                        <tr>\n                                                            <td></td>\n                                                        </tr>\n                                                        </tbody>\n                                                    </table>'),
(890, 890, NULL),
(891, 891, ''),
(894, 894, ''),
(895, 895, NULL),
(896, 896, NULL),
(897, 897, NULL),
(898, 898, '<table id="es_editor_grid_21" class="tableGrid">\n                                                        <tbody>\n                                                        <tr>\n                                                            <th>nombre cabecera</th>\n                                                        </tr>\n                                                        <tr>\n                                                            <td></td>\n                                                        </tr>\n                                                        </tbody>\n                                                    </table>'),
(899, 899, '<table id="es_editor_grid_23" class="tableGrid">\n                                                        <tbody>\n                                                        <tr>\n                                                            <th>nombre cabecera</th>\n                                                        </tr>\n                                                        <tr>\n                                                            <td></td>\n                                                        </tr>\n                                                        </tbody>\n                                                    </table>'),
(900, 900, NULL),
(911, 911, ''),
(914, 914, ''),
(915, 915, ''),
(916, 916, ''),
(917, 917, ''),
(918, 918, ''),
(919, 919, ''),
(920, 920, ''),
(921, 921, ''),
(924, 924, ''),
(925, 925, ''),
(926, 926, ''),
(927, 927, ''),
(928, 928, ''),
(929, 929, ''),
(930, 930, ''),
(931, 931, ''),
(934, 934, ''),
(935, 935, NULL),
(936, 936, NULL),
(937, 937, NULL),
(938, 938, '<table id="es_editor_grid_21" class="tableGrid">\n                                                        <tbody>\n                                                        <tr>\n                                                            <th>nombre cabecera</th>\n                                                        </tr>\n                                                        <tr>\n                                                            <td></td>\n                                                        </tr>\n                                                        </tbody>\n                                                    </table>'),
(939, 939, '<table id="es_editor_grid_23" class="tableGrid">\n                                                        <tbody>\n                                                        <tr>\n                                                            <th>nombre cabecera</th>\n                                                        </tr>\n                                                        <tr>\n                                                            <td></td>\n                                                        </tr>\n                                                        </tbody>\n                                                    </table>'),
(940, 940, NULL),
(951, 951, ''),
(954, 954, ''),
(955, 955, NULL),
(956, 956, NULL),
(957, 957, NULL),
(958, 958, '<table id="es_editor_grid_21" class="tableGrid">\n                                                        <tbody>\n                                                        <tr>\n                                                            <th>nombre cabecera</th>\n                                                        </tr>\n                                                        <tr>\n                                                            <td></td>\n                                                        </tr>\n                                                        </tbody>\n                                                    </table>'),
(959, 959, '<table id="es_editor_grid_23" class="tableGrid">\n                                                        <tbody>\n                                                        <tr>\n                                                            <th>nombre cabecera</th>\n                                                        </tr>\n                                                        <tr>\n                                                            <td></td>\n                                                        </tr>\n                                                        </tbody>\n                                                    </table>'),
(960, 960, NULL),
(961, 961, ''),
(964, 964, ''),
(965, 965, ''),
(966, 966, ''),
(967, 967, ''),
(968, 968, ''),
(969, 969, ''),
(970, 970, ''),
(981, 981, ''),
(984, 984, ''),
(985, 985, ''),
(986, 986, ''),
(987, 987, ''),
(988, 988, ''),
(989, 989, ''),
(990, 990, ''),
(991, 991, ''),
(992, 992, ''),
(993, 993, ''),
(994, 994, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_producto_categorias`
--

CREATE TABLE IF NOT EXISTS `es_producto_categorias` (
  `es_productoCategoriaId` int(11) NOT NULL AUTO_INCREMENT,
  `productoCategoriaId` int(11) NOT NULL,
  `productoCategoriaNombre` varchar(255) DEFAULT NULL,
  `productoCategoriaUrl` varchar(255) DEFAULT NULL,
  `productoCategoriaDescripcion` text,
  PRIMARY KEY (`es_productoCategoriaId`),
  KEY `es_productoCategoriaId` (`productoCategoriaId`),
  KEY `es_productoCategoriaUrl` (`productoCategoriaUrl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Volcado de datos para la tabla `es_producto_categorias`
--

INSERT INTO `es_producto_categorias` (`es_productoCategoriaId`, `productoCategoriaId`, `productoCategoriaNombre`, `productoCategoriaUrl`, `productoCategoriaDescripcion`) VALUES
(1, 1, 'Tree Root', NULL, NULL),
(19, 22, 'Cat2', 'cat2', ''),
(21, 24, 'Cat1', 'cat1', ''),
(22, 25, 'Cat3', 'cat3', ''),
(23, 26, 'Cat4', 'cat4', ''),
(24, 27, 'Cat6', 'cat6', ''),
(25, 28, 'Cat3', 'cat3', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_producto_descargas`
--

CREATE TABLE IF NOT EXISTS `es_producto_descargas` (
  `es_productoDescargaId` int(11) NOT NULL AUTO_INCREMENT,
  `productoDescargaId` int(11) NOT NULL,
  `productoDescargaTexto` text NOT NULL,
  PRIMARY KEY (`es_productoDescargaId`),
  KEY `es_productoDescargaId` (`productoDescargaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_producto_imagenes`
--

CREATE TABLE IF NOT EXISTS `es_producto_imagenes` (
  `es_productoImagenId` int(11) NOT NULL AUTO_INCREMENT,
  `productoImagenId` int(11) NOT NULL,
  `productoImagenTexto` text,
  PRIMARY KEY (`es_productoImagenId`),
  KEY `es_productoImagenId` (`productoImagenId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_producto_videos`
--

CREATE TABLE IF NOT EXISTS `es_producto_videos` (
  `es_productoVideoId` int(11) NOT NULL AUTO_INCREMENT,
  `productoVideoId` int(11) NOT NULL,
  `productoVideoTexto` text NOT NULL,
  `productoVideoTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`es_productoVideoId`),
  KEY `es_productoVideoId` (`productoVideoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_publicaciones`
--

CREATE TABLE IF NOT EXISTS `es_publicaciones` (
  `es_publicacionId` int(11) NOT NULL AUTO_INCREMENT,
  `publicacionId` int(11) NOT NULL,
  `publicacionNombre` varchar(255) NOT NULL,
  `publicacionTexto` mediumtext NOT NULL,
  `publicacionUrl` varchar(255) DEFAULT NULL,
  `publicacionLink` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`es_publicacionId`),
  KEY `publicacionId` (`publicacionId`),
  KEY `es_publicacionId` (`publicacionId`),
  KEY `es_publicacionUrl` (`publicacionUrl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `es_publicaciones`
--

INSERT INTO `es_publicaciones` (`es_publicacionId`, `publicacionId`, `publicacionNombre`, `publicacionTexto`, `publicacionUrl`, `publicacionLink`) VALUES
(4, 4, '', '', '', NULL),
(5, 5, 'asdf', '', 'asdf', ''),
(6, 6, 'Noticia 1', '<p>asd</p>', 'noticia-1', ''),
(7, 7, '', '', '', NULL),
(8, 8, 'asd', '<p>asasas</p>', 'asd', ''),
(9, 9, '', '', '', NULL),
(10, 10, '', '', '', NULL),
(11, 11, '', '', '', NULL),
(12, 12, 'Noticia 1a', '<p>1ass</p>', 'noticia-1a', ''),
(13, 13, '', '', '', NULL),
(14, 14, 'AASASA', '<p>asasas</p>', 'aasasa', ''),
(15, 15, '', '', '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_servicios`
--

CREATE TABLE IF NOT EXISTS `es_servicios` (
  `es_servicioId` int(11) NOT NULL AUTO_INCREMENT,
  `servicioId` int(11) DEFAULT NULL,
  `servicioTitulo` varchar(45) DEFAULT NULL,
  `servicioTexto` mediumtext,
  `servicioUrl` varchar(255) DEFAULT NULL,
  `servicioKeywords` varchar(255) DEFAULT NULL,
  `servicioDescripcion` varchar(255) DEFAULT NULL,
  `servicioMetaTitulo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`es_servicioId`),
  KEY `servicioId_idx_idx` (`servicioId`),
  KEY `es_servicioId` (`servicioId`),
  KEY `es_servicioUrl` (`servicioUrl`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `es_servicios`
--

INSERT INTO `es_servicios` (`es_servicioId`, `servicioId`, `servicioTitulo`, `servicioTexto`, `servicioUrl`, `servicioKeywords`, `servicioDescripcion`, `servicioMetaTitulo`) VALUES
(1, 1, 'servicio 1d', '<p>sdsd</p>', 'servicio-1d', 'asdf, asd', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed convallis placerat arcu, quis sodales orci luctus id. Proin eu purus cursus, pulvinar diam ac, facilisis leo. Pellentesque pellentesque molestie iaculis. In purus erat, accumsan quis quam sit am', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'),
(2, 2, NULL, NULL, '', NULL, NULL, NULL),
(3, 3, NULL, NULL, '', NULL, NULL, NULL),
(4, 4, NULL, NULL, '', NULL, NULL, NULL),
(10, 10, 'asd', '<p>sasas</p>', 'asd', '', '', ''),
(11, 11, 'ssssssss', '<p>asass</p>', 'ssssssss', '', '', ''),
(12, 12, 'ffffff', '', 'ffffff', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `es_user_fields`
--

CREATE TABLE IF NOT EXISTS `es_user_fields` (
  `es_userFieldId` int(11) NOT NULL AUTO_INCREMENT,
  `userFieldLabel` varchar(45) DEFAULT NULL,
  `userFieldId` int(11) DEFAULT NULL,
  `userFieldPlaceholder` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`es_userFieldId`),
  KEY `userFieldId_es_uf_idx` (`userFieldId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `faqId` int(11) NOT NULL AUTO_INCREMENT,
  `paginaId` int(11) NOT NULL,
  `faqPosicion` int(50) DEFAULT '0',
  `faqClase` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `faqHabilitado` varchar(2) CHARACTER SET latin1 DEFAULT 'si',
  `usuarioId` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`faqId`),
  KEY `fk_fpaginaId_faq_idx` (`paginaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `faq`
--

INSERT INTO `faq` (`faqId`, `paginaId`, `faqPosicion`, `faqClase`, `faqHabilitado`, `usuarioId`) VALUES
(3, 181, 1, '', 'on', NULL),
(4, 183, 2, '', 'on', NULL),
(5, 183, 3, '', 'on', NULL),
(6, 183, 4, '', 'on', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `order` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `order`) VALUES
(1, 'admin', 'Administradores', 2),
(2, 'members', 'Miembros', 3),
(3, 'superadmin', 'Super Administradores', 1),
(4, 'public', 'Público', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idioma`
--

CREATE TABLE IF NOT EXISTS `idioma` (
  `idiomaId` int(11) NOT NULL AUTO_INCREMENT,
  `idiomaNombre` varchar(25) CHARACTER SET latin1 NOT NULL,
  `idiomaDiminutivo` varchar(5) CHARACTER SET latin1 NOT NULL COMMENT 'españo es,ingles en',
  `usuarioId` mediumint(8) DEFAULT NULL,
  `idiomaTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idiomaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `idioma`
--

INSERT INTO `idioma` (`idiomaId`, `idiomaNombre`, `idiomaDiminutivo`, `usuarioId`, `idiomaTemporal`) VALUES
(1, 'spanish', 'es', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE IF NOT EXISTS `imagenes` (
  `imagenId` int(11) NOT NULL AUTO_INCREMENT,
  `seccionId` int(11) NOT NULL,
  `imagenSufijo` varchar(45) DEFAULT '_huge',
  `imagenAncho` smallint(6) DEFAULT '500',
  `imagenAlto` smallint(6) DEFAULT '300',
  `imagenNombre` varchar(45) DEFAULT NULL,
  `imagenTemporal` tinyint(1) DEFAULT '0',
  `imagenPosicion` tinyint(3) DEFAULT NULL,
  `imagenCrop` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`imagenId`),
  KEY `seccionId_i` (`seccionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Volcado de datos para la tabla `imagenes`
--

INSERT INTO `imagenes` (`imagenId`, `seccionId`, `imagenSufijo`, `imagenAncho`, `imagenAlto`, `imagenNombre`, `imagenTemporal`, `imagenPosicion`, `imagenCrop`) VALUES
(12, 1, '_medium', 150, 75, 'Mediana', 0, 2, 0),
(13, 1, '_big', 500, 300, 'Grande', 0, 1, 1),
(14, 6, '_thumb', 120, 70, 'Thumb', 0, 2, 0),
(16, 6, '_big', 372, 326, 'Grande', 0, 1, 1),
(22, 7, '', 200, 150, 'Normal', 0, 1, 1),
(27, 10, '_big', 700, 500, 'Detalle', 0, 1, 1),
(32, 10, '_medium', 150, 75, 'Modulo', 0, 2, 0),
(33, 11, '_ubicacion', 200, 150, 'ubicaion', 0, 1, 1),
(35, 12, '', 200, 150, 'Normal', 0, 1, 1),
(36, 13, '', 120, 120, 'Perfil', 0, 1, 1),
(37, 14, '', 100, 100, 'Normal', 0, 1, 1),
(40, 8, '', 200, 200, 'Normal', 0, 1, 1),
(41, 2, '', 500, 5000, 'Normal', 0, 1, NULL),
(42, 15, '', 200, 200, 'Large', 0, 1, 1),
(43, 5, '', 800, 600, 'Grande', 0, 1, 1),
(44, 16, '_thumb', 150, 75, 'Thumb', 0, 2, 0),
(45, 16, '', 800, 600, 'large', 0, 1, 1),
(46, 4, '', 500, 500, 'Mapa', 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_secciones`
--

CREATE TABLE IF NOT EXISTS `imagenes_secciones` (
  `imagenSeccionId` int(11) NOT NULL AUTO_INCREMENT,
  `imagenSeccionNombre` varchar(45) NOT NULL,
  `adminSeccionId` int(11) NOT NULL,
  PRIMARY KEY (`imagenSeccionId`),
  KEY `adminSeccionId_imgr` (`adminSeccionId`) USING BTREE,
  KEY `adminSeccionId_is` (`adminSeccionId`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Volcado de datos para la tabla `imagenes_secciones`
--

INSERT INTO `imagenes_secciones` (`imagenSeccionId`, `imagenSeccionNombre`, `adminSeccionId`) VALUES
(1, 'Enlaces', 4),
(2, 'Publicaciones', 5),
(3, 'Banners', 6),
(4, 'Mapas', 7),
(5, 'Producto', 8),
(6, 'Galería', 8),
(7, 'Categorías', 8),
(8, 'Galería', 9),
(10, 'Servicios', 15),
(11, 'Ubicaciones', 7),
(12, 'Categoría', 9),
(13, 'Usuarios', 12),
(14, 'Direccion', 11),
(15, 'Galería', 5),
(16, 'Galería', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `input`
--

CREATE TABLE IF NOT EXISTS `input` (
  `inputId` int(11) NOT NULL AUTO_INCREMENT,
  `inputTipoContenido` text CHARACTER SET latin1 NOT NULL,
  `inputTipoId` int(11) NOT NULL,
  `input_seccion` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT 'contacto' COMMENT 'donde se mostrara el input contacto , producto o ambos',
  PRIMARY KEY (`inputId`),
  KEY `fk_input_contacto_inputs_rel1` (`inputId`),
  KEY `inputTipoId` (`inputTipoId`),
  KEY `inputTipoId_i` (`inputTipoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Volcado de datos para la tabla `input`
--

INSERT INTO `input` (`inputId`, `inputTipoContenido`, `inputTipoId`, `input_seccion`) VALUES
(8, 'numero', 1, 'contacto'),
(9, 'texto', 1, 'banners'),
(10, 'texto multilinea', 3, 'banners'),
(11, 'texto multilinea', 3, 'contacto'),
(12, 'texto multilinea', 3, 'producto'),
(13, 'texto', 1, 'contacto'),
(14, 'texto', 1, 'producto'),
(16, 'link', 1, 'producto'),
(17, 'link', 1, 'contacto'),
(18, 'tabla', 5, 'producto'),
(19, 'imágenes', 6, 'producto'),
(20, 'archivos', 7, 'producto'),
(21, 'videos', 11, 'producto'),
(22, 'precio', 1, 'producto'),
(23, 'checkbox', 9, 'producto'),
(24, 'checkbox', 9, 'contacto'),
(25, 'texto', 1, 'usuarios'),
(26, 'texto multilinea', 3, 'usuarios'),
(27, 'texto', 1, 'mapas'),
(28, 'texto multilinea', 3, 'mapas'),
(29, 'listado', 12, 'producto'),
(30, 'listado predefinido', 12, 'producto'),
(31, 'fecha', 13, 'contacto'),
(32, 'fecha', 13, 'usuarios'),
(33, 'país', 12, 'usuarios'),
(34, 'audios', 14, 'producto'),
(37, 'texto', 1, 'calendario'),
(38, 'texto multilinea', 3, 'calendario'),
(40, 'imágenes', 6, 'calendario'),
(41, 'archivos', 7, 'calendario'),
(42, 'tabla', 5, 'calendario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `input_tipo`
--

CREATE TABLE IF NOT EXISTS `input_tipo` (
  `inputTipoId` int(11) NOT NULL AUTO_INCREMENT,
  `inputTipoNombre` varchar(30) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`inputTipoId`),
  KEY `fk_input_tipo_input1` (`inputTipoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Volcado de datos para la tabla `input_tipo`
--

INSERT INTO `input_tipo` (`inputTipoId`, `inputTipoNombre`) VALUES
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
-- Estructura de tabla para la tabla `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `loc_id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_type_fk` smallint(5) NOT NULL DEFAULT '0',
  `loc_parent_fk` int(11) NOT NULL DEFAULT '0',
  `loc_ship_zone_fk` smallint(5) NOT NULL DEFAULT '0',
  `loc_tax_zone_fk` smallint(5) NOT NULL DEFAULT '0',
  `loc_name` varchar(50) NOT NULL DEFAULT '',
  `loc_status` tinyint(1) NOT NULL DEFAULT '0',
  `loc_ship_default` tinyint(1) NOT NULL DEFAULT '0',
  `loc_tax_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_id`),
  UNIQUE KEY `loc_id` (`loc_id`) USING BTREE,
  KEY `loc_type_fk` (`loc_type_fk`) USING BTREE,
  KEY `loc_tax_zone_fk` (`loc_tax_zone_fk`),
  KEY `loc_ship_zone_fk` (`loc_ship_zone_fk`),
  KEY `loc_parent_fk` (`loc_parent_fk`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `locations`
--

INSERT INTO `locations` (`loc_id`, `loc_type_fk`, `loc_parent_fk`, `loc_ship_zone_fk`, `loc_tax_zone_fk`, `loc_name`, `loc_status`, `loc_ship_default`, `loc_tax_default`) VALUES
(4, 14, 0, 0, 0, 'Ecuador', 1, 1, 1),
(5, 14, 0, 0, 0, 'Colombia', 1, 0, 0),
(6, 19, 4, 4, 0, 'Pichincha', 1, 0, 0),
(7, 19, 4, 5, 0, 'Napo', 1, 0, 0),
(13, 20, 6, 0, 0, 'Quito', 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `location_type`
--

CREATE TABLE IF NOT EXISTS `location_type` (
  `loc_type_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `loc_type_parent_fk` smallint(5) NOT NULL DEFAULT '0',
  `loc_type_name` varchar(50) NOT NULL DEFAULT '',
  `loc_type_temporary` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`loc_type_id`),
  UNIQUE KEY `loc_type_id` (`loc_type_id`),
  KEY `loc_type_parent_fk` (`loc_type_parent_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Volcado de datos para la tabla `location_type`
--

INSERT INTO `location_type` (`loc_type_id`, `loc_type_parent_fk`, `loc_type_name`, `loc_type_temporary`) VALUES
(1, 0, '', 1),
(2, 0, '', 1),
(4, 0, '', 1),
(7, 0, '', 1),
(8, 0, '', 1),
(9, 0, '', 1),
(10, 0, '', 1),
(14, 0, 'País', 0),
(16, 0, '', 1),
(17, 0, '', 1),
(18, 0, '', 1),
(19, 14, 'Provincia', 0),
(20, 19, 'Cuidad', 0),
(21, 0, '', 1),
(22, 0, '', 1),
(24, 0, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `location_zones`
--

CREATE TABLE IF NOT EXISTS `location_zones` (
  `lzone_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `lzone_name` varchar(50) NOT NULL DEFAULT '',
  `lzone_description` longtext NOT NULL,
  `lzone_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lzone_id`),
  UNIQUE KEY `lzone_id` (`lzone_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `location_zones`
--

INSERT INTO `location_zones` (`lzone_id`, `lzone_name`, `lzone_description`, `lzone_status`) VALUES
(1, 'Costa', '', 1),
(4, 'Sierra', '', 1),
(5, 'Amazonía', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapas`
--

CREATE TABLE IF NOT EXISTS `mapas` (
  `mapaId` int(11) NOT NULL AUTO_INCREMENT,
  `mapaNombre` varchar(255) NOT NULL,
  `mapaImagen` varchar(255) NOT NULL,
  `mapaImagenCoord` varchar(150) DEFAULT NULL,
  `mapaPublicado` tinyint(1) NOT NULL DEFAULT '1',
  `usuarioId` mediumint(8) DEFAULT NULL,
  `mapaTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`mapaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `mapas`
--

INSERT INTO `mapas` (`mapaId`, `mapaNombre`, `mapaImagen`, `mapaImagenCoord`, `mapaPublicado`, `usuarioId`, `mapaTemporal`) VALUES
(1, 'World', 'jpg?1428686369', '{"top":0,"left":0,"width":667,"height":500,"scale":0}', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapas_campos`
--

CREATE TABLE IF NOT EXISTS `mapas_campos` (
  `mapaCampoId` int(11) NOT NULL AUTO_INCREMENT,
  `mapaCampoLabel` varchar(45) DEFAULT NULL,
  `inputId` int(11) DEFAULT NULL,
  `mapaCampoPublicado` tinyint(1) NOT NULL DEFAULT '1',
  `mapaCampoClase` varchar(45) DEFAULT NULL,
  `mapaCampoPosition` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`mapaCampoId`),
  KEY `inputId_mc_idx` (`inputId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `mapas_campos`
--

INSERT INTO `mapas_campos` (`mapaCampoId`, `mapaCampoLabel`, `inputId`, `mapaCampoPublicado`, `mapaCampoClase`, `mapaCampoPosition`) VALUES
(1, NULL, 27, 1, '', 1),
(2, NULL, 27, 1, '', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapas_ubicaciones`
--

CREATE TABLE IF NOT EXISTS `mapas_ubicaciones` (
  `mapaUbicacionId` int(11) NOT NULL AUTO_INCREMENT,
  `mapaId` int(11) NOT NULL,
  `mapaUbicacionNombre` varchar(255) NOT NULL,
  `mapaUbicacionX` int(11) NOT NULL,
  `mapaUbicacionY` int(11) NOT NULL,
  `mapaUbicacionImagen` varchar(255) NOT NULL,
  `mapaUbicacionImagenCoord` varchar(150) DEFAULT NULL,
  `mapaUbicacionPublicado` tinyint(1) NOT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `mapaUbicacionTemporal` tinyint(1) DEFAULT NULL,
  `mapaUbicacionClase` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`mapaUbicacionId`),
  KEY `mapaId_idx` (`mapaId`),
  KEY `mapaId_mu` (`mapaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Volcado de datos para la tabla `mapas_ubicaciones`
--

INSERT INTO `mapas_ubicaciones` (`mapaUbicacionId`, `mapaId`, `mapaUbicacionNombre`, `mapaUbicacionX`, `mapaUbicacionY`, `mapaUbicacionImagen`, `mapaUbicacionImagenCoord`, `mapaUbicacionPublicado`, `usuarioId`, `mapaUbicacionTemporal`, `mapaUbicacionClase`) VALUES
(21, 1, 'USA', 288, 143, 'jpg?1413577276?1413578390?1413578418', '{"top":0,"left":0,"width":200,"height":150,"scale":0}', 1, NULL, NULL, 'main'),
(22, 1, 'España', 366, 118, '', '', 1, NULL, NULL, NULL),
(23, 1, 'Costa Rica', 158, 214, '', '', 1, NULL, NULL, 'right'),
(24, 1, 'Venezuela', 187, 214, '', '', 1, NULL, NULL, NULL),
(25, 1, 'Colombia', 166, 237, '', '', 1, NULL, NULL, NULL),
(26, 1, 'Ecuador', 160, 247, '', '', 1, NULL, NULL, NULL),
(27, 1, 'Brasil', 255, 271, '', '', 1, NULL, NULL, NULL),
(28, 1, 'Perú', 175, 279, '', '', 1, NULL, NULL, NULL),
(29, 1, 'Chile', 193, 356, '', '', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapa_campo_rel`
--

CREATE TABLE IF NOT EXISTS `mapa_campo_rel` (
  `mapaCampoRelId` int(11) NOT NULL AUTO_INCREMENT,
  `mapaCampoId` int(11) DEFAULT NULL,
  `mapaUbicacionId` int(11) DEFAULT NULL,
  PRIMARY KEY (`mapaCampoRelId`),
  KEY `mapaCampoId_mcr_idx` (`mapaCampoId`),
  KEY `mapaUbicacionId_mcr_idx` (`mapaUbicacionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `mapa_campo_rel`
--

INSERT INTO `mapa_campo_rel` (`mapaCampoRelId`, `mapaCampoId`, `mapaUbicacionId`) VALUES
(1, 1, 21);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE IF NOT EXISTS `modulos` (
  `moduloId` int(11) NOT NULL AUTO_INCREMENT,
  `paginaId` int(11) NOT NULL,
  `paginaModuloTipoId` int(11) DEFAULT NULL,
  `moduloParam1` varchar(255) DEFAULT NULL,
  `moduloParam2` text,
  `moduloParam3` varchar(255) DEFAULT NULL,
  `moduloParam4` int(5) DEFAULT NULL,
  `moduloMostrarTitulo` tinyint(1) DEFAULT '1',
  `moduloClase` varchar(255) DEFAULT NULL,
  `moduloVerPaginacion` tinyint(1) DEFAULT '1',
  `moduloHabilitado` tinyint(1) DEFAULT '1',
  `moduloVista` varchar(45) DEFAULT 'default_view.php',
  PRIMARY KEY (`moduloId`),
  KEY `paginaModuloTipoId` (`paginaModuloTipoId`),
  KEY `paginaId_m` (`paginaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=166 ;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`moduloId`, `paginaId`, `paginaModuloTipoId`, `moduloParam1`, `moduloParam2`, `moduloParam3`, `moduloParam4`, `moduloMostrarTitulo`, `moduloClase`, `moduloVerPaginacion`, `moduloHabilitado`, `moduloVista`) VALUES
(16, 165, 23, '', '', '', 0, 0, '', 0, 1, ''),
(17, 165, 8, '4', '1', '', 0, 0, '', 1, 1, 'default_view.php'),
(18, 164, 8, '6', '1', '', 0, 0, '', 1, 1, 'default_view.php'),
(20, 164, 23, '', '', '', 0, 0, '', 0, 1, ''),
(30, 171, 8, '1', '', '', 0, 0, '', 1, 1, ''),
(31, 172, 8, '10', '', '', 0, 0, '', 1, 1, ''),
(32, 173, 8, '9', '', '', 0, 0, '', 1, 1, ''),
(34, 175, 8, '11', '', '', 0, 0, '', 1, 1, ''),
(35, 176, 8, '5', '1', '', 0, 0, '', 1, 1, ''),
(36, 163, 9, '75', '', '', 0, 0, '', 0, 1, 'default_view.php'),
(37, 177, 8, '13', '', '', 0, 0, '', 1, 1, ''),
(38, 178, 20, '', '', '', 0, 1, '', 0, 1, 'default_view.php'),
(39, 164, 15, '15', '1', '40', 0, 1, '', 1, 1, ''),
(41, 180, 8, '12', '', '', 0, 0, '', 1, 1, ''),
(45, 172, 25, '', '', '', 0, 1, '', 0, 1, ''),
(48, 165, 27, '166', '11', '27', 0, 1, '2', 1, 1, 'default_view.php'),
(49, 165, 22, '166', '2', '27', 0, 1, '', 1, 1, 'default_view.php'),
(50, 181, 8, '2', '', '', 0, 0, '', 0, 1, ''),
(52, 179, 2, '28', '1', '43', 0, 1, '', 1, 1, 'default_view.php'),
(53, 179, 11, '0', '', '', 0, 1, '', 0, 1, 'default_view.php'),
(54, 179, 19, '28', '1', '43', 0, 1, '', 0, 1, 'default_view.php'),
(55, 179, 26, '28', '1', '43', 0, 1, '', 0, 1, 'default_view.php'),
(56, 179, 10, '28', '', '43', 0, 1, '', 1, 1, 'default_view.php'),
(57, 179, 3, '', '', '', 0, 1, '', 0, 1, ''),
(58, 179, 9, '73', '', '', 0, 1, '', 0, 1, 'default_view.php'),
(59, 179, 13, '', '1', '', 0, 1, '', 1, 1, 'default_view.php'),
(60, 179, 14, '172', '1', '13', 0, 1, '', 1, 1, 'default_view.php'),
(61, 179, 15, '15', '1', '40', 0, 1, '', 1, 1, ''),
(62, 179, 16, '1', '', '', 0, 1, '', 0, 1, 'default_view.php'),
(63, 179, 24, '', '', '37', 0, 1, '', 0, 1, 'default_view.php'),
(64, 179, 20, '', '', '', 0, 1, '', 0, 1, 'default_view.php'),
(65, 179, 21, '2', '', '', 0, 1, '', 0, 1, 'default_view.php'),
(66, 179, 22, '166', '1', '27', 0, 1, '', 1, 1, 'default_view.php'),
(67, 179, 27, '166', '1', '27', 0, 1, '', 1, 1, 'default_view.php'),
(68, 179, 25, '', '', '', 0, 1, '', 0, 1, ''),
(69, 172, 23, NULL, NULL, NULL, NULL, 1, NULL, 1, 1, 'default_view.php'),
(70, 172, 23, '', '', '', 0, 1, '', 0, 1, ''),
(71, 172, 8, '1', '', '', 0, 0, '', 1, 1, ''),
(72, 172, 18, '', '', '', 0, 1, '', 0, 1, 'default_view.php'),
(73, 172, 12, '', '', '', 0, 0, '', 0, 1, 'default_view.php'),
(96, 166, 8, '12', '', '', 0, 0, '', 0, 1, ''),
(97, 172, 8, '10', '', '', 0, 0, '', 1, 1, ''),
(98, 163, 8, '5', '', '', 0, 0, '', 1, 1, ''),
(100, 179, 18, '', '', '', 0, 1, '', 0, 1, 'default_view.php'),
(101, 179, 12, '', '', '', 0, 0, '', 0, 1, 'default_view.php'),
(103, 176, 8, '1', '', '', 0, 0, '', 1, 1, ''),
(110, 179, 16, '1', '', '', 0, 1, '', 0, 1, 'default_view.php'),
(159, 182, 8, '1', '', '', 0, 0, '', 1, 1, ''),
(160, 182, 3, '', '', '', 0, 1, '', 0, 1, ''),
(161, 183, 8, '2', '', '', 0, 0, '', 1, 1, ''),
(164, 181, 13, '183', '1', '', 0, 1, '', 1, 1, 'default_view.php'),
(165, 181, 13, '181', '1', '', 0, 1, '', 1, 1, 'default_view.php');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo_tipo`
--

CREATE TABLE IF NOT EXISTS `modulo_tipo` (
  `paginaModuloTipoId` int(11) NOT NULL AUTO_INCREMENT,
  `moduloTipoNombre` varchar(45) DEFAULT NULL,
  `moduloTipoGrupo` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`paginaModuloTipoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `modulo_tipo`
--

INSERT INTO `modulo_tipo` (`paginaModuloTipoId`, `moduloTipoNombre`, `moduloTipoGrupo`) VALUES
(1, 'Publicaciones', 1),
(2, 'Categoría', 2),
(3, 'Html', 3),
(4, 'Twitter', 4),
(5, 'Facebook', 4),
(6, 'Hit Counter', 5),
(7, 'Producto', 2),
(8, 'Contenido', 0),
(9, 'Banner', 6),
(10, 'Productos Destacados', 2),
(11, 'Menú', 2),
(12, 'Título', 0),
(13, 'Preguntas Frecuentes', 7),
(14, 'Enlaces', 8),
(15, 'Galería', 9),
(16, 'Mapa', 10),
(17, 'Filtros', 2),
(18, 'Menú', 0),
(19, 'Producto al Azar', 2),
(20, 'Formulario', 11),
(21, 'Artículos', 12),
(22, 'Servicios', 13),
(23, 'Breadcrumbs', 0),
(24, 'Direcciones', 11),
(25, 'Publicidad', 14),
(26, 'Producto Destacado Azar', 2),
(27, 'Servicios Destacados', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `ord_det_id` int(11) NOT NULL AUTO_INCREMENT,
  `ord_det_order_number_fk` varchar(25) NOT NULL DEFAULT '',
  `ord_det_cart_row_id` varchar(32) NOT NULL DEFAULT '',
  `ord_det_item_fk` int(11) NOT NULL DEFAULT '0',
  `ord_det_item_name` varchar(255) NOT NULL DEFAULT '',
  `ord_det_item_option` varchar(255) NOT NULL DEFAULT '',
  `ord_det_quantity` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_non_discount_quantity` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_discount_quantity` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_stock_quantity` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_price` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_price_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_discount_price` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_discount_price_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_discount_description` varchar(255) NOT NULL DEFAULT '',
  `ord_det_tax_rate` double(8,4) NOT NULL DEFAULT '0.0000',
  `ord_det_tax` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_tax_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_shipping_rate` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_weight` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_weight_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_reward_points` int(10) NOT NULL DEFAULT '0',
  `ord_det_reward_points_total` int(10) NOT NULL DEFAULT '0',
  `ord_det_status_message` varchar(255) NOT NULL DEFAULT '',
  `ord_det_quantity_shipped` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_quantity_cancelled` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_det_shipped_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ord_det_id`),
  UNIQUE KEY `ord_det_id` (`ord_det_id`) USING BTREE,
  KEY `ord_det_order_number_fk` (`ord_det_order_number_fk`) USING BTREE,
  KEY `ord_det_item_fk` (`ord_det_item_fk`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `order_details`
--

INSERT INTO `order_details` (`ord_det_id`, `ord_det_order_number_fk`, `ord_det_cart_row_id`, `ord_det_item_fk`, `ord_det_item_name`, `ord_det_item_option`, `ord_det_quantity`, `ord_det_non_discount_quantity`, `ord_det_discount_quantity`, `ord_det_stock_quantity`, `ord_det_price`, `ord_det_price_total`, `ord_det_discount_price`, `ord_det_discount_price_total`, `ord_det_discount_description`, `ord_det_tax_rate`, `ord_det_tax`, `ord_det_tax_total`, `ord_det_shipping_rate`, `ord_det_weight`, `ord_det_weight_total`, `ord_det_reward_points`, `ord_det_reward_points_total`, `ord_det_status_message`, `ord_det_quantity_shipped`, `ord_det_quantity_cancelled`, `ord_det_shipped_date`) VALUES
(1, '00000001', 'e2ef524fbf3d9fe611d5a8e90fefdc9c', 97, 'tests', 'Colores: ', 1.00, 1.00, 0.00, 0.00, 120.00, 120.00, 120.00, 120.00, '', 20.0000, 20.00, 20.00, 0.00, 10.00, 10.00, 1200, 1200, '', 0.00, 0.00, '0000-00-00 00:00:00'),
(2, '00000001', 'ac627ab1ccbdb62ec96e702f07f6425b', 99, 'test1', 'Colores: ', 1.00, 1.00, 0.00, 0.00, 150.00, 150.00, 150.00, 150.00, '', 20.0000, 25.00, 25.00, 0.00, 5.00, 5.00, 1500, 1500, '', 0.00, 0.00, '0000-00-00 00:00:00'),
(3, '00000002', 'e2ef524fbf3d9fe611d5a8e90fefdc9c', 97, 'tests', 'Colores: ', 1.00, 1.00, 0.00, 0.00, 120.00, 120.00, 120.00, 120.00, '', 20.0000, 20.00, 20.00, 0.00, 10.00, 10.00, 1200, 1200, '', 0.00, 0.00, '0000-00-00 00:00:00'),
(4, '00000002', 'ac627ab1ccbdb62ec96e702f07f6425b', 99, 'test1', 'Colores: ', 1.00, 1.00, 0.00, 0.00, 150.00, 150.00, 150.00, 150.00, '', 20.0000, 25.00, 25.00, 0.00, 5.00, 5.00, 1500, 1500, '', 0.00, 0.00, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_status`
--

CREATE TABLE IF NOT EXISTS `order_status` (
  `ord_status_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `ord_status_description` varchar(50) NOT NULL DEFAULT '',
  `ord_status_cancelled` tinyint(1) NOT NULL DEFAULT '0',
  `ord_status_save_default` tinyint(1) NOT NULL DEFAULT '0',
  `ord_status_resave_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ord_status_id`),
  KEY `ord_status_id` (`ord_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `order_status`
--

INSERT INTO `order_status` (`ord_status_id`, `ord_status_description`, `ord_status_cancelled`, `ord_status_save_default`, `ord_status_resave_default`) VALUES
(1, 'Esperando Pago', 0, 0, 0),
(2, 'Nuevo', 0, 1, 0),
(3, 'Procesando', 0, 0, 0),
(4, 'Completo', 0, 0, 0),
(5, 'Cancelado', 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_summary`
--

CREATE TABLE IF NOT EXISTS `order_summary` (
  `ord_order_number` varchar(25) NOT NULL DEFAULT '',
  `ord_cart_data_fk` int(11) NOT NULL DEFAULT '0',
  `ord_user_fk` int(5) NOT NULL DEFAULT '0',
  `ord_item_summary_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_item_summary_savings_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_shipping` varchar(100) NOT NULL DEFAULT '',
  `ord_shipping_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_item_shipping_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_summary_discount_desc` varchar(255) NOT NULL DEFAULT '',
  `ord_summary_savings_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_savings_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_surcharge_desc` varchar(255) NOT NULL DEFAULT '',
  `ord_surcharge_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_reward_voucher_desc` varchar(255) NOT NULL DEFAULT '',
  `ord_reward_voucher_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_tax_rate` varchar(25) NOT NULL DEFAULT '',
  `ord_tax_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_sub_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_total` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_total_rows` int(10) NOT NULL DEFAULT '0',
  `ord_total_items` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_total_weight` double(10,2) NOT NULL DEFAULT '0.00',
  `ord_total_reward_points` int(10) NOT NULL DEFAULT '0',
  `ord_currency` varchar(25) NOT NULL DEFAULT '',
  `ord_exchange_rate` double(8,4) NOT NULL DEFAULT '0.0000',
  `ord_status` tinyint(1) NOT NULL DEFAULT '0',
  `ord_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ord_bill_name` varchar(255) DEFAULT NULL,
  `ord_bill_company` varchar(45) DEFAULT NULL,
  `ord_bill_address_01` varchar(255) DEFAULT NULL,
  `ord_bill_address_02` varchar(255) DEFAULT NULL,
  `ord_bill_city` varchar(45) DEFAULT NULL,
  `ord_bill_state` int(10) DEFAULT NULL,
  `ord_bill_post_code` varchar(45) DEFAULT NULL,
  `ord_bill_country` int(10) DEFAULT NULL,
  `ord_ship_name` varchar(255) DEFAULT NULL,
  `ord_ship_company` varchar(45) DEFAULT NULL,
  `ord_ship_address_01` varchar(255) DEFAULT NULL,
  `ord_ship_address_02` varchar(255) DEFAULT NULL,
  `ord_ship_city` varchar(45) DEFAULT NULL,
  `ord_ship_state` int(10) DEFAULT NULL,
  `ord_ship_post_code` varchar(45) DEFAULT NULL,
  `ord_ship_country` int(10) DEFAULT NULL,
  `ord_email` varchar(45) DEFAULT NULL,
  `ord_phone` varchar(45) DEFAULT NULL,
  `ord_comments` text,
  PRIMARY KEY (`ord_order_number`),
  UNIQUE KEY `ord_order_number` (`ord_order_number`) USING BTREE,
  KEY `ord_cart_data_fk` (`ord_cart_data_fk`) USING BTREE,
  KEY `ord_user_fk` (`ord_user_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `order_summary`
--

INSERT INTO `order_summary` (`ord_order_number`, `ord_cart_data_fk`, `ord_user_fk`, `ord_item_summary_total`, `ord_item_summary_savings_total`, `ord_shipping`, `ord_shipping_total`, `ord_item_shipping_total`, `ord_summary_discount_desc`, `ord_summary_savings_total`, `ord_savings_total`, `ord_surcharge_desc`, `ord_surcharge_total`, `ord_reward_voucher_desc`, `ord_reward_voucher_total`, `ord_tax_rate`, `ord_tax_total`, `ord_sub_total`, `ord_total`, `ord_total_rows`, `ord_total_items`, `ord_total_weight`, `ord_total_reward_points`, `ord_currency`, `ord_exchange_rate`, `ord_status`, `ord_date`, `ord_bill_name`, `ord_bill_company`, `ord_bill_address_01`, `ord_bill_address_02`, `ord_bill_city`, `ord_bill_state`, `ord_bill_post_code`, `ord_bill_country`, `ord_ship_name`, `ord_ship_company`, `ord_ship_address_01`, `ord_ship_address_02`, `ord_ship_city`, `ord_ship_state`, `ord_ship_post_code`, `ord_ship_country`, `ord_email`, `ord_phone`, `ord_comments`) VALUES
('00000001', 8, 1, 270.00, 0.00, '', 0.00, 270.00, '', 0.00, 0.00, '', 0.00, '', 0.00, '20', 45.00, 0.00, 270.00, 2, 2.00, 15.00, 2700, 'GBP', 1.0000, 2, '2014-05-12 13:04:55', 'Miguel', 'Dejabu', 'Shyris N43-69', 'Tomás de Berlanga', 'Quito', 3653224, 'EC171321', 3658394, 'Miguel', 'Dejabu', 'Shyris N43-69', 'Tomás de Berlanga', 'Quito', 3653224, 'EC171321', 3658394, 'miguel@dejabu.ec', '5932251526', ''),
('00000002', 9, 1, 270.00, 0.00, '', 0.00, 270.00, '', 0.00, 0.00, '', 0.00, '', 0.00, '20', 45.00, 0.00, 270.00, 2, 2.00, 15.00, 2700, 'GBP', 1.0000, 3, '2014-05-12 14:29:46', 'Miguel', 'Dejabu', 'Shyris N43-69', 'Tomás de Berlanga', 'Quito', 3653224, 'EC171321', 3658394, 'Miguel', 'Dejabu', 'Shyris N43-69', 'Tomás de Berlanga', 'Quito', 3653224, 'EC171321', 3658394, 'miguel@dejabu.ec', '5932251526', 'aaasas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paginas`
--

CREATE TABLE IF NOT EXISTS `paginas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tree` int(11) DEFAULT '1',
  `lft` int(11) DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `paginaClase` text CHARACTER SET latin1,
  `paginaEsPopup` int(1) DEFAULT '0',
  `paginaModuloColumnaId` int(11) DEFAULT NULL,
  `paginaVisiblePara` tinyint(2) NOT NULL DEFAULT '4',
  `paginaEnabled` tinyint(1) DEFAULT '1',
  `usuarioId` mediumint(8) DEFAULT NULL,
  `temporal` tinyint(1) DEFAULT '1',
  `estructura` mediumtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paginaEnabled` (`paginaEnabled`),
  KEY `lft` (`lft`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=184 ;

--
-- Volcado de datos para la tabla `paginas`
--

INSERT INTO `paginas` (`id`, `tree`, `lft`, `rgt`, `paginaClase`, `paginaEsPopup`, `paginaModuloColumnaId`, `paginaVisiblePara`, `paginaEnabled`, `usuarioId`, `temporal`, `estructura`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 34, NULL, 0, NULL, 4, 1, NULL, NULL, NULL, NULL, '2015-04-17 21:54:07', NULL),
(163, 1, 7, 8, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[36,98]}]}]', '2014-12-23 20:38:46', '2015-04-17 21:54:07', NULL),
(164, 1, 18, 19, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[20]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"4","medium":"4","small":"4"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[39]},{"class":"","span":{"large":"8","medium":"8","small":"8"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[18]}]}]', '2014-12-23 20:39:43', '2015-04-17 21:54:07', NULL),
(165, 1, 2, 5, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[16,17,48,49]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[]}]}]', '2014-12-23 20:40:16', '2015-04-17 21:54:07', NULL),
(166, 1, 9, 10, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[96]}]}]', '2015-01-05 18:52:31', '2015-04-17 21:54:07', NULL),
(171, 1, 6, 15, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[30]}]}]', '2015-01-05 20:29:02', '2015-04-17 21:54:07', NULL),
(172, 1, 16, 17, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":false,"columns":[{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[97]}]}]', '2015-01-06 17:29:50', '2015-04-17 21:54:07', NULL),
(173, 1, 11, 12, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[32]}]}]', '2015-01-06 17:40:31', '2015-04-17 21:54:07', NULL),
(175, 1, 13, 14, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[34]}]}]', '2015-01-07 18:03:37', '2015-04-17 21:54:07', NULL),
(176, 1, 3, 4, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[35,103]}]},{"class":"","expanded":false,"columns":[{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[]}]},{"class":"","expanded":false,"columns":[{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[]}]},{"class":"","expanded":false,"columns":[{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[]}]}]', '2015-01-07 19:22:02', '2015-04-17 21:54:07', NULL),
(177, 1, 20, 21, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[37]}]}]', '2015-02-08 20:03:52', '2015-04-17 21:54:07', NULL),
(178, 1, 22, 23, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[38]}]}]', '2015-02-24 17:52:58', '2015-04-17 21:54:07', NULL),
(179, 1, 24, 25, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":false,"columns":[{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[101,58,57,59,60,61,62,67,68,106]},{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[100,52,53,54,55,56,63,64,65,66]}]},{"class":"","expanded":false,"columns":[{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[110]},{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[]}]}]', '2015-03-12 20:31:54', '2015-04-17 21:54:07', NULL),
(180, 1, 26, 27, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[41]}]}]', '2015-04-08 23:02:39', '2015-04-17 21:54:07', NULL),
(181, 1, 28, 29, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"8","medium":"8","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[50]},{"class":"","span":{"large":"4","medium":"4","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[164,165]}]}]', '2015-04-10 15:07:23', '2015-04-17 21:54:07', NULL),
(182, 1, 30, 31, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[160]}]},{"class":"","expanded":false,"columns":[{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[159]},{"class":"","span":{"large":12,"medium":12,"small":12},"offset":{"large":0,"medium":0,"small":0},"push":{"large":0,"medium":0,"small":0},"pull":{"large":0,"medium":0,"small":0},"modules":[]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[]}]}]', '2015-04-13 20:29:39', '2015-04-17 21:54:07', NULL),
(183, 1, 32, 33, '', NULL, NULL, 4, 1, NULL, 0, '[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"modules":[161]}]}]', '2015-06-30 17:00:13', '2015-06-30 17:00:13', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagina_tipo`
--

CREATE TABLE IF NOT EXISTS `pagina_tipo` (
  `pagina_tipoId` int(11) NOT NULL AUTO_INCREMENT,
  `pagina_tipoNombre` varchar(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`pagina_tipoId`),
  KEY `fk_pagina_tipo_pagina1` (`pagina_tipoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `pagina_tipo`
--

INSERT INTO `pagina_tipo` (`pagina_tipoId`, `pagina_tipoNombre`) VALUES
(1, 'Artículo'),
(2, 'FAQ'),
(4, 'Catalogo'),
(5, 'Publicación'),
(6, 'Galería'),
(7, 'Redirect'),
(8, 'Mapa de Sitio'),
(9, 'Pasarela de Pedidos'),
(10, 'Enlaces'),
(11, 'Autenticación'),
(12, 'Servicios'),
(13, 'Calendario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE IF NOT EXISTS `pedidos` (
  `pedidoId` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioId` int(11) NOT NULL,
  `productoId` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`pedidoId`),
  KEY `productoId` (`productoId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE IF NOT EXISTS `productos` (
  `productoId` int(11) NOT NULL AUTO_INCREMENT,
  `productoPrioridad` int(11) DEFAULT '1' COMMENT '1-10 1mayor prioridad',
  `productoDeldia` enum('s','n') CHARACTER SET latin1 NOT NULL DEFAULT 'n' COMMENT '1 si es producto del dia 0 si no ',
  `categoriaId` int(11) NOT NULL DEFAULT '1',
  `productoEnable` enum('s','n') CHARACTER SET latin1 NOT NULL DEFAULT 's' COMMENT 'si,no para mostrar consultas',
  `productoImagenExtension` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `productoImagenCoord` varchar(150) DEFAULT NULL,
  `productoPosicion` int(11) DEFAULT '0',
  `usuarioId` mediumint(8) DEFAULT NULL,
  `productoTemporal` tinyint(1) DEFAULT NULL,
  `stock_quantity` smallint(5) DEFAULT '0',
  `stock_auto_allocate_status` tinyint(1) DEFAULT '1',
  `weight` double DEFAULT NULL,
  PRIMARY KEY (`productoId`),
  KEY `categoriaId_idx` (`categoriaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`productoId`, `productoPrioridad`, `productoDeldia`, `categoriaId`, `productoEnable`, `productoImagenExtension`, `productoImagenCoord`, `productoPosicion`, `usuarioId`, `productoTemporal`, `stock_quantity`, `stock_auto_allocate_status`, `weight`) VALUES
(127, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL),
(128, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL),
(129, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL),
(130, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL),
(131, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL),
(133, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL),
(136, 1, 'n', 24, 's', 'jpg?1428943291', '{"top":0,"left":0,"width":960,"height":600,"scale":0}', 1, NULL, 0, NULL, 0, 0),
(137, 1, 'n', 24, 's', '', '', 1, NULL, 0, NULL, 0, NULL),
(139, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL),
(140, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL),
(141, 1, 'n', 24, 's', '', '', 2, NULL, 0, NULL, 0, NULL),
(143, 1, 'n', 27, 's', '', '', 0, NULL, 0, NULL, 0, NULL),
(144, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL),
(146, 1, 'n', 1, 's', NULL, NULL, 0, NULL, 1, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_audios`
--

CREATE TABLE IF NOT EXISTS `producto_audios` (
  `productoAudioId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productoId` int(11) NOT NULL,
  `productoAudioNombre` varchar(255) DEFAULT NULL,
  `productoAudioExtension` varchar(10) DEFAULT NULL,
  `productoAudioEnabled` tinyint(1) DEFAULT NULL,
  `productoAudioPosicion` int(11) DEFAULT NULL,
  PRIMARY KEY (`productoAudioId`),
  KEY `productoId_pa_idx` (`productoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_campos`
--

CREATE TABLE IF NOT EXISTS `producto_campos` (
  `productoCampoId` int(11) NOT NULL AUTO_INCREMENT,
  `inputId` int(11) NOT NULL,
  `productoCampoVerModulo` tinyint(1) NOT NULL DEFAULT '1',
  `productoCampoVerListado` tinyint(1) NOT NULL DEFAULT '1',
  `productoCampoVerPedido` tinyint(1) NOT NULL DEFAULT '1',
  `productoCampoHabilitado` tinyint(1) NOT NULL DEFAULT '1',
  `productoCampoClase` varchar(45) DEFAULT NULL,
  `productoCampoPosicion` int(11) NOT NULL,
  `productoCampoMostrarNombre` tinyint(1) NOT NULL DEFAULT '1',
  `productoCampoVerFiltro` tinyint(1) NOT NULL DEFAULT '1',
  `usuarioId` mediumint(8) DEFAULT NULL,
  `productoCampoTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`productoCampoId`),
  KEY `inputId_pc` (`inputId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Volcado de datos para la tabla `producto_campos`
--

INSERT INTO `producto_campos` (`productoCampoId`, `inputId`, `productoCampoVerModulo`, `productoCampoVerListado`, `productoCampoVerPedido`, `productoCampoHabilitado`, `productoCampoClase`, `productoCampoPosicion`, `productoCampoMostrarNombre`, `productoCampoVerFiltro`, `usuarioId`, `productoCampoTemporal`) VALUES
(17, 12, 1, 1, 1, 0, '', 29, 1, 1, NULL, NULL),
(18, 19, 1, 1, 1, 1, '', 29, 1, 1, NULL, NULL),
(19, 30, 0, 0, 0, 1, '', 7, 1, 1, NULL, NULL),
(20, 30, 0, 0, 1, 1, '', 8, 1, 1, NULL, NULL),
(21, 18, 0, 0, 1, 1, '', 9, 1, 1, NULL, NULL),
(22, 14, 1, 1, 1, 1, '', 5, 1, 1, NULL, NULL),
(23, 18, 0, 0, 1, 1, '', 10, 1, 1, NULL, NULL),
(24, 22, 1, 1, 1, 1, '', 2, 1, 1, NULL, NULL),
(26, 34, 0, 0, 1, 1, '', 6, 1, 0, NULL, NULL),
(27, 20, 1, 1, 1, 1, '', 27, 1, 1, NULL, NULL),
(28, 20, 1, 1, 1, 1, '', 28, 1, 1, NULL, NULL),
(29, 20, 1, 1, 1, 1, '', 29, 1, 1, NULL, NULL),
(30, 20, 1, 1, 1, 1, '', 30, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_campos_listado_predefinido`
--

CREATE TABLE IF NOT EXISTS `producto_campos_listado_predefinido` (
  `productoCamposListadoPredefinidoId` int(11) NOT NULL AUTO_INCREMENT,
  `productoCampoId` int(11) DEFAULT NULL,
  `productoCamposListadoPredefinidoPublicado` tinyint(1) DEFAULT '1',
  `productoCamposListadoPredefinidoClase` varchar(45) DEFAULT NULL,
  `productoCamposListadoPredefinidoPosicion` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`productoCamposListadoPredefinidoId`),
  KEY `productoCampoId_pclp_idx` (`productoCampoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `producto_campos_listado_predefinido`
--

INSERT INTO `producto_campos_listado_predefinido` (`productoCamposListadoPredefinidoId`, `productoCampoId`, `productoCamposListadoPredefinidoPublicado`, `productoCamposListadoPredefinidoClase`, `productoCamposListadoPredefinidoPosicion`) VALUES
(2, 19, 1, '', 1),
(3, 19, 1, '', 2),
(4, 19, 1, '', 3),
(5, 20, 1, '', 4),
(6, 20, 1, '', 5),
(7, 20, 1, '', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_campos_listado_predefinido_rel`
--

CREATE TABLE IF NOT EXISTS `producto_campos_listado_predefinido_rel` (
  `productoCamposListadoPredefinidoRelId` int(11) NOT NULL AUTO_INCREMENT,
  `productoId` int(11) NOT NULL,
  `productoCampoId` int(11) NOT NULL,
  `productoCamposListadoPredefinidoId` int(11) NOT NULL,
  PRIMARY KEY (`productoCamposListadoPredefinidoRelId`),
  KEY `productoId_pclpr_idx` (`productoId`),
  KEY `productoCamposListadoPredefinidoId_pclpr_idx` (`productoCamposListadoPredefinidoId`),
  KEY `productoCampoId_pclpr_idx` (`productoCampoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_campos_rel`
--

CREATE TABLE IF NOT EXISTS `producto_campos_rel` (
  `productoCampoRelId` int(11) NOT NULL AUTO_INCREMENT,
  `productoId` int(11) NOT NULL,
  `productoCampoId` int(11) NOT NULL,
  PRIMARY KEY (`productoCampoRelId`),
  KEY `productoCampoId_rel` (`productoId`),
  KEY `productoId_pcr` (`productoId`),
  KEY `productoCampoId_pcr` (`productoCampoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=995 ;

--
-- Volcado de datos para la tabla `producto_campos_rel`
--

INSERT INTO `producto_campos_rel` (`productoCampoRelId`, `productoId`, `productoCampoId`) VALUES
(791, 127, 24),
(794, 127, 22),
(795, 127, 26),
(796, 127, 19),
(797, 127, 20),
(798, 127, 21),
(799, 127, 23),
(800, 127, 18),
(801, 128, 24),
(804, 128, 22),
(805, 128, 26),
(806, 128, 19),
(807, 128, 20),
(808, 128, 21),
(809, 128, 23),
(810, 128, 18),
(811, 129, 24),
(814, 129, 22),
(815, 129, 26),
(816, 129, 19),
(817, 129, 20),
(818, 129, 21),
(819, 129, 23),
(820, 129, 18),
(821, 130, 24),
(824, 130, 22),
(825, 130, 26),
(826, 130, 19),
(827, 130, 20),
(828, 130, 21),
(829, 130, 23),
(830, 130, 18),
(831, 131, 24),
(834, 131, 22),
(835, 131, 26),
(836, 131, 19),
(837, 131, 20),
(838, 131, 21),
(839, 131, 23),
(840, 131, 18),
(851, 133, 24),
(854, 133, 22),
(855, 133, 26),
(856, 133, 19),
(857, 133, 20),
(858, 133, 21),
(859, 133, 23),
(860, 133, 18),
(881, 136, 24),
(884, 136, 22),
(885, 136, 26),
(886, 136, 19),
(887, 136, 20),
(888, 136, 21),
(889, 136, 23),
(890, 136, 18),
(891, 137, 24),
(894, 137, 22),
(895, 137, 26),
(896, 137, 19),
(897, 137, 20),
(898, 137, 21),
(899, 137, 23),
(900, 137, 18),
(911, 139, 24),
(914, 139, 22),
(915, 139, 26),
(916, 139, 19),
(917, 139, 20),
(918, 139, 21),
(919, 139, 23),
(920, 139, 18),
(921, 140, 24),
(924, 140, 22),
(925, 140, 26),
(926, 140, 19),
(927, 140, 20),
(928, 140, 21),
(929, 140, 23),
(930, 140, 18),
(931, 141, 24),
(934, 141, 22),
(935, 141, 26),
(936, 141, 19),
(937, 141, 20),
(938, 141, 21),
(939, 141, 23),
(940, 141, 18),
(951, 143, 24),
(954, 143, 22),
(955, 143, 26),
(956, 143, 19),
(957, 143, 20),
(958, 143, 21),
(959, 143, 23),
(960, 143, 18),
(961, 144, 24),
(964, 144, 22),
(965, 144, 26),
(966, 144, 19),
(967, 144, 20),
(968, 144, 21),
(969, 144, 23),
(970, 144, 18),
(981, 146, 24),
(984, 146, 22),
(985, 146, 26),
(986, 146, 19),
(987, 146, 20),
(988, 146, 21),
(989, 146, 23),
(990, 146, 18),
(991, 143, 30),
(992, 136, 30),
(993, 137, 30),
(994, 141, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_categorias`
--

CREATE TABLE IF NOT EXISTS `producto_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `categoriaImagen` varchar(255) DEFAULT NULL,
  `temporal` tinyint(1) DEFAULT '1',
  `categoriaImagenCoord` varchar(150) DEFAULT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Volcado de datos para la tabla `producto_categorias`
--

INSERT INTO `producto_categorias` (`id`, `tree`, `lft`, `rgt`, `categoriaImagen`, `temporal`, `categoriaImagenCoord`, `usuarioId`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 14, '', NULL, NULL, NULL, NULL, '2015-01-07 21:36:06', NULL),
(22, 1, 4, 13, '', 0, '', NULL, '2014-12-18 00:19:54', '2015-01-07 21:36:06', NULL),
(24, 1, 2, 3, '', 0, '', NULL, '2014-12-18 00:22:22', '2015-01-07 21:36:06', NULL),
(25, 1, 7, 8, '', 0, '', NULL, '2014-12-18 17:10:16', '2015-01-07 21:36:06', NULL),
(26, 1, 5, 12, '', 0, '', NULL, '2014-12-18 23:02:53', '2015-01-07 21:36:06', NULL),
(27, 1, 6, 11, '', 0, '', NULL, '2014-12-18 23:03:02', '2015-01-07 21:36:06', NULL),
(28, 1, 9, 10, '', 0, '', NULL, '2014-12-18 23:03:10', '2015-01-07 21:36:06', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_configuracion`
--

CREATE TABLE IF NOT EXISTS `producto_configuracion` (
  `productoConfiguracionId` int(11) NOT NULL AUTO_INCREMENT,
  `productoMostarProductoInicio` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`productoConfiguracionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `producto_configuracion`
--

INSERT INTO `producto_configuracion` (`productoConfiguracionId`, `productoMostarProductoInicio`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_descargas`
--

CREATE TABLE IF NOT EXISTS `producto_descargas` (
  `productoDescargaId` int(11) NOT NULL AUTO_INCREMENT,
  `productoId` int(11) DEFAULT NULL,
  `productoDescargaNombre` varchar(45) DEFAULT NULL,
  `productoDescargaEnabled` tinyint(1) DEFAULT '1',
  `productoDescargaPosicion` int(11) DEFAULT NULL,
  `productoDescargaArchivo` varchar(45) DEFAULT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `productoDescargaTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`productoDescargaId`),
  KEY `productoId_descargas` (`productoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_imagenes`
--

CREATE TABLE IF NOT EXISTS `producto_imagenes` (
  `productoImagenId` int(11) NOT NULL AUTO_INCREMENT,
  `productoImagen` varchar(255) DEFAULT NULL,
  `productoImagenCoord` varchar(150) DEFAULT NULL,
  `productoId` int(11) NOT NULL,
  `productoImagenNombre` varchar(255) DEFAULT NULL,
  `productoImagenPosicion` int(11) DEFAULT '1',
  `productoImagenEnabled` tinyint(1) DEFAULT '1',
  `usuarioId` mediumint(8) DEFAULT NULL,
  `productoImagenTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`productoImagenId`),
  KEY `productoId` (`productoId`),
  KEY `productoId_pi` (`productoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_videos`
--

CREATE TABLE IF NOT EXISTS `producto_videos` (
  `productoVideoId` int(11) NOT NULL AUTO_INCREMENT,
  `productoVideo` varchar(255) DEFAULT NULL,
  `productoId` int(11) NOT NULL,
  `productoVideoNombre` varchar(255) DEFAULT NULL,
  `productoVideoPosicion` int(11) DEFAULT '1',
  `productoVideoEnabled` tinyint(1) DEFAULT '1',
  `usuarioId` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`productoVideoId`),
  KEY `productoId` (`productoId`),
  KEY `productoId_pv` (`productoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE IF NOT EXISTS `publicaciones` (
  `publicacionId` int(11) NOT NULL AUTO_INCREMENT,
  `publicacionFecha` datetime DEFAULT NULL,
  `publicacionImagen` varchar(255) DEFAULT NULL,
  `publicacionImagenCoord` varchar(150) DEFAULT NULL,
  `publicacionClase` varchar(255) DEFAULT NULL,
  `publicacionHabilitado` int(1) DEFAULT '1',
  `paginaId` int(11) DEFAULT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `publicacionTemporal` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`publicacionId`),
  KEY `paginaId` (`paginaId`),
  KEY `publicacionHabilitado` (`publicacionHabilitado`),
  KEY `paginaId_p` (`paginaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`publicacionId`, `publicacionFecha`, `publicacionImagen`, `publicacionImagenCoord`, `publicacionClase`, `publicacionHabilitado`, `paginaId`, `usuarioId`, `publicacionTemporal`) VALUES
(4, NULL, NULL, NULL, NULL, 1, 1, NULL, 1),
(5, '2014-12-23 17:24:55', '', '', '', 1, 165, NULL, 0),
(6, '2015-01-05 13:41:52', '', '', '', 1, 163, NULL, 0),
(7, NULL, NULL, NULL, NULL, 1, 1, NULL, 1),
(8, '2015-01-06 11:10:15', '', '', '', 1, 163, NULL, 0),
(9, NULL, NULL, NULL, NULL, 1, 1, NULL, 1),
(10, NULL, NULL, NULL, NULL, 1, 1, NULL, 1),
(11, NULL, NULL, NULL, NULL, 1, 1, NULL, 1),
(12, '2015-01-07 14:23:36', '', '', '', 1, 176, NULL, 0),
(13, NULL, NULL, NULL, NULL, 1, 1, NULL, 1),
(14, '2015-04-10 12:02:19', '', '', '', 1, 176, NULL, 0),
(15, NULL, NULL, NULL, NULL, 1, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones_imagenes`
--

CREATE TABLE IF NOT EXISTS `publicaciones_imagenes` (
  `publicacionImagenId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publicacionId` int(11) DEFAULT NULL,
  `publicacionImagenExtension` varchar(45) DEFAULT NULL,
  `publicacionImagenNombre` varchar(255) DEFAULT NULL,
  `publicacionImagenCoord` varchar(150) DEFAULT NULL,
  `publicacionImagenPosicion` int(4) DEFAULT NULL,
  PRIMARY KEY (`publicacionImagenId`),
  UNIQUE KEY `publicacionImagenId_UNIQUE` (`publicacionImagenId`),
  KEY `publicacionId_fk_pi_idx` (`publicacionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `publicaciones_imagenes`
--

INSERT INTO `publicaciones_imagenes` (`publicacionImagenId`, `publicacionId`, `publicacionImagenExtension`, `publicacionImagenNombre`, `publicacionImagenCoord`, `publicacionImagenPosicion`) VALUES
(1, 14, 'jpg', 'caution this is spartaas', '%7B%22top%22%3A0%2C%22left%22%3A0%2C%22width%22%3A320%2C%22height%22%3A200%2C%22scale%22%3A0%7D', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicidad`
--

CREATE TABLE IF NOT EXISTS `publicidad` (
  `publicidadId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publicidadTipoId` int(10) unsigned DEFAULT NULL,
  `moduloId` int(11) DEFAULT NULL,
  `paginaId` int(11) DEFAULT NULL,
  `publicidadNombre` varchar(45) DEFAULT NULL,
  `publicidadFechaInicio` datetime DEFAULT NULL,
  `publicidadFechaFin` datetime DEFAULT NULL,
  `publicidadEnabled` tinyint(1) DEFAULT NULL,
  `publicidadClase` varchar(45) DEFAULT NULL,
  `publicidadArchivo1` varchar(45) DEFAULT NULL,
  `publicidadArchivo2` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`publicidadId`),
  KEY `publicidadTipoId_fk_idx` (`publicidadTipoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `publicidad`
--

INSERT INTO `publicidad` (`publicidadId`, `publicidadTipoId`, `moduloId`, `paginaId`, `publicidadNombre`, `publicidadFechaInicio`, `publicidadFechaFin`, `publicidadEnabled`, `publicidadClase`, `publicidadArchivo1`, `publicidadArchivo2`) VALUES
(1, 1, 329, NULL, 'normal', '2014-03-18 11:15:14', '2014-04-18 11:15:14', 1, '', '1395260108_flyingsnail1.swf', NULL),
(2, 2, 330, 0, 'expandible', '2014-03-18 12:01:40', '2014-04-18 12:01:40', 1, '', '1395162111_flexcms.png', '1395251031_caution-this-is-sparta.jpg'),
(4, 3, 0, 78, 'popup', '2014-03-19 11:00:37', '2014-04-19 11:00:37', 1, '', '1395246519_caution-this-is-sparta.jpg', '0'),
(5, 2, 46, NULL, 'asd', '2015-04-09 12:06:38', '2015-05-09 12:06:38', 1, '', '1428599211_caution-this-is-sparta.jpg', ''),
(6, 2, 45, NULL, 'asdffff', '2015-04-09 12:10:50', '2015-05-16 12:10:50', 1, '', '1428599455_placeholder_2.jpg', ''),
(7, 1, 45, NULL, 'daa', '2015-04-09 12:26:02', '2015-05-09 12:26:02', 1, '', '', NULL),
(8, 1, 45, NULL, 'daaaaaa', '2015-04-09 12:26:59', '2015-05-09 12:26:59', 1, '', '', NULL),
(9, 2, 45, NULL, 'sssssss', '2015-04-10 15:05:28', '2015-05-10 15:05:28', 1, '', '', ''),
(10, 2, 45, NULL, 'ssssssssssssssssss', '2015-04-10 15:06:17', '2015-05-10 15:06:17', 1, '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicidad_tipo`
--

CREATE TABLE IF NOT EXISTS `publicidad_tipo` (
  `publicidadTipoId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `publicidadTipo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`publicidadTipoId`),
  UNIQUE KEY `publicidadTipoId_UNIQUE` (`publicidadTipoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `publicidad_tipo`
--

INSERT INTO `publicidad_tipo` (`publicidadTipoId`, `publicidadTipo`) VALUES
(1, 'Normal'),
(2, 'Expandible'),
(3, 'Popup');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reward_points_converted`
--

CREATE TABLE IF NOT EXISTS `reward_points_converted` (
  `rew_convert_id` int(10) NOT NULL AUTO_INCREMENT,
  `rew_convert_ord_detail_fk` int(10) NOT NULL DEFAULT '10',
  `rew_convert_discount_fk` varchar(50) NOT NULL DEFAULT '',
  `rew_convert_points` int(10) NOT NULL DEFAULT '10',
  `rew_convert_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`rew_convert_id`),
  UNIQUE KEY `rew_convert_id` (`rew_convert_id`) USING BTREE,
  KEY `rew_convert_discount_fk` (`rew_convert_discount_fk`),
  KEY `rew_convert_ord_detail_fk` (`rew_convert_ord_detail_fk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE IF NOT EXISTS `servicios` (
  `servicioId` int(11) NOT NULL AUTO_INCREMENT,
  `paginaId` int(11) DEFAULT NULL,
  `servicioPosicion` int(11) DEFAULT NULL,
  `servicioClase` varchar(45) DEFAULT NULL,
  `servicioImagen` varchar(45) DEFAULT NULL,
  `servicioImagenCoord` varchar(150) DEFAULT NULL,
  `servicioPublicado` tinyint(1) DEFAULT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `servicioTemporal` tinyint(1) DEFAULT NULL,
  `servicioDestacado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`servicioId`),
  KEY `fk_paginaId_s` (`paginaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`servicioId`, `paginaId`, `servicioPosicion`, `servicioClase`, `servicioImagen`, `servicioImagenCoord`, `servicioPublicado`, `usuarioId`, `servicioTemporal`, `servicioDestacado`) VALUES
(1, 166, 1, '', 'jpg', '{"top":0,"left":0,"width":800,"height":500,"scale":0}', 1, NULL, 0, 1),
(2, NULL, 2, NULL, NULL, '', 1, NULL, 1, 0),
(3, NULL, 3, NULL, NULL, '', 1, NULL, 1, 0),
(4, NULL, 4, NULL, NULL, '', 1, NULL, 1, 0),
(10, 180, 5, '', '', '', 1, NULL, 0, 1),
(11, 180, 6, '', '', '', 1, NULL, 0, 0),
(12, 180, 7, '', '', '', 1, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_imagenes`
--

CREATE TABLE IF NOT EXISTS `servicios_imagenes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `servicio_id` int(11) NOT NULL,
  `extension` varchar(255) CHARACTER SET utf8 NOT NULL,
  `coords` varchar(255) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `posicion` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_servicios_imagenes_s` (`servicio_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `servicios_imagenes`
--

INSERT INTO `servicios_imagenes` (`id`, `servicio_id`, `extension`, `coords`, `posicion`, `nombre`) VALUES
(11, 10, 'jpg?1428602900', '{"top":0,"left":0,"width":800,"height":600,"scale":0}', 2, 'placeholder 2s'),
(13, 10, 'jpg?1428603105', '{"top":0,"left":0,"width":960,"height":600,"scale":0}', 1, 'caution this is sparta'),
(14, 10, 'jpg?1428603498', '{"top":0,"left":0,"width":960,"height":600,"scale":0}', 3, 'caution this is sparta'),
(15, 10, 'jpg?1428603498', '{"top":0,"left":0,"width":800,"height":600,"scale":0}', 4, 'placeholder 2'),
(16, 10, 'png?1428603498', '{"top":0,"left":0,"width":1066.6666666667,"height":600,"scale":0}', 5, 'rammstein'),
(17, 1, 'jpg?1428603983', '{"top":0,"left":0,"width":960,"height":600,"scale":0}', 1, 'caution this is sparta'),
(18, 1, 'jpg?1428603983', '{"top":0,"left":0,"width":800,"height":600,"scale":0}', 2, 'placeholder 2'),
(19, 1, 'png?1428603983', '{"top":0,"left":0,"width":1066.6666666667,"height":600,"scale":0}', 3, 'rammstein');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipping_item_rules`
--

CREATE TABLE IF NOT EXISTS `shipping_item_rules` (
  `ship_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `ship_item_item_fk` int(11) NOT NULL DEFAULT '0',
  `ship_item_location_fk` smallint(5) NOT NULL DEFAULT '0',
  `ship_item_zone_fk` smallint(5) NOT NULL DEFAULT '0',
  `ship_item_value` double(8,4) DEFAULT NULL,
  `ship_item_separate` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indicate if item should have a shipping rate calculated specifically for it.',
  `ship_item_banned` tinyint(1) NOT NULL DEFAULT '0',
  `ship_item_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ship_item_id`),
  UNIQUE KEY `ship_item_id` (`ship_item_id`) USING BTREE,
  KEY `ship_item_zone_fk` (`ship_item_zone_fk`) USING BTREE,
  KEY `ship_item_location_fk` (`ship_item_location_fk`) USING BTREE,
  KEY `ship_item_item_fk` (`ship_item_item_fk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipping_options`
--

CREATE TABLE IF NOT EXISTS `shipping_options` (
  `ship_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `ship_name` varchar(50) NOT NULL DEFAULT '',
  `ship_description` varchar(50) NOT NULL DEFAULT '',
  `ship_location_fk` smallint(5) NOT NULL DEFAULT '0',
  `ship_zone_fk` smallint(5) NOT NULL DEFAULT '0',
  `ship_inc_sub_locations` tinyint(1) NOT NULL DEFAULT '0',
  `ship_tax_rate` double(7,4) DEFAULT NULL,
  `ship_discount_inclusion` tinyint(1) NOT NULL DEFAULT '0',
  `ship_status` tinyint(1) NOT NULL DEFAULT '0',
  `ship_default` tinyint(1) NOT NULL DEFAULT '0',
  `ship_temporal` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ship_id`),
  UNIQUE KEY `ship_id` (`ship_id`) USING BTREE,
  KEY `ship_zone_fk` (`ship_zone_fk`) USING BTREE,
  KEY `ship_location_fk` (`ship_location_fk`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `shipping_options`
--

INSERT INTO `shipping_options` (`ship_id`, `ship_name`, `ship_description`, `ship_location_fk`, `ship_zone_fk`, `ship_inc_sub_locations`, `ship_tax_rate`, `ship_discount_inclusion`, `ship_status`, `ship_default`, `ship_temporal`) VALUES
(1, '7 dias', '0', 4, 0, 0, NULL, 0, 1, 0, 0),
(2, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(3, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(4, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(5, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(6, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(7, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(8, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(9, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(10, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(11, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(12, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(13, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(14, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(15, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(16, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(17, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(18, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(19, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(20, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(21, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(22, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(23, '2 dias', '', 4, 0, 1, NULL, 0, 1, 1, 0),
(24, '', '', 0, 0, 0, NULL, 0, 0, 0, 1),
(25, '3 dias', '', 0, 0, 0, NULL, 0, 1, 0, 0),
(27, '', '', 0, 0, 0, NULL, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipping_rates`
--

CREATE TABLE IF NOT EXISTS `shipping_rates` (
  `ship_rate_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `ship_rate_ship_fk` smallint(5) NOT NULL DEFAULT '0',
  `ship_rate_value` double(8,2) NOT NULL DEFAULT '0.00',
  `ship_rate_tare_wgt` double(8,2) NOT NULL DEFAULT '0.00',
  `ship_rate_min_wgt` double(8,2) NOT NULL DEFAULT '0.00',
  `ship_rate_max_wgt` double(8,2) NOT NULL DEFAULT '9999.00',
  `ship_rate_min_value` double(10,2) NOT NULL DEFAULT '0.00',
  `ship_rate_max_value` double(10,2) NOT NULL DEFAULT '9999.00',
  `ship_rate_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ship_rate_id`),
  UNIQUE KEY `ship_rate_id` (`ship_rate_id`) USING BTREE,
  KEY `ship_rate_ship_fk` (`ship_rate_ship_fk`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `shipping_rates`
--

INSERT INTO `shipping_rates` (`ship_rate_id`, `ship_rate_ship_fk`, `ship_rate_value`, `ship_rate_tare_wgt`, `ship_rate_min_wgt`, `ship_rate_max_wgt`, `ship_rate_min_value`, `ship_rate_max_value`, `ship_rate_status`) VALUES
(1, 1, 1.50, 0.00, 0.00, 9999.00, 0.00, 9999.00, 1),
(2, 1, 2.00, 0.00, 0.00, 9999.00, 0.00, 9999.00, 1),
(3, 1, 3.00, 0.00, 0.00, 9999.00, 0.00, 9999.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tax`
--

CREATE TABLE IF NOT EXISTS `tax` (
  `tax_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `tax_location_fk` smallint(5) NOT NULL DEFAULT '0',
  `tax_zone_fk` smallint(5) NOT NULL DEFAULT '0',
  `tax_name` varchar(25) NOT NULL DEFAULT '',
  `tax_rate` double(7,4) NOT NULL DEFAULT '0.0000',
  `tax_status` tinyint(1) NOT NULL DEFAULT '0',
  `tax_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tax_id`),
  UNIQUE KEY `tax_id` (`tax_id`),
  KEY `tax_zone_fk` (`tax_zone_fk`),
  KEY `tax_location_fk` (`tax_location_fk`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `tax`
--

INSERT INTO `tax` (`tax_id`, `tax_location_fk`, `tax_zone_fk`, `tax_name`, `tax_rate`, `tax_status`, `tax_default`) VALUES
(3, 4, 0, 'IVA', 12.0000, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tax_item_rates`
--

CREATE TABLE IF NOT EXISTS `tax_item_rates` (
  `tax_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_item_item_fk` int(11) NOT NULL DEFAULT '0',
  `tax_item_location_fk` smallint(5) NOT NULL DEFAULT '0',
  `tax_item_zone_fk` smallint(5) NOT NULL DEFAULT '0',
  `tax_item_rate` double(7,4) NOT NULL DEFAULT '0.0000',
  `tax_item_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tax_item_id`),
  UNIQUE KEY `tax_item_id` (`tax_item_id`) USING BTREE,
  KEY `tax_item_zone_fk` (`tax_item_zone_fk`),
  KEY `tax_item_location_fk` (`tax_item_location_fk`),
  KEY `tax_item_item_fk` (`tax_item_item_fk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `password` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `usuarioImagen` varchar(255) DEFAULT NULL,
  `usuarioImagenCoord` varchar(150) DEFAULT NULL,
  `facebook_id` bigint(20) DEFAULT NULL,
  `access_token` text,
  `token_expires` timestamp NULL DEFAULT NULL,
  `fb_login` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_u` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `ip_address`, `last_name`, `first_name`, `password`, `email`, `username`, `salt`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `usuarioId`, `usuarioImagen`, `usuarioImagenCoord`, `facebook_id`, `access_token`, `token_expires`, `fb_login`) VALUES
(1, '\0\0', 'Suárez', 'Miguel', '$2y$08$x8fVggT5TDCq828X9SfWte/Xn19bl.zEt/QettSFU97EbOL.PaDcy', 'miguel@dejabu.ec', NULL, '9462e8eee0', '', NULL, NULL, 'OBSWFTtQUkj84NMOkG3SQu', 1268889823, 1435680647, 1, NULL, 'jpg', '{"top":0,"left":41,"width":192,"height":120,"scale":0}', NULL, NULL, '2015-04-21 15:22:11', NULL),
(8, '::1', '2', 'Miguel', '$2y$08$8LZsECY9ScPSFVWLMiqYreJT8WAN/7Z1gSvBUZENxU5vmUqyn8/Nq', 'miguel4@dejabu.ec', NULL, NULL, NULL, NULL, NULL, NULL, 1413320573, 1413320573, 1, NULL, '', '', NULL, NULL, NULL, NULL),
(9, '::1', 'd', 'a', '$2y$08$JThxXgYNm07L5utMOipT2OCkt0eCyF88P62nq5Hkqj.kgKwhEI4cW', 'miguel2@dejabu.ec', NULL, NULL, NULL, NULL, NULL, NULL, 1413320981, 1413320981, 1, NULL, '', '', NULL, NULL, NULL, NULL),
(10, '::1', 'b', 'a', '$2y$08$8PKUuIZsyCfrhwsYP6jLteabN/sPEW54WF8OdxQAcixA6mvtZt226', 'miguel1@dejabu.ec', NULL, NULL, NULL, NULL, NULL, NULL, 1413321050, 1413321050, 1, NULL, '', '', NULL, NULL, NULL, NULL),
(11, '::1', 'dfff', 'asdd', '$2y$08$Ab7Xs2kIO4Nz/hAHHCQhzuE4KdQaAsdWJANlt9FgAIozFuEknuEWm', 'miguel6@dejabu.ec', NULL, NULL, NULL, NULL, NULL, NULL, 1413322237, 1413322237, 1, NULL, '', '', NULL, NULL, NULL, NULL),
(24, '::1', 'Miguel', 'Miguel Suárez', '$2y$08$g8hLhYcmPbg.STTFJQ6SROnwlobubyHwo9RlEkGXfQMNDRJhNiJma', 'miguelsuarez70@gmail.com', 'miguelsuarez70@gmail.com', NULL, NULL, NULL, NULL, NULL, 1429638384, 1429638384, 1, NULL, NULL, NULL, 10152711289310776, 'CAAEOpZCw1FKABAHHGIsQA5vhCZBW5AFTTaeYJU3lBiRIH0P9cztopufAb47XUNYVcHkiZCWJ0DQsgZB2cfw4Tg2xkT6bmAlMV7CSJjRbu0VmkCypx9DPed0D9CW3vipxhqWjU1n1HxtSjJlQ8ZCAUFUAKnIZC5TRcPFDib9ItUZAWKITyBPGOGB', '2015-06-20 17:46:23', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Volcado de datos para la tabla `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 3),
(11, 8, 1),
(12, 8, 2),
(13, 9, 2),
(14, 9, 2),
(15, 10, 1),
(16, 10, 2),
(17, 11, 2),
(18, 11, 2),
(19, 12, 2),
(20, 13, 2),
(21, 14, 2),
(22, 15, 2),
(23, 16, 2),
(24, 17, 2),
(25, 18, 2),
(26, 19, 2),
(27, 20, 2),
(28, 21, 2),
(29, 22, 2),
(30, 23, 2),
(31, 24, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_countries`
--

CREATE TABLE IF NOT EXISTS `user_countries` (
  `country_id` int(5) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) DEFAULT NULL,
  `short_name` varchar(80) NOT NULL DEFAULT '',
  `long_name` varchar(80) NOT NULL DEFAULT '',
  `iso3` char(3) DEFAULT NULL,
  `numcode` varchar(6) DEFAULT NULL,
  `un_member` varchar(12) DEFAULT NULL,
  `calling_code` varchar(8) DEFAULT NULL,
  `cctld` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=251 ;

--
-- Volcado de datos para la tabla `user_countries`
--

INSERT INTO `user_countries` (`country_id`, `iso2`, `short_name`, `long_name`, `iso3`, `numcode`, `un_member`, `calling_code`, `cctld`) VALUES
(1, 'AF', 'Afghanistan', 'Islamic Republic of Afghanistan', 'AFG', '004', 'yes', '93', '.af'),
(2, 'AX', 'Aland Islands', '&Aring;land Islands', 'ALA', '248', 'no', '358', '.ax'),
(3, 'AL', 'Albania', 'Republic of Albania', 'ALB', '008', 'yes', '355', '.al'),
(4, 'DZ', 'Algeria', 'People''s Democratic Republic of Algeria', 'DZA', '012', 'yes', '213', '.dz'),
(5, 'AS', 'American Samoa', 'American Samoa', 'ASM', '016', 'no', '1+684', '.as'),
(6, 'AD', 'Andorra', 'Principality of Andorra', 'AND', '020', 'yes', '376', '.ad'),
(7, 'AO', 'Angola', 'Republic of Angola', 'AGO', '024', 'yes', '244', '.ao'),
(8, 'AI', 'Anguilla', 'Anguilla', 'AIA', '660', 'no', '1+264', '.ai'),
(9, 'AQ', 'Antarctica', 'Antarctica', 'ATA', '010', 'no', '672', '.aq'),
(10, 'AG', 'Antigua and Barbuda', 'Antigua and Barbuda', 'ATG', '028', 'yes', '1+268', '.ag'),
(11, 'AR', 'Argentina', 'Argentine Republic', 'ARG', '032', 'yes', '54', '.ar'),
(12, 'AM', 'Armenia', 'Republic of Armenia', 'ARM', '051', 'yes', '374', '.am'),
(13, 'AW', 'Aruba', 'Aruba', 'ABW', '533', 'no', '297', '.aw'),
(14, 'AU', 'Australia', 'Commonwealth of Australia', 'AUS', '036', 'yes', '61', '.au'),
(15, 'AT', 'Austria', 'Republic of Austria', 'AUT', '040', 'yes', '43', '.at'),
(16, 'AZ', 'Azerbaijan', 'Republic of Azerbaijan', 'AZE', '031', 'yes', '994', '.az'),
(17, 'BS', 'Bahamas', 'Commonwealth of The Bahamas', 'BHS', '044', 'yes', '1+242', '.bs'),
(18, 'BH', 'Bahrain', 'Kingdom of Bahrain', 'BHR', '048', 'yes', '973', '.bh'),
(19, 'BD', 'Bangladesh', 'People''s Republic of Bangladesh', 'BGD', '050', 'yes', '880', '.bd'),
(20, 'BB', 'Barbados', 'Barbados', 'BRB', '052', 'yes', '1+246', '.bb'),
(21, 'BY', 'Belarus', 'Republic of Belarus', 'BLR', '112', 'yes', '375', '.by'),
(22, 'BE', 'Belgium', 'Kingdom of Belgium', 'BEL', '056', 'yes', '32', '.be'),
(23, 'BZ', 'Belize', 'Belize', 'BLZ', '084', 'yes', '501', '.bz'),
(24, 'BJ', 'Benin', 'Republic of Benin', 'BEN', '204', 'yes', '229', '.bj'),
(25, 'BM', 'Bermuda', 'Bermuda Islands', 'BMU', '060', 'no', '1+441', '.bm'),
(26, 'BT', 'Bhutan', 'Kingdom of Bhutan', 'BTN', '064', 'yes', '975', '.bt'),
(27, 'BO', 'Bolivia', 'Plurinational State of Bolivia', 'BOL', '068', 'yes', '591', '.bo'),
(28, 'BQ', 'Bonaire, Sint Eustatius and Saba', 'Bonaire, Sint Eustatius and Saba', 'BES', '535', 'no', '599', '.bq'),
(29, 'BA', 'Bosnia and Herzegovina', 'Bosnia and Herzegovina', 'BIH', '070', 'yes', '387', '.ba'),
(30, 'BW', 'Botswana', 'Republic of Botswana', 'BWA', '072', 'yes', '267', '.bw'),
(31, 'BV', 'Bouvet Island', 'Bouvet Island', 'BVT', '074', 'no', 'NONE', '.bv'),
(32, 'BR', 'Brazil', 'Federative Republic of Brazil', 'BRA', '076', 'yes', '55', '.br'),
(33, 'IO', 'British Indian Ocean Territory', 'British Indian Ocean Territory', 'IOT', '086', 'no', '246', '.io'),
(34, 'BN', 'Brunei', 'Brunei Darussalam', 'BRN', '096', 'yes', '673', '.bn'),
(35, 'BG', 'Bulgaria', 'Republic of Bulgaria', 'BGR', '100', 'yes', '359', '.bg'),
(36, 'BF', 'Burkina Faso', 'Burkina Faso', 'BFA', '854', 'yes', '226', '.bf'),
(37, 'BI', 'Burundi', 'Republic of Burundi', 'BDI', '108', 'yes', '257', '.bi'),
(38, 'KH', 'Cambodia', 'Kingdom of Cambodia', 'KHM', '116', 'yes', '855', '.kh'),
(39, 'CM', 'Cameroon', 'Republic of Cameroon', 'CMR', '120', 'yes', '237', '.cm'),
(40, 'CA', 'Canada', 'Canada', 'CAN', '124', 'yes', '1', '.ca'),
(41, 'CV', 'Cape Verde', 'Republic of Cape Verde', 'CPV', '132', 'yes', '238', '.cv'),
(42, 'KY', 'Cayman Islands', 'The Cayman Islands', 'CYM', '136', 'no', '1+345', '.ky'),
(43, 'CF', 'Central African Republic', 'Central African Republic', 'CAF', '140', 'yes', '236', '.cf'),
(44, 'TD', 'Chad', 'Republic of Chad', 'TCD', '148', 'yes', '235', '.td'),
(45, 'CL', 'Chile', 'Republic of Chile', 'CHL', '152', 'yes', '56', '.cl'),
(46, 'CN', 'China', 'People''s Republic of China', 'CHN', '156', 'yes', '86', '.cn'),
(47, 'CX', 'Christmas Island', 'Christmas Island', 'CXR', '162', 'no', '61', '.cx'),
(48, 'CC', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', 'CCK', '166', 'no', '61', '.cc'),
(49, 'CO', 'Colombia', 'Republic of Colombia', 'COL', '170', 'yes', '57', '.co'),
(50, 'KM', 'Comoros', 'Union of the Comoros', 'COM', '174', 'yes', '269', '.km'),
(51, 'CG', 'Congo', 'Republic of the Congo', 'COG', '178', 'yes', '242', '.cg'),
(52, 'CK', 'Cook Islands', 'Cook Islands', 'COK', '184', 'some', '682', '.ck'),
(53, 'CR', 'Costa Rica', 'Republic of Costa Rica', 'CRI', '188', 'yes', '506', '.cr'),
(54, 'CI', 'Cote d''ivoire (Ivory Coast)', 'Republic of C&ocirc;te D''Ivoire (Ivory Coast)', 'CIV', '384', 'yes', '225', '.ci'),
(55, 'HR', 'Croatia', 'Republic of Croatia', 'HRV', '191', 'yes', '385', '.hr'),
(56, 'CU', 'Cuba', 'Republic of Cuba', 'CUB', '192', 'yes', '53', '.cu'),
(57, 'CW', 'Curacao', 'Cura&ccedil;ao', 'CUW', '531', 'no', '599', '.cw'),
(58, 'CY', 'Cyprus', 'Republic of Cyprus', 'CYP', '196', 'yes', '357', '.cy'),
(59, 'CZ', 'Czech Republic', 'Czech Republic', 'CZE', '203', 'yes', '420', '.cz'),
(60, 'CD', 'Democratic Republic of the Congo', 'Democratic Republic of the Congo', 'COD', '180', 'yes', '243', '.cd'),
(61, 'DK', 'Denmark', 'Kingdom of Denmark', 'DNK', '208', 'yes', '45', '.dk'),
(62, 'DJ', 'Djibouti', 'Republic of Djibouti', 'DJI', '262', 'yes', '253', '.dj'),
(63, 'DM', 'Dominica', 'Commonwealth of Dominica', 'DMA', '212', 'yes', '1+767', '.dm'),
(64, 'DO', 'Dominican Republic', 'Dominican Republic', 'DOM', '214', 'yes', '1+809, 8', '.do'),
(65, 'EC', 'Ecuador', 'Republic of Ecuador', 'ECU', '218', 'yes', '593', '.ec'),
(66, 'EG', 'Egypt', 'Arab Republic of Egypt', 'EGY', '818', 'yes', '20', '.eg'),
(67, 'SV', 'El Salvador', 'Republic of El Salvador', 'SLV', '222', 'yes', '503', '.sv'),
(68, 'GQ', 'Equatorial Guinea', 'Republic of Equatorial Guinea', 'GNQ', '226', 'yes', '240', '.gq'),
(69, 'ER', 'Eritrea', 'State of Eritrea', 'ERI', '232', 'yes', '291', '.er'),
(70, 'EE', 'Estonia', 'Republic of Estonia', 'EST', '233', 'yes', '372', '.ee'),
(71, 'ET', 'Ethiopia', 'Federal Democratic Republic of Ethiopia', 'ETH', '231', 'yes', '251', '.et'),
(72, 'FK', 'Falkland Islands (Malvinas)', 'The Falkland Islands (Malvinas)', 'FLK', '238', 'no', '500', '.fk'),
(73, 'FO', 'Faroe Islands', 'The Faroe Islands', 'FRO', '234', 'no', '298', '.fo'),
(74, 'FJ', 'Fiji', 'Republic of Fiji', 'FJI', '242', 'yes', '679', '.fj'),
(75, 'FI', 'Finland', 'Republic of Finland', 'FIN', '246', 'yes', '358', '.fi'),
(76, 'FR', 'France', 'French Republic', 'FRA', '250', 'yes', '33', '.fr'),
(77, 'GF', 'French Guiana', 'French Guiana', 'GUF', '254', 'no', '594', '.gf'),
(78, 'PF', 'French Polynesia', 'French Polynesia', 'PYF', '258', 'no', '689', '.pf'),
(79, 'TF', 'French Southern Territories', 'French Southern Territories', 'ATF', '260', 'no', NULL, '.tf'),
(80, 'GA', 'Gabon', 'Gabonese Republic', 'GAB', '266', 'yes', '241', '.ga'),
(81, 'GM', 'Gambia', 'Republic of The Gambia', 'GMB', '270', 'yes', '220', '.gm'),
(82, 'GE', 'Georgia', 'Georgia', 'GEO', '268', 'yes', '995', '.ge'),
(83, 'DE', 'Germany', 'Federal Republic of Germany', 'DEU', '276', 'yes', '49', '.de'),
(84, 'GH', 'Ghana', 'Republic of Ghana', 'GHA', '288', 'yes', '233', '.gh'),
(85, 'GI', 'Gibraltar', 'Gibraltar', 'GIB', '292', 'no', '350', '.gi'),
(86, 'GR', 'Greece', 'Hellenic Republic', 'GRC', '300', 'yes', '30', '.gr'),
(87, 'GL', 'Greenland', 'Greenland', 'GRL', '304', 'no', '299', '.gl'),
(88, 'GD', 'Grenada', 'Grenada', 'GRD', '308', 'yes', '1+473', '.gd'),
(89, 'GP', 'Guadaloupe', 'Guadeloupe', 'GLP', '312', 'no', '590', '.gp'),
(90, 'GU', 'Guam', 'Guam', 'GUM', '316', 'no', '1+671', '.gu'),
(91, 'GT', 'Guatemala', 'Republic of Guatemala', 'GTM', '320', 'yes', '502', '.gt'),
(92, 'GG', 'Guernsey', 'Guernsey', 'GGY', '831', 'no', '44', '.gg'),
(93, 'GN', 'Guinea', 'Republic of Guinea', 'GIN', '324', 'yes', '224', '.gn'),
(94, 'GW', 'Guinea-Bissau', 'Republic of Guinea-Bissau', 'GNB', '624', 'yes', '245', '.gw'),
(95, 'GY', 'Guyana', 'Co-operative Republic of Guyana', 'GUY', '328', 'yes', '592', '.gy'),
(96, 'HT', 'Haiti', 'Republic of Haiti', 'HTI', '332', 'yes', '509', '.ht'),
(97, 'HM', 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands', 'HMD', '334', 'no', 'NONE', '.hm'),
(98, 'HN', 'Honduras', 'Republic of Honduras', 'HND', '340', 'yes', '504', '.hn'),
(99, 'HK', 'Hong Kong', 'Hong Kong', 'HKG', '344', 'no', '852', '.hk'),
(100, 'HU', 'Hungary', 'Hungary', 'HUN', '348', 'yes', '36', '.hu'),
(101, 'IS', 'Iceland', 'Republic of Iceland', 'ISL', '352', 'yes', '354', '.is'),
(102, 'IN', 'India', 'Republic of India', 'IND', '356', 'yes', '91', '.in'),
(103, 'ID', 'Indonesia', 'Republic of Indonesia', 'IDN', '360', 'yes', '62', '.id'),
(104, 'IR', 'Iran', 'Islamic Republic of Iran', 'IRN', '364', 'yes', '98', '.ir'),
(105, 'IQ', 'Iraq', 'Republic of Iraq', 'IRQ', '368', 'yes', '964', '.iq'),
(106, 'IE', 'Ireland', 'Ireland', 'IRL', '372', 'yes', '353', '.ie'),
(107, 'IM', 'Isle of Man', 'Isle of Man', 'IMN', '833', 'no', '44', '.im'),
(108, 'IL', 'Israel', 'State of Israel', 'ISR', '376', 'yes', '972', '.il'),
(109, 'IT', 'Italy', 'Italian Republic', 'ITA', '380', 'yes', '39', '.jm'),
(110, 'JM', 'Jamaica', 'Jamaica', 'JAM', '388', 'yes', '1+876', '.jm'),
(111, 'JP', 'Japan', 'Japan', 'JPN', '392', 'yes', '81', '.jp'),
(112, 'JE', 'Jersey', 'The Bailiwick of Jersey', 'JEY', '832', 'no', '44', '.je'),
(113, 'JO', 'Jordan', 'Hashemite Kingdom of Jordan', 'JOR', '400', 'yes', '962', '.jo'),
(114, 'KZ', 'Kazakhstan', 'Republic of Kazakhstan', 'KAZ', '398', 'yes', '7', '.kz'),
(115, 'KE', 'Kenya', 'Republic of Kenya', 'KEN', '404', 'yes', '254', '.ke'),
(116, 'KI', 'Kiribati', 'Republic of Kiribati', 'KIR', '296', 'yes', '686', '.ki'),
(117, 'XK', 'Kosovo', 'Republic of Kosovo', '---', '---', 'some', '381', ''),
(118, 'KW', 'Kuwait', 'State of Kuwait', 'KWT', '414', 'yes', '965', '.kw'),
(119, 'KG', 'Kyrgyzstan', 'Kyrgyz Republic', 'KGZ', '417', 'yes', '996', '.kg'),
(120, 'LA', 'Laos', 'Lao People''s Democratic Republic', 'LAO', '418', 'yes', '856', '.la'),
(121, 'LV', 'Latvia', 'Republic of Latvia', 'LVA', '428', 'yes', '371', '.lv'),
(122, 'LB', 'Lebanon', 'Republic of Lebanon', 'LBN', '422', 'yes', '961', '.lb'),
(123, 'LS', 'Lesotho', 'Kingdom of Lesotho', 'LSO', '426', 'yes', '266', '.ls'),
(124, 'LR', 'Liberia', 'Republic of Liberia', 'LBR', '430', 'yes', '231', '.lr'),
(125, 'LY', 'Libya', 'Libya', 'LBY', '434', 'yes', '218', '.ly'),
(126, 'LI', 'Liechtenstein', 'Principality of Liechtenstein', 'LIE', '438', 'yes', '423', '.li'),
(127, 'LT', 'Lithuania', 'Republic of Lithuania', 'LTU', '440', 'yes', '370', '.lt'),
(128, 'LU', 'Luxembourg', 'Grand Duchy of Luxembourg', 'LUX', '442', 'yes', '352', '.lu'),
(129, 'MO', 'Macao', 'The Macao Special Administrative Region', 'MAC', '446', 'no', '853', '.mo'),
(130, 'MK', 'Macedonia', 'The Former Yugoslav Republic of Macedonia', 'MKD', '807', 'yes', '389', '.mk'),
(131, 'MG', 'Madagascar', 'Republic of Madagascar', 'MDG', '450', 'yes', '261', '.mg'),
(132, 'MW', 'Malawi', 'Republic of Malawi', 'MWI', '454', 'yes', '265', '.mw'),
(133, 'MY', 'Malaysia', 'Malaysia', 'MYS', '458', 'yes', '60', '.my'),
(134, 'MV', 'Maldives', 'Republic of Maldives', 'MDV', '462', 'yes', '960', '.mv'),
(135, 'ML', 'Mali', 'Republic of Mali', 'MLI', '466', 'yes', '223', '.ml'),
(136, 'MT', 'Malta', 'Republic of Malta', 'MLT', '470', 'yes', '356', '.mt'),
(137, 'MH', 'Marshall Islands', 'Republic of the Marshall Islands', 'MHL', '584', 'yes', '692', '.mh'),
(138, 'MQ', 'Martinique', 'Martinique', 'MTQ', '474', 'no', '596', '.mq'),
(139, 'MR', 'Mauritania', 'Islamic Republic of Mauritania', 'MRT', '478', 'yes', '222', '.mr'),
(140, 'MU', 'Mauritius', 'Republic of Mauritius', 'MUS', '480', 'yes', '230', '.mu'),
(141, 'YT', 'Mayotte', 'Mayotte', 'MYT', '175', 'no', '262', '.yt'),
(142, 'MX', 'Mexico', 'United Mexican States', 'MEX', '484', 'yes', '52', '.mx'),
(143, 'FM', 'Micronesia', 'Federated States of Micronesia', 'FSM', '583', 'yes', '691', '.fm'),
(144, 'MD', 'Moldava', 'Republic of Moldova', 'MDA', '498', 'yes', '373', '.md'),
(145, 'MC', 'Monaco', 'Principality of Monaco', 'MCO', '492', 'yes', '377', '.mc'),
(146, 'MN', 'Mongolia', 'Mongolia', 'MNG', '496', 'yes', '976', '.mn'),
(147, 'ME', 'Montenegro', 'Montenegro', 'MNE', '499', 'yes', '382', '.me'),
(148, 'MS', 'Montserrat', 'Montserrat', 'MSR', '500', 'no', '1+664', '.ms'),
(149, 'MA', 'Morocco', 'Kingdom of Morocco', 'MAR', '504', 'yes', '212', '.ma'),
(150, 'MZ', 'Mozambique', 'Republic of Mozambique', 'MOZ', '508', 'yes', '258', '.mz'),
(151, 'MM', 'Myanmar (Burma)', 'Republic of the Union of Myanmar', 'MMR', '104', 'yes', '95', '.mm'),
(152, 'NA', 'Namibia', 'Republic of Namibia', 'NAM', '516', 'yes', '264', '.na'),
(153, 'NR', 'Nauru', 'Republic of Nauru', 'NRU', '520', 'yes', '674', '.nr'),
(154, 'NP', 'Nepal', 'Federal Democratic Republic of Nepal', 'NPL', '524', 'yes', '977', '.np'),
(155, 'NL', 'Netherlands', 'Kingdom of the Netherlands', 'NLD', '528', 'yes', '31', '.nl'),
(156, 'NC', 'New Caledonia', 'New Caledonia', 'NCL', '540', 'no', '687', '.nc'),
(157, 'NZ', 'New Zealand', 'New Zealand', 'NZL', '554', 'yes', '64', '.nz'),
(158, 'NI', 'Nicaragua', 'Republic of Nicaragua', 'NIC', '558', 'yes', '505', '.ni'),
(159, 'NE', 'Niger', 'Republic of Niger', 'NER', '562', 'yes', '227', '.ne'),
(160, 'NG', 'Nigeria', 'Federal Republic of Nigeria', 'NGA', '566', 'yes', '234', '.ng'),
(161, 'NU', 'Niue', 'Niue', 'NIU', '570', 'some', '683', '.nu'),
(162, 'NF', 'Norfolk Island', 'Norfolk Island', 'NFK', '574', 'no', '672', '.nf'),
(163, 'KP', 'North Korea', 'Democratic People''s Republic of Korea', 'PRK', '408', 'yes', '850', '.kp'),
(164, 'MP', 'Northern Mariana Islands', 'Northern Mariana Islands', 'MNP', '580', 'no', '1+670', '.mp'),
(165, 'NO', 'Norway', 'Kingdom of Norway', 'NOR', '578', 'yes', '47', '.no'),
(166, 'OM', 'Oman', 'Sultanate of Oman', 'OMN', '512', 'yes', '968', '.om'),
(167, 'PK', 'Pakistan', 'Islamic Republic of Pakistan', 'PAK', '586', 'yes', '92', '.pk'),
(168, 'PW', 'Palau', 'Republic of Palau', 'PLW', '585', 'yes', '680', '.pw'),
(169, 'PS', 'Palestine', 'State of Palestine (or Occupied Palestinian Territory)', 'PSE', '275', 'some', '970', '.ps'),
(170, 'PA', 'Panama', 'Republic of Panama', 'PAN', '591', 'yes', '507', '.pa'),
(171, 'PG', 'Papua New Guinea', 'Independent State of Papua New Guinea', 'PNG', '598', 'yes', '675', '.pg'),
(172, 'PY', 'Paraguay', 'Republic of Paraguay', 'PRY', '600', 'yes', '595', '.py'),
(173, 'PE', 'Peru', 'Republic of Peru', 'PER', '604', 'yes', '51', '.pe'),
(174, 'PH', 'Phillipines', 'Republic of the Philippines', 'PHL', '608', 'yes', '63', '.ph'),
(175, 'PN', 'Pitcairn', 'Pitcairn', 'PCN', '612', 'no', 'NONE', '.pn'),
(176, 'PL', 'Poland', 'Republic of Poland', 'POL', '616', 'yes', '48', '.pl'),
(177, 'PT', 'Portugal', 'Portuguese Republic', 'PRT', '620', 'yes', '351', '.pt'),
(178, 'PR', 'Puerto Rico', 'Commonwealth of Puerto Rico', 'PRI', '630', 'no', '1+939', '.pr'),
(179, 'QA', 'Qatar', 'State of Qatar', 'QAT', '634', 'yes', '974', '.qa'),
(180, 'RE', 'Reunion', 'R&eacute;union', 'REU', '638', 'no', '262', '.re'),
(181, 'RO', 'Romania', 'Romania', 'ROU', '642', 'yes', '40', '.ro'),
(182, 'RU', 'Russia', 'Russian Federation', 'RUS', '643', 'yes', '7', '.ru'),
(183, 'RW', 'Rwanda', 'Republic of Rwanda', 'RWA', '646', 'yes', '250', '.rw'),
(184, 'BL', 'Saint Barthelemy', 'Saint Barth&eacute;lemy', 'BLM', '652', 'no', '590', '.bl'),
(185, 'SH', 'Saint Helena', 'Saint Helena, Ascension and Tristan da Cunha', 'SHN', '654', 'no', '290', '.sh'),
(186, 'KN', 'Saint Kitts and Nevis', 'Federation of Saint Christopher and Nevis', 'KNA', '659', 'yes', '1+869', '.kn'),
(187, 'LC', 'Saint Lucia', 'Saint Lucia', 'LCA', '662', 'yes', '1+758', '.lc'),
(188, 'MF', 'Saint Martin', 'Saint Martin', 'MAF', '663', 'no', '590', '.mf'),
(189, 'PM', 'Saint Pierre and Miquelon', 'Saint Pierre and Miquelon', 'SPM', '666', 'no', '508', '.pm'),
(190, 'VC', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', 'VCT', '670', 'yes', '1+784', '.vc'),
(191, 'WS', 'Samoa', 'Independent State of Samoa', 'WSM', '882', 'yes', '685', '.ws'),
(192, 'SM', 'San Marino', 'Republic of San Marino', 'SMR', '674', 'yes', '378', '.sm'),
(193, 'ST', 'Sao Tome and Principe', 'Democratic Republic of S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'STP', '678', 'yes', '239', '.st'),
(194, 'SA', 'Saudi Arabia', 'Kingdom of Saudi Arabia', 'SAU', '682', 'yes', '966', '.sa'),
(195, 'SN', 'Senegal', 'Republic of Senegal', 'SEN', '686', 'yes', '221', '.sn'),
(196, 'RS', 'Serbia', 'Republic of Serbia', 'SRB', '688', 'yes', '381', '.rs'),
(197, 'SC', 'Seychelles', 'Republic of Seychelles', 'SYC', '690', 'yes', '248', '.sc'),
(198, 'SL', 'Sierra Leone', 'Republic of Sierra Leone', 'SLE', '694', 'yes', '232', '.sl'),
(199, 'SG', 'Singapore', 'Republic of Singapore', 'SGP', '702', 'yes', '65', '.sg'),
(200, 'SX', 'Sint Maarten', 'Sint Maarten', 'SXM', '534', 'no', '1+721', '.sx'),
(201, 'SK', 'Slovakia', 'Slovak Republic', 'SVK', '703', 'yes', '421', '.sk'),
(202, 'SI', 'Slovenia', 'Republic of Slovenia', 'SVN', '705', 'yes', '386', '.si'),
(203, 'SB', 'Solomon Islands', 'Solomon Islands', 'SLB', '090', 'yes', '677', '.sb'),
(204, 'SO', 'Somalia', 'Somali Republic', 'SOM', '706', 'yes', '252', '.so'),
(205, 'ZA', 'South Africa', 'Republic of South Africa', 'ZAF', '710', 'yes', '27', '.za'),
(206, 'GS', 'South Georgia and the South Sandwich Islands', 'South Georgia and the South Sandwich Islands', 'SGS', '239', 'no', '500', '.gs'),
(207, 'KR', 'South Korea', 'Republic of Korea', 'KOR', '410', 'yes', '82', '.kr'),
(208, 'SS', 'South Sudan', 'Republic of South Sudan', 'SSD', '728', 'yes', '211', '.ss'),
(209, 'ES', 'Spain', 'Kingdom of Spain', 'ESP', '724', 'yes', '34', '.es'),
(210, 'LK', 'Sri Lanka', 'Democratic Socialist Republic of Sri Lanka', 'LKA', '144', 'yes', '94', '.lk'),
(211, 'SD', 'Sudan', 'Republic of the Sudan', 'SDN', '729', 'yes', '249', '.sd'),
(212, 'SR', 'Suriname', 'Republic of Suriname', 'SUR', '740', 'yes', '597', '.sr'),
(213, 'SJ', 'Svalbard and Jan Mayen', 'Svalbard and Jan Mayen', 'SJM', '744', 'no', '47', '.sj'),
(214, 'SZ', 'Swaziland', 'Kingdom of Swaziland', 'SWZ', '748', 'yes', '268', '.sz'),
(215, 'SE', 'Sweden', 'Kingdom of Sweden', 'SWE', '752', 'yes', '46', '.se'),
(216, 'CH', 'Switzerland', 'Swiss Confederation', 'CHE', '756', 'yes', '41', '.ch'),
(217, 'SY', 'Syria', 'Syrian Arab Republic', 'SYR', '760', 'yes', '963', '.sy'),
(218, 'TW', 'Taiwan', 'Republic of China (Taiwan)', 'TWN', '158', 'former', '886', '.tw'),
(219, 'TJ', 'Tajikistan', 'Republic of Tajikistan', 'TJK', '762', 'yes', '992', '.tj'),
(220, 'TZ', 'Tanzania', 'United Republic of Tanzania', 'TZA', '834', 'yes', '255', '.tz'),
(221, 'TH', 'Thailand', 'Kingdom of Thailand', 'THA', '764', 'yes', '66', '.th'),
(222, 'TL', 'Timor-Leste (East Timor)', 'Democratic Republic of Timor-Leste', 'TLS', '626', 'yes', '670', '.tl'),
(223, 'TG', 'Togo', 'Togolese Republic', 'TGO', '768', 'yes', '228', '.tg'),
(224, 'TK', 'Tokelau', 'Tokelau', 'TKL', '772', 'no', '690', '.tk'),
(225, 'TO', 'Tonga', 'Kingdom of Tonga', 'TON', '776', 'yes', '676', '.to'),
(226, 'TT', 'Trinidad and Tobago', 'Republic of Trinidad and Tobago', 'TTO', '780', 'yes', '1+868', '.tt'),
(227, 'TN', 'Tunisia', 'Republic of Tunisia', 'TUN', '788', 'yes', '216', '.tn'),
(228, 'TR', 'Turkey', 'Republic of Turkey', 'TUR', '792', 'yes', '90', '.tr'),
(229, 'TM', 'Turkmenistan', 'Turkmenistan', 'TKM', '795', 'yes', '993', '.tm'),
(230, 'TC', 'Turks and Caicos Islands', 'Turks and Caicos Islands', 'TCA', '796', 'no', '1+649', '.tc'),
(231, 'TV', 'Tuvalu', 'Tuvalu', 'TUV', '798', 'yes', '688', '.tv'),
(232, 'UG', 'Uganda', 'Republic of Uganda', 'UGA', '800', 'yes', '256', '.ug'),
(233, 'UA', 'Ukraine', 'Ukraine', 'UKR', '804', 'yes', '380', '.ua'),
(234, 'AE', 'United Arab Emirates', 'United Arab Emirates', 'ARE', '784', 'yes', '971', '.ae'),
(235, 'GB', 'United Kingdom', 'United Kingdom of Great Britain and Nothern Ireland', 'GBR', '826', 'yes', '44', '.uk'),
(236, 'US', 'United States', 'United States of America', 'USA', '840', 'yes', '1', '.us'),
(237, 'UM', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'UMI', '581', 'no', 'NONE', 'NONE'),
(238, 'UY', 'Uruguay', 'Eastern Republic of Uruguay', 'URY', '858', 'yes', '598', '.uy'),
(239, 'UZ', 'Uzbekistan', 'Republic of Uzbekistan', 'UZB', '860', 'yes', '998', '.uz'),
(240, 'VU', 'Vanuatu', 'Republic of Vanuatu', 'VUT', '548', 'yes', '678', '.vu'),
(241, 'VA', 'Vatican City', 'State of the Vatican City', 'VAT', '336', 'no', '39', '.va'),
(242, 'VE', 'Venezuela', 'Bolivarian Republic of Venezuela', 'VEN', '862', 'yes', '58', '.ve'),
(243, 'VN', 'Vietnam', 'Socialist Republic of Vietnam', 'VNM', '704', 'yes', '84', '.vn'),
(244, 'VG', 'Virgin Islands, British', 'British Virgin Islands', 'VGB', '092', 'no', '1+284', '.vg'),
(245, 'VI', 'Virgin Islands, US', 'Virgin Islands of the United States', 'VIR', '850', 'no', '1+340', '.vi'),
(246, 'WF', 'Wallis and Futuna', 'Wallis and Futuna', 'WLF', '876', 'no', '681', '.wf'),
(247, 'EH', 'Western Sahara', 'Western Sahara', 'ESH', '732', 'no', '212', '.eh'),
(248, 'YE', 'Yemen', 'Republic of Yemen', 'YEM', '887', 'yes', '967', '.ye'),
(249, 'ZM', 'Zambia', 'Republic of Zambia', 'ZMB', '894', 'yes', '260', '.zm'),
(250, 'ZW', 'Zimbabwe', 'Republic of Zimbabwe', 'ZWE', '716', 'yes', '263', '.zw');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_fields`
--

CREATE TABLE IF NOT EXISTS `user_fields` (
  `userFieldId` int(11) NOT NULL AUTO_INCREMENT,
  `userFieldPosition` int(11) DEFAULT NULL,
  `userFieldClass` varchar(45) DEFAULT NULL,
  `inputId` int(11) DEFAULT NULL,
  `userFieldActive` tinyint(1) DEFAULT NULL,
  `usuarioId` mediumint(8) DEFAULT NULL,
  `userFieldRequired` tinyint(1) DEFAULT '0',
  `userFieldValidation` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`userFieldId`),
  KEY `inputId_uf_idx` (`inputId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_fields_rel`
--

CREATE TABLE IF NOT EXISTS `user_fields_rel` (
  `userFieldRelId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` mediumint(8) unsigned DEFAULT NULL,
  `userFieldId` int(11) DEFAULT NULL,
  `userFieldRelContent` text,
  PRIMARY KEY (`userFieldRelId`),
  KEY `userFieldId_ufr_idx` (`userFieldId`),
  KEY `userId_idx` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `fk_calendar_id` FOREIGN KEY (`calendar_id`) REFERENCES `calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `activity_fields`
--
ALTER TABLE `activity_fields`
  ADD CONSTRAINT `fk_input_id_af` FOREIGN KEY (`input_id`) REFERENCES `input` (`inputId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `activity_fields_rel`
--
ALTER TABLE `activity_fields_rel`
  ADD CONSTRAINT `fk_activity_id_rel` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_field_id_rel` FOREIGN KEY (`field_id`) REFERENCES `activity_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_language_id_rel` FOREIGN KEY (`language_id`) REFERENCES `idioma` (`idiomaId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `admin_usuarios_secciones`
--
ALTER TABLE `admin_usuarios_secciones`
  ADD CONSTRAINT `adminSeccionId` FOREIGN KEY (`adminSeccionId`) REFERENCES `admin_secciones` (`adminSeccionId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD CONSTRAINT `paginaId_a` FOREIGN KEY (`paginaId`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `banner_campos`
--
ALTER TABLE `banner_campos`
  ADD CONSTRAINT `inputId_bc` FOREIGN KEY (`inputId`) REFERENCES `input` (`inputId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `banner_campos_rel`
--
ALTER TABLE `banner_campos_rel`
  ADD CONSTRAINT `bannerCampoId_rel` FOREIGN KEY (`bannerCampoId`) REFERENCES `banner_campos` (`bannerCampoId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bannerImagen_rel` FOREIGN KEY (`bannerCamposImagenId`) REFERENCES `banner_images` (`bannerImagesId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `banner_images`
--
ALTER TABLE `banner_images`
  ADD CONSTRAINT `bannerId_bi` FOREIGN KEY (`bannerId`) REFERENCES `banners` (`bannerId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `contacto_campos`
--
ALTER TABLE `contacto_campos`
  ADD CONSTRAINT `inputId_cc` FOREIGN KEY (`inputId`) REFERENCES `input` (`inputId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `descargas`
--
ALTER TABLE `descargas`
  ADD CONSTRAINT `descargaCategoriaId_d` FOREIGN KEY (`descargaCategoriaId`) REFERENCES `descargas_categorias` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `discount_group_items`
--
ALTER TABLE `discount_group_items`
  ADD CONSTRAINT `discount_group_fk_dg` FOREIGN KEY (`disc_group_item_group_fk`) REFERENCES `discount_groups` (`disc_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `disc_group_item_item_fk_p` FOREIGN KEY (`disc_group_item_item_fk`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `enlaces`
--
ALTER TABLE `enlaces`
  ADD CONSTRAINT `paginaId_e` FOREIGN KEY (`paginaId`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `estadisticas`
--
ALTER TABLE `estadisticas`
  ADD CONSTRAINT `paginaId_est` FOREIGN KEY (`paginaId`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_activities`
--
ALTER TABLE `es_activities`
  ADD CONSTRAINT `fk_es_activity_id` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_activity_fields`
--
ALTER TABLE `es_activity_fields`
  ADD CONSTRAINT `fk_es_activity_field_id` FOREIGN KEY (`activity_field_id`) REFERENCES `activity_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_articulos`
--
ALTER TABLE `es_articulos`
  ADD CONSTRAINT `es_articuloId` FOREIGN KEY (`articuloId`) REFERENCES `articulos` (`articuloId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_banner_campos`
--
ALTER TABLE `es_banner_campos`
  ADD CONSTRAINT `es_bannerCampos` FOREIGN KEY (`bannerCampoId`) REFERENCES `banner_campos` (`bannerCampoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_banner_campos_rel`
--
ALTER TABLE `es_banner_campos_rel`
  ADD CONSTRAINT `bannerCamposRelId` FOREIGN KEY (`bannerCamposRelId`) REFERENCES `banner_campos_rel` (`bannerCampoRelId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_contactos`
--
ALTER TABLE `es_contactos`
  ADD CONSTRAINT `es_contactoId` FOREIGN KEY (`contactoId`) REFERENCES `contactos` (`contactoId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_contacto_campos`
--
ALTER TABLE `es_contacto_campos`
  ADD CONSTRAINT `es_contactoCampoId` FOREIGN KEY (`contactoCampoId`) REFERENCES `contacto_campos` (`contactoCampoId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_contacto_direcciones`
--
ALTER TABLE `es_contacto_direcciones`
  ADD CONSTRAINT `es_contactoDireccion_idx` FOREIGN KEY (`contactoDireccionId`) REFERENCES `contacto_direcciones` (`contactoDireccionId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_descargas`
--
ALTER TABLE `es_descargas`
  ADD CONSTRAINT `es_descargaId` FOREIGN KEY (`descargaId`) REFERENCES `descargas` (`descargaId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_descargas_categorias`
--
ALTER TABLE `es_descargas_categorias`
  ADD CONSTRAINT `es_descargaCategoriaId_fk` FOREIGN KEY (`descargaCategoriaId`) REFERENCES `descargas_categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_enlaces`
--
ALTER TABLE `es_enlaces`
  ADD CONSTRAINT `es_enlaceId` FOREIGN KEY (`enlaceId`) REFERENCES `enlaces` (`enlaceId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_faq`
--
ALTER TABLE `es_faq`
  ADD CONSTRAINT `es_faqId` FOREIGN KEY (`faqId`) REFERENCES `faq` (`faqId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_mapas_campos`
--
ALTER TABLE `es_mapas_campos`
  ADD CONSTRAINT `es_mapaCampoId_mc` FOREIGN KEY (`mapaCampoId`) REFERENCES `mapas_campos` (`mapaCampoId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_mapa_campo_rel`
--
ALTER TABLE `es_mapa_campo_rel`
  ADD CONSTRAINT `es_mapaCampoRel_mcr` FOREIGN KEY (`mapaCampoRelId`) REFERENCES `mapa_campo_rel` (`mapaCampoRelId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_modulos`
--
ALTER TABLE `es_modulos`
  ADD CONSTRAINT `es_moduloId_m` FOREIGN KEY (`moduloId`) REFERENCES `modulos` (`moduloId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_paginas`
--
ALTER TABLE `es_paginas`
  ADD CONSTRAINT `es_paginaId` FOREIGN KEY (`paginaId`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_productos`
--
ALTER TABLE `es_productos`
  ADD CONSTRAINT `es_productoId` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_producto_campos`
--
ALTER TABLE `es_producto_campos`
  ADD CONSTRAINT `es_productoCampoId_pc` FOREIGN KEY (`productoCampoId`) REFERENCES `producto_campos` (`productoCampoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_producto_campos_listado_predefinido`
--
ALTER TABLE `es_producto_campos_listado_predefinido`
  ADD CONSTRAINT `es_productoCamposListadoPredefinidoId_pclpr` FOREIGN KEY (`productoCamposListadoPredefinidoId`) REFERENCES `producto_campos_listado_predefinido` (`productoCamposListadoPredefinidoId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_producto_campos_rel`
--
ALTER TABLE `es_producto_campos_rel`
  ADD CONSTRAINT `es_productoCampoRelId` FOREIGN KEY (`productoCampoRelId`) REFERENCES `producto_campos_rel` (`productoCampoRelId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_producto_categorias`
--
ALTER TABLE `es_producto_categorias`
  ADD CONSTRAINT `es_productoCategoriaId` FOREIGN KEY (`productoCategoriaId`) REFERENCES `producto_categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_producto_descargas`
--
ALTER TABLE `es_producto_descargas`
  ADD CONSTRAINT `es_productoDescargaId` FOREIGN KEY (`productoDescargaId`) REFERENCES `producto_descargas` (`productoDescargaId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_producto_imagenes`
--
ALTER TABLE `es_producto_imagenes`
  ADD CONSTRAINT `es_productoImagenId` FOREIGN KEY (`productoImagenId`) REFERENCES `producto_imagenes` (`productoImagenId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_producto_videos`
--
ALTER TABLE `es_producto_videos`
  ADD CONSTRAINT `es_productoVideoId` FOREIGN KEY (`productoVideoId`) REFERENCES `producto_videos` (`productoVideoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `es_publicaciones`
--
ALTER TABLE `es_publicaciones`
  ADD CONSTRAINT `es_publicacionId` FOREIGN KEY (`publicacionId`) REFERENCES `publicaciones` (`publicacionId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_servicios`
--
ALTER TABLE `es_servicios`
  ADD CONSTRAINT `es_servicioId` FOREIGN KEY (`servicioId`) REFERENCES `servicios` (`servicioId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `es_user_fields`
--
ALTER TABLE `es_user_fields`
  ADD CONSTRAINT `userFieldId_es_uf` FOREIGN KEY (`userFieldId`) REFERENCES `user_fields` (`userFieldId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `fk_paginaId_faq` FOREIGN KEY (`paginaId`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD CONSTRAINT `seccionId_i` FOREIGN KEY (`seccionId`) REFERENCES `admin_secciones` (`adminSeccionId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `imagenes_secciones`
--
ALTER TABLE `imagenes_secciones`
  ADD CONSTRAINT `imagenes_secciones_ibfk_1` FOREIGN KEY (`adminSeccionId`) REFERENCES `admin_secciones` (`adminSeccionId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `input`
--
ALTER TABLE `input`
  ADD CONSTRAINT `inputTipoId_i` FOREIGN KEY (`inputTipoId`) REFERENCES `input_tipo` (`inputTipoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `loc_type_fk_l` FOREIGN KEY (`loc_type_fk`) REFERENCES `location_type` (`loc_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mapas_campos`
--
ALTER TABLE `mapas_campos`
  ADD CONSTRAINT `inputId_mc` FOREIGN KEY (`inputId`) REFERENCES `input` (`inputId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mapas_ubicaciones`
--
ALTER TABLE `mapas_ubicaciones`
  ADD CONSTRAINT `mapaId_mu` FOREIGN KEY (`mapaId`) REFERENCES `mapas` (`mapaId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `mapa_campo_rel`
--
ALTER TABLE `mapa_campo_rel`
  ADD CONSTRAINT `mapaCampoId_mcr` FOREIGN KEY (`mapaCampoId`) REFERENCES `mapas_campos` (`mapaCampoId`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `mapaUbicacionId_mcr` FOREIGN KEY (`mapaUbicacionId`) REFERENCES `mapas_ubicaciones` (`mapaUbicacionId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `paginaId_m` FOREIGN KEY (`paginaId`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paginaModuloTipoId` FOREIGN KEY (`paginaModuloTipoId`) REFERENCES `modulo_tipo` (`paginaModuloTipoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `productoId` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_categoria_id` FOREIGN KEY (`categoriaId`) REFERENCES `producto_categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_audios`
--
ALTER TABLE `producto_audios`
  ADD CONSTRAINT `productoId_pa` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_campos`
--
ALTER TABLE `producto_campos`
  ADD CONSTRAINT `inputId_pc` FOREIGN KEY (`inputId`) REFERENCES `input` (`inputId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_campos_listado_predefinido`
--
ALTER TABLE `producto_campos_listado_predefinido`
  ADD CONSTRAINT `productoCampoId_pclp` FOREIGN KEY (`productoCampoId`) REFERENCES `producto_campos` (`productoCampoId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto_campos_listado_predefinido_rel`
--
ALTER TABLE `producto_campos_listado_predefinido_rel`
  ADD CONSTRAINT `productoCampoId_pclpr` FOREIGN KEY (`productoCampoId`) REFERENCES `producto_campos` (`productoCampoId`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `productoCamposListadoPredefinidoId_pclpr` FOREIGN KEY (`productoCamposListadoPredefinidoId`) REFERENCES `producto_campos_listado_predefinido` (`productoCamposListadoPredefinidoId`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `productoId_pclpr` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto_campos_rel`
--
ALTER TABLE `producto_campos_rel`
  ADD CONSTRAINT `productoCampoId_pcr` FOREIGN KEY (`productoCampoId`) REFERENCES `producto_campos` (`productoCampoId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productoId_pcr` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_descargas`
--
ALTER TABLE `producto_descargas`
  ADD CONSTRAINT `productoId_descargas` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  ADD CONSTRAINT `productoId_pi` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_videos`
--
ALTER TABLE `producto_videos`
  ADD CONSTRAINT `productoId_pv` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `paginaId_p` FOREIGN KEY (`paginaId`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `publicaciones_imagenes`
--
ALTER TABLE `publicaciones_imagenes`
  ADD CONSTRAINT `publicacionId_fk_pi` FOREIGN KEY (`publicacionId`) REFERENCES `publicaciones` (`publicacionId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `publicidad`
--
ALTER TABLE `publicidad`
  ADD CONSTRAINT `publicidadTipoId_fk` FOREIGN KEY (`publicidadTipoId`) REFERENCES `publicidad_tipo` (`publicidadTipoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `fk_paginaId_s` FOREIGN KEY (`paginaId`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `servicios_imagenes`
--
ALTER TABLE `servicios_imagenes`
  ADD CONSTRAINT `fk_servicios_imagenes_s` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`servicioId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_fields`
--
ALTER TABLE `user_fields`
  ADD CONSTRAINT `inputId_uf` FOREIGN KEY (`inputId`) REFERENCES `input` (`inputId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_fields_rel`
--
ALTER TABLE `user_fields_rel`
  ADD CONSTRAINT `userFieldId_ufr` FOREIGN KEY (`userFieldId`) REFERENCES `user_fields` (`userFieldId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
