<?php
	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
	
	$verbindung = @mysqli_connect($server, $benutzer,$passwort);
	if($verbindung)
	{	
		// mysqli_select_db fuehrt Verbindung aus
		mysqli_select_db($verbindung, $datenbank);
		if(mysqli_error($verbindung)){
			echo '<br/>Fehler '.mysqli_error($verbindung);	// falls Verbindung nicht klappt
		}
		// falls Verbindung mit der DB steht
		else{
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
			//echo '<br/><div id="savePos"></div>';	
			/* ******** Ende DropDown - Liste ******** */
		}
	}
else{
	$var = 'Damn... Anscheinend gibt es ein Problem mit der Verbindung zur Datenbank!';
	echo htmlentities($var);
}
	@mysql_close($verbindung);	
?>