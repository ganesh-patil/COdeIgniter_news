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
        $this->db->select(array('news.*','users.email','users.first_name','users.last_name'));
        $this->db->where('is_published',1);
        $this->db->join('users', 'users.id = news.user_id');
        $this->db->order_by('created', 'DESC');
        $query = $this->db->get('news');
        return $query->result();
    }

    public function get_news_by_user_id($user_id)
    {
//        $this->db->limit($limit);
        $this->db->select(array('news.*','users.email','users.first_name','users.last_name'));
        $this->db->where('news.user_id',$user_id);
        $this->db->join('users', 'users.id = news.user_id');
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
        $this->db->select(array('news.*','users.email','users.first_name','users.last_name'));
        $this->db->where('news.id',$news_id);
        $this->db->join('users', 'users.id = news.user_id');
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
    
    
    public function download($news_id) {
        $news_details = $this->get_news_by_id($news_id);
        if(empty($news_details)) {
            throw new Exception('News not exist');
        }
        $this->load->add_package_path(APPPATH.'third_party/tcpdf', FALSE);
        $this->load->library('TCPDF','tcpdf');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($news_details->email);
        $pdf->SetTitle($news_details->title);
        $pdf->SetSubject($news_details->title);
        $pdf->SetKeywords('News, PDF');

// set default header data
        $pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, $news_details->title, $news_details->first_name.' '.$news_details->last_name, array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

// ---------------------------------------------------------

// set default font subsetting mode
        $pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

// set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
        $image_url = '';
        if(!empty($news_details->image_url)) {
            $image_url = '<img src="'.base_url().'upload/'.$news_details->image_url.'"> <br><br>';
        }


        $html = <<<EOD
           $image_url
          $news_details->description
EOD;
// Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
        $name = str_replace(' ','_',$news_details->title);
        $name = trim($name,"_");
        $pdf->Output($name.'.pdf', 'I');
    }

    public function get_news_by_news_id($news_id){
        $this->db->limit(1);
        $this->db->where('news.id',$news_id);
        $query = $this->db->get('news');
        return $query->row();
    }


}