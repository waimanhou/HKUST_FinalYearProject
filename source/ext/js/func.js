 function processAjaxData(response, urlPath){
     document.getElementById("content").innerHTML = response.html;
     document.title = response.pageTitle;
     //window.history.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath);
 }

window.onpopstate = function(event){
	if(event && event.state){
		//document.title = event.state.title;
		//document.body.innerHTML = event.state.html;
		window.location = event.state.html;
	}
}

var glob_msgBox=null;
function init(){ ajax_init(); }
function focusTxt(txtID){ $('#'+txtID).focus(); }

function init_textarea(strID){
	 $('#'+strID).autosize();
	 $('#'+strID).unbind('onkeyup');
}

var isOnmouseover=false;

function setOnmouseover(state){
	isOnmouseover=state;
}

function stopTimer(){
	if(typeof(wallTimer) != "undefined")
		clearInterval(wallTimer);
}

function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}

function search(){
	$('#headerSearch').val(trim($('#headerSearch').val()));
	if($('#headerSearch').val()!=''){
		//alert($('#headerSearch').val());
		ajax_search($('#headerSearch').val());
	}
}





/*#########
  # index #
  #########*/
function CalDays(intYear,intMonth){
	switch(parseInt(intMonth)){
  		case 1:case 3: case 5: case 7: case 8: case 10: case 12: return 31;
  		case 4:case 6: case 9: case 11: return 30;
  		case 2:return (intYear=='-'||isLeapYear(intYear))?29:28;
	}
}

function isLeapYear(intYear){
	var intYear=parseInt(intYear);
	return (intYear%4==0&&intYear%100!=0)||(intYear%400==0)?true:false;
}

function birthday_change(){
	var options='<option value="-">Date</option>';
	var lastDate=31;
	var y=$('#selRegBirthYear').val();
	var m=$('#selRegBirthMonth').val();
	var d=$('#selRegBirthDate').val();
	if(m!='-')lastDate=CalDays(y,m);
	for(var i=1;i<=lastDate;i++) options+='<option value="'+i+'">'+i+'</option>';
	$('#selRegBirthDate').html(options);
}

function txtKeepLogin_change(){ $('#txtKeepLogin').val($('#txtKeepLogin').val()=='Yes'?'No':'Yes'); }

function btnSignUp_click(){
	var title=$("#selRegTitle").val();
	var firstName=escape($("#txtRegFname").val());
	var lastName=escape($("#txtRegLname").val());
	var emailAddress=$("#txtRegEmail").val();
	var username=$("#txtRegUsername").val();
	var password=$("#txtRegPWD").val();
	var confirmPassword=$("#txtRegPWDConfirm").val();
	var gender=$("#selRegSex").val();
	var birthYear=$("#selRegBirthYear").val();
	var birthMonth=$("#selRegBirthMonth").val();
	var birthDate=$("#selRegBirthDate").val();
	
	//alert(hex_md5(password)); 
	//user/insert/{uname}/{pswd}/{title}/{firstName}/{lastName}/{gender}/{emailAddress}/{dateOfBirth}
	if((title=='--')||(firstName=='')||(lastName=='')||(emailAddress=='')||(username=='')||
		(password=='')||(confirmPassword=='')||(gender=='')||(birthYear=='Year')||(birthMonth=='Month')||(birthDate=='Date')){
		$("#errMsg").html("*Please fill in all information.");
	}else if((password.length<8)||((password.length>20))){
		$("#errMsg").html("*Password must have 8-20 characters.");
	}else if(password==confirmPassword){
		var regInfo='/'+username+'/'+hex_md5(password)+'/'+title+'/'+firstName+'/'+lastName+'/'+gender+'/'+emailAddress+'/'+birthDate+'-'+birthMonth+'-'+birthYear;
		//alert(regInfo);
		
		var result;	
		var cb=function(){
			$('#main').html(result);
		}
		
		$.post('ajax.php?act=u&f=register',{ 'info':regInfo,'id':username,'pwd':hex_md5(password) },
			function(data){
				result=data;
				if((result!='Username exists')&&(result!='Email address exists.'))
					slide_reflesh(cb);
				else
					$("#errMsg").html('*'+result);
			},'html'
		);
		
	}else{
		$("#errMsg").html("*Password does not match.");
	}
}

function convertDateTime(tid,dateTime){
	var myDate = new Date(dateTime);
	myDate.setTime(myDate.getTime() - myDate.getTimezoneOffset() * 60 * 1000);
	$('#'+tid).html(myDate.toUTCString());
}

