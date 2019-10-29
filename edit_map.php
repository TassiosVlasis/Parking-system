<?php
	session_start();
	//Page for admin only
	if (!isset($_SESSION["admin"])){
		header("Location: login.php");
	}	
		
	include('header.php');
	
	include('create_polygons.php');
?>
<script>
    //global scope variables
    var map;

	<?php
	//this creates var polygons
		echo create_polygons(-1);
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
		
		google.maps.event.addListener(p, 'click', function (event) {
			var testimonial = document.getElementById('block_edit');
			testimonial.innerHTML = '<a href="edit_block.php?block_id=' + item.id + '">Edit block</a>' ;
		});
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


<div id="block_edit">Click on a block to edit and then the link here.</div>
<div id='map_canvas'></div>


<?php
echo '<script async defer src="https://maps.googleapis.com/maps/api/js?key='.$key.'&libraries=places&callback=initMap"></script>';
?>


<?php
include('footer.php');
?>