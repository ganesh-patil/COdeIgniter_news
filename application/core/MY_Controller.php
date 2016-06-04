<?php

/**
 * Class MY_Controller
 * custom base controller 
 */

class MY_Controller extends CI_Controller {

    public function __construct()
    {

        parent::__construct();

    }

    public function load_view($data,$returnhtml=false)
    {
        $this->load->helper('html');
        $view_html = $this->load->view('layout'.DIRECTORY_SEPARATOR.'default', $data,$returnhtml);
        if ($returnhtml) return $view_html;
        $this->output->enable_profiler(TRUE);
    }

}