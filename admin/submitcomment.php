<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/header.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }    

    $userid = $_POST['commentuID'];
    $comment = $_POST['comment'];
    $tID = $_POST['commentTID'];
    $date = date("Y-m-d H:i:s");

    if(isset($_POST['hidden'])){
      $hidden = 'YES';
    } else
    {
      $hidden = 'NO';
    }

    $stmt = $db->prepare("INSERT INTO comments (userid, tID, date, comment, hidden) VALUES (:userid, :tID, :date, :comment, :hidden)");
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 100);
    $stmt->bindParam(':tID', $tID, PDO::PARAM_STR, 100);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':hidden', $hidden, PDO::PARAM_STR, 3);

    if($stmt->execute()) {
      echo "Successfully added comment - <a href='viewticket.php?id=" . $tID . "'>Go Back</a>";
    }
    else {
      echo "Error submitting comment to database - <a href='index.php'>Go Back</a>";
    }

    $db = null;

require_once($_SERVER['DOCUMENT_ROOT'].'/template/footer.php');
?>
