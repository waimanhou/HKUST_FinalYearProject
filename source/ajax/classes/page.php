<?php
# Name:	page.php
# Desc:	A class, it provides all of the page functions.

# Class Description (Page)
#------------------------------------------------------
# + index():string
# + home():string

class Page{	
	public function index(){
		//ob_start();
		include_once('ajax/pages/index.php');
		//include_once($_SERVER['DOCUMENT_ROOT'].'fyp/ajax/'.CoreFunc::getPageFolderName().'/index.php');
		//$c=ob_get_contents();
		//ob_end_clean();
		//return $c;
	}
	
	public function test(){
		//include_once($_SERVER['DOCUMENT_ROOT'].'fyp/ajax/'.CoreFunc::getPageFolderName().'/home.php');
		include_once('ajax/pages/home.php');
	}
	
	public function home($uid=null){
		include_once("ajax/pages/home.php".(CoreFunc::valuable($uid)?"?t=$username":""));
		//include_once("ajax/pages/home.php?t=".$uid);
	}
	
	public function event($uid=null,$intMonth,$intYear){
		include_once("ajax/pages/event.php".(CoreFunc::valuable($uid)?"?t=$username&m=$intMonth&y=$intYear":""));
	}
	
	public function calendar($uid=null,$intMonth,$intYear){
		$e=new Event();
		echo $e->drawCalendar($intMonth,$intYear);
	}
	
	public function wall(){
		include_once('ajax/pages/wall.php');
	}
	
	public function post(){
		include_once('ajax/pages/post.php');
	}
	
	public function friend(){
		include_once('ajax/pages/friend.php');
	}
	
	public function checkIn(){
		include_once('ajax/pages/checkin.php');
	}
	
	public function notification(){
		include_once('ajax/pages/notification.php');
	}
	
	public function account(){
		include_once('ajax/pages/account.php');
	}
	
	public function profile(){
		include_once('ajax/pages/profile.php');
	}
	
	public function search(){
		include_once('ajax/pages/search.php');
	}
	
	public function venue(){
		include_once('ajax/pages/venue.php');
	}
}
?>