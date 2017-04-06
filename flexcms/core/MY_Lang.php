<?php
/* load the MX_Lang class */
require APPPATH."third_party/MX/Lang.php";

class MY_Lang extends MX_Lang {

	/**
	 * Language line
	 *
	 * Fetches a single line of text from the language array
	 *
	 * @param	string	$line		Language line key
	 * @param	string	$swap	    Swap the variable for this one
	 * @return	string	Translation
	 */
	public function lineParam($line, $swap)
	{
		$value = isset($this->language[$line]) ? $this->language[$line] : FALSE;

		if ($value === FALSE)
		{
			log_message('error', 'Could not find the language line "'.$line.'"');
		}

		return str_replace('%s', $swap, $value);
	}

}