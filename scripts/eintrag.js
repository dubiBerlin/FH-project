$(document).ready( function() {
	
	$.getJSON("eintrag.php", {yes: 'aha'}, function(json){
		
		if(json.status == 'fail'){
			alert(json.message);
		}
		else{
			if(json.eintraege.length > 0){
				
				$.each(json.eintraege, function(i, v){
					
					$('<br/><div id="zeig_eintrag" ><div id="date_time"><strong>Written on : '+v.date+' at: '+v.time+' o`clock</strong></div><br/>'+v.text+'</div>').prependTo('#after').hide().slideDown('slow');
					
				});
			}
		}
	});
	
	$("#eintrag_form").submit(function(){
    	return false;	
    });
    
	$("#addEintragButton").click(function(){
		if($("#user_eingabe").val() == '' || $("#user_eingabe").val() == 'Hier die Eingabe ...'){
			$("#eintrag_form").append('<br/><span id="err" ></span>');
			$("#err").css({color: '#FF0000', 'font-weight' : 'bolder', 'background-color' : 'yellow'}) ;
			$('#err').show();
			$('#err').html("Bitte Text eingeben!");
			setTimeout(function () { $('span').hide();},3000);
		}else{
			var userEingabe = $("#user_eingabe").val();
			var data1 = $("#eintrag_form").serializeArray();
			
			$.get('eintrag.php', data1, function(json){
				
				if(json.status == 'success'){
					
					$('<br/><div id="zeig_eintrag" ><div id="date_time"><strong>Written on : '+json.date+' at: '+json.time+' o`clock</strong></div><br/>'+json.message+'</div>').prependTo('#after').hide().slideDown('slow');
					
					$('#user_eingabe').val('');	

					count++;
					
				}else{
					if(json.status == 'fail'){
						
						
						$("#eintrag").append('<br/><span id="err" ></span>');
						$("#err").css({color: '#FF0000', 'font-weight' : 'bolder', 'background-color' : 'yellow'}) ;
						$('#err').show();
						$('#err').html(json.message);
						
						setTimeout(function () { $('span').hide();},3000);
					}
				}
			}, "json");
		}
	});
	
	// wenn der User in das TextArea klickt soll der innere Text verschwinden
	$("#user_eingabe").focus(function () {
         if($("#user_eingabe").val() == 'Hier die Eingabe ...'){
			$("#user_eingabe").val('');
		}
    });

});