<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }


    /**
     * Index method - list latest 10 news
     * 
     */
    public function index()
    {
        $this->load->helper('url');
        $data['partial'] = 'news_listing';
        $this->load_view($data);
    }
}
