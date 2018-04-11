<?php
# Name:	search.php
# Desc:	This is the search page layout.

$t=User::curr();
?>
<br/>
<p class="subtitle">Search for "<?php echo $_GET['searchInfo'];?>"</p><br/>


<p class="subtitle">Friends</p>
<?php
	$result=User::searchUser($_GET['searchInfo']);
	if($result!=''){
		$obj = json_decode($result,true);
		$i=0;
		//echo $result;
		echo '<table>';
		foreach($obj['curl'] as $key=>$val){
			if(($i%4)==0) echo '<tr>';
			echo '<td>';
			echo '<div class="user_sImg">';
			echo '<a href="#" onclick="browseFrd(\''.$val['userId'].'\',\''.$val['firstName']." ".$val['lastName'].'\');">';
			echo '<img id="profilePic" src="'.profilePicUrl.$val['profilePic'].'" width="50" height="50">';
			echo '</a></div>';
			echo '</td><td width="160px">';
			echo '<a href="#" onclick="browseFrd(\''.$val['userId'].'\',\''.$val['firstName']." ".$val['lastName'].'\');">'.$val['firstName']." ".$val['lastName'].'</a>';
			echo '<br/><br/><p class="buttons" id="frd'.$val['userId'].'">';
			
			
			if(($t->isFriendWith($val['userId'])=='0')&&($val['userId']!=$t->id())){
				echo '<a href="#" class="positive" style="font-size:10px;" onclick="addFriend('.$val['userId'].');">+Add Friend</a>';
			}
			
			echo '</p>';
			echo '</td>';
			
			if(($i%4)==3) echo '</tr>';
			$i++;
		}
		if(($i%4)!=3) echo '</tr>';
		echo '</table>';
	}
?>

<p class="subtitle">Events</p>
<?php
	$result=User::searchEvent($_GET['searchInfo']);
	if($result!=''){
		$obj = json_decode($result,true);
		//echo $result.'<br/>';
		echo '<table>';
		foreach($obj['curl'] as $key=>$val){
			echo '<tr id="searchEvent" onclick="$(\'#hmMain\').load(\'ajax.php?act=p&f=event&evtId='.$val['evtId'].'\');"><td width="150"><a href="#"  onclick="event.stopPropagation();browseFrd(\''.$val['creator']['userId'].'\',\''.$val['creator']['name'].'\');">'.
				'<img style="vertical-align:middle;" src="'.profilePicUrl.$val['creator']['profilePic'].'" width="25" height="25">'.
				$val['creator']['name'].'</a></td><td width="200">'.$val['title'].'</td><td><span id="eventDate">'.$val['duration']['start'].'</span></td></tr>';
		}
		echo '</table>';
		echo '<br/>';
	}
?>

<p class="subtitle">Venues</p>
<p class="buttons" style="line-height: 35px;">
<?php
	$result=User::getVenueByName($_GET['searchInfo']);
	if($result!=''){
		$obj = json_decode($result,true);
		$k=0;
		$maxWidth=85;
		//echo $result;
		foreach($obj['curl'] as $key=>$val){
				if(($k+strlen($val['venueName']))>$maxWidth){
					$k=0;
					echo '<br/>';
				}
				$k+=strlen($val['venueName']);
				echo '<a class="regular" onclick="$(\'#hmMain\').load(\'ajax.php?act=p&f=venue&vid='.$val['venueId'].'\');">'.$val['venueName'].'</a>';		
		}
		echo '<br/>';
	}
?>
</p>

<script>
	convertAllMSTimeToDate('eventDate');
</script>