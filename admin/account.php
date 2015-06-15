<?php
//session_start(); 
//if ($_SESSION['color'] == "true") {
//echo "okay!";
//header('Location: admin.php' );

//}
//else {
//do nothing
//}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		
		<title>Administration Console | Sign In</title>
		
		<!--                       CSS                       -->
	  
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="resources/css/reset.css" type="text/css" media="screen" />
	  
		<!-- Main Stylesheet -->
		<link rel="stylesheet" href="resources/css/style.css" type="text/css" media="screen" />
		
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<link rel="stylesheet" href="resources/css/invalid.css" type="text/css" media="screen" />	
		
		<!-- Colour Schemes
	  
		Default colour scheme is green. Uncomment prefered stylesheet to use it.
		
		<link rel="stylesheet" href="resources/css/blue.css" type="text/css" media="screen" />
		
		<link rel="stylesheet" href="resources/css/red.css" type="text/css" media="screen" />  
	 
		-->
		
		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="resources/css/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<!--                       Javascripts                       -->
	  
		<!-- jQuery -->
		<script type="text/javascript" src="resources/scripts/jquery-1.3.2.min.js"></script>
		
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="resources/scripts/simpla.jquery.configuration.js"></script>
		
		<!-- Facebox jQuery Plugin -->
		<script type="text/javascript" src="resources/scripts/facebox.js"></script>
		
		<!-- jQuery WYSIWYG Plugin -->
		<script type="text/javascript" src="resources/scripts/jquery.wysiwyg.js"></script>
		
		<!-- Internet Explorer .png-fix -->
		
		<!--[if IE 6]>
			<script type="text/javascript" src="resources/scripts/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
	<link rel="stylesheet" href="resources/css/orange.css" type="text/css" media="screen" /> 	
	</head>
  
	<body id="login">
		


		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
				<h1>Administration Console</h1>

				<!-- Logo (221px width) -->
				<img id="logo" src="resources/images/logo.png" alt="" />
			</div> <!-- End #logn-top -->
			
			<div id="login-content">
				
				<form action="account.php" method="post">
				
					<div class="notification information png_bg">
						<div>
<?php

if (!isset($_REQUEST['login'])) {
   echo "Enter Your Unique Channel ID To Continue";
}
else {
$uid = $_REQUEST['login'] ;
$pass = $_REQUEST['pass'] ;

$con=mysqli_connect("localhost","militiat_chadmin","Temp0114!","militiat_brandcoder");

						if (mysqli_connect_errno())
						  {
						  echo "Failed to connect to MySQL: " . mysqli_connect_error();
						  }
	
$qry="select * from channels where ChannelID='".$uid."'";	

	$result = mysqli_query($con,$qry);
//connect to db here
//get $uid from database where $uid is the channel id
//check if status is "active"
while($row = mysqli_fetch_array($result)){
$uid_status=$row['status'];
}

//echo $uid_status;
if(strcasecmp($uid_status,'Active') == 0)
{
$access = "<b><font color=\"#cc0000\">Great! Let's Go To Your Admin</font></b>";
//echo "<META http-equiv=\"refresh\" content=\"0;URL=http://militiatoday.com/u/$uid/admin.php\">";
echo "<META http-equiv=\"refresh\" content=\"0;URL=http://militiatoday.com/u/$uid/admin.php?user=$uid&password=$pass\">";
}
else if (strcasecmp($uid_status,'Pending') == 0) {
	$access = "<b><font color=\"#cc0000\">Your Account is Pending Activation. Contact support@militiatoday.com for help. </font></b>";
}
else if (strcasecmp($uid_status,'Blocked') == 0) {
	$access = "<b><font color=\"#cc0000\">Uh Oh! Your Channel ID is Blocked! Pleasce contact support@militiatoday.com for help.</font></b>";
}
else {
    // Unknown request
    $access = "<b><font color=\"#cc0000\">Hey! That Channel ID Does Not Exist in our database! Please check your inputs. If you are still having trouble, contact support@militiatoday.com </font></b>";
}
echo "$access";
}
?>



						</div>
					</div>
					
					<p>
						<label>Channel ID</label>
						<input class="text-input" type="text" id="login" name="login"/>
					</p>
					<div class="clear"></div>
					<p>
						<label>Password</label>
						<input class="text-input" type="password" id="pass" name="pass" />
					</p>
					<div class="clear"></div>
					<p id="remember-password">
						<input type="checkbox" />Remember me
					</p>
<!--a href="register.php"><b>Register Now</b></a-->
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" value="Log In" />
					</p>

					
				</form>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
  </body>
  
</html>
