<?php
require_once '/config/dbconnect.php';
include '/template/header.php';

    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $surname = $_POST['surname'];
    $dateofbirth = $_POST['dateofbirth'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $department = $_POST['department'];
    $usertype = $_POST['usertype'];
    $creationdate = date("Y-m-d H:i:s");

    $stmt = $db->prepare("INSERT INTO users (username, firstname, surname, dateofbirth, password, email, phonenumber, department, usertype, creationdate) VALUES (:username, :firstname, :surname, :dateofbirth, :password, :email, :phonenumber, :department, :usertype, :creationdate)");

    $stmt->bindParam(':username', $username, PDO::PARAM_STR, 100);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR, 100);
    $stmt->bindParam(':surname', $surname, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':dateofbirth', $password, PDO::PARAM_STR, 100);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR, 100);
    $stmt->bindParam(':phonenumber', $phonenumber, PDO::PARAM_STR, 100);
    $stmt->bindParam(':department', $department, PDO::PARAM_STR, 100);
    $stmt->bindParam(':usertype', $usertype, PDO::PARAM_STR, 100);
    $stmt->bindParam(':creationdate', $creationdate, PDO::PARAM_STR, 100);

    if($stmt->execute()) {
      echo "User has successfully been created - <a href='users.php'>Go Back</a>";
    }
    else {
      echo "Error submitting user to database - <a href='users.php'>Go Back</a>";
    }

    $db = null;
include '/template/footer.php'
?>
