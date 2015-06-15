<html><head>
<style type="text/css">
body,html {
	font-family: verdana;
	background-color: #000000;
	width: 100%;
	height: 99.9%;
	margin: 0px;
	padding: 0px;
	color: #fff;
}
a{
	color: #fff;
}
</style> 
<?php if (isset($_REQUEST['channelid'])) { $channelid = $_REQUEST['channelid'];} else { $channelid = "musictv";} ?>
</head>

<Script Language='Javascript'>
<!--
document.write(unescape('%3C%62%6F%64%79%3E%3C%69%66%72%61%6D%65%20%73%72%63%3D%27%68%74%74%70%3A%2F%2F%6D%69%6F%63%61%73%74%2E%63%6F%6D%2F%75%2F%64%65%6D%6F%2F%70%6C%61%79%2E%70%68%70%3F%63%68%61%6E%6E%65%6C%69%64%3D'));
//-->
</Script><?php echo $channelid; ?><Script Language='Javascript'>
<!--
document.write(unescape('%27%20%68%65%69%67%68%74%3D%27%39%39%2E%39%25%27%20%77%69%64%74%68%3D%27%39%39%25%27%20%62%6F%72%64%65%72%3D%22%30%22%20%66%72%61%6D%65%62%6F%72%64%65%72%3D%22%30%22%20%73%63%72%6F%6C%6C%69%6E%67%3D%27%6E%6F%27%3E%3C%2F%69%66%72%61%6D%65%3E%3C%2F%62%6F%64%79%3E%3C%2F%68%74%6D%6C%3E'));
//-->
</Script>