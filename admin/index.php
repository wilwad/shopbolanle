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
<!DOCTYPE html>
<?php 
 /*
  * William Sengdara -- william.sengdara@gmail.com
  * Copyright (c) 2016
  *
  * This is the host (entry point) for this system
  */
  
  /* error reporting */
  ini_set('display_startup_errors',1);
  ini_set('display_errors',1);
  error_reporting(-1);
  
 require_once('timezone.php');
 require_once('settings.php'); 
 require_once('ui.php');
 
 $container_type = "container";
?>

<html lang='en'>
<head>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<meta name='description' content='Shop genuine African wood tables, chairs, paintings and more.'>
<meta name='keywords' content='Bolanle, shopbolanle, african, furniture, tables, chairs, paintings'>
<meta name='author' content='William Sengdara'>
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
<link rel='canonical' href='http://localhost/'>

<!-- Fav Icon and Apple Touch Icons -->
<!-- link rel='icon' href='favicon.ico' type='image/x-icon' -->

<!-- CSS -->
<link href='bootstrap/themes/font-awesome/font-awesome.min.css' rel='stylesheet' type='text/css'>
<link href='lib/bootstrap/bootstrap.3.3.4.min.css' rel='stylesheet' type='text/css'>
<!-- link href='lib/bootstrap-paper/bootstrap.min.css' rel='stylesheet' type='text/css' -->
<link href='bootstrap/themes/<?php echo $theme; ?>/bootstrap.min.css' rel='stylesheet' type='text/css'>
<link href='bootstrap/themes/yeti2/sticky-footer-navbar.css' rel='stylesheet'>

<link rel="stylesheet" href="lib/introjs/introjs.min.css">
<!-- link rel="stylesheet" href="lib/introjs/introjs-wall.css" -->
<link href='lib/c3-0.4.10/c3.css' rel='stylesheet' type='text/css'>
<link href='lib/bootstrap3dialog/css/bootstrap-dialog.min.css' rel='stylesheet'>
<link href='lib/datatables/css/jquery.dataTables.min.css' rel='stylesheet'>
<link href='lib/alertifyjs/css/alertify.min.css' rel='stylesheet'>
<link href='lib/alertifyjs/css/themes/bootstrap.min.css' rel='stylesheet'>
<link href='lib/tagsinput/bootstrap-tagsinput.css' rel='stylesheet'>
<link href='lib/introjs/introjs.min.css' rel='stylesheet'>
<link href='lib/select2/select2.min.css' rel='stylesheet'>
<link href='lib/select2/select2-bootstrap.css' rel='stylesheet'>
<link href='lib/date-picker/css/bootstrap-datepicker3.min.css' rel='stylesheet'>
<link href='lib/fancybox/source/jquery.fancybox.css' rel='stylesheet'>
<link href='lib/bootstrap-color-picker/css/bootstrap-colorpicker.min.css' rel='stylesheet'>

<!-- syntax highlight -->
<link rel='stylesheet' href='lib/highlight.js/styles/xcode.css'>
<style>.datepicker{z-index:1200 !important;}</style>
<link href='css/style.css?t=<?php echo mt_rand(); ?>' rel='stylesheet'>

<!--[if lt IE 9]>
<script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
<script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>
<![endif]-->

<!-- Javascript -->
<script src='lib/jquery/jquery.1.11.1.min.js'></script>
<script src='lib/bootstrap/bootstrap.3.3.4.min.js'></script>
<!--script src='lib/bootstrap-paper/bootstrap.min.js'></script-->
<script src="lib/introjs/intro.min.js"></script>
<script src='lib/c3-0.4.10/d3.v3.min.js' charset='utf-8'></script>
<script src='lib/c3-0.4.10/c3.min.js'></script>
<script src='lib/bootstrap3dialog/js/bootstrap-dialog.min.js'></script>		
<script src='lib/datatables/js/jquery.dataTables.min.js'></script>		
<script src='lib/alertifyjs/js/alertify.min.js'></script>	
<script src='lib/b3-typeahead/bootstrap3-typeahead.min.js'></script>
<script src='lib/tagsinput/bootstrap-tagsinput.min.js'></script>
<script src='lib/highlight.js/highlight.pack.js'></script>
<script src='lib/tabcollapse/bootstrap-tabcollapse.min.js'></script>
<script src='lib/select2/select2.min.js'></script>
<script src='lib/date-picker/js/bootstrap-datepicker.min.js'></script>
<script src='lib/timeago/jquery.timeago.js'></script>
<script src='lib/fancybox/source/jquery.fancybox.js'></script>
<script src="lib/raphael/2.1.0/raphael-min.js"></script>
<script src="lib/bootstrap-color-picker/js/bootstrap-colorpicker.min.js"></script>
 
