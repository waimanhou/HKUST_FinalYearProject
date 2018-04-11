<?php #pageAPI.php
$p = new Page();
$u = User::curr();
$loggedIn=CoreFunc::loggedIn();
if($_GET['f']=='login'&&!$loggedIn){//Login
	//echo User::login($_POST['id'],$_POST['pwd'])?$p->home():CoreFunc::genErrorMsg('Login Fail','You already logged in.','xxx.xxx.xx');
	$result=User::login($_POST['id'],$_POST['pwd']);
	if($result=='3')
		echo $p->home();
	else
		echo $result;
		
}else if($_GET['f']=='logout'){//Logout
	if(isset($u)&&$loggedIn)
		echo $u->logout()?$p->index():CoreFunc::genErrorMsg('Logout Fail','You already logged out.','xxx.xxx.xx');
	else
		echo $p->index();
		
}else if($_GET['f']=='register'){//Register
	$result=User::register($_POST['info']);
	if($result!='Succeed')
		echo $result;
	else
		echo User::login($_POST['id'],$_POST['pwd'])?$p->home():CoreFunc::genErrorMsg('Login Fail','You already logged in.','xxx.xxx.xx');
}else if($_GET['f']=='resetPassword'){		
	$result=User::resetPassword($_POST['uname'],$_POST['email']);
	echo $result;
}else if($_GET['f']=='change_my_info'){//Change user info
	$t=User::curr();
	$result=$t->change_my_info($_POST['info']);
	if($result==true){
		echo '<strong style="color:#4CC417;">*Saved.</strong>';
	}else{
		echo '<strong style="color:#FF0000;">*Email address exists.</strong>';
	}
}else if($_GET['f']=='change_my_pwd'){
	$t=User::curr();
	$result=$t->change_my_pwd($_POST['oldPwd'],$_POST['newPwd']);
	echo $result;
}else if($_GET['f']=='getCurrentUserInfo'){	
	$t=User::curr();
	echo $t->get_userById($t->id());
}else if($_GET['f']=='get_userById'){
	$t=User::curr();
	$result=$t->get_userById($_POST['uid']);
	echo $result;
}else if($_GET['f']=='profilePic'){
	$t=User::curr();
	$result=$t->changeProfilePic($_FILES['file']);
	echo $result;
}else if($_GET['f']=='checkIn'){
	$t=User::curr();
	$result=$t->checkIn($_POST['venueId'],$_POST['longitude'],$_POST['latitude']);
	echo $result;
}else if($_GET['f']=='checkInByVid'){
	$t=User::curr();
	$result=$t->checkInByVid($_POST['venueId']);
	echo $result;
}else if($_GET['f']=='checkInHistory'){
	$t=User::curr();
	echo $t->checkInHistory($t->id());
}else if($_GET['f']=='getFriendsCheckin'){
	$t=User::curr();
	echo $t->getFriendsCheckin($_POST['skip']);
}else if($_GET['f']=='getNearestFriends'){
	$t=User::curr();
	echo $t->getNearestFriends($_POST['longitude'],$_POST['latitude']);
}else if($_GET['f']=='getFriendsInRange'){
	$t=User::curr();
	echo $t->getFriendsInRange($_POST['longitude'],$_POST['latitude']);
}else if($_GET['f']=='insertOwnWall'){
	$t=User::curr();
	echo $t->insertOwnWall($_POST['content']);
}else if($_GET['f']=='insertFrdWall'){
	$t=User::curr();
	echo $t->insertFrdWall($_POST['fId'],$_POST['content']);
}else if($_GET['f']=='insertEvtWall'){
	$t=User::curr();
	echo $t->insertEvtWall($_POST['eId'],$_POST['authorComment']);
}else if($_GET['f']=='replyPost'){
	$t=User::curr();
	echo $t->replyPost($_POST['pid'],$_POST['content']);
}else if($_GET['f']=='deletePost'){
	$t=User::curr();
	echo $t->deletePost($_POST['pid']);
}else if($_GET['f']=='deleteReply'){
	$t=User::curr();
	echo $t->deleteReply($_POST['replyId']);
}else if($_GET['f']=='getPostById'){	
	$t=User::curr();
	echo $t->getPostById($_POST['pid']);
}else if($_GET['f']=='getWallPost'){
	$t=User::curr();
	echo $t->getWallPost($_POST['uid'],$_POST['skip']);
}else if($_GET['f']=='getWallPostByWallId'){
	$t=User::curr();
	echo $t->getWallPostByWallId($_POST['wallId'],$_POST['skip']);
}else if($_GET['f']=='getNewsFeedPost'){
	$t=User::curr();
	echo $t->getNewsFeedPost($t->id(),$_POST['skip']);
}else if($_GET['f']=='searchUser'){
	$t=User::curr();
	echo $t->searchUser($_POST['info']);
}else if($_GET['f']=='sendFriendRequest'){
	$t=User::curr();
	echo $t->sendFriendRequest($_POST['fid']);
}else if($_GET['f']=='frdRequest'){
	$t=User::curr();
	echo $t->friendRequest($_POST['frdReqId'],$_POST['decision']);
}else if($_GET['f']=='isFriendWith'){
	$t=User::curr();
	echo $t->isFriendWith($_POST['uid']);
}else if($_GET['f']=='unFriend'){
	$t=User::curr();
	echo $t->unFriend($_POST['fid']);
	//Venue
}else if($_GET['f']=='getVenueByLoc'){	
	$t=User::curr();
	echo $t->getVenueByLoc($_POST['longitude'],$_POST['latitude']);
}else if($_GET['f']=='getVenueByName'){	
	echo User::getVenueByName($_POST['name']);
	//Event
}else if($_GET['f']=='getVenueByNameExact'){	
	echo User::getVenueByNameExact($_POST['name']);
	//Event
}else if($_GET['f']=='createEvent'){
	$t=User::curr();
	echo $t->createEvent($_POST['title'],$_POST['startDate'],$_POST['endDate'],$_POST['venue'],$_POST['applyDate'],$_POST['eventContent']);
}else if($_GET['f']=='updateEvent'){
	//updateEvent($eId,$title,$startdate,$endDate,$venue,$applyDate,$eventContent)
	$t=User::curr();
	echo $t->updateEvent($_POST['eId'],$_POST['title'],$_POST['startDate'],$_POST['endDate'],$_POST['venue'],$_POST['applyDate'],$_POST['eventContent']);
}else if($_GET['f']=='deleteEvent'){
	$t=User::curr();
	echo $t->deleteEvent($_POST['eId']);
}else if($_GET['f']=='getEventById'){
	$t=User::curr();
	echo $t->getEventById($_POST['evtId']);
}else if($_GET['f']=='getEvent'){
	$t=User::curr();
	echo $t->getEvent($t->id());
}else if($_GET['f']=='sendEventRequest'){
	$t=User::curr();
	echo $t->sendEventRequest($_POST['eID'],$_POST['fID']);
}else if($_GET['f']=='getRequestByUserId'){
	$t=User::curr();
	echo $t->getRequestByUserId($_POST['eId']);
}else if($_GET['f']=='replyEventRequest'){
	$t=User::curr();
	echo $t->replyEventRequest($_POST['reqId'],$_POST['decision']);
}else if($_GET['f']=='resetNotification'){
	$t=User::curr();
	echo $t->resetNotification();
}else if($_GET['f']=='getActiveAnnouncement'){
	echo User::getActiveAnnouncement();
}else echo 'Invalid action UserAPI.php.';	
	
	
/*
else if($_GET['f']=='logout'&&$loggedIn)
	echo $u->logout()?$p->index():CoreFunc::genErrorMsg('Logout Fail','You already logged out.','xxx.xxx.xx');
else echo 'Invalid action UserAPI.php.';
*/
?>