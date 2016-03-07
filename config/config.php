<?php
    $EVN = 'product';   
    $EVN = 'test';
    $EVN = 'develop';

    switch($EVN){
        case 'develop':
            include_once "config.dev.php";
            break;
        case 'test':
            include_once "config.test.php";
            break;
        case 'product':
            include_once "config.pro.php";
            break;
    }
?>