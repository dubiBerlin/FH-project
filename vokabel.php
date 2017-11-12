<?php 

	//error_reporting(E_ALL); // zum debuggen           
	//ini_set('display_errors', true);
	
	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
	
	$tl_ID = '';	// hier wird die testlangID eingefügt die empfangen wird von dem dropdown menu im vokabeltrainer
	
	if(!empty($_GET))
	{
		if(isset($_GET['i1'])){
			addNewLanguage($_GET['i1']);
		}
		if(isset($_GET['a'])){
			buildSelectLanguages();
		}	
		if(isset($_GET['sel_1']) && isset($_GET['sel_2']) && isset($_GET['input_w1'])&& isset($_GET['input_w2'])){
			addNewVocable($_GET['sel_1'], $_GET['input_w1'], $_GET['sel_2'], $_GET['input_w2']);
		}
		if(isset($_GET['testlangID'])){
			getAllVocsByTestLangId($_GET['testlangID']);
			// global $tl_ID;	
			// $tl_ID = $_GET['testlangID'];
			// $returnVal = selectRandomVoc($tl_ID);
			// die(json_encode($returnVal));
			//exit;//die(selectRandomVoc($tl_ID));
		//	setFirstTestVocable($_GET['testlangID']);
		}
		if(isset($_GET['vT_input1']) && isset($_GET['vT_input2']) && isset($_GET['vT_input3']) && isset($_GET['vT_input4'])){
			compareVocable($_GET['vT_input1'], $_GET['vT_input2'], $_GET['vT_input3'], $_GET['vT_input4']);	
		}
	}
	
	// fügt neue Sprache in die DB ein
	function addNewLanguage($value){

		$server    = 'localhost';
		$benutzer  = 'root';
		$passwort  = 'z8xjHAMJcKjezt3D';
		$datenbank = 'theProject';
		
		if( trim($_GET['i1'] != '' && !empty($_GET['i1'])))	{
			$verbindung = @mysqli_connect($server, $benutzer,$passwort);
			if($verbindung)
			{	
				mysqli_select_db($verbindung, $datenbank);
				
				if(mysqli_error($verbindung)){
					echo mysqli_error($verbindung);
					exit;
				}
				else{
					$language = mysqli_real_escape_string($verbindung, $value);
					$sql = "SELECT * FROM language WHERE language='{$language}'";
					$abfrage = mysqli_query($verbindung, $sql);
					while($row = @mysqli_fetch_assoc($abfrage)){
						if(strcasecmp($row['language'], $language)==0){
							echo 2;// this language exists in database
							exit;
						}
					}
					
					$sql = '';
					
					@$sql = "INSERT INTO `language`(`language`) VALUES ('{$value}')";
					
					if(mysqli_query($verbindung, $sql)){
						@mysql_close($verbindung);
						echo "success";//"Die Sprache : ".$language." wurde erfolgreich zu den anderen Sprachen hinzugefügt. ";
						exit;
					}
				}
			}
			else {
				echo 1;//'No connection to server'
				exit;	
			}
		}	
	}
	
