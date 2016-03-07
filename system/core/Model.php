<?php

abstract class Model{
        
    public $_db;

    public $_config;
    private $_initflag;
        
    public function __construct($config){
        $this->_config = $config;
        $this->_initflag = FALSE;
        if(!$this->_initflag){
            $sql_engine = "lib_". $this->_config['database']['sql_engine'];
            $this->_db = new $sql_engine($this->_config['database']);
            $this->_initflag = TRUE;
        }
    }
    // public final function init(){

    // }

}    

?>