<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminheader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminmenu.php');

$ticketid = $_GET['id'];
$pagename = $ticketid;
$userid = $_SESSION['id'];

// Close ticket
if(isset($_POST['closeticket'])){
  $ticketstatus = "Closed";
  $time = date("Y-m-d H:i:s");
  $stmt = $db->prepare("UPDATE ticket SET status=:status, closed=:timeclosed WHERE tID = '$ticketid'");
  $stmt->bindParam(':status', $ticketstatus, PDO::PARAM_INT, 100);
  $stmt->bindParam(':timeclosed', $time, PDO::PARAM_STR, 100);
  $stmt->execute();

echo "<div class='alert alert-success' role='alert'>This ticket has now been closed</div>";
}

//Send ticket update to DB
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
  $updatesuccessful = true;
}

//Add Comment
if(isset($_POST['submitcomment'])) {
    $comment = $_POST['comment'];
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
    $stmt->bindParam(':tID', $ticketid, PDO::PARAM_STR, 100);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':hidden', $hidden, PDO::PARAM_STR, 3);

    if(empty($comment)){
      $commente = true;
      $commenterror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please enter a comment<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
    else {
      $stmt->execute();
    }
  }
    // E-mail users
    if(isset($_POST['sendemail'])){
    $to = $_POST['emailto'];
    $subject = "[$ticketid] Help! Online Support";
    $body = $_POST['emailmsg'];
    $body .= "<br><br>Do no reply to this e-mail! <br>To add a response please navigate to <a href='http://www.help.fergalsexton.com/'>Help! Online Support System</a> and login to add a comment";

    $mail = new Mail();
    $mail->setFrom(SITEMAIL);
    $mail->addAddress($to);
    $mail->subject($subject);
    $mail->body($body);
    $mail->send();

    $emailsuccess = true;
    }

