<?php

	/*********************************************************************************************************
	 * 																										 *
	 * 		fileUpload.php ladet die Datei in einen Ordner hoch, liest sie Zeile für Zeile und speichert     *
	 * 		den Inhalt in die DB. Anschließend werden die beiden hochgeladenen Dateien gelöscht.             *
	 *  																									 *
	 * *******************************************************************************************************/
	
	//error_reporting('ERR_NONE');
	/**************** debuggen ************************/
	error_reporting(E_ALL); // zum debuggen           
	ini_set('display_errors', true);                  
	/**************************************************/ 
	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
	$title     = '';
	$file1     = '';
	$file2     = '';
	
	$result = 0;
	$uploadDir = 'Uploads/'; // Verzeichnis in welches die Dateien hochgeladen werden
	$nrUploadedFiles = 0;
	$dateiTyp = '*.txt';
	$files= array();	     // sammelt die hochgeladenen Dateien ein
	$filesContent = array(); // für jede einzelne Datei wird hier der Inhalt in einem array[index] gespeichert
	$storyID = '';
	
	
if(!empty($_POST) && (is_dir($uploadDir)))
{
	//echo "<script>alert('fileUpload.php')</script>";
	if(isset($_POST['title']) && $_POST['title']!='' && trim($_POST['title'] != ''))
	{
		// count zählt Elemente im Array und fügt Anzahl der hochgeladenen Dateien in Variable $anzahl
		$anzahl = count($_FILES['uploadedfile']['tmp_name']);
		
		// läuft array durch 
		for($i = 0; $i < $anzahl; $i++)
		{
			if(($_FILES['uploadedfile']['type'][$i] == 'text/plain') ){
				// lade Datei hoch	
				move_uploaded_file($_FILES['uploadedfile']['tmp_name'][$i], 'Uploads/'.$_FILES['uploadedfile']['name'][$i]);
				
				// Files sind gesetzt also auch titel setzen
				$title = $_POST['title'];
				$nrUploadedFiles++;
			}
			else
			{
				if ($_FILES['uploadedfile']['tmp_name'][$i] == "") {
					/* zur Bestimmung der genauen Anzahl fehlender Dateien */
					if($result == 0){
						$result = 1;		// Eine Datei wurde vergessen.
					}
					else{
						if($result == 1){
							$result = 2;	// Wo sind die Dateien?
						}
					}
				}
				else{
					$result = 3;			// es können nur TXt-Dateien hoch geladen werden.
				}	
			}	
		}	
	}
	else {
		$result = 4; // Upss! Wo ist der Titel?
	}
}

	/* Dateien sind jetzt hochgeladen worden */

// Inhalt der Dateien in DB speichern
if($result==0)
{
	// Verzeichnis öffnen mit opendir() Methode
	$dir = opendir('Uploads');	
	
	/* Überprüfung ob 2 Dateien hochgeladen wurden
	* +2 wegen . und .. die durch scandir zurückgegeben werden 
	  count gibt Anzahl der Dateien im Ordner zurück */
	if(count(scandir($uploadDir))>=($nrUploadedFiles+2))
	{
		$counter = 0;
		$val = 0;
		$id = 0;
		// einzelnen Dateien auslesen und jeweils in array[index] einfügen
		foreach (glob($uploadDir.$dateiTyp) as $filename) {
			$files[$counter]= file($filename);
			
			// Inhalt der Datei auslesen, Zeile für Zeile.
			foreach ($files[$counter] as $key => $value) {
				if($counter==0){
					$val++;
				}
				// $pointPos = strpos($value,".");
				// $length = strlen($value); 
// 
				// if( $pointPos != null){
					// // an der Stelle -> ; <- wird bei dem auslesen der Texte in der Datei scrollBuilders.php ein <br> Element hinzugefügt
					// // 
					// $new = substr($value,0,($pointPos+2)).';'.substr($value, $pointPos+1,($length)); 
					// $value = $new;
				// }
				//$value = htmlentities('<div id="') .$id.htmlentities('">').$value.htmlentities('</div>');
				@$filesContent[$counter] = $filesContent[$counter].$value;
				$id++;
			}
			$counter++;
			$id = 0;
		}
		
		$i = 0;
		
		/* Start der Datenbankverbindung */
		$verbindung = @mysqli_connect($server, $benutzer,$passwort);
		
		if($verbindung){	
			// mysqli_select_db fuehrt Verbindung aus
			mysqli_select_db($verbindung, $datenbank);
			
			// falls Verbindung mit der DB NICHT steht
			if(mysqli_error($verbindung)){
				$result =  '<br/>Fehler : '.mysqli_error($verbindung);	// falls Verbindung nicht klappt
			}
			// falls Verbindung mit der DB steht
			else 
			{
				$sql = "INSERT INTO `story`(`titel`, `text1`, `text2`) VALUES ('{$title}','{$filesContent[0]}','{$filesContent[1]}')";					
				@mysqli_query($verbindung, $sql);
				$sql = 'SELECT * FROM story';	
				
				// anweisung mit mysqli_query() Methode ausf�hren, Ergebnis in Variable $abfrage einf�gen.
				$abfrage = mysqli_query($verbindung, $sql);	
				
				$counter = 0; 
				
				@closedir($dir);
				@mysql_close($verbindung);
				$result = 6;	// Die Dateien wurden erfolgreich hochgeladen
			}
		} 
		else{
			$result = 5; //Es konnte keine Verbindung zur Datenbank hergestellt werden.
		}
	} 		
}// end of if(error)	

?>

<?php
	
	// anschließend hochgeladene Dateien löschen
	foreach (glob($uploadDir.$dateiTyp) as $filename){
		unlink($filename);
	}
	
	sleep(2);
	
	echo $result;
	
?>


	