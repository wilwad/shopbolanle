<?php
 // params
 $action = @ $_GET['action'];
 $id     = (int) @ $_GET['id'];
 
 $key = "shopping_cart";
 
 // icons
 $fa_eye     = font_awesome('fa-eye');
 $fa_cart    = font_awesome('fa-shopping-cart'); 
 $fa_trash   = font_awesome('fa-trash'); 
 $fa_exclaim = font_awesome('fa-exclamation-triangle');
 
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
                        'maxlength'=>0),

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
			                        'source'=>'ui/countries.txt',
			                        'required'=>true,
			                        'callback'=>'',
			                        'maxlength'=>0)                      
                  );      
?>

<div class='row'>
    <div class='col-md-12'>
        <h3><span class='fa fa-fw fa-credit-card'></span> Checkout <small class='pull-right hidden-xs'>Pay for your items in order for them to be shipped to you.</small></h3>
        <HR>
    </div>
</div>

<div class='row'>
 <div class='col-md-6' style='background: #f3f3f3; border-radius: 4px;
    box-shadow: 0 0 0 1px #d9d9d9;'>
     <h5>Shipping Information</h5>
     <BR>
      <form method="POST">
       <input type='hidden' name='action' value="contact">
       <input type='hidden' name='extra' value="1">
       
    	<?php
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
    	      <button type="submit" onclick="return validate_submit();" class="btn btn-primary"><span class='fa fa-fw fa-floppy-o'></span>
    	      Update</button>
    	    </div>
    	  </div>
      </form>     
 </div>  
 <div class='col-md-6'>
<h3><a href='?view=checkout' class='btn btn-success'>$fa_cart Checkout</a></h3>
 </div>  
</div>

<BR>
    
<?php 
     $thead = "<tr>
                <th>No.</th>
                <th>Preview</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Color / Material</th>
                <th>Shipping</th>
                <th>Price</th>
                <th>Total</th>
               </tr>";
     $tbody = "";
     
     $array = $_SESSION[$key];
     $count = count(array_filter($array));
     
     if (!$count){
         echo alertbuilder("Your shopping cart is empty.",'warning');
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
                      sic.name='item' AND si.visibility_id = lv.id AND
                      lv.name='visible' AND 
                      si.id IN ($arr)
                ORDER BY 
                      si.id DESC;";
        $ret = $database->query($sql) or 
        die(alertbuilder($database->error . " -> " . $sql,'danger'));
        
        if (!$ret || !$ret->num_rows){
            // nothing to show
            echo alertbuilder("Your shopping cart is empty.",'warning');
        } else {
            $idx = 0;
            $total = 0;
            $quant = 0;
            
            while ($row = $ret->fetch_array()){
                $idx++;
                
                $id     = $row['item_id'];
                $title  = $row['title'];
                $desc   = $row['description'];
                $price  = floatval($row['cost']);
                $shipping=  floatval($row['shipping']);
                $img1   = $row['url_image_1'];
                $cat    = strtolower($row['cat']);
                
                $quantity = $_SESSION[$key][$id]['quantity'];
                $quant += $quantity;
                
                $grand = ($price + $shipping) * $quantity;
                $color_mat = $_SESSION[$key][$id]['extra'];
                
                $tbody .= "<tr>
                             <td>$idx.</td>
                             <td>
                                 <a href='$img1' data-title=\"$title - $price\" class='fancybox'><img src='$img1' style='width:80px' class='img-thumbnail'></a>
                             </td>
                             <td>
                                 <b><a href='?view=item&id=$id'>$title</a></b> <BR> 
                                 <small>$desc</small>
                             </td>
                             <td>$quantity</td>
                             <td>$color_mat</td>
                             <td><b>$shipping</b></td>
                             <td>$price</td>
                             <td><b>$grand</b></td>
                           <tr>";
                           
                $total +=  floatval($grand);            
            }
            
            // sum up
            $tbody .= "<tr>
                        <td colspan='4'></td>
                        <td>$quant Items</td>
                        <td></td>
                        <td></td>
                        <td></td>
                       </tr>
                       ";
                       
            echo "<div class='row'>
                   <div class='col-md-9'>
                      
                   </div>
                   <div class='col-md-3'>
                      <h3>Total: $total</h3>
                      
                    </div>
                 </div>
                 <HR>
                 <div class='row'>
                     <div class='col-md-12'>
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
                      </div>
                  </div>
                   ";
        }
     }
?>
