<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class NewsTest extends PHPUnit_Framework_TestCase
{
    private $CI;
    private $news;
    private $news_id;
    public function setUp()
    {
        // Load CI instance normally
        $this->CI = &get_instance();
        $this->CI->load->model('news_model');

    }

    public function test_news() {

        
        // All test cases are depend on data so executing in order.
        $this->create_news();   // create news test case

        $this->get_news();      // Fetch news test case.

        $this->delete_news();   // Delete news test case . deleting news which are created for test cases.

    }

    /**
     * Test case for check fetch news by limit method
     */
    public function test_latest_news() {
        $limit= 5;
        $news = $this->CI->news_model->get_latest_news($limit);
        $this->assertLessThanOrEqual(count($news),$limit);
    }

    /**
     * Create News test case
     */
    private function create_news() {
        $postData['title'] = 'Unit Test Case test News ';
        $postData['description'] = 'This is Unit test case news ';
        $postData['is_published'] = 0;

        $this->assertEquals(true, $this->CI->news_model->insert_entry($postData));
        $this->news_id = $this->CI->news_model->db->insert_id();

    }

    /**
     * get news test case
     */
    private function get_news() {
        $this->news = $this->CI->news_model->get_news_by_news_id($this->news_id);
        $this->assertObjectHasAttribute('title', $this->news);
    }

    /**
     * Delete News Test case
     */
    private  function  delete_news() {
        $is_deleted = $this->CI->news_model->delete_news($this->news->id);
        $this->assertEquals(1, $is_deleted);
    }



}