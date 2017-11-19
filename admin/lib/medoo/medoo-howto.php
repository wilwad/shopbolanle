<?php

// Include Medoo
require_once 'medoo.min.php';
 
// database config
$DB 	= "sms_recv";
$SERVER = "localhost";
$USER 	= "root";
$PWD 	= "cobain";

// Initialize
$database = new medoo([
	'database_type' => 'mysqli',
	'database_name' => 'name',
	'server' => 'localhost',
	'username' => 'your_username',
	'password' => 'your_password',
	'charset' => 'utf8'
]);
 
// insert
$result = $database->insert('account', [
							'user_name' => 'foo'
							'email' => 'foo@bar.com',
							'age' => 25,
							'lang' => ['en', 'fr', 'jp', 'cn']
						]);

?>