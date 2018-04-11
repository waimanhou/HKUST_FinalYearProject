<?php require_once($_SERVER['DOCUMENT_ROOT'].'/fyp/requires.php');?>

<html>
<head>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.css"/>
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="http://code.jquery.com/mobile/latest/jquery.mobile.min.css" />
	<link rel="stylesheet" type="text/css" href="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.min.css" /> 

	<!-- Optional Mousewheel support: http://brandonaaron.net/code/mousewheel/docs -->
	<script type="text/javascript" src="PATH/TO/YOUR/COPY/OF/jquery.mousewheel.min.js"></script>

	<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.core.min.js"></script>
	<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/latest/jqm-datebox.mode.calbox.min.js"></script>
	<script type="text/javascript" src="http://dev.jtsage.com/cdn/datebox/i18n/jquery.mobile.datebox.i18n.en_US.utf8.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=0.8">
	<title><?php echo SITE_NAME; ?></title>
</head>
<body>
 <div data-role="page">
 	<div data-role="header" class="h">
		<h1>Sign Up</h1>
		<a href="../../index.php" data-icon="forward" class="ui-btn-left" rel=external>Back</a>
	</div><!-- /header -->
	<div data-role="content" align="center">
		<form action="register.php?action=email" method="post">
			<table width="100%">
				<tr>
					<td>Title</td>
					<td>
						<select name="type" id="type" class="typeId-menu" style="font-size:15px;">
							<option value="-">--</option>
							<option value="Miss">Miss</option>
							<option value="Ms.">Ms.</option>
							<option value="Mrs.">Mrs.</option>
							<option value="Mr.">Mr.</option>
							<option value="Dr.">Dr.</option>
							<option value="Prof.">Prof.</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>First Name</td>
					<td><input type="text" name="txtRegFname"></td>
				</tr>
				<tr>
					<td>Last Name</td>
					<td><input type="text" name="txtRegLname"></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="text" name="txtRegEmail"></td>
				</tr>
				<tr>
					<td>Usernname</td>
					<td><input type="text" name="txtRegUsername"></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="txtRegPWD"></td>
				</tr>
				<tr>
					<td>Confirm Password</td>
					<td><input type="password" name="txtRegPWDConfirm"></td>
				</tr>
				<tr>
					<td>Gender</td>
					<td>
						<select id="selRegSex" class=".inputSel">
							<option value="-">--</option>
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Date of Birth</td>
					<td>
						<label for="mydate">Some Date</label>

						<input name="mydate" id="mydate" type="date" data-role="datebox"
						   data-options='{"mode": "datebox"}'>
					</td>
				</tr>
			</table>
			<input type="submit" value="Submit">
		</form>
	</div>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/fyp/ajax/mpages/footer.php');?>