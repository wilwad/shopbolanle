<?php
   /*
	 * Body
	 *
	 * This file loads the php file specified by view with .php extension
	 *
	 * Author: William Sengdara
	 * Created:
	 * Modified:
	 */

	/*********** start rights verification ************/

	//$user = @ $users->loggedin();
	$view   = @ $_GET['view'];

	
	if (true) {
		$view   = get_default_view("", $view);
		$filename = "ui/{$view}.php";
		
		if (!file_exists($filename)){
		    echo alertbuilder("Unable to locate the handler for the requested view.", 'warning');
		} else {
		    require_once($filename);
		}
	}
	else {
		/*
			$right_exists = verify_right($view);
			define('ERRORS_RIGHTS_NOTALLOWED', 'You have not been authorized to access that view.');
			
			if (!$right_exists)
				die(alertbuilder(ERRORS_RIGHTS_NOTALLOWED,"danger"));
				
			*/
			
		   define('DEBUG',0);
		   $view   = get_default_view($role, $view);
		   
			// handler for the current view 
			$filename = "ui/{$view}.php";

			//$action = "VIEW_LOAD";
			//$description = "Request to load view handler: $filename";
			//update_system_log($action, $description);
			
			if (file_exists($filename)){
				
				// modification details
				//$filename = __FILE__;
				$modified = date("F d Y H:i:s.", filemtime($filename));
		
				if (DEBUG)
				    echo "<li class='fa fa-fw fa-info-circle'></li>&nbsp;<b>Debug notes follow.</b> {\"filename\": \"$filename\", \"last-modified\": \"$modified\"}";    
					  
			   require_once($filename);    
			}
			else 
			{
					$action = "VIEW_LOAD";
					$description = "Failed to load view handler: $filename. File not found.";
					update_system_log($action, $description);		
					
			       echo "  <div class='alert alert-danger'>
						    <li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;Unable to load view handler: `$filename`.
						   </div>";
			}
	}
?>