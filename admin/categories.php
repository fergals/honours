<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/template/adminheader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/dbconnect.php');

$date = date("Y-m-d H:i:s");
$userid = $_SESSION['id'];
?>


<div id="content">
<h2>Add Categories</h2>

<form class="form-inline" action="submitcat.php" method="post" />
  <div class="form-group">
    <label for="exampleInputName2">Name of Category </label>
    <input type="text" class="form-control" name="categoryname" id="categoryname">
  </div>
  <button type="submit" class="btn btn-default" name="submitcat">Add Category</button>
</form>

<h2>Edit Categories</h2>
<?php
$editcat = $db->query("SELECT ALL category FROM categories");
while ($e = $editcat->fetch(PDO::FETCH_ASSOC)){
echo "<select multiple class='form-control'><option>" . $ec['category'] . "</option></select>";
}

?>
</div>
<?php include '../template/adminfooter.php'; ?>
