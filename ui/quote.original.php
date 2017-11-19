<?php
 $action = @ $_GET['action'];
 $hash   = @ $_GET['hash'];
 
 // icons
 $fa_check   = font_awesome('fa-check');
 $fa_exclaim = font_awesome('fa-exclamation-triangle');
 $fa_cart    = font_awesome('fa-shopping-cart');
 $caption_checkout = "Pay now with PayPal";
 
 $url_payment = "https://www.shopbolanle.com/ppph/payments.php";
 $url_success = "https://www.shopbolanle.com/?view=payment_success';//ppph/success.php";
 $url_failed  = "https://www.shopbolanle.com/?view=payment_failed';//ppph/fail.php";
 $paypal_email= "orders@shopbolanle.com";
 $list_items  = "";
 
 // defaults
 $email_   = "orders@shopbolanle.com";
        
 switch ($action){
     case 'accept':
         /*
          * The completed quote has been sent out.
          * The client has clicked ACCEPT quote
          */
         
         $form_paypal = "";
         
         // find the quote identified by hash
         $hash = addslashes(htmlentities($hash));
         $sql  = "SELECT 
                        id, date_quote_accepted, date_payment_made
                  FROM 
                        quotes 
                  WHERE 
                        hash='$hash';";
         $ret  = $database->query($sql) or die(alertbuilder($database->error,'danger'));
         if (!$ret || !$ret->num_rows){
             die(alertbuilder("$fa_exclaim Quotation does not exist.",'danger'));
         }

         $row               = $ret->fetch_array();
         $quoteid           = $row['id'];
         $datequoteaccepted = $row['date_quote_accepted'];
         $datepaymentmade   = $row['date_payment_made'];
         
         // do not allow arbitrary quote acceptance,
         // as it changes the date of the acceptance
         if (strtolower($datequoteaccepted) == 'pending'){
             // fine
         } else {
             
             // The quote has already been accepted
             // if not paid, show payment page
             if (strtolower($datepaymentmade) == 'pending'){
                 
                $sql = "SELECT 
                                item_id, 
                                price,
                                url_image_1,
                                quantity, 
                                qi.shipping, 
                                specifications, 
                                sales_tax,
                                si.title
                        FROM 
                                quote_items qi, 
                                quotes q, 
                                store_items si
                        WHERE 
                                q.id = $quoteid AND
                                qi.quote_id = q.id AND
                                si.id = qi.item_id;";
                $ret = $database->query($sql) or die($database->error);
                
                if (!$ret || !$ret->num_rows){
                    die(alertbuilder("Unfortunately there are no items attached to the quotation.",'danger'));
                }
                
                $idx = 1;
                $tbody = "";
                $tax = 0;
                $total = 0;
                $curr  = "<b>US</b> $";
                $max   = 0;
                
                while ($row = $ret->fetch_array()){
                    $title = $row['title'];
                    $price = $row['price'];
                    $img   = $row['url_image_1'];
                    $quantity = $row['quantity'];
                    $specs = $row['specifications'];
                    $shipping = $row['shipping'];
                    $tax      = $row['sales_tax'];
                    
                    $total += ($price * $quantity) + $shipping;
                    $grand = ($price * $quantity);//+ $shipping;
                    
                    //header("HTTP/1.1 200 OK");
                    $tbody .= "<tr>
                                <td>
                                    <a href='$img' rel='items' class='fancybox' data-title=\"$title - $curr $price\"><img src='$img' style='width:80px' class='img-thumbnail'></a>
                                </td>
                                <td>
                                <strong>$title</strong><BR>
                                <small>Specifications: $specs</small>
                                </td>
                                <td class='text-center'>$quantity</td>
                                <td><strong>$curr $price</strong> <BR>
                                    <small>Shipping: $curr $shipping</small>
                                </td>
                               </tr>";
                        
                    $list_items .= 
                    "<input type='hidden' name='amount_$idx' value='$grand'>
                     <input type='hidden' name='shipping_$idx' value='$shipping'>
                     <input type='hidden' name='item_name_$idx' value=\"$title\">";
    
                    $idx++;
                }
                
                if ($tax)
                    $list_items .= 
                    "<input type='hidden' name='handling' value='$tax'>";
                    
                $form_paypal = 
                "<form action='$url_payment' method='POST' onsubmit=\"$('form > button').prop('disabled','disabled'); $('form > button > span').removeClass('fa-shopping-cart').addClass('fa-spinner fa-spin');\">
                    <input type='hidden' name='action' value='edit'>
                    <input type='hidden' name='txn_id' value='$quoteid'>
                    
                    $list_items
                    <button style='text-align:center' class='btn btn-lg btn-block btn-primary'>$fa_cart $caption_checkout</button>  
                    </form>";
                
                $max = $idx--;
                $grand = $tax + $total;
                
                echo "<style>
                        .container.hidden-xs.banner-image {height: 30px !important;}                
                      </style>
                      <div class='row'>
                        <h3 class='text-center'>Your Shopping Cart</h3>
                        <HR>
                        <div class='col-md-1'></div>
                        <div class='col-md-7'>
                          <div class='table-responsive'>
                           <table class='table table-hover table-bordered'>
                            <thead>
                             <tr>
                              <th></th>
                              <th>Description</th>
                              <th>Quantity</th>
                              <th>Price</th>
                             </tr>
                            </thead>
                            <tbody>
                             $tbody
                            </tbody>
                           </table>
                          </div>
                          </div>
                          <div class='col-md-3' 
                            style='-moz-box-shadow: -2px 1px 29px -4px #615f5f;
    -webkit-box-shadow: 0 0 5px rgba(0,0,0,.2), 0 1px 0 rgba(255,255,255,.15);
    box-shadow: 0 0 5px rgba(0,0,0,.2), 0 1px 0 rgba(255,255,255,.15);
    padding: 17px;'>
                              <h3><strong>Total</strong> <span class='pull-right'>$curr $grand</span></h3> 
                              <p>&nbsp;</p>
                              <h4>Subtotal<span class='pull-right'>$curr $total </span></h4>
                              <h4>Sales Tax <small class='pull-right'>$curr $tax</small></h4>
                              <h4>&nbsp; <small class='pull-right'>$max Items</small></h4>
                              <HR>
                              $form_paypal
                              <p>&nbsp;</p>
                              <p><small class='pull-right' style='color:red'>* PayPal account NOT required.</small></p>
                          </div>
                      </div>";
                      
                /*
                 * PayPal success will call ppph/success.php 
                 *  -- update date_payment_made
                 */
                exit(0);      
             } 
             
             die(alertbuilder("$fa_exclaim Payment has already been made for this quote.",'danger'));
         }
         
         // update the accepted date
         $date = date('Y-m-d H:i:s');
         
         $sql  = "UPDATE quotes 
                  SET date_quote_accepted='$date' 
                  WHERE id=$quoteid;";
         $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
         
       // notify orders at shopbolanle
		$headers_ = "From: orders@shopbolanle.com\r\n";
		$headers_ .= "Reply-To: orders@shopbolanle.com\r\n";
		$headers_ .= "CC: ubahashipoke@gmail.com\r\n";
		$headers_ .= "MIME-Version: 1.0\r\n";
		
       $subject_ = "The customer has accepted the quotation";
       $message_ = "You can manage this RFQ by clicking: \r\nhttps://www.shopbolanle.com/admin/?view=manage-orders&action=edit&id=$quoteid&tab=tabquoteitems";
                    
       @ mail($email_,$subject_, $message_, $headers_);
                   
         echo alertbuilder("$fa_check The quote has been accepted. At this point we will transfer the client to a paypal payments page.",'success');
         break;
         
     case 'decline':
         /*
          * The completed quote has been sent out.
          * The client has clicked DECLINE quote
          */         
         $hash = addslashes(htmlentities($hash));
         $sql  = "DELETE 
                  FROM quotes 
                  WHERE 
                       hash='$hash';";
         $ret  = $database->query($sql) or die(alertbuilder($database->error,'danger'));
         
       // notify orders at shopbolanle
		$headers_ = "From: orders@shopbolanle.com\r\n";
		$headers_ .= "Reply-To: orders@shopbolanle.com\r\n";
		$headers_ .= "CC: ubahashipoke@gmail.com\r\n";
		$headers_ .= "MIME-Version: 1.0\r\n";
		
       $subject_ = "A customer has declined a quote";
       $message_ = "The quote has been deleted from the system. No further action is required.";
                    
       @ mail($email_,$subject_, $message_, $headers_);
       
         echo alertbuilder("$fa_check We are sorry that you have declined the quote.", 'success');
         break;
         
     case 'item-received':
         /*
          * The client has received the item.
          */
          
         // find the quote identified by hash
         $hash = addslashes(htmlentities($hash));
         
         $sql  = "SELECT 
                        id, date_item_received 
                  FROM 
                        quotes 
                  WHERE 
                        hash='$hash';";
         $ret  = $database->query($sql) or die(alertbuilder($database->error,'danger'));
         if (!$ret || !$ret->num_rows){
             die(alertbuilder("$fa_exclaim Sorry, that quotation does not exist.",'danger'));
         }

         $row  = $ret->fetch_array();
         $quoteid = $row['id'];
         $dateitemreceived = $row['date_item_received'];
         
         // do not allow arbitrary quote acceptance,
         // as it changes the date of the acceptance
         if (strtolower($dateitemreceived) == 'pending'){
             // fine
         } else {
             die(alertbuilder("$fa_exclaim The item has already been received.", 'warning'));
         }
         
         // update the accepted date
         $date = date('Y-m-d H:i:s');
         
         $sql  = "UPDATE quotes 
                  SET date_item_received='$date' 
                  WHERE id=$quoteid;";
         $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
         
       // notify orders at shopbolanle
		$headers_ = "From: orders@shopbolanle.com\r\n";
		$headers_ .= "Reply-To: orders@shopbolanle.com\r\n";
		$headers_ .= "CC: ubahashipoke@gmail.com\r\n";
		$headers_ .= "MIME-Version: 1.0\r\n";
		
       $subject_ = "The client has confirmed receiving their order";
       $message_ = "You can manage this RFQ by clicking: \r\nhttps://www.shopbolanle.com/admin/?view=manage-orders&action=edit&id=$quoteid&tab=";
                    
       @ mail($email_,$subject_, $message_, $headers_);
                   
         echo alertbuilder("$fa_check Thank you for confirming receipt of your order.",'success');
         break;
         
     case 'item-never-received':
         /*
          * The client has never received the item.
          */
          
         // find the quote identified by hash
         $hash = addslashes(htmlentities($hash));
         
         $sql  = "SELECT 
                        id, date_item_received 
                  FROM 
                        quotes 
                  WHERE 
                        hash='$hash';";
         $ret  = $database->query($sql) or die(alertbuilder($database->error,'danger'));
         if (!$ret || !$ret->num_rows){
             die(alertbuilder("$fa_exclaim Sorry, that quotation does not exist.",'danger'));
         }

       // notify orders at shopbolanle
		$headers_ = "From: orders@shopbolanle.com\r\n";
		$headers_ .= "Reply-To: orders@shopbolanle.com\r\n";
		$headers_ .= "CC: ubahashipoke@gmail.com\r\n";
		$headers_ .= "MIME-Version: 1.0\r\n";
		
       $subject_ = "The client has indicated never receiving their order";
       $message_ = "You can manage this RFQ by clicking: \r\nhttps://www.shopbolanle.com/admin/?view=manage-orders&action=edit&id=$quoteid&tab=";
                    
       @ mail($email_,$subject_, $message_, $headers_);
                   
         echo alertbuilder("$fa_check Thank you for notifying us that you have not received your order.",'success');
         break;
         
     default:
         break;  
 }
?>