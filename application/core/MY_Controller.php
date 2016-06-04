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

    public function load_view($data)
    {
        $this->load->helper('html');
        $this->load->view('layout'.DIRECTORY_SEPARATOR.'default', $data);
        $this->output->enable_profiler(TRUE);
    }

}