<?php
   /*
	 * Report a bug
	 *
	 * This file manages bug reporting
	 *
	 * Author: William Sengdara
	 * Created:
	 * Modified:
	 */
	 
	/*********** start rights verification ************/

	if (!@$users)
		die(ERRORS_FILES_MUSTINCLUDE);

	$user = $users->loggedin();
	if (!$user) {
		echo view( 'dialog-login' );
		exit;
	}
	$right_exists = verify_right($view);
	if (!$right_exists)
		die(alertbuilder(ERRORS_RIGHTS_NOTALLOWED,"danger"));
		
	/*********** end rights verification ************/
	
	// custom code starts here
	$view   = @ $_GET['view'];
	$action = @ $_GET['action'];
	$id     = (int) @ $_GET['id'];
	$extra  = (int) @ $_POST['extra'];
	
	$fa_user = font_awesome('fa-user');
	$fa_bug  = font_awesome('fa-bug');
	$fa_edit = font_awesome('fa-edit');	
	$fa_plus = font_awesome('fa-plus');
	
	$table = "system_bugs";
	$sql_table = "CREATE TABLE IF NOT EXISTS bug_reports_2(
						 id INT(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
						 user_id INT(5) NOT NULL,
						 address VARCHAR(255) NULL comment \"Address (URL) where it occurred, if relevant\"
						 title VARCHAR(50) NOT NULL,
						 severity
						 fixed TINTINT(1) DEFAULT '0'
						)";
	$data = "";

	switch($action) {
		case 'add':
			 echo "<h4>$fa_plus Add a bug report <small class='pull-right'>Should a system malfunction occur, or a feature does not work as expected, report it here</small></h4>
					 <hr>";
					 
			break;
			
		case 'edit':
			 echo "<h4>$fa_edit Edit a bug report <small class='pull-right'>Edit your current bug report</small></h4>
					 <hr>";
					 
			$required = "<span style='color:red'>*</span>";
			$table = "system_bugs";
			$sql = "SELECT *
					  FROM 
					  			`$table` 
					  WHERE 
					  			id=$id LIMIT 1;";
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows)
				die(alertbuilder("The bug report specified does not exist.","danger"));
				
			$row = $ret->fetch_array();
			
			// required fields
			$fields = array('description',
								 'severity');
			
			// create a CSV list for javascript to
			// check if element text value is not null
 			$required_string = null;					
			foreach($fields as $field){
					   $required_string[] = "'$field'";
			}				
			$required_string = implode(",", $required_string);

			$errors = "";
			$bugs = "";

			// if extra is set, it means commit a change
			// before displaying the values of this document
			if ($extra)
			{
				foreach($fields as $field){
						$_POST[$field] = addslashes(trim( @ $_POST[$field] ));
						if (!strlen($_POST[$field])){
							$errors = missing_parameter($field);
							break;
						}
				}

				// all filled in
				if (!$errors)
				{			
					// if we are saving, the new info is in POST array
					$description = @ $_POST['description'];
					$severity  	 = (int) @ $_POST['severity'];

					$sql = "UPDATE `$table` 
							  SET description='$description',
								   severity='$severity'
								WHERE id=$id;";

					$ret = $database->query($sql);
					
					if (!$ret){
						$error = $database->error;							
						$errors = alertbuilder($error,"danger");
					}		
					else
					{
						$errors = alertbuilder("Your bug report was successfully updated.","success");
					}											
				}			
			}
			else
			{
				// this page was loaded for viewing
				// we need to init our POST array with existing
				// values
				$_POST['description'] = $row['description']; 
				$_POST['severity']    = $row['severity'];			
			}			

			$scripts = "<script>
								function upload_document(){					 
											var flds    = [$required_string];
											
											for(var idx =0; idx < flds.length; idx++)
											{
												var fld = $('#'+flds[idx]);
												
												if ( ! fld.val().trim().length )
												{
													$(fld).focus();
													alertify.error('Specify the ' + flds[idx]);
													return false;
												}
											}
					
											// submit form
											return true;
									}
								</script>";
									
			// dynamically build the form
			$sql = "SHOW columns 
					FROM `$table`";
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows)
			{
				echo alertbuilder("Failed to retrieve columns names to build the UI.","danger");
			}
			else
			{
				while ($row = $ret->fetch_array())
				{
					$input = "";
					$field = $row[0];
					$val = @ $_POST[$field];
					
					// we ignore some fields
					switch (strtolower($field)){
						case 'entrydate':
						case 'user_id':
						case 'omas_id':
						case 'type_id':
						case 'id':
						case 'lockedby_user_id':
							break;
			
						case 'description':
							$input = "<textarea class='form-control'  name='$field' id='$field'>$val</textarea>";
							$field_ = ucfirst($field);
							$bugs .= "<div class='form-group'>
													<label class='control-label col-sm-2' for='$field'>$field_ $required</label>
													<div class='col-sm-8'>$input</div>
												</div>";
							break;
							
						case 'severity':
							$val = (int) @ $_POST[$field];
							$list = "";
							for($idx=1; $idx<=10; $idx++){
								$selected = ($idx == $val) ? "selected" : "";
								$list .="<option value='$idx' $selected>$idx</option>";
							}
							 				 	
							$select_severity = "<select class='form-control' id='severity' name='severity'>$list</select>";
							 
							$field_ = "Severity";
							$bugs .= "<div class='form-group'>
											<label class='control-label col-sm-2' for='$field'>$field_ $required</label>
											<div class='col-sm-8'>$select_severity</div>
										</div>";						
							break;
							
						default:
							$tagsinput = "";
							switch ($field){
								case 'category':
								case 'keywords':
								case 'parties':
								case 'lawyer_assigned':
										$tagsinput = "data-role='tagsinput'";
										break;
							} 
							
							$input = "<input type='text' class='form-control' $tagsinput value='$val' id='$field' name='$field'>";
							$field_ = ucfirst($field);
							$bugs .= "<div class='form-group'>
											<label class='control-label col-sm-2' for='$field'>$field_ $required</label>
											<div class='col-sm-8'>$input</div>
										</div>";
							break;
					}
				}
			}	

			$fa_floppy = font_awesome('fa-floppy-o');
			$fa_edit   = font_awesome('fa-edit');

			$title = "";
			$lockerid   = 0;
			$new_state  = "Lock";
			$alert_type = "default";
			$disabled   = "";
			$save_disabled="";
			
			$btn_cancel = "<a href='?view=bug-reporter' class='btn btn-sm btn-danger'>Cancel</a>";
			$btn_save = "<div class='form-group'>
								<label class='control-label col-sm-2' for=''></label>
								<div class='col-sm-8'>
									<button class='btn btn-sm btn-success' $save_disabled>$fa_floppy Update changes</button> 
								   $btn_cancel
								</div>
							  </div>
							  <p>&nbsp;</p>";
							  
			echo "<form class='form-horizontal' role='form' method='POST'  enctype='multipart/form-data'>
					<!-- required params start -->
						<input type='hidden' name='view' value='$view'>
						<input type='hidden' name='action' value='edit'>
						<input type='hidden' name='id' value='$userid'>	
						<input type='hidden' name='extra' value='1'>
					<!-- required params end -->
					
						$errors
						$bugs					
						$btn_save
					</form>
					
					$scripts
					";		
					
			break;
			
		default:
			 // default
			$data = "<tr><td colspan='7' class='text-center'><i>No bugs have been reported.</i></td></tr>";
			$thead = "";
			$tbody = "";
			$totalfields = 0;
			$fields = array();
			
		
			$sql = "SHOW columns 
					  FROM `$table`;";
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows)
			 {
			 }
			 else
			 {
				 $thead = "<tr>";
				 while ($row = $ret->fetch_array())
				 {
					 $name = $row['Field'];
					 $fields[] = $name;
					 $thead .= "<th>$name</th>";
					 $totalfields++;
				 }
				 $thead .= "<tr>";
			 }
			 
			 $sql = "SELECT *
			         FROM $table
					 ORDER BY id DESC;";
			 $ret = $database->query($sql);
			 if (!$ret || !$ret->num_rows)
			 {
			 }
			 else
			 {
				 // severity is 1-10
				 $max = 10;
		
				 // clear this
				 $data = "";
				 $idx = 1;
				 $max_bugs = $ret->num_rows;
		
				 while ($row = $ret->fetch_array())
				 {
					 $id = "";
					 $user = "";
					 $severity = "";
					 $description = "";
					 $entrydate = "";
					 $color = "";
		
					 foreach($fields as $field)
					 {
						 $val = $row[$field];
						 
						 switch (strtolower($field)){
							 case 'id':
								$edit   = "<a href='?view=$view&action=edit&id={$row['id']}' class='' href='#' data-placement='right' data-toggle='tooltip' title='Edit bug report $val'><li class='fa fa-fw fa-edit'></li></a>";
								$delete = "<a href='#' onclick=\"bug_report_delete({$row['id']}); return false;\" class='bug-report-delete' href='#' data-placement='right' data-toggle='tooltip' title='Delete bug report $val'><li style='color:red' class='fa fa-fw fa-trash'></li></a>";
		
								/*
								 * the owner of this report can edit/delete it
								 * the administrators can delete it
								 */
								 if ($row['user_id'] == $userid )
								{
									$val = "$edit $delete";
								}
								elseif ($role == 'administrators')
								{
									$val = "$delete";
								}
		
								$id = $val;
								break;
		
							 case 'user_id':
							    $username = $row[$field] == $userid ? "Me" : $users->user($val)->get('user_name');
								$val = "<a href='?view=users&action=edit&id=$val'>$fa_user $username</a>";
								$user = $val;
								break;
		
							 case 'description':
								$description = $val;
								break;
		
							 case 'entrydate':
								$entrydate = "<abbr class='timeago' title='$val'>$val</abbr>";
								break;
		
							 case 'severity':
								/* build a progressbar out of severity */
								$perc = ($val / $max * 100);
								$perc = round($perc,0);
								$color = 'success';
		
								if ($perc >= 60)
									$color = 'danger';
								elseif ($perc >= 50)
									$color = 'warning';
		
								$val = "<div class='progress'>
										  <div class='progress-bar progress-bar-$color' role='progressbar' aria-valuenow='$perc'
										  aria-valuemin='0' aria-valuemax='$max' style='width:$perc%'>
											$perc%
										  </div>
										</div>";
								$severity = $val;
								break;
						 }
					 }
		
					 $data .= "<tr>
					 				<td>$idx</td>
					 				<td>$entrydate</td>
					 				<td>$description</td>
					 				<td>$severity</td>
					 				<td>$user</td>
									<td>$id</td>
								  </tr>";
					 $idx++;
				 }
			 }
		
			 echo "<h4>$fa_bug Bug Reporter <small class='pull-right'>Should a system malfunction occur, or a feature does not work as expected, report it here</small></h4>
					 <hr>";
					 
			 $list = "";
			 for($idx=1; $idx<=10; $idx++)
			 	$list .="<option value='$idx'>$idx</option>";			 	
			 $select_severity = "<select class='form-control' id='severity' name='severity'>$list</select>";
			 
			 echo "<p><button id='btn_report_bug' class='btn btn-sm btn-primary'>$fa_bug Report a bug</button></p>
		
				   <script>
						function bug_report_delete(id){
							BootstrapDialog.confirm('Are you sure you would like to delete this item?',function(ans){
								if (ans){
								  var payload = {'view': 'bug-report-delete',
												 'userid': $userid,
												 'id': id
												 };
		
								  console.log(payload);
		
								  ajax('api/api.php',
									   'post',
									   'text',
									   payload,
									   funcSuccess,
									   funcError);
								}
							});
						}
		
						$('#btn_report_bug').click(function(){
							report_bug($userid);
						});
		
		
					   var funcSuccess = function(data) {
											  console.log('result follows');
											  console.log(data);
											  alertify.success(data);
											  if (data == 'true')
												  window.setTimeOut(window.location.reload(),1000);
											};
					   var funcError = function (a,b,c) {
											alertify.error(b + ' ' + c);
											console.log(a,b);
										  };
		
		
				      function report_bug(userid) {
					  var dialogcss = \"<div class='table-responsive'> \
									<table class='table table-no-border'> \
									 <tbody> \
									  <tr><th>Severity (1 - 10) <span class='required'>*</span></th><td>$select_severity</td></tr>\
									  <tr><th>Description <span class='required'>*</span></th><td><textarea value='' class='form-control' placeholder='Describe the bug in as much details as you can...' id='description'></textarea></td></tr> \
									 </tbody> \
									</table></div> \
									<style>\";
		
								BootstrapDialog.show({
									title: 'Report a bug',
									message: function(dialog) {
										var content = $(dialogcss);
										return content;
									},
									buttons: [
										{
										label: 'Add report',
										action: function(){
											var controls = ['#description','#severity'];
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
		
										  var payload = {'view': 'bug-report-add',
														 'userid': userid,
														 'description':$('#description').val(),
														 'severity': $('#severity').val()
														 };
		
										  console.log(payload);
		
										  ajax('api/api.php',
											   'post',
											   'text',
											   payload,
											   funcSuccess,
											   funcError);
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
				   
				   echo "<div class='row'>
				   		 <div class='col-md-12'>
								<div class='table-responsive'>
									<table id='table-history' class='table table-hover table-bordered'>
									 <thead>
									  <tr>
									  		<th>#</th>
									  		<th>Entry date</th>
									  		<th>Description of the bug</th>
									  		<th>Severity</th>
									  		<th>Added by</th>
									  		<th>Action</th>
									  	</tr>
									 </thead>
									 <tbody>
									 	$data 
									 <tbody>
									</table>
							   </div>
				   		 </div>
				   		</div>
					      <script>
					       $(document).ready(function(){
					       	$('.timeago').timeago();
					       });
					      </script>
				   		";
			break;
	}

?>
