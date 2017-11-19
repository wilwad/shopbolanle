<?php
if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || 
   $_SERVER['HTTPS'] == 1) ||  isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
{
   $www = substr(strtolower($_SERVER['HTTP_HOST']),0,4)== 'www.' ? '' : 'www.';
   $redirect = "https://$www" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   header('HTTP/1.1 301 Moved Permanently');
   header('Location: ' . $redirect);
   exit();
}
?>
<?php 
 /*
  * Shopbolanle Website
  *
  * William Sengdara -- william.sengdara@gmail.com
  * Copyright (c) 2017
  *
  * Created:
  * Updated: 10 January 2017
  *
  * This is the host (entry point) for this system
  */
  
  // shopping cart & contact us form captcha
  @ session_start();
  
  $key = 'shopping_cart';
  $_SESSION[$key] = !isset($_SESSION[$key]) ? array() : $_SESSION[$key];
  
  /* error reporting */
  ini_set('display_startup_errors',1);
  ini_set('display_errors',1);
  error_reporting(-1);

 require_once('timezone.php');
 require_once('settings.php'); 
 require_once('ui.php');  
 
 // switch between container & container-fluid
 $container_type = @ $_GET['view'] == 'home' || @ $_GET['view'] == "" ? "container-fluid" : "container";
?>

<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">

<meta name='description' content="<?php echo settings::description; ?>">
<meta name='keywords' content='<?php echo settings::keywords; ?>'>
<meta name='author' content='William Sengdara (Sengdara IT)'>

<meta name='og:url' content='<?php echo settings::canonical; ?>'>
<meta name='og:title' content='<?php echo settings::title; ?>'>
<meta name='og:type' content='Website'>
<meta name='og:image' content='<?php echo settings::canonical; ?>/og/fb-post.png'>
<meta name='og:description' content="<?php echo settings::description; ?>">
<meta name='og:keywords' content='<?php echo settings::keywords; ?>'>
<meta name='og:author' content='William Sengdara (Sengdara IT)'>

<title>
	<?php 
	    // get the title 
	    $title = settings::title;
		$navheader = $title;
		echo $title; 
		
		// current theme
		$theme = settings::theme;
	?>
</title>

<!-- Canonical -->
<link rel='canonical' href='<?php echo settings::canonical; ?>'>

<!-- Fav Icon and Apple Touch Icons -->
<link rel='icon' href='favicon.ico?t=<?php echo mt_rand();?>' type='image/x-icon'>

<!-- CSS -->
<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
<link href='lib/bootstrap/bootstrap.3.3.4.min.css' rel='stylesheet' type='text/css'>
<link href='bootstrap/themes/<?php echo $theme; ?>/bootstrap.min.css' rel='stylesheet' type='text/css'>
<link href='bootstrap/themes/yeti2/sticky-footer-navbar.css' rel='stylesheet'>
<link href='lib/bootstrap3dialog/css/bootstrap-dialog.min.css' rel='stylesheet'>
<link href='lib/alertifyjs/css/alertify.min.css' rel='stylesheet'>
<link href='lib/alertifyjs/css/themes/bootstrap.min.css' rel='stylesheet'>
<link href='lib/date-picker/css/bootstrap-datepicker3.min.css' rel='stylesheet'>
<link href='lib/tagsinput/bootstrap-tagsinput.css' rel='stylesheet'>
<link href='lib/select2/select2.min.css' rel='stylesheet'>
<link href='lib/select2/select2-bootstrap.css' rel='stylesheet'>
<link href='lib/fancybox/source/jquery.fancybox.css' rel='stylesheet'>
<style>.datepicker{z-index:1200 !important;}</style>
<link href='css/style.css?t=<?php echo mt_rand();?>' rel='stylesheet'>

<style>
.container.hidden-xs.banner-image {
    height: 90px;
    margin-top: 10px;
    background-size: contain !important;
    background: url(images/logo-bolanle-800px.jpg) no-repeat center center;
}    

.navbar-collapse {
  text-align:center;
}
.navbar-nav {
    display:inline-block;
    float:none;
}
</style>
</head>
<body>
  
<?php 
 // params
 $view   = @ $_GET['view'];
 $action = @ $_GET['action'];
 
?>
<div class='container hidden-xs banner-image'></div>
  <nav class='navbar navbar-inverseX navbar-fixed-top'>
	  <div class='container-fluid pop-green'>
		<div class='navbar-header'>
		  <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
			<span class='sr-only'>Toggle navigation</span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
		  </button>
		  <a class='navbar-brand visible-xs' href='?view=home'>
		   <img src='images/navbrand-bolanle-200px.jpg' class='img-responsive'>
		   <?php /*echo $title;*/ ?>
		  </a>
		</div>
		
		<?php 
/*
 * returns file extension
 * lowercased
 */
function get_file_extension($file){
	$tmp = explode('.',$file);
	return strtolower(end($tmp)) ;
}

function font_awesome($icon){
	return "<span class='fa fa-fw $icon'></span>";
}

/*
 * get the default view for the current user
 */
function get_default_view($role, $view){
	$view = trim($view);
	
	if ($view == ""){
		switch ($role) {
			 case 'administrators':
				  $view = 'system';
				  break;
				  
			default:
				  $view = 'home';
				  break;
		}
	}
	
	return $view;

}
	// menu
   $menu = array(
   		array('caption'=>"<span class='fa fa-fw fa-home'></span> Home",
                       'url'=>'home'),
        array('caption'=>"All", 'url'=>'all'),
   		array('caption'=>"Tables", 'url'=>'tables'),
		array('caption'=>"Chairs",'url'=>'chairs'),     
		array('caption'=>"Mirrors",'url'=>'mirrors'), 
        array('caption'=>"Crafts", 'url'=>'crafts'),		
		array('caption'=>"Paintings",'url'=>'paintings'),
		array('caption'=>"Payment & Shipping",'url'=>'shipping'),	
		array('caption'=>'About Us','url'=>'about'),
		array('caption'=>"Contact Us",'url'=>'contact')		
		);

