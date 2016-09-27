<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php'); ?>

<div class="container">

<?php
echo "<div class='col-xs-4'>";
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
<?php
$dateinc = $db->query("SELECT id FROM ticket ORDER BY ID DESC LIMIT 1");
while ($d = $dateinc->fetch(PDO::FETCH_OBJ)){
  $up = $d->id;
  $idincrement = $up + 1;
}

echo "<div class='row'>";
if(isset($_POST['submitticket'])) {
  $tID = "T" . date("dmy") . $idincrement;
  $userid = $_SESSION['id'];
  $query = $_POST['query'];
  $date = date("Y-m-d H:i:s");
  $queue = $_POST['queue'];
  $status = $_POST['status'];
  $urgency = $_POST['urgency'];
  $department = $_POST['department'];
  $category = $_POST['category'];
  $assigned = "1";
  $stmt = $db->prepare("INSERT INTO ticket (tID, userid, query, date, queue, status, urgency, department, category, assigned) VALUES (:tID, :userid, :query, :date, :queue, :status, :urgency, :department, :category, :assigned)");
  $stmt->bindParam(':tID', $tID, PDO::PARAM_INT, 20);
  $stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 100);
  $stmt->bindParam(':query', $query, PDO::PARAM_STR, 10000);
  $stmt->bindParam(':date', $date, PDO::PARAM_STR, 20);
  $stmt->bindParam(':queue', $queue, PDO::PARAM_STR, 20);
  $stmt->bindParam(':status', $status, PDO::PARAM_STR, 20);
  $stmt->bindParam(':urgency', $urgency, PDO::PARAM_STR, 20);
  $stmt->bindParam(':department', $department, PDO::PARAM_STR, 30);
  $stmt->bindParam(':category', $category, PDO::PARAM_STR, 20);
  $stmt->bindParam(':assigned', $assigned, PDO::PARAM_STR, 10);
  $stmt->execute();
  echo "<div class='alert alert-success' role='alert'>Successfully added ticket</div>";
}
 ?>

  <form class="form-horizontal" action="" method="post" />
    <div class="form-group">
      <label for="inputEmail3" class="col-sm-2 control-label">Query</label>
      <div class="col-sm-10">
        <textarea class='form-control' rows='8' name='query'></textarea>
      </div>
    </div>

    <?php
    $stmt = $db->prepare("SELECT status FROM status");
    $stmt->execute();
    echo "<div class='form-group'>";
    echo "<label for='status' class='col-sm-2 control-label'>Status</label>";
    echo "<div class='col-sm-10'>";
    echo "<select name='status' class='form-control'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='" . $row['status'] . "'>" . $row['status'] ."</option>";
    }

    echo "</select></div></div>";
    $stmt = $db->prepare("SELECT qname FROM queues");
    $stmt->execute();
    echo "<div class='form-group'>";
    echo "<label for='queue' class='col-sm-2 control-label'>Queue</label>";
    echo "<div class='col-sm-10'>";
    echo "<select name='queue' class='form-control'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='" . $row['qname'] . "'>" . $row['qname'] ."</option>";
    }
    echo "</select></div></div>";

    $stmt = $db->prepare("SELECT urgency FROM urgency");
    $stmt->execute();
    echo "<div class='form-group'>";
    echo "<label for='urgency' class='col-sm-2 control-label'>Urgency</label>";
    echo "<div class='col-sm-10'>";
    echo "<select name='urgency' class='form-control'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='" . $row['urgency'] . "'>" . $row['urgency'] ."</option>";
    }
    echo "</select></div></div>";

    $stmt = $db->prepare("SELECT department FROM departments");
    $stmt->execute();
    echo "<div class='form-group'>";
    echo "<label for='department' class='col-sm-2 control-label'>Department</label>";
    echo "<div class='col-sm-10'>";
    echo "<select name='department' class='form-control'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='" . $row['department'] . "'>" . $row['department'] ."</option>";
    }
    echo "</select></div></div>";

    $stmt = $db->prepare("SELECT category FROM categories");
    $stmt->execute();
    echo "<div class='form-group'>";
    echo "<label for='category' class='col-sm-2 control-label'>Category</label>";
    echo "<div class='col-sm-10'>";
    echo "<select name='category' class='form-control'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='" . $row['category'] . "'>" . $row['category'] ."</option>";
    }
    echo "</select></div></div>";

    ?>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" name='submitticket' class="btn btn-default">Submit</button>
      </div>
    </div>
  </form>
</div>
</div>
</div>
<?php
include '../template/adminfooter.php'; ?>
