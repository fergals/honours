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
  $sql = "SELECT * FROM ticket WHERE id = '$ticketid'";
  $result = $con->query($sql);

  if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
          echo $row["query"] . "<br /><br />";
          echo "<hr />";

          echo "<div class='form-group'>
                <form action='none.php' method='post'>
                <textarea class='form-control' rows='5' id='comment'></textarea>
                <button type='submit' class='btn btn-default'>Submit</button>
                </form>
                </div>";
      }

  } else {
      echo "No Tickets Found";
  }

  ?>
  </div>
</div>

</div>

<?php include 'footer.php'; ?>
