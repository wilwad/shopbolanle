<h4><span class='fa fa-fw fa-picture-o'></span> Images Viewer</h4>
<HR>

<?php 

        // params
        $view     = @ $_GET['view'];
        $action   = @ $_GET['action'];
        $filename = @ $_GET['filename'];
    
    if (!function_exists("font_awesome")){
        function font_awesome($icon, $style=''){
            return "<span class='fa fa-fw $icon' style='$style'></span>";
        }
    }
    
    if (!function_exists("alertbuilder")){
        function alertbuilder($msg, $alert){
            return "<p class='alert alert-$alert'>$msg</p>";    
        }  
    }
    
    // icons
	$fa_trash = font_awesome('fa-trash','color=red');
	$fa_folder = font_awesome('fa-folder-o');
	
	switch ($action){
	  case 'delete-pic':
	        $status = "";
	        
	  	if (file_exists($filename)){
	  	  if (@ unlink($filename)){
	  	  	$status = alertbuilder("File deleted: $filename",'success');
	  	  } else {
	  	  	$status = alertbuilder("Failed to delete requested file: $filename",'warning');
	  	  }
	  	  
	  	} else {
	  	   $status = alertbuilder("File does not exist: $filename",'warning');
	  	}
	  	
	  	echo "<div class='row'> 
	  	       <div class='col-md-12'>
	  	        $status
	  	       </div>
	  	      </div>";
	  	break;
	}
?>
    
<div class='row'>
     <?php
	$folders = array("slides","images", "images/slides", "images/work");
	$idx = 0;
	
	foreach ($folders as $dir){
		
		$directory = basename($dir);
		$images = glob("$dir/*.{jpg,png,gif}", GLOB_BRACE);
		
		echo "<div class='col-md-12'>
		       <h5>$fa_folder $dir</h5>
		      <HR>
		      </div>";
		      
		if (count($images))
		{
			foreach($images as $img)
			{
				$name = basename($img);
	
				echo "<div class='col-md-3'>
				       <div class='panel panel-default'>
				        <div class='panel-heading'>
				         $name <a href='#' class='pull-right' 
				         onclick=\"BootstrapDialog.confirm('Are you sure you would like to delete this image?',function(ans){if (ans){\$('#form$idx').submit(); };}); return false;\">$fa_trash</a>
				         <form method='get' id='form$idx'>
				          <input type='hidden' name='view' value='$view'>
				          <input type='hidden' name='action' value='delete-pic'>
				          <input type='hidden' name='filename' value='$img'>
				         </form>
				        </div>			       
				        <div class='panel-body'>
				         <a href='$img' class='fancybox' data-title='$name' rel='images'>
				          <img src='$img' alt='' class='img-responsive center-block'>
				         </a>
				        </div>
				       </div>
				      </div>";
		
				$idx++;
			}
		}	
	}
	
	?>
</div>