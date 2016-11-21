<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminheader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminmenu.php');

$pagename = "Manage Departments";
$userid = $_SESSION['id'];

if(isset($_POST['newdepartment'])) {
  $department = $_POST['departmentname'];
  $stmt = $db->prepare("INSERT INTO departments (depname, userid, datecreated) VALUES (:category, :userid, :datecreated)");
  $stmt->bindParam(':userid', $userid, PDO::PARAM_STR, 4);
  $stmt->bindParam(':category', $department, PDO::PARAM_STR, 1000);
	$stmt->bindParam(':datecreated', $current_date, PDO::PARAM_STR, 60);

  if(empty($department)){
      $departmente = true;
      $departmenterror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please enter a department name<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
    else {
      $departmentsuc= true;
			$stmt->execute();
      $departmentsuccess = "<div class='alert bg-success' role='alert'><svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully added new Department<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
}

if(isset($_POST['editdepartment'])) {
  $catname = $_POST['hiddencatid'];
  $editcat = $_POST['editcat'];
  $stmt = $db->prepare("UPDATE departments SET depname=:department WHERE depname=:depname");
  $stmt->bindParam(':department', $editcat, PDO::PARAM_STR, 100);
  $stmt->bindParam(':depname', $catname, PDO::PARAM_INT,3);

  if(empty($editcat)){
      $editdepe = true;
      $editdeperror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please select a department before editing<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
    else {
			$stmt->execute();
      $editdeps = true;
      $editdepsuccess = "<div class='alert bg-success' role='alert'><svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully edited department<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
}
?>
  <script>
  function selectionchange()
  {
      var e = document.getElementById("catoptions");
      var str = e.options[e.selectedIndex].value;

      document.getElementById('editcat','hiddencatid').value = str;
      document.getElementById('hiddencatid').value = str;
  }</script>
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
      if(isset($departmente)) {echo $departmenterror;}
      if(isset($departmentsuc)) {echo $departmentsuccess;}
			if(isset($editdepe)) {echo $editdeperror;}
			if(isset($editdeps)) {echo $editdepsuccess;}
      ?>
      <form method="post" action="">
			<div class="col-md-8">

				<div class="panel panel-default">
					<div class="panel-heading" id="accordion"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-open-folder"></use></svg> Amend Current Departments</div>
					<div class="panel-body">

            <div class="form-group">
              <label>Select Department</label>
              <?php
              $editcat = $db->query("SELECT depname FROM departments ORDER BY depname");
              echo "<select multiple class='form-control' size='10' id='catoptions' onchange='selectionchange();'>";
              while ($ec = $editcat->fetch(PDO::FETCH_ASSOC)){
              echo "<option>" . $ec['depname'] . "</option>";
              }
              echo "</select>";
              ?>
            </div>

            <div class="form-group">
              <label>Edit Department </label>
              <input type="text" class="form-control" name="editcat" id="editcat" autocomplete="off">
              <input type="hidden" name="hiddencatid" id="hiddencatid" />
            </div>
            <button type="submit" class="btn btn-primary pull-right" name="editdepartment">Edit Department</button>
					</div>

				</div>

			</div><!--/.col-->

      <div class="col-md-8">

				<div class="panel panel-default">
					<div class="panel-heading" id="accordion"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-folder"></use></svg> Add Department</div>
					<div class="panel-body">

            <div class="form-group">
              <label>Name of Department</label>
              <input class="form-control" name="departmentname" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-primary pull-right" name="newdepartment">New Department</button>
					</div>

				</div>

			</div><!--/.col-->

		</div><!--/.row-->
	</div>	<!--/.main-->
</form>

</div>
</body>

</html>
