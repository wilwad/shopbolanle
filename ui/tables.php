<?php
 // params
 
 // defs
 $key = 'shopping_cart';
 
 // icons
 $fa_eye  = font_awesome('fa-eye');
 $fa_cart = font_awesome('fa-shopping-cart');
?>

<div class='row'>
    <div class='col-md-12'>
        <ul class='breadcrumb'>
            <li><a href='?view=all' style='text-transform:uppercase;'>All</a></li>
            <li style='text-transform:uppercase;'>Tables</li>
        </ul>
    </div>
</div>   
<div class='row'>
    	<!-- BEGIN PRODUCTS -->
        <?php
         $store_items = "";
         
         $sql = "SELECT *, si.id AS item_id
                 FROM store_items si, 
                      store_item_categories sic,
                      categories cat,
                      list_visibility lv
                 WHERE 
                      sic.id = si.store_item_category_id AND 
                      sic.name='item' AND si.visibility_id = lv.id AND
                      si.category_id = cat.id AND cat.name='tables' AND
                      lv.name='visible'
                 ORDER BY 
                      si.id DESC;";
         $ret = $database->query($sql) or die(alertbuilder($database->error,'danger'));
         if (!$ret || !$ret->num_rows){
             echo "<div class='col-md-12'>
             	   <i>We have no items at the moment. Please check back later.</i>
             	  </div>";
         } else {
             $idx = 0;
             $rand = 3;
             //$arr_rand = array(3,6);
             
             while ($row = $ret->fetch_array()){
                $id        = $row['item_id'];
                $entrydate = $row['entrydate'];
                $url_image = $row['url_image_1'];
                $title     = $row['title'];
                $desc      = $row['description'];
                $cost      = $row['cost'];
                $discount  = $row['discount'];
                $note      = "";

               $cart = (!array_key_exists($id, $_SESSION[$key])) ? "<a href='?view=item&action=a&id=$id' style='color:green'>$fa_cart Add to quote</a>" : "<a href='?view=item&action=r&id=$id' style='color:red'>$fa_cart Remove</a>";
               
                // in  store
                //if (!$viewlimit)
                  $rand = 3; //(($idx+1) % 5 == 0) ? 6 : 3;
                //$rand = 3;
                //$rand = $arr_rand[array_rand($arr_rand)];
            $bordertop = $idx < 4 ? 'border-top: 1px solid #e6e5df;':'';
            
                $store_items .= 
            "<div class='col-md-$rand col-sm-6' style='$bordertop border-right: 1px solid #e6e5df; border-bottom: 1px solid #e6e5df;'>
    		<span class='thumbnail'>
                <a href='$url_image' class='fancybox' data-title=\"$title\"><div class='lazy image-container' data-src=\"$url_image\"
                style='background-size: contain !important;  height: 200px; background-repeat: no-repeat;
                    background-position: center center;'></div></a>

      			<h4 style='padding-top:10px;'>$title <!-- <BR> <small>$desc</small>-->
      			</h4>
      			<hr class='line'>
      			
      			<div class='row'>
      				<div class='col-md-12'>
      				 <a href='?view=item&id=$id''>$fa_eye View Details</a><BR>
      				 $cart
      				</div>
      			</div>
    		</span>
              </div>";
              
                $idx++;
             }
             
             echo $store_items;
         }
             
        ?>

	<!-- END PRODUCTS -->   
</div>		