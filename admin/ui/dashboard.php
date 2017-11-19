<?php
    /*
    * Dashboard
    *
	 * Author: William Sengdara
	 * Created:
	 * Modified:
	 */

	if (!@$users)
		die("FATAL ERROR: this file may not be launched outside the system. It can only be included.");

	$userid = $user['userid'];
	$view = @ $_GET['view'];

    $currency = "US";
    
    define('TODAY',0);
    define('WEEK',1);
    define('MONTH',2);
    define('YEAR',3);
     
    $date = date('Y-m-d'); // 2015-02-07
    $year = date('Y');
    $month= date('m');
    $len  = strlen($date);
     
    // icons	
	$fa_icon   = font_awesome('fa-dashboard');
	$fa_edit   = font_awesome('fa-edit');
	$fa_bank   = font_awesome('fa-bank');
	$fa_remove = font_awesome('fa-remove');
	$fa_trash  = font_awesome('fa-trash');
	$fa_user   = font_awesome('fa-user');
	$fa_calendar = font_awesome('fa-calendar');
	$fa_language = font_awesome('fa-language');
	$fa_envelope = font_awesome('fa-envelope-o');
		
	echo "<h4>$fa_icon Dashboard <small class='pull-right'>Breakdown of data</small></h4>
	       <HR>";
     
 
	$tbody = "<tr><th>Categories</th></tr>";
	$thead = "<tr><td>There are no categories</td></tr>";

	$sql = "SELECT c.name, COUNT(si.id) AS total 
	        FROM 
	             store_items si, 
	             categories c,
	             store_item_categories sic
	        WHERE 
	             si.category_id = c.id AND
	             si.store_item_category_id = sic.id AND
	             sic.name = 'item'
	        GROUP BY c.name 
	        ORDER BY c.name ASC;";
	$ret = $database->query($sql) or die($database->error);
	if (!$ret || !$ret->num_rows){
	    
	} else {
	  $thead = "";
	  $tbody = "";
	  
	  while ($row = $ret->fetch_array()){
	      $name = $row['name'];
	      $total = $row['total'];
	      
	      $thead .= "<th class='text-center'>$name</th>";
	      $tbody .= "<td class='text-center'><h3>$total</h3></td>";
	  }    
	}
	
     // received stats
     $received[TODAY]['total'] = 0;
     $received[TODAY]['sql'] = "SELECT  COUNT(id) AS total FROM quotes WHERE SUBSTR(entrydate,1, $len) = '$date';";
     $received[WEEK]['total']  = 0;
     $received[WEEK]['sql'] = "SELECT  COUNT(id) AS total FROM quotes WHERE YEARWEEK(`entrydate`, 1) = YEARWEEK(CURDATE(), 1);";
     $received[MONTH]['total'] = 0;
     $received[MONTH]['sql'] = "SELECT  COUNT(id) AS total FROM quotes WHERE SUBSTR(entrydate,6, 2) = '$month';";
     $received[YEAR]['total']  = 0;
     $received[YEAR]['sql'] = "SELECT  COUNT(id) AS total FROM quotes WHERE SUBSTR(entrydate,1, 4) = '$year';";
     
     $max_received  = count($received);
     
     for ($idx = 0; $idx < $max_received; $idx++){
    	 $sql = $received[$idx]["sql"];
    
    	 $ret = $database->query($sql) or die(alertbuilder($database->error, 'danger'));
    	 if (!$ret || !$ret->num_rows){
    	 } else {
    	   $received[$idx]['total'] = $ret->fetch_array()['total'];
    	 }
     }

echo "<div class='row'>
	       <div class='col-md-6'>
	        <h4 class='text-center'>Categories</h4>
	        <div class='table-responsive'>
	         <table class='table table-bordered'>
	          <thead>$thead</thead>
	          <tbody>$tbody</tbody>
	         </table>
	        </div>
	       </div>
         <div class='col-md-6'>
          <h4 class='text-center'>Requests for Quotations</h4>
          <div class='table-responsive'>
        	<table class='table table-bordered' style='text-align:center'>
        	 <thead>
        	 	<tr>
        	 		<th class='text-center'>$fa_calendar Today</th>
        	 		<th class='text-center'>$fa_calendar This Week</th> 
        	 		<th class='text-center'>$fa_calendar This Month</th> 
        	 		<th class='text-center'>$fa_calendar This Year</th>
        	 	</tr>
        	 </thead>
        	 <tbody>
        	 	<tr>
        	 		<td><h3>{$received[TODAY]['total']}</h3></td>
        	 		<td><h3>{$received[WEEK]['total']}</h3></td>
        	 		<td><h3>{$received[MONTH]['total']}</h3></td>
        	 		<td><h3>{$received[YEAR]['total']}</h3></td>
        	 	</tr>
        	 </tbody>
        	</table>
          </div> 
         </div>
        </div>
        <HR>";
