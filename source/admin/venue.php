<?php
require_once('adminFunc.php');
?>
<html>
<head>
<script type="text/javascript" src="../ext/js/plugin/jQuery.js"></script>
<script
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false">
</script>
<script>
function getLocation(){
  if (navigator.geolocation){
    navigator.geolocation.getCurrentPosition(
		function showPosition(position){
			document.getElementById("longitude").value=position.coords.longitude;
			document.getElementById("latitude").value=position.coords.latitude;
		  
			longitude=position.coords.longitude;
			latitude=position.coords.latitude;

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
		}
	);
  }else{
    x.innerHTML="Geolocation is not supported by this browser.";
  }
}
  
function getVenue(){
	getLocation();
}

function deleteVenue(vid){
	if(confirm('Are you sure to delete the venue (venueId='+vid+',venueName='+$('#vname'+vid).html()+')?'))
		window.location = 'admin.php?f=deleteVenue&venueId='+vid;
}

function editVenue(vid){
	if($('#editbtn').val()=='Edit'){
		$('#vname'+vid).html('<input id="txtVname'+vid+'" type="text" value="'+$('#vname'+vid).html()+'">');
		$('#vlong'+vid).html('<input id="txtVlong'+vid+'" type="text" value="'+$('#vlong'+vid).html()+'">');
		$('#vlat'+vid).html('<input id="txtVlat'+vid+'" type="text" value="'+$('#vlat'+vid).html()+'">');
		$('#editbtn').val('Save');
	}else{
		$('#vname'+vid).html($('#txtVname'+vid).val());
		$('#vlong'+vid).html($('#txtVlong'+vid).val());
		$('#vlat'+vid).html($('#txtVlat'+vid).val());
		$('#editbtn').val('Edit');
		
		window.location = 'admin.php?f=editVenue&venueId='+vid+'&vname='+$('#vname'+vid).html()+'&vlong='+$('#vlong'+vid).html()+'&vlat='+$('#vlat'+vid).html();
	}
}

function insertVenue(){
	$.post('admin.php?f=insertVenue',{'venueName':escape($('#venueName').val()),'longitude':$('#longitude').val(),'latitude':$('#latitude').val()},
		function(data){
			if(data!='0'){
				window.location = 'venue.php';
			}
		},'text'
	);
}
</script>
</head>
<body>
<a href="home.php">HOME</a><br/>
<table>
	<tr>
		<td valign="top">

			<b>Insert New Venue</b><br/>
			Venue Name:<input type="text" id="venueName" name="venueName"/><br/>
			Location:<br/>
			Longitude<input type="text" id="longitude" name="longitude"/><br/>
			Latitude<input type="text" id="latitude" name="latitude"/>
			<input type="button" onclick="getVenue();" value="Get Current Position"/><br/>
			<input type="button" onclick="insertVenue();" value="Submit"/>

		</td>
	</tr>
</table>
<div id="googleMap" style="width:1000px;height:380px;overflow:visible;display:block;"></div>

<br/>
<b>Venues</b><br/>
<?php
	$curl=curlGet('/venue/getAllVenue/');
	if($curl!='0'){
		$result='{"curl":['.$curl.']}';
		//echo $result;
		$obj=json_decode($result,true);
		echo '<table text-align:left>';
		echo '<td width="80">venueId</td><td width="155">venueName</td><td width="155">location<br/>(longitude)</td><td width="155">location<br/>(latitude)</td><td>Edit</td><td>Delete</td>';
		foreach($obj['curl'] as $key=>$val){ 
			echo '<tr>';
			echo '<td id="vid'.$val['venueId'].'">'.$val['venueId'].'</td>
				<td id="vname'.$val['venueId'].'">'.$val['venueName'].'</td>
				<td id="vlong'.$val['venueId'].'">'.$val['location'][0].'</td>
				<td id="vlat'.$val['venueId'].'">'.$val['location'][1].'</td>
				<td><input type="button" id="editbtn" onclick="editVenue('.$val['venueId'].');" value="Edit"></td><td><a href="#" onclick="deleteVenue('.$val['venueId'].');">Delete</a></td>';
			echo '</tr>';
		}
		echo '</table>';
	}
?>
</body>
</html>