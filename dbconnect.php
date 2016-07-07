<?php
$servername = "localhost:3306";
$username = "ferghalse_sql";
$password = "e0#v?0H=Nyo0";
$dbname = "ferghalse_help";

$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if(!$con)
{
  die("Failed to connect to SQL: " . mysqli_connect_error());
}
?>
