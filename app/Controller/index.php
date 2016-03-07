<?php

class indexController extends Controller{
    
    public function index(){
	header("Location:/biglaw/web/");
	return self::AUTO_SHOW_VIEW_OFF;
    }

}
