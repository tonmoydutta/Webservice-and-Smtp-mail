<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE");
  header("Access-Control-Allow-Credentials: true");
  header("Access-Control-Allow-Headers: content-type");
  header("Vary: Access-Control-Request-Headers");

  $data = json_decode(file_get_contents("php://input"));

include('config/config.php');


 $bname=$data->branch_name;

$district=$data->district;



 //echo "select * from m_branch where branch_name='".$bname."'";

 $bnamesql=mysqli_query($conn,"select * from m_branch where branch_name='".$bname."'");

 /*echo $r=mysqli_num_rows($bnamesql);
 exit;*/
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

 $cname=$data->city_name;
 $cnamesql=mysqli_query($conn,"select * from m_city where city_name='".$cname."' and branch_slno='".$branchslno."'");

 if(mysqli_num_rows($cnamesql)>0){
 $cnamesql_array=mysqli_fetch_array($cnamesql);
 $cityslno=$cnamesql_array['slno'];
 }

 else{
 
 $cityinsert=mysqli_query($conn,"insert into m_city (branch_slno,city_id,city_name,del_stat) values ('".$branchslno."','cityid','".$cname."',0)");
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

 $zname=$data->zone_name;
 $znamesql=mysqli_query($conn,"select * from m_zone where zone_name='".$zname."' and city_slno='".$cityslno."'");
if(mysqli_num_rows($znamesql)>0){

$znamesql_array=mysqli_fetch_array($znamesql);
$zoneslno=$znamesql_array['slno'];

}
else{

	$zoneinsert=mysqli_query($conn,"insert into m_zone (city_slno,zone_id,zone_name,del_stat) values ('".$cityslno."','zoneid','".$zname."',0)");
	if(!$zoneinsert){

 		$msg=(object)[

 			"msg"=>"Zone Not Added for Some Technical Problem.",
 			"status"=>false


 		   ];
 	}
	$zoneslno=mysqli_insert_id($conn);
	$zoneid='Z0'.$zoneslno;
	$zoneupdate=mysqli_query($conn,"update m_zone set zone_id='".$zoneid."' where slno='".$zoneslno."'");
	if(!$zoneupdate){

 		

 		$msg=(object)[

                   "msg"=>"Zone Not Updated for Some Technical Problem.",
                   "status"=>false

	];
 	}
} 
 //echo $zoneslno;

 $aname=$data->area_name;

 //echo "select * from m_area where area_name='".$aname."' and zone_slno='".$zoneslno."'";
 //exit;
 $anamesql=mysqli_query($conn,"select * from m_area where zone_slno='".$zoneslno."' and area_name='".$aname."'");
 

if(mysqli_num_rows($anamesql)>0){

$anamesql_array=mysqli_fetch_array($anamesql);
$areaslno=$anamesql_array['slno'];
}

else{

	$areainsert=mysqli_query($conn,"insert into m_area (zone_slno,area_name,del_stat) values ('".$zoneslno."','".$aname."',0)");

	if(!$areainsert){

 		
 		$msg=(object)[

        "msg"=>"Area Not Added for Some Technical Problem.",
        "status"=>false

	];
 	}

   $areaslno=mysqli_insert_id($conn);
}

//echo $areaslno;

 $oname=$data->outlet_name;

 $oaddress=$data->outlet_address;

 $rname=$data->retailer_name;

 $rmobile=$data->retailer_mobile;

 $llat=$data->location_lat;

 $llong=$data->location_long;

 
 $uname=$data->user_name;
 
 $unamesql=mysqli_query($conn,"select * from m_user where name='".$uname."'");
if(mysqli_num_rows($unamesql)>0){
$unamesql_array=mysqli_fetch_array($unamesql);

$userslno=$unamesql_array['slno'];

$agencyslno=$unamesql_array['agency_slno'];

/*echo "insert into tbl_outlet (agency_slno,user_slno,area_slno,outlet_name,outlet_address, retailer_name,retailer_mobile,loc_lat,loc_long,outlet_status,del_stat) values ('".$agencyslno."','".$userslno."','".$areaslno."','".$oname."','".$oaddress."','".$rname."','".$rmobile."','".$llat."','".$llong."',1,0)";

exit;*/
 
 $outlet_insert=mysqli_query($conn,"insert into tbl_outlet (agency_slno,user_slno,area_slno,outlet_name,outlet_address,	retailer_name,retailer_mobile,loc_lat,loc_long,outlet_status,del_stat) values ('".$agencyslno."','".$userslno."','".$areaslno."','".$oname."','".$oaddress."','".$rname."','".$rmobile."','".$llat."','".$llong."',1,0)");

if($outlet_insert){


  $alldata=mysqli_fetch_array(mysqli_query($conn,"select a.slno,a.agency_name as aname,u.slno,u.name as uname,c.slno,c.area_name
                                                  as areaname,o.dt_upload as dt from m_agency AS a INNER JOIN m_user AS u
                                                   INNER JOIN m_area AS c INNER JOIN tbl_outlet AS o where a.slno='".$agencyslno."' and u.slno='".$userslno."'and c.slno='".$areaslno."'"));


   /*echo $alldata['aname'];  
   echo $alldata['uname']; 
   echo $alldata['areaname']; 
   echo $alldata['dt']; 

   exit;  */                                 

	

	$msg=(object)[

        "msg"=>"Outlet successfully Added.",
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
$mail->Subject    = "Psp Outlet Addition";
//$mail->Body       = "This is the HTML message body <b>in bold!</b>";
$mail->AltBody    = "This is the body when user views in plain text format"; //Text Body
$mail->WordWrap   = 50; // set word wrap

//$mail->MsgHTML($body);

$mail->AddReplyTo("webtest@mayabious.com","Webmaster");

$mail->AddAddress("webtest@mayabious.com","Psp Outlet Addition");
//$mail->AddAddress($querydatas['email'],"info save");
                                                                                                                                             
$mail->IsHTML(true); // send as HTML


         $mail->Body='<table border="1">
                           <tr>
                           <td>Date Time</td>
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

}

/********************Mail Send Code Start**********************/





              
         /*else{
          echo '<span style="color:green;">Form Submitted Successfully.</span>';
         }*/
      




/********************Mail Send Code End**********************/

}

else{

$msg=(object)[

	  "msg"=>"User Not Found",
      "status"=>false
	];
}


print_r(json_encode($msg));




 /*$sql=mysqli_query($conn,"SELECT e.slno,e.area_name,e.zone_slno
                                 FROM   m_area e 
                                join m_zone z on e.zone_slno=z.slno
                                JOIN m_city c on z.city_slno=c.slno
                                JOIN m_branch b on c.branch_slno=b.slno
                                where e.area_name='".$aname."' and z.zone_name='".$zname."'
                                and c.city_name='".$cname."' and b.branch_name='".$bname."'");


 /*echo "SELECT e.slno,e.area_name,e.zone_slno
                                 FROM   m_area e 
                                join m_zone z on e.zone_slno=z.slno
                                JOIN m_city c on z.city_slno=c.slno
                                JOIN m_branch b on c.branch_slno=b.slno
                                where e.area_name='".$aname."' and z.zone_name='".$zname."'
                                and c.city_name='".$cname."' and b.branch_name='".$bname."'";*/

	/*if($data->uname!='')
	{
		mysqli_query($conn,"insert into tbl_test (name,email,phone) values ('".$data->uname."','".$data->email."','".$data->phone."')");	
		$val[]=array("msg"=>"success");
	}
	else
	{
		$val[]=array("msg"=>"Faliure");
	} */

	//print_r(json_encode($msg));

	

	


/*header('Content-type: application/json');

function webservice(){
$sql=mysqli_query(connection(),"SELECT e.slno,e.area_name,e.zone_slno
                                FROM   m_area e 
                                join m_zone z on e.zone_slno=z.slno
                                JOIN m_city c on z.city_slno=c.slno
                                JOIN m_branch b on c.branch_slno=b.slno
                                where area_name='KAMLA NAGAR'");

//$arr=array();
while($data=mysqli_fetch_object($sql)){

 $arr[]=$data->slno;
 $arr[]=$data->area_name;
 $arr[]=$data->zone_slno;
}
return $arr;
}

$valu = webservice();
print_r(json_encode($valu));*/
?>