// Füge neue Vokabel in DB
function addNewVocable($language1, $voc1, $language2, $voc2 ){
		
	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
	
	if(trim($voc1 != '' && !empty($voc1)) && (trim($voc2 != '' && !empty($voc2)))){
		$verbindung = @mysqli_connect($server, $benutzer,$passwort);
		if($verbindung)
		{	
			mysqli_select_db($verbindung, $datenbank);
			
			if(mysqli_error($verbindung)){
				echo mysqli_error($verbindung);
				@mysql_close($verbindung);
				exit;
			}
			else{
				$l1 = mysqli_real_escape_string($verbindung, $language1);
				$l2 = mysqli_real_escape_string($verbindung, $language2);
				$v1 = mysqli_real_escape_string($verbindung, $voc1);
				$v2 = mysqli_real_escape_string($verbindung, $voc2);
				
				$id1 = '';
				$id2 = '';
				
				// Returnwert 0 heisst dass beide Vokabeln NICHT vorhanden sind
				if(searchVocable($l1, $v1, $l2, $v2 ) == 0){
					@$sql = "INSERT INTO `word`(`languageID`, `text`) VALUES ('{$l1}','{$v1}')";
				
					if(@mysqli_query($verbindung, $sql)){
						
						// hole die id des ersten eingesetzten Datensatzes
						$id1 = mysqli_insert_id($verbindung);	
						
						
						// füge zweite vokabel ein
						@$sql = "INSERT INTO `word`(`languageID`, `text`) VALUES ('{$l2}','{$v2}') ";
						
						if(@mysqli_query($verbindung, $sql)){
							// hole id der zweiten vokabel	
							$id2 = mysqli_insert_id($verbindung);
							
							
							// füge beide id`s in die Tabelle translation
							$sql = "INSERT INTO `translation`(`languageID1`,`word1_ID`,`text1`, `languageID2`, `word2_ID`, `text2`) VALUES ('{$l1}', '{$id1}', '{$v1}', '{$l2}', '{$id2}', '{$v2}') ";
							
							if(mysqli_query($verbindung, $sql)){
								
								$transID = mysqli_insert_id($verbindung);
								@mysql_close($verbindung);
								
								// fügt Sprachen + ID`s in Tabelle testlang ein
								addLangInTestlang( $l1, $l2 );
									
								echo "success";
								exit;
							}
						}		
					}
					else{
						echo 2; // an error occured
						exit;
					}
				}//ENDE if searchVocable($l1, $v1, $l2, $v2 )==3
				else{
					echo 3; // vokabel schon vorhanden
					exit;
				}
			}
		}
		else{
			echo 1; //'No connection to server'
			exit;
		}
	}
}
	
	/*
	 * Aufrufer : addNewVocable()
	 * Funktion : bekommt zwei languageID`s übergeben.
	 * 			  schaut nach ob sich beide Sprachen in Kombination in Tabelle testlang befinden.
	 *            falls nein, language zu den übergebenen languageID`s aus Tabelle language auslesen.
	 *            languages und languageIDs in Tabelle testlang einfügen.
	 * */
	function addLangInTestlang($l1, $l2 ){
		$server    = 'localhost';
		$benutzer  = 'root';
		$passwort  = 'z8xjHAMJcKjezt3D';
		$datenbank = 'theProject';		
		
		$langID1 = $l1;
		$langID2 = $l2;
		
		$language1 = '';
		$language2 = '';
		
		$verbindung = @mysqli_connect($server, $benutzer,$passwort);
		if($verbindung)
		{	
			mysqli_select_db($verbindung, $datenbank);
			
			if(mysqli_error($verbindung)){
				echo mysqli_error($verbindung);
				@mysql_close($verbindung);
				exit;
			}
			else{
				$sql = "SELECT * FROM testlang";
									
				$abfrage = mysqli_query($verbindung, $sql);
				
				$hit = 0;
				
				while($row = mysqli_fetch_assoc($abfrage)){
					if( (($row['languageID1'] == $langID1) && ($row['languageID2'] == $langID2)) || (($row['languageID2'] == $langID1) &&($row['languageID1'] == $langID2)) ){
						$hit++;
					}
				}
				
				// falls Sprachkombination nicht in Tabelle testlang vorhanden ist
				if($hit == 0){	
					$ausgabe = '';
					// hole Sprachnamen aus Tabelle language
					$sql = "SELECT * FROM language WHERE (languageID='{$langID1}' OR languageID='{$langID2}')";
					
					$abfrage = mysqli_query($verbindung, $sql);
					while($row = mysqli_fetch_assoc($abfrage)){
						
						$ausgabe = $ausgabe."languageID = ".$row['languageID'].") language : ".$row['language']."<br/>";
						
						if($row['languageID'] == $langID1){
							$language1 = $row['language'];
						}
						if($row['languageID'] == $langID2){
							$language2 = $row['language'];
						}
					}
					
					// jetzt sind beide Sprachen + zugehöriger ID vorhanden. Diese jetzt in Tabelle testlang einfügen
					$sql = "INSERT INTO `testlang`(`languageID1`, `language1`, `languageID2`, `language2`) VALUES ('{$langID1}','{$language1}', '{$langID2}','{$language2}') ";
					mysqli_query($verbindung, $sql);
					@mysql_close($verbindung);
					return 0;	
					
				}else{
					return -1;
				}
			}
		}
	}
	
		function buildSelectLanguages(){
		
		$server    = 'localhost';
		$benutzer  = 'root';
		$passwort  = 'z8xjHAMJcKjezt3D';
		$datenbank = 'theProject';
		
		$verbindung = @mysqli_connect($server, $benutzer,$passwort);
		if($verbindung)
		{	
			mysqli_select_db($verbindung, $datenbank);
			
			if(mysqli_error($verbindung)){
				echo "Keine Verbindung zur Datenbank.<br/>";
			}
			else
			{
				$sql = 'SELECT * FROM language';
				
				$abfrage = mysqli_query($verbindung, $sql);	
				
				echo '<form id="saveWordForm" name="saveWordForm" method="GET">';
				echo '<table border="0">';
				echo '	<tr>';
				echo '		<td>';
				//echo '			<div id="select1">';
				echo "				<select id='sel_1' name='sel_1'>\n";		
				echo '					<option value=""> - Select Story - </option>';
			while($row = mysqli_fetch_assoc($abfrage)){
				echo "					<option value='" .$row['languageID']. "'>";    	
	    		echo $row['language']."</option>\n";
			}
				echo "				</select>\n";	
				//echo "			</div>";
				echo '		</td>';
				
				$sql = 'SELECT * FROM language';
				
				$abfrage = mysqli_query($verbindung, $sql);	
				
				echo '		<td>';
				//echo '			<div id="select2">';
				echo "				<select id='sel_2' name='sel_2'>\n";		
				echo '					<option value=""> - Select Story - </option>';
			while($row = mysqli_fetch_assoc($abfrage)){
				echo "					<option value='" .$row['languageID']. "'>";    	
	    		echo $row['language']."</option>\n";
			}
				echo "				</select>\n";	
				//echo "			</div>";
				echo '		</td>';
				echo '	</tr>';
				echo '	<tr>';
				echo '		<td>';
				//echo '			<div id="input1">';
				echo '				<input type="text" name="input_w1" id="input_w1" size="20" />';
				//echo "			</div>";
				echo '		</td>';
				echo '		<td>';
				//echo '			<div id="input2">';
				echo '				<input type="text" name="input_w2" id="input_w2" size="20" />';
				//echo "			</div>";
				echo '		</td>';
				echo '		<td>';
				//echo '			<div id="sub1">';
				echo '				<Button type="submit" name="sub1_w"  class="navi_button"  id="sub1_w" >Save word</Button>';
				//echo "			</div>";
				echo '		</td>';
				echo '</form>
				     <br/>
				     <div id="savedOrNOt"></div>';
				@mysql_close($verbindung);
			}
		}
	}
	
