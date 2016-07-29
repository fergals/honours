<?php
include_once 'dbconnect.php';
include 'header.php'; ?>

<div class="container">
<div class="row">
<div class="col-xs-4">

<?php
 $ticketid1 = $_GET['id'];
 $ticketinfo = $db->query("SELECT userid, tID, date, query FROM ticket where id = '$ticketid1'");
 while($r = $ticketinfo->fetch(PDO::FETCH_OBJ)) {
echo '<strong>' . $r->tID . '</strong><br />';
echo 'Queue: First Line<br />';
echo 'Status: Open<br />';
echo 'Urgency: Normal<br /><br />';
echo 'Category: Computer Issues<br />';
echo 'Department: Finance <br /><br />';
echo 'Submitted: 10/10/16<br /><br />';
}

$userinfo = $db->query("SELECT id, username, firstname, surname, email, phonenumber, department, usertype FROM users where id = 1");
while($u = $userinfo->fetch(PDO::FETCH_OBJ)) {
echo '<strong>User Information</strong><br />';
echo 'Name: ' . $u->firstname . ' ' . $u->surname . '<br />';
echo 'E-mail: ' . $u->email . '<br />';
echo 'Telephone: ' . $u->phonenumber .'<br />';
echo 'Department: ' . $u->department . '<br />';
echo 'User Type: ' . $u->usertype;
}
 ?>

</div>

  <div class="col-xs-6">
<?php
    $ticketid = $_GET['id'];
    $result = $db->query("SELECT userid, tID, date, query FROM ticket where id = '$ticketid'");
    while($r = $result->fetch(PDO::FETCH_OBJ)) {
      echo $r->query;}

?>

<br />
<br />

<hr />
    <div class='form-group'>
      <form action='submitcomment.php' method='post'>
        <textarea class='form-control' rows='5' name='comment'></textarea>
        <button type='submit' class='btn btn-default'>Submit</button>
      </form>
    </div>


</div>
</div>

</div>

<?php include 'footer.php'; ?>
