<?php
	$view = @ $_GET['view'];

	$action = @ $_GET['action'];
	$table = 'users';

	$fa_plus   = font_awesome('fa-plus');
	$fa_floppy = font_awesome('fa-floppy-o');
	$fa_edit   = font_awesome('fa-edit');
	$fa_remove = font_awesome('fa-remove');
	$fa_phone  = font_awesome('fa-phone');
        $fa_info  = font_awesome('fa-info-circle');
        $fa_users = font_awesome('fa-users');
	$fa_user = font_awesome('fa-user');
        $fa_cogs = font_awesome('fa-cogs');
	$fa_envelope = font_awesome('fa-envelope');
	$fa_mobile = font_awesome('fa-mobile');
	$fa_check = font_awesome('fa-check');
	$fa_remove = font_awesome('fa-remove');
		 	                  		 
		$scripts = "<script>
				   var func_success_togglestate = function(data) {
										  console.log('result follows');
										  console.log(data);
										  if (data == 'true')
										  {
											  window.location.href='?view=$view&tab=user_groups';
										  }
										  else
											  alertify.error(data);
										};

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

					function user_delete(id)
					  {
						  BootstrapDialog.confirm('Are you sure you would like to delete this user?', function(ans){
							  if (ans)
								  window.location.href='?view=users&action=delete&id=' + id;
						  });
						  return false;
					  }
					function user_changepassword(userid){
						  var dialogcss = \"<div class='table-responsive'> \
										<table class='table table-no-border'> \
										 <tbody> \
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
										var controls = ['#user_password','#confirm_password'];
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

										var password = $('#user_password');
										var confirm  = $('#confirm_password');

										// check match
										if (password.val().length < 7 ||
										    password.val() != confirm.val())
										{
											alertify.error('Passwords cannot be less than 7 characters or passwords do not match');
											return false;
										}

									  var payload = {'view': 'users-change-password',
													 'userid': userid,
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

                   // init tab collapse
				   $(document).ready(function(){
					 $('#tabs').tabCollapse();
				   });

				   var func_success = function(data) {
										  console.log('result follows');
										  console.log(data);
										  alertify.success(data);
										 if (data == 'true')
											 window.setTimeOut(window.location.reload(),1000);
										};
				   var func_error = function (a,b,c) {
										alertify.error(b + ' ' + c);
										console.log(a,b);
									  };


					/* toggle user group isactive */
				  function delete_from_table(table, id) {
							BootstrapDialog.confirm(\"<li style='color:red' class='fa fa-fw fa-exclamation-triangle'></li>&nbsp;Are you sure you would like to delete this item?\",function(ans){
								if (ans)
								{
								  var payload = {'view': 'delete-from-table',
												 'userid': $userid,
												 'table':table,
												 'id':id
												 };

								  console.log(payload);

								  ajax('api/api.php',
									   'post',
									   'text',
									   payload,
									   func_success,
									   func_error);
								}
							});
					}

					/* delete a user group */
				  function delete_from_table(table, id) {
							BootstrapDialog.confirm(\"<li style='color:red' class='fa fa-fw fa-exclamation-triangle'></li>&nbsp;Are you sure you would like to delete this item?\",function(ans){
								if (ans)
								{
								  var payload = {'view': 'delete-from-table',
												 'userid': $userid,
												 'table':table,
												 'id':id
												 };

								  console.log(payload);

								  ajax('api/api.php',
									   'post',
									   'text',
									   payload,
									   func_success,
									   func_error);
								}
							});
					}

					/* toggle a field (tinyint) 1 or 0 */
					function toggle_state(table, field, id, newvalue){
							  var payload = {'view': 'toggle-state',
											 'userid': $userid,
											 'table': table,
											 'field': field,
											 'id': id,
											 'newvalue': newvalue
											 };

							  console.log(payload);

							  ajax('api/api.php',
								   'post',
								   'text',
								   payload,
								   func_success_togglestate,
								   func_error);
					}
		</script>";

 switch ($action)
 {
	case 'add':
		$required = array("user_name",
				  "user_password",
		                  "fname",
		                  "sname",
		                  "title",
		                  "initials",
		                  "dob",
		                  "email",
		                  "cellphone");
	                  
		           	   
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
										var controls = ['#user_password','#confirm_password'];
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

										var password = $('#user_password');
										var confirm  = $('#confirm_password');

										// check match
										if (password.val().length < 7 ||
										    password.val() != confirm.val())
										{
											alertify.error('Passwords cannot be less than 7 characters or passwords do not match');
											return false;
										}

									  var payload = {'view': 'users-change-password',
													 'userid': userid,
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
		           	                  
		switch ($role){
			/* who is allowed to add users */
			case 'administrators':
				break;

			default:
				$errors = "<p class='alert alert-danger'>Your account is not allowed to perform the requested action.</p>";
				die($errors . "</div>");
				break;
		}

		$user_name    = @ $_POST['user_name'];
		$user_password= @ $_POST['user_password'];
		$isactive     = (int) @ $_POST['isactive'];
		$roleid       = (int) @ $_POST['roleid'];

		$extra        = (int) @ $_POST['extra'];

		$login = "";
		$profile = "";
		$errors_login = "";
		$errors_profile = "";

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
			
			if (!$filledin){
				// handled by the forloop above
			}
			else
			{
				$profilepic = "";
	
				// all filled in
				if ($errors_login == "" && $errors_profile == "")
				{
					// make sure we don't have a user with this name already?
					$sql = "SELECT * 
						FROM users 
						WHERE user_name='$user_name';";
					$ret = $database->query($sql);
					if (!$ret || !$ret->num_rows)
					{
						// upload
						$filename = "-generated-";
	
						/*
						 * FILE uploads
						 */
						 if ($_FILES['profilepic']['size'] != 0)
						 {
							$target_dir = "profiles/";
							if (!is_dir($target_dir))
								mkdir($target_dir);
	
							// generate random filename & set extension							
							$file = basename($_FILES["profilepic"]["name"]);
							$fileextension = get_file_extension($file);
							$file = randomPassword();
							$file = "$file.$fileextension";
	
							$target_file = $target_dir . $file;
							$uploadOk = 1;
	
							if (!is_writable($target_dir))
								die("<p class='alert alert-danger'>$target_dir is not writable.</p>");
								
							if (@ move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
								//echo "The file ". basename( $_FILES["profilepic"]["name"]). " has been uploaded.";
								
								// resize image
								//cropImage($target_file, 200, $target_file);								
							}
							else
							{
								die( "<li style='color:red' class='fa fa-fw fa-exclamation-triangle'></li>&nbsp;
										Could not upload the user profile picture. Please try again.");
							}
	
							$profilepic = $target_file;
						}
	
						// create the user account
						$user_password = MD5($user_password);
						$date = date('Y-m-d H:i:s');
						
						$sql = "INSERT INTO users
						        (entrydate,user_name,user_password, profilepic, isactive, roleid)
						        VALUES('$date','$user_name','$user_password','$profilepic',$isactive,$roleid);";
						$ret = $database->query($sql);
						if (!$ret)
						{
							$error = $database->error;
							$errors_login = "<p class='alert alert-danger'>Error: $error</p>";
							
							// write the system log
							$action = "USER_ADD_FAIL";
							$description = "Could not add new user to system. Error: $error";
							update_system_log($action, $description);	
						}
						else
						{
							
							// this id is the same one used in user_profiles
							$new_userid = $database->insert_id;
							
							$cols_arrs = array();
							$cols_vals = array();
							$cols = "";
							$vals = "";
							
							// append
							$cols_arrs[] = 'id';
							$cols_vals[] = $new_userid;
							
							foreach ($_POST as $key=>$val){
								switch ($key){
								   case 'view':
								   case 'action':
								   case 'userid':
								   case 'extra':
								   case 'user_name':
								   case 'user_password':
								   case 'profilepic':
								   case 'isactive':
								   case 'roleid':
								   case 'entrydate':
								   case 'lastlogin':
								   case 'lastlogout':
								   case 'sessionid':
								   	break;
							   
								   default:
									$cols_arrs[]  = $key;
									$cols_vals[] = "'$val'";
									break;
								}
							}
							
							$cols = implode(',',$cols_arrs);
							$vals = implode(',',$cols_vals);
							
							$table = "user_profiles";
							$sql = "INSERT INTO `$table`($cols) VALUES($vals);";
							//die (alertbuilder($sql,'success'));
							
							$ret = $database->query($sql);
							if (!$ret)
								$errors_profile = "<p class='alert alert-danger'>Error: {$database->error}</p>";
							else
							{
								// write the system log
								$action = "USER_ADD";
								$description = "A new user has been added to the system. 
								User details: userid: $new_userid, username: $user_name. 
								Added by $userid.";
								update_system_log($action, $description);		
								
								// reload page using JS cos header() fails!
								echo "<script>
								window.location.href='?view=users&action=edit&id=$new_userid';
								</script>";
							}
						}
					}
					else
						echo alertbuilder("A user already exists with the user name $user_name.",'danger');
				}
			}
		}
		
		// we need a login page
		$sql = "SHOW FULL columns 
			FROM `users`;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{
			echo "<p class='alert alert-danger'>*Failed to get the field names.</p>";
		}
		else
		{
			while ($row = $ret->fetch_array())
			{
				$input   = "";
				//$login   = "";
				$field   = $row['Field'];
				$val     = @ $_POST[$field];
				$field_  = str_replace("_", " ", $field);
				$comment = $row['Comment'];
				$field_  = $comment ? $comment : ucfirst($field_);
				$star_required = in_array(strtolower($field), $required) ? "<span style='color:red'>*</span>" : "";
				
				switch (strtolower($field)){
					case 'id':
					case 'sessionid':
					case 'passwordexpire':
					case 'lastlogin':
					case 'lastlogout':
					case 'entrydate':
					case 'activation_hash':
						break;

					case 'user_name':
						$input = "<input type='text' autocomplete='no' class='form-control' 
							value='$val' name='$field' id='$field'>";
						$login .=  "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>User name $star_required</label>
								 <div class='col-sm-8'>
								   $input
								 </div>	 
								</div>";
						break;

					case 'user_password':
						$input = "<input type='password' autocomplete='no' class='form-control' 
								value='$val' name='$field' id='$field'>";
						$login .=  "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>Password $star_required</label>
								 <div class='col-sm-8'>
								   $input
								 </div>	 
								</div>";
						break;
					
					case 'profilepic':
						$input = "<input type='file' autocomplete='no' class='form-control' name='$field' id='$field'>";
						$login .=  "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>Profile picture</label>
								 <div class='col-sm-8'>
								   $input
								 </div>	 
								</div>";
						break;


					case 'isactive':
					        $selected1 = $val == 1 ? 'selected' : '';
						$selected2 = $val == 0 ? 'selected' : '';

						$input = "<select name='$field' class='form-control' >
						           <option value='1' $selected1>True</option>
							   <option value='0' $selected2>False</option>
						          </select>";
						$login .=  "<div class='form-group'>
								 <label class='control-label col-sm-3' 
								 for='$field'>Account enabled $star_required</label>
								 <div class='col-sm-8'>
								   $input
								 </div>	 
								</div>";
						break;

					case 'roleid':
						// POST roleids
						$sql = "SELECT id,name 
						        FROM `user_roles`;";
						$select = build_selectbox($sql,$field,$val);
					
						$login .=  "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>Role $star_required</label>
								 <div class='col-sm-8'>
								   $select
								 </div>	 
								</div>";
						break;
						
					default:
						$field_ = ucfirst($field);
						$input = "<input type='text' autocomplete='no' class='form-control' value='$val' 
						name='$field' id='$field'>";
						$login .=  "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>$field_</label>
								 <div class='col-sm-8'>
								   $input
								 </div>	 
								</div>";
						break;
				}

				$login .=  "</tr>";
			}
		}

		// we need personal page
		$table   = "user_profiles";
		$profile = build_form_users($table, $required);

		 $btn_add = "<div class='form-group'>
				 <label class='control-label col-sm-3' for='id'></label>
				 <div class='col-sm-8'>
					<button class='btn btn-sm btn-primary' type='submit'>$fa_floppy Create user</button>
					<a href='?view=users' class='btn btn-sm btn-warning'>Cancel</a>
				 </div>	 
				</div>";
						
	     echo "<h4>$fa_plus Add a new user</h4>
		    <hr>
		   <div class='row'>
		    <div class='col-md-8' style='border-right:1px solid #cacaca;'>
		     <form name='' class='form-horizontal' role='form' method='POST'  
		     	   onsubmit='return validate_form()' enctype='multipart/form-data'>
			   <input type='hidden' name='view' value='$view'>
			   <input type='hidden' name='action' value='add'>
			   <input type='hidden' name='userid' value='$userid'>
			   <input type='hidden' name='extra' value='1'>
			     $errors_login
			     $login
				 
			     $errors_profile
			     $profile
			
			     $btn_add
	     	      </form>
		    </div>
		    <div class='col-md-4'>
		     <h5><strong>Create user</strong><h5>
		     Create a profile for a new user to use the system.
		    </div>
		    </div>
		    $scripts";
		break;

	case 'edit':
		$required = array("user_name",
		                  "fname",
		                  "sname",
		                  "title",
		                  "initials",
		                  "dob",
		                  "email",
		                  "cellphone");
		                  
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
										var controls = ['#user_password','#confirm_password'];
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

										var password = $('#user_password');
										var confirm  = $('#confirm_password');

										// check match
										if (password.val().length < 7 ||
										    password.val() != confirm.val())
										{
											alertify.error('Passwords cannot be less than 7 characters or passwords do not match');
											return false;
										}

									  var payload = {'view': 'users-change-password',
													 'userid': userid,
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
		           		                  
		                  	
		switch ($role){
			case 'administrators':
				break;

			default:
				$errors = "<p class='alert alert-danger'>Your account is not allowed to perform the requested action.</p>";
				die($errors . "</div>");
				break;
		}

		$id = (int) @ $_GET['id'];

		$user_name    = @ $_POST['user_name'];

		$login = "";
		$profile = "";
		$errors_login = "";
		$errors_profile = "";

		// find this user
		$sql = "SELECT *
		        FROM users
			WHERE id=$id;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{
			$errors_login = "<p class='alert alert-danger'>User was not found.</p>";
			die($errors_login);
		}

		$row = $ret->fetch_array();

		// are we editing or just opening the page?
		if (isset($_POST['extra']))
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
			
			if ($filledin == false){
			 // handled above
			}
			else
			{
				// all filled in
				if ($errors_login == "" && $errors_profile == "")
				{
					$profilepic = "";
					
					// make sure we don't have a user with this name already?
					$sql = "SELECT *
			                        FROM users
			                        WHERE user_name='$user_name' AND id<>$id;";
					$ret = $database->query($sql);
					if (!$ret || !$ret->num_rows)
					{
						/*
						 * no user exists
						 */
						 $profilepic    = "";
						 $buploadedfile = false;

						 if ($_FILES['profilepic']['size'] != 0)
						 {
							$target_dir = "profiles/";
							if (!is_dir($target_dir))
								mkdir($target_dir);

							if (!is_writable($target_dir))
								die("Error uploading document: $target_dir is not writable");
								
							// generate random filename & set extension							
							$file = basename($_FILES["profilepic"]["name"]);
							$fileextension = get_file_extension($file);
							$file = randomPassword();
							$file = "$file.$fileextension";

							$target_file = $target_dir . $file;
							$uploadOk = 1;

							if (@ move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
								//echo "The file ". basename( $_FILES["profilepic"]["name"]). " has been uploaded.";
							}
							else
							{
								die( "Fail to upload file");
							}

							$profilepic = $target_file;
							$buploadedfile = true;
						 }
						
						$isactive = (int) @ $_POST['isactive'];
						$roleid   = (int) @ $_POST['roleid'];
						
									
						// update the user account
						if ($buploadedfile)
						{
							// we need to delete the current profilepic
							$sql = "SELECT profilepic 
									FROM users 
									WHERE id=$id;";
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
									SET user_name='$user_name',
									profilepic = '$profilepic',
									isactive=$isactive,
									roleid=$roleid
									WHERE id=$id;";
						}
						else
						{
							// update the user profile
							// but the profile picture is not touched
							$sql = "UPDATE users
									SET user_name='$user_name',
									isactive=$isactive,
									roleid=$roleid
									WHERE id=$id;";
						}

						$ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));

						if (!$ret)
							$errors_login = "<p class='alert alert-danger'>Error: {$database->error}</p>";
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
							        WHERE id=$id;";
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
								WHERE id=$id;";	
							
							$ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
							if (!$ret)
							  echo alertbuilder('Unable to upload logo','danger');
							else
							  echo alertbuilder('Logo uploaded successfully','success');
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
							   	case 'isactive':
							   	case 'roleid':
							   	case 'activation_hash':
							   	     break;
							   		
							   	default:
							   	     $col_val .= "`$key`='$val',";
							   	     break;
							   }
							}
							$col_val = substr($col_val,0,strlen($col_val)-1);
							
							$sql = "UPDATE user_profiles
								SET $col_val
								WHERE id=$id;";
							$ret = $database->query($sql) or 
							die(alertbuilder("Update error: " . $database->error . ", $sql",'danger'));
							
							if (!$ret)
								$errors_profile = alertbuilder("Error: {$database->error}",'danger');
							else
							{
								// write the system log
								$action = "USER_EDIT";
								$description = "User account has been edited. User account: $id. 
								Editing done by userid $userid.";
								update_system_log($action, $description);

								$errors_login = alertbuilder("$fa_check Profile saved successfully.",'success');
							}
						}
					}
					else
						$errors_login = alertbuilder("A user already exists with the user_name $user_name.",'danger');
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
			        WHERE id=$id";	
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
			
			while ($row   = $ret->fetch_array()){
			       $field = $row['Field'];
			       $_POST[$field] = $row2[$field];	
			}
		}

		// we need a login page
		$sql = "SHOW FULL columns 
		        FROM `users`;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{
			$errors_login = "<p class='alert alert-danger'>Failed to get the field names.</p>";
		}
		else
		{
			while ($row0 = $ret->fetch_array())
			{
				$input    = "";
				$field    = $row0['Field']; //Field -- 0
				$col_type = $row0['Type'];
	         		$comment  = $row0['Comment'];
	         
				$field_ = str_replace("_", " ", $field);
				$field_ = $comment ? $comment : ucfirst($field);
				$val    = @ $_POST[$field];
				
				$star_required = in_array(strtolower($field), $required) ? "<span style='color:red'>*</span>" : "";
				
				switch ($field){
					/* ignore this fields */
					case 'id':
					case 'sessionid':
					case 'passwordexpire':
					case 'entrydate':
					case 'lastlogin':
					case 'lastlogout':
					case 'activation_hash':
					//case 'isactive':
						break;
						
					case 'user_name':
						$input =  "<input type='text' class='form-control' value='$val' id='$field' name='$field'>";
						$login .= "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>User name $star_required</label>
								 <div class='col-sm-8'>
								  $input
								 </div>	 
								</div>";
						break;

					case 'user_password':
						$fa_edit = font_awesome('fa-key');
						$input = "<a href='#' onclick='return user_changepassword($id);' 
						class='btn btn-sm btn-primary'>$fa_edit Change password</a>";
						$login .=  "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>Password $star_required</label>
								 <div class='col-sm-8'>
								  $input
								 </div>	 
								</div>";
						break;
						
					case 'profilepic':

					    	$input = "<input type='file' class='form-control' name='$field' id='$field'>";
					    	$id = (int) @ $_POST['id'];
					        $profilepic = "";
					        $sql = "SELECT $field 
					                FROM users WHERE id=$id;";
					        $rt  = $database->query($sql) or die(alertbuilder($sql,'danger'));
					        if (!$rt || !$rt->num_rows){
					        } else {
					          $profilepic = $rt->fetch_array()[$field];
					        }
					
						if (file_exists($profilepic))
						{
							$img = "<img src='$profilepic' class='img-thumbnail' style='width:200px'>
							        <BR>
						            <a href='#' onclick='delete_profilepic($id); return false'>
						            <span class='fa fa-fw fa-trash' style='color:red'></span>&nbsp;Delete profile picture
						            </a>";
						} 

						$login .=  "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>Profile picture</label>
								 <div class='col-sm-8'>
								  $input $img
								 </div>	 
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
							 <label class='control-label col-sm-3' for='$field'>Account enabled</label>
							 <div class='col-sm-8'>
							  $input
							 </div>	 
							</div>";
						break;

					case 'roleid':
						$sql = "SELECT id,name 
						        FROM `user_roles`;";
						$select = build_selectbox($sql,$field,$val);
						
						$login .= "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>Role</label>
								 <div class='col-sm-8'>
								  $select
								 </div>	 
								</div>";
						break;

					default:
						$input = "<input type='text' class='form-control' value='$val' id='$field' name='$field'>";
						$login .=  "<div class='form-group'>
								 <label class='control-label col-sm-3' for='$field'>$field</label>
								 <div class='col-sm-8'>
								  $input
								 </div>	 
								</div>";
						break;
				}
			}
		}

		// we need personal page
		$table   = "user_profiles";
		$profile = build_form_users($table, $required);

		$fa_edit = font_awesome('fa-edit');
		$fa_floppy = font_awesome('fa-floppy-o');
		
		$btn_save = "<div class='form-group'>
						 <label class='control-label col-sm-3' for=''></label>
						 <div class='col-sm-8'>
	                   <button class='btn btn-sm btn-success'>$fa_floppy Update changes</button>
	                   <a href='?view=users' class='btn btn-sm btn-warning'> Cancel</a>
						 </div>	 
						</div>";
		echo "<h4>$fa_edit Edit user profile <small class='pull-right'>Change user's login as well as personal details</small> </h4>
			  <hr>
			  <div class='row'>
			  <div class='col-md-8 border-right'>
			   <form name='' class='form-horizontal' role='form' method='POST' 
			   		onsubmit='return validate_form()' enctype='multipart/form-data'>
				   <input type='hidden' name='view' value='$view'>
				   <input type='hidden' name='action' value='edit'>
				   <input type='hidden' name='id' value='$id'>
				   <input type='hidden' name='extra' value='1'>
		
				   $errors_login
				   $login
	
				   $errors_profile
				   $profile
				   
				   $btn_save
			  </form>
		       </div>
		       <div class='col-md-4'>
		        <h4>Edit user profile</h4>
		        Change user's login as well as personal details
		       </div>
		      </div> <!-- row -->
		      $scripts";
		break;

	case 'delete':
		switch ($role){
			case 'administrators':
				break;

			default:
				$errors = "<p class='alert alert-danger'>Your account is not allowed to perform the requested action.</p>";
				die($errors . "</div>");
				break;
		}

	         $id = @ $_GET['id'];
		 $sql = "DELETE FROM users WHERE id=$id;";
		 $ret = $database->query($sql);
		 if (!$ret)
			 die("<p class='alert alert-danger'>Error: {$database->error}</p>");
		 else
		 {
			 $sql = "DELETE FROM user_profiles WHERE id=$id;";
			 $ret = $database->query($sql);
			 if (!$ret)
				 die("<p class='alert alert-danger'>Error: {$database->error}</p>");

			// write the system log
			$action = "USER_DELETE";
			$description = "User account has been deleted. Userid: $id. Deletion performed by userid $userid.";
			update_system_log($action, $description);

			 // reload page using JS cos header() fails!
			 $view = "users";
			 echo "<script>window.location.href='?view=$view';</script>";
		 }
		 break;

	case 'delete-profile-pic':
	     $id = @ $_GET['id'];
		 // get the image
		 $sql = "SELECT profilepic 
		 	 FROM users WHERE id=$id;";
		 $ret = $database->query($sql);
		 if (!$ret || !$ret->num_rows)
		 {
			 // reload page
			 echo "<script>window.location.href='?view=users&action=edit&id=$id';</script>";
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

			// write the system log
			$action = "USER_PROFILE_DELETE";
			$description = "User profile pic has been deleted. Userid: $id. Deletion performed by userid $userid.";
			update_system_log($action, $description);


			// reload page
			echo "<script>window.location.href='?view=users&action=edit&id=$id';</script>";
		 }
		 break;

	default:

		 $btn_add = "<a href='?view=$view&action=add' class='btn btn-sm btn-primary'>
		             <li class='fa fa-fw fa-plus'></li>&nbsp;Add a new user</a>
			     <p></p>";
		 $body_users = "";
		 
		 // get a list of staff
		 $sql = "SELECT * 
		 			FROM $table 
		 			ORDER BY  id DESC;";
		 			
		 $ret = $database->query($sql);
		 if (!$ret || !$ret->num_rows)
		 {
			 echo "<p class='alert alert-default'><i>There are no $table. Click the Add button to add a $table.</i></p>";
		 }
		 else
		 {
		$profile = "";
		$headers = "";
		$fa_user = font_awesome('fa-user');
		
		// we need personal page
		$sql = "SHOW columns 
				  FROM $table;";
				  
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{
			echo "<p class='alert alert-danger'>Failed to get the field names.</p>";
		}
		else
		{
			$users = "";
			$sql = "SELECT *, u.id as userid, u.entrydate as date_added
			        FROM users u, user_profiles up
					  WHERE u.id = up.id
					  ORDER BY u.user_name ASC;";
					  
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

				$idx = 0;
				
				// reset pointer
				$ret->data_seek(0);
				while ($row = $ret->fetch_array())
				{
					$idx++;
					
					$profilepic = "";

					$id       = $row['userid'];
					$me       = $id == $userid ? "<span class='badge'>Me</span>" : "";
					$username = $row['user_name'];
					$roleid   = $row['roleid'];

					// get the role for this user
					$sql = "SELECT name
					        FROM user_roles
							  WHERE id=$roleid;";
					$user_role = subquery($sql);

					$title    = $row['title'];
					$initials = $row['initials'];
					$fname    = $row['fname'];
					$sname    = $row['sname'];
					$email    = $row['email'];
					$contactno = $row['contactno'];
					$cellphone= $row['cellphone'];

					$profilepic = $row['profilepic'];
					$profilepic = file_exists($profilepic) ? $profilepic : DEFAULT_USER;
					$lastlogin  = $row['lastlogin'];
					$entrydate  = $row['date_added'];
                    
					$enabled  = $row['isactive'];

					$acct_enabled = $enabled ==  1 ? "<span class='badge color-green'>$fa_check</span>" : "<span class='badge color-red'>$fa_remove</span>";
					
					// actions
					$delete       = "<a href='#' title='Delete user' data-toggle='tooltip' data-placement='right'  onclick='return user_delete($id);'><span class='fa fa-fw fa-trash'></span></a>";
					$edit         = "<a href='?view=$view&action=edit&id=$id' title='Edit user profile' data-toggle='tooltip' data-placement='right'><span class='fa fa-fw fa-edit'></span></a>";
					$sendmessage  = "<a href='#' onclick=\"notifications_add($id); return false;\" title='Send a message' data-toggle='tooltip' data-placement='right'><span class='fa fa-fw fa-comment'></span></a>";

					/* 
					 current logged in user cannot
					 -- delete self
					 -- send message to self
				    */
				    
					if ($id == $userid){
						$delete      = "";
						$sendmessage = "";
						$edit        = "<a href='?view=profile' title='Edit your profile' data-toggle='tooltip' data-placement='right'><span class='fa fa-fw fa-edit'></span></a>";
					}

					$user_role = ucfirst($user_role);
                    $storename = "";
					$body_users .= "<tr>
					                 <td>$idx</td>
					                 <td><img class='img-thumbnail' style='width:150px' src='$profilepic'></td>
					                 <td>$username $me </td>
					                 <td>$title $fname $sname <BR> <span class='badge'>$user_role</span></td>
					                 <td>$fa_envelope $email <BR> $fa_phone $contactno <BR> $fa_mobile $cellphone</td>
					                 <td>$acct_enabled</td>
					                 <td><small><span class='fa fa-fw fa-clock-o'></span> <abbr class='timeago' title='$lastlogin'>$lastlogin</abbr></td>
					                 <td><small><span class='fa fa-fw fa-clock-o'></span> <abbr class='timeago' title='$entrydate'>$entrydate</abbr></td>
					                 <td>$sendmessage $edit $delete</td>
							</tr>";
				}
			}


			echo "<h4>$fa_users User Management <small class='pull-right' style='clear:both'>$btn_add</small></h4>
			      <hr>
			      <div class='row'>
				<div class='col-md-12'>
				<div class='table-responsive'>
				 <table class='table table-hover table-striped' id='table-users'>
				  <thead>
				   <tr>
                       <th>#</th>									   
                       <th>Image</th>									   
                       <th>User name</th>	
                       <th>Full name</th>								   
                       <th>Contact</th>								   
                       <th>Enabled</th>
                       <th>Last login</th>		                               
                       <th>Created</th>
                       <th>Actions</th>   
				   </tr>
				  </thead>
				  <tbody>
				   $body_users
				  </tbody>
				 </table>
				</div>
				
				</div>
				</div>
				
				$scripts
				<script>
				 $(document).ready(function(){
				  $('#table-users').dataTable();
				 });
				</script>
				";
			}
		 }

		break;
 }

?>