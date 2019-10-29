<?php
	session_start();
	//Page for admin only
	if (!isset($_SESSION["admin"])){
		header("Location: login.php");
	}	
	
	include('header.php');
	
	if (isset($_POST["submit"])){
		$q = "TRUNCATE TABLE `blocks`";
		$result = mysqli_query($con, $q);
		$q = "TRUNCATE TABLE `points`";
		$result = mysqli_query($con, $q);
			
		echo '<b>All data were deleted!</b>';
	}
?>


<form id="form" action="delete.php" method="post" onsubmit="return confirm('confirm deletion!');">
	Delete: <input type="submit" name="submit" value="Delete" />
</form>

<?php
include('footer.php');
?>