<?php require_once($_SERVER['DOCUMENT_ROOT'].'/fyp/requires.php');?>
<?php
	$t=User::curr();
?>
<html>
<head>
	<link rel="stylesheet" href="ext/css/mcss.css" type="text/css" />
	<link rel="stylesheet" href="ext/js/plugin/jquery.mobile-1.3.0/jquery.mobile-1.3.0.min.css" type="text/css"/>
	<script src="ext/js/plugin/jQuery.js"></script>
	<script src="ext/js/plugin/jquery.mobile-1.3.0/jquery.mobile-1.3.0.min.js"></script>
	<script type="text/javascript" src="ext/js/md5.js"></script>
	<script type="text/javascript" src="ext/js/mfunc.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=0.8">
	<title><?php echo SITE_NAME; ?></title>
</head>
<body>
<input type="hidden" id="uid" value="<?php if(isset($t)) echo $t->id();?>">
<input type="hidden" id="name" value="">
<input type="hidden" id="loginUid" value="<?php if(isset($t)) echo $t->id();?>">
<div id="hmMain">
