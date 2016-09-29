<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/header.php');
if(!$user->is_logged_in()){ header('Location: uhoh.php'); }   ?>

<div class="container">
<div class="row">
<div class="col-xs-4">

<?php
 $ticketid = $_GET['id'];
 $userid = $_SESSION['id'];

//Get Ticket information and display on left
$ticketinfo = $db->query("SELECT userid, tID, date, queue, category, department, urgency, status FROM ticket where tID = '$ticketid'");
while($r = $ticketinfo->fetch(PDO::FETCH_OBJ)) {
$fullticket = $r->tID;
echo '<strong>' . $r->tID . '</strong><br /><br />';
echo 'Status: ' . $r->status . '<br />';
echo 'Category: ' . $r->category . '<br />';
echo 'Department: ' . $r->department . '<br /><br />';
echo 'Submitted: ' . $r->date;
}
 ?>

</div>

  <div class="col-xs-6">
<?php
    $result = $db->query("SELECT userid, tID, date, query FROM ticket where tID = '$ticketid'");
    while($r = $result->fetch(PDO::FETCH_OBJ)) {
      echo $r->query . '<br /><br /><hr />';
    }

  $comments = $db->query("SELECT comment, date FROM comments where tID = '$fullticket' AND hidden='NO'");
    while($c = $comments->fetch(PDO::FETCH_OBJ)) {
      echo $c->comment . '<br />' . $c->date . '<br /><br />';
    }
?>

    <div class='form-group'>
      <strong>Add Comment to Ticket</strong>
      <form action='submitcomment.php' method='post'>
        <textarea class='form-control' rows='5' name='comment'></textarea>

        <?php
        //Need tID and uID in order to pass comment - will update when include global variables
         $commentinfo = $db->query("SELECT userid, tID, date, query FROM ticket where tID = '$ticketid'");
         while($r = $commentinfo->fetch(PDO::FETCH_OBJ)) {

           echo "<input type='hidden' name='commentTID' value='" . $r->tID . "'>";
           echo "<input type='hidden' name='commentuID' value='" . $userid . "'>";;
        }
         ?>
        <button type='submit' class='btn btn-default'>Submit</button>
      </form>
    </div>
</div>
</div>

</div>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/footer.php'); ?>
