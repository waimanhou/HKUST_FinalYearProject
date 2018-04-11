<?php include_once('ajax/mpages/header.php');?>
<?php
# Name:	friend.php
# Desc:	This is the friend page layout.
$t=User::curr();
echo '<INPUT TYPE="hidden" id="profilePicUrl" value="'.profilePicUrl.'">';
echo '<input type="hidden" id="currUid" value="'.$_GET['uid'].'">';
?>

<div data-role="page" id="page1">
	<div data-theme="a" data-role="header">
		<a data-role="button" onclick="loadPage('home');" class="ui-btn-left" rel="external">Home</a>
		<a data-role="button" onclick="btnLogout();" class="ui-btn-right" rel="external">Logout</a>
		<h3>
			<?php echo $t->fullname(); ?>
		</h3>
	</div>
	<div data-role="content">
		<?php
			if($_GET['uid']==$t->id()){
				echo '<p class="buttons">';
				echo '<span class="subtitle">Find Friends</span> <input type="text" id="searchInfo" class="searchBox" onkeydown="searchUser();"> ';
				echo '<a href="#" class="regular" onclick="searchUser();">Search</a>';
				echo '</p>';
				echo '</br>';
			}
		?>

		<div id="searchResult"></div>
		<br/>

		<?php
			if($_GET['uid']==$t->id()){
				echo '<p class="subtitle">Friend Requests</p>';
				$result=$t->getFriendRequest();
				if($result!=''){
					$obj = json_decode($result,true);
					$i=0;
					echo '<table>';
					foreach($obj['curl'] as $key=>$val){
						if(array_key_exists('replyDate',$val))  continue;
						if(($i%4)==0)	echo '<tr>';
						echo '<td>';
						echo '<div class="user_sImg">';
						echo '<a href="#" onclick="browseFrd(\''.$val['from']['userId'].'\',\''.$val['from']['name'].'\')">';
						echo '<img id="profilePic" src="'.profilePicUrl.$val['from']['profilePic'].'" width="50" height="50">';
						echo '</a></div>';
						echo '</td><td width="160px" height="70px">';
						echo '<a href="#" onclick="browseFrd(\''.$val['from']['userId'].'\',\''.$val['from']['name'].'\')">'.$val['from']['name'].'</a><br/>';
						echo '<p class="buttons"><a href="#" class="positive" style="font-size:10px;color:green;" onclick="frdRequest(\'accept\','.$val['frdReqId'].')">Accept</a>
								<a href="#" class="negative" style="font-size:10px;color:red;" onclick="frdRequest(\'reject\','.$val['frdReqId'].')">Reject</a></p>';
						echo '</td>';
						
						if(($i%4)==3)	echo '</tr>';
						$i++;
					}
					if(($i%4)!=3)	echo '</tr>';
					echo '</table>';
				}
				echo '<br/><br/>';
				
				#Friends @ HKUST
				echo '<span class="subtitle">';
				echo "Your Friends @ HKUST";
				echo '</span> ';
				echo '<span class="buttons">';
				echo '<a href="#" class="regular" onclick="getFriendsInRange();">Find</a>';
				echo '</span><br/>';
				echo '(Check-in within '.($t->getTimeLimit()/(1000*60*60)).'hr(s) )';
				echo '<div id="frdAtUST"></div>';
				echo '<br/><br/>';
				
				#Nearest Friends
				echo '<span class="subtitle">';
				//echo (User::get_fullNameById($_GET['uid']))." 's Nearest Friends";
				echo "Your Nearest Friends";
				echo '</span> ';
				echo '<span class="buttons">';
				echo '<a href="#" class="regular" onclick="getNearestFriends();">Find</a>';
				echo '</span><br/>';
				echo '(Check-in within '.($t->getTimeLimit()/(1000*60*60)).'hr(s) )';
				echo '<div id="nearestFrd"></div>';
				echo '<br/><br/>';
			}

			$result=User::get_userById($_GET['uid']);
			$obj=json_decode($result,true);
			if($result!=''){
				$i=0;
				foreach($obj['curl'] as $key=>$val){
					if($val['friends']['count']!='0'){
						echo '<p class="subtitle">'.(User::get_fullNameById($_GET['uid']))." 's Friends (".$val['friends']['count'].')</p>';
						echo '<table>';
						foreach($val['friends']['list'] as $key2=>$val2){
							if(($i%4)==0) echo '<tr>';
							echo '<td>';
							echo '<div class="user_sImg">';
							echo '<a href="#" onclick="browseFrd(\''.$val2['userId'].'\',\''.$val2['name'].'\');">';
							echo '<img id="profilePic" src="'.profilePicUrl.$val2['profilePic'].'" width="50" height="50">';
							echo '</a></div>';
							echo '</td><td width="160px" height="70px">';
							echo '<a href="#" onclick="browseFrd(\''.$val2['userId'].'\',\''.$val2['name'].'\');">'.$val2['name'].'</a><br/>';
							echo '<p class="buttons"><a href="#" class="negative" style="font-size:10px;color:red;" onclick="unFriend(\''.$val2['userId'].'\',\''.$val2['name'].'\');">Unfriend</a></p>';
							echo '</td>';
							if(($i%4)==3) echo '</tr>';
							$i++;
						}
						if(($i%4)!=3) echo '</tr>';
						echo '</table>';
					}else{
						echo '<p class="subtitle">'.(User::get_fullNameById($_GET['uid']))." 's Friends (0)</p>";
					}
				}
			}
		?>
	</div>
