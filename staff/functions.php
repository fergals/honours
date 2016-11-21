<?php
$userid = $_SESSION['id'];
$depname = "";
$getdepname = $db->query("SELECT departments.depid, departments.depname, users.id, users.department FROM departments INNER JOIN users ON departments.depid=users.department WHERE users.id = $userid");
while ($o = $getdepname->fetch(PDO::FETCH_ASSOC)){
      $depname = $o['depname'];
    }

?>
