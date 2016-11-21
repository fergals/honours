<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/templates/header.php');
if(!$user->is_logged_in()){ header('Location: uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/header.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/menu.php');

$pagename = "Submit new ticket";

if(isset($_POST['submit'])) {
    //Gets id from ticket.table and increments from last row
    $dateinc = $db->query("SELECT id FROM ticket ORDER BY ID DESC LIMIT 1");
    while ($d = $dateinc->fetch(PDO::FETCH_OBJ)){
      $up = $d->id;
      $idincrement = $up + 1;
    }
    //Pulls POST data from ticket.php to insert into ticket.table
    $tID = "T" . date("dmy") . $idincrement;
    $userid = $_SESSION['id'];
    $query = $_POST['query'];
    $date = date("Y-m-d H:i:s");
    $queue = "1st Line";
    $status = "Open";
    $urgency = "Low";
    $department = "14";
    $category = "None";
    $assigned = "1";

    $stmt = $db->prepare("INSERT INTO ticket (tID, userid, query, date, queue, status, urgency, department, category, assigned) VALUES (:tID, :userid, :query, :date, :queue, :status, :urgency, :department, :category, :assigned)");
    $dateinc = $db->query("SELECT id FROM ticket ORDER BY ID DESC LIMIT 1");

    $stmt->bindParam(':tID', $tID, PDO::PARAM_INT, 100);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT, 100);
    $stmt->bindParam(':query', $query, PDO::PARAM_STR, 100);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':queue', $queue, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':urgency', $urgency, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':department', $department, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR, 10000);
    $stmt->bindParam(':assigned', $assigned, PDO::PARAM_STR, 10000);
    $stmt->execute();
    $ticketsubmit = true;
    $ticketsuccess = "<div class='alert bg-success' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-checkmark'></use></svg> Successfully submitted ticket<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove' data-dismiss='modal'></span></a></div>";
    $stmt = null;
}

?>


<!-- Breadcrumbs -->
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <?php if(isset($ticketsubmit)) {echo $ticketsuccess;} ?>
		<div id="result"></div>
		<div id="content">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active"><?php echo $pagename; ?></li>
			</ol>
		</div><!--/.row-->


			<div class="col-lg-8">
				<div class="panel panel-default">
          <div class="panel-heading" id="accordion"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-two-messages"></use></svg> Submit A Ticket</div>
							<div class="panel-body">
                <p> Explain your issue succinctly but include information we need such as what you were doing when the problem occurred or where it has happened.</p>
                <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
                //<![CDATA[
                bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
                //]]>
                </script>
                <form class="form-horizontal" action="" method="post" />
                  <div class="form-group">
                      <textarea class='form-control' rows='10' name='query' placeholder="Please enter your query here"></textarea>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" name="submit" class="btn btn-primary pull-right">Submit</button>
                    </form>
</div>
</div>
</div>
</div>

			</div><!--/.col-->
      <div class="col-md-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Knowledge Base?
					</div>
					<div class="panel-body">
						<p>Our most common questions have already been answered in our <a href="/kb">Knowledge Base</a></p>
            <p>This is the quickest method for resolutions for our most common asked questions</p>
					</div>
				</div>
			</div>

		</div><!--/.row-->
	</div>	<!--/.main-->
