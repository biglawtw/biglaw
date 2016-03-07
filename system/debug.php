<?php

final class debug{
    private static $_debugMessage = array();
    
    public static function addMessage($msg){
        array_push(self::$_debugMessage , $msg);
    }

    public static function showAllMsg(){
        print('<pre>');
        foreach(self::$_debugMessage as $val){
            echo $val->getMsg();
        }
        print('</pre>');
    }
}

final class message{
    private $_msg;
    private $_showtype;
    public function __construct($msg , $showType = 0){
        $this->_msg = $msg;
        $this->_showtype = $showType;
    }
    public function getMsg(){
        if($this->_showtype=1){
            debug_show($this->_msg);
            return NULL;
        }else{
            return $this->_msg;
        }
    }
}