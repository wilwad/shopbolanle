<?php
    /*
	 * Queries manager
	 *
	 * This file manages queries
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
	
	// parameters
	$view   = @ $_GET['view'];
	$action = @ $_GET['action'];
	$id     = (int) @ $_GET['id'];
	
	// This view title
	$fa_icon = font_awesome('fa fa-fw fa-edit');

		   	
	switch ($action){
		case 'edit':
		
			// editing a query
			
			echo "<h4>$fa_icon Edit Query<small></small></h4>
		   <hr>";
		   			
			$sql_ = "SELECT 
								*
					  FROM 
					  			system_queries q
					  WHERE 
					  			q.id=$id;";
					  			
			$ret = $database->query($sql_);
			if (!$ret || !$ret->num_rows)
				die("<p class='alert alert-warning'>The query specified does not exist. ($sql_)</p>");
				
			$row = $ret->fetch_array();
			
			// required fields
			$fields = array('title','description','_sql', 'enabled');
			
			$errors = "";
			$document = "";	
			
			// if extra is set, it means commit a change
			// before displaying the values of this document
			if (isset($_POST['extra']))
			{
				// if we are saving, the new info is in POST array
				$title     		 = @ $_POST['title'];
				$description    = @ $_POST['description'];
				$sql            = @ $_POST['_sql'];
				$enabled        = @ $_POST['enabled'] == 'on' || @ $_POST['enabled'] == '1'  ;
				
				// edit!				
				$flag_fields_set = true;
				foreach($fields as $field){
						$_POST[$field] = trim(@ $_POST[$field]);
						if (!strlen($_POST[$field]))
							break;
				}
				
				if (!$flag_fields_set){
					$errors = "<p class='alert alert-danger'>Some fields have not been filled in.</p>";
				}

				// all filled in
				if (!$errors)
				{
					$title       = htmlentities(@ $_POST['title']);
					$description = htmlentities(@ $_POST['description']);
					$sql         = htmlentities(@ $_POST['_sql']);
					$enabled     = (int) @ $_POST['enabled'] == 'on' ? 1 : 0;
					
					// make sure we don't have a query with this same title
					$sql_ = "SELECT 
										* 
							  FROM 
							  			system_queries
							  WHERE 
							  			title = '$title' AND
								      id <> $id;";

					$ret = $database->query($sql_);
					if (!$ret || !$ret->num_rows)
					{				
							$sql_ = "UPDATE 
												system_queries
									  SET 
									  			title='$title',
												description='$description',
												_sql=\"$sql\",
												enabled=$enabled
									  WHERE 
									  			id=$id;";
				
						$ret = $database->query($sql_);
						if (!$ret){
							$error = $database->error;							
							$errors = "<p class='alert alert-danger'>Error: $error</p>";
							
							// write the system log
							$action = "QUERY_EDIT_ERROR";
							$description = "Failed to update query changes. Error: $error. Query id: $id. Userid: $userid.";

							update_system_log($action, $description);	
						}		
						else
						{
							// write the system log
							$action = "Query edit";
							$description = "Query has been edited. Query id: $id. Userid: $userid.";
							$fa_check = font_awesome('fa-check');
							$errors = "<p class='alert alert-success'>$fa_check Changes were successfully saved.</p>";
							update_system_log($action, $description);	
						}											
					}
					else
						$errors = "<p class='alert alert-danger'>A query already exists with the name title.</p>";
				}			
			}
			else
			{
				// this page was loaded for viewing
				// we need to init our POST array with existing
				// values 
				$title             = $row['title'];
				$_POST['title']    = $title;
				
				$description          = $row['description'];
				$_POST['description'] = $description;
				
				$sql           = $row['_sql'];
				$_POST['_sql']  = $sql;		
				
				$enabled           = $row['enabled'] == 1 ? 'on' : '';
				$_POST['enabled']  = $enabled;
			}			

			$required = "<span style='color:red'>*</span>";
			
			// dynamically build the form using the
			$sql = "SHOW 
								columns 
					  FROM 
					  			system_queries";
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows)
			{
				echo "<p class='alert alert-danger'>*Failed to retrieve columns names to build the UI.</p>";
			}
			else
			{
				while ($row = $ret->fetch_array())
				{
					$input = "";
					$field = $row[0];
					
					// we ignore some fields
					switch (strtolower($field)){
						case 'entrydate':
						case 'user_id':
						case 'enddate':
						case 'omas_id':
						case 'type_id':
						case 'id':
							break;

					   case '_sql':
					   	$val = @ $_POST[$field];
							$input = "<textarea class='form-control' name='$field'>$val</textarea>";
							$field = ucfirst($field);
							$document .= "<div class='form-group'>
											   <label class='control-label col-sm-2' for='$field'>$field $required</label>
											   <div class='col-sm-8'>$input</div>
										     </div>";						   
					   	break;
					   	
						case 'enabled':
							$checked = @ $_POST['enabled'] ? 'checked' : '';
							$input = "<input type='checkbox' class='' $checked name='$field'>";
							$field = ucfirst($field);
							$document .= "<div class='form-group'>
											   <label class='control-label col-sm-2' for='$field'>$field $required</label>
											   <div class='col-sm-8'>$input</div>
										     </div>";						
								break;
								
						default:
							$val = @ $_POST[$field];
							$tagsinput = "";
							switch ($field){
								case 'category':
								case 'keywords':
								case 'parties':
								case 'lawyer_assigned':
										$tagsinput = "data-role='tagsinput'";
										break;
							} 
							
							$input = "<input type='text' class='form-control' $tagsinput value='$val' name='$field'>";
							$field = ucfirst($field);
							$document .= "<div class='form-group'>
											<label class='control-label col-sm-2' for='$field'>$field $required</label>
											<div class='col-sm-8'>$input</div>
										</div>";
							break;
					}
				}
			}	

		$fa_floppy = font_awesome('fa-floppy-o');
		$fa_edit   = font_awesome('fa-edit');
		$fa_remove = font_awesome('fa-remove');

		$btn_save = "<div class='form-group'>
						<label class='control-label col-sm-2' for=''></label>
						<div class='col-sm-8'>
							<button class='btn btn-sm btn-success'>$fa_floppy Update changes</button> 
						<a href='?view=queries' class='btn btn-sm btn-warning'>$fa_remove Cancel</a>
						</div>
					  </div>
					  <p>&nbsp;</p>";
					  
			echo "<div class='row'>
					 <div class='col-md-8' style='border-right:1px solid #cacaca'>
						<form class='form-horizontal' role='form' method='POST'  enctype='multipart/form-data'>
						<!-- required params start -->
						<input type='hidden' name='view' value='$view'>
						<input type='hidden' name='action' value='edit'>
						<input type='hidden' name='id' value='$id'>	
						<input type='hidden' name='extra' value='1'>
						<!-- required params end -->
						
						$errors
						$document					
						$btn_save
	
						</form>
					 </div>
					 <div class='col-md-4'>
					  <h5><strong>Edit this query</strong></h5>
					  Specify the requested fields in order to edit the query.
					 </div>
					</div> <!-- row -->					
					";		
			break;
			
		default:
		
	$scripts = "	
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
									  
					function query_new(userid){						 
						  var dialogcss = \"<div class='table-responsive'><table class='table table-no-border'>\" +
											 \"<tbody><tr><th>Title <span class='required'>*</span></th><td><input type='text' value='' class='form-control' placeholder='Title' name='title' id='title'></td></tr>\" +
											  \"<tr><th>Description <span class='required'>*</span></th><td><input type='text' value='' class='form-control' placeholder='Description' id='description' name='description'></td></tr>\" +
											  \"<tr><th>SQL <span class='required'>*</span></th><td><textarea class='form-control' placeholder='SELECT * FROM...' id='sql' name='sql'></textarea><small><h5>Not allowed: INSERT, DELETE, UPDATE</small></h5></td></tr>\" +
											 \"</tbody></table></div><style>\";
						  
							BootstrapDialog.show({
								title: 'Add a new query',
								message: function(dialog) {
									var content = $(dialogcss);
									return content;
								},
								buttons: [
									{
									label: 'Add query',
									action: function(){
										var controls = ['#title','#description','#sql'];
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

										var payload = {url: 'api/api.php',
										               userid: userid,
													   view: 'query-new',
													   title: $('#title').val(),
													   description: $('#description').val(),
													   sql: $('#sql').val()
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
					
					var rowid = '';
					var sqlid = '';
					
					$('#dlgEditQuery').on('shown.bs.modal', function() {
						
						var title = $(rowid).find('td').eq(2).text();
						var description = $(rowid).find('td').eq(3).text();
						var sql = $(rowid).find('td').eq(4).text();
						
						$('#title-2').val( title );
						$('#description-2').val( description );
						$('#sql-2').val( sql );
						
						$('#sql-2').each(function(i, block) {
							hljs.highlightBlock(block);
						});
					});	
					
					function query_run(id) {
						var options = {url: 'api/api.php',
									   view: 'query-run',
									   id: id};
						
						console.log(options);
						
						$.ajax({type: 'POST',
								url: options.url,
								data: options,
								error: function(xhr, ajaxOptions, thrownError){
									console.log(thrownError);
								},
								success: function(data){
									console.log(data);
									$('#query-result-container').html(data);														
								}});	

						/* we are running the query */
						$('#query-result-container').css('background','#FFFFFF');
						$('#query-result-container').html(\"Requesting request. Please wait. <img src='progbar.gif'>\");
					}
					
					function query_edit() {
						var title = $('#title-2');
						var description = $('#description-2');
						var sql = $('#sql-2');
						
						title.val(title.val().trim());
						sql.val(sql.val().trim());
						description.val(description.val().trim());
						
						if (!title.val().length)
						{
							title.focus();
							return false;
						}													
						if (!description.val().length)
						{
							description.focus();
							return false;
						}													
						if (!sql.val().length)
						{
							sql.focus();
							return false;
						}	
						
						var options = {url: 'api/api.php',
									   id: parseInt(sqlid),
									   view: 'query-edit',
									   title: title.val(),
									   description: description.val(),
									   sql: sql.val()};
						
						console.log(options);
						
						$.ajax({type: 'POST',
								url: options.url,
								data: options,
								error: function(xhr, ajaxOptions, thrownError){
									console.log(thrownError);
								},
								success: function(data){
									if (data == 'true')
										window.location.reload();
									else
										alertify.error(data);
								}});													
					}
					
					function query_run(id) {
						var options = {url: 'api/api.php',
									   view: 'query-run',
									   id: id};
						
						console.log(options);
						
						$.ajax({type: 'POST',
								url: options.url,
								data: options,
								error: function(xhr, ajaxOptions, thrownError){
									console.log(thrownError);
								},
								success: function(data){
									$('#query-result-container').html(data);														
								}});	

						/* we are running the query */
						$('#query-result-container').css('background','#FFFFFF');
						$('#query-result-container').html(\"Please wait. <img src='progbar.gif'>\");
					}												
					
					function query_delete(id) {													
						var options = {url: 'api/api.php',
									   view: 'query-delete',
									   id: id};
						
						console.log(options);
						
						$.ajax({type: 'POST',
								url: options.url,
								data: options,
								error: function(xhr, ajaxOptions, thrownError){
									console.log(thrownError);
								},
								success: function(data){
									if (data == 'true')
										window.location.reload();
									else
										alertify.error(data);
								}});													
					}
				</script>   ";
								
	$fa_user = font_awesome('fa-user');
	$btn_new_query = "<button onclick='query_new($userid)' class='btn btn-sm btn-primary pull-right'><span class='glyphicon glyphicon-plus-sign'></span> Add a new query</button>";
	$reports       = "";
	$tbody         = "<tr><td colspan='7' style='text-align:center'><small>No queries have been defined.</small></td></tr>";
	
	$sql_ = "SELECT DISTINCT 
						*, q.title As _title, 
						q.entrydate as sql_entrydate,
						q.id AS qid 
	        FROM 
	        			system_queries q, 
	        			users u 
			  WHERE 
			  			q.user_id = u.id 
			  ORDER BY q.id DESC;";
			  
	$rec = $database->query($sql_);
	if (!$rec || !$rec->num_rows)
	{}
	else 
	{
		$i = 0;
		$tbody = "";
		while ($row = $rec->fetch_array())
		{
			$i++;
			$id = $row['qid'];
			$title = $row['_title'];
			$description = $row['description'];
			$sql = $row['_sql'];
			$author = $row['user_name'];
			$entrydate = $row['sql_entrydate'];
			$entrydate = "<abbr class='timeago' title='$entrydate'>$entrydate</abrr>";
			$enabled  = $row['enabled'];
			$visibility = $enabled == 1 ? font_awesome('fa-eye') : font_awesome('fa-eye-slash');
			
			$options = "<a href='#' data-toggle='tooltip' data-placement='right' title='Run sql' onclick='query_run($id); return false;'><span class='glyphicon glyphicon-play'></span></a>
						<a href='?view=queries&action=edit&id=$id' data-toggle='tooltip' data-placement='right' title='Edit sql'><span class='fa fa-fw fa-edit'></span></a>"
					. " <a href='#' data-toggle='tooltip' data-placement='right' title='Delete sql' onclick=\"BootstrapDialog.confirm('Are you sure you would like to delete this query?',function(ans){if(ans)query_delete($id);}); return false;\"><span style'color:red' class='glyphicon glyphicon-trash'></span></a>
					
					$visibility";
					
			$tbody .= "<tr id='tr$i'>"
					 . " <td>$i</td>"
					 . " <td>$entrydate</td>"
					 . " <td>$title</td>"
					 . " <td>$description</td>"
					 . " <td class='sql'>$sql</td>"
					 . " <td>$fa_user $author</td>"
					 . " <td>$options</td>"
					 . "</tr>";
		}
		
		if ($i)
			$scripts .= "<script>
								 $(document).ready(function(){
									$('#table-queries').dataTable(); 
								});
								</script>";
	}
	
	$reports = "<h5 style=''>Results <small class='pull-right'><a href='#' onclick=\"printDiv('query-result-container');return false;\"><span class='fa fa-fw fa-print'></span> Print list</a> <a href='#' onclick=\"clearDiv('query-result-container');return false;\"><span class='fa fa-fw fa-remove'></span> Clear</a></small></h5>
			   <div id='query-result-container'  style=''>
				<p class='alert alert-info' style='padding:10px;'>
				<span class='fa fa-fw fa-info-circle'></span>
				<small>Ready.</small></p>
				</div>
				<hr>
				<small>Add a field in query named drawchart to draw a chart</small> $btn_new_query
				
				<p>&nbsp;</p>
				<div class='table-responsive' style='a'>"
			. "             <table id='table-queries' class='table table-hover '>"
			. "              <thead>"
			. "                 <tr><th>#</th>
									<th>Date Stamp</th>
									<th>Title</th>
									<th>Description</th>
									<th style='width:30%'>SQL</th>
									<th>Author</th>
									<th>Options</th></tr>"
			. "              </thead>"
			. "              <tbody>"
			. "                 $tbody"
			. "              </tbody>"
			. "             </table>"
			. "            </div>
			$scripts
			";
	   $fa_icon = font_awesome('fa-filter');
       echo "<div class=''>			 
	         <h4>$fa_icon SQL Query Management <small class='pull-right'>Create database queries that can be run by other users.</small></h4>
			 <hr>
				$reports
			</div>
			
			<script>
			 $(document).ready(function(){
				$('.sql').each(function(i, block) {
				  hljs.highlightBlock(block);
				});							 
			 });
			</script>";	
				break;
	}
?>
