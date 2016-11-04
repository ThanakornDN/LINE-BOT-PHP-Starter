<?php
$servername = "202.28.37.32";
$username = "smartcsmju";
$username = "smartcsmjudb..";
$dbname = "SmartCounter_v2";

	$objConnect = mysqli_connect($servername,$username,$username) or die("Error Connect to Database");
	$objDB = mysqli_select_db($objConnect,"$dbname");
	mysqli_query($objConnect,"SET NAMES UTF8");

?>
