<style>


 .row-separator {
            padding-top: 15px;
            padding-bottom: 15px;
            
 }
 
 .gray {
            background: #F4F4F4;
            border-top: 1px solid #e2e2e2;  
            border-bottom: 1px solid #e2e2e2;  
 }
</style>
<style>
/* original solution by https://codepen.io/Rowno/pen/Afykb */

.carousel-fade .carousel-inner .item {
    -webkit-transition-property: opacity;
    transition-property: opacity;
}
.carousel-fade .carousel-inner .item,
.carousel-fade .carousel-inner .active.left,
.carousel-fade .carousel-inner .active.right {
    opacity: 0;
}
.carousel-fade .carousel-inner .active,
.carousel-fade .carousel-inner .next.left,
.carousel-fade .carousel-inner .prev.right {
    opacity: 1;
}
.carousel-fade .carousel-inner .next,
.carousel-fade .carousel-inner .prev,
.carousel-fade .carousel-inner .active.left,
.carousel-fade .carousel-inner .active.right {
    left: 0;
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
}
.carousel-fade .carousel-control {
    z-index: 2;
}


/* carousel fullscreen */

.carousel-fullscreen .carousel-inner .item {
    height: 100vh; 
    min-height: 600px; 
    background-position: center center; 
    background-repeat: no-repeat; 
    background-size: cover;
}


/* carousel fullscreen - vertically centered caption*/

.carousel-fullscreen .carousel-caption {
    top: 75%;  
    bottom: auto;
    -webkit-transform: translate(0, -50%); 
    -ms-transform: translate(0, -50%); 
    transform: translate(0, -50%);
}

/* overlay for better readibility of the caption  */

.overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background: #000;
    opacity: 0.3;
    transition: all 0.2s ease-out;
}


/* demo typography */

h1,h2,h3,h4 {
    font-weight: 700;
}

.super-heading {
    font-size: 70px; 
}


.super-paragraph {
    font-size: 16px;
    text-align: center;
    width: 325px;
    color: #151414;
    padding: 10px;
    opacity: 1;
    text-shadow: initial;
    background: rgba(255, 255, 255, 0.79);
}

.carousel-caption .super-paragraph a,
.carousel-caption .super-paragraph a:hover
{
    color: #fff;
}
</style>
<?php
 $images = array(
                array("url"=>"https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/22729218_136856126965361_2732634957695979970_n.jpg?oh=5ba8a4abf958c257a35887e19b2efb12&oe=5A6390A7",
                      "caption"=>"Bolanle was founded by two young Namibians who aim to improve the lives of Namibian artisans and take Africa to the world."),
                array("url"=>"https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/22729179_136856043632036_3042102020137338823_n.jpg?oh=6b71f4a860a223b19ad91dd6606e7d82&oe=5A71F501",
                     "caption"=>"All of our items are handcrafted with the utmost care and focus on craftsmanship and quality."),
                array("url"=>"https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/22780286_136856066965367_3581022256102059898_n.jpg?oh=78383ab0093c2fb5b9a6e1ab1bd5fe1a&oe=5AAE1E98",
                "caption"=>"The meaning ‘finds wealth at home’ encapsulates the satisfaction and appreciation for the items, both in design and craftsmanship.")
                );
                
    $indicators = "";
    $idx = 0;
    $carid = 1;
    $items = "";
    
    foreach($images as $el=>$data){
        $url = $data['url'];
        $cap = $data['caption'];
        $active = $idx == 0 ? 'active' : '';
        
        $indicators .= "<li data-target='#carousel-$carid' data-slide-to='$idx' class='$active'></li>";
        
        $items .= "<div class='item $active' 
                    style='height:540px;     
                        background-position: center center;
                        background-size: cover !important;
                        background: url($url) no-repeat;'>
                     <div class='overlay'></div>
                     <div class='carousel-caption'>
                       <p class='super-paragraph'>$cap</p>
                     </div>
                    </div>"; 
        $idx++;
    }   

    echo "<div id='carousel-$carid' class='carousel slide carousel-fade' data-ride='carousel'>
            <!-- Indicators -->
            <ol class='carousel-indicators'>
                $indicators
            </ol>
            
            <!-- Wrapper for slides -->
            <div class='carousel-inner' role='listbox'>
                $items
            </div>

            <!-- Controls -->
            <!--
            <a class='left carousel-control' href='#carousel-$carid' role='button' data-slide='prev'>
                <span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span>
                <span class='sr-only'>Previous</span>
            </a>
            <a class='right carousel-control' href='#carousel-$carid' role='button' data-slide='next'>
                <span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span>
                <span class='sr-only'>Next</span>
            </a>
            -->
        </div>";
