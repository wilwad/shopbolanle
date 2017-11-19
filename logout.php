<?php 
 /*
  * Logout handler
  *
  * This file will unset the current session, update the sessionid column for current user in database
  * then reload current page to index.php
  *
  * Copyright William Sengdara (c) 2015
  *
  * Created:
  * Updated:
  */

 require_once('database.php');
 require_once('ui.php');
 
 $return = @ $_GET['return'];
 $params = @ $_GET['params'];

// unset the session field for this user

// check that we are logged in!
$user = $users->loggedin();
if (!$user) {
    /*
     * go back to index.php
     */
	  die("<script>window.location.href='index.php';</script>");
}

$userid = $user['userid'];

$action = "LOGIN_LOGOUT_EXTRA_FAIL";
$sql = "UPDATE users 
    		SET sessionid='', 
    		lastlogout=NOW()
    		WHERE id=$userid;";
$database->query($sql) or update_system_log($action, "Failed to update user session, logout time. Error: {$database->error}"); ;		

$action = "LOGIN_LOGOUT";
$description = "User has been successfully logged out. userid: $userid.";
update_system_log($action, $description); 

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

if (!$return)
    $return = "index.php";
	
//header("Location: $return");
echo "<script>window.location.href='$return';</script>";
?>