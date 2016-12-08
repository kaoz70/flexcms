<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends AdminController {

    /**
     * Gets the view from a module
     *
     * @param $module
     * @param $view
     */
    public function show($module, $view)
    {

        //Sending a folder segment also
        if($v = $this->uri->segment(6)) {
            $this->load->view("$module/$view/$v");
        } else {
            $this->load->view("$module/$view");
        }

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/View.php */