<?php

 class UserTest extends PHPUnit_Framework_TestCase
 {
     private $CI;
     private $userdata;
     public function setUp()
     {
         // Load CI instance normally
         $this->CI = &get_instance();
     }
     public function testInsert()
     {
         
         $this->CI->load->model('user');
         $this->CI->load->helper('string');
         // Create Test User account
         $data['email']    = 'test@yopmail.com';
         $data['activation_code'] = random_string('alnum', 25);
         $data['active'] = 0;
         $this->assertEquals(true, $this->CI->user->register_user($data));

         //get User By Activation key.
         $this->userdata = $this->CI->user->get_user_by_activation_code($data['activation_code']);
         $this->assertObjectHasAttribute('email', $this->userdata);

         //get email as identity column
         $identity_column_value = $this->CI->user->get_identity_column_value('email',$this->userdata->id);
         $this->assertEquals($identity_column_value->email, $this->userdata->email);

         //delete test user
         $is_deleted = $this->CI->user->delete_user($this->userdata->id);
         $this->assertEquals(1, $is_deleted);

     }
 }