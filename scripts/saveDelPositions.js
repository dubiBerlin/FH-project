$(document).ready(function ()
{	
	//alert("saveDelPosition.js wird aufgerufen.");
	$('#saveButton').click(function(){
		//alert("savebutton wurde geklickt");
		
		// nehme alle input Felder die sich innnerhalb von der id=addRunner befinden
		// und serialisiere sie (füge sie in ein Array ein)
		var data = $("#savePosForm input").serializeArray();
		
		
		// in $("#savePosForm").attr('action')befindet sich die URL des Servers
		$.get( 'saveDelPosition.php', data, function(data){
			//alert("data "+data);
			// if(json.status == "fail")
			// {
				// //alert(json.message);
			// }
			// if(json.status == "success")
			// {
				// // übergebe die erhaltenen Positionen und die dazugehörige ID an Variablen
				// //alert("posID = "+json.posID);
				// var posID = json.posID;
				// var pos1  = json.pos1;
				// var pos2  = json.pos2;
				// //alert(pos1+" "+pos2+" wurden gespeichert");
				// // füge den neuen Button in die Tabelle ein mit den Positionen
// 				
				// //$('#tablePos tr:last').after('<tr id="tr_'+posID+'"><td><span class="posTable">'+pos1+'</span></td><td><span class="posTable">'+pos2+'</span></td><td><button id="bt_'+posID+'" class="delPosButton"></button></td></tr>').slideDown("slow");
				// // $.getScript('scripts/callPositions.js');
				// // $.getScript('scripts/saveDelPositions.js');
				// // erstelle die gesammte
				buildScrollDivs($('#idStory1').html());
//				buildScrollDivs(json.storyID);
			// }
		}); 
		
	});
	
	// $('#saveButton').click(function(){
		// alert("savebutton wurde geklickt");
// 		
		// // nehme alle input Felder die sich innnerhalb von der id=addRunner befinden
		// // und serialisiere sie (füge sie in ein Array ein)
		// var data = $("#savePosForm input").serializeArray();
		// alert(data[0]);
// 		
		// // in $("#savePosForm").attr('action')befindet sich die URL des Servers
		// $.get( 'saveDelPosition.php', data, function(json){
// 			
			// if(json.status == "fail")
			// {
				// //alert(json.message);
			// }
			// if(json.status == "success")
			// {
				// // übergebe die erhaltenen Positionen und die dazugehörige ID an Variablen
				// //alert("posID = "+json.posID);
				// var posID = json.posID;
				// var pos1  = json.pos1;
				// var pos2  = json.pos2;
				// //alert(pos1+" "+pos2+" wurden gespeichert");
				// // füge den neuen Button in die Tabelle ein mit den Positionen
// 				
				// //$('#tablePos tr:last').after('<tr id="tr_'+posID+'"><td><span class="posTable">'+pos1+'</span></td><td><span class="posTable">'+pos2+'</span></td><td><button id="bt_'+posID+'" class="delPosButton"></button></td></tr>').slideDown("slow");
				// // $.getScript('scripts/callPositions.js');
				// // $.getScript('scripts/saveDelPositions.js');
				// // erstelle die gesammte
				// buildScrollDivs(json.storyID);
			// }
		// }, "json"); 
// 		
	// });
	
	// die standart submit Fkt des Submit-Buttons wird auf false gesetzt
    // damit sich jQuery um das Senden der Daten zum Server kümmern kann.
    $("#savePosForm").submit(function(){
    	return false;	
    });
	
	// Buttons aus der Tabelle zum löschen einer Position	
	$("button[id^='bt_']").click(function() {
		var id = parseInt(this.id.replace("bt_", ""));
	    deletePosition(id);
	   //rebuiltScrollDivs();
	});	
		
	
});
	
	// Funktion zum Löschen einer Position
	function deletePosition(value){
		
		var returnValue = '';
		//var storyID = 
		
		
		$.get("saveDelPosition.php", {posID: value}, function (data){
			if(data == 1){
				//return 1;
				//alert("Positionen wurden gelöscht posID = "+value);
				//$.getScript('scripts/callPositions.js');
				buildScrollDivs($('#idStory1').html());
				$('#tr_'+value).hide('slow', function(){ $('#tr_'+value).remove(); });
				//rebuiltScrollDivs();
			}
		});
	}
	
	// beim Löschen oder Hinzufügen einer Position die gesamten Scrolldivs nochmal bauen
	function buildScrollDivs(storyID){
		
		// hole dir die ScrollPosition des Browsers
		var windowPos = $(window).scrollTop();
		
		
		$.get('scrollBuilders.php',{storyID: storyID, scrollPos: $(window).scrollTop() },function(data){
				
			// füge die zurückgegebenen Daten in das Element mit der id=savePos ein
			//$('#savePos').hide().html(data).fadeIn('slow');
			$('#savePos').html(data);
			
			// hole die Position
			$(window).scrollTop($('#posBrowser').val());
			
			
			//$('#posMenu').hide().fadeIn('slow');
			/* diesen Teil in callPositions.js eingefügt */
			
			// // setze die Positionsanzeige der Scroll-divs auf 0
			// $('#show_pos1').html( 0 );
			// $('#show_pos2').html( 0 );
// 			
			// $('#showPos1').html( 0 );
			// $('#showPos2').html( 0 );
// 			
			// // die beiden <input type="hidden"> müssen auch auf 0 gesetzt werden falls der User die beiden
			// // ersten Positionen die ja 0 und 0 sind speichern möchte ohne vorher gescrollt zu haben, dann sind sie nämlich NaN
			// $('#pos1').val(0);
			// $('#pos2').val(0);
			
			// holt die gespeicherten Positionen um sie in die scrollDivs zu setzen
			// und zeigt damit an wie sich die Scrolls bei den gesetzte
			$.getScript('scripts/callPositions.js');
			
			// lade das script savePositions.js welches die gewählten Positionen speichert
			$.getScript('scripts/saveDelPositions.js');
		});
	}
	
	// die unteren beiden Scrolldivs müssen wieder hergestellt werden, damit sie nach dem Löschen der Positionen nicht mehr darauf reagieren
	function rebuiltScrollDivs(){
		//alert("Übergebene storyID = "+$('#idStory1').html() );
		// als Parameter die storyID der Geschichte mitgeben
		$.get('scrollBuilders.php',{storyIDrebuild: $('#idStory1').html() },function(data){
			
			$('#sDivs2').html(data);
			// lade das script savePositions.js welches die gewählten Positionen speichert
			$.getScript('scripts/saveDelPositions.js');
			
			// holt die gespeicherten Positionen um sie in die scrollDivs zu setzen
			// und zeigt damit an wie sich die Scrolls bei den gesetzte
			$.getScript('scripts/callPositions.js');
			//$.getScript('scripts/buildPosTable.js');
			//$.getScript('scripts/saveDelPositions.js');
			$('#showPos1').html( 0 );
			$('#showPos2').html( 0 );
		});
	}