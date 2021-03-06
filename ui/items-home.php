<?php
 // icons
 $fa_eye  = font_awesome('fa-eye');
 $fa_cart = font_awesome('fa-shopping-cart');
?>
<style>
span.thumbnail {
    border-radius: 0px !important;
    padding: 15px;
    min-height: initial;
    max-height: initial;
    background: #FFF;
}    
</style>
<div class='row'>
        <BR>
    	<!-- BEGIN PRODUCTS -->
        <?php
         $store_items = "";
         $limit = 10;
         if (@ $items_limit){
             $limit = $items_limit;
         }
         
         $sql = "SELECT *, si.id AS item_id, cat.name AS category
                 FROM store_items si, 
                      store_item_categories sic,
                      categories cat,
                      list_visibility lv
                 WHERE 
                      sic.id = si.store_item_category_id AND 
                      sic.name='item' AND 
                      cat.id = si.category_id AND
                      si.visibility_id = lv.id AND
                      lv.name='visible'
                 ORDER BY 
                      RAND()
                 LIMIT $limit;";
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
                $cat       = strtolower($row['category']);
                $cat_      = ucwords($cat);                
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

      			<h4 style='padding-top:10px;' class='text-center'>$title <BR>
      			 <small><a href='?view=$cat'>In $cat_</a></small>
      			 <!-- <BR> <small>$desc</small>--></h4>
      			 <!--
      			<hr class='line'>
      			
      			<div class='row'>
      				<div class='col-md-12'>
      				 <a href='?view=item&id=$id''>$fa_eye View Details</a><BR>
      				 $cart
      				</div>
      			</div>
      			-->
    		</span>
              </div>";
                
                $idx++;
             }
             
             echo $store_items;
         }
             
        ?>

	<!-- END PRODUCTS -->   
</div>		