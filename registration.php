<?php

//error_reporting(E_ALL); // zum debuggen           
	ini_set('display_errors', true);

	$data      = '';
	$server    = 'localhost';
	$benutzer  = 'root';
	$passwort  = 'z8xjHAMJcKjezt3D';
	$datenbank = 'theProject';
	
	$firstname  = '';
	$lastname   = ''; 
	$username   = '';
	$email      = '';
	$pwd	    = '';
	$retypepwd  = '';
	$gender     = '';
	$birthday   = '';
	$birthmonth = '';
	$birthyear  = '';
	$birthdate  = '';
	$joinDate   = date("d.m.y");
	
	$error     = true;
	
	if(isset($_POST['userEmail'])){
		$email = $_POST['userEmail'];
		if(isValidEmail($email)){
			if(isset($_POST['regUsername'])){
				$username = trim($_POST['regUsername']);
				if(isset($_POST['regGender'])){
					$gender = $_POST['regGender'];
					if(isset($_POST['regUserFirstname'])){
						$firstname = trim($_POST['regUserFirstname']);
						if(isset($_POST['regUserLastname'])){
							$lastname = trim($_POST['regUserLastname']);
							if(isset($_POST['birthday']) && isset($_POST['birthmonth'])&&isset($_POST['birthyear'])){
								$birthdate = $_POST['birthday'].".".$_POST['birthmonth'].".".$_POST['birthyear'];
								
								if(isset($_POST['retypeRegPassword']) && isset($_POST['regPassword'])){
									if(strcasecmp($_POST['retypeRegPassword'], $_POST['regPassword'])==0){
										$pwd = $_POST['regPassword'];
										$pwd = md5($pwd);
									}else{
										$error = false;	
									}
								}else{
									$error = false;	
								}	
							}else{
								$error = false;	
							}	
						}else{
							$error = false;	
						}	
					}else{
						$error = false;	
					}
				}else{
					$error = false;	
				}
			}else{
				$error = false;	
			}
		}else{
			echo "1"; // email not valid
			$error = FALSE;
			exit;
		}	
	}else{
		$error = false;	
	}
	
	if($error==TRUE){
			
			//$dbconnection =mysql_connect($server, $benutzer, $passwort) or die ("Fehler mysql_connect: ".mysql_error());
			// Auswahl der zu verwendenden Datenbank auf dem Server
			//mysql_select_db($datenbank) or die ("Fehler bei select_db: ".mysql_error()); 
// 		
			// echo $dbconnection."<br/>";
		
		
		$error2 = true;
		
		$verbindung = @mysqli_connect($server, $benutzer,$passwort) or die ("Fehler bei select_db: ".mysql_error());
		
		if($verbindung)
		{	
			mysqli_select_db($verbindung, $datenbank);
			
			if(mysqli_error($verbindung)){
				echo "2";// s"Keine Verbindung zur Datenbank.";
				exit;
			}else{
				$sql = "SELECT email FROM user WHERE email='{$email}'";
  				
				$abfrage = mysqli_query($verbindung, $sql);	
  			 
				$num_rows = mysqli_num_rows($abfrage);
				
				if($num_rows > 0){
					echo "3"; // email vorhanden
					$error2 = false;
					exit;
				}
				
				// nachschauen ob username schon vorhanden ist
				if($error2 == true){
					$sql = "SELECT username FROM user WHERE username='{$username}'" ;
  				
					$abfrage = mysqli_query($verbindung, $sql);	
	  			 
					$num_rows = mysqli_num_rows($abfrage);
					
					if($num_rows > 0){
						echo "4"; // username schon vorhanden
						$error2 = false;
						exit;
					}
				}
				
				if($error2 == true){
					$sql = "INSERT INTO `user` (`username`, `password`, `join_date`, `first_name`, `last_name`, `email`, 
					       `gender`, `birthdate`) VALUES 
							('{$username}','{$pwd}','{$joinDate}','{$firstname}','{$lastname}',
							'{$email}','{$gender}','{$birthdate}')";
	
					if(mysqli_query($verbindung, $sql))
					{
						echo "5";	// saved
						@mysqli_close($verbindung);
						exit;
					}	
				}
			}
		}else{
			echo "6";// "No Connection to Server";
			exit;
		}
			
	}
	
	function isValidEmail($email){
		return @eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}

?>