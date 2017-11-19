<?php
	$fa_mobile   = font_awesome('fa-mobile');
	$fa_airplane = font_awesome('fa-paper-plane');
	$fa_search   = font_awesome('fa-search');
	
	$action      = @ $_GET['action'];	
	$extra       = (int) @ $_POST['extra'];
	$to          = @ $_GET['to'];
	$message     = @ $_POST['message'];
	 
	switch($action) {
		case 'pick-individual':
			$max_records = 0;
			$datatable = "";
			
			$fields = "<tr>
			            <th>#</th>
			            <th>Full name</th>
			            <th>Cellphone</th>
			            <th>Action</th>
			           </tr>";					
			$data = "";
			
			$sql = "SELECT 
						    youth_id,
						    cellphone,
						    CONCAT(name_first,
						            ' ',
						            name_others,
						            ' ',
						            name_surname) AS full_name
						FROM
						    youth_profile
						WHERE
						    (SUBSTR(cellphone, 1, 3) = '081' OR
						    SUBSTR(cellphone, 1, 3) = '085') AND
						    CHAR_LENGTH(cellphone) > 8
						ORDER BY name_first;";
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows){
			} else {
				$data = "";
				$idx = 1;
				$max_records = $ret->num_rows;
				
				while($row = $ret->fetch_array()) {
					$id        = $row['youth_id'];
					$full_name = $row['full_name'];
					$cellphone = $row['cellphone'];

					$data .= "<tr>
					           <td>$idx</td>
					           <td>$full_name</td>
					           <td>$cellphone</td>
					           <td><a href='?view=notify-sms&to=indiv-{$id}' class='btn btn-sm btn-success'>$fa_airplane Send to cellphone</a></td>
					          </tr>";
					$idx++;
				}
				
				// enable dataTable()
				$datatable = "<script>
				               $(document).ready(function(){
										$('#tableRecipients').dataTable();
				               });
				              </script>";
			}

			echo "<div class='row'>
					 <div class='col-md-12'>
					 	<h4>$fa_mobile Pick an individual <small class='pull-right'>Send out bulk sms to the youth. Recipients available: <span class='badge'>$max_records</span></small></h4>
					 	<HR>
					 </div>
					</div>";

			echo "<div class='row'>
					 <div class='col-md-12'>
					  <div class='table-responsive'> 
					   <table id='tableRecipients' class='table table-bordered table-hover'>
					    <thead>
					     $fields
					    </thead>
					    <tbody>
					     $data
					    </tbody>
					   </table>
					  </div>
					 </div>
					</div>
					$datatable";
			break;
			
		case 'pick-group':
			echo "<div class='row'>
					 <div class='col-md-12'>
					 	<h4>$fa_mobile Pick a group <small class='pull-right'>Send out bulk sms to the youth</small></h4>
					 	<HR>
					 </div>
					</div>";
			break;
			
		default:
			$errors = "";
			$type = "";
			$full_name = "";
			$cellphone = "";
			$id = "";

			if (strlen($to)){
				$type = explode('-',$to)[0];
				$id   = (int) explode('-',$to)[1];
				
				switch ($type){
						case 'indiv':
								$sql = "SELECT 
											    cellphone,
											    CONCAT(name_first,
											            ' ',
											            name_others,
											            ' ',
											            name_surname) AS full_name
											FROM
											    youth_profile
											WHERE
											    youth_id = $id;";
											    
								$ret = $database->query($sql);
								if (!$ret || !$ret->num_rows){
								} else {
									$row = $ret->fetch_array();
									$full_name = $row['full_name'];
									$cellphone = $row['cellphone'];
									$to = $full_name;
								}
							break;
							
						case 'group':
							break;
				}
			}
			 
			if ($extra == 1){
				// read db to select the selected sms service provider
				require('providers_sms/sms-services-bulksms.php');
				
				if (!strlen($message))
					$errors = alertbuilder('You have not specified the message to send.','warning');
				else {
					$ret = send_sms($cellphone, $message);
					
					// response has called alertbuilder()
					$errors .= $ret;
					
					if (strpos($ret, 'successfully sent') !== false){
						echo "<script>
						       $('#message').val('');
						      </script>";
					}
				}								
			}
			
			echo "<div class='row'>
					 <div class='col-md-12'>
					 	<h4>$fa_mobile Bulk sms <small class='pull-right'>Send out bulk sms to the youth</small></h4>
					 	<HR>
					 </div>
					</div>";		
				
			echo "<div class='row'>
					 <div class='col-md-12'>
						$errors
						<form class='form-horizontal' role='form' method='POST' enctype='multipart/form-data'>
							<!-- required params start -->
								<input type='hidden' name='action' value='add'>
								<input type='hidden' name='userid' value='$userid'>
								<input type='hidden' name='extra' value='1'>
							<!-- required params end -->
								
								<div class='form-group'>
									<label class='control-label col-sm-2' for=''>Picker</label>
									<div class='col-sm-8'>
										<a href='?view=notify-sms&action=pick-individual' class='btn btn-sm btn-default'>$fa_search Send to an individual</a> 
										<a href='?view=notify-sms&action=pick-group' class='btn btn-sm btn-default'>$fa_search Send to a group</a> 
								  </div>
								 </div>
								 
								<div class='form-group'>
									<label class='control-label col-sm-2' for='to'>
									Recipient(s) <span style='color:red'>*</span></label>
									<div class='col-sm-6'>
									  <input type='text' class='form-control' data-role='tagsinput' value='$to' name='to' id='to'>
									</div>
								</div>
								
								<div class='form-group'>
									<label class='control-label col-sm-2' for='message'>
									Message <span style='color:red'>*</span></label>
									<div class='col-sm-6'>
									  <textarea class='form-control' maxlength='160' value='' id='message' 
									            name='message'>$message</textarea>
									</div>
								</div>
									
								<div class='form-group'>
									<label class='control-label col-sm-2' for=''></label>
									<div class='col-sm-8'>
										<button class='btn btn-sm btn-primary'>$fa_airplane Send SMS</button> 
									   <a href='#' onclick='window.history.back(-1);' class='btn btn-sm btn-warning'>Cancel</a>
								  </div>
								 </div>
								 
								 <p>&nbsp;</p>					
							</form>
					 </div>
					</div>";
			
			break;
	}
?>