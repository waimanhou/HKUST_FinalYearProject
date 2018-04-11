<?php
require_once('adminFunc.php');

function radiusToMeter($r){
	//1 radius = 111.2 km
	return $r*111.2*1000;
}
?>
<html>
<head>
<script type="text/javascript" src="../ext/js/plugin/jQuery.js"></script>

</head>
<body>
<a href="home.php">HOME</a><br/><br/>
<form method="post" action="admin.php?f=setTimeLimit">
Check-In Time Limit For Finding Friends: <br/>
<input type="text" id="timeLimit" name="timeLimit" style="width:50px;" 
	value="<?php echo number_format(curlGet('/parameter/getTimeLimit')/(60*60*1000),2);?>"/> Hour(s)
<input type="submit" value="Save"/>
</form>
<form method="post" action="admin.php?f=setRadius">
Check-In Radius For Finding Friends: <br/>
<input type="text" id="radius" name="radius" style="width:50px;" 
	value="<?php echo floor(radiusToMeter(curlGet('/parameter/getRadius')));?>"/> Meter(m)
<input type="submit" value="Save"/>
</form>
</body>
</html>