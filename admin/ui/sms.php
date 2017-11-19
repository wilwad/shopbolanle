<?php
    /*
	 *SMS Management
	 *
	 * This file configures sms blocked/settings
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

	 $fa_bug  = font_awesome('fa-bug');
	 $fa_icon = font_awesome('fa-plus');

	$script = "<script>

			   var funcSuccess = function(data) {
									  console.log('result follows');
									  console.log(data);

									  if (data == 'true')
									  {
										  window.setTimeOut(window.location.reload(),1000);
									  }
									  else
										  alertify.error(data);
									};

			   $(document).ready(function(){
				   console.log('document ready');

				   $('#tabs').tabCollapse();

					$('#truefalse').change(function() {
						var newtype = this.checked ? 'checkbox' : 'text';
						alert('change fired : ' + this.checked);
						$('#value').prop('type', newtype);
					});
			   });

			   var funcError = function (a,b,c) {
									alertify.error(b + ' ' + c);
									console.log(a,b);
								  };

		        function blockednumbers_add(userid) {
					    var dialogcss = \"<div class='table-responsive'> \
										<table class='table table-no-border'> \
										 <tbody> \
										  <tr><th>Mobile <span class='required'>*</span></th><td><input type='number' value='' class='form-control' placeholder='081...' id='mobile'></td></tr> \
										  <tr><th>Reason <span class='required'>*</span></th><td><textarea value='' class='form-control' placeholder='Reason for blocking the number' id='reason'></textarea></td></tr>\
										 </tbody> \
										</table></div> \
										<style>\";

						BootstrapDialog.show({
							title: 'Blocked numbers',
							message: function(dialog) {
								var content = $(dialogcss);
								return content;
							},
							buttons: [
								{
								label: 'Add blocked number',
								action: function(){
									var controls = ['#mobile','#reason'];
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

								  var payload = {'view': 'blocked-numbers-add',
												 'userid': userid,
												 'mobile':$('#mobile').val(),
												 'reason': $('#reason').val()
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

			function delete_from_table(table,id){
					 BootstrapDialog.confirm('Are you sure you would like to delete this item?', function(ans){
						 if (ans){
							  var payload = {'view': 'delete-from-table',
											 'userid': $userid,
											 'table': table,
											 'id':id
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



					        function sms_settings_add(userid) {
								    var dialogcss = \"<div class='table-responsive'> \
													<table class='table table-no-border'> \
													 <tbody> \
													  <tr><th>Name <span class='required'>*</span></th><td><input type='text' class='form-control' value='SMS_' id='name'></td></tr> \
													  <tr><th>True False value? <span class='required'>*</span></th><td><input type='checkbox' checked id='truefalse'></td></tr>\
													  <tr><th>Value <span class='required'>*</span></th><td><input type='checkbox' value='Value'></td></tr>\
													 </tbody> \
													</table></div> \
													<style>\";

									BootstrapDialog.show({
										title: 'SMS Settings',
										message: function(dialog) {
											var content = $(dialogcss);
											return content;
										},
										buttons: [
											{
											label: 'Add sms setting',
											action: function(){
												var controls = ['#name','#value'];
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

											  var payload = {'view': 'settings-add',
															 'userid': userid,
															 'name':$('#name').val(),
															 'truefalse': $('#truefalse').prop('checked')? 1 : 0,
															 'value': $('#value').val()
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

	$fa_edit = font_awesome('fa-edit');
	$fa_trash = font_awesome('fa-trash');

	$fa_sms = font_awesome('fa-mobile');
	$fa_plus = font_awesome('fa-plus');


	// get settings that match SMS_
	$body_sms_settings = "<tr><td colspan='3'><i>No settings are available for selected category.</i></td></tr>";
	$match = 'SMS_%';
	$sql = "SELECT * FROM system_settings WHERE name LIKE '$match';";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{}
	else
	{
		$body_sms_settings = "";
		while ($row = $ret->fetch_array()) {
			$id        = $row['id'];
			$name      = $row['name'];
			$value     = $row['value'];
			$truefalse = $row['truefalse'];
			$options   = "<a href='#' title='Edit setting' data-toggle='tooltip' onclick='sms_settings_edit($id); return false;'>$fa_edit</a>
			              <a href='#' title='Delete setting' data-toggle='tooltip' onclick=\"delete_from_table('settings',$id); return false;\">$fa_trash</a>";
			if ($truefalse) {
				$checked = $value == 1 ? 'checked' : '';
				$value = "<input type='checkbox' $checked />";
			}
			else
				$value = "<input type='text' class='form-control' value=\"$value\" />";

			$body_sms_settings .= "<tr><td>$options</td><td>$name</td><td>$value</td></tr>";
		}
	}

$fa_floppy = font_awesome('fa-floppy-o');
$btn_add_blocked = "<button onclick='blockednumbers_add($userid)' class='btn btn-sm btn-primary'>$fa_plus Block a number</button>";

$btn_add_setting = "<button onclick='sms_settings_add($userid)' class='btn btn-sm btn-primary'>$fa_plus Add new SMS setting</button>";

$buttons = "<tr>
           <td colspan='3'>
            <button class='btn btn-sm btn-success'>$fa_floppy Update changes</button>
           </td>
          </tr>";

    echo "<div class='well bg-white'>
	         <h4>$fa_sms SMS Management <small>Manage SMS traffic to and from the system.</small></h4>
			 <hr>

	    <ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>
				<li class='active'><a href='#activity' data-toggle='tab'>Activity</a></li>
				<li><a href='#received' data-toggle='tab'>Received</a></li>
				<li><a href='#sent' data-toggle='tab'>Sent</a></li>
				<li><a href='#blocked' data-toggle='tab'>Blocked</a></li>
				<li><a href='#settings' data-toggle='tab'>SMS Settings</a></li>
			</ul>
			<p></p>
			<div style='padding:0'>
				<div id='my-tab-content' class='tab-content'>
					<div class='tab-pane active' id='activity'>
					 <h5>SMS Activity <small>All SMS-related activity</small></h5>

					  <div class='table-responsive'>
					   <table class='table table-bordered table-hover table-condensed'>
					    <thead>
						 {$table_activity['head']}
						</thead>
						<tbody>
						 {$table_activity['body']}
						</tbody>
					   </table>
					  </div>
					</div>

					<div class='tab-pane' id='received'>
					 <h5>Received <small>All messages received from outside</small></h5>

					  <div class='table-responsive'>
					   <table class='table table-bordered table-hover table-condensed'>
					    <thead>{$table_received['head']}</thead>
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
					    <thead>{$table_sent['head']}</thead>
						<tbody>
						 {$table_sent['body']}
						</tbody>
					   </table>
					  </div>
					</div>

					<div class='tab-pane' id='blocked'>
					 <h5>Blocked <small>All outside mobile numbers blocked from sending messages to system</small></h5>
					  $btn_add_blocked
					  <p>&nbsp;</p>
					  <div class='table-responsive'>
					   <table class='table table-bordered table-hover table-condensed'>
					    <thead>{$table_blocked['head']}</thead>
						<tbody>
						 {$table_blocked['body']}
						</tbody>
					   </table>
					  </div>
					</div>

					<!-- sms settings -->
					<div class='tab-pane' id='settings'>
					 <h5>SMS Settings <small>Configure sms rules</small></h5>

					  $btn_add_setting
					  <p>&nbsp;</p>

					 <div class='table-responsive'>
					  <table class='table table-bordered table-hover table-striped table-condensed'>
					   <thead>
						<tr>
						 <th>#</th><th>Setting</th><th>Value</th>
						</tr>
					   </thead>
					   <tbody>
						$body_sms_settings
						$buttons
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

			 $fa_edit = font_awesome('fa-edit');
			 $fa_trash = font_awesome('fa-trash');

			 $sql = "SHOW columns FROM $table;";
			 $ret = $database->query($sql);
			 if (!$ret || !$ret->num_rows)
			 {
				// nothing
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

			 $fa_user = font_awesome('fa-user');

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
								$options = $val;
								$id = $val;

							    switch ($table){
								     case 'activity':
										$options   = "<a href='#' title='Delete activity' data-toggle='tooltip' onclick=\"delete_from_table('system_log', $id); return false;\">$fa_trash</a>";
										break;

									 case 'sms_received':
										$options   = "<a href='#' title='Delete received sms' data-toggle='tooltip' onclick=\"delete_from_table('sms_received', $id); return false;\">$fa_trash</a>";

										break;

									 case 'sms_sent':
										$options   = "<a href='#' title='Delete sent sms' data-toggle='tooltip' onclick=\"delete_from_table('sms_sent', $id); return false;\">$fa_trash</a>";

										break;

									 case 'sms_blocked':
										$options   = "<a href='#' title='Edit blocked number' data-toggle='tooltip' onclick='blocked_numbers_edit($id); return false;'>$fa_edit</a>
													  <a href='#' title='Delete from block list' data-toggle='tooltip' onclick=\"delete_from_table('sms_blocked', $id)\"; return false;\"'>$fa_trash</a>";

										break;
								}

								$val = $options;
								break;

							 case 'user_id':
								$username = $row[$field] == $userid ? "Me" : $users->user($val)->get('user_name');
								$val = "<a href='?view=users&action=edit&id=$val'>$fa_user $username</a>";
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
