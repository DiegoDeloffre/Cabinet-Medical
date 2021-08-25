<?php
	/*connection a la base de donnees (mysqli)*/
	$serverName = "localhost";
	$dbUsername = "root";
	$dbPass = "";
	$dbName = "gestioncabinet";
	$link = mysqli_connect($serverName, $dbUsername, $dbPass, $dbName);
	if(!$link){
		die("Connection failed: ".mysqli_connect_error());
	}
?>