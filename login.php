<?php
	session_start();
	
	//login for admin
	$err="";
	if (isset($_POST['submit']))
	{
		if($_POST['username']=='admin' && $_POST['password']=='admin')
		{
			$_SESSION['admin'] = 'admin'; // Set session variable
			header("Location: index.php"); // redirect to main page.	
		} else { 
			// show error
			$err = "<b style='color:red'>Wrong username/password!</b>";
		}
	}

	
	include('header.php');
?>
 
	<form action="login.php" method="post">
		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="username" /></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" /></td>
			</tr>
			<tr>
			<td>
			<input type="submit" name="submit" value="Submit" />
			</td>
			<td>
			</td>
			</tr>
		</table>
	</form>
			
<?php
	
	echo $err;		
	
	include('footer.php');
?>