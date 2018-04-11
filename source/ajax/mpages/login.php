<?php include_once('ajax/mpages/header.php');?>

<div data-role="page">
<!-- start header -->
	<div data-theme="a" data-role="header">
		<a data-role="button" href="#" onclick="$('#hmMain').load('ajax.php?act=mp&f=index');" class="ui-btn-left">Back</a>
		<h3>
			<?php echo SITE_NAME; ?>
		</h3>
	</div>
	<!-- end header -->
	<div data-role="content" data-theme="b" data-inset="true" align="center">
			<table width="100%">
				<tr>
					<td><h2 style="color:black;">Username:</h2></td>
					<td width="80%"><input type="text" name="loginName" id="loginName" style="width:80%;"/></td>
				</tr>
				<tr>
					<td><h2 style="color:black;">Password:</h2></td>
					<td width="80%"><input type="password" name="password" id="password" style="width:80%;"></td>
				</tr>
			</table>
			<input type="button" data-rel="dialog" value="Login" data-inline="true" data-theme="b" onclick="btnLogin();">
	</div>
</div>
<?php include_once('ajax/mpages/footer.php');?>