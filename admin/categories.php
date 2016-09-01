<script>
function selectionchange()
{
    var e = document.getElementById("catoptions");
    var str = e.options[e.selectedIndex].value;

    document.getElementById('editcat','hiddencatid').value = str;
    document.getElementById('hiddencatid').value = str;
}</script>

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
  <button type="submit" class="btn btn-default" name="newcat" id="newcat">Add Category</button>
</form>

<h2>Edit Categories</h2>
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
<?php include '../template/adminfooter.php'; ?>
