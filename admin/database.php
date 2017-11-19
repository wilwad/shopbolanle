<?php
	 /*
	  * William Sengdara
	  * Copyright (c) 2015
	  */
	  
	/*
     * This is the handler for databases 
	 * Please lower-case for all table names so we don't have issues on Linux machines
	 * that are case sensitive
	 */
	  @ session_start();
	  
	require_once('settings.php');
	require_once('user_rights.php');

	$database = new mysqli(settings::db_host,settings::db_user,settings::db_pwd);
	if ($database->connect_errno)
		throw new Exception("Fatal error: Failed to create a connection to the database.");

	// create db and tables
	$database->query(settings::sql_create_db) or die('sql_table_db: '                       . $database->error);	
	$database->query(settings::sql_select_db) or die('sql_select_db: '                     . $database->error);	
	$database->query(settings::sql_table_roles) or die('sql_table_roles: '                 . $database->error);
	$database->query(settings::sql_table_users) or die('sql_table_users: '                 . $database->error);
	$database->query(settings::sql_table_userprofiles) or die('sql_table_user_profiles: '  . $database->error);
	$database->query(settings::sql_table_notifications) or die('sql_table_notifications: ' . $database->error);
	$database->query(settings::sql_table_settings) or die('sql_table_settings: '           . $database->error);
	$database->query(settings::sql_table_bug_reports) or die('sql_table_bug_reports: '     . $database->error);
	$database->query(settings::sql_table_queries) or die('sql_table_queries: '             . $database->error);
	$database->query(settings::sql_table_system_log) or die('sql_table_system_log: '       . $database->error);

	/* user roles */
	if (! db_get_count($database,'user_roles','id')) 
		$database->query(settings::sql_default_roles) or 
		die('add default roles: ' . $database->error);

	/* find admin user */
	$sql = "SELECT u.id AS userid, r.id AS roleid
			FROM users u, user_roles r 
			WHERE u.roleid = r.id AND r.name='administrators';";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows) {
		$sql_insert_admin = settings::sql_default_admin;
		$database->query($sql_insert_admin) or die('find admin:' . $database->error);
		
		// requery
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
			die("FATAL: failed to insert default admin account. Error: " . $database->error);
	}
	$row = $ret->fetch_array();
	$adminid = $row['userid'];
	
	/* create the default admin user account */
	$sql = "SELECT * FROM user_profiles;";
    $ret = $database->query($sql);		
	if (!$ret || !$ret->num_rows)
	{
		$sql = "SELECT id AS userid 
		        FROM users 
				WHERE user_name='admin';";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{
			die("FATAL: failed to get userid for admin. Error: " . $database->error);
		}
	
		$row = $ret->fetch_array();
		$userid = $row['userid'];			
		$sql = "INSERT INTO user_profiles
				(id,fname,sname,title,initials,dob,address,contactno,email,cellphone)		
				VALUES($adminid,'System','Admin','Mr','N','2015-04-23','Town,Windhoek','061-','admin@crs-centaur.com','081');";
		$ret = $database->query($sql);	
	}

	/*
	 * global introJs counter
	 */
	$introJsIndex = 1;
	 
	/*
	 * helper function returns the total from a table
	 */
	function db_get_count($database, $table, $id) {
		$sql = "SELECT COUNT($id) FROM $table;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
			return 0;
		
		$row = $ret->fetch_array();
		return $row[0];
	}
	
/* returns live body from settings matching query
 * e.g. SMS_
 *      USERS_
 */	
function build_settings_table($match, $default) {
	global $database;
	$body = $default;
	
	$sql = "SELECT * FROM system_settings WHERE name LIKE '$match';";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{}
	else
	{
		$i = 1;
		$body = "";
		while ($row = $ret->fetch_array()) {
			$name = $row['name'];
			$value = $row['value'];
			$truefalse = $row['truefalse'];
			
			if ($truefalse) {
				$checked = $value == 1 ? 'checked' : '';
				$value = "<input type='checkbox' $checked />";
			}
			else
				$value = "<input type='text' class='form-control' value=\"$value\" />";
			
			$body .= "<tr><td>$i</td><td>$name</td><td>$value</td></tr>";
			$i++;
		}
	}
	
	return $body;
}
	
// gets the current date and time
function datetime_now() {
	return date('Y-m-d H:i:s');
}
 
