<?php
require_once 'dbconnect.php';

$query = $_POST['query'];
$status = $_POST['status'];
$timestamp = date("Y-m-d H:i:s");
$userid = 1;

// Perform SQL queries on DB
$sql = "INSERT INTO ticket (userid, query, date, status) VALUES ('$userid','$query','$timestamp','$status')";

// Display error or display successfully
if (mysqli_query($con, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($con);
}

mysqli_close($con);
?>
