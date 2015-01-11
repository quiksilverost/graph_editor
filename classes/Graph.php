<?php

class Graph {
    public $nodes = [];
    public $edges;
    public $treeTraversing;


    public function __construct() {
        $nodes = sql::db()->query("select * from Nodes")->fetchAll();
        foreach($nodes as $node) {
            $this->nodes[$node['id']] = [
                'name' => $node['name']
            ];
            $this->edges[$node['id']] = [];
        }

        $edges = sql::db()->query("select * from Edges")->fetchAll();
        foreach($edges as $edge) {
            // two way binding
            $this->edges[$edge['in_node']][$edge['out_node']] = $edge['length'];
            $this->edges[$edge['out_node']][$edge['in_node']] = $edge['length'];
        }

        $this->treeTraversing = new TreeTraversing($this->edges);
    }

    public function getPossibleBinds($id) {

    }

}