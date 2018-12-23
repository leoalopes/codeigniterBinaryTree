<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tree_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function normalizeChildren($children, $father) {
        $normalized['left'] = null;
        $normalized['right'] = null;
        foreach($children as $child) {
            if($father['left_node'] == $child['id'])
                $normalized['left'] = $child;
            else
                $normalized['right'] = $child;
        }
        return $normalized;
    }

    private function getChildren($father) {
        $children = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where_in('t.id', [$father['left_node'], $father['right_node']])->get()->result_array();
        $children = $this->normalizeChildren($children, $father);
        return $children;
    }

    public function json($from) {
        if($from)
            $tree = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.id', $from)->get()->row_array();
        else
            $tree = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.father', null)->get()->row_array();
        $this->getNext($tree);
        return ['data'=>$tree, 'status'=>'HTTP/1.1 200 OK', 'code'=>200];
    }

    public function getNext(&$node) {
        $children = $this->getChildren($node);
        if($children['left'] != null) {
            $node['left'] = $children['left'];
            $this->getNext($node['left']);
        }
        if($children['right'] != null) {
            $node['right'] = $children['right'];
            $this->getNext($node['right']);
        }
    }

    public function show($from) {
        if($from)
            $startPoint = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.id', $from)->get()->row_array();
        else
            $startPoint = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.father', null)->get()->row_array();
        $children = $this->getChildren($startPoint);
        $children['right'] != null && $this->printTree($children['right'], true, '');
        $this->printNode($startPoint);
        $children['left'] != null && $this->printTree($children['left'], false, '');
    }

    private function printNode($node) {
        echo $node['id'].' '.$node['name'].'<br>';
    }

    private function printTree($node, $isRight, $indent) {
        $children = $this->getChildren($node);
        $children['right'] != null && $this->printTree($children['right'], true, $indent.($isRight?"              ":" |            "));
        echo $indent;
        if($isRight) echo ' /';
        else echo ' \\';
        echo '----------- ';
        $this->printNode($node);
        $children['left'] != null && $this->printTree($children['left'], false, $indent.($isRight?" |            ":"              "));
    }
}
?>