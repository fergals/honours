<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }    ?>

<div id="content">

<?php

$userid = $_GET['id'];

if(isset($_POST['updateuser'])) {

  $firstname = $_POST['firstname'];
  $surname = $_POST['surname'];
  $email = $_POST['email'];
  $phonenumber = $_POST['phonenumber'];
  $usertype = $_POST['usertype'];
  $department = $_POST['department'];

  $stmt = $db->prepare('UPDATE users SET firstname=:firstname, surname=:surname, email=:email, phonenumber=:phonenumber, usertype=:usertype, department=:department WHERE id = :userid');
  $stmt->bindParam(':userid', $userid, PDO::PARAM_STR,100);
  $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR,100);
  $stmt->bindParam(':surname', $surname, PDO::PARAM_STR,100);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR,100);
  $stmt->bindParam(':phonenumber', $phonenumber, PDO::PARAM_STR,100);
  $stmt->bindParam(':usertype', $usertype, PDO::PARAM_STR,100);
  $stmt->bindParam(':department', $department, PDO::PARAM_STR,100);
  $stmt->execute();
  echo "<div class='alert alert-success' role='alert'>Successfully updated user information</div>";
}

$loadprofile = $db->query("SELECT username, firstname, surname, password, email, phonenumber, department, usertype FROM users WHERE id = $userid");

      echo "<form class='form-horizontal' method='post' action='' />";
      echo "<div class='form-group'>
      <label class='col-sm-2 control-label'>First Name</label>
      <div class='col-sm-10'>";
      while ($o = $loadprofile->fetch(PDO::FETCH_ASSOC)){
        echo "<input class='form-control' name='firstname' value='" . $o['firstname'] . "'></div></div>";
        echo "<div class='form-group'><label class='col-sm-2 control-label'>Surname</label>
              <div class='col-sm-10'><input class='form-control' name='surname' value='" . $o['surname'] ."'></div></div>";
        echo "<div class='form-group'><label class='col-sm-2 control-label'>E-mail Address</label>
              <div class='col-sm-10'><input class='form-control' name='email' value='" . $o['email'] ."'></div></div>";
        echo "<div class='form-group'><label class='col-sm-2 control-label'>Phonenumber</label>
              <div class='col-sm-10'><input class='form-control' name='phonenumber' value='" . $o['phonenumber'] ."'><br></div></div>";
        echo "<div class='form-group'><label class='col-sm-2 control-label'>Usertype</label>
              <div class='col-sm-10'><select name='usertype' class='form-control'>
              <option value='" . $o['usertype'] ."' selected>" . $o['usertype'] . "</option>
              <option value='Administrator'>Administrator</option>
              <option value='Manager'>Manager</option>
              <option value='Operator'>Operator</option>
              <option value='Registered'>Registered</option></select><br></div></div>";
      //Populate DEPARTMENT dropdown from DB
      $getdepartment = $db->query("SELECT department FROM departments");
        echo "<div class='form-group'><label class='col-sm-2 control-label'>Department</label>
              <div class='col-sm-10'><select name='department' class='form-control'>";
        echo "<option value='" . $o['department'] . "' selected>" . $o['department'] . "</option>";
        while($dp = $getdepartment->fetch(PDO::FETCH_ASSOC)){
        echo "<option value=" . $dp['department'] . ">" . $dp['department'] . "</option>";
      }
      echo "</select></div></div>";
      echo "<div class='form-group'><div class='col-sm-offset-2 col-sm-10'><button type='submit' class='btn btn-default' name='updateuser'>Update User</button></div></div></div></form>";
    }
?>

</div>


<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminfooter.php'); ?>
