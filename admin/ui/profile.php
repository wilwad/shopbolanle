<?php
 /*
  * Update the profile of the currently loggedin user
  *
  * This file edits the details of the current user. 
  * The file to edit OTHER users is users.php
  *
  * Created by William Sengdara (c) 2015
  *
  * Created:
  * Updated:
  */
 
  
 @ require_once("database.php");
 
 if (!@$users)
	 die("Error: this file cannot be loaded outside the system.");
 
 $view    = @ $_GET['view'];
 $action  = @ $_GET['action'];

 // icons
 $fa_edit  = font_awesome('fa-edit');
 $fa_check = font_awesome('fa-check');
 
 if ($action == "" || $action == 'profile')
	 $action = "edit";
 
 $table = "user_profiles";

 echo "<h4>$fa_edit Edit your profile <small class='pull-right'>Change your login as well as personal details</small></h4>
       <hr>";

 switch ($action){		
	case 'edit':
		$required = array("user_name",
		                  "fname",
		                  "sname",
		                  "title",
		                  "initials",
		                  "dob",
		                  "email",
		                  "cellphone");
		                  
		$id             = (int) @ $_POST['id'];
		$login          = "";
		$profile        = "";
		$errors_login   = "";
		$errors_profile = "";	

		$user_name 	= @ $_POST['user_name'];
		$profilepic     = @ $_POST['profilepic'];
		
		// find this user
		$sql = "SELECT * 
		        FROM `users`
		        WHERE id=$userid;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{
			$errors_login = "<p class='alert alert-danger'>User was not found. $sql</p>";
			die($errors_login);
		}
		
		$row = $ret->fetch_array();

		// are we editing or just opening the page?
		if (isset($_POST['extra']) && $_POST['extra'] == 1)
		{
			// TODO
			// This needs to be a function so that we wont have problems when we add a new field to the form
			$filledin = true;
			
			foreach ($required as $req){
				if (@ $_POST[$req]== ""){
				    $errors_profile = alertbuilder("Some required fields have not been filled in: <b>$req</b>",'danger');
				    $filledin = false;
				    break;
				}
			}
			
			if ( $filledin == false ){
				// handled by foreach loop above
			}
			else 
			{
				// all filled in
				if ($errors_login == "" && $errors_profile == "")
				{
					// make sure we don't have a user with this name already?
					$sql = "SELECT * 
					        FROM `users` 
					        WHERE user_name='$user_name' 
					        AND id<>$userid;";
					$ret = $database->query($sql);
					if (!$ret || !$ret->num_rows)
					{
						/*
						 * FILE uploads
						 */
						 $buploadedfile = false;
						 $profilepic    = "";
						 
						 if ($_FILES['profilepic']['size'] != 0)
						 {
							$target_dir = "profiles/";
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
								
								// resize image
								//cropImage($target_file, 200, $target_file);
							} 
							else 
							{
								$fa_share = font_awesome('fa-share');
								die( "<li style='color:red' class='fa fa-fw fa-exclamation-triangle'></li>
								&nbsp;Could not upload the user profile picture.");
							}
							
							$profilepic = $target_file;
							$buploadedfile = true;
						 }
					 
						// update the user account
						if ($buploadedfile)
						{
							// we need to delete the current profilepic
							$sql = "SELECT profilepic 
							        FROM users 
							        WHERE id=$userid;";
							$ret0 = $database->query($sql);
							if (!$ret0 || !$ret0->num_rows)
							{
								// nothing found
							}
							else
							{
								$row0 = $ret0->fetch_array();
								$filename = $row0['profilepic'];
								if (file_exists($filename))
									@ unlink($filename);
							}
							
							$sql = "UPDATE users 
								SET `user_name`='$user_name',
								`profilepic` = '$profilepic'
								WHERE id=$userid;";							
						}
						else {
							$sql = "UPDATE users 
								SET `user_name`='$user_name'
								WHERE id=$userid;";
						}
	
						$ret = $database->query($sql);
						if (!$ret)
							$errors_login = alertbuilder("Error: {$database->error}",'danger');
						else
						{
						 /*
						  * logo upload  **/
						 
						 $buploadedfile = false;
						 $logo          = "";
						 $key           = 'logo';
						 
						 if (@ $_FILES[$key]['size'] != 0)
						 {
							$target_dir = "profiles/";
							if (!is_dir($target_dir))
								mkdir($target_dir);

							// generate random filename & set extension							
							$file          = basename($_FILES[$key]["name"]);
							$fileextension = get_file_extension($file);
							$file          = randomPassword();
							$file          = "$file.$fileextension";
							$target_file   = $target_dir . $file;
							$uploadOk      = 1;

							if (@ move_uploaded_file($_FILES[$key]["tmp_name"], $target_file)) {
								//echo "The file ". basename( $_FILES[$key]["name"]). " has been uploaded.";
								
								// resize image
								//cropImage($target_file, 512, $target_file);								
							} 
							else 
							{
								$fa_share = font_awesome('fa-share');
								die( "<li style='color:red' class='fa fa-fw fa-exclamation-triangle'></li>
								&nbsp;Could not upload the logo.");
							}
							
							$logo = $target_file;
							$buploadedfile = true;
						 }
					 
						// update the user account
						if ($buploadedfile)
						{
							// we need to delete the current profilepic
							$sql = "SELECT logo 
							        FROM user_profiless 
							        WHERE id=$userid;";
							$ret0 = $database->query($sql);
							if (!$ret0 || !$ret0->num_rows)
							{
								// nothing found
							}
							else
							{
								$row0 = $ret0->fetch_array();
								$filename = $row0[$key];
								if (file_exists($filename))
									@ unlink($filename);
							}
							
							$sql = "UPDATE user_profiles 
								SET `logo`= '$logo'
								WHERE id=$userid;";	
							
							$ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
							if (!$ret)
							  echo alertbuilder('Unable to upload logo','danger');
						}						 
						 /* logo upload */
						 
							$col_val = "";
							foreach ($_POST as $key=>$val){
							   switch ($key){
							   	case 'action':
							   	case 'view':
							   	case 'extra':
							   	case 'user_name':
							   	case 'id':
							   	case 'logo':
							   	     break;
							   		
							   	default:
							   	     $col_val .= "`$key`='$val',";
							   	     break;
							   }
							}
							$col_val = substr($col_val,0,strlen($col_val)-1);
							
							$sql = "UPDATE user_profiles
								SET $col_val
								WHERE id=$userid;";

							$ret = $database->query($sql) or 
							die(alertbuilder("Update error: " . $database->error . ", $sql",'danger'));
							if (!$ret)
						           $errors_profile = alertbuilder("Error: {$database->error}: $sql;",'danger');
							else
							{
								$errors_login = alertbuilder("$fa_check Profile saved successfully.",'success');								
								
								// reload page using JS cos header() fails!
										
							}							
						}					
					}
					else
						echo alertbuilder("A user already exists with the user_name $user_name.",'danger');
				}			
			}
		}			
		else
		{
			// this page was loaded for viewing 

			$user_name              = $row['user_name'];
			$_POST['user_name']     = $user_name;
			$user_password          = $row['user_password'];
			$_POST['user_password'] = $user_password;
			$isactive               = $row['isactive'];
			$_POST['isactive']      = $isactive;
                        $profilepic             = $row['profilepic'];
			$_POST['profilepic']    = $profilepic;			
			$roleid                 = $row['roleid'];
			$_POST['roleid']        = $roleid;

			$table = "user_profiles";
			
			$sql = "SELECT * 
			        FROM `$table` 
			        WHERE id=$userid";	
			$retp = $database->query($sql);
			if (!$retp || !$retp->num_rows)
			{
				$errors_profile = "<p class='alert alert-danger'>**$view was not found: $sql.</p>";
				echo $errors_profile;
				exit;
			}
			$row2 = $retp->fetch_array();
			
			$sql = "SHOW FULL COLUMNS 
				FROM `$table`;";
			$ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			
			while ($row1   = $ret->fetch_array()){
			       $field = $row1['Field'];
			       $val = $row2[$field];	
			       $_POST[$field] = $val;	
			}
		}
		
		// get comments for the table 
		$table = "users";
		
		// dynamic iteration of columns-values 
		$sql = "SHOW FULL COLUMNS 
		        FROM `$table`;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{
			$errors_login = alertbuilder("Failed to get the field names.",'danger');
		}
		else
		{
		   while ($row0 = $ret->fetch_array())
		   {
			$input   = "";
			$field   = $row0['Field'];
			$val     = @ $_POST[$field];
			$field_  = str_replace("_", " ", $field);
			$comment = $row0['Comment'];
			$field_  = $comment ? $comment : ucfirst($field_);
			$star_required = in_array(strtolower($field), $required) ? "<span style='color:red'>*</span>" : "";
			
			switch ($field){					
				case 'id':
				case 'sessionid':
				case 'passwordexpire':
				case 'entrydate':
				case 'lastlogin':
				case 'lastlogout':
				case 'isactive':
				case 'activation_hash':
				    /* these fields will not show up on the form to be displayed */
					break;
					
				case 'profilepic':
				        $input = "<input type='file' class='form-control' name='$field' id='$field'>";
				        $profilepic = "";
				        $sql = "SELECT $field FROM users WHERE id=$userid;";
				        $rt  = $database->query($sql) or die(alertbuilder($sql,'danger'));
				        if (!$rt || !$rt->num_rows){
				        } else {
				          $profilepic = $rt->fetch_array()[$field];
				        }

					if (file_exists($profilepic))
					{
						$img = "<img src='$profilepic' class='img-thumbnail' style='width:200px'>
						        <BR>
					            <a href='#' onclick='delete_profilepic($userid); return false'>
					            <span class='fa fa-fw fa-trash' style='color:red'></span>
					            &nbsp;Delete profile picture</a>";
					}

					$field_ = "Profile picture";
					
					$login .=  "<div class='form-group'>
							 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
							 <div class='col-sm-8'>$input $img</div>
						    </div>";
					break;
					
				case 'isactive':
				        $selected1 = (int) $val == 1 ? 'selected' : '';
					$selected2 = (int) $val == 0 ? 'selected' : '';
					
					$input = "<select name='$field' id='$field' class='form-control'>
					           <option value='1' $selected1>True</option>
							   <option value='0' $selected2>False</option>
					          </select>";

					$login .= "<div class='form-group'>
					            <label class='control-label col-sm-3' for='$field'>$field_ $field_required</label>
					            <div class='col-sm-8'>$input</div>
				              </div>";					
					break;
					
				case 'roleid':
					$role   = 'undefined';
					$sql    = "SELECT name 
					           FROM users u,
					                user_roles ur
					           WHERE ur.id = u.roleid AND u.id=$userid;";
					$ret    = $database->query($sql);
					if (!$ret || !$ret->num_rows){
					} else {
					  $role = $ret->fetch_array()['name'];
					}
					
					$input  = "<input type='text' class='form-control' readonly value='$role'>";
					$login .=  "<div class='form-group'>
							 <label class='control-label col-sm-3' for='$field'>Role $star_required</label>
							 <div class='col-sm-8'>
							   $input
							 </div>	 
							</div>";
					break;
					
				case 'user_password':
					$fa_edit = font_awesome('fa-key');
					$input   = "<a href='#' onclick='return user_changepassword($userid);' 
					             class='btn btn-sm btn-danger'>$fa_edit Change password</a>";
					$field   = "Password";
					$login  .=  "<div class='form-group'>
					            <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
					            <div class='col-sm-8'>$input</div>
				              </div>";					
					break;
					
				case 'user_name':
					$input = "<input type='text' class='form-control' value='$val' name='$field' id='$field'>";
					$field = "User name";
					$login .= "<div class='form-group'>
					            <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
					            <div class='col-sm-8'>$input</div>
				              </div>";						
					break;
					
				case 'dob':
					$input  = "<input type='text' class='form-control' 
						   value='$val' name='$field' 
						   id='$field' placeholder='YYYY-mm-dd'>";
					$field  = "Date of birth";
					$login .=  "<div class='form-group'>
						            <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
						            <div class='col-sm-8'>$input</div>
					              </div>
					              <script>
					               $(document).ready(function(){
					               	$('#dob').datepicker({'format':'yyyy-mm-dd'});
					               	$('#dob').datepicker().on('changeDate',function(e) {
											$('#dob').datepicker('hide');
										});
					               });
					              </script>";					
					break;
				
				default:
					$input  = "<input type='text' class='form-control' value='$val' id='$field' name='$field'>";
					$login .=  "<div class='form-group'>
					            <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
					            <div class='col-sm-8'>$input</div>
				              </div>";
					break;
			}
		   }
		}	

		$table = "user_profiles";
		$profile = build_form($table,$required);
		
		// validate form by JS
		$required_string = "";
		foreach ($required as $req){
			$required_string .= "'$req',";
		}
		$required_string = substr($required_string,0,strlen($required_string)-1);
		
		$scripts = "<script>
				   var func_success_pwd_changed = function(data) {
										  console.log('result follows');
										  console.log(data);
										  if (data == 'true')
											  window.location.reload();
										  else
											  alertify.error(data);
										};
				   var func_error2 = function (a,b,c) {
										alertify.error(b + ' ' + c);
										console.log(a,b);
									  };
									  
					function user_changepassword(userid){						 
						  var dialogcss = \"<div class='table-responsive'> \
								     <table class='table table-no-border'> \
			<tbody> \
	<tr><th>Old password <span class='required'>*</span></th><td><input type='password' value='' class='form-control' placeholder='Old password' name='user_password_old' id='user_password_old'></td></tr> \
										  <tr><th>Password <span class='required'>*</span></th><td><input type='password' value='' class='form-control' placeholder='Password' name='user_password' id='user_password'></td></tr> \
										  <tr><th>Confirm<span class='required'>*</span></th><td><input type='password' value='' class='form-control' placeholder='Confirm password' id='confirm_password' name='confirm_password'></td></tr>\
										 </tbody> \
										</table></div> \
										<style>\";
						  
							BootstrapDialog.show({
								title: 'Change password',
								message: function(dialog) {
									var content = $(dialogcss);
									return content;
								},
								buttons: [
									{
									label: 'Change password',
									action: function(){
										var controls = ['#user_password_old', '#user_password','#confirm_password'];
										for (i=0; i < controls.length; i++)
										{
											var ctl = $(controls[i]);
											ctl.val(ctl.val().trim());
											if (ctl.val().length == 0)
											{
												alertify.error('Please specify ' + controls[i].split('#')[1]);
												ctl.focus();
												return false;
											}
										}
										
										var passwordold = $('#user_password_old');
										var password    = $('#user_password');
										var confirm     = $('#confirm_password');
										
										// check match
										if (passwordold.val().length < 7 )
										{
											alertify.error('Password cannot be less than 7 characters or passwords do not match');
											return false;
										}

										if (password.val().length < 7 || 
										    password.val() != confirm.val())
										{
											alertify.error('Passwords cannot be less than 7 characters or passwords do not match');
											return false;
										}
										
									  var payload = {'view': 'users-change-password', 
													 'userid': userid,
													 'user_password_old':$('#user_password_old').val(),
													 'user_password':$('#user_password').val()
													 };
													 
									  console.log(payload);
									  
									  ajax('api/api.php', 
										   'post',
										   'text', 
										   payload, 
										   func_success_pwd_changed,
										   func_error2);
									}},					
									{
									label: 'Cancel',
									action: function(dialogItself){
										dialogItself.close();
									}
								}]
							});							 
						 return false;
					}
					
		            function delete_profilepic(id){
						BootstrapDialog.confirm('Are you sure you would like to delete this profile picture?', function(ans){
							if (ans)
							{
								window.location.href = '?view=$view&action=delete-profile-pic&id=' + id;
							}
						});
					}
				
				/*
				 * make sure required fields are filled in before submit
				 */	
				function validate_form(){
				        var required = [$required_string];
				        var required_max = required.length;
				        
					for (var idx=0; idx < required_max; idx++){
					    var $req = $('#'+required[idx]);
					    if ($req.val().trim().length == 0){
					       $req.focus();
					       alertify.error('Field is required: ' + required[idx]);
					       return false;
					    }
					}
					
					return true;
				}
		           </script>";
				   
		$fa_floppy = font_awesome('fa-floppy-o');
		$fa_edit   = font_awesome('fa-edit');
		$fa_remove = font_awesome('fa-remove');
		
		$btn_save = "<div class='form-group'>
				<label class='control-label col-sm-3' for=''></label>
				<div class='col-sm-8'>
					<button class='btn btn-sm btn-success'>$fa_floppy Update changes</button> 
					<a href='?view=users' class='btn btn-sm btn-danger'>$fa_remove Cancel</a>
				</div>
			     </div>";
					  
		echo "<div class='row'>
		       <div class='col-md-8' style='border-right:1px solid #cacaca;'>
			<form class='form-horizontal' role='form' method='POST' 
			      onsubmit='return validate_form()' enctype='multipart/form-data'>
			   <input type='hidden' name='view' value='$view'>
			   <input type='hidden' name='action' value='edit'>
			   <input type='hidden' name='id' value='$userid'>	
			   <input type='hidden' name='extra' value='1'>
				$errors_login
				$login

			    $errors_profile				 
			    $profile		
			    $btn_save
		        </form>
		    
	        	$scripts
		       </div>
		       <div class='col-md-4'>
		         <h5><strong>Edit your profile</strong></h5>
		         Edit your profile by filling in the appropriate fields.
		       </div>
		    </div>";		
			break;

	case 'delete':
	     $id = @ $_GET['id'];
		 $sql = "DELETE FROM `users` WHERE id=$id;";
		 $ret = $database->query($sql);
		 if (!$ret)
			 die("<p class='alert alert-danger'>Error: {$database->error}</p>");
		 else
		 {
			 $sql = "DELETE FROM `$table` WHERE id=$id;";
			 $ret = $database->query($sql);
			 if (!$ret)
				 die("<p class='alert alert-danger'>Error: {$database->error}</p>");

			 // reload page using JS cos header() fails!
			 echo "<script>window.location.href='?view=$view';</script>";
		 }
		 break;
		 
	case 'delete-profile-pic':
	     $id = @ $_GET['id'];
		 // get the image
		 $sql = "SELECT profilepic FROM users WHERE id=$id;";
		 $ret = $database->query($sql);
		 if (!$ret || !$ret->num_rows)
		 {
			 // nothing to change
			 echo "<script>window.location.href='?view=profile';</script>";
		 }
		 else
		 {
			 $row = $ret->fetch_array();
			 $profilepic = $row['profilepic'];
			 if (file_exists($profilepic))
				 @unlink($profilepic);
			 
			// update the profilepic
			 $sql = "UPDATE users 
			         SET profilepic='' 
					 WHERE id=$id;";
			 $ret = $database->query($sql);
			 echo "<script>window.location.href='?view=profile';</script>";
		 }
		 break;
		 
	default:
		 // button add a new member
		 $btn_add = "<a href='?view=$view&action=add' class='btn btn-sm btn-primary'>
		 		<li class='fa fa-fw fa-plus'></li>&nbsp;Add new $view</a>
					 <p></p>";
		 echo $btn_add;
		 
		 // get a list of staff
		 $sql = "SELECT * FROM $table ORDER BY id DESC;";
		 $ret = $database->query($sql);
		 if (!$ret || !$ret->num_rows)
		 {
			 echo "<p class='alert alert-default'><i>There are no $view. Click the Add button to add a $view.</i></p>";
		 }
		 else
		 {
				$profile = "";
				$headers = "";
				
				// we need personal page
				$sql = "SHOW columns FROM $table;";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
					echo "<p class='alert alert-danger'>Failed to get the field names.</p>";
				}
				else
				{
					$data = "";
					
					while ($row = $ret->fetch_array())
					{
						$input = "";
						$profile .=  "<tr>";
						$field = $row[0];
						$headers .= "<th>$field</th>";
					}
					
					$sql = "SELECT * FROM $table ORDER BY id DESC;";
					$ret = $database->query($sql);
					if (!$ret || !$ret->num_rows)
					{
					}
					else
					{
						$row = $ret->fetch_array();
						$keys = array_keys($row);
						$arr_fields = array();
						
						foreach($keys as $field)
						    if (!is_numeric($field))
							$arr_fields[] = $field;
						
						// reset pointer
						$ret->data_seek(0);						
						while ($row = $ret->fetch_array())
						{
							$data .= "<tr>";
							foreach($arr_fields as $field){
								 $val  = $row[$field];
								 
								 switch ($field){
									 case 'id':
										$val = (int) $val;
										$userid = (int) $userid;
										$delete ="<a href='?view=$view&action=delete&id=$val' onclick=\"if (!confirm('Are you sure you would like to delete this $view?')) return false;\"><li class='fa fa-fw fa-trash'></li></a>";
										
										if ($val == $userid)
											$delete = "";

										$val = "<a href='?view=$view&action=edit&id=$val'><li class='fa fa-fw fa-edit'></li></a>&nbsp;
												$delete";
										break;
								 }
								$data .= "<td>$val</td>";
							}
							$data .= "</tr>";	
						}			
					}

					echo "<div class='table-responsive'>
					       <table class='table table-hover'>
						    <thead>
							 $headers
							</thead>
						    <tbody>
							 $data
							</tbody>
						   </table>
					      </div>";
				}				 
		 }
		
		break;		
 }
?>