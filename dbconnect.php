<?php
$servername = "localhost";
$username = "fergalse_sql";
$password = "e0#v?0H=Nyo0";
$dbname = "fergalse_help";

//PDO connection
$db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//SQLi - where am I still using this?? Needs to migrated to PDO
$con = new mysqli($servername, $username, $password, $dbname);
 if(!$con) {
  die("Failed to connect to SQL: " . mysqli_connect_error());
}
?>
