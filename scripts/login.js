$(document).ready( function() {
	
	// $("#login_form").submit(function(){
    	// return false;	
    // });
	
	$("#registration_form").submit(function(){
    	return false;	
    });
		
	// $('#loginButton').click(function(){
		// //alert("hallo "+$('#username').val());
// 		
		// var data = $("#login_form").serializeArray();
// 		
		// $('#username').val('');
// 		
// 		
// 		
		// $.post("login.php", data, function(data){
				// $("#responseMessage").html(data);
				// //setRegistrationResponse(data);
		// });
	// });
	
	
	$("#showRegFormButton").click(function () {
     	$('#registrationDiv').toggle('slow');
	});
	
	// Button der die Registrierungsdaten zum server schickt
	$("#signUpButton").click(function(){
		clearErrorAndServerResponseMessage();
		if(validateRegForm()){
			var data = $("#registration_form").serializeArray();
			        
			$.post("registration.php", data, function(data){
				$("#responseMessage").html(data);
				//setRegistrationResponse(data);
			});
		}
	});
	
	function clearErrorAndServerResponseMessage(){
		$("#userEmailError").html("");
		$("#responseMessage").html("");
		$("#regUsernameError").html("");
		$("#regUserFirstnameError").html("");
		$("#regUserLastnameError").html("");
		$("#regPasswordError").html("");
		$("#retypeRegPasswordError").html("");
	}
	
	function setRegistrationResponse(num){
		switch(num){
			case('1'):
				$("#userEmailError").html("<font color='red'><i>email is not valid.</i></font>");
				break;
			case('2'):
				$("#responseMessage").html("<font color='red'><i>No connection to database.</i></font>");
				break;
			case('3'):
				$("#userEmailError").html("<font color='red'><i>email is in usage.</i></font>");
				break;
			case('4'):
				 $("#regUsernameError").html("<font color='red'><i>username is in usage.</i></font>");
				break;
			case('5'):
				$("#responseMessage").html("<font color='blue' face='Trebuchet MS' size=7>Registration finished successfully.</font>");
				break;
			case('6'): 
			    $("#responseMessage").html("<font color='red'><i>No connection to server.</i></font>");
				break;
			case('7'):
				//$("#responseMessage").html("<font color='red'><i>"+num+"</i></font>");
				break;
			case('8'):
			
				break;
			case('9'):
			
				break;
		}
		
	}
	
	function validateRegForm(){
		var error = true;
		var firstname = $("#regUserFirstname").val();
		var lastname  = $("#regUserLastname").val();
		var username  = $("#regUsername").val();
		var email     = $("#userEmail").val();
		var pwd		  = $("#regPassword").val();
		var retypepwd = $("#retypeRegPassword").val();
		
		if(firstname.length < 2){
			$("#regUserFirstnameError").html("<font color='red'><i>Please enter at least 2 characters.</i></font>");
			error = false;
		}
		else{
			$("#regUserFirstnameError").html('');
		}
		if(lastname.length < 2){
			$("#regUserLastnameError").html("<font color='red'><i>Please enter at least 2 characters.</i></font>");
			error = false;
		}
		else{
			$("#regUserLastnameError").html('');
		}
		if(username.length < 3){
			$("#regUsernameError").html("<font color='red'><i>Please enter at least 4 characters.</i></font>");
			error = false;
		}
		else{
			$("#regUsernameError").html('');
		}
		if(pwd.length < 8){
			$("#regPasswordError").html("<font color='red'><i>Please enter at least 8 characters.</i></font>");
			error = false;
		}
		else{
			$("#regPasswordError").html('');
		}
		if(pwd != retypepwd){
			$("#retypeRegPasswordError").html("<font color='red'><i>Please enter the same password as above.</i></font>");
			error = false;
		}else{
			$("#retypeRegPasswordError").html('');
		}
		if(!looksLikeMail(email)){
			$('#userEmailError').html("<font color='red' ><i>Please enter valid email.</i></font>");
			error = false;	
		}
		else{
			$("#userEmailError").html('');
		}
		return error;
	}

	function looksLikeMail(str) {
	    var lastAtPos = str.lastIndexOf('@');
	    var lastDotPos = str.lastIndexOf('.');
	    return (lastAtPos < lastDotPos && lastAtPos > 0 && str.indexOf('@@') == -1 && lastDotPos > 2 && (str.length - lastDotPos) > 2);
	}

});