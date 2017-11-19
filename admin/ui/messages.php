<?php
	if (!@$users)
		die("FATAL ERROR: this file may not be launched outside the system. It can only be included.");

	// check that we are logged in!
	$user = $users->loggedin();
	if (!$user) {
		//echo view( 'dialog-login' );
		exit;
	}

	$userid = $user['userid'];
	$view   = @ $_GET['view'];
	$action = @ $_GET['action'];
	$id     = (int) @ $_GET['id'];

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

	$fa_user = font_awesome('fa-user');

	$select_user_noticeboard = "<select id='userid_to' class='form-control'>";
    $sql = "SELECT u.id as userid, u.user_name, CONCAT(fname, ' ', sname) as fullname
	        FROM users u, user_profiles up
			WHERE u.id = up.id AND u.id <> $userid;";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{
		// nothing
	}
	else
	{
		while ($row = $ret->fetch_array())
		{
			$id            = $row['userid'];
			$username      = $row['user_name'];
			$fullname      = $row['fullname'];
			$select_user_noticeboard .= "<option value='$id'>$username ($fullname)</option>";
		}
	}
	$select_user_noticeboard .= "</select>";
	$total_users = 0;
	$sql = "SELECT COUNT(*)
	        FROM users WHERE id <> $userid;";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{}
	else
	{
		$total_users = $ret->fetch_array()[0];
	}
	$sendtoall_enabled = $total_users == 0 ? 'disabled' : '';

	$scripts = "<script>
	                $(document).ready(function(){
						$('#tabs').tabCollapse();
					});

					function toggle_state(table, field, id, newvalue){
							  var payload = {'view': 'toggle-state',
											 'userid': $userid,
											 'table': table,
											 'field': field,
											 'id': id,
											 'newvalue': newvalue
											 };

							  ajax('api/api.php',
								   'post',
								   'text',
								   payload,
								   funcSuccess,
								   funcError);
					}

					function delete_from_table(table, id){
						BootstrapDialog.confirm('Are you sure you would like to delete this item?',function(ans){
							if (ans){
							  var payload = {'view': 'delete-from-table',
											 'userid': $userid,
											 'table': table,
											 'id': id
											 };

							  console.log(payload);

							  ajax('api/api.php',
								   'post',
								   'text',
								   payload,
								   func_success2,
								   func_error2);
							}
						});
					}

				   var func_success2 = function(data) {
										  console.log('result follows');
										  console.log(data);

										  if (data == 'true'){
											  window.setTimeOut(window.location.reload(),1000);
										  }
										  else
											  alertify.error(data);
										};
				   var func_error2 = function (a,b,c) {
										alertify.error(b + ' ' + c);
										console.log(a,b);
									  };


				  function noticeboard_add(userid) {
						  var dialogcss = \"<div class='table-responsive'> \
										<table class='table table-no-border'> \
										 <tbody> \
										  <tr><th>Heading <span class='required'>*</span></th><td><input type='text' value='' class='form-control' placeholder='Heading' id='heading'></td></tr>\
										  <tr><th>Body <span class='required'>*</span></th><td><textarea value='' class='form-control' placeholder='Body' id='body'></textarea></td></tr> \
										  <tr><th>Visible to all </th><td><input type='checkbox' id='enabled' name='enabled' checked></td></tr> \
										 </tbody> \
										</table></div> \
										<style>\";

							BootstrapDialog.show({
								title: 'Add new noticeboard item',
								message: function(dialog) {
									var content = $(dialogcss);
									return content;
								},
								buttons: [
									{
									label: 'Add item',
									action: function(){
										var controls = ['#heading','#body'];
										for (i=0; i < controls.length; i++)
										{
											console.log('reading ' + controls[i] + '...');

											var ctl = $(controls[i]);
											ctl.val(ctl.val().trim());
											if (ctl.val().length == 0)
											{
												alertify.error('Please specify ' + controls[i].split('#')[1]);
												ctl.focus();
												return false;
											}
										}

									  var payload = {'view': 'noticeboard-add',
													 'userid': userid,
													 'heading':$('#heading').val(),
													 'body': $('#body').val(),
													 'enabled': $('#enabled').prop('checked') ? 1 : 0
													 };

									  console.log(payload);

									  ajax('api/api.php',
										   'post',
										   'text',
										   payload,
										   func_success2,
										   func_error2);
									}},
									{
									label: 'Cancel',
									action: function(dialogItself){
										dialogItself.close();
									}
								}]
							});
						}

				  function notifications_add() {
						  var dialogcss = \"<div class='table-responsive'> \
										<table class='table table-no-border'> \
										 <tbody> \
										  <tr><th>To <span class='required'>*</span></th><td>$select_user_noticeboard</td></tr>\
										  <tr><th></th><td><input $sendtoall_enabled type='checkbox' id='sendtoall' name='sendtoall'> Send to all users <span class='badge'>$total_users</span></td></tr>\
										  <tr><th>Subject <span class='required'>*</span></th><td><input type='text' value='' class='form-control' placeholder='Subject' id='subject'></td></tr>\
										  <tr><th>Body <span class='required'>*</span></th><td><textarea value='' class='form-control' placeholder='Body' id='body'></textarea></td></tr> \
										 </tbody> \
										</table></div> \
										<style>\";

							BootstrapDialog.show({
								title: 'Add new notification',
								message: function(dialog) {
									var content = $(dialogcss);
									return content;
								},
								buttons: [
									{
									label: 'Send message',
									action: function(){
										var controls = ['#userid_to', '#subject','#body'];
										for (i=0; i < controls.length; i++)
										{
											console.log('reading ' + controls[i] + '...');

											var ctl = $(controls[i]);
											ctl.val(ctl.val().trim());
											if (ctl.val().length == 0)
											{
												alertify.error('Please specify ' + controls[i].split('#')[1]);
												ctl.focus();
												return false;
											}
										}

									  var payload = {'view': 'notifications-add',
													 'userid_from': $userid,
													 'userid_to': $('#userid_to').val(),
													 'sendtoall': $('#sendtoall').prop('checked') ? 1 : 0,
													 'subject':$('#subject').val(),
													 'body': $('#body').val()
													 };

									  console.log(payload);

									  ajax('api/api.php',
										   'post',
										   'text',
										   payload,
										   func_success2,
										   func_error2);
									}},
									{
									label: 'Cancel',
									action: function(dialogItself){
										dialogItself.close();
									}
								}]
							});
						}
				</script>";

 // are we editing?
 switch ($action)
 {
	case 'edit':
		switch ($role){
			case 'administrators':
			case 'top_levels':
				break;

			default:
				$errors = "<p class='alert alert-danger'>Your account is not allowed to perform the requested action.</p>";
				die($errors . "</div>");
				break;
		}

		$id = (int) @ $_GET['id'];

		$noticeboarddetails = "";
		$errors = "";

		// find this noticeboard item
		$sql = "SELECT *
		        FROM user_noticeboard
				WHERE id=$id;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{
			$errors = "<p class='alert alert-danger'>Noticeboard item was not found. $sql</p>";
			die($errors . "</div>");
		}

		$row = $ret->fetch_array();

		// are we editing or just opening the page?
		if (isset($_POST['extra']))
		{
			$heading = @ $_POST['heading'];
			$body = @ $_POST['body'];
			// watch out for checkboxes, they return on or off not 1 or 0
			$enabled = @ $_POST['enabled'] == 'on' ? 1 : 0;

			if (!strlen($heading) || !strlen($body)){
				$errors = "<p class='alert alert-danger'>Some fields have not been filled in.</p>";
			}
			else
			{
				// all filled in
				$heading = str_sanitize($heading);
				$body    = str_sanitize($body);

				// simple dup check
				$sql = "SELECT *
						FROM user_noticeboard
						WHERE heading='$heading' AND body='$body'
						AND id<>$id;";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
					/*
					 * FILE uploads
					 */
					 $buploadedfile = false;

					 if ($_FILES['image']['size'] != 0)
					 {
						$target_dir = "profiles/";
						if (!is_dir($target_dir))
							mkdir($target_dir);

						$target_file = $target_dir . basename($_FILES["image"]["name"]);
						$uploadOk = 1;

						if (@ move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
							//echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
						}
						else
						{
							die( "<li style='color:red' class='fa fa-fw fa-exclamation-triangle'></li>&nbsp;Could not upload the file.");
						}

						$image = $target_file;
						$buploadedfile = true;
					 }

					// update the user account
					if ($buploadedfile)
					{
						// we need to delete the current image
						$sql = "SELECT image
						        FROM user_noticeboard
								WHERE id=$id;";
						$ret0 = $database->query($sql);
						if (!$ret0 || !$ret0->num_rows)
						{
							// nothing found
						}
						else
						{
							$row0 = $ret0->fetch_array();
							$filename = $row0['image'];
							if (file_exists($filename))
								@ unlink($filename);
						}

						$sql = "UPDATE user_noticeboard
								SET heading='$heading',
								body='$body',
								image = '$image',
								enabled=$enabled
								WHERE id=$id;";
					}
					else
					{
						// image not modified
						$sql = "UPDATE user_noticeboard
								SET heading='$heading',
								body='$body',
								enabled=$enabled
								WHERE id=$id;";
					}

					$ret = $database->query($sql);
					if (!$ret)
						$errors = "<p class='alert alert-danger'>Error: {$database->error}</p>";
					else
					{
						// write the system log
						$action = "NOTICEBOARD_EDIT";
						$description = "Noticeboard item has been edited. Item: $id. Action performed by user $userid.";
						update_system_log($action, $description);

						$errors = "<p class='alert alert-success'>Profile information saved successfully.</p>";
						// reload page using JS cos header() fails!
						echo "<script>window.location.href='?view=notifications&action=edit&id=$id';</script>";
					}
				}
				else
					$errors = "<p class='alert alert-danger'>A noticeboard item already exists with details supplied.</p>";
			}
		}
		else
		{
			// viewing
			/*
			$heading = @ $_POST['heading'];
			$_POST['heading'] = $heading;
			$body = @ $_POST['body'];
			$_POST['body'] = $body;
			$enabled = (int) @ $_POST['enabled'];
			$_POST['enabled'] = $enabled;
			*/

			// get the info
			$sql = "SELECT *
			        FROM user_noticeboard
					WHERE id=$id";
			$retp = $database->query($sql);
			if (!$retp || !$retp->num_rows)
			{
				$errors = "<p class='alert alert-danger'>**$view was not found: $sql.</p>";
				die($errors . "</div>");
			}

			$row2 = $retp->fetch_array();
			$heading = $row2['heading'];
			$_POST['heading'] = $heading;
			$body = $row2['body'];
			$_POST['body'] = $body;
			$enabled = $row2['enabled'];
			$_POST['enabled'] = $enabled;
		}

		// details
		$sql = "SHOW columns
		        FROM user_noticeboard;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{
			$errors = "<p class='alert alert-danger'>Failed to get the field names.</p>";
		}
		else
		{
			while ($row0 = $ret->fetch_array())
			{
				$input = "";
				$noticeboarddetails .= "<tr>";
				$field = $row0[0];
				$field = strtolower($field);

				switch ($field){
					/* ignore this fields */
					case 'id':
					case 'entrydate':
					case 'userid':
						break;

					case 'image':
					    $input = "<input type='file' class='form-control' name='$field' id='$field'>";
						$img = $row['image'];
						if (file_exists($img))
						{
							$img = "<h5>Article image</h5>
							        <img src='$img' class='img-thumbnail' style='width:200px'>
						            <a href='#' onclick='delete_image($id); return false'><li class='fa fa-fw fa-trash'></li>&nbsp;Delete</a>";
						}

						$fa_trash = font_awesome('fa-trash');
						if ($img)
							$imd = "$img <a href='#' onclick='return false;'>$fa_trash</a>";

						$noticeboarddetails .=  "<th>$field</th><td>$input $img</td>";
						break;

					case 'enabled':
						$val = @ $_POST[$field];
						$checked = $val == 1 ? 'checked' : '';
						$input = "<input type='checkbox' $checked id='$field' name='$field'>";
						$noticeboarddetails .=  "<th>Visible to all</th><td>$input</td>";
						break;

					case 'body':
						$val = @ $_POST[$field];
						$input = "<textarea type='text' class='form-control' id='$field' name='$field'>$val</textarea>";
						$noticeboarddetails .=  "<th>$field</th><td>$input</td>";
						break;

					default:
						$val = @ $_POST[$field];
						$input = "<input type='text' class='form-control' value='$val' id='$field' name='$field'>";
						$noticeboarddetails .= "<th>$field</th><td>$input</td>";
						break;
				}

				$noticeboarddetails .=  "</tr>";
			}
		}

		$fa_edit = font_awesome('fa-edit');
		$fa_floppy = font_awesome('fa-floppy-o');
		$btn_save = "<tr><td></td><td> <button class='btn btn-sm btn-success'>$fa_floppy Update changes</button>
                                       <a href='?view=$view' class='btn btn-sm btn-default'>Cancel</a>
									   <!-- <a href='#' onclick=\"delete_from_table('user_noticeboard',$id); return false;\" class='btn btn-sm btn-danger'>$fa_trash Delete</a>-->
									   </td></tr>";
		echo "
	         <h4>$fa_edit Edit noticeboard item <small>Change noticeboard item details.</small></h4>
			 <hr>
		      <div class='table-responsive'>
			   <form name='' method='POST'  enctype='multipart/form-data'>
			   <input type='hidden' name='view' value='$view'>
			   <input type='hidden' name='action' value='edit'>
			   <input type='hidden' name='id' value='$id'>
			   <input type='hidden' name='extra' value='1'>

			   <table class='table table-hover table-striped'>
				<tbody>
					$errors
					$noticeboarddetails
				    $btn_save
				</tbody>
			   </table>
			  </div>";
		break;

		default:
				$noticeboard_options = "";
					switch ($role){
						case 'administrators':
						case 'top_levels':
							$noticeboard_options = "<button onclick='noticeboard_add($userid)' class='btn btn-sm btn-primary'><li class='fa fa-fw fa-plus'></li>&nbsp;Add new noticeboard item</button>
													<hr/>
													";
							break;
					}

	$notices = get_notices();
	$noticeboard = "";

	if (!$notices)
		$noticeboard = "<i>The noticeboard is currently empty.</i>";
    else
	{
		foreach($notices as $notice)
		{
			$id      = $notice['id'];
			$heading = $notice['heading'];
			$author  = $notice['author'];
			$date    = $notice['entrydate'];
			$body    = $notice['body'];
			$img     = $notice['image'];
			$user_id = $notice['user_id'];
			$visible = $notice['enabled'] ? "" : "<span class='badge color-red'>Hidden</span>";

			$image = "<img class='img-thumbnail' src=\"$img\">";
			$author = $user_id == $userid ? "Me" : $author;

			if ($img == "")
			{
				$image = "<span style='font-size:50pt'><li class='fa fa-fw fa-bullhorn'></li></span>";
			}
			    $table = "user_noticeboard";
				$noticeboard_item_options = "";
				if ($noticeboard_options)
					$noticeboard_item_options = " <p></p>
												   <a href='?view=$view&action=edit&id=$id'><li class='fa fa-fw fa-edit'></li></a>
												   <a href='#' onclick=\"delete_from_table('$table', $id); return false;\"><li class='fa fa-fw fa-trash'></li></a>";

				$noticeboard .= "<div class='row' style='border-bottom: 1px dotted #cacaca; margin-bottom:10px; padding-bottom:10px;'>
									 <div class='col-md-3' style='text-align:center;'>
									  $image
									 </div>
									 <div class='col-md-8'>
									  <h4 style='color:#000'>$heading</h4>
									  <h5><small>On $date by <a href='?view=user_noticeboard&action=edit&id=$user_id'>$author</a></small></h5>
									  $body
									   $noticeboard_item_options $visible
									 </div>
								 </div>";
		}
	}

	$messages_received = "";
	// do we have new messages?
	$total = 0;
	$sql = "SELECT COUNT(*)
			FROM user_notifications
			WHERE userid_to=$userid AND wasread=0;";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{}
	else
	{
		$total = $ret->fetch_array()[0];
	}

	$sql = "SELECT *
	        FROM user_notifications
			WHERE userid_from=$userid OR userid_to=$userid ORDER BY id DESC;";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
		$messages = "<i>You have no messages.</i>";
	else
	{
		$fa_envelope_read   = font_awesome("fa-comment-o");
		$fa_envelope_unread = font_awesome("fa-comment");

		$table = "user_notifications";
		$messages = "";
		while ($row = $ret->fetch_array())
		{
			$id      = $row['id'];
			$subject = $row['subject'];
			$entrydate=$row['entrydate'];
			$message = $row['body'];
			$wasread = $row['wasread'];
			$userid_to = $row['userid_to'];
			$useridtoname = $users->user($userid_to)->get('user_name');
			$userid_from = $row['userid_from'];
			$useridfromname = $users->user($userid_from)->get('user_name');
			$user    = $row['userid_from'] == $userid ? "Sent to <u>$useridtoname</u>" : "From <u>$useridfromname</u>";
			$color   = $wasread == false && $userid_to == $userid ? 'red' : '#999999';

			$delete  = "<a href='#' title='Delete this notification' data-placement='right' data-toggle='tooltip' onclick=\"delete_from_table('$table', $id); return false;\"><span style='color:red' class='fa fa-fw fa-trash'></span></a>";
			$mark_as_read = "";

			$envelope = $wasread ? $fa_envelope_read : $fa_envelope_unread;

			if (!$wasread)
			    if ($userid_to == $userid)
			    $mark_as_read  = "<a href='#' title='Mark as read' data-placement='right' data-toggle='tooltip' onclick=\"toggle_state('$table', 'wasread', $id, 1); return false;\"><span class='fa fa-fw fa-thumbs-up'></span></a>";

			$messages .= "<div class='row'>
							<div class='col-md-12'>
								 <h5 style='color:$color'>$envelope $subject <span class='pull-right'>$mark_as_read $delete</span></h5>
								 <h5><small>$user on $entrydate</small></h5>
								 <h4>
								  <small>$message</small>
								 </h4>
                 <hr>
						   </div>
						  </div>";
		}
	}

	$fa_bullhorn = font_awesome('fa-bullhorn');
	$fa_envelope = font_awesome('fa-comment');
	//$btn_new_noticeboarditem = "<button class='btn btn-sm btn-primary'>$fa_bullhorn Send a message</button>";
	$btn_new_message = "<button onclick=\"notifications_add();\" class='btn btn-sm btn-primary'>$fa_envelope Send a message</button>";

	$fa_icon = font_awesome('fa-comment-o');
	$color = $total ? "style='background: red'" : '';
	echo     "<h4>$fa_icon Messages <small>Messages from other users.</small></h4>
			 <hr>
			 $btn_new_message
			 <p>&nbsp;</p>
			 $messages";
			break;
    }

echo "$scripts";
?>
