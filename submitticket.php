<?php
include 'dbconnect.php';

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

if (!$link) {
  die('Could not connect to:' . mysql_error());
}

$db_selected = mysql_select_db(DB_NAME, $link);

if (!$db_selected) {
  die('Can\'t use' . DB_NAME . ':' . mysql_error());
}

$value = $_POST['query'];
$timestamp = date("Y-m-d H:i:s");
$userid = 1;

$sql = "INSERT INTO ticket (userid, query, date) VALUES ('$userid','$value','$timestamp')";

if (!mysql_query($sql)) {
  die('Error: ' . mysql_error());
}
else {
  echo("You have submitted successfully");
}

mysql_close();
 ?>
