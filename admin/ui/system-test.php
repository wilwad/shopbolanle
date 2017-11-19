<?php
 $fa_icon = font_awesome('fa-plane');
 
 echo "<h4>$fa_icon Pre-flight checks <small class='pull-right'>Simple diagnostic checks</small> </h4><hr>";
 $directories = array('profiles',
                      'adverts',
                      'discounts',
                      'uploads');
                      
 $directory_check = "";

 foreach($directories as $dir){
 	if (!file_exists($dir) || !is_dir($dir))
 		$directory_check .= alertbuilder("Directory <strong>$dir</strong> does not exist or is not a directory",'warning') ;
 	else 
 		$directory_check .= is_writable($dir) ? alertbuilder("Directory <strong>$dir</strong> is writable",'success') : alertbuilder("Directory <strong>$dir</strong> is not writable",'warning') ;
 }
 
 if ($directory_check == "")
 	  $directory_check = alertbuilder("No checks were done.","info");
 	  
 echo "<div class='row'>
        <div class='col-md-8' style='border-right:1px solid #cacaca'>
         $directory_check
        </div>
        <div class='col-md-4'>
         <h5><strong>Directory check</strong></h5>
         Checks that the necessary directories are writable
        </div>
 		 </div>";
 	