/*
 * Schaut in Tabelle word nach ob die übergebenen Wörter vorhanden sind.
 * 1) falls keines von beiden in der Tabelle vorhanden ist, wird -> 0 <- an die aufrufende
 *    Funktion addNewVocable() zurück gegeben, die dann die beiden Wörter in die Tabelle einfügt.
 * 2) Ist nur jeweils nur eine von beiden Vokabeln in der Tabelle vorhanden, übernimmt searchVocable()
 *    das einfügen in die Tabelle
 * 3) Sind beide vorhanden, gibt es -> 3 <- als Return-Wert an die aufrufende JS-Datei zurück.
 * 
 * 
 * Wird nicht mehr gebraucht!
 */	
function searchVocable($language1, $voc1, $language2, $voc2 ){
	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
		
	$vokHit1   = 0;	// beim Einfügen einer Vokabel in DB wird erstmal gesucht ob Wort schon vorhanden
	$vokHit2   = 0;	// beim Einfügen einer Vokabel in DB wird erstmal gesucht ob Wort schon vorhanden
	
	$sqlResult = ''; // um einen einzigen Datensatz aus der DB zu holen
	
	$verbindung = @mysqli_connect($server, $benutzer,$passwort);
	
	if($verbindung)
	{	
		mysqli_select_db($verbindung, $datenbank);
		
		if(mysqli_error($verbindung)){
			echo mysqli_error($verbindung);
			@mysql_close($verbindung);
			exit;
		}
		else{
			
			$vocId1 = '';
			$vocId2 = '';
			
			// Suche in Tabelle word nach den beiden übergebenen Wörtern mit passender languageID
			$sql = "SELECT * FROM word WHERE (languageID='{$language1}' AND text='{$voc1}') OR (languageID='{$language2}' AND text='{$voc2}')";
			
			$abfrage = mysqli_query($verbindung, $sql);
			
			// Vergleiche Werte
			while($row = mysqli_fetch_assoc($abfrage)){
				
				if((strcasecmp($row['text'], $voc1)==0)&& ($language1==$row['languageID'])){
					$vokHit1++;
				}
				if((strcasecmp($row['text'], $voc2)==0)&& ($language2==$row['languageID'])){
					$vokHit2++;
				}
			}
			
			$result = '';
			
			if(( $vokHit1 == 0 ) && ( $vokHit2 == 0 )){ // beide Wörter sind nicht vorhanden
				$result = 0; 
				return $result;
			}
			else{
				if(( $vokHit1 > 0 ) && ( $vokHit2 == 0 ) ){ // das erste Wort ist vorhanden das zweite nicht -> Erweiterung
					
					// vok2 in Tabelle word speichern
					$sql = "INSERT INTO `word`(`languageID`, `text`) VALUES ('{$language2}','{$voc2}')";
					
					if(mysqli_query($verbindung, $sql)){
						
						// hole wordID des neuen gerade eben gespeicherten Wortes		
						$vocId2 = mysqli_insert_id($verbindung);	
						
						// hole wordID von der Vokabel die schon in der DB vorhanden ist (hier $voc1)
						$sql = "SELECT * FROM word WHERE text='{$voc1}' AND languageID='{$language1}'";
						
						$abfrage = mysqli_query($verbindung, $sql);
						
						while($row = mysqli_fetch_assoc($abfrage)){
							if((strcasecmp($row['text'], $voc1)==0) && ($row['languageID'] == $language1)){
								$vocId1 = $row['wordID'];
							}
						}
						//echo "vokabel 1 = ".$voc1." id: ".$vocId1;
						$sql = "INSERT INTO `translation`(`languageID1`,`word1_ID`,`text1`, `languageID2`, `word2_ID`, `text2`) VALUES ('{$language1}', '{$vocId1}', '{$voc1}', '{$language2}', '{$vocId2}', '{$voc2}') ";
							
							
						if(mysqli_query($verbindung, $sql)){
							@mysql_close($verbindung);
							echo "success";
							exit;
						}
					}
				}
				else{
					if(($vokHit1 == 0) && ($vokHit2 > 0) ){// vok2 vorhanden vok1 nicht
						
					//	echo "<br/>2) ".$voc2." ist vorhanden ".$voc1."nicht<br/>";
					
						// vok1 in Tabelle word speichern
						$sql = "INSERT INTO `word`(`languageID`, `text`) VALUES ('{$language1}','{$voc1}')";
						
						if(mysqli_query($verbindung, $sql)){
							
							// hole wordID des neuen gerade eben gespeicherten Wortes		
							$vocId1 = mysqli_insert_id($verbindung);	
							
							$sql = "SELECT * FROM word WHERE text='{$voc2}' AND languageID='{$language2}'";
						
							$abfrage = mysqli_query($verbindung, $sql);
							
							while($row = mysqli_fetch_assoc($abfrage)){
								if((strcasecmp($row['text'], $voc2)==0) && ($row['languageID'] == $language2)){
									$vocId2 = $row['wordID'];
								}
							}
							
							// Speicher neues Wort in translation Tabelle mit eigener wordID und wordID von vok1
							
							$sql = "INSERT INTO `translation`(`languageID1`,`word1_ID`,`text1`, `languageID2`, `word2_ID`, `text2`) VALUES ('{$language1}', '{$vocId1}', '{$voc1}', '{$language2}', '{$vocId2}', '{$voc2}') ";
						
							if(mysqli_query($verbindung, $sql)){
								@mysql_close($verbindung);
								echo "success";
								exit;
							}
						}
					}
					else {
						if(( $vokHit1 > 0 ) && ( $vokHit2 > 0 )){
							$result = 3; // beide Wörter sind vorhanden vorhanden
							return $result;
						}	
					}
				} // $vokHit1==1 && $vokHit2==0 ELSE -> 5
			} // falls beide Wörte nicht vorhanden (else Zweig) ->4
		} // else falls mysqli_error -> 3
	}	// if(Verbindung) -> 2
}
	

	/*
	 * 
	 *  Vokabeltrainer 
	 * */
	
	function setFirstTestVocable($val){
		
		$server    = 'localhost';
		$benutzer  = 'root';
		$passwort  = 'z8xjHAMJcKjezt3D';
		$datenbank = 'theProject';
		
		$tl_ID    = ''; // übernimmt den übergebenen Funktionsparameter $val
		$lang_ID1 = ''; // speichert languageID 1
		$lang_ID2 = ''; // speichert languageID 2
		
		$verbindung = @mysqli_connect($server, $benutzer,$passwort);
		if($verbindung)
		{	
			mysqli_select_db($verbindung, $datenbank);
			
			if(mysqli_error($verbindung)){
				echo "Keine Verbindung zur Datenbank.<br/>";
			}
			else
			{
				$tl_ID = $val; // globale Variable
				$sql = "SELECT * FROM testlang WHERE testlangID='{$tl_ID}'";
				
				$abfrage = mysqli_query($verbindung, $sql);	
				
				while($row = mysqli_fetch_assoc($abfrage)){
					if($row['testlangID']== $tl_ID){
						$lang_ID1 = $row['languageID1']; // speichert languageID 1
						$lang_ID2 = $row['languageID2']; // speichert languageID 2
					}
				}
				
				// sind beide lIDs vorhanden
				if($lang_ID1!='' && $lang_ID2 != ''){
					//echo selectRandomVoc( $lang_ID1, $lang_ID2 );
					$returnValue = selectRandomVoc( $lang_ID1, $lang_ID2 );
					die(json_encode($returnValue));
				}	
			}
		}
	}//setFirstTestVocable
	
	function selectRandomVoc( $testL_id ){
		$server    = 'localhost';
		$benutzer  = 'root';
		$passwort  = 'z8xjHAMJcKjezt3D';
		$datenbank = 'theProject';
		
		$tl_ID    = ''; // übernimmt den übergebenen Funktionsparameter $val
		$lang_ID1 = ''; // speichert languageID 1
		$lang_ID2 = ''; // speichert languageID 2
		
		$countDataSet   = ''; // speichert die Anzahl der Datensätze aus Tabelle translation
		$randomVok      = ''; // Zufallszahl zwischen 1 und Anzahl der Datensätze in der Tabelle translation. Wählt eine vokabel aus
		$randomLanguage = ''; // Zufallszahl entweder 1 -> languageID1 oder 2 -> languageID2. Wählt eine der beiden Sprachen aus
		$opLanguageID   = ''; 
		$count          = 0;  
		$word_ID        = ''; // bekommt die ausgelesene Vokabel aus der Tabelle translation zugewiesen.
		$word_text      = ''; // speichert den text der vokabel 
		
		$verbindung = @mysqli_connect($server, $benutzer,$passwort);
		if($verbindung)
		{	
			mysqli_select_db($verbindung, $datenbank);
			
			if(mysqli_error($verbindung)){
				echo "Keine Verbindung zur Datenbank.<br/>";
			}
			else
			{
				$tl_ID = $testL_id; // globale Variable
				$sql = "SELECT * FROM testlang WHERE testlangID='{$tl_ID}'";
				
				$abfrage = mysqli_query($verbindung, $sql);	
				
				while($row = mysqli_fetch_assoc($abfrage)){
					if($row['testlangID']== $tl_ID){
						$lang_ID1 = $row['languageID1']; // speichert languageID 1
						$lang_ID2 = $row['languageID2']; // speichert languageID 2
					}
				}
				
				// sind beide lIDs vorhanden
				if($lang_ID1!='' && $lang_ID2 != ''){
					
					// wähle nach Zufall eine von beiden Sprachen 
					$sql = "SELECT * FROM translation WHERE (languageID1='{$lang_ID1}' AND languageID2='{$lang_ID2}') OR (languageID1='{$lang_ID2}' AND languageID2='{$lang_ID1}')";
					
					$abfrage = mysqli_query($verbindung, $sql);	
					
					$countDataSet = mysqli_num_rows($abfrage); // gibt die Anzahl der Datansätze zurück
					
					// Randomzahl ermitteln zur zufälligen Auswahl einer Vokabel
					$randomVok = rand(1, $countDataSet);
					
					//echo "<br/>".$randomVok."  ".$countDataSet."<br/>";
					
					$randomLanguage = rand(1,2); // zb entweder englisch oder deutsch
					$count = 0;
					
					while($row = mysqli_fetch_assoc($abfrage)){
						if($count == $randomVok){
							if($randomLanguage == 1){
								$word_ID = $row['word1_ID'];
								// wird in input gespeichert damit man gleich weis welche Sprache gesucht ist bei User-eingabe
								$opLanguageID = $row['languageID2']; 
							}else{
								if($randomLanguage == 2){
									$word_ID = $row['word2_ID'];
									$opLanguageID = $row['languageID1'];
								}
							}
						}
						$count++;
					}
					//echo "Ausgewähltes Word = ".$word_ID." <br/>";
					// jetzt das Wort mit text aus der Tabelle word rausholen
					$sql = "SELECT * FROM word WHERE wordID ='{$word_ID}'";
					
					$abfrage = mysqli_query($verbindung, $sql);
					
					while($row = mysqli_fetch_assoc($abfrage)){
						
						if( $word_ID == $row['wordID'] ){
							$word_text = $row['text'];
						}
					}
					// text und wordID der Vokabel wird mitgegeben und im form der html-datei gespeichert.
					// wird später für den Vergleich benötigt, wenn der user seine Eingabe gibt
					//echo $word_text." ".$word_ID."<br/>";
					@mysql_close($verbindung);
					
					$word_Infos = array(
						'status'     => 'success', 
						'text'       => $word_text,   // Text der Vokabel
						'wordID'     => $word_ID,    // id der Vokabel
						'languageID' => $opLanguageID // languageID der Sprache
					);
					
					// zurück an aufrufende Funktion
					return $word_Infos;
				}
			}
		}
	}
	
	/*
	 * selectRandomVoc(param1, param2) bekommt zwei languageID's zugewiesen und sucht über diese beiden
	 * ids in der Tabelle translation zufällig nach einer vokabel die abgefragt werden kann.
	 * Rückgabewert ist die zufällig ausgewählte Vokabel ihre dazugehörige wordID und languageID, damit man direkt
	 * nach der Eingabe des Users bescheid weiß, mit welchem Wort die Vokabel genau verglichen werden muss.
	 * 
	 * Aufgerufen von: setFirstTestVocable(param1), compareVocable(param1, param2, param3, param4)
	 */
	// function selectRandomVoc( $l_ID1, $l_ID2 ){
		// $server    = 'localhost';
		// $benutzer  = 'root';
		// $passwort  = 'z8xjHAMJcKjezt3D';
		// $datenbank = 'theProject';
