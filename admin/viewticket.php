<?php

//<input type='text' class='form-control' name='' placeholder=''>
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php'); ?>

<div class="container">
<div class="row">
<div class="col-xs-4">

<?php
// if(isset($_POST['emailticket'] $$ )) {
// $formaction = 'submitcomment.php';
// }
// else {
//   $formaction = 'submitcomment.php';
// }

 $ticketid = $_GET['id'];
 $userid = $_SESSION['id'];

 //Get User information and display on left
 $userinfo = $db->query("SELECT id, username, firstname, surname, email, phonenumber, department, usertype FROM users where id = $_SESSION[id] ");
 while($u = $userinfo->fetch(PDO::FETCH_OBJ)) {
   echo "<form action='' method='post'>";
   echo '<strong>User Information</strong><br />';
   echo "<input type='text' placeholder='" . $u->firstname . ' ' . $u->surname ."' readonly><br />";
   echo "<input type='text' name='email' class='auto' placeholder='" . $u->email . "'readonly><br />";
   echo "<input type='text' placeholder='" . $u->phonenumber . "'readonly><br />";
   echo "<input type='text' placeholder='" . $u->department . "'readonly><br />";
   echo "<input type='text' placeholder='" . $u->usertype . "'readonly><br /><br /></form>";
 }

//Get Ticket information and display on left
  $ticketinfo = $db->query("SELECT userid, tID, date, queue, category, department, urgency, status FROM ticket where tID = '$ticketid'");
  while($r = $ticketinfo->fetch(PDO::FETCH_OBJ)) {
    $fullticket = $r->tID;echo "<strong>" . $r->tID . "</strong><br />";
    echo "<input type='text' placeholder='" . $r->queue . "'><br />";
    echo "<input type='text' placeholder='" . $r->status . "'><br />";
    echo "<input type='text' placeholder='" . $r->urgency . "'><br />";

    $getcategories = $db->query("SELECT category FROM categories");
    echo "<select name='category' style='width: 174px;'>";
    while($gc = $getcategories->fetch(PDO::FETCH_ASSOC)){
      echo "<option value=" . $gc['category'] . ">" . $gc['category'] . "</option>";
    }
    echo "</select><br />";
    //echo "<input type='text' placeholder='" . $r->category . "'><br />";
    echo "<input type='text' placeholder='" . $r->department . "'><br />";
    echo "<input type='text' placeholder='" . $r->date . "'><br /><br />";
    echo "<button type='submit' class='btn btn-default' name='newcat' id='newcat'>Update Ticket Details</button>";
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

  else if(isset($_POST['sendemail'])) {
    header('Location: emailticket.php?id=' . $ticketid);
  }
 ?>


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
        <button name='sendemail' class='btn btn-default'>Send Email</button>
        <button type='submit' name='closeticket' class='btn btn-default'>Close ticket</button>
      </form>
    </div>
</div>
</div>

</div>

<?php include '../template/footer.php'; ?>
