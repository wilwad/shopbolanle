<?php
	if (!@$users)
		die("FATAL ERROR: this file may not be launched outside the system. It can only be included.");

	// check that we are logged in!
	$user = $users->loggedin();
	if (!$user) {
		echo view( 'dialog-login' );
		exit;
	}

	$userid = $user['userid'];
	$view = @ $_GET['view'];

	$content = "";	
    
	
	// verify that this user has the right to manage this right
	$right_exists = false;

	foreach($myrights as $key=>$menu)
		foreach ($menu as $id=>$val)
			if (is_array($val)){
				foreach ($val as $k=>$data)
				if (strtolower($k) == strtolower($view)){
					$right_exists = true;
				    break;
				}
			}
			
	if (!$right_exists)
		die("<div class='well bg-white'>
			   <div class='alert alert-danger'>
			    <li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;You do not have the right to request that view.
			   </div>
			 </div>");
	
	$fa_icon = font_awesome('fa-bar-chart');
    echo "<div class='well bg-white'>
	         <h4>$fa_icon Statistics <small>Breakdown of data.</small></h4>
			 <hr>
		   <div class='table-responsive'>
		    <table class='table table-hover table-striped table-condensed'>
			 <thead>
			   <tr><th>Year</th><th>Country</th><th>Total</th><th>Male</th><th>Females</th><th>Approved</th><th>Rejected</th></tr>
			 </thead>
			 <tbody>
			 </tbody>
			</table>
		   </div>
		   
		   <h4>Re-applications</h4>
		   <div class='table-responsive'>
		    <table class='table table-hover table-striped table-condensed'>
			 <thead>
			   <tr><th>Year</th><th>Country</th><th>Total</th><th>Male</th><th>Females</th><th>Approved</th><th>Rejected</th></tr>
			 </thead>
			 <tbody>
			 </tbody>
			</table>
		   </div>	
		   
		   <h4>Restorations</h4>
		   <div class='table-responsive'>
		    <table class='table table-hover table-striped table-condensed'>
			 <thead>
			   <tr><th>Year</th><th>Country</th><th>Total</th><th>Male</th><th>Females</th><th>Approved</th><th>Rejected</th></tr>
			 </thead>
			 <tbody>
			 </tbody>
			</table>
		   </div>	

		   <h4>Renunciations</h4>
		   <div class='table-responsive'>
		    <table class='table table-hover table-striped table-condensed'>
			 <thead>
			   <tr><th>Year</th><th>Country</th><th>Total</th><th>Male</th><th>Females</th><th>Approved</th><th>Rejected</th></tr>
			 </thead>
			 <tbody>
			 </tbody>
			</table>
		   </div>	
		   
	      </div>";
			
	// echo the contents tab
	echo "	 </div> <!-- close <div class='well bg-white'> -->			  
		  ";	
?>