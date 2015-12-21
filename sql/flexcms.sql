-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-12-2015 a las 10:15:15
-- Versión del servidor: 5.5.46-0ubuntu0.14.04.2
-- Versión de PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `flexcms17`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activations`
--

CREATE TABLE IF NOT EXISTS `activations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_activations_user_id_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `activations`
--

INSERT INTO `activations` (`id`, `user_id`, `code`, `completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(6, 1, 'tN8O7WyAbtLjOG1qqxigRtMh12EWMo0i', 1, '2015-11-26 19:30:55', '2015-11-26 19:30:55', '2015-11-26 19:30:55'),
(7, 2, 'm1cBWTT5K5DufPZy08RHoelXsAIpsZS3', 1, '2015-11-27 03:59:14', '2015-11-27 03:59:14', '2015-11-27 03:59:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adresses`
--

CREATE TABLE IF NOT EXISTS `adresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `image` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `adresses`
--

INSERT INTO `adresses` (`id`, `name`, `position`, `image`) VALUES
(1, 'Quitoa', 1, 'jpg'),
(4, '3', 3, NULL),
(5, '2222222', 2, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adverts`
--

CREATE TABLE IF NOT EXISTS `adverts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `widget_id` int(11) DEFAULT NULL,
  `categories` varchar(255) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL,
  `file1` varchar(45) DEFAULT NULL,
  `file2` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `publicidadTipoId_fk_idx` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) DEFAULT '1',
  `date` date NOT NULL,
  `temporary` tinyint(1) DEFAULT '1',
  `css_class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` time NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT NULL,
  `temporary` tinyint(1) DEFAULT '1',
  `enabled` tinyint(1) DEFAULT '1',
  `data` mediumtext COMMENT 'temporary field untill I finish translations and dynamic fields',
  PRIMARY KEY (`id`),
  KEY `fk_calendar_id` (`calendar_id`),
  KEY `fk_activities_placeId_idx` (`place_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
-- Estructura de tabla para la tabla `cart_currency`
--

CREATE TABLE IF NOT EXISTS `cart_currency` (
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
-- Volcado de datos para la tabla `cart_currency`
--

INSERT INTO `cart_currency` (`curr_id`, `curr_name`, `curr_exchange_rate`, `curr_symbol`, `curr_symbol_suffix`, `curr_thousand_separator`, `curr_decimal_separator`, `curr_status`, `curr_default`) VALUES
(2, 'USD', 1.0000, '$', 0, '.', ',', 1, 1);

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
-- Estructura de tabla para la tabla `cart_discounts`
--

CREATE TABLE IF NOT EXISTS `cart_discounts` (
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
-- Volcado de datos para la tabla `cart_discounts`
--

INSERT INTO `cart_discounts` (`disc_id`, `disc_type_fk`, `disc_method_fk`, `disc_tax_method_fk`, `disc_user_acc_fk`, `disc_item_fk`, `disc_group_fk`, `disc_location_fk`, `disc_zone_fk`, `disc_code`, `disc_description`, `disc_quantity_required`, `disc_quantity_discounted`, `disc_value_required`, `disc_value_discounted`, `disc_recursive`, `disc_non_combinable_discount`, `disc_void_reward_points`, `disc_force_ship_discount`, `disc_custom_status_1`, `disc_custom_status_2`, `disc_custom_status_3`, `disc_usage_limit`, `disc_valid_date`, `disc_expire_date`, `disc_status`, `disc_order_by`) VALUES
(1, 1, 2, 1, 0, 0, 0, 4, 4, 'asaas', 'asasasasasasasasas', 0, 0, 0.00, 0.00, 0, 0, 0, 0, '', '', '', 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_discount_calculation`
--

CREATE TABLE IF NOT EXISTS `cart_discount_calculation` (
  `disc_calculation_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_calculation` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`disc_calculation_id`),
  UNIQUE KEY `disc_calculation_id` (`disc_calculation_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Note: Do not alter the order or id''s of records in table.' AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `cart_discount_calculation`
--

INSERT INTO `cart_discount_calculation` (`disc_calculation_id`, `disc_calculation`) VALUES
(1, 'Percentage Based'),
(2, 'Flat Fee'),
(3, 'New Value');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_discount_columns`
--

CREATE TABLE IF NOT EXISTS `cart_discount_columns` (
  `disc_column_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_column` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`disc_column_id`),
  UNIQUE KEY `disc_column_id` (`disc_column_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Note: Do not alter the order or id''s of records in table.' AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `cart_discount_columns`
--

INSERT INTO `cart_discount_columns` (`disc_column_id`, `disc_column`) VALUES
(1, 'Item Price'),
(2, 'Item Shipping'),
(3, 'Summary Item Total'),
(4, 'Summary Shipping Total'),
(5, 'Summary Total'),
(6, 'Summary Total (Voucher)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_discount_groups`
--

CREATE TABLE IF NOT EXISTS `cart_discount_groups` (
  `disc_group_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_group` varchar(255) NOT NULL DEFAULT '',
  `disc_group_status` tinyint(1) NOT NULL DEFAULT '0',
  `disc_group_temporary` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`disc_group_id`),
  UNIQUE KEY `disc_group_id` (`disc_group_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Volcado de datos para la tabla `cart_discount_groups`
--

INSERT INTO `cart_discount_groups` (`disc_group_id`, `disc_group`, `disc_group_status`, `disc_group_temporary`) VALUES
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
-- Estructura de tabla para la tabla `cart_discount_group_items`
--

CREATE TABLE IF NOT EXISTS `cart_discount_group_items` (
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
-- Estructura de tabla para la tabla `cart_discount_methods`
--

CREATE TABLE IF NOT EXISTS `cart_discount_methods` (
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
-- Volcado de datos para la tabla `cart_discount_methods`
--

INSERT INTO `cart_discount_methods` (`disc_method_id`, `disc_method_type_fk`, `disc_method_column_fk`, `disc_method_calculation_fk`, `disc_method`) VALUES
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
-- Estructura de tabla para la tabla `cart_discount_tax_methods`
--

CREATE TABLE IF NOT EXISTS `cart_discount_tax_methods` (
  `disc_tax_method_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_tax_method` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`disc_tax_method_id`),
  UNIQUE KEY `disc_tax_method_id` (`disc_tax_method_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Note: Do not alter the order or id''s of records in table.' AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `cart_discount_tax_methods`
--

INSERT INTO `cart_discount_tax_methods` (`disc_tax_method_id`, `disc_tax_method`) VALUES
(1, 'Apply Tax Before Discount '),
(2, 'Apply Discount Before Tax'),
(3, 'Apply Discount Before Tax, Add Original Tax');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_discount_types`
--

CREATE TABLE IF NOT EXISTS `cart_discount_types` (
  `disc_type_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `disc_type` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`disc_type_id`),
  UNIQUE KEY `disc_type_id` (`disc_type_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Note: Do not alter the order or id''s of records in table.' AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `cart_discount_types`
--

INSERT INTO `cart_discount_types` (`disc_type_id`, `disc_type`) VALUES
(1, 'Item Discount'),
(2, 'Summary Discount'),
(3, 'Reward Voucher');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_locations`
--

CREATE TABLE IF NOT EXISTS `cart_locations` (
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
-- Volcado de datos para la tabla `cart_locations`
--

INSERT INTO `cart_locations` (`loc_id`, `loc_type_fk`, `loc_parent_fk`, `loc_ship_zone_fk`, `loc_tax_zone_fk`, `loc_name`, `loc_status`, `loc_ship_default`, `loc_tax_default`) VALUES
(4, 14, 0, 0, 0, 'Ecuador', 1, 1, 1),
(5, 14, 0, 0, 0, 'Colombia', 1, 0, 0),
(6, 19, 4, 4, 0, 'Pichincha', 1, 0, 0),
(7, 19, 4, 5, 0, 'Napo', 1, 0, 0),
(13, 20, 6, 0, 0, 'Quito', 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_location_type`
--

CREATE TABLE IF NOT EXISTS `cart_location_type` (
  `loc_type_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `loc_type_parent_fk` smallint(5) NOT NULL DEFAULT '0',
  `loc_type_name` varchar(50) NOT NULL DEFAULT '',
  `loc_type_temporary` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`loc_type_id`),
  UNIQUE KEY `loc_type_id` (`loc_type_id`),
  KEY `loc_type_parent_fk` (`loc_type_parent_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Volcado de datos para la tabla `cart_location_type`
--

INSERT INTO `cart_location_type` (`loc_type_id`, `loc_type_parent_fk`, `loc_type_name`, `loc_type_temporary`) VALUES
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
(24, 0, '', 1),
(25, 0, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_location_zones`
--

CREATE TABLE IF NOT EXISTS `cart_location_zones` (
  `lzone_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `lzone_name` varchar(50) NOT NULL DEFAULT '',
  `lzone_description` longtext NOT NULL,
  `lzone_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lzone_id`),
  UNIQUE KEY `lzone_id` (`lzone_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `cart_location_zones`
--

INSERT INTO `cart_location_zones` (`lzone_id`, `lzone_name`, `lzone_description`, `lzone_status`) VALUES
(1, 'Costa', '', 1),
(4, 'Sierra', '', 1),
(5, 'Amazonía', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_order_details`
--

CREATE TABLE IF NOT EXISTS `cart_order_details` (
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
-- Volcado de datos para la tabla `cart_order_details`
--

INSERT INTO `cart_order_details` (`ord_det_id`, `ord_det_order_number_fk`, `ord_det_cart_row_id`, `ord_det_item_fk`, `ord_det_item_name`, `ord_det_item_option`, `ord_det_quantity`, `ord_det_non_discount_quantity`, `ord_det_discount_quantity`, `ord_det_stock_quantity`, `ord_det_price`, `ord_det_price_total`, `ord_det_discount_price`, `ord_det_discount_price_total`, `ord_det_discount_description`, `ord_det_tax_rate`, `ord_det_tax`, `ord_det_tax_total`, `ord_det_shipping_rate`, `ord_det_weight`, `ord_det_weight_total`, `ord_det_reward_points`, `ord_det_reward_points_total`, `ord_det_status_message`, `ord_det_quantity_shipped`, `ord_det_quantity_cancelled`, `ord_det_shipped_date`) VALUES
(1, '00000001', 'e2ef524fbf3d9fe611d5a8e90fefdc9c', 97, 'tests', 'Colores: ', 1.00, 1.00, 0.00, 0.00, 120.00, 120.00, 120.00, 120.00, '', 20.0000, 20.00, 20.00, 0.00, 10.00, 10.00, 1200, 1200, '', 0.00, 0.00, '0000-00-00 00:00:00'),
(2, '00000001', 'ac627ab1ccbdb62ec96e702f07f6425b', 99, 'test1', 'Colores: ', 1.00, 1.00, 0.00, 0.00, 150.00, 150.00, 150.00, 150.00, '', 20.0000, 25.00, 25.00, 0.00, 5.00, 5.00, 1500, 1500, '', 0.00, 0.00, '0000-00-00 00:00:00'),
(3, '00000002', 'e2ef524fbf3d9fe611d5a8e90fefdc9c', 97, 'tests', 'Colores: ', 1.00, 1.00, 0.00, 0.00, 120.00, 120.00, 120.00, 120.00, '', 20.0000, 20.00, 20.00, 0.00, 10.00, 10.00, 1200, 1200, '', 0.00, 0.00, '0000-00-00 00:00:00'),
(4, '00000002', 'ac627ab1ccbdb62ec96e702f07f6425b', 99, 'test1', 'Colores: ', 1.00, 1.00, 0.00, 0.00, 150.00, 150.00, 150.00, 150.00, '', 20.0000, 25.00, 25.00, 0.00, 5.00, 5.00, 1500, 1500, '', 0.00, 0.00, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_order_status`
--

CREATE TABLE IF NOT EXISTS `cart_order_status` (
  `ord_status_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `ord_status_description` varchar(50) NOT NULL DEFAULT '',
  `ord_status_cancelled` tinyint(1) NOT NULL DEFAULT '0',
  `ord_status_save_default` tinyint(1) NOT NULL DEFAULT '0',
  `ord_status_resave_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ord_status_id`),
  KEY `ord_status_id` (`ord_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `cart_order_status`
--

INSERT INTO `cart_order_status` (`ord_status_id`, `ord_status_description`, `ord_status_cancelled`, `ord_status_save_default`, `ord_status_resave_default`) VALUES
(1, 'Esperando Pago', 0, 0, 0),
(2, 'Nuevo', 0, 1, 0),
(3, 'Procesando', 0, 0, 0),
(4, 'Completo', 0, 0, 0),
(5, 'Cancelado', 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_order_summary`
--

CREATE TABLE IF NOT EXISTS `cart_order_summary` (
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
-- Volcado de datos para la tabla `cart_order_summary`
--

INSERT INTO `cart_order_summary` (`ord_order_number`, `ord_cart_data_fk`, `ord_user_fk`, `ord_item_summary_total`, `ord_item_summary_savings_total`, `ord_shipping`, `ord_shipping_total`, `ord_item_shipping_total`, `ord_summary_discount_desc`, `ord_summary_savings_total`, `ord_savings_total`, `ord_surcharge_desc`, `ord_surcharge_total`, `ord_reward_voucher_desc`, `ord_reward_voucher_total`, `ord_tax_rate`, `ord_tax_total`, `ord_sub_total`, `ord_total`, `ord_total_rows`, `ord_total_items`, `ord_total_weight`, `ord_total_reward_points`, `ord_currency`, `ord_exchange_rate`, `ord_status`, `ord_date`, `ord_bill_name`, `ord_bill_company`, `ord_bill_address_01`, `ord_bill_address_02`, `ord_bill_city`, `ord_bill_state`, `ord_bill_post_code`, `ord_bill_country`, `ord_ship_name`, `ord_ship_company`, `ord_ship_address_01`, `ord_ship_address_02`, `ord_ship_city`, `ord_ship_state`, `ord_ship_post_code`, `ord_ship_country`, `ord_email`, `ord_phone`, `ord_comments`) VALUES
('00000001', 8, 1, 270.00, 0.00, '', 0.00, 270.00, '', 0.00, 0.00, '', 0.00, '', 0.00, '20', 45.00, 0.00, 270.00, 2, 2.00, 15.00, 2700, 'GBP', 1.0000, 2, '2014-05-12 13:04:55', 'Miguel', 'Dejabu', 'Shyris N43-69', 'Tomás de Berlanga', 'Quito', 3653224, 'EC171321', 3658394, 'Miguel', 'Dejabu', 'Shyris N43-69', 'Tomás de Berlanga', 'Quito', 3653224, 'EC171321', 3658394, 'miguel@dejabu.ec', '5932251526', ''),
('00000002', 9, 1, 270.00, 0.00, '', 0.00, 270.00, '', 0.00, 0.00, '', 0.00, '', 0.00, '20', 45.00, 0.00, 270.00, 2, 2.00, 15.00, 2700, 'GBP', 1.0000, 3, '2014-05-12 14:29:46', 'Miguel', 'Dejabu', 'Shyris N43-69', 'Tomás de Berlanga', 'Quito', 3653224, 'EC171321', 3658394, 'Miguel', 'Dejabu', 'Shyris N43-69', 'Tomás de Berlanga', 'Quito', 3653224, 'EC171321', 3658394, 'miguel@dejabu.ec', '5932251526', 'aaasas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_reward_points_converted`
--

CREATE TABLE IF NOT EXISTS `cart_reward_points_converted` (
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
-- Estructura de tabla para la tabla `cart_shipping_item_rules`
--

CREATE TABLE IF NOT EXISTS `cart_shipping_item_rules` (
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
-- Estructura de tabla para la tabla `cart_shipping_options`
--

CREATE TABLE IF NOT EXISTS `cart_shipping_options` (
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
-- Volcado de datos para la tabla `cart_shipping_options`
--

INSERT INTO `cart_shipping_options` (`ship_id`, `ship_name`, `ship_description`, `ship_location_fk`, `ship_zone_fk`, `ship_inc_sub_locations`, `ship_tax_rate`, `ship_discount_inclusion`, `ship_status`, `ship_default`, `ship_temporal`) VALUES
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
-- Estructura de tabla para la tabla `cart_shipping_rates`
--

CREATE TABLE IF NOT EXISTS `cart_shipping_rates` (
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
-- Volcado de datos para la tabla `cart_shipping_rates`
--

INSERT INTO `cart_shipping_rates` (`ship_rate_id`, `ship_rate_ship_fk`, `ship_rate_value`, `ship_rate_tare_wgt`, `ship_rate_min_wgt`, `ship_rate_max_wgt`, `ship_rate_min_value`, `ship_rate_max_value`, `ship_rate_status`) VALUES
(1, 1, 1.50, 0.00, 0.00, 9999.00, 0.00, 9999.00, 1),
(2, 1, 2.00, 0.00, 0.00, 9999.00, 0.00, 9999.00, 1),
(3, 1, 3.00, 0.00, 0.00, 9999.00, 0.00, 9999.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_tax`
--

CREATE TABLE IF NOT EXISTS `cart_tax` (
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
-- Volcado de datos para la tabla `cart_tax`
--

INSERT INTO `cart_tax` (`tax_id`, `tax_location_fk`, `tax_zone_fk`, `tax_name`, `tax_rate`, `tax_status`, `tax_default`) VALUES
(3, 4, 0, 'IVA', 12.0000, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_tax_item_rates`
--

CREATE TABLE IF NOT EXISTS `cart_tax_item_rates` (
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
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` tinyint(1) DEFAULT NULL,
  `group_visibility` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `tree`, `lft`, `rgt`, `css_class`, `enabled`, `private`, `image`, `url`, `data`, `temporary`, `popup`, `created_at`, `updated_at`, `deleted_at`, `group_visibility`) VALUES
(1, 1, 1, 12, NULL, 1, 0, NULL, NULL, NULL, 0, NULL, NULL, '2015-12-03 18:28:24', NULL, NULL),
(18, 1, 10, 11, '', 1, 0, '', '', '{"structure":[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[14],"modules":[]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[],"modules":[]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[]}]}]}', 0, NULL, '2014-12-19 22:59:27', '2015-12-03 18:33:07', NULL, 0),
(22, 1, 6, 7, '', 1, 0, NULL, NULL, '{"structure":[{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[19]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[]}]},{"class":"","expanded":0,"columns":[{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[]},{"class":"","span":{"large":"12","medium":"12","small":"12"},"offset":{"large":"0","medium":"0","small":"0"},"push":{"large":"0","medium":"0","small":"0"},"pull":{"large":"0","medium":"0","small":"0"},"widgets":[]}]}]}', 0, NULL, '2015-09-11 17:50:34', '2015-12-03 19:37:06', NULL, 0),
(23, 1, 8, 9, '', 1, 0, NULL, NULL, '{"structure":[]}', 0, NULL, '2015-09-11 17:53:09', '2015-12-03 18:28:24', NULL, 0);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(45) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`id`, `key`, `value`) VALUES
(1, 'site_name', 'FlexCMS'),
(2, 'index_page_id', '18'),
(3, 'theme', 'destiny'),
(4, 'environment', 'development'),
(5, 'debug_bar', '1'),
(6, 'facebook_app_id', '297589630375072'),
(7, 'facebook_app_secret', 'd8a3469a176c222335a1c9584d0e3578'),
(8, 'facebook_login', '0'),
(9, 'indent_html', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `contacts`
--

INSERT INTO `contacts` (`id`, `email`) VALUES
(3, 'asd@ass.com'),
(4, 'asd@asd.com'),
(5, 'asasss@asd.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `image` tinytext,
  `css_class` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `temporary` tinyint(1) DEFAULT '1',
  `important` tinyint(1) DEFAULT '0',
  `publication_start` datetime DEFAULT NULL,
  `publication_end` datetime DEFAULT NULL,
  `module` varchar(45) DEFAULT NULL,
  `data` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paginaId` (`category_id`),
  KEY `publicacionHabilitado` (`enabled`),
  KEY `paginaId_p` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `content`
--

INSERT INTO `content` (`id`, `created_at`, `image`, `css_class`, `category_id`, `enabled`, `temporary`, `important`, `publication_start`, `publication_end`, `module`, `data`) VALUES
(1, '0000-00-00 00:00:00', '', NULL, NULL, 1, 1, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fields`
--

CREATE TABLE IF NOT EXISTS `fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campoId` (`input_id`),
  KEY `inputId_bc_idx` (`input_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `fields`
--

INSERT INTO `fields` (`id`, `input_id`, `parent_id`, `position`, `css_class`, `section`, `name`, `label_enabled`, `required`, `validation`, `data`, `view_in`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 9, NULL, NULL, '33', 'slider', '12', 1, NULL, NULL, NULL, NULL, '2015-12-08 19:23:45', '2015-12-08 19:23:45', NULL),
(3, 9, NULL, 2, '3d', 'slider', '32', NULL, NULL, NULL, NULL, NULL, '2015-12-08 20:07:03', '2015-12-08 20:07:25', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `position` int(11) DEFAULT '0',
  `data` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `temporary` tinyint(1) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `descargaCategoriaId_d` (`parent_id`),
  KEY `descargaCategoriaId_idx` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_section_id` int(11) NOT NULL,
  `sufix` varchar(45) DEFAULT '_huge',
  `width` smallint(6) DEFAULT '500',
  `height` smallint(6) DEFAULT '300',
  `name` varchar(45) DEFAULT NULL,
  `position` tinyint(3) DEFAULT NULL,
  `crop` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `seccionId_i` (`image_section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `image_sections`
--

CREATE TABLE IF NOT EXISTS `image_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `section_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `adminSeccionId_imgr` (`section_id`) USING BTREE,
  KEY `adminSeccionId_is` (`section_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inputs`
--

CREATE TABLE IF NOT EXISTS `inputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET latin1 NOT NULL,
  `input_type_id` int(11) NOT NULL,
  `section` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT 'donde se mostrara el input contacto , producto o ambos',
  PRIMARY KEY (`id`),
  KEY `fk_input_contacto_inputs_rel1` (`id`),
  KEY `inputTipoId` (`input_type_id`),
  KEY `inputTipoId_i` (`input_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Volcado de datos para la tabla `inputs`
--

INSERT INTO `inputs` (`id`, `content`, `input_type_id`, `section`) VALUES
(8, 'numero', 1, 'contacto'),
(9, 'texto', 1, 'slider'),
(10, 'texto multilinea', 3, 'slider'),
(11, 'texto multilinea', 3, 'contacto'),
(12, 'texto multilinea', 3, 'producto'),
(13, 'texto', 1, 'contacto'),
(14, 'texto', 1, 'producto'),
(16, 'link', 1, 'producto'),
(17, 'link', 1, 'contacto'),
(18, 'tabla', 5, 'producto'),
(20, 'archivos', 7, 'producto'),
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_input_tipo_input1` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
('es', 'spanish');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maps`
--

CREATE TABLE IF NOT EXISTS `maps` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `temporary` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `coords` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `temporary` tinyint(1) DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mapaId_idx` (`map_id`),
  KEY `mapaId_mu` (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persistences`
--

CREATE TABLE IF NOT EXISTS `persistences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `fk_persistences_user_id_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

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
(15, 1, 'pNqSj0YvV3ILlVEbdZT3NgLZvz54A491', '2015-12-08 22:56:34', '2015-12-08 22:56:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `predefined_lists`
--

CREATE TABLE IF NOT EXISTS `predefined_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `css_class` varchar(45) DEFAULT NULL,
  `position` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productoCampoId_pclp_idx` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`id`),
  KEY `categoriaId_idx` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reminders`
--

CREATE TABLE IF NOT EXISTS `reminders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_reminders_user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permissions` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'Superadmin', NULL, '2015-11-26 15:05:41', '2015-11-26 20:05:41'),
(2, 'admin', 'Admin', NULL, '2015-11-26 18:24:01', '2015-11-26 23:24:01'),
(3, 'registered', 'Registered', NULL, '2015-11-26 18:24:18', '2015-11-26 23:24:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_sections`
--

CREATE TABLE IF NOT EXISTS `role_sections` (
  `role_id` int(10) unsigned NOT NULL,
  `section_name` varchar(45) NOT NULL,
  PRIMARY KEY (`role_id`,`section_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_users`
--

CREATE TABLE IF NOT EXISTS `role_users` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_users_role_id_idx` (`role_id`)
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
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT '800',
  `height` int(11) DEFAULT '600',
  `enabled` tinyint(1) DEFAULT '1',
  `temporary` tinyint(1) DEFAULT NULL,
  `config` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

--
-- Volcado de datos para la tabla `sliders`
--

INSERT INTO `sliders` (`id`, `name`, `class`, `type`, `width`, `height`, `enabled`, `temporary`, `config`, `created_at`, `updated_at`, `deleted_at`) VALUES
(12, 'Swiper', '', 'Swiper', 1024, 261, 1, 0, '{\n    "initialSlide": 0,\n    "direction": "horizontal",\n    "speed": 300,\n    "autoplay": 0,\n    "autoplayDisableOnInteraction": true,\n    "watchSlidesProgress": false,\n    "watchVisibility": false,\n    "freeMode": false,\n    "freeModeMomentum": true,\n    "freeModeMomentumRatio": 1,\n    "freeModeMomentumBounce": true,\n    "freeModeMomentumBounceRatio": 1,\n    "effect": "coverflow",\n    "cube": {\n        "slideShadows": 1,\n        "shadow": 1,\n        "shadowOffset": 20,\n        "shadowScale": 0.94\n    },\n    "coverflow": {\n        "rotate": 50,\n        "stretch": 0,\n        "depth": 100,\n        "modifier": 1,\n        "slideShadows": 1\n    },\n    "spaceBetween": 0,\n    "slidesPerView": 3,\n    "slidesPerColumn": 1,\n    "slidesPerColumnFill": "column",\n    "slidesPerGroup": 1,\n    "centeredSlides": false,\n    "grabCursor": false,\n    "touchRatio": 1,\n    "touchAngle": 45,\n    "simulateTouch": true,\n    "shortSwipes": true,\n    "longSwipes": true,\n    "longSwipesRatio": 0.5,\n    "longSwipesMs": 300,\n    "followFinger": true,\n    "onlyExternal": false,\n    "threshold": 0,\n    "touchMoveStopPropagation": true,\n    "resistance": true,\n    "resistanceRatio": 0.85,\n    "preventClicks": true,\n    "preventClicksPropagation": true,\n    "releaseFormElements": true,\n    "slideToClickedSlide": false,\n    "allowSwipeToPrev": true,\n    "allowSwipeToNext": true,\n    "noSwiping": true,\n    "noSwipingClass": "swiper-no-swiping",\n    "swipeHandler": null,\n    "pagination": null,\n    "paginationHide": true,\n    "paginationClickable": false,\n    "nextButton": null,\n    "prevButton": null,\n    "scrollbar": null,\n    "scrollbarHide": true,\n    "keyboardControl": false,\n    "mousewheelControl": false,\n    "mousewheelForceToAxis": false,\n    "hashnav": false,\n    "updateOnImagesReady": true,\n    "loop": false,\n    "loopAdditionalSlides": 0,\n    "loopedSlides": null,\n    "control": null,\n    "controlInverse": false,\n    "observer": false,\n    "observeParents": false,\n    "slideClass": "swiper-slide",\n    "slideActiveClass": "swiper-slide-active",\n    "slideVisibleClass": "swiper-slide-visible",\n    "slideDuplicateClass": "swiper-slide-duplicate",\n    "slideNextClass": "swiper-slide-next",\n    "slidePrevClass": "swiper-slide-prev",\n    "wrapperClass": "swiper-wrapper",\n    "bulletClass": "swiper-pagination-bullet",\n    "bulletActiveClass": "swiper-pagination-bullet-active",\n    "paginationHiddenClass": "swiper-pagination-hidden",\n    "buttonDisabledClass": "swiper-button-disabled"\n}', NULL, '2015-12-08 20:28:19', NULL),
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
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `estadisticaUserIP` (`ip`),
  KEY `estadisticaFecha` (`date`),
  KEY `paginaId_e_idx` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `throttle`
--

CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `language_id` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL COMMENT 'widget, content, field',
  `data` mediumtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_translations_1_idx` (`parent_id`),
  KEY `fk_translations_language_id_idx` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `translations`
--

INSERT INTO `translations` (`id`, `parent_id`, `language_id`, `type`, `data`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 15, 'es', 'page', '{"name":"index","menu_name":"menu index","meta_keywords":["meta","key","word","another word"],"meta_description":"","meta_title":""}', '0000-00-00 00:00:00', NULL, NULL),
(2, 18, 'es', 'page', '{"name":"Catalog","menu_name":"Catalog","meta_keywords":["meta","key","word","another word"],"meta_description":"","meta_title":""}', NULL, '2015-12-03 17:24:24', NULL),
(3, 22, 'es', 'page', '{"name":"Banner","menu_name":"menu index","meta_keywords":["meta","key","word","another word"],"meta_description":"","meta_title":""}', NULL, '2015-09-11 22:27:39', NULL),
(4, 23, 'es', 'page', '{"name":"Index","menu_name":"Index","meta_keywords":["meta","key","word","another word"],"meta_description":"","meta_title":""}', NULL, '2015-12-03 18:28:21', NULL),
(8, 28, 'es', NULL, NULL, '2015-09-11 22:06:54', '2015-09-11 22:06:54', NULL),
(12, 32, 'es', 'page', '{"name":"222","menu_name":"2222","meta_keywords":[""],"meta_description":"","meta_title":""}', '2015-09-11 22:18:19', '2015-09-11 22:18:35', NULL),
(13, 24, 'es', 'page', '{"name":"21222","menu_name":"21222","meta_keywords":[""],"meta_description":"","meta_title":""}', '2015-12-01 15:59:33', '2015-12-01 15:59:33', NULL),
(14, 2, 'es', 'field', '{"label":"222a"}', '2015-12-08 19:32:11', '2015-12-08 20:01:03', NULL),
(15, 3, 'es', 'field', '{"label":"3w"}', '2015-12-08 20:07:03', '2015-12-08 20:07:29', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permissions` text,
  `last_login` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `last_login`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
(1, 'miguel@dejabu.ec', '$2y$10$/.CrmFoCuOyJ9/4fZAgMeOeOvJjsobFyw0VBGz5QC/ge/6hloKV1e', NULL, '2015-12-08 22:56:34', 'Miguel', 'Suarez', '2015-11-25 22:41:58', '2015-12-08 22:56:34'),
(2, '', '', NULL, NULL, NULL, NULL, '2015-11-27 03:59:14', '2015-11-27 03:59:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `widgets`
--

CREATE TABLE IF NOT EXISTS `widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `view` varchar(45) DEFAULT 'default_view.php',
  `class` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `type` varchar(45) DEFAULT NULL,
  `data` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paginaId_m` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `widgets`
--

INSERT INTO `widgets` (`id`, `category_id`, `view`, `class`, `enabled`, `type`, `data`, `created_at`, `updated_at`, `deleted_at`) VALUES
(9, 22, 'default_view.php', NULL, 1, 'Content', NULL, '2015-12-02 00:36:20', '2015-12-02 00:36:20', NULL),
(10, 22, 'default_view.php', NULL, 1, 'Content', NULL, '2015-12-02 00:36:27', '2015-12-02 00:36:27', NULL),
(11, 22, 'default_view.php', NULL, 1, 'Content', NULL, '2015-12-02 00:36:31', '2015-12-02 00:36:31', NULL),
(14, 18, 'default_view.php', NULL, 1, 'Content', '{"content_type":"catalog"}', '2015-12-03 23:33:07', '2015-12-03 23:33:17', NULL),
(19, 22, 'default_view.php', NULL, 1, 'Content', 'null', '2015-12-04 00:30:16', '2015-12-04 00:30:16', NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activations`
--
ALTER TABLE `activations`
  ADD CONSTRAINT `fk_activations_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `calendar_activities`
--
ALTER TABLE `calendar_activities`
  ADD CONSTRAINT `fk_activities_placeId` FOREIGN KEY (`place_id`) REFERENCES `mapas_ubicaciones` (`mapaUbicacionId`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_calendar_id` FOREIGN KEY (`calendar_id`) REFERENCES `calendar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cart_discount_group_items`
--
ALTER TABLE `cart_discount_group_items`
  ADD CONSTRAINT `discount_group_fk_dg` FOREIGN KEY (`disc_group_item_group_fk`) REFERENCES `cart_discount_groups` (`disc_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `disc_group_item_item_fk_p` FOREIGN KEY (`disc_group_item_item_fk`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cart_locations`
--
ALTER TABLE `cart_locations`
  ADD CONSTRAINT `loc_type_fk_l` FOREIGN KEY (`loc_type_fk`) REFERENCES `cart_location_type` (`loc_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `fk_content_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `fields`
--
ALTER TABLE `fields`
  ADD CONSTRAINT `fk_fields_input_id` FOREIGN KEY (`input_id`) REFERENCES `inputs` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_image_section_id` FOREIGN KEY (`image_section_id`) REFERENCES `image_sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `image_sections`
--
ALTER TABLE `image_sections`
  ADD CONSTRAINT `fk_image_sections_section_id` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `inputs`
--
ALTER TABLE `inputs`
  ADD CONSTRAINT `fk_inputs_input_type_id` FOREIGN KEY (`input_type_id`) REFERENCES `input_type` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `map_locations`
--
ALTER TABLE `map_locations`
  ADD CONSTRAINT `fk_map_locations_map_id` FOREIGN KEY (`map_id`) REFERENCES `maps` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `persistences`
--
ALTER TABLE `persistences`
  ADD CONSTRAINT `fk_persistences_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `predefined_lists`
--
ALTER TABLE `predefined_lists`
  ADD CONSTRAINT `fk_predefined_lists_field_id` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `fk_reminders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `role_sections`
--
ALTER TABLE `role_sections`
  ADD CONSTRAINT `fk_role_sections_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `role_users`
--
ALTER TABLE `role_users`
  ADD CONSTRAINT `fk_role_users_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_role_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `stats`
--
ALTER TABLE `stats`
  ADD CONSTRAINT `fk_stats_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `fk_translations_language_id` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `widgets`
--
ALTER TABLE `widgets`
  ADD CONSTRAINT `fk_widgets_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
