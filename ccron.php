<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//drupal_cron_run();
//include_once 'mail.php';
// Email cron

require 'PHPMailer_v5.1/class.phpmailer.php';


    $query = "SELECT * FROM tbl_email WHERE status='0' and 	attempts <5";
	$result = db_query($query);

	//$host = "mail.claritusconsulting.com";
	//$username = "hafizor.rahman@claritusconsulting.com";
	//$password = "rahman@2k";
	
	while($obj = db_fetch_object($result)){
		$obj->email_id;
		
		$email_id = $obj->email_id;
		$from = $obj->emailfrom;
		 $to = $obj->emailto;
		
		$from_name = $obj->from_name;
		$cc = $obj->cc;
		$bcc = $obj->bcc;
		$subject = $obj->subject;
		$body = $obj->body;

		$email_from = variable_get('site_mail',$default);

//$email_from ="hafizor.rahman@claritusconsulting.com";
     //if (smtpmailer($to, $email_from, $email_from, $subject, $body)) {
	//echo 'Yippie, message send via Gmail';
    //} else {
	if (!smtpmailer($to, $email_from, $email_from, $subject, $body, false)) {
		if (!empty($error)) //echo $error;
		    
			$sql = "UPDATE tbl_email SET attempts= attempts+1 WHERE email_id='".$email_id."'";     
	} else {
		//echo 'Yep, the message is send (after hard working)';
		$sql = "UPDATE tbl_email SET status='1' WHERE email_id='".$email_id."'";	
	}
	db_query($sql);
}


	//}



  


function smtpmailer($to, $from, $from_name, $subject, $body, $is_gmail = true) { 
	global $error;
	$mail = new PHPMailer();
	$mail->IsSMTP();
	//$mail->IsSMTP();
	$mail->SMTPAuth = true; 
	if ($is_gmail) {
		$mail->SMTPSecure = 'ssl'; 
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;  
		$mail->Username = GUSER;  
		$mail->Password = GPWD;   
	} else {
		$mail->Host = "mail.claritusconsulting.com";
		$mail->Username = "hafizor.rahman@claritusconsulting.com";;  
		$mail->Password = "rahman@2k";
	}        
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

   $mail->AddCustomHeader($headers); 
	//$mail->Header($headers);
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AddAddress($to);
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo;
		return false;
	} else {
		//$error = 'Message sent!';
		return true;
	}
}



?>
