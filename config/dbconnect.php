<?php

session_start();
date_default_timezone_set('Europe/London');


$servername = "localhost";
$username = "fergalse_sql";
$password = "e0#v?0H=Nyo0";
$dbname = "fergalse_help";

//PDO connection
$db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//include('config/user.php');
//include ('config/phpmailer/mail.php');

$pagetitle = "HELP! Online Support System";

include(dirname(__FILE__)."/../config/user.php");
$user = new User($db);
?>
