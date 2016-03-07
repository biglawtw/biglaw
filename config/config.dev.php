<?php
    //路由設定
    $CONFIG['system']['route'] = array(
        'dufault_controller'    => 'spider',
        'default_action'        => 'index',
        'post_str'              => '.php',
        'rewrite'               => TRUE
    );

    $CONFIG['system']['lib'] = array(
        // Library of database
        'lib_mysql'     =>  'lib/mysql',
        'lib_mysqli'    =>  'lib/mysqli',
        'lib_sqlite'    =>  'lib/sqlite',
        // Library of url route
        'lib_request'   =>  'lib/request',
        'lib_route'     =>  'lib/route',
        // Others
        // default view lib
        'lib_view'      =>  'lib/view',
        // Enhanced password hash function
        'Cryptography'  =>  'lib/cryptography',
        // Helper
        'Helper'        =>  'helper/base',
    );

    $CONFIG['system']['usehelper'] = array(
        'cli', // cli helper , loadModel
        'url', // url helper , redirect, url function
        'view', // view helper , resource
        'dom', // Simple HTML DOM Parser
        'header' , // Common use HTTP header
    );

    //資料庫設定
    /*$CONFIG['system']['database'] = array(
        'sql_engine'=>  'sqlite',
        'hostname'  =>  '',
        'username'  =>  '',
        'password'  =>  '',
        'database'  =>  '.spider.db'
    );*/
    $CONFIG['system']['database'] = array(
        'sql_engine'=>  'mysqli',
        'hostname'  =>  '',
        'username'  =>  '',
        'password'  =>  '',
        'database'  =>  ''
    );
    //其他設定
    $CONFIG['system']['other'] = array(
        'debug_mode'=>  FALSE,
    );

    $CONFIG['system']['log'] = array(
        'filepattern'   =>  'Ymd'
    );


    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT ^ E_NOTICE);
    ini_set("display_errors" , "On");
?>
