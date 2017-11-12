
	/* Die eigentliche Funktion.
	 * 
	 * Zeigt die Positionen der ersten beiden Scroll-Divs
	 * 
	 * Ruft getPositions.php auf welches ein JSON Objekt mit allen 
	 * Positionspärchen aus der zurückgibt, welche aus der DB ausgelesen wurden.
	 * Markiert den Satz über den sich jeweils der Mauszeiger befindet. */
$(document).ready(function ()
{	
	//alert("call Positions.js wird aufgerufen")
	$.getJSON("callPositions.php", {storyID: $("#idStory1").html()}, function(json){
		
		//alert("success Funktion der call Positions.js");
		//alert(json.positions[1].pos1+"   length "+json.positions.length);
		if(json.positions.length > 0){
			
			var count = 0;
			// alert("Die zurückgebenen Positionen aus der Datenbank.");
			// var count=0;
			// $.each(json.positions, function(i, v){
// 				
				// alert(count+")  "+v.pos1+"  "+v.pos2);
				// count++;
			// });
// 			
			

			// alert("Anzahl der Positionen : "+count);
			 
			 var pos1ForS1 = json.positions[ json.positions.length-1 ].pos1; // nehme das letzte Pärchen aus array
			 var pos2ForS2 = json.positions[ json.positions.length-1 ].pos2;
			  
			 // setze die Werte der Anzeige der erste beiden scrollDivs auf die letzte Position
			//$('#show_pos1').hide().html( json.positions[ json.positions.length-1 ].pos1).fadeIn('slow'); 
			$('#show_pos1').html( pos1ForS1); 
			$('#show_pos2').html( pos2ForS2 );
			$('#scroll1').scrollTop(json.positions[ json.positions.length-1 ].pos1);
			$('#scroll2').scrollTop(pos2ForS2);
			$('#pos1').val(pos1ForS1);
			$('#pos2').val(pos2ForS2);
			
			
			// die unteren beiden scrollDivs können ruhig auf 0 bleiben
			$('#showPos1').html( 0 );
			$('#showPos2').html( 0 );
			$('#scroll11').scrollTop(0);
			$('#scroll21').scrollTop(0);
			
			// Handler für linken unteres Fenster
			$('#scroll11').scroll(function(){
				var scrolltop = $(this).scrollTop(); // nehme jetzige scrollPos und speicher in var scrolltop
    			$('#showPos1').html(scrolltop);      // zeige pos an
    			
    			// json Array durchlaufen und nach gleicher pos
				$.each(json.positions, function(i, v){
					
					if(scrolltop == v.pos1)	// falls gleiche pos vorhanden ist
					{
						$('#scroll21').scrollTop( v.pos2 ); // nehme pos2 und setze rechtes unteres Fenster auf diese pos
					}
				});
			});
			
			// der Handler des rechten unteren Fensters
			$('#scroll21').scroll(function(){
				$('#showPos2').html( $(this).scrollTop() );
			});
			
			// Der Handler für den Up-Button 
			$('#upButton').click(function(){
				if(count > 0){
					count--;
					//alert(count);
					$('#scroll11').scrollTop(json.positions[count].pos1);
					$('#showPos1').html(json.positions[count].pos1);
					
					$('#scroll21').scrollTop(json.positions[count].pos2);
					$('#showPos2').html(json.positions[count].pos2);
				}
			});
			
			// Der Handler für den Down-Button
			$('#downButton').click(function(){
				if(count < json.positions.length-1  ){
					count++;
					//alert(count);
					$('#scroll11').scrollTop(json.positions[count].pos1);
					$('#showPos1').html(json.positions[count].pos1);
					
					$('#scroll21').scrollTop(json.positions[count].pos2);
					$('#showPos2').html(json.positions[count].pos2);
				}
			});
			
		}else{// falls keine positionen gesetzt wurden
			// setze die Positionsanzeige der Scroll-divs auf 0
			$('#show_pos1').html( 0 );
			$('#show_pos2').html( 0 );
			
			$('#showPos1').html( 0 );
			$('#showPos2').html( 0 );
			
			// die beiden <input type="hidden"> müssen auch auf 0 gesetzt werden falls der User die beiden
			// ersten Positionen die ja 0 und 0 sind speichern möchte ohne vorher gescrollt zu haben, dann sind sie nämlich NaN
			$('#pos1').val(0);
			$('#pos2').val(0);
		}
	});
	
	/* die Positionen des oberen zwei Divs setzen */
	
	// wird der erste ScrollDiv gescrollt übergebe die Positionen an
	$('#scroll1').scroll(function(){
		
		$('#pos1').val( $(this).scrollTop() );       // den input type=hidden für das Formular
		$('#show_pos1').html( $(this).scrollTop() ); // und an den div zum Anzeigen der Positionen
	});
	
	// wenn            
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
});	
	
