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
			 
    $fa_db       = font_awesome('fa-database');
	$fa_play     = font_awesome('fa-play');
	$fa_download = font_awesome('fa-download');
	
?>


<?php
    $flag_backup = @ $_GET['action'];
	$ret = "";
	
	switch ($flag_backup){
		case 'backup':
			// backup
			$dir  = "backup";//dirname(__file__); // directory files
			$name = date('m-d-Y_hia');
			$ret = backup_database( $dir, $name, settings::db_host, 
												   settings::db_user, 
												   settings::db_pwd, 
												   settings::db_db);
			$ret = "<a href='backup/$ret' target='_blank'>$fa_download $ret</a>";							   												   
			break;
			
	}
	
	echo "<div style='background:#F5F5F5; padding:5px'>
	      <h4>$fa_db Backup Database <small>Backup the database</small></h4>
	      <hr>
		  <p style='font-style:italic'>It is recommended to occasionally backup the database in order to reduce downtime from corrupt data.</p>";
	
	/* controls */
	echo "<form method='GET'>
	       <input type='hidden' name='view' value='db-backup'>
		   <input type='hidden' name='action' value='backup'>
			  <div class='table-responsive'>
			   <table class='table'>
			    <thead>
				 <tr>
				  <th>Action</th>
				  <th>Backup file</th>
				  <th>Options</th>
				 </tr>
				</thead>
				<tbody>
				 <tr>
				  <td>Backup the database</td><td>$ret</td><td><button type='submit' class='btn btn-success'>$fa_play Backup</button</td>
				 </tr>
				</tbody>
			   </table>
			  </div>
		  </form>
		  </div>";
		  
?>