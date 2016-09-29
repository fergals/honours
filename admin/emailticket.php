<?php

//<input type='text' class='form-control' name='' placeholder=''>
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }   ?>

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
   echo "Name: " . $u->firstname . ' ' . $u->surname ."<br />";
   echo "E-mail: " . $u->email . "<br />";
   echo "Phonenumber: " . $u->phonenumber . "<br />";
   echo "Department: " . $u->department . "<br />";
   echo "Usertype: " . $u->usertype . "<br /><br /></form>";
 }

//Get Ticket information and display on left
$ticketinfo = $db->query("SELECT userid, tID, date, queue, category, department, urgency, status FROM ticket where tID = '$ticketid'");
while($r = $ticketinfo->fetch(PDO::FETCH_OBJ)) {
  $fullticket = $r->tID;
  echo '<strong>' . $r->tID . '</strong><br /><br />';
  echo 'Status: ' . $r->status . '<br />';
  echo 'Category: ' . $r->category . '<br />';
  echo 'Department: ' . $r->department . '<br /><br />';
  echo 'Submitted: ' . $r->date;
  echo '</div>';

  echo "<div class='col-xs-6'><form method='post' action=''>";
  echo "<input class='form-control' type='text' placeholder='USERS EMAIL ADDRESS' readonly/><br />";
  echo "<textarea class='form-control' rows='10' name='emailcomment'></textarea><br />";
}

 ?>
        <button name='sendemail' class='btn btn-default'>Send Email</button>
      </form>
    </div>
</div>
</div>

</div>

<?php include '../template/footer.php'; ?>