function convertDateDimeToDateString(dateTime){
	var myDate = new Date(dateTime);
	myDate.setTime(myDate.getTime() - myDate.getTimezoneOffset() * 60 * 1000);
	return myDate.getFullYear()+'/'+myDate.getMonth()+'/'+myDate.getDate();
}

function convertDateTime(eid){
	var info = $('[id^="'+eid+'"]').map(
		function () {
			if($(this).html().indexOf('GMT')!=-1)
				return;
			var myDate = new Date($(this).html());
			myDate.setTime(myDate.getTime() - myDate.getTimezoneOffset() * 60 * 1000);
			$(this).html(myDate.toUTCString());
		}
	);
}

function convertAllMSTimeToDate(eid){
	var info = $('[id^="'+eid+'"]').map(
		function () {
			//alert($(this).html());
			var date=new Date(parseInt($(this).html()));
			$(this).html(date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate());
		}
	);
}

function btnLogin_click(){
	if($('#main').is(':animated')) return false;
	var id=$('#txtLoginID').val();
	var pwd=$('#txtLoginPWD').val();
	if(id.length>0 && pwd.length>0){ 
		ajax_login(id,hex_md5(pwd));
	}else{
		msgBox_show(
			'Login Fail',
			(id.length<=0&&pwd.length<=0)?'Please enter your login ID and password.':(id.length<=0)?'Please enter your login ID.':'Please enter your password.',
			'warning',
			'message'
		);
	}
}

function signUpForm_change(e){
	var toReg=$('#loginForm').css('top')!='0%'&&$('#loginForm').css('top')!='0px'?false:true;
	var top=toReg?70:0;
	var dis=toReg?'block':'none';
    $('#signUpForm').animate({ display:dis });
    $('#loginForm').animate({ top:top+'%' });
}

function btnForgotPwd_click(){
	glob_msgBox=null;
	var msg='<br/><table>'+
		'<tr><td>Username:</td><td><input id="forgotPwdUname" type="text" style="border: 1px solid gray;"/></td></tr>'+
		'<tr><td>Email:</td><td><input id="forgotPwdEmail" type="text" style="border: 1px solid gray;"/></td></tr>'+
		'<tr><td colspan="2"><p id="forgotPwdErrMsg" style="color:red;"></p></td></tr></table>';
	glob_msgBox=new MsgBox('Forgot Password',msg,'question','message');
	glob_msgBox.btnCustom=function(){
		
		
		if($('#forgotPwdUname').val()=='')
			$('#forgotPwdErrMsg').html('Please fill in your username.');
		else if($('#forgotPwdEmail').val()=='')
			$('#forgotPwdErrMsg').html('Please fill in your email address.');
		else{
			$('#forgotPwdUname').attr("disabled",true); 
			$('#forgotPwdEmail').attr("disabled",true); 
			$('#msgBox_btnOK').attr("disabled",true); 
			$('#msgBox_btnOK').css('display','none');
			$.post('ajax.php?act=u&f=resetPassword',{'uname':$('#forgotPwdUname').val(), 'email':$('#forgotPwdEmail').val()},
				function(data){
					if(data=='0'){
						$('#forgotPwdErrMsg').html('Username or Email address not correct.');
					}else if(data=="success"){
						glob_msgBox.Hide();
						msgBox_show('Reset Password','Your new password is sent to your email.','info','message');
					}
				},'text'
			);
			//this.Hide();
		}	
		//this.Hide();
		//if(selectedUid.length>0)
			//msgBox_show('Invitation Sent','Your request is sent.','info','message');
	};
	glob_msgBox.Show();
}

/*########
  # home #
  ########*/
function btnLogout_click(){
	if(!$('#slide').is(':animated')) ajax_logout();
}

function btnHome_click(){
	//ajax_index();
	ajax_home();
}

function btnTNL_click(){
	var tns=$('#hmTNs');
	if(tns.is(':animated')) return;
	$('#btnTNL_arrow').css('display','none');
	$('#btnTNR_arrow').css('display','block');
	tns.animate({ 'margin-left':'0px' });
}

function btnTNR_click(){
	var tns=$('#hmTNs');
	if(tns.is(':animated')) return;
	$('#btnTNL_arrow').css('display','block');
	$('#btnTNR_arrow').css('display','none');
	tns.animate({ 'margin-left':'-875px' });
}

