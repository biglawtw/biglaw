<?php

// CLI Helper

function loadModel($modelname){
    $modelpath = ROOT . '/app/Model/' . $modelname . '.php';
    if(is_file($modelpath)){
        require_once($modelpath);
        $modelClassName = $modelname . "Model";
        $model = new $modelClassName(Helper::$_config);
    }else{
        Log::write("LoadModel: Load Undefined Model '" , $modelname . "' on " . $modelpath . " occur error." , 3);
        return null;
    }
    return $model;
}