// update the system log 
function update_system_log($action, $description) {
		global $database;
		
		// write to system log
		$action = strtoupper($action);
		$ip = get_client_ip();
		$now = datetime_now();
		
		$action = str_sanitize($action);
		$description = str_sanitize($description);
		
		$ret = $database->query("INSERT INTO system_log(action,ipaddress,entrydate,description)
		                         VALUES('$action', '$ip','$now', '$description');");	
}

// question on stackoverflow
// http://stackoverflow.com/questions/15699101/get-the-client-ip-address-using-php
// Function to get the client IP address
// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1')
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}	

/*
 * returns total
 */
function get_inbox($userid, $tableonly = false){
	global $database;
	
	$messages = array();
	$read = "";
	if ($tableonly)
		$read = " AND wasread=0";
	
	$sql = "SELECT * 
	        FROM notifications 
			WHERE userid_to=$userid $read 
			ORDER BY entrydate DESC;";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
		return false;

	$arr = [];
	$i = 0;
	
	while ($row = $ret->fetch_array()) {
		$i++;
		
		$arr['id'] = $row['id'];
		$arr['from'] = $row['userid_from'];
		$arr['entrydate'] = $row['entrydate'];
		$arr['subject'] = $row['subject'];
		$arr['body'] = $row['body'];
		$arr['wasread'] = $row['wasread'];
		
		// array_push
		$messages[] = $arr;
	}

	//$messages['total'] = $i;
	return $messages;
}
/*
 * creates a drop down
 */
function bs_dropdown($caption, $id, $arroptions, $icon=""){
	$temp = "";
	$opts = "";
	
	foreach ($arroptions as $val)
		$opts .= "<li role='presentation'><a role='menuitem' tabindex='-1' href='#'>$val</a></li>";
		
    $temp = "<div class='dropdown'>
				<button class='btn btn-default dropdown-toggle' type='button' id='$id' data-toggle='dropdown'>$icon $caption
				<span class='caret'></span></button>
				<ul class='dropdown-menu' role='menu' aria-labelledby='$id'>
				   $opts
				  <li role='presentation' class='divider'></li>
				  <li role='presentation'><a role='menuitem' tabindex='-1' href='#'>Cancel</a></li>
				</ul>
			  </div>";
					  
	return $temp;
}

function userinfo($userid, $fieldname ,$default){
		 global $database;
		 
	     /* get the group for this user */
		 $sql = "SELECT *, r.name AS rolename FROM users u, roles r WHERE u.id=$userid AND r.id = u.roleid;";
		 $ret = $database->query($sql);
		 if (!$ret || !$ret->num_rows)
			 return $default;
		 
		 $row = $ret->fetch_array();
		 $table = $row['rolename'];
		 
		 $sql = "SELECT *, CONCAT(fname,' ',sname) AS fullname FROM $table WHERE id=$userid;";
		 $ret = $database->query($sql);
		 if (!$ret || !$ret->num_rows)
			 return $default;	

		 $row = $ret->fetch_array();
		 return $row[$fieldname];		 
}

/*
 * returns an array of noticeboard items
 *
 * id, userid, entrydate, heading, author, body, image
 */
function get_notices($limit = 10){
	global $database;
	global $userid;
	global $role;
	
	$limit = (int) $limit;
	if (!$limit) $limit = 10;
	if ($limit)
		$limit = " LIMIT $limit";
	
	$disabled = $role == 'administrators' ? "" : "AND enabled=1";
	$sql = "SELECT 
	        u.id as user_id, 
			u.user_name, 
			n.id AS noticeboardid, 
			n.heading, n.image, n.body, n.entrydate, n.enabled
			FROM user_noticeboard n, users u 
			WHERE n.userid = u.id $disabled
			ORDER BY n.entrydate DESC $limit;";

	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
		return false;

	$notices = [];
	$arr = [];
	$i = 0;
	while ($row = $ret->fetch_array()) {
		$i++;
		
		$arr['id'] = $row['noticeboardid'];
		$arr['user_id'] = $row['user_id'];
		$arr['entrydate'] = $row['entrydate'];
		$arr['heading'] = $row['heading'];
		$arr['author'] = $row['user_name'];
		$arr['body'] = $row['body'];
		$arr['image'] = $row['image'];
		$arr['enabled'] = $row['enabled'];
		
		// array_push
		$notices[] = $arr;
	}

	return $notices;
}	

 /*
  * General functions
  */
  
 /* returns the breadcrumb */
 function breadcrumb(){
	 global $view;
	 
 	$view_header = ucfirst($view);
	return "<ul class='breadcrumb'>
	       <li>System</li>
	       <li class='active'>$view_header</li>
		  </ul>";
 }
 
 // for API consumer add / regenerate
 // we use this to generate the authkey
 function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	$max = 15;
	$temp = "";
	
    for ($i = 0; $i < $max; $i++) {
        $random_int = mt_rand();
        $temp .= $alphabet[$random_int % strlen($alphabet)];
    }
    return $temp;
}

