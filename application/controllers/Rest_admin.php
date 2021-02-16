<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rest_admin extends CI_Controller
{
    // code status
    public $http_not_allow = 405;
    public $http_bad_request = 400;
    public $http_not_found = 404;
    public $http_ok = 200;
    public $http_created = 201;
    public $http_no_content = 204;
    // content type
    public $http_content_json = "application/json";
    public $http_content_text = "text/html";
    // message
    public $message_ok = "data succsess";
    public $message_error = "data tidak berhasil";
    // url client
    public $client_url = "http://localhost:8080";
    // method  
    public $method_options = "OPTIONS";
    public $method_delete = "DELETE";
    public $method_put = "PUT";
    public $method_patch = "PATCH";
    public $method_get = "GET";
    public $method_post = 'POST';



    function __construct()
    {
        parent::__construct();
        $this->load->model('M_user', 'user');
        $this->load->model("M_News", 'news');
    }
    // route ['admin/index']
    function index()
    {
        $req = $_SERVER['REQUEST_METHOD'];
        switch ($req) {
            case 'POST':
                $this->post();
                break;
            case 'GET':
                $this->index_get();
                break;
            case 'OPTIONS':
                $this->options();
                break;
            default:
                $this->method_not_found();
                break;
        }
    }
    // route ['admin'] = method['GET']
    private function index_get()
    {

        $get_header_1 = get_headers("http://localhost/rest-api/admin");

        $data = $this->news->get_data()->num_rows();

        $data = array(
            'data' => $data,
            'header_request' => $get_header_1
        );
        $this->output
            ->set_status_header($this->http_ok)
            ->set_header("Access-Control-Allow-Origin:$this->client_url")
            ->set_content_type($this->http_content_json)
            ->set_output(json_encode($data))
            ->_display();
        exit();
    }
    // route ['admin'] = method['POST]
    private function post()
    {
        //cek content type dari client
        $data = getallheaders();
        // ubah semua data menjadi array asosiatif
        $data_array = array(
            "data_header" => $data
        );
        $check_array = array_key_exists("Content-Type", $data_array["data_header"]);
        // cek apakah ada content-type di data_array['data_header']

        if ($check_array == true) {
            // dapatkan inputan dari client berupa json
            $get_data = json_decode(file_get_contents("php://input"));

            // cke properti apakah sudah terdefinisi (jika benar dia akan true dan jika salah akan false)
            $title = property_exists($get_data, 'title');
            $image = property_exists($get_data, "image");
            $content = property_exists($get_data, 'content');
            $date_post = property_exists($get_data, 'date_post');
            $author = property_exists($get_data, 'author');

            // cek field inputan (jika true data sudah di isi semua dan jika false salah satu data belum di isi)
            if ($title == false | $image == false | $content == false | $date_post == false | $author == false) {
                // buat variabel untuk menampung pesan error
                $title_req = null;
                $image_req = null;
                $content_req = null;
                $date_post_req = null;
                $author_req = null;
                // cek kondisi error dan buat pesan masing2 field
                if ($title == false) {
                    $title_req = "title";
                }
                if ($image == false) {
                    $image_req = "image";
                }
                if ($content == false) {
                    $content_req = "content";
                }
                if ($date_post == false) {
                    $date_post_req = "date_post";
                }
                if ($author == false) {
                    $author_req = "author";
                }
                // tampilkan pesan error dan tentukan method yang di gunakan
                $return = "field $title_req $image_req $content_req $date_post_req $author_req required !!";
                $method = $this->http_bad_request;
            } else {
                // cek apakah data yang di input ini sama dengan data di database
                $where_title = array(
                    "title" => $get_data->title,
                );
                // jika data sudah di isi semua berarti data siap di masukan ke database
                if ($this->news->get_id($where_title)->num_rows() == 0) {
                    $data = array(
                        "title" => $get_data->title,
                        "image" => $get_data->image,
                        'content' => $get_data->content,
                        "author" => $get_data->author,
                        "date_post" => $get_data->date_post,

                    );
                    $return = array(
                        "data" => $this->news->insert($data),
                        "message" => "insert data sucsess"
                    );
                    $method = $this->http_created;
                } else {
                    $return = array(
                        "message" => "data yang anda inputkan sama !!"
                    );
                    $method = $this->http_bad_request;
                }
                // masukan data ke dataabase dan tentukan method yang di gunakan
                // $return = $this->news->insert($data);

            }
        } else {
            // jika content type tidak di temukan jalankan variabel di bawah ini
            $method = $this->http_bad_request;
            $return = "request not found !";
        }
        // output
        $this->output
            ->set_status_header($method)
            ->set_content_type($this->http_content_json)
            ->set_header("Access-Control-Allow-Origin:$this->client_url")
            ->set_header("Access-Control-Allow-Methods: $this->method_options,$this->method_post")
            ->set_header("Access-Control-Allow-Headers:Origin,Content-Type")
            ->set_header("Access-Control-Allow-Credentials:true")
            ->set_header("Origin:$this->client_url")
            ->set_output(json_encode($return))
            ->_display();
        exit();
    }
    // route ['admin/news'] = method['GET']
    function news()
    {

        $data = $this->news->get_data()->result();

        $this->output
            ->set_content_type($this->http_content_json)
            ->set_status_header($this->http_ok)
            ->set_header("Access-Control-Allow-Origin:$this->client_url")
            ->set_output(json_encode($data))
            ->_display();
        exit();
    }
    // route ['admin/news/id'] method ['DELETE','OPTIONS','PATCH','GET']
    function news_id($params)
    {
        $method_req = $_SERVER["REQUEST_METHOD"];

        switch ($method_req) {
            case 'GET':
                $this->news_get($params);
                break;
            case 'OPTIONS':
                $this->options();
                break;
            case 'DELETE':
                $this->delete($params);
                break;
            case 'PUT':
                $this->update($params);
                break;
            default:
                $this->method_not_found();
                break;
        }
    }
    // route ['admin/news/id'] method ['DELETE']
    private function delete($param)
    {
        $id = array(
            "id" => $param
        );
        // cek apakah id tersedia
        $data = $this->news->where($id);

        if ($data > 0) {
            $this->news->delete($id);
            $res_message = array(
                "message" => "id terhapus",
                "jumlah data yang terhapus" => $data
            );
            $method = $this->http_ok;
        } else {
            $res_message = array(
                "message" => "id not found !"
            );
            $method = $this->http_not_found;
        }
        $this->output
            ->set_status_header($method)
            ->set_content_type($this->http_content_json)
            ->set_header("Access-Control-Allow-Origin:http://localhost:8080")
            ->set_header("Access-Control-Allow-Methods: DELETE,OPTIONS")
            ->set_header("Access-Control-Allow-Headers:Origin")
            ->set_header("Origin:http://localhost:8080")
            ->set_output(json_encode($res_message))
            ->_display();
        exit();
    }
    // route ['admin/news/id'] = method['GET']
    private function news_get($params)
    {
        $url  = array(
            "id" => $params
        );
        $check = $this->news->where($url);
        if ($check > 0) {
            $return = $this->news->get_id($url)->result();
            $method = $this->http_ok;
        } else {
            $return = array(
                'message' => 'id not found'
            );
            $method = $this->http_not_found;
        }

        $this->output
            ->set_status_header($method)
            ->set_header("Access-Control-Allow-Origin:$this->client_url")
            ->set_content_type($this->http_content_json)
            ->set_output(json_encode($return))
            ->_display();
        exit();
    }
    // route ['admin/news/id'] = method['put']
    private function update($params)
    {
        // dapatkan id dari url
        $Get_id = array(
            "id" => $params
        );
        // cek id dapakah ada di database
        $filter_id = $this->news->where($Get_id);
        // condisional id
        if ($filter_id > 0) {
            // get data berupa json dari client
            $data_mentah = file_get_contents("php://input");
            //json decode
            $data_convert = json_decode($data_mentah);
            // get id sebagai where untuk update data
            $where_id = array(
                "id" => $Get_id['id']
            );
            $data = array(
                'title' => $data_convert->title,
                'image' => $data_convert->image,
                'content' => $data_convert->content,
                'author' => $data_convert->author,
                'date_post' => $data_convert->date_post
            );
            $this->news->update($where_id, $data);
            $return = array(
                "message" => "data success di update"
            );
            $method = $this->http_ok;
        } else {
            $return = array(
                'message' => "id not found !!"
            );
            $method = $this->http_bad_request;
        }
        //output json
        $this->output
            ->set_status_header($method)
            ->set_content_type($this->http_content_json)
            ->set_header("Access-Control-Allow-Origin:$this->client_url")
            ->set_header("Access-Control-Allow-Methods:PUT,OPTIONS")
            ->set_header("Access-Control-Allow-Headers: Content-Type, Origin,Accept, Accept-Encoding")
            ->set_output(json_encode($return))
            ->_display();
        exit();
    }
    // method not found
    private function method_not_found()
    {
        $data = array(
            'message' => "Method yang anda gunakan tidak di perbolehkan !"
        );
        $this->output
            ->set_status_header($this->http_not_allow)
            ->set_header("Access-Control-Allow-Origin:*")
            ->set_content_type($this->http_content_json)
            ->set_output(json_encode($data))
            ->_display();
        exit();
    }
    // prefilight
    function options()
    {
        $this->output
            ->set_status_header(200)
            ->set_header("Access-Control-Allow-Origin:$this->client_url")
            ->set_header("Access-Control-Allow-Headers:Origin,Content-Type")
            ->set_header("Access-Control-Allow-Methods: $this->method_delete,$this->method_post,$this->method_put,$this->method_options")
            ->set_header("Origin")
            ->set_header("Access-Control-Request-Method")
            ->set_header("Access-Control-Request-Headers")
            ->_display();
        exit();
    }
}
