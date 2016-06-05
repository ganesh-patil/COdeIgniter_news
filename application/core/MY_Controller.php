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
        $data['is_logged_in'] = $this->is_logged_in();
        $view_html = $this->load->view('layout/default', $data,$returnhtml);
        if ($returnhtml) return $view_html;
        $this->output->enable_profiler(TRUE);
    }

    public function is_logged_in(){
        $this->load->library('session');
        if(!empty($this->session->userdata('logged_in'))) {
            return true;
        }
        return false;
    }

    public function get_logged_in_user_data(){
        $this->load->library('session');
        return $this->session->userdata('logged_in');
    }

}