<?php 
 /*
  * Handles the user object
  *
  * Copyright  William Sengdara (c) 2015
  *
  * Created:
  * Updated:
  */

/* database */
require_once('database.php');

$users = new users();

/*
 * user object
 */
class user {
	public  $id = null;
	private $props = null;
	
	function __construct($id, $props) {
		global $database;
		
		$this->props = $props;
		$this->id = $props['id'];	
	}
	
	function get($prop) {
		global $database;
		
		switch ($prop) {
			case 'id':
				return $this->id;
				break;
				
			case 'user_name':
			case 'profilepic':
			     $sql = "SELECT `$prop`
			             FROM youth
			             WHERE id={$this->id};";
				 $ret = $database->query($sql);
				 if (!$ret || !$ret->num_rows)
					 return "";
				 
				 $row = $ret->fetch_array();
				 return $row[$prop];
				 break;
				 
			default:

				// write the system log
				$action = "FATAL_ERROR";
				$description = "Unhandled case when checking $prop.";
				update_system_log($action, $description);

				throw new Exception("unhandled get()::$prop");
				break;
		}
	}
	
	function set($prop,$val) {
		$database->update($this->group,[$prop=>$val]);
	}
}

/*
 * user authentication
 */
class users {
	function login($username, $password, &$debug = null) {
		global $database;

		// sanitize
		$username = str_sanitize($username);
		$password = str_sanitize($password);
		
		$password = MD5($password);

		$sql = "SELECT *, u.id AS userid 
				  FROM   youth u
              WHERE  u.user_name='$username' AND 
              			u.user_password='$password';";
		$ret = $database->query($sql);
		
		
		
		if (!$ret || !$ret->num_rows) {
			// log this query?
			//update_system_log("LOGIN_TEXT",$sql);
			return false;
		}
		$debug = $sql . '.............';
		$row = $ret->fetch_array();
		
		$userid    = (int) $row['userid']; 
		$logintime = date("Y-m-d H:i:s"); 

		$key_userid = settings::session_userid;
		$key_logintime = settings::session_logintime;
		
		$_SESSION[$key_userid]   = $userid;
		$_SESSION[$key_logintime]=$logintime;
		$sessionid = session_id();
		
		// save the session id
		$sql = "UPDATE youth 
		        SET sessionid='$sessionid' 
				  WHERE id=$userid;";
		$database->query($sql);

		return  new user($userid,$row);
	}
	
	// returns true if user is logged in
	function loggedin() {
		global $database;
		
		$key_userid = settings::session_userid;
		$key_logintime = settings::session_logintime;
		
		$userid    = @ $_SESSION[$key_userid];
		$logintime = @ $_SESSION[$key_logintime];
		
		if (!$userid || !$logintime)		
			return false;
			
		// ensure this user exists!
		$sql = "SELECT * 
				FROM youth 
				WHERE id=$userid;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
			return false;
		
		// ensure this user is active!
		$row = $ret->fetch_array();
		if (!$row['isactive'])
			return false;
		
		// ensure the session_id is same !
		if ($row['sessionid'] != session_id())
			return false;
		
		return ['userid'=>$userid, 
		         0=>$userid,
		        'logintime'=>$logintime,
				1=>$logintime
				];
	}
	
