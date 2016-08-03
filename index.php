<?php
include_once 'dbconnect.php';
include 'header.php'; ?>

<div id="content">
<h1>Your open tickets:</h1>
<?php

$opentickets = $db->query("SELECT tID, id, date, userid, category, department FROM ticket WHERE status='Open'");
echo "<table class='table table-striped'>
      <tr>
      <th>Ticket</th>
      <th>Date</th>
      <th>Sender</th>
      <th>Category</th>
      <th>Department</th></tr>";
while ($o = $opentickets->fetch(PDO::FETCH_ASSOC)){
  echo "<tr><td><a href='ticket.php?id=" . $o['tID'] . "'>" . $o['tID'] ."</td>";
  echo "<td>" . $o['date'] . "</td>";
  echo "<td>" . $o['userid'] . "</td>";
  echo "<td>" . $o['category'] . "</td>";
  echo "<td>" . $o['department'] . "</td></tr>";
}

echo "</table>";

echo "<h1>Your closed tickets:</h1>";

$closedtickets = $db->query("SELECT tID, id, date, userid, category, department FROM ticket WHERE status='Closed'");
echo "<table class='table table-striped'>
      <tr>
      <th>Ticket</th>
      <th>Date</th>
      <th>Sender</th>
      <th>Category</th>
      <th>Department</th></tr>";
while ($c = $closedtickets->fetch(PDO::FETCH_ASSOC)) {
  echo "<tr><td><a href='ticket.php?id=" . $c['tID'] . "'>" . $c['tID'] ."</td>";
  echo "<td>" . $c['date'] . "</td>";
  echo "<td>" . $c['userid'] . "</td>";
  echo "<td>" . $c['category'] . "</td>";
  echo "<td>" . $c['department'] . "</td></tr>";
}

echo "</table>";

?>
</div>
<?php include 'footer.php'; ?>
