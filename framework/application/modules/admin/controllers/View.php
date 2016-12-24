<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends AdminController {

    /**
     * Gets the view from a module
     *
     * @param $module
     * @param $view
     */
    public function module($module, $view)
    {

        //Sending a folder segment also
        if($v = $this->uri->segment(6)) {
            $this->load->view("$module/$view/$v");
        } else {
            $this->load->view("$module/$view");
        }

    }

    public function widget($widget, $v, $view)
    {
        $this->load->view("$widget/$v/$view");
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/View.php */