<?php
# Name:	venue.php
# Desc:	This is the venue page layout.

$t=User::curr();
?>

<br/>
<?php
	$venueName='';
	$longitude;
	$latitude;
	if(isset($_GET['vid'])){
		$result=User::getVenueById($_GET['vid']);
		if($result!=''){
			$obj = json_decode($result,true);
			//echo $result;
			echo '<br/>';
			foreach($obj['curl'] as $key=>$val){
				$venueName=$val['venueName'];
				$longitude=$val['location'][0];
				$latitude=$val['location'][1];
			}
		}
		echo '<p class="subtitle">'.$venueName.'</p><br/>';
	}else{
		echo '<span class="subtitle">Venues</span><br/><span class="buttons" style="line-height: 35px;"><input type="text" id="searchInfo" placeholder="Search for venues." class="searchBox" onkeydown="if(event.keyCode==13){searchVenues();return false;}">
		<a href="#" class="regular" onclick="searchVenues();">Search</a></span><br/><div id="searchResult" class="buttons" style="line-height: 35px;"></div>';
		$longitude=114.26371898879;	
		$latitude=22.337555519292;
	}
	//echo $longitude.','.$latitude;
?>
<?php
	if(isset($_GET['vid'])){
?>
<table>
	<tr>
		<td>
			<div id="googleMap" style="width:700px;height:380px;overflow:visible;display:block;"></div>
		</td>
		<td valign="top">
			<?php
			if(isset($_GET['vid'])){
			?>
			<table>
				<tr>
					<td align="center" width="100px">
						<p class="venueSubtitle">Your<br/> Check-Ins</p>
						<span style="font-size:18px;">
							<?php
								echo $t->getUserCheckinCnt($_GET['vid']);
							?>
						</span>
					</td>
					<td align="center" width="100px">
						<p class="venueSubtitle">Total<br/> People</p>
						<span style="font-size:18px;">
							<?php
								if($result!=''){
									$obj = json_decode($result,true);
									foreach($obj['curl'] as $key=>$val){
										if(array_key_exists('stats',$val)){
											echo $val['stats']['distinctCheckin'];
										}else
											echo '0';
									}
								}
							?>
						</span>
					</td>
					<td align="center" width="100px">
						<p class="venueSubtitle">Total<br/> Check-Ins</p>
						<span style="font-size:18px;">
							<?php
								if($result!=''){
									$obj = json_decode($result,true);
									foreach($obj['curl'] as $key=>$val){
										if(array_key_exists('stats',$val)){
											echo $val['stats']['totalCheckin'];
										}else
											echo '0';
									}
								}
							?>
						</span>
					</td>
				</tr>
			</table>
			<hr width="200px"/>
			<div style="margin-left:13px;">
				<p class="subtitle" style="font-size:13px;">My Friends' Last 5 Check-In Records</p><br/>
				<?php
					$result=$t->getFrdCheckinAtVenue($_GET['vid']);
					if($result!=''){
						$obj = json_decode($result,true);
						//echo $result;
						foreach($obj['curl'] as $key=>$val){
							echo '<p id="postDate" style="text-decoration:underline;">'.$val['checkinDate']['$date'].'</p>';
							echo '<a href="#" onclick="browseFrd(\''.$val['user']['userId'].'\',\''.$val['user']['name'].'\');">';
							echo '<img id="profilePic" style="vertical-align:middle;" src="'.profilePicUrl.$val['user']['profilePic'].'" width="25" height="25">'.
									$val['user']['name'].'</a><br/><br/>';
						}
					}
				?>
			</div>
			<?php
			}
			?>
		</td>
	</tr>
</table>
<?php
	}else{
?>
	<div id="googleMap" style="width:900px;height:450px;overflow:visible;display:block;"></div>
<?php
	}
?>

<script>
function searchVenues(){
	if($('#searchInfo').val().length==0)
		$('#searchResult').html('');
	if($('#searchInfo').val().length<2)
		return;
	$.post('ajax.php?act=u&f=getVenueByName',{'name':escape($('#searchInfo').val())},
		function(json){
			var result='';
			if(json!=null){
				k=0;
				maxWidth=85;
				for(var i=0;i<json.curl.length;i++){
					if((k+json.curl[i].venueName.toString().length)>maxWidth){
						k=0;
						if(i>0) result+='<br/>';
					}
					k+=json.curl[i].venueName.toString().length;
					result+='<a class="regular" onclick="$(\'#hmMain\').load(\'ajax.php?act=p&f=venue&vid='+json.curl[i].venueId+'\');">'+json.curl[i].venueName+'</a>';
				}
			}
			$('#searchResult').html(result);
		},'json'
	);
}
	var myCenter=new google.maps.LatLng(<?php echo $latitude;?>,<?php echo $longitude;?>);
	function initialize() {
		var mapOptions = {
			zoom: 18,
			center: myCenter,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		var map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
		<?php
		if(isset($_GET['vid'])){
		?>
		var marker=new google.maps.Marker({
			position:myCenter,
		});
		marker.setMap(map);
		<?php
		}
		?>
	}
	initialize();
	convertDateTime('postDate');
</script>