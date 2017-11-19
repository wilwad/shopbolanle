<?php
	if (!@$users)
		die("FATAL ERROR: this file may not be launched outside the system. It can only be included.");

	$userid = $user['userid'];
	$view   = @ $_GET['view'];
	$term   = @ $_GET['term'];
	$extra  = @ $_GET['extra'];
	$region_id = (int) @ $_GET['region'];
	
	// verify that this user has the right to manage this right
	$right_exists = verify_right($view);
	
	if (!$right_exists)
		die("<div class='well bg-white'>
			   <div class='alert alert-danger'>
			    <li class='fa fa-fw fa-exclamation-circle'></li>&nbsp;You do not have the right to request that view.
			   </div>
			 </div>");
	
	$fa_icon  = font_awesome('fa-search');
	$fa_edit  = font_awesome('fa-edit');
	$fa_trash = font_awesome('fa-trash');
	$fa_user  = font_awesome('fa-user');
	$fa_qr_code = font_awesome('fa-qrcode fa-2x');
	$fa_search = font_awesome('fa-search');
	
	$datatable = "";
	
	$fields = "<th>#</th>
	           <th>Entry date</th>
	           <th>Youth ID</th>
	           <th>Full Name</th>
	           <th>Age</th>
	           <th>Sex</th>
	           <th>Region</th>
	           <th>Added by</th>
	           <th>Actions</th>";
		           
	$results = "<tr><td colspan='9' class='text-center'><i>There is nothing to show.</i></td></tr>";

	if ($extra && $term <> "" ){
		
		$start   = 0;
		$display =100;
		$extra   = "";
		
		if ($region_id > 0)
			$extra = "AND lr.id=$region_id";
			
		$sql = "SELECT 
						    yp.youth_id,
						    yp.entrydate,
						    TIMESTAMPDIFF(YEAR,yp.birth_date,CURDATE()) AS age,
						    CONCAT(yp.name_first,
						            ' ',
						            yp.name_others,
						            ' ',
						            yp.name_surname) AS full_name,
						    yp.birth_date,
						    cnationality.name AS nationality,
						    ccitizenship.name AS citizenship,
						    ccountry_birth.name AS birth_country,
						    ls.name AS sex,
						    u.user_name,
						    u.id AS user_id_,
						    lr.name as region_name,
						    lr.id as region_id
						FROM
						    youth_profile yp,
						    list_countries cnationality,
						    list_countries ccitizenship,
						    list_countries ccountry_birth,
						    list_sex ls,
						    list_regions lr,
						    users u
						WHERE
						    ls.id = yp.sex_id
						        AND cnationality.id = yp.nationality_id
						        AND ccitizenship.id = yp.citizenship_id
						        AND ccountry_birth.id = yp.birth_country_id
						        AND u.id = yp.user_id 
						        AND lr.id = yp.region_id
						        AND (yp.youth_id LIKE '$term'
						        OR yp.name_first LIKE '%$term%'
						        OR yp.name_others LIKE '%$term%'
						        OR yp.name_surname LIKE '%$term%')
						        $extra
						ORDER BY entrydate DESC
						LIMIT $start , $display;";		

		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
		{}
		else
		{
			$idx  = 0;
			$results = "";
			
			while ($row = $ret->fetch_array())
			{
				$idx++;
				$youthid   = $row['youth_id'];
				$entrydate = $row['entrydate'];
				$fullname  = $row['full_name'];
				$entrydate = "<abbr class='timeago' title='$entrydate'>$entrydate</abbr>";
				$region    = $row['region_name'];
				$regionid  = $row['region_id'];
				$age       = $row['age'];
				$sex       = $row['sex'];
				$user_id_  = $row['user_id_'];
				$username  = $row['user_name'];
				
				$option_edit   = "<a href='?view=manage-youth&action=summary&id=$youthid' data-toggle='tooltip' title='Edit this youth record'>$fa_edit</a>";
				$option_delete  = "<a href='#' data-toggle='tooltip' title='Delete this youth record' style='color:red' onclick=\"confirmdelete('youth',$youthid);\">$fa_trash</a>";
					
				$results .= "<tr>
									<td>$idx</td>
									<td>$entrydate</td>
									<td>$youthid</td>
									<td>$fullname</td>
									<td><span class='badge'>$age</span></td>
									<td>$sex</td>
									<td>$region</td>
									<td><a href='?view=users&action=summary&id=$user_id_'>$fa_user $username</a></td>
									<td>$option_edit $option_delete</td>
								  </tr>";
			}
			
				// enable dataTable()
				$datatable = "<script>
				               $(document).ready(function(){
										$('#tableResults').dataTable();
				               });
				              </script>";
		}										  	
	}
	
	// selectbox of regions
	// not using list builder as 
	// need to add first option manually (All regions)
	$select_regions = "";
	$sql = "SELECT * FROM list_regions ORDER BY name ASC;";
	$ret = $database->query($sql);
	if (!$ret || !$ret->num_rows){}
	else {
		$opts = "<option value='0'>Any Region</option>";
		
		while ($row=$ret->fetch_array()){
			$id   = $row['id'];
			$name = $row["name"];
			$selected = ($id == $region_id) ? 'selected' : '';
			$opts .="<option value='$id' $selected>$name only</option>";
		}
		
		$select_regions = "<select class='form-control' _onchange=\"this.form.submit();\" name='region' id='region'> 
		                   $opts
								</select>";
	}
				
    echo "<h4>$fa_icon Search for data <small class='pull-right'>Find information in the database</small></h4>
			 <hr>	
			<form method='GET' role='form'>	
				<div class='row'>    
					<div class='col-md-8'>
							<input type='hidden' name='view' value='$view'>       
							<input type='hidden' name='extra' value='1'>  
							
							<div class='form-group'>
								<label class='control-label col-sm-3' for='term'>Search <span style='color:red'></span></label>
								<div class='col-sm-8'>
									<input type='text' class='form-control' name='term' id='term' value='$term' placeholder='Search by youth id, first name, last name'>
								</div>
							</div>

							
							<div class='form-group'>
								<label class='control-label col-sm-3' for=''></label>
								<div class='col-sm-8'><button class='btn btn-primary'>$fa_search Search</button></div>
							</div>
				        <script>
				         $(document).ready(function(){
				         	//$('#region').select2();
				         	$('#term').focus();
				         });
				        </script>
					</div>
				</div>
			</form>

		  <HR>
		  <div class='table-responsive'>
			<table id='tableResults' class='table table-hover table-bordered'>
		 	 <thead>
				<tr>$fields</tr>
			 </thead>
			 <tbody>
				$results		 
			 <tbody>
			</table>
			$datatable
		  </div>";	
?>