// 		
		// $tl_ID    = ''; // übernimmt den übergebenen Funktionsparameter $val
		// $lang_ID1 = $l_ID1; // speichert languageID 1
		// $lang_ID2 = $l_ID2; // speichert languageID 2
// 		
		// $countDataSet   = ''; // speichert die Anzahl der Datensätze aus Tabelle translation
		// $randomVok      = ''; // Zufallszahl zwischen 1 und Anzahl der Datensätze in der Tabelle translation. Wählt eine vokabel aus
		// $randomLanguage = ''; // Zufallszahl entweder 1 -> languageID1 oder 2 -> languageID2. Wählt eine der beiden Sprachen aus
		// $opLanguageID   = ''; 
		// $count          = 0;  
		// $word_ID        = ''; // bekommt die ausgelesene Vokabel aus der Tabelle translation zugewiesen.
		// $word_text      = ''; // speichert den text der vokabel 
// 		
		// $verbindung = @mysqli_connect($server, $benutzer,$passwort);
		// if($verbindung)
		// {	
			// mysqli_select_db($verbindung, $datenbank);
// 			
			// if(mysqli_error($verbindung)){
				// echo "Keine Verbindung zur Datenbank.<br/>";
			// }
			// else
			// {
				// // wähle nach Zufall eine von beiden Sprachen 
				// $sql = "SELECT * FROM translation WHERE (languageID1='{$lang_ID1}' AND languageID2='{$lang_ID2}') OR (languageID1='{$lang_ID2}' AND languageID2='{$lang_ID1}')";
