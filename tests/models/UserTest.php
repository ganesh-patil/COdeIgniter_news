<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class UserTest extends PHPUnit_Framework_TestCase
 {
     private $CI;
     private $userdata;
     private $user_id;
     public function setUp()
     {
         // Load CI instance normally
         $this->CI = &get_instance();
         $this->CI->load->model('user');  //  load user model
         $this->CI->load->helper('string');  // load string helper.

     }
     public function test_user()
     {
         
         $activation_code = $this->create_user();
         // Create Test User account


         //get User By Activation key.

        $this->get_user_by_activation_code( $activation_code);
         //get email as identity column

         $this->identity_comlumn();

         $this->get_user_by_email();
         //get email as identity column

         $this->update_user();
         //delete test user

         $this->login_user();

         $this->email_exists();

         $this->delete();


     }

     private function create_user() {
         $data['email']    = 'test@yopmail.com';
         $data['activation_code'] = random_string('alnum', 25);
         $data['active'] = 0;
         $this->assertEquals(true, $this->CI->user->register_user($data));
         $this->user_id = $this->CI->user->db->insert_id();
         return $data['activation_code'];
     }

     private function get_user_by_activation_code($activation_code) {
         $this->userdata = $this->CI->user->get_user_by_activation_code($activation_code);
         $this->assertObjectHasAttribute('email', $this->userdata);
     }

     private function  identity_comlumn() {
         $identity_column_value = $this->CI->user->get_identity_column_value('email',$this->userdata->id);
         $this->assertEquals($identity_column_value->email, $this->userdata->email);
     }

     private function get_user_by_email() {
         $user_data = $this->CI->user->get_user_by_email($this->userdata->email);
         $this->assertEquals($user_data->email, $this->userdata->email);
     }

     private function update_user() {
         $data['first_name'] = 'Updated1 first_name';
         $is_updated = $this->CI->user->update_user_password($this->user_id,$data);
         $this->assertEquals(1, $is_updated);
     }

     private function  login_user() {
         $is_login = $this->CI->user->login($this->userdata->email,$this->userdata->password);  // check non-active user are able to login?
         $this->assertEquals(false, $is_login);
     }

     private function  email_exists() {
         $is_exists = $this->CI->user->is_email_exists($this->userdata->email);  //
         $this->assertEquals(1, $is_exists);
     }

     private function delete() {
         $is_deleted = $this->CI->user->delete_user($this->user_id);
         $this->assertEquals(1, $is_deleted);
     }
 }