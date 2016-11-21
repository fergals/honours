<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
$ticketid = "T211116119";
$userid = 23;

$getdepname = $db->query("SELECT departments.depid, departments.depname, users.id, users.department FROM departments INNER JOIN users ON departments.depid=users.department WHERE users.id = $userid");
while ($g = $getdepname->fetch(PDO::FETCH_ASSOC)){
  echo $g['depid'] . " " . $g['depname'];
}
?>
