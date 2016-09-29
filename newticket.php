<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/header.php');
if(!$user->is_logged_in()){ header('Location: uhoh.php'); }  

if(isset($_POST['submit'])) {
    //Gets id from ticket.table and increments from last row
    $dateinc = $db->query("SELECT id FROM ticket ORDER BY ID DESC LIMIT 1");
    while ($d = $dateinc->fetch(PDO::FETCH_OBJ)){
      $up = $d->id;
      $idincrement = $up + 1;
    }
    //Pulls POST data from ticket.php to insert into ticket.table
    $tID = "T" . date("dmy") . $idincrement;
    $userid = $_SESSION['id'];
    $query = $_POST['query'];
    $date = date("Y-m-d H:i:s");
    $queue = "1stline";
    $status = "Open";
    $urgency = "Low";
    $department = "None";
    $category = "None";
    $assigned = "1"; //auto apply ticket to unassigned

    $stmt = $db->prepare("INSERT INTO ticket (tID, userid, query, date, queue, status, urgency, department, category, assigned) VALUES (:tID, :userid, :query, :date, :queue, :status, :urgency, :department, :category, :assigned)");
    $dateinc = $db->query("SELECT id FROM ticket ORDER BY ID DESC LIMIT 1");

    $stmt->bindParam(':tID', $tID, PDO::PARAM_INT, 100);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 100);
    $stmt->bindParam(':query', $query, PDO::PARAM_STR, 100);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':queue', $queue, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':urgency', $urgency, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':department', $department, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':assigned', $assigned, PDO::PARAM_STR, 10000);
    $stmt->execute();
    echo "<div class='alert alert-success' role='alert'>Successsfull submited ticket</div>";
    $stmt = null;
}

?>

<div class="row">
  <div class="col-xs-6 col-md-4">Please write your query below. Be as descriptive as possible and attach any files needed in order for staff members to action quickly.<br>
  Please check the ticket portal regulary for responses</div>
  <div class="col-xs-12 col-md-8">
    <form class="form-horizontal" action="" method="post" />
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Query</label>
        <div class="col-sm-10">
          <textarea class='form-control' rows='5' name='query'></textarea>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" name="submit" class="btn btn-default">Submit</button>
        </div>
      </div>
  </div>
</div>

<? require_once($_SERVER['DOCUMENT_ROOT'].'/template/footer.php'); ?>
