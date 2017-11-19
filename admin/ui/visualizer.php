<?php
    /*
    * Data Visualizer
    *
	 * Author: William Sengdara
	 * Created:
	 * Modified:
	 */

	if (!@$users)
		die("FATAL ERROR: this file may not be launched outside the system. It can only be included.");

	// check that we are logged in!
	$user = $users->loggedin();
	if (!$user) {
		echo view( 'dialog-login' );
		exit;
	}

	$userid = $user['userid'];
	$view = @ $_GET['view'];
	// ========================= begin custom code ===================================
	
	$fa_icon = font_awesome('fa-pie-chart');
	$fa_edit = font_awesome('fa-edit');

	echo "<h4>$fa_icon Data visualizer <small class='pull-right'>Alpha build</small></h4>
		   <p>&nbsp;</p>";
?>

<!--
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
-->
<style type="text/css">
.list-group-item {
    position: relative;
    display: block;
    padding: 10px 15px;
    margin-bottom: -1px;
    background-color: #fff;
    border: none;
}

.arrow-up {
  width: 0; 
  height: 0; 
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  
  border-bottom: 5px solid black;
}

.arrow-down {
  width: 0; 
  height: 0; 
  border-left: 20px solid transparent;
  border-right: 20px solid transparent;
  
  border-top: 20px solid #f00;
}

.arrow-right {
  width: 0; 
  height: 0; 
  border-top: 60px solid transparent;
  border-bottom: 60px solid transparent;
  
  border-left: 60px solid green;
}

.arrow-left {
  width: 0; 
  height: 0; 
  border-top: 10px solid transparent;
  border-bottom: 10px solid transparent; 
  
  border-right:10px solid rgb(218, 216, 216); 
}
</style>
<div id='arrow' style='position:absolute; display:none;' class="arrow-left"></div>
<div class='row'>
  <div class='col-md-8'>
    <h4 class="text-center"><span class='label label-default' id='chart-title'></span></h4>
    <div id='chart-default'></div>
  </div>
  <div class="col-md-4">
   <ul class="list-group">
    <li class="list-group-item">
      <a href='#' data-url='all-youth'class='chart-next btn btn-sm btn-primary'>All Youth</a>
      <a href='#' data-url='all-cls' class='chart-next btn btn-sm btn-default'>All CLS</a>
    </li>
    <li class="list-group-item">
    	<a href='#' data-url='youth-sex' class='chart-next btn btn-sm btn-default'>Youth By Sex</a>
    	<a href='#' data-url='cls-sex' class='chart-next btn btn-sm btn-sm btn-default'>CLS By Sex</a>
    </li>
    <li class="list-group-item">
     <a href='#' data-url='youth-region' class='chart-next btn btn-sm btn-default'>Youth By Region</a>
     <a href='#' data-url='cls-region' class='chart-next btn btn-sm btn-default'>CLS By Region</a>
    </li>
    <li class="list-group-item">
    	<a href='#' data-url='youth-region-pie' class='chart-next btn btn-sm btn-default'>Youth By Region (pie)</a>
    	<a href='#' data-url='cls-region-pie' class='chart-next btn btn-sm btn-default'>CLS By Region (pie)</a>
    </li>
    <li class="list-group-item">
     <a href='#' data-url='youth-sex-region' class='chart-next btn btn-sm btn-default'>Youth By Sex, Region</a>
     <a href='#' data-url='cls-sex-region' class='chart-next btn btn-sm btn-default'>CLS By Sex, Region</a>
    </li>
    <li class="list-group-item">
     <a href='#' data-url='youth-agegroup-region' class='chart-next btn btn-sm btn-default'>Youth By Age group</a>
     <a href='#' data-url='cls-agegroup-region' class='chart-next btn btn-sm btn-default'>CLS By Age group</a>
    </li>
    <li class="list-group-item">
     <a href='#' data-url='youth-agegroup-region-pie' class='chart-next btn btn-sm btn-default'>Youth By Age group (pie)</a>
     <a href='#' data-url='cls-agegroup-region-pie' class='chart-next btn btn-sm btn-default'>CLS By Age group (pie)</a>
    </li>
    <li class="list-group-item"><i>Note: Everything below is not set</i></li>
    <li class="list-group-item">
     <a href='#' data-url='youth-veterans' class='chart-next btn btn-sm btn-default'>Youth Veteran Parents</a>
     <a href='#' data-url='cls-veterans' class='chart-next btn btn-sm btn-default'>CLS Veteran Parents</a>
    </li>
    <li class="list-group-item">
     <a href='#' data-url='youth-veterans' class='chart-next btn btn-sm btn-default'>Youth Veteran Region</a>
     <a href='#' data-url='cls-veterans' class='chart-next btn btn-sm btn-default'>CLS Veteran Region</a>
    </li>
    <li class="list-group-item">
     <a href='#' data-url='youth-employment' class='chart-next btn btn-sm btn-default'>Youth Employment</a>
     <a href='#' data-url='cls-employment' class='chart-next btn btn-sm btn-default'>CLS Employment</a>
    </li>
    <li class="list-group-item">
     <a href='#' data-url='youth-employment-region' class='chart-next btn btn-sm btn-default'>Youth Employment Region</a>
     <a href='#' data-url='cls-employment-region' class='chart-next btn btn-sm btn-default'>CLS Employment Region</a>
    </li>    
    <li class="list-group-item">
     <a href='#' data-url='youth-dependents' class='chart-next btn btn-sm btn-default'>Youth Dependents</a>
     <a href='#' data-url='cls-dependents' class='chart-next btn btn-sm btn-default'>CLS Dependents</a>
    </li>
    <li class="list-group-item">
     <a href='#' data-url='youth-dependents-region' class='chart-next btn btn-sm btn-default'>Youth Dependents Region</a>
     <a href='#' data-url='cls-dependents-region' class='chart-next btn btn-sm btn-default'>CLS Dependents Region</a>
    </li>
   </ul>
  </div>
