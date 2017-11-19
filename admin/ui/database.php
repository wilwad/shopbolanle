<?php
    /*
	 * Database Management
	 *
	 * This file manages the database
	 *
	 * Author: William Sengdara
	 * Created:
	 * Modified:
	 */

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
    $right_exists = verify_right($view);
			
	if (!$right_exists)
		die("<div class='well bg-white'>
			   <div class='alert alert-danger'>
			    <li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;You do not have the right to request that view.
			   </div>
			 </div>");
	  
	$server      = settings::db_host;
	$db          = settings::db_db;
	$user        = settings::db_user;
	
	$fa_wrench   = font_awesome('fa-wrench');
	$fa_icon     = font_awesome('fa-database');
	$fa_info     = font_awesome('fa-info-circle');
	$fa_download = font_awesome('fa-download');
	$fa_play     = font_awesome('fa-play');
	
	$db_tables = "<i>Nothing to show</i>";
	$sql = "SHOW tables";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{
	}
	else
	{
		$db_tables = "";
		$i = 1;
		while ($row = $ret->fetch_array())
		{
			$table = $row[0];
			$fields = "";
			
			// subquery
			$sql = "DESCRIBE $table";
			$ret0 = $database->query($sql);
			if (!$ret0 || !$ret0->num_rows)
			{}
			else
			{
				$j = 1;
				while ($row0 = $ret0->fetch_array())
				{
					 $fields .= "$j. <b>{$row0['Field']}</b> --
								 {$row0['Type']},
								 {$row0['Null']},
								 {$row0['Key']},
								 {$row0['Default']},
								 {$row0['Extra']}<BR>";
					$j++;
				}
			}
			
			$db_tables .= "<tr>
			                <td>$i.</td><td><h4>$table</h4></td><td>$fields</td>
			               <tr>";
			$i++;
		}
	}
	
	/*
	 * backup mysql
	 */
	require('service-mysql-backup.php');
	
    $flag_backup = @ $_GET['action'];
	$ret = "";
	$json = "backup/database-backup.json";
	
	switch ($flag_backup){
		case 'backup':
			// backup
			$dir  = "backup";//dirname(__file__); // directory files
			$name = date('m-d-Y_hia');
			$ret = backup_database( $dir, $name, settings::db_host, 
												   settings::db_user, 
												   settings::db_pwd, 
												   settings::db_db);
												   
			// write json
			$arr = array();
			$arr['backup'] = array('date'=>date('m-d-Y_hi'),
			                       'file'=>"$ret");								   
			
			file_put_contents($json, json_encode($arr));			
			$ret = "<a href='backup/$ret' target='_blank'>$fa_download $ret</a>";							   												   
			break;
			
		default:
			// no operation so read the last backup
			if (file_exists($json)){
				$data = file_get_contents($json);
				$data = json_decode($data,true);

				foreach ($data as $backup)
				{
					$ret = $backup['file'];
					$ret = "<a href='backup/$ret' target='_blank'>$fa_download $ret</a>";
				}
			}
			else
			{
				$ret = "No backups";
			}
			break;
			
	}
	
	echo "<div class='well bg-white'>
			   <h4>$fa_icon Database Management <small>View information about the database or do backups and recovery.</small></h4>
			   <hr>
			   
			   <!-- mysql quick stats -->
			   <h4>$fa_info MySQL Server stats</h4>
			   <div class='table-responsive'>
				<table id='table-mysql-stats' class='table table-hover table-condensed'>
				 <tbody>
				 </tbody>
				</table>
			   </div>
		   </div>
		   
		   <div class='well bg-white'>
			   <!-- mysql server settings -->
			   <h4>$fa_wrench MySQL Server Settings</h4>
			   <div class='table-responsive'>
				<table id='table-mysql-settings' class='table table-hover table-condensed'>
				 <tbody>
				  <tr><th>Server</th><td>$server</td></tr>
				  <tr><th>Database</th><td>$db</td></tr>
				  <tr><th>User</th><td>$user</td></tr>
				 </tbody>
				</table>
			   </div>
		   </div>
		   
		   <div class='well bg-white'>
		   
			   <!-- backup or restore the db -->
			   <h4>$fa_wrench MySQL Database backup and import</h4>
			   <div class='table-responsive'>
				<form method='GET'>
				<input type='hidden' name='view' value='database'>
				<input type='hidden' name='action' value='backup'>
				  <div class='table-responsive'>
				   <table class='table'>
					<thead>
					 <tr>
					  <th>Action</th>
					  <th>Last backup file</th>
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
				</div>
		   </div>
		   
		   <div class='well bg-white'>
			  <!-- db tables and their columns -->
			   <h4>$fa_info MySQL Database tables</h4>
			   <div class='table-responsive'>
				<table id='' class='table table-hover table-condensed'>
				 <thead><th>#</th><th>Table name</th><th>Columns</th></thead>
				 <tbody>
				   $db_tables
				 </tbody>
				</table>
			   </div>
		  </div>
		  

		  <script>
		   // backup
		   $('#btnbackup').click(function(){
			  BootstrapDialog.confirm('Click OK to start backup of the database.', function (answer){
				  if (answer){
					  // backup the db!
					  console.log('Backing up the db!');
				  }
			  });
		   });		   

		   var url = 'api/api-mysql-status.php';
		   
		   var timestamp = function(){
			   return new Date().toLocaleString();
		   };
		   
		   var fetch_mysql_stats = function() {
			                         $.ajax({
										 url: url,
										 dataType: 'json',
										 beforeSend: function(){
											 $('#table-mysql-stats tbody').html(\"<li class='fa fa-fw fa-spinner fa-spin'></li>&nbsp;Refreshing view...\");
										 },
										 success: function(data){
											 var html = '';
											 
											for(var prop in data) {
												html += '<tr><th>'+prop+'</th><td>'+data[prop]+'</td></tr>';
											}
											 
											 $('#table-mysql-stats tbody').html(html);
											 
										 },
										 error: function(a,b,c){
											 console.log(timestamp(),'fetch_mysql_stats',b); 
										 }
									 });									 
									};
		   
		   $(document).ready(function(){
             fetch_mysql_stats();
		   });
		  </script>
		  ";
?>