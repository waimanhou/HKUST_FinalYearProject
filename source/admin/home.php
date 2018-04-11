<?php
session_start();
if(!isset($_SESSION['adminUsername'])||empty($_SESSION['adminUsername']))
	header( 'Location: index.php' ) ;
?>

HOME<br/>
<a href="admin.php?f=logout">Logout</a><br/>
<a href="admin.php?f=announcement">Announcements</a><br/>
<a href="admin.php?f=venue">Venues</a><br/>
<a href="admin.php?f=param">Parameters</a><br/>
<a href="admin.php?f=userAdmin">User Admin</a>