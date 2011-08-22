<?php
	include_once "db.php";	
	$id = $_GET['id'];
	$q = "SELECT * FROM `products` WHERE `id`={$id} LIMIT 1";
	$rs = mysql_query($q, $_connection);
	$r = mysql_fetch_assoc($rs);
	header('Content-Type: image/jpeg');
	echo base64_decode($r['itemimageblob']);
?>
