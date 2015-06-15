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
				Welcome Demo User!
				
				
			</div>        
			
			<ul id="main-nav">  <!-- Accordion Menu -->
				
	
				
				<li> 
					<a href="#" class="nav-top-item current"> <!-- Add the class "current" to current menu item -->
Account
					</a>
					<ul>
						<li><a href="account.php">Log In To Your Channel</a></li>
						<li><a href="http://militiatoday.com/admin/docs/" target="_blank">View Documentation</a></li>
						
				
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
						
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Register New Account</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Create Account</a></li> <!-- href must be unique and match the id of target div -->
						
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					

					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
					
						<form action="input_reg.php" method="post">
							<h2>Enter a valid email address to get your unique Channel ID</h2>
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								    <div class="notification success png_bg" id="update" style="display:none;">
				<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
				<div>
					Channel Successfully Created! <a href="account.php" >Click here to login and start adding videos to your TV channel.</a>
				</div>
			</div>
								
								<!--label>Channel ID:</label-->
										<input class="text-input medium-input" type="hidden" id="CID" name="CID" value="<? echo rand(); ?>" /> 
										
								
								
								<p>
									<label>Email:</label>
									<input class="text-input medium-input" type="text" id="email" name="email" /> 
									<br /><small>Enter email address for Account Administrator</small>
								</p>
								
								<p>
									<label>Password:</label>
									<input class="text-input medium-input" type="password" id="pwd" name="pwd" />
									<br /><small>Create admin password</small>
								</p>
								
								<p>
									<label>TV Channel Description:</label>
									<input class="text-input medium-input" type="text" id="descrip" name="descrip" />
									<br /><small>Brief channel description</small>
									
								</p>
																
									<input type="hidden" id="url" name="url" /> 
									<input type="hidden" id="status" name="status" value="active">					
								
								<p>
									<!--input class="button" type="submit" value="Submit" /-->
									<input class="button" name="submit" value="CREATE TV CHANNEL" id="postme" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form-->
						
					</div> <!-- End #tab2 -->        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

			

			
			
			<!-- Start Notifications -->
			
			
			<!-- End Notifications -->
			
			<div id="footer">
				<small>
						&#169; Copyright 2015| Powered by BrandCoder | <a href="#">Top</a>
				</small>
			</div><!-- End #footer -->
			
		</div> <!-- End #main-content -->
		
	</div></body>
  
</html>
