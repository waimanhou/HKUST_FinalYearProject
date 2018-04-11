<!DOCTYPE html>
<html>
<head>
<script
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDtY_dK88qBmpHhTpgruv0vyiXUS-RuKl8&sensor=false">
</script>

<script>
function initialize()
{	
var mapProp = {
  center:new google.maps.LatLng(22.3379567,114.2641038),
  zoom:20,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };
var map=new google.maps.Map(document.getElementById("googleMap")
  ,mapProp);
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>

<body>
<div id="googleMap" style="width:500px;height:380px;"></div>

</body>
</html>