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
        $this->load->helper(array('url','date','text'));
        $this->load->model('news_model');
        $data['news'] = $this->news_model->get_latest_news(10);
        $data['partial'] = 'news_listing';

        $this->load_view($data);
    }

    /**
     * View method - Show news Details 
     *
     */
    public function view()
    {
        $this->load->helper(array('url','date'));
        $this->load->model('news_model');
        $news_id = $this->uri->segment(2);
        $data['news'] = $this->news_model->get_news_by_id($news_id);
        if(empty($data['news'])){
            show_404();
        }
        $data['partial'] = 'news_details';
        $this->load_view($data);
    }

    /**
     * Index method - list latest 10 news
     *
     */
    public function my_news()
    {
        $this->load->helper(array('url','date','text'));
        if(!$this->is_user_logged_in){
            $this->session->set_flashdata('error', 'Please login to access this functionality');
            redirect(base_url('login'));
        }

        $this->load->model('news_model');

        /**
         * <@todo : Add Pagination here>
         */
        $data['news'] = $this->news_model->get_news_by_user_id($this->logged_in_user_id);
        $data['partial'] = 'news_listing';
        $this->load_view($data);
    }
    /**
     * add method - Create news
     *
     */
    public function add()
    {
        $this->load->helper(array('url','form'));
        if(!$this->is_user_logged_in){
            $this->session->set_flashdata('error', 'Please login to access this functionality');
            redirect(base_url('login'));
        }


        if($this->input->method() == 'post') {
            $this->load->model('news_model');
            if($this->news_model->check_is_valid_data()){
                $news_data = $this->get_post_input_data();
                if($this->is_user_logged_in){
                    $news_data['user_id'] = $this->logged_in_user_id;
                }
                $image_uploaded_data = $this->news_model->upload_news_image();
                if($image_uploaded_data){
                    if(is_array($image_uploaded_data)){
                        $news_data['image_url'] =  $image_uploaded_data['image_url'];
                        $news_data['thumbnail_url'] =  $image_uploaded_data['thumbnail_url'];
                    }
                    if($this->news_model->insert_entry($news_data)){
                        $this->session->set_flashdata('success', 'News published successfully.');
                        redirect(base_url());
                    }
                }
                else {
                    $this->session->set_flashdata('error', 'Image not uploaded ... please try again');
                }

            }
        }
        $data['partial'] = 'news_add';
        $this->load_view($data);
    }


    /**
     * delete method - Show news Details
     *
     */
    public function delete()
    {

        /**
         * <@todo : please check current users record>
         */
        $this->load->helper(array('url'));
        if(!$this->is_user_logged_in){
            $this->session->set_flashdata('error', 'Please login to access this functionality');
            redirect(base_url('login'));
        }

        $this->load->model('news_model');
        $news_id = $this->uri->segment(3);

        $news_details = $this->news_model->get_news_by_id($news_id);
        if(empty($news_details)) {
            $this->session->set_flashdata('error', 'Invalid News ');
            redirect(base_url());
        }
        if($this->logged_in_user_id != $news_details->user_id ) {
            $this->session->set_flashdata('error', 'You are not authorised to perform this operation. ');
            redirect(base_url());
        }
        if($this->news_model->delete_news($news_id)){
            $this->session->set_flashdata('success', 'News deleted successfully.');
        }
        else {
            $this->session->set_flashdata('error', 'Delete Error .. Please try again');
        }
        redirect(base_url());
    }

    private function get_post_input_data(){
        $postData['title'] = $this->input->post('title','');
        $postData['description'] = $this->input->post('description','');
        $postData['is_published'] = 1; // by default news is published
        return $postData;
    }

    /**
     * Rss feeds of lates news
     */
    public function rss_feeds() {
        $this->load->helper(array('url','date','text'));
        $this->load->model('news_model');
        $data['news'] = $this->news_model->get_latest_news(10);
        $data['upload_dir'] =  'upload/';
        $this->load->view('rss_feeds',$data);
    }

    public function download() {
        try{
            $this->load->model('news_model');
            $this->load->helper(array('url'));
            $news_id = $this->uri->segment(3);
            $this->news_model->download($news_id);
        }
        catch(Exception $e){
            $this->session->set_flashdata('error', 'Unable to download news');
        }

    }
}
