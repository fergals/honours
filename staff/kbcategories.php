<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminheader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminmenu.php');

$pagename = "Knowledge Base Categories";
$userid = $_SESSION['id'];

if(isset($_POST['newcategory'])) {
  $category = $_POST['categoryname'];
  $stmt = $db->prepare("INSERT INTO kbcategory (category, userid) VALUES (:category, :userid)");
  $stmt->bindParam(':userid', $userid, PDO::PARAM_STR, 4);
  $stmt->bindParam(':category', $category, PDO::PARAM_STR, 1000);
  $stmt->execute();

  if(empty($category)){
      $categorye = true;
      $categoryerror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please enter a category name<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
    else {
      $categorysuc= true;
      $categorysuccess = "<div class='alert bg-success' role='alert'><svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully added new Category<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
}

if(isset($_POST['editcategory'])) {
  $catname = $_POST['hiddencatid'];
  $editcat = $_POST['editcat'];
  $stmt = $db->prepare("UPDATE kbcategory SET category=:category WHERE category=:catname");
  $stmt->bindParam(':category', $editcat, PDO::PARAM_STR, 100);
  $stmt->bindParam(':catname', $catname, PDO::PARAM_INT,3);

  if(empty($editcat)){
      $categorye = true;
      $categoryerror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please select a category before editing<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
    else {
      $stmt->execute();
      $categorysuc= true;
      $categorysuccess = "<div class='alert bg-success' role='alert'><svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully edited category<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
}
if(isset($_POST['deletecategory'])) {
  $catname = $_POST['hiddencatid'];
  $editcat = $_POST['editcat'];
  $stmt = $db->prepare("DELETE FROM kbcategory WHERE category=:catname");
  $stmt->bindParam(':catname', $catname, PDO::PARAM_INT,3);
  $stmt->execute();

  if(empty($editcat)){
      $categorye = true;
      $categoryerror = "<div class='alert bg-danger' role='alert'><svg class='glyph stroked cancel'><use xlink:href=''#stroked-cancel'></use></svg> Please select a category before deleting<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
    }
    else {
      $categorysuc= true;
      $categorysuccess = "<div class='alert bg-success' role='alert'><svg class='glyph stroked checkmark'><use xlink:href='#stroked-empty-message'></use></svg> Successfully deleted category<a href='#' class='pull-right'><span class='glyphicon glyphicon-remove'></span></a></div>";
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
      if(isset($categorye)) {echo $categoryerror;}
      if(isset($categorysuc)) {echo $categorysuccess;}
      ?>
      <form method="post" action="">
			<div class="col-md-8">

				<div class="panel panel-default">
					<div class="panel-heading" id="accordion"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-open-folder"></use></svg> Amend Current Categories</div>
					<div class="panel-body">

            <div class="form-group">
              <label>Select Category</label>
              <?php
              $editcat = $db->query("SELECT category FROM kbcategory ORDER BY category");
              echo "<select multiple class='form-control' size='10' id='catoptions' onchange='selectionchange();'>";
              while ($ec = $editcat->fetch(PDO::FETCH_ASSOC)){
              echo "<option>" . $ec['category'] . "</option>";
              }
              echo "</select>";
              ?>
            </div>

            <div class="form-group">
              <label>Edit Category </label>
              <input type="text" class="form-control" name="editcat" id="editcat" autocomplete="off">
              <input type="hidden" name="hiddencatid" id="hiddencatid" />
            </div>
            <button type="submit" class="btn btn-default pull-right" name="deletecategory">Delete Category</button><button type="submit" class="btn btn-primary pull-right" name="editcategory">Edit Category</button>
					</div>

				</div>

			</div><!--/.col-->

      <div class="col-md-8">

				<div class="panel panel-default">
					<div class="panel-heading" id="accordion"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-folder"></use></svg> Add New Category</div>
					<div class="panel-body">

            <div class="form-group">
              <label>Name of Category </label>
              <input class="form-control" name="categoryname" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-primary pull-right" name="newcategory">Add Category</button>
					</div>

				</div>

			</div><!--/.col-->

		</div><!--/.row-->
	</div>	<!--/.main-->
</form>

</div>
</body>

</html>
