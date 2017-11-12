<?php
	
	include "dbConnection.php";
	
if(isset($_GET['storyID'])){
	
	$txt1 = "";
	$txt2 = "";
	$id   = 0;
	
	$storyID = $_GET['storyID'];
			
	$sql = "SELECT * FROM story WHERE storyID ='{$storyID}' ";
	$abfrage = mysqli_query($verbindung, $sql);
	
	
	
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
										<div id="show_pos11"></div><!--alte id = showPos1 -->
	                    			</td>			
									<td>
										<Label>Position right: </Label>
									</td>
									<td>
										<div id="show_pos21"></div><!-- alte id = showPos2 -->
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
					<tr>
					    <td>
	      				    <div id="idStory1">'.$row['storyID'].'"</div>
			            </td>
			        </tr>			
		 		</table>';
	echo '	</div>';
	
	}
}

?>