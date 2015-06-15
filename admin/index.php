<?php
session_start(); 
if ($_SESSION['color'] == "true") {
echo "okay!";
//header('Location: admin.php' );

}
else {
//do nothing
}
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
				<img id="logo" src="resources/images/logo.png" alt="Administration Console logo" />
			</div> <!-- End #logn-top -->
			
			<div id="login-content">
				
				<form action="index.php" method="post">
				
					<div class="notification information png_bg">
						<div>
<?php

if (!isset($_REQUEST['login'])) {
   echo "Site Admin Console";
}
else {
$login = $_REQUEST['login'] ;
$pass = $_REQUEST['pass'] ;


$login_exp = "admin";
$pass_exp = "password";
if($login == $login_exp && $pass == $pass_exp) {
	echo "Eureka! You've Found It!";
	$_SESSION['color'] = "true";
	echo "<meta http-equiv='refresh' content='2;url=admin.php'>";
  }
else {
    echo "Opps! Please Enter A Valid Username and Password to Continue!";
    $_SESSION['color'] = "false";
	}
}
?>



						</div>
					</div>
					
					<p>
						<label>Username</label>
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
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" value="Sign In" />
					</p>
					
				</form>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
  </body>
  
</html>
