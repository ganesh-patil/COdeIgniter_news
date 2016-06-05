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
    public function register_user($data) {

        return $this->db->insert('users', $data);
    }

    public function update_user_password($user_id,$data) {
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
    }

    function login($username, $password) {
        $this -> db -> select('id, email, password');
        $this -> db -> from('users');
        $this -> db -> where('email', $username);
        $this -> db -> where('password', MD5($password));
        $this -> db -> where('active', 1);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1) {
            return $query->result();
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





}