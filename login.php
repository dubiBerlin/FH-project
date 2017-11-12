<?php 

	session_start(); 
	
	include "dbConnection.php";
	
	$username = '';
	$password = '';
	$log = 0;
	
	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password = md5($password);
		
		//$sql = "SELECT * FROM user";
		
		$sql = "SELECT username FROM user WHERE username='{$username}' AND password='{$password}'";
		$abfrage = mysqli_query($verbindung, $sql);
		
		$num = mysqli_num_rows($abfrage);
		
		// $sql = mysql_query("SELECT username FROM user WHERE username='{$username}' AND password='{$password}'");
    	// $num = mysql_num_rows($sql);
    	
		if($num != 0){
			
			$_SESSION['username'] = $username;
			
			if(strcasecmp($username, "admin")==0 && strcasecmp($password, md5('33333333')==0)){
				header('Location: admin.php');
      			exit;
			}else{
				header('Location: userProfil.php');
      			exit;
			}
		}else{
			echo "Kein Zugriff gestattet! <a href=\"login.html\">Zurueck</a>";
		}
	}
	
	
	
?>