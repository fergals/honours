<?php
include_once 'dbconnect.php';
include 'header.php'; ?>

<div id="content">
  <h1>Your open tickets:</h1>

<?php  $sql = "SELECT id, date FROM ticket WHERE status='Open'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["id"]. " - Date: " . $row["date"]. "<br>";
    }
} else {
    echo "No Tickets Found";
}

?>

<h1>Your closed tickets:</h1>
<?php  $sql = "SELECT id, date FROM ticket WHERE status='Closed'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["id"]. " - Date: " . $row["date"]. "<br>";
    }
} else {
    echo "No Tickets Found";
}
$con->close();

?>
</div>

<?php include 'footer.php'; ?>
