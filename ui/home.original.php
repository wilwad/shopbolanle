<style>
 body {background: #FFF; }
/* override height of the primary corporate banner on home page*/
.logo-banner-image {
    height: 150px;
}    

 .carousel-control.left,
 .carousel-control.right{
    background-image: none;
    background-image: none;
    background-image: none;
    background-image: none;
    filter: none;
}
</style>
<style>
.carousel.fade {
  opacity: 1;
}
.carousel.fade .item {
  transition: opacity ease-out .7s;
  left: 0;
  opacity: 0; /* hide all slides */
  top: 0;
  position: absolute;
  width: 100%;
  display: block;
}
.carousel.fade .item:first-child {
  top: auto;
  opacity: 1; /* show first slide */
  position: relative;
}
.carousel.fade .item.active {
  opacity: 1;
}

#itemslider h4{
  font-family: 'Josefin Sans', sans-serif;
  font-weight: 400;
  font-size: 12px;
  margin: 10px auto 3px;
}
#itemslider h5{
  font-family: 'Josefin Sans', sans-serif;
  font-weight: bold;
  font-size: 12px;
  margin: 3px auto 2px;
}
#itemslider h6{
  font-family: 'Josefin Sans', sans-serif;
  font-weight: 300;;
  font-size: 10px;
  margin: 2px auto 5px;
}
.badge {
  background: #b20c0c;
  position: absolute;
  height: 40px;
  width: 40px;
  border-radius: 50%;
  line-height: 31px;
  font-family: 'Josefin Sans', sans-serif;
  font-weight: 300;
  font-size: 14px;
  border: 2px solid #FFF;
  box-shadow: 0 0 0 1px #b20c0c;
  top: 5px;
  right: 25%;
}
#slider-control img{
  padding-top: 60%;
  margin: 0 auto;
}
@media screen and (max-width: 992px){
#slider-control img {
  padding-top: 70px;
  margin: 0 auto;
}
}

.carousel-showmanymoveone .carousel-control {
  width: 0%;
  background-image: none;
  color:red;
}
.carousel-showmanymoveone .carousel-control.left {
  margin-left: 5px;
}
.carousel-showmanymoveone .carousel-control.right {
  margin-right: 5px;
}
.carousel-showmanymoveone .cloneditem-1,
.carousel-showmanymoveone .cloneditem-2,
.carousel-showmanymoveone .cloneditem-3,
.carousel-showmanymoveone .cloneditem-4,
.carousel-showmanymoveone .cloneditem-5 {
  display: none;
}
@media all and (min-width: 768px) {
  .carousel-showmanymoveone .carousel-inner > .active.left,
  .carousel-showmanymoveone .carousel-inner > .prev {
    left: -50%;
  }
  .carousel-showmanymoveone .carousel-inner > .active.right,
  .carousel-showmanymoveone .carousel-inner > .next {
    left: 50%;
  }
  .carousel-showmanymoveone .carousel-inner > .left,
  .carousel-showmanymoveone .carousel-inner > .prev.right,
  .carousel-showmanymoveone .carousel-inner > .active {
    left: 0;
  }
  .carousel-showmanymoveone .carousel-inner .cloneditem-1 {
    display: block;
  }
}
@media all and (min-width: 768px) and (transform-3d), all and (min-width: 768px) and (-webkit-transform-3d) {
  .carousel-showmanymoveone .carousel-inner > .item.active.right,
  .carousel-showmanymoveone .carousel-inner > .item.next {
    -webkit-transform: translate3d(50%, 0, 0);
    transform: translate3d(50%, 0, 0);
    left: 0;
  }
  .carousel-showmanymoveone .carousel-inner > .item.active.left,
  .carousel-showmanymoveone .carousel-inner > .item.prev {
    -webkit-transform: translate3d(-50%, 0, 0);
    transform: translate3d(-50%, 0, 0);
    left: 0;
  }
  .carousel-showmanymoveone .carousel-inner > .item.left,
  .carousel-showmanymoveone .carousel-inner > .item.prev.right,
  .carousel-showmanymoveone .carousel-inner > .item.active {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
    left: 0;
  }
}
@media all and (min-width: 992px) {
  .carousel-showmanymoveone .carousel-inner > .active.left,
  .carousel-showmanymoveone .carousel-inner > .prev {
    left: -16.666%;
  }
  .carousel-showmanymoveone .carousel-inner > .active.right,
  .carousel-showmanymoveone .carousel-inner > .next {
    left: 16.666%;
  }
  .carousel-showmanymoveone .carousel-inner > .left,
  .carousel-showmanymoveone .carousel-inner > .prev.right,
  .carousel-showmanymoveone .carousel-inner > .active {
    left: 0;
  }
  .carousel-showmanymoveone .carousel-inner .cloneditem-2,
  .carousel-showmanymoveone .carousel-inner .cloneditem-3,
  .carousel-showmanymoveone .carousel-inner .cloneditem-4,
  .carousel-showmanymoveone .carousel-inner .cloneditem-5,
  .carousel-showmanymoveone .carousel-inner .cloneditem-6  {
    display: block;
  }
}
@media all and (min-width: 992px) and (transform-3d), all and (min-width: 992px) and (-webkit-transform-3d) {
  .carousel-showmanymoveone .carousel-inner > .item.active.right,
  .carousel-showmanymoveone .carousel-inner > .item.next {
    -webkit-transform: translate3d(16.666%, 0, 0);
    transform: translate3d(16.666%, 0, 0);
    left: 0;
  }
  .carousel-showmanymoveone .carousel-inner > .item.active.left,
  .carousel-showmanymoveone .carousel-inner > .item.prev {
    -webkit-transform: translate3d(-16.666%, 0, 0);
    transform: translate3d(-16.666%, 0, 0);
    left: 0;
  }
  .carousel-showmanymoveone .carousel-inner > .item.left,
  .carousel-showmanymoveone .carousel-inner > .item.prev.right,
  .carousel-showmanymoveone .carousel-inner > .item.active {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
    left: 0;
  }
}

  h1.one {
    margin-top: 0;
  }

