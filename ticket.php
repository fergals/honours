<?php
include '/template/header.php'; ?>

<div class="container">
<div class="row">
<div class="col-xs-4">

<?php
 $ticketid = $_GET['id'];
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

//Get Ticket information and display on left
$ticketinfo = $db->query("SELECT userid, tID, date, queue, category, department, urgency, status FROM ticket where tID = '$ticketid'");
while($r = $ticketinfo->fetch(PDO::FETCH_OBJ)) {
$fullticket = $r->tID;
echo '<strong>' . $r->tID . '</strong><br />';
echo 'Queue: ' . $r->queue . '<br />';
echo 'Status: ' . $r->status . '<br />';
echo 'Urgency: ' . $r->urgency . '<br /><br />';
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

   $comments = $db->query("SELECT comment, date FROM comments where tID = '$fullticket'");
    while($c = $comments->fetch(PDO::FETCH_OBJ)) {
      echo $c->comment . ' @ ' . $c->date .'<br /><br />';
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
         <div class="checkbox">
           <label>
             <input type="checkbox" name='hidden' value='true'> Hide comment from user
           </label>
         </div>

        <button type='submit' class='btn btn-default'>Submit</button>
      </form>
    </div>
</div>
</div>

</div>

<?php include '/template/footer.php'; ?>
