<?php

	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
	
	if(!empty($_GET)){
		
		/* Abfrage für die  */	
		if(isset($_GET['yes'])){
			
			
			$verbindung = @mysqli_connect($server, $benutzer,$passwort);
			if($verbindung)
			{
				
					
				mysqli_select_db($verbindung, $datenbank);
				
				if(mysqli_error($verbindung)){
					fail(mysqli_error($verbindung)) ;
					exit;
				}
				else{
					$text = '';
					$d = '';
					$u = '';	
					
					$sql = 'SELECT * FROM eintrag';
					
					$abfrage = @mysqli_query($verbindung, $sql);
					
					$eintraege = array();
					
					while($row = @mysqli_fetch_assoc($abfrage)){
						
						// $text = $row['text'];
						// $d    = $row['datum'];
						// $u    = $row['uhrzeit'];  
// 						
						array_push( $eintraege, array('text' => $row['text'], 'date' => $row['datum'], 'time' => $row['uhrzeit']));		
					}
					
					@mysql_close($verbindung);
					
					echo json_encode(array("eintraege" => $eintraege));
					exit;
					
					
				}
			}
			else{
				fail('Ups! No connection to the server.'); // keine Verbindung zum Server
				exit;
			}
		}
			
		/* Abfrage für den erstellten User-Eintrag */	
		if((isset($_GET['user_eingabe'])!='')&& !empty($_GET['user_eingabe'])){
			
			$value = $_GET['user_eingabe'];
			
			$verbindung = @mysqli_connect($server, $benutzer,$passwort);
			
			if($verbindung)
			{	
				mysqli_select_db($verbindung, $datenbank);
				
				if(mysqli_error($verbindung)){
					fail(mysqli_error($verbindung)) ;
					exit;
				}
				else{
					
					$timestamp = time();
					$datum = date("d.m.Y",$timestamp);
					$uhrzeit = date("H:i",$timestamp);
					
					$text = mysqli_real_escape_string($verbindung, $value);
					
					@$sql = "INSERT INTO `eintrag`(`text`, `datum`, `uhrzeit`) VALUES ('{$text}', '{$datum}','{$uhrzeit}')";
					//echo 'success';
					if(mysqli_query($verbindung, $sql)){
						
						$ausgabe = nl2br($value); // fügt für Zeilenumbrüche die im Textarea erstellt worden sind, HTML Zeilenumbrüche ein.
						@mysql_close($verbindung);
						success($ausgabe, $datum, $uhrzeit);
						exit;
					}
				}
			}else{
				fail('Ups! No connection to the server.'); // keine Verbindung zum Server
				exit;
			}
		}
	}
	
	
	function fail($message) {
		die(json_encode(array('status' => 'fail', 'message' => $message )));
	}
	
	function success($message, $date, $time) {
		die(json_encode(array('status' => 'success', 'message' => $message, 'date' => $date, 'time' => $time)));
	}

?>