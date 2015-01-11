<?php

class GridController extends Controller {
    public function index() {
        $graph = new Graph();

        $this->render([
            'graph'=>$graph
        ]);
    }
}