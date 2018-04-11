//#	Name:	ajax.js
//#	Desc:	This file is used to place the ajax class,
//#			Before use this file, the jquery plugin is required.	
function ajax_init(){
	$('#main').load('ajax.php?act=p&f=index',function(){ $('body').css('display','block'); });
}

function ajax_index(){
	$('#main').load('ajax.php?act=p&f=index',function(){ $('body').css('display','block'); });
}

function ajax_home(callback){
	stopTimer();
	if(callback==null){
		$('#main').load('ajax.php?act=p&f=home',function(){ $('body').css('display','block'); });
	}else{
		$('#main').load('ajax.php?act=p&f=home&a=callback',
			function(){ 
				$('body').css('display','block');
				if(callback!=null)
					callback();
			}
		);
	}
}

function ajax_login(id,pwd){
	/*
	var html;	
	var cb=function(){
		$('#main').html(html);
	}
	
	$.post('ajax.php?act=u&f=login',{ 'id':id,'pwd':pwd },
		function(data){ html=data; if(!error(html)) slide_reflesh(cb); },'html'
	);
	*/
	var result;	
	var cb=function(){
		$('#main').html(result);
	}
	
	$.post('ajax.php?act=u&f=login',{ 'id':id,'pwd':pwd },
		function(data){
			result=data;
			if(result=='4')
				$("#errMsg2").html('*Account is locked.');
			else if((result=='1')||(result=='2'))
				$("#errMsg2").html('*Wrong username or password.');		
			else
				slide_reflesh(cb);
		},'text'
	);
}

function ajax_logout(){
	var html;
	var cb=function(){
		$('#main').html(html);	
	}
	
	$.get('ajax.php?act=u&f=logout',
		function(data){
			html=data;
			if(!error(html)){
				stopTimer();//window.history.pushState({title:'geosn',html:'geosn'},'','geosn');
				slide_reflesh(cb);
			}
		},'html'
	);
}

function ajax_event(intMonth,intYear){
	if(intMonth>0&&intMonth<13) $('#hmMain').load('ajax.php?act=p&f=event&m='+intMonth+'&y='+intYear);
	else $('#hmMain').load('ajax.php?act=p&f=event');
}

function ajax_calendar(intMonth,intYear){
	if(intMonth>0&&intMonth<13){
		$.get('ajax.php?act=p&f=calendar&m='+intMonth+'&y='+intYear,
			function(data){
				$('#calendar_frm').html(data);
				getEvent();
			},'html'
		);
	}else{
		$.get('ajax.php?act=p&f=calendar',
			function(data){
				$('#calendar_frm').html(data);
				getEvent();
			},'html'
		);
	}
}

//news
function ajax_news(){
	$('#hmMain').load('ajax.php?act=p&f=wall&t=news');
}

//wall
function ajax_wall(uid){
	$('#hmMain').load('ajax.php?act=p&f=wall&uid='+uid);
}

function ajax_post(pid){//display a specified post
	$('#hmMain').load('ajax.php?act=p&f=wall&pid='+pid);
}

//friend
function ajax_friend(uid){
	$('#hmMain').load('ajax.php?act=p&f=friend&uid='+uid);
}

//checkIn
function ajax_checkIn(){
	$('#hmMain').load('ajax.php?act=p&f=checkin');
}

//notification
function ajax_notification(){
	resetNotification();
	$('#hmMain').load('ajax.php?act=p&f=notification');
}

//account
function ajax_account(){
	$('#hmMain').load('ajax.php?act=p&f=account');
}

//profile
function ajax_profile(uid){
	$('#hmMain').load('ajax.php?act=p&f=profile&uid='+uid);
}

//search
function ajax_search(searchInfo){
	$('#hmMain').load('ajax.php?act=p&f=search&searchInfo='+searchInfo);
}

//venue
function ajax_venue(){
	$('#hmMain').load('ajax.php?act=p&f=venue');
}