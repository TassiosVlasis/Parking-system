<?php

function create_polygons($t = -1){

	$con = mysqli_connect("localhost","root","NekHTqJB","parking") 
		or die('Could not connect #1: ' . mysqli_error($con));
	
	mysqli_query($con, 'SET NAMES utf8');
	
	$query1 = "SELECT * from blocks";
	$result = mysqli_query($con, $query1);
	$blocks = array();
	while(	$row = mysqli_fetch_assoc( $result)){
		$blocks[] = $row;
	}
	
	$query2 = "SELECT * from points";
	$result = mysqli_query($con, $query2);
	$points = array();
	while($row = mysqli_fetch_assoc($result)){
		$points[] = $row;
	}
	
	$query3 = "SELECT * from demands";
	$result = mysqli_query($con, $query3);
	$demands = array();
	while($row = mysqli_fetch_assoc( $result)){
		$demands[] = $row;
	}
	
	$N =  count($points);
	
	$colors = array('gray'=>'#A0A0A0','green'=>'#00FF00','yellow'=>'#FFC000','red'=>'#FF2020');
	
	$data = 'var polygons = [';
	
	//create one Placemark for each mysql row
	$num_points = 0; 
	$counter = 0;

	foreach($blocks as $block)
	{
		$counter++;

		
	$polygon = "{id: {$block['id']},
		population: {$block['population']},
		positions: {$block['positions']},";
		  
	  if($t>-1){
		  //simulate
		  $d = $demands[$block['demand_id']-1];
		  $a = $d["t$t"];
		  $free = $block['positions'] - 0.2*$block['population'] - $a*$block['positions'];
		  if ($free < 0){
			  $free = 0;
		  }
		  $free = floor($free);
		  
		  $polygon .= "free: $free, ";
	  }
			
	  $style = 'gray';
	  if($t>-1){
		  $p = 1 - $free / $block['positions'];

		  if($p < 0.59){
			$style = 'green';  
		  }else if ($p < 0.85){
			  $style = 'yellow';
		  }else{
			  $style = 'red';
		  }
	  }
	  
	  $polygon .= "color: '".$colors[$style]."',";
	  
	  $coordinates = "";
	  while($num_points < $N){
		  $p = $points[$num_points];
		  if ($p["block_id"]!=$block["id"]){
			  break;
		  }
		  $coordinates .= "{lat: ".$p["y"].", lng: ".$p["x"]."},";
		  $num_points++;
	  }
	  
	  // Creates a coordinates element
	  $polygon .= "coords:[$coordinates]},\n";	
	  $data .= $polygon;
	}

	$data .= "];\n\n";
	return $data;
}
?>