// 				
				// $abfrage = mysqli_query($verbindung, $sql);	
// 				
				// $countDataSet = mysqli_num_rows($abfrage); // gibt die Anzahl der Datansätze zurück
// 				
				// // Randomzahl ermitteln zur zufälligen Auswahl einer Vokabel
				// $randomVok = rand(1, $countDataSet);
// 				
				// //echo "<br/>".$randomVok."  ".$countDataSet."<br/>";
// 				
				// $randomLanguage = rand(1,2); // zb entweder englisch oder deutsch
				// $count = 0;
// 				
				// while($row = mysqli_fetch_assoc($abfrage)){
					// if($count == $randomVok){
						// if($randomLanguage == 1){
							// $word_ID = $row['word1_ID'];
							// // wird in input gespeichert damit man gleich weis welche Sprache gesucht ist bei User-eingabe
							// $opLanguageID = $row['languageID2']; 
						// }else{
							// if($randomLanguage == 2){
								// $word_ID = $row['word2_ID'];
								// $opLanguageID = $row['languageID1'];
							// }
						// }
					// }
					// $count++;
				// }
				// //echo "Ausgewähltes Word = ".$word_ID." <br/>";
				// // jetzt das Wort mit text aus der Tabelle word rausholen
				// $sql = "SELECT * FROM word WHERE wordID ='{$word_ID}'";
