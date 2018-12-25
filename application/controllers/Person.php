<?php
class Person extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('utils');
        $this->load->model('person_model');
    }

    public function new() {
        $data = $this->utils->getData();
        if(isset($data['name']) && isset($data['direction']) && isset($data['father']))
            $this->utils->returnData($this->person_model->new($data));

        $this->utils->returnData(['error'=>'Missing data.', 'status'=>'HTTP/1.1 400 Bad Request', 'code'=>400]);
    }

    public function getById($id) {
        $this->utils->returnData($this->person_model->getById($id));
    }

    public function setInactive($id) {
        $this->utils->returnData($this->person_model->setInactive($id));
    }

    public function setActive($id) {
        $this->utils->returnData($this->person_model->setActive($id));
    }
}
?>