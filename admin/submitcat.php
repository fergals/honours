<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

//Pulls POST data from category page
$userid = $_SESSION['id'];
$categoryname= $_POST['categoryname'];
$date = date("Y-m-d H:i:s");

$stmt = $db->prepare("INSERT INTO categories (category, datecreated, userid) VALUES (:category, :datecreated, :userid)");

$stmt->bindParam(':category', $categoryname, PDO::PARAM_INT, 100);
$stmt->bindParam(':datecreated', $date, PDO::PARAM_INT, 100);
$stmt->bindParam(':userid', $userid, PDO::PARAM_STR, 10000);

if($stmt->execute()) {
  echo "Successfully added category - <a href='categories.php'>Go Back</a>";
}
else {
  echo "Error submitting category to database- <a href='categories.php'>Go Back</a>";
}

$stmt = null;

require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminfooter.php');
 ?>
