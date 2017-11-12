
	
	/*******************************************************************************************************
	 *																									   *
	 * 	  vokabel1.js deswegen, weil bei dem Drücken des "add new vocabel" buttons, der Inhalt             * 
	 *    dynamisch erzeugt und in die vokabeltraining.php eingefügt wird.                                 *
	 *    Deswegen müssen anschließend die jQuery-Methoden geladen werden, die auf die Elemente            *
	 *    zugreifen können.                                                                                *
	 *    Würden diese jQuery-Methoden in der vokabel.js stehen, wären die Methoden, die auf die           *          
	 *    DOM-Elemente zugreifen eher da, als die Elemente selber. Das heisst, dass nach der Erzeugung     *
	 *    der DOM-Element die Funktionen in der Luft hängen würden und nicht auf sie zugreifen könnten.    *
	 *    Es müssen immer zuerst die Elemente erzeugt und anschließend die JS-Methoden darauf geladen      *
	 *    werden.                                  														   *
	 * 	  																								   *
	 * *****************************************************************************************************/
	

$(document).ready( function() {
	
	// Standart-Submit ausschalten damit jQuery den submit übernimmt
	$("#saveWordForm").submit(function(){
    	return false;	
    });
    
    //Bei click werden eingebene Wörter und ausgewählte Sprachen zum Server gesendet
	$('#sub1_w').click(function(){
		var errorMessage = '';
		
		/* Validierung der Werte */
		// 1. Select Validierung
		if($('#sel_1').val()==''){
			errorMessage = 'Please select a language!';
		}else{
			if($('#sel_2').val()==''){
				errorMessage = 'Please select a language!';
			}
			else{
				if(  $('#sel_1').val()== $('#sel_2').val() ){
					errorMessage = 'You must choose two different languages.';
				}
				else{
					// input text Validierung
					if( ($('#input_w1').val() == '') || ($('#input_w2').val() == '') ){
						errorMessage = 'Please enter a word.';
					}
				}
			}
		}
		
		// falls keine Fehler vorhanden sind
		if(errorMessage==''){
			var data = $("#saveWordForm").serializeArray();
			
			$.get('vokabel.php', data, function(data){
				
				var errMessage = '';
				var successMessage = '';
				if(data == 'success'){
					successMessage = 'The vocables were added successfully'
				}
				else{
					if(data==1){
					errMessage = 'There is a problem with the server!'
					}else{
						if(data == 2){
							errMessage = 'An error has occured!';
						}
						else{
							if(data == 3){
								errMessage = 'These two vocables are already existing in database.'
							}else{
								// kann nur die mysqli_error($verbindung) Fehlermeldung sein
								errMessage = data;
							}
							
						}
					}
				}
				
				if(successMessage != ''){
					// $('#err').removeClass("flop");
					// $('#saveWordForm').append('<br/><span id="err" ></span>');
					// $('#err').addClass("success");
					// $('#err').show();
					// $('#err').html(successMessage);
// 					
// 					
					// setTimeout(function () { $('#err').hide();},2000);
					// successMes = '';
					showSuccessMessage(successMessage);
					
				}else{
					
					if(errMessage!=''){
						showErrorMessage(errMessage);
						// $('#err').removeClass("success");
						// $("#saveWordForm").append('<br/><span id="err" ></span>');
						// $('#err').addClass("flop");
						// $('#err').show();
						// $('#err').html(errMessage);
// 						
						// setTimeout(function () { $('#err').hide();},2000);
						// errMessage = '';
					}
				}
			});
		}else{
			showErrorMessage(errorMessage);
		}
	});
	
	function showErrorMessage(message){
		$('#savedOrNOt').html(message).addClass('flop');
		setTimeout(function () { $('#savedOrNOt').html('').removeClass('flop');},2000);
	}
	
	function showSuccessMessage(message){
		$('#savedOrNOt').html(message).addClass('success');
		setTimeout(function () { $('#savedOrNOt').html('').removeClass('success');},2000);
	}
	
	
	
	
});