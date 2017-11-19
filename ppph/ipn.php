<?php
  /* error reporting */
  ini_set('display_startup_errors',1);
  ini_set('display_errors',1);
  error_reporting(-1);

  if ($_POST){
     $fp = fopen("ipn.php.log", "a");
     
    if ($fp){
        fwrite($fp, print_r($_POST,true) . PHP_EOL);
        fclose($fp);        
    }
  }
?>