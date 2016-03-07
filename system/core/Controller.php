<?php

abstract class Controller{
    const   AUTO_SHOWVIEW_OFF = 1;
    public  $_config;
    public  $_request;
    public  $_model;
    /** @var  lib_view */
    public  $_view;

    public  $_defaultViewName;

    protected $_viewname;
    protected $_controllerName;
    /**
     * URI Parameter
     * @var array
     */
    protected $_parameter = array();
    /**
     * Output data
     * Output data to View
     * @var array
     */
    public  $_opdata = array();

    public function __construct(){}

    public function index(){}

    public final function setConfig($config){
        $this->_config = $config;
    }
    public final function setRequest($request){
        $this->_request = $request;
    }
    public final function setParameter($para){
        $this->_parameter = $para;
    }
    public final function setModel($modelname){
        $this->_model = new $modelname($this->_config);
    }
    public final function setView($controller , $action){
        $this->_view = new lib_view($this->_config);
        $this->_defaultViewName = $controller . "/" . $action;
        $this->_controllerName = $controller;
    }
    public final function _action($actionname){
        if(!$this->$actionname() == self::AUTO_SHOWVIEW_OFF ){
            $this->showView();
        }else{
            Log::write("--no View");
        }
    }

    public final function setCustomView($action , $controller = null ){
        if(empty($controller)){
            $this->_viewname = $this->_controllerName . "/" . $action;
        }else{
            $this->_viewname = $controller . "/" . $action;
        }
    }

    public final function showView($viewname = NULL , $controllerName = NULL){
        if(empty($controllerName)){
            if(empty($viewname)){
                if($this->_viewname != ""){
                    $viewname = $this->_viewname;
                }else{
                    $viewname = $this->_defaultViewName;
                }
            }else{
                $viewname = $this->_controllerName . '/' . $viewname;
            }
        }else{
            $viewname = $controllerName . '/' . $viewname;
        }
        if(is_file(ROOT. '/app/View/' . $viewname .'.php')){
            $this->_view->init( ROOT. '/app/View/' . $viewname .'.php' , $this->_opdata);
        }elseif(is_file(ROOT. '/app/View/' . $viewname .'.htm')){
            $this->_view->init( ROOT. '/app/View/' . $viewname .'.htm' , $this->_opdata);
        }elseif(is_file(ROOT. '/app/View/' . $viewname .'.html')){
            $this->_view->init( ROOT. '/app/View/' . $viewname .'.html' , $this->_opdata);
        }else{
            throw new Exception("View Not found");
        }
        $this->_view->render();
    }

}    

?>