// 				
				// $abfrage = mysqli_query($verbindung, $sql);
// 				
				// while($row = mysqli_fetch_assoc($abfrage)){
// 					
					// if( $word_ID == $row['wordID'] ){
						// $word_text = $row['text'];
					// }
				// }
				// // text und wordID der Vokabel wird mitgegeben und im form der html-datei gespeichert.
				// // wird später für den Vergleich benötigt, wenn der user seine Eingabe gibt
				// //echo $word_text." ".$word_ID."<br/>";
				// @mysql_close($verbindung);
// 				
				// $word_Infos = array(
					// 'status'     => 'success', 
					// 'text'       => $word_text, 
					// 'wordID'     => $word_ID, 
					// 'languageID' => $opLanguageID
				// );
// 				
				// // zurück an aufrufende Funktion
				// return $word_Infos;
// 				
				// // success($word_text, $word_ID, $opLanguageID);
				// // exit;
			// }
		// }
	// }
	
	function success($wordText, $wordID, $langID) {
		die(json_encode(array('status' => 'success', 'text' => $wordText, 'wordID' => $wordID, 'languageID' => $langID)));
	}

/*
 * 	Alte Version des Vokabeltests.
 *  Da der Vergleich der Usereingabe im clienten erfolgt ist diese Funktion nicht mehr brauchbar.
 * */	