?>

<div class='row'>
    <div class="col-md-4 border-right">

	   <h5 class='text-center'>Top 5 Quotes by Country</h5>
		 <div id='chart-quote-countries'></div>
	    <?php
	    	$roles = array();
	    	$totals = array();
	    	$pie    = "";
	    	
	      $sql = "SELECT 
	      					COUNT(sa.quote_id) as total, 
	      					lr.name as region 
	      		  FROM 
	      		            shipping_address sa,
	      		            list_countries lr
	      		  WHERE 
	      		            sa.country_id = lr.id
	      		  GROUP BY 
	      		  			lr.name 
	      		  ORDER BY total
	      		  DESC
	      		  LIMIT 5;";
	      $ret = $database->query($sql);
	      if (!$ret || !$ret->num_rows){
	      } else {
	      	while ($row = $ret->fetch_array()){
		      	$name  = $row['region'];
		      	$name  = ucfirst($name);
		      	$total =  $row['total'];
		      	
		      	// append to array
			$roles[]  = $name;
		      	$totals[] = $total;	
		      	
		      	// for pie
		      	$pie .= "['$name', $total],";
	         }
	         
	         // remove last comma
	         $pie = substr($pie, 0, strlen($pie)-1);
	      }
	     ?>
	    <script> 
		 $(window).load(function(){
			var chart = c3.generate({
				bindto: '#chart-quote-countries',
				data: {
				  columns: [
					//['Regions', <?php echo "'" . implode("','",$totals) . "'"; ?>]
					<?php echo $pie; ?>
				  ],		  
				  axes: {
					  data2: 'y2'
				  },
				  type: 'pie',
				  types: {
						Regions: 'pie'
				  }		  
				}
				/*,
		    axis: {
		        x: {
		            type: 'category',
		            categories: [<?php echo "'" . implode("','",$_roles) . "'"; ?>]
		        }
		        }*/
			});
		});
	     </script>

     </div>
     
    <div class="col-md-4 border-right">
	   <h5 class='text-center'>Top 5 Quoted Items</h5>
	   <div id='chart-quote-items'></div>
	    <?php
	    	$roles = array();
	    	$totals = array();
	    	$pie    = "";
	    	$tbody = "";
	    	
	      $sql = "SELECT 
	      					COUNT(qi.quote_id) as total, 
	      					si.title as region 
	      		  FROM 
	      		            quote_items qi,
	      		            store_items si
	      		  WHERE 
	      		            qi.item_id = si.id
	      		  GROUP BY 
	      		  			si.title 
	      		  ORDER BY 
	      		       total DESC
	      		  LIMIT 5;";
	      $ret = $database->query($sql);
	      if (!$ret || !$ret->num_rows){
	      } else {
	      	while ($row = $ret->fetch_array()){
		      	$name  = $row['region'];
		      	$name  = ucfirst($name);
		      	$total =  $row['total'];
		      	
		      	// append to array
			    $roles[]  = $name;
		      	$totals[] = $total;	
		      	
		      	// for pie
		      	$pie .= "['$name', $total],";
		      	$tbody .= "<tr><th>$name</th><td>$total</td></tr>";
	         }
	         
	         // remove last comma
	         $pie = substr($pie, 0, strlen($pie)-1);
	      }
	     ?>
	    <script> 
		 $(window).load(function(){
			var chart = c3.generate({
				bindto: '#chart-quote-items',
				data: {
				  columns: [
					//['Regions', <?php echo "'" . implode("','",$totals) . "'"; ?>]
					<?php echo $pie; ?>
				  ],		  
				  axes: {
					  data2: 'y2'
				  },
				  type: 'pie',
				  types: {
						Regions: 'pie'
				  }		  
				}
			});
		});
	     </script>
	     <!--
	     <div class='table-responsive'>
	         <table class='table table-hover'>
	             <tbody>
	                 <?php echo $tbody; ?>
	             </tbody>
	         </table>
	     </div>
	     -->
     </div>
     
   <div class="col-md-4 border-right">
	   <h5 class='text-center'>Quote Actions</h5>
	   <div id='chart-quote-action'></div>
	    <?php
	    	$roles = array();
	    	$totals = array();
	    	$pie    = "";
	    	
	      $sql = "SELECT
                    SUM(CASE WHEN date_quote_sent = 'Pending' THEN 1 ELSE 0 END) pending,
                    SUM(CASE WHEN date_quote_sent <> 'Pending' THEN 1 ELSE 0 END) handled
                    FROM quotes";
	      $ret = $database->query($sql);
	      if (!$ret || !$ret->num_rows){
	      } else {
	      	    $row = $ret->fetch_array();
		      	$total_pending  = $row['pending'];
		      	$total_handled  = $row['handled'];
		      	
		      	// append to array
			    $roles[]  = 'Pending';
			    $roles[]  = 'Handled';
		      	$totals[] = $total_pending;
		      	$totals[] = $total_handled;	
		      	
		      	// for pie
		      	$pie = "['Pending', $total_pending], 
		      	        ['Handled',  $total_handled]";
	      }
	     ?>
	    <script> 
		 $(window).load(function(){
			var chart = c3.generate({
				bindto: '#chart-quote-action',
				data: {
				  columns: [
					//['Regions', <?php echo "'" . implode("','",$totals) . "'"; ?>]
					<?php echo $pie; ?>
				  ],		  
				  axes: {
					  data2: 'y2'
				  },
				  type: 'pie',
				  types: {
						Regions: 'pie'
				  }		  
				}
				/*,
		    axis: {
		        x: {
		            type: 'category',
		            categories: [<?php echo "'" . implode("','",$_roles) . "'"; ?>]
		        }
		        }*/
			});
		});
	     </script>
     </div>
   	             
