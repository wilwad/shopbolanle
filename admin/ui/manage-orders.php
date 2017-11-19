<style>
    .stepwizard-step p {
        margin-top: 10px;    
    }
    
    .stepwizard-row {
        display: table-row;
    }
    
    .stepwizard {
        display: table;     
        width: 100%;
        position: relative;
    }
    
    .stepwizard-step button[disabled] {
        opacity: 1 !important;
        filter: alpha(opacity=100) !important;
    }
    
    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-order: 0;
        
    }
    
    .stepwizard-step {    
        display: table-cell;
        text-align: center;
        position: relative;
    }
    
    .btn-circle {
      width: 30px;
      height: 30px;
      text-align: center;
      padding: 6px 0;
      font-size: 12px;
      line-height: 1.428571429;
      border-radius: 15px;
    }    
</style>
<?php
 // parameters
 $action = @ $_GET['action'];
 $id = intval(@ $_GET['id']);
 $extra = (int) @ $_GET['extra'];
 $request = @ $_GET['request'];
 
    // defs
    $currency = settings::currency;
    
	// icons	
	$fa_icon     = font_awesome('fa-dashboard');
	$fa_edit     = font_awesome('fa-edit');
	$fa_bank     = font_awesome('fa-bank');
	$fa_remove   = font_awesome('fa-remove');
	$fa_trash    = font_awesome('fa-trash');
	$fa_user     = font_awesome('fa-user');
	$fa_calendar = font_awesome('fa-calendar');
	$fa_back     = font_awesome('fa-arrow-left');
	$fa_language = font_awesome('fa-language');
	$fa_envelope = font_awesome('fa-envelope-o');
	$fa_floppy   = font_awesome('fa-floppy-o');
	$fa_eye      = font_awesome('fa-eye');
	$fa_airplane = font_awesome('fa-paper-plane');
	$fa_plane    = font_awesome('fa-plane');
	$fa_list     = font_awesome('fa-list');
	$fa_map      = font_awesome('fa-map-marker');
	$fa_list     = font_awesome('fa-list');
	$fa_usd      = font_awesome('fa-usd');
	$fa_file     = font_awesome('fa-file');
	$fa_exclaim  = font_awesome('fa-exclamation-triangle');
	$fa_check    = font_awesome('fa-check');
	
    $business_address = settings::business_address;
                        	
 switch ($action){
     case 'edit':
	echo "<h4>$fa_edit Edit request for quotation 
          <small class='pull-right'><a href='?view=$view'>$fa_back Back to all requests for quotations</a></small></h4><HR>";  
	
				/* editing an application */
				$disable_editing = "";				

			$application_status_text = "";
			$application_notification = "";

			switch ($request){
			    case 'send-quote':
			        $shipping_details = "";
			        $total_shipping   = 0;
			        $quote_items      = "";
			        $quote_items_     = "";
			        
			        /* get hash for quote */
			        $sql = "SELECT 
			                        hash, sales_tax
			                FROM 
			                        quotes 
			                WHERE id='$id';";
			        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			        if (!$ret || !$ret->num_rows){
			            die(alertbuilder("No hash found for quote. Make sure the quote still exists. Client may have declined it.",'danger'));
			        }
			        
			        $row       = $ret->fetch_array();
			        $hash      = $row['hash'];
			        $sales_tax = $row['sales_tax'];
			        
			    	/* get the shipping address for quote */
			    	$sql = "SELECT 
			    	                full_name, 
			    	                email,
			    	                cellphone,
			    	                physical_address,
			    	                apt_suit,
			    	                city,
			    	                lc.name AS country
			    	        FROM 
			    	                shipping_address sa,
			    	                list_countries lc
			    	        WHERE 
			    	                lc.id = sa.country_id AND
			    	                sa.quote_id=$id;";
			    	$ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			    	if (!$ret || !$ret->num_rows){
			    	   die(alertbuilder("The quote shipping has no valid email address.",'danger'));
			    	}
			    	
			    	$max = $ret->num_rows;
			    	
			    	$row             = $ret->fetch_array();
			    	$fullname        = $row['full_name'];
			    	$email           = $row['email'];
			    	$cellphone       = $row['cellphone'];
			    	$physicaladdress = $row['physical_address'];
			    	$aptsuite        =  $row['apt_suit'];
			    	$city            =  $row['city'];
			    	$country         =  $row['country'];
			    	
                	$shipping_details = "<tr>
                	                        <th style='padding:8px;'>Full name</th><td style='padding:8px;'>$fullname</td>
                	                        </tr>
                	<tr><th style='padding:8px;'>Email</th><td style='padding:5px;'>$email</td></tr>
                			             <tr><th style='padding:8px;'>Cellphone</th><td style='padding:8px;'>$cellphone</td></tr>
                			             <tr><th style='padding:8px;'>Address</th><td style='padding:8px;'>$physicaladdress</td></tr>
                			             <tr><th style='padding:8px;'>Apt, suite. etc</th><td style='padding:8px;'>$aptsuite</td></tr>
                			             <tr><th style='padding:8px;'>City</th><td style='padding:8px;'>$city</td></tr>
                			             <tr><th style='padding:8px;'>Country</th><td style='padding:8px;'>$country</td></tr>";  
    			             
    			    /* quote headers */
                  $quote_headers = "<th>Item</td>
                    				<th>Quantity</td>
                    				<th>Unit Price</td>
                    				<th>Shipping</td>
                    				<th>Total</td>";  
                    				
			        /* send quote email to client */
			        $sql = "SELECT 
			                        si.title, 
			                        qi.price, 
			                        qi.quantity, 
			                        qi.shipping, 
			                        qi.specifications 
                            FROM 
                                    `quote_items` qi,
                                    store_items si 
                            WHERE 
                                    si.id = qi.item_id AND
                                    qi.quote_id = $id;";
			        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			        
                   if (!$ret || !$ret->num_rows){
                        // nothing to show
                        echo alertbuilder("There are no items in the quote.",'danger');
                    } else {
                        $idx = 0;
                        $total = 0;
                        
                        while ($row = $ret->fetch_array()){
                            $idx++;
                            
                            $title    = $row['title'];
                            $price    = $row['price'];
                            $shipping = $row['shipping'];
                            $total_shipping += $shipping;
                            
                            $quantity = $row['quantity'];
                            $specs    = $row['specifications'];
                            $grand    = ($price + $shipping) * $quantity;
                            $total   += $grand;
                            
                            $quote_items .= "<tr>
                                             <td style='padding:5px;'>$idx.</td>
                                             <td style='padding:5px;'>
                                                 <b>$title</b>
                                             </td>
                                             <td style='padding:5px;'>$specs</td>
                                             <td style='padding:5px;'>$quantity</td>
                                             <td style='padding:5px;'>$currency $price</td>     
                                             <td style='padding:5px;'>$currency $shipping</td>
                                             <td style='padding:5px;'>$currency $grand</td>
                                           <tr>";

                            $last = $idx == $max ? 'last' : '';
                            
                			$quote_items_ .= "<tr class='item $last'>
                                				<td>$title</td>
                                				<td>$quantity</td>
                                				<td>$currency $price</td>
                                				<td>$currency $shipping</td>
                                                <td>$currency $grand</td>
                                			</tr>";
                        }
   
                        $grandtotal = $sales_tax + $total;
                        
                        $quote_items_ .= "<tr>
                                          <td></td>
                                          <td></td>
                                          <td><b>Total Shipping</b></td>
                                          <td>$currency $total_shipping</td>
                                          <td></td>
                                         </tr>
                                         <tr>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td><b>Subtotal</b></td>
                                          <td style='border: 1px solid #ccc;'>$currency $total</td>
                                         </tr>
                                         <tr>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td><b>Sales Tax</b></td>
                                          <td style='border: 1px solid #ccc;'>$currency $sales_tax</td>
                                         </tr>
                                         <tr>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td><b>Total</b></td>
                                          <td style='border: 1px solid #ccc;'><u>$currency $grandtotal</u></td>
                                         </tr>                                         
                                         ";
                                       
                        $date = date('Y-m-d H:i:s');
                        $date_= date('Y-m-d');
                        $mtrand = mt_rand();
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
                                    	
                                        tr:nth-child(odd) {background: #CCC}
                                        tr:nth-child(even) {background: #FFF}
                                    	
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
                                    	
                                    	.invoice-box table tr.total td:nth-child(1){
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
                                          <img src='https://www.shopbolanle.com/images/navbrand-bolanle-200px.jpg' style=''>
                                          
                                            <h2 style='text-transform: uppercase;'>Price Quote</h2>
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
                                          
                                          <!--<BR>
                                    			Invoice #: $id<br>
                                    			Created: $date_<br>
                                    			Due: N/A      
                                         <BR>
                                         -->
                                          <HR>
                                          <BR>
                                            <table class='table table-striped'>
                                             <thead>
                                              <tr style='background: #786e53; color: #FFF; padding: 7px;text-align: center;'>
                                                 $quote_headers
                                                </tr>
                                             </thead>
                                             
                                             <tbody>
                                                    $quote_items_
                                             </tbody>
                                            </table>
                                    
                                            <p>
                                            <HR>
                                            <a href='https://www.shopbolanle.com/?view=quote&action=accept&hash=$hash&t=$mtrand' style='color:green; text-decoration:none'>Click here to accept this quote and pay</a> <BR>
                                            <a href='https://www.shopbolanle.com/?view=quote&action=decline&hash=$hash&t=$mtrand' style='color:red; text-decoration:none'>Click here to decline this quote</a>
                                            </p> 
                                            <p>
                                             <small style='color: gray'>
                                             Quotation prepared by: $username <BR>
This is a quotation on the goods named, subject to the quote being accepted before the expiration date.
                                             </small>
                                            </p>
                                            <p style='font-style:italic; color:#4b7b8a;'>Thank you for your business!</p>
                                            
                                            <p><small><b>Website:</b> https://www.shopbolanle.com</small>
                                            <BR>
                                            <small><b>Payment & Shipping terms</b> https://www.shopbolanle.com/?view=shipping&t=$mtrand</small></p>      
                                    </div>
                                     </body>
                                    </html>";

                   // die($message);

                       /* start */
                       date_default_timezone_set('Africa/Windhoek');
                       
            			//send email
            			$date    = date('Y-m-d H:i:s');
            			$headers = "From: orders@shopbolanle.com\r\n";
            			$headers .= "Reply-To: orders@shopbolanle.com\r\n";
            			//$headers .= "CC: william.sengdara@gmail.com\r\n";
            			$headers .= "MIME-Version: 1.0\r\n";
            			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
            			$subject = "Your Quotation";
            			
                       mail($email,$subject, $message, $headers);
                       
                       $sql = "UPDATE quotes 
                               SET date_quote_sent='$date'
                               WHERE id=$id;";
                       $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
                    }
                
			        /* save to emails sent */
			        $date = date('Y-m-d H:i:s');
			        
			        $sql = "INSERT INTO quote_emails(quote_id,entrydate,user_id,subject,body)
			        VALUES($id,'$date',$userid,'Prepared Quotation','The quotation with figures attached was sent to the client.');";
			        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));

			        echo alertbuilder("$fa_check The prepared quotation has been sent to the client (<abbr class='timeago' title='$date'>$date</abbr>).",'success');
			        
			        echo "<script>
			              /* reload the page to get rid of request=send-quote */
			              window.setTimeout(function(){
			                 window.location.href = '?view=manage-orders&action=edit&id=$id&tab=tabquoteitems'; 
			              }, 5000);
			              </script>";
			        break;
			        
			    case 'send-shipping':
			        $shipping_details = "";
			        $total_shipping   = 0;
			        $quote_items      = "";
			        $quote_items_     = "";
			        
			        /* get hash for quote */
			        $sql = "SELECT 
			                        hash, sales_tax
			                FROM 
			                        quotes 
			                WHERE id='$id';";
			        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			        if (!$ret || !$ret->num_rows){
			            die(alertbuilder("No hash found for quote. Make sure the quote still exists. Client may have declined it.",'danger'));
			        }
			        
			        $row       = $ret->fetch_array();
			        $hash      = $row['hash'];
			        $sales_tax = $row['sales_tax'];
			        
			    	/* get the shipping address for quote */
			    	$sql = "SELECT 
			    	                full_name, 
			    	                email,
			    	                cellphone,
			    	                physical_address,
			    	                apt_suit,
			    	                city,
			    	                lc.name AS country
			    	        FROM 
			    	                shipping_address sa,
			    	                list_countries lc
			    	        WHERE 
			    	                lc.id = sa.country_id AND
			    	                sa.quote_id=$id;";
			    	$ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			    	if (!$ret || !$ret->num_rows){
			    	   die(alertbuilder("The quote shipping has no valid email address.",'danger'));
			    	}
			    	
			    	$max = $ret->num_rows;
			    	
			    	$row             = $ret->fetch_array();
			    	$fullname        = $row['full_name'];
			    	$email           = $row['email'];
			    	$cellphone       = $row['cellphone'];
			    	$physicaladdress = $row['physical_address'];
			    	$aptsuite        =  $row['apt_suit'];
			    	$city            =  $row['city'];
			    	$country         =  $row['country'];
			    	
                	$shipping_details = "<tr>
                	                        <th style='padding:8px;'>Full name</th><td style='padding:8px;'>$fullname</td>
                	                        </tr>
                	<tr><th style='padding:8px;'>Email</th><td style='padding:5px;'>$email</td></tr>
                			             <tr><th style='padding:8px;'>Cellphone</th><td style='padding:8px;'>$cellphone</td></tr>
                			             <tr><th style='padding:8px;'>Address</th><td style='padding:8px;'>$physicaladdress</td></tr>
                			             <tr><th style='padding:8px;'>Apt, suite. etc</th><td style='padding:8px;'>$aptsuite</td></tr>
                			             <tr><th style='padding:8px;'>City</th><td style='padding:8px;'>$city</td></tr>
                			             <tr><th style='padding:8px;'>Country</th><td style='padding:8px;'>$country</td></tr>";  
    			             
    			    /* quote headers */
                  $quote_headers = "<th>Item</td>
                    				<th>Quantity</td>
                    				<th>Unit Price</td>
                    				<th>Shipping</td>
                    				<th>Total</td>";  
                    				
			        /* send quote email to client */
			        $sql = "SELECT 
			                        si.title, 
			                        qi.price, 
			                        qi.quantity, 
			                        qi.shipping, 
			                        qi.specifications 
                            FROM 
                                    `quote_items` qi,
                                    store_items si 
                            WHERE 
                                    si.id = qi.item_id AND
                                    qi.quote_id = $id;";
			        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			        
                   if (!$ret || !$ret->num_rows){
                        // nothing to show
                        echo alertbuilder("There are no items in the quote.",'danger');
                    } else {
                        $idx = 0;
                        $total = 0;
                        
                        while ($row = $ret->fetch_array()){
                            $idx++;
                            
                            $title    = $row['title'];
                            $price    = $row['price'];
                            $shipping = $row['shipping'];
                            $total_shipping += $shipping;
                            
                            $quantity = $row['quantity'];
                            $specs    = $row['specifications'];
                            $grand    = ($price + $shipping) * $quantity;
                            $total   += $grand;
                            
                            $quote_items .= "<tr>
                                             <td style='padding:5px;'>$idx.</td>
                                             <td style='padding:5px;'>
                                                 <b>$title</b>
                                             </td>
                                             <td style='padding:5px;'>$specs</td>
                                             <td style='padding:5px;'>$quantity</td>
                                             <td style='padding:5px;'>$currency $price</td>     
                                             <td style='padding:5px;'>$currency $shipping</td>
                                             <td style='padding:5px;'>$currency $grand</td>
                                           <tr>";

                            $last = $idx == $max ? 'last' : '';
                            
                			$quote_items_ .= "<tr class='item $last'>
                                				<td>$title</td>
                                				<td>$quantity</td>
                                				<td>$currency $price</td>
                                				<td>$currency $shipping</td>
                                                <td>$currency $grand</td>
                                			</tr>";
                        }
   
                        $grandtotal = $sales_tax + $total;
                        
                        $quote_items_ .= "<tr>
                                          <td></td>
                                          <td></td>
                                          <td><b>Total Shipping</b></td>
                                          <td>$currency $total_shipping</td>
                                          <td></td>
                                         </tr>
                                         <tr>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td><b>Subtotal</b></td>
                                          <td style='border: 1px solid #ccc;'>$currency $total</td>
                                         </tr>
                                         <tr>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td><b>Sales Tax</b></td>
                                          <td style='border: 1px solid #ccc;'>$currency $sales_tax</td>
                                         </tr>
                                         <tr>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td><b>Total</b></td>
                                          <td style='border: 1px solid #ccc;'><u>$currency $grandtotal</u></td>
                                         </tr>                                         
                                         ";
                                       
                        $date = date('Y-m-d H:i:s');
                        $date_= date('Y-m-d');
                        $mtrand = mt_rand();
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
                                    	
                                        tr:nth-child(odd) {background: #CCC}
                                        tr:nth-child(even) {background: #FFF}
                                    	
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
                                    	
                                    	.invoice-box table tr.total td:nth-child(1){
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
                                          <img src='https://www.shopbolanle.com/images/navbrand-bolanle-200px.jpg' style=''>
                                          
                                            <h2 style='text-transform: uppercase;'>This is a notice that your item(s) have been shipped.</h2>
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
                                          
                                          <!--<BR>
                                    			Invoice #: $id<br>
                                    			Created: $date_<br>
                                    			Due: N/A      
                                         <BR>
                                         -->
                                          <HR>
                                          <BR>
                                            <table class='table table-striped'>
                                             <thead>
                                              <tr style='background: #786e53; color: #FFF; padding: 7px;text-align: center;'>
                                                 $quote_headers
                                                </tr>
                                             </thead>
                                             
                                             <tbody>
                                                    $quote_items_
                                             </tbody>
                                            </table>

                                            <HR>
                                            <p>
                                            <a href='https://www.shopbolanle.com/?view=quote&action=item-received&hash=$hash&t=$mtrand' style='color:green; text-decoration:none'>Click here to confirm once you have received your item(s)</a>
                                            </p> 
                                            <p>
                                             <small style='color: gray'>
                                             Notification prepared by: $username
                                             </small>
                                            </p>
                                            <p style='font-style:italic; color:#4b7b8a;'>Thank you for your business!</p>
                                            
                                            <p><small><b>Website:</b> https://www.shopbolanle.com</small>
                                            <BR>
                                            <small><b>Payment & Shipping terms</b> https://www.shopbolanle.com/?view=shipping&t=$mtrand</small></p>      
                                    </div>
                                     </body>
                                    </html>";

                   // die($message);

                       /* start */
                       date_default_timezone_set('Africa/Windhoek');
                       
            			//send email
            			$date    = date('Y-m-d H:i:s');
            			$headers = "From: orders@shopbolanle.com\r\n";
            			$headers .= "Reply-To: orders@shopbolanle.com\r\n";
            			//$headers .= "CC: william.sengdara@gmail.com\r\n";
            			$headers .= "MIME-Version: 1.0\r\n";
            			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
            			$subject = "Your item(s) have been shipped";
            			
                       mail($email,$subject, $message, $headers);
                       
                       $sql = "UPDATE quotes 
                               SET date_item_shipped='$date'
                               WHERE id=$id;";
                       $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
                    }
                
			        /* save to emails sent */
			        $date = date('Y-m-d H:i:s');
			        
			        $sql = "INSERT INTO quote_emails(quote_id,entrydate,user_id,subject,body)
			        VALUES($id,'$date',$userid,'Item(s) shipped','The notification that item(s) have been shipped was sent to the client.');";
			        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			        
			        
			        echo alertbuilder("$fa_check The notification that item(s) have been shipped was sent to the client (<abbr class='timeago' title='$date'>$date</abbr>).",'success');
			        
			        echo "<script>
			              /* reload the page to get rid of request=send-quote */
			              window.setTimeout(function(){
			                 window.location.href = '?view=manage-orders&action=edit&id=$id&tab=tabquoteitems'; 
			              }, 5000);
			              </script>";
			        break;			        
			}
			
				$application =  "<button class='btn btn-sm btn-success'><li class='fa fa-fw fa-floppy-o'></p>&nbsp;Save changes</button>
									  <small class='pull-right'><i>Please note: when this application is under review, this option will not be available.</i></small>";
				
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
							}); 

						   </script>";

				$sql = "SELECT 
				                *
						  FROM 
									shipping_address sa
						  WHERE 
								sa.quote_id = $id;";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
					die(alertbuilder("Error #0: Unable to locate quote identified by id $id.",'danger'));
										
				while ( $row = $ret->fetch_array() ){	
					while ($columns = $ret->fetch_field()){
							 $col = $columns->name;
					 		 $_POST[$col] = $row[$col];							
					}			
				}
				
				$entrydate = @ $_POST['entrydate'];
				$application_notification = "";				
				$body_section_a = "";
				$errors_buffer = "";
			
			$required = array('email',
			                  'full_name',
			                  'cellphone',
			                  'physical_address',
			                  'city',
			                  'country_id'
			                  );
			                  				
			                  				
				$buffer = build_form('shipping_address',$required);	
				$body_shipping = "<form class='form-horizontal' role='form'>
				                    $errors_buffer
									     $buffer
				                   </form>";

			$required = array('email',
			                  'full_name',
			                  'cellphone',
			                  'physical_address',
			                  'city',
			                  'country_id'
			                  );
			                  				
			                  				
				$sql = "SELECT 
				                *
					  FROM 
								quotes q
					  WHERE 
							q.id = $id
					  LIMIT 1;";

				$ret = $database->query($sql);
				if (!$ret || !$ret->num_rows)
					die(alertbuilder("Error #0: Unable to locate quote identified by id $id.",'danger'));
										
				$row = $ret->fetch_array();
				$quote_id = $row['id'];
				$quote_entrydate = $row['entrydate'];
				$quote_entrydate = "<abbr class='timeago' title='$quote_entrydate'>$quote_entrydate</abbr>";
				$quote_hash = $row['hash'];
				$quote_date_sent = $row['date_quote_sent'];
				$quote_date_sent_ = $quote_date_sent;
				$quote_date_sent = "<abbr class='timeago' title='$quote_date_sent '>$quote_date_sent </abbr>";
				
				$quote_date_accepted = $row['date_quote_accepted'];
				$quote_date_accepted_ = $quote_date_accepted;
				
				$quote_date_accepted = "<abbr class='timeago' title='$quote_date_accepted'>$quote_date_accepted</abbr>";

                $date_payment_made_ = $row['date_payment_made'];
                $disabled_shipping_notice = strtolower($date_payment_made_) == 'pending' ? "disabled" : "";
                
                $date_payment_made__ = "<abbr class='timeago' title='$date_payment_made_'>$date_payment_made_</abbr>";
                
                $date_item_shipped_ = $row['date_item_shipped'];
                $date_item_shipped__ = "<abbr class='timeago' title='$date_item_shipped_'>$date_item_shipped_</abbr>";
                
                $date_item_received_= $row['date_item_received'];
                $date_item_received__ = "<abbr class='timeago' title='$date_item_received_'>$date_item_received_</abbr>";
                
                $sales_tax          = $row['sales_tax'];
                
				$body_quote = "<div class='table-responsive'>
				                <table class='table table-hover'>
				                 <tbody>
				                  <tr><th>Date quote requested</th><td>$quote_entrydate</td></tr>
				                  <tr><th>Customer Hash</th><td>$quote_hash</td></tr>
				                  <tr><th>Date prepared quote sent</th><td>$quote_date_sent</td></tr>
				                  <tr><th>Date prepared quote accepted</th><td>$quote_date_accepted</td></tr>
				                  <tr><th>Date payment made</th><td>$date_payment_made__</td></tr>
				                  <tr><th>Date item shipped</th><td>$date_item_shipped__</td></tr>		
				                  <tr><th>Date item received</th><td>$date_item_received__</td></tr>
				                 </tbody>
				                </table>
				               </div>";

                /* quoted items */
                $head_quote_items = "<tr>
                                       <th>#</td>
                                       <th>Description</th>
                                       <th>Quantity</th>
                                       <th>Unit Price</th>
                                       <th>Shipping</th>
                                       <th>Total</th>
                                       <th>Actions</th>
                                      </tr>";
                          
                $body_quote_items = "<tr>
                                        <td colspan='7' class='text-center'>No quote items.
                                        </td>
                                      </tr>";
                $total_quote_items = 0;
                
                $sql = "SELECT  
                                qi.item_id,
                                si.title,
                                si.url_image_1,
                                si.description,
                                qi.price,
                                qi.quantity,
                                qi.shipping,
                                qi.specifications
                        FROM quote_items qi,
                             store_items si
                        WHERE 
                             qi.quote_id = $id AND
                             si.id = qi.item_id";
                $ret = $database->query($sql) or die($database->error);
                if (!$ret || !$ret->num_rows){
                    // nothing
                } else {
                    $currency = settings::currency;
                    $total_shipping = 0;
                    $total_price    = 0;
                    
                    $total_quote_items = 1;
                    $body_quote_items = "";
                    
                    while ($row = $ret->fetch_array()){
                         $itemid = $row['item_id'];
                         
                         $actions = "<a href='#' onclick=\"confirmdelete('quote_items',$itemid); 
                         return false;\" style='color:red'>$fa_trash Delete item</a>";
                         $title       = $row['title'];
                         $url_image_1 = $row['url_image_1'];
                         $description = $row['description'];
                         $quantity    = $row['quantity'];
                         $price       = $row['price'];
                         $shipping    = $row['shipping'];
                         
                         $total_shipping += floatval($shipping);
                         $total       = (floatval($price) + floatval($shipping)) * intval($quantity);
                         $total_price    += $total;
                         
                         $body_quote_items .= "<tr>
                                                <td>$total_quote_items</td>
                                                <td><b>$title</b><BR>
                                                 <a href='$url_image_1' class='fancybox btn btn-default' 
                                                 rel='images' data-title=\"$title\"><img src='$url_image_1' class='' style='width:80px'></a>
                                                 </td>
                                                <td>$quantity <a href='#' onclick=\"alter_quote('quantity',$itemid); return false;\" 
                                                class='pull-right'>$fa_edit Edit</a></td>
                                                <td>$currency $price <a href='#' 
                                                onclick=\"alter_quote('price',$itemid); return false;\" class='pull-right'>$fa_edit Edit</a></td>                                                
                                                <td>$currency $shipping 
                                                <a href='#' onclick=\"alter_quote('shipping',$itemid); return false;\" 
                                                class='pull-right'>$fa_edit Edit</a></td>
                                                
                                                <td>$currency $total</td>
                                                <td>$actions</td>
                                               </tr>";   
                         $total_quote_items++;
                    }
                    
                    $total_quote_items--;
                    $grandtotal = $sales_tax + $total_price;
                    
                    $body_quote_items .= "<tr>
                                           <td colspan='3'></td>
                                           <td>
                                             <small>Sales Tax</small> <a href='#' onclick=\"alter_sales_tax($id); return false;\" 
                                                class='pull-right'>$fa_edit Edit</a><BR>
                                             <h3>$currency $sales_tax</h3>
                                           </td>
                                            <td>
                                            <small>Total Shipping</small><BR>
                                            <h3>$currency $total_shipping</h3></td>
                                           <td>
                                           <small>Subtotal</small><BR>
                                           <h3>$currency $total_price</h3></td>
                                            <td>
                                           <small>Total</small><BR>
                                           <h3 style='text-decoration:underline;'>$currency $grandtotal</h3></td> 
                                          </tr>";
                }
                
                /* alerts */
                $total_emails_sent = 0;
                $datatable_emails = "";
                
                $head_alerts = "<tr>
                                   <th>#</th>
                                   <th>Entry date</th>
                                   <th>To</th>
                                   <th>Message</th>
                                   <th>Sent by</th>
                                   <th>Actions</th>
                                  </tr>";
                          
                $body_alerts = "<tr>
                                <td colspan='6' class='text-center'>No emails sent to client.
                                </td>
                                </tr>";
                                
                $sql = "SELECT 
                                sa.full_name,
                                sa.email,
                                qe.id,
                                qe.entrydate,
                                qe.subject,
                                qe.body,
                                u.user_name
                        FROM quote_emails qe,
                             users u,
                             shipping_address sa
                        WHERE 
                              qe.quote_id=$id AND
                              u.id = qe.user_id AND
                              sa.quote_id = qe.quote_id
                        ORDER BY 
                              qe.id DESC;";
                $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
                if (!$ret || !$ret->num_rows){
                    // nothing
                } else {
                    $body_alerts = "";
                    $idx = 1;
                    
                    while ($row = $ret->fetch_array()){
                        $total_emails_sent++;
                        $email     = $row['email'];
                        $fullname  = $row['full_name'];
                        $emailid   = $row['id'];
                        $entrydate = $row['entrydate'];
                        $entrydate = "<abbr class='timeago' 
                        title='$entrydate'>$entrydate</abbr>";
                        $subject   = $row['subject'];
                        $body      = $row['body'];
                        $username  = $row['user_name'];
                        
                        $actions = "<a href='#' style='color:red' onclick=\"confirmdelete('quote_emails',$emailid); return false;\">$fa_trash Delete</a>";

                        $body_alerts .= "<tr>
                                          <td>$idx</td>
                                          <td>$entrydate</td>
                                          <td>$fa_user $fullname <BR>
                                           <small>$email</small>
                                          </td>
                                          <td>$fa_envelope <strong>$subject</strong><BR>
                                          <small>$body</small>
                                          </td>
                                          <td>$fa_user $username</td>
                                          <td>$actions</td>
                                         </tr>";
                        $idx++;
                    }
                    
                    $datatable_emails = "$('#tableemails').dataTable();";
                }
 
                $total_payments     = 0;
                $payments_datatable = "";
                $payments_head = "<tr>
                                   <th>#</th>
                                   <th>Entrydate</th>
                                   <th>Amount</th>
                                   <th>Paypal Payment Id</th>
                                  </tr>";
                $payments_body = "<tr>
                                   <td colspan='5' class='text-center'><i>No payments.</i></td>
                                  </tr>";
                $sql = "SELECT 
                                *
                        FROM payments
                        WHERE 
                              quote_id = $id
                        ORDER BY 
                              entrydate DESC
                        LIMIT 5;";
                $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
                if (!$ret || !$ret->num_rows){
                    // nothing
                } else {
                    $payments_body = "";
                    $idx = 1;
                    
                    while ($row = $ret->fetch_array()){
                        $total_payments++;
                        $entrydate = $row['entrydate'];
                        $entrydate = "<abbr class='timeago' 
                        title='$entrydate'>$entrydate</abbr>";
                        $amount    = $row['amount'];
                        $amount    = number_format($amount,2);
                        $pppid     = $row['paypal_paymentid'];

                        $payments_body .= "<tr>
                                          <td>$idx</td>
                                          <td>$entrydate</td>
                                          <td>$currency $amount</td>
                                          <td>$pppid</td>
                                         </tr>";
                        $idx++;
                    }
                    
                    $payments_datatable = "$('#tablepayments').dataTable();";
                }
                
                $arr_wiz_steps = array("Quote Request"=>true,
                                       "Quote Prepared"=>($quote_date_sent_ == 'Pending' ? false : true),
                                       "Quote Accepted"=>($quote_date_accepted_ == 'Pending' ? false : true),
                                       "Payment"=>($date_payment_made_ == 'Pending' ? false : true),
                                       "Item Shipped"=>($date_item_shipped_ == 'Pending' ? false : true),
                                       "Item Received"=>($date_item_received_ == 'Pending' ? false : true)
                                        );
                $wizard_steps = "";
                $idx = 1;
                
                foreach ($arr_wiz_steps as $key=>$val){
                         $btn = ($val == true) ? "btn-primary": "btn-default";
                         
                         $wizard_steps .= "<div class='stepwizard-step'>
                                                <button type='button' class='btn $btn btn-circle' disabled='disabled'>$idx</button>
                                                <p>$key</p>
                                            </div>";
                         $idx++;
                }
                
				echo "$style
					$application_notification
					
					<ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>
					<li class='active'><a href='#tabquote' data-toggle='tab'>$fa_file Quote Details</a></li>
					<li><a href='#tabquoteitems' 
					data-toggle='tab'>$fa_list Quote Items <span class='badge'>$total_quote_items</span></a></li>		
		    <li><a href='#tabshipping' data-toggle='tab'>$fa_map Shipping Details</a></li>
                    <li><a href='#tabalerts' data-toggle='tab'>$fa_envelope Emails sent to client <span class='badge'>$total_emails_sent</span></a></li>
                    <li><a href='#tabpayments' data-toggle='tab'>$fa_usd Payments <span class='badge'>$total_payments</span></a></li>                    
			 </ul>

			<div id='my-tab-content' class='tab-content'>
			   <BR>
				<div class='tab-pane active' id='tabquote'>
				<BR>
                    <div class='stepwizard'>
                        <div class='stepwizard-row'>
                            $wizard_steps                            
                        </div>
                    </div>
                    
                    <BR>				
					$body_quote
				</div>	

				<div class='tab-pane' id='tabpayments'>
				 <BR>
				 <div class='table-responsive'>
				  <table class='table table-hover table-bordered' id='tablepayments'>
				   <thead>
				    $payments_head
				   </thead>
				   <tbody>
				    $payments_body
				   </tbody>
				  </table>
				  <script>
				   $payments_datatable
				  </script>
				 </div>
				</div>
								
				<div class='tab-pane' id='tabshipping'>
				 <BR>
				 <form method='POST' class='form-horizontal' role='form'>
					<input type='hidden' name='view' value='application-unk'>
					<input type='hidden' name='action' value='edit'>
					<input type='hidden' name='extra' value='1'>
					$body_shipping	
						
					<!-- update button -->
					<!--
				        <div class='form-group'>
				         <label class='control-label col-sm-3'></label>
				         <div class='col-sm-8'>
				         <input type='submit' class='btn btn-success btn-sm' value='Update shipping'>
				         </div>
				        </div>
				    -->
				   </form>
				</div>				
                <script>
                    /* send a prepared quote to client */
                    var send_quote = function(){
                    BootstrapDialog.confirm(\"Are you sure you would like to send this quotation to the client?\", function(ans){
                        switch (ans){
                            case true:
                                window.location.href='?view=$view&action=edit&id=$id&tab=tabquoteitems&request=send-quote';
                                break;
                        }
                    });};
                
                    /* send a shipping noticed to client */
                    var send_shipping = function(){
                    BootstrapDialog.confirm(\"Are you sure you would like to notify the client that their item(s) have been shipped?\", function(ans){
                        switch (ans){
                            case true:
                                window.location.href='?view=$view&action=edit&id=$id&tab=tabquoteitems&request=send-shipping';
                                break;
                        }
                    });};
                    
                 var alter_quote = function (col,quoteid){
                   var val = prompt('Specify the new '+col+'?');
                   if (val != null){
                       console.log('Updated the value', val);
                       
                        var payload = {
                        'view': 'updatequotecol', 
		           'table':'quote_items',
		           'col':col,
		           'val':val,
				   'id':quoteid};
					  ajax('ui.php', 
					   'post',
					   'json', 
					   payload, 
					   function(data){
					       if (data.result == true){
					           window.location.href='?view=$view&action=edit&id=$id&tab=tabquoteitems';
					       } else {
					           alertify.error(data.status+'');
					           console.log(data.status);
					       }
					   },
					   funcError);                                       
           				}
                                 };
                
                var alter_sales_tax = function (quoteid){
                   var val = prompt('Specify the new sales tax');
                   if (val != null){
                       console.log('Updated the value', val);
                       
                        var payload = {
                        'view': 'updatesalestax', 
		           'table':'quotes',
		           'col':'sales_tax',
		           'val':val,
				   'id':quoteid};
				   
				   console.log(payload);
				   
					  ajax('ui.php', 
					   'post',
					   'json', 
					   payload, 
					   function(data){
					       if (data.result == true){
					           window.location.href='?view=$view&action=edit&id=$id&tab=tabquoteitems';
					       } else {
					           alertify.error(data.status+'');
					           console.log(data.status);
					       }
					   },
					   funcError);                                       
           				}
                                 };                
                </script>
								
				<div class='tab-pane' id='tabquoteitems'>
				 <BR>
				  <p>
				    <a href='#' onclick='send_quote(); return false;' class='btn btn-success'>$fa_airplane Send This Prepared Quotation to Customer</a>
				    <a href='#' onclick='send_shipping(); return false;' class='btn btn-primary' $disabled_shipping_notice>$fa_plane Send Shipping Notification</a>				    
				    </p>
				    <div class='table-responsive'>
				     <table class='table table-bordered table-hover' id='tablequotes'>
				      <thead>$head_quote_items</thead>
				      <tbody>$body_quote_items</tbody>
				     </table>
				    </div>	
				</div>
								
				<div class='tab-pane' id='tabalerts'>
				   <BR>
				    <div class='table-responsive'>
				     <table class='table table-bordered table-hover' id='tableemails'>
				      <thead>$head_alerts</thead>
				      <tbody>$body_alerts</tbody>
				     </table>
				    </div>	
				   </div>
				   <script>
                     $(document).ready(function(){
                         $datatable_emails
                     });				   
				   </script>
				   
				  </div>
				$script";
         break;
         
     default:
    /* requests quotes/orders */
    $datatable = "";
    
	$thead = "<tr>
	            <th>No.</th>
	            <th>Entrydate</th>
	            <th>Name</th>
	            <th>Country</th>
	            <th>Items</th>
	            <th>Quotation</th>
	            <th>Accepted</th>
	            <th>Payment</th>
	            <th>Shipped</th>
	            <th>Received</th>
	            <th>Actions</th>
	          </tr>";
	$tbody = "<tr><td colspan='11'>No requests for quotation.</td></tr>";

	$sql = "SELECT 
	                q.id,
	                q.entrydate,
	                q.date_quote_sent,
	                q.date_quote_accepted,
	                q.date_payment_made,
	                q.date_item_shipped,
	                q.date_item_received,
                    lc.name AS country,
                    sa.full_name,
                    (SELECT COUNT(qi.quote_id) FROM quote_items qi WHERE quote_id=q.id) AS total
	        FROM 
	                quotes q,
	                shipping_address sa,
	                list_countries lc	                
	        WHERE 
                    q.id = sa.quote_id AND
                    sa.country_id = lc.id
	        ORDER BY 
	             q.id DESC;";
	$ret = $database->query($sql) or die($database->error);
	if (!$ret || !$ret->num_rows){
	    
	} else {
	  $tbody = "";
	  $idx = 1;
	  
	  while ($row = $ret->fetch_array()){
	      $id        = $row['id'];
	      $entrydate = $row['entrydate'];
	      $entrydate = "<abbr class='timeago' title='$entrydate'>$entrydate</abbr>";
	      $fullname  = $row['full_name'];
	      $country   = $row['country'];
	      $total     = $row['total'];
	      $date_quote_sent = $row['date_quote_sent'];
	      $date_quote_sent = "<abbr class='timeago' title='$date_quote_sent'>$date_quote_sent</abbr>";
	      
	      $date_quote_accepted = $row['date_quote_accepted'];
	      $date_payment_made = $row['date_payment_made'];
	      $date_item_shipped = $row['date_item_shipped'];
	      $date_item_received = $row['date_item_received'];
	      
	      $color_accepted = strtolower($date_quote_accepted) == 'pending' ? '#f3f3f3' : 'lime';
	      $color_paid     = strtolower($date_payment_made) == 'pending' ? '#f3f3f3' : 'lime';
	      $color_shipped  = strtolower($date_item_shipped) == 'pending' ? '#f3f3f3' : 'lime';
	      $color_received = strtolower($date_item_received) == 'pending' ? '#f3f3f3' : 'lime';
	      
	      $date_quote_accepted = "<abbr class='timeago' title='$date_quote_accepted'>$date_quote_accepted</abbr>";
	      
	      $date_payment_made = "<abbr class='timeago' title='$date_payment_made'>$date_payment_made</abbr>";
	      
	      $date_item_shipped = "<abbr class='timeago' title='$date_item_shipped'>$date_item_shipped</abbr>";
	      
	      $bg = strtolower($date_item_received) == 'pending' ? "" : "rgba(61, 255, 116, 0.1)";
	      
	      $date_item_received = "<abbr class='timeago' title='$date_item_received'>$date_item_received</abbr>";
	      
          $actions   = "<a href='?view=manage-orders&action=edit&id=$id'>$fa_edit Edit</a><BR>
	      <a href='#' style='color:red' onclick=\"return confirmdelete('quotes',$id);\">$fa_trash Delete</a>";	   
	      
	      $tbody .= "<tr style='background:$bg'>
	                  <td>$idx</td>
	                  <td>$entrydate</td>
	                  <td>$fa_user $fullname</td>
	                  <td>$fa_language $country</td>
	                  <td>$total</td>
	                  <td>$date_quote_sent</td>
	                  <td style='background-color:$color_accepted'>$date_quote_accepted</td>
	                  <td style='background-color:$color_paid'>$date_payment_made</td>
	                  <td style='background-color:$color_shipped'>$date_item_shipped</td>
	                  <td style='background-color:$color_received'>$date_item_received</td>
	                  <td>$actions</td>
	                 </tr>";
	                 
	       $idx++;
	  } 
	  
	  $datatable = "$('#tablequotes').dataTable();";
	}
	
	echo "<h4>$fa_list Requests for quotations <small class='pull-right'>Quotations requested by prospective clients</small></h4><HR>";
	
	echo "<div class='row'>
	       <div class='col-md-12'>
	        <div class='table-responsive'>
	         <table class='table table-bordered table-hover' id='tablequotes'>
	          <thead>$thead</thead>
	          <tbody>$tbody</tbody>
	         </table>
	        </div>
	       </div>
	      </div>
	      <script>
	       $(document).ready(function(){
	           $datatable
	       });
	      </script>
	      ";             
         break;
 }
?>