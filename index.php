<?php
include_once 'dbconnect.php';
include 'header.php'; ?>

<div id="content">
  <h1>Your open tickets:</h1>

<?php
$sql = "SELECT tID, id, date FROM ticket WHERE status='Open'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["tID"];
        echo " - Submitted: " . $row["date"] . " ";
        echo "<a href=\"ticket.php?id=".$row['id']."\">Open Ticket</a><br />";
    }

} else {
    echo "No Tickets Found";
}

?>

<h1>Your closed tickets:</h1>
<?php  $sql = "SELECT tID, date FROM ticket WHERE status='Closed'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["tID"]. " - Submitted: " . $row["date"]. "<br>";
    }
} else {
    echo "There are no open tickets";
}
$con->close();

?>
</div>

<?php include 'footer.php'; ?>
