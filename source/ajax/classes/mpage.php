<?php
# Name:	mpage.php
# Desc:	This is a page to control the view for mobile devices.

class mPage{

	public function test(){
		$this->goToPage('home.php');
	}
	
	public function index(){
		$this->goToPage('index.php');
	}
	
	public function login(){
		$this->goToPage('login.php');
	}
	
	public function home(){
		$this->goToPage('home.php');
	}
	
	public function checkIn(){
		$this->goToPage('checkin.php');
	}
	
	public function account(){
		$this->goToPage('account.php');
	}
	
	public function profile(){
		$this->goToPage('profile.php');
	}
	
	public function friend(){
		$this->goToPage('friend.php');
	}
	
	public function wall(){
		$this->goToPage('wall.php');
	}
	
	public function goToPage($pageUrl){
		include_once($_SERVER['DOCUMENT_ROOT'].'/fyp/ajax/mpages/'.$pageUrl);
	}
}
?>