h1.one:before {
    content: "";
    display: block;
    border-top: solid 1px black;
    width: 100%;
    height: 1px;
    position: absolute;
    top: 25px;
    z-index: 1;
  }
  
  h1.one span, h1.two span {
    background: #fff;
    padding: 0 20px;
    position: relative;
    z-index: 5;
  }
</style>

<div class='row hidden-xs'>
    <div class='col-md-2 hidden-xs'>

    </div>     
    <div class='col-md-8 col-xs-12'>
        <div id="carousel-right" class="carousel fade" data-ride="carousel">
          
        <?php 
            $items      = "";
            $indicators = "";
            
             $sql = "SELECT * 
                     FROM store_items si, 
                          store_item_categories sic,
                          list_visibility v
                     WHERE 
                          sic.id = si.store_item_category_id AND 
                          sic.name='banner' AND 
                          si.visibility_id = v.id AND 
                          v.name = 'visible'
                     ORDER BY 
                          si.id DESC;";
             $ret = $database->query($sql) or die($database->error);
             
             if (!$ret || !$ret->num_rows){
                 // nothing
             } else {
                 $idx = 0;
                 
                 while ($row = $ret->fetch_array()){
                 
                        $active   = $idx == 0 ? 'active' : '';
                    $url_image = $row['url_image_1'];
                    
                        $items .= "<div class='item $active' style='height:300px; background-size: contain !important; background: url($url_image) no-repeat center center;'>
                        		      <!-- img src='$url_image' alt='Slide image' -->
                    		       </div>";
                            			
                    	$indicators .= "<li data-target='#carousel-right' data-slide-to='$idx' class='$active'></li>";
                        	
                                $idx++;     
                 }
             }
     
                ?>
                
                <!-- Indicators -->
                <ol class="left carousel-indicators">
                 <?php echo $indicators; ?>
                </ol>
                  
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                 <?php echo $items; ?>
                </div>
                
            <!-- controls -->
            <!--
    	    <a class="left carousel-control" href="#carousel-right" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
    	    <a class="right carousel-control" href="#carousel-right" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    	    -->
            </div>
     </div> 
    <div class='col-md-2 hidden-xs'>

    </div> 
</div>

<div class='row' style=''>
    <div class='col-md-12' style=''>
        <h3 class='text-center line'>On Display</h3>
    </div>
</div>
<?php require_once('carousel.php'); ?>
<BR>
<div class='row' style=''>
    <div class='col-md-12' style=''>
        <h3 class='text-center line'><span class='fa fa-fw fa-star' style='color:#ab7112'></span> Featured</h3>
    </div>
</div>
<?php require_once('items-home.php'); ?>
<script>
// Carousel Auto-Cycle
  $(document).ready(function() {
    $('.carousel').carousel({
      interval: 5000
    });
    
  });
  
(function(){

  $('#itemslider').carousel({ interval: 3000 });
}());

$(window).on('load',function(){
   console.log('iframe src set by window.on.load event')
});
 </script>