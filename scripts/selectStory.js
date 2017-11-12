$(document).ready(function ()
{
	
	/* Wird eine Geschichte aus dem DropDown Menu gewählt (prepareStory.php), überträgt diese
	 * JS-Datei die Daten (in dem Fall die storyID) zum Server. */
	
	$('#storyID').change(function()
	{
		// wenn die storyID einen Wert hat 
		if($(this).val() != '')
		{
			
			// storyID : ist die id des dropDown-Menues
			// $(this).val() : ist der value
			$.get('scrollBuilders.php',{storyID: $(this).val() },function(data){
				
				// füge die zurückgegebenen Daten in das Element mit der id=savePos ein
				$('#savePos').html(data);
				
				// setze die Positionsanzeige der Scroll-divs auf 0
				$('#show_pos1').html( 0 );
				$('#show_pos2').html( 0 );
				
				$('#show_pos11').html( 0 );
				$('#show_pos21').html( 0 );
				
				// die beiden <input type="hidden"> müssen auch auf 0 gesetzt werden falls der User die beiden
				// ersten Positionen die ja 0 und 0 sind speichern möchte ohne vorher gescrollt zu haben, dann sind sie nämlich NaN
				$('#pos1').val(0);
				$('#pos2').val(0);
				
				// holt die gespeicherten Positionen um sie in die scrollDivs zu setzen
				// und zeigt damit an wie sich die Scrolls bei den gesetzte
				$.getScript('scripts/callPositions.js');
				
				// lade das script savePositions.js welches die gewählten Positionen speichert
				//$.getScript('scripts/saveDelPositions.js');
			});
		}
	});	
});