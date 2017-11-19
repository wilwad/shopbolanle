<link href='lib/marquee/assets/css/style.css' rel='stylesheet'>
<script src='lib/marquee/assets/js/crawler.js'></script>

<div class='row'>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="carousel carousel-showmanymoveone slide" id="itemslider">
        <div class="carousel-inner">

        <?php
            $marquee = "";
            
            $sql = "
                 SELECT *, si.id AS item_id
                 FROM store_items si, 
                      store_item_categories sic,
                      categories cat,
                      list_visibility lv
                 WHERE 
                      sic.id = si.store_item_category_id AND 
                      sic.name='item' AND si.visibility_id = lv.id AND
                      si.category_id = cat.id AND
                      lv.name='visible'
                 ORDER BY RAND()
                      LIMIT 15";
            $ret = $database->query($sql) or die("Error: " . $database->error);
            if (!$ret || !$ret->num_rows){
                //
            } else {
                $buff = "";
                $idx  = 1;
                
                while ($row = $ret->fetch_array()){
   			        $active    = $idx == 1 ? 'active' :  '';
   			        $id        = $row['item_id'];
   			        $url_image = $row['url_image_1'];
   			        $title     = $row['title'];

    				$marquee .= "<a href='?view=item&id=$id'><img src='$url_image' 
    				                class='img-responsive' style='height:90px; margin-right:15px; border:1px solid #efefef;' /></a>";
                          
                    $idx++;
                }
            }
    	?>
	
        </div>

      <!-- Left and right controls
      <a class="left carousel-control" href="#itemslider" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#itemslider" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Next</span>
      </a>
      -->
      </div>
    </div> <!-- col -->
    
</div> <!-- row -->

<div class='row'>
 <div class='col-md-12'>
    <div id="mycrawler2" class="productswesupport">
        <?php echo $marquee; ?>
    </div>
 </div> <!-- col-md-12 -->
</div> <!-- row -->

<script>
 $(document).ready(function(){
  marqueeInit({
        uniqueid: 'mycrawler2',
        style: {
             
        },
        inc: 1, //speed - pixel increment for each iteration of this marquee's movement
        mouse: 'pause', //mouseover behavior ('pause' 'cursor driven' or false)
        moveatleast: 2,
        neutral: 150,
        savedirection: true,
        random: true
    });
 });
</script>	