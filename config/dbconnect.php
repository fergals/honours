<?php
session_start();
date_default_timezone_set('Europe/London');
ob_start();

$current_date = date('d/m/Y - H:i:s');

$servername = "localhost";
$username = "fergalse_sql";
$password = "e0#v?0H=Nyo0";
$dbname = "fergalse_help";

//PDO connection
$db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pagetitle = "HELP! Online Support System";

//define website details
define('DIR','http://help.fergalsexton.com/');
define('SITEMAIL', 'noreply@fergalsexton.com');

require_once($_SERVER['DOCUMENT_ROOT'].'/config/user.php');
include ($_SERVER['DOCUMENT_ROOT'].'/config/phpmailer/mail.php');

$user = new User($db);

?>
