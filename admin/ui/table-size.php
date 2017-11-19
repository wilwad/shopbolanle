<?php
   /*
    * view table structures and manage the data
    */
    
   $fa_db    = font_awesome('fa-database');
	$fa_table = font_awesome('fa-table');
	$fa_clear = font_awesome('fa-remove');
	
    echo "<h4>$fa_table Table sizes <small>display the sizes taken up by the tables (in MB)</small> <span class='pull-right no-print'><small><a href='#' onclick=\"printDiv('printable'); return false;\"><span class=\"fa fa-fw fa-print\"></span> Print this document</a></span></small></h4><hr>";
	
	$db = settings::db_db;
	
	// get parameters
	$action = @ $_GET['action'];
	$table  = @ $_GET['table'];
	
	switch ($action)
	{
		case 'edit':
			// editing a field item
			$table = @ $_GET['table'];
			$id    = (int) @ $_GET['id'];
			$url   = "?view=table-size&action=describe&table=$table";
			
	 		// required fields
	 		// everything except id, user_id, entrydate, user_id_lock
			$fields = array();
	 		
	 		$sql = "SHOW columns 
	 		        FROM `$table`;";
	 		$ret = $database->query($sql);
	 		if (!$ret || !$ret->num_rows)
	 		{}
	 		else {
	 			while ($row = $ret->fetch_array())
	 				$fields[] = $row[0];
	 		}
								 
			$sql = "SELECT 
								*
					  FROM 
					  			`$table`
					  WHERE 
					  			id=$id 
					  LIMIT 1;";
					  
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows)
				die("<p class='alert alert-warning'>The item specified does not exist. $sql</p>");
				
			$row = $ret->fetch_array();

			$errors = "";
			$document = "";	
	
			// if extra is set, it means commit a change
			// before displaying the values of this document
			if (isset($_POST['extra']))
			{
				// edit!				
				$flag_fields_set = true;
				foreach($fields as $field){
						$_POST[$field] = trim(@ $_POST[$field]);
						
						if ($field == 'isactive'){
						}
						else
						{
						if (!strlen($_POST[$field])){
							$errors = "<p class='alert alert-danger'>Some fields have not been filled in: <b>$field</b>.</p>";
							break;
						}
					}
				}

				// all filled in?
				if (!$errors)
				{
					// make sure we don't have similar item
					$post_name     = $_POST['name'];
					$post_isactive = $_POST['isactive'];

					$sql = "SELECT 
										* 
							  FROM 
							  		   `$table`
							  WHERE 
 										name = `$post_name` AND 
 										id <> $id;";
								     	
					$ret = $database->query($sql);
					if (!$ret || !$ret->num_rows)
					{
						// at this point isactive == on
						$post_isactive = $_POST['isactive'] == 'on' ? 1 : 0;
						
						$sql = "UPDATE 
											`$table`
								  SET 
								  		name ='$post_name',
								  		isactive=$post_isactive
									WHERE 
										id=$id;";

					   $ret = $database->query($sql);
						if (!$ret){
							$error = $database->error;							
							$errors = "<p class='alert alert-danger'>Error: $error</p>";
							
							// write the system log

							echo "<p class='alert alert-danger'>{$database->error}</p>";
						}		
						else
						{
							// write the system log
							$fa_check = font_awesome('fa-check');
							echo "<p class='alert alert-success'>$fa_check Successfully updated</p>";
						}											
					}
					else
						echo "<p class='alert alert-danger'>Cannot proceed with your request. Check for existing values.</p>";
				}			
			}
			else
			{
				// this page was loaded for viewing
				// we need to init our POST array with existing
				// values 

				foreach($fields as $field){
						$val = $row[$field];					
						$_POST[$field] = $row[$field];
				}			
			}		
				
			$required = "<span style='color:red'>*</span>";
			
         // Todo: dynamic load columns
			$sql = "SHOW 
								columns 
					  FROM 
					  				`$table`";
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
						case 'type_id':
						case 'id':
						case 'application_id':
						case 'user_id_lock':
						case 'parent_type':
							break;

						case 'isactive':
							 $val = @ $_POST[$field];
							 $checked = ($val == 1 || $val == 'on') ? 'checked' : '';

							$input = "<input type='checkbox' class='' $checked name='$field'>";
							$field = ucfirst($field);
							$document .= "<div class='form-group'>
											<label class='control-label col-sm-2' for='$field'>$field $required</label>
											<div class='col-sm-8'>$input</div>
										</div>";
									break;

						default:
							$val = @ $_POST[$field];

							$input = "<input type='text' class='form-control' value='$val' name='$field'>";
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

		$application_id = @ $_GET['application-id'];
		
		$btn_save = "<div class='form-group'>
						<label class='control-label col-sm-2' for=''></label>
						<div class='col-sm-8'>
							<button class='btn btn-sm btn-success'>$fa_floppy Save item</button> 
						<a href='$url' class='btn btn-sm btn-danger'>$fa_remove Cancel</a>
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
					$document					
					$btn_save";				
				break;
			
		case 'describe':
			echo "Table structure: <b>$table</b> <a href='?view=$view&action='><span class='label label-success'>$fa_clear Reset filter</span></a><hr>";
			
			$sql = "DESCRIBE $table;";
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows)
			{
				echo "<span class='label label-danger'>No data returned</span>";
			}
			else
			{
				// get the field names
				$arr_fields = [];
				$fields = "";
				/* Get field information for all columns */
				$finfo = $ret->fetch_fields();

				foreach ($finfo as $val) {
					$arr_fields[] = $val->name;
					$fields .= "<th>{$val->name}</th>";
					/*
					printf("Table:    %s\n", $val->table);
					printf("max. Len: %d\n", $val->max_length);
					printf("Flags:    %d\n", $val->flags);
					printf("Type:     %d\n\n", $val->type);
					*/
				}
		
				echo "<div class='table-responsive' id='printable'>";
				echo " <table class='table table-bordered table-hover table-condensed'>";
				echo "  <thead>
						 <tr><th>No.</th>$fields</tr>
						</thead>
						<tbody>";
				
				$i = 1;
				
				while ($row = $ret->fetch_array())
				{
					
					$data = "";
					
					foreach($arr_fields as $fld){
						$data .= "<td>{$row[$fld]}</td>";
					}
					
					echo "<tr><td>$i</td>$data</tr>"; 
					$i++;	
				}
				
				echo "  </tbody>";
				echo " </table>";
				echo "</div>";
				
				echo "<h5>Data stored in $table</h5><hr>";
				
				// now get the data
				$arr_fields = [];
				$fields = "";
				$data = "";
				$maxcolumns = 0;
				$maxrows = 0;	
				$thead = "";
				$tbody = "";
				$fa_edit = font_awesome('fa-edit');
		
				$sql = "SELECT * 
					    FROM `$table` 
						ORDER BY id ASC;";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{}
				else {
					while ($fld=mysqli_fetch_field($ret))
					{
						//printf("Name: %s\n",$fieldinfo->name);
						//printf("Table: %s\n",$fieldinfo->table);
						//printf("max. Len: %d\n",$fieldinfo->max_length);
						$fields .= "<th>{$fld->name}</th>";
	
						// store fieldnames in array so we follow the field order when we spit out data
						$arr_fields[] = $fld->name;
						$maxcolumns++;
					}
				}

				// spitting out data following field names
				if (!$ret || !$ret->num_rows)
				{
					$data = "<tr><td colspan='$maxcolumns' class='text-center'>There is no data to display.</td></tr>";
				}
				else
				{
					$maxrows = $ret->num_rows;

					while ($res = $ret->fetch_array())
					{
						$data .= "<tr>"; 

						foreach ($arr_fields as $fld) 
						{
							switch (strtolower($fld))
							{
								case 'isactive':
								   $val = (int) $res[$fld];
								   $checked = ($val === 1) ? 'checked' : '';
									$data .= "<td><input type='checkbox' disabled $checked></td>";
									break;
									
								case 'status':
									switch (strtolower($res[$fld]))
									{
										case 'pending':
											$data .= "<td><span class='label label-default'>{$res[$fld]}</span></td>"; 
											break;

										case 'archived':
											$data .= "<td><span class='label label-warning'>{$res[$fld]}</span></td>"; 
											break;

										case 'recommended':
											$data .= "<td><span class='label label-primary'>{$res[$fld]}</span></td>"; 
											break;

										case 'approved':
											$data .= "<td><span class='label label-success'>{$res[$fld]}</span></td>"; 
											break;

										case 'rejected':
											$data .= "<td><span class='label label-danger'>{$res[$fld]}</span></td>"; 
											break;									

									}
									break;

								default:
									$data .= "<td>{$res[$fld]}</td>"; 
									break;
							}
						}
						
					  $fa_trash = font_awesome('fa-remove');
				     $recid  = $res['id'];
		           $edit   = "<a href='?view=table-size&action=edit&id=$recid&table=$table'>$fa_edit Edit</a>";
			        $delete = "<a href='#' onclick=\"confirmdelete('$table',$recid); return false;\" title='Delete this item' data-toggle='tooltip'>$fa_trash Delete</a>";					
					
					  $data .= "<td>$edit $delete</td></tr>"; 
					}			
				}				
				echo "<div class='table-responsive'>
					   <table id='table' class='table table-bordered table-condensed table-hover'>
						<thead><tr>$fields <th>Actions</th></tr></thead>
						<tbody>$data</tbody>
					   </table>
					  </div>
					  <script>
					   $(document).ready(function(){
						$('#table').dataTable();
						
						 $('#table tbody tr').on('click', function(event){
							 alert('clicked');
							console.log($('#table));
						 });							
					   });
					  </script>";				
			}			
			break;
			
		default:
				$datatable = "";
				$sql = "SELECT table_name AS `Tables`, 
						round(((data_length + index_length) / 1024 / 1024), 2) 'Size in MB'
						FROM information_schema.TABLES 
						WHERE table_schema = '$db'
						ORDER BY (data_length + index_length) DESC;";
				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
				{
					echo "<span class='label label-danger'>No data returned</span>";
				}
				else
				{
					$datatable = "$('table').dataTable();";

					echo "<div class='table-responsive' id='printable'>";
					echo " <table class='table table-striped'>";
					echo "  <thead>
							 <tr><th>No.</th><th>Table</th><th>Size</th></tr>
							</thead>
							<tbody>";
					
					$i = 1;
					
					while ($row = $ret->fetch_array())
					{
						$table = $row[0];
						$size  = $row[1];
						$fa_icon = font_awesome('fa-table');
						
						echo "<tr>
							   <td>$i</td><td><a href='?view=$view&action=describe&table=$table'>$fa_icon $table</a></td><td>$size MB</td>
							  </tr>"; 
							  
						$i++;
					}
					
					echo "  </tbody>";
					echo " </table>";
					echo "</div>";
					echo "<script>
					      $(document).ready(function(){
                                               $datatable  
                                              }); 
                                              </script>";
				}		
			break;
	}
?>
