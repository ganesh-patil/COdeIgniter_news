<?php

class NewsTest extends PHPUnit_Framework_TestCase
{
    private $CI;
    private $news;
    public function setUp()
    {
        // Load CI instance normally
        $this->CI = &get_instance();
    }
    public function testAdd()
    {
        $this->CI->load->model('news_model');
     //   $this->CI->load->helper('string');
        // Create Test User account
        $postData['title'] = 'Unit Test Case test News ';
        $postData['description'] = 'This is Unit test case news ';
        $postData['is_published'] = 0;

        $this->assertEquals(true, $this->CI->news_model->insert_entry($postData));
        $insert_id = $this->CI->news_model->db->insert_id();

         // check for returned news
        $this->news = $this->CI->news_model->get_news_by_id($insert_id);
        $this->assertObjectHasAttribute('title', $this->news);

        //delete test news
        $is_deleted = $this->CI->news_model->delete_news($this->news->id);
        $this->assertEquals(1, $is_deleted);



    }
}