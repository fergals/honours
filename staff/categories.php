<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');
if(!$user->is_logged_in()){ header('Location: ../uhoh.php'); }
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminheader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/templates/adminmenu.php');

$pagename = "Categories"
;?>
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


    <div id="result"></div>
    <div class="row" id="content">

						<div class="col-md-6">
							<div class="panel panel-success">
								<div class="panel-heading dark-overlay">Add New Category</div>
								<div class="panel-body">
									<form action="submitcat.php" method="post" />
									  <div class="form-group">
									    <label>Name of Category </label>
									    <input type="text" class="form-control" name="categoryname" id="categoryname">
									  </div>
									  <button type="submit" class="btn btn-primary pull-right" name="newcat" id="newcat"> Add</button>
									</form>
								</div>
							</div>
						</div><!--/.col-->

						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading dark-overlay">Edit Categories</div>
								<div class="panel-body">
									<?php
									$editcat = $db->query("SELECT category FROM categories ORDER BY category");
									echo "<select multiple class='form-control' size='10' id='catoptions' onchange='selectionchange();'>";
									while ($ec = $editcat->fetch(PDO::FETCH_ASSOC)){
									echo "<option>" . $ec['category'] . "</option>";
									}
									echo "</select>";
									?>
									<br />
									<form class="form-inline" action="submitcat.php" method="post" />
									  <div class="form-group">
									    <label>Edit</label>
									    <input type="text" class="form-control" name="editcat" id="editcat">
									    <input type="hidden" name="hiddencatid" id="hiddencatid" />
									  </div>
									  <button type="submit" class="btn btn-default">Edit Category Name</button>
									  <button type="submit" class="btn btn-danger">Delete Category</button>
									</form>
								</div>
							</div>
						</div><!--/.col-->
				</div><!--/.row-->


			</div><!--/.main-->
    </div>

<? require_once ($_SERVER['DOCUMENT_ROOT'].'/config/scripts.php'); ?>
</body>

</html>
