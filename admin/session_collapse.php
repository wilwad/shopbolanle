<?php
require_once('database.php');

$action = @ $_POST['action'];
$id     = @ $_POST['id'];
$state  = (int) @ $_POST["state"];

if ($action == 'set-collapse-state')
{  
	$_SESSION["collapse_$id"] = $state;
	echo "set var collapse_$id == $state";
}

/*
// --- Sets the state for chosen ID.
Collapse::set_state('info', 0);

// --- Returns the state of the ID.
Collapse::state('info');
*/
?>