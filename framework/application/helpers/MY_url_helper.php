<?php

if ( ! function_exists('safe_mailto'))
{

	/**
	 * Generic str_rot function
	 * http://php.net/manual/en/function.str-rot13.php
	 * @param $s
	 * @param int $n
	 * @return string
	 */
	function str_rot($s, $n = 13) {
		static $letters = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
		$n = (int)$n % 26;
		if (!$n) return $s;
		if ($n < 0) $n += 26;
		if ($n == 13) return str_rot13($s);
		$rep = substr($letters, $n * 2) . substr($letters, 0, $n * 2);
		return strtr($s, $letters, $rep);
	}

	/**
	 * Encoded Mailto Link
	 *
	 * Create a spam-protected mailto link written in Javascript
	 *
	 * @param	string	the email address
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function safe_mailto($email, $attributes = '')
	{

		if ($attributes !== '')
		{
			$attributes = _stringify_attributes($attributes);
		}

		$string = '<a href=\"mailto:' . $email . '\"' . addslashes($attributes) . '>' . $email . '</a>';
		$obfuscated_tag =  str_rot($string);

		$em_id = 'oe_' . mt_rand();

		$output = '<span id="' . $em_id . '"></span><script type="text/javascript">
			document.getElementById("' . $em_id . '").innerHTML = "' . $obfuscated_tag . '".replace(/[a-zA-Z]/g,
			function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);});
		</script>';

		return $output;

	}
}