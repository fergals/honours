<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/header.php');

//Gets id from ticket.table and increments from last row
$dateinc = $db->query("SELECT id FROM ticket ORDER BY ID DESC LIMIT 1");
while ($d = $dateinc->fetch(PDO::FETCH_OBJ)){
   $up = $d->id;
   $idincrement = $up + 1;
}

//Pulls POST data from ticket.php to insert into ticket.table
$tID = "T" . date("dmy") . $idincrement;
$userid = $_SESSION['id'];
$query = $_POST['query'];
$date = date("Y-m-d H:i:s");
$queue = "1stline";
$status = "Open";
$urgency = "low";
$department = "None";
$category = "None";

$stmt = $db->prepare("INSERT INTO ticket (tID, userid, query, date, queue, status, urgency, department, category) VALUES (:tID, :userid, :query, :date, :queue, :status, :urgency, :department, :category)");
$dateinc = $db->query("SELECT id FROM ticket ORDER BY ID DESC LIMIT 1");

$stmt->bindParam(':tID', $tID, PDO::PARAM_INT, 100);
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 100);
$stmt->bindParam(':query', $query, PDO::PARAM_STR, 100);
$stmt->bindParam(':date', $date, PDO::PARAM_STR, 10000);
$stmt->bindParam(':queue', $queue, PDO::PARAM_STR, 10000);
$stmt->bindParam(':status', $status, PDO::PARAM_STR, 10000);
$stmt->bindParam(':urgency', $urgency, PDO::PARAM_STR, 10000);
$stmt->bindParam(':department', $department, PDO::PARAM_STR, 10000);
$stmt->bindParam(':category', $category, PDO::PARAM_STR, 10000);

if($stmt->execute()) {
  echo "Successfully added ticket - <a href='index.php'>Go Back</a>";
  echo $date;
}
else {
  echo "Error submitting ticket to database- <a href='index.php'>Go Back</a>";
}

$stmt = null;

require_once($_SERVER['DOCUMENT_ROOT'].'/template/footer.php');
 ?>
