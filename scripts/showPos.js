function removeMessage(div, divChild){
	var messageDiv = document.getElementById(div);
	var childDiv   = document.getElementById(divChild);
	if(messageDiv.hasChildNodes){
		messageDiv.removeChild(childDiv);
	}
}
    
    function callRemoveMessage(){
    	if(document.getElementById("message").hasChildNodes){
    		if(document.getElementById("success")!=null){
    			setTimeout('removeMessage("message","success")',3000);
	    	}else{
	    		if(document.getElementById("error")!=null){	
	    			setTimeout('removeMessage("message","error")',3000);
	    		}
	    	}
    	}
    }
    
	function showPosition2(){
		if(document.getElementById("scroll2") != null){
			document.getElementById("pos2").value = document.getElementById("scroll2").scrollTop;	
		}
	}
	
	function showPosition1() {
		if(document.getElementById("scroll1") != null){
			var pos1 = document.getElementById("scroll1").scrollTop;
       		document.getElementById("pos1").value = pos1;	
		}
    }
       
    function init(){
        setInterval("showPosition1()",10);
        setInterval("showPosition2()",10);
        //setInterval("callRemoveMessage()", 1000);
        callRemoveMessage();
    }