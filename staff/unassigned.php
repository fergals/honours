<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminheader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminmenu.php');

$pagename = "Unassigned Queue";
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
			<div class="col-lg-12">
				<div class="panel panel-default">
							<div class="panel-heading">
								<strong>Unassigned Queue</strong>
							</div>
							<div class="panel-body">

		<?php

	  $allopen = $db->query("SELECT tID, id, date, userid, category, department, urgency, assigned, id, status FROM ticket WHERE department = '14' ORDER BY date ASC");
	  if(count($allopen) > 0 ) {
	  echo "<div class='row'>
					<div class='col-lg-12'>
					<table class='table table-striped'>
	        <tr>
	        <th>Ticket</th>
	        <th>Date Submitted</th>
					<th>User</th>
	        <th>Category</th>
	        <th>Urgency</th>
					<th>Assigned</th></tr>";

	  while ($o = $allopen->fetch(PDO::FETCH_ASSOC)){
					$dateformat = date('d/m/Y - h:i a', strtotime($o['date']));
					$fullid = $o['tID'];
	    echo "<tr><td><a href='viewticket.php?id=" . $o['tID'] . "'>" . $o['tID'] ."</td>";
	    echo "<td>" . $dateformat . "</td>";
			$getusername = $db->query("SELECT users.id, users.firstname, users.surname, ticket.userid, ticket.tID FROM users INNER JOIN ticket ON users.id=ticket.userid WHERE ticket.tID = '$fullid'");
			while($u = $getusername->fetch(PDO::FETCH_ASSOC)){
				echo "<td>" . $u['firstname'] . " " . $u['surname'] . "</td>";
			}
	    echo "<td>" . $o['category'] . "</td>";
	    echo "<td>" . $o['urgency'] . "</td>";
			echo "<td>Unassigned</tr>";
	  }
	  echo "</table>";
	}
	?>
			</div><!--/.col-->
		</div><!--/.row-->
	</div>	<!--/.main-->
</div>
</body>

</html>