</div>
<HR>
    
<?php
    /* requests quotes/orders */
    $datatable = "";
    
	$thead = "<tr>
	            <th>No.</th>
	            <th>Entrydate</th>
	            <th>Full name</th>
	            <th>Country</th>
	            <th>Quote items</th>
	            <th>Date quote sent</th>
	            <th>Date quote accepted</th>
	            <th>Actions</th>
	          </tr>";
	$tbody = "<tr><td colspan='8' class='text-center'>No requests for quotation.</td></tr>";

	$sql = "SELECT 
	                q.id,
	                q.entrydate,
	                q.date_quote_sent,
	                q.date_quote_accepted,
                    lc.name AS country,
                    sa.full_name,
                    (SELECT COUNT(qi.quote_id) FROM quote_items qi WHERE quote_id=q.id) AS total
	        FROM 
	                quotes q,
	                shipping_address sa,
	                list_countries lc	                
	        WHERE 
                    q.id = sa.quote_id AND
                    sa.country_id = lc.id AND
                    q.date_quote_sent = 'Pending'
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
	      $date_quote_accepted = $row['date_quote_accepted'];
	      
          $actions   = "<a href='?view=manage-orders&action=edit&id=$id'>$fa_edit Edit</a><BR>
	      <a href='#' style='color:red' onclick=\"return confirmdelete('quotes',$id);\">$fa_trash Delete</a>";	   
	      
	      $tbody .= "<tr>
	                  <td>$idx</td>
	                  <td>$entrydate</td>
	                  <td>$fa_user $fullname</td>
	                  <td>$fa_language $country</td>
	                  <td>$total</td>
	                  <td>$date_quote_sent</td>
	                  <td>$date_quote_accepted</td>
	                  <td>$actions</td>
	                 </tr>";
	                 
	       $idx++;
	  } 
	  
	  $datatable = "$('#tablequotes').dataTable();";
	}
	
	echo "<div class='row'>
	       <div class='col-md-12'>
	        <h4 class='text-center'>Latest pending requests for quotation <small class='pull-right'><a href='?view=manage-orders'>$fa_edit View all quotes</a></small></h4>
	        <div class='table-responsive'>
	         <table class='table table-bordered' id='tablequotes'>
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
?>
<HR>
<?php
        $thead = "<tr>
                           <th>#</th>
                           <th>Entry date</th>
                           <th>Quote</th>
                           <th>Amount paid</th>
                           <th>PayPal payment id</th>
                           <th>Actions</th>
                          </tr>";
                  
        $tbody = "<tr>
                    <td colspan='6' class='text-center'>
                     No payments.
                    </td>
                </tr>";
                        
        $sql = "SELECT 
                        *
                FROM 
                        payments p
                ORDER BY 
                      quote_id DESC
                LIMIT 5;";
                
        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
        if (!$ret || !$ret->num_rows){
            // nothing
        } else {
            $tbody = "";
            $idx = 1;
            
            while ($row = $ret->fetch_array()){
                $quoteid   = $row['quote_id'];
                $entrydate = $row['entrydate'];
                $entrydate = "<abbr class='timeago' 
                title='$entrydate'>$entrydate</abbr>";
                $amount    = $row['amount'];
                $amount    = number_format($amount,2);
                $pid       = $row['paypal_paymentid'];
                
                $actions = "<a href='?view=manage-orders&action=edit&id=$quoteid&tab=tabpayments' style=''>$fa_edit Edit quote</a>";
                $tbody .= "<tr>
                              <td>$idx</td>
                              <td>$entrydate</td>
                              <td>Quote #$quoteid</td>
                              <td>$currency $amount</td>
                              <td>$pid</td>
                              <td>$actions</td>
                             </tr>";
                $idx++;
            }
        }
        
    echo "<div class='row'>
           <div class='col-md-12'>
           <h4 class='text-center'>Latest 5 payments</h4>
            <div class='table-responsive'>
			     <table class='table table-bordered table-hover' id='tablepayments'>
			      <thead>$thead</thead>
			      <tbody>$tbody</tbody>
			     </table>
			</div>	
		    </div>
		  </div>";
