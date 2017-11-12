$(document).ready( function() {
			
	$('#newL').click(function(){
		 $("#neueSprache").slideToggle("slow");
	});
	
	$('#newW').click(function(){
		 $("#neuesWort").slideToggle("slow");
		 createAddVocabelForm();
	});
	
	$('#newT').click(function(){
		 $("#vokTest").slideToggle("slow");
	});
	
	$('#newS').click(function(){
		 $("#editVok").slideToggle("slow");
	});
	// die standart submit Fkt des Submit-Buttons wird auf false gesetzt
    // damit sich jQuery um das Senden der Daten zum Server kümmern kann.
    $("#f1_form").submit(function(){
    	return false;	
    });
    
    (function($){
			var autoOpts = {
				minLength: 1,
				source: [
					{ value: "Arabic", label: "ARA" },
					{ value: "German", label: "GER" },
					{ value: "English", label: "EN" },
					{ value: "French", label: "FR" },
					{ value: "Spanish", label: "SP" },
					{ value: "Portuguese", label: "PO" },
					{ value: "Chinese", label: "CH" },
					{ value: "Italian", label: "ITA" },
					{ value: "Swedish", label: "SWE" },
					{ value: "Norwegian", label: "NOR" },
					{ value: "Japanese", label: "JPN" },
					{ value: "Russian", label: "RU" }
					]
				};
			$("#i1").autocomplete(autoOpts);
		})(jQuery);

	var languages = [ 'German', "English", 'Spanish', "Portuguese", "Russian" ,"Chinese" ,"French", "Italian", "Arabic",
						"Swedish", "Norwegian", "Japanese"];

	// Bei click wird eingebene Sprache zum Server gesendet
	$('#addNewLangButton').click(function(){
		$.getScript('scripts/vokabel1.js');
		
		if($("#i1").val()!='')
		{
			if( jQuery.inArray($("#i1").val(), languages)!= -1 ){ // ist der Wert im Array languages vorhanden?
				
				var data1 = $("#f1_form input").serializeArray();
				$.get($("#f1_form").attr('action'), data1, function(data){
					var errorMes = '';
					var successMes = '';
					
					if(data != 'success'){
						if(data=='1'){
							errorMes = 'No connection to server';
						}
						else{
							if(data == '2'){
								errorMes = 'This language already exists in database';
							}else{
								errorMes = data; // echo mysqli_error($verbindung);
							}
						}
					}else{
						successMes = 'The language was added successfully.';
					}
					
					if(errorMes!=''){
						$('#f1_form').append('<div id="message"></div>');
						$('#message').css({color: '#FF0000', 'font-weight' : 'bolder', 'background-color' : 'yellow'});
						$('#message').html(errorMes);
						$('#message').show();
						$('#i1').focus();
						setTimeout(function () { $('#message').remove();},3000);
						errorMes = '';
					}else{
						if(successMes!=''){
							$('#f1_form').append('<div id="message"></div>');
							$('#message').addClass("success");
							$('#message').show();
							$('#message').html(successMes);
							$('#i1').focus();
							createAddVocabelForm();
							setTimeout(function () { $('#message').remove();},3000);
							successMes = '';
						}
					}
				});
			}
			else{
				$('#f1_form').append('<div id="message"></div>');
				$('#message').css({color: '#FF0000', 'font-weight' : 'bolder', 'background-color' : 'yellow'});
				$('#message').show();
				$('#message').html('<span>Please choose one of the suggested values!</span>');
				$('#i1').focus();
				setTimeout(function () { $('#message').remove();},3000);
			}
		}
		else
		{
			$('#f1_form').append('<div id="message"></div>');
			$('#message').css({color: '#FF0000', 'font-weight' : 'bolder', 'background-color' : 'yellow'});
			$('#message').show();
			$('#message').html('<span>Whats wrong with you?</span>');
			$('#i1').focus();
			setTimeout(function () { $('#message').remove();},3000);
		}
	});
	
	// In Funktion verpackt weil mehrfach verwendet. zb bei dem Einfügen einer neuen Sprache.
	// Wird eine neue Sprache eingefügt erscheint sie auch in den Select-Boxen.
	function createAddVocabelForm(){
		$.get('vokabel.php', {a: 'buildSelectLanguages' },  function(data){
			$("#neuesWort").html(data);
			$.getScript('scripts/vokabel1.js');
		});
	}	

	
	
	/* **********************************************
	 *                                              *
	 *    !_______Alles zum Vokabeltest______!      *
	 *                                              *
	 * **********************************************/
	
	/*
	 *	 Die erste Version des Vokabeltests:Das DropDown Menu hat ein zufälliges Wort von der PHP-Datei zurückbekommen. 
	 *	 Die eingegebene Übersetzung des Users wurde mit dem $('#vT_submit') Button wieder zum Server geschickt, auf 
	 *   dem die Überprüfung der Eingabe stattfand.
	 * 
	 * */
	
	
	// standart-submit ausschalten. vT_form = vokabeltest
    // $("#vT_form").submit(function(){
    	// return false;	
    // });


	// dropDwonMenu
	// $('#testlangID').change(function()
	// {
		// // wenn die testlangID einen Wert hat 
		// if($(this).val() != '')
		// {
			// // testlangID : ist die id des dropDown-Menues
			// // $(this).val() : ist der value
			// $.get('vokabel.php',{testlangID: $(this).val() },function(json){
				// //alert(json.text);
// //				json.status == "success"
				// if(json.status == "success"){
// 					
					// $('#vT_div').html(json.text);    // füge Wort in div ein zur Anzeige
					// $('#vT_input1').val(json.text);  // Wort in hidden für submit
					// $('#vT_input2').val(json.wordID);// Wort id mit rein  
					// $('#vT_input4').val(json.languageID); // die laguageID der User-Eingabe
				// }else{
					// $('#vT_message').html('An error occured!');
				// }
			// }, "json");
		// }
	// });
	
	// Submit button um eine Vokabel los zu schicken, die der User eingegeben hat
	// $('#vT_submit').click(function(){
// 	
		// // falls keine Vokabel im Eingabefeld vorhanden
		// if($('#vT_input3').val()!= ''){
			// if(($('#vT_input2').val()!='') && ($('#vT_input1').val()!='')){
// 				
				// var data = $("#vT_form").serializeArray();
// 				
				// $.get('vokabel.php', data, function(json){
					// alert(json.trueOrNot);
					// if(json.trueOrNot == 'right'){
						// $('#vT_message').html('<font color="#7B68EE" font weight>'+json.trueOrNot+"</font>");
						// $('#vT_div').html(json.text);// füge Wort in div ein zur Anzeige
						// $('#vT_input1').val(json.text); 
						// $('#vT_input2').val(json.wordID);
						// $('#vT_input4').val(json.languageID);
						// $('#vT_input3').val('');
						// setTimeout(function () { $('#vT_message').html('');},2000);
					// }
					// else{
						// if(json.trueOrNot == 'false'){
							// $('#vT_message').html('<font color="#FF0000">'+json.trueOrNot+'</font>');
							// setTimeout(function () { $('#vT_message').html('');},2000);
// 							
						// }	
					// }
					// // if(json.status == "success"){	
						// // $('#vT_div').html(json.text);// füge Wort in div ein zur Anzeige
						// // $('#vT_input1').val(json.text); 
						// // $('#vT_input2').val(json.wordID);  // Wort in hidden für submit
					// // }else{
						// // $('#vT_message').html('An error occured!');
					// // }
// 					
				// }, "json");
			// }
		// }else{
			// $('#vT_message').html('<font color="#FF0000">What is the translation?</font>');
			// setTimeout(function () { $('#vT_message').html('');},2000);
		// }
	// });
	
	/*********************************************************************************************************
	 *                                         																 *
	 *                                         																 *
	 *       Clientseitiger Vokabeltest, Komplette Änderung des Vokabeltests:                                *
	 * 		 																								 *
	 *       Die Auswertung und zufällige Auswahl einer Vokabel wird jetzt in der JS-Datei durchgeführt	     *
	 * 		 Das einzige was der Server macht ist alle Vokabeln zurückzugeben.                               *
	 * 																	       					             *
	 * *******************************************************************************************************/
	
	var vocables     = new Array(); // bekommt das Array von Vokabeln zugewiesen, welches PHP im change()-Event zurückgibt
	var nrOfVocables = 0;           // Anzahl der Datensätze
	var randomWord   = 0;           // Zufällige Zahl zur Auswahl eines Datensatzes aus dem zurückgegebenem Array
	var w1_or_w2     = 0;           // Random Zahl 0 -> word1 oder 1 -> word2
	var choosenWord  = '';			// das ausgewählt Wort
	var translation  = '';			// die dazugehörige Übersetzung
	var user_input   = '';          // nimmt die Vokabel auf, die der User eingegeben hat
	
	//dropDownMenu nochmal für den clientseitigen Fall
	$('#testlangID').change(function()
	{
		// wenn die testlangID einen Wert hat 
		if($(this).val() != '')
		{
			// testlangID : ist die id des dropDown-Menues
			// $(this).val() : ist der value
			$.getJSON('vokabel.php',{testlangID: $(this).val() },function(json){
				
				if(json.vocables.length != 0){
					
					vocables     = json.vocables; // übergebe Array an globales script array
					nrOfVocables = json.vocables.length; // merke Anzahl der Datensätze - 1
					
					getRandomVoc(); // wählt zufällige Vokabel und setzt sie in dafür vorhergesehene divs
					
					//$('#vT_message').html("random : "+randomWord+") "+choosenWord+" : "+translation);
					//$('#vT_message').html("random : "+r+") "+json.vocables[r].word1+" : "+json.vocables[r].word2);
				}
				else{
					$('#vT_message').html('No vocables available!');
				}
				
			});
		}
	});
	
	
	$('#vT_submit').click(function(){
		if(vocables.length!=0){
			if($('#vT_input3').val()!= ''){
				user_input = $('#vT_input3').val();
				
				if(compareVoc(user_input)==1){
					$('#vT_message').html('<font color="#104E8B">Correct</font>');
					setTimeout(function () { $('#vT_message').html('');},2000);
					$('#vT_input3').focus();
					getRandomVoc();
				}else{
					$('#vT_message').html('<font color="#FF0000">False</font>');
					$('#correctAnswer').html('<strong>'+translation+'</strong>');
					setTimeout(function () { 
						$('#vT_message').html('');
						$('#correctAnswer').html('');
					},2000);
					$('#vT_input3').val(''); // user eingabe im input Feld wieder zurücksetzen 
					$('#vT_input3').focus();
					getRandomVoc();
				}
				
			}else{
				$('#vT_message').html('<font color="#FF0000">Please enter the translation!</font>');
				setTimeout(function () { $('#vT_message').html('');},2000);
			}
			// var count = 0;
			// var takeAll = '';
// 			
			// $.each(vocables, function(i, v)
			// {
				// takeAll = takeAll + "<br/>count: "+count+" ) " +v.word1+"  "+v.word2;
				// count++;
			// });
			
			//$('#vT_message').html('<br/>'+vocables[0].word1);
			$('#vT_message').html(takeAll);
			
			count = 0;
				
			var random = Math.random();
			
		}
		else{
			$('#vT_message').html('<font color="#FF0000">No languages selected!</font>');
			setTimeout(function () { $('#vT_message').html('');},2000);
		}
	});
	
	function compareVoc(userInput){
		//alert("userInput = "+userInput+"  translation = "+translation);
		
		var trueOrNot = '';
		
		if(userInput == translation){
			return 1;	// richtig
		}else{
			// falls andere Eingabe getätigt als gesucht, schaue nach ob es mit anderer Übersetzung übereinstimmt
			if(w1_or_w2 == 0){	// word1 ist gesetzt und word2(Übersetzung) ist gesucht
				$.each(vocables, function(i, v)
				{
					if(v.word2 == userInput && v.word1 == choosenWord){
						//alert('1) v.word2 == userInput && v.word1 == choosenWord');
						trueOrNot =  1;
					}
				});
				if(trueOrNot == 1){
					return 1;
				}
				//alert("1) sRAUS VERDAMMT!");
				
			}else{
				if(w1_or_w2 == 1){ // word2 gesetzt word1 gesucht
					$.each(vocables, function(i, v)
					{
						if(v.word1 == userInput && v.word2 == choosenWord){
							//alert('v.word1 == userInput && v.word2 == choosenWord');
							trueOrNot =  1;
						}
					});
					if(trueOrNot == 1){
						return 1;
					}
					return -1;
				}
			}
		}
	}
	
	function getRandomVoc(){
		randomWord   = parseInt( Math.random() * (1 + nrOfVocables) );	// 
		w1_or_w2     = parseInt( Math.random()* 2 ); 					// word1 oder word2  
		
		if(w1_or_w2 == 0){
			choosenWord = vocables[randomWord].word1;
			translation = vocables[randomWord].word2;
		}else{
			if(w1_or_w2 == 1){
				choosenWord = vocables[randomWord].word2;
				translation = vocables[randomWord].word1;
			}
		}
		
		$('#vT_div').html(choosenWord);
	}	
});