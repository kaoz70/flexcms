<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('theme_url'))
{
	/**
	 * Theme URL
	 *
	 * Create a local URL based on your basepath.
	 * Segments can be passed in as a string or an array, same as site_url
	 * or a URL to a file can be passed in, e.g. to an image file.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function theme_url($uri = '', $protocol = NULL)
	{
		$CI =& get_instance();

		if(isset($CI->m_config)) {
			return $CI->config->base_url('themes/' . $CI->m_config->theme . '/' . $uri, $protocol);
		} else {
			return $CI->config->base_url('themes/default/' . $uri, $protocol);
		}

	}

}

/**
 * Renders the structure
 * @return mixed
 */
function render_content()
{
	return Html::get_instance()->m_views_html;
}