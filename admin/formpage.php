<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FOLDER CREATE + UNZIP+ EMAIL</title>
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
    url : "input.php",
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
    <style type="text/css">
    #wrapper{background:lavender; border:1px solid #96F; height: 300px; width:300px;
margin-left: 35px;}
    form{padding: 10px;}
    span{padding: 3px;}
    label{padding-left: 40px;display: block;}
    label #postme{margin-top: 40px; color:#003; font-family:Verdana, Geneva, sans-serif;
font-size:12px; font-weight:bold; margin-left: 80px;}
    input{border: 1px solid #333; background:#CCC;}
    h3{margin-left: 50px;}
    </style>
    </head>

    <body>
    <h3>FOLDER CREATE + UNZIP+ EMAIL</h3>
    <div id="wrapper">

    <span>Chanel ID<label><input type="text" maxlength="15" name="CID" id="CID" /></label></span>
    <span>Email <label><input type="text"  name="email" id="email" /></label></span>
    <span>Status<label><input type="text" maxlength="15" name="status" id="status" /></label></span>
    <span>Password <label><input type="password" maxlength="15" name="pwd" id="pwd" /></label></span>
    <span>Description <label><input type="text" maxlength="180" name="descrip" id="descrip" /></label></span>
	<span>Path URL <label><input type="text" maxlength="15" name="url" id="url" /></label></span>
    <label><input type="submit" name="submit" value="EXECUTE" id="postme"/></label>

    </div>

    <div style="height:7px"></div>
    <div id="flash" align="left" style="display:none;"><img src="loader.gif" align="absmiddle">
 <span>Saving Record,....</span></div>

    <ol id="update" style="display:none;">
    <img src="success.png"> Data was saved!
    </ol>
    </body>
    </html>