<?php

/**
 *
 * Class Log
 * to write Log to filesystem
 * edited on 2014-05-12 13:05:03
 */
final class Log
{

    static private $_config;

    static public function setConfig($config)
    {
        self::$_config = $config;
    }

    /**
     * function to write a log to file
     * filename pattern : $CONFIG['system']['log']['filepattern']
     *
     * arguments:
     * $log : String , log meggage
     * $state : Integer , 1 is info , 2 is warning , 3 is error
     * $logtype : String , log file name
     */
    static public function write($log, $state = 1, $logtype = 'normal')
    {
        $modpermission = false;
        if($logtype == 'normal'){
            $filename = "log/" . date(self::$_config['filepattern']) . ".log";
        }else{
            $filename = "log/" . date(self::$_config['filepattern']) . "_" . $logtype . ".log";
        }

        if(!file_exists($filename)){
            $modpermission = true;
        }

        $fp = fopen($filename, "a");
        $message = "";
        switch ($state) {
            case 1:
                $message = "[info]\t";
                break;
            case 2:
                $message = "[warning]\t";
                break;
            case 3:
                $message = "[error]\t";
                break;
        }
        $message = date("H:i:s") . "\t" . $message . "\t" . $log . PHP_EOL;
        fwrite($fp, $message);
        fclose($fp);
        if($modpermission){
            chgrp($filename , 'www-data');
            chmod($filename , 0775);
        }
    }
}
