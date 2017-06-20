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