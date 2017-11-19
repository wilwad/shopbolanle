<?php
  require('../lib/qr-reader/lib/QrReader.php');
  
  $extra = (int) @ $_POST['extra'];
  
  
  if ($extra == 1){
  	
	define('UPLOAD_DIR', 'qrscans/');
	
	$img = $_POST['pngUrl'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR . 'qrscan' /*uniqid()*/ . '.png';
	$success = file_put_contents($file, $data);
	
	if ($success){
		
      $qrcode = new QrReader($file);
      $text = $qrcode->text();
      
      if ($text == "")
          echo "Invalid QR Code. Please try again.";
      else
      	die($text);		
	}
	    
	else
		 echo "Unable to save uploaded QR code. Please check directory permissions and try again.";
  }
?>
	