<?php @require 'youtvfunctions.php';

if (isset($_REQUEST['channelid'])) {
$channelid = $_REQUEST['channelid'];}
else { $channelid = "musictv";}
$account = basename(getcwd());
$webtv = new play($channelid);

?>
<!DOCTYPE html>
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

<!-- Basic Page Needs -->
<meta charset="utf-8">
<title>Now Playing: <?php echo $channelid; ?></title>
<meta name="description" content="<?php echo $channelid; ?>">
<meta name="author" content="WordpressTelevision">

<!-- Mobile Specific Metas -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!-- CSS -->
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/skeleton.css">
<link rel="stylesheet" href="css/layout.css">

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Favicons-->
<link rel="shortcut icon" href="img/favicon.ico">
<link rel="apple-touch-icon" href="img/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">
   
<!-- Jquery -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<!-- JQUERY COUNTDOWN-Change here your launch site date -->
<script type="text/javascript" src="js/countdown/jquery.countdown.js"></script>
<script type="text/javascript">
$(function () {
	var austDay = new Date();
	austDay = new Date(austDay.getFullYear() + 1, 5-12, 11);
	$('#defaultCountdown').countdown({until: austDay});
});
</script>
<SCRIPT language=JavaScript>
    var message = "";

    function clickIE() {
        if (document.all)
        { (message); return false; }
    }

    function clickNS(e) {
        if
(document.layers || (document.getElementById && !document.all)) {
            if (e.which == 2 || e.which == 3) { (message); return false; }
        }
    }
    if (document.layers)
    { document.captureEvents(Event.MOUSEDOWN); document.onmousedown = clickNS; }
    else
    { document.onmouseup = clickNS; document.oncontextmenu = clickIE; }

    document.oncontextmenu = new Function("return false")
</SCRIPT>
</head>
<body>
<div id="line_oblique_1">
    <div class="container">
       <div class="sixteen" id="logo">   <br><br>   <br><h1>Now Playing: <?php echo $channelid; ?></div>
   


<div class="twelve columns omega" id="video_container" >
<iframe src="vanilla.php?channelid=<?php echo $channelid; ?>" height="100%" width="100%" border="0" scrolling="no"></iframe>
     </div>
      <div class="four columns alpha" id="timer_container" >
      		<div id="back_in">  <div>      <?php
		if (!count($_POST)){
		?><div id="myform">
 <form action="<?php echo $_SERVER['PHP_SELF']; ?>?channelid=<? echo $channelid; ?>" method="post" id="myform" >
                            <input type="text" name="Email" value="Email Embed Code" class="required email" onBlur="if (this.value == '') { this.value = 'Email Embed Code';}" onFocus="if (this.value == 'Email Embed Code') {this.value = '';}">
                            <button type="submit" class="fsSubmitButton">Send</button>
                        </form></div>

            <?php
		}else{
	    ?>
        <!-- START SEND MAIL SCRIPT -->
        <div>
        	 <p class="success">Email Successfully Sent!</p>
        </div>
						
						<?php
						$mail = $_POST['Email'];

						/*$subject = "".$_POST['subject'];*/
						$to = $mail;
						$subject = "Embed Code for WordpressTelevision Channel: $channelid";
						$headers = "From: WordpressTelevision <noreply@wordpresstelevision.com>";
						$message = "Click this link to view your channel in the browser: http://wordpresstelevision.com/u/$account/play.php?channelid=$channelid \n\n Or, simply copy the code below into your HTML page where you want the channel to appear: \n\n <!-- copy to your page --><div id=\"realtv\" width=\"750\"><center><!-- copy to your page --><iframe src='http://wordpresstelevision.com/u/$account/play.php?channelid=$channelid' height='480px' width='720px' border='0' scrolling='no'></iframe><h2>This is a live beta channel. <br>Scan the QR code with your phone or tablet to chat on screen with other viewers.</h2><!-- end copy --></center></div><!-- end copy -->  \n\n";
						//$message .= "\nEmail: " . $_POST['Email'];
						//Receive Variable
						$sentOk = mail($to,$subject,$message,$headers);
						}
						?>
						
	<!-- END SEND MAIL SCRIPT -->    
        </div></div>
<div class="playinfos"><?php $webtv->playInfo(); ?></div>
            <!--div id="defaultCountdown"></div--><!-- Countdown -->
      </div><!-- End timer_container -->
      
      <br class="clear">
      
      <footer class="sixteen columns" >
      	        
        <div id="copy">
        	<p class=" five columns omega">Copyright WordpressTelevision 2015. All rights reserved. </p>
            
      </div>
      </footer><!-- End footer -->
      
  </div><!-- container -->
</div><!-- End line_oblique_1 -->
<div class="demo-s"></div>
<!-- JQUERY plugins: Moderniz, Tooltip, form Validate -->	
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript" src="js/functions.js"></script>  
</body>
</html>