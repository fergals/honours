<?php
require_once 'dbconnect.php';
include 'header.php';

$comment= $_POST['comment'];
$timestamp = date("Y-m-d H:i:s");
$userid = 1;

//Ticket Number
$date = date("Y-m-d");
$ticketid = 'I' . $date . '003';

    $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("INSERT INTO comments (userid, tID, date, comment) VALUES (:userid, :tID, :date, :comment)");

    $stmt->bindParam(':userid', $userid, PDO::PARAM_STR, 100);
    $stmt->bindParam(':tID', $ticketid, PDO::PARAM_STR, 100);
    $stmt->bindParam(':date', $timestamp, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR, 100);

    if($stmt->execute()) {
      echo "Comment added successfully";
    }
    else {
      echo "Couldn't add comment";
    }

    $db = null;
?>
