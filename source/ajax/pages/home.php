<?php
# Name: home.php
# Desc: This php is used to organize the user home structure

if(isset($_GET['t'])) $t=$_GET['t']; else $t='';


if(!CoreFunc::valuable($t)&&CoreFunc::loggedIn()){ //ref to current logged in user home
	$t=User::curr();
}else if(User::exists($t)){
	$t=new User($t);
}else{
	//echo genErrorMsg('Error','User does not exists.','xxx.xxx');	
	exit();
}

echo '<input type="hidden" id="loginUid" value="'.$t->id().'">';
echo '<input type="hidden" id="loginFullName" value="'.$t->fullname().'">';

if(((isset($_GET['a']))&&($_GET['a']!='callback'))||(!isset($_GET['a'])))
	echo "<script>ajax_news();</script>";

$yourName=$t->fname().' '.$t->lname();
?>
<script type="text/javascript">

	function changeHeader(id,name){//called when the user browse a friend, id=frd's id, name=frd's name
		right='';
		right+='<ul class="isMenu">';
		right+='<li id="hName" style="font-size:15px;" onclick="stopTimer();recoverHeader();"><span id="fullnameR"><?php echo $yourName;?></span></li>';
		right+='<li style="font-size:15px;" onclick="stopTimer();ajax_notification();">Notifications<span id="numNotifications"></span></li>';
		right+='<li id="btnLogout" class="vline" onclick="stopTimer();btnLogout_click();" style="font-size:15px;">Logout</li>';
		right+='</ul>';
		$('#hR').html(right);
		
		left='';
		left+='<ul class="isMenu">';
		left+='<li id="hName" style="font-size:15px;" onclick="stopTimer();ajax_profile(\''+id+'\');"><span id="fullnameL">'+name+'</span></li>';
		left+='<li style="font-size:15px;" onclick="stopTimer();ajax_wall('+id+');">Wall</li>';
		left+='<li style="font-size:15px;" onclick="stopTimer();ajax_friend('+id+');">Friends</li>';
		left+='<li><input type="text" id="headerSearch" class="searchBox" style="height:18px;" onkeydown="if(event.keyCode==13){stopTimer();search();return false;}"><img src="images/icon_search.png" style="vertical-align:middle;" width="18px" height="18px" onclick="search();"></li>';
		left+='</ul>';
		$('#hL').html(left);
		getNumOfNotification();
	}
	
	function recoverHeader(){//called when the user exit the browse and return to the user's wall
		left='';
		left+='<ul class="isMenu">';
		left+='<li id="hName" style="font-size:15px;" onclick="stopTimer();ajax_profile(\''+$('#loginUid').val()+'\');"><span id="fullnameL"><?php echo $yourName;?></span></li>'
		left+='<li style="font-size:15px;" onclick="stopTimer();ajax_news();">News</li>';
		left+='<li style="font-size:15px;" onclick="stopTimer();ajax_wall('+$('#loginUid').val()+');">Wall</li>';
		left+='<li style="font-size:15px;" onclick="stopTimer();ajax_friend('+$('#loginUid').val()+');">Friends</li>';
		left+='<li style="font-size:15px;" onclick="stopTimer();ajax_notification();">Notifications<span id="numNotifications"></span></li>';
		left+='<li style="font-size:15px;" onclick="stopTimer();ajax_event();">Events<span id="numEventRequest"></span></li>';
		left+='<li style="font-size:15px;" onclick="stopTimer();ajax_checkIn();">Check-In</li>';
		left+='<li style="font-size:15px;" onclick="stopTimer();ajax_venue();">Venues</li>';
		left+='<li><input type="text" id="headerSearch" class="searchBox" style="height:18px;" onkeydown="if(event.keyCode==13){stopTimer();search();return false;}"><img src="images/icon_search.png" style="vertical-align:middle;" width="18px" height="18px" onclick="search();"></li>';
		left+='</ul>';
		$('#hL').html(left);
		
		right='';
		right+='<ul class="isMenu">';
		right+='<li style="font-size:15px;" onclick="stopTimer();ajax_account();">My Account</li>';
		right+='<li id="btnLogout" class="vline" onclick="stopTimer();btnLogout_click();" style="font-size:15px;">Logout</li>';
		right+='</ul>';
		$('#hR').html(right);
		
		$('#uid').val($('#loginUid').val());
		//ajax_wall($('#loginUid').val());
		ajax_news();
	}
	
	function browseFrd(fid,frdname){
		if((fid==$('#loginUid').val())&&(fid!=$('#uid').val())){
			recoverHeader();
			$('#uid').val($('#loginUid').val());
		}else{
			if(fid!=$('#uid').val()){
				changeHeader(fid,frdname);
			}
			$('#uid').val(fid);
			ajax_wall(fid);
		}
	}
	
	getNumOfNotification();
	var timer;
	if(timer==null){
		timer=setInterval(
			function(){
				//alert(isOnmouseover);
				if(isOnmouseover==true)
					getNumOfNotification();
			},1000
		);
	}
	
</script>
<div id="header">
<header>
	<input type="hidden" id="uid" value="<?php echo $t->id();?>">
	<div id="hL">
		<ul class="isMenu" style="margin-top:2px;">
			<li id="hName" style="font-size:15px;" onclick="stopTimer();ajax_profile('<?php echo $t->id();?>');"><span id="fullnameL"><?php echo $t->fname().' '.$t->lname(); ?></span></li>
			<li style="font-size:15px;" onclick="stopTimer();ajax_news();">News</li>
			<li style="font-size:15px;" onclick="stopTimer();ajax_wall(<?php echo $t->id();?>);">Wall</li>
			<li style="font-size:15px;" onclick="stopTimer();ajax_friend(<?php echo $t->id();?>);">Friends<span id="numFriendRequest"></span></li>
			<li style="font-size:15px;" onclick="stopTimer();ajax_notification();">Notifications<span id="numNotifications"></span></li>
			<li style="font-size:15px;" onclick="stopTimer();ajax_event();">Events<span id="numEventRequest"></span></li>
			<li style="font-size:15px;" onclick="stopTimer();ajax_checkIn();">Check-In</li>
			<li style="font-size:15px;" onclick="stopTimer();ajax_venue();">Venues</li>
			<li><input type="text" id="headerSearch" class="searchBox" style="height:18px;" placeholder="Search here." onkeydown="if(event.keyCode==13){stopTimer();search();return false;}"><img src="images/icon_search.png" style="vertical-align:middle;" width="18px" height="18px" onclick="search();"></li>
		</ul>
	</div>
	<div id="hR">
		<ul class="isMenu" style="margin-top:2px;">
			<li style="font-size:15px;" onclick="stopTimer();ajax_account();">My Account</li>
			<li id="btnLogout" class="vline" onclick="stopTimer();btnLogout_click();" style="font-size:15px;">Logout</li>
		</ul>
	</div>
	<div id="hmNav"></div>
	<div id="hmHR" style="margin-top:-23px; width:1000px;height:5px;"></div>
</header>
</div>

<div id="hmMain" style="padding-top:30px;" onmouseover="setOnmouseover(true);" onmouseout="setOnmouseover(false);">
	<div id="hmBanner" style="background:url('');" class="unSel">
		<div><!-- for opacity only 'NO CONTENT HERE' --></div>
		<div id="hmTNArea">
			<div id="btnTNL"><div id="btnTNL_arrow" onclick="btnTNL_click();"></div></div>
			<div>
				<div id="hmTNs">
				</div>
			</div>
			<div id="btnTNR"><div id="btnTNR_arrow" onclick="btnTNR_click();"></div></div>
		</div>
	</div>
</div>