function getNumOfNotification(){
	$.post('ajax.php?act=u&f=getCurrentUserInfo',{},
		function(json){
			if(json!=null){
				for(var i=0;i<json.curl.length;i++){
					//alert(json.curl[i].numOfRequest.notification);
					if(json.curl[i].numOfRequest.notification>0)
						$('#numNotifications').html('('+json.curl[i].numOfRequest.notification+')');
					else
						$('#numNotifications').html('');
					if(json.curl[i].numOfRequest.friendRequest>0)
						$('#numFriendRequest').html('('+json.curl[i].numOfRequest.friendRequest+')');
					else
						$('#numFriendRequest').html('');
					if(json.curl[i].numOfRequest.eventRequest>0)
						$('#numEventRequest').html('('+json.curl[i].numOfRequest.eventRequest+')');
					else
						$('#numEventRequest').html('');
				}
			}
		},'json'
	);
}
/*################
  # Notification #
  ###############*/

	function resetNotification(){
		$.post('ajax.php?act=u&f=resetNotification',{},
			function(data){
				if(data=='1'){
					//$('#numNotifications').html('0');
					getNumOfNotification();
				}
			},'text'
		);
	}
	
/*#########
  # Event #
  #########*/
function eventGoTo_click(intMonth,intYear){
	ajax_calendar(intMonth,intYear);
}

function event_getWallId(evtId){
	$.post('ajax.php?act=u&f=getEventById',{'evtId':evtId},
		function(json){
			if(json!=null){
				for(var i=0;i<json.curl.length;i++){
					return json.curl[i].wallId;
				}
			}
		},'json'
	);
}

function event_getRequest(evtId){
	$('#evtDecision').hide();
	$.post('ajax.php?act=u&f=getRequestByUserId',{'eId':evtId},
		function(json){
			if(json!=null){
				for(var i=0;i<json.curl.length;i++){
					$('#hidden_reqId').val(json.curl[i].evtReqId);
					//if(json.curl[i].status=='pending')
						$('#evtDecision').show();
				}
			}
		},'json'
	);
}

function event_showInfo(evtId){
	closeCreateEventForm();
	$('#evt_msg').html('');
	$.post('ajax.php?act=u&f=getEventById',{'evtId':evtId},
		function(json){
			if(json!=null){
				for(var i=0;i<json.curl.length;i++){
					$.post('ajax.php?act=u&f=getVenueByNameExact',{'name':encodeURIComponent(json.curl[i].venue)},
						function(json2){
							if(json2!=null){
								for(var k=0;k<json2.curl.length;k++){
									$('#evt_venue').html('<a onclick="$(\'#hmMain\').load(\'ajax.php?act=p&f=venue&vid='+json2.curl[k].venueId+'\');">'+json2.curl[k].venueName+'</a>');
								}
							}
						},'json'
					);
					$('#evt_title').text(json.curl[i].title);
					$('#evt_desc').html(json.curl[i].eventContent);
					$('#evt_start_date').text(convertMSTimeToDate(json.curl[i].duration.start));
					$('#evt_end_date').text(convertMSTimeToDate(json.curl[i].duration.end));
					//$('#evt_apply_date').text(convertMSTimeToDate(json.curl[i].applyDate));
					$('#evt_venue').text(json.curl[i].venue);
					$('#evt_info').fadeIn(1000);
					var result='';
					
					result+='<img id="profilePic" style="vertical-align:middle;" src="'+$('#hidden_profilePicUrl').val()+json.curl[i].creator.profilePic+'" width="25" height="25"> ';
					result+='<a href="#" onclick="browseFrd(\''+json.curl[i].creator.userId+'\',\''+json.curl[i].creator.name+'\');">';
					result+=json.curl[i].creator.name+'</a>';
					$('#creatorUser').html(result);
					
					if(json.curl[i].usersInvited!=null){
						$('#titleInvited').html('Invited('+json.curl[i].usersInvited.count+')');
						var result='';
						for(var j=0;j<json.curl[i].usersInvited.count;j++){
							result+='<p id=""><table><tr><td><img id="profilePic" src="'+$('#hidden_profilePicUrl').val()+json.curl[i].usersInvited.list[j].profilePic+'" width="25" height="25"></td>';
							result+='<td>';
							result+='<a href="#" onclick="browseFrd(\''+json.curl[i].usersInvited.list[j].userId+'\',\''+json.curl[i].usersInvited.list[j].name+'\');">';
							result+=json.curl[i].usersInvited.list[j].name+'</a></td></tr></table></p>';
						}
						$('#invitedUser').html(result);
					}else{
						$('#titleInvited').html('Invited(0)');
						$('#invitedUser').html('');
					}
					
					if(json.curl[i].usersJoined!=null){
						$('#titleJoined').html('Joined('+json.curl[i].usersJoined.count+')');
						var result='';
						for(var j=0;j<json.curl[i].usersJoined.count;j++){
							result+='<p id=""><table><tr><td><img id="profilePic" src="'+$('#hidden_profilePicUrl').val()+json.curl[i].usersJoined.list[j].profilePic+'" width="25" height="25"></td>';
							result+='<td>';
							result+='<a href="#" onclick="browseFrd(\''+json.curl[i].usersJoined.list[j].userId+'\',\''+json.curl[i].usersJoined.list[j].name+'\');">';
							result+=json.curl[i].usersJoined.list[j].name+'</a></td></tr></table></p>';
						}
						$('#joinedUser').html(result);
					}else{
						$('#titleJoined').html('Joined(0)');
						$('#joinedUser').html('');
					}
					
					$('#hidden_evtId').val(evtId);
					$('#hidden_wallId').val(json.curl[i].wallId);
					if(json.curl[i].creator.userId==$('#hidden_uid').val()){
						$('#inviteBtn').show();
						$('#evtAdmin').show();
					}else{
						$('#inviteBtn').hide();
						$('#evtAdmin').hide();
					}
					getEventMsg(0,json.curl[i].wallId);
					event_getRequest(evtId);
					location.href = "#";
					location.href = "#evt_info";
					//return;
				}
			}else{
				msgBox_show('Event Deleted','The event is no longer exist.','error','message');
			}
		},'json'
	);
	
	/*
	var details=$('#evt'+evtId+'_hdnInfo').val();
	var details=details.split('|');
	$('#evt_title').text(details[0]);
	$('#evt_desc').html(decodeURIComponent(details[1]));
	$('#evt_start_date').text(convertMSTimeToDate(details[2]));
	$('#evt_end_date').text(convertMSTimeToDate(details[3]));
	$('#evt_apply_date').text(convertMSTimeToDate(details[4]));
	$('#evt_info').fadeIn(1000);
	$('#hidden_evtId').val(evtId);
	$('#hidden_wallId').val(wallId);
	getEventMsg(wallId);
	*/
}

