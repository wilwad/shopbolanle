<style>
div.scrollmenu {
    overflow: auto;
    white-space: nowrap;
}

div.scrollmenu a {
    display: inline-block;
    text-align: center;
    padding: 14px;
    text-decoration: none;
} 

div.scrollmenu a:hover {
    background: rgba(255, 229, 190, 0.52);
}

.active-selected {
    border-bottom: 2px solid #ebc589;
}
</style>
<?php
    // defs
    $currency = settings::currency;
    
    // parameters
	$userid = $user['userid'];
	$view   = @ $_GET['view'];
	$action = @ $_GET['action'];
	$id     = intval(@ $_GET['id']);
	$extra  = (int) @ $_POST['extra'];
	$filter = @ $_GET['category'];
	
	// icons
	$fa_icon   = font_awesome('fa-picture-o');
	$fa_edit   = font_awesome('fa-edit');
	$fa_plus   = font_awesome('fa-plus');
	$fa_remove = font_awesome('fa-remove');
	$fa_trash  = font_awesome('fa-trash');
	$fa_floppy = font_awesome('fa-floppy-o');
	$fa_check  = font_awesome('fa-check');
	$fa_user   = font_awesome('fa-user');
	$fa_filter = font_awesome('fa-filter');
	$fa_eye    = font_awesome('fa-eye');
	
	switch ($action){
	    case 'edit':
	        // needs an ID
        	$application_notification = "";
        	
        	$required = array('store_item_category_id',
        	                  'title',
        	                  'description',
        	                  'cost',
        	                  'category_id',
        	                  'url_image_1',
        	                  'visibility_id');
        	                  	
        	$sql = "SELECT id FROM store_items WHERE id=$id;";
        	$ret = $database->query($sql);
        	if (!$ret || !$ret->num_rows) {
        		die(alertbuilder("Unable to locate banner.",'danger'));
        	}

        	if ($extra){
        		$errors = "";

    			foreach($required as $field){
    					$_POST[$field] = addslashes(trim(@ $_POST[$field]));
    
    					if ($_POST[$field] == ""){
    						$errors = missing_parameter($field);
    						break;
    					}
    			}		
			
    			// update application has been called!
    			$sql = "UPDATE `store_items` SET ";
        			
    			if ($errors == ""){
    				foreach($_POST as $key=>$val){						 
    						 switch ($key){
    						 		// remove the other views
    						 		case 'action':
    						 		case 'view':
    						 		case 'extra':
    						 		case 'table-history_length':
    						 			// bug: not sure where this column comes from
    						 			break;
    						 					
    						 		default:
    						           $val = htmlentities($val);
    						           $sql .= "$key='$val', ";						 		
    						 			break;
    						 }
     				}
     				
 			   // remove trailing string
 			   $sql = substr($sql, 0, strlen($sql)-2);
 			   $sql .= " WHERE id=$id"; 
			   $ret = $database->query($sql);		   
 			   
 			   	if (!$ret)
 			   	{
 			   		$application_notification = "<p class='alert alert-danger'>$fa_exclamation Failed to update item. Error: $error</p>";
 			   		
						$table = "unk_log";
						$appid = $id;
						$userid_ = $userid;
						$caption = "App edit error";
						$description = "Failed to update the item. Error: $error";							
						//update_application_log($table, $appid, $userid_, $caption, $description);
 			   	}
 			   	else
 			   	{

						$description = "Updated successfully.";	
						//update_application_log($table, $appid, $userid_, $caption, $description);
						
 			   		$application_notification = "<p class='alert alert-success'>$fa_check $description</p>";
 			   	}	
			}
		}

		$application =  "<button class='btn btn-sm btn-success'><li class='fa fa-fw fa-floppy-o'></p>&nbsp;Save changes</button>";

		$fa_plus = font_awesome('fa-plus');
		$fa_info = font_awesome('fa-info-circle');
		$fa_lock = font_awesome('fa-lock');

		$fa_user   = font_awesome('fa-user');
		$fa_remove = font_awesome('fa-remove');
		$fa_floppy = font_awesome('fa-floppy-o');
		$fa_edit   = font_awesome('fa-edit');

		$sql = "SELECT 
		                *
				FROM 
						store_items
				WHERE 
						id=$id;";
		$ret = $database->query($sql);
		if (!$ret || !$ret->num_rows)
			die(alertbuilder("Error #0: Unable to locate quote identified by id.",'danger'));
								
		while ( $row = $ret->fetch_array() ){	
			while ($columns = $ret->fetch_field()){
					 $col = $columns->name;
			 		 $_POST[$col] = $row[$col];							
			}			
		}

		$entrydate = @ $_POST['entrydate'];
						
		$body_section_a = "";
		$errors_buffer = "";
       				
		$buffer = build_form('store_items',$required);	
		$body_section_a = "<form class='form-horizontal' method='POST'
		role='form'>
			<input type='hidden' name='action' value='edit'>
						<input type='hidden' name='extra' value='1'>
                $errors_buffer
				     $buffer
               </form>";

		$timeago = "<abbr class='timeago' title='$entrydate'>$entrydate</abbr>";
		
		echo "<h4>$fa_edit Edit store item
					<div class='pull-right'>
					 <a href='#' onclick=\"$('form').submit();\" title='Update' data-placement='bottom' data-toggle='tooltip' class='btn btn-success btn-sm'>$fa_floppy Update</a>
					 <a href='?view=$view&category=$filter' title='Cancel update' data-placement='bottom' data-toggle='tooltip' class='btn btn-warning btn-sm'>Close</a></small>		
					</div>
				</h4>
				<hr>

				";
        						
        echo "<div class='row'>
               <div class='col-md-8' style='border-right:1px solid #cacaca;'>
        				$application_notification
        				$body_section_a
               </div>
               <div class='col-md-4'>
                <h5><strong>Edit store item</strong></h5>
                Edit your store item by specifying the relevant info.
               </div>
              </div>";
                      	       
	        break;
	   
	    default:
        	echo "<h4>$fa_icon Store items <small class='hidden-xs pull-right'><a href='?view=new-store-item' class='btn btn-success'>$fa_plus Add new store item</a></small></h4>
        	       <HR>";
        	      
        	$filter_cat = "";
        	$tabs = "";
        	
            $selected_all = $filter == "" ? "active-selected" : "";
            $active_      = $filter == "" ? "active" : "";
            
            $categories = "<a href='?view=$view&category=' class='$selected_all'>All items</a>";
            $url = "?view=$view&category=";
            
            $tabs = "<li class='$active_'><a href='$url' onclick=\"window.location.href='$url';\" data-toggle='tab'>$fa_filter All items</a></li>";
                    
            $sql = "SELECT name,
                           (SELECT COUNT(si.id) 
                            FROM store_items si, 
                                 store_item_categories sic 
                            WHERE si.category_id = c.id AND 
                                  si.store_item_category_id = sic.id AND
                                  sic.name = 'item')  AS total
                    FROM 
                           categories c
                    ORDER BY name ASC;";
            $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
            if (!$ret || !$ret->num_rows){
                // nothing
            } else {
                while ($row = $ret->fetch_array()){
                    $total = $row['total'];
                    $cat = strtolower($row['name']);
                    $cat_= ucwords($cat);
                    $active = ($filter == $cat) ? "active-selected" : "";
                    $active_ = ($filter == $cat) ? 'active' : '';
                    
                    /*$categories .= "<a href='?view=$view&category=$cat' class='$active'>$cat_ <span class='badge'>$total</span></a>";
                    */
                    
                    if ($active != ""){
                        $filter_cat = "c.name='$cat' AND ";
                    }
                    
                    $url = "?view=$view&category=$cat";
                    
                    $tabs .= "<li class='$active_'><a href='$url' onclick=\"window.location.href='$url';\" data-toggle='tab'>$cat_ <span class='badge'>$total</span></a></li>";
                }
            }
        
        	echo "<div class='row'>
        	       <div class='col-md-12'>
        	        <ul id='tabs' class='nav nav-tabs' data-tabs='tabs'>
        	         $tabs
        	        </ul>
        	       </div>
        	      </div>
        	      <BR>";
        	      
            $datatable = "";
            
        	$thead = "<tr>
        	            <th>No.</th>
        	            <th>Title & Description</th>
        	            <th>Image Preview</th>
        	            <th>Information</th>
        	            <th>Actions</th>
        	          </tr>";
        	$tbody = "<tr><td colspan='5'>No store items.</td></tr>";
        
        	$sql = "SELECT 
        	                si.id,
        	                si.title,
        	                si.cost,
        	                si.description,
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
        	             $filter_cat
        	             sic.name = 'item' AND
        	             si.visibility_id = v.id AND
        	             si.user_id = u.id
        	        ORDER BY 
        	             si.id DESC;";
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
        	      $description = $row['description'];
        	      $url_image_1 = $row['url_image_1'];
        	      $category    = $row['category'];
        	      $cost        = $row['cost'];
        	      $category    = "<span class='label label-default'>$category</span>";
        	      $user        = $row['user_name'];
        	      
        	      $actions     = "<a href='?view=$view&action=edit&id=$id&category=$filter'>$fa_edit Edit</a><BR>
        	      <a href='#' style='color:red' onclick=\"return confirmdelete('store_items',$id);\">$fa_trash Delete</a> <BR><BR>
        	      <a href='../?view=item&id=$id' class='fancybox fancybox.iframe'>$fa_eye Visit</a>";
        	      
        	      $tbody .= "<tr>
        	                  <td>$idx</td>
        	                  <td>$entrydate <BR>
        	                     <h5>$title <BR>
        	                     <small>$description</small></h5> 
                                 Cost: $currency $cost 
        	                  </td>
        	                  <td>
<a href='$url_image_1' class='fancybox' data-title=\"$title - $cost\"><img style='width:180px' src='$url_image_1' class='img-responsive img-thumbnail'></a>        	                  
        	                  </td>
        	                  <td>$category <BR> $visibility <BR> $fa_user $user</td>
        	                  <td>$actions</td>
        	                 </tr>";
        	                 
        	       $idx++;
        	  } 
        	  
        	  $datatable = "$('#tablebanners').dataTable();";
        	}
        	
        	echo "<div class='row'>
        	       <div class='col-md-12'>
        	        <div class='table-responsive'>
        	         <table class='table table-bordered table-hover' id='tablebanners'>
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