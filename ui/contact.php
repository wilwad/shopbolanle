<?php
    // icons
    $fa_exclaim = font_awesome('fa-exclamation-triangle');
    
  /*
   * Edit these as needed
   */
   $form_subtitle = "Should you have any queries, suggestions or comments, do not hesitate to contact us.";
   $message_booking_success = "<span class='fa fa-fw fa-check'></span> Thank you for your input. We will be in contact as soon as possible.";
   $message_booking_fail    = "Failed to send the email of your input.";
   $to      = "orders@shopbolanle.com";
   $subject = 'Website contact (orders@shopbolanle.com)';
   $debug   = false;
   $button_submit = "Submit";  
   $signature = "Bolanle";
   $field_first = "";
   
  $fields = array(
                array('name'=>'full_name',
			      'caption'=>'Full Name',
                    'small'=>'',
                    'placeholder'=>'',
                    'type'=>'text',
                    'source'=>'',
                    'required'=>true,
                    'callback'=>'',
                    'maxlength'=>0),

            /*
		        array('name'=>'cellphone',
					 'caption'=>'Mobile number',
                    'small'=>'',
                    'placeholder'=>'',
                    'type'=>'text',
                    'source'=>'',
                    'required'=>false,
                    'callback'=>'',
                    'maxlength'=>13), 
            */
            
			 array('name'=>'email',
					  'caption'=>'Email',
                    'small'=>'',
                    'placeholder'=>'',
                    'type'=>'email',
                    'source'=>'',
                    'required'=>true,
                    'callback'=>'',
                    'maxlength'=>200),
                    
		         array('name'=>'country',
						'caption'=>'Country',
                        'small'=>'',
                        'placeholder'=>'',
                        'type'=>'select',
                        'source'=>'ui/countries.txt',
                        'required'=>true,
                        'callback'=>'',
                        'maxlength'=>0),
                        
			array('name'=>'nature',
			    'caption'=>'Nature of submission',
                'small'=>'',
                'placeholder'=>'',
                'type'=>'select',
                'source'=>'ui/nature-submission.txt',
                'required'=>true,
                'callback'=>'',
                'maxlength'=>0),                                                               
                        
			array('name'=>'details',
			    'caption'=>"Details",
                'small'=>'',
                'placeholder'=>'',
                'type'=>'textarea',
                'source'=>'',
                'required'=>true,
                'callback'=>'',
                'maxlength'=>200), 
                        
			array('name'=>'captcha',
    		        'caption'=>"*Captcha*",
                    'small'=>'',
                    'placeholder'=>'',
                    'type'=>'text',
                    'source'=>'',
                    'required'=>true,
                    'callback'=>'',
                    'maxlength'=>10)                        
                  );                  

	$errors = "";
	$extra = (int) @ $_POST['extra'];
	
	if ($extra)	{
	        $body = "";
	        
		foreach($fields as $field){
			$fld       = $field['name'];	
			
			$name      = $fld;		
			$val       = trim(@ $_POST[$fld]);
			$caption   = $field['caption'];
			$source    = $field['source'];
			$small     = "<BR>" . $field['small'];
			$type      = $field['type'];
			$maxlength = $field['maxlength'] == 0 ? '' : $field['maxlength'];

			if ($field['required'] == true && strlen($val) == 0){
				$errors = "<p class='alert alert-danger'>$fa_exclaim You forgot to specify <b>$name</b>, which is a required field.</p>
							  <script>
							   $(document).ready(function(){
							  	  $('#$name').focus();
							  	});
							  </script>";
			}
			
			if ($field['name'] == 'captcha'){
			    if ($val != $_SESSION['captcha']){
			    $_POST[$field['name']] = "";
			    
				$errors = "<p class='alert alert-danger'>$fa_exclaim Re-enter the Captcha</p>
							  <script>
							   $(document).ready(function(){
							  	  $('#$name').focus();
							  	});
							  </script>";       
			    }
			} else {
			    $body .= "<tr><td><b>$caption</b></td><td>$val</td></tr>";   
			}
		}
		
		if ($errors == ""){
			//send email
			$date    = date('Y-m-d H:i:s');
			$headers = "From: $to\r\n";
			$headers .= "Reply-To: $to\r\n";
			$headers .= "CC: ubahashipoke@gmail.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			$message = "
								<p>A client has submitted input. Below are the details.</p>

								<table>
								 <tbody>
								  $body
								 </tbody>
								</table>
								<p></p>
								<p>Follow up asap.</p>";

		        $subject = "Input from contact us form";
		        
			if ($debug)
				echo "<pre>$message</pre>";
			
                        foreach($_POST as $arg){
                           $_POST[$arg] = "";
                           unset($_POST[$arg]);
                        }
                        
            $nature  = @ $_POST['nature'];
            $nature  = strtolower($nature);
            
			if (mail($to,$subject, $message, $headers)){
				echo "<p class='alert alert-success'>$message_booking_success</p>";

			    $email = @ $_POST["email"];
		  	    if ($email <> "")
		  	    {
    				$subject = "Thank you for your input.";
    				$message = "<p>Details of your input</p>
    								<table>
    								 <tbody>
    								  $body
    								 </tbody>
    								</table>
    								<p>
    								We have taken note of your $nature. We will be in contact as soon as possible.
    								</p>
    								<p>
    								Regards,
    								</p>
    								<p>
    								$signature
    								</p>";
    				
    				if (mail($email,$subject, $message, $headers)){
    					//echo "<p class='alert alert-success'>$message_booking_success</p>";
    					//exit;
    				}
			     }
			
			     echo "<meta http-equiv='refresh' content='2;?view=home'>";
                             exit;
			}
			
			echo "<p class='alert alert-warning'>$message_booking_fail</p>";
		}
	}
