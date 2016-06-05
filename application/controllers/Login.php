<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(array('url','form'));

    }

    /**
     * User registration using email address
     */
    public function register() {
        if ($this->is_logged_in())
        {
            $this->session->set_flashdata('error', "You are already logged in ");
            redirect(base_url(), 'refresh');
        }
        $this->load->helper('string');
        $this->load->library(array('form_validation'));
        $this->load->model('user');
        $this->form_validation->set_rules('first_name', 'First name', 'required');
        $this->form_validation->set_rules('last_name', 'Last name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_unique_check');
        if ($this->form_validation->run() == true)
        {
            $data['email']    = strtolower($this->input->post('email'));
            $data['first_name']  = $this->input->post('first_name');
            $data['last_name']   = $this->input->post('last_name');
            $data['activation_code'] = random_string('alnum', 25);
            $data['active'] = 0;

            if ($this->user->register_user($data))
            {
                if($this->send_email($data['email'],$data['activation_code'], $data['first_name'],$data['last_name'])){
                    $this->session->set_flashdata('success', "Registration done,Please check your inbox for verification link.");
                }
                else {

                    $this->session->set_flashdata('error', "Error while sending verification email. please change your password for verification.");
                }
                redirect(base_url());
            }
            else{
                $this->session->set_flashdata('error', "Error while registration. please try again");
            }
        }
        $data['partial'] = 'login/register';
        $this->load_view($data);
    }


    public function email_unique_check($email) {
        $this->load->model('user');
        if ($this->user->is_email_exists($email))
        {
            $this->form_validation->set_message('email_unique_check', 'The {field}  already exists.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }

    }

    /**
     * Verify user by email address
     */
    public function verification() {
        $this->load->library(array('form_validation'));
        $activation_code = $this->uri->segment(2);
        if ($this->is_logged_in())
        {
            $this->session->set_flashdata('error', "You are already logged in ");
            redirect(base_url(), 'refresh');
        }
        $this->load->model('user');
        $user_data = $this->user->get_user_by_activation_code($activation_code);
        if(empty($user_data)){
            $this->session->set_flashdata('error', $this->lang->line('invalid_link'));
            redirect(base_url(), 'refresh');
        }
        $this->form_validation->set_rules('password', 'Please enter valid password', 'required|min_length[5]|max_length[10]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'please enter valid confirm passsword', 'required');

        if ($this->form_validation->run() == true) {
            $this->load->model('user');
            $data['password']    = md5($this->input->post('password'));

            $data['activation_code'] = '';
            $data['active'] = 1;
            $this->user->update_user_password($this->input->post('id'),$data);
            $this->session->set_flashdata('success', "Password saved successfully. please login to your account.");
            redirect(base_url('login'));
        }
        $data['id'] =  $user_data->id;
        $data['partial'] = 'login/create_user';
        $this->load_view($data);

    }

    /**
     * Mail login method
     */
     public function index() {
         if ($this->is_logged_in())
         {
             $this->session->set_flashdata('error', "You are already logged in ");
             redirect(base_url(), 'refresh');
         }
         $this->load->model('user');
         $this->load->library('form_validation');

         $this->form_validation->set_rules('email', 'email', 'trim|required');
         $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
         $this->form_validation->set_message('xss_clean', 'Invalid email or password');

         if($this->form_validation->run() == FALSE)
         {
             //Field validation failed.  User redirected to login page
             $data['partial'] = 'login/login';
             $this->load_view($data);
         }
         else
         {
             //Go to private area
             $this->session->set_flashdata('success', "logged in sucessfully..");
             redirect(base_url('my_news'), 'refresh');
         }
     }

    /**
     * Logout method
     */
    public function logout()
    {
        if (!$this->is_logged_in())
        {
            $this->session->set_flashdata('error', "You are not logged in ");
            redirect(base_url(), 'refresh');
        }
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect(base_url('login'), 'refresh');
    }

    /** check_database method : check user credentials and login
     * @param $password
     * @return bool
     */

    /**
     * User registration using email address
     */
    public function forgot_password() {
        if ($this->is_logged_in())
        {
            $this->session->set_flashdata('error', "You are already logged in ");
            redirect(base_url(), 'refresh');
        }
        $this->load->helper('string');
        $this->load->library(array('form_validation'));
        $this->load->model('user');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        if ($this->form_validation->run() == true)
        {
            $data['email']    = strtolower($this->input->post('email'));
            $data['activation_code'] = random_string('alnum', 25);
            $user = $this->user->get_user_by_email($data['email']);
            if(empty($user)) {
                $this->session->set_flashdata('error', "Email not exist.please register");
                redirect(base_url());
            }
            if ($this->user->update_user_password($user->id, $data))
            {
                if($this->send_email($data['email'],$data['activation_code'], $user->first_name,$user->last_name,true)){
                    $this->session->set_flashdata('success', "password change link sent. please check inbox.");
                }
                else {

                    $this->session->set_flashdata('error', "Error while sending verification email. please change your password for verification.");
                }
                redirect(base_url());
            }
            else{
                $this->session->set_flashdata('error', "Error while password change. please try again");
            }
        }
        $data['partial'] = 'login/forgot_password';
        $this->load_view($data);
    }
    public function check_database($password)
    {
        //Field validation succeeded.  Validate against database
        $username = $this->input->post('email');

        //query the database
        $result = $this->user->login($username, $password);

        if($result)
        {
            $sess_array = array();
            foreach($result as $row)
            {
                $sess_array = array(
                    'id' => $row->id,
                    'email' => $row->email
                );
                $this->session->set_userdata('logged_in', $sess_array);
            }
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('check_database', 'Invalid email or password');
            return false;
        }
    }

    private function send_email($email,$activation_code,$first_name, $last_name,$forgot_password = false){
        $this->load->add_package_path(APPPATH.'third_party/phpmailer', FALSE);
        $this->load->library('PHPMailer','phpmailer');
        $this->config->load('app_config');

        if($forgot_password) {
            $subject = 'News Portal password change';
            $body = "Hello $first_name  $last_name,<br>  <br> Please <a href='".base_url().'verification/'.$activation_code."' > Click Here</a> to change your password. <br>  Thank you  ";
        }
        else {
            $subject = 'News Portal Email  Verification';
            $body = "Hello $first_name  $last_name,<br> Thank you for your registration. <br> Please <a href='".base_url().'verification/'.$activation_code."' > Click Here</a> to vetify your email address. <br>  Thank you  ";
        }



        //using SMTP mail service.
        $this->phpmailer->isSMTP();                                      // Set mailer to use SMTP
        $this->phpmailer->Host =  $this->config->item('smtp_host');  // Specify main and backup SMTP servers
        $this->phpmailer->SMTPAuth = true;                               // Enable SMTP authentication
        $this->phpmailer->Username =  $this->config->item('smpt_username');                 // SMTP username
        $this->phpmailer->Password =  $this->config->item('smpt_password');                           // SMTP password
        $this->phpmailer->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->phpmailer->Port = $this->config->item('smpt_port');
        $this->phpmailer->From =  $this->config->item('from_email');
        $this->phpmailer->FromName =  $this->config->item('from_name');
        $this->phpmailer->IsHTML(true);
        $this->phpmailer->Subject = $subject;
        $this->phpmailer->Body = $body;
        $this->phpmailer->AddAddress($email);

        return $this->phpmailer->Send();
    }
    
}