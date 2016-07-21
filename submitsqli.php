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

    $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("INSERT INTO ticket (userid, tID, query, date, status) VALUES (:userid, :tID, :query, :timestamp, :status)");

    $stmt->bindParam(':userid', $userid, PDO::PARAM_STR, 100);
    $stmt->bindParam(':tID', $ticketid, PDO::PARAM_STR, 100);
    $stmt->bindParam(':query', $query, PDO::PARAM_STR, 100);
    $stmt->bindParam(':timestamp', $date, PDO::PARAM_STR, 100);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR, 100);

    if($stmt->execute()) {
      echo "Ticket has successfully been added";
    }
    else {
      echo "Didnt work";
    }

    $db = null;
?>
