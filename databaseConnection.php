<?php

   $mysqlhost   = "localhost";
   $mysqluser   = "root";
   $mysqlpasswd = "z8xjHAMJcKjezt3D";
   $mysqldbname = "test";
   $mysqltable  = "login1";
   $mysqlpwd    = "pwd";
   $mysqlname   = "name";

   $link = @mysql_pconnect($mysqlhost, $mysqluser, $mysqlpasswd);
   if ($link == FALSE) {
     echo "<p><b>Leider kann keine Verbindung zur Datenbank hergestellt werden.";
     echo "Bitte versuchen Sie es später noch einmal.</b></p>\n";
     exit();
   }
   mysql_select_db($mysqldbname);

?>