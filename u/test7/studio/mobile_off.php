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

<script>
function pass(vid)
  {
 
window.location="play.php?channelid=<?php echo $channelid; ?>&reset=true";

  }

  function reset()
  {
  //alert('reset');
  //donothing
  }
</script>
 
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet'>
</head>
<body>
<div class="vanilla-container">
<div class="video-container" style="position: absolute; top: 0; left: 0;"/>
<? $webtv->playNow();?>
</div>
</div>

<div class="demo-s-nochat"></div>

<?php
if ($_REQUEST[reset] == "true") {
$outname = "sendurl.php";
//$xmlq = "<script type=\"text/javascript\">window.parent.reset();</script>";
$xmlq = "<html>
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

        <script type=\"text/javascript\">window.parent.reset();</script>
    </body>
</html>";

  $out = fopen($outname, 'w') or die("Cannot open output file '$outname' for writing");
  fwrite($out, $xmlq);
  fclose($out);
//echo "updated";
}
else { //echo "nada";
}
?>

<iframe name="special" class="special" id="special" src="sendurl.php" frameborder="0" height="1px" width="1px" scrolling="no"></iframe>
<iframe name="control" id="control" src="loadif.html" width="1px" height="1px" frameborder="0" ></iframe></body>
</body>
</html>