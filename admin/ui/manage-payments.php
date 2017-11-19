<?php
        $fa_edit = font_awesome('fa-edit');
        $fa_usd  = font_awesome('fa-usd');
        $currency = "US";
        $total_payments     = 0;
        $payments_datatable = "";
        $payments_head = "<tr>
                           <th>#</th>
                           <th>Entrydate</th>
                           <th>Amount</th>
                           <th>Paypal Payment Id</th>
                           <th>Actions</th>
                          </tr>";
        $payments_body = "<tr>
                           <td colspan='6' class='text-center'><i>No payments.</i></td>
                          </tr>";
        $sql = "SELECT 
                        *
                FROM payments
                ORDER BY 
                      entrydate DESC;";
        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
        if (!$ret || !$ret->num_rows){
            // nothing
        } else {
            $payments_body = "";
            $idx = 1;
            
            while ($row = $ret->fetch_array()){
                $total_payments++;
                $id        = $row['quote_id'];
                $entrydate = $row['entrydate'];
                $entrydate = "<abbr class='timeago' 
                title='$entrydate'>$entrydate</abbr>";
                $amount    = $row['amount'];
                $amount    = number_format($amount,2);
                $pppid     = $row['paypal_paymentid'];
                $actions   = "<a href='?view=manage-orders&action=edit&id=$id&tab=tabpayments'>$fa_edit Edit quote</a>"; 
                
                $payments_body .= "<tr>
                                      <td>$idx</td>
                                      <td>$entrydate</td>
                                      <td>$currency $amount</td>
                                      <td>$pppid</td>
                                      <td>$actions</td>
                                     </tr>";
                $idx++;
            }
            
            $payments_datatable = "$('#tablepayments').dataTable();";
        } 
        
        echo "<h4>$fa_usd Payments <small class='pull-right'>Payments made for Quotations</small></h4>
              <HR>
              <div class='row'>
               <div class='col-md-12'>
                <div class='table-responsive'>
    			     <table class='table table-bordered table-hover' id='tablepayments'>
    			      <thead>$payments_head</thead>
    			      <tbody>$payments_body</tbody>
    			     </table>
    			</div>	
    		    </div>
    		  </div>
    		  <script>
    		    $(document).ready(function(){
    		       $payments_datatable 
    		    });
    		  </script>";                
?>
