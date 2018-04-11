<?php include_once('ajax/mpages/header.php');?>
<div data-role="page" id="page1">
	<div data-theme="a" data-role="header">
		<a data-role="button" onclick="loadPage('home');" class="ui-btn-left" rel="external">Home</a>
		<a data-role="button" onclick="btnLogout();" class="ui-btn-right" rel="external">Logout</a>
		<h3>
			<?php echo $t->fullname(); ?>
		</h3>
	</div>
	<div data-role="content">
		<table width="400px" height="100%" align="center">
			<tr><td>
				<p class="subtitle">Check-In</p>
				<p class="buttons"><a href="#" class="regular" onclick="getVenue();">Find Me</a></p>
				<p id="location"></p>
				<div id="googleMap" style="width:400px;height:380px;overflow:visible;display:block;">
					
				</div><br/>
				Select your closet venue to check-in
				<form>
				<div class="buttons">
					<p><b>Closest venues</b>:</p>
					<div id="cloestVenues" style="padding-top:10px;"></div>
					<p class="buttons" style="line-height: 35px;">
						<b>Other Venues:</b> <input type="text" id="searchInfo" class="searchBox" onkeydown="searchVenues();" style="height:200%;">
						<a href="#" class="regular" onclick="searchVenues();">Search</a>
					</p>
					<div id="searchResult" class="buttons" style="line-height: 35px;"></div>
					<span id="errMsg" style="color:red;"></span>
				</div>
				<p class="subtitle">Your Last 10 Check-In History</p>
				<div id="_checkInHistory"></div>
				</form>
		</td></tr>
		</table><br/>
	</div>
<?php include_once('ajax/mpages/footer.php');?>
<script>
var x=document.getElementById("location");
var longitude;
var latitude;

function searchVenues(){
	if($('#searchInfo').val().length<2)
		return;
	$.post('ajax.php?act=u&f=getVenueByName',{'name':$('#searchInfo').val()},
		function(json){
			var result='';
			if(json!=null){
				for(var i=0;i<json.curl.length;i++){
					result+='<a class="regular" onclick="checkIn(\''+json.curl[i].venueId+'\',\''+json.curl[i].venueName+'\');">'+json.curl[i].venueName+'</a>';
				}
			}
			$('#searchResult').html(result);
		},'json'
	);
}

function getVenue(){
	$.mobile.loading( 'show', {
		text: 'Loading',
		textVisible: true,
		theme: 'a',
		html: ""
	}); 
	if (navigator.geolocation){
		navigator.geolocation.getCurrentPosition(
			function showPosition(position){
				$.mobile.loading('hide');
				
				longitude=position.coords.longitude;
				latitude=position.coords.latitude;
				//longitude=114.264327;
				//latitude=22.3376;
				$('#location').html("Longitude: " + longitude + "<br>Latitude: " + latitude);	
				var result='';		
				$.post('ajax.php?act=u&f=getVenueByLoc',{ 'longitude': longitude, 'latitude':latitude },
					function(json){
						markers='';
						if(json!=null){
							k=0;
							//result+='<table style="line-height: 35px;">';
							for (var i = 0; i < json.curl.length; i++) { 
								//if((k%8)==0) result+='<tr>';
								//result+='<td><a class="regular" onclick="checkIn(\''+json.curl[i].venueId+'\',\''+json.curl[i].venueName+'\');">'+json.curl[i].venueName+'</a></td>';	
								//if((k%8)==7) result+='</tr>';
								//k++;
								result+='<a style="line-height:35px" class="regular" onclick="checkIn(\''+json.curl[i].venueId+'\',\''+json.curl[i].venueName+'\');">('+String.fromCharCode(65+i)+') '+json.curl[i].venueName+'</a><br/>';
								if(i<=25){
									markers+='&markers=color:red%7Clabel:'+String.fromCharCode(65+i)+'%7C'+json.curl[i].location[1]+','+json.curl[i].location[0];
								}
							}
							if((k%8)!=7) result+='</tr>';
							//result+='</table>';
							$('#cloestVenues').html(result);
						}
						$('#googleMap').html('<img src="http://maps.googleapis.com/maps/api/staticmap?center='+latitude+','+longitude+'&zoom=18&size=400x400&sensor=false'+
								'&markers=color:green%7Clabel:U%7C'+latitude+','+longitude+markers+'">');
					},'json'
				);
			}
		);
	}else{
		$('#location').html("Geolocation is not supported by this browser.");
	}
}

function checkIn(vid,vname){
	$.mobile.loading( 'show', {
		text: 'Loading',
		textVisible: true,
		theme: 'a',
		html: ""
	}); 
	var result;	
	if (navigator.geolocation){
		navigator.geolocation.getCurrentPosition(
			function showPosition(position){
				$.mobile.loading('hide');
				var opt=confirm('Do you want to check-in at '+vname+'?');
				if(opt){
					$.post('ajax.php?act=u&f=checkIn',{ 
						'venueId':vid, 
						'longitude': position.coords.longitude ,
						'latitude': position.coords.latitude },
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
				
			}
		);
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
					result+='<tr><td><p id="postDate">'+json.curl[i].checkinDate.$date+'</p></td><td>@ '+json.curl[i].venueName+'</td></tr>';
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