?>

<div class="row">
 <div class="col-md-7" style="border-right: 1px dotted #C3C3C3;">
   <p><?php echo $form_subtitle; ?></p>
   <p>&nbsp;</p> 
  <?php echo $errors; ?>
  <form method="POST">
   <input type='hidden' name='action' value="contact">
   <input type='hidden' name='extra' value="1">
   
	<?php
       // captcha
       $possibles = array(11,1,26,2,30,3,43,4,58,5,62,6,79,7,81,8,99,9);	
       $op1       = $possibles[array_rand($possibles )];
       $op2       = $possibles[array_rand($possibles )];
       $answer    = intval($op1) + intval($op2);
       
       $_SESSION['captcha'] = $answer;
        
		$required_arr = null;
		$idx = 0;

		foreach($fields as $field){
                        $idx++;

			$fld       = $field['name'];				
			$name      = $fld;		

            if ($idx == 1)
                $field_first = $name;

			$val       = @ $_POST[$fld];
			$caption   = $field['caption'];
			
			if ($fld == 'captcha'){
                $caption = "Human test: Add $op1 to $op2";
			}
			
			$source    = $field['source'];
			$small     = "<BR>" . @ $field['small'];
			$type      = $field['type'];
			$maxlength = $field['maxlength'] == 0 ? '' : $field['maxlength'];
			$required  = $field['required'] ? "<span style='color:red'>*</span>" : "";
			
			if ($field['required'])
				$required_arr[] = "'$fld'";
				
			$input   = "";
			
			switch($type) {
				case 'email':
				case 'text':
				case 'number':
					$input = "<input type='$type' class='form-control' id='$name' name='$name' maxlength='$maxlength' value='$val'>";
					break;
					
				case 'textarea':
				        $input = "<textarea name='$name' rows='3' class='form-control' id='$name' maxlength='$maxlength'>$val</textarea>"; 
					break;
						
				case 'select':
					$filename = $source;
					$options= "";
					
					if (file_exists($filename)){
							$handle = fopen($filename, "r");
							if ($handle) {
							    while (($line = fgets($handle)) !== false) {
							    	  $line = trim($line);
							          $selected = $line == $val ? 'selected' : '';							    	  
							    	  if (strlen($line))
							        		$options .= "<option value='$line' $selected>$line</option>";
							    }
							
							    fclose($handle);
							} else {
							    echo "<p class='alert alert-warning'>Error opening the file $filename</p>";
							} 

						$input = "<select class='form-control' id='$name' name='$name'>
						           $options
						          </select>
						          <script>
						           $(document).ready(function(){
						           	$('#$name').select2();
						           });
						          </script>";
					}
					else {
						$input = "<p class='alert alert-warning'>Unable to locate file $filename</p>";
					}
					break;
			}
			
			echo "<div class='form-group row'>
					    <label for='$fld' class='col-sm-4 form-control-label'>$caption $required <small class='tiny'>$small</small></label>
					    <div class='col-sm-6'>
					      $input
					    </div>
					 </div>";
		}
		
		if ($required_arr)
  		    $required = implode(",", $required_arr);
	?>
	  
	  <div class="form-group row">
	    <div class="col-sm-offset-4 col-sm-6">
	      <button type="submit" onclick="return validate_submit();" class="btn btn-primary"><span class='fa fa-fw fa-paper-plane'></span>
	      <?php echo $button_submit; ?></button>
	    </div>
	  </div>
  </form>
 </div>
 <div class="col-md-4">
  <img src='images/contact_us_pen.jpg' class='img-responsive hidden-xs'>
 </div>
</div>

<script>
$(document).ready(function(){
 // $('#<?php echo $field_first; ?>' ).focus()
});

function validate_submit(){
	var required = [<?php echo $required; ?>];
	for(var idx=0; idx < required.length; idx++){
		var ctl = required[idx];
		console.log(ctl);
		
		if ($('#' + ctl).val().trim().length == 0){
			 $('#' + ctl).focus();
			 console.log('focus set -- waiting');
			return false;
		}
	}
	
	return true;
}
</script>