	// returns a user object
	function user($id) {
		global $database;
		
		$sql = "SELECT * 
		        FROM youth
				WHERE id=$id;";
		$result = $database->query($sql);
		if (!$result || !$result->num_rows)
			return false;
		
		$row = $result->fetch_array();
		
		$user = new user($id, $row);
		return $user;
	}
}

 /*
  * Reusable calls
  */
 function view($view) {
	 switch ($view) {
		case 'dialog-login':
			  $login_title = "bbbnbm";
			  $warning_logout = settings::warning_logout;
			  $version = settings::version;
			  
			  return "Deprecated";
			break;
			
	 }
	 
 }
 
  // All method=POST are consumed here so remember to exclude your OP from this filter
  if (isset($_POST) && 
      !empty($_POST) && 
      @ $_POST['action'] != 'add' &&  
      @ $_POST['action'] != 'edit' &&
      @ $_POST['action'] != 'contact' &&
      @ $_POST['action'] != 'shipping')
  {
	  $view = @ $_POST['view'];
	  $view = strtolower($view);
	  
	  switch ($view) {
			case 'toggle-lock':
				$userid = (int) @ $_POST['userid'];	
 	  			$table = @ $_POST['table'];
 	  			$id    = @ $_POST['id'];
 	  												
				// lock/unlock document		
				// check if this document is locked
				$sql = "SELECT 
							    r.name
							FROM
							    users u,
							    user_roles r
							WHERE
							    u.id = $userid AND 
							    u.roleid = r.id";
							    
				 $role  = subquery($sql);
			 	  			
				 switch($role) {
				 	  case 'top_levels':
			 	  			// only the one who locked the document can lock or unlock it

							$sql = "SELECT lockedby_user_id, user_name
									  FROM `$table` d, users u
									  WHERE d.id=$id AND 
									  		  d.lockedby_user_id = u.id;";
									  		  
							$ret = $database->query($sql);
							if (!$ret || !$ret->num_rows){
								// lock the document!
								$sql = "UPDATE 
												`$table`
										  Set 
										  		lockedby_user_id=$userid
										  WHERE 
										  		id=$id";
								$ret = $database->query($sql);
								$failed = !$ret;
								echo $failed ? $database->error : "$fa_lock You have locked the document. Other users will not be allowed access to it until you unlock it.";
							}
							else {
								$row = $ret->fetch_array();
								$lockerid = $row['lockedby_user_id'];
								$username = $row["user_name"];
								$locked = $lockerid > 0 ? true : false;
								
								if ($locked){
									if ($lockerid == $userid)
									{
										// unlock the document!
										$sql = "UPDATE 
														`$table`
												  Set 
												  		lockedby_user_id=0
												  WHERE 
												  		id=$id";
										$ret = $database->query($sql);
										$failed = !$ret;
										echo $failed ? $database->error : " You have unlocked the document.";
									}
									else {
										echo "You cannot unlock the document. Only $username can do that.";
									}
								}
							}
			 	  		break;
			 	  		
			 	  	default:
			 	  		echo "You do not have permission to lock or unlock documents. $role";
			 	  		break;
	
				 }
				break;					
					
		  case 'deleteitem':
			$table = @ $_POST['table'];
			$id    = @ $_POST['id'];
			
			switch (strtolower($table))
			{
				case 'documents':
					// get filename
					$filename = "";
					
					$sql = "SELECT filename 
							  FROM `$table` 
							  WHERE id=$id;";
					$ret = $database->query($sql);
					if (!$ret || !$ret->num_rows)
					{}
					else
					{
						$row = $ret->fetch_array();
						$filename = $row['filename'];
						
						if (file_exists($filename))
							@unlink($filename);						
					}

					$sql = "DELETE FROM `$table` 
							  WHERE id=$id;";
					$ret = $database->query($sql);
					if ($ret)
						echo "true";
					else
						echo $database->error;
					
					break;
					
				default:
					$sql = "DELETE FROM 
							  `$table` 
							  WHERE id=$id;";
					$ret = $database->query($sql);
					
					if ($ret)
						echo "true";
					else
						echo $database->error;
						
					break;
			}
			break;
			
		  case 'upload-international-agreement':
			   $userid_         = addslashes(trim(@ $_POST['userid'])); //dont overwrite the logged in user id
			   $title           = addslashes(trim(@ $_POST['title']));
			   $reference       = addslashes(trim(@ $_POST['reference']));
			   $category        = addslashes(trim(@ $_POST['category']));
			   $parties         = addslashes(trim(@ $_POST['parties']));
			   $enddate         = addslashes(trim(@ $_POST['enddate']));
			   $dateenteredinto = addslashes(trim(@ $_POST['date_entered_into']));
			   $dateratified    = addslashes(trim(@ $_POST['date_ratified']));
			   $datedeposited   = addslashes(trim(@ $_POST['date_deposited']));
			   $keywords        = addslashes(trim(@ $_POST['keywords']));
			   $lawyerassigned  = addslashes(trim(@ $_POST['lawyer_assigned']));
			   $status_id       = addslashes(trim(@ $_POST['status_id']));
			   $lateral         = addslashes(trim(@ $_POST['lateral']));
			   $countryorganid  = addslashes(trim(@ $_POST['countryorganid']));
			   $returnurl       = addslashes(trim(@ $_POST['returnurl']));
			   
				// make sure we don't have the same document?
				switch ($lateral)
				{
					case 'bilateral':
						// countries
						$sql = "SELECT * 
								  FROM 
								  			international_agreements d, 
								  			countries c 
								  WHERE c.id = d.country_id AND 
								  		  d.title='$title';";
						break;
				
					case 'multilateral':
						// organs
						$sql = "SELECT * 
								  FROM 
								  			international_agreements d, 
								  			organs o 
								  WHERE 
								  			o.id = d.organ_id AND 
								  			d.title='$title';";
						break;
						
					default:
						die(alertbuilder("Lateral parameter is required in the request. $lateral.","danger"));
						break;
				}
				

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
					/*
					 * FILE uploads
					 */
					 $buploadedfile = false;
					 
					 //if ($_FILES['filename']['size'] != 0)
					// {
						$target_dir = "uploads/";
						if (!is_dir($target_dir))
							mkdir($target_dir);

						// generate random filename & set extension							
						$file          = basename($_FILES["filename"]["name"]);
						$fileextension = get_file_extension($file);
						$file          = randomPassword();
						$file          = "$file.$fileextension";
						$target_file   = $target_dir . $file;
						$uploadOk      = 1;

						if (@ move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
							//echo "The file ". basename( $_FILES["profilepic"]["name"]). " has been uploaded.";
						} 
						else 
						{
							$fa_share = font_awesome('fa-share');
							die( alertbuilder("Could not upload the document. Check /var/logs/apache2","danger"));
						}
					
						$buploadedfile = true;
						// write to db!
						
						switch ($lateral)
						{
							case 'bilateral':
								// uploads to countries
								$sql = "INSERT INTO international_agreements
										(entrydate,user_id,filename, title, category, parties, enddate, date_entered_into, date_ratified, date_deposited,keywords,lawyer_assigned, status_id,reference,lateral,country_id,organ_id)
										VALUES
										(NOW(),$userid_,'$target_file','$title','$category', '$parties', '$enddate','$dateenteredinto','$dateratified','$datedeposited','$keywords','$lawyerassigned',$status_id,'$reference','$lateral',$countryorganid,0);";
										
								break;
						
							case 'multilateral':
								// uploads to organs
								$sql = "INSERT INTO international_agreements
										(entrydate,user_id,filename, title, category, parties, enddate, date_entered_into, date_ratified, date_deposited,keywords,lawyer_assigned,status_id,reference,lateral,country_id,organ_id)
										VALUES
										(NOW(),$userid_,'$target_file','$title','$category', '$parties', '$enddate','$dateenteredinto','$dateratified','$datedeposited','$keywords','$lawyerassigned',$status_id,'$reference','$lateral',0,$countryorganid);";																	
								break;
								
							default:
								die(alertbuilder("Unhandled lateral parameter in the request. $lateral.","danger"));
								break;
						}

						$ret = $database->query($sql);
						if (!$ret)
							die(alertbuilder($database->error));
						
						echo "<small>Document was successfully uploaded.</small>
							   <script>
								 window.location.href=\"$returnurl\";
								</script>";
				}		
				else
				{
					echo "<h1>There is already a document of that type.</h1>";
				}
			break;
			
		  case 'upload':
		  	// normal upload
	 		// required fields
			$required = array('enddate',
								   'title',
									'parties',
									'category',
									'date_entered_into',
									'keywords', 
									'lawyer_assigned', 
									'status_id');

				foreach($required as $field){
						$_POST[$field] = addslashes(trim(@ $_POST[$field]));

						if ($_POST[$field] == ""){
							$errors = missing_parameter($field);
							die($errors);
						}
				}	
				
			   $omasid_   = @ $_POST['omasid'];
			   $userid_   = @ $_POST['userid'];
			   $typename_ = @ $_POST['typename'];
			   $enddate   = @ $_POST['enddate'];
			   $title     = @ $_POST['title'];
			   $category  = @ $_POST['category'];
			   $parties   = @ $_POST['parties'];
			   $dateenteredinto = @ $_POST['date_entered_into'];
			   $keywords  = @ $_POST['keywords'];
			   $lawyerassigned  = @ $_POST['lawyer_assigned'];
			   $status_id = @ $_POST['status_id'];
			   $returnurl = @ $_POST['returnurl'];
			   
				// make sure we don't have the same document?
				$sql = "SELECT * 
						FROM documents d, types t
						WHERE d.omas_id=$omasid_ AND 
						      d.title='$title' AND
						      d.type_id = t.id AND 
						      t.name = '$typename_' AND 
							   d.dateenteredinto='$dateenteredinto';";
							   
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
					// match id for type with name
					$typeid_ = 1;
					
					$sql = "SELECT id 
					        FROM types 
					        WHERE name='$typename_' LIMIT 1";
					
					$ret = $database->query($sql);
					if (!$ret || !$ret->num_rows)
					{}
					else
					{
						$row = $ret->fetch_array();
						$typeid_ = $row['id'];
					}

					/*
					 * FILE uploads
					 */
					 $buploadedfile = false;
					 
//					 if ($_FILES['filename']['size'] != 0)
//					 {
						$target_dir = "uploads/";
						if (!is_dir($target_dir))
							mkdir($target_dir);

						// generate random filename & set extension							
						$file          = basename($_FILES["filename"]["name"]);
						$fileextension = get_file_extension($file);
						$file          = randomPassword();
						$file          = "$file.$fileextension";
						$target_file   = $target_dir . $file;
						$uploadOk      = 1;

						if (@ move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
							//echo "The file ". basename( $_FILES["profilepic"]["name"]). " has been uploaded.";
						} 
						else 
						{
							$fa_share = font_awesome('fa-share');
							die( "Could not upload the document. Check /var/logs/apache2");
						}
					
						$buploadedfile = true;
						// write to db!
						$sql = "INSERT INTO documents
								(entrydate,omas_id,user_id,type_id, filename, enddate, title,   category,  parties,  date_entered_into,keywords, lawyer_assigned, status_id)
								VALUES
								(NOW(),   $omasid_,$userid_,$typeid_,'$target_file','$enddate','$title','$category', '$parties', '$dateenteredinto','$keywords', '$lawyerassigned', $status_id);";

						$ret = $database->query($sql);
						if (!$ret)
							die('Error: ' .$database->error);
						else
						{											
							echo "<small>Document was uploaded and saved successfully to application.</small>
							      <script>
								    window.location.href = '$returnurl';
								   </script>";
						}
				}		
				else
				{
					echo "<h1>There is already a document of that type for this application</h1>";
				}
			break;
			
		  case 'authenticate':
			header('Content-Type: application/json');
			
		    // this is a post request!
		    // let us try to login this person!
			$result = $users->loggedin();
			if (!$result){
				$username = @ $_POST['user_name'];
				$password = @ $_POST['user_password'];
				
				$debug = "";
				
				// try to log the user in
				$user = $users->login($username, $password, $debug);
				$action = 'LOGIN_FAIL';
				
				if (!$user) {
					$error = 'Unable to login. Check your username or password or contact the administrator.';
					// log this failed attempt!
					$description = $error;
					update_system_log($action, $description);	

					echo json_encode(['result'=>false, 
									      'view'=>$view,
									      'status'=>$error,
									      'debug'=>$debug,
									      'html'=>"<div class='alert alert-danger'>
									      				<span><li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;Unable to login. Check your username or password or contact the administrator.
									      			   </span>
									      			</div>"
									 ]);	
					exit;
				}	
				
				/* update lastloggedin time */
				$userid = $user->get('id');
					
				$logintime = @ $_SESSION[ settings::session_logintime];
				
				$action = "LOGIN_SUCCESS";
				$description = "User has successfully logged into the system. Details: userid: $userid, logintime: $logintime";
				update_system_log($action, $description);
				
				/* update lastloggedin time */
				$sql = "UPDATE users 
						SET lastlogin=NOW()
						WHERE id=$userid;";
				$database->query($sql) or update_system_log($action, "Failed to update user session, logout time. Error: {$database->error}"); ;						
							
				echo json_encode(['result'=>true, 
								  'view'=>$view,
								  'status'=>'You have been successfully logged in.',
								  'html'=>"<div class='alert alert-success'><span><li class='fa fa-fw fa-info-circle'></li>&nbsp;You have been successfully logged in.</span></div>"
								 ]);
				exit;
			}

			echo json_encode(['result'=>false, 
							  'view'=>'authenticate',
							  'status'=>'You are already logged in.',
							  'html'=>"<div class='alert alert-danger'><span><li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;You are already logged in.</span></div>"
							 ]);			
			break;
			
		 default:
				header('Content-Type: application/json');
				echo json_encode(['result'=>false, 
								  'view'=>$view,
								  'status'=>'Unhandled view called'
								 ]);	
			break;
	  }						
						
	  exit;
  }
  
 /*
  * This will return an array of fields formatted as table headers
  * and tbody content
  */
