<?php
 /*
  * API to return host name from ip
  */
 header("Content-type: application/json");
 $ret = array();
 
 $ip =  @ $_GET['ip']; //"192.168.178.53";
 if (empty($ip) || !strlen($ip)){
	 $ret['status'] = 'failed';
	 $ret['host'] = 'IP cannot be empty.';
	 
	 echo json_encode($ret);
	 
	 exit;
 }
 
 $val = gethostbyaddr($ip);
 
 $ret['status'] = 'success';
 $ret['host'] = $val;
 
 echo json_encode($ret);
 ?>