	
	
$(document).ready( function() {
	
	$('#uploadButton').click(function(){
		$('#message').hide();
		doUpload();
	});
	
	$("#upOrNot").click(function () {
     	
		$('#uploadID').toggle('slow');
	});
	
	$('#uploadprogress').hide();
	
});

function doUpload()
{
	// STEP 2. Create the iframe object
	var iframe;

	try 
	{
		iframe = document.createElement('<iframe name="uploadiframe">');
	} 
	catch (ex) 
	{
		iframe = document.createElement('iframe');
		iframe.name='uploadiframe';
	}
	
	iframe.src = 'javascript:false';
	iframe.id = 'uploadiframe';
	iframe.className ='iframe';
	
	document.body.appendChild(iframe);
	
	// STEP 3. Redirect the form to iframe
	$('#form').attr('target','uploadiframe');
	
	// STEP 4. Display the progress layer
	$('#uploadprogress').show();
	
	// .STEP 5. Intercept the upload result
	$('#uploadiframe').load(function () {
		
		$('#uploadprogress').hide();
		
		// STEP 6. Inform the user about the result
		var result = $('body', this.contentWindow.document).html();
		
		if(result >= 1 && result < 5)
		{
			$("#message").removeClass("success");
			$("#message").addClass("error");
			
			if(result == 1){
				$('#message').html('Eine Datei wurde vergessen.');
			}
			else{
				if(result == 2){
					$('#message').html('Wo sind die Dateien?');
				}
				else{
					if(result == 3){
						$('#message').html('Es koennen nur txt-Dateien hochgeladen werden');
					}
					else{
						if(result == 4){
							$('#message').html('Upss! Wo ist der Titel?');
						}
						else{
							if(result == 5){
								$('#message').html('Es konnte keine Verbindung zur Datenbank hergestellt werden.');
							}
							else{
								$('#message').html(result);
							}
						}
					}
				}
			}
			$('#message').show();
		}
		else{
			if(result==6){
				if($('#message').hasClass('error') ){
					$("#message").removeClass("error");
				}
				
				$("#message").addClass("success");
				
				$('#message').html('Die Dateien wurden erfolgreich hochgeladen');
				$('#message').show();
				$('#form').each(function(){
					this.reset();
				});
			}
			
		}
		//$('#message').html(result);
		
		// STEP 7. Destroy the iframe
		setTimeout(function () {
			$('#uploadiframe').remove();}, 
			50);
		
		// das wieder aufbauen des select Menus jetzt hier da sonst die hochgeladene Datei erst nach den 3 sekunden
		// zu auswahl bereit steht.	
		$.getScript('scripts/buildDropDownMenu.js');
		
		setTimeout(function () {
			$('#message').hide();}, 
			3000);
	});
	
}