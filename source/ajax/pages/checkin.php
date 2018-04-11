<?php
# Name:	checkin.php
# Desc:	This is the checkin page layout.

?>
<br/>
<p class="subtitle">Check-In</p>
<br/>
<p class="buttons"><a href="#" class="regular" onclick="getVenue();">Find Me</a></p><br/>
<p id="location"></p>
<table>
	<tr>
		<td width="500px">


<br/>
Select your closest venue to check-in
<br/>
<br/>
<form>
<div class="buttons">
	<p><b>Closest venues</b><?php echo ' (Radius:~'.User::getRadiusInMeter().'m)';?>:</p>
	<div id="cloestVenues" style="padding-top:10px;">
	</div>

	<p class="buttons" style="line-height: 35px;">
		<b>Other Venues:</b> <input type="text" id="searchInfo" class="searchBox" onkeydown="searchVenues();">
		<a href="#" class="regular" onclick="searchVenues();">Search</a>
	</p>
	<div id="searchResult" class="buttons" style="line-height: 35px;"></div>
	<span id="errMsg" style="color:red;"></span>
</div>
<br/>
<p class="subtitle">My Last 10 Check-In History</p>
<div id="_checkInHistory"></div>
</form>
	</td>
		<td valign="top">
			<div id="googleMap" style="width:400px;height:380px;overflow:visible;display:block;"></div>
		</td>
	</tr>
</table>
<div id="message" style="display:none; position:absolute; padding:10px; background:#555; color:#fff; width:75px;">
			Test text.
</div>
<script>
var x=document.getElementById("location");
var longitude;
var latitude;
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
				maxWidth=40;
				for(var i=0;i<json.curl.length;i++){
					if((k+json.curl[i].venueName.toString().length)>maxWidth){
						k=0;
						if(i>0) result+='<br/>';
					}
					k+=json.curl[i].venueName.toString().length;
					result+='<a class="regular" onclick="checkIn(\''+json.curl[i].venueId+'\');">'+json.curl[i].venueName+'</a>';
				}
			}
			$('#searchResult').html(result);
		},'json'
	);
}
var locations = new Array();
function getVenue(){
	if (navigator.geolocation){
		navigator.geolocation.getCurrentPosition(
			function showPosition(position){
				$('#location').html("Longitude: " + position.coords.longitude + "<br>Latitude: " + position.coords.latitude);	
				longitude=position.coords.longitude;
				latitude=position.coords.latitude;
				//longitude=114.2643269135;
				//latitude=22.337682433131;
				var myCenter=new google.maps.LatLng(latitude,longitude);
				//var myCenter=new google.maps.LatLng(22.337682433131,114.2643269135);
				var mapOptions = {
					zoom: 18,
					center: myCenter,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}
				var map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
				var marker=new google.maps.Marker({
					position:myCenter,
					animation:google.maps.Animation.BOUNCE
				});
				marker.setMap(map);
				marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png')
				var result='';		
				$.post('ajax.php?act=u&f=getVenueByLoc',{ 'longitude': longitude, 'latitude':latitude },
				//$.post('ajax.php?act=u&f=getVenueByLoc',{ 'longitude': 114.2643269135, 'latitude': 22.337682433131},
					function(json){
						if(json!=null){
							k=0;
							maxWidth=40;
							//var markers=new Array();
							var infowindow=new google.maps.InfoWindow();
							
							for (var i = 0; i < json.curl.length; i++) { 
								if((k+json.curl[i].venueName.toString().length)>maxWidth){
									k=0;
									if(i>0) result+='<br/><br/>';
								}
								k+=json.curl[i].venueName.toString().length;
								result+='<a class="regular" onclick="checkIn(\''+json.curl[i].venueId+'\');">'+json.curl[i].venueName+'</a>';
								
								locations[i]=new Array();
								locations[i][0]=json.curl[i].venueName;
								locations[i][1]=json.curl[i].location[1];
								locations[i][2]=json.curl[i].location[0];
								locations[i][3]=json.curl[i].venueId;
								
								marker=new google.maps.Marker({
									position:new google.maps.LatLng(json.curl[i].location[1],json.curl[i].location[0]),map:map
								});
								//markers[i].setMap(map);	
								google.maps.event.addListener(marker, 'click', (function(marker, i) {
									return function() {
									  infowindow.setContent('<a href="#" onclick="$(\'#hmMain\').load(\'ajax.php?act=p&f=venue&vid='+locations[i][3]+'\');">'+locations[i][0]+'</a>');
									  infowindow.open(map, marker);
									}
								  })(marker, i));
							}
							
							
							result+='<br/><br/>';
							$('#cloestVenues').html(result);
						}
					},'json'
				);
			}
		);
	}else{
		$('#location').html("Geolocation is not supported by this browser.");
	}
}

function checkIn(vid){
	var result;	
	if (navigator.geolocation){
		navigator.geolocation.getCurrentPosition(
			function showPosition(position){
				$.post('ajax.php?act=u&f=checkIn',{ 'venueId':vid, 'longitude': position.coords.longitude ,'latitude': position.coords.latitude },
					function(data){
						result=data;
						if((result=='1')){
							$("#errMsg").html('*'+'Check in successful');
							getCheckInHistory();
						}else
							$("#errMsg").html('*'+'Check in failed');
					},'html'
				);
			}
		,function showError(error) { 
			 $.post('ajax.php?act=u&f=checkInByVid',{ 'venueId':vid },
					function(data){
						result=data;
						if((result=='1')){
							$("#errMsg").html('*'+'Check in successful');
							getCheckInHistory();
						}else
							$("#errMsg").html('*'+'Check in failed');
					},'html'
			);
		});
	}else{
		alert('Your browser does not support this function.');
	}
}

function getCheckInHistory(){
	var result='';
	$.post('ajax.php?act=u&f=checkInHistory',{},
		function(json){
			if(json!=null){
				//alert(json.curl.length);
				result+='<table>';
				for(var i=0;i<json.curl.length;i++){
					result+='<tr><td width="180"><p id="postDate">'+json.curl[i].checkinDate.$date+'</p></td><td>@ ';
					result+='<a href="#" onclick="$(\'#hmMain\').load(\'ajax.php?act=p&f=venue&vid='+json.curl[i].venueId+'\');">'+json.curl[i].venueName+'</a></td></tr>';
				}
				result+='</table>';
				$('#_checkInHistory').html(result);
				convertDateTime('postDate');
			}
		},'json'
	);
}
getCheckInHistory();
</script>
