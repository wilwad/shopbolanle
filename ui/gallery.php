<div class='row'>
 <div class='col-md-12'>
  <h3 class='text-center line'>Gallery</h3>
 </div>
</div>

<div class='row'>
    <?php
		$folder = "images/gallery";
		$images = glob("$folder/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
		if (count($images))
		{
			foreach($images as $img)
			{
				$extension = "." . pathinfo($img, PATHINFO_EXTENSION);
				$img_title = str_replace($extension,"",$img);
				$img_title = str_replace("-"," ",$img_title);
				$img_title = basename($img_title);
				$img_title = ucwords($img_title);

				echo "<div class='col-sm-4 col-xs-12 col-md-6 col-lg-3'>
					<a class='fancybox thumbnail' style='border-radius: 0; border: 1px solid lightgrey' data-title='$img_title' href='$img'>
						<!-- img class='img-responsive' src='$img' --> 
				         <div class='image-container' style='background-size: cover !important; background: url(\"$img\") no-repeat;'>
				         </div>               
					 <p class='text-center caption'>$img_title</p>		            
					</a>
					  
				 </div>";
		    }
      }
	?>
</div>