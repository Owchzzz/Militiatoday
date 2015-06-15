<!-- let's include the script file and make a new "play" class instance -->
<?php @require 'youtvfunctions.php';

if (isset($_REQUEST['channelid'])) {
$channelid = $_REQUEST['channelid'];}
else { $channelid = "musictv";}

$webtv = new play($channelid);


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $channelid; ?></title>
	<link rel="stylesheet" href="youtv.css" />
<script type='text/javascript' src='jwplayer.js'></script>
<!--script type='text/javascript' src='jwplayer/jwplayer.js'></script>
<script type="text/javascript" src="jwplayer.html5.js"></script-->
<script src="http://code.jquery.com/jquery-latest.js"></script>
<style type="text/css">
body {
	font-family: verdana;
	background-color: #000000;
	background-image: url(wpthemegen/images/stripes_2.gif);
	background-repeat: repeat;
	background-position: center top;
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

<?php
function saveXML($xmlq, $outname) {
	global $messages;
	if(file_exists($outname)) {
		//do nothing
	} 
	$out = fopen($outname, 'w') or die("Cannot open output file '$outname' for writing");
	fwrite($out, $xmlq);
	fclose($out);
	$messages .= "$outname saved.<br>";
	//echo "<script>alert('Changes to Broadcast Schedule are now Live on Your Channel!');</script>";
	
  }

//javascript command to be written to sendurl which calls the function abc in the parent frame that holds the guide
$liveupdate = "<html>
<head>
 <SCRIPT language=JavaScript>
    var message = '';

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

    document.oncontextmenu = new Function('return false')
</SCRIPT>
</head>
    <body>

    </body>
</html>";
saveXML($liveupdate, 'sendurl.php');
?>




	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet'>
</head>
<body>
<div class="wrap" style="background: transparent;"><img src="logo.png" style="position: relative; top: 0; left: 0;"/>

<div class="video-container" style="position: absolute; top: 0; left: 0;"/>
<? $webtv->playNow();?>
</div>
</div>

<!-- <div class="demo-resizeme"></div> -->
<iframe name="special" class="special" id="special" src="sendurl.php" frameborder="0" height="1px" width="1px" scrolling="no"></iframe>
<iframe name="control" id="control" src="loadif.html" width="1px" height="1px" frameborder="0" ></iframe></body>
 <script type="text/javascript">
//golive function	
            function pass(vid)
           {
   		   var channelid = vid;
		   window.location = 'play.php?channelid='+channelid;
			
	   }

        </script> 
</body>
</html>