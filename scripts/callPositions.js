
	/********************************************************************************************** 
	 *	 CallPositions.js enthält alle wesentlichen Funktionen der Parallelfenster.               *
	 *	 Es ladet über eine PHP-Datei alle Positionspärchen aus der DB und übergibt sie einem     *
	 *	 internen Array. Mit diesem Array wird gearbeitet, das heisst, daß die Event-Handler      *
	 *	 der Fenster bei dem scrollen aus diesem Array die Positionen entnehmen und darauf        *
	 *	 reagieren.  																			  *
	 *	 Sollen zwei neue Positionen hinzugefügt werden, wird das Positionspärchen                *
	 *	 zuerst an die PHP-Datei saveDelPosition.php übergeben und von dort aus in die DB einge-  *
	 *	 fügt. Rückgabewert der PHP-Datei ist die ID des Positionspärchens (posID). Diese posID   *
	 *	 wird mit den beiden Positionen anschließend in das internet Array  und in die            *
	 *   Tabelle eingetragen, in der alle Positionen dargstellt werden.                           *
	 *                                                                                            *
	 *   Soll ein Pos-Pärchen gelöscht werden wird die posID aus dem Array ausgelesen und an die  *
	 *   PHP-Datei übergeben, die dann die Löschung ausführt.                                     *
	 *   Aufgerufen von : selectStory.js                                                          *
	 * 																							  *
	 * ********************************************************************************************/
	
	var positions = new Array(); // positions Array wird global, also ausserhalb der document.ready deklariert
								 
	
