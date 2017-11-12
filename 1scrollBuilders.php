<?php
//require_once("ConnectDB.php");

$data = '';
$server    = 'localhost';
$benutzer  = 'root';
$passwort  = 'z8xjHAMJcKjezt3D';
$datenbank = 'theProject';

			
	$verbindung = @mysqli_connect($server, $benutzer,$passwort);
	if($verbindung)
	{	
		mysqli_select_db($verbindung, $datenbank);
		
		if(mysqli_error($verbindung)){
			echo "Keine Verbindung zur Datenbank.";
		}
		else{
			if(isset($_GET['storyID'])){
	
				$storyID = $_GET['storyID'];
				
				$sql = "SELECT * FROM story WHERE storyID ='{$storyID}' ";
				$abfrage = mysqli_query($verbindung, $sql);
				
				$txt1 = "";
				$txt2 = "";
				$id   = 0;
				$scrollPosBrowser = 0;
				
				// falls die Position des Browser-Fenster mitgesendet wird
				if(isset($_GET['scrollPos'])){
					$scrollPosBrowser = $_GET['scrollPos'];
				}
				
				while($row =  mysqli_fetch_assoc($abfrage)){
					
					// Bearbeitung der Texte um sie Mouseover-fähig zu machen
					$txt1 = explode(".", $row['text1']);
					$txt2 = explode(".", $row['text2']);
					
					$newTxt1 = '';
					$newTxt2 = '';
					
					foreach ($txt1 as $key => $value) {
						$newTxt1.= '<div id="id_'.$id.'">'.htmlentities($value).'.</div>';
						$id++;
					}
					
					$id = 0;
					
					foreach ($txt2 as $key => $value) {
						$newTxt2.= '<div id="id_'.$id.'">'.htmlentities($value).'.</div>';
						$id++;
					}
					
					echo '<div id="scrollVerbund">';
					echo '<input type="hidden"  name="posBrowser" id="posBrowser" value="'.$scrollPosBrowser.'"/>';
					echo '	<table border="0">';
					echo '		<tr>';
					echo '			<td>';
					echo '				<div id="titleStory1">'.$row['titel'].'</div>';
					
					echo '			</td>';
					echo '          <td>';
					echo '				 <div id="idStory1">'.$row['storyID'].'</div>';
					echo '			</td>';
					echo '		</tr>';
					echo '	<colgroup>
								<col width="530">
								<col width="530">
						 	 </colgroup>';	
					echo '		<tr>';
					echo '			<td>';
					// echo "				<div id='scroll1'>". htmlspecialchars_decode($row['text1'])."</div>";
					echo "				<div id='scroll1'>".$newTxt1."</div>";
					echo '			</td>';
					echo '			<td>';
					//echo "				<div id='scroll2'>".htmlspecialchars_decode($row['text2'])."</div>";
					echo "				<div id='scroll2'>".$newTxt2."</div>";
					echo '			</td>';
					echo '		</tr>';
					echo '		<tr>';
					echo '			<td>';
					echo '              <div id="div_savePosForm">';
					echo '				<form id="savePosForm" name="savePosForm" action="saveDelPosition.php" method="GET">';
					/* die input Felder speichern die Positionen und sind für nötig für das Formular. 
					 * Die div's die mit den ID's show_pos1 und show_pos2 sind nur zum anzeigen der Positionen gedacht. 
					 * Die input type="" nehmen die Werte auf. */
					echo '              	<table border="0" cellpadding="0" cellspacing="4">
												<tr>
    					       						<td>
    					       							<Label>Position left :</Label>
      						       					</td>
      						       					<td>
      						       						<div name="show_pos1" id="show_pos1" ></div>
													</td>
													<td>
														<input type="hidden"  name="pos1" id="pos1" />
													</td>
													<td>
														<Label>Position rechts :</Label>
													</td>
      							   					<td>
      							   						<div name="show_pos2" id="show_pos2" ></div>
													</td>
													<td>
														<input type="hidden"  name="pos2" id="pos2"  />
													</td>
													<td>
														<input type="hidden" name="storyID" value='.$storyID.' />
													</td>
      							   					<td>
														<Button type="submit"  name="saveButton"  id="saveButton" >Save position</Button>
													</td>
   						       					</tr>
  											</table>';
					echo '				</form>';
					echo '              </div>';
					echo '			</td>';
					echo '		</tr>';
					echo '	</table>';
					echo '<div id="sDivs2">';
					echo '		<table border="0">';
					echo '		<colgroup>
									<col width="530">
									<col width="530">
							  	</colgroup>';	
					echo '			<tr>';
					echo '				<td>';
					// echo "					<div id='scroll11'>". htmlspecialchars_decode($row['text1'])."</div>";
					echo "					<div id='scroll11'>". $newTxt1."</div>";
					
					
					echo '				</td>';
					echo '				<td>';
					//echo "					<div id='scroll21'>".htmlspecialchars_decode($row['text2'])."</div>";
					echo "					<div id='scroll21'>". $newTxt2."</div>";
					echo '				</td>';
					echo '			</tr>';
					echo '			<tr>';
					echo '              <td>
											<div id="div_showPos">
											<table>
												<tr>
													<td>
														<Label>Position left: </Label>
													</td>
													<td>
														<div id="showPos1"></div>
					                    			</td>			
													<td>
														<Label>Position right: </Label>
													</td>
													<td>
														<div id="showPos2"></div>
													</td>
												</tr>
											</table>
											</div>
										</td>	
									</tr>	
									<tr>
										<td align="right">
											<button type="button" class="navi_button"  id="upButton">Up</button> 
										</td>
										<td>
											
											<button type="button" class="navi_button"  id="downButton">Down</button>
										</td>
									</tr>
												
						 		</table>';
					echo '	</div>';
					echo '	</div>';
				}	

				/* Tabelle zum Anzeigen und Löschen der Positionen */
				$count = 0;
				$sql = '';
				$sql = "SELECT * FROM position WHERE storyID ='{$storyID}' ";
				$abfrage = mysqli_query($verbindung, $sql);	
				
				echo '<div id="posMenu">';
				echo '	<table id="tablePos" style="border-collapse:separate; display:inline-table">';
				echo '	<colgroup>
							<col width="90">
							<col width="90">
							<col width="30">
						  </colgroup>';	
				echo '	<thead>';	
				echo '		<tr background-color="#c6e2ff">';
				echo '			<td ><span class="posTabTit">Position 1</span></td><td><span class="posTabTit">Position 2</span></td><td><span class="posTabTit">delete</span></td>';
				echo '      </tr>';
				echo '	</thead>';
				echo '	<tbody>';
			while($row = mysqli_fetch_assoc($abfrage)){
				
				echo '  	<tr id="tr_'.$row['posID'].'" >';
				echo '			<td ><span class="posTable">'.$row['pos1'].'</span></td><td><span class="posTable">'.$row['pos2'].'</span></td><td><button id="bt_'.$row['posID'].'" class="delPosButton"></button></td>';
				echo '  	</tr>';
				$count++;
			}
				echo '	</tbody>';
				
				echo '	</table>';
				echo '</div>';	
				@mysql_close($verbindung);
			}
			else{
				// für den rebuild der beiden unteren scrolldivs, nach dem Positionen gelöscht wurden
				if(isset($_GET['storyIDrebuild'])){
					
					$storyID = $_GET['storyIDrebuild'];
					$sql = "SELECT * FROM story WHERE storyID ='{$storyID}' ";
					$abfrage = mysqli_query($verbindung, $sql);
					
					$txt1 = "";
					$txt2 = "";
					$id   = 0;
					
					while($row =  mysqli_fetch_assoc($abfrage)){
							
						// Bearbeitung der Texte um sie Mouseover-fähig zu machen
						$txt1 = explode(".", $row['text1']);
						$txt2 = explode(".", $row['text2']);
						
						$newTxt1 = '';
						$newTxt2 = '';			
								
						foreach ($txt1 as $key => $value) {
							$newTxt1.= '<div id="id_'.$id.'">'.htmlentities($value).'.</div>';
							$id++;
						}
						
						$id = 0;
						
						foreach ($txt2 as $key => $value) {
							$newTxt2.= '<div id="id_'.$id.'">'.htmlentities($value).'.</div>';
							$id++;
						}	
						
						echo '<table border="0">';
						echo '		<colgroup>
										<col width="530">
										<col width="530">
								  	</colgroup>';	
						echo '			<tr>';
						echo '				<td>';
						echo "					<div id='scroll11'>". $newTxt1."</div>";
						
						echo '				</td>';
						echo '				<td>';
						echo "					<div id='scroll21'>".$newTxt2."</div>";
						
						echo '				</td>';
						echo '			</tr>';
						echo '			<tr>';
						echo '              <td>
												<div id="div_showPos">
												<table>
													<tr>
														<td>
															<Label>Position left: </Label>
														</td>
														<td>
															<div id="showPos1"></div>
						                    			</td>			
														<td>
															<Label>Position right: </Label>
														</td>
														<td>
															<div id="showPos2"></div>
														</td>
													</tr>
												</table>
												</div>
											</td>	
										</tr>
										<tr>
										<td align="right">
											<button type="button" class="navi_button"  id="upButton">Up</button> 
										</td>
										<td>
											
											<button type="button" class="navi_button"  id="downButton">Down</button>
										</td>
									</tr>
									</table>';
									
					}
					@mysql_close($verbindung);
				}	
					
				}
			}
		}
	
	else{
		$data = '<br/>Fehler '.@mysqli_error($verbindung);
		echo $data;
	}
	
	function returnText($t1, $t2) {
		die(json_encode(array('text1' => $t1, 'text2' => $t2)));
	}

	function fail($message) {
		die(json_encode(array('status' => 'fail', 'message' => $message )));
	}

?>