;?>

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

      if(isset($updatesuccessful)) {
        echo "<div class='alert bg-success' role='alert'>
        <svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully updated ticket<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a>
				</div>";
      }

      if(isset($emailsuccess)) {
        echo "<div class='alert bg-success' role='alert'>
        <svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully sent email<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a>
        </div>";
      }
        if(isset($commente)) {echo $commenterror;}

      ?>
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-notepad"></use></svg>Ticket Information</div>
					<div class="panel-body">
          <?php
          // Get Ticket Body
              $result = $db->query("SELECT userid, tID, date, query FROM ticket where tID = '$ticketid'");
              while($r = $result->fetch(PDO::FETCH_ASSOC)) {
                echo $r['query'];
              } ?>
					</div>
				</div>

				<div class="panel panel-default chat">
					<div class="panel-heading" id="accordion"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-two-messages"></use></svg> Ticket Comments</div>

					<div class="panel-body">
						<ul>
              <?php $comments = $db->query("SELECT comments.comment, comments.date, users.firstname, users.surname, users.department FROM comments INNER JOIN users ON comments.userid=users.id WHERE comments.tID = '$ticketid'");
                 while($c = $comments->fetch(PDO::FETCH_ASSOC)) {
                   $dateformat = date('d/m/Y - h:i a', strtotime($c['date']));
                   echo "
                   			 <div class='chat-body clearfix'>
                   			 <div class='header'>
                   				<strong class='primary-font'>". $c['firstname'] . ' ' . $c['surname'] ."</strong>
                          <small class='text-muted'>" . $dateformat . "</small>
                   				</div>
                   				<p>" . $c['comment'] ."</p><br>
                   				</div>";
                 }
                 ?>
						</ul>
					</div>

          <form method="post" action="" autocomplete="off">

				</div>

        <div class="panel panel-default">
          <div class="panel-heading"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg>Reply</div>
          <div class="panel-body">
            <div class="input-group">
              <input id="btn-input" type="text" class="form-control input-md" name="comment" placeholder="Type your message here..." />
              <span class="input-group-btn">
                <button class="btn btn-success btn-md" id="btn-chat" name="submitcomment">Reply</button>
              </span>
          </div>
        </div>

        <div class="panel-footer">
            <input type="checkbox" value="true" name="hidden"> Hide comment from user
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Send e-mail to user</button>
        </div>

        </div>


      </form>

			</div><!--/.col-->

			<div class="col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading"><svg class="glyph stroked calendar"></svg>User Information</div>
					<div class="panel-body">
            <?php
            //Get User information and display on left
             $userinfo = $db->query("SELECT users.id, users.username, users.firstname, users.surname, users.email, users.phonenumber, users.department, users.usertype, ticket.userid, ticket.id, ticket.tID FROM users INNER JOIN ticket ON users.id=ticket.userid  where ticket.tID = '$ticketid' ");
             while($u = $userinfo->fetch(PDO::FETCH_OBJ)) {
               echo "<form action='' method='post'>";
               echo "<strong>Name: </strong>". $u->firstname . ' ' . $u->surname ."<br />";
               echo "<strong>E-mail: </strong>". $u->email . "<br />";
               echo "<strong>Phone: </strong>".$u->phonenumber . "<br />";

               $displaydep = $db->query("SELECT depname, depid FROM departments WHERE depid = '$u->department'");
               while($d = $displaydep->fetch(PDO::FETCH_ASSOC)){
                 echo "<strong>Department: </strong>". $d['depname'] . "<br>";
               }
               echo "<strong>Usertype: </strong>".$u->usertype . "<br />";
             }
               ?>
					</div>
				</div>

        <div class="panel panel-info">
          <div class="panel-heading"><svg class="glyph stroked calendar"></svg>Ticket Information</div>
          <div class="panel-body">
            <?php
            //Get Ticket information and display on right
            $ticketinfo = $db->query("SELECT userid, tID, date, queue, category, department, urgency, status FROM ticket where tID = '$ticketid'");
            while($r = $ticketinfo->fetch(PDO::FETCH_OBJ)) {
              // Get QUEUES from DB
              $stmt = $db->prepare("SELECT qname FROM queues");
              $stmt->execute();
              echo "<label>Queue</label>";
              echo "<select class='form-control' name='queue'>";
              echo "<option value='" . $r->queue . "' selected>" . $r->queue . "</option>";
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='" . $row['qname'] . "'>" . $row['qname'] ."</option>";
              }
              echo "</select><br />";

              //Get STATUS from DB
              $stmt = $db->prepare("SELECT status FROM status");
              $stmt->execute();
              echo "<label>Status</label>";
              echo "<select class='form-control' name='status'>";
              echo "<option value='" . $r->status . "' selected>" . $r->status . "</option>";
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='" . $row['status'] . "'>" . $row['status'] ."</option>";
              }
              echo "</select><br />";

              //Get URGENCY from DB
              $stmt = $db->prepare("SELECT urgency FROM urgency");
              $stmt->execute();
              echo "<label>Urgency</label>";
              echo "<select class='form-control' name='urgency'>";
              echo "<option value='" . $r->urgency . "' selected>" . $r->urgency . "</option>";
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='" . $row['urgency'] . "'>" . $row['urgency'] ."</option>";
              }
              echo "</select><br />";

              //Populate categories dropdown from DB
              $getcategories = $db->query("SELECT category FROM categories");
              echo "<label>Category</label>";
              echo "<select class='form-control' name='category'>";
              echo "<option value='" . $r->category . "' selected>" . $r->category . "</option>";
              while($gc = $getcategories->fetch(PDO::FETCH_ASSOC)){
                echo "<option value=" . $gc['category'] . ">" . $gc['category'] . "</option>";
              }
              echo "</select><br />";


             //Populate DEPARTMENT dropdown from DB
             echo "<label>Department</label>";
             echo "<select class='form-control' name='department'>";
             $stmt= $db->query("SELECT departments.depid, departments.depname, ticket.department, ticket.tID FROM departments INNER JOIN ticket ON departments.depid=ticket.department WHERE ticket.tID = '$ticketid'");
             while($dp = $stmt->fetch(PDO::FETCH_ASSOC)){
               echo "<option value='" . $dp['depid'] . "'>" . $dp['depname'] . "</option>";
             }
            $getfulldep = $db->query("SELECT depid, depname FROM departments");
             while($dp = $getfulldep->fetch(PDO::FETCH_ASSOC)){
               echo "<option value='" . $dp['depid'] . "'>" . $dp['depname'] . "</option>";
             }
             echo "</select><br />";
           }



             //Populate users dropdown from DB
             $getusers = $db->query("SELECT id, firstname, surname FROM users");
             $getassigned = $db->query("SELECT users.firstname, users.surname, users.id, ticket.tID, ticket.assigned FROM users INNER JOIN ticket ON users.id=ticket.assigned WHERE ticket.tID = '$ticketid'");
             while($ga = $getassigned->fetch(PDO::FETCH_ASSOC)){
               echo "<label>Assigned to</label>";
               echo "<select class='form-control' name='assign'>";
               echo "<option value='" . $ga['id'] . "' selected>" . $ga['firstname'] . " " . $ga['surname'] . "</option>";
               while($gu = $getusers->fetch(PDO::FETCH_ASSOC)){
               echo "<option value=" . $gu['id'] . ">" . $gu['firstname'] . " " . $gu['surname'] . "</option>";
             }
             echo "</select><br /><br />";
             echo "<div class='text-center'><button class='btn btn-primary' name='updateticket' id='updateticket'>Update Ticket Details</button></div></form>";
            }
          ?>
          </div>
        </div>


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
