<?php
	$_username = "crawler";
	$_password = "crawler";	
	$_hostname = "localhost";
	$_database = "crawler";	
	$_connection = mysql_connect($_hostname, $_username, $_password);
	mysql_select_db($_database, $_connection);
	
