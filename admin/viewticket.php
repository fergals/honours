<?php

//<input type='text' class='form-control' name='' placeholder=''>
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php'); ?>

<div class="container">
<div class="row">
<div class="col-xs-4">

<?php
 $ticketid = $_GET['id'];
 $userid = $_SESSION['id'];

 //Get User information and display on left
 $userinfo = $db->query("SELECT id, username, firstname, surname, email, phonenumber, department, usertype FROM users where id = $_SESSION[id] ");
 while($u = $userinfo->fetch(PDO::FETCH_OBJ)) {
   echo "<form action='' method='post'>";
 echo '<strong>User Information</strong><br />';
 echo "<input type='text' placeholder='" . $u->firstname . ' ' . $u->surname ."'><br />";
 echo "<input type='text' name='email' class='auto' placeholder='" . $u->email . "'><br />";
 echo "<input type='text' placeholder='" . $u->phonenumber . "'><br />";
 echo "<input type='text' placeholder='" . $u->department . "'><br />";
 echo "<input type='text' placeholder='" . $u->usertype . "'><br /><br /></form>";
 }

//Get Ticket information and display on left
$ticketinfo = $db->query("SELECT userid, tID, date, queue, category, department, urgency, status FROM ticket where tID = '$ticketid'");
while($r = $ticketinfo->fetch(PDO::FETCH_OBJ)) {
$fullticket = $r->tID;
echo "<input type='text' placeholder='" . $r->tID . "'><br />";
echo "<input type='text' placeholder='" . $r->queue . "'><br />";
echo "<input type='text' placeholder='" . $r->status . "'><br />";
echo "<input type='text' placeholder='" . $r->urgency . "'><br />";
echo "<input type='text' placeholder='" . $r->category . "'><br />";
echo "<input type='text' placeholder='" . $r->department . "'><br />";
echo "<input type='text' placeholder='" . $r->date . "'><br /><br />";
echo "<button type='submit' class='btn btn-default' name='newcat' id='newcat'>Update Ticket Details</button>";
}
 ?>

 <script type="text/javascript">
 $(function() {

     //autocomplete
     $(".auto").autocomplete({
         source: "autocomplete.php",
         minLength: 1,
         messages: {
           noResults: '',
           results: function() {}
}
});
     });
 </script>

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
           <label>
           <input type="checkbox" name='hidden' value='true'> E-mail copy of comment to user
         </label>
         </div>

        <button type='submit' class='btn btn-default'>Submit Comment</button>
        <button type='submit' class='btn btn-default'>Close ticket</button>
      </form>
    </div>
</div>
</div>

</div>

<?php include '../template/footer.php'; ?>
