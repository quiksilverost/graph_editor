<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="source/css/bootstrap.css">
        <script src="source/js/jquery.js"></script>
        <script src="source/js/bootstrap.js"></script>
        <style type="text/css">
            .form-horizontal .control-group {
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <div class="navbar-inner">
                <a class="brand">Моделирование сети</a>
                <ul class="nav">
                    <li <?= ($this->controllerName == 'Graph')? 'class="active"':'' ?>><a href="?c=Graph">Узлы</a></li>
                    <li <?= ($this->controllerName == 'Grid')? 'class="active"':'' ?>><a href="?c=Grid">Таблица сети</a></li>
                </ul>
            </div>
        </div>
        <div class="container">
            <? include($controllerView); ?>
        </div>

    </body>
</html>