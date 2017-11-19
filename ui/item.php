<style>
    ul.social {list-style:none; padding-top: 15px; margin-left: -40px;}
    ul.social li {display:inline; padding: 0;}
    ul.social li a {display:inline-block; margin:0 auto; -moz-border-radius:50%; -webkit-border-radius:50%; border-radius:50%; text-align:center; width: 30px; height: 30px; position:relative; background-color: #D3D3D3;}
    ul.social li a i {color:#FFF; text-align: center; margin:0; line-height:30px; -webkit-transition: all 0.8s; -moz-transition: all 0.8s; -o-transition: all 0.8s; -ms-transition: all 0.8s; transition: all 0.8s;}

    .social li a.rss {background-color: #F56505;}
    .social li a.face {background-color:#3B5998;}
    .social li a.twit {background-color:#33ccff;}
    .social li a.google {background-color:#BD3518;}
    .social li a.linkedin {background-color:#007bb7;}
</style>
<?php
 // parameters
 $id = (int) $_GET['id'];
 $action = @ $_GET['action'];
 
 // icons
 $fa_eye  = font_awesome('fa-eye');
 $fa_cart = font_awesome('fa-shopping-cart');
 $fa_remove = font_awesome('fa-remove');
 
 // icons
 $fa_eye     = font_awesome('fa-eye');
 $fa_cart    = font_awesome('fa-shopping-cart');
 $fa_info    = font_awesome('fa-info-circle');
 $fa_check   = font_awesome('fa-check');
 $fa_exclaim = font_awesome('fa-exclamation-triangle');

//$_SESSION[$key] = array();

 switch ($action){
     case 'a':
         // add item to cart
         if (!array_key_exists($id, $_SESSION[$key])){
             $_SESSION[$key][$id] = array('quantity'=>1, 
                                          'extra'=>'');
                                       
             echo alertbuilder("$fa_check Item was added to your quote.", 'success');
             
            die("<script>window.location.href='?view=$view&id=$id';</script>");
             
         } else {
             echo alertbuilder("$fa_exclaim That item is already in your quote.", 'warning');
         }
         break;
         
     case 'r':
        if (array_key_exists($id, $_SESSION[$key])) {
            unset($_SESSION[$key][$id]);

            if (!array_key_exists($id, $_SESSION[$key])){
                echo alertbuilder("$fa_info Item was removed from your quote", 'success');   
                die("<script>window.location.href='?view=$view&id=$id';</script>");
            }
            else {
                 echo alertbuilder("$fa_exclaim Failed to remove item from your quote.", 'warning');  
            }
        } else {
             echo alertbuilder("$fa_exclaim That item is not in your quote.", 'warning');
        }
         break;                 
 } 
?>

   <?php
         $sql = "SELECT *, cat.name AS category 
                 FROM store_items si, 
                      store_item_categories sic,
                      categories cat,
                      list_visibility lv
                 WHERE 
                      sic.id = si.store_item_category_id AND 
                      sic.name='item' AND 
                      si.id = $id AND
                      cat.id = si.category_id AND
                      si.visibility_id = lv.id AND
                      lv.name='visible' 
                 ORDER BY 
                      si.id DESC;";
         $ret = $database->query($sql) or 
         die(alertbuilder($database->error,'danger'));
         
         if (!$ret || !$ret->num_rows){
             die(alertbuilder("$fa_exclaim <b>Item not found.</b>",'danger'));
         } 
         
        $row = $ret->fetch_array();
        $entrydate = $row['entrydate'];
        $url_image_1 = $row['url_image_1'];
        $url_image_2 = $row['url_image_2'];
        $url_image_3 = $row['url_image_3'];
        $url_image_4 = $row['url_image_4'];
        $url_video   = $row['url_video'];
        $title     = $row['title'];
        $desc      = $row['description'];
        $cost      = $row['cost'];
        $discount  = $row['discount'];
        $cat       = $row['category'];
        $material  = $row['material'];
        $shipping  = $row['shipping'];
        $cat       = strtolower($cat);
        $cat_      = ucwords($cat);
        $note      = "";
           
        $url_image_2_ = $url_image_2 == "" ? "" : "<img src='$url_image_2' class='img-thumbnail occupy-primary' style='cursor:pointer; max-height:200px;'><BR>";
        $url_image_3_ = $url_image_3 == "" ? "" : "<img src='$url_image_3' class='img-thumbnail occupy-primary' style='cursor:pointer; max-height:200px;'><BR>";
        $url_image_4_ = $url_image_4 == "" ? "" : "<img src='$url_image_4' class='img-thumbnail occupy-primary' style='cursor:pointer; max-height:200px;'><BR>";
        $url_video_ = $url_video    == "" ? "" : "<a href='$url_vide' class='fancybox.iframe'><img src='images/video-play.png' style='max-height:180px;'></a>";
        
        $cart = array_key_exists($id, $_SESSION[$key]) ? "<a href='?view=$view&action=r&id=$id' class='btn btn-warning'>$fa_remove Remove from Quote</a>" : "<a href='?view=$view&action=a&id=$id' class='btn btn-primary'>$fa_cart Add to Quote</a>";
        
        if (strpos($cart,'Remove')){
            $cart .= " <a href='?view=cart' class='btn btn-success'>$fa_cart Checkout</a>";
        }
        $title_ = $title;
        $description_ = "Shop genuine African wood tables, chairs, paintings and more.";
        $url_ = "https://www.shopbolanle.com/?view=item&amp;id=$id";
        $picture_ = "$url_image_1";
        
			 $fb_url = "https://www.facebook.com/sharer/sharer.php?s=100&description=$description_&title=$title&u=$url_&picture=$picture_";
			 $tw_url = "http://twitter.com/share?text=$title_&url=$url_";
			 $gp_url = "https://plus.google.com/share?url=$url_";
			 $lk_url = "https://www.linkedin.com/shareArticle?mini=true&url=$url_&title=$title&summary=$desc&source=";
			 $pi_url = "https://pinterest.com/pin/create/button/?url=$url_&media=$picture_&description=$title";
			 
        echo  
        "
        <div class='row'>
            <div class='col-md-12'>
                <ul class='breadcrumb'>
                    <li>Items</li>
                    <li><a href='?view=$cat'>$cat_</a></li>
                    <li>$title</li>
                </ul>
            </div>
        </div>
        <div class='row'>
            <div class='col-md-2'>
             <img src='$url_image_1' class='img-thumbnail occupy-primary' style='max-height:200px; cursor:pointer;'><BR>
                $url_image_2_
                $url_image_3_
                $url_image_4_
                $url_video_             
            </div>
            <div class='col-md-6 col-sm-4'>
                <img class='img-responsive zoom' id='primary' style='max-height:700px;' src=\"$url_image_1\">
      		 </div>
            <div class='col-md-4'>
                <h3>$title<h3>
                <h3><small>$desc</small></h3>
                
                <BR>
                $cart
                <!--
                <BR><BR>
                <hr class='line'>
              <ul class='social'>
                    <li><a href='$fb_url' target='_blank' class='face' title='Facebook'><i class='fa fa-facebook'></i></a></li>
                    <li><a href='$tw_url' target='_blank' class='twit' title='Twitter'> <i class='fa fa-twitter'></i></a></li>
                    <li><a href='$gp_url' target='_blank' class='google'  title='Google +'><i class='fa fa-google-plus'></i></a></li>
                    <li><a href='$lk_url' target='_blank' class='linkedin' title='Linkedin'><i class='fa fa-linkedin'></i></a></li>
                    <li><a href='$pi_url' target='_blank' class='pintererst' title='Pinterest'><i class='fa fa-pinterest'></i></a></li>
              </ul>                
              -->  
            </div>  
        </div>
        <script>
         $(document).ready(function(){
            $('#nav-main').find('li').each(function(idx){
              if ($(this).text().toLocaleLowerCase().trim() == '$cat'){
                  $(this).addClass('active');
                  return false;
              }
            });
            
            $('.occupy-primary').on('click', function(e){
             var url = $(this).prop('src');
            $(this).addClass('active');
             $('#primary').attr('src',url);
             $('.zoom').elevateZoom();
            });            
         });
        </script>
  		 ";
        ?>
<script>
    $(document).ready(function(){
        $(".zoom").elevateZoom();
    });
</script>