?>
<HR>
<?php
        $head_alerts = "<tr>
                           <th>#</th>
                           <th>Entry date</th>
                           <th>To</th>
                           <th>Email</th>
                           <th>Sent by</th>
                           <th>Actions</th>
                          </tr>";
                  
        $body_alerts = "<tr>
                        <td colspan='6' class='text-center'>
                         No emails.
                        </td>
                        </tr>";
                        
        $sql = "SELECT 
                        sa.full_name,
                        sa.email,
                        qe.quote_id,
                        qe.id,
                        qe.entrydate,
                        qe.subject,
                        qe.body,
                        u.user_name
                FROM 
                     quote_emails qe,
                     users u,
                     shipping_address sa
                WHERE 
                      u.id = qe.user_id AND
                      sa.quote_id = qe.quote_id
                ORDER BY 
                      qe.id DESC
                LIMIT 5;";
                
        $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
        if (!$ret || !$ret->num_rows){
            // nothing
        } else {
            $body_alerts = "";
            $idx = 1;
            
            while ($row = $ret->fetch_array()){
                $quoteid   = $row['quote_id'];
                $fullname  = $row['full_name'];
                $email     = $row['email'];
                $emailid   = $row['id'];
                $entrydate = $row['entrydate'];
                $entrydate = "<abbr class='timeago' 
                title='$entrydate'>$entrydate</abbr>";
                $subject = $row['subject'];
                $body    = $row['body'];
                $username = $row['user_name'];
                
                $actions = "<a href='?view=manage-orders&action=edit&id=$quoteid&tab=tabquoteitems' style=''>$fa_edit Edit quote</a>";
                $body_alerts .= "<tr>
                                  <td>$idx</td>
                                  <td>$entrydate</td>
                                  <td>$fa_user $fullname <BR>
                                   <small>$email</small>
                                  </td>
                                  <td>$fa_envelope <strong>$subject</strong>
                                  <BR>
                                  <small>$body</small></td>
                                  <td>$fa_user $username</td>
                                  <td>$actions</td>
                                 </tr>";
                $idx++;
            }
        }
        
    echo "<div class='row'>
           <div class='col-md-12'>
           <h4 class='text-center'>Latest 5 quote emails <small class='pull-right'><a href='?view=manage-quotes'>View all</a></small></h4>
            <div class='table-responsive'>
			     <table class='table table-bordered table-hover' id='tableemails'>
			      <thead>$head_alerts</thead>
			      <tbody>$body_alerts</tbody>
			     </table>
			</div>	
		    </div>
		  </div>";
?>    