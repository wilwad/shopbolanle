<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>title</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="index,follow" />
	<link rel="stylesheet" type="text/css" href="../bootstrap/bootstrap.3.3.4.min.css" />
	<link rel="stylesheet" type="text/css" href="light-carousel.css" />
	<style>
	 body {padding-top:70px; }
	</style>
</head>

<body>
 <div class='container'>
  <div class='row'>
   <div class='col-md-6'>
		<div class="lightcarousel">
			<div class="carousel">
				<ul>
					<li>
						<img src="img/img1.jpg" alt="June looking like a boy">
					</li>
					<li>
						<img src="img/img2.jpg" alt="Description for photo 2">
					</li>
					<li>
						<img src="img/img3.jpg" alt="Description for photo 3">
					</li>
					<li>
						<img src="img/img4.jpg" alt="Description forof photo 4">
					</li>
				</ul>
				<div class="controls">
					<div class="prev"></div>
					<div class="next"></div>
				</div>
			</div>
			<div class="thumbnails">
				<ul>
					<li>
						<img src="img/img1_thumb.jpg" alt="June looking like a little boy hehe">
					</li>
					<li>
						<img src="img/img2_thumb.jpg" alt="Description for photo 2">
					</li>
					<li>
						<img src="img/img3_thumb.jpg" alt="Description for photo 3">
					</li>
					<li>
						<img src="img/img4_thumb.jpg" alt="Description for photo 4">
					</li>
				</ul>
			</div>
		  </div>
	  </div>
	</div>
   </div>
   
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="../bootstrap/bootstrap.3.3.4.min.js"></script>
	<script src="jquery.light-carousel.js"></script>
	<script>
	 $(document).ready(function(){
		// or use options
		$('.lightcarousel').lightCarousel({
		  interval: 6000, // Interval between transitions
		  changeHeight: true, // If set to false, it retains the height of first slide
		  slideshow: true // Auto transitons
		}); 
	 });
	</script>

</body>
</html>
