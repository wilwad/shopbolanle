<?php
     date_default_timezone_set('Africa/Windhoek');
     // defaults
     $email_   = "orders@shopbolanle.com";
     $date = date("Y-m-d H:i:s");
?>
<div class='row'>
    <div class='col-md-12'>
      <?php
         $req = array("qid",
                      "pid",
                      "amount",
                      "payeremail",
                      "payerid");
         $allset = true;
         $failed = "";
         
         foreach ($req as $key){
             if ( !isset($_GET[$key]) || empty($_GET[$key])){
                 $allset = false;
                 $failed = $key;
                 break;
             }
         }
         
         if (!$allset){
             echo alertbuilder("Not all required parameters have been set", 'danger');
         } else {
             /* params */
             $qid        = intval($_GET['qid']);
             $pid        = trim(stripslashes($_GET['pid']));
             $payeremail = trim(stripslashes($_GET['payeremail']));
             $payerid    = trim(stripslashes($_GET['payerid']));
             $amount     = floatval($_GET['amount']);
             $quoteid    = $qid;
             
             /* check if this quote exists */
             $sql = "SELECT email,  date_payment_made 
                     FROM quotes q, shipping_address sa
                     WHERE sa.quote_id = q.id AND 
                           q.id=$qid;";
             $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
             if (!$ret || !$ret->num_rows){
                die(alertbuilder('No quote found for the payment. Please contact us.','danger'));    
             }
             
             $row = $ret->fetch_array();
             
             $date_payment_made = $row['date_payment_made'];
             $recipient_email   = $row['email'];
             
             /* check that payment has not already been made */
             if (strtolower($date_payment_made) === 'pending'){
                 // payment is still pending so its fine to proceed
             } else {
                 die(alertbuilder("Payment has already been made for that quote on $date_payment_made. Please contact us.",'danger'));
             }
             
             /* make sure we don't do double payments? */
             $sql = "SELECT * 
                     FROM payments 
                     WHERE quote_id=$quoteid;";
             $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
             
             if (!$ret || !$ret->num_rows){
                 // nothing found so it's fine
             } else {
                die(alertbuilder('Payment has already been made for that quote. Please contact us.','danger'));    
             }
             
             /* insert into payments */
             $sql = "INSERT INTO payments 
                    (quote_id, entrydate, amount, paypal_paymentid, paypal_payer_email)
                    VALUES
                    ($quoteid, 
                    '$date',
                    '$amount', 
                    '$pid',
                    '$payeremail');";

             $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
             
             /* update the date_payment_made */
             $sql = "UPDATE quotes 
                     SET date_payment_made='$date'
                     WHERE id=$quoteid;";
             $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
             
             /* Everything cool */

            $message_ = "";
            
			    /* quote headers */
              $quote_headers = "<th>Item</td>
                				<th>Quantity</td>
                				<th>Unit Price</td>
                				<th>Shipping</td>
                				<th>Total</td>";  

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
                                qi.quote_id = $quoteid;";
		        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
			       
			       $total_shipping = 0;
			       $total = 0;
			       $grand = 0;
			       $currency = "USD";
			       $quote_items = "";
			       $max = 0;
			       $quote_items_ = "";
			       $business_address = "";
			       $fullname = "";
			       $physicaladdress = "";
			       $aptsuite = "";
			       $city = "";
			       $country = "";
			       $id = 0;
			       $hash = "";
			       $sales_tax = 0;
			       
                   if (!$ret || !$ret->num_rows){

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
            			$message_ = "<!Doctype html>
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
                                            Thank you for your payment!
                                            </p> 
                                            <p>
                                             <small style='color: gray'>
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
            }
            
             /* send email to payer */
    		$headers_ = "From: orders@shopbolanle.com\r\n";
    		$headers_ .= "Reply-To: orders@shopbolanle.com\r\n";
    		//$headers_ .= "CC: ubahashipoke@gmail.com\r\n";
    		$headers_ .= "MIME-Version: 1.0\r\n";
    		
           $subject_ = "Thank you for your payment.";

           $message_ = "We have received your payment for the quotation."; 
           
           @ mail($recipient_email, $subject_, $message_, $headers_);
           
	        /* save to emails sent */
	        $date = date('Y-m-d H:i:s');
	        
	        $sql = "INSERT INTO quote_emails(quote_id,entrydate,user_id,subject,body)
	        VALUES($quoteid,'$date',(SELECT id FROM users WHERE user_name='admin' LIMIT 1) AS user_id,'Quotation Paid','The client has been notified that we have received payment.');";
	        
	        $ret = $database->query($sql);
			        
             /* send email to Ubaha and JJ */
    		$headers_ = "From: orders@shopbolanle.com\r\n";
    		$headers_ .= "Reply-To: orders@shopbolanle.com\r\n";
    		$headers_ .= "CC: ubahashipoke@gmail.com\r\n";
    		$headers_ .= "MIME-Version: 1.0\r\n";
    		
           $subject_ = "The customer has made payment.";
           $message_ = "The customer has made payment for the quotation. \r\n \r\n
           You can manage this RFQ by clicking: \r\nhttps://www.shopbolanle.com/admin/?view=manage-orders&action=edit&id=$quoteid&tab=tabpayments";
                        
           @ mail($email_,$subject_, $message_, $headers_);
       
             $fa_check = font_awesome('fa-check');
             echo alertbuilder("$fa_check Your payment was successfully made. Thank you.",'success');
         
             echo "<script>
                        window.setTimeout(function(){
                            window.location.href = '?view=home';
                        },1000 * 8);
                    </script>";

         }
        ?>
    </div>
</div>