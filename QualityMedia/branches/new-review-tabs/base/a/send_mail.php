<?php
$flag = 0;
require_once('PHPMailer/class.phpmailer.php');

if(isset($_POST)){
	//$to  = "ardy@qualitymedia.com.au"; 
	/*
	* As per Siva the TO Email id as ardy@qualitymedia.com not ardy@qualitymedia.com .us
	*/
		
	$to  = "ardy@qualitymedia.com"; 
	$to_name  = ""; 
	
	$from_emailid = "no-reply@qualitymedia.com";
	$from_name = "no-reply";
	
	
	$subject = 'New Lead From Getstarted.php';
	
	$message = '
	<table border="1" cellspacing="0" cellpadding="5">
			<tr><td><b>Name: </td><td>'.$_POST['name'].'</td></tr>
			<tr><td><b>Business Name: </td><td>'.$_POST['busi_name'].'</td></tr>
			<tr><td><b>Phone: </td><td>'.$_POST['phone'].'</td></tr>
	</table>
	';
	

	$mail  = new PHPMailer();
	/*
	* SMTP Auth Setup
	*/
	/*
	$mail->IsSMTP();                           
	$mail->SMTPAuth   = true;                  
	$mail->SMTPSecure = "ssl";                 
	$mail->Host       = "smtp.gmail.com";      
	$mail->Port       = 465;                   
	$mail->Username   = "dineshkumar.glm@gmail.com";
	$mail->Password   = "golive1234";            
	*/
	
	$mail->SetFrom($from_emailid, $from_name);
	$mail->Subject    = $subject;
	$mail->AltBody    = "New Lead"; 
	
	$mail->MsgHTML($message);
	$mail->AddAddress($to, $to_name);
	
	$mail->AddBCC("tony@qualitymedia.com", "Tony");
	$mail->AddBCC("tony@golivemobile.com", "Tony Alen");
	$mail->AddBCC("ryan.mangune@golivemobile.com", "Ryan Mangune");
	$mail->AddBCC("reece@qualitymedia.com", "Reece Stojanovic");

	if(!$mail->Send()) {
		 $flag = 0;
	} else {
	    $flag = 1;
	}
	
}

echo $flag;
exit;
?>