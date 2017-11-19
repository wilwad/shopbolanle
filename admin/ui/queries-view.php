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
	$view = @ $_GET['view'];

	// verify that this user has the right to manage this right
	$right_exists = verify_right($view);
	
	if (!$right_exists)
		die("<div class='well bg-white'>
			   <div class='alert alert-danger'>
			    <li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;You do not have the right to request that view.
			   </div>
			 </div>");
			 
	
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
											  \"<tr><th>SQL <span class='required'>*</span></th><td><textarea class='form-control' placeholder='SELECT * FROM...' id='sql' name='sql'></textarea></td></tr>\" +										  
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
								
	$fa_info = font_awesome('fa-info-circle');
	$btn_new_query = "<button onclick='query_new($userid)' class='btn btn-sm btn-primary'><span class='glyphicon glyphicon-plus-sign'></span> Add a new query</button>";
	$reports = "";
	$tbody = "<tr><td colspan='7' style='text-align:center'><small>$fa_info There are no queries to show. Please contact administration to add new queries.</small></td></tr>";
	
	$sql = "SELECT DISTINCT 
									*, 
									sq.title As _title, 
									sq.id AS qid 
	        FROM 
	        						system_queries sq, 
	        						users u 
			  WHERE 
			  						sq.user_id = u.id AND 
			  						sq.enabled=1
			  ORDER BY 
			  						sq.id DESC;";
			  						
	$rec = $database->query($sql);
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
			$entrydate = $row['entrydate'];
			$entrydate = "<abbr class='timeago' title='$entrydate'>$entrydate</abrr>";
			
			$options = "<a href='#' data-toggle='tooltip' data-placement='right' title='Run sql' onClick='query_run($id); return false;'><span class='glyphicon glyphicon-play'></span></a>";
			$tbody .= "<tr id='tr$i'>"
					. " <td>$i</td>"
					. " <td>$entrydate</td>"
					. " <td>$title</td>"
					. " <td>$description</td>"
					. " <td class='sql'>$sql</td>"
					. " <td>$author</td>"
					. " <td>$options</td>"
					. "</tr>";
		}
	}

	$reports = "<h5 style='text-decoration:underline'>Results <small></small></h5>
			   <div id='query-result-container'  style=''>
				<p class='alert alert-info' style='padding:10px;'>
				<span class='fa fa-fw fa-info-circle'></span>
				<small>Ready.</small></p>
				</div>
				<hr>
				<h5><small style='text-decoration:none !important'>Execute the saved queries below against the database.</small></h5>
				<div class='table-responsive' style='a'>"
			. "             <table id='table-queries' class='table table-hover table-bordered'>"
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
			<script>
                         $(document).ready(function(){ 
                                $('#table-queries').dataTable(); 
                        });
			</script>
			";

	   $fa_icon = font_awesome('fa-filter');
       echo "<h5>$fa_icon SQL Query Viewer <small>Execute queries against the database.</small></h5>
			 <hr>
				$reports

			<script>
			 $(document).ready(function(){
				$('.sql').each(function(i, block) {
				  hljs.highlightBlock(block);
				});							 
			 });
			</script>";	
?>
