<?php
require_once 'dbconnect.php';
include 'header.php';

$query = $_POST['query'];
$status = $_POST['status'];
$timestamp = date("Y-m-d H:i:s");
$userid = 1;

//Ticket Number
$date = date("Y-m-d");
$ticketid = 'I' . $date . '003';



// Perform SQL queries on DB
$sql = "INSERT INTO ticket (userid, tID, query, date, status) VALUES ('$userid','$ticketid','$query','$timestamp','$status')";

// Display error or display successfully
if (mysqli_query($con, $sql)) {
    echo "Added ticket successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($con);
}

mysqli_close($con);
?>
