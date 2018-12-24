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

    private function getUnilevelChildren($father) {
        $children = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.father', $father['id'])->get()->result_array();
        return $children;
    }

    public function unileveljson($from) {
        if($from)
            $tree = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.id', $from)->get()->row_array();
        else
            $tree = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.father', null)->get()->row_array();
        $this->getUnilevelTree($tree);
        return ['data'=>$tree, 'status'=>'HTTP/1.1 200 OK', 'code'=>200];
    }

    private function getUnilevelTree(&$node) {
        $node['children'] = $this->getUnilevelChildren($node);
        foreach($node['children'] as &$child) {
            $this->getUnilevelTree($child);
        }
    }

    public function json($from) {
        if($from)
            $tree = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.id', $from)->get()->row_array();
        else
            $tree = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.father', null)->get()->row_array();
        $this->getTree($tree);
        return ['data'=>$tree, 'status'=>'HTTP/1.1 200 OK', 'code'=>200];
    }

    private function getTree(&$node) {
        $children = $this->getChildren($node);
        if($children['left'] != null) {
            $node['left'] = $children['left'];
            $this->getTree($node['left']);
        }
        if($children['right'] != null) {
            $node['right'] = $children['right'];
            $this->getTree($node['right']);
        }
    }

    public function showunilevel($from) {
        if($from)
            $startPoint = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.id', $from)->get()->row_array();
        else
            $startPoint = $this->db->select('t.*, p.name')->from('tree t')->join('person p', 't.id = p.id')->where('t.father', null)->get()->row_array();
        $this->divideByLayers($startPoint);
    }

    private function printLayer($layer) {
        $current = $total = count($layer);
        foreach($layer as $node) {
            if($current < $total) echo '         ';
            echo $node['name'];
            $current--;
        }
    }

    private function divideByLayers($startPoint) {
        $nextLayer = [$startPoint];
        do {
            $this->printLayer($nextLayer);
            echo '<br><br><br>-----------------<br><br><br>';
        } while($nextLayer = $this->getNextLayer($nextLayer));
    }

    private function getNextLayer($nodes) {
        $layer = array();
        foreach($nodes as $node) {
            $layer = array_merge($layer, $this->getUnilevelChildren($node));
        }
        return $layer != [] ? $layer : false;
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