<?php

class Controller {
    public $action = 'index';
    public $controllerName;

    public function __construct() {
        $this->controllerName = preg_replace('/^(.*)Controller$/','$1', get_class($this));
    }

    public function render($data = []) {

        $controllerView = 'views/' . strtolower($this->controllerName) . '/' . $this->action . '.php';
        include('views/_layout.php');
    }

    public function ajaxReslut($data = []) {
        echo json_encode($data);
    }

    public function run() {
        $action = $this->action;

        $this->$action();
    }
}