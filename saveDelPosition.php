<?php
	/************************************************************************************************
	*	 saveDelPositions.php speichert zwei Positionen, die StoryID in die Datenbank.				*
	*	 Zuerst wird überprüft ob es diese Positionen vielleicht schon gesetzt wurden.				*
	*	 Nach dem die beiden Positionen gespeichert wurden, wird anschließend die ID der             *
	*    Positionspärchen ausgelesen und an das aufrufende JavaScript zurückgegeben					*
	*	 Die posId wird im JavaScript-File für die Verarbeitung der Positionen benötigt				* 																			*
	*	 z.B. zum Löschen.																            *
	*    Die PHP-Datei löscht auch Positionen in der DB. Dabei empfängt es von der JavaScript 	    *
	*    Datei die posID und löscht durch einen einfach SQL-DELETE-Befehl das Pärchen.              *
	*	 RückgabeWert wäre hier eine 1 für erflogreich oder ein fail-Text							* 																				     		*
	*	 Aufgerufen von: callPositions.js  //saveDelPosition.js										*				
	* 																								*
	*************************************************************************************************/
	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
	$pos1      = '';
	$pos2      = '';
	$sql	   = '';
	$storyID   = '';
	$posID     = '';
	$posExists = false;
	
	$verbindung = @mysqli_connect($server, $benutzer,$passwort);
	
	if($verbindung)
	{
		mysqli_select_db($verbindung, $datenbank);
		
		if(mysqli_error($verbindung))
		{
			fail('Es besteht keine Verbindung zu der Datenbank.');	
		}
		else 
		{
			if(isset($_GET['pos1']) && isset($_GET['pos2']) && isset($_GET['storyID'])){
				
				$pos1    = $_GET['pos1'];
				$pos2    = $_GET['pos2'];
				$storyID = $_GET['storyID'];
				
				// schau zuerst nach ob sich nicht eine von den beiden Positionen in der DB befindet
				$sql = "SELECT * FROM position where storyID='{$storyID}'";
				
				$abfrage = mysqli_query($verbindung, $sql);	
				
				while($row = mysqli_fetch_assoc($abfrage)){
					if($pos1 == $row['pos1']){
						fail(-1); // Position 1 wird schon verwendet
						exit;
					}else{
						if($pos2 == $row['pos2']){
							fail(-2); // Position 2 wird schon verwendet
							exit;
						}
					}
				}
				
				@$sql = "INSERT INTO `position`(`storyID`, `pos1`, `pos2`) VALUES ('{$storyID}','{$pos1}','{$pos2}')";					
				
				if(@mysqli_query($verbindung, $sql))
				{
					$posID = mysqli_insert_id($verbindung); // posID der gerade eben eingefügten Positionen
					@mysql_close($verbindung);
					success($posID);
				}
			}

			// falls Position löschen
			if(isset($_GET['posID'])){
				
				$posID = $_GET['posID'];
				
				$sql = "DELETE FROM position WHERE posID='{$posID}' ";
				
				if(mysqli_query($verbindung, $sql)){
					successDelete(1);
				}
				else{
					fail('Could not delete positions');
				}
			}
		}
	}
	
	function fail($message) {
		die(json_encode(array('status' => 'fail', 'message' => $message )));
	}
	
	function success($posID) {
		die(json_encode(array('status' => 'success', 'posID' => $posID)));
	}
	
	function successDelete($message) {
		die(json_encode(array('status' => 'success', 'message' => $message)));
	}
	
?>
