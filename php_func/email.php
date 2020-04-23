<?php

require_once('session_stub.php');
php_session_start();
php_auth_restaurant_only();

function php_send_email($emailAddr, $emailTitle, $emailContent)
{    
	//send email
        $emailcontent = "<html><head></head><body>". $emailContent . "</body></html>";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($emailAddr,$emailTitle, $emailcontent, $headers.'From:sfyip2-c@my.cityu.edu.hk');
	return true;
}

?>