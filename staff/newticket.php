<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminheader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminmenu.php');

$pagename = "New Ticket";
$userid = $_SESSION['id'];

$dateinc = $db->query("SELECT id FROM ticket ORDER BY ID DESC LIMIT 1");
while ($d = $dateinc->fetch(PDO::FETCH_OBJ)){
  $up = $d->id;
  $idincrement = $up + 1;
}

if(isset($_POST['submitticket'])) {
  $tID = "T" . date("dmy") . $idincrement;
  $userid = $_POST['userid'];
  $query = $_POST['query'];
  $date = $current_date;
  $queue = $_POST['queue'];
  $status = $_POST['status'];
  $urgency = $_POST['urgency'];
  $department = $_POST['department'];
  $category = $_POST['category'];
  $assigned = "1";
  $stmt = $db->prepare("INSERT INTO ticket (tID, userid, query, date, queue, status, urgency, department, category, assigned) VALUES (:tID, :userid, :query, :date, :queue, :status, :urgency, :department, :category, :assigned)");
  $stmt->bindParam(':tID', $tID, PDO::PARAM_INT, 20);
  $stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 100);
  $stmt->bindParam(':query', $query, PDO::PARAM_STR, 10000);
  $stmt->bindParam(':date', $date, PDO::PARAM_STR, 20);
  $stmt->bindParam(':queue', $queue, PDO::PARAM_STR, 20);
  $stmt->bindParam(':status', $status, PDO::PARAM_STR, 20);
  $stmt->bindParam(':urgency', $urgency, PDO::PARAM_STR, 20);
  $stmt->bindParam(':department', $department, PDO::PARAM_STR, 30);
  $stmt->bindParam(':category', $category, PDO::PARAM_STR, 20);
  $stmt->bindParam(':assigned', $assigned, PDO::PARAM_STR, 10);
  $stmt->execute();

  if(empty($userid)){
      $useriderror = true;
      $userinfoerror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please search for a user or enter user information manually<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
    else {
      $ticketsuccess = true;
    }
}
  ?>
<!-- Breadcrumbs -->
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div id="result"></div>
    <div id="content">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active"><?php echo $pagename; ?></li>
			</ol>
		</div><!--/.row-->
    <div class="row">
      <?php
      if(isset($ticketsuccess)) {
        echo "<div class='alert bg-success' role='alert'>
        <svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully add new ticket<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a>
				</div>";
      }

      if(isset($useriderror)) {echo $userinfoerror;}
      if(isset($queryerror)) {echo $querydisplay;}
      ?>

      <form method="post" action="">
			<div class="col-md-8">

				<div class="panel panel-default">
					<div class="panel-heading" id="accordion"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-two-messages"></use></svg> Add New Ticket</div>
					<div class="panel-body">
              <textarea class="form-control" rows="15" name='query'></textarea>
					</div>
          <div class="panel-footer">
              <button class="btn btn-default" name="submitticket" type="submit">Submit New Ticket</button>
          </div>
				</div>

			</div><!--/.col-->
      <script type="text/javascript">
      $(function() {

          //autocomplete
          $(".auto").autocomplete({
              source: 'search.php',
              minLength: 1,
              select: function (e, ui) {
                $('[name="firstname"]').val(ui.item.firstname);
                $('[name="surname"]').val(ui.item.surname);
                $('[name="department"]').val(ui.item.department);
                $('[name="email"]').val(ui.item.email);
                $('[name="userid"]').val(ui.item.id);
              }
          }).autocomplete( "instance" )._renderItem = function (ul, item) {
            console.log(item);
            return $( "<li>" )
              .append( "<div>" + item.firstname + " " + item.surname + "</div>" )
              .appendTo( ul );
          };

      });
      </script>
      <form action='' method='post'>
			<div class="col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading"><svg class="glyph stroked calendar"></svg>Enter User Information</div>
					<div class="panel-body">

                <label>Search for user:</label><input type='text' name='name' value='' id="auto" class='form-control auto'>
              <label>Name:</label><input type='text' name='firstname' value='' id="firstname" class='form-control' readonly>
              <label>Surname:</label><input type='text' name='surname' id="surname" value='' class='form-control'readonly>
              <label>Department:</label><input type='text' name='department' id="department" value='' class='form-control' readonly>
              <label>Email Address:</label><input type='text' name='email' id="email" value='' class='form-control' readonly>
              <label>Userid:</label><input type='text' name='userid' value='' class='form-control' readonly>
					</div>
				</div>
      </form>

        <div class="panel panel-info">
          <div class="panel-heading"><svg class="glyph stroked calendar"></svg>Ticket Information</div>
          <div class="panel-body">
            <?php
            $stmt = $db->prepare("SELECT status FROM status");
            $stmt->execute();
            echo "<label>Status</label>";
            echo "<select name='status' class='form-control'>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['status'] . "'>" . $row['status'] ."</option>";
            }

            echo "</select>";
            $stmt = $db->prepare("SELECT qname FROM queues");
            $stmt->execute();
            echo "<label>Queue</label>";
            echo "<select name='queue' class='form-control'>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['qname'] . "'>" . $row['qname'] ."</option>";
            }
            echo "</select>";

            $stmt = $db->prepare("SELECT urgency FROM urgency");
            $stmt->execute();
            echo "<label>Urgency</label>";
            echo "<select name='urgency' class='form-control'>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['urgency'] . "'>" . $row['urgency'] ."</option>";
            }
            echo "</select>";

            $stmt = $db->prepare("SELECT department FROM departments");
            $stmt->execute();
            echo "<label>Department</label>";
            echo "<select name='department' class='form-control'>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['department'] . "'>" . $row['department'] ."</option>";
            }
            echo "</select>";

            $stmt = $db->prepare("SELECT category FROM categories");
            $stmt->execute();
            echo "<label>Category</label>";
            echo "<select name='category' class='form-control'>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['category'] . "'>" . $row['category'] ."</option>";
            }
            echo "</select>";
          ?>
          <button type="submit" class="btn btn-primary">Update Ticket Information</button>

          </div>
        </div>
              </form>

			</div><!--/.col-->
		</div><!--/.row-->
	</div>	<!--/.main-->


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

              //Get users email address
              $sendemail = $db->query("SELECT users.email FROM users INNER JOIN ticket ON ticket.userid=users.id WHERE ticket.tID = '$ticketid'");
               while($s = $sendemail->fetch(PDO::FETCH_OBJ)) {
                 echo "<input type='text' class='form-control' id='recipient-name' name='emailto' value='" . $s->email . "'>";
              } ?>
            </div>
            <div class="form-group">
              <label for="message-text" class="control-label">Email Message:</label>
              <textarea class="form-control" id="message-text" name="emailmsg" ></textarea>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name='sendemail'>Send e-mail</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>

</html>
