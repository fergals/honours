<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/header.php');

    $userid = $_POST['commentuID'];
    $comment = $_POST['comment'];
    $tID = $_POST['commentTID'];
    $date = date("Y-m-d H:i:s");

    $stmt = $db->prepare("INSERT INTO comments (userid, tID, date, comment) VALUES (:userid, :tID, :date, :comment)");

    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 100);
    $stmt->bindParam(':tID', $tID, PDO::PARAM_STR, 100);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR, 10000);

    if($stmt->execute()) {
      echo "Successfully added comment - <a href='ticket.php?id=" . $tID . "'>Go Back</a>";
    }
    else {
      echo "Error submitting user to database - <a href='index.php'>Go Back</a>";
    }

    $db = null;

require_once($_SERVER['DOCUMENT_ROOT'].'/template/footer.php');
?>
