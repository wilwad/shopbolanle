<?php
	/*
	 * Approve stores application
	 * 
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

	/* ==================== custom code starts below ==========================*/	
	
	$fa_print       = font_awesome('fa-print');
	$fa_info        = font_awesome('fa-info-circle');
	$icon_user      = font_awesome('fa-user');
	$fa_envelope    = font_awesome('fa-envelope-o');
	$fa_plane       = font_awesome('fa-paper-plane');
	$fa_plus        = font_awesome('fa-plus');
	$icon_edit      = font_awesome('fa-edit');
	$fa_floppy      = font_awesome('fa-floppy-o');
   $fa_edit        = font_awesome('fa-edit');
   $fa_remove      = font_awesome('fa-remove');
 	$fa_exclamation = font_awesome('fa-exclamation-triangle');
 	$fa_check       = font_awesome('fa-check');
 	$fa_cog         = font_awesome('fa-cog');
 	$fa_bank        = font_awesome('fa-bank');
 	$fa_users       = font_awesome('fa-users');
 	$fa_mobile      = font_awesome('fa-mobile');
   $fa_list        = font_awesome('fa-list-alt');
   $fa_graduate    = font_awesome('fa fa-fw fa-graduation-cap');
   $fa_wrench      = font_awesome('fa-wrench');
   $fa_disability  = font_awesome('fa-wheelchair');
   $fa_check       = font_awesome('fa-check-circle');
   $fa_crop        = font_awesome('fa-crop');
   $fa_user        = font_awesome('fa-user');
   $fa_gradcap     = font_awesome('fa-graduation-cap');
   $fa_qualifications = font_awesome('fa-files-o');
   $fa_file			 = font_awesome('fa-file-o');
   $fa_key			 = font_awesome('fa-key');
	$fa_lifebuoy    = font_awesome('fa-life-buoy');
	$icon_view      = font_awesome('fa-eye');
	$icon_download  = font_awesome('fa-download');
	$fa_gamepad     = font_awesome('fa-gamepad');
 	$action         = @ $_GET['action'];
	$extra          = @ $_POST['extra'];
	
	// use this for app id, instead of id as
	// it gets overwritten by the queries
	$id             = (int) @ $_GET['id'];
	$storesid        = (int) @ $_GET['id'];		
	$recordid       = (int) @ $_GET['recordid'];
	$term           = @ $_GET['term'];
	
	// error/success message
	$application_notification = "";
			  
	// reusable for add and edit parent
	$fa_check = font_awesome('fa-check');
	$required = "<span style='color:red'>*</span>";
	$fa_upload = font_awesome('fa-upload');
	$star_required = "<span style='color:red'>*</span>";
	
	// what is encoded into the QR
	$qr_stores_url = "http://192.168.178.10/storesportal/admin/?view=manage-stores&action=edit&id=$storesid";
	
	switch ($action)
	{				
		case 'general-add':
			/*
			 * Creates UI for adding a record to a table
			 * id -- storesid
			 * table -- target
			 */
			$table = @ $_GET['table'];
			$table = trim($table);
			
			if (!strlen($table) || $storesid <= 0)
				die("<p class='alert alert-danger'>Check parameters</p>");
				
			$returnurl = "?view=manage-stores&action=edit&id=$storesid";
			$required = null;
			
			switch($table) {
				case 'employment_history':
					$returnurl = "?view=manage-stores&action=edit&id=$storesid&tab=tabemployment";
					
			 		// required fields
					$required = array('omas_id',
					                  'position',
					                  'employment_type_id',
					                  'contract_type_id',
					                  'date_started'/*,
					                  'date_ended'*/);
					break;

				default:
					die("<p class='alert alert-danger'>Check parameters</p>");
					break;
			}

			// This view title
			echo "<h4>$fa_plus Add new $table record 
			     <small class='pull-right' id='hint'>Fields with a <span style='color:red'>*</span> are required. 
			     Where a question is not applicable, write <b>N/A</b></small></h4>
				   <hr>";		
				   
			$errors   = "";
			$inputs = "";	
									
			// create a CSV list for javascript to
			// check if element text value is not null
 			$required_string = null;					
			foreach($required as $field){
					   $required_string[] = "'$field'";
			}				
			$required_string = implode(",", $required_string);
				
			// if extra is set, it means commit a change
			// before displaying the values of this document
			if ($extra)
			{
				foreach($required as $field){
						$_POST[$field] = addslashes(trim(@ $_POST[$field]));

						if ($_POST[$field] == ""){
							$errors = missing_parameter($field);
							break;
						}
				}				
							
				// all filled in
				if (!$errors)
				{
					// upload handled by ui.php
				}			
			}
			
			//echo "<p>Place of application</p>";
			
			// dynamically build stores profile form
			$inputs = build_form($table, $required);	

		$fa_floppy = font_awesome('fa-floppy-o');
		$fa_edit   = font_awesome('fa-edit');
		$fa_remove = font_awesome('fa-remove');

		$btn_save = "<div class='form-group'>
							<label class='control-label col-sm-3' for=''></label>
							<div class='col-sm-8'>
								<button class='btn btn-sm btn-success' onclick=\"return upload_document();\">$fa_floppy Save</button> 
							   <a href='$returnurl' class='btn btn-sm btn-danger'>$fa_remove Close</a>
							</div>
						  </div>
						  <p>&nbsp;</p>";					  
				
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
			
			echo "<form class='form-horizontal' role='form' method='POST'  enctype='multipart/form-data'>
					<!-- required params start -->
						<input type='hidden' name='view'      value='generic-add'>
						<input type='hidden' name='user_id'    value='$userid'>
						<input type='hidden' name='id'   value='$storesid'>
						<input type='hidden' name='table'   value='$table'>
						<input type='hidden' name='returnurl' value='$returnurl'>
						<input type='hidden' name='extra'     value='1'>
					<!-- required params end -->
						
						$errors
						$inputs					
						$btn_save					
					</form>
					
					<!-- validate inputs -->
					$scripts";
						
			break;
			
		case 'general-edit':
			/*
			 * Creates UI for adding a record to a table
			 * id -- storesid
			 * table -- target
			 */
			$table = @ $_GET['table'];
			$table = trim($table);
			
			if (!strlen($table) || $storesid <= 0)
				die("<p class='alert alert-danger'>Check parameters</p>");
				
			$returnurl = "?view=manage-stores&action=edit&id=$storesid";
			$required = null;
			
			switch($table) {
				case 'employment_history':
					$returnurl = "?view=manage-stores&action=edit&id=$storesid&tab=tabemployment";
					
			 		// required fields
					$required = array('omas_id',
					                  'position',
					                  'employment_type_id',
					                  'contract_type_id',
					                  'date_started'/*,
					                  'date_ended'*/);
					break;
					
				default:
					die("<p class='alert alert-danger'>Check parameters</p>");
					break;
			}

			// This view title
			echo "<h4>$fa_plus Add new $table record 
			     <small class='pull-right' id='hint'>Fields with a <span style='color:red'>*</span> are required. 
			     Where a question is not applicable, write <b>N/A</b></small></h4>
				   <hr>";		
				   
			$sql = "SELECT 
								*
					  FROM 
					  			`$table` 
					  WHERE 
					  			id=$recordid 
					  LIMIT 1;";
					  
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows)
				die("<p class='alert alert-warning'>The application specified does not exist. $sql</p>");
				
			$row = $ret->fetch_array();
							   
			$errors   = "";
			$inputs = "";	
									
			// create a CSV list for javascript to
			// check if element text value is not null
 			$required_string = null;					
			foreach($required as $field){
					   $required_string[] = "'$field'";
			}				
			$required_string = implode(",", $required_string);
				
			// if extra is set, it means commit a change
			// before displaying the values of this document
			if ($extra)
			{
				foreach($required as $field){
						$_POST[$field] = addslashes(trim(@ $_POST[$field]));

						if ($_POST[$field] == ""){
							$errors = missing_parameter($field);
							break;
						}
				}				
							
				// all filled in
				if (!$errors)
				{
					// upload handled by ui.php
				}			
			} else {

				$finfo = $ret->fetch_fields();
				
				// read from db
				foreach($finfo as $field){
						$_POST[$field->name] = $row[$field->name];
				}	
			}
			
			//echo "<p>Place of application</p>";
			
			// dynamically build stores profile form
			$inputs = build_form($table, $required);	

		$fa_floppy = font_awesome('fa-floppy-o');
		$fa_edit   = font_awesome('fa-edit');
		$fa_remove = font_awesome('fa-remove');

		$btn_save = "<div class='form-group'>
							<label class='control-label col-sm-3' for=''></label>
							<div class='col-sm-8'>
								<button class='btn btn-sm btn-success' onclick=\"return upload_document();\">$fa_floppy Update</button> 
							   <a href='$returnurl' class='btn btn-sm btn-danger'>$fa_remove Close</a>
							</div>
						  </div>
						  <p>&nbsp;</p>";					  
				
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
			
			echo "<form class='form-horizontal' role='form' method='POST'  enctype='multipart/form-data'>
					<!-- required params start -->
						<input type='hidden' name='view'      value='generic-edit'>
						<input type='hidden' name='user_id'    value='$userid'>
						<input type='hidden' name='id'   value='$storesid'>
						<input type='hidden' name='table'   value='$table'>
						<input type='hidden' name='recordid'   value='$recordid'>
						<input type='hidden' name='returnurl' value='$returnurl'>
						<input type='hidden' name='extra'     value='1'>
					<!-- required params end -->
						
						$errors
						$inputs					
						$btn_save					
					</form>
					
					<!-- validate inputs -->
					$scripts";
						
			break;
			
		case 'summary':		
				$panels = "Invalid company.";
				
				$sql = "SELECT * 
						  FROM stores 
						  WHERE id=$id
						  LIMIT 1;";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows){
					
				echo "<div class='row'>
				       <div class='col-md-12'>
				        $panels
				       </div>
						</div>";
						
				}
				else {
						$panels = "";
		
						 $row = $ret->fetch_array();
							  $id          = $row['id'];
							  $name        = $row['name'];
							  $description = $row['description'];
							  $banner      = $row['banner'];
							  $logo        = $row['logo'];
							  $website     = $row['website'];
							  $telephone   = $row['telephone'];
							  $town        = $row['town'];
							  $bgcolor     = $row['color_background'];

							  if ($banner != ''){
							  		$bgcolor ='';
							  		$banner = "background-image: url($banner);";
							  } else {
							      $bgcolor = "background: $bgcolor;";
							      $banner = '';							  	 
							  }
							  
						echo "    <div class='row'>
										<div class='col-md-12'>								
								                <div class='cardheader'
								                		style='$bgcolor
								                		       $banner 
								                				 min-height:150px;
								                				 max-height:200px;'>
								                    <img alt='' class='img-responsive'
								                    		style='margin: 15px; max-height:150px' src='$logo'>
								                </div>
								                <div class='info'>
								                    <div class='title'>
								                        <h3>$name <small class='pull-right'><a href='?view=manage-stores&action=edit&id=$id'>$fa_edit Edit company</a></small></h3>
								                    </div>
								                    <div class='desc'>$description</div>
								                    <div class='desc'><strong>Telephone:</strong> $telephone</div>
								                    <div class='desc'><strong>Town:</strong> $town</div>
								                    <div class='desc'><strong>Website:</strong> <a href='$website' target='_blank'>$website</a></div>
								                </div>

								                 <hr>
								                 <div class='desc'>$description</div>					
								        </div>								
									</div>";	
				}	
				break;
														  
		case 'upload-document':
				$table     = "stores_documents";
				$returnurl = "?view=manage-stores&action=edit&id=$id&tab=tabattachments";				

			   $disable_editing = "";
			   $icon = font_awesome('fa-file');
				
				$fa_upload = font_awesome('fa-upload');		
							
		 		// required fields
		 		$required_string = "";
				$required = array('title',
										'file_type_id',
				                  'filename');
				
				// create a CSV list for javascript to
				// check if element text value is not null
	 			$required_string = null;					
				foreach($required as $field){
						   $required_string[] = "'$field'";
				}				
				$required_string = implode(",", $required_string);
					
				// if extra is set, it means commit a change
				// before displaying the values of this document
				if ($extra)
				{
					foreach($required as $field){
							$_POST[$field] = addslashes(trim(@ $_POST[$field]));
	
							if ($_POST[$field] == ""){
								$errors = missing_parameter($field);
								break;
							}
					}				
								
					// all filled in
					if (!$errors)
					{
						// upload handled by ui.php
					}			
				}
				
				// dynamically build stores profile form
				$table  = "documents";
				$inputs = "";

				$sql = "SHOW FULL COLUMNS
						  FROM `$table`;";		  
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
					echo alertbuilder("Failed to retrieve column to build the UI.","danger");
				}
				else
				{
					while ($row = $ret->fetch_array())
					{
						$input  = "";
						$field  = $row[0]; //Field -- 0
						$col_type = $row['Type'];
						
						//echo alertbuilder($col_type,'success');
						
	               $comment= $row['Comment'];
	               
						$field_ = str_replace("_", " ", $field);
						$field_ = $comment ? $comment : ucfirst($field);
						$val    = @ $_POST[$field];
						
						// we ignore some fields
						switch (strtolower($field)){
							case 'entrydate':
							case 'user_id':
							case 'id':
							case 'id':
							case 'locked_user_id':
								break;
															
							case 'birth_date':
							   $star_required = in_array($field, $required) ? "<span style='color:red'>*</span>" : "";
							   
								$input  = "<input type='text' class='form-control' value='$val' 
														id='$field' name='$field' 
														placeholder='YYYY-mm-dd'>";
								$inputs .=  "<div class='form-group'>
													<label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
													<div class='col-sm-6'>$input</div>
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
					  						
							case 'file_type_id':
								$star_required = in_array($field, $required) ? "<span style='color:red'>*</span>" : "";
								
								$sql = "SELECT * 
								        FROM `file_types`
								        ORDER BY name ASC;";
								$select = build_selectbox($sql,$field,$val);
								
								$inputs .= "<div class='form-group'>
					    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
					    						 <div class='col-sm-6'>
													$select
					    						 </div>
					  						  </div>
												  <script>
												   $(document).ready(function(){
													    $('#$field').select2();
												   });
												  </script>";
								break;
								
							case 'path':
							case 'filename':
							case 'project_proposal':
							
								$input = "<input type='file' class='form-control' name='$field' 
								                 id='$field' value='$val'>";
								                
								$inputs .= "<div class='form-group'>
					    						 <label class='control-label col-sm-3' for='$field'>$field_ $star_required</label>
					    						 <div class='col-sm-6'>
													$input
					    						 </div>
					  						  </div>";
								break;
								
							default:				
									$star_required = in_array($field, $required) ? "<span style='color:red'>*</span>" : "";			
									$tagsinput = "";
									$type = 'text';
									
									switch($col_type) {
										case 'tinyint(1)':
											$type = 'checkbox';
											$input = "<input type='$type' class='form-control' 
																	$tagsinput value='$val' 
											                 id='$field' name='$field'>";											
											break;
											
										case 'longtext':
											$type = 'textarea';
											$input = "<textarea id='$field' name='$field' class='form-control' 
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
													<div class='col-sm-6'>$input</div>
												</div>";
									break;
							}
					}
				}	
				
				echo "<div class='row'>
				       <div class='col-md-12'>
				        <h4>$fa_upload Upload a file <small class='pull-right'>Scan a document and attach it to the application</small></h4>
				        <hr>
							<form class='form-horizontal' role='form' method='POST' 
							      id='form' enctype='multipart/form-data'>
								<input type='hidden' name='table' value='$table'>
								<input type='hidden' name='userid' value='$userid'>
	                     <input type='hidden' name='view' value='upload'>
							   <input type='hidden' name='storesid' value='$id'>
								<input type='hidden' name='returnurl' value='$returnurl'>
								$inputs
									  						    	
								<div class='form-group'>
	    						 <label class='control-label col-sm-3' for=''></label>
	    						 <div class='col-sm-6'>
									<a href='#' class='btn btn-primary btn-sm' onclick=\"upload_document($id, $userid, $id);\" >$icon Upload document</a>
									<a href='?view=manage-stores&action=edit&id=$id&tab=tabattachments'  class='btn btn-warning btn-sm'>Cancel</a>
	    						 </div>
	  						  </div>
  						  								
							</form>
						  </div>
						 </div>";
				
			echo "<script>
						function upload_document(appid, userid, id){				 

									var flds    = [$required_string];
									
									for(var idx=0; idx<flds.length; idx++)
									{
									  var fld = $('#'+flds[idx]);
									  
									  if ( ! fld.val().length )
									  {
										alertify.error('Fill in ' + flds[idx]);
										fld.focus();
										
										return false;
									  }
								   }
						 
						 			$('form').submit();
									 return true;
						}
					  </script>
					  ";		
			break;
		
		// editing an application
		
		case 'edit':
				/* editing an application */
				$disable_editing = "";				
				          
								
			// receptionists cannot  edit
			switch ($role) {
				     //case 'back office':
			   	  case 'receptionists':
			   	  //case 'secretariats':
			   	  case 'top_levels':
			   	  case 'ministers':
			   	  case 'administrators':
			   	  		 $disable_editing = "disabled";
				          break;
		      }

			$application_status_text = "";
			
			switch ($role) {
					  case 'administrators':
			   	  case 'secretariats':			   	  
				          break;
		      }
    
			if ($extra){
					// update application has been called!
					$sql = "UPDATE `stores`
							  SET ";
               
					foreach($_POST as $key=>$val){						 
						 switch ($key){
						 		// remove the other views
						 		case 'action':
						 		case 'view':
						 		case 'extra':
						 		case 'table-history_length':
						 			// bug: not sure where this column comes from
						 			break;
						 					
						 		default:
						           $val = htmlentities($val);
						           $sql .= "$key='$val', ";						 		
						 			break;
						 }
 				   }
 				   // remove trailing string
 				   $sql = substr($sql, 0, strlen($sql)-2);
 				   $sql .= " WHERE id=$storesid"; 		

					// ensure that we don't have existing id numbers
					$id_number = @ $_POST['id_number'];
					$sql_check_file_number = "SELECT 
																* 
													  FROM 
													  			`stores`
													  WHERE 
													  			id_number='$id_number' AND
													  			id <> $storesid"; 		
					
					$ret = $database->query($sql_check_file_number);		   
					if (!$ret || !$ret->num_rows){
						// nothing found so proceed
						
 				      $ret = $database->query($sql);
 				      $error = $database->error; 				   
 				   
 				   	if (!$ret)
 				   	{
 				   		$application_notification = "<p class='alert alert-danger'>$fa_exclamation Failed to update application. Error: $error</p>";
							$table = "unk_log";
							$appid = $id;
							$userid_ = $userid;
							$caption = "App edit error";
							$description = "Failed to update the application. Error: $error";							
							//update_application_log($table, $appid, $userid_, $caption, $description);
 				   	}
 				   	else
 				   	{
 				   		// write the application log
							$table = "unk_log";
							$appid = $id;
							$userid_ = $userid;
							$caption = "app edit";
							$description = "Application was updated successfully.";	
							//update_application_log($table, $appid, $userid_, $caption, $description);
							
 				   		$application_notification = "<p class='alert alert-success'>$fa_check $description</p>";
 				   	}			
					} else {
						// duplication!
						$application_notification = "<p class='alert alert-danger'>$fa_exclamation An application already exists with that ID number.</p>";
					} 				   		   
				}
				
				$application =  "<button class='btn btn-sm btn-success'><li class='fa fa-fw fa-floppy-o'></p>&nbsp;Save changes</button>
									  <small class='pull-right'><i>Please note: when this application is under review, this option will not be available.</i></small>";

				$filetypes = null;
				$attachments_dropdown = "";
				$sql = "SELECT * 
						  FROM `list_document_type` 
						  ORDER BY name ASC";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else
				{
					$opts = "";

					while ($row = $ret->fetch_array())
					{
						$id      = $row['id'];
						$caption = $row['name'];
						$opts   .= "<option value='$id'>$caption</option>";
					}

					$doctypes = "<select class='form-control' name='filetypeid' id='filetypeid'>$opts</select>";
					$icon = font_awesome('fa-file');
					$attachments_dropdown  = "
														<a href='?view=manage-stores&action=upload-document&id=$storesid' 
														class='btn btn-primary btn-sm' onsclick=\"upload_document($id, $userid, $id); $('form').submit(function(e){ if(e.preventDefault) e.preventDefault(); else e.returnValue = false;});\">$icon Upload a file</a>
										 <script>
												function upload_document(appid, userid, id){					 
															  var dialogcss = \"<div class='table-responsive'><form id='form-upload' method='POST' enctype='multipart/form-data'><table class='table table-no-border'><tbody><tr><th>Document type <span class='required'>*</span></th><td>$doctypes</td></tr><tr><th>File<span class='required'>*</span></th><td><input type='hidden' name='view' value='upload'><input type='hidden' name='appid' value='\"+appid+\"'><input type='hidden' name='table' value='unk_documents'><input type='hidden' name='userid' value='\"+userid+\"'><input class='form-control' type='file' name='filename' id='filename'></td></tr></tbody></table></form></div>\";
															  
																BootstrapDialog.show({
																	title: 'Upload document',
																	onshown: function(){
																		// set Select2()
																		$('.bootstrap-dialog select').select2()
																	},
																	message: function(dialog) {
																		var content = $(dialogcss);
																		return content;
																	},
																	buttons: [
																		{
																		label: 'Upload document',
																		action: function(){
																			var flds    = ['#filetypeid','#filename'];
																			
																			for(var idx=0; idx<flds.length; idx++)
																			{
																			  var fld = $(flds[idx]);
																			  
																			  if ( ! fld.val().length )
																			  {
																				alertify.error('Select a file to be uploaded');
																				return false;
																			  }
																		   }

																			// submit form
																			$('#form-upload').submit();
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
											  </script>
											  ";	
				}

				$body_attachments = "<tr><td colspan='5' class='text-center'><i>There are no files saved for this application.</i></td></tr>";
				$idx = 0;
				$sql = "SELECT * , 
				             d.entrydate as entrydate_, 
						       d.id as d_id, 
						       ft.name as filetype_name, 
						       u.id as user_id
						FROM 
						       documents d, 
						       file_types ft,
							   users u
						WHERE 
						       d.id=$storesid AND 
							   ft.id = file_type_id AND 
							   u.id = d.user_id
					   ORDER BY d.entrydate DESC";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
					// nothing found
				}
				else
				{
					$body_attachments = "";
					$table            = "documents";

					while ($row = $ret->fetch_array())
					{
						$idx++;
						$icon_trash = font_awesome('fa-trash');
						$id        = $row['d_id'];
						$date      = $row['entrydate_'];
						$date      = "<abbr class='timeago' title='$date'>$date</abbr>";
						$type      = $row['filetype_name'];
						$title     = $row['title'];
						$filename  = $row['filename'];
						$filename  = basename($filename);
						$icon_user = font_awesome('fa-user');
						$username  = $row['user_name'];
						$user_id   = $row['user_id'];
						$user      = "<a href=?view=users&action=summary&id=$user_id'>$icon_user $username</a>";
						$open      = "<a href='uploads/$filename' data-toggle='tooltip' rel='attachments' title='View this document in a popup' class='fancybox' data-title='$title - $type' data-fancybox-type='iframe'>$icon_view</a>
 										  <a data-toggle='tooltip' style='color:green' title='Download this document' href='download.php?table=$table&filename=$filename'>$icon_download</a>";
						$delete    = "<a data-toggle='tooltip' style='color:red' title='Delete this document' href='#' onclick=\"delete_attachment($id,$userid); return false;\">$icon_trash</a>";
						$body_attachments .= "<tr>
														<td>$idx</td>
														<td>$date</td>
														<td>$title</td>
														<td>$type</td>
														<td>$user</td>
														<td>$open $delete</td>														
													 </tr>";
					}
				}

				$attachments = "<p></p>
								<div class='table-responsive'>
								<table id='table-documents' class='table table-hover table-bordered'>
								 <thead>
								  <tr>
								  	<th>#</th>
								  	<th>Date</th>
								  	<th>Title</th>
								  	<th>Type</th>
								  	<th>Added by</th>
								  	<th>Actions</th>
								  </tr>
								 </thead>
								 <tbody>
								  $body_attachments		 
								 <tbody>
								</table>
							   </div>
							  <script> 							  
									   var func_success2 = function(data) {
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

								function delete_attachment(id, userid){					 
												BootstrapDialog.show({
													title: 'Delete attachment',
													message: 'Are you sure you would like to delete this attachment?'
													,
													buttons: [
														{
														label: 'Delete',
														action: function(){
														  var payload = {'view': 'application-document-delete', 
																		 'userid': userid,
																		 'id': id
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
											 return false;
										}

									$(document).ready(function(){
										$('.fancybox').fancybox({
											  beforeLoad: function(){
											  	this.title = $(this.element).attr('data-title');},
											   'overlayShow':true,
											   'hideOnContentClick':false,
											  iframe: {preload:false},
											  									    	
									        prevEffect: 'none',
									        nextEffect: 'none',
									        closeBtn: true,
									        arrows: false,
									        nextClick: false
										});    
									});
							  </script>";
							
				// employment
				$body_employment = "<tr><td colspan='8' class='text-center'><i>No employment information recorded for this person.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT eh.id,
									eh.entrydate,
									o.id AS company_id,
									o.name As company, 
									u.id AS user_id,
									u.user_name,
									position,
									date_started,
									date_ended
							FROM users u, omas o, employment_history eh
							WHERE eh.omas_id = o.id AND
									u.id = eh.user_id AND
									eh.id=$storesid
							ORDER BY eh.id DESC;";
					   				
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else
				{
					$body_employment = "";
					$table = 'employment_history';
					
					while ($row = $ret->fetch_array())
					{
						$icon_trash = font_awesome('fa-trash');
						$icon_view  = font_awesome('fa-eye');
						$idx++;
						$id          = $row['id'];
						$companyid   = $row['company_id'];
						$date        = $row['entrydate'];
						$date        = "<abbr class='timeago' title='$date'>$date</abbr>";
						
						$company     = $row['company'];
						$company     = "<a href='?view=list-stores&action=summary&id=$companyid'>$fa_bank $company</a>";
						$position    = $row['position'];
						$started     = $row['date_started'];
						$ended       = $row['date_ended'];
						$user_id     = $row['user_id'];
						$icon_user   = font_awesome('fa-user');
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$icon_user $username</a>";
						$action      = "<a style='color:blue' href='?view=manage-stores&action=general-edit&table=employment_history&id=$storesid&recordid=$id'>$fa_edit Edit</a>
										    <a style='color:red' href='' onclick=\"return delete_item('$table','id',$id);\">$icon_trash Delete</a>";
										    
						$body_employment .= "<tr><td>$idx</td>
														 <td>$date</td>
														 <td>$company</td>
														 <td>$position</td>
														 <td>$started</td>
														 <td>$ended</td>														 
														 <td>$user</td>
														 <td>$action</td>
														 </tr>";
					}
					
					$body_employment .= "<script>
					                   $(document).ready(function(){
					                   	//$('#table-employment').dataTable();
					                   }) ;
					                  </script>";
				}
				$table_employment = "
								<p>
									<a href='?view=manage-stores&action=general-add&table=employment_history&id=$storesid' 
									class='btn btn-primary btn-sm'>$fa_plus Add employment record</a>
								</p>						
								<div class='table-responsive'>
								<table id='table-employment' class='table table-hover table-bordered '>
								 <thead>
								  <tr><th>#</th>
								  		<th>Entry date</th>
								  		<th>Organization</th>
								  		<th>Position</th>
								  		<th>Started</th>
								  		<th>Left</th>
								  		<th>Added by</th>
								  		<th>Actions</th>
								  </tr>
								 </thead>
								 <tbody>
									$body_employment		 
								 <tbody>
								</table>
							   </div>";
						     		   
			   
				// qualifications
				$body_qualifications = "<tr><td colspan='7' class='text-center'><i>No qualifications information recorded for this person.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT d.id,
									d.entrydate,
									d.title,
									o.id as company_id,
									o.name as company,
									ft.name as file_type,
									d.date_started,
									d.date_ended,
									u.id AS user_id,
									u.user_name
							FROM users u, 
								  qualifications d,
								  omas o,
								  file_types ft
							WHERE 
									d.id = $storesid AND
									o.id = d.omas_id AND
									ft.id = d.file_type_id AND
									u.id = d.user_id
							ORDER BY d.id DESC;";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else
				{
					$body_qualifications = "";

					while ($row = $ret->fetch_array())
					{
						$idx++;
						$id          = $row['id'];
						$title       = $row['title'];
						$company     = $row['company'];
						$companyid   = $row['company_id'];
						$filetype    = $row['file_type'];
						$icon_trash  = font_awesome('fa-trash');
						$icon_view   = font_awesome('fa-eye');
						
						$company     = "<a href='?view=list-stores&action=summary&id=$companyid'>$fa_bank $company</a>";
												
						$date        = $row['entrydate'];
						$date        = "<abbr class='timeago' title='$date'>$date</abbr>";						
						$user_id     = $row['user_id'];
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$fa_user $username</a>";
						$edit        = "<a href='?view=manage-stores&action=general-edit&table=qualifications&id=$storesid&recordid=$id' data-placement='bottom' data-toggle='tooltip' title='Make changes to this qualifications record'>$icon_edit Edit</a>";
						$delete      = "<a href='#' data-placement='bottom' data-toggle='tooltip' title='Delete this qualifications record' onclick=\"return delete_item('qualifications','id',$id);\" style='color:red'>$icon_trash Delete</a>";
						$action      = "$edit $delete";
						$body_qualifications .= "<tr>
													 <td>$idx</td>
													 <td>$date</td>
													 <td>$title</td>
													 <td>$filetype</td>
													 <td>$company</td>													 
													 <td>$user</td>
													 <td>$action</td>
													</tr>";
					}
					
					$body_qualifications .= "<script>
					                   $(document).ready(function(){
					                   	//$('#table-qualifications').dataTable();
					                   }) ;
					                  </script>";
				}
				$table_qualifications = "
													<p>
														<a href='?view=manage-stores&action=general-add&table=qualifications&id=$storesid' 
														class='btn btn-primary btn-sm'>$fa_plus Add qualifications record</a>
													</p>					
													<div class='table-responsive'>
													<table id='table-qualifications' class='table table-hover table-bordered '>
													 <thead>
													  <tr><th>#</th>
													  		<th>Entry date</<th>
													  		<th>Title</th>
													  		<th>Type</th>
													  		<th>Organization</th>								  		
													  		<th>Added by</th>
													  		<th>Actions</th>
													  </tr>
													 </thead>
													 <tbody>
														$body_qualifications		 
													 <tbody>
													</table>
												   </div>";	
												   
				// education
				$body_education = "<tr><td colspan='8' class='text-center'><i>No education information recorded for this person.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT d.id,
									d.entrydate,
									d.title,
									o.name as company,
									o.id as company_id,
									ft.name as file_type,
									d.date_started,
									d.date_ended,
									u.id AS user_id,
									u.user_name
							FROM users u, 
								  education d,
								  omas o,
								  file_types ft
							WHERE 
									d.id = $storesid AND
									o.id = d.omas_id AND
									ft.id = d.file_type_id AND
									u.id = d.user_id
							ORDER BY d.id DESC;";
					   				
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else
				{
					$body_education = "";

					while ($row = $ret->fetch_array())
					{
						$idx++;
						$id          = $row['id'];
						$title       = $row['title'];
						$company     = $row['company'];
						$companyid   = $row['company_id'];
						
						$filetype    = $row['file_type'];
						$icon_trash  = font_awesome('fa-trash');
						$icon_view   = font_awesome('fa-eye');			
												
						$company     = "<a href='?view=list-stores&action=summary&id=$companyid'>$fa_bank $company</a>";
									
						$date        = $row['entrydate'];
						$date        = "<abbr class='timeago' title='$date'>$date</abbr>";						
						$user_id     = $row['user_id'];
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$fa_user $username</a>";
						$edit        = "<a href='?view=manage-stores&action=general-edit&table=education&id=$storesid&recordid=$id' data-placement='bottom' data-toggle='tooltip' title='Make changes to this qualifications record'>$icon_edit Edit</a>";
						$delete      = "<a href='#' data-placement='bottom' data-toggle='tooltip' title='Delete this education record' onclick=\"return delete_item('education','id',$id);\" style='color:red'>$icon_trash Delete</a>";
						$action      = "$edit $delete";
						$body_education .= "<tr>
													 <td>$idx</td>
													 <td>$date</td>
													 <td>$title</td>
													 <td>$filetype</td>
													 <td>$company</td>													 
													 <td>$user</td>
													 <td>$action</td>
													</tr>";
					}
					
					$body_education .= "<script>
					                   $(document).ready(function(){
					                   	//$('#table-education').dataTable();
					                   }) ;
					                  </script>";
				}
				$table_education = "
													<p>
														<a href='?view=manage-stores&action=general-add&table=education&id=$storesid' 
														class='btn btn-primary btn-sm'>$fa_plus Add education record</a>
													</p>					
													<div class='table-responsive'>
													<table id='table-education' class='table table-hover table-bordered '>
													 <thead>
													  <tr><th>#</th>
													  		<th>Entry date</<th>
													  		<th>Title</th>
													  		<th>Type</th>
													  		<th>Organization</th>								  		
													  		<th>Added by</th>
													  		<th>Actions</th>
													  </tr>
													 </thead>
													 <tbody>
														$body_education		 
													 <tbody>
													</table>
												   </div>";	
							   				  
				// training
				$body_training = "<tr><td colspan='8' class='text-center'><i>No training information recorded for this person.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT d.id,
									d.entrydate,
									d.title,
									o.name as company,
									o.id as company_id,
									ft.name as file_type,
									d.date_started,
									d.date_ended,
									u.id AS user_id,
									u.user_name
							FROM users u, 
								  training d,
								  omas o,
								  file_types ft
							WHERE 
									d.id = $storesid AND
									o.id = d.omas_id AND
									ft.id = d.file_type_id AND
									u.id = d.user_id
							ORDER BY d.id DESC;";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else
				{
					$body_training = "";

					while ($row = $ret->fetch_array())
					{
						$idx++;
						$id          = $row['id'];
						$title       = $row['title'];
						$company     = $row['company'];
						$companyid     = $row['company_id'];
						
						$filetype    = $row['file_type'];
						$icon_trash  = font_awesome('fa-trash');
						$icon_view   = font_awesome('fa-eye');		
						
						$company     = "<a href='?view=list-stores&action=edit&id=$companyid'>$fa_bank $company</a>";
										
						$date        = $row['entrydate'];
						$date        = "<abbr class='timeago' title='$date'>$date</abbr>";						
						$user_id     = $row['user_id'];
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$fa_user $username</a>";
						$edit        = "<a href='?view=manage-stores&action=general-edit&table=training&id=$storesid&recordid=$id' data-placement='bottom' data-toggle='tooltip' title='Make changes to this training record'>$icon_edit Edit</a>";
						$delete      = "<a href='#' data-placement='bottom' data-toggle='tooltip' title='Delete this education record' onclick=\"return delete_item('training','id',$id);\" style='color:red'>$icon_trash Delete</a>";
						$action      = "$edit $delete";
						$body_training .= "<tr>
													 <td>$idx</td>
													 <td>$date</td>
													 <td>$title</td>
													 <td>$filetype</td>
													 <td>$company</td>													 
													 <td>$user</td>
													 <td>$action</td>
													</tr>";
					}
					
					$body_training .= "<script>
					                   $(document).ready(function(){
					                   	//$('#table-training').dataTable();
					                   }) ;
					                  </script>";
				}
				$table_training = "
													<p>
														<a href='?view=manage-stores&action=general-add&table=training&id=$storesid' 
														class='btn btn-primary btn-sm'>$fa_plus Add training record</a>
													</p>					
													<div class='table-responsive'>
													<table id='table-training' class='table table-hover table-bordered '>
													 <thead>
													  <tr><th>#</th>
													  		<th>Entry date</<th>
													  		<th>Title</th>
													  		<th>Type</th>
													  		<th>Organization</th>								  		
													  		<th>Added by</th>
													  		<th>Actions</th>
													  </tr>
													 </thead>
													 <tbody>
														$body_training		 
													 <tbody>
													</table>
												   </div>";	
							   
							   
				// skills
				$body_skills = "<tr><td colspan='7' class='text-center'><i>No skills information recorded for this person.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT d.id,
									d.entrydate,
									d.description,
									sl.name as skill_level,
									d.date_started,
									u.id AS user_id,
									u.user_name
							FROM users u, 
								  skills d,
								  list_skill_level sl
							WHERE 
									d.id = $storesid AND
									sl.id = d.skill_level_id AND
									u.id = d.user_id
							ORDER BY d.id DESC;";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else
				{
					$body_skills = "";

					while ($row = $ret->fetch_array())
					{
						$idx++;
						$id          = $row['id'];
						$description = $row['description'];
						
						$skilllevel  = $row['skill_level'];
						$datestarted = $row['date_started'];
						$date        = $row['entrydate'];
						$date        = "<abbr class='timeago' title='$date'>$date</abbr>";						
						$user_id     = $row['user_id'];
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$fa_user $username</a>";
						$edit        = "<a href='?view=manage-stores&action=general-edit&table=skills&id=$storesid&recordid=$id' data-placement='bottom' data-toggle='tooltip' title='Make changes to this skills record'>$icon_edit Edit</a>";
						$delete      = "<a href='#' data-placement='bottom' data-toggle='tooltip' title='Delete this record' onclick=\"return delete_item('skills','id',$id);\" style='color:red'>$icon_trash Delete</a>";
						$action      = "$edit $delete";
						$body_skills .= "<tr>
													 <td>$idx</td>
													 <td>$date</td>
													 <td>$description</td>
													 <td>$skilllevel</td>
													 <td>$datestarted</td>													 
													 <td>$user</td>
													 <td>$action</td>
													</tr>";
					}
					
					$body_skills .= "<script>
					                   $(document).ready(function(){
					                   	//$('#table-skills').dataTable();
					                   }) ;
					                  </script>";
				}
				$table_skills = "
													<p>
														<a href='?view=manage-stores&action=general-add&table=skills&id=$storesid' 
														class='btn btn-primary btn-sm'>$fa_plus Add skills record</a>
													</p>					
													<div class='table-responsive'>
													<table id='table-skills' class='table table-hover table-bordered '>
													 <thead>
													  <tr><th>#</th>
													  		<th>Entry date</<th>
													  		<th>Description</th>
													  		<th>Level</th>
													  		<th>Date started</th>								  		
													  		<th>Added by</th>
													  		<th>Actions</th>
													  </tr>
													 </thead>
													 <tbody>
														$body_skills		 
													 <tbody>
													</table>
												   </div>";													   				
				// interventions
				$body_interventions = "<tr><td colspan='8' class='text-center'><i>No interventions information recorded for this person.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT d.id,
									d.entrydate,
									d.title,
									o.name as company,
									o.id as company_id,
									d.date_started,
									d.date_ended,
									u.id AS user_id,
									u.user_name
							FROM users u, 
								  interventions d,
								  omas o
							WHERE 
									d.id = $storesid AND
									o.id = d.omas_id AND
									u.id = d.user_id
							ORDER BY d.id DESC;";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
				}
				else
				{
					$body_interventions = "";

					while ($row = $ret->fetch_array())
					{
						$idx++;
						$id          = $row['id'];
						$title       = $row['title'];
						$company      = $row['company'];
						$companyid    = $row['company_id'];
						
						$company     = "<a href='?view=list-stores&action=summary&id=$companyid'>$fa_bank $company</a>";
																
						$icon_trash  = font_awesome('fa-trash');
						$icon_view   = font_awesome('fa-eye');						
						$date        = $row['entrydate'];
						$date        = "<abbr class='timeago' title='$date'>$date</abbr>";						
						$user_id     = $row['user_id'];
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$fa_user $username</a>";
						$edit        = "<a href='?view=manage-stores&action=general-edit&table=interventions&id=$storesid&recordid=$id' data-placement='bottom' data-toggle='tooltip' title='Make changes to this interventions record'>$icon_edit Edit</a>";
						$delete      = "<a href='#' data-placement='bottom' data-toggle='tooltip' title='Delete this education record' onclick=\"return delete_item('interventions','id',$id);\" style='color:red'>$icon_trash Delete</a>";
						$action      = "$edit $delete";
						$body_interventions .= "<tr>
													 <td>$idx</td>
													 <td>$date</td>
													 <td>$title</td>
													 <td>$company</td>													 
													 <td>$user</td>
													 <td>$action</td>
													</tr>";
					}
					
					$body_interventions .= "<script>
					                   $(document).ready(function(){
					                   	//$('#table-interventions').dataTable();
					                   }) ;
					                  </script>";
				}
				$table_interventions = "
													<p>
														<a href='?view=manage-stores&action=general-add&table=interventions&id=$storesid' 
														class='btn btn-primary btn-sm'>$fa_plus Add interventions record</a>
													</p>					
													<div class='table-responsive'>
													<table id='table-interventions' class='table table-hover table-bordered '>
													 <thead>
													  <tr><th>#</th>
													  		<th>Entry date</<th>
													  		<th>Title</th>
													  		<th>Organization</th>								  		
													  		<th>Added by</th>
													  		<th>Actions</th>
													  </tr>
													 </thead>
													 <tbody>
														$body_interventions		 
													 <tbody>
													</table>
												   </div>";	
												   
												   
				// relations
				$body_relations = "<tr><td colspan='8' class='text-center'><i>No relations information recorded for this person.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT d.id,
									d.entrydate,
									d.name,
									d.telephone,
									r.name as relation,
									u.id AS user_id,
									u.user_name
							FROM users u, 
								  relations d,
								  list_relationship_types r
							WHERE 
									d.id = $storesid AND
									r.id = d.relation_type_id AND
									u.id = d.user_id
							ORDER BY d.id DESC;";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
				}
				else
				{
					$body_relations = "";

					while ($row = $ret->fetch_array())
					{
						$idx++;
						$id          = $row['id'];
						$name       = $row['name'];
						$telephone       = $row['telephone'];
						$relation    = $row['relation'];
						$icon_trash  = font_awesome('fa-trash');
						$icon_view   = font_awesome('fa-eye');						
						$date        = $row['entrydate'];
						$date        = "<abbr class='timeago' title='$date'>$date</abbr>";						
						$user_id     = $row['user_id'];
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$fa_user $username</a>";
						$edit        = "<a href='?view=manage-stores&action=general-edit&table=relations&id=$storesid&recordid=$id' data-placement='bottom' data-toggle='tooltip' title='Make changes to this relations record'>$icon_edit Edit</a>";
						$delete      = "<a href='#' data-placement='bottom' data-toggle='tooltip' title='Delete this education record' onclick=\"return delete_item('relations','id',$id);\" style='color:red'>$icon_trash Delete</a>";
						$action      = "$edit $delete";
						$body_relations .= "<tr>
													 <td>$idx</td>
													 <td>$date</td>
													 <td>$name</td>
													 <td>$relation</td>													 
													 <td>$telephone</td>													 
													 <td>$user</td>
													 <td>$action</td>
													</tr>";
					}
					
					$body_relations .= "<script>
					                   $(document).ready(function(){
					                   	//$('#table-relations').dataTable();
					                   }) ;
					                  </script>";
				}
				$table_relations = "
													<p>
														<a href='?view=manage-stores&action=general-add&table=relations&id=$storesid' 
														class='btn btn-primary btn-sm'>$fa_plus Add relations record</a>
													</p>					
													<div class='table-responsive'>
													<table id='table-relations' class='table table-hover table-bordered '>
													 <thead>
													  <tr><th>#</th>
													  		<th>Entry date</<th>
													  		<th>Name</th>
													  		<th>Relation</th>								  		
													  		<th>Telephone</th>								  		
													  		<th>Added by</th>
													  		<th>Actions</th>
													  </tr>
													 </thead>
													 <tbody>
														$body_relations		 
													 <tbody>
													</table>
												   </div>";	
												   

				// dependents
				$body_dependents = "<tr><td colspan='8' class='text-center'><i>No dependents information recorded for this person.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT d.id,
									d.entrydate,
									d.name,
									d.telephone,
									r.name as relation,
									u.id AS user_id,
									u.user_name
							FROM users u, 
								  dependents d,
								  list_relationship_types r
							WHERE 
									d.id = $storesid AND
									r.id = d.relation_type_id AND
									u.id = d.user_id
							ORDER BY d.id DESC;";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
				}
				else
				{
					$body_dependents = "";

					while ($row = $ret->fetch_array())
					{
						$idx++;
						$id          = $row['id'];
						$name        = $row['name'];
						$relation    = $row['relation'];
						$telephone   = $row['telephone'];
						$icon_trash  = font_awesome('fa-trash');
						$icon_view   = font_awesome('fa-eye');						
						$date        = $row['entrydate'];
						$date        = "<abbr class='timeago' title='$date'>$date</abbr>";						
						$user_id     = $row['user_id'];
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$fa_user $username</a>";
						$edit        = "<a href='?view=manage-stores&action=general-edit&table=dependents&id=$storesid&recordid=$id' data-placement='bottom' data-toggle='tooltip' title='Make changes to this dependents record'>$icon_edit Edit</a>";
						$delete      = "<a href='#' data-placement='bottom' data-toggle='tooltip' title='Delete this education record' onclick=\"return delete_item('dependents','id',$id);\" style='color:red'>$icon_trash Delete</a>";
						$action      = "$edit $delete";
						$body_dependents .= "<tr>
													 <td>$idx</td>
													 <td>$date</td>
													 <td>$name</td>
													 <td>$relation</td>
													 <td>$telephone</td>													 
													 <td>$user</td>
													 <td>$action</td>
													</tr>";
					}
					
					$body_dependents .= "<script>
					                   $(document).ready(function(){
					                   	//$('#table-dependents').dataTable();
					                   }) ;
					                  </script>";
				}
				$table_dependents = "
													<p>
														<a href='?view=manage-stores&action=general-add&table=dependents&id=$storesid' 
														class='btn btn-primary btn-sm'>$fa_plus Add dependents record</a>
													</p>					
													<div class='table-responsive'>
													<table id='table-dependents' class='table table-hover table-bordered '>
													 <thead>
													  <tr><th>#</th>
													  		<th>Entry date</<th>
													  		<th>Name</th>
													  		<th>Relation</th>
													  		<th>Telephone</th>								  		
													  		<th>Added by</th>
													  		<th>Actions</th>
													  </tr>
													 </thead>
													 <tbody>
														$body_dependents		 
													 <tbody>
													</table>
												   </div>";													   
												   
				// disability
				$body_disability = "<tr><td colspan='8' class='text-center'><i>No disability information recorded for this person.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT d.id,
									d.entrydate,
									d.description,
									u.id AS user_id,
									u.user_name
							FROM users u, 
								  disability d
							WHERE 
									d.id = $storesid AND
									u.id = d.user_id
							ORDER BY d.id DESC;";

				
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else
				{
					$body_disability = "";

					while ($row = $ret->fetch_array())
					{
						$idx++;
						$id= $row['id'];
						$icon_trash  = font_awesome('fa-trash');
						$icon_view   = font_awesome('fa-eye');						
						$date        = $row['entrydate'];
						$date        = "<abbr class='timeago' title='$date'>$date</abbr>";						
						$description = $row['description'];
						$user_id     = $row['user_id'];
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$icon_user $username</a>";
						$edit        = "<a href='?view=manage-stores&action=general-edit&id=$storesid&recordid=$id&table=disability' data-placement='bottom' data-toggle='tooltip' title='Make changes to this disability record'>$icon_edit Edit</a>";
						$delete      = "<a href='#' data-placement='bottom' data-toggle='tooltip' title='Delete this disability record' onclick=\"return delete_item('disability','id',$id)\" style='color:red'>$icon_trash Delete</a>";
						$action      = "$edit $delete";
						$body_disability .= "<tr>
													 <td>$idx</td>
													 <td>$date</td>
													 <td>$description</td>
													 <td>$user</td>
													 <td>$action</td>
													</tr>";
					}
					
					$body_disability .= "<script>
					                   $(document).ready(function(){
					                   	//$('#table-employment').dataTable();
					                   }) ;
					                  </script>";
				}
				$table_disability = "
								<p>
									<a href='?view=manage-stores&action=general-add&table=disability&id=$storesid' 
									class='btn btn-primary btn-sm'>$fa_plus Add disability record</a>
								</p>					
								<div class='table-responsive'>
								<table id='table-employment' class='table table-hover table-bordered '>
								 <thead>
								  <tr><th>#</th>
								  		<th>Entry date</th>
								  		<th>Description</th>
								  		<th>Added by</th>
								  		<th>Actions</th>
								  </tr>
								 </thead>
								 <tbody>
									$body_disability		 
								 <tbody>
								</table>
							   </div>";
							   						   
				// notes
				$body_notes = "<tr><td colspan='4'><i>There are no notes recorded for this application.</i></td></tr>";
				$idx = 0;
				$sql = "SELECT 
										*, 
										cl.id as note_id,
										u.id as user_id, 
										cl.entrydate as entrydate_
						FROM 
										unk_notes cl, 
										users u
						WHERE 
										cl.application_id=$id AND 
										cl.user_id = u.id 
						ORDER BY 
										cl.entrydate DESC;";
										
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else
				{
					$body_notes = "";

					while ($row = $ret->fetch_array())
					{
						$icon_trash = font_awesome('fa-trash');
						$icon_view  = font_awesome('fa-eye');
						$idx++;
						$noteid     = $row['note_id'];
						$date        = $row['entrydate_'];
						$date     = "<abbr class='timeago' title='$date'>$date</abbr>";
						$text        = $row['text'];
						$user_id     = $row['user_id'];
						$icon_user   = font_awesome('fa-user');
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$icon_user $username</a>";
						$delete      = "<a href='#' onclick=\"confirmdelete('unk_notes',$noteid); return false;\" title='Delete this note' data-toggle='tooltip'>$icon_trash</a>";
						$body_notes .= "<tr>
												<td>$idx</td>
												<td>$date</td>
												<td>$text</td>
												<td>$user</td>
												<td>$delete</td>
											 </tr>";
					}
				}		   
				$icon_note = font_awesome('fa-edit');
				$notes = "<button onclick=\"addnote($id,$userid); return false;\" class='btn btn-sm btn-primary' $disable_editing>$icon_note Add a note</button> 
						  <script>
								   var func_success3 = function(data) {
														  console.log('result follows');
														  console.log(data);
														  if (data == 'true')
															  window.location.href='?view=manage-stores&action=summary&id=$id&tab=tabnotes';
														  else
															  alertify.error(data);
														};
								   var func_error3 = function (a,b,c) {
														alertify.error(b + ' ' + c);
														console.log(a,b);
													  };

							function addnote(app_id, userid){					 
										  var dialogcss = \"<div class='table-responsive'> \
														<table class='table table-no-border'> \
														 <tbody> \
														  <tr><th>Note text <span class='required'>*</span></th><td><textarea id='note' class='form-control'></textarea></td></tr> \
														 </tbody> \
														</table></div> \
														<style>\";
										  
											BootstrapDialog.show({
												title: 'Add a note to this application',
												message: function(dialog) {
													var content = $(dialogcss);
													return content;
												},
												buttons: [
													{
													label: 'Save note',
													action: function(){
														var fld    = $('#note');
														
														// check match
														if ( ! fld.val().trim().length )
														{
															alertify.error('Type the note');
															return false;
														}
														
													  var payload = {'view': 'application-note-add', 
																	 'userid': userid,
																	 'application_id': app_id,
																	 'note':fld.val().trim()
																	 };
																	 
													  console.log(payload);
													  
													  ajax('api/api.php', 
														   'post',
														   'text', 
														   payload, 
														   func_success3,
														   func_error3);

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
						  </script>
						  <p></p>
						  <div class='table-responsive'>
							<table id='table-notes' class='table table-hover table-bordered '>
							 <thead>
							  <tr><th>#</th><th>Date</th><th>Notes</th><th>Added by</th><th>Action</th></tr>
							 </thead>
							 <tbody>
							  $body_notes		 
							 <tbody>
							</table>
						   </div>";
						   
				$body_history = "<tr><td colspan='5'><i>There are no events recorded for this application.</i></td></tr>";
				$idx = 0;
				
				$sql = "SELECT 
										*, u.id as user_id, 
										cl.entrydate as entrydate_
						FROM 
										unk_log cl, 
										users u
						WHERE 
										cl.application_id=$id AND 
										cl.user_id = u.id
					   ORDER BY 
					   				cl.entrydate DESC;";
					   				
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else
				{
					$body_history = "";

					while ($row = $ret->fetch_array())
					{
						$icon_trash = font_awesome('fa-trash');
						$icon_view  = font_awesome('fa-eye');
						$idx++;
						$date        = $row['entrydate_'];
						$date     = "<abbr class='timeago' title='$date'>$date</abbr>";
						$action      = $row['action'];
						$description = $row['description'];
						$user_id     = $row['user_id'];
						$icon_user   = font_awesome('fa-user');
						$username    = $row['user_name'];
						$user        = "<a href=?view=users&action=summary&id=$user_id'>$icon_user $username</a>";
						$delete= "<span style='color:red' class='pull-right'><a href=''>$icon_trash Delete</a></span>";
						$body_history .= "<tr><td>$idx</td><td>$date</td><td>$action</td><td>$description</td><td>$user</td></tr>";
					}
					
					$body_history .= "<script>
					                   $(document).ready(function(){
					                   	$('#table-history').dataTable();
					                   }) ;
					                  </script>";
				}
				$history = "<div class='table-responsive'>
							<table id='table-history' class='table table-hover table-bordered '>
							 <thead>
							  <tr><th>#</th><th>Date</th><th>Action</th><th>Description</th><th>User</th></tr>
							 </thead>
							 <tbody>
								$body_history		 
							 <tbody>
							</table>
						   </div>";
				
				$style = "<style type='text/css'>

							#myScrollspy.ul.nav-tabs.affix {
								top: 10px; /* Set the top position of pinned element */
								z-index: 10000;
								background-color: #ffffff;
							}
							</style>";
							
				// active tab
				$tab = @ $_GET['tab'];
				
				$script = "<script>
							$(document).ready(function(){
								// select the active tab
								console.log('active the current tab', '$tab');
								activate_tab('$tab');
								
								// unlock the document for editing
								console.log('bind beforeunload');
                        $(window).bind('beforeunload', function() {
                        	// ajax api.php, 'applications_unk', 'user_id_lock', 0
                        	//alert('resetting user_id_lock');
                        });
							}); 

						   </script>";
						   
				$fa_plus = font_awesome('fa-plus');
				$fa_info = font_awesome('fa-info-circle');
				$fa_lock = font_awesome('fa-lock');

				$fa_user   = font_awesome('fa-user');
				$fa_remove = font_awesome('fa-remove');
				$fa_floppy = font_awesome('fa-floppy-o');
				$fa_edit   = font_awesome('fa-edit');

				$sql = "SELECT 
				                *, 
				                yp.entrydate as app_entrydate, 
				                u.id as user_id
						  FROM 
									stores yp,									
							 		users u
						  WHERE 
									yp.id=$storesid AND 
									u.id = yp.user_id;";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
					die(alertbuilder("Error #0: Unable to locate application identified by id $id.",'danger'));
										
				while ( $row = $ret->fetch_array() ){	
					while ($columns = $ret->fetch_field()){
							 $col = $columns->name;
					 		 $_POST[$col] = $row[$col];							
					}			
				}

				// only admin, minister or board committee can change status of application
				$role      = $users->user($userid)->get('rolename');
				$role      = strtolower($role);

				switch ($role) {
					 case 'administrators':
					 case 'ministers':
					 case 'board_committees':
					 case 'secretariats':
						  break;
						  
					default:
					      $status_dropdown = "";
						  break;
				}

				// bootstrap color code for status
				$status = @ $_POST['status'];
				$status = decorate_status($status);
				
				$entrydate = @ $_POST['entrydate'];
								
				$body_section_a = "";
				$errors_buffer = "";
			
			$required = array('name_surname',
			                  'name_first',
			                  'nationality_id',
			                  'citizenship_id',
			                  'id_number',
			                  'birth_date',
			                  'birth_country_id',
			                  'region_id',
			                  'sex_id');
			                  				
				$buffer = build_form('stores',$required);	
				$body_section_a = "<form class='form-horizontal' role='form'>
				                    $errors_buffer
									     $buffer
				                   </form>";

				$timeago = "<abbr class='timeago' title='$entrydate'>$entrydate</abbr>";
				
				// get a list of services
				//todo move this to its own function
				$services = "";
				$sql = "SHOW TABLES 
				        LIKE 'service_%';";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows){
					$services = alertbuilder("$fa_cog There are no services available at the moment.",'warning');;
				} 
				else {
					while ($row = $ret->fetch_array()){
						$table = $row[0];
						$comment = "<i>Unable to retrieve service description</i>";			
						$settings_db = settings::db_db;
						
						// get the service description from the table (comment)
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
        
        				$action = ""; //verb
        				$actions= "";
        				$btn_type = "btn-default";

						// is the person registered?
						$sql = "SELECT id 
								  FROM `$table`
								  WHERE id={$storesid};";
						$ret1=$database->query($sql);
						if (!$ret1 || !$ret1->num_rows){
							 $action = "register";
							 $actions="Click to Subscribe this stores to this service";
						} else {
							 $action = "view";
							 $actions="$fa_check Subscribed. Click to edit details.";
							 $btn_type = "btn-success";
						}
						
						$services .= "<p>
						                <a href='?view=services-handler&storesid=$storesid&table=$table&action=$action' class='btn $btn_type btn-block text-vertical-center'>
						                 <span class='fa fa-fw fa-cog'></span>
						                 $comment <small class='pull-right'>$actions</small>
						                </a>
						               </p>";
					}					
				}
				
				$btn_send_inbox = "<a href='?view=notify-sms&to=indiv-$storesid' class='btn btn-sm btn-primary'>$fa_envelope Send to Inbox</a>";
				$btn_send_sms   = "<a href='?view=notify-sms&to=indiv-$storesid' class='btn btn-sm btn-success'>$fa_mobile Send SMS</a>";
				$btn_send_push  = "<a href='?view=notify-push&to=indiv-$storesid' class='btn btn-sm btn-warning'>$fa_plane Send Push Notification</a>";

				$logo                   = "";
				$stores_username     = "Undefined";	
				$stores_creator_name = "";
				$stores_creator_id   = "";
				
				$stores_entrydate = "";

				$sql = "SELECT y.entrydate, 
									y.user_name, 
									y.logo, 
				               u.user_name as creator_name, 
				               u.id as creator_id
				        FROM stores y, users u 
				        WHERE y.id=$storesid AND 
				        		   u.id = y.user_id
				        LIMIT 1;";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else {
					$row = $ret->fetch_array();
					$stores_entrydate    = $row['entrydate'];
					$stores_username     = $row['user_name'];
					$logo                   = $row['logo'];
 				   $stores_creator_name = $row["creator_name"];
				   $stores_creator_id   = $row["creator_id"];
									
					$storespic = file_exists($storespic) ? $storespic : 'profiles/avatar-blank.jpg';
				}

				$account = "<!-- form class='form-horizontal' -->
				
		                  <div class='form-group'>
								 <label class='control-label col-sm-3' for='user_name'>User name </label>
								 <div class='col-sm-8'><input type='text' class='form-control' value='$stores_username' id='user_name'></div>
								</div>
								
		                  <div class='form-group'>
								 <label class='control-label col-sm-3' for='password'>Password </label>
								 <div class='col-sm-8'><input type='password' class='form-control' value='' placeholder='Leave this blank when not changing password' id='password'></div>
								</div>
																
								<div class='form-group'>
								 <label class='control-label col-sm-3' for='profile'>Profile picture </label>
								 <div class='col-sm-8'>
								  <img class='img-responsive img-thumbnail' id='profile' src='$logo'>
								  <BR>
								  <input type='file' id='profilepic' class='form-control'>
								  <script>
								  	 $('#profilepic').change(function(){
								  	 	alertify.success('profilepic change');
								  	 	
										var file = this.files[0];
										var imagefile = file.type;
										
										var match= ['image/jpeg','image/png','image/jpg'];
										
										if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
										{
											alertify.error('Please Select A valid image file. Only jpeg, jpg and png images allowed');
											return false;
										}
										
										var reader = new FileReader();
										
										reader.onload = imageIsLoaded;
										reader.readAsDataURL(this.files[0]);
									});

									function imageIsLoaded(e) {
											$('#profile').attr('src', e.target.result);
											alertify.success('imageIsLoaded');
									};
								  </script>
								 </div>
								</div>				
								
		                  <div class='form-group'>
								 <label class='control-label col-sm-3' for=''>Date Created</label>
								 <div class='col-sm-8'><abbr class='timeago' title='$stores_entrydate'>$stores_entrydate</abbr></div>
								</div>
											
		                  <div class='form-group'>
								 <label class='control-label col-sm-3' for=''>Created By</label>
								 <div class='col-sm-8'><a href='?view=users&action=summary&id=$stores_creator_id'>$fa_user $stores_creator_name</a></div>
								</div>
																			
								<div class='form-group'>
								<label class='control-label col-sm-3' for=''></label>
								<div class='col-sm-8'>
									<a href='#' class='btn btn-sm btn-success' onclick='update_stores_login($storesid); return false;'><span class='fa fa-fw fa-floppy-o'></span> Update stores login details</a>
									<script>
									function update_stores_login(storesid){
										alertify.success('update_stores_login called');
										
										var form  = $(\"<form action='' method='post' enctype='multipart/form-data'>\" +
										              \"<input type='hidden' name='view' value='upload-stores-profilepic'>\"+
										              \"<input type='hidden' name='returnurl' value='?view=manage-stores&action=edit&id={$storesid}&tab=tabaccount'>\"+
										              \"<input type='hidden' name='storesid' value='$storesid'></form>\");
										var clone = $('#profilepic');
										$(clone).attr('name','profilepic');
										$(clone).appendTo($(form)); 
										$(form).appendTo('body').submit();
									}
									</script> 
								</div>
							  </div>
								  <p>&nbsp;</p>					
								<!-- /form -->";
            
				echo "$style

						<h4>$fa_edit Edit stores Application
							<div class='pull-right'>
							 <a href='?view=manage-stores&action=summary&id=$storesid' onclick=\"\" title='Summary' data-placement='bottom' data-toggle='tooltip' class='btn btn-default btn-sm'>$fa_list Summary</a>
							 <a href='#' onclick=\"$('form').submit();\" title='Update application' data-placement='bottom' data-toggle='tooltip' class='btn btn-success btn-sm'>$fa_floppy Update</a>
							 <a href='?view=manage-stores&action=print&id=$storesid' title='Print' data-placement='bottom' data-toggle='tooltip' class='btn btn-default btn-sm'>$fa_print Print</a>
							 <a href='?view=manage-stores' title='Cancel updating this application' data-placement='bottom' data-toggle='tooltip' class='btn btn-warning btn-sm'>Close</a></small>		
							</div>
						</h4>
						<hr>
						$application_notification

						<ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>
							<li class='active'><a href='#tabapplication' data-toggle='tab'>Profile</a></li>
							<li><a href='#tabemployment' data-toggle='tab'>Discounts</a></li>													
							<!--<li><a href='#tabmessages' data-toggle='tab'>$fa_envelope Messages</a></li>
							-->
							<li><a href='#tabaccount' data-toggle='tab'>$fa_users Users</a></li>	
							<!-- <li><a href='#tabnotes' data-toggle='tab'>Notes</a></li>	-->									
						 </ul>
						 <p></p>	
						 
						<form method='POST' class='form-horizontal' role='form'>
							<input type='hidden' name='view' value='application-unk'>
							<input type='hidden' name='action' value='edit'>
							<input type='hidden' name='extra' value='1'>
						
							<div id='my-tab-content' class='tab-content'>
								<div class='tab-pane active' id='tabapplication'>
									$body_section_a		
								</div>				

								<div class='tab-pane' id='tabqualifications'>
								 $table_qualifications
								</div>
											
								<div class='tab-pane' id='tabmessages'>
								 <p>$btn_send_inbox $btn_send_sms $btn_send_push</p>
								 <BR>
								 <i>Messages sent to this stores will come here</i>
								</div>
													
								<div class='tab-pane' id='tabnotes'>
									$notes
								</div>

									
								<div class='tab-pane' id='tabservices'>
								   <h5><span class='label label-default'>Services</span> <small class='pull-right'>A stores may be subscribed to one or more services</small></h5>
								   
									$services
								</div>	
								
								<div class='tab-pane' id='tabaccount'>
								  $account
								</div>									
						  </div>	
						</form>

					  $script";
			break;

		default:
			/*
			 * if filter is set, do a filter
			 */
			 
			require('lib/pagination/pagination.php');			
         $start   = (int) @ $_GET['start'];
         $display = 20; // (int) @ $_GET['max'];
        
         $total_participants = 0;
			$filter = "";
			$arr_fields = [];
			$fields = "";
			$data = "";
			$maxcolumns = 0;
			$maxrows = 0;	
			$thead = "";
			$tbody = "";
			
			$fa_edit  = font_awesome('fa-edit');
			$fa_user  = font_awesome('fa-user');
			$fa_trash = font_awesome('fa-trash');	
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS
				        *
				        FROM(
				        		 SELECT
				             yp.id,
				             yp.entrydate,
				             yp.name,
							    u.user_name,
							    u.id AS user_id_
							FROM
							    stores yp,
							    users u
							WHERE
							      u.id = yp.user_id) temp
							LIMIT 0,1;";

        $rec = $database->query($sql);
        if (!$rec || !$rec->num_rows)
        		die(alertbuilder($database->error,'danger'));

        // total rows
        $sql = "SELECT FOUND_ROWS() AS total;";
        $rec1 = $database->query($sql);
        if (!$rec1 || !$rec1->num_rows)
        		die(alertbuilder($database->error,'danger'));
        		
        $row1 = $rec1->fetch_array();
		  $max = $row1['total'];
		  $page = $start;
		  $start_original = $start;
		  $page = $page < 1 ? 1 : $page;
		  $page = $page > $max ? $max : $page;
		
		  $pg = new bootPagination();
		  $pg->pagenumber = $page;
		  $pg->pagesize = $display;
		  $pg->totalrecords = $max;
		  $pg->showfirst = true;
		  $pg->showlast = true;
		  $pg->paginationcss = "pagination-large";
		  $pg->paginationstyle = 1; // 1: advance, 0: normal
		  $pg->defaultUrl = "?view=approve-stores";
		  $pg->paginationUrl = "?view=approve-stores&start=[p]";
		  
		  $start = $start - 1;
		  $start = $start < 0 ? 0 : $start;
		  $start = $start > $max ? $max : $start; 
		  
        $start = $start * $display;

			$sql = "SELECT 
							SQL_CALC_FOUND_ROWS
							*
						FROM
						(
						    SELECT
			             yp.id,
			             yp.entrydate,
						    yp.name,
						    yp.logo,
						    yp.telephone,
						    yp.email,
						    yp.enabled,
						    u.user_name,
						    u.id AS user_id_ 
						FROM
						    stores yp,
						    users u
						WHERE
						     u.id = yp.user_id
						ORDER BY yp.id DESC) temp
						LIMIT $start,$display;";
						
			$ret = $database->query($sql);

			$data = "<tr><td colspan='8' class='text-center'><i>There is no data to display.</i></td></tr>";
			$datatable = "";
			
			// spitting out data following field names
			if (!$ret || !$ret->num_rows)
			{}
			else
			{
				$idx = $start;
				$status = "";
				$fa_link = font_awesome('fa-external-link');
				$data = "";
				$maxrows = $ret->num_rows;
				
				while ($row = $ret->fetch_array())
				{
					$idx++;	
					$companyid     = $row['id'];			
					$entrydate     = $row['entrydate'];					
					$timeago       = "<small><abbr class='timeago' title='$entrydate'>$entrydate</abbr></small>";
					$name          = $row['name'];
					$logo          = $row['logo'];
					$email         = $row['email'];
					$telephone     = $row['telephone'];
					$enabled       = $row['enabled'];
					$approved      = $row['enabled'] ? "<span style='color:green'>Approved</span>" : "<span style='color:red'>Not Approved</span>";
					$addedby       = $row['user_name'];
					$userid_       = $row['user_id_'];
					
					$option_approve   = $enabled ? 
					"<a href='#' onclick=\"toggle_field('stores','enabled',$companyid, 0)\" class='btn btn-success' data-toggle='tooltip' title='Reject'>$fa_check Approved</a>" :
					"<a href='#' onclick=\"toggle_field('stores','enabled',$companyid, 1)\" class='btn btn-warning' data-toggle='tooltip' title='Approve'>$fa_remove Approved</a>";

					$data .= "<tr>
								   <td>{$idx}</td>
								   <td>$timeago</td>
								   <td>
								   <a href='?view=manage-stores&action=edit&id=$companyid'>$name <BR><img src='$logo' class='img-responsive'> </a></td>
								   <td>$email</td>
								   <td>$telephone</td>
								   <td>$approved</td>		
								   <td>$fa_user $addedby</td>
								   <td>$option_approve</td>
								 </tr>"; 
				}			
				$datatable = "<script>
				               $(document).ready(function(){
				               	$('#tableData').dataTable();
				               });
				              </script>";
			}
			
			$fa_add = font_awesome('fa-plus');
					     			
			echo "<h4>$fa_users Approve stores <small class='pull-right'>Approve or unapprove stores on the system</small></h4>
				  <hr>";

$datatable = "";

			$columns = "<tr>
			             <th>$</th>
			             <th>Entrydate</th>
			             <th>Company</th>
			             <th>Email</th>
			             <th>Telephone</th>
			             <th>Status</th>
			             <th>Added by</th>
			             <th>Actions</th>
						</tr>";
							
			echo "<div class='table-responsive'>
					 <table class='table table-hover table-bordered' id='tableData'>
					  <thead>
					   $columns
					  </thead>
					  <tbody>
					   $data
					  </thead>
					 </table>
					</div>
					$datatable
					";
					
			echo $pg->process();
			break;
	}
?>
