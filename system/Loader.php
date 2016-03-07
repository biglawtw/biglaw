<?php

    define('SYS_ROOT' , dirname(__FILE__) );
    define('ROOT' , substr(SYS_ROOT , 0 , -7));

    if(dirname($_SERVER['PHP_SELF'])=='\\'){
        define('WEB_ROOT' , '.');
    }else{
        define('WEB_ROOT' , dirname($_SERVER['PHP_SELF']));
    }

    define('REWRITE' , $CONFIG['system']['route']['rewrite']);

    Log::write("WEB_ROOT: " . WEB_ROOT);
    Log::write("ROOT: " . ROOT);

    function debug_show($object){
        echo '<pre>';
        print_r($object);
        echo '</pre>';
    }

    function conver_url($url){
        if(!REWRITE){
            return $url;
        }
        // ./index.php?controller=xxx&action=OOO
        $tmp = mb_split("\?" , $url);
        $tmp2 = mb_split("&" , $tmp[1]);
        $url = WEB_ROOT."/";
        if(count($tmp2)>=2){
            for($i=0;$i<2;$i++){
                $tmp3 = mb_split("=" , $tmp2[$i]);
                $url .= $tmp3[1].'/';
            }
            if($tmp2[2]!=""){
                $url .= '?';
                for($i=2;$i<count($tmp2);$i++){
                    $url .= '&'.$tmp2[$i];
                }                
            }
        }else{
            foreach($tmp2 as $key => $value){   
                $tmp3 = mb_split("=" , $value);
                $url .= $tmp3[1].'/';
            }    
        }        
        return $url;
    }


    final class Loader{
        
        public static $_config;
        public static $_route;
        public static $_request;
        public static $_controller;

        public static $_libmapping;

        public function __construct(){

        }

        public static function run($config){
            self::$_config = $config;
            self::$_libmapping = $config['lib'];
            spl_autoload_register( array('Loader' , 'autoload') );
            Log::write("Load core files");
            self::loadCoreFiles();
            Log::write("Load Helper");
            self::loadhelper();

            if(PHP_SAPI == 'cli'){
                // CLI Mode
                Log::write("CLI Mode");

                $argv = $_SERVER['argv'];
                $cliHandler = $argv[1];
                $cliAction = $argv[2];

                if(empty($cliHandler)){
                    $cliHandler = "test";
                }
                if(empty($cliAction)){
                    $cliAction = "main";
                }

                if(is_file(ROOT . '/app/CLI/' . $cliHandler . '.php')){
                    include_once(ROOT . '/app/CLI/' . $cliHandler . '.php');
                    $cn = $cliHandler . "CLI";
                    Log::write("CLI name:" . $cn);
                    $cli = new $cn();
                    $cli->setArgv($argv);
                    $cli->$cliAction();
                }else{
                    Log::write("No CLI Handler file:" . (ROOT . '/app/CLI/' . $cliHandler . '.php') , 3);
                }
            }else{
                // Web Mode
                Log::write("Web Mode");
                Log::write("Load Route");
                self::route();
                Log::write("Load Request");
                self::request();
                Log::write("Attach to Controller");
                self::attach_Controller();
            }
        }

        public static function attach_Controller(){

            $controller = self::$_route->controller . "Controller";
            $model = self::$_route->controller . 'Model';
            $action = self::$_route->action;

            Log::write("Dispatch to " . $controller . "/" . $action);
            if( file_exists( ROOT.'/app/Controller/' .self::$_route->controller . '.php') ){
                require ROOT.'/app/Controller/' . self::$_route->controller . '.php';
                self::$_controller = new $controller();
                self::$_controller->setConfig(self::$_config);
                self::$_controller->setRequest(self::$_request);
                self::$_controller->setView(self::$_route->controller , self::$_route->action);                
                if( file_exists( ROOT . '/app/Model/' . self::$_route->controller . '.php')){
                    require ROOT . '/app/Model/' . self::$_route->controller . '.php';
                    self::$_controller->setModel($model);
                }
                self::$_controller->_action($action);
            }else{
                Log::write("Controller " . $controller . " not exist" , 3);
                throw new Exception("Controller " . $controller . " not exist");
            }
        }

        public static function loadhelper(){
            Helper::setConfig(self::$_config);
            Helper::setcontroller(self::$_route->controller);
            foreach(self::$_config['usehelper'] as $value){
                Log::write("Load Helper:" . $value );
                $file = SYS_ROOT . '/helper/' . $value . '.php';
                if(is_file($file)){
                    require_once($file);
                }
            }
        }

        public static function request(){
            Log::write("-Load lib_request");
            self::$_request = new lib_request();
        }

        public static function route(){
            if(self::$_config['route']['rewrite']){
                Log::write("-Load lib_routere");
                self::$_route = new lib_routere(self::$_config['route']);
            }else{
                Log::write("-Load lib_route");
                self::$_route = new lib_route(self::$_config['route']);
            }
        }

        public static function loadCoreFiles(){
            Log::write("-Load core Controller");
            require SYS_ROOT.'/core/Controller.php';
            Log::write("-Load core Model");
            require SYS_ROOT.'/core/Model.php';
            Log::write("-Load core CLI");
            require SYS_ROOT.'/core/CLIHandler.php';
        }

        public static function autoload($classnane){
            if(!empty(self::$_libmapping[$classnane])){
                $file = SYS_ROOT . '/' . self::$_libmapping[$classnane] . '.php';
            }else{
                $file = SYS_ROOT.'/'.str_replace("_" , "/" , $classnane ) . '.php';
            }
            if(file_exists($file)){
                Log::write("AutoLoad: " . $classnane);
                require_once $file;
            }else{
                Log::write("AutoLoad: " . $classnane . " not found" , 3);
                //die('error on file : ' . $file);
            }
            Log::write("AutoLoad Filename:" . $file);
        }
    }

?>