function compareVocable($word, $wordID, $userInput, $languageID){
	
	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
	
	$translangID = '';
	$error = '';
	$vokID = ''; // speichert die ID des gesuchten Wortes
	$right_or_wrong = '';
	$l_ID1 = $languageID;
	$l_ID2 = '';

	$verbindung = @mysqli_connect($server, $benutzer,$passwort);
	if($verbindung)
	{	
		mysqli_select_db($verbindung, $datenbank);
		
		if(mysqli_error($verbindung)){
			echo mysqli_error($verbindung);
			@mysql_close($verbindung);
			exit;
		}
		else{
			if(trim($userInput != '' && !empty($userInput))){
				
				$userInput = mysqli_real_escape_string($verbindung, $userInput);
				
				// schaue nach ob die Eingabe auch zur geforderten Sprache passt
				$sql = "SELECT * FROM word WHERE text='{$userInput}' AND languageID='{$languageID}'";
				
				$abfrage = mysqli_query($verbindung, $sql);
				
				while($row = mysqli_fetch_assoc($abfrage)){
					if((strcasecmp($row['text'], $userInput)==0)&& ($row['languageID'] == $languageID ) ){
						$vokID = $row['wordID'];
					}
				}
				
				// falls das eingebene Wort in DB vorhanden war und eine wordID zurückgegeben wurde
				if($vokID!=''){
					// jetzt haben wir die ID der eingegebenen Vokabel
					// und es wird geschaut ob die Eingabe auch die korrekte Übersetzung ist
					$sql = "SELECT * FROM translation WHERE (word1_ID='{$wordID}' AND word2_ID='{$vokID}') OR (word1_ID='{$vokID}' AND word2_ID='{$wordID}')";
					
					$abfrage = mysqli_query($verbindung, $sql);
					
					while($row = mysqli_fetch_assoc($abfrage)){
						if(($row['word1_ID'] == $wordID && $row['word2_ID'] == $vokID)){
							$right_or_wrong = 'right';
							$l_ID1 = $row['languageID1'];
							$l_ID2 = $row['languageID2'];
						}
						else{
							if(($row['word2_ID'] == $wordID && $row['word1_ID'] == $vokID)){
								$right_or_wrong = 'right';	
								$l_ID1 = $row['languageID1'];
								$l_ID2 = $row['languageID2'];
							}else{
								$right_or_wrong = 'false';	
							}
							
						}
					}
					$sql = "SELECT * FROM testlang WHERE languageID1='{$l_ID1}' AND languageID2='{$l_ID2}' 
					OR languageID1='{$l_ID2}' AND languageID2='{$l_ID1}'";
					$abfrage = mysqli_query($verbindung, $sql);
					
					while($row = mysqli_fetch_assoc($abfrage)){
						if(($l_ID1 = $row['languageID1'] && $l_ID2 = $row['languageID2'])
							|| ($l_ID2 = $row['languageID1'] && $l_ID1 = $row['languageID2'] )){
							$translangID = $row['testlangID'];
						}
					}
					
					// jetzt wissen wir ob die Eingabe des Users korrekt oder nicht war
					$returnVal = selectRandomVoc( $translangID );
					$returnVal['trueOrNot'] = $right_or_wrong;// ein weiteres Array-Feld wird angehangen
					$returnVal = json_encode($returnVal);
					
					die($returnVal);
				}
				else{ // falls keine wordID gefunden wurde, also die Eingabe ist falsch
					$array = array('trueOrNot' => 'false');
					die(json_encode($array));
				}
			}
		}
	}
}