// creates xml from array
function array2xml($data, $root = null){
    $xml = new SimpleXMLElement($root ? '<' . $root . '/>' : '<root/>');
    array_walk_recursive($data, function($value, $key)use($xml){
        $xml->addChild($key, $value);
    });
    return $xml->asXML();
}

// Array to CSV Function
// Copyright (c) 2014, Ink Plant
// https://inkplant.com/code/array-to-csv
function array2csv($data,$args=false) {
    if (!is_array($args)) { $args = array(); }
    foreach (array('download','line_breaks','trim') as $key) {
        if (array_key_exists($key,$args)) { $$key = $args[$key]; } else { $$key = false; }
    }

    //for this to work, no output should be sent to the screen before this function is called
    if ($download) {
        if ((is_string($download)) && (substr($download,-4) == '.csv')) { $filename = $download; }
        else { $filename = 'download.csv'; }
        header('Content-Type:text/csv');
        header('Content-Disposition:attachment; filename='.$filename);
    }

    if ($line_breaks == 'windows') { $lb = "\r\n"; }
    else { $lb = "\n"; }

    //get rid of headers row, if it exists (headers should exist as keys)
    if (array_key_exists('headers',$data)) { unset($data['headers']); }

    $i = 0;
    foreach ($data as $row) {
        $i++;
        //display headers
        if ($i == 1) { 
            $c = '';
            foreach ($row as $key => $value) {
                $key = str_replace('"','""',$key);
                if ($trim) { $key = trim($key); }
                echo $c.'"'.$key.'"'; $c = ',';
            }
            echo $lb;
        }

        //display values
        $c = '';
        foreach ($row as $key => $value) {
            $value = str_replace('"','""',$value);
            if ($trim) { $value = trim($value); }
            echo $c.'"'.$value.'"'; $c = ',';
        }
        echo $lb;
    }

    if ($download) { die(); }
}

/*
 * Warning this function only works
 * for SELECT COUNT(*) FROM *
 */
function query($sql){
	global $database;
	
	$ret = $database->query($sql);
	return (!$ret || !$ret->num_rows) ? 0 : $ret->fetch_array()[0];
}

/* clean up a string */
function str_sanitize($str){
		 global $database;
		 $str = $database->real_escape_string($str);
	     return trim($str);	
}

/*
 * returns true if item is in multidimensional array
 */
function exists_in_array($val, $arr) {
	foreach ($arr as $key)
	{
		$key = strtolower($key);
		$val = strtolower($val);
		
		if ($key == $val)
			return true;
	}	
}	

/*
 * helper: returns full span with font awesome icons
 */
function font_awesome($icon){
	return "<span class='fa fa-fw $icon'></span>";
}

function verify_right($view){
	global $myrights;
	
	// verify that this user has the right to manage this right
	foreach($myrights as $key=>$menu){
		foreach ($menu as $id=>$val)
			if (is_array($val)){
				foreach ($val as $k=>$data){
					if (strtolower($data['url']) == strtolower($view)){						
						return true;
						break;
					}
				}
			}
	}
	
	return false;
}

/*
 * returns file extension
 * lowercased
 */
function get_file_extension($file){
	$tmp = explode('.',$file);
	return strtolower(end($tmp)) ;
}

function build_selectbox($sql, $ctlid, $preselect_id=""){
	global $database;
	$options = "";

	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{ 
		// nothing 
	}
	else
	{
		while ($row = $ret->fetch_array())
		{
			$id = $row['id'];
			$name = $row['name'];
			$selected = "";

			if ($preselect_id)
			{
				if ((int)$id == (int)$preselect_id)
					$selected = "selected";
			}

			$options .= "<option value='$id' $selected>$name</option>";
		}
	}

	return  "<select name='$ctlid' id='$ctlid' class='form-control'>
	           $options
			  </select>
			  <script>
			   $(document).ready(function(){
			    //	$('#$ctlid').select2();
			   });
			  </script>";


}

