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
	
	echo "<h4>$fa_icon Dashboard <small class='pull-right'>Breakdown of data</small></h4>
	       <HR>";

    /* banners */
    $datatable = "";
    
	$thead = "<tr>
	            <th>No.</th>
	            <th>Entrydate</th>
	            <th>Title</th>
	            <th>Image Preview</th>
	            <th>Visibility</th>
	            <th>Added by</th>
	            <th>Actions</th>
	          </tr>";
	$tbody = "<tr><td colspan='7'>No banners.</td></tr>";

	$sql = "SELECT 
	                si.id,
	                si.title,
	                si.entrydate,
	                v.name AS visibility,
	                si.url_image_1,
	                c.name AS category,
	                u.user_name
	        FROM 
	             store_items si, 
	             categories c,
	             store_item_categories sic,
	             list_visibility v,
	             users u
	        WHERE 
	             si.category_id = c.id AND
	             si.store_item_category_id = sic.id AND
	             sic.name = 'banner' AND
	             si.visibility_id = v.id AND
	             si.user_id = u.id
	        ORDER BY 
	             si.id DESC
	        LIMIT 5;";
	$ret = $database->query($sql) or die($database->error);
	if (!$ret || !$ret->num_rows){
	    
	} else {
	  $tbody = "";
	  $idx = 1;
	  
	  while ($row = $ret->fetch_array()){
	      $id          = $row['id'];
	      $visibility  = $row['visibility'];
	      $visibility  = $visibility == 'visible' ? "<span class='label label-success'>Visible</span>" : "<span class='label label-warning'>Hidden</span>";
	      $entrydate   = $row['entrydate'];
	      $entrydate   = "<abbr class='timeago' title='$entrydate'>$entrydate</abbr>";
	      $title       = $row['title'];
	      $url_image_1 = $row['url_image_1'];
	      $category    = $row['category'];
	      $actions     = "<a href='?view=manage-banners&action=edit&id=$id'>$fa_edit Edit</a><BR>
	      <a href='#' style='color:red' onclick=\"return confirmdelete('store_items',$id);\">$fa_trash Delete</a>";
	      $user        = $row['user_name'];
	      
	      $tbody .= "<tr>
	                  <td>$idx</td>
	                  <td>$entrydate</td>
	                  <td>$title</td>
	                  <td><a href='$url_image_1' class='fancybox'><img style='width:200px' src='$url_image_1' class='img-responsive img-thumbnail'></a>
	                  </td>
	                  <td>$visibility</td>
	                  <td>$fa_user $user</td>
	                  <td>$actions</td>
	                 </tr>";
	                 
	       $idx++;
	  } 
	  
	  $datatable = "$('#tablebanners').dataTable();";
	}
	
	echo "<div class='row'>
	       <div class='col-md-12'>
	        <h4 class='text-center'>Latest 5 banners <small class='pull-right'><a href='?view=manage-banners'>$fa_edit View all banners</a></small></h4>
	        <div class='table-responsive'>
	         <table class='table table-bordered' id='tablebanners'>
	          <thead>$thead</thead>
	          <tbody>$tbody</tbody>
	         </table>
	        </div>
	       </div>
	      </div>
	      <HR>
	      <script>
	       $(document).ready(function(){
	           $datatable
	       });
	      </script>
	      ";    
          
    /* items */
    $datatable = "";
    
	$thead = "<tr>
	            <th>No.</th>
	            <th>Title</th>
	            <th>Image Preview</th>
	            <th>Category & Visibility</th>
	            <th>Added by</th>
	            <th>Actions</th>
	          </tr>";
	$tbody = "<tr><td colspan='6'>No items.</td></tr>";

	$sql = "SELECT 
	                si.id,
	                si.title,
	                si.entrydate,
	                si.description,
	                si.cost,
	                v.name AS visibility,
	                si.url_image_1,
	                c.name AS category,
	                u.user_name
	        FROM 
	             store_items si, 
	             categories c,
	             store_item_categories sic,
	             list_visibility v,
	             users u
	        WHERE 
	             si.category_id = c.id AND
	             si.store_item_category_id = sic.id AND
	             sic.name = 'item' AND
	             si.visibility_id = v.id AND
	             si.user_id = u.id
	        ORDER BY 
	             si.id DESC
	        LIMIT 5;";
	$ret = $database->query($sql) or die($database->error);
	if (!$ret || !$ret->num_rows){
	    
	} else {
	  $tbody = "";
	  $idx = 1;
	  
	  while ($row = $ret->fetch_array()){
	      $id          = $row['id'];
	      $visibility  = $row['visibility'];
	      $visibility  = $visibility == 'visible' ? "<span class='label label-success'>Visible</span>" : "<span class='label label-warning'>Hidden</span>";
	      $entrydate   = $row['entrydate'];
	      $entrydate   = "<abbr class='timeago' title='$entrydate'>$entrydate</abbr>";
	      $title       = $row['title'];
	      $url_image_1 = $row['url_image_1'];
	      $category    = $row['category'];
	      $category    = "<span class='label label-default'>$category</span>";
	      $cost        = $row['cost'];
	      $description = $row['description'];
	      $user        = $row['user_name'];
	      
	      $actions     = "<a href='?view=manage-store-items&action=edit&id=$id'>$fa_edit Edit</a><BR>
	      <a href='#' style='color:red' onclick=\"return confirmdelete('store_items',$id);\">$fa_trash Delete</a>";
	      
	      $tbody .= "<tr>
	                  <td>$idx</td>
	                  <td>$entrydate <BR>
	                      <h5>$title <BR>
	                      <small>$description</small></h5>
	                      Cost: $$cost
	                  </td>
	                  <td><a href='$url_image_1' class='fancybox' data-title=\"$title\"><img style='width:180px' src='$url_image_1' class='img-responsive img-thumbnail'></a></td>
	                  <td>$category <BR>
	                      $visibility</td>
	                  <td>$fa_user $user</td>
	                  <td>$actions</td>
	                 </tr>";
	                 
	       $idx++;
	  } 
	  
	  $datatable = "$('#tablelatestitems').dataTable();";
	}
	
	echo "<div class='row'>
	       <div class='col-md-12'>
	        <h4 class='text-center'><strong>Latest 5 items</strong> <small class='pull-right'><a href='?view=manage-store-items'>$fa_edit View all store items</a></small></h4>
	        <div class='table-responsive'>
	         <table class='table table-bordered' id='tablelatestitems'>
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