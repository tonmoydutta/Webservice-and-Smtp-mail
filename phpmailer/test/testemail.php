<?php

// example on using PHPMailer with GMAIL

include("phpmailer/class.phpmailer.php");
include("phpmailer/class.smtp.php"); // note, this is optional - gets called from main class if not already loaded

$mail             = new PHPMailer();

//$body             = $mail->getFile('http://save-animation.org/contents.html');
//$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port

$mail->Username   = "info@save-animation.org";  // GMAIL username
$mail->Password   = "mayabious";            // GMAIL password

$mail->From       = "info@save-animation.org";
$mail->FromName   = "info save";
$mail->Subject    = "This is the subject";
$mail->Body    	  = "This is the HTML message body <b>in bold!</b>";
$mail->AltBody    = "This is the body when user views in plain text format"; //Text Body
$mail->WordWrap   = 50; // set word wrap

//$mail->MsgHTML($body);

$mail->AddReplyTo("info@save-animation.org","Webmaster");


$mail->AddAddress("info@save-animation.org","info save");

$mail->IsHTML(true); // send as HTML

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message has been sent";
}

?>
