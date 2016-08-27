<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/header.php');?>
<div id="content">
<h1>Edit your profile</h1>

<?php
$loadprofile = $db->query("SELECT username, firstname, surname, password, email, phonenumber, department, usertype FROM users WHERE id = $_SESSION[id]");

      echo "<form class='form-horizontal' action='sprofile.php' method='post' />";
      echo "<div class='form-group'>
      <label class='col-sm-2 control-label'>First Name</label>
      <div class='col-sm-10'>";
      while ($o = $loadprofile->fetch(PDO::FETCH_ASSOC)){
        echo "<input class='form-control' placeholder='" . $o['firstname'] . "'></div></div>";
        echo "<div class='form-group'><label class='col-sm-2 control-label'>Surname</label>
              <div class='col-sm-10'><input class='form-control' placeholder='" . $o['surname'] ."'></div></div>";
        echo "<div class='form-group'><label class='col-sm-2 control-label'>Password</label>
              <div class='col-sm-10'><input class='form-control' placeholder='" . $o['password'] ."'></div></div>";
        echo "<div class='form-group'><label class='col-sm-2 control-label'>Confirm Password</label>
              <div class='col-sm-10'><input class='form-control' placeholder='" . $o['password'] ."'></div></div>";
        echo "<div class='form-group'><label class='col-sm-2 control-label'>E-mail Address</label>
              <div class='col-sm-10'><input class='form-control' placeholder='" . $o['email'] ."'></div></div>";
        echo "<div class='form-group'><label class='col-sm-2 control-label'>Phonenumber</label>
              <div class='col-sm-10'><input class='form-control' placeholder='" . $o['phonenumber'] ."'></div></div>";
    }
?>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>
  </form>


</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/footer.php'); ?>
