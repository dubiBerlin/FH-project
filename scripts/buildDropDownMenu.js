$(document).ready( function() {
	
	$.get('buildStorySelect.php',function(data){
		
		if(data=='Damn... Anscheinend gibt es ein Problem mit der Verbindung zur Datenbank!'){
			alert(data);
			$("#menuSelect").addClass("error");
			$("#menuSelect").html(data);
		}else{
			
			$("#menuSelect").html(data);
		}
		$.getScript('scripts/selectStory.js');
	});	
	
});