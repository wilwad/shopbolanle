<?php
    header('Content-Type: application/json');
    require_once('../database.php');
	
	$array = explode("  ", mysql_stat());
	$data = array();
	
	// get the version
	$data["Server version"] = mysql_get_server_info();	
	
    foreach ($array as $value){
       $arr = explode(":",$value);
	   $prop = trim($arr[0]);
	   $val = trim($arr[1]);
	   
	   if ($prop == 'Uptime')
	   {
		   $val = ($val >= 31557000) ? ' >365 days' : (gmdate("z", ($val)) . ' days, ' . gmdate("H:i", $val)) . ' minutes';
	       $val = str_replace(':',' hours ',$val);
	   }
	   
	   $data[$prop] = $val;
    }

	echo json_encode($data);
?>