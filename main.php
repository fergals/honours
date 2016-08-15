<?php
include '/template/header.php'; ?>

<div id="content">
<?php
$opentickets = $db->query("SELECT tID, id, date, userid, category, department FROM ticket WHERE userid = '$_SESSION[id]' AND status='Open'");

if(count($opentickets) > 0) {
  echo "<h2>Your open tickets:</h2>";
  echo "<table class='table table-striped'>
        <tr>
        <th>Ticket</th>
        <th>Date Submitted</th>
        <th>Category</th>
        <th>Department</th></tr>";
  $o = $opentickets->fetch(PDO::FETCH_ASSOC);
  echo "<tr><td><a href='ticket.php?id=" . $o['tID'] . "'>" . $o['tID'] ."</td>";
  echo "<td>" . $o['date'] . "</td>";
  echo "<td>" . $o['category'] . "</td>";
  echo "<td>" . $o['department'] . "</td></tr>";
  echo "</table>";
}
  else
  {
    echo "<h2>You have no open tickets</h2>";
  }


$closedtickets = $db->query("SELECT tID, id, date, userid, category, department FROM ticket WHERE userid = '$_SESSION[id]' AND status='closed'");

if(count($closedtickets) > 0) {
echo "<h2>Your closed tickets:</h2>";
echo "<table class='table table-striped'>
      <tr>
      <th>Ticket</th>
      <th>Date</th>
      <th>Category</th>
      <th>Department</th>
      <th>Date Closed</th></tr>";
$c = $closedtickets->fetch(PDO::FETCH_ASSOC);
  echo "<tr><td><a href='ticket.php?id=" . $c['tID'] . "'>" . $c['tID'] ."</td>";
  echo "<td>" . $c['date'] . "</td>";
  echo "<td>" . $c['category'] . "</td>";
  echo "<td>" . $c['department'] . "</td>";
  echo "<td>Date Closed not in SQL</td></tr>";
  echo "</table>";
}
else {
  echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><hr />";
  echo "<h2>You have no closed tickets</h2>";
}

?>
</div>
<?php include '/template/footer.php'; ?>