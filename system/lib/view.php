<?php
    class lib_view{
        public $_viewfile;
        public $_content;
        public $_config;
        public function __construct($config){
            $this->_config = $config;
        }

        public function init( $viewfile , $data ){
            $this->_viewfile = $viewfile;
            Log::write("-ViewName: ".$this->_viewfile);
            debug::addMessage(new message('ViewName: +' + $viewfile));
            if(file_exists($this->_viewfile)){
                ob_start();
                include( $this->_viewfile);
                if($this->_config['other']['debug_mode']){
                    debug::addMessage(new message('debug Mode Enable'));
                    debug::addMessage(new message('session'));
                    debug::addMessage(new message($_SESSION , 1));
                    debug::addMessage(new message('view data'));
                    debug::addMessage(new message($data , 1));
                    debug::showAllMsg();
                }
                $this->_content = ob_get_contents();
                ob_end_clean();
            }
        }

        public function render(){
            echo $this->_content;
        }
    }