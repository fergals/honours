<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }    ?>

<div id="content">
  <?php

  $userid = $_SESSION['id'];

  $stmt = $db->prepare("SELECT department FROM users where id = $userid");
  $stmt->execute();
  echo "<ol class='breadcrumb'>";
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  echo "<li><a href='#'>" . $row['department'] . " 1st Line</a></li>";
  echo "<li><a href='#'>" . $row['department'] . " 2nd Line</a></li>";
  }
  echo "<li>Assigned to you</li>";
  echo "</ol>";

  $allopen = $db->query("SELECT ticket.tID, ticket.id, ticket.date, ticket.userid, ticket.category, ticket.department, ticket.urgency, ticket.assigned, users.id, users.firstname, users.surname FROM ticket INNER JOIN users ON ticket.assigned=users.id WHERE status='Open' AND ticket.assigned = $userid");
  if(count($allopen) > 0 ) {
  echo "<table class='table table-striped'>
        <tr>
        <th>Ticket</th>
        <th>Date Submitted</th>
        <th>Category</th>
        <th>Department</th>
        <th>Urgency</th>
        <th>Assigned</th></tr>";

  while ($o = $allopen->fetch(PDO::FETCH_ASSOC)){
    echo "<tr><td><a href='viewticket.php?id=" . $o['tID'] . "'>" . $o['tID'] ."</td>";
    echo "<td>" . $o['date'] . "</td>";
    echo "<td>" . $o['category'] . "</td>";
    echo "<td>" . $o['department'] . "</td>";
    echo "<td>" . $o['urgency'] . "</td>";
    echo "<td>" . $o['firstname'] . " " . $o['surname'] . "</td></tr>";
  }
  echo "</table>";
}
?>

</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminfooter.php'); ?>
