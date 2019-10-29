<?php 
	session_start();
	
	include('header.php');
	
	$t = -1;
	if(isset($_POST['simulate'])){
		$t = $_POST['hour'];
	}	
	include('create_polygons.php');

?>

<script>
    //global variable
    var map;

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
        p.setMap(map);
	}
	
	function initMap(){
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: 14,
            center: new google.maps.LatLng(40.632839,22.940641),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
		polygons.forEach(add_polygon);
	}
</script>


<form id="form" method="post" action="index.php">
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
	<input type="submit" name="simulate" value="Simulate">
</form>

<div id='map_canvas'></div>

<?php
	echo '<script async defer src="https://maps.googleapis.com/maps/api/js?key='.$key.'&libraries=places&callback=initMap"></script>';
?>


<?php
	include('footer.php');
?>