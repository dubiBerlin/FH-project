

	/************************************************************************
	 *                                                                      *
	 *	Dieses script enthält die Funktion zum Löschen zweier Positionen.   *
	 *  Es muss jedesmal neu geladen werden, nach dem eine neue Position    *
	 *  in die Tabelle hinzugefügt worden ist.                              *
	 *  Wird es nicht geladen, steht es mit der neu hinzugefügten Position  *
	 *  nicht in Verbindung und sie kann nicht gelöscht werden.             *
	 *																	    *
	 ************************************************************************/
	
	//var i = 0;
	
	// Buttons aus der Tabelle zum löschen einer Position	
	$("button[id^='bt_']").click(function() {
		//alert(i);
		//i++;
		// nehme ID des Buttons der auch die posID ist
		var id = parseInt(this.id.replace("bt_", ""));
		
		$.get("saveDelPosition.php", {posID: id}, function (json){
			if(json.message == '1'){
				var result = $.grep(positions, function(v){
				  return v.posID != id;
				});	   
			    positions = result;
			    $('#tr_'+id).hide('slow', function(){ $('#tr_'+id).remove(); });
			}
		}, 'json');
	});	
	
	function deletePosition(value){
		$.get("saveDelPosition.php", {posID: value}, function (data){
			if(data == 1){
				$('#tr_'+value).hide('slow', function(){ $('#tr_'+value).remove(); });
			}
		});
	}