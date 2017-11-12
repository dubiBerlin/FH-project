<?php
	
	include "dbConnection.php";
	include 'auth.php';
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Eintrag</title>
		<script type="text/javascript" src="scripts/jquery-1.6.4.min.js"></script>
		<script type="text/javascript" src="scripts/user.js"></script>
		<link rel="stylesheet" type="text/css" href="CSS/user.css">
		<link rel="stylesheet" type="text/css" href="CSS/test.css">
	</head>
	<body>
		<div id="header">
			<table>
				<tr>
					<td>
						<div id="headerDivs">
							<a href="userProfil.php"><?php echo $_SESSION['username']."<br/>"; ?></a>
						</div>
					</td>
					<td>
						<div id="headerDivs">
							<a href="logout.php">Logout</a>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<table id="table1">
			<tr>
				<td>
					<div id="abstand1DivURS"></div>
				</td>
			</tr>
			<tr>
				<td>
					<?php
						$sql = 'SELECT * FROM story';
						//mysql_query($sql) or trigger_error('Fehler in Query "'.$sql.'". Fehlermeldung: '.mysql_error(),E_USER_ERROR);
						$abfrage = mysqli_query($verbindung, $sql);	
						
						/* ********* DropDown - Liste ********* */
						echo "<select id='storyID'>\n";		
						echo '<option value=""> - Select Story - </option>';
						while($row = mysqli_fetch_assoc($abfrage)){
							echo "<option value='" .$row['storyID']. "'>";    	
				    		echo $row['titel']."</option>\n";
						}
						echo "</select>\n";	
					?>
				</td>
			</tr>
			<tr>
				<td>
					<div id="abstand2DivURS"></div>
				</td>
			</tr>
			<tr>
				<td>
					<!-- hier fügt die js-Datei die zwei Fenster ein -->
					<div id="storyWindows"></div>
				</td>
			</tr>
		</table>
		
		
		
	</body>
</html>

