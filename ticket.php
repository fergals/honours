<?php
include_once 'dbconnect.php';
include 'header.php'; ?>

<div class="container">
<div class="row">
  <div class="col-xs-4">
    <strong>I2016-07-07003</strong><br />
    Queue: 1st Line<br />
    Status: Open<br />
    Urgency: Normal<br /></br>

    Submitted: <br /><br />

    <strong>User Information</strong><br />
    Name:<br />
    E-mail:<br />
    Telephone:<br />
    Department:<br />
    User Type:<br />

  </div>


  <div class="col-xs-6">
<?php
    $ticketid = $_GET['id'];
    $db = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT userid, tID, date, query FROM ticket where id = '$ticketid'");

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    print_r($result);
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
