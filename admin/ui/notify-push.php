<?php
	$fa_external_link   = font_awesome('fa-external-link');
	$fa_airplane = font_awesome('fa-paper-plane');
	$fa_search   = font_awesome('fa-search');
	
	$action      = @ $_GET['action'];	
	$extra       = (int) @ $_POST['extra'];
	$to          = @ $_GET['to'];
	$title       = @ $_POST['title'];
	$message     = @ $_POST['message'];
	 
	switch($action) {
		case 'pick-individual':
			echo "<div class='row'>
					 <div class='col-md-12'>
					 	<h4>$fa_external_link Pick an individual <small class='pull-right'>Send out push notification to the youth</small></h4>
					 	<HR>
					 </div>
					</div>";

			$fields = "<tr>
			            <th>#</th>
			            <th>Full name</th>
			            <th>GCM Registration ID</th>
			            <th>Action</th>
			           </tr>";					
			$data = "";
			
			$sql = "SELECT 
						    youth_id,
						    gcm_reg_id,
						    CONCAT(name_first,
						            ' ',
						            name_others,
						            ' ',
						            name_surname) AS full_name
						FROM
						    youth_profile
						WHERE
						    gcm_reg_id <> ''
						ORDER BY name_first;";
			$ret = $database->query($sql);
			if (!$ret || !$ret->num_rows){
			} else {
				$data = "";
				$idx = 1;
				
				while($row = $ret->fetch_array()) {
					$id        = $row['youth_id'];
					$full_name = $row['full_name'];
					$gcmregid  = $row['gcm_reg_id'];

					$data .= "<tr>
					           <td>$idx</td>
					           <td>$full_name</td>
					           <td>$gcmregid</td>
					           <td><a href='?view=notify-push&to=indiv-{$id}' class='btn btn-sm btn-success'>$fa_airplane Send to mobile app</a></td>
					          </tr>";
					$idx++;
				}
			}
			
			// get a list of people on the system
			echo "<div class='row'>
					 <div class='col-md-12'>
					  <div class='table-responsive'> 
					   <table class='table table-bordered table-hover'>
					    <thead>
					     $fields
					    </thead>
					    <tbody>
					     $data
					    </tbody>
					   </table>
					  </div>
					 </div>
					</div>";
			break;
			
		case 'pick-group':
			echo "<div class='row'>
					 <div class='col-md-12'>
					 	<h4>$fa_external_link Pick a group <small class='pull-right'>Send out push notification to the youth</small></h4>
					 	<HR>
					 </div>
					</div>";
			break;
			
		default:
			$errors = "";
			$type = "";
			$full_name = "";
			$gcmregid = "";
			$id = "";

			if (strlen($to)){
				$type = explode('-',$to)[0];
				$id   = (int) explode('-',$to)[1];
				
				switch ($type){
						case 'indiv':
								$sql = "SELECT 
											    gcm_reg_id,
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
									$row       = $ret->fetch_array();
									$full_name = $row['full_name'];
									$gcmregid  = $row['gcm_reg_id'];
									$to        = $full_name;
								}
							break;
							
						case 'group':
							break;
				}
			}
			 
			if ($extra == 1){
				// read db to select the selected sms service provider
				/*
				require('providers_gcm/gcm-services-gcm.php');
				
				if (!strlen($message))
					$errors = alertbuilder('You have not specified the message to send.','warning');
				else {
					$gcm = new GCM();
					$ret = $gcm->send(json_encode(array('to'=>$gcmregid)),
					                  array('data'=>$message));
					
					// response has called alertbuilder()
					$errors .= alertbuilder($ret,'warning');
				}		
				*/	
				
				require('providers_gcm/gcm-sample.php');
				
				if (!strlen($title))
					$errors = alertbuilder('You have not specified the title.','warning');
					
				elseif (!strlen($message))
					$errors = alertbuilder('You have not specified the message to send.','warning');
					
				else {					
					$ret = gcm_sample_send(array($gcmregid, "fmYDuor5EVE:APA91bH-GJn4zeY1SBNC4V_w66e9jR-yDFtxl5cfF-5Gw5eNgOAigkA7-A_hiNvCrc56n8kCFtLP3eP6TWtoN6pPNkoeMwYl9JlsXVsPBl0L9QpanjwEShJi4d5jwvmgJzSgVQypX8S4"), $message);
					
					// response has called alertbuilder()
					$errors .=$ret;
				}										
			}
			
			echo "<div class='row'>
					 <div class='col-md-12'>
					 	<h4>$fa_external_link Push Notification <small class='pull-right'>Send out push notification to the youth</small></h4>
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
										<a href='?view=notify-push&action=pick-individual' class='btn btn-sm btn-default'>$fa_search Send to an individual</a> 
										<a href='?view=notify-push&action=pick-group' class='btn btn-sm btn-default'>$fa_search Send to a group</a> 
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
									<label class='control-label col-sm-2' for='title'>
									Title <span style='color:red'>*</span></label>
									<div class='col-sm-6'>
									  <input class='form-control' maxlength='' value='' id='title' 
									            name='title' value='$title'>
									</div>
								</div>
																
								<div class='form-group'>
									<label class='control-label col-sm-2' for='message'>
									Message <span style='color:red'>*</span></label>
									<div class='col-sm-6'>
									  <textarea class='form-control' maxlength='' value='' id='message' 
									            name='message'>$message</textarea>
									</div>
								</div>
									
								<div class='form-group'>
									<label class='control-label col-sm-2' for=''></label>
									<div class='col-sm-8'>
										<button class='btn btn-sm btn-primary'>$fa_airplane Send Push Notification</button> 
									   <a href='#' onclick='window.history.back(-1);' class='btn btn-sm btn-warning'> Cancel</a>
								  </div>
								 </div>
								 
								 <p>&nbsp;</p>					
							</form>
					 </div>
					</div>";
			
			break;
	}
	

			

	

?>