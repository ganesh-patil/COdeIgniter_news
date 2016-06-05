<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Model {

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }

    public function get_user_by_activation_code($activation_code) {
        $this->db->where('activation_code', $activation_code);
        $query = $this->db->get('users', 1);
        return $query->row();
    }

    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users', 1);
        return $query->row();
    }
    public function register_user($data) {

        return $this->db->insert('users', $data);
    }

    public function update_user_password($user_id,$data) {
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
        return $this->db->affected_rows();
    }

    function login($username, $password) {
        $this -> db -> select('id, email, password');
        $this -> db -> from('users');
        $this -> db -> where('email', $username);
//        $this -> db -> where('password', MD5($password));
        $this -> db -> where('active', 1);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1) {
            $user =  $query->result();
            $hash =  $user[0]->password;
            if(password_verify($password, $hash)) {
                return $user;
            }
        }
        else {
            return false;
        }
    }

    public function get_identity_column_value($column,$id) {
          $result  = $this->db->select(array($column,'password'))
              ->where('id', $id)
              ->limit(1)
              ->get('users')->row();
          if(!empty($result)){
              return $result;
          }
          return'';
    }

    public function is_email_exists($email) {
        $this->db->select('email');
        $this->db->where('email', $email);
        $result = $this->db->get('users', 1)->row();
        return $this->db->affected_rows();
    }

    public function delete_user($id){
        $this->db->where('id', $id);
        $this->db->delete('users');
        return $this->db->affected_rows();
    }

    /**
     * send_email using PHPmailer.
     * @param $email
     * @param $activation_code
     * @param $first_name
     * @param $last_name
     * @param bool $forgot_password
     * @return mixed
     */
    public function send_email($email,$activation_code,$first_name, $last_name,$forgot_password = false) {
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

        if($this->config->item('phpmailer_host') == 'localhost') {
            $this->phpmailer->isMail();                             //use php mail funciton
        }
        else {
            //using SMTP mail service.
            $this->phpmailer->isSMTP();                                      // Set mailer to use SMTP
            $this->phpmailer->Host =  $this->config->item('smtp_host');  // Specify main and backup SMTP servers
            $this->phpmailer->SMTPAuth = true;                               // Enable SMTP authentication
            $this->phpmailer->Username =  $this->config->item('smpt_username');                 // SMTP username
            $this->phpmailer->Password =  $this->config->item('smpt_password');                           // SMTP password
            $this->phpmailer->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $this->phpmailer->Port = $this->config->item('smpt_port');
        }
        $this->phpmailer->From =  $this->config->item('from_email');
        $this->phpmailer->FromName =  $this->config->item('from_name');
        $this->phpmailer->IsHTML(true);
        $this->phpmailer->Subject = $subject;
        $this->phpmailer->Body = $body;
        $this->phpmailer->AddAddress($email);

        return $this->phpmailer->Send();
    }





}