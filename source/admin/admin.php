<?php
# Name:	admin.php
# Desc:	This contain admin functions.
require_once('adminFunc.php');

session_start();
if(($_GET['f']!='login')&&(!isset($_SESSION['adminUsername'])||empty($_SESSION['adminUsername'])))
	header( 'Location: index.php' );
	
	
if($_GET['f']=='login'){
	$username=$_POST['username'];
	$password=$_POST['password'];
	
	#/admin/checkLogin/{uname|email}/{pswd}
	//[real_connection]
	$result=curlGet('/admin/checkLogin/'.$username.'/'.$password);
	//$result='1';
	if($result=='3'){//login success
		$_SESSION['adminUsername']=$username;
		header( 'Location: home.php' ) ;
	}else{//login fail
		echo 'fail';		
	}
}else if($_GET['f']=='logout'){
	unset($_SESSION['adminUsername']); 
	header( 'Location: index.php' ) ;
}else if($_GET['f']=='announcement'){
	header( 'Location: announcement.php' ) ;
}else if($_GET['f']=='addAnnouncement'){
	#"2013-02-06T06:46:17.000Z"   Date format
	#"/insert/{aId}/{content}/{effectDate}"
	$adminId=getAdminId();

	//[real_connection]
	if($_POST['ms']=='now'){
		$result=curlGet('/announcement/insert/'.$adminId.'/'.$_POST['content'].'/'.time());
	}else{
		$result=curlGet('/announcement/insert/'.$adminId.'/'.$_POST['content'].'/'.$_POST['ms']);
	}
	
	//$result='1';
	if($result=='1'){
		header( 'Location: announcement.php' ) ;
	}else{
		//echo 'fail';
		echo '/announcement/insert/'.$adminId.'/'.$_POST['content'].'/'.$_POST['ms'];
	}
}else if($_GET['f']=='deleteAnnocunce'){
	#deleteAnnouncementWS: @Path("/deleteAnnouncement/{aId}/{annId}")
	$announceId=$_GET['announceId'];
	$result=curlGet('/admin/deleteAnnouncement/'.getAdminId().'/'.$announceId);
	if($result=='1'){//success
		header( 'Location: announcement.php' ) ;
	}else{
		echo 'fail';
	}
}else if($_GET['f']=='venue'){
	header( 'Location: venue.php' ) ;
}else if($_GET['f']=='insertVenue'){
	$result=curlGet('/venue/insert/'.htmlspecialchars($_POST['venueName']).'/'.$_POST['longitude'].','.$_POST['latitude']);
	header( 'Location: venue.php' ) ;
}else if($_GET['f']=='deleteVenue'){
	$result=curlGet('/venue/delete/'.$_GET['venueId']);
	header( 'Location: venue.php' ) ;
}else if($_GET['f']=='editVenue'){
	#/update/{vId}/{venueName}/{loc: String}
	$result=curlGet('/venue/update/'.$_GET['venueId'].'/'.$_GET['vname'].'/'.$_GET['vlong'].','.$_GET['vlat']);
	header( 'Location: venue.php' ) ;
}else if($_GET['f']=='param'){
	header( 'Location: param.php' ) ;
}else if($_GET['f']=='setTimeLimit'){
	$result=curlGet('/parameter/setTimeLimit/'.$_POST['timeLimit']*60*60*1000);
	if($result=='1'){//success
		header( 'Location: param.php' ) ;
	}else{
		echo 'fail';
	}
}else if($_GET['f']=='setRadius'){
	$result=curlGet('/parameter/setRadius/'.$_POST['radius']/111.2/1000);
	if($result=='1'){//success
		header( 'Location: param.php' ) ;
	}else{
		echo 'fail';
	}	
}else if($_GET['f']=='userAdmin'){
	header( 'Location: userAdmin.php' ) ;
}else if($_GET['f']=='searchUser'){
	//searchUserWS: @Path("/searchUser/{keyword}"), Optional: skip: int, limit: int
	$result=curlGet('/user/searchUser/'.$_POST['info']);
	if($result!='0'){
		echo '{"curl":['.$result.']}';
	}else
		echo '';
}else if($_GET['f']=='lockUnlockAccount'){
	$result=curlGet('/user/isAccountLocked/'.$_POST['uid']);
	
	$admin=curlGet('/admin/getAdminByUsername/'.$_SESSION['adminUsername']);
	$r='{"curl":['.$admin.']}';
	$obj=json_decode($r,true);
	$aid='';
	foreach($obj['curl'] as $key=>$val){ 
		$aid=$val['adminId'];
	}
	
	if($result=='1'){//locked
		$result=curlGet('/admin/unlockAccount/'.$aid.'/'.$_POST['uid']);
		echo $result;
	}else{//not locked
		$result=curlGet('/admin/lockAccount/'.$aid.'/'.$_POST['uid']);
		echo $result;
	}
}














?>