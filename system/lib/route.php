<?php

    class lib_route{
        
        public $config;
        
        public $controller;
        public $action;
        
        public $para;

        public $url;

        public function __construct($config){
            $this->config = $config;
            //$this->url = $_SERVER['REQUEST_URI'];
            $this->url = $_SERVER['QUERY_STRING'];
            $this->parse_url();
            $this->check_para();
        }

        public function check_para(){
            
            if(!preg_match('/^[A-Za-z0-9\-\_]{1,}$/' , $this->controller)){
                $this->controller = $this->config['dufault_controller'];
            }
            if(!preg_match('/^[A-Za-z0-9\-]{1,}$/' , $this->action)){
                $this->action = $this->config['default_action'];
            }
        }

        public function parse_url(){
            $tmp = explode('&' , $this->url);
            foreach($tmp as $key => $value){
                $tmp2 = explode('=' , $value);
                $this->para[$tmp2[0]] = $tmp2[1];    
            }
            $this->controller = $this->para['controller'];
            $this->action = $this->para['action'];
        }


    }    

?>
