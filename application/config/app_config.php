<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//File Upload parameters

$config['allowed_types']        = 'gif|jpg|png';
$config['max_size']             = 500;
$config['max_width']            = 1200;
$config['max_height']           = 600;

$config['min_width']            = 300;
$config['min_height']           = 200;


//email configurations 
$config['phpmailer_host']       = 'smtp';  //  provide options 'localhost'  or 'smtp' . if localhost then local php mailer is used . for smtp smtp service is used.
$config['smtp_host']            = 'smtp.gmail.com';
$config['smpt_username']        = 'patil.ganesh170@gmail.com';
$config['smpt_password']             = 'December@2015';
$config['smpt_port']            = '587';
$config['from_email']           = 'patil.ganesh170@gmail.com';

$config['from_name']            = 'News Portal';
