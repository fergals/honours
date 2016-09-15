<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php'); ?>

<div id="content">
<?php

if(isset($_POST['deleteselected'])){
  $idArr = $_POST['checkedid'];
  foreach($idArr as $id){
    $stmt = $db->query("DELETE FROM users WHERE id= $id");
  }
  echo "<div class='alert alert-success' role='alert'>Successfully deleted users</div>";
}

$users = $db->query("SELECT id, username, firstname, surname, department FROM users");
echo "<h2>Users</h2>";
echo "<form action='' method='post'>";
echo "<table class='table table-striped'><tr><th>Name</th><th>Username</th><th>Department</th><th>Edit User</th><th>Delete User</th>
</td>";
while($u = $users->fetch(PDO::FETCH_ASSOC)){
  echo "<tr><td>" . $u['firstname'] . " " . $u['surname'] . "</td>";
  echo "<td>" . $u['username'] . "</td>";
  echo "<td>" . $u['department'] . "</td>";
  echo "<td><a href='useredit.php?id=" . $u['id'] ."'>Edit</a></td>";
  echo "<td><input type='checkbox' name='checkedid[]' value='" . $u['id'] . "'></td></tr>";

}
echo "</table>";
echo "<button type='submit' class='btn btn-default' name='deleteselected'>Delete Selected</button></form>";

?>
</div>


<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminfooter.php'); ?>