?>

<div class='row row-separator'>
    <div class='col-md-12' style=''>
        <h2 class='text-center'><a href='?view=all' class=''><span class='fa fa-shopping-cart'></span> SHOP NOW</a></h2>

    </div>
</div>

<?php

   $images = array(
                array("url"=>"https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/22789198_136856020298705_406641962357611349_n.jpg?oh=96b247bed15e726cff8d48b66503137d&oe=5A7C9FE2","caption"=>"The rich Bolanle catalogue contains furnishings for every area of your home. Seamlessly integrating modern advancements with retro details, the bespoke collection adds an elegant and inviting ambiance."),
                array("url"=>"https://scontent.fers1-1.fna.fbcdn.net/v/t1.0-9/22788696_136856100298697_5267392336377618381_n.jpg?oh=b991e966226ca02c9b89a0cecc7c4e7a&oe=5A639887",
                    "caption"=>"Bolanle reflects a continent that takes pride in artistic tradition, overcomes challenges through innovation, and is endlessly creative. The Bolanle goal is to deliver superb handcrafted furniture, timeously, again and again.")
                );
                
    $carid++;
    $indicators = "";
    $idx = 0;
    $items = "";
    
    foreach($images as $el=>$data){
        $url = $data['url'];
        $cap = $data['caption'];
        $active = $idx == 0 ? 'active' : '';
        
        $indicators .= "<li data-target='#carousel-$carid' data-slide-to='$idx' class='$active'></li>";
        
        $items .= "<div class='item $active' 
                    style='height:540px;     
                        background-position: center center;
                        background-size: cover !important;
                        background: url($url) no-repeat;'>
                     <div class='overlay'></div>
                     <div class='carousel-caption'>
                       <p class='super-paragraph'>$cap</p>
                     </div>
                    </div>"; 
        $idx++;
    }   

    echo "<div id='carousel-$carid' class='carousel slide carousel-fade' data-ride='carousel'>
            <!-- Indicators -->
            <ol class='carousel-indicators'>
                $indicators
            </ol>

            <!-- Wrapper for slides -->
            <div class='carousel-inner' role='listbox'>
                $items
            </div>

            <!-- Controls -->
            <!--
            <a class='left carousel-control' href='#carousel-$carid' role='button' data-slide='prev'>
                <span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span>
                <span class='sr-only'>Previous</span>
            </a>
            <a class='right carousel-control' href='#carousel-$carid' role='button' data-slide='next'>
                <span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span>
                <span class='sr-only'>Next</span>
            </a>
            -->
        </div>";    
?>

<div class='row row-separator'>
    <div class='col-md-12'>
        <p>&nbsp;</p>
        <h3 class='text-center'>Our products are proudly made in Africa.</h3>
        <h4 class='text-center'>We deliver worldwide.</h4>
        <p>&nbsp;</p>
        <h2 class='text-center' style='margin-top: 22px;'><a href='?view=contact' class=''><span class='fa fa-envelope'></span> CONTACT US</a></h2>       
    </div>    
</div>

<script>
// Carousel Auto-Cycle
  $(document).ready(function() {
    $('.carousel').carousel({
      interval: 5000,
      pause: false
    });
    
  });
 </script>