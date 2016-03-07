<?php

final class Helper {
    static public $_config;
    static public $_controller;

    private function __construct(){}

    static public function setConfig($config){
        self::$_config = $config;
    }

    static public function setController($controller){

        self::$_controller = $controller;
    }
}