<head>

<?php
function saveXML($xmlq, $outname) {
	global $messages;
	if(file_exists($outname)) {
		//rename($outname, $outname.'-'.date('Y-m-d-H:i:s'));
		//$messages .= "$outname exists, renaming.<br>";
	} 
	$out = fopen($outname, 'w') or die("Cannot open output file '$outname' for writing");
	fwrite($out, $xmlq);
	fclose($out);
	$messages .= "$outname saved.<br>";
	//echo "<script>alert('Changes to Broadcast Schedule are now Live on Your Channel!');</script>";
	//echo" <li id='standard'>Standard notification</li>";

  }

$vid = $_GET['vid'];
//$title = $_GET['title'];
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

        <script type=\"text/javascript\">window.parent.pass('".$vid."');</script>
    </body>
</html>";
saveXML($liveupdate, 'sendurl.php');
?>
</head>
	<body>

		<div id="welcome">
	<ul class="menu">
		<li id="standard">Standard notification</li>
		
	</ul>
</div>
	</body>
	</html>