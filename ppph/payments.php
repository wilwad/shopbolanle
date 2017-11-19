<?php
  /* error reporting */
  ini_set('display_startup_errors',1);
  ini_set('display_errors',1);
  error_reporting(-1);

if (!function_exists("font_awesome")){
    function font_awesome($icon){
    	return "<span class='fa fa-fw $icon'></span>";
    }
}

$site = "sandbox.paypal.com"; //www.paypal.com
$port = 443;

/*
$fp = fsockopen($site, $port, $errno, $errstr, 30);

if(!$fp){
  echo "<b>The port is NOT open!</b>";
}else{
  echo "<b>The port is open!</b>";
  fclose($fp);
}*/

 //require_once('../settings.php'); 
 //require_once('../ui.php');  
 
 $url_payment = "https://www.shopbolanle.com/ppph/payments.php";
 $url_success = "https://www.shopbolanle.com/?view=payment_success";
 $url_failed  = "https://www.shopbolanle.com/?view=payment_failed";
 $url_notify  = "https://www.shopbolanle.com/ppph/ipn.php";
 $paypal_email= "orders@shopbolanle.com";
 
$notify_url = $url_payment;

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && ($_POST["txn_type"])){
    $querystring = '';
    
    // Firstly Append paypal account to querystring
    $querystring .= "?business=". rawurlencode($paypal_email)."&";
    
    //loop for posted values and append to querystring
    foreach($_POST as $key=>$value){
        $val = rawurlencode(stripslashes($value));
        $querystring .= "$key=$val&";
    }
    
    // Append paypal return addresses
    $querystring .= "return=".rawurlencode(stripslashes($return_url))."&";
    $querystring .= "cancel_return=".rawurlencode(stripslashes($cancel_url))."&";
    $querystring .= "notify_url=".rawurlencode($notify_url);
    
    // Append querystring with custom field
    //$querystring .= "&acustom=$";
    
    $fp = fopen("paypal.ipn.log", "a");
    if ($fp){
        fwrite($fp, print_r($_POST,true) . PHP_EOL);
        fclose($fp);        
    }

    // Redirect to paypal IPN
    header('location: https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);

    //header("HTTP/1.1 200 OK");
    exit();
    
} else {

    // Response from Paypal

    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    $data = array();
    
    // for security, only add these here
    $_POST['cmd'] ='_cart';
    $_POST['business']=$paypal_email;
    $_POST['upload']='1';
    //$_POST['currency_code']='USD';
    //$_POST['notify_url']=$url_payment;
    $_POST['return']=$url_success;
    $_POST['notify_url']=$url_notify;
    $_POST['cancel_return']=$url_failed;
                    
    foreach ($_POST as $key=> $value) {
        if ($key == 'action') continue;

        $val = rawurlencode(stripslashes($value));
        $val = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$val);// IPN fix
        $req .= "&$key=$val";
        
        //echo "$key <BR>";
        
        /*
        if ($key == 'amount_'){
            $data['item_name']          = $_POST['item_name'];    
        }
        if ($key == 'item_name_'){
            $data['item_name']          = $_POST['item_name'];    
        }  
        */    
    }
    
    // assign posted variables to local variables
    /*
    $data['item_name']          = $_POST['item_name'];
    $data['item_number']        = $_POST['item_number'];
    */
    
    $data['payment_status']     = @ $_POST['payment_status'];
    $data['payment_amount']     = @ $_POST['mc_gross'];
    $data['payment_currency']   = @ $_POST['currency_code'];
    $data['txn_id']             = $_POST['txn_id'];
    $data['receiver_email']     = @ $_POST['receiver_email'];
    $data['payer_email']        = @ $_POST['payer_email'];
    $data['custom']             = @ $_POST['custom'];
        
    //echo "Request follows <BR><BR> $req <BR>";
    
    // post back to PayPal system to validate
    $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Host: $site\r\n";
    $header .= "Connection: close\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    
    $ssl = "ssl://www.sandbox.paypal.com";
    
    $fp = fsockopen ($ssl, 443, $errno, $errstr, 30);
    
    if (!$fp) {
        // HTTP ERROR
        echo "HTTP Error";
        
    } else {
        fputs($fp, $header . $req);
        $idx = 1;
        
        while (!feof($fp)) {
            $res = fgets ($fp, 1024);
            $res = trim($res);
            
            //I don't see this
            if (strcmp($res, "VERIFIED") == 0) {
                //insert order into database    
                echo "Response Message: " . "VERIFIED";
            }
            else if (strcmp ($res, "INVALID") == 0) {
                //insert into DB in a table for bad payments for you to process later
                echo "Response Message: " . "INVALID" ;
            } else {
                if (substr($res,0, strlen('Location:')) == 'Location:'){
                    $url = substr($res,strlen('Location:'));
                    $url = trim($url);
                    
                    fclose($fp);
                    header("Location: $url");
                    exit();
                }
            }

            $idx++;
        }
        
        fclose ($fp);
    }
}
?>