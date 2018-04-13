<?php

  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE");
  header("Access-Control-Allow-Credentials: true");
  header("Access-Control-Allow-Headers: content-type");
  header("Vary: Access-Control-Request-Headers");

  $data = json_decode(file_get_contents("php://input"));

include('config/config.php');

//echo 'ffsdf';

$agencyname=$data->agency_name;

$cityname=$data->city_name;



/*$sql=mysqli_query($conn,"select a.slno as aslno,c.slno as cslno,b.slno as bslno 
	                      from m_agency AS a INNER JOIN
	                      m_city AS c INNER JOIN m_branch AS b   
	                      where a.agency_name='".$agencyname."' 
	                      and c.city_name='".$cityname."' and 
	                      b.branch_name='".$branchname."'");*/

//$sql_array=mysqli_fetch_array($sql);

 $bname=$data->branch_name;
 $district=$data->district;

 $bnamesql=mysqli_query($conn,"select * from m_branch where branch_name='".$bname."'");
 if(mysqli_num_rows($bnamesql)>0){

//echo 'succ';
 $bnamesql_array=mysqli_fetch_array($bnamesql);
 $branchslno=$bnamesql_array['slno'];
 }

else{

	$branchinsert=mysqli_query($conn,"insert into m_branch (branch_code,branch_name,del_stat,district) values ('".$bname."','".$bname."',0,'".$district."')");
 	if(!$branchinsert){

 		$msg=(object)[

 			  "msg"=>"Branch Not Added for Some Technical Problem.",
 			  "status"=>false

 			];
 	}

 	$branchslno=mysqli_insert_id($conn);
}
 
//echo $branchslno;

$anamesql=mysqli_query($conn,"select * from m_agency where agency_name='".$agencyname."'");

if(mysqli_num_rows($anamesql)>0){

//echo 'succ';
 $anamesql_array=mysqli_fetch_array($anamesql);
 $agencyslno=$anamesql_array['slno'];
 }

 else{
$agencyinsert=mysqli_query($conn,"insert into m_agency (agency_name,agency_code,del_stat) values ('".$agencyname."','".$agencyname."',0)");
	if(!$agencyinsert){

 		$msg=(object)[

 			  "msg"=>"Branch Not Added for Some Technical Problem.",
 			  "status"=>false

 			];
 	}

 $agencyslno=mysqli_insert_id($conn);

 }



//echo $agencyslno;
//echo "select * from m_city where city_name='".$cityname."' and branch_slno='".$branchslno."'";
$cnamesql=mysqli_query($conn,"select * from m_city where city_name='".$cityname."' and branch_slno='".$branchslno."'");

/*echo $a=mysqli_num_rows($cnamesql);
exit;*/
if(mysqli_num_rows($cnamesql)>0){
$cnamesql_array=mysqli_fetch_array($cnamesql);	
$cityslno=$cnamesql_array['slno'];
}
	else{
/*echo "insert into m_city (branch_slno,city_id,city_name,del_stat) values ('".$branchslno."','cityid','".$cityname."',0)";
exit;*/
		$cityinsert=mysqli_query($conn,"insert into m_city (branch_slno,city_id,city_name,del_stat) values ('".$branchslno."','cityid','".$cityname."',0)");
		if(!$cityinsert){

 		$msg=(object)[

 			   "msg"=>"City Not Added for Some Technical Problem.",
 			   "status"=>false

 			];
 	}

 	$cityslno=mysqli_insert_id($conn);

    $cityid='C0'.$cityslno;

	

  $cityupdate=mysqli_query($conn,"update m_city set city_id='".$cityid."' where slno='".$cityslno."'");

if(!$cityupdate){

 		$msg=(object)[

 			    "msg"=>"City Not Updated for Some Technical Problem.",
 			    "status"=>false


 			   ];
 	}

	}

   //echo $cityslno;

$username=$data->user_name;

$userid=$data->user_id;

$userpass=$data->user_pass;

$usermob=$data->user_mob;
//echo "select * from m_user where name='".$username."' and agency_slno='".$agencyslno."'";
$usersql=mysqli_query($conn,"select * from m_user where name='".$username."' and agency_slno='".$agencyslno."'");

if(mysqli_num_rows($usersql)>0){

	$msg=(object)[

 			    "msg"=>"User already exists.",
 			    "status"=>false


 			   ];

}

else{

	$user_insert=mysqli_query($conn,"insert into m_user (name,user_id,usr_pass,user_mob,agency_slno,
		user_type,del_stat,city_slno)  values ('".$username."','".$userid."','".$userpass."','".$usermob."','".$agencyslno."',
	    'A',0,'".$cityslno."')");
//$userslno=mysqli_insert_id($conn);
$alluserdata=mysqli_fetch_array(mysqli_query($conn,"select a.*,c.* from m_agency a JOIN m_city c
	                                                 where a.slno='".$agencyslno."' and c.slno='".$cityslno."'"));


	if($user_insert){

		$msg=(object)[

        "msg"=>"User successfully Added.",
        "status"=>true

	];


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
$mail->Subject    = "Psp user Addition";
//$mail->Body       = "This is the HTML message body <b>in bold!</b>";
$mail->AltBody    = "This is the body when user views in plain text format"; //Text Body
$mail->WordWrap   = 50; // set word wrap

//$mail->MsgHTML($body);

$mail->AddReplyTo("webtest@mayabious.com","Webmaster");

$mail->AddAddress("webtest@mayabious.com","Psp user Addition");
//$mail->AddAddress($querydatas['email'],"info save");
                                                                                                                                             
$mail->IsHTML(true); // send as HTML


         $mail->Body='<table border="1">
                           <tr>
                           
                           <td>User Name</td>
                           
                           <td>User Mobile</td>
                           <td>Agency Name</td>
                           
                           <td>City name</td>
                           
                           </tr>
                           <tr>
                           <td>'.$username.'</td>
                           <td>'.$usermob.'</td>
                           <td>'.$alluserdata['agency_name'].'</td>
                           <td>'.$alluserdata['city_name'].'</td>
                           

                           </tr>

                </table>';

                

                if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
      } 
	}

	else{
  
      $msg=(object)[

        "msg"=>"User Not Added For Some Technical Problem.",
        "status"=>false

	];

	}
}

print_r(json_encode($msg));
?>