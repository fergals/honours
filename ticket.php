<?php
include_once 'dbconnect.php';
include 'header.php'; ?>

<div id="content">
  <h1>Your selected ticket</h1>

<?php
$id = $_GET['id'];
$sql = "SELECT * FROM ticket WHERE id = '$id'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<div='ticket'>" . $row["tID"] . " Submitted by: [user] " . "(" . $row["status"] . ")<br />";
        echo $row["query"];
        echo "<div id='ticket-reply'>
              <form action='submitsqli.php' method='post' /><p>
              Query: <input type='text' name='query' />
              <input type='submit' value='Submit' />
              </form>
              </div>";
    }

} else {
    echo "No Tickets Found";
}

?>

</div>

<?php include 'footer.php'; ?>
