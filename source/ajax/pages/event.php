<?php
# Name: event.php
# Desc: Here is the event page to show what events is related to the user
$e=new Event();

$m=CoreFunc::GET('m');
$y=CoreFunc::GET('y');

$t=User::curr();

?>
<script>
	var skip=0;
	var eventSkip=0;
	var pickingDate=false;
	var pickingDateId;
	function openMsg(msgdiv){
		document.getElementById(msgdiv).style.display='block'; 
		document.getElementById('fade').style.display='block';
	}
	function closeMsg(msgdiv){
		document.getElementById(msgdiv).style.display='none';
		document.getElementById('fade').style.display='none';
	}
	function showCreateEventForm(y,m,d){
		if(pickingDate==true){
			$('#'+pickingDateId).val(y+'/'+m+'/'+d);
			pickingDate=false;
		}else{
			s='';
			s+='<p class="subtitle">Create Event</p>';
			s+='<table>';
			s+='<tr><td>Start Date:</td><td><input id="txtStartDate" class="eventInputBox" type="text" readonly="readonly" onclick="pickDate(\'txtStartDate\')" value="'+y+'/'+m+'/'+d+'"/></td></tr>';
			//s+='<tr><td>End Date:</td><td><input id="txtEndDate" class="eventInputBox" type="text" readonly="readonly" onclick="pickDate(\'txtEndDate\')"/></td></td></tr>';
			s+='<tr><td>Title:</td><td><input id="txtTitle" class="eventInputBox" type="text"/></td></tr>';
			s+='<tr><td>Venue:</td><td><input style="width:145px;" id="txtVenue" class="eventInputBox" type="text" onkeydown="if(event.keyCode==13){eventFindVenue($(\'#txtVenue\').val());return false;}"/> <span id="searchResult" class="buttons" style="line-height: 35px;"><a class="regular" onclick="eventFindVenue($(\'#txtVenue\').val());">Find</a></span></td></tr>';
			//Reserve
			//s+='<tr><td>Apply Date:</td><td><input id="txtApplyDate" class="eventInputBox" type="text" readonly="readonly" onclick="pickDate(\'txtApplyDate\')"/></td></tr>';
			
			s+='<tr><td>Description:</td><td></td></tr>';
			s+='<tr><td colspan="2"><textarea id="txtDesc" rows="3" cols="30"></textarea></td></tr>';
			s+='<tr><td><span id="errMsg" style="font-color:red;"></span></td><td align="right"><p class="buttons"><a href="#" class="regular" onclick="closeCreateEventForm();">Close</a><a href="#" class="regular" onclick="createNewEvent();">Submit</a></p></td></tr>';
			s+='</table>';
			$('#frmCreateEvent').html(s);
		}
	}
	function pickDate(dateId){
		//alert(eid);
		pickingDate=true;
		pickingDateId=dateId;
		$('#'+dateId).val('Pick a date from the calendar');
	}
	function closeCreateEventForm(){
		$('#frmCreateEvent').html('');
	}
	function eventFindVenue(key){
		$.post('ajax.php?act=u&f=getVenueByName',{'name':escape(key)},
			function(json){
				var result='<div class="buttons" style="line-height: 35px;">';
				if(json!=null){
					k=0;
					maxWidth=25;
					for(var i=0;i<json.curl.length;i++){
						if((k+json.curl[i].venueName.toString().length)>maxWidth){
							k=0;
							if(i>0) result+='<br/>';
						}
						k+=json.curl[i].venueName.toString().length;
						result+='<a class="regular" onclick="$(\'#txtVenue\').val(\''+json.curl[i].venueName+'\');glob_msgBox.Hide();">'+json.curl[i].venueName+'</a>';
					}
					result+='</div>';
				}
				glob_msgBox=null;
				glob_msgBox=new MsgBox('Find Venue',result,'question','message');
				glob_msgBox.Show();
			},'json'
		);
		
	}
	function createNewEvent(){
		//insertEventWS: @Path("/insert/{uId}/{title}/{startDate: long}/{endDate: long}/{venue}/{applyDate: long}/{eventContent}")
		/*
		result='';
		result+=$('#txtTitle').val()+'/'+convertDate($('#txtStartDate').val())+'/'+convertDate($('#txtEndDate').val())+'/'+$('#txtVenue').val()+
				'/'+convertDate($('#txtApplyDate').val())+'/'+encodeURIComponent($('#txtDesc').val());
		
		$('#evt_list').html(result);
		*/
		//alert(convertMSTimeToDate(convertDate($('#txtStartDate').val())));
		
		var myStartDate=new Date($('#txtStartDate').val());
		//var myEndDate=new Date($('#txtEndDate').val());
		if(isNaN(myStartDate.getTime())){
			msgBox_show('Error','Wrong date.','error','message');
			return;
		}
		/*
		if(myStartDate.getTime()>myEndDate.getTime()){
			msgBox_show('Error','Start date should before end date.','error','message');
			return;
		}*/
		if(($('#txtTitle').val()=='')||($('#txtVenue').val()=='')||($('#txtDesc').val()=='')){
			msgBox_show('Error','Please fill in all information.','error','message');
			return;
		}
		$.post('ajax.php?act=u&f=createEvent',{
			'title':encodeURIComponent($('#txtTitle').val()),
			'startDate':convertDate($('#txtStartDate').val()),
			'endDate':convertDate($('#txtStartDate').val()),
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
	function convertDate(s){
		var date=new Date(s);
		return date.getTime()/1000;
	}
	function convertMSTimeToDate(ms){
		var date=new Date(ms);
		return date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate();
	}
	function getEvent(){
		//ajax_calendar($('#hidden_intMonth').val(),$('#hidden_intYear').val());
		if(eventSkip==0)
			$('#eventBack').hide();
		else
			$('#eventBack').show();
		var info = $('[id^="evtOnCal"]').map(
			function () {
				$(this).html('');
			}
		);
		//TO-DO
		//This function limits the number of result to 10 which are the most 10 recent events.
		//need to implement a 'more' button to display the others
		var result='';
		$.post('ajax.php?act=u&f=getEvent',{},
			function(json){
				if(json!=null){
					for(var i=0;i<json.curl.length;i++){
						//check if the user recevied a request
						if((i>=eventSkip)&&(i<eventSkip+10)){
							result+='<input type="hidden" id="evtIds" value="'+json.curl[i].evtId+'"/>';
							result+='<li id="'+json.curl[i].evtId+'" class="clickable" onclick="event_showInfo(this.id);">';
							result+='<p class="title">'+json.curl[i].title+'</p>';
							result+='<p class="desc" style="float:right;">'+convertMSTimeToDate(json.curl[i].duration.start)+'</p>';
							//result+='<p class="desc" style="float:right;">'+convertMSTimeToDate(json.curl[i].duration.start)+
							//	' - '+convertMSTimeToDate(json.curl[i].duration.end)+'</p>';
							result+='<input id="evt'+json.curl[i].evtId+'_hdnInfo" type="hidden" value="'+
								json.curl[i].title+'|'+ //title
								json.curl[i].eventContent+'|'+ //description
								convertMSTimeToDate(json.curl[i].duration.start)+'|'+//startDate
								convertMSTimeToDate(json.curl[i].duration.end)+'|'+//endDate
								convertMSTimeToDate(json.curl[i].applyDate)+'" />';
							result+='<input id="evt'+json.curl[i].evtId+'_comments" type="hidden" value="..." />';
							result+='</li>';
						}
						
						
						
						var date=new Date(json.curl[i].duration.start);
						//return date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate();
						if(($('#hidden_month').val())==(date.getMonth()+1))
							$('#d'+date.getDate()).html($('#d'+date.getDate()).html()+'<div id="evtOnCal" onclick="event_showInfo('+json.curl[i].evtId+');"><a href="#evt_info">'+json.curl[i].title+'</a></div>');
					}
					if((json.curl.length-eventSkip)<10)
						$('#eventNext').hide();
					else
						$('#eventNext').show();
					$('#evt_list').html(result);
					hasEventRequest();
				}else{
					$('#eventNext').hide();
				}
			},'json'
		);
	}
	function getNextEvent(){
		eventSkip+=10;
		getEvent();
	}
	function getBackEvent(){
		eventSkip-=10;
		getEvent();
	}
	function hasEventRequest(){
		var info = $('[id^="evtIds"]').map(
			function () {
				$.post('ajax.php?act=u&f=getRequestByUserId',{'eId':$(this).val()},
					function(json){
						if(json!=null){
							for(var i=0;i<json.curl.length;i++)
								$('li[id='+json.curl[i].evtId+']').css('background-color','#EEF');
						}
					},'json'
				);
			}
		);
	}
	
	function deleteReply(replyId,pid){
		opt=confirm('Do you want to delete your reply?');
		if(opt==true){
			//alert('true');
			//alert(replyId+','+postId);
			$.post('ajax.php?act=u&f=deleteReply',{ 'replyId': replyId },
				function(data){
					if(data=='1'){
						$.post('ajax.php?act=u&f=getPostById',{ 'pid': pid},
							function(json){
								result='';
								if(json!=null){		
									for (var i = 0; i < json.curl.length; i++) { 
										for (var j = 0; j < json.curl[i].reply.length; j++) { 
											for (var k = 0; k < json.curl[i].replier.length; k++) { 
												if(json.curl[i].replier[k].userId==json.curl[i].reply[j].authorId){
													result+= '<div class="user_sImg"><img id="profilePic" src="<?php echo profilePicUrl;?>'+json.curl[i].replier[k].profilePic+'" width="50" height="50"></div>';
													result+= '<div class="msg">';
													result+= '<p class="publisher"><a href="#" onclick="browseFrd(\''+json.curl[i].reply[j].authorId+'\',\''+json.curl[i].replier[k].name+'\');">'+json.curl[i].replier[k].name+'</a></p>';
													result+= '<p class="post_content">'+json.curl[i].reply[j].authorComment+'</p>';
													result+= '<p class="post_date" id="postDate">'+json.curl[i].reply[j].replyDate.$date+'</p>';
													result+= '</div><br/>';
													break;
												}
											}
										}
										$('#replyMsg'+pid).html(result);
										$('#numComment'+pid).html(json.curl[i].numOfReply);
										$("textarea[name=reply_"+pid+"]").val('');
										convertDateTime('postDate');
									}
								}	
							},'json'
						);
					}
				},'text'
			);
		}
	}
	
	function deletePost(pid){
		opt=confirm('Do you want to delete your post?');
		if(opt==true){
			$.post('ajax.php?act=u&f=deletePost',{ 'pid': pid },
				function(data){
					//alert(data);
					refresh();
				},'text'
			);
		}
	}
	
	function getEventMsg(skip,wallId,e){
		var result='';
		$.post('ajax.php?act=u&f=getWallPostByWallId',{'wallId':wallId,'skip':skip},
			function(json){
				if(json!=null){
					var focusedTextarea;
					var inputText;
					for(var i=0;i<json.curl.length;i++){
						result+='<li class="new_msg" style="background-color:#EEF;">';
						result+='<div class="user_sImg"><img id="profilePic" src="<?php echo profilePicUrl;?>'+json.curl[i].author.profilePic+'" width="50" height="50"></div>';
						result+= '<div class="msg" onclick="showHideMsg(\'reply'+json.curl[i].postId+'\');">';
						result+='<p class="publisher"><a href="#" onclick="browseFrd(\''+json.curl[i].author.userId+'\',\''+json.curl[i].author.name+'\');">'+json.curl[i].author.name+'</a>';
						
						if(json.curl[i].author.userId=='<?php echo $t->id();?>')
							result+='<img src="images/icon_close.png" style="vertical-align:middle;float:right;" width="20" height="20" onclick="event.stopPropagation();deletePost(\''+json.curl[i].postId+'\');"></p>';
						
						result+='<p class="post_content">'+json.curl[i].authorComment+'</p>';
						result+='<p class="post_date" id="postDate">'+json.curl[i].postDate.$date+'</p>'
						result+='<p id="'+json.curl[i].postId+'"><a class="comment" onclick="showHideMsg(\'reply'+json.curl[i].postId+'\');">Comment(<span id="numComment'+json.curl[i].postId+'">'+json.curl[i].numOfReply+'</span>)</a></p>';
						result+='</div>';
						
						if((e!=null)&&(e=='refresh')){
							if($('#replyMsg'+json.curl[i].postId).is(":visible")==true){
								result+='<div id="reply'+json.curl[i].postId+'" class="reply_msg"><br/>';
							}else
								result+='<div id="reply'+json.curl[i].postId+'" style="display:none;" class="reply_msg"><br/>';
						}else{
							result+='<div id="reply'+json.curl[i].postId+'" style="display:none;" class="reply_msg"><br/>';
						}
						
						
						result+='<div class="msg" style="background-color:#eee;border-radius:3px;padding:5px; ">';
						//replies
						result+='<div id="replyMsg'+json.curl[i].postId+'">';
						if(json.curl[i].reply.length>0){
							for(var j=0;j<json.curl[i].reply.length;j++){
								for(var k=0;k<json.curl[i].replier.length;k++){
									if(json.curl[i].replier[k].userId==json.curl[i].reply[j].authorId){
										result+= '<div class="user_sImg"><img id="profilePic" src="<?php echo profilePicUrl;?>'+json.curl[i].replier[k].profilePic+'" width="50" height="50"></div>';
										result+= '<div class="msg">';
										result+= '<p class="publisher"><a href="#" onclick="browseFrd(\''+json.curl[i].reply[j].authorId+'\',\''+json.curl[i].replier[k].name+'\');">'+json.curl[i].replier[k].name+'</a>';
										if(json.curl[i].reply[j].authorId=='<?php echo $t->id();?>')
											result+='<img src="images/icon_close.png" style="vertical-align:middle;float:right;" width="20" height="20" onclick="event.stopPropagation();deleteReply(\''+json.curl[i].reply[j].replyId+'\',\''+json.curl[i].postId+'\');"></p>';
										result+= '<p class="post_content">'+json.curl[i].reply[j].authorComment+'</p>';
										result+= '<p class="post_date" id="postDate">'+json.curl[i].reply[j].replyDate.$date+'</p>';
										result+= '</div><br/>';
										break;
									}
								}
							}	
						}
						result+='</div>';
						result+='<div class="user_sImg"><img id="profilePic" src="<?php echo profilePicUrl.$t->get_profilePic();?>" width="50" height="50"></div>';
						result+='<div>';
						//TO-DO
						//dont know why the css style isnt working if we use id to refer the style in main.css
						if((e!=null)&&(e=='refresh')){
							if(($("textarea[name=reply_"+json.curl[i].postId+"]").is(":focus"))||($("textarea[name=reply_"+json.curl[i].postId+"]").val()!='')){
								focusedTextarea='reply_'+json.curl[i].postId;
								inputText=$("textarea[name=reply_"+json.curl[i].postId+"]").val();
							}
						}
						result+='<textarea id="evt_txtWriteReply" style="border-color:#C0C0C0 ;width:530px; height:32px; margin-left:10px; background-color:#eee;  border-width:1px 1px 1px 1px;" name="reply_'+json.curl[i].postId+'" class="inputArea" onkeyup="init_textarea(this.id);"></textarea>';
						result+='<div id="evt_writeReplyCTRL" style="width:588px; height:25px; margin-left:-35px; text-align:right; border-radius:3px;">';
						result+='<p class="buttons" style="line-height:20px;"><a class="regular" onclick="replyPost('+json.curl[i].postId+');">Reply</a></p>';
						result+='</div></div>';
						result+='</div></div>';
						result+='</li><br/>';
					}
				}
				if((json==null)||(json.curl.length<10))
					$('#btnMore').hide();
				else
					$('#btnMore').show();
					
				if((e!=null)&&(e=='refresh')){
					$('.skip'+skip).html('<span id="skips" name="'+skip+'" class="skip'+skip+'">'+result+'</span>');
				}else{
					$('#evt_msg').html($('#evt_msg').html()+'<span id="skips" name="'+skip+'" class="skip'+skip+'">'+result+'</span>');
				}
				//$('#evt_msg').html($('#evt_msg').html()+result);
				convertDateTime('postDate');
				$("textarea[name="+focusedTextarea+"]").focus();
				$("textarea[name="+focusedTextarea+"]").val(inputText);
			},'json'
		);
	}
	function replyPost(pid){
		var result;	
		var msg=$("textarea[name=reply_"+pid+"]").val();
		$.post('ajax.php?act=u&f=replyPost',{ 'pid': pid, 'content': encodeURIComponent(msg.replace(/\n/g, '<br />'))},
			function(data){
				result=data;
				if(result=='1'){//success
					$.post('ajax.php?act=u&f=getPostById',{ 'pid': pid},
						function(json){
							result='';
							//if(json!=null){		
								for (var i = 0; i < json.curl.length; i++) { 
									for (var j = 0; j < json.curl[i].reply.length; j++) { 
										for (var k = 0; k < json.curl[i].replier.length; k++) { 
											if(json.curl[i].replier[k].userId==json.curl[i].reply[j].authorId){
												result+= '<div class="user_sImg"><img id="profilePic" src="<?php echo profilePicUrl;?>'+json.curl[i].replier[k].profilePic+'" width="50" height="50"></div>';
												result+= '<div class="msg">';
												result+= '<p class="publisher"><a href="#" onclick="browseFrd(\''+json.curl[i].reply[j].authorId+'\',\''+json.curl[i].replier[k].name+'\');">'+json.curl[i].replier[k].name+'</a></p>';
												result+= '<p class="post_content">'+json.curl[i].reply[j].authorComment+'</p>';
												result+= '<p class="post_date" id="postDate">'+json.curl[i].reply[j].replyDate.$date+'</p>';
												result+= '</div><br/>';
												break;
											}
										}
									}
									$('#replyMsg'+pid).html(result);
									$('#numComment'+json.curl[i].postId).html(json.curl[i].numOfReply);
									$("textarea[name=reply_"+pid+"]").val('');
									convertDateTime('postDate');
								}
							//}	
						},'json'
					);
				}
			},'html'
		);		
	}
	function postOnWall(){
		//insertEvtWallWS: @Path("/insertEvtWall/{uId}/{eId}/{authorComment}"), Optional: photoPath
		//alert($('#hidden_wallId').val());
		var msg=$("#evt_txtWrite").val();
		$.post('ajax.php?act=u&f=insertEvtWall',
			{
			 'eId':$('#hidden_evtId').val(), 
			 'authorComment':encodeURIComponent(msg.replace(/\n/g, '<br />'))
			},
			function(data){
				//alert(data);
				if(data=='1'){
					//alert('success');
					//refresh msg
					$("#evt_txtWrite").val('');
					event_showInfo($('#hidden_evtId').val());
				}
			},'text'
		);
	}
	var selectedUid=new Array();
	function selectFrd(uid){
		if($('#uid'+uid).css("background-color")=='rgb(255, 255, 255)'){
			//insert
			if(selectedUid.indexOf(uid)==-1){
				selectedUid.push(uid);
			}
			$('#uid'+uid).css({'background-color':'#b0c4de'});
		}else{
			//remove
			selectedUid.splice(selectedUid.indexOf(uid), 1); 
			$('#uid'+uid).css({'background-color':'#FFFFFF'});
		}
	}
	function sendEventRequest(){
		selectedUid=new Array();
		//recevive friend list
		$.post('ajax.php?act=u&f=get_userById',{'uid':<?php echo $t->id();?>},
			function(json){
				if(json!=null){
					var count=0;
					var msg='Select your friends to join the event.<br/>';
					msg+='<table>';
					for (var i = 0; i < json.curl.length; i++) { 
						for(var j = 0; j < json.curl[i].friends.count; j++){
							if((count%2)==0)	msg+='<tr>';
							
							msg+='<td><img id="profilePic" src="<?php echo profilePicUrl;?>'+json.curl[i].friends.list[j].profilePic+'" width="25" height="25"></td>';
							msg+='<td width="300px" style="background-color:#FFFFFF;" id="uid'+json.curl[i].friends.list[j].userId+'"><a onclick="selectFrd('+json.curl[i].friends.list[j].userId+');">'+json.curl[i].friends.list[j].name+'</a></td>';
							
							if((count%2)==1)	msg+='</tr>';
							
							count++;
						}
					}
					if((count%2)!=1)
						msg+='</tr>';
					msg+='</table>';

					glob_msgBox=null;
					glob_msgBox=new MsgBox('Invite Friend',msg,'question','message');
					glob_msgBox.btnCustom=function(){
						//send invitation
						for(var i=0;i<selectedUid.length;i++){
							$.post('ajax.php?act=u&f=sendEventRequest',{'eID':$('#hidden_evtId').val(),'fID':selectedUid[i]},
								function(data){
									
								},'text'
							);
						}
						this.Hide();
						if(selectedUid.length>0)
							msgBox_show('Invitation Sent','Your request is sent.','info','message');
					};
					glob_msgBox.Show();
					
				}
			},'json'
		);
	}
	function getMoreEventPost(){
		skip+=10;
		getEventMsg(skip,$('#hidden_wallId').val());
	}
	function refresh(){
		var info = $('[id^="skips"]').map(
			function () {
				getEventMsg($(this).attr('name'),$('#hidden_wallId').val(),'refresh');
			}
		);
	}
	getEvent();
	var wallTimer=null;
	var isOnmouseover=false;
	if(wallTimer==null){
		wallTimer=setInterval(
			function(){
				//alert(isOnmouseover);
				refresh();
			},5000
		);
	}
	
	<?php
		if(isset($_GET['evtId'])){
			echo 'event_showInfo("'.$_GET['evtId'].'");';
		}
	?>
</script>
<input type="hidden" id="hidden_evtId" value=""/>
<input type="hidden" id="hidden_wallId" value=""/>
<?php
	echo '<input type="hidden" id="hidden_uid" value="'.$t->id().'"/>';
	echo '<input type="hidden" id="hidden_profilePicUrl" value="'.profilePicUrl.'"/>';
?>
<div onmouseover="setOnmouseover(true);" onmouseout="setOnmouseover(false);">
	<div id="calendar_frm" class="lhs73"><?php echo $e->drawCalendar($m,$y); ?></div>
	<div class="rhs73" >
		<p id="frmCreateEvent"></p>
		<br />
		<p id="subtitle" class="subtitle">Coming Up Events</p>
		<table align="center">
			<tr valign="middle" align="center">
				<td valign="middle">
					<div id="eventBack" class="eventList" onclick="getBackEvent();">
						Back
					</div>
				</td>
			</tr>
			<tr>
				<td width="300">
					<ul id="evt_list"></ul>
				</td>
			</tr>
			<tr valign="middle" align="center">
				<td valign="middle">
					<div id="eventNext" class="eventList" onclick="getNextEvent();">
						Next
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="evt_info" onmouseover="setOnmouseover(true);" onmouseout="setOnmouseover(false);">
	<div class="lhs73">
		<ul class="comments">
			<li class="evt_write">
				<div class="user_sImg"><img id="profilePic" src="<?php echo profilePicUrl.$t->get_profilePic();?>" width="50" height="50"></div>
				<div>
					<textarea style="width:560px;" id="evt_txtWrite" class="inputArea" placeholder="Enter a new message here." onkeyup="init_textarea(this.id);"></textarea>
					<div id="evt_writeCTRL" class="buttons" style="margin-left:-5px;">
						<a class="regular" onclick="postOnWall();">Post</a>
					</div>
				</div>
			</li>
			<br/>
			<p id="evt_msg"></p>
			<li class="new_msg" id="btnMore" onclick="getMoreEventPost();"><table align="center"><tr><td>More</td></tr></table></li>
		</ul>
	</div>
	<div class="rhs73">
		<div class="buttons">
			<span id="evtAdmin">
				<a href="#" style="line-height:30px;" class="regular" onclick="editEvent();">Edit</a>
				<a style="line-height:30px;" class="negative" onclick="deleteEvent();">Delete</a>
			</span>
		</div>
		<ul>
			<li>
				<p class="title">Title</p>
				<p id="evt_title"></p>
			</li>
			<li>
				<p class="title">Start Date</p>
				<p id="evt_start_date">
				<!--
				<table>
					<tr><td width="80px;"><p class="title">Start Date</p></td><td><p class="title">End Date</p></td></tr>
					<tr><td><p id="evt_start_date"></p></td><td><p id="evt_end_date"></p></td></tr>
				</table>
				-->
			</li>
			<li>
				<p class="title">Venue</p>
				<p id="evt_venue"></p>
			</li>
			<!--Reserve
			<li>	
				<p class="title">Apply Date</p>
				<p id="evt_apply_date"></p>
			</li>
			-->
			<li>
				<p class="title">Description</p>
				<p id="evt_desc"></p>
			</li>
			<!--Reserve
			<li>	
				<p class="title">Status</p>
				<p id="evt_status">Eat much as you can.</p>
			</li>
			-->
			<li>
				<p class="title" id="title">Organizer</p>
				<p id="creatorUser"></p>
			</li>
			<li>
				<p class="title" id="titleInvited">Invited</p>
				<p id="invitedUser"></p>
			</li>
			<li>
				<p class="title" id="titleJoined">Joined</p>
				<p id="joinedUser"></p>
			</li>
		</ul>
		<div class="buttons">
			<a id="inviteBtn" style="line-height:30px;" class="regular" onclick="sendEventRequest();">Invite</a>
			<span id="evtDecision">
				<input type="hidden" id="hidden_reqId" value=""/>
				<a style="line-height:30px;" class="positive" onclick="replyEventRequest('accept');">Accept</a>
				<a style="line-height:30px;" class="negative" onclick="replyEventRequest('reject');">Reject</a>
			</span>
		</div>
		<div id="msgBox">
			<div id="msgBox_top">
				<div id="msgBox_title"></div>
				<div id="msgBox_btnClose" class="clickable unSel" onclick="glob_msgBox.Hide();">X</div>
			</div>
			<div id="msgBox_main">
				<div id="msgBox_icon"></div>
				<div id="msgBox_content"></div>
				<input id="msgBox_txtInput" class="inputBox" type="text" />
			</div>
			<div id="msgBox_controls">
				<div id="msgBox_btnConfirm" class="inputBtn clickable unSel" onclick="glob_msgBox.btnConfirm_click();">Confirm</div>
				<div id="msgBox_btnCancel" class="inputBtn clickable unSel" onclick="glob_msgBox.btnCancel_click();">Cancel</div>
				<div id="msgBox_btnOK" class="inputBtn clickable unSel" onclick="glob_msgBox.btnOK_click();">OK</div>
			</div>
		</div>
	</div>
	<div class="buttons">
		
	</div>
</div>