<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if (isset($_GET['term'])){
    $return_arr = array();

    try {
        $stmt = $db->prepare('SELECT id, firstname, surname, department, email, usertype FROM users WHERE firstname LIKE :term');
        $stmt->execute(array('term' => '%'.$_GET['term'].'%'));

        while($row = $stmt->fetch()) {
          $user = array();
          // $user[0] = $row['firstname'];
          // $user[1] = $row['surname'];
          // $user[2] = $row['department'];
          // $user[3] = $row['email'];
          // $user[4] = $row['id'];
          $userData[] = $row;

            }
          } catch(PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
            echo json_encode($userData);
        }
?>
