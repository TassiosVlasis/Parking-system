<?php
	session_start();
	//Page for admin only
	if (!isset($_SESSION["admin"])){
		header("Location: login.php");
	}	

	include('header.php');
		
	$err = '';
		
	if(isset($_POST['submit'])){
		$query = "UPDATE blocks SET positions=".$_POST['positions'].", demand_id=".$_POST['demands']." WHERE id=".$_POST['block_id'];
		$result = mysqli_query($con, $query);
		if(!$result){
			$err = 'Error on saving!';
		}else{
			$err = 'Successfully updated!';
		}
	}
	
	
	$block_id = $_GET['block_id'];
	//load block data
	$query = "SELECT * from blocks WHERE id=$block_id";
	$result = mysqli_query($con, $query);
	$block = mysqli_fetch_assoc($result);
	
	//get demand curves
	$query = "SELECT * from demands";
	$result = mysqli_query($con, $query);
	$demands = array();
	while($row = mysqli_fetch_assoc( $result)){
		$demands[] = $row;
	}

	
	echo '<br><b>'.$err.'</b>';

?>


<a href="edit_map.php">Back</a>
<br><br>

<form id="form" method="post" action="edit_block.php?block_id=<?php echo $block_id;?>">
Number of positions: <input type="number" name="positions" value="<?php echo $block["positions"]?>"><br>
Demand type: <select name="demands">
	<?php
	foreach($demands as $d){
		echo "<option value='{$d["id"]}' ";
		if ($d["id"] == $block["demand_id"]){
			echo "selected='selected'";
		}
		echo ">{$d["name"]}</option>";
	}
	?>
</select><br>
<input type="hidden" name="block_id" value="<?php echo $block_id;?>">
<input type="submit" name="submit" value="Submit">
</form>

<?php
include('footer.php');
?>