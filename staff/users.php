<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminheader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminmenu.php');

$pagename = "User Management"
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
								<strong>User Management</strong>
							</div>
							<div class="panel-body">
								<?php

								if(isset($_POST['deleteselected'])){
								  $idArr = $_POST['checkedid'];
								  foreach($idArr as $id){
								    $stmt = $db->query("UPDATE users Set usertype='Archived' WHERE id= $id");
								  }
									$archivesuccess = true;
								}

								if(isset($archivesuccess)) {
									echo "<div class='alert bg-success' role='alert'>
												<svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully archived user<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a>
												</div>";
											}

								$users = $db->query("SELECT id, username, firstname, surname, department, usertype FROM users");
								echo "<form action='' method='post'>";
								echo "<table class='table table-striped'><tr><th>Name</th><th>Username</th><th>Department</th><th>User Type</th><th>Edit User</th><th>Archive User</th>
								</td>";
								while($u = $users->fetch(PDO::FETCH_ASSOC)){
								  echo "<tr><td>" . $u['firstname'] . " " . $u['surname'] . "</td>";
								  echo "<td>" . $u['username'] . "</td>";
								  echo "<td>" . $u['department'] . "</td>";
								  echo "<td>" . $u['usertype'] . "</td>";
								  echo "<td><a href='useredit.php?id=" . $u['id'] ."'>Edit</a></td>";
								  echo "<td><input type='checkbox' name='checkedid[]' value='" . $u['id'] . "'></td></tr>";

								}
								echo "</table>";
								echo "<button type='submit' class='btn btn-primary pull-right' name='deleteselected'>Archive Selected</button></form>";

								?>
								</div>
							</div>
						</div>
				</div><!--/.row-->


			</div><!--/.main-->
		</div>
</body>

</html>
