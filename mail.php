<?php
include("phpmailer/class.phpmailer.php");
include("phpmailer/class.smtp.php");




$mail= new PHPMailer();



$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port

$mail->Username   = "webtest@mayabious.com";  // GMAIL username
$mail->Password   = "templogin";            // GMAIL password

$mail->From       = "info@mayabious.com";
$mail->FromName   = "Info Save";
$mail->Subject    = "Psp Outlet Addition";
//$mail->Body       = "This is the HTML message body <b>in bold!</b>";
$mail->AltBody    = "This is the body when user views in plain text format"; //Text Body
$mail->WordWrap   = 50; // set word wrap

//$mail->MsgHTML($body);

$mail->AddReplyTo("webtest@mayabious.com","Webmaster");

$mail->AddAddress("webtest@mayabious.com","Psp Outlet Addition");
//$mail->AddAddress($querydatas['email'],"info save");
                                                                                                                                             
$mail->IsHTML(true); // send as HTML


         $mail->Body='<table>
                           <tr>
                           <td>Date And Time</td>
                           <td>Agency Name</td>
                           <td>User Name</td>
                           <td>Area Name</td>
                           <td>Outlet name</td>
                           <td>Outlet Address</td>
                           <td>Retailer Name</td>
                           <td>Retailer Mobile</td>
                           <td>Location Lat</td>
                           <td>Location Lang</td>
                           </tr>
                           <tr>
                           <td>'.$alldata['dt'].'</td>
                           <td>'.$alldata['aname'].'</td>
                           <td>'.$alldata['uname'].'</td>
                           <td>'.$alldata['areaname'].'</td>
                           <td>'.$oname.'</td>
                           <td>'.$oaddress.'</td>
                           <td>'.$rname.'</td>
                           <td>'.$rmobile.'</td>
                           <td>'.$llat.'</td>
                           <td>'.$llong.'</td>

                           </tr>

                </table>';

                

                if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
      } 
      ?>