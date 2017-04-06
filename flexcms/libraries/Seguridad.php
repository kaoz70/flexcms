<?php use Cartalyst\Sentinel\Native\Facades\Sentinel;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seguridad
{
	
	public function init()
	{
		$this->checkAdmin();

		$CI =& get_instance();
		//Load the cache driver with a fallback option
		$CI->load->driver('cache', array(
			'adapter' => 'memcached',
			'backup' => 'file',
		));

		//Clean the cache every time user uses the backend
		//TODO: the proper place is to add this on every CRUD method on the admin: more than 300 :(
		$CI->cache->clean(); //Delete Object caches
		delete_files(APPPATH . 'cache/pages'); //Delete full page caches
		$CI->db->cache_delete_all(); //Delete Database caches

	}
	
	private function checkAdmin()
	{
		$CI =& get_instance();
		//$CI->load->library('auth/ion_auth');
        $CI->load->set_admin_theme();

		if (Sentinel::check())
		{
			if(Sentinel::inRole('admin') || Sentinel::inRole('superadmin'))
			{
				//ADMIN
			}
			else {
				redirect('admin/cpanel/login/1');
			}
				
		}
		else {
            if($CI->input->is_ajax_request()) {
	            $CI->load->view('admin/request/html', array('return' => '<script type="text/javascript">createLoginWindow(null);</script>'));
            } else {
	            $CI->load->view('admin/request/html', array('return' => '<p>Necesita estar logueado para ver este contenido!</p>'));
            }
			$CI->output->_display();
            exit;
		}
		
	}


}