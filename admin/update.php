<?php
$db = mysql_connect("localhost","militiat_chadmin","Temp0114!") or
die ("Unable to connect to Database Server.");
mysql_select_db ("militiat_brandcoder", $db) or die ("Could not select database.");

// $db = mysql_connect("localhost","root","") or
// die ("Unable to connect to Database Server.");
// mysql_select_db ("test", $db) or die ("Could not select database.");;

$cid=$_POST['cid'];
// $email=$_POST['email'];
$status=$_POST['status'];
// $pwd=md5($_POST['pwd']);
$descrip=$_POST['descrip'];
// $url=$_POST['url'];

//mysql_query("UPDATE channels set status='".$status."',description='" .$descrip." where channelID='".$cid."'");
$query="UPDATE channels set status='".$status."',description='" .$descrip."' where channelID='".$cid."'";
if(mysql_query($query))
    $response_array['status'] = 'success';  
else 
    $response_array['status'] = 'error';  

?>