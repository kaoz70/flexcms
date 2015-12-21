<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CMS_General extends CI_Controller
{

    function __construct()
    {
        //parent::__construct();
        $CI =& get_instance();
        $CI->load->helper('text');
        $CI->load->model('admin/general_model', 'General');

        $CI->load->set_admin_theme();

    }

    /**
     * Format the error messages
     * @param $message
     * @param $error
     * @return stdClass
     */
    public function error($message, $error)
    {
        $response = new stdClass();
        $response->message = $message;
        $response->error_code = $error->getCode();
        $response->error_message = $error->getMessage();
        return $response;
    }

    public function generarId($tabla) {
        $CI =& get_instance();
        return $CI->General->generarId($tabla);
    }

    public function generateSafeUrl($string) {
        $string = convert_accented_characters($string);
        return url_title($string, '-', TRUE);
    }

    public function lockTable($table, $columnName, $resourceId, $userId){
        $CI =& get_instance();
        $CI->General->lockTable($table, $columnName, $resourceId, $userId);
    }

}