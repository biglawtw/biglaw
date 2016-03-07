<?php

abstract class CLIHandler {

    public $_argv;

    final public function setArgv($argv){
        $this->_argv = $argv;
    }

    public function main(){

    }

}