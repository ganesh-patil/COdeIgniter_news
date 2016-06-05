<?php
class News_model extends CI_Model {

    public function __construct()
    {
        // Call the CI_Model constructor
        $this->load->database();
        parent::__construct();
    }

    public function get_latest_news($limit)
    {
        $this->db->limit($limit);
        $this->db->order_by('created', 'DESC');
        $query = $this->db->get('news');
        return $query->result();
    }

    public function get_news_by_user_id($user_id)
    {
//        $this->db->limit($limit);
        $this->db->where('user_id',$user_id);
        $this->db->order_by('created', 'DESC');
        $query = $this->db->get('news');
        return $query->result();
    }
    public function insert_entry($data)
    {
            $data['created'] = date("Y-m-d H:i:s");
        return $this->db->insert('news', $data);
    }

    public function get_news_by_id($news_id){
        $this->db->limit(1);
        $this->db->where('id',$news_id);
        $query = $this->db->get('news');
        return $query->row();
    }

    public function delete_news($id){
        $this->db->where('id', $id);
        $this->db->delete('news');
        return $this->db->affected_rows();
    }

    public function check_is_valid_data(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('description', 'description', 'required');
        if ($this->form_validation->run() == TRUE)
        {
            return true;
        }
        return false;
    }

    /**
     * upload imgae
     * @return bool
     */
    public  function upload_news_image() {
        if (!(isset($_FILES['image_url']) && is_uploaded_file($_FILES['image_url']['tmp_name']))) {

            return true;  // User has not seleted file.
        }
        $this->config->load('app_config');
        $config['upload_path']          = $this->config->item('upload_path');
        $config['allowed_types']        = $this->config->item('allowed_types');
        $config['max_size']             = $this->config->item('max_size');
        $config['max_width']            = $this->config->item('max_width');
        $config['max_height']           = $this->config->item('max_height');
        $config['min_width']            = $this->config->item('min_width');
        $config['min_height']           = $this->config->item('min_height');

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('image_url'))
        {
            $error = array('error' => $this->upload->display_errors());  // add in to log
            return false;

        }
        else {
            if($this->image_resize($config['upload_path'].DIRECTORY_SEPARATOR.$this->upload->data('file_name'))){
                $path = $this->upload->data('file_name');
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file = basename($path, ".".$ext);
                $news_data['thumbnail_url']  = $file.'_thumb.'.$ext;
            }
            else{
                $news_data['thumbnail_url'] =  $this->upload->data('file_name');
            }
            $news_data['image_url'] =  $this->upload->data('file_name');

            return $news_data;
        }
    }

    /**
     * image resize
     * @param $filepath
     * @return bool
     */

    public function image_resize($filepath){
        $config['image_library'] = 'gd2';
        $config['source_image'] = $filepath;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 300;
        $config['height']       = 200;
        $this->load->library('image_lib', $config);
        if ( ! $this->image_lib->resize())
        {
            return false;
        }
        return true;
    }

}