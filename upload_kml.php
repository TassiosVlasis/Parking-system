<?php 
	session_start();
	//Page for admin only
	if (!isset($_SESSION["admin"])){
		header("Location: login.php");
	}	

	//helper function for xml
	function parseToArray($xpath,$class)
	{
		$xpathquery="//span[@class='".$class."']";
		$elements = $xpath->query($xpathquery);

		if (!is_null($elements)) {  
			$resultarray=array();
			foreach ($elements as $element) {
				$nodes = $element->childNodes;
				foreach ($nodes as $node) {
				  $resultarray[] = $node->nodeValue;
				}
			}
			return $resultarray;
		}
	}

	include('Polygon.php');
	include('header.php');
	
	$err = '';

	if (isset($_POST["submit"])){
		//if upload file
		if(isset($_FILES["uploaded_file"])) {
			$file = $_FILES["uploaded_file"]["tmp_name"];	
		
			$xml = simplexml_load_file($file) 
				or die("Error: Cannot create object");
			$childs = $xml->Document->Folder->children();
			$count = 0;
			$xx = 0; 
			
			foreach($childs as $child) {
				$pop = -1;
				$description = $child->description;
				if (!$description){
					continue;
				}
				$dom = new DomDocument();
				$dom->loadHTML($description);
				$xpath = new DOMXpath($dom);
				$txt1 = parseToArray($xpath,'atr-name');
				$i = array_search('gid',$txt1);
				if ($i!== false)
				{
					$s = array_search('Population',$txt1);
					if ($s !== false){
						$txt2 = parseToArray($xpath,'atr-value');
						$pop = $txt2[$i];
					}
					else 
					{
						$pop = 0;
					}
				}
				//if ($i !== false){
					//$txt2 = parseToArray($xpath,'atr-value');
					//$pop = $txt2[$i];
                //}
         
				
				if($pop == -1){
					continue;
				}
				
				$coordinates = @$child->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
				if (!$coordinates){
					continue;
				}
				$xx++;
				$count++;
				$demand_id = rand(1,3); //random demand type [1,3]
				$positions = rand(50,150); //random position number [50, 150]
				$query1 = "INSERT INTO blocks VALUES($count, $pop, $positions, 0, 0, $demand_id)";
				
				$result = mysqli_query($con, $query1);
				
				$coordinates = explode(' ', $coordinates);


				
				$coor_array = array();
				foreach($coordinates as $c)
				{
					$tmp = explode(',',$c);
					$x = $tmp[0];
					$y = $tmp[1];
					
													
					$query4 = "INSERT INTO points (block_id , x,y) VALUES ( $xx ,$x , $y)";
					$result = mysqli_query($con, $query4);
						
					
					$coor_array[] = array($x, $y);
				}
				
				
				$res = getCentroidOfPolygon($coor_array);
				$centroid_x = $res[0];
				$centroid_y = $res[1];
				
				$query3 = "UPDATE blocks SET centroid_x=$centroid_x, centroid_y=$centroid_y WHERE id=$count";
				$result = mysqli_query($con, $query3);
			}
			
			$err .= "<br>Data uploaded!<br>";
			
		}
	}
	
	echo '<b>'.$err.'</b>';
?>

<form id="form" action="upload_kml.php" method="post" enctype="multipart/form-data">
Upload KML file: <input type="file" name="uploaded_file" id="uploaded_file" /> 
<input type="submit" name="submit" value="Submit" /> (Be patient for large files...)
</form>
<br>

<a href="delete.php">Delete all data</a>

<?php
include('footer.php');
?>


