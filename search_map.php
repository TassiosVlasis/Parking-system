<?php 
	session_start();

	$t = -1;
	if(isset($_POST['submit'])){
		//echo '<script>alert("Not yet implemented!");</script>';
		$t = $_POST['hour'];
		
		
	}
	
	include('header.php');
	include('create_polygons.php');

?>
<script>
    //global variables
    var map;
	var myMarker;

	<?php
	//create var polygons
	echo create_polygons($t);
	?>
	add_polygon =  function(item, index){
        var p = new google.maps.Polygon({
          paths: item.coords,
          strokeColor: item.color,
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: item.color,
          fillOpacity: 0.35
        });	
        p.setMap(map); //topothetisi
	}


    function initMap(){
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: 14,
            center: new google.maps.LatLng(40.632839,22.940641),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
		polygons.forEach(add_polygon); //dhmiourgia

		
		myMarker = new google.maps.Marker({
            position: new google.maps.LatLng(40.632839,22.940641),
            draggable: true,
            map: map
        });
		//update fields with marker position
        google.maps.event.addListener(myMarker, 'dragend', function (evt) {
            document.getElementById('lat').innerHTML = evt.latLng.lat().toFixed(6);
            document.getElementById('lng').innerHTML = evt.latLng.lng().toFixed(6);
        });

		//create and join circle with marker
		var	markerCircle = new google.maps.Circle({
			strokeColor: '#00BFFF' ,
			strokeOpacity: 0.8 ,
			strokeWeight: 2,
			fillColor: '#00BFFF' , 
  			center: myMarker.position,
			draggable: true,
  			radius: 500,
  			map: map
		});
		myMarker.bindTo("position", markerCircle , "center"); //desmeuei
	}
</script>

<form action="search_map.php" method="post">
    Drag marker to parking place (now at: <span id="lat">40.632839</span>, <span id="lng">22.940641</span>)<br>
	Max Distance (meters): <input id="dist" type="number" name="dist" value="50" /><br>
	Hour: <select name="hour">
		<?php
		
		if(isset($_POST["hour"])){
			$selected = $_POST["hour"]; //selected hour
		}else{
			$selected = date("H")+3; //get current hour
		}

		for($t=0;$t<=23;$t++){
			echo '<option value="'.$t.'" ';
			if ($t == $selected){
				echo 'selected="selected"';
			}
			echo '>'.$t.':00</option>';
		}
		?>
	</select> 
	<br>
    <input type="submit" name="submit" value="Submit" />
</form>


<div id='map_canvas'></div>

<?php
	echo '<script async defer src="https://maps.googleapis.com/maps/api/js?key='.$key.'&libraries=places&callback=initMap"></script>';
?>

<?php
	include('footer.php');
?>