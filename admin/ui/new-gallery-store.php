<div>
<?php
    /*
	 *
	 * Author: William Sengdara
	 * Created: 
	 * Modified: April 27, 2016
	 */

	if (!@$users)
		die("FATAL ERROR: this file may not be launched outside the system. It can only be included.");

	// check that we are logged in!
	$user = $users->loggedin();
	if (!$user) {
		echo view( 'dialog-login' );
		exit;
	}

	$view = @ $_GET['view'];
   $right_exists = verify_right($view);		
	if (!$right_exists)
		die(alertbuilder('You do not have the right to request that view.','danger'));
	
	//====================== custom code starts below 
	// when set, means we are trying to update data
	$extra   = (int) @ $_POST['extra']; 
	
	// our icons -> too lazy to have these in database.php for sharing
	$fa_icon   = font_awesome('fa-file');
	$fa_plus   = font_awesome('fa-plus');
	$icon      = font_awesome('fa-file'); 
	$fa_icon   = font_awesome('fa-user');
	$fa_floppy = font_awesome('fa-floppy-o');
	$fa_plus   = font_awesome('fa-plus');
	$fa_bank   = font_awesome('fa-bank');
	$fa_edit   = font_awesome('fa-edit');
	$fa_trash  = font_awesome('fa-trash');
	$fa_add    = font_awesome('fa-plus');
	$fa_view   = font_awesome('fa-eye');	
	$fa_user   = font_awesome('fa-user');
	$fa_file   = font_awesome('fa-file');
	$fa_refresh= font_awesome('fa-refresh');
	$fa_lock   = font_awesome('fa-lock');
	$fa_unlock = font_awesome('fa-unlock');
	
	$required = " <span style='color:red'>*</span> ";
	$star_required = "<span style='color:red'>*</span>";
		
	// This view title
	$cat = "gallery";
	if (@ $_POST['store_item_category_id'] == ''){
	    $sql = "SELECT id FROM store_item_categories WHERE name='$cat';";
	    $ret = $database->query($sql);
	    if (!$ret || !$ret->num_rows){
	        // nothing to do
	    } else {
	        $_POST['store_item_category_id'] = $ret->fetch_array()['id'];
	    }
	}
	echo "<h4>$fa_file Create a $cat item
	     <small class='pull-right' id='hint'>Fields with a <span style='color:red'>*</span> are required. 
	     Where a field is not applicable, write <b>N/A</b></small></h4>
		   <hr>";
					
			$errors   = "";
			$inputs = "";	
			
			$fa_upload = font_awesome('fa-upload');		
						
	 		// required fields
			$required = array('store_item_category_id',
			                  'title',
			                  'description',
			                  'url_image_1',
			                  /*'floor',*/
			                  /*'store_no',*/
			                  /*'website',
			                  'color_background',
			                  'banner',
			                  'logo',
			                  'enabled'*/
			                  );
			
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
			
			// dynamically build form
			$table = "store_items";
			$inputs = build_form($table, $required);	

		$fa_floppy = font_awesome('fa-floppy-o');
		$fa_edit   = font_awesome('fa-edit');
		$fa_remove = font_awesome('fa-remove');

		$btn_save = "<div class='form-group'>
							<label class='control-label col-sm-3' for=''></label>
							<div class='col-sm-8'>
								<button class='btn btn-sm btn-success' onclick=\"return upload_document();\">$fa_floppy Save</button> 
							   <a href='?view=home' class='btn btn-sm btn-danger'>$fa_remove Close</a>
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
			
			echo "<div class='col-md-9 border-right'>
			      <form class='form-horizontal' role='form' method='POST'  enctype='multipart/form-data'>
					<!-- required params start -->
						<input type='hidden' name='view'     value='generic-add'>
						<input type='hidden' name='table'     value='store_items'>		
						<input type='hidden' name='returnurl'     value='?view=manage-{$cat}s-store'>						
						<input type='hidden' name='user_id'   value='$userid'>
						<input type='hidden' name='extra' value='1'>
					<!-- required params end -->
						
						$errors
						$inputs					
						$btn_save					
					</form>
					</div>
					<div class='col-md-9'>
					</div>
										
					<!-- validate inputs -->
					$scripts";
 ?>
 </div>
