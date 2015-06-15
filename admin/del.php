<?php
$db = mysql_connect("localhost","militiat_chadmin","Temp0114!") or
die ("Unable to connect to Database Server.");
mysql_select_db ("militiat_brandcoder", $db) or die ("Could not select database.");

// $db = mysql_connect("localhost","root","") or
// die ("Unable to connect to Database Server.");
// mysql_select_db ("test", $db) or die ("Could not select database.");

$cid=$_GET['id'];


//mysql_query("UPDATE channels set status='".$status."',description='" .$descrip." where channelID='".$cid."'");
$query="DELETE from channels where channelID='".$cid."'";

if(mysql_query($query))
    header("Location: admin.php");
else 
    echo "$query can't be executed";


?>