<?php

class TreeTraversing {
    public $edges;
    private $color = [];
    private $cycle;

    const WHITE = 0;
    const GRAY = 1;
    const BLACK = 2;

    public function __construct($edges =[]) {
        $this->edges = $edges;
    }

    public function GraphCycleSearch($node) {
        $this->cycle = null;

        $this->color = [];
        foreach($this->edges as $key => $edge) {
            $this->color[$key] = self::WHITE;
        }

        $this->DepthFirstSearch($node);

        return $this->cycle;
    }

    private function DepthFirstSearch($node, $from = null) {
        $this->color[$node] = self::GRAY;

        foreach($this->edges[$node] as $to => $length) {
            if(is_null($from) || $from != $to) {
                if ($this->color[$to] == self::WHITE) {
                    if ($this->DepthFirstSearch($to, $node)) {
                        return true;
                    }
                } else if ($this->color[$to] == self::GRAY) {
                    $this->cycle = $node;
                    return true;
                }
            }
        }

        $color[$node] = self::BLACK;

        return false;
    }
}