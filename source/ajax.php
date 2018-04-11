<?php #ajax.php
require_once('requires.php');

switch($_GET['act']){
	case 'p': include_once('ajax/api/pageAPI.php'); break;
	case 'u': include_once('ajax/api/userAPI.php'); break;
	case 'mp':include_once('ajax/api/mpageAPI.php'); break;
	case 'mu':include_once('ajax/api/muserAPI.php'); break;
	default: echo 'Invalid action in ajax.php.'; break;
}
?>