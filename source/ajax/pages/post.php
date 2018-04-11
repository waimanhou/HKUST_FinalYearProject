<?php
# Name:	post.php
# Desc:	This is the post page layout.
# This page is for display a specified post which can be accessed from notification.

$t=User::curr();
echo $_GET['pid'];
$result=User::getPostById($_GET['pid']);
?>
<div id="wall_msg" style="display:block;">
	<div class="lhs73">
		<ul class="comments">
		<?php
		if($result!=''){
			$obj = json_decode($result,true);
			foreach($obj['curl'] as $key=>$val){
				echo '<li class="new_msg">';
				echo '<div class="user_sImg"><a href="#" onclick="browseFrd(\''.$val['author']['userId'].'\',\''.$val['author']['name'].'\');"><img id="profilePic" src="'.profilePicUrl.$val['author']['profilePic'].'" width="50" height="50"></a></div>';
				echo '<div class="msg">';
				echo '<p class="publisher"><a href="#" onclick="browseFrd(\''.$val['author']['userId'].'\',\''.$val['author']['name'].'\');">'.$val['author']['name'].'</a>';
				
				if(array_key_exists('wallOwner',$val)){ //receiver's name
					if(array_key_exists('evtId',$val['wallOwner'])){
						echo ' > <a href="#" onclick="browseEvt('.$val['wallOwner']['evtId'].')";>'.$val['wallOwner']['title'].'</a>';
					}else{//&&$val['wallOwner']['userId']!=$val['author']['userId']
						echo ' > <a href="#" onclick="browseFrd(\''.$val['wallOwner']['userId'].'\',\''.$val['wallOwner']['name'].'\');">'.$val['wallOwner']['name'].'</a>';
					}
				}
					
				
				
				echo '</p>';
				echo '<p class="post_content">'.$val['authorComment'].'</p>';
				echo '<p class="post_date" id="postDate">'.$val['postDate']['$date'].'</p>';
				
				echo '<p id="'.$val['postId'].'"><a class="comment" onclick="showHideMsg(\'reply'.$val['postId'].'\');">Comment('.$val['numOfReply'].')</a></p>';
				echo '</div>';
				echo '<div id="reply'.$val['postId'].'" style="display:none;" class="reply_msg"><br/>';
				echo '<div class="msg">';
				if($val['numOfReply']>0){
					echo '<div id="replyMsg'.$val['postId'].'">';
					foreach($val['reply'] as $key2=>$val2){//retrieve the comments
						foreach($val['replier'] as $key3=>$val3){//find the replier's info
							if($val3['userId']==$val2['authorId']){
								echo '<div class="user_sImg"><img id="profilePic" src="'.profilePicUrl.$val3['profilePic'].'" width="50" height="50"></div>';
								echo '<div class="msg">';
								echo '<p class="publisher"><a href="#" onclick="browseFrd(\''.$val2['authorId'].'\',\''.$val3['name'].'\');">'.$val3['name'].'</a></p>';
								echo '<p class="post_content">'.$val2['authorComment'].'</p>';
								echo '<p class="post_date" id="postDate">'.$val2['replyDate']['$date'].'</p>';
								echo '</div><br/>';
								break;
							}
						}
					}
					echo '</div>';
				}
				echo '<div class="user_sImg"><img id="profilePic" src="'.profilePicUrl.$t->get_profilePic().'" width="50" height="50"></div>';
				echo '<div>';
				echo '<textarea id="evt_txtWriteReply" name="reply_'.$val['postId'].'" class="inputArea" onkeyup="init_textarea(this.id);"></textarea>';
				echo '<div id="evt_writeReplyCTRL">';
				echo '<p class="buttons" style="line-height:20px;"><a class="regular" onclick="replyPost('.$val['postId'].');">Reply</a></p>';
				echo '</div></div>';
				echo '</div></div>';
				echo '</li><br/>';
			}
		}
		?>
		</ul>
	</div>
</div>
<script type="text/javascript">
	function convertDateTime(eid){
		var info = $('[id^="'+eid+'"]').map(
			function () {
				var myDate = new Date($(this).html());
				myDate.setTime(myDate.getTime() - myDate.getTimezoneOffset() * 60 * 1000);
				$(this).html(myDate.toUTCString());
			}
		);
	}
	convertDateTime('postDate');
</script>