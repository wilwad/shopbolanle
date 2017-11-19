<?php
    /*
	 * SMS View
	 *
	 * This file shows incoming and sent sms traffic
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
    $right_exists = false;
	
	// verify that this user has the right to manage this right
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

     $table_activity = table_from_query("system_log",
	                   "SELECT * FROM system_log 
					   WHERE description = 'SMS_RECEIVED' OR
                             description = 'SMS_SENT' OR
 							 description = 'SMS_FAILED';");
							 
	 $table_received = table_from_query("sms_received",
	                   "SELECT * FROM sms_received 
					   ORDER BY id DESC;");
							 
	 $table_sent = table_from_query("sms_sent",
	                   "SELECT * FROM sms_sent
					   ORDER BY id DESC;");
							 
	 $table_blocked = table_from_query("sms_blocked",
	                   "SELECT * FROM sms_blocked 
					   ORDER BY id DESC;");
							 
	 $fa_icon = font_awesome('fa-plus');
		   
	$script = "		   <script>
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
							  <tr><th>Description <span class='required'>*</span></th><td><textarea value='' class='form-control' placeholder='Describe the bug in as much details as you can...' id='description'></textarea></td></tr> \
							  <tr><th>Severity <span class='required'>*</span></th><td><input type='number' value='1' class='form-control' placeholder='Severity' id='severity'></td></tr>\
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
	
	// get settings that match SMS_
	$body_sms_settings = build_settings_table('SMS_%',"<tr><td colspan='3'><i>No settings are available for selected category.</i></td></tr>");
    $buttons = "<tr>
                 <td colspan='3'>
                  <button class='btn btn-sm btn-primary'><li class='fa fa-fw fa-floppy-o'></li>&nbsp;Save changes</button>
                 </td>
                </tr>";    	
	$fa_sms = font_awesome('fa-mobile');
	$fa_plus = font_awesome('fa-plus');
	
	$btn_add_blocked = "<button class='btn btn-sm btn-primary'>$fa_plus Block a number</button>";
	
    echo "<div class='well bg-white'>
	         <h4>$fa_sms SMS Traffic <small>Display incoming and outgoing sms traffic.</small></h4>
			 <hr>
			 
	        <ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>
				<li class='active'><a href='#received' data-toggle='tab'>Received</a></li>
				<li><a href='#sent' data-toggle='tab'>Sent</a></li>
			</ul>
			<p></p>
			<div style='padding:10px'>
				<div id='my-tab-content' class='tab-content'>

					<div class='tab-pane active' id='received'>
					 <h5>Received <small>All messages received from outside</small></h5>	
					  <div class='table-responsive'>
					   <table class='table table-bordered table-hover table-condensed'>
					    <thead>
						 {$table_received['head']}
						</thead>
						<tbody>
						 {$table_received['body']}
						</tbody>
					   </table>
					  </div>
					</div>
					
					<div class='tab-pane' id='sent'>
					 <h5>Sent <small>All messages sent from the system</small></h5>				
					  <div class='table-responsive'>
					   <table class='table table-bordered table-hover table-condensed'>
					    <thead>
						 {$table_sent['head']}
						</thead>
						<tbody>
						 {$table_sent['body']}
						</tbody>
					   </table>
					  </div>					 
					</div>		

			    </div>
		    </div>
			
           </div>
		   $script";	
		   
    function table_from_query($table, $query = ""){	
	         global $database;
	         global $userid;
			 global $users;
			 
			 $thead = "";
			 $tbody = "";
			 $totalfields = 0;
			 $fields = array();	 

			 $sql = "SHOW columns FROM $table;";
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
			 
			 // default
			 $tbody = "<tr><td colspan='$totalfields' style='text-align:center'><small>There is no data to show.</small></tr>";
			 
			 $sql = $query;
			 $ret = $database->query($sql);
			 if (!$ret || !$ret->num_rows)
			 {
			 }
			 else
			 {
				 // clear this
				 $tbody = "";
				 $i = 1;
				 while ($row = $ret->fetch_array())
				 {
					 $tbody .= "<tr>";
					 foreach($fields as $field)
					 {
						 $val = $row[$field];
						 switch (strtolower($field)){
							 case 'id':
								$edit   = "<a onclick=\"bug_report_edit({$row['id']});\" class='' href='#' data-placement='right' data-toggle='tooltip' title='Edit bug report $val'><li class='fa fa-fw fa-edit'></li></a>";
								$delete = "<a onclick=\"bug_report_delete({$row['id']});\" class='bug-report-delete' href='#' data-placement='right' data-toggle='tooltip' title='Delete bug report $val'><li class='fa fa-fw fa-trash'></li></a>";
								


								break;
								
							 case 'user_id':
								$username = $users->user($userid)->get('user_name');
								$val = "$username";
								break;					 
						 }
						 
						 $tbody .= "<td>$val</td>";
					 }
					 $tbody .= "</tr>";
					 $i++;
				 }
			 }

    	     return ['head'=>$thead, 
			         'body'=>$tbody];
	}	
?>