<script src='js/script.js'></script>
<!-- script src="lib/responsivevoice/responsivevoice.js"></script -->

</head>
<body>
<!-- Return to Top -->
<a href="javascript:" id="return-to-top"><i class="fa fa-fw fa-arrow-up"></i></a>
<style>
#return-to-top {
    position: fixed;
    z-index: 10000000;
    bottom: 20px;
    right: 20px;
    /* background: rgb(0, 0, 0); */
    background: rgba(173, 173, 173, 0.7);
    width: 50px;
    height: 50px;
    display: block;
    text-decoration: none;
    -webkit-border-radius: 35px;
    -moz-border-radius: 35px;
    border-radius: 35px;
    display: none;
    -webkit-transition: all 0.3s linear;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    text-align: center;
    font-size: 30px;
    color: #FFF;
}
</style>
<script>	
// ===== Scroll to Top ==== 
$(window).scroll(function() {
    if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
        $('#return-to-top').fadeIn(200);    // Fade in the arrow
    } else {
        $('#return-to-top').fadeOut(200);   // Else fade out the arrow
    }
});
$('#return-to-top').click(function() {      // When arrow is clicked
    $('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
});
</script>

  <nav class='navbar navbar-inverseX navbar-fixed-top'>
	  <div class='<?php echo $container_type; ?>'>
		<div class='navbar-header'>
		  <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
			<span class='sr-only'>Toggle navigation</span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
			<span class='icon-bar'></span>
		  </button>
		  <a class='navbar-brand' href='?view=home' title='Go to the home panel' data-toggle='tooltip'>
		      <img src='images/navbrand-bolanle-200px.jpg' class='img-responsive'>
		   <?php /*echo settings::brandtext;*/ ?>
		  </a>
		</div>
		
		<?php 
			if ($users->loggedin()) {	
			   $user = $users->loggedin();
			   $userid = $user['userid'];
			   $username = $users->user($userid)->get('user_name');
			   
			   $profilepic = $users->user($userid)->get('profilepic');
			   $img = file_exists($profilepic) ? $profilepic : DEFAULT_USER;
			   $image = "<img src='$img' style='height:20px; width:auto;' class='nav-profile-image'>";	
			   
			   $role      = $users->user($userid)->get('rolename');
			   $role      = strtolower($role);
		
			   // options based on the role of logged in user
			   $myrights = @ $role_rights[$role];			   
		?>
			<div class="collapse navbar-collapse" id="navbar">
			  <ul class="nav navbar-nav navbar-right">
			  
<?php
      $view = @ $_GET['view'];
		$img = "";
		
		if (!is_array($myrights))
			die( alertbuilder("Unable to locate your role: $role","danger") );
			
		// build the options for this user
		foreach($myrights as $key=>$arr) 
		{
			/* get the title of this panel and icon */
			$icon = $arr['icon'];
			$key_= str_replace(" ", "_", $key);
			$key = ucfirst($key);
			$initial_key = $key;

			echo "<li class='dropdown'>
				   <a href='#' class='dropdown-toggle text-shadow' data-toggle='dropdown'>";
			
			if ($key_  == 'profile'){
			   $role_ = str_replace("_"," ", $role);
			   echo  "Hi, $image&nbsp;$username <span class='badge'>$role_</span>";
			}
			else
			   echo  "<span class='$icon'></span>&nbsp;$key";
			
			echo  "<span class='caret'></span></a>	";
            echo  "<ul class='dropdown-menu text-shadow' role='menu'>";
				   
			/* get options */
			$options = "";
			$text = '??';
			
			foreach($arr['menu'] as $key=>$menu) {
					$ico = $menu['icon'];
					$tooltip = $menu['title'];
					$text = ucfirst($key);
					$introtext = $menu['intro-text'];
					$url = $menu['url'];
					$divider_top = @ $menu['divider-top'] == true ? true : false;
					$divider_bottom = @ $menu['divider-bottom'] == true ? true : false;
					$active = strtolower($view) == strtolower($url) ? "class='active-option'" : "";
					$disabled = strtolower($view) == strtolower($text) ? 'disabled' : '';
					
					if ($active){
						$key__ = ucfirst($key_);
						echo "<script>
								 $('ul li.dropdown a:contains(\"$key__\")').parent().css('border-bottom','2px solid #9f7f66').css('background','rgba(255, 193, 99, 0.42)');
								</script>";
					}
						
					$img   = "";
					$extra = "";

               if ($divider_top)  $options .= "<li class='divider'></li>";
				
					switch ($key) {
						case 'sign out':
								$options .= " <li>
								              <a href='#' onclick=\"confirmlogout();\" title=\"$tooltip\"><span class=\"$ico\"></span>&nbsp;$text</a>
											    </li>";
							$introJsIndex++;									 
							break;
							
						case 'help wizard':
							$options .= "<li>
							              <a href='#' id=\"link$key\" title=\"$tooltip\" data-placement='right' data-toggle='tooltip'  data-original-value=\"$key\" onclick='introJs().start();'><span class=\"$ico\"></span>&nbsp;$key</a>
									       </li>";						
							break;
							
						default:
								$span_notifications = "<span class='pull-right' 
                                                     style='cursor:pointer' 
                                                     data-toggle='tooltip' 
                                                     title='Start help wizard'
                                                     onclick=\"introJs().goToStep($introJsIndex).start();\"
                                                     data-placement='right'>
                                               </span>";
                        
								switch ($key) {
									 // show profile picture in left menu
									 // takes up space
									case 'profile':
										$profilepic = $users->user($userid)->get('profilepic');
										$img = file_exists($profilepic) ? $profilepic : DEFAULT_USER;
										$img = "<p style='text-align:center'>
										         <a href='?view=profile'><img src='$img' style='height:100px; width: auto;' class='img-thumbnail'></a>
												</p>";
										break;

									case 'messages':
									    // do we have new messages?
										$total = 0;
										$sql = "SELECT COUNT(*)
												FROM user_notifications 
												WHERE userid_to=$userid AND wasread=0;";
										$ret = $database->query($sql);
										if (!$ret || !$ret->num_rows)
										{}
										else
										{
											$total = $ret->fetch_array()[0];
										}
										$alert = $total ? 'pm-new-messages' : '';
										

									    // we auto-update the new messages counter
										$span_notifications = "<span style='display: inline-block'; class='badge $alert' 
                                                                     data-toggle='tooltip' 
                                                                      title='Start help wizard'
                                                                      onclick=\"introJs().goToStep($introJsIndex).start();\"
                                                                     data-placement='right' 
                                                                     title=\"How many new notifications you have.\" id='notifications-counter'>$total</span>";

										break;
								}
													
								$key = strtolower($key);
								
								if ($key == 'home'){
								   $pic = $users->user($userid)->get('profilepic');
								   if (file_exists($pic))
										$options .= "<li><img class='img-thumbnail center-block' style='border:none;' src='$pic'></li>";
								}
								
								$options .= "<li $active>
												  $img
								              <a href=\"?view=$url\" id=\"$key\" title=\"$tooltip\" data-toggle='tooltip' data-placement='right' data-step='$introJsIndex' data-position='right'><span class=\"$ico\"></span>&nbsp;$text $span_notifications</a>
								             </li>
								             ";

								$introJsIndex++;
								break;
					}

                if ($divider_bottom)  
                	  $options .= "<li class='divider'></li>";
			}

			echo "   
			          $options
			        </ul>
			       </li>";
		}	
?>
			  </ul>
			</div>
			<?php 
			} //if users logged in
			?>
	  </div>
	</nav>
	
  <div class='<?php echo $container_type; ?>' id='container'>

	 <?php 
	  require_once('ui/menu.php');
	 ?>

	 <?php 
	  timer();
	  
	  require_once('ui/body.php');
	 ?>	

   <div class="row">
    <div class="col-md-12">
    <?php
    	//echo alertbuilder("<b>Performance indexer</b>: This page was generated in " . timer() . " seconds.",'success');
    ?>
	 <hr>
	 <h4 class='muted text-center'><small><?php echo settings::copyright; ?></small></h4>
	</div>
   </div>   
  </div>		
 </body>
</html>
