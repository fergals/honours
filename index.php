<?php
include_once 'dbconnect.php';
include 'header.php'; ?>

<div id="content">
<h1>Your open tickets:</h1>
<?php

$opentickets = $db->query("SELECT tID, id, date FROM ticket WHERE status='Open'");
while ($o = $opentickets->fetch(PDO::FETCH_OBJ)) {
  echo $o->tID;
  echo " - Submitted: " . $o->date . " - ";
  echo "<a href='ticket.php?id=" . $o->tID . "'>Open Ticket</a><br />";
}

echo "<h1>Your closed tickets:</h1>";

$closedtickets = $db->query("SELECT tID, id, date FROM ticket WHERE status='Closed'");
while ($c = $closedtickets->fetch(PDO::FETCH_OBJ)) {
  echo $c->tID;
  echo " - Submitted: " . $c->date . " - ";
  echo "<a href='ticket.php?id=" . $c->tID . "'>View Closed Ticket</a><br />";
}
?>
</div>
<?php include 'footer.php'; ?>
