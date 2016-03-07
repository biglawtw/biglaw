<?php

function resource($resourcename){

    if($resourcename[0] == '/'){
        $resourcename = substr($resourcename , 1);
    }
    echo WEB_ROOT . '/' . $resourcename;
}

function partialView($viewname , $data = null){
    $path = ROOT . '/app/View/partialView/' . $viewname;
    if(file_exists($path.'.html')){
        include($path.'.html');
    }else if(file_exists($path . '.php')){
        include($path.'.php');
    }else if(file_exists($path . '.tpl')){
        include($path.'.tpl');
    }
}

function api(){
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
// Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}

function api_json(){

    header("Content-Type:application/json; charset=utf-8");
}

function utf8(){
    header("Content-Type:text/html; charset=utf-8");
}