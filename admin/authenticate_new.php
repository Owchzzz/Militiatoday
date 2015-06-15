<?php
if(isset($_REQUEST['cid'])){
	$uid = ($_REQUEST['cid']);


$con=mysqli_connect("localhost","militiat_chadmin","Temp0114!","militiat_brandcoder");
	//					$con=mysqli_connect("localhost","root","","test");
						// Check connection
						if (mysqli_connect_errno())
						  {
						  echo "Failed to connect to MySQL: " . mysqli_connect_error();
						  }
	
$qry="select * from channels where ChannelID='".$uid."'";	

	$result = mysqli_query($con,$qry);
//connect to db here
//get $uid from database where $uid is the account id
//check if status is "active"
while($row = mysqli_fetch_array($result)){
$uid_status=$row['status'];
}

//echo $uid_status;
if(strcasecmp($uid_status,'Active') == 0)
{
$access = "permitted";
}
else if (strcasecmp($uid_status,'Pending') == 0) {
	$access = "blocked";
}
else if (strcasecmp($uid_status,'Blocked') == 0) {
	$access = "blocked";
}
else {
    // Unknown request
    $access = "blocked";
}
echo "&access=$access";
}
?>