function build_selectbox_textonly($sql, $ctlid, $preselect_id=""){
	global $database;
	$options = "";

	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{ 
		// nothing 
	}
	else
	{
		while ($row = $ret->fetch_array())
		{
			$id = $row['id'];
			$name = $row['name'];
			$selected = "";

			if ($preselect_id)
			{
				if ($name == $preselect_id)
					$selected = "selected";
			}

			$options .= "<option value='$name' $selected>$name</option>";
		}
	}

	return  "<select name='$ctlid' id='$ctlid' class='form-control'>
	           $options
			  </select>
			  <script>
			   $(document).ready(function(){
			    //	$('#$ctlid').select2();
			   });
			  </script>";


}

/*
 * returns a bootstrap color for status
 */
function decorate_status($status) {
			switch (strtolower($status)){
							case 'completed':
								$status = "<span class='label label-success'>$status</span>";
								break;
							
							case 'on hold':
								$status = "<span class='label label-warning'>$status</span>";
								break;
								
							case 'expired':
							case 'overdue':
								$status = "<span class='label label-danger'>$status</span>";
								break;
								
							case 'pending':
								$status = "<span class='label label-default'>$status</span>";
								break;
			}
						
			return $status;
}

 /*
  * simple query
  */
 function subquery($sql)
 {
	 global $database;
	 $ret = $database->query($sql);
	 if (!$ret || !$ret->num_rows)
		 return "";

	 $row = $ret->fetch_array();
	 return $row[0];
 }
 
/*
 * returns user friendly error for PHP upload errors
 */
function php_upload_error($code)
{
	    $message = "";
	    
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return "<p class='alert alert-danger'>$message</p>";
} 

 
 // creates a bootstrap alert
 function alertbuilder($alert,$type="success"){
 	return "<p class='alert alert-$type'>$alert</p>";
 }
 
 // for POST parameters
 // returns an alert and sets focus on the missing field
 function missing_parameter($field){
			return "<p class='alert alert-danger'>Some fields have not been filled in: <b>$field</b></p>
			        <script>$(document).ready(function(){
			        		$('[name=\"$field\"]').focus();
			        	});
			        </script>";
 }
 
  /*
  * returns table comment
  */
 function get_table_comment($table){
 			 global $database;
 			 $comment = "<i style='color:red'>Unable to get table comment</i>";
 			 $settings_db = settings::db_db;
  			 
  			 $sql = "SELECT table_comment 
			         FROM INFORMATION_SCHEMA.TABLES 
			         WHERE table_schema='$settings_db' 
			         AND table_name='$table';";
			 $ret0=$database->query($sql);
			 if (!$ret0 || !$ret0->num_rows){
			 } else {
				$row0    = $ret0->fetch_array();
				$comment = $row0[0];
			 }
			 
			 return $comment;
 }
 
