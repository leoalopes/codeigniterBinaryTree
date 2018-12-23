<?php
class Notfound extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('utils');
    }
    public function index() {
        $this->utils->returnData(['error'=>'Not found', 'status'=>'HTTP/1.1 404 Not Found', 'code'=>404]);
    }
}
?>