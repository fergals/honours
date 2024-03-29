<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/header.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/menu.php');

$pagename = "Resolved Tickets" ?>


<!-- Breadcrumbs -->
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div id="result"></div>
		<div id="content">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active"><?php echo $_SESSION['name'] . "'s " . $pagename; ?></li>
			</ol>
		</div><!--/.row-->


			<div class="col-lg-12">
				<div class="panel panel-default">
							<div class="panel-heading">
								<strong><?php echo $_SESSION['name'] . "'s " . $pagename; ?></strong>
							</div>
							<div class="panel-body">
<?php
$resolvedtickets = $db->query("SELECT tID, id, date, query, status, userid, category, department FROM ticket WHERE userid = '$_SESSION[id]' AND status='Resolved'");

if(count($resolvedtickets) > 1) {
  echo "<table class='table table-striped'>
        <tr>
        <th>Ticket</th>
        <th>Date Submitted</th>
				<th>Query</th></tr>";
        while($o = $resolvedtickets->fetch(PDO::FETCH_ASSOC)){
				$dateformat = date('d/m/Y', strtotime($o['date']));
        echo "<tr><td><a href='ticket.php?id=" . $o['tID'] . "'>" . $o['tID'] ."</td>";
        echo "<td>" . $dateformat . "</td>";
        echo "<td>" . substr($o['query'],0,110) . "</td></tr>";
      }
      echo "</table>";
      echo "<br />";
}
else {
	echo "You have no resolved tickets";
}

?>
</div>
</div>
</div>
</div>

			</div><!--/.col-->
		</div><!--/.row-->
	</div>	<!--/.main-->