/*
  * Give me a table, I will return an input form preselected
  */
 function build_form($table,$required, $ignored = null) {
 	global $database;
 	
	$inputs = "";


	$sql = "SHOW FULL COLUMNS
			  FROM `$table`;";		  
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{
		return alertbuilder("Failed to retrieve column to build the UI.","danger");
	}

		while ($row = $ret->fetch_array())
		{
			$input    = "";
			$field    = $row[0]; //Field -- 0
			$col_type = $row['Type'];
			
         $comment= $row['Comment'];
         
			$field_ = str_replace("_", " ", $field);
			$field_ = $comment ? $comment : ucfirst($field);
			$val    = @ $_POST[$field];
			
			$star_required = in_array(strtolower($field), $required) ? "<span style='color:red'>*</span>" : "";
			
			if ($ignored == null){
				//echo alertbuilder('ignored not specified','warning');
			} else {
				if (in_array(strtolower($field), $ignored)){
					//echo alertbuilder("ignoring this field: '$field'",'success');
					continue;
				}
			}
			
			// we ignore some fields
			switch (strtolower($field)){
				case 'entrydate':
				case 'user_id':
				case 'store_id':
				case 'id':
				case 'youth_id':
				case 'locked_user_id':
		 		case "youthid":
		 		case "table":
		 		case 'enabled':
		 		case 'sessionid':
		 		case 'approved':
		 		case 'quote_id':
					break;
					
           case 'visibility_id':
                $sql = "SELECT * 
                        FROM `list_visibility`;";
                $select = build_selectbox($sql,$field,$val);
                                         
                $inputs .= "<div class='form-group'>
                                <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
                                <div class='col-sm-8'>$select</div>
                            </div>
                            <script>
                                          $(document).ready(function(){
                                                   $('#$field').select2();
                                          });
                            </script>";
               break;
               
           case 'country_id':
                $sql = "SELECT * 
                        FROM `list_countries`;";
                $select = build_selectbox($sql,$field,$val);
                                         
                $inputs .= "<div class='form-group'>
                                <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
                                <div class='col-sm-8'>$select</div>
                            </div>
                            <script>
                                          $(document).ready(function(){
                                                   $('#$field').select2();
                                          });
                            </script>";
               break;
               
           case 'store_item_category_id':
                $sql = "SELECT * 
                        FROM `store_item_categories`;";
                $select = build_selectbox($sql,$field,$val);
                                         
                $inputs .= "<div class='form-group'>
                                <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
                                <div class='col-sm-8'>$select</div>
                            </div>
                            <script>
                                          $(document).ready(function(){
                                                   $('#$field').select2();
                                          });
                            </script>";
               break;
 
           case 'category_id':
                $sql = "SELECT * 
                        FROM `categories`;";
                $select = build_selectbox($sql,$field,$val);
                                         
                $inputs .= "<div class='form-group'>
                                <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
                                <div class='col-sm-8'>$select</div>
                            </div>
                            <script>
                                          $(document).ready(function(){
                                                   $('#$field').select2();
                                          });
                            </script>";
                 break;

				case 'color_background':
				case 'color_nav_selected':
				case 'color_container':
		          $input = "<div id='{$field}cb' class='input-group colorpicker-component'> 
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					            <span class='input-group-addon'><i class='fa fa-eyedropper'></i></span>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>
				  						  <script>
				  						   $(document).ready(function(){
				  						   	$('#{$field}cb').colorpicker();
				  						   });
				  						  </script>";
							break;
										
				case 'file_type_id':
					$sql = "SELECT * 
					        FROM `file_types`
					        ;";
					$select = build_selectbox($sql,$field,$val);
					
					$inputs .= "<div class='form-group'>
		    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
		    						 <div class='col-sm-8'>
										$select
		    						 </div>
		  						  </div>
									  <script>
									   $(document).ready(function(){
										    $('#$field').select2();
									   });
									  </script>";
					break;
					
				case 'date_started':
				case 'date_ended':
				case 'birth_date':
				case 'date_start':
				case 'date_end':
				case 'dob':
				case 'date_col':
					$input  = "<input type='text' class='form-control' value='$val' 
											id='$field' name='$field' 
											placeholder='YYYY-mm-dd'>";
											
					$inputs .=  "<div class='form-group'>
										<!-- <span class='input-group-addon'><li class='fa fa-calendar'></li></span> -->
										<label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
										<div class='col-sm-8'>$input</div>
									  </div>
									  <script>
									   $(document).ready(function(){
									   	 // set field to calendar & resolve drop down bug
										    $('#$field').datepicker({'format':'yyyy-mm-dd'});
										    $('#$field').datepicker().on('changeDate',function(e) {
										    $('#$field').datepicker('hide');
										  });
									   });
									  </script>";	
					break;				  			  		
						
				case 'store_id':
					$sql = "SELECT id, name
					        FROM `stores`;";
					$select = build_selectbox($sql,$field,$val);
					
					$inputs .= "<div class='form-group'>
		    						 <label class='control-label col-sm-3' for='$field'>$field</label>
		    						 <div class='col-sm-8'>
										$select
		    						 </div>
		  						  </div>
									  <script>
									   $(document).ready(function(){
									   	console.log('init select2 for $field');
										    $('#$field').select2();
									   });
									  </script>";
					break;

				case 'sex_id':
					$sql = "SELECT * 
					        FROM `list_sex`
					        ;";
					$select = build_selectbox($sql,$field,$val);
					
					$inputs .= "<div class='form-group'>
		    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
		    						 <div class='col-sm-8'>
										$select
		    						 </div>
		  						  </div>
									  <script>
									   $(document).ready(function(){
										    $('#$field').select2();
									   });
									  </script>";
					break;

				case 'cellphone':
					$input = "<input type='text' placeholder='0812223333' class='form-control' name='$field' 
					                 id='$field' value='$val'>";
					                
          $input = "<div class='input-group'>
			            <span class='input-group-addon'><span class='fa fa-mobile'></span></span>
			            <input type='text' placeholder='0812223333' class='form-control' 
			                                 name='$field' id='$field' value='$val'>
			          </div>";
          					                
					$inputs .= "<div class='form-group'>
		    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
		    						 <div class='col-sm-8'>
										$input
		    						 </div>
		  						  </div>";
					break;
								
				case 'fax':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-fax'></span></span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;
											
				case 'address':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-map-marker'></span></span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;
																						
				case 'telephone':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-phone'></span></span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;

				case 'social_facebook':
				case 'social_twitter':
				case 'social_youtube':
				case 'social_instagram':
				case 'social_snapchat':
				case 'social_pinterest':
				case 'social_googleplus':
				  $icon = "<span class='fa fa-picture-o'></span>";
				  
				  switch (strtolower($field)){
        				case 'social_facebook':
        				    $icon = "<span class='fa fa-facebook'></span>";
        				    break;
        				    
        				case 'social_twitter':
        				    $icon = "<span class='fa fa-twitter'></span>";
        				    break;
        				    
        				case 'social_youtube':
        				    $icon = "<span class='fa fa-youtube'></span>";
        				    break;
        				    
        				case 'social_instagram':
        				    $icon = "<span class='fa fa-instagram'></span>";
        				    break;
        				    
        				case 'social_snapchat':
        				    $icon = "<span class='fa fa-camera'></span>";
        				    break;
        				    
        				case 'social_pinterest':
        				    $icon = "<span class='fa fa-pinterest'></span>";
        				    break;
        				    
        				case 'social_googleplus':
        				    $icon = "<span class='fa fa-google-plus'></span>";
        				    break;        				    
				  }
				  
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'>$icon</span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ page $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;
							
				case 'banner':
				case 'logo':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-picture-o'></span></span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ URL $star_required</label>
				    						 <div class='col-sm-8'>
												$input
												<BR>
												<a href='$val' class='img-thumbnail fancybox'>
												 <img class='img-responsive' src='$val'>
												</a>
				    						 </div>
				  						  </div>";
							break;
														
				case 'email':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-envelope-o'></span></span>
					            <input type='email' placeholder='name@mail.com' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;

				case 'url_image_1':
				case 'url_image_2':
				case 'url_image_3':
				case 'url_image_4':
				     $preview = "";
				     if ($val != ""){
				         $preview = "<BR><a href='$val' data-title='Image preview' class='img-thumbnail fancybox'>
												 <img class='img-responsive' src='$val'></a>";
				     }
					$input = "<input type='text' class='form-control' name='$field' 
					                 id='$field' value='$val'>";
					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
												$preview
				    						 </div>
				  						  </div>";
				    break;
				    
				case 'url_video':
				    $fa_video = font_awesome('fa-video-camera');
				    $preview = ""; 
				    if ($val != ""){
                        $preview = "<BR><a href='$val' data-title='Video preview' class='img-thumbnail fancybox fancybox.iframe'>$fa_video Click to open video (if set)</a>"; 
				    }
				    $input = "<input type='text' class='form-control' name='$field' 
					                 id='$field' value='$val'>";
					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
												$preview
				    						 </div>
				  						  </div>";
				    break;
				    
				case 'file_name_banner':
				case 'file_name_logo':
				case 'filename':
				case 'project_proposal':	
					$input = "<input type='file' class='form-control' name='$field' 
					                 id='$field' value='$val'>";
					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ URL $star_required</label>
				    						 <div class='col-sm-8'>
												$input
												<BR>
												<a href='$val' class='img-thumbnail fancybox'>
												 <img class='img-responsive' src='$val'>
												</a>
				    						 </div>
				  						  </div>";
					break;
					
				case 'profile_pic':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-picture-o'></span></span>
					            <input type='file' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
		          		switch($table) {
		          			case 'students':
		          				// student profiles are outside admin
		          			   $val = "../$val";
		          				break;
		          		}
		          		
						$inputs .= "<div class='form-group'>
			    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
			    						 <div class='col-sm-8'>
											$input
											<BR>
											<a href='$val' class='img-thumbnail fancybox'>
											 <img class='img-responsive' style='max-width:150px;' src='$val'>
											</a>
			    						 </div>
			  						  </div>";
						break;
							
				default:							
						$tagsinput = "";
						$type = 'text';
						
						switch($col_type) {
							case 'tinyint(1)':
								$type = 'checkbox';
								$input = "<input type='$type' class='' 
														$tagsinput value='$val' 
								                 id='$field' name='$field'>";											
								break;
								
							case 'longtext':
								$type = 'textarea';
								$input = "<textarea id='$field' name='$field' class='form-control' rows='7'
														$tagsinput>$val</textarea>";												
								break;	
								
							default:
								$input = "<input type='$type' class='form-control' 
														$tagsinput value='$val' 
								                 id='$field' name='$field'>";	
								break;										
						}
						
						$inputs .= "<div class='form-group'>
										<label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
										<div class='col-sm-8'>$input</div>
									</div>";
						break;
				}
		}

		return $inputs;	
 }
 
