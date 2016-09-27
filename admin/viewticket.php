<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php'); ?>

<?php

$ticketid = $_GET['id'];
$userid = $_SESSION['id'];

echo "<div class='container'>";
echo "<div class='row'>";

if(isset($_POST['updateticket'])) {

  $queue = $_POST['queue'];
  $status = $_POST['status'];
  $urgency = $_POST['urgency'];
  $category = $_POST['category'];
  $department = $_POST['department'];
  $assign = $_POST['assign'];
  $stmt = $db->prepare('UPDATE ticket SET queue=:queue, status=:status, urgency=:urgency, category=:category, department=:department, assigned=:assign WHERE tid = :ticketid');
  $stmt->bindParam(':queue', $queue, PDO::PARAM_STR,100);
  $stmt->bindParam(':status', $status, PDO::PARAM_STR,100);
  $stmt->bindParam(':urgency', $urgency, PDO::PARAM_STR,100);
  $stmt->bindParam(':category', $category, PDO::PARAM_STR,100);
  $stmt->bindParam(':department', $department, PDO::PARAM_STR,100);
  $stmt->bindParam(':ticketid', $ticketid, PDO::PARAM_STR,100);
  $stmt->bindParam(':assign', $assign, PDO::PARAM_STR,100);

  $stmt->execute();
  echo "<div class='alert alert-success' role='alert'>Successfully updated ticket</div>";
}
echo "<div class='col-xs-4'>";


 //Get User information and display on left
 $userinfo = $db->query("SELECT id, username, firstname, surname, email, phonenumber, department, usertype FROM users where id = $_SESSION[id] ");
 while($u = $userinfo->fetch(PDO::FETCH_OBJ)) {
   echo "<form action='' method='post'>";
   echo '<strong>User Information</strong><br />';
   echo "<input type='text' placeholder='" . $u->firstname . ' ' . $u->surname ."' readonly><br />";
   echo "<input type='text' name='email' class='auto' placeholder='" . $u->email . "'readonly><br />";
   echo "<input type='text' placeholder='" . $u->phonenumber . "'readonly><br />";
   echo "<input type='text' placeholder='" . $u->department . "'readonly><br />";
   echo "<input type='text' placeholder='" . $u->usertype . "'readonly><br /><br />";

   //Get Ticket information and display on left
   $ticketinfo = $db->query("SELECT userid, tID, date, queue, category, department, urgency, status FROM ticket where tID = '$ticketid'");
   while($r = $ticketinfo->fetch(PDO::FETCH_OBJ)) {
     $fullticket = $r->tID;
     echo "<strong>" . $r->tID . " </strong>(" . $r->date . ")<br />";

     // Get QUEUES from DB
     $stmt = $db->prepare("SELECT qname FROM queues");
     $stmt->execute();
     echo "<select name='queue' style='width: 174px'>";
     echo "<option value='" . $r->queue . "' selected>" . $r->queue . "</option>";
     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
     echo "<option value='" . $row['qname'] . "'>" . $row['qname'] ."</option>";
     }
     echo "</select><br />";

     //Get STATUS from DB
     $stmt = $db->prepare("SELECT status FROM status");
     $stmt->execute();
     echo "<select name='status' style='width: 174px'>";
     echo "<option value='" . $r->status . "' selected>" . $r->status . "</option>";
     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
     echo "<option value='" . $row['status'] . "'>" . $row['status'] ."</option>";
     }
     echo "</select><br />";

     //Get URGENCY from DB
     $stmt = $db->prepare("SELECT urgency FROM urgency");
     $stmt->execute();
     echo "<select name='urgency' style='width: 174px'>";
     echo "<option value='" . $r->urgency . "' selected>" . $r->urgency . "</option>";
     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
     echo "<option value='" . $row['urgency'] . "'>" . $row['urgency'] ."</option>";
     }
     echo "</select><br />";

     //Populate categories dropdown from DB
     $getcategories = $db->query("SELECT category FROM categories");
     echo "<select name='category' style='width: 174px;'>";
     echo "<option value='" . $r->category . "' selected>" . $r->category . "</option>";
     while($gc = $getcategories->fetch(PDO::FETCH_ASSOC)){
       echo "<option value=" . $gc['category'] . ">" . $gc['category'] . "</option>";
     }
     echo "</select><br />";

     //Populate DEPARTMENT dropdown from DB
     $getdepartment = $db->query("SELECT department FROM departments");
     echo "<select name='department' style='width: 174px;'>";
     echo "<option value='" . $r->department . "' selected>" . $r->department . "</option>";
     while($dp = $getdepartment->fetch(PDO::FETCH_ASSOC)){
       echo "<option value=" . $dp['department'] . ">" . $dp['department'] . "</option>";
     }
     echo "</select><br /><br />";
    }

    //Populate users dropdown from DB
    $getusers = $db->query("SELECT id, firstname, surname FROM users");
    $getassigned = $db->query("SELECT users.firstname, users.surname, users.id, ticket.tID, ticket.assigned FROM users INNER JOIN ticket ON users.id=ticket.assigned WHERE ticket.tID = 'T27091696'");
    while($ga = $getassigned->fetch(PDO::FETCH_ASSOC)){
    echo "<select name='assign' style='width: 174px;'>";
    echo "<option value='" . $ga['id'] . "' selected>" . $ga['firstname'] . " " . $ga['surname'] . "</option>";
    while($gu = $getusers->fetch(PDO::FETCH_ASSOC)){
      echo "<option value=" . $gu['id'] . ">" . $gu['firstname'] . " " . $gu['surname'] . "</option>";
    }
    echo "</select><br /><br />";
    echo "<button class='btn btn-default' name='updateticket' id='updateticket'>Update Ticket Details</button></form";
   }
 }
