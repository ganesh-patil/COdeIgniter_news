<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    function __construct()
    {
        parent::__construct();   // parent constructor call
        $this->load->library('session');
        $this->load->helper(array('url','form'));

    }


    /**
     * Main login method
     */
    public function index() {
        if ($this->is_logged_in())       //check if user is already logged in
        {
            $this->session->set_flashdata('error', "You are already logged in ");
            redirect(base_url(), 'refresh');
        }
        $this->load->model('user');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');

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
     * User registration using email address
     */
    public function register() {
        if ($this->is_logged_in())               //check User is already logged in
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
            $data['email']           = strtolower($this->input->post('email'));
            $data['first_name']      = $this->input->post('first_name');
            $data['last_name']       = $this->input->post('last_name');
            $data['activation_code'] = random_string('alnum', 25);  //generate totan for activation code.
            $data['active']          = 0;                           //set user as inactive initialy

            if ($this->user->register_user($data))                 // register user
            {
                // send email if user registered successfully
                if($this->user->send_email($data['email'],$data['activation_code'], $data['first_name'],$data['last_name'])){
                    $this->session->set_flashdata('success', "Registration complete,Please check your inbox for verification link.");
                }
                else {
                    // If email is not sent , inform user so that he can get verification code on change password.
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

    /**
     * email_unique_check Check email is unique or not
     * callback method for  email validation
     * @param $email
     * @return bool
     */

    public function email_unique_check($email) {
        $this->load->model('user');
        if ($this->user->is_email_exists($email))
        {
            $this->form_validation->set_message('email_unique_check', 'The {field}  already exists.');  // set validation rule if already exists
            return FALSE;
        }
        else
        {
            return TRUE;
        }

    }

    /**
     * Verify user by email address
     * this method is called with activation code is url segment.
     */
    public function verification() {
        $this->load->library(array('form_validation'));
        $activation_code = $this->uri->segment(2);
        if ($this->is_logged_in())                           // check if user is already logged in
        {
            $this->session->set_flashdata('error', "You are already logged in ");
            redirect(base_url(), 'refresh');
        }
        $this->load->model('user');
        $user_data = $this->user->get_user_by_activation_code($activation_code);  // check for activation code is valid or not.
        if(empty($user_data)) {
            $this->session->set_flashdata('error', $this->lang->line('Invalid link'));
            redirect(base_url(), 'refresh');
        }
        $this->form_validation->set_rules('password', 'Please enter valid password', 'required|min_length[5]|max_length[10]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'please enter valid confirm passsword', 'required');
        if ($this->form_validation->run() == true) {
            $this->load->model('user');
            $data['password']    = password_hash($this->input->post('password'), PASSWORD_DEFAULT);        //hash password before save.
            $data['activation_code'] = '';                                   // set activation code to blank so that user will not use it again.
            $data['active'] = 1;           // activate the user
            $this->user->update_user_password($user_data->id,$data);
            $this->session->set_flashdata('success', "Password saved successfully. please login to your account.");
            redirect(base_url('login'));
        }
        $data['partial'] = 'login/create_user';
        $this->load_view($data);

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

    /**
     *Forgot Password functionality
     */
    public function forgot_password() {
        if ($this->is_logged_in())            // check if user is already logged in.
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
            $user = $this->user->get_user_by_email($data['email']);            // check user exis or not in system
            if(empty($user)) {
                $this->session->set_flashdata('error', "Email not exist.please register");
                redirect(base_url());
            }
            if ($this->user->update_user_password($user->id, $data))
            {
                if($this->user->send_email($data['email'],$data['activation_code'], $user->first_name,$user->last_name,true)){
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

    /** check_database method : check user credentials and login
     * @param $password
     * @return bool
     */
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
}