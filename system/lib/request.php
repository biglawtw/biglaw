<?php
    

    class lib_request{
        
        public function __construct(){
            
        }

        public function isDelete(){
            return (bool)( 'DELETE' == $_SERVER['REQUEST_METHOD']);
        }

        public function isPost(){
            return (bool)( 'POST' == $_SERVER['REQUEST_METHOD']);
        }

        public function isAjax(){
            $flag = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : FALSE;
            return (bool)('XMLHttpRequest' == $flag);
        }

        public function getPost($key , $stripTag = true){
            if( isset($_POST[$key])){
                if($stripTag){
                    return strip_tags(trim($_POST[$key]));
                }else{
                    return $_POST[$key];
                }
            }else{
                return NULL;
            }
        }

        public function getQuery($key , $stripTag = true){
            if( isset($_GET[$key])){
                if($stripTag){
                    return strip_tags(trim($_GET[$key]));
                }else{
                    return $_GET[$key];
                }
            }else{
                return NULL;
            }
        }

        public function getPostJSON(){
            $content = file_get_contents("php://input");
            $data = json_decode($content ,true);
            return $data;
        }

    }

?>