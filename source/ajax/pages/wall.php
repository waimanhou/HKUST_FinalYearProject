<?php
# Name:	wall.php
# Desc:	This is the wall page layout.

$t=User::curr();
$fullname='';
$result;

$uid='';
$funcUrl='';
if((isset($_GET['t']))&&($_GET['t']=='news')){
	$fullname=$t->fname().' '.$t->lname();
	$uid=$t->id();
	//$result=$t->getNewsFeedPost($uid);
	$funcUrl='getNewsFeedPost';
	echo '<br/><p id="subtitle" class="subtitle" style="position:absolute;">News Feed</p>';
}else if(isset($_GET['uid'])){
	$fullname=User::get_fullNameById($_GET['uid']);
	$uid=$_GET['uid'];
	//$result=$t->getWallPost($uid);
	$funcUrl='getWallPost';
	echo '<br/><p id="subtitle" class="subtitle" style="position:absolute;">'.$fullname.' \'s Wall</p>';
}else if(isset($_GET['pid'])){//display a specified post
	//$result=User::getPostById($_GET['pid']);
	$funcUrl='getPostById';
}


?>
<script type="text/javascript">
	var skip=0;
	var isOnmouseover=false;
	var frdChkSkip=0;
	
	function setOnmouseover(state){
		isOnmouseover=state;
	}

	function showHideMsg(id) {
		$('#'+id).toggle();
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
			},'html'
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
	
	function postOnWall(){
		//alert(encodeURIComponent($("#evt_txtWrite").val()));
		<?php
			if(!isset($_GET['pid'])){
				echo 'var result;var msg=$("#evt_txtWrite").val();';
				if($uid==$t->id())
					echo '$.post("ajax.php?act=u&f=insertOwnWall",{ "content": encodeURIComponent(msg.replace(/\n/g, \'<br />\'))},';
				else
					echo '$.post("ajax.php?act=u&f=insertFrdWall",{ "content": encodeURIComponent(msg.replace(/\n/g, \'<br />\')), "fId": '.$uid.' },';
				
					echo 'function(data){';
						echo 'result=data;';
						echo 'if(result=="1")';
							if((isset($_GET['t']))&&($_GET['t']=='news'))
								echo '$("#hmMain").load("ajax.php?act=p&f=wall&t=news");';
							else
								echo '$("#hmMain").load("ajax.php?act=p&f=wall&uid='.$uid.'");';
					echo '},"html"';
				echo ');';
			}
		?>
	}
	
	function browseEvt(evtId){
		$("#hmMain").load("ajax.php?act=p&f=event&evtId="+evtId);
	}
	
	function getPost(skip,e){
		//$('#btnMore').hide();
		var result='';
		<?php
			if($funcUrl=='getNewsFeedPost')
				echo '$.post("ajax.php?act=u&f=getNewsFeedPost",{\'skip\':skip},';
			else if($funcUrl=='getWallPost')
				echo '$.post("ajax.php?act=u&f=getWallPost",{\'uid\':'.$uid.',\'skip\':skip},';
			else
				echo '$.post("ajax.php?act=u&f=getPostById",{\'pid\':'.$_GET['pid'].'},';
		?>
			function(json){
				if(json!=null){
					var focusedTextarea;
					var inputText;
					for(var i=0;i<json.curl.length;i++){
						result+='<li class="new_msg" style="background-color:#EEF;">';
						result+='<div class="user_sImg"><img id="profilePic" src="<?php echo profilePicUrl;?>'+json.curl[i].author.profilePic+'" width="50" height="50"></div>';
						result+= '<div class="msg" onclick="showHideMsg(\'reply'+json.curl[i].postId+'\');">';
						result+='<p class="publisher"><a href="#" onclick="browseFrd(\''+json.curl[i].author.userId+'\',\''+json.curl[i].author.name+'\');">'+json.curl[i].author.name+'</a>';
						if(json.curl[i].wallOwner!=null){
							if(json.curl[i].wallOwner.evtId!=null)
								result+=' > <a href="#" onclick="browseEvt('+json.curl[i].wallOwner.evtId+')";>'+json.curl[i].wallOwner.title+'</a>';
							else
								result+=' > <a href="#" onclick="browseFrd(\''+json.curl[i].wallOwner.userId+'\',\''+json.curl[i].wallOwner.name+'\');">'+json.curl[i].wallOwner.name+'</a>';
						}
						if(json.curl[i].author.userId=='<?php echo $t->id();?>')
							result+='<img src="images/icon_close.png" style="vertical-align:middle;float:right;" width="20" height="20" onclick="event.stopPropagation();deletePost(\''+json.curl[i].postId+'\');"></p>';
						result+='<p class="post_content">'+json.curl[i].authorComment+'</p>';
						result+='<p class="post_date" id="postDate">'+json.curl[i].postDate.$date+'</p>'
						result+='<p id="'+json.curl[i].postId+'"><a class="comment" style="cursor: pointer;" onclick="event.stopPropagation();showHideMsg(\'reply'+json.curl[i].postId+'\');">Comment(<span id="numComment'+json.curl[i].postId+'">'+json.curl[i].numOfReply+'</span>)</a></p>';
						result+='</div>';
						if((e!=null)&&(e=='refresh')){
							if($('#replyMsg'+json.curl[i].postId).is(":visible")==true){
								result+='<div id="reply'+json.curl[i].postId+'" class="reply_msg"><br/>';
							}else
								result+='<div id="reply'+json.curl[i].postId+'" style="display:none;" class="reply_msg"><br/>';
						}else{
							result+='<div id="reply'+json.curl[i].postId+'" <?php echo (!isset($_GET['pid']))?'style="display:none;"':''; ?> class="reply_msg"><br/>';
						}
						
						result+='<div class="msg">';
						//replies
						result+='<div id="replyMsg'+json.curl[i].postId+'">';
						if(json.curl[i].reply.length>0){
							for(var j=0;j<json.curl[i].reply.length;j++){
								for(var k=0;k<json.curl[i].replier.length;k++){
									if(json.curl[i].replier[k].userId==json.curl[i].reply[j].authorId){
										result+= '<div class="user_sImg"><img id="profilePic" src="<?php echo profilePicUrl;?>'+json.curl[i].replier[k].profilePic+'" width="50" height="50"></div>';
										result+= '<div class="msg">';
										result+= '<p class="publisher">';
										result+= '<a href="#" onclick="browseFrd(\''+json.curl[i].reply[j].authorId+'\',\''+json.curl[i].replier[k].name+'\');">'+json.curl[i].replier[k].name+'</a>';
										if(json.curl[i].reply[j].authorId=='<?php echo $t->id();?>')
											result+='<img src="images/icon_close.png" style="vertical-align:middle;float:right;" width="20" height="20" onclick="event.stopPropagation();deleteReply(\''+json.curl[i].reply[j].replyId+'\',\''+json.curl[i].postId+'\');"></p>';
										result+= '</p>';
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
	
						result+='<textarea placeholder="Give a comment here." id="evt_txtWriteReply" style="border-color:#C0C0C0 ;width:530px; height:32px; margin-left:10px; border-width:1px 1px 1px 1px;" name="reply_'+json.curl[i].postId+'" class="inputArea" onkeyup="init_textarea(this.id);"></textarea>';
						result+='<div id="evt_writeReplyCTRL" style="width:588px; height:25px; margin-left:-35px; text-align:right; border-radius:3px;">';
						result+='<p class="buttons" style="line-height:20px;"><a class="regular" onclick="replyPost('+json.curl[i].postId+');">Reply</a></p>';
						result+='</div></div>';
						result+='</div></div>';
						result+='</li><br/>';
					}
					
					if(json.curl.length<10)
						$('#btnMore').hide();
					else
						$('#btnMore').show();
				}else{
					$('#btnMore').hide();
					<?php
						if(($funcUrl!='getNewsFeedPost')&&($funcUrl!='getWallPost')){
					?>
					result="Access Error. The post may be removed.";
					$('#evt_msg').html('<span id="skips" val="'+skip+'" class="skip'+skip+'">'+result+'</span>');
					<?php
						}
					?>
				}
				
				if((e!=null)&&(e=='refresh')){
					//alert(skip);
					$('.skip'+skip).html('<span id="skips" val="'+skip+'" class="skip'+skip+'">'+result+'</span>');
				}else
					$('#evt_msg').html($('#evt_msg').html()+'<span id="skips" val="'+skip+'" class="skip'+skip+'">'+result+'</span>');
				convertDateTime('postDate');
				$("textarea[name="+focusedTextarea+"]").focus();
				$("textarea[name="+focusedTextarea+"]").val(inputText);
			},'json'
		);
	}
	function getMorePost(){
		skip+=10;
		getPost(skip);
	}
	function getFriendsCheckin(){
		$.post("ajax.php?act=u&f=getFriendsCheckin",{'skip':frdChkSkip},
			function(json){
				var result='';
				if(json!=null){
					for(var i=0;i<json.curl.length;i++){
						result+='<p id="postDate" style="text-decoration:underline;">'+json.curl[i].checkinDate.$date+'</p>'+
							'<a href="#" onclick="browseFrd(\''+json.curl[i].user.userId+'\',\''+json.curl[i].user.name+'\');">'+
							'<img id="profilePic" style="vertical-align:middle;" src="<?php echo profilePicUrl;?>'+json.curl[i].user.profilePic+'" width="25" height="25">'+
							json.curl[i].user.name+'</a> @ <a href="#" onclick="$(\'#hmMain\').load(\'ajax.php?act=p&f=venue&vid='+json.curl[i].venueId+'\');">'+json.curl[i].venueName+'</a><br/><br/>';
					}
					if(frdChkSkip==0)
						$('#frdChkBack').hide();
					else{
						$('#frdChkBack').show();
						$('#frdChkNext').show();
					}
					//alert(frdChkSkip+','+json.curl.length);
					if(json.curl.length<5)
						$('#frdChkNext').hide();
					else
						$('#frdChkNext').show();
					$('#checkInHistory').html(result);
					convertDateTime('postDate');
				}else{
					$('#frdChkNext').hide();
					if(frdChkSkip==0)
						$('#frdChkBack').hide();
				}
			},'json'
		);
	}
	function getBackFriendsCheckin(){
		frdChkSkip-=5
		getFriendsCheckin();
	}
	function getNextFriendsCheckin(){
		frdChkSkip+=5;
		getFriendsCheckin();
	}
	function refresh(){
		<?php
			if($funcUrl=='getNewsFeedPost'){
				//refresh recent check-in history
				echo 'getFriendsCheckin();';
			}
		?>
		
		//refresh post
		var info = $('[id^="skips"]').map(
			function () {
				getPost($(this).attr('val'),'refresh');
			}
		);
	}
	getPost(skip);
	getFriendsCheckin();
	var wallTimer=null;
	if(wallTimer==null){
		wallTimer=setInterval(
			function(){
				//alert(isOnmouseover);
				if(isOnmouseover)
					refresh();
			},2000
		);
	}
	
	
	<!--http://jqueryfordesigners.com/fixed-floating-elements/ -->
	$(function () {
	  var msie6 = $.browser == 'msie' && $.browser.version < 7;
	  
	  if (!msie6) {
		var top = $('#chkInHistDiv').offset().top - parseFloat($('#chkInHistDiv').css('margin-top').replace(/auto/, 0));
		$(window).scroll(function (event) {
		  // what the y position of the scroll is
		  var y = $(this).scrollTop();
		  
		  // whether that's below the form
		  if (y >= top) {
			// if so, ad the fixed class
			$('#chkInHistDiv').addClass('fixed');
		  } else {
			// otherwise remove it
			$('#chkInHistDiv').removeClass('fixed');
		  }
		});
	  }  
	});
	

</script>
<?php
	if(!isset($_GET['pid'])){
		echo '<br/>';
	}
?>
<table WIDTH="112%" onmouseover="setOnmouseover(true);" onmouseout="setOnmouseover(false);">
	<tr>
		<td>
			<div id="wall_msg" style="display:block;">
				<div class="lhs73">
					<ul class="comments">
						<?php
							if(!isset($_GET['pid'])){
						?>
						<li class="evt_write">
							<div class="user_sImg"><img id="profilePic" src="<?php echo profilePicUrl.$t->get_profilePic();?>" width="50" height="50"></div>
							<div>
								<textarea id="evt_txtWrite" class="inputArea" onkeyup="init_textarea(this.id);" placeholder="Enter a new message here."></textarea>
								<div id="evt_writeCTRL" class="buttons">
									<p style="line-height:20px;"><a class="regular" onclick="postOnWall();">Post</a></p>
								</div>
							</div>
						</li>
						<br/>
						<?php
							}
						?>
						<p id="evt_msg"></p>
						<?php
							if(!isset($_GET['pid']))
								echo '<li class="new_msg" id="btnMore" onclick="getMorePost();"><table align="center"><tr><td>More</td></tr></table></li>';
						?>
					</ul>	
				</div>
			</div>	
		</td>
		<td valign="top" align="left" width="100">
			<?php
				if($funcUrl=='getNewsFeedPost'){
			?>
				<div style="margin-left:-280px;margin-top:0px;">
					<div id="chkInHistDiv">
						<p id="subtitle" class="subtitle">Recent Check-In</p><br/>
						<table align="center" width="250px">
							<tr valign="middle" align="center">
								<td>
									<div id="frdChkBack" class="frdChkHist" onclick="getBackFriendsCheckin();">
										Back
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<p id="checkInHistory"></p>
								</td>
							</tr>
							<tr valign="middle" align="center">
								<td valign="middle">
									<div id="frdChkNext" class="frdChkHist" onclick="getNextFriendsCheckin();">
										Next
									</div>
								</td>
							</tr>
						</table>
						
						
					</div>
				</div>
			<?php
				}
			?>
		</td>
	</tr>
</table>


<script type="text/javascript">
	convertDateTime('postDate');
</script>