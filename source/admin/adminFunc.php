<?php
require_once('../system/config/settings.php');
function getAdminId(){
	#/admin/getAdminByUsername/{uname}
	#[real_connection]
	$result='{"curl":['.curlGet('/admin/getAdminByUsername/'.$_SESSION['adminUsername']).']}';
	//$result='{"curl":['.curlGet('/admin/adminUser.json').']}';
	$obj = json_decode($result,true);
	//echo $result;
	$adminId='';
	foreach($obj['curl'] as $key=>$val){ 
		$adminId=$val['adminId'];
	}
	return $adminId;
}

function curlGet($json_url){
	$username = 'your_username';  // authentication
	$password = 'your_password';  // authentication
	 
	// jSON String for request
	$json_string = '[your json string here]';
	 
	// Initializing curl
	// $curlConnectionUrl is defined in /system/config/settings
	$ch = curl_init(curlConnectionUrl.$json_url);
	 
	// Configuring curl options
	$options = array(
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_USERPWD => $username . ":" . $password,   // authentication
	CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
	CURLOPT_POSTFIELDS => $json_string,
	CURLOPT_HTTPGET => true
	);
	 
	// Setting curl options
	curl_setopt_array( $ch, $options );
	
	$result=curl_exec($ch); // Getting result string
	
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	if($http_status==200)
		return $result;
	else
		return '';
}
?>