/*
  * Give me a table, I will return an input form preselected
  */
 function build_form_users($table,$required, $ignored = null) {
 	global $database;

	$inputs = "";
	
	$sql = "SHOW FULL COLUMNS
			  FROM `$table`;";		  
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows)
	{
		return alertbuilder("Failed to retrieve column to build the UI.","danger");
	}

		while ($row = $ret->fetch_array())
		{
			$input    = "";
			$field    = $row[0]; //Field -- 0
			$col_type = $row['Type'];
			
         $comment= $row['Comment'];
         
			$field_ = str_replace("_", " ", $field);
			$field_ = $comment ? $comment : ucfirst($field);
			$val    = @ $_POST[$field];
			
			$star_required = in_array(strtolower($field), $required) ? "<span style='color:red'>*</span>" : "";
			
			if ($ignored == null){
				//echo alertbuilder('ignored not specified','warning');
			} else {
				if (in_array(strtolower($field), $ignored)){
					//echo alertbuilder("ignoring this field: '$field'",'success');
					continue;
				}
			}
			
			// we ignore some fields
			switch (strtolower($field)){
				case 'entrydate':
				case 'user_id':
				case 'id':
				case 'youth_id':
				case 'locked_user_id':
		 		case "youthid":
		 		case "table":
		 		case 'enabled':
		 		case 'sessionid':
		 		case 'approved':
					break;

           case 'category_id':
                $sql = "SELECT * 
                        FROM `categories`;";
                $select = build_selectbox($sql,$field,$val);
                                         
                $inputs .= "<div class='form-group'>
                                            <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
                                            <div class='col-sm-8'>
                                                           $select
                                            </div>
                                             </div>
                                                     <script>
                                                      $(document).ready(function(){
                                                               $('#$field').select2();
                                                      });
                                                     </script>";
                 break;

				case 'color_background':
				case 'color_nav_selected':
				case 'color_container':
		          $input = "<div id='{$field}cb' class='input-group colorpicker-component'> 
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					            <span class='input-group-addon'><i class='fa fa-eyedropper'></i></span>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>
				  						  <script>
				  						   $(document).ready(function(){
				  						   	$('#{$field}cb').colorpicker();
				  						   });
				  						  </script>";
							break;

				case 'file_type_id':
					$sql = "SELECT * 
					        FROM `file_types`
					        ;";
					$select = build_selectbox($sql,$field,$val);
					
					$inputs .= "<div class='form-group'>
		    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
		    						 <div class='col-sm-8'>
										$select
		    						 </div>
		  						  </div>
									  <script>
									   $(document).ready(function(){
										    $('#$field').select2();
									   });
									  </script>";
					break;
					
				case 'date_started':
				case 'date_ended':
				case 'birth_date':
				case 'date_start':
				case 'date_end':
				case 'dob':
					$input  = "<input type='text' class='form-control' value='$val' 
											id='$field' name='$field' 
											placeholder='YYYY-mm-dd'>";
											
					$inputs .=  "<div class='form-group'>
										<!-- <span class='input-group-addon'><li class='fa fa-calendar'></li></span> -->
										<label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
										<div class='col-sm-8'>$input</div>
									  </div>
									  <script>
									   $(document).ready(function(){
									   	 // set field to calendar & resolve drop down bug
										    $('#$field').datepicker({'format':'yyyy-mm-dd'});
										    $('#$field').datepicker().on('changeDate',function(e) {
										    $('#$field').datepicker('hide');
										  });
									   });
									  </script>";	
					break;				  			  		
						
				case 'store_id':
					$sql = "SELECT id, name FROM `stores`;";
					$select = build_selectbox($sql,$field,$val);
					
					$inputs .= "<div class='form-group'>
		    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
		    						 <div class='col-sm-8'>
										$select
		    						 </div>
		  						  </div>
									  <script>
									   $(document).ready(function(){
									   	console.log('init select2 for $field');
										    $('#$field').select2();
									   });
									  </script>";
					break;

				case 'sex_id':
					$sql = "SELECT * 
					        FROM `list_sex`
					        ;";
					$select = build_selectbox($sql,$field,$val);
					
					$inputs .= "<div class='form-group'>
		    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
		    						 <div class='col-sm-8'>
										$select
		    						 </div>
		  						  </div>
									  <script>
									   $(document).ready(function(){
										    $('#$field').select2();
									   });
									  </script>";
					break;
					
				case 'cellphone':
					$input = "<input type='number' placeholder='0812223333' class='form-control' name='$field' 
					                 id='$field' value='$val'>";
        					                
                  $input = "<div class='input-group'>
        			            <span class='input-group-addon'><span class='fa fa-mobile'></span></span>
        			            <input type='number' placeholder='0812223333' class='form-control' 
        			                                 name='$field' id='$field' value='$val'>
        			          </div>";
          					                
					$inputs .= "<div class='form-group'>
		    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
		    						 <div class='col-sm-8'>
										$input
		    						 </div>
		  						  </div>";
					break;
								
				case 'fax':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-fax'></span></span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;
											
				case 'address':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-map-marker'></span></span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;
																						
				case 'telephone':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-phone'></span></span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;

				case 'social_facebook':
				case 'social_twitter':
				case 'social_youtube':
				case 'social_instagram':
				case 'social_snapchat':
				  $icon = "<span class='fa fa-picture-o'></span>";
				  
				  switch (strtolower($field)){
        				case 'social_facebook':
        				    $icon = "<span class='fa fa-facebook'></span>";
        				    break;
        				    
        				case 'social_twitter':
        				    $icon = "<span class='fa fa-twitter'></span>";
        				    break;
        				    
        				case 'social_youtube':
        				    $icon = "<span class='fa fa-youtube'></span>";
        				    break;
        				    
        				case 'social_instagram':
        				    $icon = "<span class='fa fa-instagram'></span>";
        				    break;
        				    
        				case 'social_snapchat':
        				    $icon = "<span class='fa fa-camera'></span>";
        				    break;
				  }
				  
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'>$icon</span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ page $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;
							
				case 'banner':
				case 'logo':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-picture-o'></span></span>
					            <input type='text' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ URL $star_required</label>
				    						 <div class='col-sm-8'>
												$input
												<BR>
												<a href='$val' class='img-thumbnail fancybox'>
												 <img class='img-responsive' src='$val'>
												</a>
				    						 </div>
				  						  </div>";
							break;
														
				case 'email':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-envelope-o'></span></span>
					            <input type='email' placeholder='name@mail.com' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
				    						 </div>
				  						  </div>";
							break;

				case 'file_name_banner':
				case 'file_name_logo':
				case 'filename':
				case 'project_proposal':				
					$input = "<input type='file' class='form-control' name='$field' 
					                 id='$field' value='$val'>";
					                
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ URL $star_required</label>
				    						 <div class='col-sm-8'>
												$input
												<BR>
												<a href='$val' class='img-thumbnail fancybox'>
												 <img class='img-responsive' src='$val'>
												</a>
				    						 </div>
				  						  </div>";
					break;
					
				case 'profile_pic':
		          $input = "<div class='input-group'>
					            <span class='input-group-addon'><span class='fa fa-picture-o'></span></span>
					            <input type='file' placeholder='' class='form-control' 
					                                 name='$field' id='$field' value='$val'>
					          </div>";
		          					                
		          		switch($table) {
		          			case 'students':
		          				// student profiles are outside admin
		          			   $val = "../$val";
		          				break;
		          		}
		          		
							$inputs .= "<div class='form-group'>
				    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
				    						 <div class='col-sm-8'>
												$input
												<BR>
												<a href='$val' class='img-thumbnail fancybox'>
												 <img class='img-responsive' style='max-width:150px;' src='$val'>
												</a>
				    						 </div>
				  						  </div>";
							break;
							
				default:							
						$tagsinput = "";
						$type = 'text';
						
						switch($col_type) {
							case 'tinyint(1)':
								$type = 'checkbox';
								$input = "<input type='$type' class='' 
														$tagsinput value='$val' 
								                 id='$field' name='$field'>";											
								break;
								
							case 'longtext':
								$type = 'textarea';
								$input = "<textarea id='$field' name='$field' class='form-control' rows='6'
														$tagsinput>$val</textarea>";												
								break;	
								
							default:
								$input = "<input type='$type' class='form-control' 
														$tagsinput value='$val' 
								                 id='$field' name='$field'>";	
								break;										
						}
						
						$inputs .= "<div class='form-group'>
										<label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
										<div class='col-sm-8'>$input</div>
									</div>";
						break;
				}
		}

		return $inputs;	
 } 
 /*
  * Measuring page load times
  */
 function timer(){
    static $start;

    if (is_null($start))
    {
        $start = microtime(true);
    }
    else
    {
        $diff = round((microtime(true) - $start), 4);
        $start = null;
        return $diff;
    }
}

?>
