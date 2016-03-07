<?php

    class lib_routere{
        
        public $config;
        
        public $controller;
        public $action;
        
        public $para;

        public $url;

        public function __construct($config){
            $this->config = $config;
            $this->url = substr($_SERVER['REQUEST_URI'] , strlen(WEB_ROOT));
            
            if($config['post_str'] == substr($this->url , strlen($this->url)-strlen($config['post_str']) , strlen($config['post_str']))){
                $this->url = substr($this->url , 0 , strlen($this->url) - strlen($config['post_str']));
            }
            $this->url = substr($this->url ,1);

            $this->parse_url();
            $this->check_para();
        }

        public function check_para(){
            
            if(!preg_match('/^[a-z0-9\-\_]{1,}$/' , $this->controller)){
                $this->controller = $this->config['dufault_controller'];
            }
            if(!preg_match('/^[a-z0-9\-]{1,}$/' , $this->action)){
                $this->action = $this->config['default_action'];
            }
        }

        public function parse_url(){
            $tmp = explode("/" , $this->url);

            if(count($tmp)>=2){
                $this->action = $tmp[1];
            }
            $this->controller= $tmp[0];

            /*$tmp = explode('&' , $this->url);
            foreach($tmp as $key => $value){
                $tmp2 = explode('=' , $value);
                $this->para[$tmp2[0]] = $tmp2[1];    
            }
            $this->controller = $this->para['controller'];
            $this->action = $this->para['action'];
            */
        }


    }    

?>
