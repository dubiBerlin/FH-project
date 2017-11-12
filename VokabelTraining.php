

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Test-Upload</title>
	<link rel="stylesheet" type="text/css" href="CSS/vok.css">
	<script type="text/javascript" src="scripts/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="scripts/vokabel.js"></script>
	<!-- includes jQuery UI -->
	<link type="text/css" href="scripts/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="Stylesheet" />	

</head>
<body>
	<div id="mainDiv">
		<div id="first-wrap">
			<div id="a1">
				<button id="newL" class="navi_button" >Add New language</button>
			</div>
			<div id="a2">
				<button id="newW" class="navi_button">New words</button>
			</div>
			<div id="a3">
				<button id="newT" class="navi_button">Vocable Training</button>
			</div>
			<div id="a4">
				<button id="newS" class="navi_button">Edit Vocable</button>
			</div>
		</div><!-- addNewLangForm -->
		<div id="second-wrap">
			<div id="neueSprache">
				<div id="nl">
					<form id="f1_form" name="f1_form" class="f1_form" action="vokabel.php"  method="GET">
						<label>choose language</label>
						<input type="text" name="i1" id="i1" />
						<Button type="submit"  name="addNewLangButton"  id="addNewLangButton"  class="navi_button">Add Language</Button>
					</form>
				</div>
			</div>
			<div id="neuesWort">
				<!-- Inhalt wird dynamisch erzeugt -->
			</div>
			<div id="vokTest">
				<div id="vT_select">
					
					<?php
						$server    = 'localhost';
						$benutzer  = 'root';
						$passwort  = 'z8xjHAMJcKjezt3D';
						$datenbank = 'theProject';
						
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
									
								$sql = 'SELECT * FROM testlang';
								
								$abfrage = mysqli_query($verbindung, $sql);	
								
								/* ********* DropDown - Liste ********* */
								echo "<select id='testlangID'>\n";		
								echo '<option value=""> - Select Story - </option>';
								while($row = mysqli_fetch_assoc($abfrage)){
									echo "<option value='" .$row['testlangID']. "'>";    	
						    		echo $row['language1']."/".$row['language2']."</option>\n";
								}
								echo "</select>\n";
								
							}	
						}			
					?>	
						
				</div><br />
				<!-- Das ist die alte Version Vokabeltests. die Eingabe des Users war in einer
					 Form eingebaut und jede eingabe wurde zum Server gesendet. In der PHP- Datei
					 wurde dann die Eingabe in der DB verglichen. -->
				<!-- <form id="vT_form" name="vT_form" action="vokabel.php"  method="GET"> -->
					
					<!-- <div id="vT_div"></div> --><!-- hier wird die vorgeschlagene Vokabel angezeigt -->
					<!-- <input type="hidden" id="vT_input1" name="vT_input1"/>  -->       <!-- hier Vokabel für submit -->
					<!-- <input type="hidden" id="vT_input2" name="vT_input2"/> -->        <!-- hier Vokabel-ID für den submit -->
					<!-- <input type="text" id="vT_input3" name="vT_input3" size="35"/> --><!-- Eingabe des Users -->
					<!-- <input type="hidden" id="vT_input4" name="vT_input4" /> --> <!-- languageID -->
					<!-- <br/>
					<Button type="submit"  name="vT_submit" class="navi_button" id="vT_submit" >Send</Button>
					
					<div id="vT_message"></div> --><!-- hier wird angegeben ob die Eingabe die korrekte Übersetzung ist -->
				<!-- </form> -->
				
				<div id="vT_div"></div><!-- hier wird die vorgeschlagene Vokabel angezeigt -->
				<input type="text" id="vT_input3" name="vT_input3" size="35"/><!-- Eingabe des Users -->
				<br/>
				<Button type="submit" name="vT_submit" class="navi_button" id="vT_submit" >Send</Button><div id="correctAnswer"></div>
				<div id="vT_message"></div> <!-- hier wird angegeben ob die Eingabe die korrekte Übersetzung ist -->
				
			</div>
			<div id="editVok">
				<input type="text" id="vT_input4" name="vT_input4" size="35"/><!-- Eingabe des Users -->
				<button id="searchVok" class="navi_button">Search vocable</button>
			</div>	
		</div>
		
	</div>
	<script src="scripts/development-bundle/jquery-1.6.2.js"></script>
	<script src="scripts/development-bundle/ui/jquery.ui.core.js"></script>
	<script src="scripts/development-bundle/ui/jquery.ui.widget.js"></script>
	<script src="scripts/development-bundle/ui/jquery.ui.position.js"></script>
	<script src="scripts/development-bundle/ui/jquery.ui.autocomplete.js"></script>
	<script>
		
	</script>
	
</body>
</html>