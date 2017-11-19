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
	
	echo "<h4>$fa_icon Quoted Items <small class='pull-right'>Breakdown of data</small></h4>
	       <HR>";
?>


<div class='row'>
    <div class="col-md-8">
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
	      		       total DESC;";
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
	     <div class='table-responsive'>
	         <table class='table table-hover'>
	             <tbody>
	                 <?php echo $tbody; ?>
	             </tbody>
	         </table>
	     </div>
     </div>
</div>