function replyEventRequest(decision){
	$.post('ajax.php?act=u&f=replyEventRequest',{'reqId':$('#hidden_reqId').val(),'decision':decision},
		function(data){
			if(data=='1'){
				//msgBox_show();
				getNumOfNotification();
				$('#hmMain').load('ajax.php?act=p&f=event');
			}
		},'text'
	);
}

function evt_btnWrite_click(){
	var e=$('#evt_txtWrite');
	alert(e.val());
}

function editEvent(){
	s='';
	s+='<p class="subtitle">Edit Event</p>';
	s+='<table>';
	s+='<tr><td>Start Date:</td><td><input id="txtStartDate" class="eventInputBox" type="text" readonly="readonly" onclick="pickDate(\'txtStartDate\')" value="'+$('#evt_start_date').html()+'"/></td></tr>';
	//s+='<tr><td>End Date:</td><td><input id="txtEndDate" class="eventInputBox" type="text" readonly="readonly" onclick="pickDate(\'txtEndDate\')" value="'+$('#evt_end_date').html()+'"/></td></td></tr>';
	s+='<tr><td>Title:</td><td><input id="txtTitle" class="eventInputBox" type="text" value="'+$('#evt_title').html()+'"/></td></tr>';
	s+='<tr><td>Venue:</td><td><input id="txtVenue" class="eventInputBox" type="text" value="'+$('#evt_venue').html()+'"/></td></tr>';
	//Reserve
	//s+='<tr><td>Apply Date:</td><td><input id="txtApplyDate" class="eventInputBox" type="text" readonly="readonly" onclick="pickDate(\'txtApplyDate\')"/></td></tr>';
	s+='<tr><td>Description:</td><td></td></tr>';
	s+='<tr><td colspan="2"><textarea id="txtDesc" rows="3" cols="30">'+$('#evt_desc').text()+'</textarea></td></tr>';
	s+='<tr><td></td><td align="right"><p class="buttons"><a href="#" class="regular" onclick="closeCreateEventForm();">Close</a><a href="#" class="regular" onclick="updateEvent();">Save</a></p></td></tr>';
	s+='</table>';
	$('#frmCreateEvent').html(s);
}

