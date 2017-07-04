<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function show_my_404($page, $theme) {

	log_message('error', '404 Page Not Found : '.$page);

	$CI =& get_instance();
	$CI->output->set_status_header('404');
	$data['theme_path'] = base_url('themes/' . $theme);
	$CI->load->view('errors/error_404', $data);
	echo $CI->output->get_output();
	exit(4); // EXIT_UNKNOWN_FILE

}

/**
 * If running nginx, implement getallheaders ourself.
 *
 * Code is taken from http://php.net/manual/en/function.getallheaders.php
 */
if (!function_exists('getallheaders')) {
    function getallheaders() {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}