<?php
    /*
	 * API
	 *
	 * This file is the API handler for CRS
	 *
	 * Author: William Sengdara
	 *
	 * Created:
	 * Updated: 9/9/2015 07:42 AM
	 */
	 
 require_once('../database.php');

 $debug = (int) @ $_GET['debug'];
 $view  = $debug ? @ $_GET['view'] : @ $_POST['view'];
 $view  = strtolower($view);
 
 define("MAX_PASSWORD", 7);
$date = date('Y-m-d H:i:s');

 switch ($view) {
		/*
		 * CATEGORY: REMOTE DATA ACCESS 
		 *
		 * Entry point for external data queries
		 */	 
		 
		 /*
		  * khomas grove
		  * alert management
		  */
		  
		 case 'alert-subscribe':
             $storeid   = (int) $_POST['store_id'];
             $cellphone = addslashes(htmlentities($_POST['cellphone']));
             $fullname  = addslashes(htmlentities($_POST['full_name']));
             $dob       = addslashes(htmlentities($_POST['dob']));
             $sex_id    = (int) $_POST['sex_id'];
             
		     // find the shop
		     $sql = "SELECT id, name 
		             FROM stores 
		             WHERE id=$storeid AND enabled=1;";
		     $ret = $database->query($sql) or die($database->error);
		     if (!$ret || !$ret->num_rows){
		         die("You cannot subscribe to alerts from this store at the moment. Try again later.");
		     }
		     
		     $shop_name = $ret->fetch_array()['name'];

		     // proper the cellphone number
		     
		     // find existing cellphone
		     $sql = "SELECT id 
		             FROM alert_subscribers 
		             WHERE cellphone = '$cellphone' AND store_id=$storeid;";
		     $ret = $database->query($sql) or die($database->error);
		     if (!$ret || !$ret->num_rows){
		         // new entry
		     } else {
		         die("You are already subscribed to alerts from this store.");
		     }
		     
             require_once('../providers_sms/sms-services-effinity.php');		
             
		     // insert the subscriber
		     $sql = "INSERT INTO alert_subscribers 
		             (store_id, cellphone, full_name, dob, sex_id)
		             VALUES
		             ($storeid, '$cellphone', '$fullname','$dob',$sex_id);";
		     $ret = $database->query($sql) or die($database->error);
             $subscriberid = $database->insert_id;
             
             $message = "You have subscribed to alerts from: {$shop_name}.\n- Khomas Grove Mall";   
             
             /*
             // log the message
		     $sql = "INSERT INTO alert_messages 
		             (store_id, entrydate, alert_subscriber_id, message)
		             VALUES
		             ($storeid, '$date', $subscriberid, '$message');";
		     $ret = $database->query($sql) or die($database->error);
		     
             // send an sms to this person!!
             send_sms($cellphone,"88801",$message);
             */
             
		     die("true");
		     break;
		     
        case 'alert-send-message':
              $storeid = (int) $_POST['store_id'];
              $cellphone = $_POST['cellphone'];
              $subscriberid = (int) $_POST['subscriber_id'];
              $message      = addslashes(htmlentities($_POST['message']));
              
		     // find the shop
		     $sql = "SELECT id, name 
		             FROM stores 
		             WHERE id=$storeid AND enabled=1;";
		     $ret = $database->query($sql) or die($database->error);
		     if (!$ret || !$ret->num_rows){
		         die("You cannot send an alert from this store at the moment. Try again later.");
		     }
		     
		     $shop_name = $ret->fetch_array()['name'];
		     
              require_once('../providers_sms/sms-services-effinity.php');
              
		     // insert into alert_messages
		     $sql = "INSERT INTO alert_messages 
		             (store_id, entrydate, alert_subscriber_id, message)
		             VALUES
		             ($storeid, '$date', '$subscriberid', '$message');";
		     $ret = $database->query($sql) or die($database->error);

             // send an sms to this person!!
            echo send_sms($cellphone,"88801",$message);
             
            break;
            
        case 'alert-send-message-bulk':
              $storeid = (int) $_POST['store_id'];
              $message      = addslashes(htmlentities($_POST['message']));
              
		     // find the shop
		     $sql = "SELECT id, name 
		             FROM stores 
		             WHERE id=$storeid AND enabled=1;";
		     $ret = $database->query($sql) or die($database->error);
		     if (!$ret || !$ret->num_rows){
		         die("You cannot send an alert from this store at the moment. Try again later.");
		     }
		     
		  /* 
		   * Lyrics for Yeke Yeke by Mory Kante
		   *
		    Bi sounkouroun lou la donkégna, ah, ah
            Bi sounkouroun lou la donkégna, ah, ah
            I madji I ma yélé I kanan n'bila nara ro
            N'bo n'bolo bila Kanfalani yana sara le ila
            Yékéké nimo yé ké yé ké
            Yékéké nimo yé ké yé ké
            Bi kamberen lou la donkégna, ah, ah
            Bi kamberen lou la donkégna, ah, ah
            I madji I ma yélo I kanan n'bila nara ro
            N'bo n'bolo bila Gnin kisse gbel a sara le ila
		   */
		  $sql = "SELECT 
                         subs.id,
                         subs.cellphone
            	  FROM 
            			 alert_subscribers subs
            	  WHERE 
            			 subs.store_id=$storeid AND subs.cellphone <> ''
            	  ORDER BY id DESC;";
	     $ret = $database->query($sql) or die($database->error);
	     if (!$ret || !$ret->num_rows){
	         die("Cannot send an SMS alert from this store: No subscribers.");
	     }
		     
         require_once('../providers_sms/sms-services-effinity.php');
         
         $idx = 0;
         
         while ($row       = $ret->fetch_array()){  
             $cellphone    = $row["cellphone"];
             $subscriberid = $row['id'];
             
             $date = date('Y-m-d H:i:s');
             
		     // insert into alert_messages
		     $sql = "INSERT INTO alert_messages 
		             (store_id, entrydate, alert_subscriber_id, message)
		             VALUES
		             ($storeid, '$date', '$subscriberid', '$message');";
		     $database->query($sql) or die($database->error);

             // send an sms to this person!!
             $resp = send_sms($cellphone,"88801",$message);
             
             if ($resp == 'success') $idx++;
         }
         
        echo "true"; //"SMS Alert sent to $idx subscribers.";
        
        break;            
		     
		/*
		 * Generic functions
		 */
		case 'delete-from-table':
			// delete an item from a table

			$userid = (int) @ $_POST['userid'];
			$table  = str_sanitize(@ $_POST['table']);
			$id     = (int) @ $_POST['id'];
			
			/* add the report */
			$sql = "DELETE FROM $table WHERE id=$id;";
			$database->query($sql) or die($database->error);
			
			$action = "{$table}_DELETE";
			$description = "Item deleted from $table. Action by user $userid.";
			update_system_log($action, $description);
			
			echo "true";
			break;		

		case 'toggle-state':
			// toggle tinyint value of a column

			$userid   = (int) @ $_POST['userid'];
			$table    = str_sanitize(@ $_POST['table']);
			$field    = str_sanitize(@ $_POST['field']);
			$newvalue = (int) @ $_POST['newvalue'];
			$id       = (int) @ $_POST['id'];
			
			/* toggle */
			$sql = "UPDATE $table 
       			    SET $field=$newvalue
					WHERE id=$id;";
			$database->query($sql) or die($database->error);
			
			$action = "{$table}_TOGGLE";
			$description = "Item deleted from $table. Action by user $userid.";
			update_system_log($action, $description);
			
			echo "true";
			break;

		/*
		 * CATEGORY: USERS 
		 *
		 * User Management
		 */	 
		case 'users-add':
			// adding a new user

			$userid = @ $_POST['userid'];
			$userid = (int) $userid;
			
			/* POST params */
			$username = @ $_POST['username'];
			$password = @ $_POST['password'];
			$fname 	  = @ $_POST['fname'];
			$sname    = @ $_POST['sname'];
			$isactive = (int) @ $_POST['isactive'];
			$roleid   = (int) @ $_POST['roleid'];
			
			$username = trim($username);
			$password = trim($password);
			$fname = trim($fname);
			$sname = trim($sname);
			$roleid = trim($roleid);
			
			if ($username == "" ||
			    $password == "" ||
				$fname == "" ||
				$sname == "" ||
				$roleid == "")
				die("Ensure all parameters have been set.");
				
			if (strlen($password) < MAX_PASSWORD)
				die("Password cannot be less than " . MAX_PASSWORD . " characters.");
			
			/* check if this user has admin or top level right to add a new user */
			$sql = "SELECT * 
			        FROM users 
			        WHERE username='$username';";
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows)
			{
				// nothing found
			}
			else
			{
				echo "That user already exists.";
				exit;
			}
			
			/* check if this user has admin or top level right to add a new user */
			$sql = "SELECT r.name as rolename 
			        FROM users u, roles r 
			        WHERE u.id = $userid AND u.roleid = r.id;";
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows)
			{
				echo "Unable to locate user account creator.";
				exit;
			}
			
			/* check if we have right to add users */
			$row = $ret->fetch_array();
			$myrole = $row['rolename'];
			$myrole = strtolower($myrole);
			
			switch ($myrole){
				case 'administrators':
				case 'secretariats':
				case 'top_levels':
					break;
					
				default:
					echo "You do not have the right to create user accounts.";
					exit;
					break;
			}

			/* create the account */
			$sql = "INSERT INTO users(usern, pwd, roleid, isactive)
			        VALUES('$username',MD5('$password'),$roleid,$isactive);";
			$ret = $database->query($sql);
			if (!$ret)
			{
				echo "Failed to insert data into users account. SQL: $sql. Error: " . $database->error;
				exit;
			}

			$userid = $database->insert_id;
			
			// insert into the account
			$sql = "INSERT INTO user_profiles(id, fname, sname)
			        VALUES($userid, '$fname','$sname');";
			$ret = $database->query($sql);
			if (!$ret)
			{
				echo "Failed to insert data into user profiles. SQL: $sql. Error: " . $database->error;
				exit;
			}
					
			echo "true";
			break;
			
		/* change user password */
		case 'users-change-password':
			// adding a new user
			$userid = @ $_POST['userid'];
			$userid = (int) $userid;
			$user_password = str_sanitize(@ $_POST['user_password']);
			
			if (!strlen($user_password))
			{
				echo "Error: new password cannot be empty.";
				exit;
			}
			// has this password
			$user_password = MD5($user_password);
			
			// update the database
			$sql = "UPDATE users 
					SET user_password='$user_password'
					WHERE id=$userid;";
			$ret = $database->query($sql);
			$ret = $ret ? "true" : $database->error;
			
			// write log
			$action = "USER_PWD_CHANGE";
			$description = "The password of the user has been changed. User id: $userid";
			update_system_log($action, $description);
			
			echo $ret;
			break;
			
		/*
		 * CATEGORY: NOTICEBOARD
		 *
		 * notices management
		 */			
		 
		// add a bug report
		case 'noticeboard-add':
			$userid = (int) @ $_POST['userid'];
			$heading =  str_sanitize( @ $_POST['heading'] );
			$body = str_sanitize( @ $_POST['body'] );
			$enabled = (int) @ $_POST['enabled'];
			
			/* POST params */
			if (!strlen($heading) || !strlen($body))
				die("Ensure all parameters have been set.");

			/* add the report */
			$sql = "INSERT INTO 
			        user_noticeboard(heading,body, enabled, userid)
			        VALUES('$heading','$body',$enabled, $userid);";
			$ret = $database->query($sql);
			if (!$ret)
			{
				echo "Failed to add noticeboard item. SQL: $sql. Error: " . $database->error;
				exit;
			}
			echo "true";
			break;
					
		// editing
		case 'noticeboard-edit':
			$userid = (int) @ $_POST['userid'];
			$id = (int) @ $_POST['id'];
			$heading =  str_sanitize( @ $_POST['heading'] );
			$body = str_sanitize( @ $_POST['body'] );
			$enabled = (int) @ $_POST['enabled'];

			if ($description == "" || !strlen($description))
				die("Ensure all parameters have been set.");

			/* add the report */
			$sql = "UPDATE
			        user_noticeboard
					SET heading='$heading', 
					body='$body',
					$enabled=$enabled
					WHERE id=$id;";
			$ret = $database->query($sql);
			if (!$ret)
			{
				echo "Failed to add noticeboard item. SQL: $sql. Error: " . $database->error;
				exit;
			}
			echo "true";
			break;
			
		// delete
		case 'noticeboard-delete':
			// adding a new bug report
			$userid = (int) @ $_POST['userid'];
			$id = (int) @ $_POST['id'];
			
			/* add the report */
			$sql = "DELETE 
			        FROM user_noticeboard 
					WHERE id=$id;";
			$database->query($sql) or die($database->error);
			
			echo "true";
			break;
			
			
		/*
		 * CATEGORY: notifications
		 *
		 * notices management
		 */			
		 
		// add a bug report
		case 'notifications-add':
			$userid_to = (int) @ $_POST['userid_to'];
			$userid_from = (int) @ $_POST['userid_from'];
			$subject =  str_sanitize( @ $_POST['subject'] );
			$body = str_sanitize( @ $_POST['body'] );
			
			/* POST params */
			if (!strlen($subject) || !strlen($body))
				die("Ensure all parameters have been set.");

			/* add the report */
			$sql = "INSERT INTO 
			        user_notifications(userid_from,userid_to,subject,body,wasread, entrydate)
			        VALUES($userid_from, $userid_to, '$subject', '$body', 0, NOW());";
			//die($sql);
			$ret = $database->query($sql);
			if (!$ret)
			{
				echo "Failed to add notifications item. SQL: $sql. Error: " . $database->error;
				exit;
			}
			echo "true";
			break;
					
		// editing
		case 'notifications-edit':
			$userid = (int) @ $_POST['userid'];
			$id = (int) @ $_POST['id'];
			$subject =  str_sanitize( @ $_POST['subject'] );
			$body = str_sanitize( @ $_POST['body'] );
			$enabled = (int) @ $_POST['enabled'];

			if ($description == "" || !strlen($description))
				die("Ensure all parameters have been set.");

			/* add the report */
			$sql = "UPDATE
			        user_notifications
					SET subject='$subject', 
					body='$body',
					$enabled=$enabled
					WHERE id=$id;";
			$ret = $database->query($sql);
			if (!$ret)
			{
				echo "Failed to add notifications item. SQL: $sql. Error: " . $database->error;
				exit;
			}
			echo "true";
			break;
			
		// delete
		case 'notifications-delete':
			// adding a new bug report
			$userid = (int) @ $_POST['userid'];
			$id = (int) @ $_POST['id'];
			
			/* add the report */
			$sql = "DELETE 
					FROM user_notifications 
					WHERE id=$id;";
			$database->query($sql) or die($database->error);
			
			echo "true";
			break;

		/*
		 * CATEGORY: BUG REPORTS
		 *
		 * Bug report management
		 */			
		 
		// add a bug report
		case 'bug-report-add':
			$userid = (int) @ $_POST['userid'];
			$severity = (int) @ $_POST['severity'];
			$description = @ $_POST['description'];
			
			/* POST params */
			$description = $database->real_escape_string($description);
			$description = trim($description);
			
			if ($description == "" || !strlen($description))
				die("Ensure all parameters have been set.");

			/* add the report */
			$sql = "INSERT INTO 
			        system_bugs(description, severity, user_id)
			        VALUES('$description',$severity,$userid);";
			$ret = $database->query($sql);
			if (!$ret)
			{
				echo "Failed to report bug. SQL: $sql. Error: " . $database->error;
				exit;
			}
			echo "true";
			break;
					
		// editing
		case 'bug-report-edit':
			$userid = (int) @ $_POST['userid'];
			$id = (int) @ $_POST['id'];
			$severity = (int) @ $_POST['severity'];
			$description = @ $_POST['description'];
			
			/* POST params */
			$description = $database->real_escape_string($description);
			$description = trim($description);
			
			if ($description == "" || !strlen($description))
				die("Ensure all parameters have been set.");

			/* add the report */
			$sql = "UPDATE
			        system_bugs
					SET description='$description', severity=$severity
					WHERE id=$id;";
			$ret = $database->query($sql);
			if (!$ret)
			{
				echo "Failed to report bug. SQL: $sql. Error: " . $database->error;
				exit;
			}
			echo "true";
			break;
			
		// delete
		case 'bug-report-delete':
			// adding a new bug report
			$userid = (int) @ $_POST['userid'];
			$id = (int) @ $_POST['id'];
			
			/* add the report */
			$sql = "DELETE FROM system_bugs WHERE id=$id;";
			$database->query($sql) or die($database->error);
			
			echo "true";
			break;
			
		/*
		 * CATEGORY: SYSTEM
		 *
		 * System Management
		 */			
		case 'backup':
			// ensure the backup folder exists
			$host = settings::db_host ;
			$port = settings::db_port ;
			$db   = settings::db_db   ;
			$user = settings::db_user ;
			$pwd  = settings::db_pwd ;
			   
			$dir = "../backup";
			if (!is_dir($dir))
				mkdir($dir);
			
			// file path
			$filename = 'database_backup_' . date('d-m-Y-H-i-s') . '.sql';
			$filename = "$dir/$filename";
			$command = "mysqldump --user=$user --password='$pwd' --host=$host $db > $filename";
			$ret = exec($command);
			
			$action = "DB_BACKUP";
			$description = "Performing db backup as $filename.";
			update_system_log($action, $description);
			
			$result = "Operation failed.";
			
			if (file_exists($filename))
				if (filesize($filename))
					$result = "Operation success. Saved backup as $filename.";
				else
					$result = "Operation failed. Zero sized file.";
			else
				$result = "Operation failed. Could not backup to $filename.";	

			$action = "DB_BACKUP";
			update_system_log($action, $result);
			echo $result;
			break;
			
		/* queries */
        /* queries */
        case 'query-new':
			$userid      = (int) @ $_POST['userid'];
            $title       = str_sanitize( @ $_POST['title'] );
            $description = str_sanitize( @ $_POST['description'] );
            $sql         = str_sanitize( @ $_POST['sql'] );
			
            // add the school
            $sql = "INSERT INTO 
		            system_queries(entrydate, user_id, title, description, _sql) 
				    VALUES(NOW(),$userid,'$title','$description','$sql');";
		    $res = $database->query($sql);
           
            // return result
            echo $res ? "true" : $database->error;
            break;  
			
        case 'query-edit':
			$id          = (int) @ $_POST['id'] ;
            $title       = str_sanitize( @ $_POST['title'] );
            $description = str_sanitize( @ $_POST['description'] );
            $sql         = str_sanitize( @ $_POST['sql'] );

			// find the query using the id
			$sql_r = "SELECT * FROM system_queries WHERE id=$id;";
			$rec = $database->query($sql_r);
			if (!$rec || !$rec->num_rows)
				die("<p class='error' style='padding:10px'>Unable to locate the selected query for updating.</p>");
			
            // add the school
           $sql = "UPDATE system_queries 
				   SET entrydate=NOW(),
				   title='$title',
				   user_id=$userid,
				   description='$description',
				   _sql='$sql'
				   WHERE id=$id;";
			$res = $database->query($sql);
			
            // return result
            echo $res ? "true" : $database->error;
            break;  
			
        case 'query-delete':
            $id = (int) @ $_POST['id'];

            // add the school
           $sql = "DELETE 
		           FROM system_queries 
		           WHERE id=$id;";
           $res = $database->query($sql);
           
            // return result
            echo $res ? "true" : $database->error;
            break;  
			
        case 'query-run':
            $id = (int) @ $_POST['id'];

           $sql = "SELECT * 
		           FROM system_queries 
				   WHERE id=$id;";
           $rec = $database->query($sql);
           if (!$rec || !$rec->num_rows)
		   {
			   $error = $database->error;
			   die("Your query did not return any results. <small>$error</small>");
		   }
		   
           $row = $rec->fetch_array();
		   
		   $sql = $row['_sql'];
		   $title = $row['title'];
		   $description = $row['description'];
           $rec = $database->query($sql);
           if (!$rec || !$rec->num_rows)
		   {
			   $error = $database->error;
			   echo "<p class='alert alert-danger' style='padding: 10px;'><b>Your query did not return any results.</b> <BR><BR>
			        <b>Title: </b>$title<BR>
					<b>Description: </b>$description<BR>
					<b>SQL: </b> $sql <BR>
					<b>Errors:</b> <small>$error</small></p>";
			   exit;
		   }
		   
		   // get the columnheaders
		    $i = 0;
		    $fields = "<tr>";
			$body = "";
			/* should we draw a chart or not? */
			$charting = false;
			$fields_names = array();
			$labels = "";
			
			while ($fieldinfo=mysqli_fetch_field($rec))
			{	
				$fieldname = $fieldinfo->name;
			
				if (strtolower($fieldname) != 'drawchart') {
					$field_names[] = $fieldname;
				    $fields .= "<th>$fieldname</th>";
				}

				if (strtolower($fieldname) == 'drawchart')
					$charting = true;
			}

			$tr = "";
			
			$numbers = "";
			$data = "";
			
			while ($row = $rec->fetch_array())
			{
				$data = "";
				foreach ($field_names as $fld)
				{
					$val = $row[$fld];
					
					switch (strtolower($fld))
					{
						case 'uid':
							$value = "<a href='?view=employees&id=$val'>$val <span class='glyphicon glyphicon-share'></span></a>";
							break;
						
						case 'email':
							$value = "<a href='mailto:$val'>$val</a>";
							break;
						
						case 'picture':
							$value = "<img src='$val' class='ímg-thumbnail drop-shadow'>";
							break;
							
						default:
							$value = $val;
							break;
					}

					$data .= "<td>$value</td>";
					
					if ($charting && strtolower($fld) != 'drawchart')
					   if (is_numeric($val))
							 $numbers .= "$val,";
						else
							 $labels .= "\"$val\",";	
		 				
				}
				
			    $tr .= "<tr>$data</tr>";		
			}
			
			if ($charting){
				$labels = substr($labels,0,strlen($labels)-1);
			}
			
			$numbers = substr($numbers, 0, strlen($numbers)-1);
			
			$body .= "$tr";	
			$fields .= "</tr>";
			$chart_inc = "";
			
			$min =0;
			$max = 255;
			$rnd1 = mt_rand($min,$max);
			$rnd2 = mt_rand($min,$max);
			$rnd3 = mt_rand($min,$max);
			
			$datasets = "{label: 'My First dataset',
							fillColor: 'rgba($rnd1,$rnd2,$rnd3,1)',
							strokeColor: 'rgba(220,220,220,0.8)',
							highlightFill: 'rgba(220,220,220,0.75)',
							highlightStroke: 'rgba(220,220,220,1)',
							data: [$numbers]
						 }";
			
			if ($charting)
				$chart_inc = "<style> 
							.drop-shadow {
								-webkit-box-shadow: 0 0 5px 2px rgba(0, 0, 0, .5);
								box-shadow: 0 0 5px 2px rgba(0, 0, 0, .5);
							}
	
							canvas{
								width: 100% !important;

								max-height:450px !important;
								height: auto !important;
							}
							</style>
							
							<script src='lib/chart.js/chart.min.js'></script>
							<script src='lib/table2excel/jquery.table2excel.min.js'></script>
							<script src='lib/excellentexport-1.4/excellentexport.min.js'></script>
							
							 <canvas id='myChart' width='400' height='400'></canvas>
							 <script>
							  $(document).ready(function(){
								  
								var data = {
									labels: [$labels],
									datasets: [$datasets]
								};		
								
								// Get context with jQuery - using jQuery's .get() method.
								var ctx = $('#myChart').get(0).getContext('2d');
								var myBarChart = new Chart(ctx).Bar(data, null);
								
							
							  });
							 </script>
				  <p>
				  <p class='alert-danger' style='padding:10px'>
				   <span class='fa fa-fw fa-info-circle'></span>&nbsp;
				   Right-click on the chart and choose <i>Save image as</i>.
				  </p>
				  <!-- button class='btn btn-md btn-primary' id='btnExportChart'><li class='fa fa-fw fa-arrow-circle-down'></li>&nbsp;Export Graph</button -->
				  <script>
					$('#btnExportChart').on('click', function(){ downloadFile();});
				  
					function downloadFile()
					{
						alertify.success('called');
						var iframe;
						iframe = document.getElementById('download-container');
						if (iframe === null)
						{
							iframe = document.createElement('a');
							iframe.id = 'download-container';
							iframe.style.visibility = 'hidden';
							document.body.appendChild(iframe);
							
							$('#download-container').attr('href',document.getElementsByTagName('canvas')[0].toDataURL('image/png'));
							$('#download-container').attr('download','graph-export.png');
							$('#download-container').text('Hello world')
							$('#download-container').on('click', function(){alertify.success('i was clicked');});							
						}

						$('#download-container').click();
						
						//iframe.src = document.getElementsByTagName('canvas')[0].toDataURL('image/png').replace(/^data:image\/[^;]/, 'data:application/octet-stream');
					}
				  </script>
				  </p>
				  ";
			
			echo "$chart_inc
			      <h4 id='resultTitle' onclick='changeTitle()'>$description</h4>
			      <div class='table-responsive' style='overflow-x:scroll;'>
				  <table class='table table-hover table-bordered' id='table-export'>
			       <thead>
				    $fields
				   </thead>
				   <tbody>
					$body
				   </tbody>
				  </table>
				  </div>

				  <!--button class='btn btn-md btn-primary' id='btnExportExcel'><li class='fa fa-fw fa-arrow-circle-down'></li>&nbsp;Export to excel</button -->
				  <script>
					$('#btnExportExcel').on('click', function(){
					    $('#query-result-container table').table2excel({name: 'Data query export'});});
				  
				  </script>
				  </p>
				  ";
            break;  
			
		default:
			echo "Unhandled view (in api/api.php): '$view'";
			break;
 }
 
?>