?>

		<!-- login menu start -->
		<div class="collapse navbar-collapse" id="navbar">
		  <ul class="nav navbar-nav navbar-left" id='nav-main'>
			  <?php
				$view    = @ $_GET['view'];
				if (!strlen(trim($view)))
				    $view = "home";
			  
			    foreach($menu as $menuitem){
						 $caption = $menuitem['caption'];
						 $url     = $menuitem['url'];
			    	    $active  = $view == $url ? "class='active'" : "";

		             echo "<li $active>
					             <a href='?view=$url' style='text-transform:uppercase;' 
					                id='$url' class='text-shadow' title='' data-toggle='tooltip' data-placement='bottom'>
					              $caption
				                 </span></a>
					          </li>";	

			    }
			   	
			   	// get total items in cart
			   	$key   = "shopping_cart";
			   	$array = $_SESSION[$key];
			   	$count = count(array_filter($array));
			   	$cart = $count ? "<span style='color:orange'><b>$count</b> Items in quote cart</span>" : "Quote cart is empty";
			  ?>
			  </ul>
			  <?php
			   if ($view == 'home'){
			       // do not show cart
			   } else {
              ?>
			  <ul class='nav navbar-nav navbar-right'>
                <li>
		             <a href='?view=cart' class='text-shadow' title='' data-toggle='tooltip' data-placement='bottom'>
		              <span class='fa fa-fw fa-shopping-cart'></span> <small><?php echo $cart; ?></small></a>
		        </li>
			 </ul>              
              <?php
			   }
			  ?>
			</div>
	  </div>
	</nav>



<!--[if lt IE 9]>
<script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
<script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>
<![endif]-->

<!-- Javascript -->
<script src='lib/jquery/jquery.1.11.1.min.js'></script>
<script src='lib/bootstrap/bootstrap.3.3.4.min.js'></script>
<script src='lib/bootstrap3dialog/js/bootstrap-dialog.min.js'></script>		
<script src='lib/datatables/js/jquery.dataTables.min.js'></script>		
<script src='lib/alertifyjs/js/alertify.min.js'></script>	
<script src='lib/b3-typeahead/bootstrap3-typeahead.min.js'></script>
<script src='lib/tagsinput/bootstrap-tagsinput.min.js'></script>
<script src='lib/tabcollapse/bootstrap-tabcollapse.min.js'></script>
<script src='lib/date-picker/js/bootstrap-datepicker.min.js'></script>
<script src='lib/select2/select2.min.js'></script>
<script src='lib/timeago/jquery.timeago.js'></script>
<script src='lib/fancybox/source/jquery.fancybox.js'></script>
<script src="https://cdn.rawgit.com/igorlino/elevatezoom-plus/1.1.20/src/jquery.ez-plus.js"></script>
<script src="lib/elevatezoom/jquery.elevateZoom-3.0.8.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/10.3.0/lazyload.min.js"></script>
<script src='js/script.js?t=<?php echo mt_rand();?>'></script>

   <script>
		$(document).ready(function(){
			console.log('This website designed & developed by William Sengdara (www.SengdaraIT.com)');
			
		     $(window).scroll(function () {
		            if ($(this).scrollTop() > 50) {
		                $('#back-to-top').fadeIn();
		            } else {
		                $('#back-to-top').fadeOut();
		            }
		        });
		        // scroll body to 0px on click
		        $('#back-to-top').click(function () {
		            $('#back-to-top').tooltip('hide');
		            $('body,html').animate({
		                scrollTop: 0
		            }, 800);
		            return false;
		        });
		
		});
   </script>
   
   <a id="back-to-top" style='z-index:999;' href="#" class="pull-right btn btn-default back-to-top" 
      role="button" title="Scroll to top" 
      data-toggle="tooltip" data-placement="top">
      <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
       
    <!--
    <div id='social' class="hidden-xs">
        <a href='https://www.facebook.com/Khomas-Grove-Mall-567832473324243/' target='_blank'>
         <div class='social-icon bg-facebook'>
          <span class='fa fa-facebook'></span>
         </div>
	    </a>
	
        <a href='?view=location'>
         <div class='social-icon' style='background: #c35946'>
          <span class='fa fa-map-marker'></span>
         </div>
        </a>
        
        <a href='?view=about'>
         <div class='social-icon' style='background: #846b56'>
          <span class='fa fa-clock-o'></span>
         </div>
        </a>
    </div> 
    -->
    
  <div class='container-fluid' id='container'>
      <!-- p class='alert alert-warning'><span class='fa fa-fw fa-info-circle'></span> This website is currently under maintenance.</p -->
	 <?php 
	  /*
	   * left panel (menu)
	   */
	 // require_once('menu.php');
	 ?>
	 <?php 
	  /*
	   * right panel menu
	   */
	  require_once('body.php');
	 ?>	
   
   <div class="row">
    <div class="col-md-12" style="padding-top:20px">
     <div class="bottom-shadow">&nbsp;</div>
     <h4 class='muted text-center'><small><?php echo settings::copyright; ?></small></h4>
    </div>   
   </div>
      
  </div><!-- container -->
    <script>
     var myLazyLoad;
     
        $(window).load(function(){
            myLazyLoad = new LazyLoad({
                elements_selector: ".lazy"
            });
        });
    </script>  
 </body>
</html>
