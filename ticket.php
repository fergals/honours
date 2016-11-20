<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/header.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/menu.php');

$ticketid = $_GET['id'];
$pagename = $ticketid;
$userid = $_SESSION['id'];

if(isset($_POST['submitcomment'])){
  $comment = $_POST['comment'];
  $date = date("Y-m-d H:i:s");
  $hidden = "NO";

  $stmt = $db->prepare("INSERT INTO comments (userid, tID, date, comment, hidden) VALUES (:userid, :tID, :date, :comment, :hidden)");

  $stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 100);
  $stmt->bindParam(':tID', $ticketid, PDO::PARAM_STR, 100);
  $stmt->bindParam(':date', $date, PDO::PARAM_STR, 10000);
  $stmt->bindParam(':comment', $comment, PDO::PARAM_STR, 10000);
  $stmt->bindParam(':hidden', $hidden,PDO::PARAM_STR,10);
  $stmt->execute();
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
          <div class="panel-footer">
            <ul>
              <?php $comments = $db->query("SELECT comments.comment, comments.date, users.firstname, users.surname, users.department FROM comments INNER JOIN users ON comments.userid=users.id WHERE comments.tID = '$ticketid'");
              while($c = $comments->fetch(PDO::FETCH_ASSOC)) {
                $dateformat = date('d/m/Y - h:m a', strtotime($c['date']));
                echo "
                <div class='chat-body clearfix'>
                <div class='header'><strong class='primary-font'>". $c['firstname'] . ' ' . $c['surname'] ."</strong>
                <small class='text-muted'>" . $dateformat . "</small>
                </div>
                <p>" . $c['comment'] ."</p><br>
                </div>";
                 }
                 ?>
            </ul>
          </div>
				</div>
        <form action="" method="post">
        <div class="panel panel-default">
          <div class="panel-heading"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg>Reply</div>
          <div class="panel-body">
            <div class="input-group">
              <input id="btn-input" type="text" class="form-control input-md" name="comment" placeholder="Type your message here..." autocomplete="off"/>
              <span class="input-group-btn">
                <button class="btn btn-success btn-md" id="btn-chat" name="submitcomment">Reply</button>
              </span>
          </div>
        </div>
        </div>
      </form>

			</div><!--/.col-->

			<div class="col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading"><svg class="glyph stroked calendar"></svg>Ticket Information</div>
					<div class="panel-body">
            <?php
            //Get Ticket information and display on left
            $ticketinfo = $db->query("SELECT userid, tID, date, queue, category, department, urgency, status FROM ticket where tID = '$ticketid'");
            while($r = $ticketinfo->fetch(PDO::FETCH_OBJ)) {
            $fullticket = $r->tID;
            $dateformat = date('d/m/Y h:i a', strtotime($r->date));
            echo '<strong>Status:</strong> ' . $r->status . '<br />';
            echo '<strong>Category:</strong> ' . $r->category . '<br />';
            echo '<strong>Department:</strong> ' . $r->department . '<br /><br />';
            echo '<strong>Submitted:</strong> ' . $dateformat;
            }
               ?>
					</div>
				</div>


			</div><!--/.col-->
		</div><!--/.row-->
	</div>	<!--/.main-->

</div>

</body>

</html>
