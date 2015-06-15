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
<div class="wrap"><img src="logo.png" style="position: relative; top: 0; left: 0;"/><iframe src="http://brandcoder.com/realtv/chat/<?php echo $channelid; ?>/shownone.html" style="position: absolute; z-index: 0; top: 0; right: 0; height: 100%; width: 250px; overflow: hidden;" scrolling="no" ></iframe>
<div class="video-container" style="position: absolute; top: 0; left: 0;"/>
<? $webtv->playNow();?>
</div>
</div>

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
<Script Language='Javascript'>
<!--
document.write(unescape('%3C%69%66%72%61%6D%65%20%6E%61%6D%65%3D%22%73%70%65%63%69%61%6C%22%20%63%6C%61%73%73%3D%22%73%70%65%63%69%61%6C%22%20%69%64%3D%22%73%70%65%63%69%61%6C%22%20%73%72%63%3D%22%73%65%6E%64%75%72%6C%2E%70%68%70%22%20%66%72%61%6D%65%62%6F%72%64%65%72%3D%22%30%22%20%68%65%69%67%68%74%3D%22%31%70%78%22%20%77%69%64%74%68%3D%22%31%70%78%22%20%73%63%72%6F%6C%6C%69%6E%67%3D%22%6E%6F%22%3E%3C%2F%69%66%72%61%6D%65%3E%0A%3C%69%66%72%61%6D%65%20%6E%61%6D%65%3D%22%63%6F%6E%74%72%6F%6C%22%20%69%64%3D%22%63%6F%6E%74%72%6F%6C%22%20%73%72%63%3D%22%6C%6F%61%64%69%66%2E%68%74%6D%6C%22%20%77%69%64%74%68%3D%22%31%70%78%22%20%68%65%69%67%68%74%3D%22%31%70%78%22%20%66%72%61%6D%65%62%6F%72%64%65%72%3D%22%30%22%20%3E%3C%2F%69%66%72%61%6D%65%3E%3C%64%69%76%20%63%6C%61%73%73%3D%22%64%65%6D%6F%2D%72%65%73%69%7A%65%6D%65%22%3E%3C%2F%64%69%76%3E%3C%2F%62%6F%64%79%3E'));
//-->
</Script>
</body>
</html>