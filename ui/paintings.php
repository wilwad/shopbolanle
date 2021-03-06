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
            <li style='text-transform:uppercase;'>Paintings</li>
        </ul>
    </div>
</div>

<div class='row'>
    <div class='col-md-8 border-right'>
        <h3 class='text-center line'>Bolanle is pleased to introduce</h3>
        <p>
            <img src='images/papa.jpg' class='img-responsive' style='float:left; padding-right:10px'> <strong>Ndasuunje Shikongeni</strong>, popularly known as “Papa”, is an internationally and nationally
renowned artist, as well as a storyteller, spiritual believer and leader in Namibia's post-
independence arts education and culture. He fought for his country's independence from the
late 1980's to early 1990s, but now uses art, not the gun, as a means of raising
consciousness and liberating people's minds.</p>
<p>Papa's skills in the visual arts are extensive,
having sculpted in wood and papier-mâché, pioneered novel techniques in printmaking using
cardboard and painting ink on paper and linoleum, as well as pioneering an innovative
technique using stretched zips on a frame.</p>
<p>
Early in 1993, Papa met and worked with his
mentor, Joseph Madisia, a prominent African artist in Namibia.
Joseph nurtured Papa's artistic skills, helping him expand his knowledge and giving him room to develop his own
original techniques in printmaking. Over the past decade, Papa's works, depicting different
aspects of Namibian culture and traditions, and have been showcased around the world.
Papa has held a total of 19 solo exhibitions globally.</p>
<p>He uses art to promote freedom of self-
expression and individual spirituality, highlighting the importance of tradition and culture in a
sustainable and economical way.</p> 
<p>His works are held by various public and private
institutions, namely Namibian Embassies, NamWater, Namcol, Bank of Namibia, NAGN,
UNAM, Supreme Court and Gamsberg MacMillan, Old Mutual, and Government buildings,
as well as globally.</p>
    </div>

    <div class='col-md-4'>
    	<div class="embed-responsive embed-responsive-16by9">
    <video controls src="videos/video-papa.mp4" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></video>
    	</div>  
    </div>    

</div>

<HR>
    
<div class='row'>
    	<!-- BEGIN PRODUCTS -->
        <?php
         $store_items = "";
         
         $sql = "SELECT * , si.id AS item_id
                 FROM store_items si, 
                      store_item_categories sic,
                      categories cat,
                      list_visibility lv
                 WHERE 
                      sic.id = si.store_item_category_id AND 
                      sic.name='item' AND si.visibility_id = lv.id AND
                      si.category_id = cat.id AND cat.name='paintings' AND
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