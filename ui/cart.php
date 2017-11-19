<?php
 // params
 $action = @ $_REQUEST['action'];
 $id     = (int) @ $_GET['id'];
 $key    = "shopping_cart";
 
 // icons
 $fa_eye     = font_awesome('fa-eye');
 $fa_cart    = font_awesome('fa-shopping-cart'); 
 $fa_trash   = font_awesome('fa-trash'); 
 $fa_check   = font_awesome('fa-check');
 $fa_exclaim = font_awesome('fa-exclamation-triangle');
 
 $button_request_state = "disabled";
 
$business_address = settings::business_address;
?>

<div class='row'>
    <div class='col-md-12'>
        <h3><span class='fa fa-fw fa-shopping-cart'></span> Quote Cart <small class='pull-right'><a href='?view=shipping' style='text-decoration:underline'>Payment & Shipping terms</a></small></h3>
        <HR>
    </div>
</div>
<?php
  $key_shipping = "shipping_details";
  //$_SESSION[$key_shipping] = null;
  
  $_SESSION[$key_shipping] = !isset($_SESSION[$key_shipping]) ? 
  array('email'=>'',
        'full_name'=>'',
        'cellphone'=>'',
        'address'=>'',
        'apartment'=>'',
        'city'=>'',
        'country'=>'') : 
      $_SESSION[$key_shipping];
  
   switch ($action){
     case 'dispatch-quote':
         $shipping_details = "";
         $quote_items      = "";
         $quote_items_sql  = "";
         
         $email           = addslashes(htmlentities( @ $_SESSION[$key_shipping]['email']));
         $fullname        = addslashes(htmlentities( @ $_SESSION[$key_shipping]['full_name']));
         $cellphone       = addslashes(htmlentities( @ $_SESSION[$key_shipping]['cellphone']));
         $physicaladdress = addslashes(htmlentities( @ $_SESSION[$key_shipping]['address']));
         $aptsuite        = addslashes(htmlentities( @ $_SESSION[$key_shipping]['apartment']));
         $city            = addslashes(htmlentities( @ $_SESSION[$key_shipping]['city']));
         $countryid       = (int) addslashes(htmlentities( @ $_SESSION[$key_shipping]['country']));
                
         // get the countries
         $sql = "SELECT name 
                 FROM list_countries 
                 WHERE id=$countryid;";
         $ret = $database->query($sql) or die($database->error);
         if (!$ret || !$ret->num_rows){
             die("Failed to get your country name.");
         }
         $country = $ret->fetch_array()['name'];
         
    	$shipping_details = "<tr><th style='padding:5px;'>Full name</th><td style='padding:5px;'>$fullname</td></tr>
    	<tr><th style='padding:5px;'>Email</th><td style='padding:5px;'>$email</td></tr>
    			             <tr><th style='padding:5px;'>Cellphone</th><td style='padding:5px;'>$cellphone</td></tr>
    			             <tr><th>Address</th><td style='padding:5px;'>$physicaladdress</td></tr>
    			             <tr><th style='padding:5px;'>Apt, suite. etc</th><td style='padding:5px;'>$aptsuite</td></tr>
    			             <tr><th style='padding:5px;'>City</th><td style='padding:5px;'>$city</td></tr>
    			             <tr><th style='padding:5px;'>Country</th><td style='padding:5px;'>$country</td></tr>";         
         // send to DB
         if (!isset($_SESSION[$key]) || !isset($_SESSION[$key_shipping])){
             echo alertbuilder("Your quote cart is empty or your shipping information is not set.");
         } else {
           if (!count(array_filter($_SESSION[$key]))){
               echo alertbuilder("Your quote cart is empty.");
           } else {

             $array = $_SESSION[$key];
             $count = count(array_filter($array));
             
             if (!$count){
                 echo alertbuilder("Your quote cart is empty.",'warning');
             } else {
                $arr = array();
                
                foreach($array as $key_=>$arry)
                    if ($key_) $arr[] = $key_;
                
                $arr = implode(',',$arr);
                
                $sql = "SELECT 
                                *, si.id AS item_id, sic.name AS cat
                        FROM 
                              store_items si, 
                              store_item_categories sic,
                              list_visibility lv
                        WHERE 
                              sic.id = si.store_item_category_id AND 
                              sic.name='item' AND 
                              si.visibility_id = lv.id AND
                              lv.name='visible' AND 
                              si.id IN ($arr)
                        ORDER BY 
                              si.id DESC;";
                $ret = $database->query($sql) or 
                die(alertbuilder($database->error . " -> " . $sql,'danger'));
                
                if (!$ret || !$ret->num_rows){
                    // nothing to show
                    echo alertbuilder("Your quote cart is empty.",'warning');
                } else {
                    $idx = 0;
                    $total = 0;
                    $quant = 0;
                    $tbody = "";
                    
                    while ($row = $ret->fetch_array()){
                        $idx++;
                        
                        $id     = $row['item_id'];
                        $title  = $row['title'];
                        $desc   = $row['description'];
                        $price  = floatval($row['cost']);
                        $shipping=  floatval($row['shipping']);
                        $img1   = $row['url_image_1'];
                        $cat    = strtolower($row['cat']);
                        
                        $quantity = (int) $_SESSION[$key][$id]['quantity'];
                        $quant += $quantity;
                        
                        $grand = ($price + $shipping) * $quantity;
                        
                        $color_mat = $_SESSION[$key][$id]['extra'] == "" ? "None" : $_SESSION[$key][$id]['extra'];
                        
                        $quote_items .= "<tr>
                                     <td style='padding:5px;'>$idx.</td>
                                     <td style='padding:5px;'>
                                         <b>$title</b></td>
                                     <td style='padding:5px;'>$quantity</td>
                                     <td style='padding:5px;'>$color_mat</td>
                                   <tr>";
                                   
                        // not SAFE, 
                        // someone can add SQL injection here
                        $color_mat_ = addslashes(htmlentities($color_mat));           
                        /*quote_id, item_id, price, quantity, shipping, specifications*/
                        /*
                         INSERT INTO `quote_items`(quote_id, item_id,  quantity, specifications)
                        */
                        $quote_items_sql .= "(_quote_id_, 
                                              '$id',
                                              '$quantity',
                                              '$color_mat_'),";
                    }
                    
                    // remove trailing comma
                    $quote_items_sql = substr($quote_items_sql, 0, strlen($quote_items_sql)-1);

        			$message = "<p>Dear $fullname, thank you for requesting a quotation from Bolanle.</p>
        			            <p>&nbsp;</p>
        			            <p><b>Your shipping details</b><p>
        			            <table style='border-collapse: collapse;' border='1'>
        			             <tbody>
                                    $shipping_details
        			             </tbody>
        			            </table>
        			            <p>&nbsp;</p>
        			            <p><b>Item(s) you need a quote for</b><p>
        			            <table style='border-collapse: collapse;' border='1' width='100%'>
        			             <thead>
        			              <tr>
        			               <th style='padding:8px'>#</th>
        			               <th style='padding:8px'>Title</th>
        			               <th style='padding:8px'>Quantity</th>
        			               <th style='padding:8px'>Specifications</th>
        			              </tr>
        			             </thead>
        			             <tbody>
        			              $quote_items
        			             </tbody>
        			            </table>
        			            <p>&nbsp;</p>
        			            <p>We will create a custom quotation for you shortly.  </p>
        			            <p>Regards,</p>
        			            <p>Bolanle</p>
        			            <p><small><b>Website:</b> http://www.shopbolanle.com</small> | <small><b>Payment & Shipping terms</b> http://www.shopbolanle.com/?view=shipping</small><p>";
        			            
    $message = "<!Doctype html>
                <html>
                <head>
                	<meta charset='utf-8'>
                	<meta name='viewport' content='width=device-width, initial-scale=1'>
                	
                	<title>Quotation</title>

                	<!-- Invoice styling -->
                	<style>
                	body{
                		font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                		text-align:center;
                		color:#777;
                		font-size: 12pt;
                	}
                	
                	body h1{
                		font-weight:300;
                		margin-bottom:0px;
                		padding-bottom:0px;
                		color:#000;
                	}
                	
                	body h3{
                		font-weight:300;
                		margin-top:10px;
                		margin-bottom:20px;
                		font-style:italic;
                		color:#555;
                	}
                	
                	body a{
                		color:#06F;
                	}
                	
                    tr:nth-child(even) {background: #CCC}
                    tr:nth-child(odd) {background: #FFF}
                	
                	.invoice-box{
                		max-width:800px;
                		margin:auto;
                		padding:30px;
                		border:2px solid #4b7b8a;
                		/*box-shadow:0 0 10px 
                		#4b7b8a;*/
                		font-size:14px;
                		line-height:24px;
                		font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                		color:#555;
                	}
                	
                	.invoice-box table{
                		width:100%;
                		line-height:inherit;
                		text-align:left;
                	}
                	
                	.invoice-box table td{
                		padding:5px;
                		vertical-align:top;
                	}
                	
                	.invoice-box table tr td:nth-child(2){
                		text-align:right;
                	}
                	
                	.invoice-box table tr.top table td{
                		padding-bottom:20px;
                	}
                	
                	.invoice-box table tr.top table td.title{
                		font-size:45px;
                		line-height:45px;
                		color:#333;
                	}
                	
                	.invoice-box table tr.information table td{
                		padding-bottom:40px;
                	}
                	
                	.invoice-box table tr.heading td{
                		background:#eee;
                		border-bottom:1px solid #ddd;
                		font-weight:bold;
                	}
                	
                	.invoice-box table tr.details td{
                		padding-bottom:20px;
                	}
                	
                	.invoice-box table tr.item td{
                		border-bottom:1px solid #eee;
                	}
                	
                	.invoice-box table tr.item.last td{
                		border-bottom:none;
                	}
                	
                	.invoice-box table tr.total td:nth-child(2){
                		border-top:2px solid #eee;
                		font-weight:bold;
                	}
                	
                	@media only screen and (max-width: 600px) {
                		.invoice-box table tr.top table td{
                			width:100%;
                			display:block;
                			text-align:center;
                		}
                		
                		.invoice-box table tr.information table td{
                			width:100%;
                			display:block;
                			text-align:center;
                		}
                	}
                	</style>
                </head>
                <body>
                <div class='invoice-box'>
                      <img src='https://www.shopbolanle.com/images/navbrand-bolanle-200px.jpg'>
                      
                        <h2 style='text-transform: uppercase;'>Request for Quote</h2>
                        <BR>
                        $business_address
                        <BR><BR>
                        <span style='color: gray'>To</span>
                        <BR>
                        <small>
                			$fullname<br>
                			$physicaladdress $aptsuite<br>
                			$city, $country
                        </small>
                      <BR>
                      <HR>
                      <BR>
                        <table class='table table-striped'>
                         <thead>
                          <tr style='background: #786e53; color: #FFF; padding: 7px;text-align: center;'>
                                <th>#</th>
                				<th>Item</td>
                				<th>Quantity</td>
                				<th>Specifications</td>          
                			</tr>
                         </thead>
                         
                         <tbody>
                                $quote_items
                         </tbody>
                        </table>
                
                        <p>We will create a custom quotation for you shortly.</p>

                        <p style='font-style:italic; color:#4b7b8a;'>Thank you for your business!</p>
                        
                        <p><small><b>Website:</b> https://www.shopbolanle.com</small>
                        <BR>
                        <small><b>Payment & Shipping terms</b> https://www.shopbolanle.com/?view=shipping</small></p>      
                </div>
                 </body>
                </html>";        			            

                 // die($message);
                  
                   /* start */
                   date_default_timezone_set('Africa/Windhoek');
                
                   // quote hash
                   $hash = randomPassword(10);
                   $date = date('Y-m-d H:i:s');

                   /* step 1: create the quote */
                   $sql = "INSERT INTO `quotes`(entrydate, hash)
                           VALUES('$date','$hash');";
                   $ret = $database->query($sql) or die($database->error);
                   if (!$ret){
                       die($database->error);
                   }
                   $quoteid = $database->insert_id;
                   
                   /* make sure we don't have this shipping ID */
                   $sql = "SELECT quote_id FROM shipping_address WHERE quote_id=$quoteid;";
                   $ret = $database->query($sql) or die($database->error);
                   if (!$ret || !$ret->num_rows){
                       // fine. nothing found
                   } else {
                        $sql = "DELETE FROM quotes WHERE id=$quoteid;";
                        $ret = $database->query($sql);
                        die("There is already shipping information for quote: $quoteid");
                   }
                   
                   /* step 2: shipping address */
                   $sql = "INSERT INTO `shipping_address`(quote_id, email, full_name, cellphone, physical_address, apt_suit, city, country_id)
                         VALUES
                         ($quoteid, 
                          '$email',
                          '$fullname',
                          '$cellphone',
                          '$physicaladdress',
                          '$aptsuite',
                          '$city',
                          '$countryid');";
                   $ret = $database->query($sql) or die($database->error);
                   if (!$ret){
                       $sql = "DELETE FROM quotes WHERE id='$quoteid';";
                       $ret = $database->query($sql);
                       die("Failed to save your shipping address.");
                   }
                   
                   /* step 3: add quote items, 
                              loop through $_SESSION */
                   $quote_items_sql = str_replace('_quote_id_', $quoteid, $quote_items_sql);

                   $sql = "INSERT INTO `quote_items`(quote_id, item_id, quantity, specifications)
                         VALUES
                         $quote_items_sql";
                   $ret = $database->query($sql) or die($database->error);
                   
                   if (!$ret){
                       $error = $database->error;
                       
                       $sql = "DELETE FROM quotes WHERE id='$quoteid';";
                       $ret = $database->query($sql);
    
                       $sql = "DELETE FROM shipping_address WHERE id='$quoteid';";
                       $ret = $database->query($sql);
                       
                       die("Failure requesting quotation.");                   
                   }
                   
                   echo alertbuilder("$fa_check Success. Check your email.", 'success');
                   
        			//send email
        			$date    = date('Y-m-d H:i:s');
        			$headers = "From: orders@shopbolanle.com\r\n";
        			$headers .= "Reply-To: orders@shopbolanle.com\r\n";
        			$headers .= "CC: ubahashipoke@gmail.com\r\n";
        			$headers .= "MIME-Version: 1.0\r\n";
        			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
        			$subject = "Request for Quotation";
        			
                   @ mail($email,$subject, $message, $headers);

                    /* save to quote_emails */
			        $sql = "INSERT INTO quote_emails(quote_id,entrydate,user_id,subject,body)
			        VALUES($quoteid,'$date',(SELECT id FROM users WHERE user_name='Admin' LIMIT 1),'Request for quotation','The client has made a request for quotation.');";
			        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			        
			       // notify orders at shopbolanle
        			$headers_ = "From: orders@shopbolanle.com\r\n";
        			$headers_ .= "Reply-To: orders@shopbolanle.com\r\n";
        			$headers_ .= "CC: ubahashipoke@gmail.com\r\n";
        			$headers_ .= "MIME-Version: 1.0\r\n";
        			
			       $email_   = "orders@shopbolanle.com";
			       $subject_ = "A customer has requested a quote";
			       $message_ = "Details of the RFQ:\r\n\r\nDate: $date \r\nFull name: $fullname \r\nCountry: $country \r\nTotal items: $idx \r\n\r\nYou can manage this RFQ by clicking: \r\nhttps://www.shopbolanle.com/admin/?view=manage-orders&action=edit&id=$quoteid&tab=tabquoteitems ";
			                    
                   @ mail($email_,$subject_, $message_, $headers_);

                  // flush the cart
                  //$_SESSION[$key_shipping] = null;
                  $_SESSION[$key] = array();
                  
                  // reload window
                  echo "<script>window.location.href = '?view=cart';</script>";                    
                    /* end */
                }
                
                /* database */
             }
           }
         }
         break;
                
     case 'shipping':
         // update shipping info
         echo alertbuilder("$fa_check Saved your shipping details.",'success');

        foreach ($_SESSION[$key_shipping] as $key2=>$val){
            $_SESSION[$key_shipping][$key2] = $_POST[$key2];
        }
        break;
   }
   
   // build UI
  $fields = array(
			 array('name'=>'email',
					  'caption'=>'Email',
		                        'small'=>'',
		                        'placeholder'=>'',
		                        'type'=>'email',
		                        'source'=>'',
		                        'required'=>true,
		                        'callback'=>'',
		                        'maxlength'=>200),
		                        
                array('name'=>'full_name',
			      'caption'=>'Full Name',
                        'small'=>'',
                        'placeholder'=>'',
                        'type'=>'text',
                        'source'=>'',
                        'required'=>true,
                        'callback'=>'',
                        'maxlength'=>50),
                        
                array('name'=>'cellphone',
			      'caption'=>'Cellphone',
                        'small'=>'',
                        'placeholder'=>'',
                        'type'=>'text',
                        'source'=>'',
                        'required'=>true,
                        'callback'=>'',
                        'maxlength'=>20),
                        
		        array('name'=>'address',
						 'caption'=>'Physical address',
			                        'small'=>'',
			                        'placeholder'=>'',
			                        'type'=>'text',
			                        'source'=>'',
			                        'required'=>true,
			                        'callback'=>'',
			                        'maxlength'=>300), 
		        array('name'=>'apartment',
						 'caption'=>'Apt, suit, etc. (optional)',
			                        'small'=>'',
			                        'placeholder'=>'',
			                        'type'=>'text',
			                        'source'=>'',
			                        'required'=>false,
			                        'callback'=>'',
			                        'maxlength'=>300), 			                        
		        array('name'=>'city',
						 'caption'=>'City',
			                        'small'=>'',
			                        'placeholder'=>'',
			                        'type'=>'text',
			                        'source'=>'',
			                        'required'=>true,
			                        'callback'=>'',
			                        'maxlength'=>100), 			                        
		         array('name'=>'country',
							'caption'=>'Country',
			                        'small'=>'',
			                        'placeholder'=>'',
			                        'type'=>'select',
			                        'source'=>'database::list_countries',
			                        'required'=>true,
			                        'callback'=>'',
			                        'maxlength'=>0)                      
                  );      
?>
<div class='row'>
 <div class='col-md-6' style='/*background: #f3f3f3; border-radius: 4px;
    box-shadow: 0 0 0 1px #d9d9d9;*/'>
     
    <div class="panel panel-default group-panel">
      <div class="panel-heading collapsed" role="button" data-toggle="collapse" data-target="#panel-body-foobar">
         <h5><span class=''></span> Shipping Details <small class='pull-right'>Required before you can request a quote</small></h5>          
      </div>
      <div class="panel-body collapsed in" id="panel-body-foobar">

      <form method='POST'>
        <input type='hidden' name='view' value='<?php echo $view; ?>'>
        <input type='hidden' name='action' value='shipping'>
       
    	<?php
    		$required_arr = null;
    		$idx = 0;
    
    		foreach($fields as $field){
                $idx++;
    
    			$fld       = $field['name'];				
    			$name      = $fld;		
    
                if ($idx == 1)
                    $field_first = $name;
 
    			$val       =  @ $_SESSION[$key_shipping][$field['name']];
    			
    			//@ $_POST[$fld];
    			$caption   = $field['caption'];
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
    					$input = "<input type='$type' class='form-control' id='$name' name='$name' maxlength='$maxlength' value='$val'>";
    					break;
    					
    				case 'textarea':
    				        $input = "<textarea name='$name' rows='3' class='form-control' id='$name' maxlength='$maxlength'>$val</textarea>"; 
    					break;
    						
    				case 'select':
    					$filename = $source;
    					$options= "";
    					
    					if (substr($filename,0,strlen('database::')) == 'database::'){
    					    $table = substr($filename,strlen('database::'));
    					    if (!strlen($table)){
    					        die(alertbuilder('Not expected.','danger'));
    					    }
    					    
    					    $sql = "SELECT id, name FROM `$table` ORDER BY name ASC;";
    					    $ret = $database->query($sql) or die('Error building list.');
    					    if (!$ret || !$ret->num_rows){
    					        //
    					    } else {
    					        $idx = (int) @ $_SESSION[$key_shipping]['country'];
    					        
    					        while ($row = $ret->fetch_array()){
    					            $id_ = $row['id'];
    					            $val = $row['name'];
    					            $selected = ($idx == $id_) ? 'selected' : '';
    					            $options .= "<option value='$id_' $selected>$val</option>";
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
    					} else {
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
    					    
    					}
    					break;
    					
    			} // switch
    			
    			echo "<div class='form-group row'>
    					    <label for='$fld' class='col-sm-4 form-control-label'>$caption $required <small class='tiny'>$small</small></label>
    					    <div class='col-sm-8'>
    					      $input
    					    </div>
    					 </div>";
    					 
    		} // foreach
    		
    		if ($required_arr){
      		    $required = implode(",", $required_arr);
      		    
      		    $all_filled_in = true;
      		    
      		    foreach ($required_arr as $x){
      		        $x = str_replace("'",'',$x);
      		        
      		        if (strlen(trim(@ $_SESSION[$key_shipping][$x])) == 0){
      		            $all_filled_in = false;
      		        }
      		    }
      		    
      		    if ($all_filled_in == true){
      		        echo "<script>
      		               $(document).ready(function(){
      		                   $('.panel-body').collapse('toggle');
      		               });
      		              </script>";
      		        $button_request_state = '';
      		    }
    		}
    	?>
    	  
    	  <div class='form-group row'>
    	    <div class='col-sm-offset-4 col-sm-6'>
    	      <button type='submit' onclick='return validate_submit();' class='btn btn-primary'><span class='fa fa-fw fa-floppy-o'></span>
    	      Update Shipping Details</button>
    	    </div>
    	  </div>
      </form>  
      
      <script>
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
      
      </div>
    </div>
    
 </div> 
</div>
<BR>
    
<?php
 
 switch ($action){
     case 'r':
         // remove
        if (array_key_exists($id, $_SESSION[$key])) {
            unset($_SESSION[$key][$id]);

            if (!in_array($id, $_SESSION[$key])){
                /*echo alertbuilder("$fa_info Item was removed from your cart", 'success');  */
                die("<script>window.location.href='?view=$view&id=$id';</script>");
            }
            else {
                 echo alertbuilder("$fa_exclaim Failed to remove item from your quote.", 'warning');  
            }
        } else {
             echo alertbuilder("$fa_exclaim That item is not in your quote.", 'warning');
        }
         break;  
         
     case 'q':
        // change quantity
        $quantity = (int) @ $_GET['quantity'];
        
        if ($quantity <= 0){
             echo alertbuilder("$fa_exclaim Quantity cannot be 0 or less.", 'warning');
        } else {
            if (array_key_exists($id,$_SESSION[$key])) {
                $_SESSION[$key][$id]['quantity'] = $quantity;
                
            } else {
                 echo alertbuilder("$fa_exclaim That item is not in your quote.", 'warning');
            }
        }
         break;
         
     case 'cm':
        // change color/material
        $color_mat = @ $_GET['color_mat'];
        
        if (array_key_exists($id,$_SESSION[$key])) {
            $_SESSION[$key][$id]['extra'] = $color_mat;
            
        } else {
             echo alertbuilder("$fa_exclaim That item is not in your quote.", 'warning');
        }
         break;         
 } 
?>
<div class='row'>
    <div class='col-md-12'>
        <?php 
             $thead = "<tr>
                        <th>No.</th>
                        <th>Preview</th>
                        <th>Item Title</th>
                        <th>Quantity</th>
                        <th>Specifications (Color, Material)</th>
                       </tr>";
             $tbody = "<tr><td colspan='5' class='text-center'>Your quote is empty.</td></tr>";
             
             $array = $_SESSION[$key];
             $count = count(array_filter($array));
             
             if (!$count){
                 echo alertbuilder("Your quote cart is empty.",'warning');
             } else {
                $arr = array();
                
                foreach($array as $key_=>$arry)
                    if ($key_) $arr[] = $key_;
                
                $arr = implode(',',$arr);
                
                $sql = "SELECT 
                                *, si.id AS item_id, sic.name AS cat
                        FROM 
                              store_items si, 
                              store_item_categories sic,
                              list_visibility lv
                        WHERE 
                              sic.id = si.store_item_category_id AND 
                              sic.name='item' AND 
                              si.visibility_id = lv.id AND
                              lv.name='visible' AND 
                              si.id IN ($arr)
                        ORDER BY 
                              si.id DESC;";
                $ret = $database->query($sql) or 
                die(alertbuilder($database->error . " -> " . $sql,'danger'));
                
                if (!$ret || !$ret->num_rows){
                    // nothing to show
                    echo alertbuilder("Your quote cart is empty.",'warning');
                } else {
                    $idx = 0;
                    $total = 0;
                    $quant = 0;
                    $tbody = "";
                    
                    while ($row = $ret->fetch_array()){
                        $idx++;
                        
                        $id     = $row['item_id'];
                        $title  = $row['title'];
                        $desc   = $row['description'];
                        $price  = floatval($row['cost']);
                        $shipping=  floatval($row['shipping']);
                        $img1   = $row['url_image_1'];
                        $cat    = strtolower($row['cat']);
                        
                        $action = "<a href='?view=$view&action=r&id=$id' style='color:red' class='btn btn-default'>$fa_trash Remove</a>";
                        
                        $quantity = $_SESSION[$key][$id]['quantity'];
                        $quant += $quantity;
                        
                        $grand = ($price + $shipping) * $quantity;
                        
                        $color_mat = $_SESSION[$key][$id]['extra'];
                        
                        $sel_quantity = "
                        <form style='display:inline' method='GET'>
                            <input type='hidden' name='view' value='$view'>
                            <input type='hidden' name='action' value='q'>
                            <input type='hidden' name='id' value='$id'>
                            <input type='number' class='' value='$quantity' min='1' max='5' style='width:60px;' name='quantity'>
                            <input type='submit' value='Update'>
                        </form>";
                        
                        $sel_color_mat = "
                        <form style='display:inline' method='GET'>
                            <input type='hidden' name='view' value='$view'>
                            <input type='hidden' name='action' value='cm'>
                            <input type='hidden' name='id' value='$id'>
                            <input type='text' class='' value='$color_mat' name='color_mat'>
                            <input type='submit' value='Update'>
                        </form>";
                        
                        $tbody .= "<tr>
                                     <td>$idx.</td>
                                     <td>
                                         <a href='$img1' data-title=\"$title\" class='fancybox'><img src='$img1' style='width:80px' class='img-thumbnail'></a>
                                     </td>
                                     <td>
                                         <b><a href='?view=item&id=$id'>$title</a></b> <!--<BR> 
                                         <small>$desc</small>-->
                                         <BR>
                                         $action
                                     </td>
                                     <td>$sel_quantity</td>
                                     <td>$sel_color_mat</td>
                                   <tr>";
                                   
                        $total +=  floatval($grand);            
                    }

                    // sum up
                    $tbody .= "<tr>
                                <td colspan='3'></td>
                                <td><b>Total:</b> $quant Items</td>
                                <td></td>
                                <td>
                                </td>
                               </tr>";   
                               
                    echo "<h3><a href='#' onclick='return validate_submit2()' class='pull-right btn btn-success' $button_request_state>$fa_cart Request Your Quote Now</a></h3>
                        <BR>
                         <HR>
                           <div class='table-responsive'>
                            <table class='table table-hover'>
                                <thead>
                                 $thead
                                </thead>
                                <tbody>
                                    $tbody
                                </tbody>
                            </table>
                           </div>
                           ";
                }
             }
        ?>
        
    </div>
</div>

<script>
    function validate_submit2(){
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
    	
    	$('#request_quote').modal();
    	return false;
    }          
</script>

<div class="modal fade product_view" id="request_quote">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" data-dismiss="modal" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                <h5 class="modal-title">Request Quotation</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><span class='fa fa-fw fa-check'></span> You are now ready to request a quote for the items in your quote cart.</p>
                        <HR>
                        <h5>Payment & Shipping Terms</h5>
                        <p>We will process your selection and generate your quote.</p>
                        <p>The processed quote will be sent to your email address.</p>
                        <p>Pieces will be delivered to your shipping address, and times of delivery will be communicated within the quote due to the variations in production times.</p>
                    </div>
                </div>
                <HR>
                <div class="row">
                    <div class="col-md-6">
                        <a href='#' onclick="$('#request_quote').modal('hide'); return false;" class='btn btn-warning 
                        text-center btn-block'><span class='fa fa-fw fa-remove'></span> Let me double check my cart</a>
                    </div>   
                    <div class="col-md-6">
                        <a href='?view=cart&action=dispatch-quote' class='btn btn-success text-center btn-block'><span class='fa fa-fw fa-check'></span> Proceed</a>
                    </div>                    
                </div>                
            </div>
        </div>
    </div>
</div>

<script>
$('#product_view').on('shown.bs.modal', function(e) {
    //get data-id attribute of the clicked element
    //var image = $(e.relatedTarget).data('product-image');
    
    //populate the textbox
    //$(e.currentTarget).find('input[name="bookId"]').val(bookId);
    console.log('modal shown', image, url, desc, title, cost);
});    

$('#product_view').on('hidden.bs.modal', function(e) {
    console.log('modal hidden');
}); 

$('.panel-body').on('show.bs.collapse', function(e){
   console.log('collapse shown'); 
   $('.panel-heading > h5 > span').removeClass('fa fa-chevron-up').addClass('fa fa-chevron-down');
});

$('.panel-body').on('hidden.bs.collapse', function(e){
   console.log('collapse hidden'); 
   $('.panel-heading > h5 > span').removeClass('fa fa-chevron-down').addClass('fa fa-chevron-up');
});
</script>