<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		
		<title>New Account Registration</title>
		
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
		<link rel="stylesheet" href="resources/css/orange.css" type="text/css" media="screen" />  
		
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
		


    <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
    $("#postme").click(function(){
	var cid = $("#CID").val();
    var email = $("#email").val();
    var status = $("#status").val();
    var pwd = $("#pwd").val();
    var descrip = $("#descrip").val();
    var url = $("#url").val();

    $.ajax({
    type: "POST",
    url : "input_reg.php",
    data: "cid=" + cid + "&email=" + email + "&status=" + status + "&pwd=" + pwd +
"&descrip=" + descrip+"&url=" + url,
    success: function(data){
    $("#update").slideDown();
  
	// $("#CID").val('');
	// $("#email").val('');
	// $("#status").val('');
	// $("#pwd").val('');
	// $("#descrip").val('');
	// $("#url").val('');
    }
    });
    });
    });
    </script>


	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><a href="#">Register To Create Your TV Channel Now</a></h1>
		  
			<!-- Logo (221px wide) -->
			<img id="logo" src="resources/images/logo.png" alt="logo" />
		  
			<!-- Sidebar Profile links -->
			<div id="profile-links">
				Welcome New User.  <br />
				<br />
				<!--a href="#" title="View the Site">View the Site</a--> <a href="account.php" title="Log In Now">Log In Now</a>
			</div>        
			
			<ul id="main-nav">  <!-- Accordion Menu -->
				
	
				
				<li> 
					<a href="#" class="nav-top-item current"> <!-- Add the class "current" to current menu item -->
Accounts
					</a>
					<ul>
					
						<li><a href="help.php">View Tutorial Videos</a></li>
						<li><a href="account.php">Log In To Your Channel</a></li>
				
					</ul>
				</li>
				
				

				
			</ul> <!-- End #main-nav -->
			
			<div id="messages" style="display: none"> <!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->

				
			</div> <!-- End #messages -->
			
		</div></div> <!-- End #sidebar -->
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<noscript> <!-- Show a notification if the user has disabled javascript -->
				<div class="notification error png_bg">
					<div>
						Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.
					</div>
				</div>
			</noscript>
			
			<!-- Page Head -->
			<!--h2>Welcome, Admin</h2-->
						
			<div class="content-box">
				<iframe src="http://militiatoday.com/admin/docs/" width="100%" height="99%" frameborder="no" ></iframe>
			</div> <!-- End .content-box -->
			

			

			
			
			<!-- Start Notifications -->
			
			
			<!-- End Notifications -->
			
			<div id="footer">
				<small>
						&#169; Copyright 2015 | Powered by <a href="http://brandcoder.com" target="_blank">BrandCoder</a> | <a href="#">Top</a>
				</small>
			</div><!-- End #footer -->
			
		</div> <!-- End #main-content -->
		
	</div></body>
  
</html>
