$(document).ready( function() {
	
	// User wählt Story zum lesen aus
	$('#storyID').change(function()
	{
		// wenn die storyID einen Wert hat 
		if($(this).val() != '')
		{
			
			// storyID : ist die id des dropDown-Menues
			// $(this).val() : ist der value
			$.get('buildStoryUserSelect.php',{storyID: $(this).val() },function(data){
				
				// füge die zurückgegebenen Daten in das Element mit der id=savePos ein
				$('#storyWindows').html(data);
				
				// setze die Positionsanzeige der Scroll-divs auf 0
				$('#show_pos11').html( 0 );
				$('#show_pos21').html( 0 );
				
				// holt die gespeicherten Positionen um sie in die scrollDivs zu setzen
				// und zeigt damit an wie sich die Scrolls bei den gesetzte
				$.getScript('scripts/callPositions.js');
				
				// lade das script savePositions.js welches die gewählten Positionen speichert
				//$.getScript('scripts/saveDelPositions.js');
			});
		}
	});	
	
	
	

});