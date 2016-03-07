<?php
    $CONFIG['system']['route'] = array(
        'dufault_controller'    => 'index',
        'default_action'        => 'index',
        'post_str'              => '.php',
        'rewrite'               => TRUE
    );
    
    $CONFIG['system']['lib'] = array(
        'mysql'     =>  'lib_mysql',
        'mysqli'    =>  'lib_mysqli',
        'request'   =>  'lib_requests'
    );

    $CONFIG['system']['database'] = array(
        'hostname'  =>  '',
        'username'  =>  '',
        'password'  =>  '',
        'database'  =>  ''
    );
    $CONFIG['system']['other'] = array(
        'debug_mode'=>  FALSE
        'sql_engine'=>  'mysqli'
    );
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set("display_errors" , "Off");
?>