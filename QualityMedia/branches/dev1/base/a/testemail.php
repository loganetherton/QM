<?php
require_once('PHPMailer/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$body = '
<table border="1" cellspacing="0" cellpadding="5">
		<tr><td><b>Name: </td><td>Dinesh Kumar</td></tr>
		<tr><td><b>Business Name: </td><td>Test Business Name</td></tr>
		<tr><td><b>Phone: </td><td>9999999999</td></tr>
</table>';

$mail  = new PHPMailer();
/*
$mail->IsSMTP(); // telling the class to use SMTP
//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
$mail->Username   = "dineshkumar.glm@gmail.com";  // GMAIL username
$mail->Password   = "golive1234";            // GMAIL password
*/
$mail->SetFrom('no-reply@qualitymedia.com', 'no-reply');

//$mail->AddReplyTo("arun.n@golivemobile.com","");

$mail->Subject    = "New Lead From Getstarted.php";

$mail->AltBody    = "New Lead"; // optional, comment out and test

$mail->MsgHTML($body);

$address = "arun@qualitymedia.com";
$mail->AddAddress($address, "Arun N");
$mail->AddBCC($address, "Arun N");
//$mail->AddAddress("dinesh@golivemobile.com", "Dinesh Kumar");


echo "hello";

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}