<?php include_once('ajax/mpages/footer.php');?>
<script type="text/javascript">
	var userJson;
	function getUserJson(){
		$.post('ajax.php?act=u&f=getCurrentUserInfo',{},
			function(json){
				userJson=json;
			},'json'
		);
	}
	
	function searchUser(){
		if($('#searchInfo').val().length<2)
			return;
		var result='';	
		var profilePicUrl=$('#profilePicUrl').val();
		$.post('ajax.php?act=u&f=searchUser',{ 'info':escape($('#searchInfo').val()) },
			function(json){
				if(json==null){
					result='';
					$('#searchResult').html(result);
				}else{
					var x=0;
					result+='<table>';
					for (var i = 0; i < json.curl.length; i++) { 
						if((x%4)==0) result+='<tr>';
						result+='<td>';
						result+='<div class="user_sImg">';
						result+='<a href="#" onclick="browseFrd(\''+json.curl[i].userId+'\',\''+json.curl[i].firstName+" "+json.curl[i].lastName+'\');">';
						result+='<img id="profilePic" src="'+profilePicUrl+json.curl[i].profilePic+'" width="50" height="50">';
						result+='</a></div>';
						result+='</td><td width="160px">';
						result+='<a href="#" onclick="browseFrd(\''+json.curl[i].userId+'\',\''+json.curl[i].firstName+" "+json.curl[i].lastName+'\');">'+json.curl[i].firstName+" "+json.curl[i].lastName+'</a>';
						result+='<br/><p class="buttons" id="frd'+json.curl[i].userId+'">';
						
						var isFriend=false;
						for(var j=0;j<userJson.curl.length;j++){
							if(json.curl[i].userId!='<?php echo $t->id();?>'){
								if(userJson.curl[j].friends.count>0){
									for(var k=0;k<userJson.curl[j].friends.count;k++){
										if(userJson.curl[j].friends.list[k].userId==json.curl[i].userId){
											isFriend=true;
											break;
										}
									}
								}
							}
						}
						if((!isFriend)&&(json.curl[i].userId!='<?php echo $t->id();?>'))
							result+='<a href="#" class="positive" style="font-size:10px;" onclick="addFriend('+json.curl[i].userId+');">+Add Friend</a>';
						
						result+='</p>';
						
						result+='</td>';
						if((x%4)==3) result+='</tr>';
						x++;
					}
					if((x%4)!=3) result+='</tr>';
					result+='</table><br/>';
				}
				$('#searchResult').html(result);
			},'json'
		);
	}
	
	function isFriendWith(uid){
		$.post('ajax.php?act=u&f=isFriendWith',{ 'uid':uid },
			function(text){
				return text;
			},'text'
		);
	}
	
	function addFriend(fid){//fid=friend's userId
		var result='';	
		$.post('ajax.php?act=u&f=sendFriendRequest',{ 'fid':fid },
			function(data){
				//alert(data);
				if(data=='1'){//sent request successful
					$('#frd'+fid).html('Sent Request');
				}
			},'html'
		);
	}
	
	function frdRequest(decision,frdReqId){
		//alert(decision+','+frdReqId);
		$.post('ajax.php?act=u&f=frdRequest',{'decision':decision,'frdReqId':frdReqId},
			function(data){
				loadPage('friend','uid='+$('#loginUid').val());
			},'html'
		)
	}
	
	function getNearestFriends(){
		if (navigator.geolocation){
			navigator.geolocation.getCurrentPosition(
				function showPosition(position){
					$.post('ajax.php?act=u&f=getNearestFriends',{ 'longitude': position.coords.longitude ,'latitude': position.coords.latitude },
						function(json){
							var result='';	
							if(json!=null){
								for(var i=0;i<json.curl.length;i++){
									result+= '<table>';
									result+= '<tr>';
									result+= '<td>';
									result+= '<div class="user_sImg"><img id="profilePic" style="vertical-align:middle;" src="<?php echo profilePicUrl;?>'+json.curl[i].profilePic+'" width="50" height="50"></div>';
									result+= '</td><td width="200px">';
									result+= '<a href="#" onclick="browseFrd(\''+json.curl[i].user.userId+'\',\''+json.curl[i].user.name+'\')">'+json.curl[i].user.name+'</a>';
									result+= '<br/><p class="buttons" id="">@'+json.curl[i].venueName+'</p>';
									result+= '<p id="chkInDate">'+json.curl[i].checkinDate.$date+'</p>';
									result+= '</td>';
									result+= '</tr>';
									result+= '</table>';
								}
								$('#nearestFrd').html(result);
								convertDateTime('chkInDate');
							}else{
								$('#nearestFrd').html('Not found.');
							}
						},'json'
					);
				}
			);
		}
	}
	
	function getFriendsInRange(){
		if (navigator.geolocation){
			navigator.geolocation.getCurrentPosition(
				function showPosition(position){
					$.post('ajax.php?act=u&f=getFriendsInRange',{ 'longitude': position.coords.longitude ,'latitude': position.coords.latitude },
						function(json){
							var result='';	
							if(json!=null){
								result+= '<table>';
								for(var i=0;i<json.curl.length;i++){
									if((i%4)==0)
										result+= '<tr>';
									result+= '<td>';
									result+= '<div class="user_sImg"><img id="profilePic" style="vertical-align:middle;" src="<?php echo profilePicUrl;?>'+json.curl[i].profilePic+'" width="50" height="50"></div>';
									result+= '</td><td width="200px">';
									result+= '<a href="#" onclick="browseFrd(\''+json.curl[i].user.userId+'\',\''+json.curl[i].user.name+'\')">'+json.curl[i].user.name+'</a>';
									result+= '<br/><p class="buttons" id="">@'+json.curl[i].venueName+'</p>';
									result+= '<p id="chkInDate">'+json.curl[i].checkinDate.$date+'</p>';
									result+= '</td>';
									if((i%4)==3)
										result+= '</tr>';
								}
								if((i%4)!=3)
									result+= '</tr>';
								result+= '</table>';
								$('#frdAtUST').html(result);
								convertDateTime('chkInDate');
							}else{
								$('#frdAtUST').html('Not found.');
							}
						},'json'
					);
				}
			);
		}
	}
	

	
	getUserJson();
</script>