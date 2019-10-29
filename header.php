<?php
	$key = 'AIzaSyBiGDY0sTFg5kcp2v4L8qfWB7tyTzIqI1k';
	
	$con = mysqli_connect("localhost","root","NekHTqJB","parking") 
		or die('Could not connect #1: ' . mysqli_error($con));
	
	mysqli_query($con, 'SET NAMES utf8');
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Parking web app</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div>
    <div id="menu">
        <table>
			<tr>
				<th>
					<a href="index.php">Parking Map</a>
				</th>
				<th>
					<a href="search_map.php">Parking Search</a>
				</th>
			<?php
			if (isset($_SESSION['admin'])) {
			?>
				<th>
					<a href="upload_kml.php">Upload KML</a>
				</th>
				<th>
					<a href="edit_map.php">Edit Map</a>
				</th>
				<th>
					<a href="logout.php">Logout</a>
				</th>
			<?php
			} else {
			?>
				<th>
					<a href="login.php">Login</a>				
				</th>				
			<?php
			} 
			?>
			</tr>
		</table>
    </div>
  
	<div id="main">