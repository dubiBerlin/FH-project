<?php 

	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
	
	$verbindung = @mysqli_connect($server, $benutzer,$passwort);
	//echo "<script>alert('YES')</script>";
	if($verbindung)
	{
		mysqli_select_db($verbindung, $datenbank);
		
		if(mysqli_error($verbindung))
		{
			echo '<br/>Fehler '.mysqli_error($verbindung);	
		}
		else
		{
			if(isset($_GET['storyID'])){
				
				$storyID = $_GET['storyID'];
				
				$positions = array();
				
				$query = "SELECT * FROM position WHERE storyID='{$storyID}' ";
			
				$abfrage = mysqli_query($verbindung, $query);
				
				while($row = mysqli_fetch_assoc($abfrage)){
					
					array_push( $positions, array('posID' => $row['posID'], 'pos1' => $row['pos1'], 'pos2' => $row['pos2']));		
		
				}
				
				@mysql_close($verbindung);
				echo json_encode(array("positions" => $positions));
				exit;
				
				$positions = '';
			}
		}	

	}

?>