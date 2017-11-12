$(document).ready( function() {
	
	alert("buildDropDownMenu2.js")
	
	$.get('buildStorySelect.php',function(data){
		
		if(data=='Damn... Anscheinend gibt es ein Problem mit der Verbindung zur Datenbank!'){
			
			$("#menuSelect").addClass("error");
			$("#menuSelect").html(data);
		}else{
			
			$("#menuSelect").html(data);
		}
	});
	
	
	
	
	
	
	
});