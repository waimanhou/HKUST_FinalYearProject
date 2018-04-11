<?php 
# Name:	requires.php
# Desc:	This php is only used to include the required php files like a file list.

date_default_timezone_set('PRC');
ini_set('default_charset', 'utf-8');

session_start();

//System Configuration files
require_once('system/config/settings.php');

//Libraries
require_once('ajax/classes/coreFunc.php');
require_once('ajax/classes/page.php');
require_once('ajax/classes/mpage.php');
require_once('ajax/classes/user.php');
require_once('ajax/classes/event.php');
require("ajax/api/phpMailer/class.phpmailer.php");
?>