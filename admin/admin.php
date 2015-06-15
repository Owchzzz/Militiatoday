<?php
session_start(); 
if ($_SESSION['color'] == "true") {
	//do nothing :)
}
else {
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		
		<title>Administration Console</title>
		
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
		



	</head>
  
	<body>


<script type="text/javascript">
<!--
function confirmation(cid) {
	var answer = confirm("Do you want to delete Account ID: " +cid)
	if (answer){
		alert("Account ID has been Deleted")
		window.location = "del.php?id="+cid;
	}
	else{
		alert("Thanks!! No action taken")
	}
}
//-->
</script>


	<div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><a href="#">Administration Console</a></h1>
		  
			<!-- Logo (221px wide) -->
			<a href="#"><img id="logo" src="resources/images/logo.png" alt="Administration Console logo" /></a>
		  
			<!-- Sidebar Profile links -->
			<div id="profile-links">
				Hello, Admin, <br />
				<br />
				<!--a href="#" title="View the Site">View the Site</a--> <a href="logout.php" title="Sign Out">Sign Out</a>
			</div>        
			
			<ul id="main-nav">  <!-- Accordion Menu -->
				
	
				
				<li> 
					<a href="#" class="nav-top-item current"> <!-- Add the class "current" to current menu item -->
Accounts
					</a>
					<ul>
					
						<li><a class="current" href="admin.php">Manage Accounts</a></li> <!-- Add class "current" to sub menu items also -->
						<li><a href="create.php">Create New Account</a></li>
				
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
					
					<h3>Accounts</h3>
					
					<ul class="content-box-tabs">
						<li><a href="#tab1" class="default-tab">Account List</a></li> <!-- href must be unique and match the id of target div -->
						
					</ul>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
						
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								Use the account creation tool on the left to add a new account.
							</div>
						</div>
						<?php
						$con=mysqli_connect("localhost","militiat_chadmin","Temp0114!","militiat_brandcoder");
						//$con=mysqli_connect("localhost","root","","test");
						// Check connection
						if (mysqli_connect_errno())
						  {
						  echo "Failed to connect to MySQL: " . mysqli_connect_error();
						  }


						  
						  
						  
						$result = mysqli_query($con,"SELECT * FROM channels");

						
						  echo "<table><thead><tr><th><input class='check-all' type='checkbox' /></th>
								   <th>Account ID</th>
								   <th>Status</th>
								   <th>Info</th>
								   <th>Admin</th>
								   <th>Pass</th>
								   <th>Edit</th>
								</tr>
								
							</thead>
							<tfoot>
								<tr>
									<td colspan='6'>
										<div class='bulk-actions align-left'>
											<select name='dropdown'>
												<option value='option1'>Choose an action...</option>
												<option value='option2'>Edit</option>
												<option value='option3'>Delete</option>
											</select>
											<a class='button' href='#'>Apply to selected</a>
										</div>
										
										<!--div class='pagination'>
											<a href='#' title='First Page'>&laquo; First</a><a href='#' title='Previous Page'>&laquo; Previous</a>
											<a href='#' class='number current' title='1'>1</a>
											<a href='#' class='number' title='2'>2</a>
											<a href='#' class='number' title='3'>3</a>
											<a href='#' class='number' title='4'>4</a>
											<a href='#' title='Next Page'>Next &raquo;</a><a href='#' title='Last Page'>Last &raquo;</a>
										</div--> <!-- End .pagination -->
										<div class='clear'></div>
									</td>
								</tr>
							</tfoot>
							
							<tbody>";
							$usr='kristen';
							while($row = mysqli_fetch_array($result))
						  {
						   if(strcasecmp($row['status'],'Active') == 0)
						  $clr="<td class='status-active'>".$row['status']."</td>";
						   if(strcasecmp($row['status'],'Pending') == 0)
						  $clr="<td class='status-pending'>".$row['status']."</td>";
						  if(strcasecmp($row['status'],'Blocked') == 0)
						   $clr="<td class='status-blocked'>".$row['status']."</td>";
						 $chid= "'".$row['channelID']."'";
						  echo"
								<tr>
									<td><input type='checkbox' /></td>
									<td>".$row['channelID']."</td>".$clr."
									<td>".$row['description']."</td>
									<td>".$row['email']."</td>
									<td>".$row['password']."</td>
									<td>
										<!-- Icons -->
										 <a href='edit.php?cid=".$row['channelID']."' title='Edit'><img src='resources/images/icons/pencil.png' alt='Edit' /></a>
										 <a href=\"javascript:confirmation($chid);\" title='Delete'><img src='resources/images/icons/cross.png' alt='Delete' /></a> 
										 
									</td>
								</tr>
							";
						  
						  
						  }
						echo "</tbody>
							
						</table>";
						?>
						
								
					
								
								
								
								
								
								
								
								
								
							
						
					</div> <!-- End #tab1 -->
					
					
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

			

			
			<div id="footer">
				<small>
						&#169; Copyright 2015 | Powered by <a href="http://brandcoder.com" target="_blank">BrandCoder</a> | <a href="#">Top</a>
				</small>
			</div><!-- End #footer -->
			
		</div> <!-- End #main-content -->
		
	</div></body>
  
</html>
