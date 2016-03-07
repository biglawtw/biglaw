<?php
    session_start();

    require "config/config.php";
    require "system/log.php"; 
    Log::setConfig($CONFIG['system']['log']);
    Log::write("-------------- Start Loader --------------");
    require "system/Loader.php";

    Loader::run($CONFIG['system']);
    Log::write("--------------- End Loader ---------------");
?>