if(isset($_POST['submitcomment'])) {
    $userid = $_POST['commentuID'];
    $comment = $_POST['comment'];
    $tID = $_POST['commentTID'];
    $date = date("Y-m-d H:i:s");

// check if checbox has been to checked to make comment invisible to customer
    if(isset($_POST['hidden'])){
      $hidden = 'YES';
    } else
    {
      $hidden = 'NO';
    }

    $stmt = $db->prepare("INSERT INTO comments (userid, tID, date, comment, hidden) VALUES (:userid, :tID, :date, :comment, :hidden)");
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 100);
    $stmt->bindParam(':tID', $tID, PDO::PARAM_STR, 100);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':hidden', $hidden, PDO::PARAM_STR, 3);
    $stmt->execute();
  }
    // E-mail users
    if(isset($_POST['emailbtn'])){
    $to = $_POST['emailto'];
    $subject = "[$ticketid] Help! Online Support";
    $body = $_POST['emailmsg'];

    $mail = new Mail();
    $mail->setFrom(SITEMAIL);
    $mail->addAddress($to);
    $mail->subject($subject);
    $mail->body($body);
    $mail->send();

    echo "<div class='alert alert-success' role='alert'>Successfully updated ticket</div>";
    }
?>

</div>
</div>

  <div class="col-xs-6">
<?php
    $result = $db->query("SELECT userid, tID, date, query FROM ticket where tID = '$ticketid'");
    while($r = $result->fetch(PDO::FETCH_OBJ)) {
      echo $r->query . '<br /><br /><hr />';
    }

    $comments = $db->query("SELECT comments.comment, comments.date, users.firstname, users.surname, users.department FROM comments INNER JOIN users ON comments.userid=users.id WHERE comments.tID = '$fullticket'");
       while($c = $comments->fetch(PDO::FETCH_OBJ)) {
         echo $c->comment . '<br />' . $c->firstname . ' ' . $c->surname . ' on ' . $c->date . ' in ' . $c->department . '<br /><br />';
       }
?>

    <div class='form-group'>
      <strong>Add Comment to Ticket</strong>
      <?php
      if(isset($_GET['submitcomment'])){
        echo "<div class='alert alert-success' role='alert'>Added comment</div>";
      }
      ?>

      <form method='post' action=''>
        <textarea class='form-control' rows='5' name='comment'></textarea>

        <?php
        //Need tID and uID in order to pass comment - will update when include global variables
         $commentinfo = $db->query("SELECT userid, tID, date, query FROM ticket where tID = '$ticketid'");
         while($r = $commentinfo->fetch(PDO::FETCH_OBJ)) {

           echo "<input type='hidden' name='commentTID' value='" . $r->tID . "'>";
           echo "<input type='hidden' name='commentuID' value='" . $userid . "'>";
        }
         ?>
         <div class="checkbox">
           <label>
             <input type="checkbox" name='hidden' value='true'> Hide comment from user
           </label>
           <label>
           <input type="checkbox" name='emailticket' value='true'> E-mail copy of comment to user
         </label>
         </div>

        <button type='submitcomment' name='submitcomment' class='btn btn-default'>Submit Comment</button>
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Send e-mail to user</button>
        <button type='submit' name='closeticket' class='btn btn-default'>Close ticket</button>
      </form>
      <br>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New e-mail</h4>
      </div>
      <div class="modal-body">
        <form method='post' action=''>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Recipient:</label>
            <?php

            //Needs to be updated to allow actual users ID
            $sendemail = $db->query("SELECT users.email FROM users INNER JOIN ticket ON users.id=ticket.userid WHERE ticket.tID = $ticketid");
             while($s = $sendemail->fetch(PDO::FETCH_OBJ)) {
               echo "<input type='text' class='form-control' id='recipient-name' name='emailto' value='" . $s->email . "'>";
            } ?>
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message:</label>
            <textarea class="form-control" id="message-text" name="emailmsg"></textarea>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name='emailbtn'>Send e-mail</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../template/footer.php'; ?>
