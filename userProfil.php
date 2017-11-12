<?php

	//@session_start(); 
	
	include "dbConnection.php";
	include 'auth.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>User profile</title>
		<script type="text/javascript" src="scripts/jquery-1.6.4.min.js"></script>
		<script type="text/javascript" src="scripts/user.js"></script>
		<script type="text/javascript" src="scripts/selectStory.js"></script>
		<link rel="stylesheet" type="text/css" href="CSS/user.css">
	</head>
	<body>
	<table>
		<tr>
			<td>
				<div id="header">
					<table>
						<tr>
							<td>
								<div id="headerDivs"><?php echo "Hallo ".$_SESSION['username']."<br/>"; ?></div>
								
							</td>
							<td>
								<div id="headerDivs"><a href="userReadStory.php">Stories</a></div>
								
							</td>
							<td>
								<div id="headerDivs"><a href="logout.php">Logout</a></div>
								
							</td>
						</tr>
					</table>	
				</div>
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
		</tr>
	</table>	
		
		
		
		
	</body>
</html>