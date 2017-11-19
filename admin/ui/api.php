<?php
    /*
	 * API Management
	 *
	 * This file manages API Consumers
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
			
	// ensure this user is permitted to perform this
	if (!$right_exists)
		die("<div class='well bg-white'>
			   <div class='alert alert-danger'>
			    <li style='color:red' class='fa fa-fw fa-exclamation-circle'></li>&nbsp;You do not have the right to request that view.
			   </div>
			 </div>");
	
	 /**
	  * SECTION: Activity 
	  *
	  * This section deals with getting the system_log entries that start with API_
	  */
	 $total_activity = 0;
	 $thead_activity = "";
	 $tbody_activity = "";
	 $totalfields = 0;
	 $fields = array();	 
	 $table = "api_log";
	 
	 $sql = "SHOW columns FROM $table;";
	 $ret = $database->query($sql);
	 if (!$ret || !$ret->num_rows)
	 {
		 // failed to get  columns
	 }
	 else
	 {
		 $thead_activity = "<tr>";
		 while ($row = $ret->fetch_array())
		 {
			 $name = $row['Field'];
			 $fields[] = $name;
			 $thead_activity .= "<th>$name</th>";
			 $totalfields++;
		 }
		 $thead_activity .= "<tr>";
	 }
	 
	 // get the data
	 $tbody_activity = "<tr><td colspan='$totalfields' style='text-align:center'><small>There is no data to show.</small></tr>";
	 $sql = "SELECT * 
	         FROM $table 
	         ORDER BY id DESC;";
	 $ret = $database->query($sql);
	 if (!$ret || !$ret->num_rows)
	 {
		 // default will be display
	 }
	 else
	 {
		 // clear the default response
		 $tbody_activity = "";
		 $i = 1;
		 $total_activity = $ret->num_rows;
		 while ($row = $ret->fetch_array())
		 {
			 $tbody_activity .= "<tr>";
			 foreach($fields as $field)
			 {
				 $val = $row[$field];
				 switch (strtolower($field)){
					 case 'id':
						$val = "<a onclick=\"activity_delete({$row['id']});\" class='' href='#' data-placement='right' data-toggle='tooltip' title='Delete activity $val'><li class='fa fa-fw fa-trash'></li></a>";
						break;
						
					 case 'user_id':
					    $username = $row[$field] == $userid ? "Me" : $users->user($val)->get('user_name');
						$val = "<a href='?view=users&action=edit&id=$val'>$fa_user $username</a>";
						break;					 
				 }
				 $tbody_activity .= "<td>$val</td>";
			 }
			 $tbody_activity .= "</tr>";
			 $i++;
		 }
	 }

	 /**
	  * SECTION: Consumers
	  *
	  * This section deals with adding / editing / deleting consumers
	  */	 
	 $total_consumers = 0;
	 $thead_consumers = "";
	 $tbody_consumers = "";
	 $totalfields = 0;
	 $fields = array();	 
	 $table = "api_consumers";	 
	 
	 $sql = "SHOW columns FROM $table;";
	 $ret = $database->query($sql);
	 if (!$ret || !$ret->num_rows)
	 {
		 // failed to get columns
	 }
	 else
	 {
		 $thead_consumers = "<tr>";
		 while ($row = $ret->fetch_array())
		 {
			 $name = $row['Field'];
			 $fields[] = $name;
			 $thead_consumers .= "<th>$name</th>";
			 $totalfields++;
		 }
		 $thead_consumers .= "<tr>";
	 }
	 
	 // default
	 $tbody_consumers = "<tr><td colspan='$totalfields' style='text-align:center'><small>There is no data to show.</small></tr>";
	 $sql = "SELECT * FROM $table ORDER BY id DESC;";
	 $ret = $database->query($sql);
	 if (!$ret || !$ret->num_rows)
	 {
		 // default will be shown
	 }
	 else
	 {
		 // clear this
		 $tbody_consumers = "";
		 $i = 1;
		 $total_consumers = $ret->num_rows;
		 while ($row = $ret->fetch_array())
		 {
			 $tbody_consumers .= "<tr>";
			 foreach($fields as $field)
			 {
				 $val = $row[$field];
				 switch (strtolower($field)){
					 case 'id':
 					    $edit = "<a onclick=\"consumer_edit({$row['id']});\" class='' href='#' data-placement='right' data-toggle='tooltip' title='Edit consumer $val'><li class='fa fa-fw fa-edit'></li></a>";
						$delete = "<a onclick=\"consumer_delete({$row['id']});\" class='' href='#' data-placement='right' data-toggle='tooltip' title='Delete consumer $val'><li class='fa fa-fw fa-trash'></li></a>";
						
						 if ($row['user_id'] == $userid ||
							 $role == 'administrators')
						{
						  $val = "$edit $delete";
						}
						break;
						
					 case 'enabled':
						$fa_check = font_awesome('fa-check');
						$fa_remove = font_awesome('fa-remove');
						$val = ($val == 1) ? "<span class='badge color-green'>$fa_check</span>" : "<span class='badge color-red'>$fa_remove</span>";
						break;
						
					 case 'authkey':
						$options = "<a onclick=\"consumer_authkey_new({$row['id']});\" class='' href='#' data-placement='right' data-toggle='tooltip' title='Generate new authkey'><li class='fa fa-fw fa-refresh'></li></a>";
						
						 if ($role == 'administrators')
						 {
							 $val = "$val $options";
						 }
						break;
						
					 case 'user_id':
						$fa_user = font_awesome('fa-user');
					    $username = $row[$field] == $userid ? "Me" : $users->user($val)->get('user_name');
						$val = "<a href='?view=users&action=edit&id=$val'>$fa_user $username</a>";
						break;					 
				 }
				 
				 $tbody_consumers .= "<td>$val</td>";
			 }
			 $tbody_consumers .= "</tr>";
			 $i++;
		 }
	 }
	 
	 // list of accepted formats
	 $list_format = "<select id='format' class='form-control'><option value='JSON'>JSON</option><option value='CSV'>CSV</option><option value='XML'>XML</option></select>";
	 
	 // icons
	 $fa_api  = font_awesome('fa-share-alt');
	 $fa_add = font_awesome('fa-plus');
	 $btn_consumer_add = "<button onclick=\"consumer_add($userid)\" class='btn btn-sm btn-primary'>$fa_add Add an API consumer</button>";
	 $scripts = "<script type='text/javascript'>	
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
									  
					function activity_delete(id){
						BootstrapDialog.confirm(\"<li class='fa fa-fw fa-exclamation-triangle'></li>&nbsp;Are you sure you would like to delete this item?\",function(ans){
							if (ans){							
							  var payload = {'view': 'api-activity-delete', 
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

				  /* add an API consumer */
				  function consumer_add(userid) {
						  var dialogcss = \"<div class='table-responsive'> \
										<table class='table table-no-border'> \
										 <tbody> \
										  <tr><th>Entity Name <span class='required'>*</span></th><td><input type='text' value='' class='form-control' placeholder='Name of the institution' name='name' id='name'></td></tr> \
										  <tr><th>Format <span class='required'>*</span></th><td>$list_format</td></tr>\
										  <tr><th>Enabled <span class='required'>*</span></th><td><input type='checkbox' checked id='enabled' name='enabled'></td></tr>\
										 </tbody> \
										</table></div> \
										<style>\";
						  
							BootstrapDialog.show({
								title: 'Add an API consumer',
								message: function(dialog) {
									var content = $(dialogcss);
									return content;
								},
								buttons: [
									{
									label: 'Add consumer',
									action: function(){
										var controls = ['#name','#format'];
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

									  var payload = {'view': 'api-consumer-add', 
													 'userid': userid,
													 'name':$('#name').val(),
													 'format': $('#format').val(),
													 'enabled': $('#enabled').prop('checked') ? 1 : 0
													 };
													 
									  console.log(payload);
									  
									  ajax('api/api.php', 
										   'post',
										   'text', 
										   payload, 
										   func_success,
										   func_error);
									}},					
									{
									label: 'Cancel',
									action: function(dialogItself){
										dialogItself.close();
									}
								}]
							});	
					}		

					/* delete an API consumer */
				  function consumer_delete(id) {
							BootstrapDialog.confirm(\"<li style='color:red' class='fa fa-fw fa-exclamation-triangle'></li>&nbsp;Are you sure you would like to delete this item?\",function(ans){
								if (ans) 
								{
								  var payload = {'view': 'api-consumer-delete', 
												 'userid': $userid,
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

				  /* consumer authkey change */
				  function consumer_authkey_new(id) {
							BootstrapDialog.confirm('Are you sure would like to generate a new authkey for this API consumer?',function(ans){
								if (ans) 
								{
								  var payload = {'view': 'api-consumer-authkey-new', 
												 'userid': $userid,
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
			   </script>";
	 
	 $fa_info = font_awesome('fa-info-circle');
	 
	 echo "<div class='well bg-white'>
			 
	         <h4>$fa_api API Management <small>External entities can request statistical data from the system.</small></h4>
			 <hr>
			 
			<ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>
				<li class='active'><a href='#activity' data-toggle='tab'>Activity Log <span class='badge'>$total_activity </span></a></li>
				<li><a href='#consumers' data-toggle='tab'>Consumers <span class='badge'>$total_consumers </span> </a></li>
			</ul>

			<div id='my-tab-content' class='tab-content'>
				<div class='tab-pane active' id='activity'>

					 <div style='padding:10px'>
					     <h5>Activity Log <small>All API request activity is listed below</small></h5>
						 <HR>
						 <div class='table-responsive'>
						  <table class='table table-striped table-bordered table-condensed'>
						   <thead>
							 $thead_activity
						   </thead>
						   <tbody>
							$tbody_activity
						   </tbody>
						  </table>
						 </div>	 	
					 
					 </div>
			
				</div>
				
				<div class='tab-pane' id='consumers'>
					 <div style='padding:10px'>
						 <h5>API Consumers <small>Manage the entities that are allowed to access statistical data remotely. By default all responses are JSON-formatted.</small></h5>					 
						 <HR>
						 $btn_consumer_add
						 <p>&nbsp;</p>
						 <div class='table-responsive'>
						  <table class='table table-striped table-bordered table-condensed'>
						   <thead>
							 $thead_consumers
						   </thead>
						   <tbody>
							$tbody_consumers
						   </tbody>
						  </table>
						 </div>	
					     <HR>
					     <h4> <small>$fa_info Usage: POST to api/api.php action=<i>statistics</i>, parameters=<i>authkey</i> </small></h4>
					 </div>
					 			
				</div>					
			</div>			
			
           </div>
		   
		   $scripts
		   ";			   
?>