<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('money_format'))
{
	/**
	 * Money Format
	 *
	 * money_format() function is not available in windows
	 *
	 * @param	string	$format
	 * @param	string	$val
	 * @return	string
	 */
	function money_format($format, $val)
	{
		$CI =& get_instance();

		$fmt = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );
		return $fmt->formatCurrency($val, "USD");

	}

}

if ( ! function_exists('stripallslashes'))
{
	/**
	 * Remova all slaehs and backslashes from a string
	 *
	 * @param $string
	 * @return mixed
	 */
	function stripallslashes($string)
	{
		return str_replace(array('/', '\\'), '', $string);
	}

}