function updateEvent(){
	//alert($('#hidden_evtId').val()+"/"+encodeURIComponent($('#txtTitle').val())+"/"+convertDate($('#txtStartDate').val())+"/"+convertDate($('#txtStartDate').val())+"/"+$('#txtVenue').val()+"/"+convertDate($('#txtStartDate').val())+"/"+encodeURIComponent($('#txtDesc').val().replace(/\n/g, '<br/>')));
	$.post('ajax.php?act=u&f=updateEvent',{
		'eId':$('#hidden_evtId').val(),
		'title':encodeURIComponent($('#txtTitle').val()),
		'startDate':convertDate($('#txtStartDate').val()),
		'endDate':convertDate($('#txtStartDate').val()),//same as txtStartDate
		'venue':encodeURIComponent($('#txtVenue').val()),
		'applyDate':convertDate($('#txtStartDate').val()),//same as txtStartDate
		'eventContent':encodeURIComponent($('#txtDesc').val().replace(/\n/g, '<br/>'))
		},
		function(data){
			if(data=='1'){
				closeCreateEventForm();
				$("#hmMain").load("ajax.php?act=p&f=event");
			}
		},'text'
	);
}

function deleteEvent(){
	glob_msgBox=null;
	glob_msgBox=new MsgBox('Delete Event','Are you sure to delete the event?','warning','confirm');
	glob_msgBox.btnCustom=function(){
		$.post('ajax.php?act=u&f=deleteEvent',{'eId':$('#hidden_evtId').val()},
			function(data){
				if(data=='1'){
					$("#hmMain").load("ajax.php?act=p&f=event");
				}
			},'text'
		);
		this.Hide();
	};
	glob_msgBox.Show();
}


/*###############
  # Message Box #
  ###############*/
function msgBox_show(title,message,icon,boxType){
	glob_msgBox=null;
	glob_msgBox=new MsgBox(title,message,icon,boxType);
	glob_msgBox.Show();
}

function msgBox_hide(){ if(glob_msgBox!=null) glob_msgBox.Hide(); }

/*########
  # Mask #
  ########*/
function globalMask_show(){
	var h=$('header').height()+$('#main').height()+$('footer').height()+$('#remarks').height()+50;
	if(h<$(window).height()) h=$(window).height();
	$('#globalMask').css({
		'height':h+'px',
		'display':'block',
		'position':'absolute',
		'top':'-1px'
	});
}

function globalMask_hide(){ $('#globalMask').css('display','none'); }

/*#########
  # Slide #
  #########*/
function slide_hide(callback){	//up
	var m=$('#main');
	m.animate({height:'0px'},m.height()/0.7,function(){if(callback!=null)callback();});
}

function slide_show(callback){	//down
	var m=$('#main');
	var hdr=$('header');
	if(hdr.length) hdr.css('display','none');
	m.css({display:'none', height:'auto'});
	var h=m.height();
	m.css({height:'0px',display:'block'});
	$('#main').animate({height:h+'px'},h/0.7,function(){ m.css('height','auto'); if(callback!=null)callback();});
	if(hdr.length) hdr.css('display','block');
}

function slide_reflesh(callback){
	var cb=function(){ callback(); slide_show(); }
	slide_hide(cb);
}

/*##################
  #	error handling #
  ##################*/
function error(html, callback){
	if($('error').length>0){
		var title=$('title').html();
		var err_msg=$('message').html();
		var code=$('code').html();
		var msg='<div>'+err_msg+'</div><div id="err_code">'+code+'</div>';
		msgBox_show(title,msg,'error','message');
		return true;
	}else{
		if(callback != null) callback();
		return false;
	}
}


function isDate(txtDate){
	var currVal = txtDate;
	if(currVal == '')
		return false;
	  
	  //Declare Regex  
	var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; 
	var dtArray = currVal.match(rxDatePattern); // is format OK?

	if (dtArray == null)
		return false;
	 
	  //Checks for mm/dd/yyyy format.
	dtMonth = dtArray[1];
	dtDay= dtArray[3];
	dtYear = dtArray[5];

	if (dtMonth < 1 || dtMonth > 12)
		return false;
	else if (dtDay < 1 || dtDay> 31)
		  return false;
	else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
		  return false;
	else if (dtMonth == 2){
		var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
		if (dtDay> 29 || (dtDay ==29 && !isleap))
			return false;
	}
	return true;
}
  