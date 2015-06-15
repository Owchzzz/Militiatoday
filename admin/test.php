<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

$db = mysql_connect("localhost","militiat_chadmin","Temp0114!") or
die ("Unable to connect to Database Server.");
mysql_select_db ("militiat_brandcoder", $db) or die ("Could not select database.");;

$cid="qwert";
$email="support@brandcoder.com";
$status="active";
$pwd=md5("test");
$descrip="hello world";
$url="http://test.com";

$ret = mysql_query("INSERT INTO channels(channelID, email, status, password, description,pathurl)
values('$cid','$email','$status','$pwd','$descrip','$url')");

echo "ret = $ret";

echo mysql_error();

?>