<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Person_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function check_errors() {
        if($this->db->error()['code'] != 0) {
            $error = $this->db->error()['message'];
            $this->db->trans_rollback();
            return ['error'=>$error, 'status'=>'HTTP/1.1 500 Internal Server Error', 'code'=>500];
        }
        return false;
    }

    public function getPrevious($previous, $direction) {
        if($previous[$direction.'_node'] == null) return $previous;
        $next = $this->db->where('id', $previous[$direction.'_node'])->get('tree')->row_array();
        return $this->getPrevious($next, $direction);
    }

    public function new($data) {
        $this->db->trans_begin();
        $this->db->insert('person', ['name'=>$data['name']]);
        if($err = $this->check_errors()) return $err;
        $currentId = $this->db->insert_id();
        $previous = $this->db->where('id', $data['father'])->get('tree')->row_array();
        $previous = $this->getPrevious($previous, $data['direction']);
        $this->db->insert('tree', [
                                    'id' => $currentId,
                                    'father' => $data['father']
                                  ]);
        if($err = $this->check_errors()) return $err;
        $this->db->where('id', $previous['id'])->set($data['direction'].'_node', $currentId)->update('tree');
        if($err = $this->check_errors()) return $err;
        $this->db->trans_commit();
        return ['status'=>'HTTP/1.1 204 No Content'];
    }
}
?>