function row_to_table($table, $ret, $default) {
	    $i = 0;
		$j = 0;
		$data = $default;
		$fields = "";
		$arr_fields = [];
		
		if ($ret) {
			while ($fld=mysqli_fetch_field($ret))
			{
				$fld_name = $fld->name;
				$fld_name = ucfirst($fld_name);
				
				$fields .= "<th>$fld_name</th>";

				// store fieldnames in array so we follow 
				// same field order when we spit out data
				$arr_fields[] = $fld->name;
			}

			// reset the recordset or you wont display any data
			mysqli_data_seek($ret,0);
			
			// spitting out data following field names
			$data = "<i>There is currently no data available.</i>";
			if ($ret->num_rows) $data = "";
			
			while ($res = $ret->fetch_array())
			{
				$data .= "<tr>";
				foreach ($arr_fields as $fld) {
					switch (strtolower($table)) {
							case 'system_log':
								switch (strtolower($fld)) {
									case 'ipaddress':
										$ip = $res[$fld];
										
										if (is_valid_ip($ip))
											$ip = "<a href='#' onclick=\"dlg_ip('$ip');\");\" data-toggle='tooltip' title='View location'><li class='fa fa-fw fa-search'></li>&nbsp;$ip</a>";
										
										$data .= "<td>$ip</td>";									
										break;
										
									case 'id':
										$id = $res[$fld];
										$id = "<a href='#' onclick=\"delete_row('$table','id',$id);\" data-toggle='tooltip' title='Delete this system log entry'><li class='fa fa-fw fa-trash'></li></a>";
										$data .= "<td>$id</td>";
										break;
										
									default:
										$data .= "<td>{$res[$fld]}</td>";
										break;
								}
								break;
								
							default:
								$data .= "<td>{$res[$fld]}</td>";
								break;
					}
				}

				$data .= "</tr>";
			}
		}
		return ['fields'=>$fields, 'data'=>$data];
}	
?>
