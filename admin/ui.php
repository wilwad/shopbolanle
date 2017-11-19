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
				
			case 'roleid':
			case 'rolename':
			    $sql = "SELECT *, r.name AS rolename
						FROM users u
					    INNER JOIN user_roles r
						ON u.roleid = r.id AND u.id={$this->id};";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows){
					// write the system log
					$action = "FATAL_ERROR";
					$description = "Unexpected zero while querying $prop. SQL: $sql";
					update_system_log($action, $description);

					throw new Exception("Fatal Error: Unable to retrieve role name for role id");	
				}

				$row = $ret->fetch_array();
				return $row[$prop];
				break;
				
			case 'user_name':
			case 'profilepic':
			     $sql = "SELECT $prop 
			             FROM users 
			             WHERE id={$this->id};";
				 $ret = $database->query($sql);
				 if (!$ret || !$ret->num_rows)
					 return "";
				 
				 $row = $ret->fetch_array();
				 return $row[$prop];
				 break;
						
			case 'fname':
			case 'sname':
			case 'title':
			case 'initials':
			case 'dob':
			case 'address':
			case 'contactno':
			case 'email':
			case 'cellphone':			
			     $sql = "SELECT $prop 
			             FROM user_profiles 
			             WHERE id={$this->id};";
				 $ret = $database->query($sql);
				 if (!$ret || !$ret->num_rows)
					 return "";
				 
				 $row = $ret->fetch_array();
				 return $row[0];
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
	function login($username, $password) {
		global $database;

		// sanitize
		$username = str_sanitize($username);
		$password = str_sanitize($password);
		
		$password = MD5($password);
		
		$sql = "SELECT *, u.id AS userid 
				  FROM users u, user_roles ur
              WHERE u.isactive=1 AND 
              		  u.roleid = ur.id AND 
                	  ur.isactive=1;";
                
		$result = $database->query($sql);
		
		if (!$result || !$result->num_rows) {
			// log this query?
			//update_system_log("LOGIN_TEXT",$sql);
			return false;
		}
		
		$found = false;
		
		while ($row = $result->fetch_array()){
 			if ($row['user_name'] == $username AND 
 				 $row['user_password'] == $password)
 		  { 
				 $userid    = (int) $row['userid'];
				 $found = true;
				 break;
			}
		}
	 
	   if (!$found)
	   	return false;
	   	
		$logintime = date("Y-m-d H:i:s"); 

		$key_userid = settings::session_userid;
		$key_logintime = settings::session_logintime;
		
		$_SESSION[$key_userid]   = $userid;
		$_SESSION[$key_logintime]=$logintime;
		$sessionid = session_id();
		
		// save the session id
		$sql = "UPDATE users 
		        SET sessionid='$sessionid' 
				WHERE id=$userid;";
		$database->query($sql);
		
		$user = new user($userid,$row);
		return $user;
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
				FROM users 
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
		        FROM users 
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
			  $brandtext =  settings::brandtext;
			  $login_title = settings::login_title;
			  $warning_logout = settings::warning_logout;
			  $title   = settings::title;
			  $version = settings::version;
			  
			  return " <!-- start -->
			  <style>
					/*
					 * Specific styles of signin component
					 */
					 
					/*
					 * General styles
					 */
					 body {
						/*margin-top: 30px !important;*/
						background-attachment: fixed;
						background: rgba(221, 221, 221, 0.52);
					}
					
					#container {
                        background: #ece4d9;
                        -webkit-box-shadow: 1px 1px 1px rgba(128, 128, 128, 0.28);
                        -moz-box-shadow: 1px 1px 1px rgba(128, 128, 128, 0.28);
                        box-shadow: 1px 1px 1px rgba(128, 128, 128, 0.28);
                        height: 100%;
					}
						
					.card-container.card {
					    max-width: 350px;
					    /*padding: 40px 40px;*/
					}
					
					.btn {
					    font-weight: 700;
					    height: 35px;
					    -moz-user-select: none;
					    -webkit-user-select: none;
					    user-select: none;
					    cursor: default;
					}
					
					/*
					 * Card component
					 */
					.card {
					    background-color: #FFFFFF;
					    /* just in case there no content*/
					    padding: 20px 25px 30px;
					    margin: 0 auto 25px;
					    /*margin-top: 50px;*/
					    /* shadows and rounded borders */
					    -moz-border-radius: 2px;
					    -webkit-border-radius: 2px;
					    border-radius: 2px;
					    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
					    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
					    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
					}
					
					.profile-img-card {
					    /*
					    width: 96px;
					    height: 96px;
					    */
					    margin: 0 auto;
					    display: block;
					    /*
					    -moz-border-radius: 50%;
					    -webkit-border-radius: 50%;
					    border-radius: 50%;
					    */
					}
					
					/*
					 * Form styles
					 */
					.profile-name-card {
					    font-size: 16px;
					    font-weight: bold;
					    text-align: center;
					    margin: 10px 0 0;
					    min-height: 1em;
					}
					
					.reauth-email {
					    display: block;
					    color: #404040;
					    line-height: 2;
					    margin-bottom: 10px;
					    font-size: 14px;
					    text-align: center;
					    overflow: hidden;
					    text-overflow: ellipsis;
					    white-space: nowrap;
					    -moz-box-sizing: border-box;
					    -webkit-box-sizing: border-box;
					    box-sizing: border-box;
					}
					
					.form-signin #inputEmail,
					.form-signin #inputPassword {
					    direction: ltr;
					    height: 44px;
					    font-size: 16px;
					}
					
					.form-signin input[type=email],
					.form-signin input[type=password],
					.form-signin input[type=text],
					.form-signin button {
					    width: 100%;
					    display: block;
					    margin-bottom: 10px;
					    z-index: 1;
					    position: relative;
					    -moz-box-sizing: border-box;
					    -webkit-box-sizing: border-box;
					    box-sizing: border-box;
					}
					
					.form-signin .form-control:focus {
					    border-color: rgb(104, 145, 162);
					    outline: 0;
					    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
					    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
					}
					
					.btn.btn-signin {
					    /*background-color: #4d90fe; */
					    background-color: rgb(104, 145, 162);
					    /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
					    padding: 0px;
					    font-weight: 700;
					    font-size: 14px;
					    height: 35px;
					    -moz-border-radius: 3px;
					    -webkit-border-radius: 3px;
					    border-radius: 3px;
					    border: none;
					    -o-transition: all 0.218s;
					    -moz-transition: all 0.218s;
					    -webkit-transition: all 0.218s;
					    transition: all 0.218s;
						text-align: center;
					}
					
					.btn.btn-signin:hover,
					.btn.btn-signin:active,
					.btn.btn-signin:focus {
					    background-color: rgb(12, 97, 33);
					}
					
					.forgot-password {
					    color: rgb(104, 145, 162);
					}
					
					.forgot-password:hover,
					.forgot-password:active,
					.forgot-password:focus{
					    color: rgb(12, 97, 33);
					}			  
			  </style>
        <div class='card card-container' id='login-dialog'>
          <h2>&nbsp;</h2>
            
			 <h4 style='text-align:center;'>
			 	$brandtext
			 	<BR><BR>
			 	<small>$login_title</small>
			 </h4>

			 <hr>

			<form  class='form-signin' onsubmit='return false;'>
                <span id='reauth-username' class='reauth-username'></span>
                <input type='text'     id='user_name' name='user_name' class='form-control' value='' title='Please enter you username' placeholder='Enter your username' required autofocus>
                <input type='password' id='user_password' name='user_password' class='form-control' value='' title='Please enter your password' placeholder='Enter your password' required autofocus>
                
                <div id='loginMsg'></div>

                <button  id='btn-login' class='btn btn-lg btn-primary btn-block btn-signin'>Login</button>
            </form>
        </div><!-- /card-container -->
					  
			<script>
			  /*
			   * when the document is loaded and we have a login dialog,
			   * set focus on username textbox and listen for keypress
			   * ENTER cos this is not a form so we don't get default action
			   */							   
			  $(document).ready(function(){
				  // setfocus to username textbox
				  window.setTimeout(function(){
					if ($('#login-dialog').length) {
						console.log('ses: login-dialog exists, select username field');
						$('#user_name').focus();
					}
				  },1000);	

				  console.log('listening for keypress ENTER');
				  $(document).on('keypress',function(event){
					  if (event.which == 13) {
						  $('#btn-login').trigger('click');
					  }
				  });
			  });
			</script>";		
			break;
			
	 }
	 
 }
 
  // All method=POST are consumed here so remember to exclude your OP from this filter
  if (isset($_POST) && 
      !empty($_POST) && 
      @ $_POST['action'] != 'add' &&  
      @ $_POST['action'] != 'edit' &&
      @ $_POST['action'] != 'register' &&
      @ $_POST['action'] != 'unsubscribe' &&
      @ $_POST['action'] != 'view' &&
      @ $_POST['action'] != 'shipping' &&
      @ $_POST['action'] != 'cart'
      )
  {
	  $view = @ $_POST['view'];
	  $view = strtolower($view);
	  
	  switch ($view) {
		  case 'generic-add':
		  		$fields = array();
		  		$data   = array();
		  		
		  		$table     = @ $_POST['table'];
				$returnurl = @ $_POST['returnurl'];
				
				foreach($_POST as $key=>$val){
					switch ($key){
						case 'view':
						case 'extra':
						case 'table':
						case 'returnurl':
							break;
							
						default:					
							$fields[] = $key;
							$data[] = addslashes(htmlentities($val));								
							break;
					}
				}
				
				$fields = implode(",", $fields);
				$data = implode("','", $data);
				
				$sql = "INSERT INTO `$table`($fields)VALUES('$data');";
				
				// run the query
				$ret = $database->query($sql) or die("<p class='alert alert-warning'>{$database->error}</p>");
				
				echo "<p class='alert alert-success'>Record successfully added to application.</p>
				      <script>
					    window.location.href='$returnurl';
					   </script>";
				break;
				  	
		  case 'generic-add-companies':
		  		$fields = array();
		  		$data   = array();
		  		
		  		$table     = @ $_POST['table'];
				$returnurl = @ $_POST['returnurl'];
				
				foreach($_POST as $key=>$val){
					switch ($key){
						case 'view':
						case 'extra':
						case 'table':
						case 'returnurl':
						case 'user_id':
							break;
							
						default:					
							$fields[] = $key;
							$data[] = $val;								
							break;
					}
				}
				
				$fields = implode(",", $fields);
				$data = implode("','", $data);
				
				$sql = "INSERT INTO `$table`($fields)VALUES('$data');";
				
				// run the query
				$ret = $database->query($sql) or die("<p class='alert alert-warning'>{$database->error}</p>");
				$newid = $database->insert_id;
				$returnurl = "?view=list-companies&action=summary&id=$newid";
				
				echo "<p class='alert alert-success'>Record successfully added to application.</p>
				      <script>
					    window.location.href='$returnurl';
					   </script>";
				break;
								  	
		  case 'generic-edit':
		  		$recordid    = (int) @ $_POST['recordid'];
		  		$fields_data = "";		  		
		  		$table     	 = @ $_POST['table'];
				$returnurl 	 = @ $_POST['returnurl'];
				
				foreach($_POST as $key=>$val){
					switch ($key){
						case 'view':
						case 'extra':
						case 'table':
						case 'returnurl':
						case 'user_id':
						case 'youth_id':
						case 'recordid':
							break;
							
						default:					
							$val = strip_tags($val);
							$fields_data .= "$key='$val',";						
							break;
					}
				}
				
				$fields_data = substr($fields_data, 0, strlen($fields_data)-1);
				$sql = "UPDATE `$table` 
						  SET $fields_data
						  WHERE id=$recordid;";

				// run the query
				$ret = $database->query($sql) or die("<p class='alert alert-warning'>{$database->error}</p>");
				
				echo "<p class='alert alert-success'>Record successfully updated.</p>
				      <script>
					    window.location.href='$returnurl';
					   </script>";		  
		  		break;
		  		
		  case 'upload':
		       $table_     = @ $_POST['table'];
			    $youthid_    = (int) @ $_POST['youthid'];
			    $title       = @ $_POST['title'];
			    $userid_     = (int) @ $_POST['userid'];
			    $filetypeid_ = (int) @ $_POST['file_type_id'];
				 $returnurl   = @ $_POST['returnurl'];

					/*
					 * FILE uploads
					 */
					 $buploadedfile = false;
					 
					 $table_ = strip_tags($table_);
					 $title = strip_tags($title);
					 
					 if ($_FILES['filename']['size'] != 0)
					 {
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
						    // Check $_FILES['upfile']['error'] value.
						    switch ($_FILES['filename']['error']) {
						        case UPLOAD_ERR_OK:
						            break;
						        case UPLOAD_ERR_NO_FILE:
						            throw new RuntimeException('No file sent.');
						        case UPLOAD_ERR_INI_SIZE:
						        case UPLOAD_ERR_FORM_SIZE:
						            throw new RuntimeException('Exceeded filesize limit.');
						        default:
						            throw new RuntimeException('Unknown errors.');
						    }
						}
					
						$buploadedfile = true;

						$sql = "INSERT INTO 
												`{$table_}`
												(entrydate,youth_id,user_id,file_type_id, title, filename)
								  VALUES(
											NOW(),$youthid_,$userid_,$filetypeid_,'$title', '$target_file');";

						$ret = $database->query($sql);
						if (!$ret)
							die($database->error);
						else
						{							
							$type = "$filetypeid_";
							$sql  = "SELECT 
													name 
										FROM 
													file_types 
										WHERE 
													id=$filetypeid_
										LIMIT 1;";
													
							$ret  = $database->query($sql);
							if (!$ret || !$ret->num_rows)
							{}
							else
							{
								$row = $ret->fetch_array();
								$type = $row['name'];
							}

							// don't choke on apostrophe
							$type = addslashes($type);
							 
							// write to log
							/*
							$table_ = str_replace("documents", "log", $table_);
							$sql = "INSERT INTO `$table_`
									  (entrydate, youth_id, user_id, action, description)
										VALUES(NOW(), $youthid, $userid_, 'Document upload', 'A document was uploaded ($type)');";
							$database->query($sql);
							*/
							
							echo "<span style='color:green'>Document was uploaded and saved successfully to application.</span>
							      <script>
							       //alert('$returnurl');
								    window.location.href='$returnurl';
								   </script>";
						}
					 }
				//}		
				/*
				else
				{
					echo "<h3>There is already a document of that type for this application uploaded.</h3>";
				}
				*/
			break;
				  	
		  case 'upload-youth-profilepic':
		  	// normal upload
	 		// required fields
			$required = array('youthid',/*'profilepic',*/ 'returnurl');

				foreach($required as $field){
						$_POST[$field] = addslashes(trim(@ $_POST[$field]));

						if ($_POST[$field] == ""){
							//$errors = missing_parameter($field);
							die("Some fields have not been field in: $field");//$errors);
						}
				}	
				
			   $youthid    = (int) @ $_POST['youthid'];
			   $returnurl  = @ $_POST['returnurl'];
			   
			   // verify the youth exists before we upload
			   $sql = "SELECT profilepic 
			   		  FROM youth 
			   		  WHERE id=$youthid;";
			   $ret = $database->query($sql);
			   if (!$ret || !$ret->num_rows)
			   {
			   	die("Youth does not exist.");
			   }
			   
			   $row = $ret->fetch_array();
			   $profilepic = $row['profilepic'];
			   
			   if (file_exists($profilepic))
			   {
			   	unset($profilepic);
			   }
			   
				/*
				 * FILE uploads
				 */
			 $buploadedfile = false;

				$target_dir = "youth-profiles/";
				if (!is_dir($target_dir))
					mkdir($target_dir);

				// generate random filename & set extension							
				$file          = basename($_FILES["profilepic"]["name"]);
				$fileextension = get_file_extension($file);
				$file          = randomPassword();
				$file          = "$file.$fileextension";
				$target_file   = $target_dir . $file;
				$uploadOk      = 1;

				if (@ move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
					//echo "The file ". basename( $_FILES["profilepic"]["name"]). " has been uploaded.";
				} 
				else 
				{
					$fa_share = font_awesome('fa-share');
					die( "Could not upload the document. Check /var/logs/apache2");
				}
			
				$buploadedfile = true;
				
				// write to db!
				$sql = "UPDATE youth SET profilepic='$target_file'
				        WHERE id=$youthid;";
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
			break;
    
			case 'toggle-field':
				$userid = (int) @ $_POST['userid'];	
 	  			$table  = @ $_POST['table'];
 	  			$field  = @ $_POST['field'];
 	  			$id     = (int) @ $_POST['id'];
 	  			$newval = (int) @ $_POST['newval'];
 	  			
 	  			$sql = "UPDATE `$table` 
 	  					  SET `$field`='$newval' 
 	  					  WHERE id='$id';";
 	  					  
 	  			$ret = $database->query($sql);
 	  			
 	  			if (!$ret || !$ret->num_rows)
 	  				die($sql . ": " . $database->error);
 	  			else
 	  				die('success');
 	  			break;				
					
		  case 'updatequotecol':
		      header('Content-Type: application/json');
		      
			$table = @ $_POST['table'];
			$col   = @ $_POST['col'];
			$val   = @ $_POST['val'];
			$id    = @ $_POST['id'];
			
			$sql   = "UPDATE `$table` 
			          SET $col='$val' 
			          WHERE item_id='$id';";
			$ret = $database->query($sql);
			if ($ret)
				echo json_encode(array("result"=>true, "status"=>"updated"));
			else
				echo json_encode(array("result"=>false, "status"=>$database->error));
		    break;
		      
		  case 'updatesalestax':
		      header('Content-Type: application/json');
		      
			$table = @ $_POST['table'];
			$col   = @ $_POST['col'];
			$val   = @ $_POST['val'];
			$id    = @ $_POST['id'];
			
			$sql   = "UPDATE `$table` 
			          SET $col='$val' 
			          WHERE id='$id';";
			$ret = $database->query($sql);
			if ($ret)
				echo json_encode(array("result"=>true, "status"=>"updated"));
			else
				echo json_encode(array("result"=>false, "status"=>"$sql > ".$database->error));
		    break;
		    
		  case 'deleteitem':
			$table = @ $_POST['table'];
			$id    = @ $_POST['id'];
			
			switch (strtolower($table))
			{
			    case 'quote_items':
					$sql = "DELETE FROM `$table` 
							  WHERE item_id=$id;";
					$ret = $database->query($sql);
					if ($ret)
						echo "true";
					else
						echo $database->error;
	        
			        break;
			        
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
		  	
		  case 'new-store':

		  	// normal upload
	 		// required fields
						
	 		// required fields
			$required = array('name',
			                  /*'description',*/
			                  'telephone',
			                  /*'email',*/
			                  /*'floor',*/
			                  /*'store_no',*/
			                  /*'website',
			                  'color_background',
			                  'banner',
			                  'logo',
			                  'enabled'*/
			                  );
			
				foreach($required as $field){
						$_POST[$field] = addslashes(htmlentities(trim(@ $_POST[$field])));

						if ($_POST[$field] == ""){
							$errors = missing_parameter($field);
							die($errors);
						}
				}	
							
		 		// required fields determine what gets inserted
				$insert_fields = array('userid',
									      'name',
						                  'description',
						                  'telephone',
						                  'email',
						                  'floor',
						                  'store_no',						   
						                  'social_facebook',
						                  'social_twitter',
						                  'social_instagram',
						                  'social_snapchat',
						                  'social_pinterest',
						                  'social_googleplus',
						                  'website',
						                  'color_background',
						                  'color_container',
						                  'color_nav_selected',
						                  'banner',
						                  'logo',
						                  'enabled'
						                  );
			                  
				$name          = addslashes(trim(@ $_POST['name']));
				/*$description   = addslashes(trim(@ $_POST['description']));
				$contactperson = addslashes(trim(@ $_POST['contact_person']));
				$contactnumber = addslashes(trim(@ $_POST['contact_number']));
				$address       = addslashes(trim(@ $_POST['address']));
				$telephone     = addslashes(trim(@ $_POST['telephone']));
				$website       = addslashes(trim(@ $_POST['website']));
				$banner = addslashes(trim(@ $_POST['banner']));
				$logo   = addslashes(trim(@ $_POST['logo']));*/
				$enabled          = (int) addslashes(trim(@ $_POST['enabled']));
			    $userid_          = @ $_POST['userid'];

				// make sure we don't have the same document?
				$sql = "SELECT * 
						FROM `stores`
						WHERE name='$name';";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
					$arr_vals = array();
					
					foreach($insert_fields as $key){
						$key = strtolower($key);
						
						switch($key) {								
							case 'enabled':
								$arr_vals[] = isset($_POST[$key]) ? 1 : 0;								
								break;
								
							default:								
								$arr_vals[] = addslashes(htmlentities(@ $_POST[$key]));
								break;
						}
					}
					
					// change userid to user_id cos thats how its saved in db					
					$str_insert_fields = implode(",", $insert_fields);
					$str_insert_vals = implode("','", $arr_vals);
					$str_insert_vals = "'$str_insert_vals'";					
					
					// change userid to user_id cos thats how its saved in db
					$str_insert_fields = str_replace('userid', 'user_id', $str_insert_fields);
					
					// save youth profile
					$sql = "INSERT INTO `stores`($str_insert_fields)
							 VALUES($str_insert_vals);";

					$ret = $database->query($sql) or	
							die('Error #1: ' .$database->error);
					$companyid = $database->insert_id;
				   $returnurl = "?view=manage-stores&action=edit&id=$companyid";
				   
					echo "Company created successfully.";
					
					echo "<script>
						    window.location.href = '$returnurl';
						   </script>";
				}		
				else
				{
					echo alertbuilder("A store already exists on the system with the name: <b>$name</b>","danger");
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
				
				// try to log me user in
				$user = $users->login($username, $password);
				$action = 'LOGIN_FAIL';
				
				if (!$user) {
					$error = 'Unable to login. Check your username or password or contact the administrator.';
					// log this failed attempt!
					$description = $error;
					update_system_log($action, $description);	

					echo json_encode(['result'=>false, 
									      'view'=>$view,
									      'status'=>$error,
									      'html'=>"<div class='alert alert-danger'><span><li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;Unable to login. Check your username or password or contact the administrator.</span></div>"
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
