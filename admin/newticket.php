<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php'); ?>

<div class="container">
<div class="row">
<div class="col-xs-4">
<?php
$userid = $_SESSION['id'];

  //Get User information and display on left


$userinfo = $db->query("SELECT id, username, firstname, surname, email, phonenumber, department, usertype FROM users where id = $_SESSION[id] ");
while($u = $userinfo->fetch(PDO::FETCH_OBJ)) {
echo '<strong>User Information</strong><br />';
echo 'Name: ' . $u->firstname . ' ' . $u->surname . '<br />';
echo 'E-mail: ' . $u->email . '<br />';
echo 'Telephone: ' . $u->phonenumber .'<br />';
echo 'Department: ' . $u->department . '<br />';
echo 'User Type: ' . $u->usertype . '<br /><br />';
  }
?>
</div>

<div class="col-xs-6">

  <form class="form-horizontal" action="submitticket.php" method="post" />
    <div class="form-group">
      <label for="inputEmail3" class="col-sm-2 control-label">Query</label>
      <div class="col-sm-10">
        <textarea class='form-control' rows='5' name='query'></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="status" class="col-sm-2 control-label">Staus</label>
      <div class="col-sm-10">
        <select name="status" class="form-control">
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="queue" class="col-sm-2 control-label">Queue</label>
      <div class="col-sm-10">
        <select name="queue" class="form-control">
          <option value="1stline">1st Line</option>
          <option value="2ndline">2nd Line</option>
          <option value="3rdline">3rd Line</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="urgency" class="col-sm-2 control-label">Urgency</label>
      <div class="col-sm-10">
        <select name="urgency" class="form-control">
          <option value="Low">Low</option>
          <option value="Normal">Normal</option>
          <option value="Critical">Crtical</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="department" class="col-sm-2 control-label">Departments</label>
      <div class="col-sm-10">
        <select name="department" class="form-control">
          <option value="Accounts">Accounts</option>
          <option value="Admissions">Admissions</option>
          <option value="Computing">Computing</option>
          <option value="Finance">Finance</option>
          <option value="Marketing">Marketing</option>
          <option value="Student">Student Issues</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="category" class="col-sm-2 control-label">Category</label>
      <div class="col-sm-10">
        <?php
        $getcategories = $db->query("SELECT category FROM categories");
        echo "<select name='category' class='form-control'>";
        while($gc = $getcategories->fetch(PDO::FETCH_ASSOC)){
          echo "<option value=" . $gc['category'] . ">" . $gc['category'] . "</option>";
        }
        echo "</select>";
        $db = null;
        ?>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>
  </form>
</div>
</div>
</div>
<?php
include '../template/adminfooter.php'; ?>