</div>
<script type="text/javascript">
 function build_chart(url){
 	 var data = {'action': url,
 	 				 'view': url};
 	 
 	 $.ajax({url: 'ui.php',
 	         data: data,
 	         method: 'POST',
 	         success: function (data) {
 	         			console.log(data);
 	         			
 	         			if (data || data.indexOf('--c3js--'))
 	         	       	$('body').append(data);
 	         	      else {
 	         	      	console.log('not valid data in success call');
 	         	      }
 	         },
 	         error: function (xhr, b, errorthrown) {
 	         	console.log('ajax-error',errorthrown);
 	         }
 	        });
 	        
 	  return false;
 }
 
 $(document).ready(function () {
   var obj  = $('.chart-next')[0];
   var text = $(obj).text();
   var url  = $(obj).attr('data-url');
   
   $('#chart-title').text(text);  	
 	build_chart(url);
 	
 	$('.chart-next').on('click',function () {
 		var text = $(this).text();
 		var url  = $(this).attr('data-url');
 		
 		// clear the list-group-item bg colors
		$('.list-group-item').each(function(idx,elem){
		  $(elem).css('background','#FFF');
		});

 		build_chart(url);
 		
 		$('#chart-title').text(text);
 		
 		$('.chart-next').each(function (idx,el) {
 			if (text == $(el).text()){
 			    $(el).addClass('btn-primary');
 			    $(el).parent().css('background','rgb(218, 216, 216)');
 			    var offset = $(el).parent().offset();
 			    var t = $(el).offset();
 			    
 			    console.log('pos.left',offset.left);
 			    
 			    $('#arrow').css('left', (offset.left - 10) + 'px')
 			    $('#arrow').css('top', (t.top + 5) + 'px');
 			    $('#arrow').css('display', 'block');
 			 }
 			else
 				 $(el).removeClass('btn-primary').addClass('btn-default');
 		});
 		
 		return false;
 	})
 });
</script>