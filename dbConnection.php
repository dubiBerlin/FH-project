<?php

   $server    = 'localhost';
   $benutzer  = 'root';
   $passwort  = 'z8xjHAMJcKjezt3D';
   $datenbank = 'theProject';

   $verbindung = @mysqli_connect($server, $benutzer,$passwort);
   
   
   
   if ($verbindung == FALSE) {
     echo "<p><b>Leider kann keine Verbindung zur Datenbank hergestellt werden.";
     echo "Bitte versuchen Sie es später noch einmal.</b></p>\n";
     exit();
   }
   
   mysqli_select_db($verbindung, $datenbank);
   
   if(mysqli_error($verbindung))
   {
		echo 'No connection to database.';	
   }

?>