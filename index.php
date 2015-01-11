<?php

require_once('config.php');

function __autoload($className) {
    if(file_exists("controllers/$className.php")) {
        require_once("controllers/$className.php");
    } else {
        require_once("classes/$className.php");
    }
}


$controllerName = INIT_CONTROLLER;

if(!empty($_GET['c']))  {
    $controllerName = $_GET['c'].'Controller';
}

$controller = new $controllerName();
if(!empty($_GET['a'])) {
    $controller->action = $_GET['a'];
}

$controller->run();

//$_GET['c'];