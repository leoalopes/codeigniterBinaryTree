<?php
class Tree extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('utils');
        $this->load->model('tree_model');
    }

    public function show($from = false) {
        echo '<pre>';
        $this->tree_model->show($from);
        echo '</pre>';
    }

    public function showunilevel($from = false) {
        echo '<br><br><pre style="text-align: center">';
        $this->tree_model->showunilevel($from);
        echo '</pre>';
    }

    public function json($from = false) {
        $this->utils->returnData($this->tree_model->json($from));
    }

    public function unileveljson($from = false) {
        $this->utils->returnData($this->tree_model->unileveljson($from));
    }
}
?>