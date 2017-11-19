<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>

<!-- CSS -->
<link href='../bootstrap/themes/font-awesome/font-awesome.min.css' rel='stylesheet' type='text/css'>
<link href='../lib/bootstrap/bootstrap.3.3.4.min.css' rel='stylesheet' type='text/css'>
<!-- link href='../lib/bootstrap-paper/bootstrap.min.css' rel='stylesheet' type='text/css' -->
<link href='../bootstrap/themes/paper/bootstrap.min.css' rel='stylesheet' type='text/css'>
<!-- link href='../bootstrap/themes/yeti2/sticky-footer-navbar.css' rel='stylesheet' -->

<!-- link href='../css/style.css' rel='stylesheet' -->

<link href='../lib/alertifyjs/css/alertify.min.css' rel='stylesheet'>
<link href='../lib/alertifyjs/css/themes/bootstrap.min.css' rel='stylesheet'>

<!-- Javascript -->
<!-- script src='lib/jquery/jquery.1.11.1.min.js'></script -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src='../lib/bootstrap/bootstrap.3.3.4.min.js'></script>	
<script src='../lib/alertifyjs/js/alertify.min.js'></script>	
<!-- script src="lib/responsivevoice/responsivevoice.js"></script -->

<body>

	<!--
		Ideally these elements aren't created until it's confirmed that the 
		client supports video/camera, but for the sake of illustrating the 
		elements involved, they are created with markup (not JavaScript)
	-->
	<div class="container" style="border:1px solid #cacaca">
	 <div class="col-md-12">
		<h4><span class='fa fa-fw fa-qrcode'></span> Scan QR Code <small class="pull-right">Hold the CLS card infront of the camera</small></h4>
		<canvas id="canvas" style='background:#FFFFFF; position:absolute; top:0; left:-1000px;' width="320" height="240"></canvas>
		<video id="video" width="320" height="240" autoplay></video>
		<BR>
		
		<button id="snap" style='display:none' class="btn btn-sm btn-primary">Submit QR Code</button>
		<p>&nbsp;</p>
	 </div>
	</div>

	<script>
      var width = 320,
          height = 240;
          
      $(document).ready(function () {
        alertify.success('document ready');
        
			// Grab elements, create settings, etc.
			var canvas = document.getElementById("canvas"),
				context = canvas.getContext("2d"),
				video = document.getElementById("video"),
				videoObj = { "video": true },
				errBack = function(error) {
					console.log("Video capture error: ", error.code); 
				};
      
			// Put video listeners into place
			if(navigator.getUserMedia) { // Standard
				$('#snap').css('display','block');
				
				navigator.getUserMedia(videoObj, function(stream) {
					video.src = stream;
					video.play();
				}, errBack);
			} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
				$('#snap').css('display','block');
				
				navigator.webkitGetUserMedia(videoObj, function(stream){
					video.src = window.URL.createObjectURL(stream);
					video.play();
				}, errBack);
			} else if(navigator.mozGetUserMedia) { // WebKit-prefixed
				$('#snap').css('display','block');
				
				navigator.mozGetUserMedia(videoObj, function(stream){
					video.src = window.URL.createObjectURL(stream);
					video.play();
				}, errBack);
			}

			// Trigger photo take
			document.getElementById("snap").addEventListener("click", function() {
				    context.drawImage(video, 0, 0, width, height);

            var canvas = document.getElementById("canvas");
            var image = canvas.toDataURL();
            
            var form = "<form method='POST'>" +
                       " <input type='hidden' name='extra' value='1'>"+
                       " <input type='hidden' name='pngUrl' value='" +image +"'>"+
                       "</form>";
				
				// submits to self
				//$(form).appendTo('body').submit();

          $.ajax({
            type: 'post',
            url: 'qr-scanner-decoder.php',
            data: $(form).serialize(),
            success: function (data) {
              if (data.indexOf('http') != -1)
                 window.parent.location.href = data;
              else
              	  alertify.success(data);
            },
            error: function (a,b,c) {
            	
            }
            }); 
 			});
		  var display = $('#snap').css('display');
		  
		  console.log('display',display);
		  
	     if (display.toString() == 'block'){
	     		console.log('will auto submit');
			   window.setTimeout(click_snap,5000);
	     }	  	
	 
	 });
	 
     function click_snap(){
     		console.log('trigger called');
     		
     		//$('#video').css('opacity',0.5);
     		$('#snap').trigger('click');
     		//$('#video').css('opacity',1);
     		window.setTimeout(click_snap,5000);
     }
	</script>
</body>
</html>