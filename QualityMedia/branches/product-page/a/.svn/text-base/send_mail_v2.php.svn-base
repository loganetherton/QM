<?php
$flag = 0;
require_once('PHPMailer/class.phpmailer.php');

if(isset($_POST)){
/*	$to  = 'arun.n@golivemobile.com'; 
	$to_name  = "Arun N"; 
*/	

	//$to  = "ardy@qualitymedia.com.au"; 
	/*
	* As per Siva the TO Email id as ardy@qualitymedia.com not ardy@qualitymedia.com .us
	*/
	
	$to  = "ardy@qualitymedia.com"; 
	$to_name  = ""; 
	
	$from_emailid = "no-reply@qualitymedia.com";
	$from_name = "no-reply";
	
	$subject = 'New Lead From Start.php';
	
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
/*	$mail->IsSMTP();                           
	$mail->SMTPAuth   = true;                  
	$mail->SMTPSecure = "ssl";                 
	$mail->Host       = "smtp.gmail.com";      
	$mail->Port       = 465;                   
	$mail->Username   = "dineshkumar.glm@gmail.com";
	$mail->Password   = "golive1234"; */           
	
	
	$mail->SetFrom($from_emailid, $from_name);
	$mail->Subject    = $subject;
	$mail->AltBody    = "New Lead"; // optional, comment out and test
	
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

<?php
/*$flag = 0;
if(isset($_POST)){
	$name='=?UTF-8?B?'.base64_encode($_POST['name']).'?=';
	$subject='=?UTF-8?B?'.base64_encode("New Lead From Start.php").'?=';
	$headers="From: ".$name." <no-reply@qualitymedia.com>\r\n".
		"Reply-To: no-reply@qualitymedia.com\r\n".
		"MIME-Version: 1.0\r\n".
		"Content-type: text/html; charset=UTF-8";

	$content = '<table border="1" cellspacing="0" cellpadding="5">
				<tr><td><b>Name: </td><td>'.$_POST['name'].'</td></tr>
				<tr><td><b>Business Name: </td><td>'.$_POST['busi_name'].'</td></tr>
				<tr><td><b>Phone: </td><td>'.$_POST['phone'].'</td></tr>
				</table>';

	if(mail('sekar@golivemobile.com,venu@golivemobile.com',$subject,$content,$headers)){
		$flag = 1;
	}
}

echo $flag;
*/?>