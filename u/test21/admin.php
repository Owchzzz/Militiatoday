<?php
//error_reporting(E_ALL);
@require 'youtvfunctions.php';

	$bc = new admin_panel;
	// WARNING: the following conditional is a SIMPLE
	// login implementation! Since YouTV does not use databases,
	// a very simple login php script is almost secure
	// but you should setup a login process by yourself
	// to be really safe.
	
	include 'config.php'; 
	if ($_REQUEST['user']==$adminu && $_REQUEST['password']== $adminp){	
		$access = true;
	} else { $access = false;}
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

	<meta charset="utf-8" />
	<title>Channel Admin</title>
	<link rel="stylesheet" href="youtvadmin.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<style>
/* Let's get this party started */
::-webkit-scrollbar {
    width: 12px;
}
 
/* Track */
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
    -webkit-border-radius: 10px;
    border-radius: 10px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
    -webkit-border-radius: 10px;
    border-radius: 10px;
    background: rgba(51,51,51,0.8); 
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
}
::-webkit-scrollbar-thumb:window-inactive {
	background: rgba(51,51,51,0.4); 
}
</style>

</head>
<body>
<!--[if lte IE 8]>
<div id="ie8">
<![endif]-->
	<div class="heading clear">
		<p class="heading_title">MilitiaToday Admin</p>
		
		<img style="margin:20px;display:none;" id="ajax-preloader" src="icons/ajax-loader.gif" alt="loader" />
	</div>
<?php if ($access == true) {?>
	<div class="leftcol">
<div class="broadcasting_panel">
<p class="title">Preview</p>
<iframe src="play.php?channelid=default" width="250px" height="180px" border="0" frameborder="no" muted="muted"></iframe>
</div>

<div class="broadcasting_panel">
<p class="title">TV Guide</p>
<a href="convert.php?src=youtv" target="conversion">Generate TV Guide Schedule</a><br>
<iframe src="null.php" name="conversion" id="conversion" width="250px" height="40px" border="0" frameborder="no" scrolling="no" ></iframe>
</div>

	<div class="broadcasting_list">
		<? @$bc->print_broadcasting_list(); ?>
	</div>

</div>

<div class="main">

	<div class="broadcasting_main">
		<? @$bc->print_broadcasting();?>
	</div>


<div class="broadcasting_panel">
<p class="title">Search</p>
<iframe src="http://watchstartup.com/search" width="250px" height="480px" border="0" frameborder="no"></iframe>
</div>




	<div class="broadcasting_new">
		<p class="title">New Channel</p>
		<form id="addbroadcasting">
			<fieldset>
				<label>Title</label>
				<input type="text" name="title" maxlength="20"></input>
				<label>Starting time (24h)</label>
				<span class="time">
					<input class="input_small" name="time1" type="text" maxlength="2"></input><p>:</p><input class="input_small" name ="time2" type="text" maxlength="2"></input>
				</span>
				<label>Days</label>
				<div class="daysblock">
					<label for="mon">M</label>
					<input id="mon" name="days[1]" type="checkbox"></input>
					<label for="tue">T</label>
					<input id="tue" name="days[2]" type="checkbox"></input>
					<label for="wed">W</label>
					<input id="wed" name="days[3]" type="checkbox"></input>
					<label for="thu">T</label>
					<input id="thu" name="days[4]" type="checkbox"></input>
					<label for="fri">F</label>
					<input id="fri" name="days[5]" type="checkbox"></input>
					<label for="sat">S</label>
					<input id="sat" name="days[6]" type="checkbox"></input>
                    <label for="sun">S</label>
					<input id="sun" name="days[0]" type="checkbox"></input>
					<input type="hidden" name="action" value="add_broadcasting"></input>
				</div>
			</fieldset>
		</form>
		<button class="metrobtn btn_big bcadd"></button>
	</div>
<? }else{ ?>
	<form id="login" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

<br>
<br><br>
    <div class="ytv_pre_login_info"><p> Enter your admin credentials below to login</p></div>
    <fieldset>
    <label for="user">Admin Username:</label>
    <input id="user" type="text" name="user" />
    <label for="password">Password: </label>
    <input id="password" type="password" name="password"/>
    <button type="submit" class="metrobtn btn_big save"></button>
    </fieldset>
    </form>
<? } ?>
	</div>

	<div class="footer clear"></div>

<!-- Ajax Calls using jQuery -->
<script type="text/javascript" src="youtvadmin.js"></script>
<!--[if lte IE 8]>
</div>
<![endif]-->


</body>
</html>