/************************************************************************************
 * 		Das ist die einzige Funktion die vom Vokabeltrainer benötigt wird.          *
 *      sie gibt alle vokabeln aus die für die sprachkombination vorhanden sind     *
 * 																				    *
 * **********************************************************************************/

function getAllVocsByTestLangId($val){
	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';	
		
	$testLangID =  $val;
	$lang_ID1   = '';
	$lang_ID2   = '';
	
	$verbindung = @mysqli_connect($server, $benutzer,$passwort);
	
	mysqli_select_db($verbindung, $datenbank);
		
	if(mysqli_error($verbindung)){
		echo mysqli_error($verbindung);
		@mysql_close($verbindung);
		exit;
	}
	else{
		
		$sql = "SELECT * FROM testlang WHERE testlangID='{$testLangID}'";
				
		$abfrage = mysqli_query($verbindung, $sql);	
		
		while($row = mysqli_fetch_assoc($abfrage)){
			if($row['testlangID']== $testLangID){
				$lang_ID1 = $row['languageID1']; // speichert languageID 1
				$lang_ID2 = $row['languageID2']; // speichert languageID 2
			}
		}
		
		// sind beide lIDs vorhanden
		if($lang_ID1!='' && $lang_ID2 != ''){
			
			$sql = "SELECT * FROM translation WHERE (languageID1='{$lang_ID1}' AND languageID2='{$lang_ID2}') OR (languageID1='{$lang_ID2}' AND languageID2='{$lang_ID1}')";
			$abfrage = mysqli_query($verbindung, $sql);	
			
			 $vocables = array(); // hier werden die ganzen Vokabelpärchen aus der DB gespeichert
// 			
			while($row = mysqli_fetch_assoc($abfrage)){
				array_push( $vocables, array('word1' => $row['text1'], 'word2' => $row['text2']));		
				//echo $row['text1']." : ".$row['text2']."<br/>";
			}
			
			@mysql_close($verbindung);
			echo json_encode(array("vocables" => $vocables));
			exit;
			
		}
		
	}
}

	
?>