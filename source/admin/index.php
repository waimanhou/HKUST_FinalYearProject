<?php
session_start();
if(isset($_SESSION['adminUsername'])||!empty($_SESSION['adminUsername']))
	header( 'Location: home.php' ) ;
?>

<html>
<head>
<script type="text/javascript" src="../ext/js/plugin/jQuery.js"></script>
<script type="text/javascript">
	function btnLogin(){		
		$.post("admin.php?f=login",{ "username":$('#username').val(),"password":$('#password').val() },
			function(data){
				result=data;
				if(result=='fail')
					$("#errMsg").html('*Wrong username or password.');
				else
					$('#main').html(result);
			},'html'
		);
	}					
</script>
</head>
<body id="main">
Administration
<form action="admin.php?f=login" method="post">
<table>
<tr><td>Username</td><td><input id="username" name="username" type="text"></td></tr>
<tr><td>Password</td><td><input id="password" name="password" type="password" onkeydown="if(event.keyCode==13){btnLogin();return false;}"></td></tr>
<tr><td></td><td><input type="button" value="Login" onclick="btnLogin();"></td></tr>
</table>
<span id="errMsg" style="font-weight:bold;color:#FF0000;"></span>
</form>
nick<br>password
</body>
</html>