$(document).ready(function ()
{	
	
	var posID = 0; // ( id der neu hinzugefügten Position und des neu hinzugefügten deleteButton )
	var count = 0; // Wird von den Buttons Up und Down hoch bzw runtergesetzt und holt die pos-Pärchen aus dem 
				   // positions-array
	
	// diese Funktion wird sofort, nach dem das script geladen wurde, ausgeführt und holt alle Positionen aus der DB
	$.getJSON("callPositions.php", {storyID: $("#idStory1").html()}, function(json){
	
		// ist die Anzahl der Positionen > 0
		if(json.positions.length > 0){
	
			// übergebe die Positionen an das internet Array
			positions = json.positions; 
			var count = 0;
			
			$.getScript('scripts/deletePosition.js');

			var pos1ForS1 = json.positions[ json.positions.length-1 ].pos1; // nehme das letzte Pärchen aus array
			var pos2ForS2 = json.positions[ json.positions.length-1 ].pos2;
			  
			// setze die Werte der Anzeige der erste beiden scrollDivs auf die letzte Position
			$('#show_pos1').html( pos1ForS1); 
			$('#show_pos2').html( pos2ForS2 );
			$('#scroll1').scrollTop(json.positions[ json.positions.length-1 ].pos1);
			$('#scroll2').scrollTop(pos2ForS2);
			$('#pos1').val(pos1ForS1); // Werte der input Felder
			$('#pos2').val(pos2ForS2);
			
			
			// die unteren beiden scrollDivs können ruhig auf 0 bleiben
			$('#show_pos11').html( 0 );
			$('#show_pos21').html( 0 );
			$('#scroll11').scrollTop(0);
			$('#scroll21').scrollTop(0);
			
		}else{// falls keine positionen für die ausgewählte Geschichte vorhanden sind
			
			// setze die Positionsanzeige der Scroll-divs auf 0
			$('#show_pos1').html( 0 );
			$('#show_pos2').html( 0 );
			
			$('#show_pos11').html( 0 );
			$('#show_pos21').html( 0 );
			
			// die beiden <input type="hidden"> müssen auch auf 0 gesetzt werden falls der User die beiden
			// ersten Positionen die ja 0 und 0 sind speichern möchte ohne vorher gescrollt zu haben, dann sind sie nämlich NaN
			$('#pos1').val(0);
			$('#pos2').val(0);
		}
	});
	
	// Handler des linken unteren Fenster
	$('#scroll11').scroll(function(){
		var scrolltop = $(this).scrollTop(); // nehme jetzige scrollPos und speicher in var scrolltop
		$('#show_pos11').html(scrolltop);    // zeige pos an
		if(positions.length != 0){
			// json Array durchlaufen und nach gleicher pos
			$.each(positions, function(i, v){
				if(scrolltop == v.pos1)	// falls gleiche pos vorhanden ist
				{
					$('#scroll21').scrollTop( v.pos2 ); // nehme pos2 und setze rechtes unteres Fenster auf diese pos
				}
			});	
		}
	});
	
	// der Handler des rechten unteren Fensters
	$('#scroll21').scroll(function(){
		$('#show_pos21').html( $(this).scrollTop() );
	});
	
	// Der Handler für den Up-Button 
	$('#upButton').click(function(){
		
		if(positions.length != 0){
			if(count > 0){
				count--;
				//alert(count);
				$('#scroll11').scrollTop(positions[count].pos1);
				$('#show_pos11').html(positions[count].pos1);
				
				$('#scroll21').scrollTop(positions[count].pos2);
				$('#show_pos21').html(positions[count].pos2);
			}	
		}
	});
	
	// Der Handler für den Down-Button
	$('#downButton').click(function(){
		if(positions.length != 0){
			//alert("length : "+positions.length);
			if(count < positions.length-1  ){
				count++;
				//alert(count);
				$('#scroll11').scrollTop(positions[count].pos1);
				$('#show_pos11').html(positions[count].pos1);
				
				$('#scroll21').scrollTop(positions[count].pos2);
				$('#show_pos21').html(positions[count].pos2);
			}
		}
	});
	
	/* die Positionen des oberen zwei Divs setzen */
	
	// wird der erste ScrollDiv gescrollt übergebe die Positionen an
	$('#scroll1').scroll(function(){
		$('#pos1').val( $(this).scrollTop() );       // den input type=hidden für das Formular
		$('#show_pos1').html( $(this).scrollTop() ); // und an den div zum Anzeigen der Positionen
	});
	           
	$('#scroll2').scroll(function(){
		$('#pos2').val( $(this).scrollTop() ); // 
		$('#show_pos2').html( $(this).scrollTop() );
	});
	
	/* Mouseover-Funktion */
	
	$("div[id^='id_']").mouseover(function() {
		//alert($(this).html());
	    //$('#log').append('<div>Handler for .mouseover() called.</div>');
		var currentId = $(this).attr('id');
		//$(this).css("background-color","yellow");
		$("div[id^='"+currentId+"']").css({"background-color":"#FFE1FF", "-moz-border-radius" : "10px","-khtml-border-radius":"10px"});
		//var s1_text = $('#scroll1').text(); // Textinhalt des scrollDivs
	}).mouseout(function(){
		var currentId = $(this).attr('id');
    	$("div[id^='"+currentId+"']").css({"background-color":"white", "padding":"1px"});
  	});
  	
  	
  	$('#saveButton').click(function(){
		
		// nehme Positionen 
		var pos1Value = $('#pos1').val();	// nehme Wert aus input type=hidden also aktuelle scroll-Position
		var pos2Value = $('#pos2').val();
		var storyID   = $("#idStory1").html();
		
		var posExists = false;
		
		if(positions.length != 0){
			
			// erstmal schauen ob neu hinzugefügte Positionen schon existieren
			$.each(positions, function(i, v){
				if(v.pos1 == pos1Value || v.pos2 == pos2Value){
					posExists = true;	// falls gefunden setze posExists auf 1
				}
			});
			
			// falls positionen nicht existieren
			if(posExists == false){
				sendPosToServer(storyID, pos1Value, pos2Value);
			}
			else{
				setErrorMessage('Position already exists');
			}
		}
		else{
			sendPosToServer(storyID, pos1Value, pos2Value);
		}
	});
  	
  	// übernimmt die zwei Positionen und sendet sie zum Server
  	function sendPosToServer(storyID, pos1, pos2){
  		
  		$.get("saveDelPosition.php", {storyID: storyID, pos1: pos1, pos2: pos2}, function(json){
  		
  			if(json.status == 'success')
  			{
  				posID = json.posID;
  				
  				positions.push({
					'posID': posID,
					'pos1' : pos1,
					'pos2' : pos2
				});	
				
				$('#tablePos tr:last').after('<tr id="tr_'+posID+'"><td><span class="posTable">'+pos1+'</span></td><td><span class="posTable">'+pos2+'</span></td><td><button id="bt_'+posID+'" class="delPosButton"></button></td></tr>');
				
				// script nochmal laden um Kontakt zum neu erstellten Element aufzunehmen
				$.getScript('scripts/deletePosition.js');
  			}
  			else
  			{
  				if(json.status == 'fail'){
  					if(json.message == '-1'){
  						setErrorMessage('Position 1 is already used!');
  					}else{
  						setErrorMessage('Position 2 is already used!');
  					}
  				}
  			}
  		}, 'json');
  	}

	function setErrorMessage(message){
		$('#errorMessage').html(message).hide().addClass('errorMessage').slideDown('slow');
		setTimeout(function () { 
			$('#errorMessage').html('');
			$('#errorMessage').removeClass();
		},2000);
	}	
/* **************************************************************
 * 											     			    * 
 *   Funktionen ohne Einsatz weil der Code verändert wurde      *
 * 	 und sie damit unbrauchbar geworden sind.					*
 *                                                              *
 * **************************************************************/

  	// beim Drücken werden Positionen in die DB gespeichert
  	// $('#save_to_db_button').click(function(){
  		// positions = $.parseJSON(positions);
  		// $.get("savePositions.php", {storyID: $("#idStory1").html(), positions: positions}, function(data){
  			// alert(data);
  		// });
  	// });
  	

  	// generiert random id für die neu hinzugefügte Position. Werden Positionen in DB gespeichert
  	// werden für die neu hinzugefügten IDs, neue IDs von der DB generiert. 
  	function generateRandomID(){
  		var found_or_not = 0;	// gibt an ob zufällig generierte 
		
		// generiere zufällige ID für den deleteButton
		randomID   = parseInt( Math.random() * (1000) );	
		
		// suche in Array nach zufällig generierter ID
		$.each(positions, function(i, v){
			if(v.posID == randomID){
				found_or_not = 1;	// falls gefunden setze fount_or_not auf 1
			}
		});	
		
		// falls gefunden nochmal funktion aufrufen
		if(found_or_not == 1){
			generateRandomID();
		}else{
			return randomID;	// sonst ID zurückgeben
		}
  	} 
  	
});	
	
