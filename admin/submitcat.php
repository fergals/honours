<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

//Pulls POST data from category page
if(isset($_POST['newcat'])) {
  $userid = $_SESSION['id'];
  $categoryname = $_POST['categoryname'];
  $date = date("Y-m-d H:i:s");

$stmt = $db->prepare("INSERT INTO categories (category, datecreated, userid) VALUES (:category, :datecreated, :userid)");

$stmt->bindParam(':category', $categoryname, PDO::PARAM_STR, 100);
$stmt->bindParam(':datecreated', $date, PDO::PARAM_STR, 100);
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 10000);

if($stmt->execute()) {
  echo "Successfully added category - <a href='categories.php'>Go Back</a>";
}
else {
  echo "Error submitting category to database- <a href='categories.php'>Go Back</a>";
}
}
else if(isset($_POST['editsubmit'])) {
  $catname = $_POST['hiddencatid'];
  $editcat = $_POST['editcat'];
  $stmt = $db->prepare("UPDATE categories SET category=:category WHERE category=:catname");
  $stmt->bindParam(':category', $editcat, PDO::PARAM_STR, 100);
  $stmt->bindParam(':catname', $catname, PDO::PARAM_INT,3);

  if($stmt->execute()) {
    echo "Successfully edited category - <a href='categories.php'>Go Back</a>";
  }
  else {
    echo "Error editing category - <a href='categories.php'>Go Back</a>";
  }
}
else {
  $catname = $_POST['hiddencatid'];
  $editcat = $_POST['editcat'];
  $stmt = $db->prepare("DELETE FROM categories WHERE category=:catname");
  $stmt->bindParam(':catname', $catname, PDO::PARAM_INT,3);

  if($stmt->execute()) {
    echo "Successfully deleted category - <a href='categories.php'>Go Back</a>";
  }
  else {
    echo "Error deleting category - <a href='categories.php'>Go Back</a>";
  }
}

$stmt = null;
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminfooter.php');
 ?>
