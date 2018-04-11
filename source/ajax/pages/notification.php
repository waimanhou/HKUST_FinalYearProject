<?php
# Name:	notification.php
# Desc:	This is the notification page layout.

function checkType($type){
	if($type=='reply')
		return 'replied';
	else
		return 'wrote a post on';
}

$t=User::curr();
$result=$t->getNotification();

echo '<br/>';
echo '<p class="subtitle">Notifications</p>';

//echo $result;

?>
<div id="wall_msg" style="display:block;">
	<div class="lhs73">
		<ul class="comments">
			<?php
			if($result!=''){
				$obj = json_decode($result,true);
				foreach($obj['curl'] as $key=>$val){
					$replier='';
					if(($val['type']=='reply')||($val['type']=='post')){
						if(array_key_exists('evtId',$val)){
							echo '<li class="new_msg" onclick="$(\'#hmMain\').load(\'ajax.php?act=p&f=event&evtId='.$val['evtId'].'\');">';
							echo '<div class="msg">';
							echo '<span id="postDate">'.$val['dateAdded']['$date'].'</span> - <a href="#" onclick="event.stopPropagation();browseFrd(\''.$val['friend']['userId'].'\',\''.$val['friend']['name'].'\');">';
							echo '<img id="profilePic" style="vertical-align:middle;" src="'.profilePicUrl.$val['friend']['profilePic'].'" width="25" height="25">';
							echo $val['friend']['name'].'</a> '.'posted on the event\'s wall.';
							echo '</div></li><br/>';
						}else{
							echo '<li class="new_msg" onclick="ajax_post('.$val['postId'].');">';
							echo '<div class="msg">';
							foreach($val['userIds'] as $key2=>$val2){
								if($val2==$t->id())
									$replier='your';
								else
									$replier=User::get_fullNameById($val2);
							}
							//event.stopPropagation(); prevent the overlaped function calls on overlapped elements
							echo '<span id="postDate">'.$val['dateAdded']['$date'].'</span> - <a href="#" onclick="event.stopPropagation();browseFrd(\''.$val['friend']['userId'].'\',\''.$val['friend']['name'].'\');">';
							echo '<img id="profilePic" style="vertical-align:middle;" src="'.profilePicUrl.$val['friend']['profilePic'].'" width="25" height="25">';
							echo $val['friend']['name'].'</a> '.checkType($val['type']).' '.$replier.' '.(($val['type']=='reply')?'post':'wall').'.';
							echo '</div></li><br/>';
						}
						
					}else if($val['type']=='event'){
						echo '<li class="new_msg" onclick="$(\'#hmMain\').load(\'ajax.php?act=p&f=event&evtId='.$val['evtId'].'\');">';
						echo '<div class="msg">';
						echo '<span id="postDate">'.$val['dateAdded']['$date'].'</span> - <a href="#" onclick="event.stopPropagation();browseFrd(\''.$val['friend']['userId'].'\',\''.$val['friend']['name'].'\');">';
						echo '<img id="profilePic" style="vertical-align:middle;" src="'.profilePicUrl.$val['friend']['profilePic'].'" width="25" height="25">';
						echo $val['friend']['name'].'</a> '.'joined the event';
						echo '</div></li><br/>';
					}else if($val['type']=='friend'){
						echo '<li class="new_msg" onclick="event.stopPropagation();browseFrd(\''.$val['friend']['userId'].'\',\''.$val['friend']['name'].'\');">';
						echo '<div class="msg">';
						echo '<span id="postDate">'.$val['dateAdded']['$date'].'</span> - <a href="#" onclick="event.stopPropagation();browseFrd(\''.$val['friend']['userId'].'\',\''.$val['friend']['name'].'\');">';
						echo '<img id="profilePic" style="vertical-align:middle;" src="'.profilePicUrl.$val['friend']['profilePic'].'" width="25" height="25">';
						echo $val['friend']['name'].'</a> '.'becomes your friend.';
						echo '</div></li><br/>';
					}
				}
			}
			?>
		</ul>
	</div>
</div>
<br/>

<script type="text/javascript">
	
	convertDateTime('postDate');
	resetNotification();
</script>