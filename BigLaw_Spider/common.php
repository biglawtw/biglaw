<?php
  // Turn off all error reporting
  error_reporting(0);
  
  function getrequest($var, $default)
  {
    return isset($_REQUEST[$var]) ? $_REQUEST[$var] : $default;
  }
  function echoArray($array)
  {
    foreach($array as $i => $data)
    {
      echo $data;
      echo '<br/>';
    }
  }
?>