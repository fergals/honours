<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php'); ?>

<div id="content">
  <ol class="breadcrumb">
    <li><a href="#">1st Line General</a></li>
  </ol>
  <?php
  $allopen = $db->query("SELECT tID, id, date, userid, category, department, urgency FROM ticket WHERE status='Open'");
  if(count($allopen) > 0 ) {
  echo "<table class='table table-striped'>
        <tr>
        <th>Ticket</th>
        <th>Date Submitted</th>
        <th>Category</th>
        <th>Department</th>
        <th>Urgency</th></tr>";

  while ($o = $allopen->fetch(PDO::FETCH_ASSOC)){
    echo "<tr><td><a href='viewticket.php?id=" . $o['tID'] . "'>" . $o['tID'] ."</td>";
    echo "<td>" . $o['date'] . "</td>";
    echo "<td>" . $o['category'] . "</td>";
    echo "<td>" . $o['department'] . "</td>";
    echo "<td>" . $o['urgency'] . "</td></tr>";
  }
  echo "</table>";
}
?>

</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminfooter.php'); ?>
