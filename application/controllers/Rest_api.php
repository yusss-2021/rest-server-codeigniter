<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rest_api extends CI_Controller
{
    public $url_client = "http://localhost:8080";

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_News', 'news');
    }

    function index()
    {
        $methode_req = $_SERVER['REQUEST_METHOD'];
        if ($methode_req === 'GET') {
            $this->get();
        } else {
            $this->method_not_found();
        }
    }
    // get all data
    private function get()
    {
        $all_news  = $this->news->get_data()->result();
        $carousel = $this->news->get_carousel()->result();

        $data = array(
            'carousel' => $carousel,
            'all_news' => $all_news

        );
        $this->output
            ->set_status_header(200)
            ->set_header("Access-Control-Allow-Origin:$this->url_client")
            ->set_content_type('application/json')
            ->set_output(json_encode($data))
            ->_display();
        exit();
    }
    // detail news
    function detail($param)
    {

        $data_req = array(
            'title' => urldecode($param)
        );
        $data = $this->news->get_id($data_req)->row();
        if ($data !== null) {
            $this->output
                ->set_content_type("application/json")
                ->set_header("Access-Control-Allow-Origin:*")
                ->set_status_header(200)
                ->set_output(json_encode($data))
                ->_display();
            exit();
        } else {
            $this->output
                ->set_status_header(404)
                ->set_header("Access-Control-Allow-Origin:*")
                ->_display();
            exit();
        }
    }
    // method not found
    private function method_not_found()
    {
        $data = array(
            'message' => 'method not allowed'
        );
        $this->output
            ->set_status_header(405)
            ->set_content_type('application/json')
            ->set_output(json_encode($data))
            ->_display();
        exit();
    }
}
